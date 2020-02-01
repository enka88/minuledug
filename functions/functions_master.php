<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
// ----------------------------- data master / referensi-----------------------
class masterclass {
//****************************************** data kelas **************************//
// kelas
function kelas() {
include("koneksi.php");
  $program=$_GET['program'];
  if ($program=='') $program='-';
  $i=1;
  echo "<font><b><center> Daftar Kelas</center></b><br>";
  
  if($cmstingkat=='sma' or $cmstingkat=='smk') {
      echo "<form action='admin.php' method='post' name='kelas' >Jurusan/Program Keahlian ";  
      echo '<select name="program" onchange="document.location.href=\'admin.php?mode=kelas&program=\'+document.kelas.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
    
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      	else echo "<option value='$al[program]' >$al[program]</option>";
      }
      echo "</select> &nbsp;&nbsp;</form>";
  }
  
  echo "<br><form action='admin.php' method=\"post\" name='kelas2' >";
  echo "<table border='1' bordercolor='#000000' cellpadding='2' cellspacing='0' width='95%'>
  <tr bgcolor='#dcddcc'><td><font><b>No</td><td><font><b>Kelas</td><td><font><b>NIP</td><td><font><b>Nama</td><td><font><b>Tingkatan Kelas </td><td><font><b>Jurusan/ Program Keahlian</td><td><font><center><b>Edit</td><td><font><b>Hapus</td></tr>";
  $sql="select * from t_kelas where program='".mysql_escape_string($program)."' order by kelas";
  $mysql_result=mysql_query($sql);
   echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.kelas2.elements.length;i++) {
     var e = document.kelas2.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row=mysql_fetch_array($mysql_result)) {
  	$sql1="select nama from t_staf where nip='$row[nip]'";
  $mysql=mysql_query($sql1);
  $r=mysql_fetch_array($mysql);
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td>
      <td><font>$row[kelas]</td><td><font>$row[nip]</td><td><font>$r[nama]</td><td align='center'><font>$row[tingkat]</td><td><font>$row[program]</td><td><center><font><a href='admin.php?mode=edit_kelas&id=$row[kelas]'><img src='../images/edit.gif' border=0></a></td><td><font><input type='checkbox' name='kd[$row[kelas]]' value='on'></td>
	</tr>";
 	$i++;
  }
  echo"</table><font><input type=hidden name=mode value='del_kelas' ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
  <input type=\"submit\" value=\"Hapus\">| <a href='admin.php?mode=tam_kelas'>Tambah Kelas</a> | </form>";
}

function edit_kelas() {
  include "koneksi.php";
  $save=$_GET['save'];
  $id=$_GET['id'];
  $nip=$_POST['nip'];
  $tingkat=$_POST['tingkat'];
  $program=$_POST['program'];
  $kelas=$_POST['kelas'];
  
  if ($save=="") {
  $sql="select * from t_kelas where kelas='". mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
	echo"<font > <b>----> Perubahan Data Kelas </b><br><br><form action='admin.php?mode=edit_kelas&save=1&id=$id' method=\"post\" name=\"f1\" >";
    echo"<font>Kelas &nbsp;&nbsp;&nbsp;: <input type=text name=kelas value='$row[kelas]' size=10 maxlength=10 ><br>";

	$program=$row[program];
 	echo"Nama Guru &nbsp;: ";
	  echo " <select name='nip' >";
  $sql2="select nip,nama from t_staf order by nama";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[nip]==$row[nip]) echo "<option value='$al[nip]' selected>$al[nama]</option>";
  	else echo "<option value='$al[nip]' >$al[nama]</option>";
  }
  echo "</select><br>";	
  if ($tingkat=='6') $s6 = "selected";
  elseif ($tingkat=='2') $s2 = "selected";
  elseif ($tingkat=='3') $s3 = "selected";
  elseif ($tingkat=='4') $s4 = "selected";
  elseif ($tingkat=='5') $s5 = "selected";
  else $s1 = "selected";
   
	echo"Tingkatan Kelas : <select name='tingkat'>";
	if ($cmstingkat=='smp') {	
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>";
	}
	elseif ($cmstingkat=='sma') {	
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>";
	}
	elseif ($cmstingkat=='smk') {
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>
	<option value='4' ".$s4." >4</option>";
	}
    else {
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>
	<option value='4' ".$s4." >4</option>
    <option value='5' ".$s5." >5</option>
    <option value='6' ".$s6." >6</option>";
	}
	echo "</select><br>";
    if ($cmstingkat=='smk' or $cmstingkat=='sma') {  
  echo "Jurusan/Program Keahlian <select name='program' >";
  $sql2="select * from t_programahli order by idprog";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
  	else echo "<option value='$al[program]' >$al[program]</option>";
  }
  echo "</select> &nbsp;&nbsp;<br><br>";
  }
  else {
    echo "<input type=hidden name='program' value='-' />";
  }	
	echo"<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";
  }
  else {
  $sql = "UPDATE t_kelas SET kelas='".mysql_escape_string($kelas)."',tingkat='".mysql_escape_string($tingkat)."',program='".mysql_escape_string($program)."',nip='$nip' WHERE kelas ='".mysql_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Perubahan kelas berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=kelas'>Lihat Kelas</a> | <a href='admin.php?mode=tam_kelas'>Tambah Kelas</a> |
  </font>"; 
  }
  
}
  //hapus 
