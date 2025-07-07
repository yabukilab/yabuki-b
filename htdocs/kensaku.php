<?php
session_start();
require_once 'db.php';

// ログイン処理（POSTされたらログイン試行）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // ログイン成功 → セッションに保存
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // ログイン失敗
            header("Location: index.php?error=1");
            exit;
        }
    } catch (PDOException $e) {
        die("ログイン処理エラー: " . htmlspecialchars($e->getMessage()));
    }
}

// ログイン済みでなければトップへ戻す
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$books = [];

if (!empty($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}";

    $json = file_get_contents($url);
    $data = json_decode($json, true);

    if (!empty($data['items'])) {
        $books = $data['items'];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>作者名サジェスト検索</title>
  <link rel="stylesheet" href="style.css">
  <script>
    function fetchSuggestions() {
      const input = document.getElementById("author").value;
      if (input.length < 2) return;

      fetch("google_api.php?q=" + encodeURIComponent(input))
        .then(response => response.json())
        .then(data => {
          const list = document.getElementById("suggestions");
          list.innerHTML = "";

          data.forEach(author => {
            const item = document.createElement("div");
            item.textContent = author;
            item.onclick = () => {
              document.getElementById("author").value = author;
              list.innerHTML = "";
            };
            list.appendChild(item);
          });
        });
    }
  </script>
  <style>
    #suggestions div {
      background: #eee;
      padding: 5px;
      cursor: pointer;
    }
    #suggestions div:hover {
      background: #ccc;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>作者・作品名で本を検索</h1>
    <form method="get" action="sakuhinnhyouji.php">
      <input type="text" id="author" name="q" placeholder="作者・作品名" oninput="fetchSuggestions()" autocomplete="off">
      <button type="submit">検索</button>
      <div id="suggestions"></div>
    </form>
  </div>

  <div class="notes">
    <h2>― 注意事項 ―</h2>
    <ol>
      <li>検索を行う際、ローマ字入力をすると上手く検索できない場合があります。</li>
    </ol>
  </div>
</body>
</html>
