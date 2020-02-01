<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}

// tambahan agar guru yang jadi  kepesek/admin bisa akses by Ansari Saleh Ahmar 10 Januari 2012
// if ($level=='Guru' or $level=='Admin')

// fungsi untuk upload materi ajar dan kumpulan soal
// kumpulan materi ajar

function tamdownload() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .='<h3>Tambah Materi Ajar</h3>';
if ($level=='Guru' or $level=='Admin') { // Tambahan Ansari 22/11/2012
$cetak .= "Penambahan Materi Ajar ini akan mengupload file yang dapat diakses umum oleh member komunitas ini.
<form action='user.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='50' maxlength='180' >
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" ></textarea> 
              </td></tr> 
			  <tr><td width='24%'><font>Kategori</font></td>
			  <td width='76%'><select name=kategori>";
	$query = "SELECT idpel,pel FROM t_pelajaran  order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {

		if ($row[kategori]==$k[pel]) echo"<option value='$k[pel]' selected >$k[pel]</option>";
		else $cetak .="<option value='$k[pel]'>$k[pel]</option>";
	}			  
	$query = "SELECT * FROM t_kategori where jenis='1' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[kategori]==$k[kategori]) $cetak .="<option selected value='$k[kategori]'>$k[kategori]</option>";
		else $cetak .="<option value='$k[kategori]'>$k[kategori]</option>";
	}
    $cetak .= "</select></td></tr> 
			<tr><td width='24%'><font>File Materi</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font><font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP.
              </td></tr>
			<tr> <td valign=top >Kode Verifikasi</td><td><img src='../functions/captcha/captcha.php'> <br><input type='text' name='code' size='12' >
                <input type=\"hidden\" name=\"id\" value=\"downloadsave\"><br><br>
                <input type=\"submit\" value=\"Simpan\" id=button2 >
              </td></tr></table></form>";
}
else $cetak .="Fasilitas ini hanya untuk Guru di lingkungan ".$nmsekolah ;
$cetak .="</div>";
return $cetak;
}

// fungsi simpan materi
function downloadsave() {
include "koneksi.php";
//$code=$_POST['code'];
$code = $_POST['code'];
$userid = $_SESSION['User']['userid'];
$judul = $_POST['judul'];
$ket = $_POST['ket'];
$kategori =$_POST['kategori'];
$file = $_FILES['file'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= "<h3>Tambah Materi Ajar</h3>";
if ($level=='Guru' or $level=='Admin') { // Tambahan Ansari 22/11/2012
if ($judul=='') $cetak .="Judul masih kosong, Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";
elseif ($ket=='')  $cetak .="Keterangan masih kosong, Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";
elseif (strtoupper($code) != $_SESSION['captcha']) {
	die ("<body onload=\"alert('Kode verifikasi salah');window.history.back()\">");
}
elseif (empty($file['name']))  $cetak .="File masih kosong, Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
$ext = array_pop(explode(".", $file['name']));
elseif ($ext<>'doc' && $ext<>'xls' && $ext<>'pdf' && $ext<>'zip' && $ext<>'docx' && $ext<>'xlsx' && $ext<>'pptx' && $ext<>'ppt' && $ext<>'swf' )
		{
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";
		exit;}
// Akhir

else {
	$tgl = date("d/m/Y")." ".date("H:i:s");
     $sql = "SELECT max(id) AS total FROM t_download";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;

	$ext = array_pop(explode(".", $file['name']));
	$ukuran =$file['size'];
	$n=strlen($ukuran);
	if ($n>3) $ukuran=substr($ukuran,0,$n-3).",".substr($ukuran,$n-3,2)." Kbytes";
	else $ukuran.=" bytes";
	$target_file = "../download/al".$total.".".$ext;
	if(@move_uploaded_file($file['tmp_name'], $target_file)) {
		$sql = "insert into t_download (id,judul,deskripsi,kategori,file,ukuran,tanggal) values ('$total','".mysql_real_escape_string($judul)."','".mysql_real_escape_string($ket)."','".mysql_real_escape_string($kategori)."','al".$total.".".$ext."','".$ukuran."','".mysql_real_escape_string($tgl)."')";
		 if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal 1 ");
		 $cetak .="Penyimpanan Materi Ajar berhasil dilakukan.";
		 $kodeRandom='';
	 }
    }
  }
  return $cetak;
}
// kumpulan soal-soal
function tamsoal() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .='<h3>Tambah Materi Uji</h3>';
if ($level=='Guru' or $level=='Admin') { // Tambahan Ansari 22/11/2012
$cetak .= "Penambahan Kumpulan Soal/Materi Uji ini akan mengupload file yang dapat diakses umum oleh member komunitas <form action='user.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='50' maxlength='180' >
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" ></textarea> 
              </td></tr> 
			  <tr><td width='24%'><font>Kategori</font></td>
			  <td width='76%'><select name=kategori>";
	$query = "SELECT idpel,pel FROM t_pelajaran  order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {

		if ($row[kategori]==$k[pel]) echo"<option value='$k[pel]' selected >$k[pel]</option>";
		else $cetak .="<option value='$k[pel]'>$k[pel]</option>";
	}			  
	$query = "SELECT * FROM t_kategori where jenis='2' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[kategori]==$k[kategori]) $cetak .="<option selected value='$k[kategori]'>$k[kategori]</option>";
		else $cetak .="<option value='$k[kategori]'>$k[kategori]</option>";
	}
    $cetak .= "</select></td></tr> 
			<tr><td width='24%'><font>File Download</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP. 
              </td></tr>
			<tr> <td valign=top >Kode Verifikasi</td><td><img src='../functions/captcha/captcha.php'> <br><input type='text' name='code' size='12' >
                <input type=\"hidden\" name=\"id\" value=\"soalsave\"><br><br>
                <input type=\"submit\" value=\"Simpan\" id=button2 >
              </td></tr></table></form>";
}
else $cetak .="Fasilitas ini hanya untuk Guru di lingkungan ".$nmsekolah ;
$cetak .="</div>";
return $cetak;
}

