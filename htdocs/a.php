<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>統合画像選択プログラム</title>
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
        .red-circle:nth-child(1) {
            left: 20px;
            bottom: 20px;
        }
        .red-circle:nth-child(2) {
            left: 80px; /* 1つ目との間隔を調整 */
            bottom: 20px;
        }
    </style>
</head>
<body>
    <h1 style="text-align: right;">統合画像選択プログラム</h1>
    <div class="container">
        <div class="label-container">
            <!-- 画像選択フォーム -->
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

            <!-- 画像保存結果表示 -->
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
                echo "画像が選択されていません";
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

    <!-- イニングデータ入力フォーム -->
    <h2>イニングデータ入力フォーム</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="inning">ここにデータを入力:</label>
        <input type="text" id="inning" name="inning" required><br><br>
        <input type="submit" value="Submit">
    </form>

    <!-- 赤い丸操作フォーム -->
    <h2>赤い丸の操作</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="circle">赤い丸を追加するには「0」を入力:</label>
        <input type="text" id="circle" name="circle" required><br><br>
        <input type="submit" value="Submit">
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="reset_circles" value="1">
        <input type="submit" value="赤い丸をリセット">
    </form>

    <!-- イニングデータ表示 -->
    <h2>イニングデータ表示</h2>
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
        // イニングデータを取得するSQLクエリ
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
        ?>
    </table>

    <!-- 赤い丸表示 -->
    <h2>赤い丸の表示</h2>
    <?php
    // 赤い丸のデータを取得するSQLクエリ
    $sql_circle = "SELECT * FROM red_circles LIMIT 2";
    $result_circle = $conn->query($sql_circle);

    if ($result_circle->num_rows > 0) {
        // 赤い丸を表示
        $circle_count = 1;
        while ($row_circle = $result_circle->fetch_assoc()) {
            $x = $row_circle['x_position'];
            $y = $row_circle['y_position'];
            echo "<div class='red-circle' style='left: {$x}px; bottom: {$y}px;'></div>";
            $circle_count++;
        }
    }
    ?>

</body>
</html>

<?php
// データベース接続を閉じる
$conn->close();
?>
