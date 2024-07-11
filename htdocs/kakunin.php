<?php
$db_host = 'localhost'; // データベースのホスト名
$db_username = 'root'; // データベースのユーザー名
$db_password = ''; // データベースのパスワード
$db_name = 'baseball'; // 作成したデータベース名

// データベースに接続する
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
