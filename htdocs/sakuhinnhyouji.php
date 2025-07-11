<?php
session_start();
require_once 'db.php';

$books = [];
$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $perPage;
$totalItems = 0;
$title = $_GET['q'] ?? '検索結果';

if (!empty($_GET['q'])) {
    $keyword = $_GET['q'];
    $query = urlencode("inauthor:{$keyword}+OR+intitle:{$keyword}");
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&startIndex={$startIndex}&maxResults={$perPage}&orderBy=newest";

    $json = @file_get_contents($url);
    $data = json_decode($json, true);

    if (!empty($data['items'])) {
        $books = $data['items'];
        $totalItems = $data['totalItems'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?> の検索結果</title>
