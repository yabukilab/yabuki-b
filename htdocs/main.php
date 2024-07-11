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


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像選択プログラム</title>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: flex-end; /* ボタンと画像を右寄せ */
            text-align: right;
        }
        .label-container {
            display: flex; /* ラベルを横並びに */
            justify-content: flex-end; /* 右寄せ */
            margin-bottom: 20px; /* ラベルと画像の間にスペースを追加 */
        }
        label {
            margin: 0 5px;
        }
        .image-display {
            text-align: right; /* 画像を右寄せ */
            width: 100%; /* 画像表示エリアの幅を固定 */
        }
        .image-container {
            display: none;
        }
        input[type="radio"]:checked + label + .image-container {
            display: block;
        }
        .image-container img {
            max-width: 100%; /* 画像の大きさを表示エリアに合わせる */
            height: auto;
        }
    </style>
</head>
<body>
    <h1 style="text-align: right;">ランナー選択</h1>
    <div class="container">
        <div class="label-container">
            <form method="post">
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
                <button type="submit">選択した画像を保存</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['image'])) {
                $servername = "localhost";
                $username = "root"; // MySQLのユーザー名を入力してください
                $password = ""; // MySQLのパスワードを入力してください
                $dbname = "baseball";

                // データベース接続の作成
                $conn = new mysqli($servername, $username, $password, $dbname);

                // 接続の確認
                if ($conn->connect_error) {
                    die("接続失敗: " . $conn->connect_error);
                }

                $imageUrl = $_POST['image'];

                $stmt = $conn->prepare("INSERT INTO images (url) VALUES (?)");
                $stmt->bind_param("s", $imageUrl);

                if ($stmt->execute()) {
                    echo "画像が保存されました";
                } else {
                    echo "画像の保存に失敗しました: " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
            } else {
                echo "";
            }
            ?>
        </div>
        <div class="image-display">
            <!-- 選択された画像を表示する場所 -->
            <?php
            if (isset($_POST['image'])) {
                $selectedImage = $_POST['image'];
                echo '<img src="'.$selectedImage.'" alt="選択された画像">';
            }
            ?>
        </div>
    </div>
</body>
</html>
