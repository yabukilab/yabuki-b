<?php
$books = [];

if (!empty($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}";

    $json = file_get_contents($url);
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
    <title>Google Books検索</title>
</head>
<body>
    <h1>本を検索</h1>
    <form method="get">
        <input type="text" name="q" placeholder="タイトルや著者名" required>
        <button type="submit">検索</button>
    </form>

    <?php if (!empty($books)): ?>
        <h2>検索結果</h2>
        <ul>
            <?php foreach ($books as $book): ?>
                <?php
                    $volumeInfo = $book['volumeInfo'];
                    $title = $volumeInfo['title'] ?? 'タイトル不明';
                    $authors = isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : '著者不明';
                    $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? '';
                ?>
                <li style="margin-bottom: 20px;">
                    <strong><?= htmlspecialchars($title) ?></strong><br>
                    著者: <?= htmlspecialchars($authors) ?><br>
                    <?php if ($thumbnail): ?>
                        <img src="<?= htmlspecialchars($thumbnail) ?>" alt="カバー画像">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
