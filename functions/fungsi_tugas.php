<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function datatugas() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$datatugas .="<div id='depan-tengahkanan'>";
$datatugas .= statusanda($userid);
$datatugas .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$pel=$_GET['pel'];
$thajar=$_GET['thajar'];

$nis = konversi_id($userid);
$kelas=konversi_kls($nis);
$nmsiswa = konversi_nama($nis);
$program=konversi_program($kelas);
$datatugas .= ataslogin("Data Tugas - Siswa - $nmsiswa");
  $brs=20;
  $kol=10;

  $byk_result=mysql_query("SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas  
  and t_tugas_kelas.kelas='".mysql_real_escape_string($kelas)."' and t_tugas.sem='".mysql_real_escape_string($sem)."' 
  and t_tugas.pelajaran='".mysql_real_escape_string($pel)."' and thajar='".mysql_real_escape_string($thajar)."' ");
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
  
  $query = "SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas 
  and thajar='".mysql_real_escape_string($thajar)."' and t_tugas_kelas.kelas='".mysql_real_escape_string($kelas)."' 
  and t_tugas.sem='".mysql_real_escape_string($sem)."' and t_tugas.pelajaran='".mysql_real_escape_string($pel)."' order by t_tugas.tgl_kirim DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
 
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['thajar']==$thajar) $data4 .=  "<option value='$al[thajar]' selected>$al[thajar]</option>";
  	else $data4 .=  "<option value='$al[thajar]' >$al[thajar]</option>";
  }
  $data4 .= "</select> &nbsp;";	
   
  $datatugas .= "<form action='user.php?' method='get' name='siswa'>
  <input type=hidden name=id value='datatugas'>
  Pelajaran : <select name='pel'>";
  $q2 = mysql_query ("select * from t_pelajaran where program='-' or program='".mysql_real_escape_string($program)."' order by pel");
  while($r = mysql_fetch_array($q2)) {
	if ($r[pel]==$pel) $datatugas .= "<option value='$r[pel]' selected>$r[pel]</option>";
	else $datatugas .= "<option value='$r[pel]' >$r[pel]</option>";
  }

  $datatugas .= "</select>&nbsp;&nbsp;Thn Pelajaran : $data4 &nbsp; Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $datatugas .= "<tr><td colspan=6 ><center><a href='user.php?id=datatugas&hal=1&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal 1'>First </a> 
  <a href='user.php?id=datatugas&hal=$back&sem=$sem&pel=$pel&thajar=$thajar'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$datatugas .= "<b><a href='user.php?id=datatugas&hal=$i&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$datatugas .= "<a href='user.php?id=datatugas&hal=$i&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $datatugas .= "<a href='user.php?id=datatugas&hal=$next&sem=$sem&pel=$pel&thajar=$thajar'  title='$next'> Next</a> 
  <a href='user.php?id=datatugas&hal=$jml&sem=$sem&pel=$pel&thajar=$thajar'  title='Page $jml'> Last</a></font></center></td></tr>";
  }
    $datatugas .="<tr class='td0'><td><b>No</td><td><b>Tgl Kirim</td><td><b>Tgl Kumpul</td>
  <td><b>Status</td><td><b>Keterangan</td></tr>";
 
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
	
	$isi =strip_tags($row[isi]);
	$isi  =  substr($isi, 0, 200)."...";
	//if($row[jenis]=='0') { $jenis = "Materi"; $st='-'; }
//	else {
		$q=mysql_query("SELECT * from t_tugas_siswa where nis='$nis' and idtugas='$row[idtugas]' ");
		$r = mysql_fetch_array($q);
		if($r[status]=='0') $st = "Sudah";
		elseif($r[status]=='1') $st = "Proses";
		else $st ='Belum';
	//	$jenis = "Tugas";
