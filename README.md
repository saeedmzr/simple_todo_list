
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
