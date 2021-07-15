<?php
session_start();

#if(isset($_GET['add'])){
	require 'db.php';
	$nam =$_GET['name'];
	$dy=$_GET['day'];
	$tim= $_GET['time'];
	$sk= $_GET['seki'];

	$y_dt = $dy." " . $tim . ":00";
	# INSERT文を変数に格納
	$sql = "INSERT INTO reserve1  (name, sheet, resdata) VALUES ('".$nam."', '".$sk."', '".$y_dt."')";
 
	$prepare = $db->prepare($sql); # 準備
	$prepare->execute(); # 実行

	# 登録完了のメッセージ
	echo '登録完了しました';
#}
?>


<!DOCTYPE html>
<html>
    <head>
	<form method="get" action="">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>ログイン画面</title>
    </head>

 
   <body>
	<form method="get" action="goodjob.php"> 
        <h1 align="center">座席予約ツール</h1>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 　　　<p></p>
        <div align="center">
            <table border="1">
              <tr><td colspan="2">ご予約ありがとうございます</td></tr>
            </table>
	<a href="yoyakusekichk.php?name=<?php echo $nam; ?>&day=<?php echo $dy; ?>&time=<?php echo $tim?>" align="center">予約状況の画面へ戻る</a><br>
	</div>
</body>
</form>
</html>