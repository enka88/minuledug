<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

//---------------------------------- data guru ---------------------------------//
function dataguru() {
include "koneksi.php";
include_once ("../functions/fungsi_filter.php");

$nama = filter($_GET['nama'],"lcase ucase space");
$kd   =$_GET['kd'];
$hal =$_GET['hal'];
if ($kd=='') $kd='0';
if ($kd=='0') $sl1='selected';
else $sl2='selected';

  $brs=40;
  $kol=10;
  $byk_result=mysql_query("select * from t_staf where kategori='".mysql_real_escape_string($kd)."' and nama like'%".mysql_real_escape_string($nama)."%'");
  
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
 $query = "SELECT * FROM t_staf where kategori='".mysql_real_escape_string($kd)."' and nama like'%".mysql_real_escape_string($nama)."%'  order by user_id  LIMIT ".$awal.",".$brs.""; 

  
  $query1 = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
//    $num_of_rows = mysql_num_rows ($query1) 
 //   or die (error("<font class='ver10'>Tidak ditemukan data berita di database!</font>"));

$data .='<table width="100%" cellspacing="2" cellpadding="2"  >
	<tr><form action="guru.php" method="get" name="guru"><td width="60%" >
	<input type=hidden name="id" value="dbguru">Pilih Data 
	<select name=kd onchange="document.location.href=\'guru.php?id=dbguru&kd=\'+document.guru.kd.value">
	<option value=0 '.$sl1.'>GURU</option>
	<option value=1 '.$sl2.'>Pegawai TU</option></select>&nbsp;&nbsp;&nbsp;
	Cari Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="nama" value="'.$nama.'" ></td>
	<td width="40%" ><input type=submit value=" Cari " class="art-button" onclick="document.location.href=\'guru.php?id=dbguru&nama=\'+document.guru.nama.value" >
	</td></form></tr>
	</table>';

$data .='<BR>';
	$i=1;
	  if ($jml!=0) {
  $data .= "<center><a href='guru.php?id=dbguru&kd=$kd&nama=$nama&hal=1'  title='Hal 1'>Awal </a> 
  <a href='guru.php?id=dbguru&kd=$kd&nama=$nama&hal=$back' title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$data .= "<b><a href='guru.php?id=dbguru&kd=$kd&nama=$nama&hal=$i' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$data .= "<a href='guru.php?id=dbguru&kd=$kd&nama=$nama&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $data .= "<a href='guru.php?id=dbguru&kd=$kd&nama=$nama&hal=$next' title='$next'> Lanjut</a> 
  <a href='guru.php?id=dbguru&kd=$kd&nama=$nama&hal=$jml' title='Hal $jml'> Akhir</a></center>";
  }
$data .='<table  cellspacing="1" cellpadding="2" class="art-article" width="100%" >';

$n=($brs*($hal-1)) + 1; 
$data .='<tr ><th><b>No</b></th><th><b>NIP </b></th><th><b>Nama</b></th><th><b>Pelajaran</b></th><th><b>Jabatan</b></th><th><b>Detail</b></th></tr>';
	while($row=mysql_fetch_array($query1)) {
	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;

	$data .="<tr  onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\" ><td valign=top >$n</td><td valign=top >$row[nip]</td>
	<td valign=top >$row[nama]</td>
	<td valign=top >$row[pelajaran]</td><td valign=top >$row[tugas]</td>
	<td valign=top ><center><a href='guru.php?id=data&kode=$row[user_id]' ><img src='../images/edit.gif' style='border:0;margin:0;' ></a></td></tr>";
	$n++;
	}
$data .='</table>';


return $data;
}
function silabus() {
include "koneksi.php";
include_once ("../functions/fungsi_filter.php");
$kd=$_GET['kode'];
$hal=abs((int)$_GET['hal']);
$sem= filter($_GET['sem'],"num");

if ($kd=='') {

if ($sem=='') $sem='1';
  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_silabus where semester='".mysql_real_escape_string($sem)."'");
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
  
  $query = "SELECT * FROM t_silabus where semester='".mysql_real_escape_string($sem)."' order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $silabus .= "<table width='97%' border='0' cellspacing='4' cellpadding='2' >
  <tr><td><b><form action='guru.php?id=silabus' method='post' name=silabus >Semester ";
  $silabus .= '<select name="sem" onchange="document.location.href=\'guru.php?id=silabus&sem=\'+document.silabus.sem.value">';
    $q2 = mysql_query ("select * from t_semester order by semester");
  while($r = mysql_fetch_array($q2)) {
	if ($r[semester]==$sem) $silabus .= "<option value='$r[semester]' selected>$r[semester]</option>";
	else $silabus .= "<option value='$r[semester]' >$r[semester]</option>";
  }
  $silabus .="</select>&nbsp; </form></td></tr>";
  if ($jml!=0) {
  $silabus .= "<tr><td  ><center><font class='ver10'><a href='guru.php?id=silabus&sem=$sem&hal=1' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='guru.php?id=silabus&sem=$sem&hal=$back' style='color:000000;text-decoration:none' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$silabus .= "<b><a href='guru.php?id=silabus&sem=$sem&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	$silabus .= "<a href='guru.php?id=silabus&sem=$sem&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  $silabus .= "<a href='guru.php?id=silabus&sem=$sem&hal=$next' style='color:000000;text-decoration:none' title='$next'> Next</a> 
  <a href='guru.php?id=silabus&sem=$sem&hal=$jml' style='color:000000;text-decoration:none' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {
		$silabus .="<tr><td>$gb<font class='ver10'>Pelajaran <b>$row[pelajaran]</b>, kelas <b>$row[kelas]</b><br>
		Didownload $row[visit] kali, [ <b><a href='guru.php?id=silabus&kode=$row[id]' class=ver10>Download</a></b> ], Update : $row[tanggal]<br>$row[ket]</td></tr><tr><td height=2 background='../images/gris_user.gif'></td></tr>";
 }        
  $silabus .= "</table>";
}
else {
	$query="select * from  t_silabus where id='".mysql_real_escape_string($kd)."'";
	$alan=mysql_query($query) or die ("query gagal 1");
	$row=mysql_fetch_array($alan);
	$n =$row[visit];
	$n++;
	$query="update t_silabus set visit='$n' where id='".mysql_real_escape_string($kd)."'";
	$alan=mysql_query($query) or die ("query gagal 2");
	if($row[file]=='0') header("Location: guru.php?id=silabus");
	else echo "<script>document.location.href = '../silabus/$row[file]';</script>";
}   //header("Location: ../silabus/$row[file]");
return $silabus;
}

function homepage() {
include "koneksi.php";
 	$sql="select * from t_homepage where jenis='1' order by visit desc ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 homepage");
	
$homepage .='<table width="97%" border="0" cellspacing="2" cellpadding="2">';
	$i=0;
	while($row=mysql_fetch_array($query)) {
	$a="";$b="";
	  if ($i==0) {$i=1;$a="<tr>"; }
	  else {$i=0;$b="</tr>";}
	    $file="../homepage/$row[folder]/homepage.jpg";
	  	$gbr="<img src='../homepage/mode/homepage.jpg' width=92 height=102 id='gambar' >";
		if (file_exists(''.$file.'')) {
	        $gbr="<img src='../homepage/$row[folder]/homepage.jpg' width=92 height=102 id='gambar' >";
		}
		$c=strtotime($row[tanggal]);
		$tgl=date("d M Y",$c);
		$jam=date("H:i:s",$c);
	  $homepage .="$a<td bgcolor='#88CBDA'><table width='100%' border=0 cellspacing='0' cellpadding='2'>
	  <tr><td><a href='visit.php?kode=$row[id_mgmp]' target='_blank' title='http://$webhost/homepage/$row[folder]'>$gbr</a></td><td valign=top>
	  <b><a href='visit.php?kode=$row[id_mgmp]' target='_blank' class='tah10'>$row[judul]</a></b><br>$row[ket]<br>Pengunjung $row[visit] kali, Update $tgl $jam</td></tr>
	  </table></td>$b";

	}
$homepage .='</center></table>';


return $homepage;
}
function data() {
include "koneksi.php";
$kode=$_GET['kode'];

	 $sql="select * from t_staf where user_id='".mysql_real_escape_string($kode)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);
	$sek = $nmsekolah;
