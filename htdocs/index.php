<?php
// ここでセッション開始など必要に応じて追加可能
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>読書記録交流アプリ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>読書記録交流<span class="accent">アプリ</span></h1>

        <!-- ログインフォーム -->
        <form class="login-form" action="gamen2.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="36" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" maxlength="18" required>

            <button type="submit">ログイン</button>
        </form>
    </div>

    <div class="notes">
        <h2>― 注意事項 ―</h2>
        <ol>
            <li>IDは36桁パスワードは18桁まで入力可能です。</li>
            <li>Enterキーの押すことでもログインボタンの役割を果たします。</li>
            
        </ol>
    </div>
</body>
</html>

