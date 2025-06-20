<?php
$title = $_GET['title'] ?? '不明な作品';

$reviews = [];

if (file_exists('reviews.txt')) {
  $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
  foreach ($lines as $line) {
    list($t, $comment) = explode("\t", $line, 2);
    if ($t === $title) {
      $reviews[] = $comment;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?> の感想</title>
  <style>
    .btn {
      padding: 6px 12px;
      background-color: #337ab7;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
  </style>
</head>
<body>

  <!-- マイページボタン -->
  <div class="mypage-button" style="text-align: right; margin-bottom: 20px;">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <h1><?= htmlspecialchars($title) ?> の感想一覧</h1>

  <?php if (empty($reviews)): ?>
    <p>まだ感想がありません。</p>
  <?php else: ?>
    <?php foreach ($reviews as $c): ?>
      <div>
        <p><?= nl2br(htmlspecialchars($c)) ?></p>
        <hr>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <a href="gamen6.php">← 作品一覧に戻る</a>

</body>
</html>
