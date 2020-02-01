<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
 //------------------------- fungsi berhubungan dengan sistem informasi
class simclass { 

function datalaporan() {
include "koneksi.php";
$hal=$_GET['hal'];$st=$_GET['st'];
if ($st=='') $st=0;

  $brs=30;
  $kol=10;

  $byk_result=mysql_query("SELECT * FROM t_laporan where status='". mysql_escape_string($st)."'");
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
  
  $query = "SELECT * FROM t_laporan where status='". mysql_escape_string($st)."' order by tgl_kirim DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  if ($st==1) $s2='selected';
  elseif ($st==2) $s3='selected';
  else $s1='selected';
  
  echo "<div align=left><font><center><b>::  Data Pelaporan Guru  ::</b> </center></font>
  <form action='admin.php?' method='get' name='guru'><font>
  <input type=hidden name=mode value='datalaporan'>&nbsp;::&nbsp; Status Laporan <select name='st'>
  <option value='0' $s1>Pending</option><option value='1' $s2>Disetujui</option><option value='2' $s3>Diperbaiki</option></select>&nbsp;&nbsp;<input type=submit value='Pilih' >
  <br></form><form action='admin.php' method=\"post\" name='lapor' >
  <table width='100%' border='1' cellspacing='1' cellpadding='2'>";
  if ($jml!=0) {
  echo "<tr><td colspan=6 ><center><font ><a href='admin.php?mode=datalaporan&hal=1&st=$st' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='admin.php?mode=datalaporan&hal=$back&st=$st' style='color:000000;text-decoration:none' title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	echo"<b><a href='admin.php?mode=datalaporan&hal=$i&st=$st' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=datalaporan&hal=$i&st=$st' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=datalaporan&hal=$next&st=$st' style='color:000000;text-decoration:none' title='$next'> Next</a> 
  <a href='admin.php?mode=datalaporan&hal=$jml&st=$st' style='color:000000;text-decoration:none' title='Page $jml'> Last</a></font></center></td></tr>";
  }
    echo"<tr bgcolor='#2492AB'><td><font><b>No</td><td><font><b>Tgl Kirim</td><td><font><b>Pengirim</td><td><font><b>Judul Laporan</td><td><font><b>File</td><td><font><b>Hapus</td></tr>";
 
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.lapor.elements.length;i++) {
     var e = document.lapor.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "#ddedea";
	if ($x==1) {
	$warna = "#2492AB";
	$x=0; }
	else $x=1;
	$isi = substr($row[isi],0,30);
	if($row[status]=='1') $st = "Disetujui"; 
	elseif($row[status]=='2') $st ="Diperbaiki";
	else $st = "Pending";
	 $guru='';
	 $q=mysql_query("select * from t_staf where nip='$row[nip]'");
  	$r=mysql_fetch_array($q);
	$guru = $r[nama];
   echo "<tr bgcolor=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td width='5%'><font>$j</td>
   <td width='10%'><font>".date("d-m-Y",strtotime($row[tgl_kirim]))."</td>
   <td width='15%'><font>$guru</td>
   <td width='40%'><font>$row[judul]</td>
   <td width='5%'><font><a href='../laporan/$row[file]' title='klik untuk download data'>$row[file]</a></td>
   <td width='5%'><center><input  title='Hapus data laporan $row[judul] dari $guru' type='checkbox' name='kd[$row[idlap]]' value='on'></td></tr>";
	$j++;
 }        
  echo "</table><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"mode\" value=\"datalaporhapus\">
                <input type=\"submit\" value=\"Hapus\"></form>";

 

}
function datalaporhapus() {
include "koneksi.php";
$kd=$_POST['kd'];
if (!empty($kd))
{
   while (list($key,$value)=each($kd)) {
	$sql="select * from t_laporan where idlap='". mysql_escape_string($key)."'";
	$result=mysql_query($sql) or die ("Penghapusan gagal 1");
	$row =mysql_fetch_array($result);
		$sql="delete from t_laporan where idlap='". mysql_escape_string($key)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
		$file = "../laporan/$row[file]";
		if (file_exists($file)) {
			unlink($file);
		}
	}
	    
 }
} 

