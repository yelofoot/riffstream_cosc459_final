## RiffStream – COSC 459 Final Project
A simple PHP/MySQL web application featuring user signup, login, and a session-protected dashboard.

## Project Structure
signup.php – Registration page for new users
login.php – User login page
dashboard.php – User dashboard (requires login)
edit_profile.php – Edit/update user profile
playlists.php – View user playlists (placeholder page until playlists are implemented)
delete_account.php – Delete the currently logged-in user account
logout.php – Ends the user session
db.php – Database connection (MAMP defaults)
common.php – Shared session/helper functions
style.css – Global site styling
database.sql – Database schema + sample user data
images/logo.svg – Project logo

## Features
- Secure signup/login and session-based dashboard
- Update Feature: Edit profile details via edit_profile.php
- Delete Feature: Delete account via delete_account.php
- Additional Feature: “My Playlists” page via playlists.php

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

## Navigation
After logging in, use the dashboard buttons to:

- Edit Profile
- View My Playlists
- Delete Account
- Log Out
