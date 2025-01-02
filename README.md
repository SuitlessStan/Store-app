# Store App

This is a basic Laravel application for a store. It handles user authentication and authorization, includes essential database migrations, and provides the necessary setup instructions for local development.

---

## Features

- User Authentication (Register, Login, Password Reset)
- Authorization with Roles and Permissions
- Admin Dashboard for managing products
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

### 3. Using Postman for API Testing

1. Set the **base URL**:
```
http://localhost:8000/api
```

2. Authentication:
   - Register a new user at:
     `POST /register`
   - Login to get a token:
     `POST /login`
   - Use the **Authorization: Bearer <token>** header in subsequent requests.

3. Example Endpoints:
   - **Customers**
     - `GET /customers` - Fetch all customers.
     - `POST /customers` - Create a new customer.
   - **Orders**
     - `GET /orders` - Fetch all orders.
     - `POST /orders` - Create a new order.
   - **Products**
     - `GET /products` - Fetch all products.
     - `POST /products` - Create a new product.

---

## Admin Dashboard

1. Visit: `http://localhost:9000/admin`
2. Sign in with admin credentials.
3. Add new products by filling in the required details including name, description, price, stock quantity, category, brand, and product image.
4. Manage existing products, edit details, or delete products as needed.

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
