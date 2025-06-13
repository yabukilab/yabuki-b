<?php
session_start();

// ユーザー情報（仮）
$user = [
    'name' => '村上 春樹',
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
    $redirectUrl = $_SERVER['PHP_SELF'];
    if ($authorQuery !== '') {
        $redirectUrl .= '?author=' . urlencode($authorQuery);
    }
    header("Location: $redirectUrl");
    exit;
}

// コメント数追加＆並び替え
foreach ($books as &$book) {
    $id = $book['id'];
    $count = isset($_SESSION['comments'][$id]) ? count($_SESSION['comments'][$id]) : 0;
    $book['comment_count'] = $count;
}
unset($book);
usort($books, fn($a, $b) => ($b['comment_count'] ?? 0) - ($a['comment_count'] ?? 0));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>感想投稿マイページ</title>
    <style>
        body {
            font-family: 'Hiragino Kaku Gothic ProN', sans-serif;
            margin: 0;
            background: #f9f9f9;
        }
        .container {
            max-width: 960px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .author-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .author-header img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        .author-name {
            font-size: 24px;
            font-weight: bold;
        }
        h1 {
            margin-bottom: 20px;
        }
        .works {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
        }
        .work-item {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 15px;
            display: flex;
            flex-direction: column;
        }
        .work-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
            background: #eee;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .authors {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }
        textarea {
            width: 100%;
            resize: vertical;
            padding: 8px;
            font-size: 13px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 8px;
        }
        button {
            margin-top: 6px;
            background-color: #007acc;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .comment-list {
            margin-top: 10px;
            font-size: 13px;
            padding-left: 18px;
            max-height: 100px;
            overflow-y: auto;
        }
        .comment-count {
            color: #333;
            font-size: 13px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>感想投稿マイページ</h1>

    <div class="author-header">
        <img src="<?= htmlspecialchars($user['icon']) ?>" alt="作者アイコン">
        <div class="author-name"><?= htmlspecialchars($user['name']) ?> さんのマイページ</div>
    </div>

    <?php if ($authorQuery === ''): ?>
        <p>画面2から作者名を検索して感想を投稿してください。</p>
    <?php else: ?>
        <h2>作者「<?= htmlspecialchars($authorQuery) ?>」の作品</h2>

        <?php if (empty($books)): ?>
            <p>該当する作品は見つかりませんでした。</p>
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
                            <div style="width:100%;height:220px;background:#ccc;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#666;">
                                画像なし
                            </div>
                        <?php endif; ?>
                        <div class="title"><?= htmlspecialchars($title) ?></div>
                        <div class="authors"><?= htmlspecialchars(implode('、', $authors)) ?></div>
                        <div class="comment-count">感想：<?= $commentCount ?> 件</div>

                        <form method="post">
                            <input type="hidden" name="work_id" value="<?= htmlspecialchars($workId) ?>">
                            <textarea name="comment" rows="3" placeholder="この作品についての感想を入力" required></textarea>
                            <button type="submit">感想を投稿</button>
                        </form>

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
