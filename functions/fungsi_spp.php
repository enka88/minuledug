<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
function dataspp() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$dataspp .="<div id='depan-tengahkanan'>";
$dataspp .= statusanda($userid);
$dataspp .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$kd=$_GET['kd'];
$kelas=$_GET['kelas'];

$nis = konversi_id($userid);
if ($kelas=='') $kelas=konversi_kls($nis);

if ($kd=='') $kd='spp';

$nmsiswa = konversi_nama($nis);
$dataspp .= ataslogin("Data SPP/DSP - Siswa - $nmsiswa");
  $brs=30;
  $kol=10;

  $byk_result=mysql_query("SELECT * FROM t_$kd where nis='".mysql_real_escape_string($nis)."' and tingkat='".mysql_real_escape_string($kelas)."' ");
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
  
  $query = "SELECT * FROM t_$kd where nis='".mysql_real_escape_string($nis)."' and tingkat='".mysql_real_escape_string($kelas)."' order by tgl_bayar DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	

  $dataspp .= "<form action='user.php' method='get' name='siswa'>
  <input type=hidden name=id value='dataspp'>
  Kelas : <select name='kelas'>";
  $q2 = mysql_query ("select  DISTINCT tingkat,nis from t_$kd where nis='$nis' group by nis,tingkat");
  while($r = mysql_fetch_array($q2)) {
	if ($r[tingkat]==$kelas) $dataspp .= "<option value='$r[tingkat]' selected>$r[tingkat]</option>";
	else $dataspp .= "<option value='$r[tingkat]' >$r[tingkat]</option>";
  }
  if ($kd=='spp') $se1='selected';
  else $se2='selected';
  $dataspp .= "</select>&nbsp;&nbsp; Kategori :  <select name='kd'><option value='spp' $se1>SPP</option><option value='dsp' $se2>DSP</option>
  </select> <input type=submit value=' Pilih ' id=button2 ></form>
  <table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $dataspp .= "<tr><td colspan=5 ><center><a href='user.php?id=dataspp&hal=1&kd=$kd&kelas=$kelas'  title='Hal 1'>First </a> 
  <a href='user.php?id=dataspp&hal=$back&kd=$kd&kelas=$kelas'  title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$dataspp .= "<b><a href='user.php?id=dataspp&hal=$i&kd=$kd&kelas=$kelas'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$dataspp .= "<a href='user.php?id=dataspp&hal=$i&kd=$kd&kelas=$kelas'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $dataspp .= "<a href='user.php?id=dataspp&hal=$next&kd=$kd&kelas=$kelas'  title='$next'> Next</a> 
  <a href='user.php?id=dataspp&hal=$jml&kd=$kd&$kelas=$kelas'  title='Page $jml'> Last</a></font></center></td></tr>";
  }
  if ($kd=='spp') {
  $dataspp .="<tr class='td0' ><td><b>Kode</td><td><b>Tanggal</td><td><b>Bulan Tahun</td>
  <td><b>Iuran</td><td><b>Ket</td></tr>";
  }
  else {
    $dataspp .="<tr  class='td0'><td><b>Kode</td><td><b>Tanggal</td><td><b>DSP</td>
  <td><b>Sisa</td><td><b>Ket</td></tr>";
  }
 
 
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	if ($kd=='spp') {
	$thn="20".substr($row[bulan],0,2);
	$bln=substr($row[bulan],2,2);
	if ($bln=='01') $bln='Januari';
	elseif ($bln=='02') $bln='Februari';
	elseif ($bln=='03') $bln='Maret';
	elseif ($bln=='04') $bln='April';
	elseif ($bln=='05') $bln='Mei';
	elseif ($bln=='06') $bln='Juni';
	elseif ($bln=='07') $bln='Juli';
	elseif ($bln=='08') $bln='Agustus';
	elseif ($bln=='09') $bln='September';
	elseif ($bln=='10') $bln='Oktober';
	elseif ($bln=='11') $bln='November';
	else $bln='Desember';
	if ($row[ket]=='0') $ket='-';
	else $ket='Tunggakan';
   $dataspp .= "<tr class=$warna ><td width='5%'>$row[idspp]</td>
   <td width='15%'>".date("d-m-y",strtotime($row[tgl_bayar]))."</td>
   <td width='15%'>$bln $thn</td>
   <td width='20%'>$row[iuran]</td><td width='20%'>$ket</td></tr>";
   }
   else {
      $dataspp .= "<tr class=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$row[iddsp]</td>
   <td width='15%'>".date("d-m-y",strtotime($row[tgl_bayar]))."</td>
   <td width='20%'>$row[dsp]</td>
   <td width='20%'>$row[sisa]</td><td width='20%'>$ket</td></tr>";
   }
 }        
  $dataspp .= "</table>";
  $dataspp.="</div>";
 
