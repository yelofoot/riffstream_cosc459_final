# Navigation visibility issue — diagnosis and fix

## What we observed
Users reported that the navigation bar and the links to newly added features were missing. The issue happened whenever they navigated to pages like **My Playlists** or **Delete Account**, which still used the legacy layout.

## Why it happened
Those pages never included `navbar.php`, so the shared navigation markup (and the link to the features workspace) was not rendered. Because the navbar is the only in-app entry point to "New Features" after login, omitting it effectively hid the new tools from anyone landing on those screens.

## How we fixed it
- Added `navbar.php` to the remaining authenticated pages (`playlists.php` and `delete_account.php`) using the current session’s account type for role-aware links.
- Removed duplicated, outdated copy on both pages so the UI reflects a single, clear flow after the navbar appears.
- Re-ran the PHP syntax lint to confirm the templates load cleanly after the changes.

With the navbar now present on every signed-in page, users can consistently reach the dashboard and the "New Features" area.
