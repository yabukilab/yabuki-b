<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    // ログインしていなければログインページなどへリダイレクト
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'] ?? 'ユーザー';
$user_id = $_SESSION['user_id'];

// DB接続設定
$host = 'localhost';
$dbname = 'mydb';
$user = 'testuser';
$pass = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 自分のレビューのみ取得
    $stmt = $pdo->prepare("SELECT title, rating, content FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    

} catch (PDOException $e) {
    die("DB接続エラー: " . htmlspecialchars($e->getMessage()));
}

// 星表示関数
function printStars($count) {
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        $stars .= $i < $count ? '★' : '☆';
    }
    return $stars;
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="profile-header">
            <div class="avatar"><?= htmlspecialchars(mb_substr($username, 0, 1)) ?></div>
            <div>
                <div class="username"><?= htmlspecialchars($username) ?></div>
            </div>
         </div>

        <h1><span class="accent">投稿した作品一覧</span></h1>

        <?php if (count($reviews) === 0): ?>
            <p>まだ感想は投稿されていません。</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="work-item">
                    <div class="thumbnail"></div>
                    <div class="work-details">
                        <div class="work-title">
                             <?= htmlspecialchars($review['title']) ?>
                             <span class="stars"><?= printStars((int)$review['rating']) ?></span>
                        </div>
                        <div class="comment"><?= nl2br(htmlspecialchars($review['content'])) ?></div>

                    </div>
                </div>
             <?php endforeach; ?>
        <?php endif; ?>

         <div class="back-link">
             <a href="kensaku.php">← トップページへ</a>
         </div>
    </div>

</body>
</html>
