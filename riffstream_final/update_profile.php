<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

// Fetch current user details for pre-filling the form
$stmt = $pdo->prepare('SELECT user_id, first_name, last_name, email, account_type FROM users WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$me = $stmt->fetch();

if (!$me) {
    die('User not found.');
}

$error = '';
$success = '';

$first_name   = $me['first_name'];
$last_name    = $me['last_name'];
$email        = $me['email'];
$account_type = $me['account_type'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $account_type = $_POST['account_type'] ?? 'Listener';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_pw   = $_POST['confirm_password'] ?? '';

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
        // Ensure the email is unique for another account
        $check = $pdo->prepare('SELECT COUNT(*) AS c FROM users WHERE email = ? AND user_id <> ?');
        $check->execute([$email, $me['user_id']]);
        if (($check->fetch()['c'] ?? 0) > 0) {
            $error = 'That email address is already in use by another account.';
        } else {
            $params = [$first_name, $last_name, $email, $account_type, $me['user_id']];
            $setPassword = '';

            if ($new_password !== '') {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $setPassword = ', password_hash = ?';
                array_splice($params, 4, 0, [$hash]); // insert hash before user_id
            }

            $sql = 'UPDATE users SET first_name = ?, last_name = ?, email = ?, account_type = ?' . $setPassword . ' WHERE user_id = ? LIMIT 1';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $_SESSION['email']        = $email;
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
  <title>RiffStream · Update Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="header-row">
        <img src="images/logo.svg" alt="RiffStream logo" class="logo">
        <div>
          <h1>Edit your profile</h1>
          <p>Update your basic information. Leave password blank to keep your current one.</p>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
      <?php elseif ($success): ?>
        <div class="success"><?php echo h($success); ?></div>
      <?php endif; ?>

      <form method="post" action="update_profile.php" novalidate>
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
            <label for="new_password">New password</label>
            <input class="input" type="password" id="new_password" name="new_password" minlength="8" autocomplete="new-password" placeholder="Leave blank to keep current">
          </div>
          <div class="form-row">
            <label for="confirm_password">Confirm new password</label>
            <input class="input" type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" placeholder="Re-type new password">
          </div>
        </div>

        <div class="actions">
          <button type="submit">Save changes</button>
          <a class="link" href="dashboard.php">Back to dashboard</a>
        </div>
      </form>

      <div class="footer">Profile updates write to the <code>users</code> table using prepared statements.</div>
    </main>
  </div>
</body>
</html>
