<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // テストユーザーでログイン中ということにする
    $_SESSION['user_id'] = 2;
    $_SESSION['username'] = 'tanaka';
}



$self_id = $_SESSION['user_id'];
$self_name = $_SESSION['username'];
$partner_name = $_GET['partner'] ?? '';
if ($partner_name === '') {
    die('相手ユーザーが指定されていません');
}


$host = 'localhost';
$dbname = 'mydb';
$db_user = 'testuser';
$db_pass = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 相手ユーザーIDを取得
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$partner_name]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$partner) {
        die('相手ユーザーが見つかりません');
    }
    $partner_id = $partner['id'];

    // チャット履歴を取得（自分⇔相手のメッセージ全部）
    $stmt = $pdo->prepare("SELECT sender_id, message, created_at FROM messages
                           WHERE (sender_id = ? AND receiver_id = ?)
                              OR (sender_id = ? AND receiver_id = ?)
                           ORDER BY created_at ASC");
    $stmt->execute([$self_id, $partner_id, $partner_id, $self_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DBエラー: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($partner_name) ?> さんとのチャット</title>
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .chat-container {
    width: 90%;
    max-width: 600px;
    height: 600px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .chat-header {
    padding: 20px;
    font-size: 1.4rem;
    font-weight: 700;
    color: #333;
    text-align: center;
    border-bottom: 1px solid #eee;
  }

  .chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background-color: #f0f2f5;
  }

  .message {
    display: flex;
    margin-bottom: 14px;
  }

  .message.self {
    justify-content: flex-end;
  }

  .message.other {
    justify-content: flex-start;
  }

  .bubble {
    max-width: 70%;
    padding: 12px 18px;
    border-radius: 18px;
    font-size: 1rem;
    line-height: 1.4;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    word-wrap: break-word;
  }

  .message.self .bubble {
    background-color: #dcf8c6;
    border-bottom-right-radius: 6px;
  }

  .message.other .bubble {
    background-color: #fff;
    border: 1px solid #ccc;
    border-bottom-left-radius: 6px;
  }

  .input-area {
    display: flex;
    padding: 15px 20px;
    background-color: #fff;
    border-top: 1px solid #eee;
  }

  .input-area textarea {
    flex: 1;
    resize: none;
    padding: 12px 15px;
    font-size: 1rem;
    border-radius: 12px;
    border: 1px solid #ccc;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .input-area textarea:focus {
    border-color: #1e90ff;
    box-shadow: 0 0 6px rgba(30,144,255,0.4);
  }

  .input-area button {
    margin-left: 15px;
    background-color: #1e90ff;
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0 25px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .input-area button:hover {
    background-color: #1c7cd6;
  }

  /* スクロールバー（webkit系） */
  .chat-messages::-webkit-scrollbar {
    width: 8px;
  }
  .chat-messages::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 4px;
  }
</style>
</head>
<body>
<div class="chat-container">
  <h2><?= htmlspecialchars($partner_name) ?> さんとのチャット</h2>

  <?php foreach ($messages as $msg): ?>
    <div class="message <?= $msg['sender_id'] == $self_id ? 'self' : 'other' ?>">
      <div class="bubble"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
    </div>
  <?php endforeach; ?>

  <form class="input-area" action="send_message.php" method="POST">
       <textarea name="message" rows="2" required></textarea>
       <input type="hidden" name="receiver" value="<?= htmlspecialchars($partner_name) ?>">
       <button type="submit">送信</button>
  </form>
</div>
</body>
</html>