return $dataspp;
}

function guruspp() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak.="<hr style='border: thin solid #6A849D;'>";
$th=$_GET['th'];
$kd=$_GET['kd'];
if ($th=='') $th=$_POST['th'];
if ($kd=='') $kd=$_POST['kd'];

$nip = konversi_id($userid);
$kls = konversi_wali($nip);
$cetak .= ataslogin("Data SPP/DSP - Siswa ");
if ($kd=='') $kd='spp';
if ($th=='') $th='2008';
if ($kls<>'')   {
  $query = "select * from t_siswa where kelas='".mysql_real_escape_string($kls)."'"; 
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
 if($th=='2008') $t1='selected';
elseif($th=='2009') $t2='selected';
elseif($th=='2010') $t3='selected';
elseif($th=='2011') $t4='selected';
elseif($th=='2012') $t5='selected';
elseif($th=='2013') $t6='selected';
elseif($th=='2014') $t7='selected';
elseif($th=='2015') $t8='selected';
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='guruspp'>
  &nbsp;Kelas : $kls&nbsp;&nbsp;&nbsp;";
  $cetak .= " Tahun : <select name='th' >";
  $cetak .='<option value="2008" '.$t1.'>2008</option>
		<option value="2009" '.$t2.'>2009</option>	
		<option value="2010" '.$t3.'>2010</option>	
		<option value="2011" '.$t4.'>2011</option>	
		<option value="2012" '.$t5.'>2012</option>	
		<option value="2013" '.$t6.'>2013</option>	
		<option value="2014" '.$t7.'>2014</option>	
		<option value="2015" '.$t8.'>2015</option></select>	';
  
  if ($kd=='dsp') $k2='selected';
  else $k1='selected';
	$cetak .="&nbsp;&nbsp;Kategori <select name='kd'><option value='spp' $k1>SPP</option>
	<option value='dsp' $k2>DSP</option></select>
	<input type=submit value=' Pilih ' id=button2 ></form>
  <table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>";
  $n=1;
  if ($kd=='spp') {
  $cetak .="<tr class=td0 ><td><b>No</td><td><b>NIS</td><td><b>Nama</td>
  <td><b>1</td><td><b>2</td><td><b>3</td><td><b>4</td><td><b>5</td><td><b>6</td>
  <td><b>7</td><td><b>8</td><td><b>9</td><td><b>10</td><td><b>11</td><td><b>12</td></tr>";
  }
  else {
    $cetak .="<tr class=td0 ><td><b>No</td><td><b>NIS</td><td><b>Nama</td>
  <td><b>DSP</td><td><b>Sisa</td></tr>";
  }
  
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	
	   $cetak .= "<tr class=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$n</td>
   <td width='10%'>$row[user_id]</td>
   <td width='30%'>$row[nama] </td>";
   
	if ($kd=='spp') {
	$s1='';$s2='';$s3='';$s4='';$s5='';$s6='';$s71='';$s8='';$s9='';$s10='';$s11='';$s12='';

	$q=mysql_query("select * from t_spp where nis='$row[user_id]' and left(bulan,2)='".substr($th,2,2)."' order by bulan");
  	while($r=mysql_fetch_array($q)) {
		if (substr($r[bulan],2,2)=='01') $s1='Y';
		elseif (substr($r[bulan],2,2)=='02') $s2='Y';
		elseif (substr($r[bulan],2,2)=='03') $s3='Y';
		elseif (substr($r[bulan],2,2)=='04') $s4='Y';
		elseif (substr($r[bulan],2,2)=='05') $s5='Y';
		elseif (substr($r[bulan],2,2)=='06') $s6='Y';
		elseif (substr($r[bulan],2,2)=='07') $s7='Y';
		elseif (substr($r[bulan],2,2)=='08') $s8='Y';
		elseif (substr($r[bulan],2,2)=='09') $s9='Y';
		elseif (substr($r[bulan],2,2)=='10') $s10='Y';
		elseif (substr($r[bulan],2,2)=='11') $s11='Y';
		elseif (substr($r[bulan],2,2)=='12') $s12='Y';
		
    }
	$cetak .= "<td width='3%'>$thn$s1</td><td width='3%'>$s2</td><td width='3%'>$s3</td><td width='3%'>$s4</td>
	<td width='3%'>$s5</td><td width='3%'>$s6</td><td width='3%'>$s7</td><td width='3%'>$s8</td>
	<td width='3%'>$s9</td><td width='3%'>$s10</td><td width='3%'>$s11</td><td width='3%'>$s12</td></tr>";
	}
	else {
		$q=mysql_query("select * from t_dsp where nis='$row[user_id]' order by iddsp desc");
		$r=mysql_fetch_array($q);
		$cetak .= "<td width='20%'>$r[dsp]</td><td width='15%'>$r[sisa]</td></tr>";
	}
	$n++;
 }        
  $cetak .= "</table><br>s";

 }
 else $cetak .="<br><br><center><b>Maaf Anda Bukan wali kelas";
 
 $cetak .="</div>";
 
