<?php session_start();
session_start();
include "../functions/koneksi.php";
$username = addslashes($_POST['user_name']);
$password = addslashes($_POST['password']);
$username=strtolower($username);
$password=md5($password);

$sql="SELECT * FROM t_member WHERE username='".mysql_real_escape_string($username)."'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

if(mysql_num_rows($result)>0)
{
	if(strcmp($row['password'],$password)==0)
	{
			if($row['status']=="1") {
			   $_SESSION['User'] = $row;
			   $userid = $_SESSION['User']['userid'];
			   $q=mysql_query("update t_member set stlogin='1',tgl_login=NOW(),ip='".$_SERVER['REMOTE_ADDR']."',totlogin=totlogin+1,point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
               echo "yes";
			}
			else {
				echo "no";
			} 
	}
	else
		echo "no"; 
}
else
	echo "no"; //Invalid Login


?>