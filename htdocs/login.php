<?php
session_start(); 
$error = "";
$success = false;

// DB接続情報
$dbServer = '127.0.0.1';
$dbUser = $_SERVER['MYSQL_USER'] ?? 'testuser';
$dbPass = $_SERVER['MYSQL_PASSWORD'] ?? 'pass';
$dbName = $_SERVER['MYSQL_DB'] ?? 'mydb';

$dsn = "mysql:host=$dbServer;dbname=$dbName;charset=utf8";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
} catch (PDOException $e) {
    die("データベース接続失敗: " . htmlspecialchars($e->getMessage()));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: kensaku.php");
        exit();
    } else {
        $error = "※IDまたはパスワードが正しくありません";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>読書記録<span class="accent">ログイン</span></h1>

    <form class="login-form" action="login.php" method="post">
      <label for="username">ユーザーID（登録時に入力した名前）</label>
      <input type="text" id="username" name="username" maxlength="36" required>

      <label for="password">パスワード</label>
      <input type="password" id="password" name="password" maxlength="18" required>

      <button type="submit">ログイン</button>
    </form>

    <?php if (!empty($error)): ?>
      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
  </div>

  <div class="notes">
    <h2>― 実装にあたっての注意事項 ―</h2>
    <ol>
      <li>IDは36桁まで入力可能とする。</li>
      <li>パスワードは20桁まで入力可能として文字は「＊」で表示する。</li>
      <li>Enterキーの押下にてログインボタンの押下と同様の動作を行う。</li>
      <li>各入力枠に対してSQLインジェクションを防止する内部機構を講じる。</li>
    </ol>
  </div>
</body>
</html>
