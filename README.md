# Strive Sports Club

Strive Sports Club is an indoor sports club booking web application built with **PHP**, **PostgreSQL**, **HTML**, **CSS**, and **JavaScript**. The project follows an **MVC-style structure** and focuses on allowing customers to register, log in, browse available sports, view timetables, book sessions, make payments, and manage their bookings.

> **Project Status:** The **user/customer side is completed**. The **admin side** and **coach side** are still under development.

---

## Project Overview

The main purpose of Strive Sports Club is to provide an online booking system for an indoor sports club.

Users can:

- Register and log in
- View available indoor sports
- View weekly sports timetables
- Book sports sessions
- Choose payment options
- Make payments
- View dashboard information
- Manage their bookings
- Submit feedback

The project also includes database support for admins, coaches, roles, bookings, payments, sessions, announcements, audit logs, privilege logs, and feedback. However, the admin and coach interfaces are still in progress.

---

## Current Completion Status

| Module | Status |
|---|---|
| User registration | Completed |
| User login | Completed |
| User dashboard | Completed |
| Sports listing | Completed |
| Timetable display | Completed |
| Booking flow | Completed |
| Payment flow | Completed |
| User booking management | Completed |
| Feedback support | Completed |
| Admin dashboard | Under development |
| Coach dashboard | Under development |
| Admin management features | Under development |
| Coach-side features | Under development |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Styling | Tailwind CSS / Custom CSS |
| Backend | PHP |
| Database | PostgreSQL |
| Database Access | PDO |
| Architecture | MVC-style structure |
| Local Server | XAMPP / PHP built-in server |

---

## Folder Structure

```text
Strive-Sports-Club/
│
├── controllers/
│   ├── authController.php
│   ├── bookingController.php
│   ├── userController.php
│   └── payController.php
│
├── core/
│   ├── db.php
│   └── Session.php
│
├── css/
│   └── style.css
│
├── database/
│   ├── schema.sql
│   └── seed.sql
│
├── img/
│   └── project images
│
├── js/
│   └── script.js
│
├── models/
│   ├── user.php
│   ├── booking.php
│   ├── pay.php
│   └── feedback.php
│
├── pages/
│   ├── home.php
│   ├── register.php
│   ├── login.php
│   ├── dashboard.php
│   ├── book.php
│   ├── paychoice.php
│   ├── payment.php
│   ├── timetable.php
│   ├── about.php
│   ├── contact.php
│   ├── terms.php
│   │
│   ├── user/
│   │   ├── dashboard.php
│   │   ├── profile.php
│   │   ├── my_bookings.php
│   │   └── feedback.php
│   │
│   ├── admin/
│   │   └── admin pages under development
│   │
│   ├── coach/
│   │   └── coach pages under development
│   │
│   └── partials/
│       ├── header.php
│       └── footer.php
│
├── screenshots/
│   └── README screenshots
│
└── README.md
```

---

## Main Features

### Completed User-Side Features

- User registration
- User login and logout
- Session-based authentication
- Sports browsing
- Timetable viewing
- Sports session booking
- Pay-now / pay-later flow
- Payment handling
- User dashboard
- Booking status handling
- User booking history
- Feedback submission

### Backend and Database Features

- PostgreSQL database integration
- PDO-based database connection
- Password hashing and verification
- Role-based database structure
- Booking status management
- Sports and timetable seed data
- Coach seed data
- Admin seed account
- Slot count tracking
- Booking-related triggers
- Database constraints and relationships

### Under Development

- Admin dashboard
- Admin user management
- Admin booking management
- Admin sports management
- Coach dashboard
- Coach schedule management
- Coach-side booking/session features

---

## Database Setup

The project uses PostgreSQL and includes two SQL files inside the `database/` folder:

```text
database/
├── schema.sql
└── seed.sql
```

### `schema.sql`

The `schema.sql` file creates the database and required tables.

It includes:

```sql
CREATE DATABASE isports_club;

\c isports_club;
```

So run it without selecting the database manually.

### `seed.sql`

The `seed.sql` file inserts initial project data such as:

- Roles
- Booking statuses
- Sports
- Admin demo account
- Coach demo accounts
- Timetable data

The seed file also includes password comments for the initial admin and coach demo accounts.

---

## Database Tables

| Table | Purpose |
|---|---|
| `roles` | Stores role types such as admin, coach, and customer |
| `users` | Stores users, coaches, and admin account details |
| `sports` | Stores available indoor sports |
| `timetable` | Stores weekly sports schedule data |
| `sport_timetable` | Stores sport timetable slots with assigned coaches |
| `booking_status` | Stores booking status values |
| `bookings` | Stores customer bookings |
| `payments` | Stores payment records |
| `sessions` | Stores created sports sessions |
| `slot_count` | Tracks confirmed bookings per timetable slot |
| `feedback` | Stores user feedback and ratings |
| `announcements` | Stores announcements |
| `audit_logs` | Stores system activity logs |
| `privilege_logs` | Stores role-change history |

