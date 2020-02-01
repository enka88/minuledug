<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function databk() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$nis = konversi_id($userid);
$nmsiswa = konversi_nama($nis);
$cetak .= ataslogin("Data BP/BK - Siswa - $nmsiswa");

  $brs=30;
  $kol=10;
  $byk_result=mysql_query("select * from t_bpbk where nis='".mysql_real_escape_string($nis)."' ");
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
  
  $query = "select * from t_bpbk where nis='".mysql_real_escape_string($nis)."'  order by tgl  LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  $cetak .="<table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=6  ><center><a href='user.php?id=databk&hal=1'  title='Hal 1'>First </a> 
  <a href='user.php?id=databk&hal=$back'  title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=databk&hal=$i'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=databk&hal=$i'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=databk&hal=$next'  title='$next'> Next</a> 
  <a href='user.php?id=databk&hal=$jml' title='Page $jml'> Last</a></font></center></td></tr>";
  }
  $cetak .="<tr class='td0' ><td><b>No</td><td><b>Tanggal</td><td><b>Guru BP/BK</td>
  <td><b>Kelas</td><td><b>Sem</td><td><b>Penilaian</td><td><b>Keterangan</td></tr>";
  $n=($brs*($hal-1)) + 1; 
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;

   $cetak .= "<tr class=$warna ><td width='5%' valign=top>$n</td>
   <td width='10%' valign=top>".date("d-m-y",strtotime($row[tgl]))."</td>
   <td width='10%' valign=top>$row[guru]</td>
   <td width='10%' valign=top >$row[kelas]</td>
   <td width='5%' valign=top>$row[sem]</td><td width='20%' valign=top>$row[penilaian]</td>
   <td width='30%' valign=top>$row[ket]</td></tr>";
	$n++;
 }        
  $cetak .= "</table>";
  $cetak.="</div>";
return $cetak;
}

function gurubk() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak  .="<div id='depan-tengahkanan'>";
$cetak  .= statusanda($userid);
$cetak  .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$kelas=$_GET['kelas'];
$sem=$_GET['sem'];
$program=$_GET['program'];
if ($hal=='') $hal=$_POST['hal'];
if ($sem=='') $sem=$_POST['sem'];
if ($kelas=='') $kelas=$_POST['kelas'];
if ($program=='') $program=$_POST['program'];

if ($sem=='') $sem=1;
if ($kelas=='') $kelas='X - 1';
$nip = konversi_id($userid);
$cetak .= ataslogin("Data BP/BK - Guru");
  $brs=50;
  $kol=10;

  $byk_result=mysql_query("SELECT * FROM t_bpbk where kelas='".mysql_real_escape_string($kelas)."' and sem='".mysql_real_escape_string($sem)."' ");
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
  
  $query = "SELECT * FROM t_bpbk where kelas='".mysql_real_escape_string($kelas)."' and sem='".mysql_real_escape_string($sem)."' order by tgl DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

if($program=='') $program='-';
    $data2 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data2 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data2 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data2 .= "</select> &nbsp;";	
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
  $data3 .='Jurusan/Program keahlian <select name=program onchange="document.location.href=\'user.php?id=gurubk&program=\'+document.nilai.program.value">';
	$sql="select * from t_programahli ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($program==$row[program]) $data3 .="<option value='$row[program]' selected>$row[program]</option>";
		else $data3 .="<option value='$row[program]'>$row[program]</option>";
	}
	$data3 .='</select>&nbsp;&nbsp;'; 
  }
  else $data3 = "<input type=hidden name=program value='-'/>";    
  	$data .='<select name=kelas >';
	$sql="select * from t_kelas where program='".mysql_real_escape_string($program)."' ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
  $cetak .= "<a href='user.php?id=gurubktambah' id=button2 >Tambah Data</a> <br><br>
  <form action='user.php?id=gurubk' method=post name='nilai' >
   $data3 &nbsp;&nbsp;Kelas $data &nbsp;&nbsp; Sem $data2 &nbsp; &nbsp;<input type=submit value=' Pilih ' id=button2  ></form> <table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=8 ><center><a href='user.php?id=gurubk&kelas=$kelas&sem=$sem&hal=1'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurubk&kelas=$kelas&sem=$sem&hal=$back'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurubk&kelas=$kelas&sem=$sem&hal=$i'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurubk&kelas=$kelas&sem=$sem&hal=$i'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurubk&kelas=$kelas&sem=$sem&hal=$next'  title='$next'> Next</a> 
  <a href='user.php?id=gurubk&kelas=$kelas&sem=$sem&hal=$jml'  title='Page $jml'> Last</a></font></center></td></tr>";
  }
    $cetak .="<tr class=td0 ><td><b>No</td><td><b>Tgl Kirim</td><td><b>NIS/Nama</td>
  <td><b>Guru</td><td><b>Penilaian</td><td><b>Ket</td><td><b>Hap</td><td><b>Edit</td></tr>";
 
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	$sql="select * from t_siswa where user_id='$row[nis]'";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	$ro=mysql_fetch_array($q);
	$nama=$ro[nama];
	
    $cetak .= "<tr class=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='2%' valign=top >$j</td>
   <td width='3%' valign=top >".date("d-m-Y",strtotime($row[tgl]))."</td>
   <td width='15%' valign=top >$row[nis]<br>$nama</td>
   <td width='15%' valign=top >$row[guru]</td>
   <td width='15%' valign=top >$row[penilaian]</td>
   <td width='15%' valign=top >$row[ket]</td>
   <td width='2%' valign=top ><a href='user.php?id=gurubkhapus&kd=$row[id]' title='klik untuk hapus data'><img src='../images/hapus.gif'></a></td>
   <td width='2%' valign=top ><a href='user.php?id=gurubkedit&kd=$row[id]' title='klik untuk edit data'><img src='../images/edit.gif'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table>";
 $cetak .="</div>";
 
