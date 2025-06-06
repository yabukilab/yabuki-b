
<?php
header("Content-Type: application/json");

if (empty($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$queryRaw = $_GET['q'];
$query = urlencode("inauthor:" . $queryRaw);

$url = "https://www.googleapis.com/books/v1/volumes?q=" . $query;

$json = file_get_contents($url);
$data = json_decode($json, true);

$authors = [];

if (!empty($data['items'])) {
    foreach ($data['items'] as $item) {
        if (!empty($item['volumeInfo']['authors'])) {
            foreach ($item['volumeInfo']['authors'] as $author) {
                // 入力文字列が含まれているかをチェック（部分一致）
                if (mb_stripos($author, $queryRaw) !== false) {
                    $authors[] = $author;
                }
            }
        }
    }
}

$authors = array_unique($authors);
echo json_encode(array_slice($authors, 0, 5));
