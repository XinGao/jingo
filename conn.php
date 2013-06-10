<?php
/*****************************
*db connection
*****************************/
$conn = @mysql_connect('127.0.0.1:8889','root','root');
if (!$conn){
    die("fail to connect database：" . mysql_error());
}
mysql_select_db("jingodb", $conn);
