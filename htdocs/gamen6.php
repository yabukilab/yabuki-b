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
    .card {
      border: 1px solid #ccc;
      padding: 12px;
      margin-bottom: 12px;
      border-radius: 6px;
    }
    .user-icon {
      display: inline-block;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: #888;
      color: #fff;
      text-align: center;
      line-height: 36px;
      margin-right: 8px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <!-- マイページボタン -->
  <div class="mypage-button" style="text-align: right; margin-bottom: 20px;">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <h1>投稿された作品一覧</h1>

  <?php
  $titles = []; // [タイトル => 投稿者名]
  $filterTitle = $_GET['title'] ?? null;

  if (file_exists('reviews.txt')) {
    $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
      $parts = explode("\t", $line);
      if (count($parts) < 3) continue;
      list($title, $user, $comment) = $parts;

      // 最初の投稿者を表示（または上書きして最新投稿者）
      $titles[$title] = $user;
    }
  }

  if (empty($titles)) {
    echo "<p>まだ作品が投稿されていません。</p>";
  } else {
    foreach ($titles as $title => $user) {
      if ($filterTitle && $filterTitle !== $title) continue;

      $initial = mb_substr($user, 0, 1);

      echo "<div class='card'>";
      echo "<div><span class='user-icon'>" . htmlspecialchars($initial) . "</span><strong>" . htmlspecialchars($user) . "</strong> さんの投稿</div>";
      echo "<p>作品名：<strong>" . htmlspecialchars($title) . "</strong></p>";
      echo "<a href='gamen7.php?title=" . urlencode($title) . "' class='btn'>感想を見る</a>";
      echo "</div>";
    }
  }
  ?>

</body>
</html>
