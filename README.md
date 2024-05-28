Notification Service
====================

This project is a multi-provider notification service built with Laravel, capable of sending different types of notifications (email, SMS) through various providers based on user preferences and provider availability.

Requirements
------------

- Docker
- Docker Compose

Setup
-----

Follow these steps to set up and run the application:

1. Clone the Repository
-----------------------
git clone https://github.com/yourusername/notification-service.git
cd notification-service

2. Create and Configure the .env File
-------------------------------------

Create a `.env` file in the root directory of your project based on the `.env.example` file. You can use the following command:
Edit the `.env` file to configure your environment variables. Make sure the database settings match those defined in the `docker-compose.yml` file.
cp .env.example .env

3. Build and Run Docker Containers
----------------------------------

Build and start the Docker containers using Docker Compose:

docker-compose up --build -d


This command will:
- Build the Docker images for the PHP application and MySQL database.
- Start the containers in detached mode.

4. Install Composer Dependencies
--------------------------------

Once the containers are up and running, you need to install the Composer dependencies. Use the following command:

docker-compose exec php composer install


5. Generate Application Key
---------------------------

Generate the application key for Laravel:

docker-compose exec php php artisan key


6. Run Database Migrations
--------------------------

Run the database migrations to set up the database schema:

