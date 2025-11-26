## RiffStream – COSC 459 Final Project
A PHP/MySQL web app where listeners and artists can sign up, log in, and manage their music world from a session-protected dashboard.

## Overview
- Two account types: **Listener** and **Artist**. Artist accounts see an extra **Add Track** option in navigation.
- Core flows: registration, login, profile updates, playlist creation/deletion, and logout—all styled with the included dark/glass UI.

## Pages / Files
- `signup.php` – Registration form for new users.
- `login.php` – User login page.
- `dashboard.php` – Main hub with profile summary, playlist creation form, and quick links.
- `update_profile.php` – Edit/update user profile details.
- `add_track.php` – Artist-only track entry and listing.
- `delete_playlist.php` & `delete_playlist_confirm.php` – Manage and delete playlists.
- `playlists.php` – Simple “My Playlists” view / placeholder listing.
- `delete_account.php` – Delete the currently logged-in user account.
- `logout.php` – Ends the user session.
- `db.php` – Database connection (MAMP defaults).
- `common.php` – Shared session/auth helpers including `require_login()`.
- `style.css` – Global styling for the glassy UI and components.
- `database.sql` – Schema and starter data for users/playlists/tracks.
- `images/logo.svg` – Project logo used in headers.

## Project Structure
- signup.php – Registration page for new users
- login.php – User login page
- dashboard.php – User dashboard (requires login)
- edit_profile.php – Edit/update user profile
- playlists.php – View user playlists (placeholder page until playlists are implemented)
- delete_account.php – Delete the currently logged-in user account
- logout.php – Ends the user session
- db.php – Database connection (MAMP defaults)
- common.php – Shared session/helper functions
- style.css – Global site styling
- database.sql – Database schema + sample user data
- images/logo.svg – Project logo

## Features
- Secure signup/login and session-based dashboard
- Update Feature: Edit profile details via edit_profile.php
- Delete Feature: Delete account via delete_account.php
- Additional Feature: “My Playlists” page via playlists.php

## How to Run (MAMP)
Move the project folder into:
Applications/MAMP/htdocs/

## Features
- Secure signup/login with session-based dashboard access.
- Profile editing, including account type and optional password change.
- Playlist management: create from the dashboard and delete with confirmation.
- Artist-only track entry plus a listener-friendly playlists view.
- Shared navigation showing “Add Track” only to Artist accounts.

## Quick Setup (MAMP)
1) Place the project folder inside `Applications/MAMP/htdocs/`.
2) Open phpMyAdmin at `http://localhost:8888/phpMyAdmin/`.
3) Create/import the database using `database.sql` (Import tab → choose file → Go).
4) Visit `http://localhost:8888/riffstream_final/signup.php` to register, then log in via `login.php`.

## Using the App
- After login, the dashboard shows your profile and playlist tools with a nav bar linking to Dashboard, Manage Playlists, Edit Profile, (Artist: Add Track), and Log Out.
- Create a playlist directly on the dashboard, or manage/delete playlists from the Manage Playlists page.
- Artists can add tracks from the Add Track page; listeners attempting direct access are redirected back to the dashboard.
- Use Edit Profile to keep account details current, or Delete Account to remove your profile entirely.
Access the application:

  Signup page:
  http://localhost:8888/riffstream_final/signup.php

  Login page:
  http://localhost:8888/riffstream_final/login.php

  Dashboard:
  http://localhost:8888/riffstream_final/dashboard.php
  

## Navigation
After logging in, use the dashboard buttons to:

- Edit Profile
- View My Playlists
- Delete Account
- Log Out
