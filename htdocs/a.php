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
    // データ挿入フォーム処理
    if (isset($_POST['inning'])) {
        $new_value = $_POST['inning'];

        // フィールドの順番
        $fields = ['Inning1', 'Inning2', 'Inning3', 'Inning4', 'Inning5', 'Inning6', 'Inning7', 'Inning8', 'Inning9'];

        // 現在の入力位置を追跡するためのクエリ
        $sql = "SELECT * FROM my_table ORDER BY id ASC";
        $result = $conn->query($sql);

        $updated = false;
        foreach ($fields as $field) {
            if ($result->num_rows > 0) {
                $result->data_seek(0); // 結果セットのポインタを最初に戻す
                while ($row = $result->fetch_assoc()) {
                    if (empty($row[$field])) {
                        $record_id = $row['id'];
                        $sql_update = "UPDATE my_table SET $field='$new_value' WHERE id=$record_id";
                        if ($conn->query($sql_update) === TRUE) {
                            $updated = true;
                            break 2; // 内側と外側のループを終了
                        } else {
                            echo "エラー: " . $sql_update . "<br>" . $conn->error;
                        }
                    }
                }
            }
        }

        if (!$updated) {
            // すべてのフィールドが埋まっている場合、新しいレコードを作成
            $sql_insert = "INSERT INTO my_table (Inning1) VALUES ('$new_value')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "新しいレコードが正常に作成されました";
            } else {
                echo "エラー: " . $sql_insert . "<br>" . $conn->error;
            }
        }

        // ページをリロードしてPOSTデータをクリア
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // 赤い丸のデータを挿入するための処理
    if (isset($_POST['circle']) && $_POST['circle'] == '0') {
        // 既存の赤い丸の数を確認
        $sql_count = "SELECT COUNT(*) as count FROM red_circles";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        
        // 赤い丸を2つまでに制限
        if ($row_count['count'] < 2) {
            // 赤い丸の座標を設定
            $x_positions = [20, 80]; // x座標の候補
            $y_position = 20; // y座標固定
            
            // 新しい赤い丸の座標をランダムで選択
            $random_key = $row_count['count']; // x_positionsのインデックスをcountで固定
            $x_position = $x_positions[$random_key];
            
            // 赤い丸の座標をデータベースに挿入
            $sql_insert_circle = "INSERT INTO red_circles (x_position, y_position) VALUES ($x_position, $y_position)";
            if ($conn->query($sql_insert_circle) === TRUE) {
                echo "赤い丸が正常に保存されました";
            } else {
                echo "エラー: " . $sql_insert_circle . "<br>" . $conn->error;
            }
        }
    }

    // 赤い丸をリセットする処理
    if (isset($_POST['reset_circles'])) {
        $sql_reset = "DELETE FROM red_circles";
        if ($conn->query($sql_reset) === TRUE) {
            echo "赤い丸がリセットされました";
        } else {
            echo "エラー: " . $sql_reset . "<br>" . $conn->error;
        }
    }

    // 画像選択フォームの処理
    if (isset($_POST['image'])) {
        $imageUrl = $_POST['image'];

        $stmt = $conn->prepare("INSERT INTO images (url) VALUES (?)");
        $stmt->bind_param("s", $imageUrl);

        if ($stmt->execute()) {
            echo "画像が保存されました";
        } else {
            echo "画像の保存に失敗しました: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .red-circle {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: red;
        }
        .red-circle:first-of-type {
            left: 20px; /* 1つ目の赤い丸の左位置 */
            bottom: 20px; /* 1つ目の赤い丸の下位置 */
        }
        .red-circle:nth-of-type(2) {
            left: 80px; /* 2つ目の赤い丸の左位置 */
            bottom: 20px; /* 2つ目の赤い丸の下位置 */
        }
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
    <h1 style="text-align: right;">データ入力と表示</h1>
    <div class="container">
    <h2>データベースからのデータ表示</h2>
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

        <h2>データ入力フォーム</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="inning">ここにデータを入力:</label>
            <input type="text" id="inning" name="inning" required><br><br>
            <input type="submit" value="Submit">
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="circle">赤い丸を追加するには0を入力:</label>
            <input type="text" id="circle" name="circle" required><br><br>
            <input type="submit" value="Submit">
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="reset_circles" value="1">
            <input type="submit" value="赤い丸をリセット">
        </form>

        <h2>画像選択プログラム</h2>
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
            if (isset($_POST['image'])) {
                $selectedImage = $_POST['image'];
                echo "画像が保存されました: " . $selectedImage;
            }
            ?>
        </div>

        <?php
        // 赤い丸のデータを取得
        $sql_circles = "SELECT * FROM red_circles";
        $result_circles = $conn->query($sql_circles);

        if ($result_circles->num_rows > 0) {
            while ($row = $result_circles->fetch_assoc()) {
                echo '<div class="red-circle" style="left: ' . $row["x_position"] . 'px; bottom: ' . $row["y_position"] . 'px;"></div>';
            }
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
