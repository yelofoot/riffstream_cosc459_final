<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

// Create a per-session CSRF token for account deletion.
if (empty($_SESSION['csrf_token_delete_account'])) {
    $_SESSION['csrf_token_delete_account'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token_delete_account'], $token)) {
        $error = 'Invalid request. Please try again from the dashboard.';
    } else {
        $pdo->beginTransaction();
        try {
            $delete = $pdo->prepare('DELETE FROM users WHERE user_id = ? LIMIT 1');
            $delete->execute([$_SESSION['user_id']]);
            $pdo->commit();

            session_unset();
            session_destroy();

            unset($_SESSION['csrf_token_delete_account']);
            header('Location: login.php?msg=' . urlencode('Your account has been deleted successfully.'));
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'We could not delete your account right now. Please try again.';
        }
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
        <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token_delete_account']); ?>">
        <button type="submit" class="btn btn-danger">Yes, delete my account</button>
        <a class="link" href="dashboard.php">Cancel and go back to dashboard</a>
      </form>
    </main>
  </div>
</body>
</html>
