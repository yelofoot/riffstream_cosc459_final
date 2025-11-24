<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';

$error = '';
$success = '';

$first_name   = '';
$last_name    = '';
$username     = '';
$email        = '';
$account_type = 'Listener';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $username     = trim($_POST['username'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $password2    = $_POST['password_confirm'] ?? '';
    $account_type = $_POST['account_type'] ?? 'Listener';

    if ($first_name === '' || $last_name === '' || $username === '' ||
        $email === '' || $password === '' || $password2 === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^[A-Za-z0-9_.\\-]{3,30}$/', $username)) {
        $error = 'Username must be 3–30 characters (letters, numbers, _, ., -).';
    } elseif ($password !== $password2) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $username]);
        $row = $stmt->fetch();

        if (($row['c'] ?? 0) > 0) {
            $error = 'A user with that email or username already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                'INSERT INTO users (first_name, last_name, username, email, password_hash, account_type, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([$first_name, $last_name, $username, $email, $hash, $account_type]);

            $_SESSION['user_id']        = $pdo->lastInsertId();
            $_SESSION['username']       = $username;
            $_SESSION['email']          = $email;
            $_SESSION['account_type']   = $account_type;
            $_SESSION['last_login_at']  = date('Y-m-d H:i:s');

            header('Location: dashboard.php?msg=' . urlencode('Welcome to RiffStream! Your account is ready.'));
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Sign up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Create your RiffStream account</h1>
          <p>Sign up to start building playlists or sharing your music.</p>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php endif; ?>

      <form action="signup.php" method="post" novalidate>
        <div class="grid-2">
          <div class="form-row">
            <label for="first_name">First name</label>
            <input class="input" type="text" id="first_name" name="first_name"
                   value="<?php echo h($first_name); ?>" required maxlength="50" autocomplete="given-name">
          </div>
          <div class="form-row">
            <label for="last_name">Last name</label>
            <input class="input" type="text" id="last_name" name="last_name"
                   value="<?php echo h($last_name); ?>" required maxlength="50" autocomplete="family-name">
          </div>
        </div>

        <div class="form-row">
          <label for="username">Username</label>
          <input class="input" type="text" id="username" name="username"
                 value="<?php echo h($username); ?>"
                 required minlength="3" maxlength="30" autocomplete="username">
        </div>

        <div class="form-row">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email"
                 value="<?php echo h($email); ?>"
                 required maxlength="120" autocomplete="email">
        </div>

        <div class="grid-2">
          <div class="form-row">
            <label for="password">Password</label>
            <input class="input" type="password" id="password" name="password"
                   required minlength="8" autocomplete="new-password">
          </div>
          <div class="form-row">
            <label for="password_confirm">Confirm password</label>
            <input class="input" type="password" id="password_confirm" name="password_confirm"
                   required minlength="8" autocomplete="new-password">
          </div>
        </div>

        <div class="form-row">
          <label for="account_type">Account type</label>
          <select id="account_type" name="account_type" class="input" required>
            <option value="Listener" <?php echo $account_type === 'Listener' ? 'selected' : ''; ?>>Listener</option>
            <option value="Artist"   <?php echo $account_type === 'Artist'   ? 'selected' : ''; ?>>Artist</option>
          </select>
        </div>

        <div class="actions">
          <button type="submit">Create account</button>
          <a class="link" href="login.php">Already have an account? Log in</a>
        </div>
      </form>

      <div class="footer">By creating an account you agree to use RiffStream responsibly.</div>
    </main>
  </div>
</body>
</html>

