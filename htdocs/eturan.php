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

// HTMLの表示部分
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baseball Scoreboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
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
        .red-circles {
            margin-top: 50px; /* 赤い丸を下に配置 */
        }
        .red-circles .red-circle {
            bottom: 0;
        }
        .image-display {
            text-align: right;
            width: 100%;
            margin-top: 20px; /* 画像をボタンの下に表示するためのスペースを追加 */
        }
        .image-container {
            display: none;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<h2>Baseball Scoreboard</h2>

<!-- スコアボードの表示 -->
<table>
    <tr>
        <th>Team</th>
        <?php
        // イニングの数を取得
        $sql = "SELECT DISTINCT inning FROM baseball_scores ORDER BY inning";
        $result = $conn->query($sql);
        $innings = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $innings[] = $row["inning"];
                echo "<th>" . $row["inning"] . "</th>";
            }
        }
        ?>
        <th>Total</th>
    </tr>
    <?php
    // チーム（先行・後攻）ごとにスコアを表示
    $teams = array("Away", "Home");
    foreach ($teams as $team) {
        echo "<tr>";
        echo "<td>" . $team . "</td>";

        // 各イニングごとのスコアを初期化
        $scores = array_fill(0, count($innings), null);
        $total = 0;

        // スコアを取得して配列に格納
        $sql = "SELECT inning, score FROM baseball_scores WHERE game = " . ($team == "Away" ? "0" : "1") . " ORDER BY inning";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $index = array_search($row["inning"], $innings);
                if ($index !== false) {
                    $scores[$index] = (int)$row["score"];
                    $total += (int)$row["score"];
                }
            }
        }

        // 各イニングのスコアを表示
        foreach ($scores as $score) {
            echo "<td>" . ($score === null ? '' : $score) . "</td>";
        }
        echo "<td>" . $total . "</td>";
        echo "</tr>";
    }
    ?>
</table>

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
// データベース接続を閉じる
$conn->close();
?>
