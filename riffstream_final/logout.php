<?php
require __DIR__ . '/common.php';
session_unset();
session_destroy();
header('Location: login.php?msg=' . urlencode('You have been logged out.'));
exit;
?>