function del_kelas() {
include("koneksi.php");
$kd=$_POST['kd'];
  if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_kelas where kelas='".mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }

} 
function tam_kelas() {
  include "koneksi.php";
  $save=$_GET['save'];
  $nip=$_POST['nip'];
  $tingkat=$_POST['tingkat'];
  $program=$_POST['program'];
  $kelas=$_POST['kelas'];
  if ($save=="") {
	echo"<font > <b>----> Perubahan Data Kelas </b><br><br>
	<form action='admin.php?mode=tam_kelas&save=1' method=\"post\" name=\"f1\" >";
    echo"<font>Kelas &nbsp;&nbsp;&nbsp;: <input type=text name=kelas value='' size=10 maxlength=10 ><br>";
	echo"Nama Guru &nbsp;: ";
	  echo " <select name='nip' >";
  $sql2="select nip,nama from t_staf order by nama";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	echo "<option value='$al[nip]' >$al[nama]</option>";
  }
  echo "</select><br>";	
  
  echo"Tingkatan Kelas : <select name='tingkat'>";
	if ($cmstingkat=='smp') {	
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>";
	}
	elseif ($cmstingkat=='sma') {	
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>";
	}
	elseif ($cmstingkat=='smk') {
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>
	<option value='4' ".$s4." >4</option>";
	}
    else {
	echo "<option value='1' ".$s1." >1</option>
	<option value='2' ".$s2." >2</option>
	<option value='3' ".$s3." >3</option>
	<option value='4' ".$s4." >4</option>
    <option value='5' ".$s5." >5</option>
    <option value='6' ".$s6." >6</option>";
	}
    echo "</select><br/>";
    if ($cmstingkat=='smk' or $cmstingkat=='sma') {  
      echo "Jurusan/Program Keahlian <select name='program' >";
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      	else echo "<option value='$al[program]' >$al[program]</option>";
      }
      echo "</select> &nbsp;&nbsp;<br><br>";	
    }
    else {
        echo "<input type=hidden name='program' value='-' />";
    }
	echo"<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";
  }
  else {
  $sql = "insert into t_kelas (kelas,nip,tingkat,program) values ('".mysql_escape_string($kelas)."','".mysql_escape_string($nip)."','".mysql_escape_string($tingkat)."','".mysql_escape_string($program)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Penambahan Kelas berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=kelas'>Lihat Kelas</a> | <a href='admin.php?mode=tam_kelas'>Tambah Kelas</a> |</font>"; 
  }
 
}

 function pelajaran() {
 include "koneksi.php";
  
  echo "<font> <center><b>--- Data Pelajaran ---</b></center><br>
  <form action='admin.php' method=\"post\" name=pelajaran >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr bgcolor='dedede' ><td><font>No</td><td><font>Pelajaran</td>
  <td><font>Program</td><td><font>Edit</td>
  <td><font>Hapus</td></tr>";
  $query = "SELECT * from t_pelajaran order by pel "; 
  $q = mysql_query ($query) ;
  $i=1;
   echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row = mysql_fetch_array($q)) {
    $program = $row['program']=='-'?'Umum':$row['program'];
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td>
      <td><font>$row[pel]</td><td><font>".$program."</td>
	<td><font><a href='admin.php?mode=pelajaran_edit&kd=$row[idpel]' ><img src='../images/edit.gif' border='0' ></a></td><td><input type='checkbox' name='kd[$row[idpel]]' value='on'></td></tr>";
	$i++;
  }
  echo "</table><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=hidden name=mode value='pelajaran_hapus' >
