<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
//fungsi untuk menambah info alumni
function infoalumni() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

 include "functions_editor.php";
 $cetak .= editor_standar();	
 
$cetak .='<h3>Info Alumni</h3>';
$cetak .="<a href='../html/alumni.php?id=info' target='_blank' id='button2' >Lihat Info Alumni</a><br><br>";
$cetak .="<form action='user.php' method='post' name=pesan >Informasi<br>";
$cetak .= '<textarea id="elm1" name="editor1" rows="25" cols="80" style="width: 80%"></textarea>'; 
$cetak .="<br>Kode Verifikasi<br><img src='../functions/spam.php' > <br>
<input type=hidden id='id' name='id' value='siminfoalumni' >
<input type='text' name='code' size='12' >
<br><input type=submit id='button2' class='simalumni' value='Simpan'></form>";

$cetak .="</div>";
return $cetak;
}

function siminfoalumni() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
	$editor = stripslashes($_POST['editor1']);
	$code=$_POST['code'];
	$tgl = date("Y-m-d H:i:s");
	$nama = member_nama($userid);
	if ($code=='') {
		$cetak .="Kode keamanan masih kosong, Klik <a href='?id=infoalumni' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($editor=='') {
		$cetak .="Informasi masih kosong, Klik <a href='?id=infoalumni' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($_SESSION['kodeRandom']=="") {
		$cetak .="Silahkan kembali <a href='?id=infoalumni' >ke sini</a> untuk menambah informasi baru";
	}
	elseif (strtoupper($code) != $_SESSION['kodeRandom']) {
		$cetak .="Kode keamanan salah, Klik <a href='?id=infoalumni' >disini</a> untuk kembali ke sebelumnya";
	}
	else {
		$sql2="insert into t_pesan_alum (pesan,pengirim,waktu) values ('".mysql_real_escape_string($editor)."','".mysql_real_escape_string($nama)."','".$tgl."') ";
		$query=mysql_query($sql2);
		$cetak .= "Informasi Alumni berhasil ditambahkan";	
		$kodeRandom="";
	}
$cetak .="</div>";
return $cetak;
}
?>