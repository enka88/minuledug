<?php
session_start();
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}
//echo '<link type="text/css" rel="stylesheet" media="all" href="css/kontenbox.css" />';
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
$userid = $_POST['userid'];
$userid = unhex($userid,$noacak);
$value=explode(",",$userid,2);
$kondisi=$value[0];$userid=$value[1];

if ($kondisi=='hapopini'){
	$kdopini=unhex($_POST['kdopini'],$noacak);
	$sql="delete from t_project where id='".mysql_real_escape_string($kdopini)."' and userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan oponi ");
	echo "Penghapusan opini berhasil";
}
elseif ($kondisi=='veriopini'){
	$kdopini=unhex($_POST['kdopini'],$noacak);
	$sql="update t_project  set status='1' where id='".mysql_real_escape_string($kdopini)."' and userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan oponi ");
	echo "Penghapusan opini berhasil";
}

?>