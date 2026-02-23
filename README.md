# Attendance API — Multi-Tenant SaaS

A RESTful API built with Laravel for a **Multi-Tenant Attendance Management System (SaaS)**. Designed as a prototype using SQLite, with JWT-based authentication and tenant isolation.

---

## Features

- JWT Authentication (Login, Logout, Refresh Token)
- Multi-Tenant Support (tenant isolation per organization)
- Attendance Management (check-in, check-out, history)
- Employee Management per Tenant
- Attendance Report per Tenant
- Role-based Access Control (Admin, Employee)

---

## Tech Stack

- **Framework:** Laravel
- **Authentication:** JWT (tymon/jwt-auth)
- **Database:** SQLite (Prototype)
- **Architecture:** RESTful API / API Only
- **Multi-Tenancy:** Tenant-based data isolation

---

## Installation

```bash
# Clone the repository
git clone https://github.com/psyco357/api_only.git
cd api_only

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Run migrations
php artisan migrate

# Serve the application
php artisan serve
```

---

## Authentication

This API uses **JWT (JSON Web Token)** for authentication.

| Method | Endpoint             | Description                 |
| ------ | -------------------- | --------------------------- |
| POST   | `/api/auth/register` | Register new tenant & admin |
| POST   | `/api/auth/login`    | Login & get JWT token       |
| POST   | `/api/auth/logout`   | Logout                      |
| POST   | `/api/auth/refresh`  | Refresh JWT token           |

---

## API Endpoints

### Attendance

| Method | Endpoint                    | Description        |
| ------ | --------------------------- | ------------------ |
| POST   | `/api/attendance/check-in`  | Check-in           |
| POST   | `/api/attendance/check-out` | Check-out          |
| GET    | `/api/attendance/history`   | Attendance history |

### Employee

| Method | Endpoint              | Description        |
| ------ | --------------------- | ------------------ |
| GET    | `/api/employees`      | List all employees |
| POST   | `/api/employees`      | Add new employee   |
| PUT    | `/api/employees/{id}` | Update employee    |
| DELETE | `/api/employees/{id}` | Delete employee    |

---

## Notes

> This project is a **prototype** built for demonstration purposes.  
> SQLite is used for simplicity. For production, it is recommended to switch to **MySQL or PostgreSQL**.

---

## Author

- GitHub: [@psyco357](https://github.com/psyco357)

---

## License

This project is open-sourced under the [MIT License](LICENSE).
