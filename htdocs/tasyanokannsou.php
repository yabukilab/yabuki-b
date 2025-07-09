<?php
session_start();
require_once 'db.php';  // ここで $db が使えるようになる

$title = $_GET['title'] ?? 'タイトル不明';
$reviews = [];

$user_id = $_SESSION['user_id'] ?? null;  
try {
    // 自分の感想を除外して他人の感想だけ取得
    $stmt = $db->prepare("
        SELECT users.username, reviews.content
        FROM reviews
        JOIN users ON reviews.user_id = users.id
        WHERE reviews.title = ?
          AND reviews.user_id != ?
        ORDER BY reviews.created_at DESC
    ");
    $stmt->execute([$title, $user_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB接続エラー: " . h($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?> の感想一覧</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .user-icon {
      width: 40px;
      height: 40px;
      background-color: #1e90ff;
      color: white;
      border-radius: 50%;
      text-align: center;
      line-height: 40px;
      font-weight: bold;
      font-size: 1.1rem;
    }
    .user-icon-link {
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="mypage-button">
    <a href="mypage.php" class="btn">マイページ</a>
  </div>

  <div class="section">
    <h1 class="accent">「<?= htmlspecialchars($title) ?>」への感想</h1>

    <?php if (empty($reviews)): ?>
      <p>この作品にはまだ感想が投稿されていません。</p>
    <?php else: ?>
      <?php foreach ($reviews as $r): ?>
        <div class="notice-box">
          <div style="display: flex; align-items: center; gap: 12px;">
            <a href="tasyanomypage.php?user=<?= urlencode($r['username']) ?>" class="user-icon-link">
              <div class="user-icon"><?= htmlspecialchars(mb_substr($r['username'], 0, 1)) ?></div>
            </a>
            <div>
              <strong><?= htmlspecialchars($r['username']) ?></strong> さんの感想：
              <p style="margin-top: 6px;"><?= nl2br(htmlspecialchars($r['content'])) ?></p>
            </div>
          </div>
        </div><br>
      <?php endforeach; ?>
    <?php endif; ?>

    <a href="kannsou.php?title=<?= urlencode($title) ?>" class="btn" style="margin-top: 20px;">← 感想を投稿する</a>
  </div>

</body>
</html>
