CREATE DATABASE isports_club;

\c isports_club;

-- Strive Sports Club database schema
-- PostgreSQL schema only.
-- Create the database first, then run:
-- psql -U postgres -d isports_club -f database/schema.sql

-- 1. Roles(admin, coach, customer)
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE NOT NULL
);

-- 2. Users
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    contact VARCHAR(15),
    role_id INT REFERENCES roles(id),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Sports
CREATE TABLE sports (
    id SERIAL PRIMARY KEY,
    sport_name VARCHAR(50) NOT NULL,
    description TEXT,
    image_path TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Timetable (weekly sports schedule)
CREATE TABLE timetable (
    id SERIAL PRIMARY KEY,
    sport_id INT REFERENCES sports(id) ON DELETE CASCADE,
    day_of_week VARCHAR(10) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    coach_id INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Booking Status
CREATE TABLE booking_status (
    id SERIAL PRIMARY KEY,
    status_name VARCHAR UNIQUE NOT NULL
);

-- 6. Sessions
-- Created before bookings because bookings.session_id references sessions(id).
CREATE TABLE sessions (
    id SERIAL PRIMARY KEY,
    sport_id INT REFERENCES sports(id) ON DELETE CASCADE,
    coach_id INT REFERENCES users(id),
    timetable_id INT REFERENCES timetable(id) ON DELETE SET NULL,
    session_day VARCHAR(10) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_at TIMESTAMP WITHOUT TIME ZONE
        GENERATED ALWAYS AS (created_at + INTERVAL '30 days') STORED
);

-- 7. Bookings
CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    timetable_id INT REFERENCES timetable(id) ON DELETE CASCADE,
    status_id INT REFERENCES booking_status(id),
    session_id INT REFERENCES sessions(id) ON DELETE SET NULL,
    booking_date DATE DEFAULT CURRENT_DATE,
    CONSTRAINT uk_user_single_session UNIQUE (user_id, timetable_id, booking_date)
);

-- 8. Payments
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    booking_id INT REFERENCES bookings(id) ON DELETE CASCADE,
    amount NUMERIC(10,2),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    method VARCHAR(20),
    status VARCHAR(20) DEFAULT 'Success'
);

-- 9. Audit Logs
CREATE TABLE audit_logs (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    action TEXT,
    action_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. Feedback
CREATE TABLE feedback (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    message TEXT,
    target_type VARCHAR(20),
    target_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 11. Announcements
CREATE TABLE announcements (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_by INT REFERENCES users(id),
    visible_to_role INT REFERENCES roles(id),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 12. Privilege Logs
CREATE TABLE privilege_logs (
    id SERIAL PRIMARY KEY,
    admin_id INT REFERENCES users(id),
    target_user_id INT REFERENCES users(id),
    old_role_id INT REFERENCES roles(id),
    new_role_id INT REFERENCES roles(id),
    change_reason TEXT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 13. Slot Count
CREATE TABLE slot_count (
    timetable_id INT PRIMARY KEY REFERENCES timetable(id) ON DELETE CASCADE,
    "count" INT NOT NULL DEFAULT 0
);

-- 14. Sport Timetable
-- Included because the provided seed data inserts timetable rows into sport_timetable.
CREATE TABLE sport_timetable (
    id SERIAL PRIMARY KEY,
    sport_id INT NOT NULL REFERENCES sports(id) ON DELETE CASCADE,
    day_of_week TEXT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    coach_id INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT ck_time_range CHECK (end_time > start_time),
    CONSTRAINT sport_timetable_day_of_week_check
        CHECK (day_of_week = ANY (ARRAY[
            'Monday'::text,
            'Tuesday'::text,
            'Wednesday'::text,
            'Thursday'::text,
            'Friday'::text,
            'Saturday'::text,
            'Sunday'::text
        ])),
    CONSTRAINT uq_slot UNIQUE (sport_id, day_of_week, start_time, end_time, coach_id)
);

-- Helper function to adjust the count (+1 or -1) for a timetable_id
CREATE OR REPLACE FUNCTION adjust_slot_count(p_tid INT, p_delta INT)
RETURNS VOID
LANGUAGE plpgsql AS $$
BEGIN
    INSERT INTO slot_count (timetable_id, "count")
    VALUES (p_tid, CASE WHEN p_delta > 0 THEN p_delta ELSE 0 END)
    ON CONFLICT (timetable_id)
    DO UPDATE SET "count" = GREATEST(0, slot_count."count" + p_delta);
END;
$$;

-- Trigger function: keep slot_count in sync with bookings.
-- Only count rows where bookings.status_id = 2 (Confirmed)
CREATE OR REPLACE FUNCTION bookings_slot_count_trg()
RETURNS TRIGGER
LANGUAGE plpgsql AS $$
DECLARE
    confirmed_id INT := 2;
BEGIN
    IF TG_OP = 'INSERT' THEN
        IF NEW.status_id = confirmed_id THEN
            PERFORM adjust_slot_count(NEW.timetable_id, +1);
        END IF;

    ELSIF TG_OP = 'UPDATE' THEN
        IF OLD.status_id = confirmed_id THEN
            PERFORM adjust_slot_count(OLD.timetable_id, -1);
        END IF;

        IF NEW.status_id = confirmed_id THEN
            PERFORM adjust_slot_count(NEW.timetable_id, +1);
        END IF;

    ELSIF TG_OP = 'DELETE' THEN
        IF OLD.status_id = confirmed_id THEN
            PERFORM adjust_slot_count(OLD.timetable_id, -1);
        END IF;
    END IF;

    RETURN NULL;
END;
$$;

CREATE TRIGGER trg_bookings_slot_count
AFTER INSERT OR UPDATE OF status_id, timetable_id OR DELETE
ON bookings
FOR EACH ROW
EXECUTE FUNCTION bookings_slot_count_trg();

-- Coach validation function for sport_timetable.
CREATE OR REPLACE FUNCTION ensure_coach_role()
RETURNS TRIGGER
LANGUAGE plpgsql AS $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM users u WHERE u.id = NEW.coach_id AND u.role_id = 2
    ) THEN
        RAISE EXCEPTION 'coach_id % is not a coach (role_id must be 2)', NEW.coach_id
            USING HINT = 'Insert/update with a user who has role_id = 2.';
    END IF;

    RETURN NEW;
END;
$$;

CREATE TRIGGER trg_sport_tt_coach
BEFORE INSERT OR UPDATE OF coach_id
ON sport_timetable
FOR EACH ROW
EXECUTE FUNCTION ensure_coach_role();

CREATE INDEX idx_sport_tt_day_time
ON sport_timetable (day_of_week, start_time, end_time);