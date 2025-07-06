
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

var_dump($_POST);
var_dump($_SESSION);
exit;


// 書籍検索処理（GET）
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

            fetch("googleapi.php?q=" + encodeURIComponent(input))
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
                })
        }
    </script>
<style>
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
        <form method="get" action="kannsou.php">
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
