
<?php
header("Content-Type: application/json");

// 空なら空配列返して終了
if (empty($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$queryRaw = trim($_GET['q']);

// APIリクエスト準備
$apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode("inauthor:" . $queryRaw);

// API呼び出し（失敗対策付き）
$json = @file_get_contents($apiUrl);
if ($json === false) {
    echo json_encode([]);
    exit;
}

$data = json_decode($json, true);

$authors = [];

if (!empty($data['items'])) {
    foreach ($data['items'] as $item) {
        if (!empty($item['volumeInfo']['authors'])) {
            foreach ($item['volumeInfo']['authors'] as $author) {
                if (mb_stripos($author, $queryRaw) !== false) {
                    $authors[] = $author;
                }
            }
        }
    }
}

// 重複除去＆最大5件に絞って返す
$authors = array_unique($authors);
echo json_encode(array_slice(array_values($authors), 0, 5));
?>
