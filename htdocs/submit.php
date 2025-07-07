<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  die("ログインしていません。");
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'] ?? 'タイトル不明';
$content = trim($_POST['content'] ?? '');
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;

$errorMessage = '';
$success = false;

if ($content === '') {
  $errorMessage = "感想は必ず入力してください。";
} elseif ($rating !== null && ($rating < 1 || $rating > 5)) {
  $errorMessage = "評価は1～5の整数で入力してください。";
} 