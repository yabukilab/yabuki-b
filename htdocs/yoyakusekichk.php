<?php
session_start();
$userid = $_SESSION['userid'];
$name = $_SESSION['name'];

$getday=$_GET['day']; # 前画面からのパラメータ
$gettime=$_GET['time'];

echo $name. 'さんがログインしています';


?>
<!DOCTYPE html>
<html>
<head>
	<form method="get" action="yoyakusekichk.php"  id="f1">
	<link rel="stylesheet" href="clickyoyaku.css">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>座席選択フォーム</title>
</head>
	<body>
	<div style="text-align:center">
    <h1 align="center">予約状況の確認</h1>
	<?php
	echo '日にちは、'.$getday;
	echo ' 時間は、' .$gettime. 'です';
	?>
	<p align="center">
	<br>
	<br>
	<br>
	<br>
	<?php
 	$seki =array("A1","B1","C1","D1","E1","F1","G1","H1","I1","J1","K1","A2","B2","C2","D2","E2","F2","G2","H2","I2","J2","K2","A3","B3","C3","D3","E3","F3","G3","H3","I3","J3","K3","A4","B4","C4","D4","E4","F4","G4","H4","I4","J4","K4","A5","B5","C5","D5","E5","F5","G5","H5","I5","J5","K5","A6","B6","C6","D6","E6","F6","G6","H6","I6","J6","K6","A7","B7","C7","D7","E7","F7","G7","H7","I7","J7","K7","A8","B8","C8","D8","E8","F8","G8","H8","I8","J8","K8","A9","B9","C9","D9","E9","F9","G9","H9","I9","J9","K9","A10","B10","C10","D10","E10","F10","G10","H10","I10","J10","K10","A11","B11","C11","D11","E11","F11","G11","H11","I11","J11","K11","L1","L3","M1","M3","N1","N3","O1","O3","P1","P3","Q1","L2","L4","M2","M4","N2","N4","O2","O4","P2","P4","Q2");
	$work = array();
	require 'db.php';

	$y_dt = $getday." " . $gettime . ":00";
	$sql = "SELECT sheet   FROM reserve1 where  resdata = '".$y_dt."'    " ; # SQL文

	$prepare = $db->prepare($sql); # 準備
	$prepare->execute(); # 実行
	$result = $prepare->fetchAll(PDO::FETCH_ASSOC); # 結果の取得
	$j=0;
	foreach($result as $row){
		$chk = array_search($row['sheet'], $seki);
		echo $row['sheet']; 
		echo $chk; 
		$work[$j]=$row['sheet'];
	}


   	for($i=0 ; $i < 143; $i++) {
	if(isset($_POST['.$seki[$i].'])) {
        	$work[$j]=$_POST['.$seki[$i].'];
		echo $work[$j];    	
	} 
    }?>
			    <br>

<!--	<table border="0" style="font-size: 20pt; line-height: 200%;>-->
	<table border="0" >
<?php	
	$lop=0;
	for($i = 0; $i < 13; $i++){  #テーブルの1行ごとに、件数を表示する ?>
	<tr>
<?php		for($j = 0; $j < 11; $j++){ 
			$num=$seki[$lop+$j];?>
			<td align="center"><input type="submit" name="<?php echo $nam;?>" value="<?php echo $num;?>"></td>
<?php		}?>
<?php		$lop=$j*($i+1);?>
		</tr>
<?php	}?>
	</table>
	
	<br><br>
	<input type="submit" value="確認"><br>
 	<a href="hyout.php" align="center">予約画面へ戻る</a><br>
</p>
</div>
</form>
</body>
</html>