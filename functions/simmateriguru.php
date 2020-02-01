<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['User'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}

$st =$_POST['st'];
$tgl = $_POST['tgl'];
$tgl2 = $_POST['tgl2'];
$thajar = $_POST['thajar'];
$pel = $_POST['pel'];
$sem = $_POST['sem'];
$nip = $_POST['nip'];
$richEdit0 = $_POST['richEdit0'];
$id=$_POST['id'];
//tambahan Ansari Saleh Ahmar
$kd=$_POST['kd'];
//akhir
$file = $_FILES['file'];

if ($st=="tambah") {
   $sql = "SELECT max(idtugas) AS total FROM t_tugas";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	if (file_exists($file['tmp_name'])) {
	    $ext = array_pop(explode(".", $file['name']));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 09 April 2012
		if ($ext<>'doc' && $ext<>'xls' && $ext<>'pdf' && $ext<>'zip' && $ext<>'docx' && $ext<>'xlsx' && $ext<>'pptx' && $ext<>'ppt' && $ext<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../member/user.php?id=gurulap'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

		$newfile="file".$total.".".$ext;
		if (file_exists("../materi/".$newfile)) unlink("../materi/".$newfile);
    	move_uploaded_file($file['tmp_name'], "../materi/$newfile");
	}
		$tgl2=date("Y-m-d");
  		$sql = "insert into t_tugas (idtugas,tgl_kirim,tgl_kumpul,thajar,pelajaran,sem,nip,isi,file) values ('$total','".mysql_real_escape_string($tgl2)."','".mysql_real_escape_string($tgl)."','".mysql_real_escape_string($thajar)."','".mysql_real_escape_string($pel)."','".mysql_real_escape_string($sem)."','".mysql_real_escape_string($nip)."','$richEdit0','".mysql_real_escape_string($ext)."')";
  		if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");	
		$tdk = "Pengiriman Tugas dan File berhasil";
			if (!empty($id))
			{
				while (list($key,$value)=each($id))		{
					$sql="insert into t_tugas_kelas (idtugas,kelas) values ('".mysql_real_escape_string($total)."','".mysql_real_escape_string($key)."')";
					$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
				}
			}
 }
else {
	$total =$kd;
	if ($file['name']<>"") {
	 if (file_exists($file['tmp_name'])) {
        $ext = array_pop(explode(".", $file['name']));
		$newfile="file".$total.".".$ext;
		if (file_exists("../materi/".$newfile)) unlink("../materi/".$newfile);
    	move_uploaded_file($file['tmp_name'], "../materi/$newfile");
	    $q = mysql_query("select file from t_tugas where idtugas='".mysql_real_escape_string($total)."'");
        if($r=mysql_fetch_array($q)) {
            if(file_exists("../materi/file".$total.".".$r['file'])) {
                unlink("../materi/file".$total.".".$r['file']);
            }
        }
	 }
	}
		$tgl2=date("Y-m-d");
  		$sql = "update t_tugas set tgl_kirim='".mysql_real_escape_string($tgl2)."',tgl_kumpul='".mysql_real_escape_string($tgl)."', thajar='".mysql_real_escape_string($thajar)."',pelajaran='".mysql_real_escape_string($pel)."',sem='".mysql_real_escape_string($sem)."',file='".mysql_real_escape_string($ext)."',isi='$richEdit0' where idtugas='".mysql_real_escape_string($total)."'";
  		if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");	
		$tdk = "Perubahan Pengiriman Tugas dan File berhasil ";

}
//header("Location: ../member/user.php?id=simmateri&kd=$tdk");
echo "<script>document.location.href = '../member/user.php?id=simmateri&kd=$tdk';</script>";
?>