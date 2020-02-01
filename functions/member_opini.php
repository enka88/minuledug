<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi member opini
// terdiri menampilkan opini sendiri
// menambah,edit dan hapus oponi
// melihat opini orang lain.

function opinisaya() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];

$cetak .='<script type="text/javascript">
function hapopini(userid,kdopini) {
	if(confirm("Apakah Anda yakin akan menghapus opini ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdopini=\'+ kdopini ;
    $.ajax({type: "POST",url: "kontenopini.php",data: dataString,cache: false,
	success: function(html){window.location="user.php?id=opinisaya"; }});
	return false;
	}
}
</script>';
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' > &nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >";

if ($level=='Guru') $verifikasi = "<a href='?id=veriopini' title='Klik untuk memverifikasi opini member lain'  id='button2' >Verifikasi Opini</a>&nbsp;&nbsp;&nbsp;";

$cetak .="<div style='float:right;' >$verifikasi<a href='?id=tamopini' id='button2' >Tambah Opini</a></div>";
$cetak .='<h3>Opini Pribadi</h3>';
$sql = "SELECT * from t_project where userid='". mysql_real_escape_string($userid)."' ";
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=opinisaya&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
	if ($n > 0 ) {
	$cetak .= "<script language='JavaScript'>
	function gCheckAll(chk) {
	   for (var i=0;i < document.forms[0].elements.length;i++) {
		 var e = document.forms[0].elements[i];
		 if (e.type == 'checkbox') {
			e.checked = chk.checked  }
		}
	}
	</script>";

	$cetak .= "<div id='hasil' ></div><form action='user.php' method=\"post\"><table border=0 width=100% id='tablebaru' cellspacing=2 cellpadding=2 >";
	 $query = mysql_query($sql." order by status asc,tanggal desc limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
	  	$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
		if ($row[status]=='0') $st= " - Belum diverifikasi";
		else $st="";
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$isi = strip_tags($row[deskripsi]);
			$max = 200; // maximal 300 karakter 
			$min = 150; // minimal 150 karakter 
			if( strlen( $isi ) > $max ) { 
				$pecah = substr( $isi, 0, $max ); 
				$akhirParagrap = strrpos( $pecah, "\n" ); 
				$akhirKalimat = strrpos( $pecah, '.' ); 
				$akhirSubKalimat = strrpos( $pecah, ',' ); 
				$spasiTerakhir = strrpos( $pecah, ' ' ); 
	 
				if( $akhirParagrap >= $min ) { 
					$potong = $akhirParagrap; 
				}elseif( $akhirKalimat >= $min ) { 
					$potong = $akhirKalimat; 
				}elseif( $akhirSubKalimat >= $min ) { 
					$potong = $akhirSubKalimat; 
				}else { 
					$potong = $spasiTerakhir; 
				} 
				$isi = substr( $isi, 0, $potong+1 )."..."; 
			}
		
		
		$cetak .="<tr class='$warna' >
		<td width='10' ><input type='checkbox' name='kdopini[".$row[id]."]' value='on'></td><td ><img src='../images/time.png' align=left > &nbsp;".$selisih." - Dibaca $row[visit] kali  $st<div style='float:right' ><a href='?id=editopini&kdopini=".hex($row[id],$noacak)."'  title='Klik untuk mengedit opini ini' id='editlink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;<a href='#' onclick=\"hapopini('".hex("hapopini,".$userid,$noacak)."','".hex($row[id],$noacak)."')\" title='Klik untuk menghapus opini ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div><hr style='border:1px dashed #999999;'> &nbsp;<a href='?id=lihopini&kdopini=".hex($row[id],$noacak)."' title='Klik untuk lihat opini ini' ><b>".$row[judul]."</b></a><br>".$isi."</td></tr>";
	  }
	  $cetak .= "</table ><br><input type=hidden name='id' value='hapusopini' >
	  &nbsp;&nbsp;<input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Cek Semua <input type=submit value='Hapus' id=button2 ></form>";
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
$cetak .="</div>";
return $cetak;
}

// tambah opini
function tamopini() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
//$cetak .= '<script type="text/javascript" src="../functions/ckeditor/ckeditor_basic.js"></script>';
 include "functions_editor.php";
 $cetak .= editor_standar();
$cetak .='<h3>Tambah Opini</h3>';
$cetak .="<form action='user.php' method='post' name=pesan >Judul <input type=text name=tema id=tema size=50 ><br><br>";
$cetak .= '<textarea id="elm1" name="editor1" rows="25" cols="80" style="width: 80%"></textarea>'; 
$cetak .="<br>Kode Verifikasi<br><img src='../functions/spam.php' > <br>
<input type=hidden id='id' name='id' value='simopini' >
<input type='text' name='code' size='12' >
<br><input type=submit id='button2' value='Simpan'></form>";
$cetak .="</div>";
return $cetak;
}
//simpan opini baru
function simopini() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= "<h3>Tambah Opini</h3>";
	$editor = stripslashes($_POST['editor1']);
	$code=$_POST['code'];
	$judul= htmlentities($_POST['tema']);

	if ($code=='') {
		$cetak .="Kode keamanan masih kosong, Klik <a href='?id=tamopini' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($judul=='') {
		$cetak .="Judul masih kosong, Klik <a href='?id=tamopini' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($editor=='') {
		$cetak .="Isi Opini masih kosong, Klik <a href='?id=tamopini' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($_SESSION['kodeRandom']=="") {
		$cetak .="Silahkan kembali <a href='?id=tamopini' >ke sini</a> untuk menambah informasi baru";
	}
	elseif (strtoupper($code) != $_SESSION['kodeRandom']) {
		$cetak .="Kode keamanan salah, Klik <a href='?id=tamopini' >disini</a> untuk kembali ke sebelumnya";
	}
	else {
		$sql2="insert into t_project (judul,deskripsi,tanggal,userid,status) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($editor)."',NOW(),'".mysql_real_escape_string($userid)."','0') ";
		$query=mysql_query($sql2);
		$cetak .= "Penambahan Opini berhasil dilakukan";	
		$kodeRandom="";
	}
