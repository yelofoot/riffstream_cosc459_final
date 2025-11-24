## RiffStream – COSC 459 Final Project
A simple PHP/MySQL web application featuring user signup, login, and a session-protected dashboard.

## Project Structure 
signup.php – Registration page for new users
login.php – User login page
dashboard.php – User dashboard (requires login)
logout.php – Ends the user session
db.php – Database connection (MAMP defaults)
common.php – Shared session/helper functions
style.css – Global site styling
database.sql – Database schema + sample user data
images/logo.svg – Project logo

## How to Run (MAMP)
Move the project folder into:
Applications/MAMP/htdocs/

Open phpMyAdmin at:
http://localhost:8888/phpMyAdmin/

Import the database:
Go to Import
Select database.sql
Click Go

Access the application:

  Signup page:
  http://localhost:8888/riffstream_final/signup.php
  
  Login page:
  http://localhost:8888/riffstream_final/login.php
