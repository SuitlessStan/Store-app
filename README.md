# Store App

This is a Laravel 11 application designed for managing products, customers, orders, and suppliers. It supports user authentication, authorization, and a dedicated admin dashboard for managing the store.

---

## Features

- **User Authentication** - Register, Login, and Logout using Laravel Sanctum.
- **Admin Dashboard** - Allows admins to view statistics and manage products.
- **Products Management** - Create, edit, delete, and view products.
- **Favorites and Cart** - Users can add products to favorites and carts, which are linked to products.
- **Swagger API Documentation** - Auto-generated API documentation available.
- **Image Upload** - Products support image uploads.
- **Role-Based Access Control** - Admin-only access to dashboards and product management.
- **Docker Support** - Simplified development and deployment.

---

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM (for frontend assets)
- Docker and Docker Compose (optional for containerization)

---

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/your-repository/store-app.git
cd store-app
```

### 2. Environment Setup
```bash
cp .env.example .env
```

Update `.env` file with your database and other configurations:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=store_app
DB_USERNAME=root
DB_PASSWORD=root
```

### 3. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 4. Database Setup
```bash
php artisan migrate --seed
```

---

## Running the Application

### Option 1: Local Development
```bash
php artisan serve
```
Visit: `http://localhost:8000`

### Option 2: Using Docker
```bash
docker-compose up -d
```
Visit: `http://localhost:9000`

---

## Admin Dashboard

- **Access Admin Dashboard** - `http://localhost:8000/admin/dashboard`
- **Default Admin Credentials:**
  - Email: `admin@admin.com`
  - Password: `password123`

---

## API Documentation

### Generate Swagger Docs
```bash
php artisan l5-swagger:generate
```

### Access Swagger UI
```
http://localhost:8000/api/documentation
```

### Example API Requests

1. **Register User**
```http
POST /api/register
Content-Type: application/json
{
  "name": "John",
  "email": "john@example.com",
  "password": "password123"
}
```

2. **Login User**
```http
POST /api/login
Content-Type: application/json
{
  "email": "john@example.com",
  "password": "password123"
}
```
- **Token Response:** Include the token in the `Authorization` header:
```http
Authorization: Bearer {token}
```

3. **Create Product (Admin Only)**
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json
{
  "name": "Sample Product",
  "description": "Sample description",
  "price": 100.00
}
```

---

## Folder Structure

- `app/`: Models and controllers.
- `resources/`: Views, Tailwind CSS, and JS assets.
- `database/`: Migrations, seeders, and factories.
- `routes/`: API and web routes.
- `public/`: Frontend assets.

---

## Testing
```bash
php artisan test
```

---

## Additional Notes

- **Middleware:** Role-based middleware restricts access to admin features.
- **Image Uploads:** Product images are stored locally.
- **Favorites and Cart:** Database tables link to products for seamless management.

---
   
## License
This project is open-source and available under the [MIT License](LICENSE).
