<?php
session_start();
$title = $_GET['title'] ?? 'タイトル不明';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>感想投稿</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>感想を投稿する</h1>

        <h2>作品タイトル：<?= htmlspecialchars($title) ?></h2>

        <!-- 感想投稿フォーム -->
        <form action="submit.php" method="POST" class="login-form">
            <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">
            
            <label for="content">感想</label>
            <textarea id="content" name="content" rows="10" placeholder="ここに感想を入力してください" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;"></textarea><br>

            <button type="submit">投稿</button>
        </form>

        <!-- 他者の感想を見るボタン -->
        <form action="others.php" method="GET" style="margin-top: 15px;">
            <button type="submit">他の人の感想を見る</button>
        </form>
    </div>
</body>
</html>

