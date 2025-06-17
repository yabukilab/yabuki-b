<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }

        .header {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }

        .profile {
            display: flex;
            align-items: center;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            cursor: pointer;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .name-edit-form {
            display: none;
        }

        .container {
            padding: 20px;
        }

        .card {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .card img {
            height: 100px;
        }

        .card-content {
            flex-grow: 1;
        }

        .back-link {
            margin: 10px 20px;
        }
    </style>
</head>
<body>

    <!-- ヘッダー -->
    <div class="header">
        <div class="profile">
            <img src="<?= htmlspecialchars($user['icon']) ?>" alt="アイコン" id="userIcon">
            <span class="user-name" id="userName"><?= htmlspecialchars($user['name']) ?></span>
            <form id="editNameForm" class="name-edit-form" method="POST" action="update_name.php">
                <input type="text" name="new_name" value="<?= htmlspecialchars($user['name']) ?>">
                <input type="submit" value="保存">
            </form>
        </div>
    </div>

    <div class="container">
        <h2>作品一覧</h2>

        <?php if (count($comments) === 0): ?>
            <p>まだ感想が投稿されていません。</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($comment['thumbnail']) ?>" alt="cover">
                    <div class="card-content">
                        <strong><?= htmlspecialchars($comment['title']) ?></strong><br>
                        著作者：<?= htmlspecialchars($comment['authors']) ?><br>
                        感想：<?= nl2br(htmlspecialchars($comment['comment'])) ?><br>
                        投稿日：<?= htmlspecialchars($comment['created_at']) ?><br>
                        <button>修正</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="back-link">
            <a href="index.php">← 戻る</a>
        </div>
    </div>

    <script>
        const userName = document.getElementById('userName');
        const editForm = document.getElementById('editNameForm');

        userName.addEventListener('click', () => {
            userName.style.display = 'none';
            editForm.style.display = 'inline';
        });
    </script>
</body>
</html>

