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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    $new_value = $_POST['inning'];
    $image = $_FILES['image']['tmp_name'];
    $imgData = addslashes(file_get_contents($image));

    // 画像とInningのデータをimages_tableに挿入
    $sql = "INSERT INTO images_table (inning, image) VALUES ('$new_value', '$imgData')";

    if ($conn->query($sql) === TRUE) {
        echo "画像が正常にアップロードされました";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }
}

// データベース接続を閉じる
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像選択プログラム</title>
    <style>
        .image-container {
            display: none;
        }
        #option1:checked ~ .image1,
        #option2:checked ~ .image2,
        #option3:checked ~ .image3,
        #option4:checked ~ .image4,
        #option5:checked ~ .image5,
        #option6:checked ~ .image6,
        #option7:checked ~ .image7 {
            display: block;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>画像選択プログラム</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <label for="inning">ここにデータを入力:</label>
        <input type="text" id="inning" name="inning" required><br><br>
        <label for="image">画像を選択:</label>
        <input type="file" name="image" id="image" required><br><br>
        <input type="submit" value="Submit">
    </form>

    <input type="radio" id="option1" name="image_option" hidden>
    <input type="radio" id="option2" name="image_option" hidden>
    <input type="radio" id="option3" name="image_option" hidden>
    <input type="radio" id="option4" name="image_option" hidden>
    <input type="radio" id="option5" name="image_option" hidden>
    <input type="radio" id="option6" name="image_option" hidden>
    <input type="radio" id="option7" name="image_option" hidden>

    <label for="option1">1塁</label>
    <label for="option2">2塁</label>
    <label for="option3">3塁</label>
    <label for="option4">1.2塁</label>
    <label for="option5">1.3塁</label>
    <label for="option6">2.3塁</label>
    <label for="option7">満塁</label>

    <div class="image-container image1">
        <img src="1塁.jpg" alt="1塁">
    </div>
    <div class="image-container image2">
        <img src="2塁.jpg" alt="2塁">
    </div>
    <div class="image-container image3">
        <img src="3塁.jpg" alt="3塁">
    </div>
    <div class="image-container image4">
        <img src="1.2塁.jpg" alt="1.2塁">
    </div>
    <div class="image-container image5">
        <img src="1.3塁.jpg" alt="1.3塁">
    </div>
    <div class="image-container image6">
        <img src="2.3塁.jpg" alt="2.3塁">
    </div>
    <div class="image-container image7">
        <img src="満塁.jpg" alt="満塁">
    </div>
</body>
</html>


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
        <th>Inning</th>
        <th>Image</th>
    </tr>
    <?php
    // データを取得するSQLクエリ
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM images_table ORDER BY id ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // データ出力
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["inning"] . "</td>";
            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" width="100"/></td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data found</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>
