<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

// Placeholder data/flags for the new features UI. Replace with real handlers.
$flash_success = '';
$flash_error = '';

$recent_activity = [
    ['label' => 'Created playlist "Road Trip Vibes"', 'time' => '2h ago'],
    ['label' => 'Added track "Echoes" to "Focus Mix"', 'time' => '6h ago'],
    ['label' => 'Updated profile picture', 'time' => '1d ago'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · New Features</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <?php $currentUser = ['account_type' => $_SESSION['account_type'] ?? 'Listener']; include __DIR__ . '/navbar.php'; ?>
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>New Features</h1>
          <p>Use this workspace to try the latest playlist and track tools.</p>
        </div>
      </div>

      <?php if ($flash_error): ?>
        <div class="error"><?php echo h($flash_error); ?></div>
      <?php endif; ?>
      <?php if ($flash_success): ?>
        <div class="success"><?php echo h($flash_success); ?></div>
      <?php endif; ?>

      <div class="grid-2">
        <section>
          <h2>Create a Playlist</h2>
          <p>Name it, set a vibe, and add it to your library.</p>
          <form method="post" action="">
            <div class="form-row">
              <label for="playlist-name">Playlist name</label>
              <input class="input" id="playlist-name" name="playlist_name" placeholder="Late Night Drives" required>
            </div>
            <div class="form-row">
              <label for="playlist-mood">Mood / tags</label>
              <input class="input" id="playlist-mood" name="playlist_mood" placeholder="chill · synthwave · instrumental">
            </div>
            <div class="actions">
              <button type="submit">Create playlist</button>
              <a class="btn btn-secondary" href="playlists.php">View playlists</a>
            </div>
          </form>
        </section>

        <section>
          <h2>Add a Track</h2>
          <p>Drop a new track into an existing playlist.</p>
          <form method="post" action="" enctype="multipart/form-data">
            <div class="form-row">
              <label for="track-title">Track title</label>
              <input class="input" id="track-title" name="track_title" placeholder="Midnight Skyline" required>
            </div>
            <div class="form-row">
              <label for="playlist-select">Choose playlist</label>
              <select id="playlist-select" name="playlist_id">
                <option value="">Select…</option>
                <option value="1">Focus Mix</option>
                <option value="2">Road Trip Vibes</option>
              </select>
            </div>
            <div class="form-row">
              <label for="audio-file">Upload audio (demo)</label>
              <input class="input" type="file" id="audio-file" name="audio_file" accept="audio/*">
            </div>
            <div class="actions">
              <button type="submit">Add track</button>
              <a class="btn btn-secondary" href="dashboard.php">Back to dashboard</a>
            </div>
          </form>
        </section>
      </div>

      <section style="margin-top: 20px;">
        <h2>Recent Activity</h2>
        <p>A quick log of the latest changes.</p>
        <?php if (empty($recent_activity)): ?>
          <div class="note">No activity yet. Your updates will show here.</div>
        <?php else: ?>
          <ul class="stacked-list" aria-label="Recent activity">
            <?php foreach ($recent_activity as $item): ?>
              <li>
                <div class="item-top">
                  <div class="qa-item-title"><?php echo h($item['label']); ?></div>
                  <div class="meta"><?php echo h($item['time']); ?></div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
