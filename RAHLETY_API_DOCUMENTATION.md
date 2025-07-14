# 🌊 Rahlety App - Complete API Documentation

**Travel Booking Platform for Egyptian Tourist Destinations**

*Prepared By: Ahmed Eldeeb*  
*Implementation Date: July 2025*  
*Laravel Version: 12.20.0*

---

## 📋 Table of Contents
- [Overview](#-overview)
- [Tech Stack](#-tech-stack)
- [Database Schema](#-database-schema)
- [Authentication](#-authentication)
- [API Endpoints](#-api-endpoints)
- [Models & Relationships](#-models--relationships)
- [Installation & Setup](#-installation--setup)
- [Testing](#-testing)

---

## 🌟 Overview

Rahlety is a mobile-first travel application enabling users to book daily trips in Egyptian tourist destinations (Sharm El Sheikh, Dahab, Nuweiba). The platform connects:

- **End Users (Tourists)** - Browse and book trips
- **Companies (Tour Operators)** - Manage trip listings and bookings
- **Agents (SimSars)** - Earn commissions through referrals
- **Admins** - Platform management and oversight

### Key Features
✅ Multi-user authentication system  
✅ Trip discovery with advanced filtering  
✅ Secure booking system with QR codes  
✅ Commission-based referral system  
✅ Review and rating system  
✅ Admin panel for platform management  
✅ Real-time availability tracking  

---

## 🛠 Tech Stack

- **Framework**: Laravel 12.20.0
- **Authentication**: Laravel Sanctum (Token-based)
- **Database**: SQLite (development) / MySQL (production)
- **ORM**: Eloquent
- **Queue System**: Database/Redis
- **File Storage**: Local/Cloudinary
- **API Format**: RESTful JSON API

---

## 🗄 Database Schema

### Users Table
```sql
- id (Primary Key)
- name (User's full name)
- email (Optional login credential)
- phone (Unique phone number)
- password (Hashed password)
- agent_id (Foreign Key - nullable)
- language (ar/en/ru)
- timestamps
```

### Agents Table
```sql
- id (Primary Key)
- name (Agent's name)
- phone (Unique phone)
- password (Hashed password)
- referral_code (Auto-generated unique code)
- total_commission (Decimal - tracked amount)
- timestamps
```

### Companies Table
```sql
- id (Primary Key)
- name (Company name)
- email (Unique email)
- phone (Contact phone)
- logo (Image path)
- description (Optional)
- password (Hashed password)
- is_approved (Boolean - admin approval)
- timestamps
```

### Trips Table
```sql
- id (Primary Key)
- company_id (Foreign Key)
- title (Trip title)
- description (Full details)
- price (Per person - decimal)
- included (Items included)
- excluded (Items not included)
- capacity (Max participants)
- date (Trip date)
- time (Start time)
- location (Departure point)
- image (Cover image)
- is_active (Boolean)
- timestamps
```

### Bookings Table
```sql
- id (Primary Key)
- user_id (Foreign Key)
- trip_id (Foreign Key)
- company_id (Foreign Key)
- agent_id (Foreign Key - nullable)
- persons (Number of people)
- total_price (Decimal)
- status (pending/confirmed/cancelled)
- reference_code (QR code reference)
- timestamps
```

### Reviews Table
```sql
- id (Primary Key)
- user_id (Foreign Key)
- trip_id (Foreign Key)
- rating (1-5 stars)
- comment (Optional text)
- timestamps
- UNIQUE(user_id, trip_id)
```

### Commissions Table
```sql
- id (Primary Key)
- agent_id (Foreign Key)
- booking_id (Foreign Key)
- amount (Decimal)
- paid (Boolean)
- timestamps
```

---

## 🔐 Authentication

### Token-Based Authentication (Laravel Sanctum)

All API requests (except public endpoints) require authentication via Bearer token:

```http
Authorization: Bearer {token}
```

### User Types
1. **User** - Regular tourists
2. **Agent** - Commission-earning referrers  
3. **Company** - Tour operators
4. **Admin** - Platform administrators

---

## 🚀 API Endpoints

### Authentication Endpoints

#### Register User
```http
POST /api/auth/register/user
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+201234567890",
    "password": "password123",
    "password_confirmation": "password123",
    "referral_code": "AGT-12345678", // Optional
    "language": "en" // Optional: ar/en/ru
}
```

#### Register Agent
```http
POST /api/auth/register/agent
Content-Type: application/json

{
    "name": "Agent Name",
    "phone": "+201234567890",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Register Company
```http
POST /api/auth/register/company
Content-Type: application/json

{
    "name": "Adventure Tours",
    "email": "info@adventure.com",
    "phone": "+201234567890",
    "description": "Best tours in Sharm El Sheikh",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "login": "john@example.com", // Email or phone
    "password": "password123"
}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

#### Get User Info
```http
GET /api/auth/user
Authorization: Bearer {token}
```

### Trip Endpoints

#### List Trips (Public)
```http
GET /api/trips?location=sharm&date=2025-07-15&min_price=100&max_price=500&sort_by=price&sort_order=asc
```

#### Show Trip Details (Public)
```http
GET /api/trips/{id}
```

#### Create Trip (Company Only)
```http
POST /api/trips
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "title": "Red Sea Snorkeling Adventure",
    "description": "Amazing underwater experience...",
    "price": 250.00,
    "included": "Lunch, Equipment, Transportation",
    "excluded": "Personal expenses",
    "capacity": 20,
    "date": "2025-07-20",
    "time": "09:00",
    "location": "Sharm El Sheikh Marina",
    "image": {file}
}
```

#### Update Trip (Company Only)
```http
PUT /api/trips/{id}
Authorization: Bearer {token}
```

#### Delete Trip (Company Only)
```http
DELETE /api/trips/{id}
Authorization: Bearer {token}
```

#### My Trips (Company Only)
```http
GET /api/trips/my/trips
Authorization: Bearer {token}
```

### Booking Endpoints

#### Create Booking
```http
POST /api/bookings
Authorization: Bearer {token}
Content-Type: application/json

{
    "trip_id": 1,
    "persons": 2
}
```

#### User Bookings
```http
GET /api/bookings/user
Authorization: Bearer {token}
```

#### Company Bookings
```http
GET /api/bookings/company
Authorization: Bearer {token}
```

#### Update Booking Status (Company Only)
```http
PUT /api/bookings/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "confirmed" // confirmed/cancelled
}
```

#### Cancel Booking
```http
PUT /api/bookings/{id}/cancel
Authorization: Bearer {token}
```

### Review Endpoints

#### Add Review
```http
POST /api/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
    "trip_id": 1,
    "rating": 5,
    "comment": "Amazing experience!"
}
```

#### Trip Reviews (Public)
```http
GET /api/reviews/trip/{trip_id}
```

#### My Reviews
```http
GET /api/reviews/my
Authorization: Bearer {token}
```

### Agent Endpoints

#### Agent Dashboard
```http
GET /api/agents/dashboard
Authorization: Bearer {token}
```

#### Commission History
```http
GET /api/agents/commissions
Authorization: Bearer {token}
```

#### Referred Users
```http
GET /api/agents/referrals
Authorization: Bearer {token}
```

### Admin Endpoints (Admin Only)

#### Users Management
```http
GET /api/admin/users
GET /api/admin/users/{id}
PUT /api/admin/users/{id}/status
```

#### Company Management
```http
GET /api/admin/companies
PUT /api/admin/companies/{id}/approve
PUT /api/admin/companies/{id}/reject
```

#### Commission Management
```http
GET /api/admin/commissions
GET /api/admin/commissions/pending
PUT /api/admin/commissions/{id}/pay
```

---

## 🏗 Models & Relationships

### Key Model Features

#### User Model
- Extends `Authenticatable`
- Uses `HasApiTokens`, `HasFactory`, `Notifiable`
- Relationships: `agent()`, `bookings()`, `reviews()`

#### Trip Model
- Advanced filtering scopes
- Computed attributes for availability and ratings
- Image upload handling
- Relationship with bookings and reviews

#### Booking Model
- Automatic reference code generation
- Commission calculation on creation
- Cancellation logic with business rules

#### Agent Model
- Auto-generated referral codes
- Commission tracking methods
- Authentication capabilities

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP 8.4+
- Composer
- SQLite/MySQL
- Git

### Step-by-Step Installation

1. **Clone & Install**
```bash
git clone <repository>
cd laravel-app
composer install
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
touch database/database.sqlite
php artisan migrate
```

4. **Storage Setup**
```bash
php artisan storage:link
```

5. **Start Development Server**
```bash
php artisan serve
```

### Configuration

#### Commission Settings (config/rahlety.php)
```php
'commission_rate' => 0.05, // 5% commission
'min_withdrawal_amount' => 100,
'cancellation_hours' => 24,
'max_booking_persons' => 10,
```

---

## 🧪 Testing

### API Testing with Postman/cURL

#### Example: Register and Login Flow
```bash
# Register user
curl -X POST http://localhost:8000/api/auth/register/user \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "phone": "+201234567890",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "login": "+201234567890",
    "password": "password123"
  }'

# Use returned token for authenticated requests
curl -X GET http://localhost:8000/api/trips \
  -H "Authorization: Bearer {your-token}"
```

---

## 📊 Business Logic

### Commission System
- 5% commission on successful bookings through agent referrals
- Automatic commission calculation and tracking
- Reversal on booking cancellation

### Booking Rules
- 24-hour cancellation policy
- Capacity management with real-time availability
- Status workflow: pending → confirmed/cancelled

### Review System
- One review per user per trip
- 1-5 star rating system
- Time-limited review window (30 days after trip)

---

## 🔒 Security Features

- Token-based authentication
- Role-based access control
- Input validation and sanitization
- SQL injection prevention via Eloquent ORM
- CSRF protection
- Rate limiting capabilities

---

## 🚀 Production Deployment

### Environment Variables
```env
RAHLETY_COMMISSION_RATE=0.05
RAHLETY_MIN_WITHDRAWAL=100
RAHLETY_CANCELLATION_HOURS=24
RAHLETY_MAX_BOOKING_PERSONS=10
```

### Recommended Stack
- **Server**: Ubuntu 20.04+
- **Web Server**: Nginx
- **Database**: MySQL 8.0+
- **PHP**: 8.4+
- **Queue**: Redis
- **Storage**: AWS S3/Cloudinary

---

## 📞 Support & Contact

**Developer**: Ahmed Eldeeb  
**Email**: [ahmed@example.com]  
**Documentation Date**: July 2025  

---

*This documentation covers the complete implementation of the Rahlety travel booking platform. All endpoints are fully functional and ready for frontend integration.*