<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

//---------------------------------- search ke web lain ------------------
function search() {
include "koneksi.php";
include ("../functions/fungsi_filter.php");
$query=$_POST['query'];
$hal=$_POST['hal'];
if ($hal=='') $hal=abs((int)$_GET['hal']);
if ($query=='') $query= filter($_GET['query'],"ucase lcase space num");
//--------------------------------- pencarian di web site--------------------------//
	$search .="<center><table width='100%' border='0' cellspacing='2' cellpadding='2'><tr><td>";
if ($query<>'') {
if ($hal=='') {
//--------------------------------- algoritma pencarain data per kata pada mysql------------//

// ---------------penghapusan data ------------//
$query1 = "delete from t_temp ";
$result = mysql_query($query1) or die("Pengambilan gagal1");
//------------------- pengulangan pencarian dimulai--------//
	//table artikel
	$sql="select * from t_artikel where isi like '%".mysql_real_escape_string($query)."%'";
	if(!$alan2=mysql_query($sql)) die ("Pengambilan gagal");
	while ($row=mysql_fetch_array($alan2)) {
		$judul = '[ Artikel ] <a href="index.php?id=artikel&kode='.$row[id].'" style="color:000000">'.$row[judul].'</a>';
		$judul =eregi_replace("'"," ",$judul);
		$d =strip_tags($row[isi],5);
		$e = strpos($d,$query);
		$e = $e-5;
		$c = substr($d,$e,200);
		$b = str_replace($query,"<b>$query</b>",$c);
		$b =eregi_replace("'"," ",$b);
	    $query1 = "insert into t_temp (judul,isi) values ('$judul','$b')";
    	$result = mysql_query($query1) or die("Pengambilan gagal artikel");
	}

	//table berita
	$sql="select * from t_news where isi like '%".mysql_real_escape_string($query)."%'";
	if(!$alan2=mysql_query($sql)) die ("Pengambilan gagal");
	while ($row=mysql_fetch_array($alan2)) {
		$judul = '[ Berita ] <a href="index.php?id=berita&kode='.$row[id].'" style="color:000000">'.$row[subject].'</a>';
		$judul =eregi_replace("'"," ",$judul);
		$d =strip_tags($row[isi],5);
		$e = strpos($d,$query);
		$e = $e-5;
		$c = substr($d,$e,200);
		$b = str_replace($query,"<b>$query</b>",$c);
		$b =eregi_replace("'"," ",$b);
	    $query1 = "insert into t_temp (judul,isi) values ('$judul','$b')";
    	$result = mysql_query($query1) or die("Pengambilan gagal berita 2");
	}
	
		//table profil
	$sql="select * from t_profil where isi like '%".mysql_real_escape_string($query)."%'";
	if(!$alan2=mysql_query($sql)) die ("Pengambilan gagal");
	while ($row=mysql_fetch_array($alan2)) {
		$judul =eregi_replace("'"," ","<a href='index.php?id=profil&kd=$row[id]' class=ver10 >[ $row[judul] ]</a>");
		$d =strip_tags($row[isi],5);
		$e = strpos($d,$query);
		$e = $e-5;
		$c = substr($d,$e,200);
		$b = str_replace($query,"<b>$query</b>",$c);
		$b =eregi_replace("'"," ",$b);
	    $query1 = "insert into t_temp (judul,isi) values ('$judul','$b')";
    	$result = mysql_query($query1) or die("Pengambilan gagal berita 2");
	}
//selesai----pengulangan - cari----//
}
}
//-------function--------//
  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_temp");
  $byk=mysql_num_rows($byk_result);
  if ($byk==0) $search .="<font class='ver10'>Data yang dicari tidak ada. Silahkan masukan kembali keyword yang lain</font>";
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
   		
  $query1 = "SELECT * FROM t_temp order by id  LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query1) 
  or die (mysql_error()); 

  // check that there is news
 //   $num_of_rows = mysql_num_rows ($query_result_handle) 
 //   or die (error("<font>Tidak ditemukan data konsultasi di database!</font>"));
  // tambah alan untuk delete multiple	
  $search .=  "<center><table width='97%' id='table-no'>";
  if ($jml!=0) {
  $search .=  "<tr><td ><center><font class='ver10'><a href='?id=cari&hal=1' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='?id=cari&hal=$back' style='color:000000;text-decoration:none' title='Page $back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	$search .=  "<a href='?id=cari&hal=$i' title='Page $i from $byk Data' style='color:000000;text-decoration:none'> $i </a> |";		
  }
  $search .=  "<a href='?id=cari&hal=$next' title='Page $next' style='color:000000;text-decoration:none'> Next</a>
  <a href='?id=cari&hal=$jml' title='Page $jml' style='color:000000;text-decoration:none'> Last</a> </font></center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {
    $search .=  "<tr ><td width='22%' valign='top'><font class='ver10'><b>$row[judul]</b><br>$row[isi]</font><hr></td></tr>";
  }   
  if ($jml!=0) {     
  $search .=  "<tr><td ><center><font class='ver10'><a href='?id=cari&hal=1' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='?id=cari&hal=$back' style='color:000000;text-decoration:none' title='Page $back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
   	if ($i==$hal)
  	$search.= "<b><a href='?id=cari&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	$search .= "<a href='?id=cari&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";	  }
  $search .=  "<a href='?id=cari&hal=$next' title='Page $next' style='color:000000;text-decoration:none'> Next</a>
  <a href='?id=cari&hal=$jml' title='Page $jml' style='color:000000;text-decoration:none'> Last</a> </font></center></td></tr>";
  }
  $search .=  "</table>";



//------------------------------------------- akhir pencarian ----------------------------//


$search .="</td></tr></table> ";


return $search;
}
?>