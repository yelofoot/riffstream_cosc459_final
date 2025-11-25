<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $delete = $pdo->prepare('DELETE FROM users WHERE user_id = ? LIMIT 1');
        $delete->execute([$_SESSION['user_id']]);
        $pdo->commit();

        session_unset();
        session_destroy();

        header('Location: login.php?msg=' . urlencode('Your account has been deleted successfully.'));
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = 'We could not delete your account right now. Please try again.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Delete Account</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Delete your RiffStream account</h1>
          <p>Deleting your account will remove your profile from this project and sign you out. This action cannot be undone.</p>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php endif; ?>

      <div class="note">Are you sure you want to continue?</div>

      <form method="post" action="delete_account.php" class="actions">
        <button type="submit" class="btn btn-danger">Yes, delete my account</button>
        <a class="link" href="dashboard.php">Cancel and go back to dashboard</a>
      </form>
    </main>
  </div>
</body>
</html>
