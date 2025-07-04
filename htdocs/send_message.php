<?php
session_start();


// テストでユーザーIDを明示的にセット（usersテーブルに存在するIDに変更）
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 3635; // 存在するIDに
    $_SESSION['username'] = 'h.masahiro';
}

// 必須データチェック
if (!isset($_POST['receiver']) || !isset($_POST['message'])) {
    die('必要な情報が不足しています');
}

$self_id = $_SESSION['user_id'];
$message = trim($_POST['message']);
$partner_name = $_POST['receiver'];

if ($message === '') {
    die('メッセージが空です');
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=mydb;charset=utf8mb4", "testuser", "pass");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 送信ユーザーがDBに存在するかチェック
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$self_id]);
    if (!$stmt->fetch()) {
        die('送信ユーザーが存在しません。');
    }

    // 受信ユーザーのIDを取得
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$partner_name]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$partner) {
        die('受信ユーザーが存在しません。');
    }
    $partner_id = $partner['id'];

    // メッセージ登録
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$self_id, $partner_id, $message]);

    // リダイレクト
    header('Location: chat.php?partner=' . urlencode($partner_name));
    exit;

} catch (PDOException $e) {
    die('DBエラー: ' . htmlspecialchars($e->getMessage()));
}
