<?php
session_start();

// ユーザー情報（仮）
$user = [
    'name' => '作者名',
    'icon' => 'images/user_icon.png',
];
if (!file_exists($user['icon'])) {
    $user['icon'] = 'images/default_icon.png';
}

// コメントセッション初期化
if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = [];
}

// GETパラメータ author で検索
$authorQuery = '';
$books = [];
if (!empty($_GET['author'])) {
    $authorQuery = trim($_GET['author']);
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode("inauthor:" . $authorQuery);
    $json = @file_get_contents($apiUrl);
    if ($json !== false) {
        $data = json_decode($json, true);
        if (!empty($data['items'])) {
            $books = $data['items'];
        }
    }
}

// 投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['work_id'], $_POST['comment'])) {
    $workId = $_POST['work_id'];
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        if (!isset($_SESSION['comments'][$workId])) {
            $_SESSION['comments'][$workId] = [];
        }
        $_SESSION['comments'][$workId][] = $comment;
    }
    // 投稿後リダイレクト（GETパラメータを保持）
    $redirectUrl = $_SERVER['PHP_SELF'];
    if ($authorQuery !== '') {
        $redirectUrl .= '?author=' . urlencode($authorQuery);
    }
    header("Location: $redirectUrl");
    exit;
}

// コメント数を作品ごとにカウント（API結果に反映）
foreach ($books as &$book) {
    $id = $book['id'];
    $count = isset($_SESSION['comments'][$id]) ? count($_SESSION['comments'][$id]) : 0;
    // APIのvolumeInfoにコメント数を入れておく
    $book['comment_count'] = $count;
}
unset($book);

// コメント数でソート（多い順）
usort($books, fn($a, $b) => ($b['comment_count'] ?? 0) - ($a['comment_count'] ?? 0));

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>感想投稿マイページ</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: auto; padding: 20px; }
        .author-info { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
        .icon-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc; }
        .works { display: grid; grid-template-columns: repeat(auto-fill,minmax(180px,1fr)); gap: 20px; }
        .work-item { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .work-item img { width: 100%; height: 220px; object-fit: contain; border-radius: 6px; background: #eee; }
        .title { font-weight: bold; margin-top: 8px; font-size: 14px; }
        .authors { font-size: 12px; color: #666; margin-bottom: 8px; }
        textarea { width: 100%; border-radius: 5px; border: 1px solid #ccc; padding: 8px; resize: vertical; }
        button { background-color: #1e90ff; color: #fff; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; margin-top: 6px; }
        .comment-list { margin-top: 10px; padding-left: 20px; list-style: disc; max-height: 120px; overflow-y: auto; }
        .comment-list li { margin-bottom: 5px; color: #333; font-size: 13px; }
    </style>
</head>
<body>

<div class="container">
    <h1>感想投稿マイページ</h1>

    <section class="author-info">
        <img src="<?= htmlspecialchars($user['icon']) ?>" alt="ユーザーアイコン" class="icon-img">
        <div class="author-name" style="font-size: 18px; font-weight: bold;"><?= htmlspecialchars($user['name']) ?></div>
    </section>

    <?php if ($authorQuery === ''): ?>
        <p>検索結果を表示するには、上部の画面2から作者名検索してください。</p>
    <?php else: ?>
        <h2>作者「<?= htmlspecialchars($authorQuery) ?>」の作品一覧</h2>

        <?php if (empty($books)): ?>
            <p>該当する作品はありません。</p>
        <?php else: ?>
            <div class="works">
                <?php foreach ($books as $book):
                    $info = $book['volumeInfo'] ?? [];
                    $title = $info['title'] ?? 'タイトル不明';
                    $authors = $info['authors'] ?? [];
                    $thumbnail = $info['imageLinks']['thumbnail'] ?? '';
                    $workId = $book['id'];
                    $commentCount = $book['comment_count'] ?? 0;
                ?>
                    <div class="work-item">
                        <?php if ($thumbnail): ?>
                            <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($title) ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 220px; background: #ddd; display: flex; align-items: center; justify-content: center; color: #999; border-radius: 6px;">画像なし</div>
                        <?php endif; ?>
                        <div class="title"><?= htmlspecialchars($title) ?></div>
                        <div class="authors"><?= htmlspecialchars(implode('、', $authors)) ?></div>
                        <div class="comment-count" style="color: #555; font-size: 13px; margin-bottom: 6px;">
                            (<?= $commentCount ?> 件の感想)
                        </div>

                        <!-- 感想投稿フォーム -->
                        <form method="post">
                            <input type="hidden" name="work_id" value="<?= htmlspecialchars($workId) ?>">
                            <textarea name="comment" rows="3" placeholder="感想を入力..." required></textarea>
                            <button type="submit">投稿</button>
                        </form>

                        <!-- 感想一覧 -->
                        <?php if (!empty($_SESSION['comments'][$workId])): ?>
                            <ul class="comment-list">
                                <?php foreach ($_SESSION['comments'][$workId] as $c): ?>
                                    <li><?= htmlspecialchars($c) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
