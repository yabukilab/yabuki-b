<?php
header("Content-Type: application/json");

if (empty($_GET['q'])) {
  echo json_encode([]);
  exit;
}

$queryRaw = trim($_GET['q']);
$apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode("inauthor:" . $queryRaw);

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

$authors = array_unique($authors);
echo json_encode(array_slice(array_values($authors), 0, 5));
?>