function datatugas() {
include "koneksi.php";
$hal=$_GET['hal'];$sem=$_GET['sem'];$pel=$_GET['pel'];
$kelas=$_GET['kelas'];$program=$_GET['program'];
  $brs=30;
  $kol=10;
if($program=='') $program='-';
$byk_result=mysql_query("SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas and t_tugas.sem='". mysql_escape_string($sem)."' and t_tugas.pelajaran='". mysql_escape_string($pel)."' and t_tugas_kelas.kelas='". mysql_escape_string($kelas)."'");
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
  
  $query = "SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas and t_tugas.sem='". mysql_escape_string($sem)."' and t_tugas.pelajaran='". mysql_escape_string($pel)."' and t_tugas_kelas.kelas='". mysql_escape_string($kelas)."' order by t_tugas.tgl_kirim DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      $jur .= '<br/>Jurusan/Program Keahlian <select name="program" onchange="document.location.href=\'admin.php?mode=datatugas&program=\'+document.guru.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) $jur .= "<option value='$al[program]' selected>$al[program]</option>";
      	else $jur .= "<option value='$al[program]' >$al[program]</option>";
      }
      $jur .= "</select><br> &nbsp;&nbsp;";	
  }
  else $jur .= "<input type=hidden name=program value='-'/>";
  
    $data .="<select name=kelas >";
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>';
  	$data2 .="<select name=pel >";
	$sql="select * from t_pelajaran where program='". mysql_escape_string($program)."' or program='-'";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 pel ");
	while($row=mysql_fetch_array($q)) {
		if ($pel==$row[pel]) $data2 .="<option value='$row[pel]' selected>$row[pel]</option>";
		else $data2 .="<option value='$row[pel]'>$row[pel]</option>";
	}
	$data2 .='</select>';
  	$data3 .="<select name=sem >";
	$sql="select * from t_semester";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 pel ");
	while($row=mysql_fetch_array($q)) {
		if ($semester==$row[semester]) $data3 .="<option value='$row[semester]' selected>$row[semester]</option>";
		else $data3 .="<option value='$row[semester]'>$row[semester]</option>";
	}
	$data3 .='</select>';
  echo "<center><b><font>:: Data Materi Ajar</font></b></center><div align='left'><form action='admin.php?' method='get' name='guru'><input type=hidden name=mode value='datatugas'><font>
  $jur<br>&nbsp;&nbsp;&nbsp;&nbsp;
  Semester :  $data3 &nbsp;&nbsp;Kelas : $data &nbsp;&nbsp; Pelajaran : $data2<input type=submit value=' Pilih ' ></form></div>
  <form action='admin.php' method=\"post\" name='nilai' >
  <table width='100%' border='1' cellspacing='1' cellpadding='2'>";
  if ($jml!=0) {
  echo"<tr><td colspan=7 ><center><font ><a href='admin.php?mode=datatugas&hal=1&sem=$sem&pel=$pel&kelas=$kelas' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='admin.php?mode=datatugas&hal=$back&sem=$sem&pel=$pel&kelas=$kelas&program=$program' style='color:000000;text-decoration:none' title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	echo "<b><a href='admin.php?mode=datatugas&hal=$i&sem=$sem&pel=$pel&kelas=$kelas&program=$program' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=datatugas&hal=$i&sem=$sem&pel=$pel&kelas=$kelas&program=$program' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=datatugas&hal=$next&sem=$sem&pel=$pel&kelas=$kelas&program=$program' style='color:000000;text-decoration:none' title='$next'> Next</a> 
  <a href='admin.php?mode=datatugas&hal=$jml&sem=$sem&pel=$pel&kelas=$kelas&program=$program' style='color:000000;text-decoration:none' title='Page $jml'> Last</a></font></center></td></tr>";
  }
    echo"<tr bgcolor='#2492AB'><td><font><b>No</td><td><font><b>Tgl Kirim</td><td><font><b>Guru</td><td><font><b>Kelas</td><td><font><b>Jenis</td><td><font><b>Keterangan</td><td><font><b>Hap</td></tr>";
 
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.nilai.elements.length;i++) {
     var e = document.nilai.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "#ddedea";
	if ($x==1) {
	$warna = "#2492AB";
	$x=0; }
	else $x=1;

	if($row[jenis]=='0') { $jenis = "Materi";  }
	else {
		$jenis = "Tugas";
	}
	$sql="select * from t_staf where nip='$row[nip]'";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 pel ");
	$r=mysql_fetch_array($q);
	$nmguru =$r[nama];
    echo "<tr bgcolor=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td width='3%' valign=top><font>$j</td>
   <td width='8%' valign=top><font>".date("d-m-Y",strtotime($row[tgl_kirim]))."</td>
   <td width='20%' valign=top><font>$nmguru</td>
   <td width='10%' valign=top><font>$row[kelas]</td>
   <td width='5%' valign=top><font>$jenis</td>
   <td width='40%' valign=top><font>$row[isi]</td><td width='3%' valign=top><center>
   <input  title='Hapus Data Materi Ajar $pel dari $nmguru' type='checkbox' name='kls[$row[idkls]]' value='on'></td></tr>";
	$j++;
  }        
  echo "</table><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"mode\" value=\"datatughapus\">
                <input type=\"submit\" value=\"Hapus\"></form>";
}

function datatughapus() {
include "koneksi.php";
$kls=$_POST['kls'];

if (!empty($kls)) {
	while (list($key,$value)=each($kls)) {
	 $kd='';
	 $sql1="select idtugas from t_tugas_kelas where idkls='". mysql_escape_string($key)."'";
	 $result=mysql_query($sql1);
	 $row=mysql_fetch_array($result);
	 $kd=$row[idtugas];
	
	$sql="delete from t_tugas_kelas where idkls='". mysql_escape_string($key)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
	$sql="select * from t_tugas_kelas where idtugas='$kd'";
	$result=mysql_query($sql) or die ("Penghapusan gagal 2");
	$row =mysql_num_rows($result);
	if ($row==0) {
		$sql="delete from t_tugas where idtugas='$kd'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 3");
		$file = "../materi/file$kd.doc";
		if (file_exists($file)) {
			unlink($file);
		}
		$sql="select * from t_tugas_siswa where idtugas='$kd'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
		while($rows=mysql_fetch_array($mysql_result)) {
			$file = "../tugas/$rows[file]";
				if (file_exists($file)) {
				unlink($file);
			}
		}
		$sql="delete from t_tugas_siswa where idtugas='$kd'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");		
	}

   }
	    
 }
} 


