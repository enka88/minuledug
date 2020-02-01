<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function datanilai() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$pel=$_GET['pel'];
$thajar =$_GET['thajar'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$nis = konversi_id($userid);
$kelas = konversi_kls($nis);
$nmsiswa = konversi_nama($nis);
$program = konversi_program($kelas);
$cetak .= ataslogin("Data Nilai - Siswa - $nmsiswa");

  $brs=30;
  $kol=10;
  $thajar = mysql_real_escape_string($thajar);
  $tahun = substr($thajar,2,2)."".substr($thajar,7,2);
  $byk_result=mysql_query("SELECT * FROM t_nilai INNER JOIN t_nilai_detail ON (t_nilai.kd_nilai = t_nilai_detail.kd_nilai)
WHERE t_nilai_detail.NIS ='".mysql_real_escape_string($nis)."' and t_nilai.pelajaran='".mysql_real_escape_string($pel)."'  and t_nilai.semester='".mysql_real_escape_string($sem)."' and left(t_nilai.kd_nilai,4)='".$tahun."'");
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
  
  $query = "SELECT * FROM t_nilai INNER JOIN t_nilai_detail ON (t_nilai.kd_nilai = t_nilai_detail.kd_nilai)
WHERE t_nilai_detail.NIS ='".mysql_real_escape_string($nis)."' and t_nilai.pelajaran='".mysql_real_escape_string($pel)."'  and t_nilai.semester='".mysql_real_escape_string($sem)."' and left(t_nilai.kd_nilai,4)='".$tahun."' order by tgl_ujian DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
 $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[thajar]==$thajar) $data4 .=  "<option value='$al[thajar]' selected>$al[thajar]</option>";
  	else $data4 .=  "<option value='$al[thajar]' >$al[thajar]</option>";
  }
  $data4 .= "</select> &nbsp;";	
  
 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  
  $cetak .= "<div align='left'><form action='user.php' method='get' name='siswa'>
  <input type=hidden name=id value='datanilai'>
  Pelajaran : <select name='pel'>";
  $q2 = mysql_query ("select * from t_pelajaran where program='-' or program='".mysql_real_escape_string($program)."' order by pel");
  while($r = mysql_fetch_array($q2)) {
	if ($r[pel]==$pel) $cetak .= "<option value='$r[pel]' selected>$r[pel]</option>";
	else $cetak .= "<option value='$r[pel]' >$r[pel]</option>";
  }

  $cetak .= "</select>&nbsp;&nbsp; Tahun Pelajaran : $data4 &nbsp;&nbsp;Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form></div>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=8  ><center><a href='user.php?id=datanilai&hal=1&pel=$pel&sem=$sem' title='Hal 1'>First </a> 
  <a href='user.php?id=datanilai&hal=$back&pel=$pel&sem=$sem' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=datanilai&hal=$i&pel=$pel&sem=$sem' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=datanilai&hal=$i&pel=$pel&sem=$sem' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=datanilai&hal=$next&pel=$pel&sem=$sem'  title='$next'> Next</a> 
  <a href='user.php?id=datanilai&hal=$jml&pel=$pel&sem=$sem' title='Page $jml'> Last</a></font></center></td></tr>";
  }
  $cetak .="<tr class='td0' ><td>Kode</td><td>Tanggal</td><td>Kompetensi Dasar</td>
  <td>U Ke</td><td>Jenis</td><td>KKM</td><td>Nilai</td><td>Ket</td></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	if ($row[status]=='0') $jenis='U.Harian'; 
	elseif ($row[status]=='1') $jenis='Tgs.Kognitif'; 
	elseif ($row[status]=='2') $jenis='Remedial'; 
	elseif ($row[status]=='3') $jenis='Tugas'; 
	elseif ($row[status]=='4') $jenis='Praktikum'; 
	elseif ($row[status]=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 
	if($row[nilai]>=$row[skbm]) $tuntas='Tuntas';
	else $tuntas='Tidak';
   $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>$row[kd_nilai]</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_ujian]))."</td>
   <td width='25%'>$row[ket]</td><td width='5%'>$row[ujian_ke]</td>
   <td width='15%'>$jenis</td><td width='5%'>$row[skbm]</td><td width='5%'>$row[nilai]</td><td width='10%'>$tuntas</td></tr>";
 }        
  $cetak .= "</table>";
  $cetak .="</div>";
return $cetak;
}


function gurunilai() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$pel=$_GET['pel'];
$kelas=$_GET['kelas'];
$thajar=$_GET['thajar'];
$nip = konversi_id($userid);
$nama= member_nama($userid);
$cetak .= ataslogin("Data Nilai - Guru");
if ($sem=='') $sem='1';

