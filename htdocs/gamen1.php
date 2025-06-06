<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>読書記録交流アプリ</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      padding: 50px;
    }
    .container {
      background: white;
      padding: 30px 40px;
      border: 1px solid #ccc;
      border-top: 10px solid #0077c8;
      width: 400px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.5em;
    }
    .accent {
      font-weight: bold;
      color: #333;
    }
    label {
      display: block;
      margin-top: 10px;
      margin-bottom: 5px;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      background-color: #3b3b3b;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 5px;
      font-size: 1em;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>読書記録交流<span class="accent">アプリ</span></h1>
    <form>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">ログイン</button>
    </form>
  </div>
</body>
</html>
