<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function datatest() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$siswatest .="<div id='depan-tengahkanan'>";
$siswatest.= statusanda($userid);
$siswatest .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$pel=$_GET['pel'];
$thajar=$_GET['thajar'];

$nis = konversi_id($userid);
$kelas=konversi_kls($nis);
$nmsiswa = konversi_nama($nis);
$program=konversi_program($kelas);
$siswatest .= ataslogin("Tes Online - Siswa - $nmsiswa");
  $brs=20;
  $kol=10;
  $tgl = date("Y-m-d");  
  $byk_result=mysql_query("SELECT * FROM soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama
  and soal_kelas.kelas='".mysql_real_escape_string($kelas)."' and soal_utama.sem='".mysql_real_escape_string($sem)."' 
  and soal_utama.pel='".mysql_real_escape_string($pel)."' and soal_utama.thajar='".mysql_real_escape_string($thajar)."' and tgl_mulai <='".$tgl."' and tgl_akhir>='".$tgl."'");
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
  
  $query = "SELECT * FROM soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama
  and soal_kelas.kelas='".mysql_real_escape_string($kelas)."' and soal_utama.sem='".mysql_real_escape_string($sem)."' 
  and soal_utama.pel='".mysql_real_escape_string($pel)."' and soal_utama.thajar='".mysql_real_escape_string($thajar)."'and tgl_mulai <='".$tgl."' and tgl_akhir>='".$tgl."' LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

 $data3 .=  "<select name='sem'>";
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
   
  $siswatest .= "<form action='user.php?' method='get' name='siswa'>
  <a href='user.php?id=v_nilai' title='Daftar nilai tes online' id=button2 >Daftar Nilai Tes Online</a><br/><br/>
  <input type=hidden name=id value='siswatest'>
  Pelajaran : <select name='pel'>";
  $q2 = mysql_query ("select * from t_pelajaran where program='-' or program='".mysql_real_escape_string($program)."' order by pel");
  while($r = mysql_fetch_array($q2)) {
	if ($r[pel]==$pel) $siswatest .= "<option value='$r[pel]' selected>$r[pel]</option>";
	else $siswatest .= "<option value='$r[pel]' >$r[pel]</option>";
  }

  $siswatest .= "</select>&nbsp;&nbsp;Thn Pelajaran : $data4 &nbsp; Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $siswatest .= "<tr><td colspan=3 ><center><a href='user.php?id=siswatest&hal=1&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal 1'>First </a> 
  <a href='user.php?id=siswatest&hal=$back&sem=$sem&pel=$pel&thajar=$thajar'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$siswatest .= "<b><a href='user.php?id=siswatest&hal=$i&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$siswatest .= "<a href='user.php?id=siswatest&hal=$i&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $siswatest .= "<a href='user.php?id=siswatest&hal=$next&sem=$sem&pel=$pel&thajar=$thajar'  title='$next'> Next</a> 
  <a href='user.php?id=siswatest&hal=$jml&sem=$sem&pel=$pel&thajar=$thajar'  title='Page $jml'> Last</a></font></center></td></tr>";
  }
    $siswatest .="<tr class='td0'><td><b>No</td><td><b>Tanggal</td><td><b>Materi</td><td><b>Jenis</td></tr>";
 
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
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
    $tgl = date("d-m-Y",strtotime($row['tgl_mulai']))." s.d ".date("d-m-Y",strtotime($row['tgl_akhir']));
    $siswatest .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\">
    <td width='3%' valign=top >$j</td><td width='10%' valign=top >$tgl</td>
    <td width='50%'><a href='user.php?id=masuktest&idsoal=$row[idsoalutama]' title='Lihat $jenis'>$row[materi]</a></td>
    <td width='10%' valign=top >$jenis</td></tr>";
	$j++;
 }        
  $siswatest .= "</table>";

 $siswatest .="</div>";
return $siswatest;
}

function masuk() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level  = $_SESSION['User']['ket'];
$nis    = konversi_id($userid);
$kelas  = konversi_kls($nis);
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
//$userid	= $_SESSION['UserName'];
$idsoalutama = $_GET['idsoal'];
$query=mysql_query("select * from soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama and kelas='".mysql_real_escape_string($kelas)."' and soal_utama.idsoalutama='".mysql_real_escape_string($idsoalutama)."'");
$result=mysql_num_rows($query);