$cetak .="</div>";
return $cetak;
}

// edit opini
function editopini() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$kdopini = unhex($_GET['kdopini'],$noacak);
$kdveri = unhex($_GET['kdveri'],$noacak);
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
if ($level=='Guru') {
	$sql="select * from t_project where id='". mysql_real_escape_string($kdopini)."' ";
}
else $sql="select * from t_project where id='". mysql_real_escape_string($kdopini)."' and userid='". mysql_real_escape_string($userid)."' ";
	$query=mysql_query($sql);
	if($row = mysql_fetch_array($query)) {
 include "functions_editor.php";
 $cetak .= editor_standar();
$cetak .='<h3>Perubahan Opini</h3>';
$cetak .="<form action='user.php' method='post' name=pesan >Judul <input type=text name=tema id=tema value='".$row[judul]."' size=50 ><br><br>";
$cetak .= '<textarea id="elm1" name="editor1" rows="25" cols="80" style="width: 80%">'.$row[deskripsi].'</textarea>'; 
$cetak .="<br>Kode Verifikasi<br><img src='../functions/spam.php' > <br>
<input type=hidden id='id' name='id' value='simeditopini' ><input type=hidden id='kdopini' name='kdopini' value='".hex($kdopini,$noacak)."' >
<input type='text' name='code' size='12' >
<br><input type=submit id='button2' value='Simpan'></form>";
	}
