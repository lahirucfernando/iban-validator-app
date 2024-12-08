# IBAN Number Validation App
## Set up backend - Laravel 11

Install Dependencies
```
Install PHP 8.3 + version and composer
Install Mysql 5.7 + version
```
Go to backend folder
```
cd backend
```
Setup project environment
```
cp .env.example .env
Create Database called 'iban-validator-app'
Setup DB_USERNAME and DB_PASSWORD in .env file
```   
Install vendor packages
```
composer install
```
Run database migrations
```
php artisan migrate
```
Run seeders
```
php artisan db:seed
```
Generate Swagger API Documentation 
```
php artisan l5-swagger:generate
```
Start backend application
```
php artisan serve
```
You can access the following urls
- **API URL**:  http://127.0.0.1:8000/api
- **Swagger API documentation URL**: http://127.0.0.1:8000/api/documentation


## Default Users
Once the seeders are run, the following default users will be available:

- **Regular User**  
  - **Email**: `user@example.com`  
  - **Password**: `password@123`

- **Admin User**  
  - **Email**: `admin@example.com`  
  - **Password**: `password@123`

You can log in using these credentials to test the app.


## Set up front end - Vuetify 3
Install Dependencies
```
Install Node.js 18.x + version
Install npm 10.x + version
```
Go to frontend folder
```
cd frontend
```
Install packages
```
npm install
```
Setup project environment
```
cp .env.example .env
```
Start frontend application
```
npm run dev
```
You can access the frontend application at http://localhost:3000


