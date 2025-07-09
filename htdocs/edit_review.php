<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$review_id = $_GET['id'] ?? null;

if (!$review_id) {
    die('IDが指定されていません。');
}

// 編集対象のレビューを取得
$stmt = $db->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
$stmt->execute([$review_id, $user_id]);
$review = $stmt->fetch();

if (!$review) {
    die('レビューが見つかりません。');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $rating = (int)($_POST['rating'] ?? 0);
    $content = $_POST['content'] ?? '';

    // バリデーション省略

    $stmt = $db->prepare("UPDATE reviews SET title = ?, rating = ?, content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $rating, $content, $review_id, $user_id]);

    header('Location: mypage.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>レビュー編集</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>レビュー編集</h1>
    <form method="POST">
      <label>タイトル
        <input type="text" name="title" value="<?= htmlspecialchars($review['title']) ?>" required />
      </label><br>

      <label>評価
        <select name="rating" required>
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>" <?= $review['rating'] == $i ? 'selected' : '' ?>><?= $i ?>★</option>
          <?php endfor; ?>
        </select>
      </label><br>

      <label>感想
        <textarea name="content" rows="6" required><?= htmlspecialchars($review['content']) ?></textarea>
      </label><br>

      <button type="submit" class="btn">更新する</button>
    </form>

    <p><a href="mypage.php" class="btn">マイページに戻る</a></p>
  </div>
</body>
</html>