$cetak .="</div>";
return $cetak;
}
//simpan edit opini
function simeditopini() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= "<h3>Perubahan Opini</h3>";
	$editor = stripslashes($_POST['editor1']);
	$code=$_POST['code'];
	$kdopini=$_POST['kdopini'];
	$judul= htmlentities($_POST['tema']);
	if ($code=='') {
		$cetak .="Kode keamanan masih kosong, Klik <a href='?id=editopini&kdopini=$kdopini' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($judul=='') {
		$cetak .="Judul masih kosong, Klik <a href='?id=editopini&kdopini=$kdopini' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($editor=='') {
		$cetak .="Isi Opini masih kosong, Klik <a href='?id=editopini&kdopini=$kdopini' >disini</a> untuk kembali ke sebelumnya";
	}
	elseif ($_SESSION['kodeRandom']=="") {
		$cetak .="Silahkan kembali <a href='?id=editopini&kdopini=$kdopini' >ke sini</a> untuk menambah informasi baru";
	}
	elseif (strtoupper($code) != $_SESSION['kodeRandom']) {
		$cetak .="Kode keamanan salah, Klik <a href='?id=editopini&kdopini=$kdopini' >disini</a> untuk kembali ke sebelumnya";
	}
	else {
		if ($level=='Guru') {
		$sql2="update t_project set judul='".mysql_real_escape_string($judul)."',deskripsi='".mysql_real_escape_string($editor)."',tanggal=NOW(),status='0' where  id='".mysql_real_escape_string(unhex($kdopini,$noacak))."' "; }
		else {
		$sql2="update t_project set judul='".mysql_real_escape_string($judul)."',deskripsi='".mysql_real_escape_string($editor)."',tanggal=NOW(),status='0' where userid='".mysql_real_escape_string($userid)."' and id='".mysql_real_escape_string(unhex($kdopini,$noacak))."' "; }
		$query=mysql_query($sql2);
		$cetak .= "Perubahan Opini berhasil dilakukan";	
		$kodeRandom="";
	}
$cetak .="</div>";
return $cetak;
}

// hapus opini
function hapusopini() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kdopini=$_POST['kdopini'];
	if (!empty($kdopini))
	  {
	  	while (list($key,$value)=each($kdopini))
		{
			$sql="delete from t_project where id='".mysql_real_escape_string($key)."' and userid='".mysql_real_escape_string($userid)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }
}

// verifikasi opini member lain
function veriopini() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];

