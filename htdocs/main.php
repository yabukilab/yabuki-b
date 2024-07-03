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

// フォームが送信された場合、データベースにデータを挿入
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_value = $_POST['inning'];

    // 次の空のフィールドを持つ最初のレコードを取得
    $next_field = '';
    $record_id = null;

    $sql = "SELECT * FROM my_table ORDER BY id ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (empty($row['Inning1'])) {
                $next_field = 'Inning1';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning2'])) {
                $next_field = 'Inning2';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning3'])) {
                $next_field = 'Inning3';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning4'])) {
                $next_field = 'Inning4';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning5'])) {
                $next_field = 'Inning5';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning6'])) {
                $next_field = 'Inning6';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning7'])) {
                $next_field = 'Inning7';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning8'])) {
                $next_field = 'Inning8';
                $record_id = $row['id'];
                break;
            } elseif (empty($row['Inning9'])) {
                $next_field = 'Inning9';
                $record_id = $row['id'];
                break;
            }
        }
    }

    if ($next_field) {
        // 次の空のフィールドにデータを挿入
        $sql = "UPDATE my_table SET $next_field='$new_value' WHERE id=$record_id";
    } else {
        // すべてのフィールドが埋まっている場合、新しいレコードを作成
        $sql = "INSERT INTO my_table (Inning1) VALUES ('$new_value')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "データが正常に更新されました";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }
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
    <label for="inning">ここにデータを入力:</label>
    <input type="text" id="inning" name="inning" required><br><br>
    <input type="submit" value="Submit">
</form>

<h2>データの表示</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Team</th>
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
    $sql = "SELECT * FROM my_table ORDER BY id ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // データ出力
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["Team"] . "</td>";
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
        echo "<tr><td colspan='11'>No data found</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>