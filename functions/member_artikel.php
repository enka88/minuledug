<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi member aktikel
// menambah artikel

// tambah artikel
function tamartikel() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .='<h3>Tambah Artikel</h3>';
if ($level=='Guru') {
 include "functions_editor.php";
 $cetak .= editor_standar();
$cetak .="<form action='user.php' method='post' name=pesan >Judul <input type=text name=tema id=tema size=50 ><br><br>";
$cetak .= '<textarea id="elm1" name="editor1" rows="25" cols="80" style="width: 80%"></textarea>'; 
$cetak .="<br>Kode Verifikasi<br><img src='../functions/spam.php' > <br>
<input type=hidden id='id' name='id' value='simartikel' >
<input type='text' name='code' size='12' >
<br><input type=submit id='button2' value='Simpan'></form>";
}
else $cetak .="Fasilitas ini hanya untuk Guru di lingkungan ".$nmsekolah ;
$cetak .="</div>";
return $cetak;
}
//simpan artikel baru
function simartikel() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$nama = $_SESSION['User']['nama'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= "<h3>Tambah Artikel</h3>";
	$editor = stripslashes($_POST['editor1']);
	$code=$_POST['code'];
	$judul= htmlentities($_POST['tema']);

	if ($code=='') {
		$cetak .="Kode keamanan masih kosong, Klik <a href='?id=tamartikel' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($judul=='') {
		$cetak .="Judul masih kosong, Klik <a href='?id=tamartikel' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($editor=='') {
		$cetak .="Isi Artikel masih kosong, Klik <a href='?id=tamartikel' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($_SESSION['kodeRandom']=="") {
		$cetak .="Silahkan kembali <a href='?id=tamartikel' >ke sini</a> untuk menambah informasi baru";
	}
	elseif (strtoupper($code) != $_SESSION['kodeRandom']) {
		$cetak .="Kode keamanan salah, Klik <a href='?id=tamartikel' >disini</a> untuk kembali ke sebelumnya";
	}
	else {
		if ($level=='Guru') {
		$tgl =date("d-m-Y H:i");
		$sql2="insert into t_artikel (judul,isi,tanggal,admin,pengirim) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($editor)."','$tgl','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($nama)."') ";
		$query=mysql_query($sql2);
		}
		$cetak .= "Penambahan Artikel berhasil dilakukan";	
		
		$kodeRandom="";
	}
$cetak .="</div>";
return $cetak;
}

?>