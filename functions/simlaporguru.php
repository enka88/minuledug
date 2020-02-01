<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['User'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}
$st =$_POST['st'];
$judul = $_POST['judul'];
$nip = $_POST['nip'];
$kd = $_POST['kd'];
$file = $_FILES['file'];
if ($st=="tambah") {
   $sql = "SELECT max(idlap) AS total FROM t_laporan";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	 $tgl=date("Y-m-d");
	 
	if (file_exists($file['tmp_name'])) {
		$a=$_FILES['file']['name'];
		$m=strlen($a);
		$p=strtolower(substr($_FILES['file']['name'],$m-3,3));
		$newfile="lap-".$total.".".$p;

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 09 April 2012
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx') {
		echo "Format laporan harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., atau .ZIP., <a href='../member/user.php?id=gurulap'>Kembali</a>";
		exit;}
// Akhir

		if (file_exists("../laporan/".$newfile)) unlink("../laporan/".$newfile);
    	copy($file['tmp_name'], "../laporan/$newfile");
		$sql = "insert into t_laporan (idlap,tgl_kirim,judul,status,file,nip) values ('$total','$tgl','".mysql_real_escape_string($judul)."','0','$newfile','".mysql_real_escape_string($nip)."')";
	}
	else  { $sql = "insert into t_laporan (idlap,tgl_kirim,judul,status,nip) values ('$total','$tgl','".mysql_real_escape_string($judul)."','0','".mysql_real_escape_string($nip)."')"; }

  		if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");	
		$tdk = "Pengiriman laporan dan File berhasil";
 }
else {
	$total =$kd;
	$tgl=date("Y-m-d");
	$q=mysql_query("select * from t_laporan where idlap='".mysql_real_escape_string($kd)."' ");
	$row=mysql_fetch_array($q);
	$filelama=$row[file];
	if (!empty($_FILES['file']['name'])) {
	 if (file_exists($file['tmp_name'])) {
		$a=$_FILES['file']['name'];
		$m=strlen($a);
		$p=strtolower(substr($_FILES['file']['name'],$m-3,3));
		$newfile="lap-".$total.".".$p;

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 09 April 2012
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx') {
		echo "Format laporan harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., atau .ZIP., <a href='../member/user.php?id=gurulap'>Kembali</a>";
		exit;}
// Akhir

		if (file_exists("../laporan/".$filelama)) unlink("../laporan/".$filelama);
    	copy($file['tmp_name'], "../laporan/$newfile");
		$sql = "update t_laporan set tgl_kirim='$tgl',judul='".mysql_real_escape_string($judul)."',status='0',file='$newfile' where idlap='".mysql_real_escape_string($total)."'";
	 }
	}
	else {$sql = "update t_laporan set tgl_kirim='$tgl',judul='".mysql_real_escape_string($judul)."',status='0' where idlap='".mysql_real_escape_string($total)."'"; }

  	if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal2 ".$sql);	
	$tdk = "Perubahan Pengiriman laporan dan File berhasil ";

}
//header("Location: ../member/user.php?id=simmateri&kd=$tdk");
echo "<script>document.location.href = '../member/user.php?id=simmateri&kd=$tdk';</script>";

?>