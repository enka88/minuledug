<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['Admin'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}

$save =$_POST['save'];
if ($save=='') $save = $_GET['save'];
$status = $_POST['status'];
$atas = $_POST['atas'];
$tengah = $_POST['tengah'];
$bawah = $_POST['bawah'];
$id = $_POST['id'];

if ($save=='edit') {

	$atas =stripslashes($atas);
	$tengah =stripslashes($tengah);
	$bawah =stripslashes($bawah);
	$sql = "UPDATE t_temp_menu SET status='".mysql_real_escape_string($status)."',temp_atas='".mysql_real_escape_string($atas)."',temp_tengah='".mysql_real_escape_string($tengah)."',temp_bawah='".mysql_real_escape_string($bawah)."' WHERE idtemp='".mysql_real_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  //echo "<font>Perubahan Profil Lembaga berhasil<br>Silahkan pilih menu $nfile kembali  $file!!!</font>"; 
  //header("Location: ../".$folderadmin."/admin.php?mode=tempmenu");
  echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=tempmenu';</script>";
}
else {
	$atas =stripslashes($atas);
	$tengah =stripslashes($tengah);
	$bawah =stripslashes($bawah);
	
	$sql = "insert into t_temp_menu (status,temp_atas,temp_tengah,temp_bawah) values ('".mysql_real_escape_string($status)."','".mysql_real_escape_string($atas)."','".mysql_real_escape_string($tengah)."','".mysql_real_escape_string($bawah)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
 // echo "<font>Perubahan Profil Lembaga berhasil<br>Silahkan pilih menu $nfile kembali $file!!!</font>"; 
  //header("Location: ../".$folderadmin."/admin.php?mode=tempmenu");
  echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=tempmenu';</script>";
}
?>