//	}
    $datatugas .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$j</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_kirim]))."</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_kumpul]))."</td>
   <td width='5%'>$st</td><td width='50%'><a href='user.php?id=tugasdetail&kd=$row[idtugas]' title='Lihat $jenis'>$isi</a></td></tr>";
	$j++;
 }        
  $datatugas .= "</table>";

 $datatugas .="</div>";
return $datatugas;
}

function tugasdetail() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .= "<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .= "<hr style='border: thin solid #6A849D;'>";
$kd = $_GET['kd'];
if($kd=='') $kd = $_POST['kd'];

$nis = konversi_id($userid);
$nmsiswa = konversi_nama($nis);
$cetak .= ataslogin("Data Tugas - Siswa - $nmsiswa");

$q=mysql_query("SELECT * from t_tugas where idtugas='".mysql_real_escape_string($kd)."' ");
$row = mysql_fetch_array($q);
//if ($row[jenis]=='0') $jenis='Materi';
//else {
	$jenis='Tugas';
	$q=mysql_query("SELECT * from t_tugas_siswa where idtugas='".mysql_real_escape_string($kd)."' and nis='".mysql_real_escape_string($nis)."'");
	$r = mysql_fetch_array($q);
	$filedulu = $r[file];
	if ($r[status]=='1' ) $st = "Proses";
	else $st="$r[ids]";
	
	if ($st<>"Proses") {
		if ($kategori=='Siswa') {
		$tug ="<b>Pengiriman Tugas</b><form action='../functions/simmateri.php' method='post' enctype=\"multipart/form-data\">File Tugas : <input type='file' name='file' >
        <br>Pesan  Ket : <input type=text name=pesan size='40' maxlength='250'> <input type=submit value='Kirim' id='button2' ><br>File yang dikirim berbentuk Format bebas,maksimal 5 Mbyte <input type=hidden name='idtugas' value='$kd' ><input type=hidden name='st' value='$st' ><br> File tugas yg sudah diupload klik <a href='../tugas/$filedulu' title='Donwload file'>disini</a><input type=hidden name='nis' value='$nis' ></form>";
		}
	}
//}
$file = "../materi/file$row[idtugas].$row[file]";
if (file_exists($file)) {
	$filedown = "<a href='$file' title='Klik disini untuk download' >Dokumen $jenis</a>";
}
else { $filedown ="-"; }
$cetak .=" <table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>
<tr><td width='60' class=td2 >Pelajaran</td><td width='150' >: <b>$row[pelajaran]</td><td rowspan=4  valign=top>$tug</td></tr>
<tr><td class=td2 >Tgl Kirim</td><td>: <b>".date("d-m-Y",strtotime($row[tgl_kirim]))."</td></tr>
<tr><td class=td2 >Tgl Kumpul</td><td >: <b>".date("d-m-Y",strtotime($row[tgl_kumpul]))."</td></tr>
<tr><td class=td2 >Download</td><td>: $filedown</td></tr>
<tr><td colspan=3 class=td1 ><b>$jenis :</b><br>$row[isi]</td></tr></table>";
$cetak .="</div>";
return $cetak;
}


