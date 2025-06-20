<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>作品一覧</title>
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

  <h1>投稿された作品</h1>

  <?php
  $titles = [];

  if (file_exists('reviews.txt')) {
    $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
      list($title, ) = explode("\t", $line, 2);
      $titles[$title] = true; // 重複排除
    }
  }

  if (empty($titles)) {
    echo "<p>まだ作品が投稿されていません。</p>";
  } else {
    foreach (array_keys($titles) as $title) {
      echo "<div>";
      echo "<p>" . htmlspecialchars($title) . "</p>";
      echo "<a href='gamen7.php?title=" . urlencode($title) . "'>感想を見る</a>";
      echo "</div><hr>";
    }
  }
  ?>

</body>
</html>