<input type=\"submit\" value=\"Hapus\">
  | <a href='admin.php?mode=pelajaran_tam' >Tambah Pelajaran</a> |</form>";
  
 
 }
 
 function pelajaran_tam() {
 include "koneksi.php";
  
  echo "<font><b>Tambah Data pelajaran<br></b><form action='admin.php' method='post' >
  Kode Pel &nbsp;: <input type='text' name='kdpel' > <br>
  Pelajaran : <input type='text' name='pel' maxlength='30' > <br>
  Pel Rapor : <input type='text' name='pelajaran' maxlength='50' ><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
  echo "Program Keahlian : <select name='program'>";
  	$sql="select * from t_programahli order by idprog";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		echo "<option value='$row[program]'>$row[program]</option>";
	}
	echo "</select>";
  }
  else {
    echo "<input type=hidden name=program value='-'/>";
  }  
  echo "<br>&nbsp;<input type='reset' value='Ulang' > &nbsp;<input type='submit' value='Simpan' >
  <input type='hidden' name='mode' value='pelajaran_save' ><input type='hidden' name='st' value='tambah' ></form>";
  
 
 }
 
 function pelajaran_edit() {
 include "koneksi.php";
  $kd=$_GET['kd'];
    $query = "SELECT * from t_pelajaran where idpel='". mysql_escape_string($kd)."' "; 
  $q = mysql_query ($query) ;
  $row = mysql_fetch_array($q);
  echo "<font><b>Edit Data pelajaran<br></b><form action='admin.php' method='post' >
  Kode Pel &nbsp;: <input type='text' name='kdpel' value='$row[kode_pel]'  > <br>
  Pelajaran : <input type='text' name='pel' value='$row[pel]' maxlength='30' > <br>
  Pel Rapor : <input type='text' name='pelajaran' value='$row[pelajaran]' maxlength='50' ><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
  echo "Program Keahlian : <select name='program'>";
  	$sql="select * from t_programahli order by idprog";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 pelajaran ");
	while($r=mysql_fetch_array($q)) {
		if ($row['program']==$r['program']) echo "<option value='$r[program]' selected>$r[program]</option>";
		else echo "<option value='$r[program]'>$r[program]</option>";
	}
	echo "</select>";
  }
  else {
    echo "<input type=hidden name=program value='-'/>";
  } 
  echo "<br>&nbsp;<input type='reset' value='Ulang' > &nbsp;<input type='submit' value='Simpan' >
  <input type='hidden' name='mode' value='pelajaran_save' ><input type='hidden' name='st' value='edit' >
  <input type=hidden name=kd value='$kd' ></form>";
  
 
 }
function pelajaran_save() {
 include "koneksi.php";
  $kd=$_POST['kd'];
  $kdpel=$_POST['kdpel'];
  $pel=$_POST['pel'];$pelajaran=$_POST['pelajaran'];
  $program=$_POST['program'];$st=$_POST['st'];
  if ($st=='edit') {
    $query = "update t_pelajaran set kode_pel='". mysql_escape_string($kdpel)."', pel='". mysql_escape_string($pel)."',pelajaran='". mysql_escape_string($pelajaran)."',program='". mysql_escape_string($program)."' where idpel='". mysql_escape_string($kd)."' "; 
	}
  else {
  	$query = "insert into t_pelajaran (kode_pel,pel,pelajaran,program) values ('". mysql_escape_string($kdpel)."','". mysql_escape_string($pel)."','". mysql_escape_string($pelajaran)."','". mysql_escape_string($program)."')"; 
  }
  $q = mysql_query ($query) ;
	echo  "<font>Perubahan data pelajaran berhasil !!!<br><br>
	| <a href='admin.php?mode=pelajaran' >Lihat Pelajaran</a> | <a href='admin.php?mode=pelajaran_tam' >Tambah Pelajaran</a> |";

 }
 function pelajaran_hapus() {
 include "koneksi.php";
 $kd=$_POST['kd'];
  if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_pelajaran where idpel='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }
} 
 
 function semester() {
 include "koneksi.php";
  
  echo "<font> <center><b>--- Data Semester ---</b></center><br><form action='admin.php' method=\"post\" name=semester >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr bgcolor='dedede' ><td><font>No</td><td><font>Semester</td><td><font>Edit</td>
  <td><font>Hapus</td></tr>";
  $query = "SELECT * from t_semester order by idsem "; 
  $q = mysql_query ($query) ;
  $i=1;
    echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row = mysql_fetch_array($q)) {
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td><td><font>$row[semester]</td><td><font><a href='admin.php?mode=semester_edit&kd=$row[idsem]' ><img src='../images/edit.gif' border='0' ></a></td><td><input type='checkbox' name='kd[$row[idsem]]' value='on'></td></tr>";
	$i++;
  }
  echo "</table><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=hidden name=mode value='semester_hapus' >