if ($kategori=='Guru') {
  $brs=30;
  $kol=10; 
  // $nama diganti menjadi nip
  $byk_result=mysql_query("SELECT * FROM t_nilai where guru='".mysql_real_escape_string($nip)."' and pelajaran='".mysql_real_escape_string($pel)."' and semester='".mysql_real_escape_string($sem)."' and left(kd_nilai,4)='".$thajar."' and kelas='".mysql_real_escape_string($kelas)."' ");
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
  // $nama diganti menjadi $nip
  $query = "SELECT * FROM t_nilai where guru='".mysql_real_escape_string($nip)."' and pelajaran='".mysql_real_escape_string($pel)."' and semester='".mysql_real_escape_string($sem)."' and left(kd_nilai,4)='".$thajar."' and kelas='".mysql_real_escape_string($kelas)."' order by kelas LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // tambah alan untuk delete multiple	
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	$tahun = substr($al[thajar],2,2)."".substr($al[thajar],7,2);
  	if ($tahun==$thajar) $data4 .=  "<option value='$tahun' selected>$al[thajar]</option>";
  	else $data4 .=  "<option value='$tahun' >$al[thajar]</option>";
  }
  $data4 .= "</select> &nbsp;";
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='gurunilai'>
  <a href='user.php?id=guruniltambah' id=button2 >Tambah Nilai</a> &nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;<a href='user.php?id=nilaiwali' id=button2 >WALI KELAS</a><br>
  <table border=0 >
  <tr><td>Pelajaran </td><td>: <select name='pel'>";
  $q2 = mysql_query ("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r = mysql_fetch_array($q2)) {
	if ($r['pel']==$pel) $cetak .= "<option value='$r[pel]' selected>$r[pel]</option>";
	else $cetak .= "<option value='$r[pel]' >$r[pel]</option>";
  }
  $cetak .= "</select></td><td>Thn Pelajaran </td><td>: $data4 </td></tr>";
  $cetak .= "<tr><td>Semester </td><td>: <select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['semester']==$sem) $cetak .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $cetak .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $cetak .= "</select> </td><td>";	
  $cetak .=  "Kelas </td><td>: <select name='kelas' >";
  $sql2="select distinct(kelas) from t_mengajar where nip='".$nip."' ";
  $my=mysql_query($sql2);
  while($kel=mysql_fetch_array($my)) {
  	if ($kel['kelas']==$kelas) $cetak .=  "<option value='$kel[kelas]' selected>$kel[kelas]</option>";
  	else $cetak .=  "<option value='$kel[kelas]' >$kel[kelas]</option>";
  }
  $cetak .= "</select> ";	  
  $cetak .="<input type=submit value=' Pilih ' id=button2 > ";
 
  $cetak .= ($kelas=='' and $pel=='')?'':"<a href='../functions/fungsi_excelnilai.php?format=semuanilai&kelas=$kelas&thpel=$thajar&pel=$pel&sem=$sem' target='_blank' id=button2 >Download</a>";
  
  $cetak .="</td></tr></form><br>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=8  ><center><a href='user.php?id=gurunilai&hal=1&pel=$pel&sem=$sem' title='Hal 1'>First </a> 
  <a href='user.php?id=gurunilai&hal=$back&pel=$pel&sem=$sem' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurunilai&hal=$i&pel=$pel&sem=$sem' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurunilai&hal=$i&pel=$pel&sem=$sem' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurunilai&hal=$next&pel=$pel&sem=$sem' title='$next'> Next</a> 
  <a href='user.php?id=gurunilai&hal=$jml&pel=$pel&sem=$sem' title='Page $jml'> Last</a></center></td></tr>";
  }
  $cetak .="<tr class='td0'><td><b>Kode</td><td><b>Tanggal</td><td><b>Kelas</td>
  <td><b>U Ke</td><td><b>Jenis</td><td><b>KKM</td><td><b>Ket</td><td><b>Kontrol</b></td></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	if ($row[status]=='0') $jenis='U.Harian'; 
	elseif ($row[status]=='1') $jenis='Tgs.Kognitif'; 
	elseif ($row[status]=='2') $jenis='Remedial'; 
	elseif ($row[status]=='3') $jenis='Tugas'; 
	elseif ($row[status]=='4') $jenis='Praktikum'; 
	elseif ($row[status]=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 
	
   $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>$row[kd_nilai]</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_ujian]))."</td>
   <td width='10%'>$row[kelas]</td>
   <td width='5%' align='center'>$row[ujian_ke]</td>
   <td width='15%' >$jenis</td><td width='8%' align='center'>$row[skbm]</td><td width='25%'>$row[ket]</td>
   <td width='10%' align='center'><a href='user.php?id=gurunilhapus&kd=$row[kd_nilai]' title='klik untuk hapus data'><img src='../images/hapus.gif'></a> &nbsp;
   <a href='user.php?id=guruniledit&kd=$row[kd_nilai]' title='klik untuk edit data'><img src='../images/edit.gif'></a> &nbsp;
   <a href='../functions/fungsi_excelnilai.php?format=nilai&kd=$row[kd_nilai]' target='_blank' title='klik untuk download data'><img src='../images/xls.gif'></a></td></tr>";
 }        
  $cetak .= "</table>";
  }
  else $cetak .="Mohon maaf anda tidak diperkenankan mengakses fasilitas ini";
 $cetak .="</div>";
