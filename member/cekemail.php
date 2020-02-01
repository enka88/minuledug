<?php
include "../functions/koneksi.php";
$request = trim(strtolower($_REQUEST['email']));
$valid = 'true';
session_start();
$userid = $_SESSION['User']['userid'];

$sql2="select email,userid,nama from t_member where email='".mysql_real_escape_string($request)."' ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 cek email");
if($rows=mysql_fetch_array($query2)) {
	if ($rows[userid]==$userid) $valid = 'true';
	else $valid = 'false';

}
echo $valid;
?>