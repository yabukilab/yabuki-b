<?php
$books = [];

if (!empty($_GET['q'])) {
    $query = urlencode("inauthor:" . $_GET['q']);
    $maxPerPage = 40;
    $totalFetched = 0;
    $maxTotal = 200;

    while ($totalFetched < $maxTotal) {
        $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&startIndex={$totalFetched}&maxResults={$maxPerPage}";
        $json = @file_get_contents($url);
        $data = json_decode($json, true);

        if (empty($data['items'])) {
            break;
        }

        $books = array_merge($books, $data['items']);
        $totalFetched += count($data['items']);

        if ($totalFetched >= ($data['totalItems'] ?? 0)) {
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mypage - 作者の作品一覧</title>
    <style>
        /* 共通レイアウト */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        /* セクション（中央寄せ） */
        .section {
            width: 90%;
            max-width: 600px;
            margin: 60px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .accent {
            color: #1e90ff;
        }

        .author-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .author-icon {
            background: #1e90ff;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .book-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .book-card {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .book-card img {
            width: 60px;
            height: 90px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 4px;
        }

        .book-title {
            font-weight: bold;
            color: #333;
        }

        .btn {
            font-size: 1.1rem;
            padding: 12px;
            border-radius: 8px;
            background-color: #1e90ff;
            color: white;
            border: none;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 30px;
            text-align: center;
        }

        .btn:hover {
            background-color: #1c7cd6;
        }

        .notice-box {
            background-color: #eaf6ff;
            padding: 20px;
            border-left: 5px solid #1e90ff;
            border-radius: 6px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="section">
        <h1>作者の作品一覧</h1>

        <?php if (!empty($_GET['q'])): ?>
            <div class="author-info">
                <div class="author-icon"><?= htmlspecialchars(mb_substr($_GET['q'], 0, 1)) ?></div>
                <div>
                    <div><strong>作者名:</strong> <span class="accent"><?= htmlspecialchars($_GET['q']) ?></span></div>
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
                            <div class="book-title"><?= htmlspecialchars($title) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="notice-box">作者名が指定されていません。</div>
        <?php endif; ?>

        <a href="gamen2.php" class="btn">← 戻る</a>
    </div>
</body>
</html>
