

<?php
session_start(); // ← セッション開始（必要なら）
$error = "";
$success = false;

// DB接続情報（Docker向け例）
$dbServer = '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host=$dbServer;dbname=$dbName;charset=utf8";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// フォーム送信時
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    // データベースからユーザー情報を取得
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"]; // セッションに保存（必要なら）
        $_SESSION["username"] = $user["username"];

        // ログイン成功 → 検索画面へリダイレクト
        header("Location: kensaku.php"); // ← ここを変更してもOK
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
  <title>読書記録交流アプリ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>読書記録交流<span class="accent">アプリ</span></h1>

    <form class="login-form" action="login.php" method="post">
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





