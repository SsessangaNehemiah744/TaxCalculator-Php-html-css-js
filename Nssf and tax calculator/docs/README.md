# NSSF and Tax Calculator

A PHP-based payroll management system for calculating taxes, NSSF contributions, and managing employee records.

## Project Overview

This application provides:
- Employee registration and data management
- Payroll calculations including tax and NSSF deductions
- Database storage of employee information
- User-friendly web interface

## Project Structure

```
public/                   # Web-accessible files (HTML, PHP)
assets/                   # Images and static assets
docs/                     # Documentation and requirements
README.md                 # This file (moved to docs)
```

## Features

### 1. Employee Registration (`submit.php`)
- Register new employees with personal and salary information
- Store data in the `registration` table

### 2. Display Employees (`display_data.php`)
- View all registered employees in a formatted table
- Secure data display with XSS protection
- Error handling for database issues
- Responsive table layout

### 3. Update Employee Records (`updating.php`)
- Modify existing employee information
- Update salary and tax details

### 4. Delete Employee Records (`deleting.php`)
- Remove employee records from the database
- Permanent deletion with confirmation

## Setup Instructions

### Prerequisites
- PHP 7.0 or higher
- MySQL/MariaDB server
- XAMPP or similar local development environment

### Installation Steps

1. **Create Database**
   ```sql
   CREATE DATABASE taxation;
   USE taxation;
   ```

2. **Create Tables**
   ```sql
   CREATE TABLE registration (
       id INT AUTO_INCREMENT PRIMARY KEY,
       EmployeeName VARCHAR(100) NOT NULL
       -- Add other relevant columns based on your requirements
   );
   ```

3. **Configure Database Connection**
   - PHP files in `public/` use local constants for DB config. Update as needed.

4. **Access Application**
   - Place project folder in `C:\xampp\htdocs\`
   - Open `http://localhost/<folder_name>/public/index.html`

## Contact

For issues or questions regarding this application, please contact the development team.

---

**Last Updated:** December 2, 2025  
**Version:** 1.0 (Refactored)
