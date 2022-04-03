## UD_TODO_LIST_API_APP

# Defination

This project is a simple Rest API project build with Laravel and PHP 8.0. Major functionality it includes are:

- Creating a task
- Updating the task either by complete or incomplete,
- Filter the task based on title and due date i.e. today, this week, next week, and overdue
- Delete the task

# Prerequisites

- Docker
- Composer

# To run project

- After importing the project 
- Install all the project dependency with 
`composer install`
- Project was created using the sail, so php won't work for migration and database seeder
- You can either start the server using the sail command for go through `docker.composer.yml`

- Using Sail
`./vendor/bin/sail up`
- It will take several minutes to load up all the required container and images setup. and make sure docker desktop is running.
- From project path on terminal execute this command for use of sail command. `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'`
- Once sail up is done, now we can migrate the tables and seed the testing data into database
- `sail artisan migrate` to migrate all the tables into database.
- `sail artsian db:seed` to seed testing data into table.
- Test case is also included on project. To run test cases: `sail artisan test`
- Afterwards you can start docker container from docker desktop app.

Note: Postman collection is added on project file

