<?php
$books = [];


if (!empty($_GET['q'])) {
    $query = urlencode("inauthor:" . $_GET['q']);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=10";

    $json = @file_get_contents($url);
    $data = json_decode($json, true);

    if (!empty($data['items'])) {
        $books = $data['items'];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mypage - 作者の作品一覧</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }

        header {
            background: #0077cc;
            color: white;
            padding: 10px;
            font-size: 20px;
        }

        .container {
            padding: 20px;
        }

        .author-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .author-icon {
            background: #444;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .book-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .book-card {
            display: flex;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            align-items: center;
        }

        .book-card img {
            width: 60px;
            height: 90px;
            object-fit: cover;
            margin-right: 15px;
            background: #eee;
        }

        .book-title {
            font-weight: bold;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: #0077cc;
        }
    </style>
</head>
<body>
    <header>Mypage</header>

    <div class="container">
       <?php if (!empty($_GET['q'])): ?>
             <div class="author-info">
                 <div class="author-icon"><?= htmlspecialchars(mb_substr($_GET['q'], 0, 1)) ?></div>
                 <div><strong>作者名</strong>: <?= htmlspecialchars($_GET['q']) ?></div>
             </div>




            <div class="book-list">
                <?php if (empty($books)): ?>
                    <p>作品が見つかりませんでした。</p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <?php
                            $title = $book['volumeInfo']['title'] ?? 'タイトル不明';
                            $image = $book['volumeInfo']['imageLinks']['thumbnail'] ?? 'https://via.placeholder.com/60x90?text=No+Image';
                        ?>
                        <div class="book-card">
                            <img src="<?= htmlspecialchars($image) ?>" alt="Book cover">
                            <div class="book-title"><?= htmlspecialchars($title) ?></div>
                            <div class="book-title">
                                 <a href="gamen5.php?title=<?= urlencode($title) ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($title) ?>
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>作者名が指定されていません。</p>
        <?php endif; ?>

        <a href="gamen2.php" class="back-link">← 戻る</a>
    </div>
</body>
</html>
