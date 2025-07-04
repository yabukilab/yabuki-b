
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("ログインしていません。"); // またはログインページにリダイレクト
}

$user_id = $_SESSION['user_id'];

$title = $_POST['title'] ?? 'タイトル不明';
$content = $_POST['content'] ?? '';
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;


// DB接続情報
$host = 'localhost'; 
$dbname = 'mydb';
$user = 'testuser';
$pass = 'pass';

$success = false;
$errorMessage = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    $stmt = $pdo->prepare("INSERT INTO reviews (title, content, rating, user_id) VALUES (?, ?, ?, ?)"); 
    $stmt->execute([$title, $content, $rating, $user_id]);

    $success = true;

} catch (PDOException $e) {
    $errorMessage = "DB接続・登録エラー: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>感想投稿完了</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>投稿完了</h1>

    <?php if ($success): ?>
        <div class="notice-box">
            <p>レビューの登録が完了しました！</p>
        </div>

        <h2><?= htmlspecialchars($title) ?></h2>
        <p><strong>感想:</strong><br><?= nl2br(htmlspecialchars($content)) ?></p>
        <p><strong>評価:</strong>
            <?php if ($rating !== null): ?>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?= $i <= $rating ? "★" : "☆" ?>
                <?php endfor; ?>
                （<?= $rating ?> / 5）
            <?php else: ?>
                評価なし
            <?php endif; ?>
        </p>

        <div style="margin-top: 30px; text-align: center;">
            <a href="mypage.php" class="btn">マイページへ戻る</a>
            <a href="tasyanokannsou.php?title=<?= urlencode($title) ?>" class="btn">他の人の感想を見る</a>
        </div>
    <?php else: ?>
        <div class="notice-box" style="border-left-color: #ff4d4d; background-color: #ffecec;">
            <p><?= $errorMessage ?></p>
        </div>
        <div style="margin-top: 30px; text-align: center;">
            <a href="javascript:history.back()" class="btn">戻る</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
