<?php
require "db.php";
session_start();

try {
    // ユーザーの追加処理
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // パスワードをハッシュ化
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // ユーザーをデータベースに追加
        $sql_add_user = "INSERT INTO users (username, password) VALUES (:username, :password_hash)";
        $stmt = $db->prepare($sql_add_user);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "新しいユーザーを追加しました";
        } else {
            echo "ユーザーの追加に失敗しました";
        }
    }

    // ログイン処理
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 入力されたユーザー名とパスワードが正しいかをデータベースからチェックする
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                // 認証成功
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("Location: main.php"); // ダッシュボードや他のページへリダイレクトする
                exit;
            } else {
                // パスワードが間違っている場合
                $error_message = "ユーザー名またはパスワードが無効です。";
            }
        } else {
            // ユーザーが見つからない場合
            $error_message = "ユーザー名またはパスワードが無効です。";
        }

        // ログインに失敗した場合のリダイレクト
        if (isset($error_message)) {
            header("Location: black.php");
            exit;
        }
    }
} catch (PDOException $e) {
    echo '接続に失敗しました: ' . $e->getMessage();
}
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
            <p><?php echo h($error_message); ?></p>
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
                <button type="submit" class="button" name="login">ログイン</button>
            </div>
        </form>

        <h2>新規登録</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="new_username">ユーザー名:</label>
                <input type="text" id="new_username" name="username" required>
            </div>
            <div class="input-group">
                <label for="new_password">パスワード:</label>
                <input type="password" id="new_password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" class="button" name="register">登録</button>
            </div>
        </form>
    </div>
</body>
</html>
