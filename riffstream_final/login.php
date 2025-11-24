<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';

$error   = '';
$email   = '';
$info_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $error = 'Please enter your email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT user_id, username, email, password_hash, account_type
                               FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($pass, $user['password_hash'])) {
            $error = 'Invalid email or password.';
        } else {
            $_SESSION['user_id']        = $user['user_id'];
            $_SESSION['username']       = $user['username'];
            $_SESSION['email']          = $user['email'];
            $_SESSION['account_type']   = $user['account_type'];
            $_SESSION['last_login_at']  = date('Y-m-d H:i:s');

            header('Location: dashboard.php?msg=' . urlencode('Logged in successfully.'));
            exit;
        }
    }
} elseif (isset($_GET['msg'])) {
    $info_msg = $_GET['msg'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Log in</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Welcome back</h1>
          <p>Log in to continue to your RiffStream dashboard.</p>
        </div>
      </div>

      <?php if ($info_msg): ?>
        <div class="success"><?php echo h($info_msg); ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php endif; ?>

      <form action="login.php" method="post" novalidate>
        <div class="form-row">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email"
                 value="<?php echo h($email); ?>"
                 required autocomplete="email">
        </div>

        <div class="form-row">
          <label for="password">Password</label>
          <input class="input" type="password" id="password" name="password"
                 required autocomplete="current-password">
        </div>

        <div class="actions">
          <button type="submit">Log in</button>
          <a class="link" href="signup.php">Create a new account</a>
        </div>
      </form>

      <div class="footer">Make sure MAMP (Apache &amp; MySQL) is running before logging in.</div>
    </main>
  </div>
</body>
</html>

