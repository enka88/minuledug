<?php
include "../functions/koneksi.php";
$request = trim(strtolower($_REQUEST['username']));
$valid = 'true';
$polauser =  "^[a-zA-Z0-9_]{1,}$";
if(preg_match("/^$polauser/",$request)) {
	$sql2="select userid,username from t_member where username='".mysql_real_escape_string($request)."' ";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 cek username");
	if($rows=mysql_fetch_array($query2)) {
		 $valid = 'false';
	}
}
else $valid = 'false';
echo $valid;
?>