return $cetak;
}

function gurubktambah() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak  .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak  .="<hr style='border: thin solid #6A849D;'>";
$kelas=$_GET['kelas'];
$sem=$_GET['sem'];
if ($sem=='') $sem=$_POST['sem'];
if ($kelas=='') $kelas=$_POST['kelas'];
$cetak .= ataslogin("Tambah BP/BK - Guru");

$nip = konversi_id($userid);
$sql="select * from t_mengajar where nip='$nip' and pel='BK'";
if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
$ro=mysql_fetch_array($q);
//$kelas = $ro[kelas];
$r=mysql_num_rows($q);
if ($sem=='') $sem='1';

if ($r > 0) {

  $data .='<select name="kelas" onchange="document.location.href=\'user.php?id=gurubktambah&kelas=\'+document.gurubk.kelas.value+\'&sem=\'+document.gurubk.sem.value" >';
	$sql="select * from t_mengajar where nip='".mysql_real_escape_string($nip)."' and pel='BK'";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected >$row[kelas]</option>";
		elseif ($kelas=='') { $data .="<option value='$row[kelas]' selected >$row[kelas]</option>";$kelas=$row[kelas];}
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>';
	$data2 .="<select name='nis' >";
	$sql="select * from t_siswa where kelas='".mysql_real_escape_string($kelas)."' ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 nama ");
	while($row=mysql_fetch_array($q)) {
		$data2 .="<option value='$row[user_id]'>$row[nama]</option>";
	}
	$data2.='</select>';
  $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
$cetak .="<table width='100%' id=tablebaru cellspacing='1' cellpadding='3'><form action='user.php?id=gurubk_save' method='post' name='gurubk' >
  <tr><td><b>Sem</td><td> $data3</td></tr>
  <tr><td><b>Kelas</td><td> $data</td></tr>
  <tr><td><b>Nama</td><td> $data2</td></tr>
  <tr><td><b>Penilaian</td><td valign=top> <textarea name='pesan' cols=40 rows=3 ></textarea></td></tr>
  <tr><td><b>Ket</td><td valign=top > <textarea name='ket' cols=40 rows=3 ></textarea></td></tr>
  <tr><td><b></td><td><input type='submit' value=' Simpan ' id=button2 >
  <input type=hidden name='save' value='tambah'><input type=hidden name='st' value='1'>
  </td></tr>
  </form></table>";
}
else $cetak .="Mohon Maaf, fasilitas ini dipergunakan untuk Guru BP/BK";
$cetak .="</div>";
return $cetak;
}


function gurubk_save() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Simpan BP/BK - Guru");
$sem=$_POST['sem'];
$kelas=$_POST['kelas'];
$pesan=$_POST['pesan'];
$ket=$_POST['ket'];
$save=$_POST['save'];
$nis=$_POST['nis'];
$kd=$_POST['kd'];
$st=$_POST['st'];

