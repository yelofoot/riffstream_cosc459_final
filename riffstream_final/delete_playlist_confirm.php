<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

$playlistId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare('SELECT playlist_id, name, description FROM playlists WHERE playlist_id = ? AND user_id = ?');
$stmt->execute([$playlistId, $_SESSION['user_id']]);
$playlist = $stmt->fetch();

if (!$playlist) {
    die('Playlist not found or not owned by you.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $del = $pdo->prepare('DELETE FROM playlists WHERE playlist_id = ? AND user_id = ? LIMIT 1');
    $del->execute([$playlist['playlist_id'], $_SESSION['user_id']]);

    if ($del->rowCount() > 0) {
        header('Location: delete_playlist.php?msg=' . urlencode('Playlist "' . $playlist['name'] . '" deleted.'));
        exit;
    } else {
        $error = 'Unable to delete that playlist. Please try again.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Confirm delete</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Delete playlist</h1>
          <p>Are you sure you want to delete "<?php echo h($playlist['name']); ?>"?</p>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php endif; ?>

      <?php if (!empty($playlist['description'])): ?>
        <div class="note">Description: <?php echo h($playlist['description']); ?></div>
      <?php endif; ?>

      <form method="post" action="delete_playlist_confirm.php?id=<?php echo h($playlist['playlist_id']); ?>">
        <div class="actions">
          <button type="submit" class="btn btn-danger">Yes, delete it</button>
          <a class="btn btn-secondary" href="delete_playlist.php">Cancel</a>
        </div>
      </form>

      <div class="footer">Deletion uses a prepared DELETE statement scoped to your account.</div>
    </main>
  </div>
</body>
</html>
