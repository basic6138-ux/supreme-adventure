# 🏢 Apartment Booking System

> A modern, user-friendly web application for browsing and booking apartments online.

**Live Demo:** 🌐 [https://bookingapartment.up.railway.app/apartments](https://bookingapartment.up.railway.app/apartments)

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Schema](#database-schema)
- [Project Requirements](#project-requirements-met)
- [Setup Instructions](#setup-instructions)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Team](#team)

---

## 📌 Overview

The **Apartment Booking System** is a full-stack web application built with Laravel that enables users to:
- Create and manage accounts
- Browse available apartments
- Book apartments for specific dates
- Track their bookings and payment records
- Admin management of apartments and bookings

This project demonstrates a complete implementation of user authentication, CRUD operations, and a responsive user interface following modern web development practices.

---

## ✨ Features

✅ **User Authentication**
- User registration and login
- Secure password handling with Laravel Breeze
- Role-based access (User & Admin)
- Logout functionality

✅ **Apartment Management**
- Browse all available apartments
- View detailed apartment information
- Search and filter apartments
- View amenities for each apartment

✅ **Booking System**
- Create new bookings for apartments
- Check-in and check-out date selection
- Real-time availability checking
- Booking status tracking

✅ **Payment Management**
- Payment records linked to bookings
- Total price calculation
- Payment tracking

✅ **Admin Dashboard**
- Add and manage apartments
- View all bookings
- User management
- System statistics

✅ **Responsive Design**
- Mobile-friendly interface
- Tablet and desktop support
- Built with Tailwind CSS

---

## 🛠 Tech Stack

| Component | Technology |
|-----------|-----------|
| **Framework** | Laravel 11 |
| **Frontend** | Blade Template Engine + Tailwind CSS |
| **Database** | MySQL / SQLite |
| **Authentication** | Laravel Breeze |
| **Build Tool** | Vite |
| **Package Manager** | Composer, NPM |
| **Hosting** | Railway |

---

## 🗄 Database Schema

The application includes **5 main database tables**:

1. **users** - User accounts and authentication
2. **apartments** - Apartment listings and details
3. **amenities** - Features and facilities per apartment
4. **bookings** - Booking records with dates and status
5. **payment_records** - Payment information for bookings

### Entity Relationship:
```
Users (1) ──< (Many) Bookings
Apartments (1) ──< (Many) Bookings
Apartments (1) ──< (1) Amenities
Bookings (1) ──< (Many) Payment Records
```

---

## ✅ Project Requirements Met

| Requirement | Status |
|------------|--------|
| User Authentication | ✅ Implemented with Laravel Breeze |
| Register/Login/Logout | ✅ Complete |
| CRUD Operations | ✅ Full CRUD for Apartments & Bookings |
| Minimum 5 Tables | ✅ 5 tables designed |
| Input Validation | ✅ Laravel validation rules |
| Error Handling | ✅ Flash messages & error views |
| Responsive UI | ✅ Tailwind CSS responsive design |
| Laravel Framework | ✅ Built with Laravel 11 |
| GitHub Repository | ✅ Public repository available |
| Working Deployment | ✅ Live on Railway |

---

## 🚀 Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or SQLite
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/basic6138-ux/supreme-adventure.git
   cd supreme-adventure/Apartment_BoookingSystem/laravel_app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Setup environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** (edit `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=apartment_booking
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Install Node dependencies**
   ```bash
   npm install
   npm run dev
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

---

## 💻 Usage

### Default Test Accounts

After seeding the database, use these accounts:

**Admin Account:**
- Email: `admin@example.com`
- Password: `password`

**User Account:**
- Email: `student@example.com`
- Password: `password`

### Basic Workflows

**As a User:**
1. Register or login to your account
2. Browse apartments on the homepage
3. Click an apartment to view details and amenities
4. Click "Book Now" and select dates
5. Confirm your booking

**As an Admin:**
1. Login with admin credentials
2. Navigate to Admin Dashboard
3. Add new apartments or manage existing ones
4. View and manage all bookings
5. Monitor system statistics

---

## 📸 Screenshots

(Add screenshots here showing key pages: login, apartment listing, booking form, admin dashboard, etc.)

- **Homepage** - Browse available apartments
- **Apartment Detail** - View amenities and booking form
- **My Bookings** - Track your reservations
- **Admin Dashboard** - Manage apartments and bookings

---

## 👥 Team

This project was completed as a group assignment for **CIT18 | CITCS 3H GROUP B** - Laravel Final Project.

**Group Members:**
- Dennis Jean Thompson
- (Add other team members here)

---

## 📂 Project Structure

```
supreme-adventure/
├── Apartment_BoookingSystem/
│   ├── app/Models/              # Database models
│   │   ├── User.php
│   │   ├── Apartment.php
│   │   ├── Booking.php
│   │   ├── Amenity.php
│   │   └── PaymentRecord.php
│   ├── database/
│   │   ├── migrations/          # Database migrations
│   │   └── seeders/             # Database seeders
│   └── laravel_app/             # Main Laravel application
│       ├── app/Http/Controllers # Request handlers
│       ├── resources/views/     # Blade templates
│       ├── routes/              # Application routes
│       └── public/              # Public assets
├── README.md                    # This file
├── Dockerfile                   # Container configuration
└── railway.json                 # Railway deployment config
```

---

## 🔗 Important Links

- **Live Application:** [https://bookingapartment.up.railway.app/apartments](https://bookingapartment.up.railway.app/apartments)
- **GitHub Repository:** [https://github.com/basic6138-ux/supreme-adventure](https://github.com/basic6138-ux/supreme-adventure)
- **Laravel Documentation:** [https://laravel.com](https://laravel.com)

---

## 📝 License

This project is created for educational purposes as part of the Laravel Final Project coursework.

---

## 🤝 Contributing

This is a class project. For contributions or questions, please contact the team members directly.

---

**Last Updated:** April 9, 2026

*Make sure to test the live deployment before submitting!*