function simmateri() {
    $kd=$_GET['kd'];
    if($kd=='') $kd=$_POST['kd'];
    $userid = $_SESSION['User']['userid'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Data Perubahan Tugas ");
    $cetak.="<br><center>$kd</center>";
    $cetak .="</div>";
    return $cetak;
}

function gurutugas() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$thajar=$_GET['thajar'];
$kelas=$_GET['kelas'];

if ($sem=='') $sem=1;
$nip = konversi_id($userid);
$cetak .= ataslogin("Data Tugas - Guru");
  $brs=20;
  $kol=10;
if ($kategori=='Guru') {
  $byk_result=mysql_query("SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas 
  and t_tugas.sem='".mysql_real_escape_string($sem)."' and t_tugas.nip='".mysql_real_escape_string($nip)."' 
  and thajar='".mysql_real_escape_string($thajar)."' and kelas='".mysql_real_escape_string($kelas)."' ");
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
  
  $query = "SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas 
  and thajar='".mysql_real_escape_string($thajar)."' and t_tugas.sem='".mysql_real_escape_string($sem)."' 
  and t_tugas.nip='".mysql_real_escape_string($nip)."' 
  and kelas='".mysql_real_escape_string($kelas)."' order by t_tugas.tgl_kirim DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $data2 .=  "<select name='kelas' >";
  $sql2="select distinct(kelas) from t_mengajar where nip='".mysql_real_escape_string($nip)."' ";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['kelas']==$kelas) $data2 .=  "<option value='$al[kelas]' selected>$al[kelas]</option>";
  	else $data2 .=  "<option value='$al[kelas]' >$al[kelas]</option>";
  }
  $data2 .= "</select> &nbsp;";	

  $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['thajar']==$thajar) $data4 .=  "<option value='$al[thajar]' selected>$al[thajar]</option>";
  	else $data4 .=  "<option value='$al[thajar]' >$al[thajar]</option>";
  }
  $data4 .= "</select> &nbsp;";	
    
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='gurutugas'>
  <a href='user.php?id=gurutugtambah' id=button2 >Tambah Tugas</a> ::&nbsp;&nbsp;Kelas : $data2 &nbsp;&nbsp;Th Pelajaran : $data4 &nbsp;&nbsp;Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form>
  <form action='user.php' method='post' name='tugas' >
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=9 ><center><a href='user.php?id=gurutugas&hal=1&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurutugas&hal=$back&sem=$sem&thajar=$thajar&kelas=$kelas'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurutugas&hal=$i&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurutugas&hal=$i&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurutugas&hal=$next&sem=$sem&thajar=$thajar&kelas=$kelas'  title='$next'> Next</a> 
  <a href='user.php?id=gurutugas&hal=$jml&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Page $jml'> Last</a></font></center></td></tr>";
  }

     $cetak .="<tr class='td0' ><td>No</td><td>Tgl Kirim/ Tgl Kumpul</td>
  <td>Pelajaran</td><td>Keterangan</td><td>Cek Tugas</td><td>Hap</td><td>Edit</td></tr>";
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  $cetak .="<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.tugas.elements.length;i++) {
     var e = document.tugas.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	$isi =strip_tags($row[isi]);
	$isi  =  substr($isi, 0, 200)."...";
//	if($row[jenis]=='0') { $jenis = "Materi";  }
//	else {
		$jenis = "Tugas";
//	}
    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>$j</td>
   <td width='10%'>".date("d-m-Y",strtotime($row[tgl_kirim]))."/ ".date("d-m-Y",strtotime($row[tgl_kumpul]))."</td>
   <td width='5%'>$row[pelajaran]</td>
   <td width='40%'>$isi</td>
   <td width='5%'><a href='user.php?id=gurutugmasuk&kode=$row[idtugas]&kls=$row[kelas]' title='klik untuk lihat tugas yang masuk'><img src='../images/inbox.gif'></a></td>
   <td width='5%'><input type='checkbox' name='kls[$row[idkls]]' value='on'></td>
   <td width='5%'><a href='user.php?id=gurutugedit&kd=$row[idtugas]&kls=$row[kelas]' title='klik untuk edit data'><img src='../images/edit.gif'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"id\" value=\"gurutughapus\"><input type=\"submit\" value=\"Hapus\" id=button2 ></form>";
}
else $cetak .="Mohon maaf anda tidak diperkenankan mengaksesn fasilitas ini";
 $cetak .="</div>";
return $cetak;
}

function gurutugtambah() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Tambah Tugas - Guru");

$nip = konversi_id($userid);

