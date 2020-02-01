<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/


function album() {
include "koneksi.php";
$hal=abs((int)$_GET['hal']);
  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_galerialbum");
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
  
  $query = "SELECT * FROM t_galerialbum order by idalbum DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) ;
  // tambah alan untuk delete multiple	
  if ($jml!=0) {
  $g .= "<center><a href='index.php?id=album&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='index.php?id=album&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$g .= "<b><a href='index.php?id=album&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$g .= "<a href='index.php?id=album&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $g .= "<a href='index.php?id=album&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='index.php?id=album&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a></center>";
  }
  $galeri .= $g."<ul>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
    $jml=0;
  	$sql2="select count(*) as jum from t_galeri where idalbum='$row[idalbum]'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 gambar atas");
	$r = mysql_fetch_array($query2);
	$jml=$r[jum];
	$galeri .="<li><a href='index.php?id=galeri&kode=$row[idalbum]' title='Lihat detail photo'  >
	[ <i>$row[tanggal]</i>&nbsp;] &nbsp;&nbsp; $row[album] ($jml)</a></li>";
  }        
  $galeri .= "</ul><br>$g<br>";


return $galeri;
}

function galeri() {
$galeri .= "<link rel='stylesheet' href='../plugins/lightbox/css/lightbox.css' type='text/css' media='screen' />";    
$galeri .="<script type='text/javascript' src='../plugins/lightbox/js/prototype.js'></script>
<script type='text/javascript' src='../plugins/lightbox/js/scriptaculous.js?load=effects,builder'></script>
<script type='text/javascript' src='../plugins/lightbox/js/lightbox.js'></script>";

include "koneksi.php";
$hal=abs((int)$_GET['hal']);$kode=$_GET['kode'];
  $brs=4;
  $kol=10;
  $byk_result=mysql_query("select * from t_galeri where idalbum='". mysql_escape_string($kode)."'");
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
  
  $query = "SELECT * FROM t_galeri where idalbum='". mysql_escape_string($kode)."' order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) ;
  // tambah alan untuk delete multiple	
    $sql2="select * from t_galerialbum where idalbum='". mysql_escape_string($kode)."'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 gambar atas");
	$r = mysql_fetch_array($query2);
	$judul =$r[album];
  $galeri .= "<table width='100%' cellspacing='4' cellpadding='2'  >
  <tr><td colspan=2><center> <a href='index.php?id=album' >Galeri Photo</a> - <b>$judul</b> [ $r[tanggal] ]</td></tr>";
  if ($jml!=0) {
  $g .= "<tr><td colspan=2  ><center><font class='ver10'><a href='index.php?id=galeri&kode=$kode&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='index.php?id=galeri&kode=$kode&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$g .= "<b><a href='index.php?id=galeri&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$g .= "<a href='index.php?id=galeri&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $g .= "<a href='index.php?id=galeri&kode=$kode&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='index.php?id=galeri&kode=$kode&hal=$jml' style='color:000000;text-decoration:none' title='Page $jml'> Akhir</a></font></center></td></tr>";
  }
  $i=0;
  $galeri .= $g;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$a="";$b="";
	  if ($i==0) {$i=1;$a="<tr>"; }
	  else {$i=0;$b="</tr>";}
	  $gbr="<img src='../images/galeri/gb$row[id].jpg' width=280 height=200 class='art-article' >";
	  
	  $galeri .="$a<td width=50%><center>";
	  if ($i==1) {
	  	$galeri .=" <a href='../images/galeri/gb$row[id].jpg' rel='lightbox[roadtrip]' title='$row[ket]'>
			  $gbr</a>";
  	   }
	  else {
	  	  	$galeri .="<a href='../images/galeri/gb$row[id].jpg' rel='lightbox[roadtrip]' title='$row[ket]'>
			  $gbr</a>";
	  }

	  $galeri .="<br>$row[ket]</td>$b";
	   }        
  $galeri .= "</table><br>$g<br>";


return $galeri;
}
?>