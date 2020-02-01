<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
//**************************************** fungsi index ********************************//
//******************************************* isi utama ********************************* //
function depan($id) {
include "koneksi.php";
$depan .='<div class="art-content">';

$sql2="select * from t_pos_menu where kategori='$id' and posisi='T' and sembunyi='t' order by urut ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu posisi");
while($rows=mysql_fetch_array($query2)) {
//cek pake temp engga
  if ($rows[idtemp]=='0')  {
  	// tag awal source isi
	$fungsi ="../modul/tag_".$rows[fungsi].".php";
	include ("$fungsi");
	$depan .= $tag;
  }
  else {
	$sql1="select * from t_temp_menu where idtemp='$rows[idtemp]'";
	if(!$query1=mysql_query($sql1)) die ("Pengambilan gagal1 menu posisi");
	$r=mysql_fetch_array($query1);
	$depan .= $r[temp_atas]; //atas
	//tag tengah
	$depan .= $rows[menu]; //judul setiap tag
	$depan .= $r[temp_tengah];
	// tag awal source isi
	$fungsi ="../modul/tag_".$rows[fungsi].".php";
	include ("$fungsi");
	$depan .= $tag;
	// bawah
	$depan .= $r[temp_bawah]; //bawah
  }
	$tag="";
}	
$depan .=' </div>
<div class="art-sidebar2">';
		
$sql2="select * from t_pos_menu where kategori='$id' and posisi='R' and sembunyi='t' order by urut ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu posisi");
while($rows=mysql_fetch_array($query2)) {
//pake temp apa enngaa
  if ($rows[idtemp]=='0')  {
  	// tag awal source isi
	$fungsi ="../modul/tag_".$rows[fungsi].".php";
	include ("$fungsi");
	$depan .= $tag;
  }
  else {
	$sql1="select * from t_temp_menu where idtemp='$rows[idtemp]'";
	if(!$query1=mysql_query($sql1)) die ("Pengambilan gagal1 menu posisi");
	$r=mysql_fetch_array($query1);
	$depan .= $r[temp_atas]; //atas
	//tag tengah
	$depan .= $rows[menu]; //judul setiap tag
	$depan .= $r[temp_tengah];
	// tag awal source isi
	$fungsi ="../modul/tag_".$rows[fungsi].".php";
	include ("$fungsi");
	$depan .= $tag;
	
	// bawah
	$depan .= $r[temp_bawah]; //bawah
  }
	$tag="";
}	
$depan.='</div>';


return $depan;
}

function webmaster() {
include "koneksi.php";
  	$sql="select * from t_profil where judul='webmaster'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);
	
$web .=$row[isi];
return $web;
}
function peta() {
include "koneksi.php";
  	$sql="select * from t_profil where judul='petasitus'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);

$peta .=$row[isi];
return $peta;
}


?>