return $cetak;
}


function guruniltambah() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Tambah Nilai - Guru");

$nip = konversi_id($userid);

if ($kategori=='Guru') {
$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
  $m=date("m");
  $d=date("d");
  $y=date("Y");

 $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	$tahun = substr($al[thajar],2,2)."".substr($al[thajar],7,2);
	$data4 .=  "<option value='$tahun' >$al[thajar]</option>";
  }

$cetak .="<table width=100% id='tablebaru' cellspacing='1' cellpadding='3' ><form action='user.php' method='post' id='form1'>
<input type=hidden value='guruniltam2' name='id'>
<tr class=td0 ><td colspan=2>Input Data Nilai Manual</td></tr>
<tr><td>Tahun Pelajaran</td><td>$data4</td></tr>
<tr><td>Semester</td><td>";
  $cetak .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $cetak.=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $cetak .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $cetak .= "</select> &nbsp;";	
$cetak .="</td></tr>
  <tr><td>Pelajaran</td><td><select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='$nip' ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td></tr>
  <tr><td>Jenis</td><td><select name='status'>
  <option value='0'>U.Harian</option><option value='1'>Tgs.Kognitif</option>
  <option value='2'>Remedial</option><option value='3'>Tugas</option>
  <option value='4'>Praktikum</option><option value='5'>U.Umum</option>
  <option value='5'>Lain-lain</option></select></td></tr>
  <tr><td>Tgl Ujian</td><td>";
 $cetak .='<input name="tgl1" type="text" id="tgl1" readonly />
                <a href="#" id="anctgl1"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl1" border="0" class="calCanvas"></div>';
  $cetak .="</td></tr>
  <tr><td>Ujian ke</td><td><input type='text' name='ujianke' size=5 maxlength=3 ></td></tr>
  <tr><td>KKM</td><td><input type='text' name='skbm' size=5 maxlength=3 ></td></tr>
  <tr><td>Kompetensi Dasar</td><td><input type='text' name='kd' size=30 maxlength=250 ></td></tr>
  <tr><td>Kelas</td><td><select name='kelas'>";
    $q=mysql_query("select * from t_mengajar where nip='$nip' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[kelas]'>$r[kelas]</option>";
  }
  $cetak .="</select></td></tr>
    <tr><td><b></td><td><input type='submit' value=' Lanjut ' id=button2 >
  <input type=hidden name='st' value='tambah'>
  <input type=hidden name='nip' value='$nip'></td></tr>
  </form></table><br>
  
  <table width=100% id='tablebaru' cellspacing='1' cellpadding='3' >
  <form action='../functions/fungsi_excelnilai.php' method='post'>
  <tr class=td0 ><td colspan=2>Input Data Nilai menggunakan import Excel </td></tr>
  <tr><td colspan=2 >Membuat Format Nilai ke Excel  : <select name='kelas'>";
    $q=mysql_query("select * from t_mengajar where nip='$nip' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[kelas]'>$r[kelas]</option>";
  }
  $cetak .="</select>&nbsp;&nbsp;<input type=submit value=' Format ' id=button2 ></td></tr>
  <tr><td colspan=2 ><b>Langkah-langkah :</b><br>
  1. Buatlah Format Nilai yang telah ditentukan pada fasilitas ini sesuai kelasnya.<br>
  2. Jangan merubah susunan kolom pada baris pertama pada format tersebut.<br>
  3. Isi lah data nilai pada format excel tersebut.<br>
  4. Ganti nama sheet <b>FormatNilai</b> menjadi sheet <b>Nilai</b> saja, kemudian SIMPAN.<br>
  5. Import kan file Excel tersebut pada fasilitas di bawah ini.<br>
  6. Isi inputan dibawah ini.<br>
  7. Klik tombol <b><i>browse</i></b>, pilih file Excelnya.Kemudian Klik tombol <b><i>Proses</i></b><br>
  </td></tr>
  </form>
  <form action='../functions/impornilai.php' method='post' name='exc_upload' enctype=\"multipart/form-data\">
  <tr><td>Tahun Pelajaran</td><td>$data4</td></tr>
  <tr><td>Semester</td><td>";
    $cetak .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['semester']==$sem) $cetak.=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $cetak .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $cetak .= "</select> &nbsp;";	
  $cetak .="</td></tr>
  <tr><td>Pelajaran</td><td><select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td></tr>
  <tr><td>Jenis</td><td><select name='status'>
  <option value='0'>U.Harian</option><option value='1'>Tgs.Kognitif</option>
  <option value='2'>Remedial</option><option value='3'>Tugas</option>
  <option value='4'>Praktikum</option><option value='5'>U.Umum</option>
  <option value='5'>Lain-lain</option></select></td></tr>
  <tr><td>Tgl Ujian</td><td>";
 $cetak .='<input name="tgl2" type="text" id="tgl2" readonly />
                <a href="#" id="anctgl2"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl2" border="0" class="calCanvas"></div>';
  $cetak .="</td></tr>
  <tr><td>Ujian ke</td><td><input type='text' name='ujianke' size=5 maxlength=3 ></td></tr>
  <tr><td>KKM</td><td><input type='text' name='skbm' size=5 maxlength=3 ></td></tr>
  <tr><td>Kompetensi Dasar</td><td><input type='text' name='kd' size=30 maxlength=250 ></td></tr>
  <tr><td colspan=2 >Import dari Excel : <input type=file name='excel_file'> &nbsp;&nbsp;<input type=submit value=' Proses ' id=button2 ></td></tr><input type=hidden name=nip value=$nip ></form>
  </table><br><br>";
  $cetak .='<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl1 = new DatePicker();
