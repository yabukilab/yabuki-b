<?php
header("Content-Type: application/json");

if (empty($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$query = urlencode($_GET['q']);
$url = "https://www.googleapis.com/books/v1/volumes?q=" . $query;

$json = file_get_contents($url);
$data = json_decode($json, true);

$authors = [];

if (!empty($data['items'])) {
    foreach ($data['items'] as $item) {
        if (!empty($item['volumeInfo']['authors'])) {
            foreach ($item['volumeInfo']['authors'] as $author) {
                if (stripos($author, $_GET['q']) !== false) {
                    $authors[] = $author;
                }
            }
        }
    }
}

// 重複を排除
$authors = array_unique($authors);

// 上位5件だけ返す
echo json_encode(array_slice($authors, 0, 5));
