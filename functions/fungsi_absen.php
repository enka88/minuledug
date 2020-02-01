<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function dataabsen() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$absen .="<div id='depan-tengahkanan'>";
$absen .= statusanda($userid);
$absen .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$bln=$_GET['bln'];
$th=$_GET['th'];

$nis = konversi_id($userid);
$nmsiswa = konversi_nama($nis);
$absen .= ataslogin("Data Absensi - Siswa - $nmsiswa");

  $brs=40;
  $kol=10;
  $byk_result=mysql_query("select idabsen from t_absensi where nis='".mysql_real_escape_string($nis)."' and month(tglabsen)='".mysql_real_escape_string($bln)."' and year(tglabsen)='".mysql_real_escape_string($th)."'");
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
  
  $query = "select * from t_absensi where nis='".mysql_real_escape_string($nis)."' and month(tglabsen)='".mysql_real_escape_string($bln)."' and year(tglabsen)='".mysql_real_escape_string($th)."' order by tglabsen  LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  if($bln=='01') $b1='selected';
  elseif($bln=='02') $b2='selected';
  elseif($bln=='03') $b3='selected';
  elseif($bln=='04') $b4='selected';
  elseif($bln=='05') $b5='selected';
  elseif($bln=='06') $b6='selected';
  elseif($bln=='07') $b7='selected';
  elseif($bln=='08') $b8='selected';
  elseif($bln=='09') $b9='selected';
  elseif($bln=='10') $b10='selected';
  elseif($bln=='11') $b11='selected';
  elseif($bln=='12') $b12='selected';
 if($th=='2008') $t1='selected';
elseif($th=='2009') $t2='selected';
elseif($th=='2010') $t3='selected';
elseif($th=='2011') $t4='selected';
elseif($th=='2012') $t5='selected';
elseif($th=='2013') $t6='selected';
elseif($th=='2014') $t7='selected';
elseif($th=='2015') $t8='selected';
  $absen .= "<div align='left'><form action='user.php' method='get' name='siswa'>
  <input type=hidden name=id value='dataabsen'>
  Bulan : ";
  $absen .='<select name="bln">
        <option value="01" '.$b1.'>Januari</option>
		<option value="02" '.$b2.'>Februari</option>
		<option value="03" '.$b3.'>Maret</option>
		<option value="04" '.$b4.'>April</option>
		<option value="05" '.$b5.'>Mei</option>
		<option value="06" '.$b6.'>Juni</option>
		<option value="07" '.$b7.'>Juli</option>
		<option value="08" '.$b8.'>Agustus</option>
		<option value="09" '.$b9.'>September</option>
		<option value="10" '.$b10.'>Oktober</option>
		<option value="11" '.$b11.'>November</option>
		<option value="12" '.$b12.'>Desember</option>
      </select>';
  $absen .= "</select>&nbsp;&nbsp; <select name='th' >";
  $absen .='<option value="2010" '.$t1.'>2010</option>
		<option value="2011" '.$t2.'>2011</option>	
		<option value="2012" '.$t3.'>2012</option>	
		<option value="2013" '.$t4.'>2013</option>	
		<option value="2014" '.$t5.'>2014</option>	
		<option value="2015" '.$t6.'>2015</option>	
		<option value="2016" '.$t7.'>2016</option>	
		<option value="2017" '.$t8.'>2017</option></select>	';
		
	$absen .="<input type=submit value=' Pilih ' id=button2 >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</form</div>
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $absen .= "<tr><td colspan=4  ><center><a href='user.php?id=dataabsen&hal=1&bln=$bln&th=$th' style='color:000000;text-decoration:none' title='Hal 1'>First </a> 
  <a href='user.php?id=dataabsen&hal=$back&bln=$bln&th=$th' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$absen .= "<b><a href='user.php?id=dataabsen&hal=$i&bln=$bln&th=$th'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$absen .= "<a href='user.php?id=dataabsen&hal=$i&bln=$bln&th=$th' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $absen .= "<a href='user.php?id=dataabsen&hal=$next&bln=$bln&th=$th' title='$next'> Next</a> 
  <a href='user.php?id=dataabsen&hal=$jml&bln=$bln&th=$th' title='Page $jml'> Last</a></center></td></tr>";
  }
  $absen .="<tr class='td0' ><td align='center'><b>No</td><td><b>Tanggal</td><td><b>Status Absensi</td><td><b>Terlambat</td></tr>";
  $n=($brs*($hal-1)) + 1; 
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	if($row['stabsen']=='') $status='Hadir';
	elseif($row['stabsen']=='S') $status='Sakit';
	elseif($row['stabsen']=='I') $status='Izin';
	elseif($row['stabsen']=='A') $status='Alpa';
    elseif($row['stabsen']=='T') $status='Terlambat';
   $absen .= "<tr class='$warna' ><td width='5%' align='center'>$n</td>
   <td width='15%'>".date("d-m-Y",strtotime($row[tglabsen]))."</td>
   <td width='30%'>$status</td><td width='20%'>$row[terlambat]</td></tr>";
	$n++;
 }        
  $absen .= "</table>";
  $absen .="</div>";