dptgl1.id = "tgl1";
dptgl1.month = '.$m.';
dptgl1.year = '.$y.';
dptgl1.canvas = "dtDivtgl1";
dptgl1.format = "yyyy-mm-dd";
dptgl1.anchor = "anctgl1";
dptgl1.initialize();

var dptgl2 = new DatePicker();
dptgl2.id = "tgl2";
dptgl2.month = '.$m.';
dptgl2.year = '.$y.';
dptgl2.canvas = "dtDivtgl2";
dptgl2.format = "yyyy-mm-dd";
dptgl2.anchor = "anctgl2";
dptgl2.initialize();
-->

</script>';

}

else $cetak .="Mohon maaf anda tidak diperkenankan mengakses fasilitas ini";

$cetak .="</div>";
return $cetak;
}

function guruniltam2() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Tambah Nilai - Guru");
$sem=$_POST['sem'];
$pel=$_POST['pel'];
$status=$_POST['status'];
$tgl1=$_POST['tgl1'];
$kelas=$_POST['kelas'];
$ujianke=$_POST['ujianke'];
$skbm=$_POST['skbm'];
$kd=$_POST['kd'];
$thajar=$_POST['thajar'];

$nip = konversi_id($userid);

	if ($status=='0') $jenis='U.Harian'; 
	elseif ($status=='1') $jenis='Tgs.Kognitif'; 
	elseif ($status=='2') $jenis='Remedial'; 
	elseif ($status=='3') $jenis='Tugas'; 
	elseif ($status=='4') $jenis='Praktikum'; 
	elseif ($status=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 

$cetak .="<form action='../functions/simnilai.php' method='post' id='form1'>
<table width=100% id='tablebaru' cellspacing='1' cellpadding='3'>
<tr><td colspan=2>Semester</td><td colspan=2>$sem</td><tr>
<tr><td colspan=2>Pelajaran</td><td colspan=2>$pel</td><tr>
<tr><td colspan=2>Jenis</td><td colspan=2>$jenis</td><tr>
<tr><td colspan=2>Tgl Ujian</td><td colspan=2>$tgl1</td><tr>
<tr><td colspan=2>Kelas</td><td colspan=2>$kelas</td><tr>
<tr><td colspan=2>Ujian ke</td><td colspan=2>$ujianke</td><tr>
<tr><td colspan=2>KKM</td><td colspan=2>$skbm</td><tr>
<tr><td colspan=2>Kompetensi Dasar</td><td colspan=2>$kd</td><tr>

<tr class='td0'><td>No</td><td>NIS</td><td>Nama</td><td>Nilai</td><tr>";
$i=1;
  $q=mysql_query("select * from t_siswa where kelas='".mysql_real_escape_string($kelas)."' order by nama ");
  while($row=mysql_fetch_array($q)) {
    $warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;

	$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td>$i</td><td>$row[user_id]</td><td>$row[nama]</td>
	<td><input type='text' name='nil[$row[user_id]]' size=5></td></tr>";
	$i++;
  }
  $cetak .="</table><br><input type=hidden name='pel' value='$pel'>
  <input type=hidden name='sem' value='$sem'><input type=hidden name='nip' value='$nip'>
  <input type=hidden name='kelas' value='$kelas'><input type=hidden name='tgl1' value='$tgl1'>
  <input type=hidden name='kd' value='$kd'><input type=hidden name='status' value='$status'>
  <input type=hidden name='ujianke' value='$ujianke'><input type=hidden name='skbm' value='$skbm'>
  <input type=hidden name='kode' value='tambah'><input type=hidden name='thajar' value='$thajar'>
  <input type='submit' value=' Simpan ' id=button2 > &nbsp;<a href='user.php?id=gurunilai' id=button2 > Batal </a></form><br><br>";
  $cetak .="</div>";
return $cetak;
}

