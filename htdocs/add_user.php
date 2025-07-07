<?php
require_once 'db.php';

// 入力したいユーザー情報
$username = 'testuser1';
$email = 'test@example.com';
$password = 'pass1234';

// パスワードをハッシュ化
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
    echo "✅ ユーザー登録に成功しました！";
} catch (PDOException $e) {
    echo "❌ ユーザー登録に失敗: " . htmlspecialchars($e->getMessage());
}
