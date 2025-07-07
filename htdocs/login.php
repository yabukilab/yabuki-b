<?php
session_start();
var_dump($_POST);
require_once 'db.php';  // DB接続（$db 変数）

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: index.php?error=1');
    exit;
}

try {
    $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: kensaku.php');
        exit;
    } else {
        header('Location: index.php?error=1');
        exit;
    }

} catch (PDOException $e) {
    echo "ログイン処理エラー: " . htmlspecialchars($e->getMessage());
    exit;
}
