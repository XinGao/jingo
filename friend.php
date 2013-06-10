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

 
date_default_timezone_set('America/New_York');
$time = date('Y-m-d H:i:s');

if(isset($_POST['Confirmed'])){
    $n=$_POST['requester'];
   
   $response = "UPDATE friendrequest SET responsetime='$time', status='Confirmed' where uid=getUID('$n') and friend_id=$uid and status is null";
   mysql_query($response);

}

if(isset($_POST['Declined'])){
	$n=$_POST['requester'];
    
    $response = "UPDATE friendrequest SET responsetime='$time', status='Declined' where uid=getUID('$n') and friend_id=$uid and status is null";
	mysql_query($response);
}

if(isset($_POST['Search'])){
	$un=$_POST['unameS'];
	if($un != null){
		$search= "SELECT uid, uname FROM user WHERE uname like '%$un%' and uid !=$uid and (uid not in (select u.uid from friendrequest f, user u where f.friend_id=u.uid and f.uid=$uid and status='Confirmed')) and (uid not in (select uid from friendrequest natural join user where friend_id=$uid and status='Confirmed'))";
	}
}

if(isset($_GET['request_sent'])){
	$request_sent=$_GET['request_sent'];
    $requestSent="INSERT INTO friendrequest (uid,friend_id,senttime) VALUES('$uid','$request_sent','$time')";
	mysql_query($requestSent);
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
		<h4>Friend Request</h4>
		<p>
		<?php
		$friend_request = mysql_query("select * from user natural join friendrequest where friend_id=$uid and status is NULL");
		echo "<table border='1' style='margin-left:8px'>
		<tr>
		<th>requester</th>
		<th>request_time</th>
		<th>response</th>
		</tr>";

        while($row = mysql_fetch_array($friend_request))
        {
        	echo
        		 "<form action='friend.php?' method='POST'> 
        		  <tr>
    
        		  <td align='center'><input type='text' name='requester' value=".$row['uname']." readonly='readonly' /></td>
        		  <td align='center'><input type='text' name='senttime' value=".$row['senttime']." readonly='readonly' /></td>
        	      <td> 
        	      <input type='submit' name='Confirmed' value='Confirmed' /> 
        	      <input type='submit' name='Declined' value='Declined' /> 
        	      </td>
        	      </tr>
        	      </form>";
        }
       
        echo "</table>"; 
		?>		
		</p>
		
		<h4> Search Friend </h4>
		<form action="friend.php" method="post" >
		<p><strong>uname:</strong> <input type="text" name="unameS" />
		<input type="submit" value="Search" name="Search" style="margin-left:30px;font-size:30px"/><p>
		</form>
		<?php
		$friend = mysql_query($search);
		echo "<table border='1' style='margin-left:8px'>";
		while($r=mysql_fetch_array($friend)){
			echo "<td>".'<a href = "friend.php?request_sent='.$r['uid'].'">'.$r['uname']."</td>";
		}
		echo "</table>";
		?>
		
		<h4> Friends </h4>
		<?php
		$all_friend="(select uname from friendrequest f, user u where f.friend_id=u.uid and f.uid=$uid and status='Confirmed') union (select uname from friendrequest natural join user where friend_id=$uid and status='Confirmed')";
		echo "<table border='1' style='margin-left:8px'>";
		$allfriends = mysql_query($all_friend);
		while($a=mysql_fetch_array($allfriends)){
			echo "<td align='center'>".$a['uname']."</td>";
		}
		echo "</table>";
		
		mysql_close($con);
		?>

		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xin Gao and Peng Zhou. All rights reserved.</p>
		</div>
	</body>
</html>