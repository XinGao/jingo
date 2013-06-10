<?php
session_start();
ini_set('session.gc_maxlifetime',300);
//注销登录
if($_GET['action'] == "logout"){
    unset($_SESSION['uid']);
    unset($_SESSION['uname']);
    unset($_SESSION['state']);
    unset($_SESSION['curtime']);
    echo 'logout sucess <a href="login.html">login</a>';
    exit;
}

//登录
if(!isset($_POST['login'])){
    exit('invalid visit!');
}
//$EMAIL = htmlspecialchars($_POST['EMAIL']);
$uname = $_POST['uname'];
$pw = $_POST['pw'];

//包含数据库连接文件
include('conn.php');
//检测用户名及密码是否正确
$check_query = mysql_query("select uid from user where uname='$uname' and pw='$pw' 
limit 1");

if($result = mysql_fetch_array($check_query)){
    //登录成功
    $_SESSION['uname'] = $uname;
    $_SESSION['uid'] = $result['uid'];
 
} else {
    exit('login fail! Click here <a href="javascript:history.back(-1);">back</a> try again');
}

mysql_close($con)
?>




<html>
	<head>
		<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
		<title>jingo</title>
	</head>
	<body>
		<div id="header">
		<?php
		if($_SESSION['uid']!=null){
			echo "<a><p id='name'>".$_SESSION['uname']."</p></a>";
			echo "<a href='login.php?action=logout'><p id='logout'>logout</p></a>";
		} else {
			echo "<a href='login.html'><p id='name'>login</p></a>";
		}
		?>
		</div>
		<div class="left">   
			<a href="homepage.php"><p class="navigator">Homepage</p></a>
			<a href="friend.php"><p class="navigator">Friend</p></a>
			<a href="note.php"><p class="navigator">Note</p></a>
			<a href="filters.php"><p class="navigator">Filters</p></a>
			<a href="receive.php"><p class="navigator">Receive</p></a>
			<a href="like.php"><p class="navigator">Popular Note</p></a>
			<a href="bookmark.php"><p class="navigator">Bookmark</p></a>
		</div>
		<div class="right">
		<h1 style="margin-left:5px">Welcome to jingo!</h1>
		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xin Gao and Peng Zhou. All rights reserved.</p>
		</div>
	</body>
</html>


