<?php
session_start();

// 仮ユーザーデータ
$user = [
    'name' => '作者名',
    'icon' => 'images/user_icon.png' // ← ユーザーが設定したアイコン画像のパス
];

// アイコン画像が存在しない場合はデフォルトに
if (!file_exists($user['icon'])) {
    $user['icon'] = 'images/default_icon.png';
}

// 仮作品データ
$works = [
    ['id' => 1, 'title' => '作品A', 'image' => 'images/sample1.png', 'comments' => 5],
    ['id' => 2, 'title' => '作品B', 'image' => 'images/sample2.png', 'comments' => 12],
    ['id' => 3, 'title' => '作品C', 'image' => 'images/sample3.png', 'comments' => 8],
];

// 初期化（セッションコメント保存）
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
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// コメント数加算処理
foreach ($works as &$work) {
    $id = $work['id'];
    $count = isset($_SESSION['comments'][$id]) ? count($_SESSION['comments'][$id]) : 0;
    $work['comments'] += $count;
}
unset($work);

// コメント数で降順ソート
usort($works, fn($a, $b) => $b['comments'] - $a['comments']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mypage</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #fff;
            margin: 0;
        }

        h1 {
            background-color: #0078D7;
            color: white;
            padding: 10px;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .icon-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .works {
            margin-top: 20px;
        }

        .work-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            background: #fafafa;
        }

        .comment-form textarea {
            width: 100%;
            margin-top: 10px;
            padding: 5px;
            resize: vertical;
        }

        .comment-form button {
            margin-top: 5px;
            padding: 5px 10px;
        }

        .comment-list {
            margin-top: 10px;
            padding-left: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0078D7;
        }
    </style>
</head>
<body>

<h1>Mypage</h1>
<div class="container">
    <section class="author-info">
        <img src="<?= htmlspecialchars($user['icon']) ?>" alt="ユーザーアイコン" class="icon-img">
        <div class="author-name" style="font-weight: bold; font-size: 20px;">
            <?= htmlspecialchars($user['name']) ?>
        </div>
    </section>

    <section class="works">
        <h2>作品一覧</h2>

        <?php foreach ($works as $work): ?>
            <div class="work-item">
                <img src="<?= htmlspecialchars($work['image']) ?>" alt="作品画像" style="width: 100%; border-radius: 10px;">
                <strong><?= htmlspecialchars($work['title']) ?></strong><br>
                <span class="comment-count">(<?= $work['comments'] ?> 件の感想)</span>

                <!-- 感想投稿フォーム -->
                <form method="post" class="comment-form">
                    <input type="hidden" name="work_id" value="<?= $work['id'] ?>">
                    <textarea name="comment" placeholder="感想を入力..." rows="3" required></textarea><br>
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
            </div>
        <?php endforeach; ?>
    </section>

    <div style="text-align: center;">
        <a href="#" class="back-link">戻る</a>
    </div>
</div>

</body>
</html>