function simnilai() {
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Data Nilai - Guru");
$cetak .="<br><center>$kd";
$cetak .="</div>";
return $cetak;
}

function gurunilhapus() {
include "koneksi.php";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];
	$sql="delete from t_nilai_detail where kd_nilai='".mysql_real_escape_string($kd)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
		$sql="delete from t_nilai where kd_nilai='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
    //Header("Location: user.php?id=gurunilai");
    echo "<script>document.location.href = 'user.php?id=gurunilai';</script>";
	return 0;
} 

function guruniledit() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];

$cetak .= ataslogin("Edit Nilai - Guru");
$nip = konversi_id($userid);

if ($kategori=='Guru') {
$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
  $m=date("m");
  $d=date("d");
  $y=date("Y");

$nip = konversi_id($userid);
$i=1;
  $q1=mysql_query("select * from t_nilai where kd_nilai='".mysql_real_escape_string($kd)."' ");
  $row=mysql_fetch_array($q1);
    
	if ($row[status]=='0') $j1='selected'; 
	elseif ($row[status]=='1') $j2='selected'; 
	elseif ($row[status]=='2') $j3='selected'; 
	elseif ($row[status]=='3') $j4='selected'; 
	elseif ($row[status]=='4') $j5='selected'; 
	elseif ($row[status]=='5') $j6='selected'; 
	else $j7='selected'; 
	
	if (substr($row[kd_nilai],1,4)=='0809') $th1='selected';
	elseif (substr($row[kd_nilai],1,4)=='0910') $th2='selected';
	elseif (substr($row[kd_nilai],1,4)=='1011') $th3='selected';
	elseif (substr($row[kd_nilai],1,4)=='1112') $th4='selected';
	elseif (substr($row[kd_nilai],1,4)=='1213') $th5='selected';
	
	$thajar =substr($row[kd_nilai],0,4);
    
    if ($row['semester']=='1') $sem1 = 'selected';
    else $sem2 = 'selected';
$cetak .="<form action='../functions/simnilai.php' method='post' id='form1'>
<table width=100% id='tablebaru' cellspacing='1' cellpadding='3'>
<tr><td colspan=2>Tahun Pelajaran</td><td colspan=2>$thajar<input type=hidden name=thajar value='' ></td></tr>
<tr><td colspan=2>Semester</td><td colspan=2><select name='sem'><option value='1' $sem1 >1</option><option value='2' $sem2 >2</option></td></tr>
<tr><td colspan=2>Pelajaran</td><td colspan=2><select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td><tr>
<tr><td colspan=2>Jenis</td><td colspan=2><select name='status'>
  <option value='0' $j1>U.Harian</option><option value='1' $j2>Tgs.Kognitif</option>
  <option value='2' $j3>Remedial</option><option value='3' $j4>Tugas</option>
  <option value='4' $j5>Praktikum</option><option value='5' $j6>U.Umum</option>
  <option value='5' $j7>Lain-lain</option></select></td><tr>
<tr><td colspan=2>Tgl Ujian</td><td colspan=2>";
 $cetak .='<input name="tgl1" type="text" id="tgl1" value="'.$row['tgl_ujian'].'" readonly /><a href="#" id="anctgl1"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl1" border="0" class="calCanvas"></div>';
 $cetak .="</td><tr>
  <tr><td colspan=2>Ujian ke</td><td colspan=2><input type='text' name='ujianke' value='".$row['ujian_ke']."' size=5 maxlength=3 ></td></tr>
  <tr><td colspan=2>KKM</td><td colspan=2><input type='text' name='skbm' value='".$row['skbm']."' size=5 maxlength=3 ></td></tr>
  <tr><td colspan=2>Kompetensi Dasar</td><td colspan=2><input type='text' name='ket' value='".$row['ket']."' size=30 maxlength=250 ></td></tr>
  <tr><td colspan=2>Kelas</td><td colspan=2>".$row['kelas']."</td></tr>
<tr class='td0'><td>No</td><td>NIS</td><td>Nama</td><td>Nilai</td><tr>";
  $q=mysql_query("select * from t_nilai_detail,t_siswa where t_nilai_detail.nis=t_siswa.user_id and t_nilai_detail.kd_nilai='".mysql_real_escape_string($kd)."' order by t_siswa.nama");
  while($rows=mysql_fetch_array($q)) {
    $warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;

	$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td>$i</td><td>$rows[user_id]</td><td>$rows[nama]</td>
	<td><input type='text' name='nil[$rows[user_id]]' value='$rows[nilai]' size=5></td></tr>";
	$i++;
  }
  $cetak .="</table><br><input type=hidden name='pel' value='$pel'>
  <input type=hidden name='nip' value='$nip'><input type=hidden name='kdnilai' value='$kd'>
  <input type=hidden name='kelas' value='$kelas'>
  <input type=hidden name='kode' value='edit'>
  <input type='submit' value=' Simpan ' id=button2 > &nbsp;<a href='user.php?id=gurunilai' id=button2 > Batal </a></form><br><br>";
 
  $cetak .='<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl1 = new DatePicker();
dptgl1.id = "tgl1";
dptgl1.month = '.$m.';
dptgl1.year = '.$y.';
dptgl1.canvas = "dtDivtgl1";
dptgl1.format = "yyyy-mm-dd";
dptgl1.anchor = "anctgl1";
dptgl1.initialize();

</script>';
}
else $cetak .="Mohon maaf anda tidak diperkenankan mengakses fasilitas ini";