$no=1;
if($row=mysql_fetch_array($query)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
    $cetak .= '<table width="100%"  border="0" cellspacing="2" cellpadding="2">';
    $cetak .= "<tr><td width=\"20%\"><b>Pelajaran</b></td><td width=\"2%\">:</td>";
    $cetak .= "<td width=\"78%\">".$row["pel"]."</td></tr>";
    $cetak .= "<tr><td><b>Materi</b></td><td>:</td>";
    $cetak .= "<td>".$row["materi"]."</td></tr>";
    $cetak .= "<tr><td><b>Jenis Tes</b></td><td>:</td>";
    $cetak .= "<td>".$jenis."</td></tr>";
    $cetak .= "<tr><td><b>Tanggal</b></td><td>:</td>";
    $cetak .= "<td>".date("d-m-Y",strtotime($row["tgl_mulai"]))." s.d ".date("d-m-Y",strtotime($row["tgl_akhir"]))."</td></tr>";
    $cetak .= "<tr><td><b>Kesempatan</b></td><td>:</td>";
    $q = mysql_query("select nis from soal_hasil where nis='".mysql_real_escape_string($nis)."' and idsoalutama='".mysql_real_escape_string($idsoalutama)."' ");
    $n = $row['kesempatan']- mysql_num_rows($q);
    $cetak .= "<td>".$n." kali lagi</td></tr>";
    $cetak .= "<form method=\"post\" action=\"user.php\">";
     $cetak .= "<tr><td><b>Password Soal</b></td>";
	 $cetak .= "<td>:</td>";
     $cetak .= "<td><input type=\"password\" name=\"pass\" size=\"20\">";
     $cetak .= "<input type=\"hidden\" name=\"idsoalutama\" value=\"$idsoalutama\">";
     $cetak .= "<input type=hidden id='id' name='id' value='kerjakan' >";
     $cetak .= "</td></tr>";
    $cetak .= "</table>";
    $cetak .= "<br><input type=\"submit\" id='button2' name=\"submit\" value=\"Kerjakan soal >>\">";
    $cetak .= "</form>";
    $cetak .= "</table>";
}
else {
    $cetak .= "Mohon maaf, anda tidak dapat melakukan tes online.";
}

$cetak .="<br><div><strong>Catatan</strong>:";
$cetak .="<br>Apabila anda telah mengisi <b>password soal</b> dengan benar dan Menekan tombol <b>Kerjakan Soal</b> Jangan sekali-kali melakukan proses <b>Back, mengaktifkan menu yang lain atau menutup windows Browsernya</b> sebelum anda menyelesaikan menjawab soal-soal dan mengakhiri menjawab soal-soal dengan menekan tombol <b>selesai</b><br>Karena itu akan menyebabkan anda dianggap telah melakukan proses <b>Tes/Ujian</b> dan nilai anda menjadi <b>0</b>";

$cetak .="</div>";
return $cetak;
}


function kerjakan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
//$cetak .="<div id='depan-tengahkanan'>";
$cetak .="<div style ='padding:10px;'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$nis     = konversi_id($userid);
$kelas   = konversi_kls($nis);
$nmsiswa = konversi_nama($nis);
$cetak  .= "</td><td width=\"5px\"></td><td valign=top>";

