<?php
session_start();
require_once 'db.php';

$title = $_GET['q'] ?? '';
$title = trim($title);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= h($title) ?> の検索結果</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>「<?= h($title) ?>」の検索結果</h1>
    <!-- ここにAPI結果や検索結果の表示を記述 -->
    <!-- 省略: 必要に応じて追加 -->
  </div>
</body>
</html>
