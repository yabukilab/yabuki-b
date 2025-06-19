<?php
session_start();
$title = $_GET['title'] ?? 'タイトル不明';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>感想投稿</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>感想を投稿する</h1>
        <h2>作品タイトル：<?= htmlspecialchars($title) ?></h2>

        <form action="submit.php" method="POST" class="form-wrapper">
            <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">
            <label for="content">感想</label>
            <textarea id="content" name="content" rows="10" placeholder="ここに感想を入力してください" required></textarea>
            <button type="submit" class="btn">投稿</button>
        </form>

        <form action="others.php" method="GET" style="margin-top: 20px;">
            <button type="submit" class="btn">他の人の感想を見る</button>
        </form>
    </div>

    <div class="mypage-button">
       <a href="gamen4.php" class="btn">マイページ</a>
    </div>

</body>
</html>

