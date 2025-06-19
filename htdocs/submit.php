<?php
session_start();

$title = $_POST['title'] ?? 'タイトル不明';
$content = $_POST['content'] ?? '';

echo "<h2>投稿された感想</h2>";
echo "<strong>作品タイトル：</strong> " . htmlspecialchars($title) . "<br><br>";
echo "<strong>感想：</strong><br>" . nl2br(htmlspecialchars($content));