$nip = konversi_id($userid);
$sql="select * from t_staf where nip='".mysql_real_escape_string($nip)."'";
if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
$r=mysql_fetch_array($q);
$nmguru =$r[nama];
if ($st==1) {
  if ($save=='tambah') {
  	$tgl = date("Y-m-d");
  		$sql="insert into t_bpbk (nis,guru,kelas,tgl,sem,penilaian,ket) values ('".mysql_real_escape_string($nis)."','".mysql_real_escape_string($nmguru)."','".mysql_real_escape_string($kelas)."','".mysql_real_escape_string($tgl)."','".mysql_real_escape_string($sem)."','".mysql_real_escape_string($pesan)."','".mysql_real_escape_string($ket)."') ";
		if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 bpbk ");
		$cetak .="<br><center>Terima Kasih, Data BP/BK telah bertambah.</center>";
  }
  else {
    	$tgl=date("Y-m-d");
  		$sql="update t_bpbk set guru='".mysql_real_escape_string($nmguru)."',tgl='$tgl',sem='".mysql_real_escape_string($sem)."',penilaian='".mysql_real_escape_string($pesan)."',ket='".mysql_real_escape_string($ket)."' where id='".mysql_real_escape_string($kd)."' ";
		if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 update");
		$cetak .="<br><center>Terima Kasih, Perubahan Data BP/BK telah berhasil.</center>";
  
  }
}
else $cetak .="Mohon Maaf, fasilitas ini dipergunakan untuk Guru BP/BK";
$cetak .="</div>";
return $cetak;
}

function gurubkedit() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Perubahan Data BP/BK - Guru");
$kd =$_GET['kd'];
if ($kd=='') $kd=$_POST['kd'];

$nip = konversi_id($userid);
$sql="select * from t_mengajar where nip='".mysql_real_escape_string($nip)."' and pel='BK'";
if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
$r=mysql_num_rows($q);

if ($r > 0) {
 	$sql="select * from t_bpbk where id='".mysql_real_escape_string($kd)."'";
	if(!$q2=mysql_query($sql)) die ("Pengambilan gagal1 bp ");
	$row=mysql_fetch_array($q2);
	$sem =$row[sem];
	$sql1="select nama from t_siswa where user_id='$row[nis]'";
	if(!$q=mysql_query($sql1)) die ("Pengambilan gagal1 bp ");
	$ro=mysql_fetch_array($q);
	$nama=$ro[nama];
	$data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
$cetak .="<table width='100%' id=tablebaru cellspacing='1' cellpadding='3'><form action='user.php?id=gurubk_save' method='post' name='gurubk' >
  <tr><td><b>Sem</td><td> $data3 </td></tr>
  <tr><td><b>Kelas</td><td> $row[kelas]</td></tr>
  <tr><td><b>Nama</td><td> $nama</td></tr>
  <tr><td><b>Penilaian</td><td valign=top > <textarea name='pesan' cols=40 rows=3 >$row[penilaian]</textarea></td></tr>
  <tr><td><b>Ket</td><td valign=top > <textarea name='ket' cols=40 rows=3 >$row[ket]</textarea></td></tr>
  <tr><td><b></td><td><input type='submit' value=' Simpan ' id=button2 ><input type=hidden name='kd' value='$kd'>
  <input type=hidden name='save' value='edit'><input type=hidden name='st' value='1'>
  </td></tr>
  </form></table>";
}
else $cetak .="Mohon Maaf, fasilitas ini dipergunakan untuk Guru BP/BK";
$cetak .="</div>";
return $cetak;
}

function gurubkhapus() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$kd =$_GET['kd'];
if ($kd=='') $kd=$_POST['kd'];
$nip = konversi_id($userid);
$sql="select * from t_mengajar where nip='".mysql_real_escape_string($nip)."' and pel='BK'";
if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
$r=mysql_num_rows($q);

  if ($r>0) {
		$sql="delete from t_bpbk where id='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
		// Header("Location: user.php?id=gurubk");
         echo "<script>document.location.href = 'user.php?id=gurubk';</script>";
  		return 0;
  }
  else {
  	$userid = $_SESSION['User']['userid'];
	$cetak .="<div id='depan-tengahkanan'>";
	$cetak .= statusanda($userid);
	$cetak .="<hr style='border: thin solid #6A849D;'>";
  	$cetak ="<br><center>Mohon Maaf, fasilitas ini dipergunakan untuk Guru BP/BK</center>";
	$cetak .="</div>";
  	return $cetak;
  }

} 
 
?>