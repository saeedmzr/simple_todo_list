
## TodoList: A Laravel To-Do List Application


This repository contains the source code for TodoList, a Laravel 10 application for managing your tasks.

## Features
- **User Authentication**
- **Create, read, update, and delete tasks**
- **Schedule tasks to be automatically completed after 2 days (using Laravel's task scheduling functionality)**
- **utilizes Laravel's broadcasting functionality to enable real-time updates**

## Requirements:

- **PHP >= 8.2**
- **Composer**
- **Mysql 8**
- **PHP >= 8.2**
## Technical Stack:
- **Laravel 10**
- **Laravel Sail (for dockerized development environment)**

## Installation:

#### 1. Clone this repository:

##### Clone the repo:
`git clone https://github.com/saeedmzr/simple_todo_list`

#### 2. Install dependencies:
`composer install`

#### 3. Navigate to the project directory:
`cd simple_todo_list`

#### 4. Generate the application key:
`php artisan key:generate`

#### 5. Create a .env file from .env.example and set your environment variables, including your database connection details.:

#### 6. Create a database and set it up on .env file.

#### 7. Run the database migrations:
`php artisan migrate`

#### 8. Run the server:
`php artisan serve`

#### Note:
##### This project offers the flexibility of running in a Dockerized environment using Laravel Sail. To set up Sail, configure the required environment variables, such as db_port and ext, in your .env file. Then, simply execute the following command to start the development environment in the background: ######

`.\vendor\bin\sail up -d`


## Broadcasting with Pusher

TodoList utilizes Laravel's broadcasting feature with Pusher to provide real-time updates to users. Whenever a task is updated, such as being created, updated, or deleted, all connected users receive immediate updates without needing to refresh the page.

### How It Works

1. **Event Triggering**: Whenever a task-related action occurs, such as creating, updating, or deleting a task, an event is triggered in the application.

2. **Broadcasting Event**: The triggered event is then broadcasted to Pusher, a real-time messaging service, which acts as a bridge between the server and connected clients.

3. **Client Listening**: Clients, such as web browsers, mobile apps, or other devices, listen for events from Pusher. When an event is received, the client updates the user interface in real-time to reflect the changes.

### Channel Authorization

To ensure that users only receive updates for tasks they have access to, TodoList implements channel authorization. This means that users are only subscribed to channels for tasks they own or have permission to access.


```<?php
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tasks.{taskId}', function (User $user, int $taskId) {
    return $user->id === Task::findOrNew($taskId)->user_id;
});
```

### Testing

This project prioritizes code quality and maintainability through a robust testing suite. It includes a total of 20 well-structured unit and feature tests that cover various aspects of the application, including:

- ### Unit Tests: ###
    - #### `TaskRepositoryTest`: Ensures the `TaskRepository` class functions properly, including creating, finding, updating, and deleting tasks. ###
    - #### `UserRepositoryTest`:  Verifies the functionality of the `UserRepository` class, testing login, registration, and user retrieval by ID. ###
- ### Feature  Tests: ###
    - #### `AuthTest`: Validates user authentication functionalities, including login, registration, and logout. ###
    - #### `DatabaseTest`:  Confirms that the database connection is established and operational. ###
    - #### `TaskTest`: Tests user interactions with tasks, such as creating, viewing, updating, finding, deleting, and marking them complete. ###

### Running Tests:

#### Navigate to the project directory in your terminal and execute this command:
`php artisan test`

#### This command will run all the tests and display the results, indicating which tests passed and highlighting any failures.

### Testing Database:

#### To run tests against a dedicated testing database without affecting your main database:

##### 1. Create a separate database for testing purposes.
##### 2. Configure the connection details for this testing database in your `.env.testing` file.
##### 3. Run the tests using the following command: `php artisan test --env=testing` 

#### This ensures your tests are isolated and don't modify data in your production environment.

### Documentation: 
#### Documentation for this project is generated using Swagger. To view the documentation, you can run the following command:
`php artisan l5-swagger:generate`

#### Once generated, you can access the documentation through the endpoint /api/documentation#/. This provides comprehensive details about the API endpoints and their functionalities.