if ($kategori=='Guru') {
 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	

 include "functions_editor.php";
 $cetak .= editor_standar();
 
$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
$cetak .="<table><form action='../functions/simmateriguru.php' method='post' enctype=\"multipart/form-data\">
  <tr><td><b>Th Pelajaran</td><td><select name='thajar'>";
  $q=mysql_query("select * from t_thajar ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[thajar]'>$r[thajar]</option>";
  }
  $cetak .= "</select></td></tr>
  <tr><td><b>Sem</td><td>$data3</td></tr>
  <tr><td><b>Pelajaran</td><td><select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='$nip' ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td></tr>

  <tr><td><b>Tgl Kumpul</td><td>";
    $m=date("m");
  $d=date("d");
  $y=date("Y");
 $cetak .='<input name="tgl" type="text" id="tgl"  readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl" border="0" class="calCanvas"></div>';
  $cetak.="</td></tr>
  <tr><td><b>Materi</td><td><textarea name='richEdit0' id='richEdit0' rows='15' cols='80' style='width: 80%' ></textarea>";

$cetak .="</td></tr>
  <tr><td><b>File</td><td><input type='file' name='file'> <br/>File maksimal 5 Mbyte, </td></tr>
  <tr><td><b>Kelas</td><td>";

    $q=mysql_query("select distinct kelas from t_mengajar where nip='$nip' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "$r[kelas] <input type='checkbox' name='id[$r[kelas]]' value='on'><br>";
  }
  $cetak .="</td></tr>
    <tr><td><b></td><td><input type='reset' value='Ulang' id=button2 >&nbsp;<input type='submit' id=button2 value=' Simpan ' > <input type=hidden name='st' value='tambah'>
  <input type=hidden name='nip' value='$nip'></td></tr>
  </form></table>";
 }
  $cetak.='<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "yyyy-mm-dd";
dptgl.anchor = "anctgl";
dptgl.initialize();
-->
</script>';
$cetak .="</div>";
return $cetak;
}


function gurutugedit() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];
$kls=$_GET['kls'];
if($kls=='') $kls=$_POST['kls'];

$nip = konversi_id($userid);

if ($kategori=='Guru') {
$cetak .= ataslogin("Edit Materi - Guru");
$q=mysql_query("select * from t_tugas where idtugas='".mysql_real_escape_string($kd)."' ");
$row=mysql_fetch_array($q);
$thajar = $row['thajar'];

 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$row['sem']) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  
 include "functions_editor.php";
 $cetak .= editor_standar();
 
$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
$cetak .="<table><form action='../functions/simmateriguru.php' method='post' enctype=\"multipart/form-data\">
  <tr><td><b>Th Pelajaran</td><td><select name='thajar'>";
  $q=mysql_query("select * from t_thajar ");
  while($r=mysql_fetch_array($q)) {
    if ($r['thajar']==$thajar ) $cetak .= "<option value='$r[thajar]' selected >$r[thajar]</option>";
  	else $cetak .= "<option value='$r[thajar]'>$r[thajar]</option>";
  }
  $cetak .= "</select></td></tr>
  
<tr><td><b>Sem</td><td>$data3</td></tr>
  <tr><td><b>Pelajaran</td><td><select name='pel'>";
  $q1=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q1)) {
  	if ($r[pel]==$row[pelajaran]) $cetak .= "<option value='$r[pel]' selected>$r[pel]</option>";
  	else $cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td></tr>
  <tr><td><b>Tgl Kumpul</td><td> ";

    $m=date("m");
  $d=date("d");
  $y=date("Y");
 $cetak .='<input name="tgl" type="text" id="tgl" value="'.$row[tgl_kumpul].'" readonly maxlenght=12/>
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl" border="0" class="calCanvas"></div>';
$cetak .="</td></tr>
  <tr><td><b>Materi</td><td><textarea name='richEdit0' id='richEdit0'  rows='15' cols='80' style='width: 80%' >".$row[isi]."</textarea>";

$cetak .="</td></tr>
  <tr><td><b>File</td><td><input type='file' name='file'> <br/>File maksimal 5 Mbyte, 
  <br/><a href='../materi/file$row[idtugas].$row[file]'>Download File</a></td></tr>
  <tr><td><b>Kelas</td><td>$kls</td></tr>
    <tr><td><b></td><td><input type='reset' value='Ulang' id=button2 >&nbsp;<input type='submit' value=' Simpan ' id=button2 >
  <input type=hidden name='st' value='edit'><input type=hidden name='kd' value='$kd'></td></tr>
  </form></table>";
}
 $cetak .='<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "yyyy-mm-dd";
