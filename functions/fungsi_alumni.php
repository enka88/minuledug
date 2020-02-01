<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

function lihat() {
include "koneksi.php";
require_once ("../functions/fungsi_filter.php");
$kd= filter($_GET['kd'],"num");

$depan .='<table width="100%" class="art-article" cellspacing="0" cellpadding="0" >';
		$query = "SELECT * FROM t_member where userid='".mysql_real_escape_string($kd)."'";
    	$result = mysql_query($query) or die("Query failed banner");
    	$row = mysql_fetch_array($result);
		$depan .="<tr><td width='20%' >Nama </td><td>&nbsp;&nbsp;<b>$row[nama]</a></td></tr>
		<tr><td >Angkatan/Lulus </td><td>&nbsp;&nbsp;$row[kelas]</td></tr>
		<tr><td >Kuliah </td><td> &nbsp;&nbsp;$row[sekolah]</td></tr>
		<tr><td >Pekerjaan </td><td> &nbsp;&nbsp;$row[kerja]</td></tr>
		<tr><td >Alamat</td><td> &nbsp;&nbsp;$row[alamat]</td></tr>
		<tr><td >Telp. </td><td> &nbsp;&nbsp;$row[telp]</td></tr>
		<tr><td >Homepage/Blog </td><td> &nbsp;&nbsp;$row[homepage]</td></tr>
		<tr><td >Email </td><td> &nbsp;&nbsp;$row[email]</td></tr>
		<tr><td >Tanggal Update </td><td> &nbsp;&nbsp;$row[tgl_login]</td></tr>
		<tr><td  valign=top >Profil </td><td> &nbsp;&nbsp;$row[profil]</td></tr>";
		
			$depan .= '</table>
			<br><div align=center > Untuk data lebih lengkap, silahkan daftar sebagai Alumni di <a href="../member/index.php?id=daftar1" target="_blank">sini</a></div><br>';
return $depan;
}
function data() {
include "koneksi.php";
include ("../functions/fungsi_filter.php");
$hal=abs((int)$_GET['hal']);
$tahun= filter($_GET['tahun'],"num");

$brs=30;
$kol=10;
  $byk_result1=mysql_query("select * from t_member where ket='Alumni' and kelas='".mysql_real_escape_string($tahun)."'");
  $byk=mysql_num_rows($byk_result1);
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
  
  $query = "SELECT * FROM t_member where ket='Alumni' and kelas='".mysql_real_escape_string($tahun)."' order by nama  LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  $data .="Data Alumni ini yang telah menjadi member dan bergabung di sistem komunitas sekolah";
  $data .= '<table cellspacing=2 cellpadding=5  width=100%  > <form action="alumni.php" method="get" name="alumni">
  <tr><td width="200" >Pilih Angkatan/Lulus : <select name=tahun onchange="document.location.href=\'alumni.php?id=data&tahun=\'+document.alumni.tahun.value" >';
  	$sql2="select distinct kelas from t_member where ket='Alumni' order by kelas";
	if(!$q=mysql_query($sql2)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($tahun==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
  $data .='</select></td><td><input type=submit value="Pilih" class="art-button" onclick="document.location.href=\'alumni.php?id=data&tahun=\'+document.alumni.tahun.value" ></td></tr></form></table><br><table cellspacing=2 cellpadding=5 class="art-article" width=100%  > ';
  
  if ($jml!=0) {
  $data .="<tr><td ><center><a href='alumni.php?id=data&tahun=$tahun&hal=1' style='color:000000;text-decoration:none' title='Page 1'>Awal </a> 
  <a href='alumni.php?id=data&tahun=$tahun&hal=$back' style='color:000000;text-decoration:none' title='Page $back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$data .= "<b><a href='alumni.php?id=data&tahun=$tahun&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  		$data .= "<a href='alumni.php?id=data&tahun=$tahun&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $data .="<a href='alumni.php?id=data&tahun=$tahun&hal=$next' style='color:000000;text-decoration:none' title='Hal $next'> Lanjut</a> 
  <a href='alumni.php?id=data&tahun=$tahun&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a></center></td></tr>";
  }
	$data .="<tr ><th>Nama</th><th>Angkatan</th><th >Email</th></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  			$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
  $data .="<tr  onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"> 
    <td width='50%' ><img src='../images/buku.gif' style='border:0;margin:0;' >&nbsp;<b><a href='alumni.php?id=lihat&kd=$row[userid]' style='color:#000000' title='Lihat Profil Alumni'>$row[nama]</a></b>
    </td><td ><center>$row[kelas]</td><td >$row[email]</td></tr>"; 
	  }  
  $data .= "</table><br>";						
return $data;
}
function info() {
include "koneksi.php";
$hal=abs((int)$_GET['hal']);

$brs=30;
$kol=10;
  $byk_result1=mysql_query("select * from t_pesan_alum");
  $byk=mysql_num_rows($byk_result1);
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
  
  $query = "SELECT * FROM t_pesan_alum order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  $data .= "<table cellspacing='2' cellpadding='5' border=0 width=100% class='art-article'  >  ";
  
  if ($jml!=0) {
  $data .="<tr><td ><center><a href='alumni.php?id=info&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='alumni.php?id=info&hal=$back' style='color:000000;text-decoration:none' title='Hal $back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$data .= "<b><a href='alumni.php?id=info&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  		$data .= "<a href='alumni.php?id=info&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $data .="<a href='alumni.php?id=info&hal=$next' style='color:000000;text-decoration:none' title='Hal $next'> Lanjut</a> 
  <a href='alumni.php?id=info&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a></center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {
  			$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
  $data .="<tr  > 
    <td width='50%'  ><b>Tanggal</b> : $row[waktu] <img src='../images/buku.gif' style='border:0;margin:0;'><b>Pengirim </b>: &nbsp;$row[pengirim] <br>
	<b>Pesan</b> : $row[pesan]
    </tr>"; 
	  }  
  $data .= "</table><br></center><br>";						
return $data;
}

?>