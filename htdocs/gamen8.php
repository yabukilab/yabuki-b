<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>交流チャット</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>交流チャット</h1>

        <form action="post_chat.php" method="POST" class="form-wrapper">
            <input type="text" name="name" placeholder="名前" required>
            <textarea name="message" placeholder="メッセージ" rows="4" required></textarea>
            <button type="submit" class="btn">送信</button>
        </form>

        <h2>チャットログ</h2>
        <div class="chat-box">
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

        <div class="mypage-button">
            <a href="gamen4.php" class="btn">マイページ</a>
        </div>


        <div style="margin-top: 20px;">
            <a href="gamen5.php" class="btn">感想投稿に戻る</a>
        </div>
    </div>
</body>
</html>