dptgl.anchor = "anctgl";
dptgl.initialize();

-->

</script>';
$cetak .="</div>";
return $cetak;
}


function gurutugmasuk() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

$kode=$_GET['kode'];
if($kode=='') $kode=$_POST['kode'];
$kls=$_GET['kls'];
if($kls=='') $kls=$_POST['kls'];

$cetak .= ataslogin("Lihat Tugas Masuk - Guru ");
$nip = konversi_id($userid);

$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
  $m=date("m");
  $d=date("d");
  $y=date("Y");
  
$nip = konversi_id($userid);
$i=1;
  $q1=mysql_query("select * from t_nilai where kd_remedial='".mysql_real_escape_string($kode)."' ");
  $row=mysql_fetch_array($q1);
  $idtugas =$row[kd_nilai];
  $sem = $row['sem'];
  $tglujian = $row[tgl_ujian];
	if ($row[status]=='0') $j1='selected'; 
	elseif ($row[status]=='1') $j2='selected'; 
	elseif ($row[status]=='2') $j3='selected'; 
	elseif ($row[status]=='6') $j7='selected'; 
	elseif ($row[status]=='4') $j5='selected'; 
	elseif ($row[status]=='5') $j6='selected'; 
	else $j4='selected'; 

 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	$tahun = substr($al[thajar],2,2)."".substr($al[thajar],7,2);
    if(substr($row[id_tugas],1,4)==$tahun) $data4 .=  "<option value='$tahun' selected >$al[thajar]</option>";
	else $data4 .=  "<option value='$tahun' >$al[thajar]</option>";
  }
    
