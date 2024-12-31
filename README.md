# Store App

This is a basic Laravel application for a store. It handles user authentication and authorization, includes essential database migrations, and provides the necessary setup instructions for local development.

---

## Features

- User Authentication (Register, Login, Password Reset)
- Authorization with Roles and Permissions
- Basic Database Migrations (Users, Cache, and Jobs tables)
- Built using Laravel, Tailwind CSS, and Vite for modern development
- Dockerized environment for simplified setup and deployment
- API with Swagger documentation available at `/api/documentation`

---

## Requirements

Before installing the application, make sure you have the following installed:

- Docker
- Docker Compose
- Git (for cloning the repository)

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-repository/store-app.git
cd store-app
```

---

### 2. Set Up the Environment

- Copy the example `.env` file and update it as needed:

  ```bash
  cp .env.example .env
  ```

- Configure your database and application environment variables in the `.env` file (these will be used by Docker Compose):

  ```dotenv
  DB_CONNECTION=mysql
  DB_HOST=mysql
  DB_PORT=3306
  DB_DATABASE=store_app
  DB_USERNAME=root
  DB_PASSWORD=root

  MYSQL_ROOT_PASSWORD=root
  MYSQL_DATABASE=store_app
  MYSQL_USER=root
  MYSQL_PASSWORD=root
  ```

---

### 3. Build and Start the Docker Containers

1. **Build the Docker images**:

   ```bash
   docker-compose build
   ```

2. **Start the containers**:

   ```bash
   docker-compose up -d
   ```

---

### 4. Set Up Laravel

1. **Install Laravel Dependencies**:

   ```bash
   docker exec -it laravel-app composer install
   ```

2. **Generate the Application Key**:

   ```bash
   docker exec -it laravel-app php artisan key:generate
   ```

3. **Run Migrations**:

   ```bash
   docker exec -it laravel-app php artisan migrate
   ```

4. **Set Permissions**:

   ```bash
   docker exec -it laravel-app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
   ```

---

### 5. Access the Application

- Laravel App: [http://localhost:9000](http://localhost:9000)
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)

---

## API Documentation

### 1. Generate Swagger Documentation

```bash
php artisan l5-swagger:generate
```

### 2. Access Swagger UI

Open the browser and visit:
```
http://localhost:8000/api/documentation
```

---

## Using the API with Postman

### 1. Register a New User
**Endpoint**:  
```
POST /api/register
```

**Headers**:
```
Content-Type: application/json
```

**Body**:
```json
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response**:
```json
{
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "johndoe@example.com"
    },
    "token": "your-access-token-here"
  }
}
```

---

### 2. Login to Get Token
**Endpoint**:  
```
POST /api/login
```

**Headers**:
```
Content-Type: application/json
```

**Body**:
```json
{
  "email": "johndoe@example.com",
  "password": "password123"
}
```

**Response**:
```json
{
  "token": "your-access-token-here",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

---

### 3. Use Token for Authorization
For all subsequent requests, add the following header:
```
Authorization: Bearer your-access-token-here
```

---

### 4. Example - Create a Customer
**Endpoint**:  
```
POST /api/customers
```

**Headers**:
```
Authorization: Bearer your-access-token-here
Content-Type: application/json
```

**Body**:
```json
{
  "name": "Alice Smith",
  "email": "alice.smith@example.com",
  "phone": "1234567890"
}
```

**Response**:
```json
{
  "data": {
    "id": 1,
    "name": "Alice Smith",
    "email": "alice.smith@example.com",
    "phone": "1234567890",
    "created_at": "2024-12-31",
    "updated_at": "2024-12-31"
  }
}
```

---

### 5. Other API Endpoints
- **List Customers**:
  ```
  GET /api/customers
  ```
- **Create a Product**:
  ```
  POST /api/products
  ```
  Body:
  ```json
  {
    "name": "Laptop",
    "description": "Gaming laptop",
    "price": 1200.00
  }
  ```
- **Create an Order**:
  ```
  POST /api/orders
  ```
  Body:
  ```json
  {
    "customer_id": 1,
    "order_date": "2024-12-31",
    "status": "pending",
    "total": 1200.00
  }
  ```

---

## Folder Structure

- `app/`: Application logic and service providers
- `config/`: Application configuration files
- `database/`: Migrations, seeders, and factories
- `public/`: Frontend entry point
- `routes/`: Application routes (`web.php`, `api.php`)
- `resources/`: Views, Tailwind CSS, and frontend assets
- `tests/`: Automated tests

---

## Testing

To run automated tests:

```bash
docker exec -it laravel-app php artisan test
```

---

## Contributing

Feel free to fork the repository and create pull requests. For significant changes, please open an issue first to discuss your ideas.

---

## License

This project is open-source and available under the [MIT License](LICENSE).
