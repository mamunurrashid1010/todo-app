# Laravel Todo List Management API

This is the **backend API** for a todo list management app built using **Laravel** and **Laravel Sanctum** for token-based authentication. It provides user registration, login/logout, and CRUD operations for tasks.

## Features

- User Registration
- User Login (token-based)
- Logout (token revocation)
- Authenticated task management:
    - Create task
    - Update task
    - Delete task
    - Toggle task completion
    - List all tasks

---

## Requirements

- PHP ^8.2
- Composer
- Laravel 12
- MySQL
- Laravel Sanctum

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/mamunurrashid1010/todo-app
cd backend
```

### 2. Install dependencies
```
composer install
```

### 3. Copy ```.env``` file and set environment variables
```
cp .env.example .env
```
Update your ```.env```:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_db
DB_USERNAME=root
DB_PASSWORD=

// Add/update Sanctum config:
SANCTUM_STATEFUL_DOMAINS=http://localhost:5173,http://localhost:5174
SESSION_DOMAIN=localhost
```

### 4. Generate app key
```
php artisan key:generate
```

### 5. Run migrations
```
php artisan migrate
```

### 6. Start the server
```
php artisan serve
```

---

## API Endpoints
### Auth Routes
| Method | Endpoint      | Description                |
| ------ | ------------- | -------------------------- |
| POST   | /api/register | Register user              |
| POST   | /api/login    | Login user (returns token) |
| POST   | /api/logout   | Logout & revoke token      |
| GET    | /api/user     | Get authenticated user     |

### Task Routes (Protected)
| Method | Endpoint        | Description       |
| ------ | --------------- | ----------------- |
| GET    | /api/tasks      | List user tasks   |
| POST   | /api/tasks      | Create a new task |
| PUT    | /api/tasks/{id} | Update task       |
| DELETE | /api/tasks/{id} | Delete task       |



