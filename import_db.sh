#!/bin/bash

echo "======================================"
echo " MariaDB Database & User Creation Script"
echo "======================================"
echo "This script will:"
echo "  1. Prompt for MariaDB admin credentials."
echo "  2. Create a new database and user with the provided details."
echo "  3. Generate a .env file with the new credentials."
echo "  4. Optionally import an SQL file (db/cinema_db.sql) if it exists."
echo ""
echo "Prerequisites:"
echo "  - MariaDB installed and running."
echo "  - Admin credentials with privileges to create databases and users."
echo ""
echo "Usage:"
echo "  Run the script and follow the on-screen prompts."
echo "======================================"
echo ""

# Prompt for MariaDB admin credentials (must have privileges to create DBs/users)
read -p "MariaDB username: " admin_user
read -sp "MariaDB password: " admin_password
echo ""

# Prompt for new database details
read -p "Enter new database name: " new_db
read -p "Enter new database username: " new_user
read -sp "Enter new database user password: " new_password
echo ""

# Create the database and user, then grant privileges
mysql -u "$admin_user" -p"$admin_password" -e "
CREATE DATABASE IF NOT EXISTS \`${new_db}\`;
CREATE USER IF NOT EXISTS '${new_user}'@'localhost' IDENTIFIED BY '${new_password}';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON \`${new_db}\`.* TO '${new_user}'@'localhost';
FLUSH PRIVILEGES;"

if [ $? -eq 0 ]; then
  echo "Database '${new_db}' and user '${new_user}' created successfully."
else
  echo "Error creating database/user. Please check your credentials and privileges."
  exit 1
fi

# Create .env file with the new credentials
{
    echo "DB_DATABASE=${new_db}"
    echo "DB_HOST=localhost"
    echo "DB_USERNAME=${new_user}"
    echo "DB_PASSWORD=${new_password}"
} > ".env"

if [ $? -eq 0 ]; then
    echo ".env file created successfully."
else
    echo "Error creating .env file."
    exit 1
fi

# Import the SQL file if it exists
if [ -f "db/cinema_db.sql" ]; then
  mysql -u "$admin_user" -p"$admin_password" "$new_db" < db/cinema_db.sql
  if [ $? -eq 0 ]; then
    echo "Database imported successfully from database.sql."
  else
    echo "Error importing database from database.sql."
    exit 1
  fi
else
  echo "database.sql file not found. Skipping import."
fi
