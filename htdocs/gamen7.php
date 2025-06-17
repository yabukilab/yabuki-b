v<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>他者の感想</title>
</head>
<body>

  <header>
    <h1>Mypage</h1>
  </header>

  <main>
    <h1>他者の感想</h1>

    <section>
      <h2>他者の感想</h2>

      <?php
      // --- 仮レビューリスト --- 
      $reviews = [
        ['rating' => 4, 'comment' => 'とても面白かった！', 'name' => '山田', 'days' => '2日前',],
        ['rating' => 5, 'comment' => '感動しました！', 'name' => '佐藤', 'days' => '昨日',],
        ['rating' => 3, 'comment' => 'まあまあかな', 'name' => '鈴木', 'days' => '3日前',],
      ];

      foreach ($reviews as $r) {
  echo "<div>";
  echo "<div>評価: " . str_repeat("★", $r['rating']) . str_repeat("☆", 5 - $r['rating']) . "</div>";
  echo "<p>感想: " . htmlspecialchars($r['comment'], ENT_QUOTES, 'UTF-8') . "</p>";
  echo "<div>ユーザ: " . htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') . " (" . $r['days'] . ")</div>";
  echo "<form action='comment.php' method='POST'>";
  echo "<input type='hidden' name='reviewId' value='" . $r['id'] . "'>";
  echo "<input type='submit' value='コメント'>";
  echo "</form>";
  echo "<hr>";
}
      ?>
    </section>

    <section>
      <h3>実装にあたっての注意事項</h3>
      <ul>
        <li>作品に対する他者の感想と何日前に書いたのかを表示</li>
        <li>その感想に対する評価とコメントボタンを表示</li>
        <li>アイコンを押すことで他者ページに移動可能</li>
      </ul>
    </section>

  </main>

  <footer>
    <button onclick="history.back()">戻る</button>
  </footer>

</body>
</html>