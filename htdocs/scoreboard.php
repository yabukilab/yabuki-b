<!DOCTYPE html>
<html>
<head>
    <title>Baseball Scoreboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Baseball Scoreboard</h2>

<!-- データ入力フォーム -->
<h3>Enter Score</h3>
<form method="post" action="">
    得点: <input type="text" name="score" required>
    <input type="submit" name="submit" value="更新↻">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["score"])) {
    // フォームから送信されたスコアを取得
    $score = $_POST["score"];

    // データベース接続情報
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "baseball";

    // データベース接続を作成
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 接続をチェック
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 現在のデータ入力回数を取得
    $result = $conn->query("SELECT COUNT(*) AS count FROM baseball_scores");
    $row = $result->fetch_assoc();
    $input_count = $row['count'] + 1;

    // gameとinningを計算
    $game = $input_count % 2 === 0 ? 1 : 0;
    $inning = ceil($input_count / 2);

    // SQLクエリを準備
    $sql = "INSERT INTO baseball_scores (inning, score, game) VALUES (?, ?, ?)";

    // SQLクエリの準備とバインド
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $inning, $score, $game);

    // クエリを実行
    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // 接続を閉じる
    $stmt->close();
    $conn->close();

    // リダイレクトしてフォーム再送信を防ぐ
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<table>
    <tr>
        <th>Team</th>
        <?php
        // データベース接続情報
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "baseball";

        // データベース接続を作成
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 接続をチェック
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
        $scores = array_fill(0, count($innings), null);  // 0 から null に変更
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

    $conn->close();
    ?>
</table>

</body>
</html>
