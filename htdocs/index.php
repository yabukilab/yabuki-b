<?php
session_start();

// データベース接続情報
$db_host = 'localhost'; // データベースのホスト名
$db_username = 'root'; // データベースのユーザー名
$db_password = ''; // データベースのパスワード
$db_name = 'baseball'; // 作成したデータベース名

// データベースに接続する
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ログインフォームの送信を処理する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 入力されたユーザー名とパスワードが正しいかをデータベースからチェックする
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // 認証成功
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // ダッシュボードや他のページへリダイレクトする
            exit;
        } else {
            // パスワードが間違っている場合
            $error_message = "ユーザー名またはパスワードが無効です。";
        }
    } else {
        // ユーザーが見つからない場合
        $error_message = "ユーザー名またはパスワードが無効です。";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <h1>ログイン</h1>
        
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" class="button">ログイン</button>
            </div>
        </form>
    </div>
    <div class="footer">
        <a href="otherpage.php" class="button">他ページへのリンク</a>
    </div>
</body>
</html>
