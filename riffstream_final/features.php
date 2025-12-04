<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

$flash_success = '';
$flash_error = '';

// Load the current user so we can validate access.
$userStmt = $pdo->prepare('SELECT user_id, account_type FROM users WHERE user_id = ?');
$userStmt->execute([$_SESSION['user_id']]);
$currentUser = $userStmt->fetch();

if (!$currentUser) {
    die('User not found.');
}

// Handle form submissions for playlist creation or adding a track.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'create_playlist') {
        $playlist_name = trim($_POST['playlist_name'] ?? '');
        $playlist_mood = trim($_POST['playlist_mood'] ?? '');

        if ($playlist_name === '') {
            $flash_error = 'Please enter a playlist name.';
        } else {
            $insert = $pdo->prepare('INSERT INTO playlists (user_id, name, description, created_at) VALUES (?, ?, ?, NOW())');
            $insert->execute([$currentUser['user_id'], $playlist_name, $playlist_mood ?: null]);
            $flash_success = 'Playlist created and saved to your account.';
        }
    } elseif ($formType === 'add_track') {
        if ($currentUser['account_type'] !== 'Artist') {
            $flash_error = 'Tracks are available to artist accounts only. Switch to an Artist account to add music.';
        } else {
            $track_title   = trim($_POST['track_title'] ?? '');
            $playlist_id   = trim($_POST['playlist_id'] ?? '');
            $playlist_name = '';

            if ($track_title === '') {
                $flash_error = 'Please provide a track title.';
            } else {
                if ($playlist_id !== '') {
                    $plCheck = $pdo->prepare('SELECT name FROM playlists WHERE playlist_id = ? AND user_id = ?');
                    $plCheck->execute([$playlist_id, $currentUser['user_id']]);
                    $plRow = $plCheck->fetch();

                    if (!$plRow) {
                        $flash_error = 'Selected playlist was not found in your account.';
                    } else {
                        $playlist_name = $plRow['name'];
                    }
                }

                if ($flash_error === '') {
                    $trackInsert = $pdo->prepare('INSERT INTO tracks (user_id, title, genre, album_name, created_at) VALUES (?, ?, NULL, NULL, NOW())');
                    $trackInsert->execute([$currentUser['user_id'], $track_title]);

                    if ($playlist_name) {
                        $flash_success = 'Track saved to your artist account. Playlist "' . h($playlist_name) . '" was selected and verified.';
                    } else {
                        $flash_success = 'Track saved to your artist account.';
                    }
                }
            }
        }
    }
}

// Load playlists for the current user to populate the selector.
$playlistsStmt = $pdo->prepare('SELECT playlist_id, name FROM playlists WHERE user_id = ? ORDER BY created_at DESC');
$playlistsStmt->execute([$currentUser['user_id']]);
$playlists = $playlistsStmt->fetchAll();

// Build a simple recent activity feed from playlists and tracks.
$activityStmt = $pdo->prepare('
    SELECT name AS item_label, "Playlist" AS item_type, created_at
    FROM playlists WHERE user_id = ?
    UNION ALL
    SELECT title AS item_label, "Track" AS item_type, created_at
    FROM tracks WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 5
');
$activityStmt->execute([$currentUser['user_id'], $currentUser['user_id']]);
$recent_activity = [];
foreach ($activityStmt->fetchAll() as $row) {
    $recent_activity[] = [
        'label' => ($row['item_type'] === 'Playlist' ? 'Created playlist "' : 'Added track "') . $row['item_label'] . '"',
        'time'  => date('M j, Y g:i a', strtotime($row['created_at']))
    ];
}
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
      <?php include __DIR__ . '/navbar.php'; ?>
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
            <input type="hidden" name="form_type" value="create_playlist">
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
            <input type="hidden" name="form_type" value="add_track">
            <div class="form-row">
              <label for="track-title">Track title</label>
              <input class="input" id="track-title" name="track_title" placeholder="Midnight Skyline" required>
            </div>
            <div class="form-row">
              <label for="playlist-select">Choose playlist</label>
              <select id="playlist-select" name="playlist_id">
                <option value="">Select…</option>
                <?php foreach ($playlists as $pl): ?>
                  <option value="<?php echo h($pl['playlist_id']); ?>"><?php echo h($pl['name']); ?></option>
                <?php endforeach; ?>
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
