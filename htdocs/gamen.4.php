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
            
            <?php
            // 仮の作品データ（データベースと連携する場合はここを変更）
            $works = [
                ['title' => '作品名1', 'image' => 'images/sample.png'],
                ['title' => '作品名2', 'image' => 'images/sample.png'],
                ['title' => '作品名3', 'image' => 'images/sample.png'],
            ];

            foreach ($works as $work) {
                echo '<div class="work-item">';
                echo '<img src="' . $work['image'] . '" alt="サムネイル">';
                echo '<span>' . $work['title'] . '</span>';
                echo '</div>';
            }
            ?>
        </section>

        <a href="#" class="back-link">戻る</a>
    </main>

</body>
</html>
