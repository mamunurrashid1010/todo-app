# Todo List Manager App

A simple todo list management web application built using **Laravel 12** (as backend API) and **Vue 3** (as frontend), with **token-based authentication** via Laravel Sanctum.

---

## Project Structure

- **Backend:** Laravel 12 API with Sanctum authentication
- **Frontend:** Vue 3 using `axios`, `vue-router`, and `bootstrap`

---

## Features

- User Registration
- User Authentication (Login & Logout)
- Task CRUD:
    - Add a new task
    - Edit existing tasks
    - Mark tasks as complete/incomplete
    - Delete tasks
- Uses token-based authentication with Sanctum (`Bearer` token)
- Responsive UI with Bootstrap

---

## Tech Stack

- **Frontend:** Vue 3, Axios, Bootstrap 5
- **Backend:** Laravel 12, Sanctum, MySql
- **Auth:** Token-based via Laravel Sanctum

---

## Backend Setup (Laravel)

### 1. Clone & Install

```bash
git clone https://github.com/mamunurrashid1010/todo-app.git
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure ```.env```
- Set your database credentials
- Add Sanctum config:
```
SANCTUM_STATEFUL_DOMAINS=http://localhost:5173,http://localhost:5174
SESSION_DOMAIN=localhost
```

### 3. Run Migrations
```
php artisan migrate
```

### 4. Serve the app
```
php artisan serve
```

## Feature Tests
```tests/Feature/AuthTest.php```

#### Covers:

- User registration

- Login & token issuance

- Accessing authenticated user profile


```tests/Feature/TaskTest.php```
#### Covers:

- Authenticated user can create tasks

- Authenticated user can view their own tasks

- User cannot view others’ tasks (403 Forbidden)

- Authenticated user can update their own tasks

- User cannot update tasks they don't own (403 Forbidden)

### Run the tests
```
php artisan test
```

##  API Endpoints

| Method | Endpoint        | Description                     |
| ------ | --------------- |---------------------------------|
| POST   | /api/register   | Registraion a new user          |
| POST   | /api/login      | Login user (returns token)      |
| POST   | /api/logout     | Logout user (requires token)    |
| GET    | /api/user       | Get current authenticated user  |
| GET    | /api/tasks      | List all tasks (requires token) |
| POST   | /api/tasks      | Create a new task               |
| PUT    | /api/tasks/{id} | Update a task                   |
| DELETE | /api/tasks/{id} | Delete a task                   |


## Frontend Setup (Vue 3)

---

## Install Vue Project
```
cd frontend
npm install
```

## Run the app
```
npm run dev
```

Ensure it’s running on http://localhost:5174 to match CORS settings.

Done