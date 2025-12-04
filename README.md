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
The downloaded ZIP extracts to an outer folder named `riffstream_cosc459_final-main` that already contains the actual PHP app in the inner `riffstream_final` folder. Place that **entire** extracted folder directly inside `/Applications/MAMP/htdocs` so the paths stay aligned with the URLs below:

- Files sit at `/Applications/MAMP/htdocs/riffstream_cosc459_final-main/riffstream_final/{add_track.php,...}`
- Base URL for every page: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/`
- Example: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/login.php`

If you see a 404, verify the `-main` suffix remains in the folder name and that both `riffstream_cosc459_final-main` and `riffstream_final` are present in the URL.
The project ships as an outer repository folder (`riffstream_cosc459_final`) containing the actual PHP app in the inner `riffstream_final` folder. Choose the layout that matches how you download/place the repo into `/Applications/MAMP/htdocs` to avoid 404s:

1. **Clone or download the repo into `htdocs` (keeps outer folder)**
   - Files sit at `/Applications/MAMP/htdocs/riffstream_cosc459_final/riffstream_final/{add_track.php,...}`
   - Base URL for every page: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/`
   - Example: `http://localhost:8888/riffstream_cosc459_final/riffstream_final/login.php`

2. **Copy only the app folder into `htdocs` (no outer folder)**
   - Files sit at `/Applications/MAMP/htdocs/riffstream_final/{add_track.php,...}`
   - Base URL for every page: `http://localhost:8888/riffstream_final/`
   - Example: `http://localhost:8888/riffstream_final/login.php`

If you see a 404, double-check whether you kept the outer `riffstream_cosc459_final` directory; the base URL must match the chosen layout exactly.
The repository contains an outer project folder plus the actual app folder (`riffstream_final`) that holds the PHP files shown in the screenshot (`add_track.php`, `dashboard.php`, etc.). Use one of these layouts in `/Applications/MAMP/htdocs`:

1. **App folder only (recommended)** — copy the inner `riffstream_final` folder so `htdocs` looks like: `htdocs/riffstream_final/{add_track.php,...}`
   - Base URL: `http://localhost:8888/riffstream_final/`

2. **Outer repo kept** — if you place the whole repository in `htdocs` so you still see `README.md` and the `docs/` folder next to the app, the PHP lives at `htdocs/riffstream_cosc459_final/riffstream_final/{add_track.php,...}`
   - Base URL (nested): `http://localhost:8888/riffstream_cosc459_final/riffstream_final/`

## MAMP Setup Instructions
- Start Apache and MySQL in the MAMP app.
- Ensure the document root is set to `/Applications/MAMP/htdocs`.
- Move the extracted `riffstream_cosc459_final-main` folder (containing `riffstream_final`) directly into `htdocs` and keep both folder names unchanged.
- Open each page using the URL format shown below.

## URL cheat sheet
Using the required nested folders in `htdocs`, the full URLs are:
- Login: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/login.php`
- Signup: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/signup.php`
- Dashboard: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/dashboard.php`
- Playlists: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/playlists.php`
- Edit profile: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/edit_profile.php`
- Update profile: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/update_profile.php`
- Delete account: `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/delete_account.php`
- Add track (Artist only): `http://localhost:8888/riffstream_cosc459_final-main/riffstream_final/add_track.php`
- Place the project in `htdocs` using one of the two layouts above (whole repo vs. inner app folder) and use the matching base URL.
- Open each page using the URL format shown below.

## Quick setup
1. Place the extracted `riffstream_cosc459_final-main` folder (with `riffstream_final` inside) into `/Applications/MAMP/htdocs`.
2. Open phpMyAdmin at `http://localhost:8888/phpMyAdmin/`.
3. Create/import the database using `database.sql` (Import tab → choose file → Go).
4. Visit the signup page to create an account, then log in via `login.php`.

## Using the app
After login, the dashboard shows your profile and playlist tools with a nav bar linking to Dashboard, Manage Playlists, Edit Profile, (Artist: Add Track), and Log Out. Create playlists directly on the dashboard or manage/delete them from the Playlists page. Artists can add tracks from Add Track; listeners attempting direct access are redirected back to the dashboard. Use Edit Profile to keep details current or Delete Account to remove your profile entirely.

## Limitations
- Riffstream currently supports the uploading of audiofiles but has no audio playback functionality currently