return $cetak;
}

function adminspp() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

$th=$_GET['th'];
$kd=$_GET['kd'];
$kls=$_GET['kls'];
$program=$_GET['program'];
if ($th=='') $th=$_POST['th'];
if ($kd=='') $kd=$_POST['kd'];
if ($kls=='') $kls=$_POST['kls'];
if ($program=='') $program=$_POST['program'];

$nip = konversi_id($userid);

$cetak .= ataslogin("Data SPP/DSP - Admin ");
if ($kd=='') $kd='spp';
if ($th=='') $th='2008';
if ($kategori=='Admin') {
  $query = "select * from t_siswa where kelas='".mysql_real_escape_string($kls)."'"; 
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
 if($th=='2008') $t1='selected';
elseif($th=='2009') $t2='selected';
elseif($th=='2010') $t3='selected';
elseif($th=='2011') $t4='selected';
elseif($th=='2012') $t5='selected';
elseif($th=='2013') $t6='selected';
elseif($th=='2014') $t7='selected';
elseif($th=='2015') $t8='selected';
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='adminspp'>";
  $cetak .= " Tahun : <select name='th' >";
  $cetak .='<option value="2008" '.$t1.'>2008</option>
		<option value="2009" '.$t2.'>2009</option>	
		<option value="2010" '.$t3.'>2010</option>	
		<option value="2011" '.$t4.'>2011</option>	
		<option value="2012" '.$t5.'>2012</option>	
		<option value="2013" '.$t6.'>2013</option>	
		<option value="2014" '.$t7.'>2014</option>	
		<option value="2015" '.$t8.'>2015</option></select>	';
  if($program=='') $program='-';
  $data2.= 'Jurusan/Program Keahlian <select name=program onchange="document.location.href=\'user.php?id=adminspp&program=\'+document.guru.program.value">';
  $sql2="select * from t_programahli order by idprog";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[program]==$program) $data2.= "<option value='$al[program]' selected>$al[program]</option>";
  	else $data2.= "<option value='$al[program]' >$al[program]</option>";
  }
  $data2.= "</select> &nbsp;&nbsp;";
  if ($kd=='dsp') $k2='selected';
  else $k1='selected';
	$cetak .="&nbsp;&nbsp;Kategori <select name='kd'><option value='spp' $k1>SPP</option>
	<option value='dsp' $k2>DSP</option></select>&nbsp;&nbsp;$data2 &nbsp;Kelas : <select name='kls'>";
  $q=mysql_query("select * from t_kelas where program='".mysql_real_escape_string($program)."' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	if ($r[kelas]==$kls) $cetak.= "<option value='$r[kelas]' selected>$r[kelas]</option>";
  	else $cetak .= "<option value='$r[kelas]'>$r[kelas]</option>";
  }
  $cetak .= "</select>
	<input type=submit value=' Pilih ' id=button2 ></form>
  <table width='100%' id=tablebaru cellspacing='1' cellpadding='3'>";
  $n=1;
  if ($kd=='spp') {
  $cetak .="<tr class=td0 ><td>No</td><td>NIS</td><td>Nama</td>
  <td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td>
  <td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td></tr>";
  }
  else {
    $cetak .="<tr class=td0 ><td>No</td><td>NIS</td><td>Nama</td>
  <td>DSP</td><td>Sisa</td></tr>";
  }
  
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	
	   $cetak .= "<tr class=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td width='5%'>$n</td>
   <td width='10%'>$row[user_id]</td>
   <td width='30%'>$row[nama] </td>";
   
	if ($kd=='spp') {
	$s1='';$s2='';$s3='';$s4='';$s5='';$s6='';$s71='';$s8='';$s9='';$s10='';$s11='';$s12='';

	$q=mysql_query("select * from t_spp where nis='$row[user_id]' and left(bulan,2)='".substr($th,2,2)."' order by bulan");
  	while($r=mysql_fetch_array($q)) {
		if (substr($r[bulan],2,2)=='01') $s1='Y';
		elseif (substr($r[bulan],2,2)=='02') $s2='Y';
		elseif (substr($r[bulan],2,2)=='03') $s3='Y';
		elseif (substr($r[bulan],2,2)=='04') $s4='Y';
		elseif (substr($r[bulan],2,2)=='05') $s5='Y';
		elseif (substr($r[bulan],2,2)=='06') $s6='Y';
		elseif (substr($r[bulan],2,2)=='07') $s7='Y';
		elseif (substr($r[bulan],2,2)=='08') $s8='Y';
		elseif (substr($r[bulan],2,2)=='09') $s9='Y';
		elseif (substr($r[bulan],2,2)=='10') $s10='Y';
		elseif (substr($r[bulan],2,2)=='11') $s11='Y';
		elseif (substr($r[bulan],2,2)=='12') $s12='Y';
		
    }
	$cetak .= "<td width='3%'>$thn$s1</td><td width='3%'>$s2</td><td width='3%'>$s3</td><td width='3%'>$s4</td>
	<td width='3%'>$s5</td><td width='3%'>$s6</td><td width='3%'>$s7</td><td width='3%'>$s8</td>
	<td width='3%'>$s9</td><td width='3%'>$s10</td><td width='3%'>$s11</td><td width='3%'>$s12</td></tr>";
	}
	else {
		$q=mysql_query("select * from t_dsp where nis='$row[user_id]' order by iddsp desc");
		$r=mysql_fetch_array($q);
		$cetak .= "<td width='20%'>$r[dsp]</td><td width='15%'>$r[sisa]</td></tr>";
	}
	$n++;
 }        
  $cetak .= "</table>";

 }
 else $cetak .="<br><br><center>Maaf Anda tidak diperkenankan mengakses fasilitas ini</center>";
 $cetak .="</div>";
return $cetak;
}

?>