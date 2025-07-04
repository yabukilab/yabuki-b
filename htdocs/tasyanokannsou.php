<?php
$title = $_GET['title'] ?? 'タイトル不明';
$reviews = [];

if (file_exists('reviews.txt')) {
  $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
  foreach ($lines as $line) {
    $parts = explode("\t", $line);
    if (count($parts) < 3) continue;
    list($t, $user, $comment) = $parts;
    if ($t === $title) {
      $reviews[] = ['user' => $user, 'comment' => $comment];
    }
  }
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
            <a href="gamen7.php?user=<?= urlencode($r['user']) ?>" class="user-icon-link">
              <div class="user-icon"><?= htmlspecialchars(mb_substr($r['user'], 0, 1)) ?></div>
            </a>
            <div>
              <strong><?= htmlspecialchars($r['user']) ?></strong> さんの感想：
              <p style="margin-top: 6px;"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
            </div>
          </div>
        </div><br>
      <?php endforeach; ?>
    <?php endif; ?>

    <a href="gamen5.php?title=<?= urlencode($title) ?>" class="btn" style="margin-top: 20px;">← 感想を投稿する</a>
  </div>

</body>
</html>