$cetak .="</div>";
return $cetak;
}

function nilaiwali() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$thajar=$_GET['thajar'];
$pel=$_GET['pel'];
$nip = konversi_id($userid);
$kelas = konversi_wali($nip);

$cetak .= ataslogin("Data Nilai per Wali Kelas $kelas - Guru");
if ($sem=='') $sem='1';
if ($kelas<>'') {
  $brs=30;
  $kol=10;
  $byk_result=mysql_query("SELECT * FROM t_nilai where pelajaran='".mysql_real_escape_string($pel)."' and kelas='".mysql_real_escape_string($kelas)."' and semester='".mysql_real_escape_string($sem)."' and left(kd_nilai,4)='".$thajar."'");
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
  
  $query = "SELECT * FROM t_nilai where pelajaran='".mysql_real_escape_string($pel)."' and  kelas='".mysql_real_escape_string($kelas)."' and semester='".mysql_real_escape_string($sem)."' and left(kd_nilai,4)='".$thajar."' order by tgl_ujian DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	$tahun = substr($al[thajar],2,2)."".substr($al['thajar'],7,2);
	if ($tahun==$thajar) $data4 .=  "<option value='$tahun' selected >".$al['thajar']."</option>";
	else $data4 .=  "<option value='$tahun' >".$al['thajar']."</option>";
  }
  $data4 .="</select>";
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='nilaiwali'>
  Tahun Pelajaran : $data4 &nbsp;&nbsp; Semester :  ";
    $cetak .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['semester']==$sem) $cetak .=  "<option value='".$al['semester']."' selected >".$al['semester']."</option>";
  	else $cetak .=  "<option value='".$al['semester']."' >".$al['semester']."</option>";
  }
  $cetak .= "</select> </b>&nbsp;Pelajaran : <select name='pel'>";
  $q=mysql_query("select pel from t_pelajaran order by pel");
  while($r=mysql_fetch_array($q)) {
    if ($pel==$r['pel']) $cetak .= "<option value='$r[pel]' selected>$r[pel]</option>";
  	else $cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select> &nbsp;";
  $cetak .=" <input type=submit value=' Pilih ' id=button2  ></form><br>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=8  ><center><a href='user.php?id=nilaiwali&hal=1&sem=$sem' title='Hal 1'>First </a> 
  <a href='user.php?id=nilaiwali&hal=$back&sem=$sem' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=nilaiwali&hal=$i&sem=$sem' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=nilaiwali&hal=$i&sem=$sem' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=nilaiwali&hal=$next&sem=$sem' title='$next'> Next</a> 
  <a href='user.php?id=nilaiwali&hal=$jml&sem=$sem' title='Page $jml'> Last</a></font></center></td></tr>";
  }
  $cetak .="<tr class='td0'><td><b>Kode</td><td><b>Tanggal</td><td><b>Pelajaran</td><td><b>Guru</td>
  <td><b>Jenis</td><td><b>KKM</td><td><b>Ket</td><td><b>Lihat</td></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	if ($row[status]=='0') $jenis='U.Harian'; 
	elseif ($row[status]=='1') $jenis='Tgs.Kognitif'; 
	elseif ($row[status]=='2') $jenis='Remedial'; 
	elseif ($row[status]=='3') $jenis='Tugas'; 
	elseif ($row[status]=='4') $jenis='Praktikum'; 
	elseif ($row[status]=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 
	$guru = konversi_guru($row['guru']);
   $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>".$row['kd_nilai']."</td>
   <td width='8%'>".date("d-m-Y",strtotime($row['tgl_ujian']))."</td>
   <td width='10%'>".$row['pelajaran']."</td>
   <td width='20%'>".$guru."</td>
   <td width='10%'>$jenis</td><td width='5%'>".$row['skbm']."</td><td width='25%'>".$row['ket']."</td>
   <td width='5%'><a href='user.php?id=nilaiwalilihat&kd=".$row['kd_nilai']."' title='klik untuk lihat data'><img src='../images/edit.gif'></a></td></tr>";
 }        
  $cetak .= "</table>";
  
  }
 else { $cetak .="Mohon Maaf, Anda Bukan wali kelas"; }
 $cetak .="</div>";
