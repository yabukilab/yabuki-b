<?php

session_start();

$dbServer = '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    exit('データベース接続エラー: ' . htmlspecialchars($e->getMessage()));
}

// POSTデータを取得
$userId = $_POST['userid']; 
$password = $_POST['password'];

// 入力チェック（サーバー側でも）
if (empty($userId) || empty($password)) {
    exit('IDまたはパスワードが未入力です');
}

// パスワードをハッシュ化
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// 重複チェック（ユーザーIDが既に存在しないか）
$sql = "SELECT COUNT(*) FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
if ($stmt->fetchColumn() > 0) {
    exit('このIDはすでに使用されています');
}

// 登録処理

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([$userId, $email, $passwordHash]);

if ($result) {
    $_SESSION['user_id'] = $pdo->lastInsertId();  // auto_increment ID
    $_SESSION['username'] = $userId;              // 入力されたユーザー名

    header("Location: kensaku.php");               // ログイン後の画面へ
    exit();
} else {
    echo '<div style="font-size:6.0em; color:red; margin-top:1em;">登録に失敗しました</div>';
}
?>


