<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>感想投稿</title>
</head>
<body>
    <h1>感想を投稿する</h1>

    <!-- 感想投稿フォーム -->
    <form action="submit.php" method="POST">
        <textarea name="content" rows="10" cols="50" placeholder="ここに感想を入力してください"></textarea><br>
        <button type="submit">投稿</button>
    </form>

    <!-- 他者の感想を見るボタン -->
    <form action="others.php" method="GET" style="margin-top: 1em;">
        <button type="submit">他の人の感想を見る</button>
    </form>
</body>
</html>
