<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

// Pull account type to verify artist access
$stmt = $pdo->prepare('SELECT account_type FROM users WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die('User not found.');
}

if ($user['account_type'] !== 'Artist') {
    header('Location: dashboard.php?msg=' . urlencode('Tracks are available to artist accounts only. Switch to an Artist account to add music.'));
    exit;
}

$error = '';
$success = '';
$title = '';
$genre = '';
$album_name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title'] ?? '');
    $genre      = trim($_POST['genre'] ?? '');
    $album_name = trim($_POST['album_name'] ?? '');

    if ($title === '') {
        $error = 'Please provide a track title.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO tracks (user_id, title, genre, album_name, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$_SESSION['user_id'], $title, $genre ?: null, $album_name ?: null]);

        $success = 'Track added to your catalog.';
        $title = $genre = $album_name = '';
    }
}

$listStmt = $pdo->prepare('SELECT title, genre, album_name, created_at FROM tracks WHERE user_id = ? ORDER BY created_at DESC');
$listStmt->execute([$_SESSION['user_id']]);
$tracks = $listStmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Add a track</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <?php $currentUser = ['account_type' => $user['account_type']]; include __DIR__ . '/navbar.php'; ?>
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Add a track</h1>
          <p>Simple metadata entry for artists. File upload is optional and stored later.</p>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php elseif ($success): ?>
        <div class="success"><?php echo h($success); ?></div>
      <?php endif; ?>

      <form method="post" action="add_track.php" enctype="multipart/form-data" novalidate>
        <div class="form-row">
          <label for="title">Track title</label>
          <input class="input" type="text" id="title" name="title" value="<?php echo h($title); ?>" required maxlength="150">
        </div>

        <div class="grid-2">
          <div class="form-row">
            <label for="genre">Genre</label>
            <input class="input" type="text" id="genre" name="genre" value="<?php echo h($genre); ?>" maxlength="80" placeholder="e.g., Synthwave">
          </div>
          <div class="form-row">
            <label for="album_name">Album/EP name</label>
            <input class="input" type="text" id="album_name" name="album_name" value="<?php echo h($album_name); ?>" maxlength="150" placeholder="Optional">
          </div>
        </div>

        <div class="form-row">
          <label for="audio_file">Audio file (optional placeholder)</label>
          <input class="input" type="file" id="audio_file" name="audio_file" accept="audio/*">
          <div class="meta">File upload is shown for UI completeness; this demo does not store the file.</div>
        </div>

        <div class="actions">
          <button type="submit">Save track</button>
          <a class="link" href="dashboard.php">Back to dashboard</a>
        </div>
      </form>

      <h2 style="margin-top:24px;">Your uploaded tracks</h2>
      <?php if (empty($tracks)): ?>
        <div class="note">No tracks yet. Add one above to start building your catalog.</div>
      <?php else: ?>
        <ul class="stacked-list">
          <?php foreach ($tracks as $t): ?>
            <li>
              <div class="item-top">
                <div>
                  <strong><?php echo h($t['title']); ?></strong>
                  <div class="meta">Added <?php echo h($t['created_at']); ?></div>
                  <div class="meta">
                    <?php
                      $bits = [];
                      if ($t['genre'])      { $bits[] = 'Genre: ' . h($t['genre']); }
                      if ($t['album_name']) { $bits[] = 'Album: ' . h($t['album_name']); }
                      echo $bits ? implode(' · ', $bits) : '—';
                    ?>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <div class="footer">Tracks are stored in the <code>tracks</code> table and filtered by your artist account.</div>
    </main>
  </div>
</body>
</html>
