<?php
session_start();

// ログインしていなければログインページなどへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// セッション変数からユーザー情報を取得
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';

// DB接続（db.php から $pdo を読み込む）
require_once 'db.php';

try {
    // 自分のレビューのみ取得
    $stmt = $db->prepare("SELECT title, rating, content FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("レビュー取得エラー: " . htmlspecialchars($e->getMessage()));
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
            <div>
                <div class="username"><?= htmlspecialchars($username) ?></div>
            </div>
        </div>

        <h1><span class="accent">投稿した作品一覧</span></h1>

        <?php if (empty($reviews)): ?>
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
