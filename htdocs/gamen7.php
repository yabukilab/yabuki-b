<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>他者の感想</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="section">
    <div class="mypage-button" style="text-align: right;">
      <a href="gamen4.php" class="btn">マイページ</a>
    </div>

    <h1>他者の感想</h1>

    <?php
    // 仮レビューリスト
    $reviews = [
      ['id' => 1, 'rating' => 4, 'comment' => 'とても面白かった！', 'name' => '山田', 'days' => '2日前'],
      ['id' => 2, 'rating' => 5, 'comment' => '感動しました！', 'name' => '佐藤', 'days' => '昨日'],
      ['id' => 3, 'rating' => 3, 'comment' => 'まあまあかな', 'name' => '鈴木', 'days' => '3日前'],
    ];

    foreach ($reviews as $r): ?>
      <div class="review-card" style="border: 1px solid #ddd; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
        <div style="font-size: 1.2rem; margin-bottom: 8px;">
          <?= str_repeat("★", $r['rating']) . str_repeat("☆", 5 - $r['rating']) ?>
        </div>
        <p style="margin-bottom: 8px;">感想: <?= htmlspecialchars($r['comment'], ENT_QUOTES, 'UTF-8') ?></p>
        <div class="user-info" style="display: flex; align-items: center;">
          <a href="user.php?name=<?= urlencode($r['name']) ?>" style="margin-right: 10px; text-decoration: none;">
            <div class="icon" style="background:#1e90ff; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
              <?= mb_substr($r['name'], 0, 1) ?>
            </div>
          </a>
          <div><?= htmlspecialchars($r['name']) ?>（<?= $r['days'] ?>）</div>
        </div>

        <form action="comment.php" method="POST" style="margin-top: 10px;">
          <input type="hidden" name="reviewId" value="<?= $r['id'] ?>">
          <button type="submit" class="btn">コメント</button>
        </form>
      </div>
    <?php endforeach; ?>

    <div class="notice-box">
      <h3>実装にあたっての注意事項</h3>
      <ul>
        <li>作品に対する他者の感想と何日前に書いたのかを表示</li>
        <li>その感想に対する評価とコメントボタンを表示</li>
        <li>アイコンを押すことで他者ページに移動可能</li>
      </ul>
    </div>

    <div style="margin-top: 30px; text-align: left;">
      <button onclick="history.back()" class="btn">戻る</button>
    </div>

  </div>

</body>
</html>
