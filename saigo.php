<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = ""; // データベースのパスワード
$dbname = "baseball"; // データベース名

// データベース接続を作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続を確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 最新のレコードを取得
$latest_record_sql = "SELECT * FROM my_table ORDER BY id DESC LIMIT 1";
$latest_record_result = $conn->query($latest_record_sql);
$latest_record = $latest_record_result->fetch_assoc();

// フォームが送信された場合、データベースにデータを挿入
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_value = $_POST['inning'];
    $next_inning = '';

    // 次の空のイニングフィールドを見つける
    if (!$latest_record) {
        // レコードがない場合、新しいレコードを作成
        $sql = "INSERT INTO my_table (Inning1) VALUES ('$new_value')";
    } else {
        // レコードがある場合、次の空のイニングフィールドを見つけて更新
        if (empty($latest_record['Inning1'])) {
            $next_inning = 'Inning1';
        } elseif (empty($latest_record['Inning2'])) {
            $next_inning = 'Inning2';
        } elseif (empty($latest_record['Inning3'])) {
            $next_inning = 'Inning3';
        } elseif (empty($latest_record['Inning4'])) {
            $next_inning = 'Inning4';
        } elseif (empty($latest_record['Inning5'])) {
            $next_inning = 'Inning5';
        } elseif (empty($latest_record['Inning6'])) {
            $next_inning = 'Inning6';
        } elseif (empty($latest_record['Inning7'])) {
            $next_inning = 'Inning7';
        } elseif (empty($latest_record['Inning8'])) {
            $next_inning = 'Inning8';
        } elseif (empty($latest_record['Inning9'])) {
            $next_inning = 'Inning9';
        }

        if ($next_inning) {
            $sql = "UPDATE my_table SET $next_inning='$new_value' WHERE id=" . $latest_record['id'];
        } else {
            // すべてのイニングフィールドが埋まっている場合、新しいレコードを作成
            $sql = "INSERT INTO my_table (Inning1) VALUES ('$new_value')";
        }
    }

    if ($conn->query($sql) === TRUE) {
        echo "データが正常に更新されました";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }

    // 最新のレコードを再取得
    $latest_record_result = $conn->query($latest_record_sql);
    $latest_record = $latest_record_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>データ入力と表示</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>データ入力フォーム</h2>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="inning">ここに点数を入力:</label>
    <input type="text" id="inning" name="inning" required><br><br>
    <input type="submit" value="Submit">
</form>

<h2>実際のスコア</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Inning1</th>
        <th>Inning2</th>
        <th>Inning3</th>
        <th>Inning4</th>
        <th>Inning5</th>
        <th>Inning6</th>
        <th>Inning7</th>
        <th>Inning8</th>
        <th>Inning9</th>
    </tr>
    <?php
    // データを取得するSQLクエリ
    $sql = "SELECT * FROM my_table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // データ出力
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["Inning1"] . "</td>";
            echo "<td>" . $row["Inning2"] . "</td>";
            echo "<td>" . $row["Inning3"] . "</td>";
            echo "<td>" . $row["Inning4"] . "</td>";
            echo "<td>" . $row["Inning5"] . "</td>";
            echo "<td>" . $row["Inning6"] . "</td>";
            echo "<td>" . $row["Inning7"] . "</td>";
            echo "<td>" . $row["Inning8"] . "</td>";
            echo "<td>" . $row["Inning9"] . "</td>";
            echo "</tr>";
        }
    } else {
        // データがない場合でも空の行を表示
        echo "<tr>";
        echo "<td colspan='10'>No data found</td>";
        echo "</tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>


