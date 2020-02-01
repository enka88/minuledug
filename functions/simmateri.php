<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['User'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}

$st =$_POST['st'];
$idtugas = $_POST['idtugas'];
$nis = $_POST['nis'];
$pesan = $_POST['pesan'];
$file = $_FILES['file'];

if ($st=="") {
   $sql = "SELECT max(ids) AS total FROM t_tugas_siswa";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
    if($_FILES['file']['name'] == '') {
    }
    else {
	if (file_exists($file['tmp_name'])) {

        $ext = array_pop(explode(".", $_FILES['file']['name']));
        if ($ext=='php' || $ext=='html' || $ext=='htm' || $ext=='exe') $ext='doc';
		$newfile="tgs$nis-".$total.".".$ext;
		if (file_exists("../tugas/".$newfile)) unlink("../tugas/".$newfile);
    	move_uploaded_file($file['tmp_name'], "../tugas/$newfile");
		$tgl=date("Y-m-d");
  		$sql = "insert into t_tugas_siswa (ids,idtugas,nis,tgl,status,pesan,file) values ('$total','".mysql_real_escape_string($idtugas)."','".mysql_real_escape_string($nis)."','$tgl','0','".mysql_real_escape_string($pesan)."','$newfile')";
  		if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");	
		$tdk = "Pengiriman Tugas dan File berhasil";
		}
	else {
		$tdk="File Tugas yang diimputkan tidak ada ".$file['name'];
        
		}
	}
 }
else {
	$total =$st;
    if($_FILES['file']['name'] == '') {
    }
    else {
	if (file_exists($file['tmp_name'])) {
		$ext = array_pop(explode(".", $_FILES['file']['name']));
        if ($ext=='php' || $ext=='html' || $ext=='htm' || $ext=='exe') $ext='doc';
		$newfile="tgs$nis-".$total.".".$ext;
		if (file_exists("../tugas/".$newfile)) unlink("../tugas/".$newfile);
    	move_uploaded_file($file['tmp_name'], "../tugas/$newfile");
		$tgl=date("Y-m-d");
  		$sql = "update t_tugas_siswa set tgl='$tgl',pesan='".mysql_real_escape_string($pesan)."',file='$newfile' where ids='".mysql_real_escape_string($total)."'";
  		if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");	
	    $q = mysql_query("select file from t_tugas_siswa where idtugas='".mysql_real_escape_string($total)."'");
        if($r=mysql_fetch_array($q)) {
            if(file_exists("../tugas/".$r['file'])) {
                unlink("../tugas/".$r['file']);
            }
        }
		$tdk = "Pengiriman Tugas dan File berhasil ";
	}
	else {
		$tdk="File Tugas yang diimputkan tidak ada";
		}
	}
}
//header("Location: ../member/user.php?id=simmateri&kd=$tdk");
echo "<script>document.location.href = '../member/user.php?id=simmateri&kd=$tdk';</script>";
?>