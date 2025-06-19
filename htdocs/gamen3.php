<?php
$books = [];


if (!empty($_GET['q'])) {
    $author = htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8');
    $query = urlencode("inauthor:\"$author\"");
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=10";

    $json = @file_get_contents($url);
    $data = $json ? json_decode($json, true) : [];

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
        /* 全体レイアウト */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f8;
            margin: 0;
            padding: 0;
        }

        /* コンテナ（中央寄せ） */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* ヘッダー */
        header {
            background-color: #1e90ff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.8rem;
            border-radius: 0 0 10px 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.2rem;
        }

        .accent {
            color: #1e90ff;
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
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .book-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .book-card {
            display: flex;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
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
            font-size: 1.1rem;
            color: #333;
        }

        .back-link {
            margin-top: 30px;
            display: inline-block;
            text-decoration: none;
            font-size: 1rem;
            color: #1e90ff;
        }
    </style>
</head>
<body>

    <header>Mypage</header>

    <div class="container">
<<<<<<< HEAD
        <h1><span class="accent">作者の作品一覧</span></h1>

        <?php if (!empty($_GET['q'])): ?>
            <div class="author-info">
                <div class="author-icon"><?= htmlspecialchars(mb_substr($_GET['q'], 0, 1)) ?></div>
                <div><strong>作者名</strong>: <?= htmlspecialchars($_GET['q']) ?></div>
            </div>
=======
       <?php if (!empty($_GET['q'])): ?>
             <div class="author-info">
                 <div class="author-icon"><?= htmlspecialchars(mb_substr($_GET['q'], 0, 1)) ?></div>
                 <div><strong>作者名</strong>: <?= htmlspecialchars($_GET['q']) ?></div>
             </div>



>>>>>>> 56b41d8ca7a2d3c12be6a45e4bf4898e2554245a

            <?php if (empty($books)): ?>
                <p>作品が見つかりませんでした。</p>
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
                                 <a href="gamen5.php?title=<?= urlencode($title) ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($title) ?>
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <p>作者名が指定されていません。</p>
        <?php endif; ?>

        <a href="gamen2.php" class="back-link">← 戻る</a>
    </div>

</body>
</html>