$cetak .='<script type="text/javascript">
function hapopini(userid,kdopini) {
	if(confirm("Apakah Anda yakin akan menghapus opini ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdopini=\'+ kdopini ;
    $.ajax({type: "POST",url: "kontenopini.php",data: dataString,cache: false,
	success: function(html){window.location="user.php?id=veriopini"; }});
	return false;
	}
}
function veriopini(userid,kdopini) {
	if(confirm("Apakah Anda yakin akan mem-verifikasi opini ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdopini=\'+ kdopini ;
    $.ajax({type: "POST",url: "kontenopini.php",data: dataString,cache: false,
	success: function(html){window.location="user.php?id=veriopini"; }});
	return false;
	}
}
</script>';
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

$cetak .="<div style='float:right;' >$verifikasi<a href='?id=tamopini' id='button2' >Tambah Opini</a></div>";
$cetak .='<h3>Verifikasi Opini Member lain</h3>';
if ($level=='Guru') {
$sql = "SELECT * from t_project where status='0' ";
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=veriopini&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
	if ($n > 0 ) {

	$cetak .= "<table border=0 width=100% id='tablebaru' cellspacing=2 cellpadding=2 >";
	 $query = mysql_query($sql." order by tanggal desc limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
	  	$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$isi = strip_tags($row[deskripsi]);
			$max = 200; // maximal 300 karakter 
			$min = 150; // minimal 150 karakter 
			if( strlen( $isi ) > $max ) { 
				$pecah = substr( $isi, 0, $max ); 
				$akhirParagrap = strrpos( $pecah, "\n" ); 
				$akhirKalimat = strrpos( $pecah, '.' ); 
				$akhirSubKalimat = strrpos( $pecah, ',' ); 
				$spasiTerakhir = strrpos( $pecah, ' ' ); 
	 
				if( $akhirParagrap >= $min ) { 
					$potong = $akhirParagrap; 
				}elseif( $akhirKalimat >= $min ) { 
					$potong = $akhirKalimat; 
				}elseif( $akhirSubKalimat >= $min ) { 
					$potong = $akhirSubKalimat; 
				}else { 
					$potong = $spasiTerakhir; 
				} 
				$isi = substr( $isi, 0, $potong+1 )."..."; 
			}
		$gb=fotouser($row[userid]);
		$nama = member_nama($row[userid]);
		$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\">
		<td width='10' >".$gb."</td><td ><img src='../images/user.png' align=left > &nbsp;$nama - ".$selisih." $st<div style='float:right' ><a href='#' onclick=\"veriopini('".hex("veriopini,".$row[userid],$noacak)."','".hex($row[id],$noacak)."')\" title='Klik untuk verifikasi opini ini' id='imgprofil' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;<a href='?id=editopini&kdopini=".hex($row[id],$noacak)."'  title='Klik untuk mengedit opini ini' id='editlink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;<a href='#' onclick=\"hapopini('".hex("hapopini,".$row[userid],$noacak)."','".hex($row[id],$noacak)."')\" title='Klik untuk menghapus opini ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div><hr style='border:1px dashed #999999;'><a href='?id=lihopini&kdopini=".hex($row[id],$noacak)."' title='Klik untuk lihat opini ini' >".$row[judul]."</a><br>".$isi."</td></tr>";
	  }
	  $cetak .= "</table >";
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
 } //klo status level guru
$cetak .="</div>";
return $cetak;
}

// lihat opini
function lihopini() {
include "koneksi.php";
$level = $_SESSION['User']['ket'];
$saya = $_SESSION['User']['userid'];
if (empty($_GET['kode']))  $userid = $_SESSION['User']['userid'];
else $userid = unhex($_GET['kode'],$noacak);
$cetak .= '<script type="text/javascript">
// awal pengiriman komentar
$(function() {$(".komenopini").click(function() {
	var element = $(this);
    var kdopini = $("#kdopini").val();
	var userid = $("#userid").val();
	var komentar = $("#komentar").val();
    var dataString = \'userid=\'+ userid +\'&kdopini=\'+ kdopini +\'&pesan=\'+ komentar ;
	if(komentar==\'\') {
		alert("Komentar masih kosong");
	}
	else if(komentar==\'Tuliskan komentar Anda....\') {
		alert("Komentar masih kosong ");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihopini&kode='.hex($userid,$noacak).'&kdopini="+ kdopini;}});
	}
	
});

});

function hapusopinikom(userid,kdkom,kdopini) {
	if(confirm("Apakah Anda yakin akan menghapus komentarnya ?")) {
    var dataString = \'userid=\'+ userid + \'&kdkom=\' + kdkom;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihopini&kode='.hex($userid,$noacak).'&kdopini="+kdopini;}});
	}
}

</script>';
$kdopini = unhex($_GET['kdopini'],$noacak);
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<h3>Opini</h3>";
$q=mysql_query("update t_project set visit=visit+1 where id='".mysql_real_escape_string($kdopini)."' and userid<>'".mysql_real_escape_string($userid)."' ");

 	$query = mysql_query("select * from t_project where id='". mysql_real_escape_string($kdopini)."'");
	if($row=mysql_fetch_array($query)) {
		$nama = member_nama($row[userid]);
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$cetak .="<h3 align=center>$row[judul]</h3><img src='../images/user.png' > $nama - $selisih <br>Dibaca : $row[visit]
		<hr style='border:1px dashed #999999;'>$row[deskripsi]";
		$cetak .="<hr style='border: thin solid #6A849D;'><h3>Komentar :</h3>";
		$sql2="select * from t_project_com where id_project='".mysql_real_escape_string($kdopini)."' order by id desc limit 0,15";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal komentar opini");
		while($r = mysql_fetch_array($query2)) {
			$selisih = ambilselisih(strtotime($r[tanggal]), time());
			$nama = member_nama($r[userid]);
			$pesan = $r[komentar];
			$gb=fotouser($r[userid]);
			$cetak .="<div id='komen3'>$gb <b>$nama</b> :: $selisih ";
			if ($level=='Guru') $cetak .="<div id='sthapus' ><a href='#' title='Klik untuk menghapus komentar' onclick=\"hapusopinikom('".hex("hapopinikom,".$saya,$noacak)."','".hex($r[id],$noacak)."','".hex($kdopini,$noacak)."')\">x</a></div>";
			$cetak .="<br>$pesan </div>";
	
		}
		$cetak .='<br><form action="" method="post" name="komentar"><textarea style="width:380px;height:20px;" id="komentar" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea> &nbsp;&nbsp;<input type="submit" value="  Kirim  " name="komenopini" class="komenopini" id="button2" /><input type=hidden name="kdopini" value="'.hex($kdopini,$noacak).'" id="kdopini" ><input type=hidden name="userid" value="'.hex("komopini,".$saya,$noacak).'" id="userid" >
	</form><br>';
	}
	else $cetak ="Data Opini yang dicari tidak ada";
$cetak .="</div>";
return $cetak;
}

// opini teman --member lain
function opiniteman() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$kode = unhex($_GET['kode'],$noacak);

$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($kode);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<input type='button' id='button2' onclick=\"location.href='user.php?id=lih_profil&kode=".hex($kode,$noacak)."'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofilmember&kode=".hex($kode,$noacak)."'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;
<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifotomember&kode=".hex($kode,$noacak)."'\" value=' Koleksi Foto ' >";
$cetak .='<h3>Opini Pribadi</h3>';
$sql = "SELECT * from t_project where userid='". mysql_real_escape_string($kode)."' and status='1' ";
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=opiniteman&kode=".hex($kode,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
	if ($n > 0 ) {

	$cetak .= "<table border=0 width=100% id='tablebaru' cellspacing=2 cellpadding=2 >";
	 $query = mysql_query($sql." order by status asc,tanggal desc limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
	  	$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$isi = strip_tags($row[deskripsi]);
			$max = 200; // maximal 300 karakter 
			$min = 150; // minimal 150 karakter 
			if( strlen( $isi ) > $max ) { 
				$pecah = substr( $isi, 0, $max ); 
				$akhirParagrap = strrpos( $pecah, "\n" ); 
				$akhirKalimat = strrpos( $pecah, '.' ); 
				$akhirSubKalimat = strrpos( $pecah, ',' ); 
				$spasiTerakhir = strrpos( $pecah, ' ' ); 
	 
				if( $akhirParagrap >= $min ) { 
					$potong = $akhirParagrap; 
				}elseif( $akhirKalimat >= $min ) { 
					$potong = $akhirKalimat; 
				}elseif( $akhirSubKalimat >= $min ) { 
					$potong = $akhirSubKalimat; 
				}else { 
					$potong = $spasiTerakhir; 
				} 
				$isi = substr( $isi, 0, $potong+1 )."..."; 
			}
		
		
		$cetak .="<tr class='$warna' >
		<td ><img src='../images/time.png' align=left > &nbsp;".$selisih." - Dibaca $row[visit] kali<hr style='border:1px dashed #999999;'> <a href='?id=lihopini&kode=".hex($kode,$noacak)."&kdopini=".hex($row[id],$noacak)."' title='Klik untuk lihat opini ini' ><b>".$row[judul]."</b></a><br>".$isi."</td></tr>";
	  }
	  $cetak .= "</table >";
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
$cetak .="</div>";
return $cetak;
}
?>