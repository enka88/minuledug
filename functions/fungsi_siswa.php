<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=$_GET['hal']; menjadi $hal=abs((int)$_GET['hal']);
*/

function prestasi() {
include "koneksi.php";
$hal=abs((int)$_GET['hal']);

  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_prestasi ");
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
  
  $query = "SELECT * FROM t_prestasi order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $prestasi .= "<table width='100%' border='0' cellspacing='4' cellpadding='2'  >";
  if ($jml!=0) {
  $prestasi .= "<tr><td  ><center><a href='siswa.php?id=prestasi&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='siswa.php?id=prestasi&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$prestasi .= "<b><a href='siswa.php?id=prestasi&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$prestasi .= "<a href='siswa.php?id=prestasi&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $prestasi .= "<a href='siswa.php?id=prestasi&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='siswa.php?id=prestasi&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a>
  </center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {
  		$file ="../images/prestasi/".$row[id].".jpg";
		$gbr="<img src='../images/noava.jpg' id='gambar' >";
		if (file_exists($file)) {
	        $gbr="<img src='../images/prestasi/$row[id].jpg' width=200 height=170 class='art-article' >";
		}
		$prestasi .="<tr><td valign=top>$gbr</td><td valign=top><b>$row[judul]</b><br>
		$row[ket]</td></tr>
		<tr><td colspan=2 height=2 background='../images/gris_user.gif'></td></tr>";
 }        
  $prestasi .= "</table>";

return $prestasi;
}

/*---------------------------------- data siswa ---------------------------------*/
function datasiswa() {
include "koneksi.php";
include_once ("../functions/fungsi_filter.php");
$nama = filter($_GET['nama'],"lcase ucase space");
$kd = filter($_GET['kd'],"lcase ucase space num");
$hal =$_GET['hal'];
$program = filter($_GET['program'],"lcase ucase space");

  $brs=50;
  $kol=10;
  if ($nama<>'') $byk_result=mysql_query("select * from t_siswa where nama like'%".mysql_real_escape_string($nama)."%' ");
  else $byk_result=mysql_query("select * from t_siswa where  kelas='".mysql_real_escape_string($kd)."'");
  
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
  if ($nama<>'')  $query = "SELECT * FROM t_siswa where nama like'%".mysql_real_escape_string($nama)."%' order by nama  LIMIT ".$awal.",".$brs.""; 
	else $query = "SELECT * FROM t_siswa where kelas='".mysql_real_escape_string($kd)."' order by nama  LIMIT ".$awal.",".$brs.""; 
  
  $query1 = mysql_query ($query) 
  or die (mysql_error()); 

  if ($program=='') $program='-';
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
    $data3 ='Jurusan/Program <select name="program" onchange="document.location.href=\'siswa.php?id=dbsiswa&program=\'+document.siswa.program.value" >';
	$sql="select * from t_programahli order by idprog";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 program ");
	while($al=mysql_fetch_array($q)) {
  		if ($al[program]==$program)$data3 .= "<option value='$al[program]' selected>$al[program]</option>";
  		else $data3 .="<option value='$al[program]' >$al[program]</option>";
	}
	$data3 .='</select>';
  }
  else $data3 = "<input type=hidden name=program value='-'/>";
  
$data .='<table width="100%"  cellspacing="2" cellpadding="2"  ><form action="siswa.php" method="get" name="siswa">
	<tr><td > &nbsp; '.$data3.' &nbsp;Kelas &nbsp;&nbsp;
	<select name=kd onchange="document.location.href=\'siswa.php?id=dbsiswa&kd=\'+document.siswa.kd.value+\'&program=\'+document.siswa.program.value">';
	$sql="select * from t_kelas where program='".mysql_real_escape_string($program)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kd==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select></td><td width="50"><input type=button value="Pilih" class="art-button" onclick="document.location.href=\'siswa.php?id=dbsiswa&kd=\'+document.siswa.kd.value+\'&program=\'+document.siswa.program.value" >
	</td><td>Cari Siswa&nbsp;&nbsp;&nbsp;<input type=text name="nama" value="'.$nama.'" ></td>
	<td><input type="submit" value="Cari" class="art-button" onclick="document.location.href=\'siswa.php?id=dbsiswa&nama=\'+document.siswa.nama.value"></td></tr></form>
	</table>';

$data .='<BR>';
	$i=1;
	  if ($jml!=0) {
  $data .= "<center><a href='siswa.php?id=dbsiswa&nama=$nama&kd=$kd&program=$program&hal=1' title='Hal 1'>Awal </a> 
  <a href='siswa.php?id=dbsiswa&nama=$nama&kd=$kd&program=$program&hal=$back' title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$data .= "<b><a href='siswa.php?id=dbsiswa&nama=$nama&kd=$kd&program=$program&hal=$i' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$data .= "<a href='siswa.php?id=dbsiswa&nama=$nama&kd=$kd&program=$program&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $data .= "<a href='siswa.php?id=dbsiswa&nama=$nama&kd=$kd&program=$program&hal=$next' title='$next'> Lanjut</a> 
  <a href='siswa.php?id=dbsiswa&nama=$nama&kd=$kd&program=$program&hal=$jml' title='Hal $jml'> Akhir</a></center>";
  }
$data .='<table width="100%" cellspacing="1" cellpadding="2" class="art-article" >';

$n=($brs*($hal-1)) + 1; 
$data .='<tr ><th >No</th><th >NIS</th><th >Nama</th><th >Kelas</th><th >Detail</th>';
	while($row=mysql_fetch_array($query1)) {
	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;

	$data .="<tr  onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td valign=top >$n</td><td valign=top >$row[user_id]</td>
	<td valign=top >$row[nama]</td>
	<td valign=top >$row[kelas]</td>
	<td valign=top ><center><a href='siswa.php?id=data&kode=$row[user_id]' class='login' ><img src='../images/edit.gif' style='border:0;margin:0;' ></a></td>";
	$n++;
	}
$data .='</table><br></center>';


return $data;
}

