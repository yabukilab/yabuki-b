<?php
session_start();
require_once 'db.php'; // db.phpをインクルードしてデータベース接続を使用

// データベース接続を確認
if (!$db) {
    die("Connection failed: " . h($e->getMessage()));
}
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
        .container {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        .form-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .label-container, .left-container {
            margin-bottom: 20px;
            flex-direction: column; /* フォームと赤丸を縦に並べる */
        }

        .red-circles {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .image-display {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto;
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
        $stmt = $db->query($sql);
        $innings = array();
        if ($stmt->rowCount() > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $innings[] = $row["inning"];
                echo "<th>" . h($row["inning"]) . "</th>";
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
        echo "<td>" . h($team) . "</td>";

        // 各イニングごとのスコアを初期化
        $scores = array_fill(0, count($innings), null);  // 0 から null に変更
        $total = 0;

        // スコアを取得して配列に格納
        $sql = "SELECT inning, score FROM baseball_scores WHERE game = " . ($team == "Away" ? "0" : "1") . " ORDER BY inning";
        $stmt = $db->query($sql);
        if ($stmt->rowCount() > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $index = array_search($row["inning"], $innings);
                if ($index !== false) {
                    $scores[$index] = (int)$row["score"];
                    $total += (int)$row["score"];
                }
            }
        }

        // 各イニングのスコアを表示
        foreach ($scores as $score) {
            echo "<td>" . ($score === null ? '' : h($score)) . "</td>";
        }
        echo "<td>" . h($total) . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- 赤い丸の表示と画像を同じ行に配置 -->
<div class="form-container">
    <div class="red-circles">
        <?php
        // 赤い丸のデータを取得
        $sql_circles = "SELECT * FROM red_circles";
        $stmt_circles = $db->query($sql_circles);

        if ($stmt_circles->rowCount() > 0) {
            while ($row = $stmt_circles->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="red-circle" style="left: ' . h($row["x_position"]) . 'px;"></div>';
            }
        }
        ?>
    </div>

    <div class="image-display">
        <?php
        if (isset($_SESSION['selected_image'])) {
            $selectedImage = $_SESSION['selected_image'];
            echo '<img src="'.h($selectedImage).'" alt="選択された画像">';
        }
        ?>
    </div>
</div>

<?php
// データベース接続を閉じる
$db = null;
?>
</body>
</html>