$cetak .="<form action='../functions/simnilai2.php' method='post' id='form1'>
<table id=tablebaru width=100% cellspacing='1' cellpadding='3'>
<tr><td colspan=2><b>Tahun Ajar</td><td colspan=2>$data4</td></tr>
<tr><td colspan=2><b>Semester</td><td colspan=2>$data3</td></tr>
<tr><td colspan=2><b>Pelajaran</td><td colspan=2><select name='pelajaran'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q)) {
        if ($row['pelajaran']==$r[pel]) $cetak .= "<option value='$r[pel]' selected >$r[pel]</option>";
  	     else $cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td><tr>
<tr><td colspan=2><b>Jenis</td><td colspan=2><select name='status'>
  <option value='0' $j1>U.Harian</option><option value='1' $j2>Tgs.Kognitif</option>
  <option value='2' $j3>Remedial</option><option value='3' $j4>Tugas</option>
  <option value='4' $j5>Praktikum</option><option value='5' $j6>U.Umum</option>
  <option value='5' $j7>Lain-lain</option></select></td><tr>
<tr><td colspan=2><b>Tgl Periksa</td><td colspan=2>";
 $cetak .='<input name="tgl1" type="text" id="tgl1" value="'.$tglujian.'" readonly /><a href="#" id="anctgl1"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl1" border="0" class="calCanvas"></div>';
 $cetak .="</td><tr>
  <tr><td colspan=2><b>Tugas ke</td><td colspan=2><input type='text' name='ujianke' value='$row[ujian_ke]' size=5 maxlength=3 ></td></tr>
  <tr><td colspan=2><b>KKM</td><td colspan=2><input type='text' name='skbm' value='$row[skbm]' size=5 maxlength=3 ></td></tr>
  <tr><td colspan=2><b>Kompetensi Dasar</td><td colspan=2><input type='text' name='ket' value='$row[ket]' size=30 maxlength=250 ></td></tr>
  <tr><td colspan=2><b>Kelas</td><td colspan=2>$kls</td></tr>
<tr class=td0 ><td><b>No</td><td><b>NIS</td><td><b>Nama</td><td><b>Pesan</td><td><b>Tgl Kirim</td><td><b>File</td><td><b>Nilai</td><tr>";
  $q=mysql_query("select * from t_siswa where kelas='".mysql_real_escape_string($kls)."' order by t_siswa.nama");
  while($rows=mysql_fetch_array($q)) {
    $warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
	
	$nilai=0;
	$q1=mysql_query("select * from t_nilai_detail where kd_nilai='$row[kd_nilai]' and nis='$rows[user_id]' ");
  	if($ralan=mysql_fetch_array($q1)) {
		 $nilai=$ralan[nilai];
	}
	$tgl='-';$filen='-';$pesan='-';
	$q2=mysql_query("select * from t_tugas_siswa where idtugas='".mysql_real_escape_string($kode)."' and nis='$rows[user_id]' ");
  	if($ralan2=mysql_fetch_array($q2)) {
		$tgl=date("d-m-Y",strtotime($ralan2[tgl]));
		$filen="<a href='../tugas/$ralan2[file]' target='_blank' title='klik untuk melihat hasil tugas' >$ralan2[file]</a>";
		$pesan=$ralan2[pesan];
	}
	
	$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td valign='top' width=5>$i</td><td valign='top' width=10>$rows[user_id]</td>
	<td valign='top' width=150 >$rows[nama]</td>
	<td valign='top' >$pesan</td><td valign='top' width=15>$tgl</td>
	<td valign='top' width=15 >$filen</td>
	<td width=5 ><input type='text' name='nil[$rows[user_id]]' value='$nilai' size=3 ></td></tr>";
	$i++;
  }
  if ($idtugas=='') $cetak .="<input type=hidden name='kode' value='tambah'>";
  else $cetak .="<input type=hidden name='kode' value='edit'>";
  $cetak .="</table><br><input type=hidden name='pel' value='$pel'>
  <input type=hidden name='nip' value='$nip'><input type=hidden name='kdnilai' value='".$idtugas."'>
  <input type=hidden name='kelas' value='$kls'>
  <input type=hidden name='idtugas' value='$kode'>
  <input type='submit' value=' Simpan ' id=button2 > &nbsp;</form><br>";
 
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
$cetak .="</div>";
return $cetak;
}

function gurutughapus() {
include "koneksi.php";
$kd=$_GET['kd'];
if($kd=='') $kd=$_POST['kd'];
$kls=$_GET['kls'];
if($kls=='') $kls=$_POST['kls'];
if (!empty($kls)) {
	while (list($key,$value)=each($kls)) {
	 $kd='';
	 $sql1="select idtugas from t_tugas_kelas where idkls='".mysql_real_escape_string($key)."'";
	 $result=mysql_query($sql1);
	 $row=mysql_fetch_array($result);
	 $kd=$row[idtugas];
	
	$sql="delete from t_tugas_kelas where idkls='".mysql_real_escape_string($key)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
	$sql="select * from t_tugas_kelas where idtugas='$kd'";
	$result=mysql_query($sql) or die ("Penghapusan gagal 2");
	$row =mysql_num_rows($result);
	if ($row==0) {
		$sql="delete from t_tugas where idtugas='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 3");
		$file = "../materi/file$kd.doc";
		if (file_exists($file)) {
			unlink($file);
		}
		$sql="select * from t_tugas_siswa where idtugas='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
		while($rows=mysql_fetch_array($mysql_result)) {
			$file = "../tugas/$rows[file]";
				if (file_exists($file)) {
				unlink($file);
			}
		}
		$sql="delete from t_tugas_siswa where idtugas='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");		
	}

   }
	
 }
    //Header("Location: user.php?id=gurutugas&kd=$row");
    echo "<script>document.location.href = 'user.php?id=gurutugas&kd=$row';</script>";
	return 0;
 } 
?>