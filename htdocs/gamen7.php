<?php
$user = $_GET['user'] ?? '未指定';
$reviews = [];

if (file_exists('reviews.txt')) {
  $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
  foreach ($lines as $line) {
    $parts = explode("\t", $line);
    if (count($parts) < 3) continue;
    list($title, $u, $comment) = $parts;
    if ($u === $user) {
      $reviews[] = ['title' => $title, 'comment' => $comment];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($user) ?> さんの感想一覧</title>
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
  </style>
</head>
<body>

  <div class="mypage-button">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <div class="section">
    <h1 class="accent"><?= htmlspecialchars($user) ?> さんの感想</h1>

    <?php if (empty($reviews)): ?>
      <p>このユーザーの感想はまだありません。</p>
    <?php else: ?>
      <?php foreach ($reviews as $r): ?>
        <div class="notice-box">
          <strong>作品タイトル：</strong><?= htmlspecialchars($r['title']) ?><br>
          <p style="margin-top: 6px;"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
        </div><br>
      <?php endforeach; ?>
    <?php endif; ?>

    <a href="gamen6.php?title=<?= urlencode($reviews[0]['title'] ?? '') ?>" class="btn">← 感想一覧に戻る</a>
  </div>

</body>
</html>
