README
RiffStream – COSC 459 Final Project
Signup, Login, and Dashboard Web Application

How to run:

1. Download zip and open folder in Applications/MAMP/htdocs/
2. import the database.sql file into myphpadmin
3. click go
4. Test the links - Signup: http://localhost:8888/riffstream_final/signup.php
5. Login: http://localhost:8888/riffstream_final/login.php

Files Included:
signup.php      - signup page
login.php       - login page
dashboard.php   - user dashboard
logout.php      - end session
db.php          - database connection (MAMP defaults)
common.php      - shared session/helper functions
style.css       - site styling
database.sql    - schema + sample data
images/logo.svg - required images folder

Additional Notes:

Passwords use password_hash() (secure hashing)
Dashboard access requires login (session-protected)
User details load from the users table
Contains sample users for demonstration; create your own via signup
