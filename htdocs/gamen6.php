<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>作品ページ</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .back-button {
      position: fixed;
      left: 20px;
      bottom: 20px;
      padding: 10px 20px;
      background: #ccc;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 0 2px 5px rgb(0 0 0 / 0.5);
    }
    .back-button:hover {
      background: #bbb;
    }
  </style>
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
        // 本当はデータベースから取得するもよし
        $reviews = [
          ['rating' => 4, 'comment' => 'とても面白かった！', 'name' => '山田', 'days' => '2日前'],
          ['rating' => 5, 'comment' => '感動しました！', 'name' => '佐藤', 'days' => '昨日'],
          ['rating' => 3, 'comment' => 'まあまあかな', 'name' => '鈴木', 'days' => '3日前'],
        ];

        foreach ($reviews as $r) : ?>
          <div class="review-card">
            <div class="stars">
              <?= str_repeat('★', $r['rating']); ?>
              <?= str_repeat('☆', 5 - $r['rating']); ?>
            </div>
            <p><?= htmlspecialchars($r['comment']); ?> </p>
            <div class="user-info">
              <div class="icon">F</div>
              <div class="username">
                <?= htmlspecialchars($r['name']); ?>
                <br><span><?= $r['days']; ?> </span>
              </div>
            </div>
            <button class="comment-btn">コメント</button>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- 新しくレビュー投稿欄も付加（ログインしているユーザが投稿） -->
    <section class="post-section">
      <h2>レビューを投稿する</h2>
      <form action="post.php" method="POST">
        <input type="number" name="rating" min="1" max="5" required>
        <textarea name="comment" placeholder="感想も入力して下さい" required></textarea>
        <button type="submit">投稿</button>
      </form>
    </section>

  </main>

  <!-- 戻るボタン -->
  <button class="back-button" onclick="history.back()">戻る</button>

</body>
</html>
