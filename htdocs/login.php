
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
    $userid = $_POST["userid"] ?? "";
    $password = $_POST["password"] ?? "";

    // データベースからユーザー情報を取得
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$userid]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
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