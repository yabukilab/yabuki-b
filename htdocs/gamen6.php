<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>作品ページ</title>
</head>
<body>

  <!-- マイページボタン -->
  <div class="mypage-button" style="text-align: right; margin-bottom: 20px;">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <h1>作品一覧</h1>

  <?php
  // 仮の作品リスト
  $works = [
    ['id' => 1, 'title' => '作品A'],
    ['id' => 2, 'title' => '作品B'],
    ['id' => 3, 'title' => '作品C'],
  ];

  foreach ($works as $work) {
    echo "<div>";
    echo "<p>{$work['title']}</p>";
    echo "<a href='gamen7.php?work_id={$work['id']}'>感想を見る</a>";
    echo "</div><hr>";
  }
  ?>

</body>
</html>
