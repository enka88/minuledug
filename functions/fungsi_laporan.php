<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function gurulap() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal =$_GET['hal'];

if ($sem=='') $sem=1;
$nip = konversi_id($userid);
$cetak .= ataslogin("Data Laporan - Guru");
  $brs=50;
  $kol=10;
if ($kategori=='Guru') {
  $byk_result=mysql_query("SELECT * FROM t_laporan where nip='".mysql_real_escape_string($nip)."'");
  $byk=mysql_num_rows($byk_result); 
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
  
  if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_laporan where nip='".mysql_real_escape_string($nip)."' order by tgl_kirim DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  
  $cetak .= "<a href='user.php?id=gurulaptambah' id=button2 ><B>Tambah Laporan</B></a><br><br>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=7 ><center><a href='user.php?id=gurulap&hal=1'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurulap&hal=$back'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurulap&hal=$i'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurulap&hal=$i'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurulap&hal=$next'  title='$next'> Next</a> 
  <a href='user.php?id=gurulap&hal=$jml'  title='Page $jml'> Last</a></center></td></tr>";
  }
    $cetak .="<tr class='td0'><td><b>No</td><td><b>Tgl Kirim</td><td><b>Judul Laporan</td>
  <td><b>Status</td><td><b>File</td><td><b>Hap</td><td><b>Edit</td></tr>";
 
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	$isi = substr($row[isi],0,30);
	if($row[status]=='1') $st = "Disetujui"; 
	elseif($row[status]=='2') $st ="Diperbaiki";
	else $st = "Pending";
	
    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$j</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_kirim]))."</td>
   <td width='40%'>$row[judul]</td>
   <td width='5%'>$st</td>
   <td width='5%'>$row[file]</td>
   <td width='5%'><a href='user.php?id=gurulaphapus&kd=$row[idlap]' title='klik untuk hapus data'><img src='../images/hapus.gif'></a></td>
   <td width='5%'><a href='user.php?id=gurulapedit&kd=$row[idlap]' title='klik untuk edit data'><img src='../images/edit.gif'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table>";
}
else $cetak .="Mohon maaf, anda tidak diperkenankan menggunakan fasilitas ini";
 
 $cetak .="</div>";
return $cetak;
}


function gurulaptambah() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Tambah Laporan - Guru");

$nip = konversi_id($userid);

$cetak .="<table id=tablebaru cellspacing='1' cellpadding='3'><form action='../functions/simlaporguru.php' method='post' enctype=\"multipart/form-data\">
  <tr><td><b>Judul</td><td><input type='text' name='judul' size=60 maxlength='250'></td></tr>
  <tr><td><b>File</td><td><input type='file' name='file'> Format File dokumen bebas </td></tr>
  <tr><td><b></td><td><input type='submit' value=' Simpan ' id=button2 >
  <input type=hidden name='st' value='tambah'>
  <input type=hidden name='nip' value='$nip'></td></tr>
  </form></table>";
$cetak .="</div>";
return $cetak;
}

function gurulapedit() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];

$cetak .= ataslogin("Tambah Laporan - Guru");
$q=mysql_query("select * from t_laporan where idlap='".mysql_real_escape_string($kd)."' ");
$row=mysql_fetch_array($q);

$cetak .="<table id=tablebaru cellspacing='1' cellpadding='3'><form action='../functions/simlaporguru.php' method='post' enctype=\"multipart/form-data\">
  <tr><td><b>Judul</td><td><input type='text' name='judul' size=60 maxlength='250' value='$row[judul]'></td></tr>
  <tr><td><b>File</td><td><input type='file' name='file'> Format File dokumen bebas <a href='../laporan/$row[file]'>Download File</a></td></tr>
  <tr><td><b></td><td><input type='submit' value=' Simpan ' id=button2 >
  <input type=hidden name='st' value='edit'>
  <input type=hidden name='kd' value='$kd'></td></tr>
  </form></table>";
$cetak .="</div>";
return $cetak;
}

