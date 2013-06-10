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

if($_GET['action']=="likemark"){
	$nid=$_POST['nid'];
	
	if(isset($_POST['like'])){
		mysql_query("insert into liked (nid,liker) values ('$nid','$uid')");
	}
	
	if(isset($_POST['dislike'])){
		mysql_query("delete from liked where nid=$nid and liker=$uid");
	}
	
	if(isset($_POST['mark'])){
		mysql_query("insert into bookmark (nid,uid) values ('$nid','$uid')");
	}
		
	
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
		<h4>Popular Note</h4>
		<?php
		$liked=mysql_query("select nid,uname,posttime,text,hyperlink, count(*) as count from liked natural join note natural join user where authority='everybody' group by nid order by count desc");
		echo "<table border='1' style='margin-left:8px'>
		<tr>
		<th>note</th>
		<th>poster</th>
		<th>text</th>
		<th>hyperlink</th>
		<th>tag</th>
		<th>posttime</th>
		<th>popularity</th>
		<th>operation</th>
		</tr>";
		
		while($com=mysql_fetch_array($liked)){
			 echo"<form action='like.php?action=likemark' method='post'>
			 <tr>
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
			 <td align='center'>".$com['count']."</td>
			 
			 <td align='center'>";
			 $check=$com['nid'];
			 
			 $li=mysql_query("select checkLiked('$check','$uid')");
			 while($ra=mysql_fetch_array($li)){
				  if($ra[0]==0){
					  echo "<input type='submit' name='like' value='like' style='font-size:20px' disabled='disabled'/>";
					  echo "<input type='submit' name='dislike' value='dislike' style='font-size:20px'/>";

				  }else{
					  echo "<input type='submit' name='like' value='like' style='font-size:20px' />";
					  echo "<input type='submit' name='dislike' value='dislike' style='font-size:20px' disabled='disabled'/>";
				  }
			 }
			 
			 $bm=mysql_query("select checkMarked('$check','$uid')");
			 while($rm=mysql_fetch_array($bm)){
				 if($rm[0]==0){
					 echo "<input type='submit' name='mark' value='mark' style='font-size:20px' disabled='disabled'/>";
				 }else{
					 echo "<input type='submit' name='mark' value='mark' style='font-size:20px' />";
				 }
			 }
			 echo "</td>
			 	   </form>";
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