<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

// Fetch the current user to prefill the form
$stmt = $pdo->prepare('SELECT user_id, first_name, last_name, username, email, account_type FROM users WHERE user_id = ? LIMIT 1');
$stmt = $pdo->prepare('SELECT user_id, first_name, last_name, email, account_type FROM users WHERE user_id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die('User not found.');
}

$error   = '';
$success = '';

$first_name   = $user['first_name'];
$last_name    = $user['last_name'];
$username     = $user['username'];
$email        = $user['email'];
$account_type = $user['account_type'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $username     = trim($_POST['username'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $account_type = $_POST['account_type'] ?? 'Listener';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_pw   = $_POST['confirm_password'] ?? '';

    if ($first_name === '' || $last_name === '' || $username === '' || $email === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($username) > 30) {
        $error = 'Please choose a username of 30 characters or fewer.';
    if ($first_name === '' || $last_name === '' || $email === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!in_array($account_type, ['Listener', 'Artist'], true)) {
        $error = 'Please choose a valid account type.';
    } elseif ($new_password !== '' && $new_password !== $confirm_pw) {
        $error = 'New password and confirmation do not match.';
    } elseif ($new_password !== '' && strlen($new_password) < 8) {
        $error = 'If setting a new password, it must be at least 8 characters.';
    } else {
        // Ensure the email and username are unique for another account
        $check = $pdo->prepare('SELECT COUNT(*) AS c FROM users WHERE (email = ? OR username = ?) AND user_id <> ?');
        $check->execute([$email, $username, $user['user_id']]);
        $count = $check->fetchColumn();

        if ($count > 0) {
            $error = 'That email address or username is already in use by another account.';
        } else {
            $params = [$first_name, $last_name, $username, $email, $account_type];
        // Ensure the email is unique for another account
        $check = $pdo->prepare('SELECT COUNT(*) AS c FROM users WHERE email = ? AND user_id <> ?');
        $check->execute([$email, $user['user_id']]);
        $count = $check->fetchColumn();

        if ($count > 0) {
            $error = 'That email address is already in use by another account.';
        } else {
            $params = [$first_name, $last_name, $email, $account_type];
            $setPassword = '';

            if ($new_password !== '') {
                $setPassword = ', password_hash = ?';
                $params[] = password_hash($new_password, PASSWORD_DEFAULT);
            }

            $params[] = $user['user_id'];

            $sql = 'UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, account_type = ?'
            $sql = 'UPDATE users SET first_name = ?, last_name = ?, email = ?, account_type = ?'
                 . $setPassword
                 . ' WHERE user_id = ? LIMIT 1';

            $update = $pdo->prepare($sql);
            $update->execute($params);

            // Keep session values in sync for the dashboard
            $_SESSION['email']        = $email;
            $_SESSION['username']     = $username;
            $_SESSION['account_type'] = $account_type;

            $success = 'Profile updated successfully.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Edit Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Edit your RiffStream profile</h1>
          <p>Update the details that appear on your dashboard. Changes here keep your account information accurate and up to date.</p>
          <h1>Edit your profile</h1>
          <p>Update your account details. Leave the password blank to keep your current one.</p>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php elseif ($success): ?>
        <div class="success"><?php echo h($success); ?></div>
      <?php endif; ?>

      <form method="post" action="edit_profile.php" novalidate>
        <div class="grid-2">
          <div class="form-row">
            <label for="first_name">First name</label>
            <input class="input" type="text" id="first_name" name="first_name" value="<?php echo h($first_name); ?>" required maxlength="50">
          </div>
          <div class="form-row">
            <label for="last_name">Last name</label>
            <input class="input" type="text" id="last_name" name="last_name" value="<?php echo h($last_name); ?>" required maxlength="50">
          </div>
        </div>

        <div class="form-row">
          <label for="username">Username</label>
          <input class="input" type="text" id="username" name="username" value="<?php echo h($username); ?>" required maxlength="30">
        </div>

        <div class="form-row">
          <label for="email">Email address</label>
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email" value="<?php echo h($email); ?>" required maxlength="120">
        </div>

        <div class="form-row">
          <label for="account_type">Account type</label>
          <select id="account_type" name="account_type" class="input" required>
            <option value="Listener" <?php echo $account_type === 'Listener' ? 'selected' : ''; ?>>Listener</option>
            <option value="Artist" <?php echo $account_type === 'Artist' ? 'selected' : ''; ?>>Artist</option>
          </select>
        </div>

        <div class="grid-2">
          <div class="form-row">
            <label for="new_password">New password (leave blank to keep your current password)</label>
            <label for="new_password">New password</label>
            <input class="input" type="password" id="new_password" name="new_password" minlength="8" autocomplete="new-password" placeholder="Leave blank to keep current">
          </div>
          <div class="form-row">
            <label for="confirm_password">Confirm new password</label>
            <input class="input" type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" placeholder="Re-type new password">
          </div>
        </div>

        <div class="actions">
          <button class="btn" type="submit">Save changes</button>
          <button type="submit">Save changes</button>
          <a class="link" href="dashboard.php">Back to dashboard</a>
        </div>
      </form>

      <div class="footer">Profile updates use prepared statements against the <code>users</code> table.</div>
    </main>
  </div>
</body>
</html>
