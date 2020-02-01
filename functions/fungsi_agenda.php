<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/


function agenda() {
include "koneksi.php";
include_once("../functions/fungsi_filter.php");

$bulan= filter($_GET['bulan'],"lcase ucase num");
$tahun= filter($_GET['tahun'],"lcase ucase num");
$hal=abs((int)$_GET['hal']);

if ($bulan=='') $bulan = date("m");
if ($tahun=='') $tahun = date("Y");
  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from calendarevent where month(date_start)='".mysql_real_escape_string($bulan)."' and year(date_start)='".mysql_real_escape_string($tahun)."'");
  
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
  $query = "SELECT * FROM calendarevent where  month(date_start)='".mysql_real_escape_string($bulan)."' and year(date_start)='".mysql_real_escape_string($tahun)."' order by id  LIMIT ".$awal.",".$brs.""; 
  
  $query1 = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
//    $num_of_rows = mysql_num_rows ($query1) 
 //   or die (error("<font class='ver10'>Tidak ditemukan data berita di database!</font>"));
	
$data .='<table width="100%"  cellspacing="2" cellpadding="2"  >
	<tr><form action="index.php" method="get" name="agenda"><td valign=top> &nbsp;Pilih Bulan &nbsp;&nbsp;
	<select name=bulan onchange="document.location.href=\'index.php?id=agenda&bulan=\'+document.agenda.bulan.value+\'&tahun=\'+document.agenda.tahun.value">';
	if ($bulan=='01') $s1="selected";
	elseif ($bulan=='02') $s2="selected";
	elseif ($bulan=='03') $s3="selected";
	elseif ($bulan=='04') $s4="selected";
	elseif ($bulan=='05') $s5="selected";
	elseif ($bulan=='06') $s6="selected";
	elseif ($bulan=='07') $s7="selected";
	elseif ($bulan=='08') $s8="selected";
	elseif ($bulan=='09') $s9="selected";
	elseif ($bulan=='10') $s10="selected";
	elseif ($bulan=='11') $s11="selected";
	elseif ($bulan=='12') $s12="selected";
	$data .="<option value='01' $s1>Januari</option><option value='02' $s2>Februari</option><option value='03' $s3>Maret</option>
	<option value='04' $s4>April</option><option value='05' $s5>Mei</option><option value='06' $s6>Juni</option>
	<option value='07' $s7>Juli</option><option value='08' $s8>Agustus</option><option value='09' $s9>September</option>
	<option value='10' $s10>Oktober</option><option value='11' $s11 >November</option><option value='12' $s12 >Desember</option>";
	$data.='</select>&nbsp;&nbsp;Tahun &nbsp;&nbsp;
		<select name=tahun onchange="document.location.href=\'index.php?id=agenda&bulan=\'+document.agenda.bulan.value+\'&tahun=\'+document.agenda.tahun.value">';
    $th=date("Y")+3;
    for ($k=2009;$k<=$th;$k++) {
        if ($k==$tahun)  $data .="<option value='$k' selected >$k</option>";
        else   $data .="<option value='$k' >$k</option>";
    }
	$data .='</select></td></form></tr>
	</table>';

$data .='<BR>';
	$i=1;
	  if ($jml!=0) {
  $data .= "<center><a href='index.php?id=agenda&bulan=$bulan&tahun=$tahun&hal=1' class=ver10 title='Page 1'>Awal </a> 
  <a href='index.php?id=agenda&bulan=$bulan&tahun=$tahun&hal=$back' class=ver10 title='$back'>Sebelum</a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$data .= "<b><a href='index.php?id=agenda&bulan=$bulan&tahun=$tahun&hal=$i' class=ver10 title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$data .= "<a href='index.php?id=agenda&bulan=$bulan&tahun=$tahun&hal=$i' class=ver10 title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $data .= "<a href='index.php?id=agenda&bulan=$bulan&tahun=$tahun&hal=$next' class=ver10 title='$next'> Lanjut</a> 
  <a href='index.php?id=agenda&bulan=$bulan&tahun=$tahun&hal=$jml' class=ver10 title='Hal $jml'> Akhir</a></center>";
  }
$data .='<table width="100%" class="art-article" cellspacing="1" cellpadding="2"  >';

$n=($brs*($hal-1)) + 1; 
$data .='<tr ><th>No</th><th>Tanggal</th><th>Acara</th><th>Kegiatan</th></tr>';
	while($row=mysql_fetch_array($query1)) {
	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
  $tgl1=date("d-m-Y",strtotime($row[date_start]));
  $tgl2=date("d-m-Y",strtotime($row[date_end]));
	$data .="<tr ><td valign=top width=5%  >$n</td><td  valign=top width=15%>$tgl1 s/d <br>$tgl2</td>
	<td valign=top width=20%  >$row[eventTitle]</td>
	<td valign=top width=50%  >$row[EventDetail]</td>";
	$n++;
	}
$data .='</table><br>';


return $data;
}
?>