return $cetak;
}

function nilaiwalilihat() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];

$nip = konversi_id($userid);
$kelas = konversi_wali($nip);

$cetak .= ataslogin("Data Nilai per Wali Kelas $kelas - Guru");
$i=1;
  $q1=mysql_query("select * from t_nilai where kd_nilai='".mysql_real_escape_string($kd)."' ");
  $row=mysql_fetch_array($q1);
  $skbm =$row['skbm'];
  $pel=$row['pelajaran'];
  $guru = konversi_guru($row['guru']);
  $status = $row['status'];
	if ($status=='0') $jenis='U.Harian'; 
	elseif ($status=='1') $jenis='Tgs.Kognitif'; 
	elseif ($status=='2') $jenis='Remedial'; 
	elseif ($status=='3') $jenis='Tugas'; 
	elseif ($status=='4') $jenis='Praktikum'; 
	elseif ($status=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 
      
  $thpel = "20".substr($row['kd_nilai'],0,2)."/20".substr($row['kd_nilai'],2,2);
 $cetak .="<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>
 <tr><td colspan=4>
    <table width=100% ><tr><td width='15%' >Pelajaran</td><td>: $pel</td><td width='20%'>Tahun Pelajaran</td><td>: $thpel</td></tr>
    <tr><td>Guru</td><td>: $guru</td><td>Semester</td><td>: ".$row['semester']."</td></tr>
    <tr><td>KKM</td><td>: $skbm</td><td>Jenis</td><td>: ".$jenis."</td></tr>
    <tr><td>Ujian ke</td><td>: ".$row['ujian_ke']."</td><td>Materi</td><td>: ".$row['ket']."</td></tr>
    </table>
 </td>
 </tr>
<tr class='td0'><td>No</td><td>NIS</td><td>Nama</td><td>Nilai</td><td>Ket</td><tr>";
  $q=mysql_query("select * from t_nilai_detail,t_siswa where t_nilai_detail.nis=t_siswa.user_id and t_nilai_detail.kd_nilai='$kd' order by t_siswa.nama");
  while($rows=mysql_fetch_array($q)) {
    $warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	
	$w='';
	if($skbm <$rows['nilai']) $st="Tuntas";
	else {$st="<font style='color:red'>Tdk Tuntas";$w="<font style='color:red'>";}
	$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td>$w $i</td><td>$w $rows[user_id]</td><td>$w $rows[nama]</td>
	<td>$w ".$rows['nilai']."</td><td><b>$st</td></tr>";
	$i++;
  }
  $cetak .="</table><br>";
 $cetak .="</div>";
return $cetak;
}