// fungsi simpan soal
function soalsave() {
include "koneksi.php";
//$code=$_POST['code'];
$code = $_POST['code'];
$userid = $_SESSION['User']['userid'];
$judul = $_POST['judul'];
$ket = $_POST['ket'];
$kategori =$_POST['kategori'];
$file = $_FILES['file'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= "<h3>Tambah Kumpulan Soal</h3>";
if ($level=='Guru' or $level=='Admin') { // Tambahan Ansari 22/11/2012
if ($judul=='') $cetak .="Judul masih kosong, Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";
elseif ($ket=='')  $cetak .="Keterangan masih kosong, Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";
elseif (strtoupper($code) != $_SESSION['captcha']) {
	die ("<body onload=\"alert('Kode verifikasi salah');window.history.back()\">");
}
elseif (empty($file['name']))  $cetak .="File masih kosong, Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
$ext = array_pop(explode(".", $file['name']));
elseif ($ext<>'doc' && $ext<>'xls' && $ext<>'pdf' && $ext<>'zip' && $ext<>'docx' && $ext<>'xlsx' && $ext<>'pptx' && $ext<>'ppt' && $ext<>'swf' )
		{
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., Klik <a href='javascript:history.back()' id=button2 >disini</a> untuk kembali ke sebelumnya";
		exit;}
// Akhir

else {
	$tgl = date("d/m/Y")." ".date("H:i:s");
     $sql = "SELECT max(id) AS total FROM t_soal";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	$ext = array_pop(explode(".", $file['name']));
	$ukuran =$file['size'];
	$n=strlen($ukuran);
	if ($n>3) $ukuran=substr($ukuran,0,$n-3).",".substr($ukuran,$n-3,2)." Kbytes";
	else $ukuran.=" bytes";
	$target_file = "../soal/al".$total.".".$ext;
	if(@move_uploaded_file($file['tmp_name'], $target_file)) {
		$sql = "insert into t_soal (id,judul,deskripsi,kategori,file,ukuran,tanggal) values ('$total','".mysql_real_escape_string($judul)."','".mysql_real_escape_string($ket)."','".mysql_real_escape_string($kategori)."','al".$total.".".$ext."','".$ukuran."','".mysql_real_escape_string($tgl)."')";
		 if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal 1 ");
		 $cetak .="Penyimpanan Kumpulan Soal berhasil dilakukan.";
		 $kodeRandom='';
	 }
    }
  }
  return $cetak;
}
?>