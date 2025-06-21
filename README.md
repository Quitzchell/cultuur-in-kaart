# Cultuur in Kaart
Cultuur in Kaart is a web application built with Laravel and Filament, designed to manage and visualize projects, activities, and partnerships for Samen Ontwikkelen door Cultuur. It provides tools for users to effectively track and organize contact persons, stakeholders, and cultural initiatives.

> Note: This is an unfinished project, developed as part of a school assignment. It serves as a minimal proof of concept. Some features, such as authentication, detailed validation, and production asset compilation, may be incomplete or missing.

## Features
* Manage projects, activities, and tasks.
* Track coordinators, contact persons, and partners.
* Visualize data related to neighborhoods and disciplines.
* Integration with Filament for admin panel functionalities.
* Database seeding and migrations for easy setup.

## Requirements
* PHP 8.1 or higher
* Composer
* Node.js and npm
* Docker
* SQLite (or another database supported by Laravel)

## Development with Docker
__1. Clone the repository:__
```bash
git clone https://github.com/quitzchell/cultuur-in-kaart.git
```
__2. Navigate to the project directory and copy environment file:__
```bash
cd cultuur-in-kaart
cp .env.example .env
```
_make sure to update the .env file_

__3. Build and start the Docker containers using the Makefile:__
```bash
make dev
```

__4. Find the container ID of the running application:__
```bash
docker ps
```
__5. Locate the container for the application and note its ID.__

__6. Enter the container's shell:__
```bash
docker exec -it <container-id> /bin/sh
```
_Replace <container-id> with the first few characters of the container ID._

__7. Install backend and frontend dependencies and run database setup:__
```bash
composer install && npm install
php artisan key:generate
php artisan migrate --seed
npm run build
```

__8. Access the application at <a>http://localhost.8080</a>__

## Testing
__Run the test suite in the container using Pest:__
```bash
php artisan test
```

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact
For questions, reach out via [GitHub @quitzchell](https://github.com/quitzchell).
