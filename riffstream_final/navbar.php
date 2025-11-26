<?php
// Simple reusable navigation for authenticated pages
// Expect require_login() to have run before including this file.

$currentUser = $currentUser ?? ($me ?? []);
$accountType = $currentUser['account_type'] ?? ($_SESSION['account_type'] ?? 'Listener');
?>
<nav class="nav-bar" aria-label="Main navigation">
  <div class="nav-brand">RiffStream</div>
  <div class="nav-links">
    <a href="dashboard.php" class="nav-link">Dashboard</a>
    <a href="delete_playlist.php" class="nav-link">Manage Playlists</a>
    <a href="update_profile.php" class="nav-link">Edit Profile</a>
    <?php if ($accountType === 'Artist'): ?>
      <a href="add_track.php" class="nav-link">Add Track</a>
    <?php endif; ?>
    <a href="logout.php" class="nav-link">Log Out</a>
  </div>
</nav>