function datanilai() {
include "koneksi.php";
$hal=$_GET['hal'];$pel=$_GET['pel'];$sem=$_GET['sem'];$kelas=$_GET['kelas'];$program=$_GET['program'];$thajar=$_GET['thajar'];
if ($sem=='') $sem='1';
if($program=='') $program='-';
  $brs=30;
  $kol=10;
  $byk_result=mysql_query("SELECT * FROM t_nilai where pelajaran='". mysql_escape_string($pel)."' and left(kd_nilai,4)='".$thajar."' and semester='". mysql_escape_string($sem)."' and kelas='". mysql_escape_string($kelas)."' ");
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
  
  $query = "SELECT * FROM t_nilai where pelajaran='". mysql_escape_string($pel)."' and left(kd_nilai,4)='".$thajar."' and semester='". mysql_escape_string($sem)."' and kelas='". mysql_escape_string($kelas)."' order by tgl_ujian DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // tambah alan untuk delete multiple	

  echo "<center><font><b>:: Data Nilai Siswa :: </b></font></center>
  <div align='left'><font><form action='admin.php?' method='get' name='guru'>
  <input type=hidden name=mode value='datanilai'><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      echo 'Jurusan/Program Keahlian <select name="program" onchange="document.location.href=\'admin.php?mode=datanilai&program=\'+document.guru.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      	else echo "<option value='$al[program]' >$al[program]</option>";
      }
      echo "</select>";
  }
  else echo "<input type=hidden name=program value='-'/>";
  
  echo "&nbsp;&nbsp;&nbsp;&nbsp;Tahun Pelajaran : <select name='thajar' >";	
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
    
  	if (substr($al['thajar'],2,2).substr($al['thajar'],7,2)==$thajar) echo "<option value='".substr($al['thajar'],2,2).substr($al['thajar'],7,2)."' selected>$al[thajar]</option>";
  	else echo "<option value='".substr($al['thajar'],2,2).substr($al['thajar'],7,2)."' >$al[thajar]</option>";
  }
  echo "</select><br> &nbsp;&nbsp;&nbsp;&nbsp;";  
  echo "Pelajaran : <select name='pel'>";
  $q2 = mysql_query ("select pel from t_pelajaran where program='". mysql_escape_string($program)."' or program='-' order by program,pel");
  while($r = mysql_fetch_array($q2)) {
	if ($r[pel]==$pel) echo "<option value='$r[pel]' selected>$r[pel]</option>";
	else echo"<option value='$r[pel]' >$r[pel]</option>";
  }
  if ($sem=='2') $se2='selected';
  else $se1='selected';
      $data .="<select name=kelas >";
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>';
      $data3 .="<select name='sem' >";
	$sql="select * from t_semester";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($sem==$row[semester]) $data3 .="<option value='$row[semester]' selected>$row[semester]</option>";
		else $data3 .="<option value='$row[semester]'>$row[semester]</option>";
	}
	$data3.='</select>';
  echo "</select>&nbsp;&nbsp; Semester :  $data3 &nbsp; Kelas : $data&nbsp;&nbsp; <input type=submit value=' Pilih ' ></form></div><br><form action='admin.php' method=\"post\" name='nilai' >
  <table width='100%' border='1' cellspacing='1' cellpadding='2'>";
  if ($jml!=0) {
  echo  "<tr><td colspan=7  ><center><font class='ver10'><a href='admin.php?mode=datanilai&kelas=$kelas&hal=1&pel=$pel&sem=$sem&program=$program' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='admin.php?mode=datanilai&kelas=$kelas&hal=$back&pel=$pel&sem=$sem&program=$program' style='color:000000;text-decoration:none' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	echo "<b><a href='admin.php?mode=datanilai&kelas=$kelas&hal=$i&pel=$pel&sem=$sem&program=$program' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=datanilai&kelas=$kelas&hal=$i&pel=$pel&sem=$sem&program=$program' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=datanilai&kelas=$kelas&hal=$next&pel=$pel&sem=$sem&program=$program' style='color:000000;text-decoration:none' title='$next'> Next</a> 
  <a href='admin.php?mode=datanilai&kelas=$kelas&hal=$jml&pel=$pel&sem=$sem&program=$program' style='color:000000;text-decoration:none' title='Page $jml'> Last</a></font></center></td></tr>";
  }
  echo"<tr bgcolor='#2492AB'><td><font><b>Kode</td><td><font><b>Tanggal</td>
  <td><font><b>U Ke</td><td><font><b>Jenis</td><td><font><b>KKM</td><td><font><b>Ket</td><td><font><b>Hap</td></tr>";
    echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.nilai.elements.length;i++) {
     var e = document.nilai.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "#2492AB";
	if ($j==1) {
	$warna = "#ddedea";
	$j=0; }
	else $j=1;
	if ($row[status]=='0') $jenis='U.Harian'; 
	elseif ($row[status]=='1') $jenis='Tgs.Kognitif'; 
	elseif ($row[status]=='2') $jenis='Remedial'; 
	elseif ($row[status]=='3') $jenis='Tugas'; 
	elseif ($row[status]=='4') $jenis='Praktikum'; 
	elseif ($row[status]=='5') $jenis='U.Umum'; 
	else $jenis='Lain-lain'; 
	
   echo"<tr bgcolor=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td width='5%'><font>$row[kd_nilai]</td>
   <td width='15%'><font>".date("d-m-Y",strtotime($row[tgl_ujian]))."</td>
   <td width='5%'><font>$row[ujian_ke]</td>
   <td width='15%'><font>$jenis</td><td width='10%'><font>$row[skbm]</td><td width='30%'><font>$row[ket]</td><td width='5%'><center>
   <input  title='Hapus data nilai Pelajaran $pel' type='checkbox' name='kd[$row[kd_nilai]]' value='on'></td></tr>";
 }        
  echo "</table>
  <font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"mode\" value=\"datanilhapus\"><input type=\"submit\" value=\"Hapus\"></form>";


}
function datanilhapus() {
include "koneksi.php";
$kd=$_POST['kd'];
if (!empty($kd)) {
	while (list($key,$value)=each($kd)) {
		$sql="delete from t_nilai_detail where kd_nilai='". mysql_escape_string($key)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
		$sql="delete from t_nilai where kd_nilai='". mysql_escape_string($key)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
	}
	    
  }
} 


