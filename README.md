GUVI Internship Task – User Profile Management System

CHECK INSIDE FOLDER FOR SCREENSHOTS OF THE OUTPUT AND THE SQL QUERY TEXT 

Tech Stack
Frontend: HTML, CSS, Bootstrap, jQuery (AJAX)
Backend: PHP
Databases: MySQL (Auth), MongoDB (Profile), Redis (Session)

Features
- User Registration & Login (MySQL)
- Secure Password Hashing
- AJAX-based communication (No page reloads)
- Profile Management (MongoDB)
- Edit Profile (Name, Age, DOB, Phone)
- Profile Photo Upload (stored locally)
- Session Management using Redis
- Logout functionality
- Clean UI with Bootstrap

Setup Instructions
1️. Install Required Software
Download and install:
XAMPP :  https://www.apachefriends.org/

MongoDB Community Server:  https://www.mongodb.com/try/download/community

Redis :  https://github.com/microsoftarchive/redis/releases 

Composer :  https://getcomposer.org/download/

2. Start All Services
-Open XAMPP Control Panel
Start:
Apache 
MySQL 
-Start MongoDB : mongod
-Start Redis : redis-server

3️.  Setup MySQL Database
Open in browser:
http://localhost/phpmyadmin

To access the database: http://127.0.0.1/phpmyadmin/ 

Create database and table

Run this SQL:

CREATE DATABASE guvi;
USE guvi;
CREATE TABLE users (
   id INT AUTO_INCREMENT PRIMARY KEY,
   email VARCHAR(255) UNIQUE,
   password VARCHAR(255)
);

4. Place Project in Correct Folder
Move your project to:

C:\xampp\htdocs\guvi-project

5️. Install PHP Dependencies

composer install

6️. Verify MongoDB Connection 

mongosh
use guvi
db.profiles.find()

7️. Run the Application

http://localhost/guvi-project/index.html

How It Works

Registration/Login -> MySQL
Profile -> MongoDB
Session -> Redis
Frontend -> AJAX

