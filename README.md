<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400">
    </a>
    <a>
        <h1><p align="center">Leaderboard API</p></h1>
    </a>
</p>
<p align="center">
    <a href="https://laravel.com/" alt="Built with: Laravel v8.68.1">
        <img src="https://badgen.net/badge/Built%20with/Laravel%20v8.68.1/FF2D20" />
    </a>
    <a href="https://www.php.net/downloads.php" alt="Powered by: PHP v7.4.8">
        <img src="https://badgen.net/badge/Powered%20by/PHP%20v7.4.8/8892BF" />
    </a>
</p>

## LARAVEL Features used
- **Migration and Seeding**
- **Cascade Soft deletes using: [michaeldyrynda/laravel-cascade-soft-deletes](https://github.com/michaeldyrynda/laravel-cascade-soft-deletes)**
- **Requests and Rules (Sanitization and Validation)**
- **Custom Exception Handling**
- **Scopes**
- **Mutators and Accessors**
- **Resources (For Response Transformation)**
- **Global constants**
- **Testing (Features, Exceptions and Error Response)**

## Installation and Requirements
1. Install [Composer](https://getcomposer.org/download/)
2. Install [Xampp](https://www.apachefriends.org/download.html)
3. Install [Postman](https://www.postman.com/downloads/)
4. Clone the repository [GitHub](https://github.com/singhpreet89/leaderboard_api.git)
```bash
git clone git@github.com:singhpreet89/leaderboard_api.git
``` 
5. Use [Composer](https://getcomposer.org/download/) to install the required dependencies by navigating to the root directory of the cloned repository and run the following command inside the Terminal:
```bash
composer install
``` 
6. Rename the **".env.example"** file in the root directory to **".env"**.

## Running the application
1. Navigate to the root directory and run the following commands:
```bash
php artisan key:generate
php artisan serve
``` 
2. Open Xampp and verify PhpMyAdmin is running.
3. Make sure to create a new database with name ***"leaderboard_api"*** using PhpMyAdmin.
4. Create the database and add fake data with the following commands:
```bash
php artisan migrate
php artisan db:seed 
```
6. Open the Postman and send a GET request to fetch the Leaderboard or a PATCH request to play the **"Leaderboard game"** according to the api calls format in the next section.

## Endpoints
- **(All Endpoints Invluded)** Import the **"Leaderboard API.postman_collection.json"** into Postman. 
- A quick overview of some of the Leaderboard API structure is as follows:

| Description | Endpoints | HTTP Method | HEADERS | Payload |
| ------------- | ------------- | ------------- | ------------- | ------------- |
| Get the Leaderboard (All Users): | `http://localhost:6000/api/users` | GET | content-type: contentTypeJson | |
| Play the Game: | `http://localhost:6000/api/users/{user_id}` | PATCH | content-type: contentTypeJson | Select **"raw"** data, with content-type: contentTypeJson, {"operation" : "subtraction"} or {"operation" : "addition"} |
| Create a User: | `http://localhost:6000/api/users` | POST | content-type: contentTypeJson | Select the **"raw"** data as follows: {"name" : "harpreet singh", "email" : "harpreet.singh@yopmail.com", "birth_date" : "01/01/1972", "line1" : "59 Alpine drive", "line2" : "Apt. 714", "city" : "Ottawa", "province" : "Ontario", "country" : "CA", "postal_code" : "K2B 1G1"} |
| View a User: | `http://localhost:6000/api/users/{user_id}` | GET | content-type: contentTypeJson | |
| Delete a User: | `http://localhost:6000/api/users/{user_id}` | DELETE | content-type: contentTypeJson | |
