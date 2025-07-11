<?php
session_start();
require_once 'db.php';

$books = [];
$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $perPage;
$totalItems = 0;
$title = $_GET['q'] ?? '検索結果';

if (!empty($_GET['q'])) {
    $keyword = $_GET['q'];
    $query = urlencode("inauthor:{$keyword}+OR+intitle:{$keyword}");
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&startIndex={$startIndex}&maxResults={$perPage}&orderBy=newest";

    $json = @file_get_contents($url);
    $data = json_decode($json, true);

    if (!empty($data['items'])) {
        $books = $data['items'];
        $totalItems = $data['totalItems'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?> の検索結果</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .btn {
      display: inline-block;
      padding: 6px 12px;
      margin: 5px 4px;
      font-size: 14px;
      text-align: center;
      color: #fff;
      background-color: #007bff;
      border-radius: 4px;
      text-decoration: none;
    }
    .btn.disabled {
      background-color: #ccc;
      pointer-events: none;
      color: #666;
    }
  </style>
</head>
<body>
  <div class="section">
    <h1>検索結果</h1>

    <?php if (isset($_GET['q']) && trim($_GET['q']) !== ''): ?>
      <div class="author-info">
        <div>
          <strong>検索キーワード:</strong> <span class="accent"><?= htmlspecialchars($_GET['q']) ?></span>
        </div>
      </div>

      <?php if (empty($books)): ?>
        <div class="notice-box">作品が見つかりませんでした。</div>
      <?php else: ?>
        <div class="book-list">
          <?php foreach ($books as $book): ?>
            <?php
              $title = $book['volumeInfo']['title'] ?? 'タイトル不明';
              $image = $book['volumeInfo']['imageLinks']['thumbnail'] ?? 'https://via.placeholder.com/60x90?text=No+Image';
            ?>
            <div class="book-card">
              <img src="<?= htmlspecialchars($image) ?>" alt="Book cover">
              <div class="book-title">
                <a href="kannsou.php?title=<?= urlencode($title) ?>&q=<?= urlencode($_GET['q']) ?>">
                  <?= htmlspecialchars($title) ?>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="pagination">
          <?php
            $prevPage = $page - 1;
            $nextPage = $page + 1;
            $hasNext = $startIndex + $perPage < $totalItems;
          ?>
          <?php if ($page > 1): ?>
            <a href="?q=<?= urlencode($_GET['q']) ?>&page=<?= $prevPage ?>" class="btn">← 前へ</a>
          <?php else: ?>
            <span class="btn disabled">← 前へ</span>
          <?php endif; ?>

          <?php if ($hasNext): ?>
            <a href="?q=<?= urlencode($_GET['q']) ?>&page=<?= $nextPage ?>" class="btn">次へ →</a>
          <?php else: ?>
            <span class="btn disabled">次へ →</span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="notice-box">検索キーワードが指定されていません。</div>
    <?php endif; ?>
  </div>
</body>
</html>