session_start();
if (isset($_POST['submit'])) {
$idsoalutama = $_POST['idsoalutama'];
$pass   = $_POST['pass'];

$SQL=mysql_query("select * from soal_utama where idsoalutama='".mysql_real_escape_string($idsoalutama)."'");
if($row=mysql_fetch_array($SQL))
{
   $password=$row['psswd_soal'];
   $kesempatan = $row['kesempatan'];
   $waktu=$row['waktu'];
   $metode=$row['metode'];
   $disajikan=$row['jml_tampil'];
   $pel = $row['pel'];
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
   $q = mysql_query("select nis from soal_hasil where nis='".$nis."' and idsoalutama='".mysql_real_escape_string($idsoalutama)."'");
   $pernah = mysql_num_rows($q);
   $q2 = mysql_query("select kelas from soal_kelas where idsoalutama='".mysql_real_escape_string($idsoalutama)."' and kelas='".$kelas."'");
   $k  = mysql_num_rows($q2);   
}
if ($k==0 ) {
	die ("<body onload=\"alert('Anda tidak diperkenankan untuk mengerjakan tes online ini!');window.history.back()\">");
    exit;
}
if ($pass!=$password ) {
	die ("<body onload=\"alert('Password yang anda masukan salah!');window.history.back()\">");
    exit;
}
if ($pernah >= $kesempatan ) {
	die ("<body onload=\"alert('Kesempatan tes online hanya $kesempatan kali !');window.history.back()\">");
    exit;
}
?>
<noscript>
<meta http-equiv="refresh" content="0;URL=user.php?id=jsdisabled" />
</noscript> 
<script language="JavaScript" type="text/JavaScript">
var TimeOver = true

function getJam(Tanggal)
{
   Jam =  (Tanggal.getHours() < 10) ? "0"  + Tanggal.getHours()  + ":" : Tanggal.getHours() + ":"
   Jam += (Tanggal.getMinutes() < 10) ? "0" + Tanggal.getMinutes() + ":" : Tanggal.getMinutes() + ":"
   Jam += (Tanggal.getSeconds() < 10) ? "0" + Tanggal.getSeconds() : Tanggal.getSeconds()
   return Jam
}
function dispJam()
{
   TglCur = new Date()
   document.User.Watch.value = getJam(TglCur)
   document.User.TimeTaken.value = getWaktu(TglCur,TglStart)
   if ((Tgl.getTime() - TglCur.getTime()) <= 0)
   {
      if(TimeOver) TimeOverWarn()
      document.User.TimeLeft.value = "Habis"
   }
   else
   {
		document.User.TimeLeft.value = getWaktu(Tgl,TglCur)
    	setTimeout("dispJam()",1000)
    }
}
function getWaktu(Tgl,TglCur)
{
   TmLf = Tgl.getTime() - TglCur.getTime()
   TmLfHours = Math.floor(TmLf/3600000) 
   TmLfMinutes = Math.floor((TmLf%3600000)/60000)
   TmLfSeconds = Math.round((TmLf%60000)/1000)
   TmLfStr = (TmLfHours < 10) ? "0" + TmLfHours + ":" : TmLfHours + ":"
   TmLfStr += (TmLfMinutes < 10) ? "0" + TmLfMinutes + ":" : TmLfMinutes + ":"
   TmLfStr += (TmLfSeconds < 10) ? "0" + TmLfSeconds : TmLfSeconds
   return TmLfStr
}
function TimeOverWarn()
{
   alert("\n Maaf <?php echo $nmsiswa;?> ....\n Waktu Anda Habis")
   TimeOver = true
   return true
}
Tanggal =  new Date()
Tgl = new Date()
TglStart = new Date()
ArrayBulan = new Array("Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember")
Tahun =  Tanggal.getYear()
TglStr = Tanggal.getDate()  + " " + ArrayBulan[Tanggal.getMonth()] + " " + Tahun
Tgl.setTime(Tgl.getTime() + <?php echo $waktu;?> * 60 * 1000) 

function selesai() {
document.ljkform.submit.click();
clearTimeout(timeID);
} 
function timer() {
timeID=setTimeout("selesai()",60000*<?php echo $waktu;?>);
}
 
function init() {
  dispJam();
  timer();
}

window.onload = init;
</script>

<?php 

if ($metode=="1") {
       $query=mysql_query("SELECT soal_test.idsoal,soal_opsi.nip,soal_opsi.pertanyaan,soal_opsi.opsia,soal_opsi.opsib,soal_opsi.opsic,
                        soal_opsi.opsid,soal_opsi.jawaban,soal_opsi.pembahasan,soal_opsi.`status`,soal_utama.idsoalutama FROM
                        soal_test
                        Inner Join soal_opsi ON soal_test.idsoal = soal_opsi.idsoal
                        Inner Join soal_utama ON soal_utama.idsoalutama = soal_test.idsoalutama
                        where soal_utama.idsoalutama='".mysql_real_escape_string($idsoalutama)."' order by RAND() limit 0,$disajikan");
    
}
else {
     $query=mysql_query("SELECT soal_test.idsoal,soal_opsi.nip,soal_opsi.pertanyaan,soal_opsi.opsia,soal_opsi.opsib,soal_opsi.opsic,
                        soal_opsi.opsid,soal_opsi.jawaban,soal_opsi.pembahasan,soal_opsi.`status`,soal_utama.idsoalutama FROM
                        soal_test
                        Inner Join soal_opsi ON soal_test.idsoal = soal_opsi.idsoal
                        Inner Join soal_utama ON soal_utama.idsoalutama = soal_test.idsoalutama
                        where soal_utama.idsoalutama='".mysql_real_escape_string($idsoalutama)."' limit 0,$disajikan");
}

if ($pernah!=0) $ke = $pernah+1;
else $ke=1;

$input=mysql_query("insert into soal_hasil (idsoalutama,nis,benar,salah,nilai,kesempatanjawab,lama,tglpengerjaan) 
values ('".mysql_real_escape_string($idsoalutama)."','$nis','0','0','0','$ke','0',sysdate())");  
$cetak .= "<table width='100%' cellspacing='2' cellpadding='2' border=0 ><tr><td style='border:1px solid #6a849d'>";     
                                
$jam = '<FORM NAME="User"> 
<table border="0" cellspacing="2" align=center cellpadding="5" style="color:black;font-size:12px;border:1px solid #6a849d;background:#a1b4c7;">
   <tr><td ><b>Sisa Waktu</b></td><td><input readonly="true" type="text" size="8" border="0" name="TimeLeft"></td></tr>
   <tr><td ><b>Waktu</b></td><td><input readonly="true" type="text" size="8" border="0" name="TimeTaken"></td></tr>
   <tr><td ><b>Sekarang Jam</b></td><td><input readonly="true" type="text" size="8" border="0" name="Watch"></td></tr>
   </table>
</FORM>';

$jumlah = mysql_num_rows($query);
$mulai = date("Y-m-d H:i:s",mktime(gmdate("H")+$timezone,gmdate("i"),gmdate("s"),gmdate("m"),gmdate("d"),gmdate("Y")));

$cetak .= "<table width='100%' cellspacing='2' cellpadding='3' style='color:black;font-size:12px;background:#DEDEDE;border:1px solid #6a849d'>";
$cetak .= "<tr><td width='15%'>NIS</td><td width='3'><b>:</b></td><td >".$nis."</td><td rowspan=7 >".$jam."</td></tr>";
$cetak .= "<tr><td>Nama Peserta</td><td><b>:</b></td><td>".$nmsiswa."</td></tr>";
$cetak .= "<tr><td>Pelajaran</td><td><b>:</b></td><td>".$pel."</td></tr>";
$cetak .= "<tr><td>Jenis</td><td><b>:</b></td><td>".$jenis."</td></tr>";
$cetak .= "<tr><td>Jumlah soal</td><td><b>:</b></td><td>".$disajikan."</td></tr>";
$cetak .= "<tr><td>Kesempatan ke</td><td><b>:</b></td><td>".$ke." dari ".$kesempatan." kesempatan</td></tr>";
$cetak .= "<tr><td>Waktu</td><td><b>:</b></td><td>".$waktu." menit</td></tr>";
$cetak .= "<tr><td></td><td></td><td colspan=2 ><b>Jangan Reload / Refresh browser ini, apabila dilakukan maka kesempatan tes online bertambah.<br/>
Klik tombol SELESAI apabila telah menjawab semua soal.</b></td></tr>";
$cetak .= "</table><br/>";
$cetak .= "<form name='ljkform' method='post' action='user.php'>";
$no=1;
while ($row=mysql_fetch_array($query)) {
    $pertanyaan=$row['pertanyaan'];
    $ary=$row['idsoal'];
    $jawab="jwb[$ary]";
    $tanya="tanya[$no]";
    $tanyanilai="[$ary]";
           
    $alt_6=$row["jawaban"];
   	$alt_1=$row["opsia"];
   	$alt_2=$row["opsib"];
   	$alt_3=$row["opsic"];
   	$alt_4=$row["opsid"];
    $input=array($alt_1,$alt_2,$alt_3,$alt_4,$alt_6);
    sort($input, SORT_STRING);
    srand ((float)microtime()*1000000);
    shuffle($input);
    $cetak .= '<table border="0" width="100%" cellspacing="1" cellpadding="1" style="color:black;font-size:12px;" >'; 
    $cetak .= "<tr><td valign='top' width='20' >$no.</td>";
    $cetak .= "<td >$pertanyaan <input type=hidden name=$tanya value=$ary ></td></tr>";
    $cetak .= "<tr><td></td><td><table border=0 cellspacing=2 cellpadding=3 style='color:black;font-size:12px;' >";
    for ($j = 0; $j < 5; $j++){
          $cetak .= "<tr><td width='5' valign=top ><input name='$jawab' type='radio' value='$input[$j]'></td>";
          $cetak .= "<td >".$input[$j]."</td></tr>";
    }  			  
    $cetak .= "</table></td></tr></table>";
    $no++;
}

$cetak .= "<center><br><input type=\"submit\" name=\"submit\" id='button2'value=\"Selesai\"></center>";
$cetak .= "<input type=\"hidden\" name=\"mulai\" value=\"$mulai\"><input type=\"hidden\" name=\"tampil\" value=\"$disajikan\">";
$cetak .= "<input type=\"hidden\" name=\"ke\" value=\"$ke\">";
$cetak .= "<input type=\"hidden\" name=\"vnosoal\" value=\"$idsoalutama\"><input type=hidden id='id' name='id' value='selesaites'  >";
$cetak .= "</form></td></tr></table>";
$_SESSION['save'] = 'ya';
}
$cetak .="</div>";
return $cetak;
}

function selesaites() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
session_start();
    $nis     = konversi_id($userid);
    $kelas   = konversi_kls($nis);
    $nmsiswa = konversi_nama($nis);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $mulai     = $_POST['mulai'];		
    $vnosoal   = $_POST['vnosoal'];
    $ke		   = $_POST['ke'];
    $disajikan = $_POST['tampil'];
    
if (isset($_SESSION['save'])) {

    $selesai = strtotime(date("Y-m-d H:i:s",mktime(gmdate("H")+$timezone,gmdate("i"),gmdate("s"),gmdate("m"),gmdate("d"),gmdate("Y"))));
    $mulai=strtotime($mulai);
    $lama = $selesai - $mulai;
    
    $jam   = floor($lama/3600);
    $menit = floor(($lama - $jam * 3600)/60);
    $detik = $lama - $jam*3600 - $menit*60;
    
    if($jam < 10) $jam="0$jam";
    if($menit < 10) $menit="0$menit";
    if($detik < 10) $detik="0$detik";
    	
    $time_lama="$jam : $menit : $detik";
    $benar=0;$salah=0;
    
    for ($i = 1; $i <= $disajikan; $i++) {
        $tanya=$_POST['tanya'];
        $query=mysql_query("SELECT soal_test.idsoal,soal_opsi.nip,soal_opsi.pertanyaan,soal_opsi.opsia,soal_opsi.opsib,soal_opsi.opsic,
                            soal_opsi.opsid,soal_opsi.jawaban,soal_opsi.pembahasan,soal_opsi.`status`,soal_utama.idsoalutama FROM
                            soal_test
                            Inner Join soal_opsi ON soal_test.idsoal = soal_opsi.idsoal
                            Inner Join soal_utama ON soal_utama.idsoalutama = soal_test.idsoalutama
                            where soal_utama.idsoalutama='".mysql_real_escape_string($vnosoal)."' and soal_test.idsoal='".$tanya[$i]."'");
        $jumlah = mysql_num_rows($query);
        while($row = mysql_fetch_array($query)) {
            $ary=$row['idsoal'];
            $jwb=$_POST['jwb'];
            
            if ($jwb[$ary]==$row['jawaban']) {
         		$ket="Benar";
                $benar++;
            }
            else {
         		$ket="Salah";	
                $salah++;
            }
            $sqlidhasil = mysql_query("select idhasil from soal_hasil where idsoalutama='".mysql_real_escape_string($vnosoal)."' 
            and nis='".mysql_real_escape_string($nis)."' and kesempatanjawab='".mysql_real_escape_string($ke)."'");
            if ($rowhasil = mysql_fetch_array($sqlidhasil)) {
                $idhasilna = $rowhasil['idhasil'];
            }
//            $sql = mysql_query("select * from soal_opsi where idsoal='".mysql_real_escape_string($ary)."'");
//            if ($r = mysql_fetch_array($sql)) {
//                if ($jwb[$ary]==$r['jawaban']) $opsi='jawaban';
//                elseif ($jwb[$ary]==$r['opsia']) $opsi='opsia';
//                elseif ($jwb[$ary]==$r['opsib']) $opsi='opsib';
//                elseif ($jwb[$ary]==$r['opsic']) $opsi='opsic';
//                else $opsi='opsid';
//            }
            $insert=mysql_query("insert into soal_jawab(idhasil,idsoal,ket) values ('$idhasilna','$ary','$ket')");
        }
    }
    
    if ($disajikan > $jumlah) $soaldikerjakan = $jumlah;
    else $soaldikerjakan = $disajikan;
    
    if ($benar==0) $nilai=0;
    else $nilai= $benar/$disajikan*100;

    $cetak .= '<br/><table border="0" cellspacing="2" align=center cellpadding="5" 
    style="color:black;font-size:12px;border:1px solid #6a849d;background:#a1b4c7;">';
    $cetak .= "<tr><td>Lama </td><td>: $time_lama ($menit menit)</td></tr>";
    $cetak .= "<tr><td>Benar </td><td>: $benar</td></tr>";
    $cetak .= "<tr><td>Salah </td><td>: $salah</td></tr>";
    $cetak .= "<tr><td>Nilai </td><td >: <b>$nilai</b></td></tr></table>";
    
    $insert=mysql_query("update soal_hasil set tglpengerjaan=sysdate(),benar='$benar',salah='$salah',nilai='$nilai',lama='$menit'
     where idsoalutama='".mysql_real_escape_string($vnosoal)."' and nis='$nis' and kesempatanjawab='$ke'");
    unset($_SESSION['save']);
    
}
   $cetak .='<br><table border="0" cellspacing="2" width="100%" cellpadding="5" 
    style="color:black;font-size:12px;border:1px solid #6a849d;">
    <tr bgcolor="#a1b4c7" ><td>No</td><td align=center>Pertanyaan</td><td align=center>Jawaban Anda</td></tr>';
    
    $query8=mysql_query("SELECT
    soal_opsi.pertanyaan,
    soal_jawab.ket
    FROM
    soal_jawab
    Inner Join soal_opsi ON soal_jawab.idsoal = soal_opsi.idsoal
    Inner Join soal_hasil ON soal_hasil.idhasil = soal_jawab.idhasil
    where soal_hasil.nis='$nis' and soal_hasil.idsoalutama='".mysql_real_escape_string($vnosoal)."' and soal_hasil.kesempatanjawab ='$ke'");
    $no=1;
    while($row8 = mysql_fetch_array($query8)) {
    
       $cetak .="<tr ><td valign=top >$no</td><td>";
       //$cetak .= F_decode_tcecode($row8['pertanyaan']);
       $cetak .= $row8['pertanyaan'];
       $cetak .="</td><td align=center valign=top >";
       $cetak .= $row8['ket'];
       $cetak .="</td></tr>";
       $no++;
    }
    $cetak .="</table><br>";

$cetak .="</div>";
return $cetak;
}


function vnilai() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$siswatest .="<div id='depan-tengahkanan'>";
$siswatest.= statusanda($userid);
$siswatest .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$pel=$_GET['pel'];
$thajar=$_GET['thajar'];

$nis = konversi_id($userid);
$kelas=konversi_kls($nis);
$nmsiswa = konversi_nama($nis);
$program=konversi_program($kelas);
$siswatest .= ataslogin("Tes Online - Siswa - $nmsiswa");
  $brs=20;
  $kol=10;
  $tgl = date("Y-m-d");  
  $byk_result=mysql_query("SELECT soal_utama.idsoalutama FROM soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama
  and soal_kelas.kelas='".mysql_real_escape_string($kelas)."' and soal_utama.sem='".mysql_real_escape_string($sem)."' 
  and soal_utama.pel='".mysql_real_escape_string($pel)."' and soal_utama.thajar='".mysql_real_escape_string($thajar)."'");
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
  
  $query = "SELECT * FROM soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama
  and soal_kelas.kelas='".mysql_real_escape_string($kelas)."' and soal_utama.sem='".mysql_real_escape_string($sem)."' 
  and soal_utama.pel='".mysql_real_escape_string($pel)."' and soal_utama.thajar='".mysql_real_escape_string($thajar)."' LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

 $data3 .=  "<select name='sem'>";
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
   
  $siswatest .= "<form action='user.php?' method='get' name='siswa'>
  <a href='user.php?id=siswatest' title='Daftar nilai tes online' id=button2 >Tes Online</a><br/><br/>
  <input type=hidden name=id value='v_nilai'>
  Pelajaran : <select name='pel'>";
  $q2 = mysql_query ("select * from t_pelajaran where program='-' or program='".mysql_real_escape_string($program)."' order by pel");
  while($r = mysql_fetch_array($q2)) {
	if ($r[pel]==$pel) $siswatest .= "<option value='$r[pel]' selected>$r[pel]</option>";
	else $siswatest .= "<option value='$r[pel]' >$r[pel]</option>";
  }

  $siswatest .= "</select>&nbsp;&nbsp;Thn Pelajaran : $data4 &nbsp; Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $siswatest .= "<tr><td colspan=3 ><center><a href='user.php?id=v_nilai&hal=1&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal 1'>First </a> 
  <a href='user.php?id=v_nilai&hal=$back&sem=$sem&pel=$pel&thajar=$thajar'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$siswatest .= "<b><a href='user.php?id=v_nilai&hal=$i&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$siswatest .= "<a href='user.php?id=v_nilai&hal=$i&sem=$sem&pel=$pel&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $siswatest .= "<a href='user.php?id=v_nilai&hal=$next&sem=$sem&pel=$pel&thajar=$thajar'  title='$next'> Next</a> 
  <a href='user.php?id=v_nilai&hal=$jml&sem=$sem&pel=$pel&thajar=$thajar'  title='Page $jml'> Last</a></font></center></td></tr>";
  }
    $siswatest .="<tr class='td0'><td><b>No</td><td><b>Tanggal</td><td><b>Jenis</td><td><b>Materi</td><td align=center ><b>Detail</td></tr>";
 
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
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
    $tgl = date("d-m-Y",strtotime($row['tgl_mulai']))." s.d ".date("d-m-Y",strtotime($row['tgl_akhir']));
    $siswatest .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\">
    <td width='3%' valign=top >$j</td><td width='13%' vlign=top>$tgl</td><td width='13%' vlign=top >$jenis</td>
    <td >$row[materi]</td><td width='8%' vlign=top align=center >
    <a href='user.php?id=hasildetail&idsoal=$row[idsoalutama]' title='Lihat hasil tes' id=button2 >Detail</a></td>
    </tr>";
	$j++;
 }        
  $siswatest .= "</table>";

 $siswatest .="</div>";
return $siswatest;
}

function hasildetail() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $level = $_SESSION['User']['ket'];
    $namaguru = $_SESSION['User']['nama'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $nis = konversi_id($userid);
    $nmsiswa = konversi_nama($nis);
    $cetak .="<hr style='border: thin solid #6A849D;'>";  
    
    $cetak .= ataslogin("Tes Online - Siswa - $nmsiswa");
    $cetak .= "<a href='user.php?id=siswatest' id=button2 >Tes Online</a> 
    <a href='user.php?id=v_nilai' id=button2 >Daftar Nilai Tes Online</a> <br><br>";
    $idsoalutama = mysql_real_escape_string($_GET['idsoal']);
    $q = mysql_query("select * from soal_utama where idsoalutama='".$idsoalutama."'");
    if($r=mysql_fetch_array($q)) {
        if($r['jenis']==1) $jenis = "Ulangan Harian";
        elseif($r['jenis']==2) $jenis = "Ulangan Blok";
        elseif($r['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($r['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($r['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        
        $cetak .= "<table><tr><td>Pelajaran</td><td>: ".$r['pel']."</td></tr>";
        $cetak .= "<tr><td valign=top >Materi</td><td>: ".$r['materi']."</td></tr>";
        $cetak .= "<tr><td>Kesempatan</td><td>: ".$r['kesempatan']." kali</td></tr>";
        $cetak .= "<tr><td>Jenis </td><td>: ".$jenis."</td></tr>";
        $cetak .= "<tr><td>Jumlah Soal</td><td>: ".$r['jml_tampil']."</td></tr></table><br/>";
        $query = mysql_query("select * from soal_hasil where idsoalutama='".$idsoalutama."' and nis='".$nis."' order by kesempatanjawab ");
        $jmlsoal  = mysql_num_rows($query);
        
        $cetak .= "<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
        $cetak .="<tr class='td0'><td width='3%'><b>No</b></td><td align=center width='20%' ><b>Tgl Pengerjaan</b></td>
        <td align=center ><b>Waktu</b></td><td align=center ><b>Tes ke</b></td><td align=center ><b>Benar</b></td><td align=center ><b>Salah</b></td>
        <td align=center ><b>Nilai</b></td><td align=center width=10% ><b>Pembahasan</b></td></tr>";
        $j=1;
        while($row=mysql_fetch_array($query)) {
          	$warna = "td1";
        	if ($x==1) {
        	$warna = "td2";
        	$x=0; }
        	else $x=1;
            
            $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\">
            <td >$j</td><td>".date("d-m-Y h:i:s",strtotime($row['tglpengerjaan']))."</td>
            <td align=center >".$row['lama']."</td><td align=center >".$row['kesempatanjawab']."</td>
            <td align=center >".$row['benar']."</td><td align=center >".$row['salah']."</td><td align=center >".$row['nilai']."</td>
            <td align=center ><a href='user.php?id=v_detail&idsoal=$idsoalutama&idhasil=$row[idhasil]' title='Lihat pembahasan' id=button2 >Detail</a></td>
            </tr>";
            $j++;
        }
        $cetak .="</table>";
    }
    else {
        $cetak .= "Maaf anda tidak bisa mengakses fitur ini";
    }
 $cetak .="</div>";
return $cetak;      
}

function vdetail() {
include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $nis = konversi_id($userid);
    $nmsiswa = konversi_nama($nis);
    $cetak .="<hr style='border: thin solid #6A849D;'>";  
    
    $cetak .= ataslogin("Tes Online - Siswa - $nmsiswa");
    $cetak .= "<a href='user.php?id=siswatest' id=button2 >Tes Online</a> 
    <a href='user.php?id=v_nilai' id=button2 >Daftar Nilai Tes Online</a> 
    <a href='#' id='button2' onClick=\"history.go(-1)\">Kembali</a><br><br>";
    
    $idsoalutama = mysql_real_escape_string($_GET['idsoal']);
    $idhasil     = mysql_real_escape_string($_GET['idhasil']);
    $q = mysql_query("select * from soal_utama where idsoalutama='".$idsoalutama."'");
    if($r=mysql_fetch_array($q)) {
        if($r['jenis']==1) $jenis = "Ulangan Harian";
        elseif($r['jenis']==2) $jenis = "Ulangan Blok";
        elseif($r['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($r['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($r['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        
        $cetak .= "<table ><tr><td>Pelajaran</td><td>: ".$r['pel']."</td></tr>";
        $cetak .= "<tr><td valign=top >Materi</td><td>: ".$r['materi']."</td></tr>";
        $cetak .= "<tr><td>Jenis </td><td>: ".$jenis."</td></tr>";
        $cetak .= "<tr><td>Jumlah Soal</td><td>: ".$r['jml_tampil']."</td></tr>";
        $cetak .= "<tr><td>Tes ke</td><td>: ".$r['kesempatan']." kali</td></tr></table><br/><h3>Pembahasan :</h3>";
        $sql = "SELECT soal_hasil.idsoalutama,soal_hasil.kesempatanjawab,soal_hasil.nis,soal_utama.tgl_akhir,
        soal_jawab.ket,soal_jawab.idsoal,soal_opsi.pertanyaan,soal_opsi.opsia,soal_opsi.opsib,soal_opsi.opsic,
        soal_opsi.opsid,soal_opsi.jawaban,soal_opsi.pembahasan FROM
        soal_hasil
        Inner Join soal_utama ON soal_hasil.idsoalutama = soal_utama.idsoalutama
        Inner Join soal_jawab ON soal_jawab.idhasil = soal_hasil.idhasil
        Inner Join soal_opsi ON soal_jawab.idsoal = soal_opsi.idsoal
        where soal_hasil.nis ='$nis' and soal_hasil.idsoalutama='$idsoalutama' and soal_jawab.idhasil='$idhasil'";

        if ($r['jenis']==5) {
            $cetak .= "<table border=0 cellpadding='2' cellspacing='2' style='border: solid 1px #6A849D;' >";
            $q = mysql_query($sql);
            $i=1;
            while($row = mysql_fetch_array($q)) {
                $cetak .= "<tr><td width=5 valign=top >$i.</td><td>".$row['pertanyaan']." <br/><b>Jawab : ".$row['ket']." </b><br/>
                <hr style='border:dotted 1px #6A849D' /><b>Pembahasan</b><br/>".$row['pembahasan']."<br/><b>Kunci : </b>".$row['jawaban']."</td></tr>";
                $cetak .= "<tr><td colspan=2 ><hr style='border:solid 1px #6A849D' /></td></tr>";
                $i++;
            }
            $cetak .= "</table><br/>";
        }
        else {
            //cek tanggal akhir ujian
            $tgl = date('Y-m-d');
            $sql .= " and '".$tgl."'>soal_utama.tgl_akhir";
            $q = mysql_query($sql);
            $i=1;
            if ( mysql_num_rows($q) > 0 ) {
              $cetak .= "<table border=0 cellpadding='2' cellspacing='2' style='border: solid 1px #6A849D;' >";  
              while($row = mysql_fetch_array($q)) {
                $cetak .= "<tr><td width=5 valign=top >$i</td><td>".$row['pertanyaan']." <br/><b>Jawab : </b><br/>
                <hr style='border:dotted 1px #6A849D' /><b>Pembahasan</b><br/>".$row['pembahasan']."<br/><b>Kunci : </b>".$row['jawaban']."</td></tr>";
                $cetak .= "<tr><td colspan=2 ><hr style='border:solid 1px #6A849D' /></td></tr>";
                $i++;
              }
              $cetak .= "</table><br/>";
            }
            else {
                $cetak .= "<br/>Anda tidak dapat melihat pembahasan dari soal ini, karena soal tes ini digunakan oleh kelas lain.<br/>
                Pembahasan akan bisa dilihat setelah tanggal tes berakhir.";
            }
        }
    }
    else {
        $cetak .= "Maaf anda tidak bisa mengakses fitur ini";
    }

$cetak .="</div>";

return $cetak;
} 
?>