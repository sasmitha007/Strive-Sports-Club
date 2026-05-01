-- Strive Sports Club seed data
-- Run this after importing database/schema.sql:
-- psql -U postgres -d isports_club -f database/seed.sql
--
-- Initial demo login credentials are listed as comments above each seeded user.
-- Do not use these credentials in production.

BEGIN;

INSERT INTO roles (id, role_name) VALUES
(1, 'admin'),
(2, 'coach'),
(3, 'customer')
ON CONFLICT (id) DO NOTHING;

INSERT INTO booking_status (id, status_name) VALUES
(1, 'Pending'),
(2, 'Confirmed'),
(3, 'Cancelled'),
(4, 'Refunded'),
(5, 'Expired')
ON CONFLICT (id) DO NOTHING;

INSERT INTO sports (id, sport_name, description, image_path) VALUES
(1, 'Badminton', 'From singles to doubles, enjoy our high-quality indoor badminton courts with flexible booking options.', '../img/badminton.jpg'),
(2, 'Basketball', 'Challenge your friends to a friendly match or join our indoor basketball tournaments for all skill levels.', '../img/basketball.jpg'),
(3, 'Futsal', 'Experience the thrill of 5-a-side football in a safe and climate-controlled indoor environment.', '../img/futsal.jpg'),
(4, 'Table Tennis', 'Play solo or compete in fast-paced rallies on professional-grade tables in our table tennis zone.', '../img/tblt.jpg'),
(5, 'Volleyball', 'Join indoor volleyball games in our spacious court designed for fun, fitness, and team spirit.', '../img/vol.jpg'),
(6, 'Boxing', 'Build strength and stamina with our indoor boxing facilities — whether you''re training or sparring.', '../img/boxing.jpg'),
(7, 'Indoor Climbing', 'Scale our indoor climbing wall for a full-body workout that challenges your strength and agility.', '../img/climbing.jpg')
ON CONFLICT (id) DO NOTHING;
-- Admin login
-- Email: admin@striveclub.local
-- Password: admin#007
INSERT INTO users (id, full_name, email, password, contact, role_id, is_active, created_at) VALUES
(1, 'admin', 'admin@striveclub.local', '$2y$10$JSnNbPW/mICXUmtTDy/UbuHLERPzDYZQwybZdvo.Fsd8EtAD3xHd.', NULL, 1, TRUE, NOW())
ON CONFLICT (id) DO NOTHING;

-- Coach logins
INSERT INTO users (id, full_name, email, password, contact, role_id, is_active, created_at) VALUES
-- James Carter password: couch#001
(2, 'James Carter', 'jamesc@gmail.com', '$2y$10$TFCcGPXhWC8/z2ly6MiQLeYFA3xqeNCUqAu.mVHGy2brn/ycRXPRm', '0711000001', 2, TRUE, NOW()),
-- Sarah Johnson password: couch#002
(3, 'Sarah Johnson', 'sarahj@gmail.com', '$2y$10$JTKxIUXm3Uaed9fUB5uiQeMvPXsTgiiaCf4Z1JQxnZlrxSKLFXSjm', '0711000002', 2, TRUE, NOW()),
-- David Brown password: couch#003
(4, 'David Brown', 'davidb@gmail.com', '$2y$10$E/Y2kty/d9b4ldReTFgnW.0WOOHsyUSemvSbPkU5FjVTvXuTOhT1a', '0711000003', 2, TRUE, NOW()),
-- Emma Wilson password: couch#004
(5, 'Emma Wilson', 'emmaw@gmail.com', '$2y$10$/52SuvgmBCZIJ4eUndGPL.DFWiRehUHXanXlE8EAo7N99J9Wu.Tau', '0711000004', 2, TRUE, NOW()),
-- Michael Davis password: couch#005
(6, 'Michael Davis', 'michaeld@gmail.com', '$2y$10$z/5Ttec65uM8nRwu/tql.ONyjp.uRQfmsKAc9wj.Cgz.0RivfvPiq', '0711000005', 2, TRUE, NOW()),
-- Olivia Miller password: couch#006
(7, 'Olivia Miller', 'oliviam@gmail.com', '$2y$10$U39k7kE9SKIfNONYTZG99ebL651fCVsRQjoeOsHJfcEKMspiYF0WW', '0711000006', 2, TRUE, NOW()),
-- William Garcia password: couch#007
(8, 'William Garcia', 'williamg@gmail.com', '$2y$10$d3dy/mYqNsNhLZ3JlglUkuoFR9qJUPtbFHTsxUN61xwk6ICGam4ya', '0711000007', 2, TRUE, NOW()),
-- Sophia Martinez password: couch#008
(9, 'Sophia Martinez', 'sophiam@gmail.com', '$2y$10$vg7VSTXnTnGxSz1dtLuCROCcEawjNElAX62e.X23VUa/nME2A.iUS', '0711000008', 2, TRUE, NOW()),
-- Benjamin Rodriguez password: couch#009
(10, 'Benjamin Rodriguez', 'benjaminr@gmail.com', '$2y$10$9QgnY6ZhG5mBjJ.TItBjtu4hBkTwplah9Xo04fwNNQq3Yaif2I8d.', '0711000009', 2, TRUE, NOW()),
-- Isabella Hernandez password: couch#010
(11, 'Isabella Hernandez', 'isabellah@gmail.com', '$2y$10$nHYoZRH27VRqWr4BsVgx3.hvU.LJzHBHd2Xh0mcwgFQ4F2LUAZPtG', '0711000010', 2, TRUE, NOW()),
-- Henry Lopez password: couch#011
(12, 'Henry Lopez', 'henryl@gmail.com', '$2y$10$YGxP1xJSsd98lXb2j5733.zaoxoQ.KxxgbsYl2vl0h5FCUu9d4.x.', '0711000011', 2, TRUE, NOW()),
-- Mia Gonzalez password: couch#012
(13, 'Mia Gonzalez', 'miag@gmail.com', '$2y$10$4DltpcVaJpnYPuNvgDEm0.MOtAK7tNo2YnABi4O.5yzGS4JyifZmO', '0711000012', 2, TRUE, NOW()),
-- Alexander Perez password: couch#013
(14, 'Alexander Perez', 'alexanderp@gmail.com', '$2y$10$4oVJmQcywiMgHo.5Uw3ypeSXtjl9CZCBCEv.QRxW5NDU1qNnA786S', '0711000013', 2, TRUE, NOW()),
-- Charlotte Thompson password: couch#014
(15, 'Charlotte Thompson', 'charlottet@gmail.com', '$2y$10$XgydvUnluKvQUtsCaSNy..ygVzM6LBGVRY4BUh19U4AV3dWMt578y', '0711000014', 2, TRUE, NOW())
ON CONFLICT (id) DO NOTHING;

