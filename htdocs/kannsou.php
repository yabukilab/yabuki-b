<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die('ログインしていません。');
}

$user_id = $_SESSION['user_id'];

// タイトル取得（GETまたはPOSTから）
$title = $_GET['title'] ?? $_POST['title'] ?? 'タイトル不明';

// 検索キーワード（戻る用）
$keyword = $_GET['q'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>感想投稿</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="mypage-button">
        <a href="mypage.php" class="btn">マイページ</a>
    </div>

    <div class="container">
        <h1>感想を投稿する</h1>
        <h2>作品タイトル：<?= htmlspecialchars($title) ?></h2>

        <!-- 感想投稿フォーム -->
        <form action="submit.php" method="POST">
            <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">

            <label for="rating">評価</label>
            <div class="star-rating">
                <input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label>
                <input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label>
                <input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label>
                <input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label>
                <input type="radio" id="star5" name="rating" value="5" /><label for="star5">★</label>
            </div>

            <label for="content">感想</label><br>
            <textarea id="content" name="content" rows="6" cols="50" placeholder="ここに感想を入力してください" required></textarea><br><br>
            <button type="submit" class="btn">投稿</button>
        </form>

        <!-- 他の人の感想を見るボタン -->
        <form action="tasyanokannsou.php" method="GET" style="margin-top: 20px;">
            <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">
            <button type="submit" class="btn">他の人の感想を見る</button>
        </form>

        <!-- 作品一覧に戻るボタン -->
        <?php if (!empty($keyword)): ?>
            <form action="sakuhinnhyouji.php" method="GET" style="margin-top: 20px;">
                <input type="hidden" name="q" value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" class="btn">← 作品一覧に戻る</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
