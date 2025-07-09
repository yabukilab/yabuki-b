<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$review_id = $_GET['id'] ?? null;

if (!$review_id) {
    die('IDが指定されていません。');
}

// 自分のレビューのみ削除
$stmt = $db->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
$stmt->execute([$review_id, $user_id]);

header('Location: mypage.php');
exit;