-- Insert timetable data with adjusted coach IDs.
-- Admin uses id = 1. Coaches continue from id = 2 to id = 15.
INSERT INTO sport_timetable (sport_id, day_of_week, start_time, end_time, coach_id)
VALUES
(3, 'Tuesday', '08:00', '10:00', 6),  -- Futsal - Michael Davis
(4, 'Wednesday', '08:00', '10:00', 10),  -- Table Tennis - Benjamin Rodriguez
(2, 'Friday', '08:00', '10:00', 4),  -- Basketball - David Brown
(5, 'Saturday', '08:00', '10:00', 8),  -- Volleyball - William Garcia
(1, 'Sunday', '08:00', '10:00', 2),  -- Badminton - James Carter
(6, 'Tuesday', '10:00', '12:00', 12),  -- Boxing - Henry Lopez
(1, 'Wednesday', '10:00', '12:00', 11),  -- Badminton - Isabella Hernandez
(5, 'Friday', '10:00', '12:00', 3),  -- Volleyball - Sarah Johnson
(2, 'Saturday', '10:00', '12:00', 7),  -- Basketball - Olivia Miller
(3, 'Sunday', '10:00', '12:00', 9),  -- Futsal - Sophia Martinez
(4, 'Tuesday', '14:00', '16:00', 13),  -- Table Tennis - Mia Gonzalez
(7, 'Wednesday', '14:00', '16:00', 5),  -- Climbing - Emma Wilson
(6, 'Friday', '14:00', '16:00', 15),  -- Boxing - Charlotte Thompson
(3, 'Saturday', '14:00', '16:00', 9),  -- Futsal - Sophia Martinez
(5, 'Sunday', '14:00', '16:00', 3),  -- Volleyball - Sarah Johnson
(7, 'Tuesday', '16:00', '18:00', 14),  -- Climbing - Alexander Perez
(2, 'Wednesday', '16:00', '18:00', 7),  -- Basketball - Olivia Miller
(1, 'Friday', '16:00', '18:00', 2),  -- Badminton - James Carter
(6, 'Saturday', '16:00', '18:00', 15),  -- Boxing - Charlotte Thompson
(4, 'Sunday', '16:00', '18:00', 10);  -- Table Tennis - Benjamin Rodriguez
-- Keep sequences aligned after inserting fixed IDs.
SELECT setval('roles_id_seq', COALESCE((SELECT MAX(id) FROM roles), 1), true);
SELECT setval('booking_status_id_seq', COALESCE((SELECT MAX(id) FROM booking_status), 1), true);
SELECT setval('sports_id_seq', COALESCE((SELECT MAX(id) FROM sports), 1), true);
SELECT setval('users_id_seq', COALESCE((SELECT MAX(id) FROM users), 1), true);
SELECT setval('sport_timetable_id_seq', COALESCE((SELECT MAX(id) FROM sport_timetable), 1), true);

COMMIT;