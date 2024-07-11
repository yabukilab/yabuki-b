<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = ""; // データベースのパスワード
$dbname = "baseball"; // データベース名

// データベース接続を作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// スコアを取得するSQLクエリ
$sql = "SELECT game_id, inning, team_name, score FROM baseball_scores ORDER BY game_id, inning, team_name";
$result = $conn->query($sql);

$scores = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $scores[$row['game_id']][$row['inning']][$row['team_name']] = $row['score'];
    }
} else {
    echo "No scores found";
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Baseball Scoreboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Baseball Scoreboard</h1>
    <?php foreach($scores as $game_id => $innings): ?>
        <h2>Game ID: <?php echo $game_id; ?></h2>
        <table>
            <tr>
                <th>Inning</th>
                <?php
                $teams = array_keys(current($innings));
                foreach ($teams as $team_name) {
                    echo "<th>" . $team_name . "</th>";
                }
                ?>
            </tr>
            <?php foreach ($innings as $inning => $team_scores): ?>
                <tr>
                    <td><?php echo $inning; ?></td>
                    <?php foreach ($teams as $team_name): ?>
                        <td><?php echo isset($team_scores[$team_name]) ? $team_scores[$team_name] : 0; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>