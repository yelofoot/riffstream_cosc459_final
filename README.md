## RiffStream – COSC 459 Final Project
A PHP/MySQL web app where listeners and artists can sign up, log in, and manage their music world from a session-protected dashboard.

## Overview
- Two account types: **Listener** and **Artist**. Artist accounts see an extra **Add Track** option in navigation.
- Core flows: registration, login, profile updates, playlist creation/deletion, and logout—all styled with the included dark/glass UI.

## Pages / Files
- `signup.php` – Registration form for new users.
- `login.php` – User login page.
- `dashboard.php` – Main hub with profile summary, playlist creation form, and quick links.
- `edit_profile.php` / `update_profile.php` – Edit/update user profile details.
- `add_track.php` – Artist-only track entry and listing.
- `delete_playlist.php` & `delete_playlist_confirm.php` – Manage and delete playlists.
- `playlists.php` – "My Playlists" view.
- `delete_account.php` – Delete the currently logged-in user account.
- `logout.php` – Ends the user session.
- `db.php` – Database connection (MAMP defaults).
- `common.php` – Shared session/auth helpers including `require_login()`.
- `style.css` – Global styling for the glassy UI and components.
- `database.sql` – Schema and starter data for users/playlists/tracks.
- `images/logo.svg` – Project logo used in headers.

## Project Structure
- `signup.php` – Registration page for new users
- `login.php` – User login page
- `dashboard.php` – User dashboard (requires login)
- `edit_profile.php` – Edit/update user profile
- `playlists.php` – View user playlists (placeholder page until playlists are implemented)
- `delete_account.php` – Delete the currently logged-in user account
- `logout.php` – Ends the user session
- `db.php` – Database connection (MAMP defaults)
- `common.php` – Shared session/helper functions
- `style.css` – Global site styling
- `database.sql` – Database schema + sample user data
- `images/logo.svg` – Project logo

## Features
- Secure signup/login with session-based dashboard access.
- Profile editing, including account type and optional password change.
- Playlist management: create from the dashboard and delete with confirmation.
- Artist-only track entry plus a listener-friendly playlists view.
- Shared navigation showing "Add Track" only to Artist accounts.

## Running under MAMP
The project lives at `/Applications/MAMP/htdocs/riffstream_cosc459_final/riffstream_final/`. With the default MAMP Apache port `8888`, the live site is served from:

- Base URL: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/`

## MAMP Setup Instructions
- Start Apache and MySQL in the MAMP app.
- Ensure the document root is set to `/Applications/MAMP/htdocs`.
- Place the entire project folder inside `htdocs`.
- Open each page using the URL format shown below.

## URL cheat sheet
With the structure above, the full URLs are:
- Login: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/login.php`
- Signup: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/signup.php`
- Dashboard: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/dashboard.php`
- Playlists: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/playlists.php`
- Edit profile: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/edit_profile.php`
- Update profile: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/update_profile.php`
- Delete account: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/delete_account.php`
- Add track (Artist only): `http://localhost:8888/riffstream_cosc459_final/riffstream_final/add_track.php`

## Quick setup
1. Place the project in MAMP using one of the options above.
2. Open phpMyAdmin at `http://localhost:8888/phpMyAdmin/`.
3. Create/import the database using `database.sql` (Import tab → choose file → Go).
4. Visit the signup page to create an account, then log in via `login.php`.

## Using the app
After login, the dashboard shows your profile and playlist tools with a nav bar linking to Dashboard, Manage Playlists, Edit Profile, (Artist: Add Track), and Log Out. Create playlists directly on the dashboard or manage/delete them from the Playlists page. Artists can add tracks from Add Track; listeners attempting direct access are redirected back to the dashboard. Use Edit Profile to keep details current or Delete Account to remove your profile entirely.
