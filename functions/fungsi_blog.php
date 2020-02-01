<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

function kunjungblog() {
include "koneksi.php";
$kode  = $_GET['kode'];
$judul = $_GET['judul'];
  $byk_result=mysql_query("select kunjungblog from t_member where userid='".mysql_real_escape_string($kode)."'");
  $row=mysql_fetch_array($byk_result);
  $kd = $row[kunjungblog]+1;
    $query1 = "update t_member set kunjungblog='$kd' where userid='".mysql_real_escape_string($kode)."'";
   	$result = mysql_query($query1) or die("Pengambilan gagal pesan");
   // header("Location: http://$judul");
    echo "<script>document.location.href = 'http://$judul';</script>";
}


function dafblog() {
include "koneksi.php";
include ("../functions/fungsi_filter.php");
$nama = filter($_GET['nama'],"lcase ucase num");
$kd   = filter($_GET['kd'],"ucase lcase");
$hal=abs((int)$_GET['hal']);
if ($kd=='') $kd='Siswa';

  $brs=40;
  $kol=10;
  if ($nama<>'') $byk_result=mysql_query("select * from t_member where stblog='1' and nama like'%".mysql_real_escape_string($nama)."%' ");
  else $byk_result=mysql_query("select * from t_member where ket='".mysql_real_escape_string($kd)."' and stblog='1'");
  
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
  if ($nama<>'')  $query = "SELECT * FROM t_member where stblog='1' and nama like'%".mysql_real_escape_string($nama)."%' order by kunjungblog desc  LIMIT ".$awal.",".$brs.""; 
	else $query = "SELECT * FROM t_member where stblog='1' and ket='".mysql_real_escape_string($kd)."' order by kunjungblog desc  LIMIT ".$awal.",".$brs.""; 
  
  $query1 = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
//    $num_of_rows = mysql_num_rows ($query1) 
 //   or die (error("<font class='ver10'>Tidak ditemukan data berita di database!</font>"));
	
$data .='<form action="index.php" method="get" name="blog">
<table width="70%"  border="0" cellspacing="2" cellpadding="2"  >

	<tr><td > &nbsp;Pilih Status &nbsp;&nbsp;
	<select name=kd onchange="document.location.href=\'index.php?id=dafblog&kd=\'+document.blog.kd.value">';
	if ($kd=='Siswa') $s1="selected";
	elseif ($kd=='Guru') $s2="selected";
	elseif ($kd=='Alumni') $s3="selected";
	else $s4="selected";
	 $data .="<option value='Siswa' $s1>Siswa</option><option value='Guru' $s2>Guru</option>
	 <option value='Alumni' $s3>Alumni</option><option value='Tamu' $s4>Tamu</option>";
	$data.='</select>&nbsp;&nbsp;&nbsp;<input type=hidden name="id" value="dafblog">
	</td><td>Cari Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="nama" value="'.$nama.'" class=editbox >
	</td><td><input type="submit" value=" Cari " class="art-button" />
	</td></tr></table></form>';

$data .='<BR>';
	$i=1;
	  if ($jml!=0) {
  $data .= "<center><a href='index.php?id=dafblog&nama=$nama&kd=$kd&hal=1' title='Hal 1'>Awal </a> 
  <a href='index.php?id=dafblog&nama=$nama&kd=$kd&hal=$back' title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$data .= "<b><a href='index.php?id=dafblog&nama=$nama&kd=$kd&hal=$i' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$data .= "<a href='index.php?id=dafblog&nama=$nama&kd=$kd&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $data .= "<a href='index.php?id=dafblog&nama=$nama&kd=$kd&hal=$next' title='$next'> Lanjut</a> 
  <a href='index.php?id=dafblog&nama=$nama&kd=$kd&hal=$jml' title='Hal $jml'> Akhir</a></font></center>";
  }
$data .='<table width="100%"  border="0" class="art-article" cellspacing="1" cellpadding="2"  >';

$n=($brs*($hal-1)) + 1; 
$data .='<tr ><th width=10 >No</th><th>Nama</th><th>Kelas</th><th>Homepage/Blog</th><th>Hit</th>';
	while($row=mysql_fetch_array($query1)) {
	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;

	$data .="<tr  onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td valign=top >$n</td><td valign=top >$row[nama]</td>
	<td valign=top >$row[kelas]</td><td valign=top ><a href='index.php?id=kunjungblog&judul=$row[homepage]&kode=$row[userid]&kd=$row[kunjungblog]' style='color:#000000' target='_blank'>$row[homepage]</a></td>
	<td valign=top ><center>$row[kunjungblog]</a></td>";
	$n++;
	}
$data .='</table><br></center>';


return $data;
}
?>