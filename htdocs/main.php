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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inning'])) {
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

<<!DOCTYPE html>
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
