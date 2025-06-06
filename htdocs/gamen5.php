<?php
session_start();
// 仮ログイン：ユーザーID 1
$_SESSION['user_id'] = 1;
$user_id = $_SESSION['user_id'];

$pdo = new PDO('mysql:host=db;dbname=app;charset=utf8', 'root', 'root');

// 感想一覧を取得
$stmt = $pdo->prepare('SELECT * FROM comments WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
</head>
<body>
    <h1>マイページ（これまでに投稿した本の感想）</h1>
    <?php if (count($comments) === 0): ?>
        <p>まだ感想が投稿されていません。</p>
    <?php else: ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <img src="<?= htmlspecialchars($comment['thumbnail']) ?>" alt="cover" style="height:100px;"><br>
                    <strong><?= htmlspecialchars($comment['title']) ?></strong><br>
                    著者：<?= htmlspecialchars($comment['authors']) ?><br>
                    感想：<?= nl2br(htmlspecialchars($comment['comment'])) ?><br>
                    投稿日：<?= htmlspecialchars($comment['created_at']) ?><br>
                    <hr>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
