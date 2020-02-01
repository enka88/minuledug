<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

/*********************************************************** Artikel ***************************************/
function artikel() {
include "koneksi.php";
$kd=$_GET['kode'];
$hal=abs((int)$_GET['hal']);
if ($kd=='') $kd=$_POST['kode'];
//  $artikel .= "<table width='98%' border=0 cellspacing='4' cellpadding='2'><TR><TD>";
if ($kd=='') {
global $memberlog;
session_start();
list($userid,$user,$pass,$kategori) = explode("\|",$memberlog);

  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_artikel");
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
  
  $query = "SELECT * FROM t_artikel order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $artikel .= "<table width='100%' border='0' cellspacing='4' cellpadding='2'  >";
  if ($jml!=0) {
  $artikel .= "<tr><td ><center><font ><a href='index.php?id=artikel&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='index.php?id=artikel&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$artikel .= "<b><a href='index.php?id=artikel&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$artikel .= "<a href='index.php?id=artikel&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $artikel .= "<a href='index.php?id=artikel&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='index.php?id=artikel&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a>
  </font></center></td></tr>";
  }
  $artikel .= "<tr><td><ul>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$del="";
   $artikel .= "<li><a href='index.php?id=artikel&kode=$row[id]' class='ver10' title='Dibaca $row[visits] kali'>$row[judul] <i>[Pengirim : $row[pengirim]]</i> ...</a></li>";
 }        
  $artikel .= "</ul></td></tr></table></center>";
}
else {
    $query = "SELECT * FROM t_artikel WHERE id='". mysql_real_escape_string($kd)."'";
    $result = mysql_query($query) or die("Query failed");
    
    $rows = mysql_fetch_row($result);
    
    $visits = $rows[5] + 1;
    $query = "UPDATE t_artikel SET visits=$visits WHERE id='". mysql_real_escape_string($kd)."'";
    $result = mysql_query($query) or die("Query failed");
	
	$sql="select * from t_artikel where id='". mysql_real_escape_string($kd)."'";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	$row = mysql_fetch_array($alan);
	$artikel .= "<h3><center>$row[judul]</center></h3>
	Tanggal  : $row[tanggal], dibaca $row[visits] kali.<hr style='border: dashed 1px;'> ";
		 $file = "../images/artikel/gb$row[id].jpg";
   		if (file_exists(''.$file.'')) {
	     $artikel .="<a href='$file' title='lihat gambar' target='_blank'><img src='$file' align='left' hspace='5' width='300' height='225' id='gambar' ></a>";
	   }
	$artikel .= "$row[isi]<br><br>Pengirim : $row[pengirim]<br><a href='index.php?id=artikel&kode=$kd' >Kembali ke Atas</a>";
		$artikel .="<hr><b>Artikel Lainnya :</b><br><ul>";
	$sql="select * from t_artikel where id<>'". mysql_real_escape_string($kd)."' order by id desc limit 0,5";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 artikel");
	while ($row=mysql_fetch_array($query)) {
	$artikel .="<li><a href='index.php?id=artikel&kode=$row[id]'  >$row[judul]</a></li>";
	}
	$artikel .="</ul>";
	$artikel .="<b>Silahkan Isi Komentar dari tulisan artikel diatas</b><br>
<form action='index.php' method='post' >
<table><tr><td class='bg1'><font class='ver10'>Nama</font></td><td><input type='text' name='nama' maxlenght=20 size=30 id='editbox'></td></tr>
<tr><td class='bg1'><font class='ver10'>E-mail</font></td><td><input type='text' name='email' maxlenght=20 size=30 id='editbox'></td></tr>
<tr><td VALIGN=TOP class='bg1'><font class='ver10'>Komentar</font></td><td><textarea name='komentar' cols='55' rows='5' id='editbox'></textarea></td></tr>
<tr><td VALIGN=TOP class='bg1'><font class='ver10'></font></td><td><img src='../functions/captcha/captcha.php'><br>
Kode Verifikasi <br><input type='text' name='code' size='12' id='editbox' ></td></tr>
<input type='hidden' name='id' value='simkom_artikel'><input type='hidden' name='kode' value='$kd'>
</table>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' name='submit' value='Kirim' class='art-button'>&nbsp;<input type='reset' class='art-button' name='reset' value='Ulang'></form>";
	$artikel .="<h3>Komentar :</h3>";
	$sql="select * from t_artikel_kom where id='". mysql_real_escape_string($kd)."' order by idkom desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 artikel");
	while ($row=mysql_fetch_array($query)) {
	$artikel .="<table class='art-article' width=100% ><tr><td><img src='../images/buku.gif'  style='border:0;margin:0;' > Pengirim : $row[nama] - 
	<img src='../images/email2.gif' style='border:0;margin:0;' >&nbsp;<i>[$row[email]]</i>&nbsp;&nbsp;Tanggal : $row[tgl]<br>$row[komentar]
	</td></tr></table><br>";
	}
	$artikel .="<br>&nbsp;&nbsp;&nbsp;<a href='index.php?id=artikel&kode=$kd' >Kembali ke Atas</a><br>";
}

$artikel .= "<br>";

return $artikel;
}

function simkom_artikel() {
include "koneksi.php";
include ("../functions/fungsi_filter.php");

$nama = filter($_POST['nama'],"lcase ucase space");
$email= filter($_POST['email'],"lcase ucase symbol");
$komentar=$_POST['komentar'];
$kd=$_POST['kode'];
if ($nama == '' ) {
	die ("<body onload=\"alert('Nama masih kosong');window.history.back()\">");
}

if ($komentar == '' ) {
	die ("<body onload=\"alert('Komentar masih kosong');window.history.back()\">");
}
session_start();

$code = $_POST['code'];
if ($code != $_SESSION['captcha']) {
	die ("<body onload=\"alert('Kode keamanan salah');window.history.back()\">");
}

if ($email == '' ) {
	die ("<body onload=\"alert('email masih kosong');window.history.back()\">");
}
else {
if (preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/",$email)) $a="";
else  die ("<body onload=\"alert('Email yang dimasukan tidak valid');window.history.back()\">");
	
 $postdate = date("d/m/Y");

 $komentar = htmlentities($komentar);
 $komentar = nl2br($komentar);
 $a = substr($komentar,0,100);
 $b = strstr($a," ");

 if ($b=='') $komentar = "";
   
$sql="INSERT INTO t_artikel_kom (id,nama,email,komentar,tgl) values ('". mysql_escape_string($kd)."','".mysql_escape_string($nama)."','".mysql_escape_string($email)."','".mysql_escape_string($komentar)."','$postdate')";
 if(!$result = mysql_query($sql)) {
          die("Pengisian komentar artikel tidak berhasil, data ada yang salah coba kembali dan pilih Back ! <BR><BR>$mysql_error()</td></tr></table> "); }

}

}

?>