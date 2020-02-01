<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['Admin'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}

$save =$_POST['save'];
if($save=='') $save=$_GET['save'];
$id = $_POST['id'];
if($id=='') $id=$_GET['id'];

$pilfungsi = $_POST['pilfungsi'];
$nfile = $_FILES['nfile'];
$menu=$_POST['menu'];
$posisi=$_POST['posisi'];
$kategori=$_POST['kategori'];
$urut=$_POST['urut'];
$idtemp=$_POST['idtemp'];
$pilfungsi=$_POST['pilfungsi'];


if ($save=='edit') {
$nmfile='';
	if($nfile['tmp_name'] == '') {
		if ($pilfungsi<>'-') $nmfile=",fungsi='$pilfungsi'";
    }
    else {
	if (file_exists($nfile['tmp_name'])) {
		$a=$nfile['name'];
		$m=strlen($a);
		$file=strtolower(substr($nfile['name'],0,$m-4));
		if (file_exists("../modul/tag_".$file.".php")) unlink("../modul/tag_".$file.".php");
    	copy($nfile['tmp_name'], "../modul/tag_".$file.".php");
		$nmfile=",fungsi='$file'";
	}
	}
	
	$sql = "UPDATE t_pos_menu SET menu='".mysql_real_escape_string($menu)."',posisi='".mysql_real_escape_string($posisi)."',kategori='".mysql_real_escape_string($kategori)."',urut='".mysql_real_escape_string($urut)."',idtemp='".mysql_real_escape_string($idtemp)."' $nmfile WHERE id ='".mysql_real_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  //echo "<font>Perubahan Profil Lembaga berhasil<br>Silahkan pilih menu $nfile kembali  $file!!!</font>"; 
  //header("Location: ../".$folderadmin."/admin.php?mode=posmenu");
  echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=posmenu';</script>";
}
else {
$file='';
	if($nfile['name'] == '') {
		if ($pilfungsi<>'-') $file="$pilfungsi";
    }
    else {
	if (file_exists($nfile['tmp_name'])) {
		$a=$nfile['name'];
		$m=strlen($a);
		$file=strtolower(substr($nfile['name'],0,$m-4));
		if (file_exists("../modul/tag_".$file.".php")) unlink("../modul/tag_".$file.".php");
    	copy($nfile['tmp_name'], "../modul/tag_".$file.".php");
		//$nmfile=",fungsi='$file'";
	}
	}
	
	$sql = "insert into t_pos_menu (menu,posisi,kategori,urut,idtemp,fungsi) values ('".mysql_real_escape_string($menu)."','".mysql_real_escape_string($posisi)."','".mysql_real_escape_string($kategori)."','".mysql_real_escape_string($urut)."','".mysql_real_escape_string($idtemp)."','$file')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
 // echo "<font>Perubahan Profil Lembaga berhasil<br>Silahkan pilih menu $nfile kembali $file!!!</font>"; 
 // header("Location: ../".$folderadmin."/admin.php?mode=posmenu");
  echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=posmenu';</script>";
}
?>