function homepage() {
include "koneksi.php";
 	$sql="select * from t_homepage where jenis='2' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 homepage");
	
$homepage .='<table width="97%" id="tablebaru" >';
	$i=0;
	while($row=mysql_fetch_array($query)) {
	$a="";$b="";
	  if ($i==0) {$i=1;$a="<tr>"; }
	  else {$i=0;$b="</tr>";}
	    $file="../homepage/$row[folder]/homepage.jpg";
	  	$gbr="<img src='../homepage/mode/homepage.jpg' width=92 height=102>";
		if (file_exists(''.$file.'')) {
	        $gbr="<img src='../homepage/$row[folder]/homepage.jpg' width=92 height=102>";
		}
		$c=strtotime($row[tanggal]);
		$tgl=date("d M Y",$c);
		$jam=date("H:i:s",$c);
	  $homepage .="$a<td ><table width='100%' border=0 cellspacing='0' cellpadding='2'>
	  <tr><td><a href='visit.php?kode=$row[id_mgmp]' target='_blank' title='$webhost/homepage/$row[folder]'>$gbr</a></td><td valign=top>
	  <b><a href='visit.php?kode=$row[id_mgmp]' target='_blank' class='tah10'>$row[judul]</a></b><br>$row[ket]<br>Pengunjung $row[visit] kali, Update $tgl $jam</td></tr>
	  </table></td>$b";
	}
$homepage .='</center></table>';


return $homepage;
}
function ekstra() {
include "koneksi.php";
 	$sql="select * from t_homepage where jenis='3' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 ektra");
	
$ektra .='<table width="97%" border="1" bordercolor="'.#88CBDA.'" cellspacing="2" cellpadding="2" class="tabletd" >';
	$i=0;
	while($row=mysql_fetch_array($query)) {
	$a="";$b="";
	  if ($i==0) {$i=1;$a="<tr>"; }
	  else {$i=0;$b="</tr>";}
	    $file="../homepage/$row[folder]/homepage.jpg";
	  	$gbr="<img src='../homepage/mode/homepage.jpg' width=92 height=102>";
		if (file_exists(''.$file.'')) {
	        $gbr="<img src='../homepage/$row[folder]/homepage.jpg' width=92 height=102>";
		}
			$c=strtotime($row[tanggal]);
		$tgl=date("d M Y",$c);
		$jam=date("H:i:s",$c);
	  $ektra .="$a<td ><table width='100%' border=0 cellspacing='0' cellpadding='2'>
	  <tr><td><a href='visit.php?kode=$row[id_mgmp]' target='_blank' title='$webhost/homepage/$row[folder]'>$gbr</a></td><td valign=top>
	  <b><a href='visit.php?kode=$row[id_mgmp]' target='_blank' class='tah10'>$row[judul]</a></b><br>$row[ket]<br>Pengunjung $row[visit] kali,  Update $tgl $jam</td></tr>
	  </table></td>$b";

	}
$ektra .='</center></table>';


return $ektra;
}
function data() {
include"koneksi.php";
$kode=$_GET['kode'];
	$sql="select * from t_siswa where user_id='".mysql_real_escape_string($kode)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);

		$file ="../images/siswa/".$row[user_id].".jpg";
		$gbr="<img src='../images/noava.jpg' width='130' height='160' id='gambar' >";
		if (file_exists($file)) {
	        $gbr="<img src='../images/siswa/$row[user_id].jpg' width='130' height='160' id='gambar' >";
		}
		if ($row[kelamin]=='P') $kel='Perempuan';
		else $kel='Laki-laki';

	$data .="<center><br><table width='100%' class='art-article' cellspacing=2 cellpadding=1>
	<tr><td  valign=top width=20%>Nama</td><td width=50%>$row[nama]</td>
	<td rowspan=10 width=20%>$gbr</td></tr>
	<tr><td  valign=top>NIS</td><td>$row[user_id]</td></tr>
	<tr><td  valign=top>Kelamin</td><td>$kel</td></tr>
	<tr><td  valign=top>Tmp/Tgl Lahir</td><td>$row[tmp_lahir],$row[tgl_lahir]</td></tr>
	<tr><td  valign=top>Agama</td><td>$row[agama]</td></tr>
	<tr><td  valign=top>Kelas</td><td>$row[kelas]</td></tr>
	<tr><td  valign=top>STTB</td><td>$row[sttb]</td></tr>
	<tr><td  valign=top>NEM</td><td>$row[nem]</td></tr>
	<tr><td  valign=top>Orang tua</td><td>$row[wali]</td></tr>
	<tr><td  valign=top>Alamat Rumah</td><td >$row[alamat]<br>Telp.$row[telp]</td></tr>
	</table></center><br>";

return $data;
}


?>