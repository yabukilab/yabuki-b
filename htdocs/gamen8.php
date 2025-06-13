<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>交流チャット</title>
</head>
<body>
    <h1>交流チャット</h1>

    <!-- 投稿フォーム -->
    <form action="post_chat.php" method="POST">
        名前：<input type="text" name="name" required>
        <br>
        メッセージ：<br>
        <textarea name="message" rows="4" cols="50" required></textarea><br>
        <button type="submit">送信</button>
    </form>

    <hr>

    <!-- チャットログ表示 -->
    <h2>チャットログ</h2>
    <div style="border:1px solid #ccc; padding:10px; max-height:300px; overflow-y:scroll;">
        <?php
        $file = 'chatlog.txt';
        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                echo nl2br(htmlspecialchars($line, ENT_QUOTES, 'UTF-8')) . "<br>";
            }
        } else {
            echo "まだ投稿がありません。";
        }
        ?>
    </div>

    <p><a href="post.php">感想投稿に戻る</a></p>
</body>
</html>
