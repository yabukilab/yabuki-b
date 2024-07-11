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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データ閲覧</title>
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
        .red-circle {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: red;
        }
        .container {
            display: flex;
            flex-direction: column;
            text-align: left;
            position: relative;
        }
        .form-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        .label-container, .left-container {
            margin-bottom: 20px;
            flex-direction: column;
        }
        .left-container {
            position: relative;
        }
        .red-circles {
            margin-top: 10px;
            position: relative;
        }
        .red-circles .red-circle {
            bottom: 0;
        }
        label {
            margin: 0 5px;
        }
        .image-display {
            text-align: right;
            width: 100%;
            margin-top: 20px;
        }
        .image-container {
            display: none;
        }
        input[type="radio"]:checked + label + .image-container {
            display: block;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1 style="text-align: left;">データ閲覧</h1>
    <div class="container">

        <h2>得点データ</h2>
        <table>
            <tr>
                <th>TEAME</th>
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
            $sql = "SELECT * FROM my_table";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
                echo "<tr><td colspan='11'>No data found</td></tr>";
            }
            ?>
        </table>

        <h2>アウトカウント</h2>
        <div class="red-circles">
            <?php
            // 赤い丸のデータを取得
            $sql_circles = "SELECT * FROM red_circles";
            $result_circles = $conn->query($sql_circles);

            if ($result_circles->num_rows > 0) {
                while ($row = $result_circles->fetch_assoc()) {
                    echo '<div class="red-circle" style="left: ' . $row["x_position"] . 'px;"></div>';
                }
            } else {
                echo "ノーアウト";
            }
            ?>
        </div>

        <h2>ランナー表示</h2>
        <div class="image-display">
            <?php
            // 最新の画像を取得
            $sql_image = "SELECT url FROM images ORDER BY id DESC LIMIT 1";
            $result_image = $conn->query($sql_image);

            if ($result_image->num_rows > 0) {
                $row_image = $result_image->fetch_assoc();
                echo '<img src="'.$row_image['url'].'" alt="選択された画像">';
            } else {
                echo "ランナーなし";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
