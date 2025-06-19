<div class="section">

  <!-- マイページボタン -->
  <div class="mypage-button" style="text-align: right; margin-bottom: 20px;">
    <a href="gamen4.php" class="btn">マイページ</a>
  </div>

  <h1>感想を投稿する</h1>
  <h2>作品タイトル：<?= htmlspecialchars($title) ?></h2>

  <form action="post.php" method="POST" class="form-wrapper">
    <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">

    <label for="comment">感想</label>
    <textarea id="comment" name="comment" rows="10" placeholder="ここに感想を入力してください" required></textarea>

    <label for="rating">評価 (1～5)</label>
    <input id="rating" name="rating" type="number" min="1" max="5" required>

    <button type="submit" class="btn">投稿</button>
  </form>

  <form action="index.php" method="GET" style="margin-top: 20px;">
    <button type="submit" class="btn">他の人の感想を見る</button>
  </form>

</div>