$thn =  strval(date("Y"))- strval($row[thn]);
$bln =  strval(date("m"))- strval($row[bln]); 
if ($bln<0) { $bln = $bln + 12; 
$thn =  $thn - 1;
}
	$file ="../images/staf/".$row[nip].".jpg";
		$gbr="<img src='../images/noava.jpg' width='150' height='180' id='gambar' >";
		if (file_exists($file)) {
	        $gbr="<img src='../images/staf/$row[nip].jpg' width='150' height='180' id='gambar' >";
		}
		if ($row[kelamin]=='P') $kel='Perempuan';
		else $kel='Laki-laki';

	 $sql="select * from t_member where nis=".$row[user_id]."";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$r=mysql_fetch_array($query);
	$blog=$r[homepage];
$data .='<table width="100%" class="art-article" cellspacing="0" cellpadding="0"  >
  <tr>
    <td colspan="2" valign=top>';
	$data .="<table width='100%' cellspacing=2 cellpadding=1 align=center >
	<tr><td  valign=top width=20%>Nama</td><td width=50%>$row[nama]</td>
	<td rowspan=13 width=20%>$gbr</td></tr>
	<tr><td  valign=top>NIP</td><td>$row[nip]</td></tr>
    <tr><td  valign=top>NUPTK</td><td>$row[nuptk]</td></tr>
	<tr><td ' valign=top>Kelamin</td><td>$kel</td></tr>
	<tr><td  valign=top>Tmp/Tgl Lahir</td><td>$row[tmp_lahir],$row[tgl_lahir]</td></tr>
	<tr><td  valign=top>Pelajaran/Jabatan</td><td>$row[pelajaran]</td></tr>
	<tr><td  valign=top>Pangkat/Gol</td><td>$row[pangkat]</td></tr>
	<tr><td  valign=top>Status</td><td>$row[tugas]</td></tr>
	<tr><td  valign=top>Email</td><td>$row[email]</td></tr>
	<tr><td  valign=top>Alamat Rumah</td><td >$row[alamat]<br>Telp.$row[telp]<br>Hp.$row[hp]</td></tr>
	<tr><td  valign=top>Homepage/Blog</td><td ><a href='$blog' target='_target' >$blog</a></td></tr>
	<tr><td  valign=top>Profil/Prestasi</td><td >$row[profilstaf]</td></tr>
	</table>";
$data .='</td></tr></table><br>';
return $data;
}
 
?>