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

if($_GET['action'] == "delete"){	
		$nid=$_POST['nid'];
		mysql_query("delete from bookmark where nid=$nid");
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
		<h4>Bookmark</h4>
		<form action="bookmark.php?action=delete" method="post">
		<?php
		$mark=mysql_query("select n.nid,uname,posttime,text,hyperlink from note n natural join user, bookmark b where n.nid=b.nid and b.uid=$uid");
		echo "<table border='1' style='margin-left:8px'>
		<tr>
		<th>note</th>
		<th>poster</th>
		<th>text</th>
		<th>hyperlink</th>
		<th>tag</th>
		<th>posttime</th>
		<th>operation</th>
		</tr>";
		
		while($com=mysql_fetch_array($mark)){
			echo"<tr>
			 <td> <input type='text' id='nid' name='nid' size='1' value= ".$com['nid']. " readonly='readonly' onclick='comment(".$com['nid'].")' /></td>
			 <td align='center'>".$com['uname']."</td>
			 <td align='center'>".$com['text']."</td>
			 <td align='center'>".$com['hyperlink']."</td>
			 <td align='center'>";
			 $nid=$com['nid'];
			 $note_tag = mysql_query("select tag from note_tag natural join tag where nid = $nid");
			 while($r_tag = mysql_fetch_array($note_tag)){
				echo "#".$r_tag['tag']."  ";
			 }
			 echo "</td>
			 <td align='center'>".$com['posttime']."</td>
			 <td align='center'><input type='submit' name='delete' value='delete'/></td>";
		}
		echo "</table>";

		?>
		
		
		</div>
		<script>
		function comment(nid){
			window.location.href="comment.php?nid="+nid;
		}
		</script>

		<div id="footer">
			<p>  Copyright &copy 2013 Xin Gao and Peng Zhou. All rights reserved.</p>
		</div>
	</body>
</html>