<html><head><title>Check Username</title></head> <body>
<?php

if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
include "koneksi.php";
$usename = $_GET['username'];
if($username=='') $username = $_POST['username'];

	$username = implode("_",explode(" ",strtolower($username)));

	$query = "SELECT * FROM t_member where username='".mysql_real_escape_string($username)."'";
    $result = mysql_query($query) or die("Query failed member");
    $r = mysql_num_rows($result);
	if($r>=1) echo " Maaf username ini sudah ada yang menggunakannya.";
	else echo " Selamat Anda boleh menggunakan username : ".$username;
	
?>
</body></html>