function nilaiadmin() {
include "koneksi.php";

$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$kls=$_GET['kls'];
$nis = konversi_id($userid);
$kelas=konversi_kls($nis);
$nmsiswa = konversi_nama($nis);
$program=konversi_program($kelas);
$cetak .= ataslogin("Data Nilai Admin - Admin");

if($kategori=='Admin')  {
if($program=='') $program='-';
  $brs=30;
  $kol=10;
  $byk_result=mysql_query("SELECT * FROM t_nilai where kelas='".mysql_real_escape_string($kls)."' and semester='".mysql_real_escape_string($sem)."' ");
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
  
  $query = "SELECT * FROM t_nilai where kelas='".mysql_real_escape_string($kls)."' and semester='".mysql_real_escape_string($sem)."' order by tgl_ujian DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
      $data2.= 'Jurusan/Program Keahlian <select name=program onchange="document.location.href=\'user.php?id=nilaiadmin&program=\'+document.nilai.program.value">';
  $sql2="select * from t_programahli order by idprog";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[program]==$program) $data2.= "<option value='$al[program]' selected>$al[program]</option>";
  	else $data2.= "<option value='$al[program]' >$al[program]</option>";
  }
  $data2.= "</select> &nbsp;&nbsp;";
 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  $cetak .= "<form action='user.php?' method='get' name='nilai'>
  <input type=hidden name=id value='nilaiadmin'>
  $data2 Kelas : <select name='kls'>";
  $q=mysql_query("select * from t_kelas where program='".mysql_real_escape_string($program)."' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	if ($r[kelas]==$kls) $cetak .= "<option value='$r[kelas]' selected>$r[kelas]</option>";
  	else $cetak .= "<option value='$r[kelas]'>$r[kelas]</option>";
  }
  $cetak .= "</select>&nbsp;&nbsp;Semester :  $data3 <input type=submit value=' Pilih ' id=button2  ></form><br>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=8  ><center><a href='user.php?id=nilaiadmin&hal=1&sem=$sem&kls=$kls' title='Hal 1'>First </a> 
  <a href='user.php?id=nilaiadmin&hal=$back&sem=$sem&kls=$kls' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=nilaiadmin&hal=$i&sem=$sem&kls=$kls' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=nilaiadmin&hal=$i&sem=$sem&kls=$kls' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=nilaiadmin&hal=$next&sem=$sem&kls=$kls' title='$next'> Next</a> 
  <a href='user.php?id=nilaiadmin&hal=$jml&sem=$sem&kls=$kls' title='Page $jml'> Last</a></font></center></td></tr>";
  }
  $cetak .="<tr class='td0'><td><b>Kode</td><td><b>Tanggal</td><td><b>Pelajaran</td><td><b>Guru</td>
  <td><b>Jenis</td><td><b>KKM</td><td><b>Ket</td><td><b>Lihat</td></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	if ($row[status]=='0') $jenis='U.Harian'; 
	elseif ($row[status]=='1') $jenis='Tgs.Kognitif'; 
	elseif ($row[status]=='2') $jenis='Remedial'; 
	elseif ($row[status]=='3') $jenis='Tugas'; 
	elseif ($row[status]=='4') $jenis='Praktikum'; 
	elseif ($row[status]=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 
	$guru = konversi_guru($row['guru']);
    
   $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>".$row['kd_nilai']."</td>
   <td width='8%'>".date("d-m-Y",strtotime($row['tgl_ujian']))."</td>
   <td width='10%'>".$row['pelajaran']."</td>
   <td width='20%'>".$guru."</td>
   <td width='10%'>$jenis</td><td width='5%'>".$row['skbm']."</td><td width='25%'>".$row['ket']."</td>
   <td width='5%'><a href='user.php?id=nilaiadminlihat&kd=".$row['kd_nilai']."' title='klik untuk lihat data'><img src='../images/edit.gif'></a></td></tr>";
 }        
  $cetak .= "</table>";
  }
  else $cetak.='Mohon maaf, anda tidak diperkenankan mengakses fasilitas ini.';
  $cetak .="</div>";
return $cetak;
}

function nilaiadminlihat() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];

$nip = konversi_id($userid);
$cetak .= ataslogin("Data Nilai - Admin");
if($kategori=='Admin')  {

$i=1;
  $q1=mysql_query("select * from t_nilai where kd_nilai='".mysql_real_escape_string($kd)."' ");
  $row=mysql_fetch_array($q1);
  $skbm =$row[skbm];
  $pel=$row[pelajaran];
  $kls=$row[kelas];$guru=$row[guru];
 $cetak .="<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>
 <tr><td colspan=2>Pelajaran</td><td colspan=2>$pel</td></tr>
 <tr><td colspan=2>Guru</td><td colspan=2>$guru</td></tr>
 <tr><td colspan=2>Kelas</td><td colspan=2>$kls</td></tr>
<tr class='td0'><td>No</td><td>NIS</td><td>Nama</td><td>Nilai</td><td>Ket</td><tr>";
  $q=mysql_query("select * from t_nilai_detail,t_siswa where t_nilai_detail.nis=t_siswa.user_id and t_nilai_detail.kd_nilai='".mysql_real_escape_string($kd)."' order by t_siswa.nama");
  while($rows=mysql_fetch_array($q)) {
    $warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	
	$w='';
	if($skbm < $rows['nilai']) $st="Tuntas";
	else {$st="<font style='color:red'>Tdk Tuntas";$w="<font style='color:red'>";}
	$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td>$w $i</td><td>$w $rows[user_id]</td><td>$w $rows[nama]</td>
	<td>$w ".$rows['nilai']."</td><td><b>$st</td></tr>";
	$i++;
  }
  $cetak .="</table><br>";
 }
 else $cetak .="Mohon maaf, Anda tidak diperkenankan mengakses fasilitas ini.";
 $cetak .="</div>";
return $cetak;
}


?>