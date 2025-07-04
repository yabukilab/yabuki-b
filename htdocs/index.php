
<?php
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
        <form class="login-form" action="kensaku.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="36" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" maxlength="18" required>

            <button type="submit">ログイン</button>
        </form>
    

    </div>

    <div class="notes">
        <h2>― 実装にあたっての注意事項 ―</h2>
        <ol>
            <li>IDは36桁まで入力可能とする。</li>
            <li>パスワードは18桁まで入力可能として文字は「＊」で表示する。</li>
            <li>Enterキーの押下にてログインボタンの押下と同様の動作を行う。</li>
            <li>各入力枠に対してSQLインジェクションを防止する内部機構を講じる。</li>
        </ol>
    </div>
</body>
</html>

