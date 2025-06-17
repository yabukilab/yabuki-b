<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>作品ページ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <div class="navbar">Mypage</div>
  </header>

  <main>
    <h1 class="title">作品名</h1>

    <section class="review-section">
      <h2>他者の感想</h2>
      
      <div class="review-cards">
        <?php
        // --- 仮レビューリスト ---
        $reviews = [
          ['rating' => 4, 'comment' => 'とても面白かった！', 'name' => '山田', 'days' => '2日前'],
          ['rating' => 5, 'comment' => '感動しました！', 'name' => '佐藤', 'days' => '昨日'],
          ['rating' => 3, 'comment' => 'まあまあかな', 'name' => '鈴木', 'days' => '3日前'],
        ];

        // --- レビューカード表示ループ ---
        foreach ($reviews as $r) {
          echo '<div class="review-card">';
          echo '<div class="stars">' . str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) . '</div>';
          echo '<p>' . htmlspecialchars($r['comment']) . '</p>';
          echo '<div class="user-info">';
          echo '<div class="icon">F</div>';
          echo '<div class="username">' . htmlspecialchars($r['name']) . '<br><span>' . $r['days'] . '</span></div>';
          echo '</div>';
          echo '<button class="comment-btn">コメント</button>';
          echo '</div>';
        }
        ?>
      </div>
    </section>
  </main>

  <footer>
    <button onclick="history.back()">戻る</button>
  </footer>

</body>
</html>