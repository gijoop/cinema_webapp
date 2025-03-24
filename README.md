# Cinema Web App

This repository contains a PHP web application for a cinema. The app allows users to view upcoming movies, check daily showings, and book seats for movie screenings. It also includes features for user registration, login, and an employee panel.

## Features

- **Movie Listings & Showings:**  
  - View upcoming movies with release dates and posters.
  - Browse the daily repertoire of movies with details like title, category, length, release date, language, and description.
  - Navigate between dates to see past or future showings.

- **Ticket Booking:**  
  - Secure seat booking interface with real-time seat availability.
  - Transaction-based booking process to prevent double booking.
  - Interactive seat selection with visual cues.

- **User Management:**  
  - User registration and login.
  - Separate panels for general users and employees.
  - Session-based authentication to personalize the experience.

- **Database Integration:**  
  - Uses MariaDB to store all cinema-related data.
  - Secure prepared statements to prevent SQL injection.
  - Transaction-based for fragile operations like booking seats.
  - Automated setup with a `setup.sh` script.
  - Sample database schema and data provided.

## Prerequisites

- **PHP:** Version 8.x or higher.
- **MariaDB:** Installed and running (with a user that has admin privileges).
- **MariaDB Client:** Command line access to execute the setup script.
- **Web Server:** Apache, Nginx, or another server with PHP support.
- **Git:** For cloning the repository.

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/gijoop/cinema_webapp
   cd cinema_webapp
   ```

2. **Setup the Database:**

   - Ensure MariaDB is installed and running.
   - Run the setup script to create the database, user, and environment file:

     ```bash
     ./setup.sh
     ```

   - The script will:
     - Prompt for your MariaDB admin credentials.
     - Ask for the new database name, username, and password.
     - Create the database and new user.
     - Generate a `.env` file with the new credentials.
     - Import the SQL file (`db/cinema_db.sql`) containing the database schema and sample data.

3. **Configure Your Web Server:**

   - Point your web server to the repository’s root directory.
   - Ensure PHP is enabled and properly configured.
   - Adjust the `.env` file or other configuration files if necessary for your server environment.

## Usage

- **Homepage:**  
  The homepage displays upcoming movies and the current day's showings. Users can navigate between different dates to view showings on other days.

- **Seat Booking:**  
  Select a movie showing and choose available seats. The booking process ensures seats are not double-booked, using transaction controls in the backend.

- **User Panels:**  
  - **User Panel:** After logging in, users can view their booked tickets and manage their profiles.
  - **Employee Panel:** Employees have access to additional management features (accessible via a dedicated panel).

- **Admin Notes:**  
  A sample DB backup command is included in the PHP code comments for backing up the database:

  ```bash
  mysqldump -u root -p --routines [db_name] > [backup_file].sql
  ```

## Troubleshooting

- **Database Connection Issues:**  
  Verify that the credentials in the `.env` file match those in your MariaDB installation and that the database is running.

- **Script Errors:**  
  Make sure you have sufficient privileges for the MariaDB admin account. Check the terminal output for any errors during the execution of `setup.sh`.

- **File Not Found:**  
  Ensure that the `cinema_db.sql` file is located in the `db/` directory before running the setup script.