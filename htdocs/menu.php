<?php
session_start();
$userid = $_SESSION['userid'];
$name = $_SESSION['name'];
echo $name;
echo 'さんがログインしています';
?>
<html>
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8">

        <title>メニュー画面</title>

    </head>

 
   <body>

        <h1 align="center">座席予約ツール</h1>

 　　　<p>
 　　　</p>

        <div align="center">

            <table border="0">

 
              <tr>

                        <td colspan="2">
				<input type="button" onclick="location.href='hyout.php'" value="予約時間の選択へ">

                        </td>

                    </tr>

 
            </table>
	<br>
	<br>
	<center>
	<a href="index.php" align="center">ログイン画面へ戻る</a><br><br>

        <a href="logout.php">ログアウト</a>
	</center>

        </div>

    </body>

</html>
