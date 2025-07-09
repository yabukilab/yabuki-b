
<?php
// セッションスタートが必要なら
session_start();

$title = $_GET['title'] ?? 'タイトル不明';
$reviews = [];

$dbServer = '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

require_once 'db.php';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    exit('データベース接続エラー: ' . htmlspecialchars($e->getMessage()));
}


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // タイトルに一致する感想を取得
    $stmt = $pdo->prepare("
    SELECT users.username, reviews.content
    FROM reviews
    JOIN users ON reviews.user_id = users.id
    WHERE reviews.title = ?
    ORDER BY reviews.created_at DESC
    ");
    $stmt->execute([$title]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB接続エラー: " . htmlspecialchars($e->getMessage()));
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