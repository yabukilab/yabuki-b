<?php
session_start();

$title = $_POST['title'] ?? 'タイトル不明';
$content = $_POST['content'] ?? '';
$rating = $_POST['rating'] ?? null;

echo "<h2>投稿された感想</h2>";
echo "<strong>作品タイトル：</strong> " . htmlspecialchars($title) . "<br><br>";
echo "<strong>感想：</strong><br>" . nl2br(htmlspecialchars($content)) . "<br><br>";

// 評価の表示
if ($rating !== null) {
    echo "<strong>評価：</strong> ";
    for ($i = 1; $i <= 5; $i++) {
        echo $i <= $rating ? "★" : "☆";
    }
    echo "（{$rating} / 5）";
} else {
    echo "<strong>評価：</strong> 評価なし";
}
?>
