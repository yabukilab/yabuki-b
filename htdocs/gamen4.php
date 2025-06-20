<?php
// ダミーデータ（通常はDBから取得）
$works = [
    ['title' => '作品名', 'rating' => 3, 'comment' => '感想'],
    ['title' => '作品名', 'rating' => 2, 'comment' => '感想'],
    ['title' => '作品名', 'rating' => 3, 'comment' => '感想']
];

function printStars($count) {
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        $stars .= $i < $count ? '★' : '☆';
    }
    return $stars;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="style.css"> <!-- 外部CSS読み込み -->
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .avatar {
            width: 60px;
            height: 60px;
            background-color: #888;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            font-size: 1.4rem;
            margin-right: 15px;
        }

        .username {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .subtext {
            font-size: 0.9rem;
            color: #666;
        }

        .work-item {
            display: flex;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0 4px rgba(0,0,0,0.1);
        }

        .thumbnail {
            width: 70px;
            height: 70px;
            background-color: #ddd;
            margin-right: 15px;
            border-radius: 5px;
        }

        .work-details {
            flex-grow: 1;
        }

        .work-title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .stars {
            color: #f39c12;
            margin-left: 10px;
        }

        .comment {
            margin-top: 5px;
            color: #555;
        }

        .edit-button {
            margin-top: 10px;
            font-size: 1rem;
            padding: 6px 12px;
            border: none;
            background-color: #1e90ff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #1c7cd6;
        }

        .back-link {
            margin-top: 30px;
            text-align: left;
        }

        .back-link a {
            color: #1e90ff;
            text-decoration: none;
            font-size: 1rem;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="profile-header">
            <div class="avatar">F</div>
            <div>
                <div class="username">名前</div>
                <div class="subtext">mypage</div>
            </div>
        </div>

        <h1><span class="accent">作品一覧</span></h1>

        <?php foreach ($works as $work): ?>
        <div class="work-item">
            <div class="thumbnail"></div>
            <div class="work-details">
                <div class="work-title">
                    <?= htmlspecialchars($work['title']) ?>
                    <span class="stars"><?= printStars($work['rating']) ?></span>
                </div>
                <div class="comment"><?= htmlspecialchars($work['comment']) ?></div>
                <button class="edit-button">修正</button>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="back-link">
            <a href="#">← 戻る</a>
        </div>
    </div>

</body>
</html>