<input type=\"submit\" value=\"Hapus\">
  | <a href='admin.php?mode=semester_tam' >Tambah Semester</a> | </form>";
  
 
 }
 
 function semester_tam() {
  
  echo "<font><b>Tambah Data Semester<br></b><form action='admin.php' method='post' >
  Semester <input type='text' name='sem' ><input type='reset' value='Ulang' > &nbsp; &nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='semester_save' ><input type='hidden' name='st' value='tambah' ></form>";

 }
 
 function semester_edit() {
 include "koneksi.php";
  $kd=$_GET['kd'];
    $query = "SELECT * from t_semester where idsem='". mysql_escape_string($kd)."' "; 
  $q = mysql_query ($query) ;
  $row = mysql_fetch_array($q);
  echo "<font><b>Perubahan Data Semester<br></b><form action='admin.php' method='post' >
  Semester <input type='text' name='sem' value='$row[semester]' > <input type='reset' value='Ulang' > &nbsp;&nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='semester_save' ><input type='hidden' name='st' value='edit' >
  <input type=hidden name=kd value='$kd' ></form>";

}
function semester_save() {
 include "koneksi.php";
  $kd=$_POST['kd'];
  $sem=$_POST['sem'];$st=$_POST['st'];
  if ($st=='edit') {
    $query = "update t_semester set semester='". mysql_escape_string($sem)."' where idsem='". mysql_escape_string($kd)."' "; 
	}
  else {
  	$query = "insert into t_semester (semester) values ('". mysql_escape_string($sem)."')"; 
  }
  $q = mysql_query ($query) ;
	echo  "<font>Perubahan data semester telah berhasil !!! <br><br>
	| <a href='admin.php?mode=semester' >Lihat Semester</a> | <a href='admin.php?mode=semester_tam' >Tambah Semester</a> |";
  
 
 }
 function semester_hapus() {
 include "koneksi.php";
 $kd=$_POST['kd'];
  if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_semester where idsem='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }
 } 
 //--------------------------- tahun pelajaran
  function thajar() {
 include "koneksi.php";
  
  echo "<font> <center><b>--- Data Tahun Pelajaran ---</b></center><br><form action='admin.php' method=\"post\" name=thajar >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr bgcolor='dedede' ><td><font>No</td><td><font>Tahun Pelajaran</td><td><font>Edit</td>
  <td><font>Hapus</td></tr>";
  $query = "SELECT * from t_thajar order by idthajar "; 
  $q = mysql_query ($query) ;
  $i=1;
    echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row = mysql_fetch_array($q)) {
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td><td><font>$row[thajar]</td><td><font><a href='admin.php?mode=thajar_edit&kd=$row[idthajar]' ><img src='../images/edit.gif' border='0' ></a></td><td><input type='checkbox' name='kd[$row[idthajar]]' value='on'></td></tr>";
	$i++;
  }
  echo "</table><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=hidden name=mode value='thajar_hapus' >
