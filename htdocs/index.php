<?php
session_start();
$message='ログインしてください';




if(isset($_POST['userid'], $_POST['name'])){
   	$userid=$_POST['userid'];
  	$name=$_POST['name'];

#	$dbServer = '127.0.0.1';
#	$dbName = 'mydb';
#	$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8mb4";

#	$dbUser = 'user';
#	$dbPass = 'pass';
	//データベースへの接続
#	$db = new PDO($dsn, $dbUser, $dbPass);


	require 'db.php';
	//検索実行
	$sql = 'select * from user1 where userid = "'.$userid.'" && name = "'.$name.'"';
	$prepare = $db->prepare($sql);
	$prepare->execute();
	$result = $prepare->fetchAll(PDO::FETCH_ASSOC);

	if($result != null){

		session_regenerate_id();
  		$_SESSION['userid']=$userid;
 		$_SESSION['name']=$name;
		if($userid == '1942006'){
			$_SESSION['admin'] = true;
 			header('Location: admin.php');
		} else {
 			header('Location: menu.php');
		}
 	}
	$message='ユーザ名またはパスワードが違います。';
	
}
?>
<IDOCTYPE html>
<html lang="ja">
    <head>

        <meta charset='utf-8'/>
	<link rel = 'stylesheet' href='style.css' />

        <title>ログイン画面</title>

    </head>

 
   <body>
        <?php echo $message;?> 
	<p>今日の日付を表示します</p>

	<?php
	date_default_timezone_set('Asia/Tokyo');
	echo date("Y/m/d H:i:s");
	?>
	<h1 align="center">座席予約ツール</h1>

 　　　<p>
 　　　</p>

        <div align="center">

            <table border="0">

                <form action="index.php" method="post">

                    <tr>
                       <h2> 学籍番号と 氏名を入力して下さい。</h2>
                       <th>
 
                           学籍番号

                        </th>

                        <td>
 
                           <input type="password" name="userid" value="" size="24">

                        </td>

                    </tr>

                    <tr>

                        <th>

                           氏名

                        </th>

                        <td>

                            <input type="text" name="name" value="" size="24">

                        </td>

                    </tr>

                    <tr>

                        <td colspan="2">

                            <input type="submit" value="ログイン">

                        </td>

                    </tr>

                </form>

            </table>

        </div>
    </body>

</html>