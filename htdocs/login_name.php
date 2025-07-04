<?php
session_start();

$email = $_POST['email'] ?? 'guest@example.com';
$username = explode('@', $email)[0]; // 仮のユーザー名
$user_id = rand(1000, 9999); // ランダムIDを仮に割り当て

// セッションに保存（ログインした状態を保持）
$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;

// 検索ページへ遷移
header("Location: kensaku.php");
exit;