<input type=\"submit\" value=\"Hapus\">
  | <a href='admin.php?mode=thajar_tam' >Tambah Tahun Pelajaran</a> | </form>";
  
 
 }
 
 function thajar_tam() {
  
  echo "<font><b>Tambah Data Tahun Pelajaran<br></b><form action='admin.php' method='post' >
  Tahun Pelajaran <input type='text' name='thajar' > Misal : 2008/2009 &nbsp;&nbsp;<input type='reset' value='Ulang' > &nbsp; &nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='thajar_save' ><input type='hidden' name='st' value='tambah' ></form>";

 }
 
 function thajar_edit() {
 include "koneksi.php";
  $kd=$_GET['kd'];
    $query = "SELECT * from t_thajar where idthajar='". mysql_escape_string($kd)."' "; 
  $q = mysql_query ($query) ;
  $row = mysql_fetch_array($q);
  echo "<font><b>Perubahan Data Tahun Pelajaran<br></b><form action='admin.php' method='post' >
  Tahun Pelajaran <input type='text' name='thajar' value='$row[thajar]' > Misal : 2008/2009 &nbsp;&nbsp;<input type='reset' value='Ulang' > &nbsp;&nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='thajar_save' ><input type='hidden' name='st' value='edit' >
  <input type=hidden name=kd value='$kd' ></form>";

}
function thajar_save() {
 include "koneksi.php";
  $kd=$_POST['kd'];
  $thajar=$_POST['thajar'];$st=$_POST['st'];
  if ($st=='edit') {
    $query = "update t_thajar set thajar='". mysql_escape_string($thajar)."' where idthajar='". mysql_escape_string($kd)."' "; 
	}
  else {
  	$query = "insert into t_thajar (thajar) values ('". mysql_escape_string($thajar)."')"; 
  }
  $q = mysql_query ($query) ;
	echo  "<font>Perubahan data Tahun Pelajaran telah berhasil !!! <br><br>
	| <a href='admin.php?mode=thajar' >Lihat Tahun Pelajaran</a> | <a href='admin.php?mode=thajar_tam' >Tambah Tahun Pelajaran</a> |";
  
 
 }
 function thajar_hapus() {
 include "koneksi.php";
 $kd=$_POST['kd'];
  if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_thajar where idthajar='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }
 } 

//--------------------------program
function program() {
 include "koneksi.php";

  echo "<font> <b><center> --- Data Jurusan/Program Keahlian ---</center></b>
  <br><form action='admin.php' method=\"post\" name=program >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100%>
  <tr bgcolor='dedede'><td><font>No</td><td><font>Jurusan/Program Keahlian</td><td><font>Edit</td>
  <td><font>Hapus</td></tr>";
  $query = "SELECT * from t_programahli order by idprog "; 
  $q = mysql_query ($query) ;
  $i=1;
    echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row = mysql_fetch_array($q)) {
    $program = $row['program']=='-'?'umum':$row['program'];
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\" ><td><font>$i</td>
      <td><font>".$program."</td><td><font><a href='admin.php?mode=program_edit&kd=$row[idprog]' ><img src='../images/edit.gif' border='0' ></a></td><td><input type='checkbox' name='kd[$row[idprog]]' value='on'></td></tr>";
	$i++;
  }
  echo "</table><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=hidden name=mode value='program_hapus' ><input type=\"submit\" value=\"Hapus\"> 
  | <a href='admin.php?mode=program_tam' >Tambah Program</a> |</form>";

 }
 
 function program_tam() {
  
  echo "<font><b>Tambah Data program<br></b><form action='admin.php' method='post' >
  Jurusan/Program keahlian <input type='text' name='program' > <input type='reset' value='Ulang' > &nbsp;&nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='program_save' ><input type='hidden' name='st' value='tambah' ></form>";

 }
 
 function program_edit() {
 include "koneksi.php";
  $kd=$_GET['kd'];
 if ($kd=='1') { echo "Maaf Id Program ini tidak dapat diedit"; }
 else {
    $query = "SELECT * from t_programahli where idprog='". mysql_escape_string($kd)."' "; 
  $q = mysql_query ($query) ;
  $row = mysql_fetch_array($q);
  echo "<font><b>Perubahan Data program<br></b><form action='admin.php' method='post' >
  Jurusan/Program keahlian <input type='text' name='program' value='$row[program]' > <input type='reset' value='Ulang' > &nbsp;&nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='program_save' ><input type='hidden' name='st' value='edit' >
  <input type=hidden name=kd value='$kd' ></form>";
 }
 
 }
