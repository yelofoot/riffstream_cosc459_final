<?php
// common.php — helpers shared by all pages
session_start();

function h($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php?msg=' . urlencode('Please log in to continue.'));
        exit;
    }
}
?>