---

## Seeded Sports

The seed data includes the following sports:

- Badminton
- Basketball
- Futsal
- Table Tennis
- Volleyball
- Boxing
- Indoor Climbing

---

## Seeded Coach Accounts

The seed data includes coach accounts for:

- James Carter
- Sarah Johnson
- David Brown
- Emma Wilson
- Michael Davis
- Olivia Miller
- William Garcia
- Sophia Martinez
- Benjamin Rodriguez
- Isabella Hernandez
- Henry Lopez
- Mia Gonzalez
- Alexander Perez
- Charlotte Thompson

The demo passwords are included as SQL comments inside `database/seed.sql`.

---

## Requirements

Make sure you have the following installed:

- PHP 8.0 or higher
- PostgreSQL
- XAMPP, WAMP, Laragon, MAMP, or PHP built-in server
- Git
- `pdo_pgsql` PHP extension enabled

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/sasmitha007/Strive-Sports-Club.git
cd Strive-Sports-Club
```

### 2. Import the Database Schema

Run the schema file first:

```bash
psql -U postgres -f database/schema.sql
```

On Windows, if `psql` is not recognized, use the full PostgreSQL path:

```cmd
"C:\Program Files\PostgreSQL\17\bin\psql.exe" -U postgres -f "database\schema.sql"
```

If your project is inside XAMPP, example:

```cmd
"C:\Program Files\PostgreSQL\17\bin\psql.exe" -U postgres -f "C:\xampp\htdocs\Strive Club\database\schema.sql"
```

### 3. Import the Seed Data

After the schema is created, run:

```bash
psql -U postgres -d isports_club -f database/seed.sql
```

On Windows:

```cmd
"C:\Program Files\PostgreSQL\17\bin\psql.exe" -U postgres -d isports_club -f "database\seed.sql"
```

If your project is inside XAMPP:

```cmd
"C:\Program Files\PostgreSQL\17\bin\psql.exe" -U postgres -d isports_club -f "C:\xampp\htdocs\Strive Club\database\seed.sql"
```

### 4. Configure Database Connection

Open:

```text
core/db.php
```

Update the database connection details:

```php
private static $host = 'localhost';
private static $port = '5432';
private static $dbname = 'isports_club';
private static $user = 'your_postgres_username';
private static $password = 'your_postgres_password';
```

Do not commit real local database passwords to GitHub.

### 5. Run the Project

If using PHP's built-in server:

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000/pages/home.php
```

If using XAMPP, place the project folder inside `htdocs` and open it through your browser:

```text
http://localhost/Strive%20Club/pages/home.php
```

---

## Screenshots

The screenshots should be placed inside a folder named:

```text
screenshots/
```

The following screenshots are planned for the README:

### Home Page

![Home Page](screenshots/home.png)

### Register Page

![Register Page](screenshots/register.png)

### Login Page

![Login Page](screenshots/login.png)

### User Dashboard

![User Dashboard](screenshots/dashboard.png)

### Booking Page

![Booking Page](screenshots/booking.png)

### Timetable Page

![Timetable Page](screenshots/timetable.png)

### Payment Page

![Payment Page](screenshots/payment.png)

### Admin Dashboard

![Admin Dashboard](screenshots/admin-dashboard.png)

> Note: Some screenshots may not be added yet. Add them later using the exact filenames shown above so the README image links work correctly.

---

## Security Notes

This project includes some important security practices:

- Password hashing
- Password verification
- PDO prepared statements
- Session-based authentication
- Database constraints
- Role-based database structure

Recommended improvements:

- Move database credentials out of `core/db.php`
- Add CSRF protection for forms
- Add stronger server-side validation
- Add centralized route protection
- Add role-based middleware for admin and coach pages
- Add better error handling
- Add automated tests

---

## Future Improvements

- Complete the admin dashboard
- Complete the coach dashboard
- Add admin sports management
- Add admin user management
- Add coach schedule management
- Add email confirmation for bookings
- Add real payment gateway integration
- Add booking filters
- Add backend tests
- Add API endpoints for future frontend/mobile integration
- Improve validation and error messages

---

## Author

**Sasmitha Rajapaksha**

GitHub: [sasmitha007](https://github.com/sasmitha007)

---

## License

This project is currently not licensed. Add a license if you want others to use, modify, or distribute the project.