return $absen;
}


function guruabsen() {
include "koneksi.php";

$userid = $_SESSION['User']['userid'];
$absen .="<div id='depan-tengahkanan'>";
$absen .= statusanda($userid);
$absen .="<hr style='border: thin solid #6A849D;'>";
$bln=$_GET['bln'];
$th=$_GET['th'];
$kls=$_GET['kls'];
$program=$_GET['program'];
if ($bln=='') $bln=$_POST['bln'];
if ($th=='') $th=$_POST['th'];
if ($kls=='') $kls=$_POST['kls'];
if ($program=='') $program=$_POST['program'];

$absen .= ataslogin("Data Absensi - Siswa ");

   if($program=='') $program='-';
  $query = "select user_id,nama,kelas from t_siswa where kelas='".mysql_real_escape_string($kls)."' order by nama"; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // tambah alan untuk delete multiple	
  if($bln=='01') $b1='selected';
  elseif($bln=='02') $b2='selected';
  elseif($bln=='03') $b3='selected';
  elseif($bln=='04') $b4='selected';
  elseif($bln=='05') $b5='selected';
  elseif($bln=='06') $b6='selected';
  elseif($bln=='07') $b7='selected';
  elseif($bln=='08') $b8='selected';
  elseif($bln=='09') $b9='selected';
  elseif($bln=='10') $b10='selected';
  elseif($bln=='11') $b11='selected';
  elseif($bln=='12') $b12='selected';
 if($th=='2010') $t1='selected';
elseif($th=='2011') $t2='selected';
elseif($th=='2012') $t3='selected';
elseif($th=='2013') $t4='selected';
elseif($th=='2014') $t5='selected';
elseif($th=='2015') $t6='selected';
elseif($th=='2016') $t7='selected';
elseif($th=='2017') $t8='selected';
  
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      $data2.= 'Jurusan/Program Keahlian <select name=program onchange="document.location.href=\'user.php?id=guruabsen&program=\'+document.guru.program.value">';
  $sql2="select * from t_programahli order by idprog";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[program]==$program) $data2.= "<option value='$al[program]' selected>$al[program]</option>";
  	else $data2.= "<option value='$al[program]' >$al[program]</option>";
  }
  $data2.= "</select> &nbsp;&nbsp;";
  }
  else  $data2.= "<input type=hidden name=program value='-'/>";
  $absen .= "<form action='user.php' method='get' name='guru'>
  <input type=hidden name=id value='guruabsen'>
  $data2 &nbsp;Kelas : <select name='kls'>";
  $q=mysql_query("select * from t_kelas where program='".mysql_real_escape_string($program)."' ");
  while($r=mysql_fetch_array($q)) {
  	if ($r[kelas]==$kls) $absen .= "<option value='$r[kelas]' selected>$r[kelas]</option>";
  	else $absen .= "<option value='$r[kelas]'>$r[kelas]</option>";
  }
  
  $absen .= "</select>&nbsp;&nbsp;<br>Bulan : ";
  $absen .='<select name="bln">
        <option value="01" '.$b1.'>Januari</option>
		<option value="02" '.$b2.'>Februari</option>
		<option value="03" '.$b3.'>Maret</option>
		<option value="04" '.$b4.'>April</option>
		<option value="05" '.$b5.'>Mei</option>
		<option value="06" '.$b6.'>Juni</option>
		<option value="07" '.$b7.'>Juli</option>
		<option value="08" '.$b8.'>Agustus</option>
		<option value="09" '.$b9.'>September</option>
		<option value="10" '.$b10.'>Oktober</option>
		<option value="11" '.$b11.'>November</option>
		<option value="12" '.$b12.'>Desember</option>
      </select>';
  $absen .= "</select>&nbsp;&nbsp; Tahun : <select name='th' >";
  $absen .='<option value="2010" '.$t1.'>2010</option>
		<option value="2011" '.$t2.'>2011</option>	
		<option value="2012" '.$t3.'>2012</option>	
		<option value="2013" '.$t4.'>2013</option>	
		<option value="2014" '.$t5.'>2014</option>	
		<option value="2015" '.$t6.'>2015</option>	
		<option value="2016" '.$t7.'>2016</option>	
		<option value="2017" '.$t8.'>2017</option></select>	';
		
  $absen .="<input type=submit value=' Pilih ' id=button2 > &nbsp;&nbsp;";
  $absen .= ($bln=='' and $th=='')?'':"<a href='../functions/fungsi_excelabsensi.php?format=semua&kelas=$kls&bln=$bln&th=$th' target='_blank' id=button2 >Download</a>";
  
  $absen .="</form><table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>";
  $n=1;
  $absen .="<tr class='td0' ><td>No</td><td>NIS</td><td>Nama</td>
  <td>Hadir</td><td>Sakit</td><td>Izin</td><td>Alpha</td><td>Terlambat</td><td></td></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
    $hadir=0;$sakit=0;$alpa=0;$izin=0;$lambat=0;
     $query = mysql_query("select count(stabsen) as jum,stabsen from t_absensi where nis='".$row['user_id']."' and month(tglabsen)='$bln' and year(tglabsen)='$th' 
     and stabsen in ('','S','T','A','I') group by stabsen ");
     while($r=mysql_fetch_array($query)) {
        if ($r['stabsen']=='')  $hadir =$r['jum'];
        if ($r['stabsen']=='S') $sakit =$r['jum'];
        if ($r['stabsen']=='A') $alpa  =$r['jum'];
        if ($r['stabsen']=='I') $izin  =$r['jum'];
        if ($r['stabsen']=='T') $lambat=$r['jum'];

     }
   $absen .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$n</td>
   <td width='10%'>$row[user_id]</td>
   <td width='30%'>$row[nama]</td>
   <td width='5%'>$hadir</td>
   <td width='5%'>$sakit</td>
   <td width='5%'>$izin</td>
   <td width='5%'>$alpa</td>
   <td width='5%'>$lambat</td><td width='5%' align=center ><a href='user.php?id=guruabsendetail&kd=$row[user_id]&bln=$bln&thn=$th' >Detail</a></td></tr>";
	$n++;
 }        
  $absen .= "</table><br>";
  $absen .="</div>";
