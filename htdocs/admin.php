<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8">

        <title>管理者画面</title>

    </head>

 
   <body>

        <h1 align="center">管理者用：予約状況確認画面</h1>
 		<p>
        </p>
 	<?php
	require 'db.php';


	$sql = 'SELECT * FROM reserve1'; # SQL文
	$prepare = $db->prepare($sql); # 準備
	$prepare->execute(); # 実行
	$result = $prepare->fetchAll(PDO::FETCH_ASSOC); # 結果の取得

	?>
	<table border="2" align="center">
	<tr>
		<th width="80">id</th><th width="100">氏名</th><th>予約席</th><th width="200">日程</th>
	</tr>

	<?php 
	foreach($result as $row){
       ?> 
	<tr> 
		<td><?php echo $row['id']; ?></td> 
		<td><?php echo htmlspecialchars($row['name'],ENT_QUOTES,'UTF-8'); ?></td> 
		<td><?php echo $row['sheet']; ?></td> 
		<td><?php echo $row['resdata']; ?></td> 
	</tr> 
	<?php 
	} 
	?>

	</table>
 	<br>
	<center>
	<a href="index.php" align="center">ログイン画面へ戻る</a><br>
	</center>
   </body>
</html>
