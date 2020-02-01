<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

/*********************************************************** berita ***************************************/
function info() {
include "koneksi.php";
$kd=$_GET['kode'];
$hal=abs((int)$_GET['hal']);

if ($kd=='') {
  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_info");
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
  
  $query = "SELECT * FROM t_info order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	

  if ($jml!=0) {
  $info .= "<center><a href='index.php?id=info&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='index.php?id=info&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$info .= "<b><a href='index.php?id=info&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$info .= "<a href='index.php?id=info&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $info .= "<a href='index.php?id=info&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='index.php?id=info&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a></center>
  ";
  }
  $info .="<ul>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  $subject = $row[subject];
   $info .= "<li><a href='index.php?id=info&kode=$row[id]' class='ver10' >$subject</a>";
 }        
 $info .="</ul>";

}
else {

$sql="select * from t_info where id='".mysql_real_escape_string($kd)."'";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	$row=mysql_fetch_array($alan);

	$info .= "<h3><center>$row[subject]</center></h3>
	Tanggal  : $row[postdate]<hr style='border: dashed 1px;'>  ";
	$info .= "$row[isi]<br><br><a href='index.php?id=info&kode=$kd' >Kembali ke Atas</a><br>";
		$info .="<hr><br><b>Info Sekolah Lainnya :</b><br><ul>";
	$sql="select * from t_info where id<>'".mysql_real_escape_string($kd)."' order by id desc limit 0,5";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 berita ");
	while ($row=mysql_fetch_array($query)) {
	$info .="<li><a href='index.php?id=info&kode=$row[id]'  class='ver10'>$row[subject]</a></li>";
	}
	$info .="</ul>";
}
	

return $info;
}


?>