function dataspp() {
include "koneksi.php";
$th=$_GET['th'];$kd=$_GET['kd'];$kls=$_GET['kls'];$program=$_GET['program'];
if ($kd=='') $kd='spp';
if ($th=='') $th='2008';

  $query = "select * from t_siswa where kelas='". mysql_escape_string($kls)."' order by nama"; 
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
  echo "<font><center><b>:: Data SPP/DSP ::</b></center><div align='left'><form action='admin.php?' method='get' name='guru'>
  <input type=hidden name=mode value='dataspp'><font>
  <br>&nbsp;&nbsp;";
  echo " Tahun : <select name='th' >";
  echo'<option value="2008" '.$t1.'>2008</option>
		<option value="2009" '.$t2.'>2009</option>	
		<option value="2010" '.$t3.'>2010</option>	
		<option value="2011" '.$t4.'>2011</option>	
		<option value="2012" '.$t5.'>2012</option>	
		<option value="2013" '.$t6.'>2013</option>	
		<option value="2014" '.$t7.'>2014</option>	
		<option value="2015" '.$t8.'>2015</option></select>	';
  
  if ($kd=='dsp') $k2='selected';
  else $k1='selected';
   if($program=='') $program='-';
  if ($cmstingkat=='sma' or $cmstingkat=='smk') { 
      $data2.= 'Jurusan/Program Keahlian <select name=program onchange="document.location.href=\'admin.php?mode=dataspp&program=\'+document.guru.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) $data2.= "<option value='$al[program]' selected>$al[program]</option>";
      	else $data2.= "<option value='$al[program]' >$al[program]</option>";
      }
      $data2.= "</select> &nbsp;&nbsp;";
      }
  else $data2.= "<input type=hidden name=program value='-'/>";
  
	echo"<font>&nbsp;&nbsp;Kategori <select name='kd'><option value='spp' $k1>SPP</option>
	<option value='dsp' $k2>DSP</option></select>&nbsp;<br>&nbsp;&nbsp;&nbsp;$data2 Kelas : <select name='kls'>";
  $q=mysql_query("select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	if ($r[kelas]==$kls) echo "<option value='$r[kelas]' selected>$r[kelas]</option>";
  	else echo "<option value='$r[kelas]'>$r[kelas]</option>";
  }
  if ($kd=='spp') $jum=$jum_spp;
  else $jum=$jum_dsp;
  echo "</select>
	<input type=submit value=' Pilih ' ></form></div><form action='admin.php' method=post ><font>Klik Simpan untuk menyimpan data apabila ada perubahan dengan Jumlah $kd <input type=text name=jumspp value='$jum' size=10 > &nbsp;<input type=submit value=' Simpan ' ><br>
  <table width='100%' border='1' cellspacing='1' cellpadding='2'>";
  $n=1;
  if ($kd=='spp') {
  echo"<tr bgcolor='#A0BB77'><td><font><b>No</td><td><font><b>NIS</td><td><font><b>Nama</td>
  <td bgcolor='dede55'><font><b>1</td><td><font><b>2</td><td bgcolor='dede55'><font><b>3</td><td><font><b>4</td><td bgcolor='dede55'><font><b>5</td><td><font><b>6</td> <td bgcolor='dede55'><font><b>7</td><td><font><b>8</td><td bgcolor='dede55'><font><b>9</td><td><font><b>10</td><td bgcolor='dede55'><font><b>11</td><td><font><b>12</td></tr>";
  }
  else {
    echo"<tr bgcolor='#A0BB77'><td><font><b>No</td><td><font><b>NIS</td><td><font><b>Nama</td>
  <td><font><b>DSP</td><td><font><b>Sisa</td></tr>";
  }
  
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "#ddedea";
	if ($j==1) {
	$warna = "#2492AB";
	$j=0; }
	else $j=1;
	$nis = $row[user_id];
	   echo "<tr bgcolor=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td width='5%'><font>$n</td>
   <td width='10%'><font>$row[user_id]</td>
   <td width='30%'><font>$row[nama] </td>";
   
	if ($kd=='spp') {
	$s1="<input type=hidden name='b1[$nis]' value='0'><input type='checkbox' name='b1[$nis]' value='1' />";
	$s2="<input type=hidden name='b2[$nis]' value='0'><input type='checkbox' name='b2[$nis]' value='1' />";
	$s3="<input type=hidden name='b3[$nis]' value='0'><input type='checkbox' name='b3[$nis]' value='1' />";
	$s4="<input type=hidden name='b4[$nis]' value='0'><input type='checkbox' name='b4[$nis]' value='1' />";
	$s5="<input type=hidden name='b5[$nis]' value='0'><input type='checkbox' name='b5[$nis]' value='1' />";
	$s6="<input type=hidden name='b6[$nis]' value='0'><input type='checkbox' name='b6[$nis]' value='1' />";
	$s7="<input type=hidden name='b7[$nis]' value='0'><input type='checkbox' name='b7[$nis]' value='1'/>";
	$s8="<input type=hidden name='b8[$nis]' value='0'><input type='checkbox' name='b8[$nis]' value='1' />";
	$s9="<input type=hidden name='b9[$nis]' value='0'><input type='checkbox' name='b9[$nis]' value='1' />";
	$s10="<input type=hidden name='b10[$nis]' value='0'><input type='checkbox' name='b10[$nis]' value='1' />";
	$s11="<input type=hidden name='b11[$nis]' value='0'><input type='checkbox' name='b11[$nis]' value='1' />";
	$s12="<input type=hidden name='b12[$nis]' value='0'><input type='checkbox' name='b12[$nis]' value='1' />";
	
	$q=mysql_query("select * from t_spp where nis='$nis' and left(bulan,2)='".substr($th,2,2)."' order by bulan");
  	while($r=mysql_fetch_array($q)) {
		if (substr($r[bulan],2,2)=='01') $s1="<input type=hidden name='b1[$nis]' value='0'><input type='checkbox' name='b1[$nis]' value='1' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='02') $s2="<input type=hidden name='b2[$nis]' value='0'><input type='checkbox' name='b2[$nis]' value='1' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='03') $s3="<input type=hidden name='b3[$nis]' value='0'><input type='checkbox' name='b3[$nis]' value='1' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='04') $s4="<input type=hidden name='b4[$nis]' value='0'><input type='checkbox' name='b4[$nis]' value='1' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='05') $s5="<input type=hidden name='b5[$nis]' value='0'><input type='checkbox' name='b5[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='06') $s6="<input type=hidden name='b6[$nis]' value='0'><input type='checkbox' name='b6[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='07') $s7="<input type=hidden name='b7[$nis]' value='0'><input type='checkbox' name='b7[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='08') $s8="<input type=hidden name='b8[$nis]' value='0'><input type='checkbox' name='b8[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='09') $s9="<input type=hidden name='b9[$nis]' value='0'><input type='checkbox' name='b9[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='10') $s10="<input type=hidden name='b10[$nis]' value='0'><input type='checkbox' name='b10[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='11') $s11="<input type=hidden name='b11[$nis]' value='0'><input type='checkbox' name='b11[$nis]' value='0' checked='checked' />";
		elseif (substr($r[bulan],2,2)=='12') $s12="<input type=hidden name='b12[$nis]' value='0'><input type='checkbox' name='b12[$nis]' value='0' checked='checked' />";
		
    }
	echo "<td width='3%' bgcolor='dede55'><font>$s1</td><td width='3%'><font>$s2</td><td width='3%' bgcolor='dede55'><font>$s3</td><td width='3%'><font>$s4</td><td width='3%' bgcolor='dede55'><font>$s5</td><td width='3%'><font>$s6</td><td width='3%' bgcolor='dede55'><font>$s7</td><td width='3%'><font>$s8</td><td width='3%' bgcolor='dede55'><font>$s9</td><td width='3%'><font>$s10</td><td width='3%' bgcolor='dede55'><font>$s11</td><td width='3%'><font>$s12</td></tr>";
	}
	else {
		$q=mysql_query("select * from t_dsp where nis='$nis' order by iddsp desc");
		$r=mysql_fetch_array($q);
		echo"<td width='20%'><font><input type=text name='totdsp[$nis]' value='$r[dsp]' size=10 ></td>
		<td width='15%'><input type=text name='sisadsp' value='$r[sisa]' size=10 ></td></tr>";
	}
	$n++;
 }        
  echo "</table><font>Klik Simpan untuk menyimpan data apabila ada perubahan<input type=submit value=' Simpan ' ><input type=hidden name=mode value=dataspp_save ><input type=hidden name='kd' value='$kd' ><input type=hidden name='th' value='$th' >
  <input type=hidden name='kelas' value='$kls' ></form><br>";


}

