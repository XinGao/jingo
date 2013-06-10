<?php
session_start();
ini_set('session.gc_maxlifetime',300);

if($_GET['action'] == "logout"){
    unset($_SESSION['uid']);
    unset($_SESSION['uname']);
    unset($_SESSION['state']);
    unset($_SESSION['curtime']);
    echo 'logout sucess <a href="login.html">login</a>';
    exit;
}

include('conn.php');
$uid=$_SESSION['uid'];

$name=$_POST['name'];
$age=$_POST['age'];
$phone=$_POST['phone'];


if(isset($_POST['name'])||isset($_POST['age'])||isset($_POST['phone'])){
	$info_update = "UPDATE user SET name='$name', age='$age', phone='$phone' where uid='$uid'";
	mysql_query($info_update);
}

?>

<!DOCTYPE html>
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
		<?php
		$user_info = mysql_query("select * from user where uid=$uid");
		if($result=mysql_fetch_array($user_info)){
		    echo "<h4> Registration Information </h4>";
			echo "<p> <strong> userName: </strong> ".$result['uname']."</p>";
			echo "<p> <strong> email: </strong> ".$result['email']."</p>";
			echo "<p> <strong> registration date: </strong> ".$result['date']."</p>";
			echo "<h4> Personal Information </h4>";
			echo "<p> <strong> name: </strong> ".$result['name']."</p>";
			echo "<p> <strong> age: </strong> ".$result['age']."</p>";
			echo "<p> <strong> phone: </strong> ".$result['phone']."</p>";		
		}
		mysql_close($con);
		?>
		
		<h4> Update Information </h4>
		<form action="homepage.php" method="post">
		<p> <strong>name:</strong> <input type="text" name="name" value="<?php echo $result['name'];?>"/></p>
		<p> <strong>age:</strong> <input type="text" name="age" value="<?php echo $result['age'];?>"/></p>
		<p> <strong>phone:</strong> <input type="text" name="phone" value="<?php echo $result['phone'];?>"/></p>
		<input type="submit" value="update" style='margin-left:8px;font-size:30px' />
		</form>
		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xin Gao and Peng Zhou. All rights reserved.</p>
		</div>
	</body>
</html>