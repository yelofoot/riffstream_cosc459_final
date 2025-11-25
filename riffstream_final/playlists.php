<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

$playlists = [];
$load_error = '';

try {
    $stmt = $pdo->prepare('SELECT name, created_at FROM playlists WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->execute([$_SESSION['user_id']]);
    $playlists = $stmt->fetchAll();
} catch (Exception $e) {
    $load_error = 'Playlists are not available yet in this environment.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · My Playlists</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>My Playlists</h1>
          <p>Everything you curate will appear here as playlist features roll out.</p>
        </div>
      </div>

      <?php if ($load_error): ?>
        <div class="error"><?php echo h($load_error); ?></div>
      <?php elseif (empty($playlists)): ?>
        <div class="note">You don’t have any playlists yet. Once playlist features are implemented, they will appear here.</div>
      <?php else: ?>
        <ul class="stacked-list" aria-label="Your playlists">
          <?php foreach ($playlists as $pl): ?>
            <li>
              <div class="item-top">
                <div class="qa-item-title"><?php echo h($pl['name']); ?></div>
                <div class="meta">Created <?php echo h(date('M j, Y', strtotime($pl['created_at']))); ?></div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <div class="actions">
        <a class="btn" href="dashboard.php">Back to dashboard</a>
      </div>
    </main>
  </div>
</body>
</html>