function gurulaphapus() {
include "koneksi.php";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];
	$sql="select * from t_laporan where idlap='".mysql_real_escape_string($kd)."'";
	$result=mysql_query($sql) or die ("Penghapusan gagal 1");
	$row =mysql_fetch_array($result);
		$sql="delete from t_laporan where idlap='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
		$file = "../laporan/$row[file]";
		if (file_exists($file)) {
			unlink($file);
		}

 } 

function adminstatus() {
include "koneksi.php";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];
$st=$_GET['st'];
if($st=='') $st=$_POST['st'];
	$sql="update t_laporan set status='".mysql_real_escape_string($st)."' where idlap='".mysql_real_escape_string($kd)."'";
	$result=mysql_query($sql) or die ("Penghapusan gagal 1");
	
    Header("Location: user.php?id=adminlap");
	return 0;
 } 
function adminlap() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$st=$_GET['st'];

$nip = konversi_id($userid);

if ($st=='') $st=0;

$cetak .= ataslogin("Data Laporan - Admin");
  $brs=50;
  $kol=10;

if ($kategori=='Admin') {
  $byk_result=mysql_query("SELECT * FROM t_laporan ");
  $byk=mysql_num_rows($byk_result); 
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
  
  if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_laporan where status='".mysql_real_escape_string($st)."' order by tgl_kirim DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  if ($st==1) $s2='selected';
  elseif ($st==2) $s3='selected';
  else $s1='selected';
  
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='adminlap'>Status Laporan&nbsp;&nbsp;";
  $cetak .= '<select name="st" onchange="document.location.href=\'user.php?id=adminlap&st=\'+document.guru.st.value">
  <option value="0" '.$s1.'>Pending</option><option value="1" '.$s2.'>Disetujui</option>
  <option value="2" '.$s3.'>Diperbaiki</option></select>';
  $cetak .="&nbsp;
  <br></form><table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=6 ><center><a href='user.php?id=adminlap&hal=1&st=$st'  title='Hal 1'>First </a> 
  <a href='user.php?id=adminlap&hal=$back&st=$st'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=adminlap&hal=$i&st=$st'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=adminlap&hal=$i&st=$st'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=adminlap&hal=$next&st=$st'  title='$next'> Next</a> 
  <a href='user.php?id=adminlap&hal=$jml&st=$st'  title='Page $jml'> Last</a></center></td></tr>";
  }
    $cetak .="<tr class='td0'><td>No</td><td>Tgl Kirim</td><td>Pengirim</td><td>Judul Laporan</td><td>Status</td><td>File</td></tr>";
 
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	$isi = substr($row[isi],0,30);
if ($row[status]=='0') $cek = "<a href='user.php?id=adminstatus&kd=$row[idlap]&st=1' title='klik untuk ubah status menjadi disetujui'>Setuju</a><br><a href='user.php?id=adminstatus&kd=$row[idlap]&st=2' title='klik untuk ubah status data menjadi diperbaiki'>Perbaiki</a>";
	elseif ($row[status]=='1') $cek = "<a href='user.php?id=adminstatus&kd=$row[idlap]&st=2' title='klik untuk ubah status menjadi diperbaiki'>Perbaiki</a><br><a href='user.php?id=adminstatus&kd=$row[idlap]&st=0' title='klik untuk ubah status data menjadi pending'>Pending</a>";
	else $cek = "<a href='user.php?id=adminstatus&kd=$row[idlap]&st=1' title='klik untuk ubah status menjadi disetujui'>Setuju</a><br><a href='user.php?id=adminstatus&kd=$row[idlap]&st=0' title='klik untuk ubah status data menjadi dipending'>Pending</a>";
	 $guru='';
	 $q=mysql_query("select * from t_staf where nip='$row[nip]'");
  	$r=mysql_fetch_array($q);
	$guru = $r[nama];
    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$j</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_kirim]))."</td>
   <td width='15%'>$guru</td>
   <td width='40%'>$row[judul]</td>
   <td width='5%'>$cek</td>
   <td width='5%'><a href='../laporan/$row[file]' title='klik untuk download data'>$row[file]</a></td></tr>";
	$j++;
 }        
  $cetak .= "</table>";
}
else $cetak .="Mohon maaf, anda tidak diperkenankan menggunakan fasilitas ini";
$cetak .= "</div>";
return $cetak;
}


?>
