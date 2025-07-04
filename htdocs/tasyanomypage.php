<?php
session_start();

// ユーザー名（username）を取得
$username = $_GET['user'] ?? '';
$reviews = [];

if (empty($username)) {
    die('ユーザーが指定されていません。');
}

// DB接続設定
$host = 'localhost';
$dbname = 'mydb';
$user = 'testuser';
$pass = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // username → id を取得
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userRow) {
        die('指定されたユーザーは存在しません。');
    }

    $user_id = $userRow['id'];

    // 該当ユーザーのレビュー取得
    $stmt = $pdo->prepare("SELECT title, content, rating, created_at FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
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
    <title><?= htmlspecialchars($username) ?> さんの投稿一覧</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .section {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .notice-box {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #1e90ff;
            border-radius: 5px;
        }

        .mypage-button {
            position: fixed;
            top: 20px;
            left: 20px;
        }

        .btn {
            background: #1e90ff;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #187bcd;
        }

        .accent {
            color: #1e90ff;
        }
    </style>
</head>
<body>

<div class="mypage-button">
    <a href="mypage.php" class="btn">マイページに戻る</a>
    <a href="chat.php?partner=<?= urlencode($username) ?>" class="btn">このユーザーとチャットする</a>

</div>


<div class="section">
    <h1 class="accent"><?= htmlspecialchars($username) ?> さんの感想一覧</h1>

    <?php if (empty($reviews)): ?>
        <p>このユーザーの感想はまだありません。</p>
    <?php else: ?>
        <?php foreach ($reviews as $r): ?>
            <div class="notice-box">
                <strong>作品タイトル：</strong><?= htmlspecialchars($r['title']) ?><br>
                <strong>評価：</strong><?= printStars((int)$r['rating']) ?><br>
                <strong>投稿日時：</strong><?= htmlspecialchars($r['created_at']) ?><br>
                <p style="margin-top: 8px;"><?= nl2br(htmlspecialchars($r['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
