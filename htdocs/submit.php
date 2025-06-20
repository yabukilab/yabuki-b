
<?php
session_start();

$title = $_POST['title'] ?? 'タイトル不明';
$content = $_POST['content'] ?? '';
$rating = $_POST['rating'] ?? null;

// DB接続情報（例）
$host = 'localhost';
$dbname = 'mydb';
$user = 'testuser';
$pass = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO reviews (title, content, rating) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $rating]);

    echo "<p>レビューの登録が完了しました。</p>";

} catch (PDOException $e) {
    echo "DB接続・登録エラー: " . htmlspecialchars($e->getMessage());
    exit;
}

echo "<h2>投稿された感想</h2>";
echo "<strong>作品タイトル：</strong> " . htmlspecialchars($title) . "<br><br>";
echo "<strong>感想：</strong><br>" . nl2br(htmlspecialchars($content)) . "<br><br>";

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
