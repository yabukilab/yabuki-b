<?php
require_once 'db.php'; // db.phpをインクルードしてデータベース接続を使用

// データベース接続を確認
if (!$db) {
    die("Connection failed: " . h($e->getMessage()));
}

// フォームからの得点を処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["score"])) {
    $score = $_POST["score"];

    // 現在のデータ入力回数を取得
    $stmt = $db->query("SELECT COUNT(*) AS count FROM baseball_scores");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $input_count = $row['count'] + 1;

    // gameとinningを計算
    $game = $input_count % 2 === 0 ? 1 : 0;
    $inning = ceil($input_count / 2);

    // SQLクエリを準備
    $sql = "INSERT INTO baseball_scores (inning, score, game) VALUES (?, ?, ?)";

    // SQLクエリの準備とバインド
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $inning, PDO::PARAM_INT);
    $stmt->bindParam(2, $score, PDO::PARAM_STR);
    $stmt->bindParam(3, $game, PDO::PARAM_INT);

    // クエリを実行
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . h($stmt->errorInfo());
    }

    // リダイレクトしてフォーム再送信を防ぐ
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 赤い丸のデータを挿入するための処理
if (isset($_POST['circle']) && $_POST['circle'] == '0') {
    // 既存の赤い丸の数を確認
    $sql_count = "SELECT COUNT(*) as count FROM red_circles";
    $stmt_count = $db->query($sql_count);
    $row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);

    // 赤い丸を2つまでに制限
    if ($row_count['count'] < 2) {
        // 赤い丸の座標を設定
        $x_positions = [20, 80]; // x座標の候補
        $y_position = 50; // y座標固定

        // 新しい赤い丸の座標をインデックスで選択
        $x_position = $x_positions[$row_count['count']];

        // 赤い丸の座標をデータベースに挿入
        $sql_insert_circle = "INSERT INTO red_circles (x_position, y_position) VALUES (:x_position, :y_position)";
        $stmt_insert_circle = $db->prepare($sql_insert_circle);
        $stmt_insert_circle->bindParam(':x_position', $x_position, PDO::PARAM_INT);
        $stmt_insert_circle->bindParam(':y_position', $y_position, PDO::PARAM_INT);

        if ($stmt_insert_circle->execute()) {
            echo "";
        } else {
            echo "エラー: " . h($stmt_insert_circle->errorInfo());
        }
    }
}

// 赤い丸をリセットする処理
if (isset($_POST['reset_circles'])) {
    $sql_reset = "DELETE FROM red_circles";
    $stmt_reset = $db->prepare($sql_reset);

    if ($stmt_reset->execute()) {
        echo "OUTカウントがリセットされました";
    } else {
        echo "エラー: " . h($stmt_reset->errorInfo());
    }
}

// 得点データをリセットする処理
if (isset($_POST['reset_scores'])) {
    $sql_reset_scores = "DELETE FROM baseball_scores";
    $stmt_reset_scores = $db->prepare($sql_reset_scores);

    if ($stmt_reset_scores->execute()) {
        echo "得点データがリセットされました";
    } else {
        echo "エラー: " . h($stmt_reset_scores->errorInfo());
    }
}

// 画像選択フォームの処理
if (isset($_POST['image'])) {
    $imageUrl = $_POST['image'];

    $sql_insert_image = "INSERT INTO images (url) VALUES (:url)";
    $stmt_insert_image = $db->prepare($sql_insert_image);
    $stmt_insert_image->bindParam(':url', $imageUrl, PDO::PARAM_STR);

    if ($stmt_insert_image->execute()) {
        echo "";
    } else {
        echo "画像の保存に失敗しました: " . h($stmt_insert_image->errorInfo());
    }
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
            top: -20px; /* 赤い丸を上に20ピクセル移動 */
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
            margin-top: 50px; /* 赤い丸を下に配置 */

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

<!-- 得点の入力フォーム -->
<h3>得点データ入力</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    得点: <input type="text" name="score" required>
    <input type="submit" name="submit" value="更新↻">
</form>

<!-- 得点データをリセットするフォーム -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="reset_scores" value="1">
    <input type="submit" value="得点データをリセット">
</form>

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

<!-- 赤い丸の表示 -->
<div class="form-container">
    <div class="left-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="circle">OUTを追加するには0を入力:</label>
            <input type="text" id="circle" name="circle" required><br><br>
            <input type="submit" value="更新↻">
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="reset_circles" value="1">
            <input type="submit" value="OUTカウントをリセット">
        </form>
    </div>

    <div class="label-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="radio" id="option1" name="image" value="1塁.jpg">
            <label for="option1">1塁</label>
            <div class="image-container image1">
                <img src="1塁.jpg" alt="1塁">
            </div>

            <input type="radio" id="option2" name="image" value="2塁.jpg">
            <label for="option2">2塁</label>
            <div class="image-container image2">
                <img src="2塁.jpg" alt="2塁">
            </div>

            <input type="radio" id="option3" name="image" value="3塁.jpg">
            <label for="option3">3塁</label>
            <div class="image-container image3">
                <img src="3塁.jpg" alt="3塁">
            </div>

            <input type="radio" id="option4" name="image" value="1.2塁.jpg">
            <label for="option4">1.2塁</label>
            <div class="image-container image4">
                <img src="1.2塁.jpg" alt="1.2塁">
            </div>

            <input type="radio" id="option5" name="image" value="1.3塁.jpg">
            <label for="option5">1.3塁</label>
            <div class="image-container image5">
                <img src="1.3塁.jpg" alt="1.3塁">
            </div>

            <input type="radio" id="option6" name="image" value="2.3塁.jpg">
            <label for="option6">2.3塁</label>
            <div class="image-container image6">
                <img src="2.3塁.jpg" alt="2.3塁">
            </div>

            <input type="radio" id="option7" name="image" value="満塁.jpg">
            <label for="option7">満塁</label>
            <div class="image-container image7">
                <img src="満塁.jpg" alt="満塁">
            </div>

            <input type="radio" id="option8" name="image" value="ランナーなし.jpg">
            <label for="option8">ランナーなし</label>
            <div class="image-container image8">
                <img src="ランナーなし.jpg" alt="ランナーなし">
            </div>

            <button type="submit">更新↻</button>
        </form>
    </div>
</div>

<!-- 選択された画像を表示する場所 -->
<div class="image-display">
    <?php
    if (isset($_POST['image'])) {
        $selectedImage = $_POST['image'];
        echo '<img src="'.h($selectedImage).'" alt="選択された画像">';
    }
    ?>
</div>

<!-- 赤い丸の表示 -->
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

<?php
// データベース接続を閉じる
$db = null;
?>
</body>
</html>
