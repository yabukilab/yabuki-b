<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>作品一覧</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- マイページボタン -->
  <div class="mypage-button">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <div class="section">
    <h1 class="accent">投稿された作品一覧</h1>

    <?php
    $titles = []; // [タイトル => ユーザー名]
    $filterTitle = $_GET['title'] ?? null;

    if (file_exists('reviews.txt')) {
      $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
      foreach ($lines as $line) {
        $parts = explode("\t", $line);
        if (count($parts) < 3) continue;
        list($title, $user, $comment) = $parts;
        $titles[$title] = $user; // 最新の投稿者で上書き
      }
    }

    if (empty($titles)) {
      echo "<p>まだ作品が投稿されていません。</p>";
    } else {
      foreach ($titles as $title => $user) {
        if ($filterTitle && $filterTitle !== $title) continue;
        $initial = mb_substr($user, 0, 1);

        echo "<div class='notice-box'>";
        echo "<div style='display: flex; align-items: center; gap: 12px;'>";
        echo "<div class='user-icon'>$initial</div>";
        echo "<div>";
        echo "<strong>" . htmlspecialchars($user) . "</strong> さんが投稿した<br>";
        echo "作品タイトル：<span class='accent'>" . htmlspecialchars($title) . "</span><br>";
        echo "<a href='gamen7.php?title=" . urlencode($title) . "' class='btn' style='margin-top: 10px; display: inline-block;'>感想を見る</a>";
        echo "</div></div></div><br>";
      }
    }
    ?>
  </div>

</body>
</html>
