<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

$stmt = $pdo->prepare('SELECT user_id, account_type FROM users WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$me = $stmt->fetch();

$flash = $_GET['msg'] ?? '';

$playlistsStmt = $pdo->prepare('SELECT playlist_id, name, description, created_at FROM playlists WHERE user_id = ? ORDER BY created_at DESC');
$playlistsStmt->execute([$_SESSION['user_id']]);
$playlists = $playlistsStmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Delete a playlist</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <?php $currentUser = $me; include __DIR__ . '/navbar.php'; ?>
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Your playlists</h1>
          <p>Choose a playlist to remove. Deleting a playlist does not affect tracks themselves.</p>
        </div>
      </div>

      <?php if ($flash): ?>
        <div class="success"><?php echo h($flash); ?></div>
      <?php endif; ?>

      <?php if (empty($playlists)): ?>
        <div class="note">
          You don’t have any playlists yet. Create one on the dashboard, then you can delete it here.
        </div>
        <div class="actions">
          <a class="btn" href="dashboard.php">Create your first playlist</a>
        </div>
        <div class="note">No playlists found. Create one on the dashboard, then you can delete it here.</div>
      <?php else: ?>
        <ul class="stacked-list">
          <?php foreach ($playlists as $pl): ?>
            <li>
              <div class="item-top">
                <div>
                  <strong><?php echo h($pl['name']); ?></strong>
                  <div class="meta">Created <?php echo h($pl['created_at']); ?></div>
                  <?php if (!empty($pl['description'])): ?>
                    <div class="meta">"<?php echo h($pl['description']); ?>"</div>
                  <?php endif; ?>
                </div>
                <a class="btn btn-danger" href="delete_playlist_confirm.php?id=<?php echo h($pl['playlist_id']); ?>">Delete</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <div class="actions">
        <a class="btn btn-secondary" href="dashboard.php">Back to dashboard</a>
      </div>
    </main>
  </div>
</body>
</html>
