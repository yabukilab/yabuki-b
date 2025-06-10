
// 仮データ
$works = [
    ['id' => 1, 'title' => '作品A', 'image' => 'images/sample1.png', 'comments' => 5],
    ['id' => 2, 'title' => '作品B', 'image' => 'images/sample2.png', 'comments' => 12],
    ['id' => 3, 'title' => '作品C', 'image' => 'images/sample3.png', 'comments' => 8],
];

// 感想保存用（セッションに保存）
session_start();
if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = [];
}

// 感想投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['work_id'], $_POST['comment'])) {
    $workId = (int)$_POST['work_id'];
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        $_SESSION['comments'][$workId][] = $comment;
    }
}

// 感想数の降順で並び替え（仮データ + 投稿分）
foreach ($works as &$work) {
    $id = $work['id'];
    $count = isset($_SESSION['comments'][$id]) ? count($_SESSION['comments'][$id]) : 0;
    $work['comments'] += $count;
}
unset($work);

usort($works, function ($a, $b) {
    return $b['comments'] - $a['comments'];
});
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mypage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Mypage</h1>
</header>

<main>
    <section class="author-info">
        <div class="icon">F</div>
        <div class="author-name">作者名</div>
    </section>

    <section class="works">
        <h2>作品一覧</h2>

        <?php foreach ($works as $work): ?>
            <div class="work-item">
                <img src="<?= htmlspecialchars($work['image']) ?>" alt="作品画像">
                <strong><?= htmlspecialchars($work['title']) ?></strong>
                <span class="comment-count">(<?= $work['comments'] ?> 件の感想)</span>

                <!-- 感想フォーム -->
                <form method="post" class="comment-form">
                    <input type="hidden" name="work_id" value="<?= $work['id'] ?>">
                    <textarea name="comment" placeholder="感想を入力..." rows="3" cols="40" required></textarea><br>
                    <button type="submit">投稿</button>
                </form>

                <!-- 感想表示 -->
                <?php if (!empty($_SESSION['comments'][$work['id']])): ?>
                    <ul class="comment-list">
                        <?php foreach ($_SESSION['comments'][$work['id']] as $c): ?>
                            <li><?= htmlspecialchars($c) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
