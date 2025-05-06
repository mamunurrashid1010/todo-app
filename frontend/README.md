# Vue Todo List Manager

A simple and responsive todo list management application built with **Vue 3**  
It allows authenticated users to create, update, complete, and delete tasks easily.

## Features

- User Registration
- User authentication (login/logout)
- Create, edit, and delete tasks
- Mark tasks as completed/incomplete
- REST API integration with Axios

## Backend Requirements
To support the functionality of this frontend app, the backend should expose the following API routes:

User registration
```
POST /api/register
```
Authenticates the user
```
POST /api/login
```
Logs the user out and invalidates the authentication token.
```
POST /api/logout
```
Retrieves the list of tasks for the authenticated user.
```
GET /api/tasks
```
Creates a new task with the provided data (title, body).
```
POST /api/tasks
```
Updates the specified task (identified by id) with the new data (title, body, is_completed).
```
PUT /api/tasks/{id}
```
Deletes the specified task (identified by id).
```
DELETE /api/tasks/{id}
```



## Installation

Follow the steps below to install and run this project locally:

### 1. Install Dependencies

```bash
npm install
```

### 2. Configure API Base URL
Update the ```baseURL``` for Axios in ```main.js```
```bash
axios.defaults.baseURL = 'http://your-api-domain.com';
```

### 3. Run the App
```bash
npm run dev
```