function program_save() {
 include "koneksi.php";
  $kd=$_POST['kd'];
  $program=$_POST['program'];$st=$_POST['st'];
  if ($st=='edit') {
  	 	if ($kd=='1') echo "Maaf Id Program ini tidak dapat dihapus";
 		else $query = "update t_programahli set program='". mysql_escape_string($program)."' where idprog='". mysql_escape_string($kd)."' "; 
	}
  else {
  	$query = "insert into t_programahli (program) values ('". mysql_escape_string($program)."')"; 
  }
  $q = mysql_query ($query) ;
	echo  "<font>Data telah disimpan <br>| <a href='admin.php?mode=program' >Lihat Program</a> | <a href='admin.php?mode=program_tam' >Tambah Program</a> |";

 }
 function program_hapus() {
 include "koneksi.php";
   $kd=$_POST['kd'];
 if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_programahli where idprog='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }
 } 

 function kategorilink() {
 include "koneksi.php";

  echo "<font> <center><b> --- Data Kategori Link --- </b></center><br>
  <form action='admin.php' method=\"post\" name=kategorilink >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr bgcolor'dedede' ><td><font>No</td><td><font>Kategori Link</td>
  <td><font>Jenis</td><td><font>Edit</td>  <td><font>Hapus</td></tr>";
  $query = "SELECT * from t_kategori order by id_kategori "; 
  $q = mysql_query ($query) ;
  $i=1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row = mysql_fetch_array($q)) {
    if ($row[jenis]=='0') $jenis='Link Web';
    elseif ($row[jenis]=='1') $jenis='Materi Ajar';
	elseif ($row[jenis]=='2')  $jenis='Materi Uji';
	else $jenis='Banner';
	
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td><td><font>$row[kategori]</td>
	<td><font>$jenis</td><td><font><a href='admin.php?mode=kategorilink_edit&kd=$row[id_kategori]' ><img src='../images/edit.gif' border='0' ></a></td><td><input type='checkbox' name='kd[$row[id_kategori]]' value='on'></td></tr>";
	$i++;
  }
  echo "</table><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=hidden name=mode value='kategorilink_hapus' >
<input type=\"submit\" value=\"Hapus\">
  | <a href='admin.php?mode=kategorilink_tam' >Tambah Kategori</a> |</form>";

 }
 
 function kategorilink_tam() {
  
  echo "<font><b>Tambah Data kategorilink<br></b><form action='admin.php' method='post' >
  Kategori Link <input type='text' name='kategori' > <br>
  Jenis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name='jenis' ><option value='0' $s1>Link Web</option>
  <option value='1' $s2>Materi Ajar</option><option value='2' $s3>Materi Uji</option><option value='3' $s3>Banner</option></select><br><input type='reset' value='Ulang' > &nbsp;
  &nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='kategorilink_save' ><input type='hidden' name='st' value='tambah' ></form>";

 }
 
 function kategorilink_edit() {
 include "koneksi.php";
  $kd=$_GET['kd'];
    $query = "SELECT * from t_kategori where id_kategori='". mysql_escape_string($kd)."' "; 
  $q = mysql_query ($query) ;
  $row = mysql_fetch_array($q);
  if ($row[jenis]=='0') $s1='selected';
  elseif ($row[jenis]=='1') $s2='selected';
  elseif ($row[jenis]=='2') $s3='selected';
  else $s4='selected';
  echo "<font><b>Perubahan Data Kategori Link<br></b><form action='admin.php' method='post' >
  Kategori Link <input type='text' name='kategori' value='$row[kategori]' > <br>
  Jenis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name='jenis' ><option value='0' $s1>Link Web</option
  ><option value='1' $s2>Materi Ajar</option><option value='2' $s3>Materi Uji</option><option value='3' $s4>Banner</option></select><br><input type='reset' value='Ulang' > &nbsp;
  &nbsp;<input type=submit value='Simpan' >
  <input type='hidden' name='mode' value='kategorilink_save' ><input type='hidden' name='st' value='edit' >
  <input type=hidden name=kd value='$kd' ></form>";
  
 }
function kategorilink_save() {
 include "koneksi.php";
 $kd=$_POST['kd'];
 $kategori=$_POST['kategori'];$jenis=$_POST['jenis'];$st=$_POST['st'];
  if ($st=='edit') {
    $query = "update t_kategori set kategori='". mysql_escape_string($kategori)."',jenis='". mysql_escape_string($jenis)."' where id_kategori='". mysql_escape_string($kd)."' "; 
	}
  else {
  	$query = "insert into t_kategori (kategori,jenis) values ('". mysql_escape_string($kategori)."','". mysql_escape_string($jenis)."')"; 
  }
  $q = mysql_query ($query) ;
	echo  "<font> Perubahan data kategori link berhasil !!!<br><br>
	| <a href='admin.php?mode=kategorilink' >Lihat Kategori</a> | <a href='admin.php?mode=kategorilink_tam' >Tambah Kategori</a> |";

 }
function kategorilink_hapus() {
 include "koneksi.php";
 $kd=$_POST['kd'];
  if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_kategori where id_kategori='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }

} 

}//akhir class
?>