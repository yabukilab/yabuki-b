<?php
session_start();

// 仮ユーザー情報
$user = [
    'name' => '作者名',
    'icon' => 'images/user_icon.png'
];

// デフォルトアイコン
if (!file_exists($user['icon'])) {
    $user['icon'] = 'images/default_icon.png';
}

// 仮作品
$works = [
    ['id' => 1, 'title' => '作品A', 'image' => 'images/sample1.png', 'comments' => 5],
    ['id' => 2, 'title' => '作品B', 'image' => 'images/sample2.png', 'comments' => 12],
    ['id' => 3, 'title' => '作品C', 'image' => 'images/sample3.png', 'comments' => 8],
];

// セッション初期化
if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = [];
}

// 投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['work_id'], $_POST['comment'])) {
    $workId = (int)$_POST['work_id'];
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        $_SESSION['comments'][$workId][] = $comment;
    }
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?')); // 投稿後はGETに戻す
    exit;
}

// コメント数の再計算
foreach ($works as &$work) {
    $id = $work['id'];
    $count = isset($_SESSION['comments'][$id]) ? count($_SESSION['comments'][$id]) : 0;
    $work['comments'] += $count;
}
unset($work);

// ソート
usort($works, fn($a, $b) => $b['comments'] - $a['comments']);

// --- Google Books API 著者検索処理 ---
$authorSuggestions = [];
if (!empty($_GET['q'])) {
    $queryRaw = trim($_GET['q']);
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode("inauthor:" . $queryRaw);

    $json = @file_get_contents($apiUrl);
    if ($json !== false) {
        $data = json_decode($json, true);
        $authors = [];

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['volumeInfo']['authors'])) {
                    foreach ($item['volumeInfo']['authors'] as $author) {
                        if (mb_stripos($author, $queryRaw) !== false) {
                            $authors[] = $author;
                        }
                    }
                }
            }
        }

        $authorSuggestions = array_slice(array_unique($authors), 0, 5);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mypage with Book Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- タイトル -->
    <h1><span class="accent">My</span>page</h1>

    <!-- ユーザー情報 -->
    <section class="author-info" style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
        <img src="<?= htmlspecialchars($user['icon']) ?>" alt="ユーザーアイコン" class="icon-img" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc;">
        <div class="author-name" style="font-size: 18px; font-weight: bold;">
            <?= htmlspecialchars($user['name']) ?>
        </div>
    </section>

    <!-- 著者検索フォーム -->
    <section class="author-search" style="margin-bottom: 40px;">
        <h2 style="color: #1e90ff;">著者検索（Google Books API）</h2>
        <form method="get" style="margin-bottom: 10px;">
            <input type="text" name="q" placeholder="著者名を入力" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" style="width: 300px; padding: 8px;">
            <button type="submit" style="padding: 8px 12px; background-color: #1e90ff; color: white; border: none; border-radius: 4px;">検索</button>
        </form>

        <?php if (!empty($authorSuggestions)): ?>
            <ul style="padding-left: 20px;">
                <?php foreach ($authorSuggestions as $author): ?>
                    <li style="color: #333;"><?= htmlspecialchars($author) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_GET['q'])): ?>
            <p style="color: #777;">該当する著者が見つかりませんでした。</p>
        <?php endif; ?>
    </section>

    <!-- 作品一覧 -->
    <section class="works">
        <h2 style="color: #1e90ff;">作品一覧</h2>

        <?php foreach ($works as $work): ?>
            <div class="work-item" style="background-color: #ffffff; padding: 20px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
                <img src="<?= htmlspecialchars($work['image']) ?>" alt="作品画像" style="width: 100%; border-radius: 10px;">
                <strong style="display: block; margin-top: 10px; font-size: 16px;"><?= htmlspecialchars($work['title']) ?></strong>
                <span class="comment-count" style="color: #555;">(<?= $work['comments'] ?> 件の感想)</span>

                <!-- 投稿フォーム -->
                <form method="post" class="comment-form" style="margin-top: 10px;">
                    <input type="hidden" name="work_id" value="<?= $work['id'] ?>">
                    <textarea name="comment" placeholder="感想を入力..." rows="3" style="width: 100%; margin-top: 8px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;" required></textarea>
                    <button type="submit" style="margin-top: 8px; padding: 8px 16px; background-color: #1e90ff; color: #fff; border: none; border-radius: 5px; cursor: pointer;">投稿</button>
                </form>

                <!-- 感想一覧 -->
                <?php if (!empty($_SESSION['comments'][$work['id']])): ?>
                    <ul class="comment-list" style="margin-top: 10px; padding-left: 20px;">
                        <?php foreach ($_SESSION['comments'][$work['id']] as $c): ?>
                            <li style="color: #333; margin-bottom: 5px;"><?= htmlspecialchars($c) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>

    <div style="text-align: center;">
        <a href="#" class="back-link" style="color: #1e90ff; text-decoration: none; display: inline-block; margin-top: 20px;">戻る</a>
    </div>
</div>

</body>
</html>
