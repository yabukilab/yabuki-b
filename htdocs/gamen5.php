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

        <label for="rating">評価</label>
        <div class="star-rating">
                   <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                   <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                   <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                   <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                   <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
        </div>


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

