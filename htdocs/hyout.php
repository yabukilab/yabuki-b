<?php
session_start();
$userid = $_SESSION['userid'];
$name = $_SESSION['name'];
echo $name;
echo 'さんがログインしています';
?>
<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8">

        <title>予約画面</title>

    </head>

 
   	<body>
	<?php
 		$yoyakuday =array("2021-07-01","2021-07-02","2021-07-03","2021-07-04","2021-07-05","2021-07-06","2021-07-07");
		$yoyakutime =array("08:45","09:00","09:15","09:30","09:45","10:00","10:15","10:30","10:45","11:00","11:15","11:30","11:45","12:00","12:15","12:30","12:45","13:00","13:15","13:30","13:45","14:00","14:15");
		$hantei=array("〇","△","×");
		$kakuno=array();

	?>

        <h1 align="center">座席予約ツール</h1>
        <h2 align="center">予約の件数を表示します</h2>
		<p>
      	</p>
      	<div align="center">
 
	<?php
	require 'db.php';

	for($i = 0; $i < 7; $i++){  #日付時間ごとにｓｑｌを実行し、予約数を取得する
		for($j = 0; $j < 23; $j++){
			$y_dt = $yoyakuday [$i]." " . $yoyakutime [$j] . ":00";
			$sql = "SELECT count(*)   FROM reserve1 where  resdata = '".$y_dt."'"; # SQL文

			$prepare = $db->prepare($sql); # 準備
			$prepare->execute(); # 実行
			$cnt = $prepare->fetchColumn();
			$result = $prepare->fetchAll(PDO::FETCH_ASSOC); # 結果の取得
			$kakuno[$i][$j]=$cnt;
		}
	}
	?>
	<p></p>
	
	<!-- <?php>#<a href="zaseki.php?day=<?php echo $yoyakuday [$i]; ?>"> time=<?php echo $yoyakutime [$j]?></a>?> -->

	<table border="2">
		<tr>
			<th> 時間／日程 </th><th> 7月1日 </th><th> 7月2日 </th><th> 7月3日 </th><th> 7月4日 </th><th> 7月5日 </th><th> 7月6日 </th><th> 7月7日 </th>
		</tr>

		<?php	for($i = 0; $i < 23; $i++){  #テーブルの1行ごとに、件数を表示する ?>
				<tr>
					<td align="center"><?php echo $yoyakutime [$i];?></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [0]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[0][$i];?></a></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [1]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[1][$i];?></a></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [2]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[2][$i];?></a></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [3]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[3][$i];?></a></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [4]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[4][$i];?></a></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [5]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[5][$i];?></a></td><td align="center"><a href="yoyakusekichk.php?day=<?php echo $yoyakuday [6]; ?>&time=<?php echo $yoyakutime [$i]?>"><?php echo $kakuno[6][$i];?></a></td>
				</tr>
		<?php	}?>
	</table>

 	<a href="menu.php" align="center">予約時間の選択画面へ戻る</a><br>
        </div>
  </body>


</html>
