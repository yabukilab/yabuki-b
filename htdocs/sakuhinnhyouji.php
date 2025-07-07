<?php
session_start();
require_once 'db.php';
$books = [];
$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $perPage;
$totalItems = 0;

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
  <title><?= h($title) ?> の検索結果</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>検索結果</h1>
    <!-- ここにAPI結果や検索結果の表示を記述 -->
    <?php if (!empty($_GET['q'])): ?>
        <div class="author-info">
            <div class="author-icon"><?= htmlspecialchars(mb_substr($_GET['q'], 0, 1)) ?></div>
            <div>
                <div><strong>検索キーワード:</strong> <span class="accent"><?= htmlspecialchars($_GET['q']) ?></span></div>
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
                            <a href="kannsou.php?title=<?= urlencode($title) ?>" style="text-decoration: none; color: #1e90ff;">
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
                    <a href="?q=<?= urlencode($_GET['q']) ?>&page=<?= $prevPage ?>">← 前へ</a>
                <?php else: ?>
                    <a class="disabled">← 前へ</a>
                <?php endif; ?>

                <?php if ($hasNext): ?>
                    <a href="?q=<?= urlencode($_GET['q']) ?>&page=<?= $nextPage ?>">次へ →</a>
                <?php else: ?>
                    <a class="disabled">次へ →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="notice-box">検索キーワードが指定されていません。</div>
    <?php endif; ?>

    <a href="kensaku.php" class="btn" style="margin-top: 30px;">← 戻る</a>

  </div>
</body>
</html>
