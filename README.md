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

## Features
- Secure signup/login with session-based dashboard access.
- Profile editing, including account type and optional password change.
- Playlist management: create from the dashboard and delete with confirmation.
- Artist-only track entry plus a listener-friendly playlists view.
- Shared navigation showing "Add Track" only to Artist accounts.

## Running under MAMP
The application source lives in the `riffstream_final/` directory of this repository. Your URLs must match the directory name you place inside `Applications/MAMP/htdocs/`.

### Option A: use the app folder only (recommended)
1. Move the **`riffstream_final/`** folder into `Applications/MAMP/htdocs/`.
2. Visit the app at: `http://localhost:8888/riffstream_final/`

### Option B: keep the repository wrapper
1. Move the entire repository (e.g., **`riffstream_cosc459_final/`**) into `Applications/MAMP/htdocs/`.
2. Visit the app with the nested path: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/`

If the URL path does not match the folder name you copied into `htdocs`, MAMP will return a 404 when following the README links.

## URL cheat sheet
Replace `<base>` with the correct base path from the options above.

- Signup: `http://localhost:8888/<base>/signup.php`
- Login: `http://localhost:8888/<base>/login.php`
- Dashboard: `http://localhost:8888/<base>/dashboard.php`
- Edit profile: `http://localhost:8888/<base>/edit_profile.php`
- Add track (Artist only): `http://localhost:8888/<base>/add_track.php`
- Playlists: `http://localhost:8888/<base>/playlists.php`
- Delete account: `http://localhost:8888/<base>/delete_account.php`
- Logout: `http://localhost:8888/<base>/logout.php`

## Quick setup
1. Place the project in MAMP using one of the options above.
2. Open phpMyAdmin at `http://localhost:8888/phpMyAdmin/`.
3. Create/import the database using `database.sql` (Import tab → choose file → Go).
4. Visit the signup page to create an account, then log in via `login.php`.

## Using the app
After login, the dashboard shows your profile and playlist tools with a nav bar linking to Dashboard, Manage Playlists, Edit Profile, (Artist: Add Track), and Log Out. Create playlists directly on the dashboard or manage/delete them from the Playlists page. Artists can add tracks from Add Track; listeners attempting direct access are redirected back to the dashboard. Use Edit Profile to keep details current or Delete Account to remove your profile entirely.