return $absen;
}

function guruabsendetail() {
    include "koneksi.php";

    $userid = $_SESSION['User']['userid'];
    $absen .="<div id='depan-tengahkanan'>";
    $absen .= statusanda($userid);
    $absen .="<hr style='border: thin solid #6A849D;'>";
    $nis=$_GET['kd'];
    $bln=$_GET['bln'];
    $th=$_GET['thn'];
    
 if($bln=='01') $b1='selected';
  elseif($bln=='02') $b2='selected';
  elseif($bln=='03') $b3='selected';
  elseif($bln=='04') $b4='selected';
  elseif($bln=='05') $b5='selected';
  elseif($bln=='06') $b6='selected';
  elseif($bln=='07') $b7='selected';
  elseif($bln=='08') $b8='selected';
  elseif($bln=='09') $b9='selected';
  elseif($bln=='10') $b10='selected';
  elseif($bln=='11') $b11='selected';
  elseif($bln=='12') $b12='selected';
 if($th=='2010') $t1='selected';
elseif($th=='2011') $t2='selected';
elseif($th=='2012') $t3='selected';
elseif($th=='2013') $t4='selected';
elseif($th=='2014') $t5='selected';
elseif($th=='2015') $t6='selected';
elseif($th=='2016') $t7='selected';
elseif($th=='2017') $t8='selected';
  
    $q = mysql_query("select user_id,nama,kelas from t_siswa where user_id='".mysql_real_escape_string($nis)."'");
    $r = mysql_fetch_array($q);
    $nama = $r['nama'];
    $kelas= $r['kelas'];
  
    $absen .="<form action='user.php' method='get' name='guru'>
    <input type=hidden name=id value='guruabsendetail' /><input type=hidden name='kd' value='$nis' />
    <table border=0 ><tr><td>NIS </td><td>: $nis</td></tr>
    <tr><td>Nama </td><td>: $nama</td></tr>
    <tr><td>Kelas </td><td>: $kelas</td></tr>
    <tr><td>Bulan/Tahun </td><td>: ";

  $absen .='<select name="bln">
        <option value="01" '.$b1.'>Januari</option>
		<option value="02" '.$b2.'>Februari</option>
		<option value="03" '.$b3.'>Maret</option>
		<option value="04" '.$b4.'>April</option>
		<option value="05" '.$b5.'>Mei</option>
		<option value="06" '.$b6.'>Juni</option>
		<option value="07" '.$b7.'>Juli</option>
		<option value="08" '.$b8.'>Agustus</option>
		<option value="09" '.$b9.'>September</option>
		<option value="10" '.$b10.'>Oktober</option>
		<option value="11" '.$b11.'>November</option>
		<option value="12" '.$b12.'>Desember</option>
      </select>';
  $absen .= "</select>&nbsp;&nbsp; <select name='thn' >";
  $absen .='<option value="2010" '.$t1.'>2010</option>
		<option value="2011" '.$t2.'>2011</option>	
		<option value="2012" '.$t3.'>2012</option>	
		<option value="2013" '.$t4.'>2013</option>	
		<option value="2014" '.$t5.'>2014</option>	
		<option value="2015" '.$t6.'>2015</option>	
		<option value="2016" '.$t7.'>2016</option>	
		<option value="2017" '.$t8.'>2017</option></select>	';
        
    $absen .="<input type=submit value=' Pilih ' id=button2 ></td></tr></table></form>";
     $absen .="<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>
     <tr class='td0' ><td align='center'><b>No</td><td><b>Tanggal</td><td><b>Status Absensi</td><td><b>Terlambat</td></tr>";
    $query = "select * from t_absensi where nis='".mysql_real_escape_string($nis)."' and month(tglabsen)='".mysql_real_escape_string($bln)."' and year(tglabsen)='".mysql_real_escape_string($th)."' order by tglabsen "; 
    $query_result_handle = mysql_query($query); 
    $n=1;
      while ($row = mysql_fetch_array($query_result_handle))
      {
      	$warna = "td1";
    	if ($j==1) {
    	$warna = "td2";
    	$j=0; }
    	else $j=1;
    	if($row['stabsen']=='') $status='Hadir';
    	elseif($row['stabsen']=='S') $status='Sakit';
    	elseif($row['stabsen']=='I') $status='Izin';
    	elseif($row['stabsen']=='A') $status='Alpa';
        elseif($row['stabsen']=='T') $status='Terlambat';
       $absen .= "<tr class='$warna' ><td width='5%' align='center'>$n</td>
       <td width='15%'>".date("d-m-Y",strtotime($row['tglabsen']))."</td>
       <td width='30%'>$status</td><td width='20%'>$row[terlambat]</td></tr>";
    	$n++;
     }        
      $absen .= "</table><br/>";
      $absen .="</div>";
    return $absen;
}
?>