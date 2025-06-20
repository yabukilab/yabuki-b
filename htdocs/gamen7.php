<?php
$workId = $_GET['work_id'] ?? null;

// 仮レビューリスト（DBの代わり）
$reviews = [
  1 => [
    ['rating' => 5, 'comment' => '最高！', 'name' => '佐藤', 'days' => '昨日'],
  ],
  2 => [
    ['rating' => 3, 'comment' => '普通かな', 'name' => '鈴木', 'days' => '2日前'],
  ],
  3 => [
    ['rating' => 4, 'comment' => '意外と良かった', 'name' => '田中', 'days' => '3日前'],
  ],
];

$selectedReviews = $reviews[$workId] ?? [];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>他者の感想</title>
</head>
<body>

  <!-- マイページボタン -->
  <div class="mypage-button" style="text-align: right; margin-bottom: 20px;">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <h1>作品ID: <?php echo htmlspecialchars($workId); ?> に対する感想</h1>

  <?php if (empty($selectedReviews)): ?>
    <p>感想はまだありません。</p>
  <?php else: ?>
    <?php foreach ($selectedReviews as $r): ?>
      <div>
        <p>評価: <?php echo str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']); ?></p>
        <p>感想: <?php echo htmlspecialchars($r['comment'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>ユーザ: <?php echo htmlspecialchars($r['name']); ?>（<?php echo $r['days']; ?>）</p>
        <form action="comment.php" method="POST">
          <input type="hidden" name="reviewId" value="">
          <input type="submit" value="コメント">
        </form>
        <hr>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <a href="gamen6.php">← 作品一覧に戻る</a>

</body>
</html>
