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


if($_GET['action'] == "comment"){
	$t=$_POST['comtext'];
	$nid=$_SESSION['nid'];
	$add_com=mysql_query("INSERT INTO comment (nid,uid,context) VALUES ('$nid','$uid','$t')");
	
}else{
	$_SESSION['nid']=$_GET['nid'];
	$nid=$_SESSION['nid'];
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
		echo "<h4>Note</h4>";
		echo "<table border='1' style='margin-left:8px'>
		<tr>
		<th>poster</th>
		<th>text</th>
		<th>hyperlink</th>
		<th>tag</th>
		<th>posttime</th>
		</tr>";
		
		$com_note=mysql_query("select distinct poster,text,hyperlink,posttime from view_note where nid=$nid");
		while($com=mysql_fetch_array($com_note)){
		  echo"<tr>
			 <td align='center'>".$com['poster']."</td>
			 <td align='center'>".$com['text']."</td>
			 <td align='center'>".$com['hyperlink']."</td>
			 <td align='center'>";
			 $note_tag = mysql_query("select tag from note_tag natural join tag where nid = $nid");
			 while($r_tag = mysql_fetch_array($note_tag)){
				echo "#".$r_tag['tag']."  ";
			 }
			 echo "</td>
			 <td align='center'>".$com['posttime']."</td>";
		}
		echo "</table>";
				
		echo "<h4>Add Comment</h4>";
		echo "<form action='comment.php?action=comment' method='post'>
		      <p><textarea cols='45' rows='5' name='comtext'></textarea>
		      <input type='submit' name='comment' value='comment' style='margin-left:50px;font-size:30px'></p>";
		
		echo "<h4>Comment</h4>";
		
		echo "<div style='position:absolute; height:300px; overflow:auto'>
		<table border='1' style='margin-left:8px'>
		<tr>
		<th>user</th>
		<th>content</th>
		<th>comment time</th>
		</tr>";
		
		$comment=mysql_query("select uname,context,commenttime from comment natural join user where nid=$nid");
		while($res=mysql_fetch_array($comment)){
			echo "<tr>
				  <td align='center'>".$res['uname']."</td>
				  <td align='center'>".$res['context']."</td>
				  <td align='center'>".$res['commenttime']."</td>";
		}
		
		echo "</table>";
		echo "</div>";
		?>
		
		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xin Gao and Peng Zhou. All rights reserved.</p>
		</div>
	</body>
</html>