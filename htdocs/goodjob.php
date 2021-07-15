
<!DOCTYPE html>
<html>
<head>
	<form method="get" action=""> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>予約確認画面</title> 	
</head>
<body>
	<div style="text-align:center">
 
	<h1>予約します</h1>
	<p align="center">
<?php session_start();?>
<?php $nam =$_GET['name'];$dy=$_GET['day'];$tim= $_GET['time'];$sk= $_GET['seki'];?>
	
	<?php  	echo $_GET['name'] .'さんの予約をします。';?><br>
	<?php	echo '日時：' . $_GET['day'] . '  '. $_GET['time'] .'座席は、' . $_GET['seki'] . 'です。'; ?><br>
	<?php	echo '登録する場合は、登録ボタンを押してください。'; ?><br>
	</P>
	<a href="allright.php?name=<?php echo $nam; ?>&day=<?php echo $dy; ?>&time=<?php echo $tim?>&seki=<?php echo $sk;?>"><input type="button" name="add" value="登録"><br>
	<a href="yoyakusekichk.php?name=<?php echo $nam; ?>&day=<?php echo $dy; ?>&time=<?php echo $tim?>" align="center">予約状況の画面へ戻る</a><br>

</body>
</html>