function dataspp_save() {
include "koneksi.php";
$b1=$_POST['b1'];$b2=$_POST['b2'];$b3=$_POST['b3'];$b4=$_POST['b4'];$b5=$_POST['b5'];
$b6=$_POST['b6'];$b7=$_POST['b7'];$b8=$_POST['b8'];$b9=$_POST['b9'];$b10=$_POST['b10'];
$b11=$_POST['b11'];$b12=$_POST['b12'];$th=$_POST['th'];$kd=$_POST['kd'];$kelas=$_POST['kelas'];
$jumspp=$_POST['jumspp'];$totdsp=$_POST['totdsp'];
	$tgl=date("Y-m-d");
  if($kd=='spp') {
	while (list($nis,$value)=each($b1)) {
 		$bulan = substr($th,2,2)."01";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','". mysql_escape_string($tgl)."','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 1
	while (list($nis,$value)=each($b2)) {
 		$bulan = substr($th,2,2)."02";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}// bulan 2
	while (list($nis,$value)=each($b3)) {
 		$bulan = substr($th,2,2)."03";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."'  ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."'  ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 3
	while (list($nis,$value)=each($b4)) {
 		$bulan = substr($th,2,2)."04";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 4
	while (list($nis,$value)=each($b5)) {
 		$bulan = substr($th,2,2)."05";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 5
	while (list($nis,$value)=each($b6)) {
 		$bulan = substr($th,2,2)."06";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 6
	while (list($nis,$value)=each($b7)) {
 		$bulan = substr($th,2,2)."07";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 7
	while (list($nis,$value)=each($b8)) {
 		$bulan = substr($th,2,2)."08";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 8
	while (list($nis,$value)=each($b9)) {
 		$bulan = substr($th,2,2)."09";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 9
	while (list($nis,$value)=each($b10)) {
 		$bulan = substr($th,2,2)."10";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values (''". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 10
	while (list($nis,$value)=each($b11)) {
 		$bulan = substr($th,2,2)."11";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 11
	while (list($nis,$value)=each($b12)) {
 		$bulan = substr($th,2,2)."12";
		$sql="select * from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
			if ($value=='0') {
			$sql2="delete from t_spp where nis='". mysql_escape_string($nis)."' and bulan='". mysql_escape_string($bulan)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");
			//echo "$nis = ada hapus $value<br>";
			}
		}
		else {
			if ($value=='1') {
			$sql2="insert into t_spp (nis,tgl_bayar,bulan,tingkat,iuran,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($bulan)."','". mysql_escape_string($kelas)."','". mysql_escape_string($jumspp)."','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
			//echo "$nis = tambah $value<br>";
			}
		} 
	}//bulan 12
  } //spp
  else {
	$totdsp = (array) $totdsp;
	reset($totdsp);
  	while (list($nis,$value)=each($totdsp)) {
		$sisa = $jumspp-$value;
  		$sql="select * from t_dsp where nis='". mysql_escape_string($nis)."' ";
		$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		$r=mysql_num_rows($mysql_result);
		if($r>=1) {
  			$sql2="update t_dsp set tgl_bayar='$tgl',tingkat='". mysql_escape_string($kelas)."',dsp='". mysql_escape_string($value)."',sisa='$sisa' where nis='". mysql_escape_string($nis)."' ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");			
		}
		else {
  			$sql2="insert into t_dsp (nis,tgl_bayar,tingkat,dsp,sisa,tu) values ('". mysql_escape_string($nis)."','$tgl','". mysql_escape_string($kelas)."','". mysql_escape_string($value)."','$sisa','-') ";
			$mysql_result=mysql_query($sql2) or die ("Query failed - Mysql");	
		}
	}
  } //dsp

	echo "<font>Perubahan Data SPP/DSP berhasil dilakukan ";

}

function databsen() {
  include "koneksi.php";
  $program=$_GET['program'];
  $kelas=$_GET['kelas'];
  $bulan=$_GET['bulan'];
  $thn=$_GET['thn'];
  $hal=$_GET['hal'];
  
  if($program=='') 	$program='-';
  
  		
  $query = "SELECT user_id,nama,kelas from t_siswa where kelas='". mysql_escape_string($kelas)."' order by nama "; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	

  $data .='<select name=kelas onchange="document.location.href=\'admin.php?mode=databsen&kelas=\'+document.siswa.kelas.value+\'&program=\'+document.siswa.program.value">';
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
  echo "<form action='admin.php' method='get' name='siswa' ><table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% > 
  <tr><td bgcolor='#999999' ><font><center>--- Daftar Absensi Siswa---</center><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      echo 'Jurusan/Program Keahlian 
      <select name="program" onchange="document.location.href=\'admin.php?mode=databsen&program=\'+document.siswa.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      	else echo "<option value='$al[program]' >$al[program]</option>";
      }
      echo "</select> &nbsp;&nbsp;";
  }
  else echo "<input type='hidden' name='program' value='-' />";
  
 if($bulan=='02') $s2='selected';
 elseif($bulan=='03') $s3='selected';
 elseif($bulan=='04') $s4='selected';
 elseif($bulan=='05') $s5='selected';
 elseif($bulan=='06') $s6='selected';
 elseif($bulan=='07') $s7='selected';
 elseif($bulan=='08') $s8='selected';
 elseif($bulan=='09') $s9='selected';
 elseif($bulan=='10') $s10='selected';
 elseif($bulan=='11') $s11='selected';
 elseif($bulan=='12') $s12='selected';
 else $s1='selected';

 if ($thn=='2011') $t2='selected';
 elseif ($thn=='2012') $t3='selected';
 elseif ($thn=='2013') $t4='selected';
 elseif ($thn=='2014') $t5='selected';
 elseif ($thn=='2015') $t6='selected';
 elseif ($thn=='2016') $t7='selected';
 else 	$t1='selected';
 
  echo " Kelas $data</font>&nbsp;&nbsp;
  <select name=bulan ><option value='01' $s1 >Januari</option><option value='02' $s2 >Februari</option><option value='03' $s3 >Maret</option>
    <option value='04' $s4 >April</option><option value='05' $s5 >Mei</option><option value='06' $s6 >Juni</option><option value='07' $s7 >Juli</option>
    <option value='08' $s8 >Agustus</option><option value='09' $s9 >September</option><option value='10' $s10 >Oktober</option>
    <option value='11' $s11 >November</option><option value='12' $s12 >Desember</option></select> 
     <select name=thn ><option value='2010' $t1 >2010</option><option value='2011' $t2 >2011</option><option value='2012' $t3 >2012</option>
    <option value='2013' $t3 >2013</option><option value='2014' $t4 >2014</option><option value='2015' $t5 >2015</option>
    <option value='2016' $t6 >2016</option>
    </select>
    <input type='submit' value=' Pilih ' ><input type=hidden name=mode value=databsen > 
    <a href='admin.php?mode=importabsen'>Import Absensi</a></td></tr>";

  echo "</table></form>
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >";
  echo "<tr><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Hadir</center></font></td>
  <td><font><center>Sakit</center></font></td><td><font><center>Izin</center></font></td>
  <td><font><center>Alpa</center></font></td><td><font><center>Terlambat</center></font></td>
  <td><font><center>Detail</center></font></td></tr>";


$j=1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
    echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[user_id]</font></td>
	<td width='40%' ><font>$row[nama]</font></td>";
    $hadir=0;$sakit=0;$alpa=0;$izin=0;$lambat=0;
     $query = mysql_query("select count(stabsen) as jum,stabsen from t_absensi where nis='".$row['user_id']."' and month(tglabsen)='$bulan' and year(tglabsen)='$thn' 
     and stabsen in ('','S','T','A','I') group by stabsen ");
     while($r=mysql_fetch_array($query)) {
        if ($r['stabsen']=='')  $hadir =$r['jum'];
        if ($r['stabsen']=='S') $sakit =$r['jum'];
        if ($r['stabsen']=='A') $alpa  =$r['jum'];
        if ($r['stabsen']=='I') $izin  =$r['jum'];
        if ($r['stabsen']=='T') $lambat=$r['jum'];

     }
     echo "<td width='5%' ><center>$hadir</center></td><td width='5%' ><center>$sakit</center></td>
     <td width='5%' ><center>$izin</center></td><td width='5%' ><center>$alpa</center></td><td width='5%' ><center>$lambat</center></td>";
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=detailabsen&id=<?php echo $row['user_id']."&bln=".$bulan."&thn=".$thn; ?>"><img src="../images/edit.gif" border="0" /></a></td> 
  </tr>
  <?php
  }  
  echo "</table> ";
  
}

function detailabsen() {
   $bln = $_GET['bln'];
   $thn = $_GET['thn'];
   $id  = $_GET['id'];
    $query = mysql_query("select nama from t_siswa where user_id='".mysql_escape_string($id)."'");
    $r= mysql_fetch_array($query);
    $nama = $r['nama'];
    
   
   $query = mysql_query("select * from t_absensi where nis='".mysql_escape_string($id)."' and 
   month(tglabsen)='".mysql_escape_string($bln)."' and year(tglabsen)='".mysql_escape_string($thn)."' order by tglabsen");
   echo "<table  cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100%  >
   <form action='admin.php' method=post >
   <tr><td colspan=4 ><b>Data Detail Absensi Siswa</b><br>
   Nama : $nama <br/>Bulan : $bln <br>Tahun : $thn</td></tr>";
   echo "<tr><td><b>No</b></td><td><b>Tanggal</b></td><td><b>Status</b></td><td><b>Terlambat</b></td></tr>";
   $i=1;
   while ($row= mysql_fetch_array($query)) {
    $s1='';$s2='';$s3='';$s4='';$s5='';
    if ($row['stabsen']=='') $s1='selected';
    elseif ($row['stabsen']=='S') $s2='selected';
    elseif ($row['stabsen']=='I') $s3='selected';
    elseif ($row['stabsen']=='A') $s4='selected';
    elseif ($row['stabsen']=='T') $s5='selected';
    
    echo "<tr><td>$i</td><td>".date("d-m-Y",strtotime($row['tglabsen']))."</td><td><select name='stabsen[".$row['idabsen']."]' >
    <option value='' $s1 >Hadir</option><option value='S' $s2 >Sakit</option><option value='I' $s3 >Izin</option>
    <option value='A' $s4 >Alpa</option><option value='T' $s5 >Terlambat</option></select></td><td><input type=text name='lambat[".$row['idabsen']."]' value='".$row['terlambat']."'/></td></tr>";
    $i++;
   }
   echo "<tr><td colspan=4 ><input type=submit value='Simpan' /><input type='hidden' name='mode' value='saveabsen' />
   <input type='hidden' name='nis' value='$id' />
   </td></tr></form></table>";
   
}

function saveabsen() {
    echo "<h2>Konfirmasi perubahan data absensi</h2>";

    $ket = $_POST['lambat'];
   
    while (list($key,$value)=each($_POST['stabsen']))	{
        //echo $key."-".$value."=".$ket[$key]."<br>";
        $sql="update t_absensi set stabsen='".mysql_escape_string($value)."',terlambat='".mysql_escape_string($ket[$key])."' 
        where idabsen='".mysql_escape_string($key)."' and nis ='".mysql_escape_string($_POST['nis'])."'";
        $mysql_result=mysql_query($sql);
    }
    echo "Perubahan berhasil dilakukan.<br/><br/>| <a href='admin.php?mode=databsen' >Lihat Data Absensi</a> |";
    
}

function importabsen() {
     include "koneksi.php";
  $kelas=$_GET['kelas'];
  $program=$_GET['program'];
  
  if($program=='')  $program='-';
  
	$data .='<select name=kelas onchange="document.location.href=\'admin.php?mode=importabsen&kelas=\'+document.siswa.kelas.value+\'&program=\'+document.siswa.program.value">';
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas";
    $data .="<option value=''>Semua</option>";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
 
 echo "<table width=98% ><tr bgcolor=dedede><td><font><b>Membuat Format Data Absensi Siswa <b></td></tr>
 <tr><td><font><form action='../functions/fungsi_excelabsensi.php' method='post' name='siswa' >Pilih Format Data Absensi <br/>";
 if ($cmstingkat=='sma' or $cmstingkat=='smk') {
 echo 'Jurusan/Program Keahlian  <select name="program" onchange="document.location.href=\'admin.php?mode=importabsen&program=\'+document.siswa.program.value">';
  $sql2="select * from t_programahli order by idprog";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
  	else echo "<option value='$al[program]' >$al[program]</option>";
  }
  echo "</select>";
 }
 else echo "<input type=hidden name=program value='-'/>";
  
 echo "  Kelas $data
 <input type=submit value='Format'> 
 <input type=hidden name=format value='siswa' ></form><br><br>
 <font><b>File berupa format Excel dengan nama SHEET harus FormatAbsensi</b></font></td></tr>
 </table>
 <form action='../functions/importabsensi.php' method='post' enctype=\"multipart/form-data\"'>
 <table border=0 width=98% >
 <tr bgcolor=dedede><td colspan=2 ><font><b>Import Data Absensi Siswa</td></tr>
 <tr><td width='20%'>Bulan/Tahun</td><td>
    <select name=bulan ><option value='01' >Januari</option><option value='02' >Februari</option><option value='03' >Maret</option>
    <option value='04' >April</option><option value='05' >Mei</option><option value='06' >Juni</option><option value='07' >Juli</option>
    <option value='08' >Agustus</option><option value='09' >September</option><option value='10' >Oktober</option>
    <option value='11' >November</option><option value='12' >Desember</option></select> 
     <select name=tahun ><option value='2010'>2010</option><option value='2011'>2011</option><option value='2012'>2012</option>
    <option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><option value='2016'>2016</option>
    </select>
 </td></tr>
 <tr><td>Pilih File Import </td><td>
 <input type=file name='excel_file' >&nbsp;<input type=submit value='Import' >
	 <br></td><tr></table></form><br>
	 <font><b>Keterangan</b><br>
	 Field NIS harus diisi tidak boleh kosong dan harus sesuai dengan database karena NIS merupakan Primary Key dari Data Siswa<br>
     <b>Pengisian Keterangan Absensi</b><br/> <ul>
     <li>Hadir dikosongkan</li><li>Sakit diisi S </li><li> Alpa diisi A</li>
     <li>Izin diisi I</li><li>Terlambat diisi T atau Selisih waktu terlambat (misal : 00:20:15 )</li></ul>
     Apabila terdapat hari Libur Nasional atau tepat hari Libur Sekolah, maka sebaiknya kolom pada format Excel dihapus.<br><br>";

}

function gurubk() {
include "koneksi.php";
 $hal=$_GET['hal'];
 $kelas=$_GET['kelas'];$sem=$_GET['sem'];$program=$_GET['program'];
if ($sem=='') $sem=1;
if ($kelas=='') $kelas='X - 1';

  $brs=50;
  $kol=10;
	
  $byk_result=mysql_query("SELECT * FROM t_bpbk where kelas='". mysql_escape_string($kelas)."' and sem='". mysql_escape_string($sem)."' ");
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
  if($program=='') $program='-';
  
  $query = "SELECT * FROM t_bpbk where kelas='". mysql_escape_string($kelas)."' and sem='". mysql_escape_string($sem)."' order by tgl DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      $jur .= 'Jurusan/Program Keahlian <select name="program" onchange="document.location.href=\'admin.php?mode=gurubk&program=\'+document.guru.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) $jur .="<option value='$al[program]' selected>$al[program]</option>";
      	else $jur .="<option value='$al[program]' >$al[program]</option>";
      }
      $jur .="</select> &nbsp;&nbsp;";
  }
  else  $jur .= "<input type=hidden name=program value='-'/>";
  
  	$data .="<select name=kelas >";
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>';
  	$data2 .="<select name=sem >";
	$sql="select * from t_semester ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($sem==$row[semester]) $data2 .="<option value='$row[semester]' selected>$row[semester]</option>";
		else $data2.="<option value='$row[semester]'>$row[semester]</option>";
	}
	$data2 .='</select>';
  echo "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
  <tr><td ><center><font><center><b>::  Data Penilaian BP/BK  ::</b> </center>
  <br><form action='admin.php' method=get name='guru' >
  <input type=hidden name=mode value='gurubk'>$jur &nbsp;&nbsp;
  Kelas $data &nbsp;&nbsp; Sem $data2 &nbsp; &nbsp;<input type=submit value=' Pilih ' ></form></td></tr></table>
  
  <form action='admin.php' method=\"post\" name='bk' >
  <table width='100%' border='1' cellspacing='1' cellpadding='1'>";
  if ($jml!=0) {
  echo "<tr><td colspan=7 ><center><font class='ver10'><a href='admin.php?mode=gurubk&kelas=$kelas&sem=$sem&hal=1&program=$program' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='admin.php?mode=gurubk&kelas=$kelas&sem=$sem&hal=$back' style='color:000000;text-decoration:none' title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal) echo "<b><a href='admin.php?mode=gurubk&kelas=$kelas&sem=$sem&hal=$i&program=$program' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else echo "<a href='admin.php?mode=gurubk&kelas=$kelas&sem=$sem&hal=$i&program=$program' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=gurubk&kelas=$kelas&sem=$sem&hal=$next&program=$program' style='color:000000;text-decoration:none' title='$next'> Next</a> 
  <a href='admin.php?mode=gurubk&kelas=$kelas&sem=$sem&hal=$jml&program=$program' style='color:000000;text-decoration:none' title='Page $jml'> Last</a></font></center></td></tr>";
  }
    echo "<tr bgcolor='#2492AB'><td><font><b>No</td><td><font><b>Tgl Kirim</td><td><font><b>NIS/Nama</td>
  <td><font><b>Guru</td><td><font><b>Penilaian</td><td><font><b>Ket</td><td><font><b>Hap</td></tr>";
 
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.bk.elements.length;i++) {
     var e = document.bk.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "#ddedea";
	if ($x==1) {
	$warna = "#2492AB";
	$x=0; }
	else $x=1;
	$sql="select * from t_siswa where user_id='$row[nis]'";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	$ro=mysql_fetch_array($q);
	$nama=$ro[nama];
	
    echo"<tr bgcolor=$warna onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td width='2%' valign=top ><font>$j</td>
   <td width='3%' valign=top ><font>".date("d-m-Y",strtotime($row[tgl]))."</td>
   <td width='15%' valign=top ><font>$row[nis]<br>$nama</td>
   <td width='15%' valign=top ><font>$row[guru]</td>
   <td width='15%' valign=top ><font>$row[penilaian]</td>
   <td width='15%' valign=top ><font>$row[ket]</td>
   <td width='2%' valign=top ><font><input  title='Hapus Data BP/BK a.n. $nama' type='checkbox' name='kd[$row[id]]' value='on'></td></tr>";
	$j++;
  }        
  echo " </table><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"mode\" value=\"gurubkhapus\">
                <input type=\"submit\" value=\"Hapus\"></form>";

  
 }
 function gurubkhapus() {
 include "koneksi.php";
 $kd=$_POST['kd'];
   if (!empty($kd)) {
	  	while (list($key,$value)=each($kd))
		{
		$sql="delete from t_bpbk where id='". mysql_escape_string($key)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
		}
   }

 }
 
}//akhir
?>