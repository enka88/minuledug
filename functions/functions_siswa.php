<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
 //------------------ fungsi berhubungan dengan siswa--------------
class siswaclass {
 //************************** data import siswa **********************//
 function imsiswa() {
 
 echo "<table width=98% ><tr bgcolor=dedede><td><font><b>Membuat Format Data Siswa<b></td></tr>
 <tr><td><font><form action='../functions/fungsi_excelnilai.php' method='post' >Pilih Format Data <input type=submit value='Format'> <input type=hidden name=format value='siswa' ></form><br><br>
 <font><b>File berupa format Excel dengan nama SHEET harus FormatSiswa</b></font></td></tr>
 <tr bgcolor=dedede><td><font><b>Import Data Siswa</td></tr>
 <tr><td><br><font><form action='../functions/importsiswa.php' method='post' enctype=\"multipart/form-data\"'>Pilih File Import <input type=file name='excel_file' >&nbsp;<input type=submit value='Import' >
	 <input type=hidden name=st value='1'><br></form></td><tr></table><br>
	 <font><b>Keterangan</b><br>
	 Format Tanggal Lahir : bulan/hari/tahun<br>
	 Apabila ada kosong diisi dengan tanda ( - )<br>
     Dikolom WALI diisi dengan nama orang tua<br>
	 Format Kelamin berupa L atau P <br>
	 Nama tidak boleh mengandung tanda petik satu ( ' )<br>
	 Field NIS harus diisi tidak boleh kosong karena NIS merupakan Primary Key dari Data Siswa<br>";

 
 }

 //---------------------------------------- ortu ---------------------
function ortu() {
     include "koneksi.php";
$kelas=$_GET['kelas'];
$hal=$_GET['hal'];
$program=$_GET['program'];

  if($program=='') $program='-';
  
$brs=50;
  $byk_result1=mysql_query("select * from t_siswa where kelas='". mysql_escape_string($kelas)."'");
  $byk=mysql_num_rows($byk_result1);
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
  		
  $query = "SELECT * from t_siswa where kelas='". mysql_escape_string($kelas)."' order by nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"get\" name='ortu' name=ortu >";
  
  $data .='<select name=kelas onchange="document.location.href=\'admin.php?mode=ortu&kelas=\'+document.ortu.kelas.value+\'&program=\'+document.ortu.program.value">';
	$sql="select * from t_kelas where program='$program' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999'  ><font><center>--- Mendaftarkan Orang Tua/Wali---</center><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      echo 'Jurusan/Program Keahlian <select name="program" onchange="document.location.href=\'admin.php?mode=ortu&program=\'+document.ortu.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      	else echo "<option value='$al[program]' >$al[program]</option>";
      }
      echo "</select> &nbsp;&nbsp;";
  }
  else echo "<input type=hidden name=program value='-'/>";
  
  echo "Kelas $data &nbsp;&nbsp;<input type=submit value=' Pilih ' ><input type=hidden name=mode value=ortu ></font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td ><center><font><a href='admin.php?mode=ortu&kelas=$kelas&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=ortu&kelas=$kelas&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=ortu&kelas=$kelas&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "</table></form><form action='admin.php' method=\"post\" name='ortu2'>
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Kelas</center></font></td>
  <td><font><center>Orangtua/Wali</center></font></td>
  <td><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[1].elements.length;i++) {
     var e = document.forms[1].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$wali="<center><a href='admin.php?mode=ortu_tam&nis=$row[user_id]'>Daftar</a>";
	$nowali="0";$edit="-";
    $query = "SELECT nama,userid FROM t_member WHERE nis='$row[user_id]' and kategori='2'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  if($r = mysql_fetch_array($result)) {
  $wali =$r[nama];
  $nowali =$r[userid];
  $edit='<a href="admin.php?mode=ortu_edit&id='.$nowali.'"><img src="../images/edit.gif" border="0" ></a>';
  }
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[user_id]</font></td>
	<td width='30%' ><font>$row[nama]</font></td><td width='10%' ><font>$row[kelas]</font></td>
	<td width='20%' ><font>$wali</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><?php echo $edit;?></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $nowali; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"ortu_hap\">
                <input type=\"submit\" value=\"Hapus\"></form><br><br>";
  
  }

  function ortu_tam() {
  include "koneksi.php";
  $nis=$_GET['nis'];
  $query = "SELECT user_id,nama,kelas FROM t_siswa WHERE user_id='". mysql_escape_string($nis)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  $nmsiswa=$row[nama];
  $kelas=$row[kelas];
  
  echo "<form name='daftar' action='admin.php?mode=ortu_save' method='post'  >
	<table border=0 width='100%' cellspacing='0' cellpadding='3' bordercolor='#000000'><tr><td colspan=3 ><FONT class='ver10'>";
	echo "</font></td></tr>
	<tr><td colspan=3 bgcolor='#DEDEDE'><FONT class='ver11'><b>Membuat Username Orang Tua/Wali Siswa</b></font></td></tr>	
		<tr >
		<td align=right valign=top><FONT class='ver10'>Email/Username</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='email' size=20 value='$email' style='width:180; height:20;'>
		<br><FONT class='ver10'>Masukan email yang benar dan valid.</td>
	</tr>
	<tr >
		<td align=right><FONT class='ver10'>Nama Orang Tua/Wali</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='name' value='$name' size=20 style='width:180; height:20;'></td>
	</tr>";
	echo"	<tr >
		<td align=right><FONT class='ver10'>NIS</font></td>
		<td><FONT class='ver10'>: $nis</td><input type=hidden name='nis' value='$nis'>
	</tr>";	
	echo"	<tr >
		<td align=right><FONT class='ver10'>Nama Siswa</font></td>
		<td><FONT class='ver10'>: $nmsiswa</font></td>
	</tr><tr >
		<td align=right><FONT class='ver10'>Kelas</font></td>
		<td><FONT class='ver10'>: $kelas</font</td><input type=hidden name='kelas' value='$kelas'>
	</tr>";	
	echo"<tr >
		<td align=right><FONT class='ver10'>Telp/HP</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name=telp size=20 value='$telp' style='width:180; height:20;'></td>
	</tr>
 	<tr >
		<td align=right valign=top ><FONT class='ver10'>Kirim Email</font></td>
		<td><FONT class='ver10'>&nbsp;<input type='checkbox' name=kirimemail value=on />
	</tr>
	<tr><td>
	<input type=hidden name='save' value='1'><input type='reset' value='Ulang' > &nbsp;
	<input type='submit' class='button' name=submit value=' Simpan '>&nbsp;&nbsp;
	</td></tr>	
	</table></form>";
  
  }

function ortu_save() {
  include "koneksi.php";
  include "../functions/fungsi_pass.php";
  $save=$_POST['save'];
  $email=$_POST['email'];
  $name=$_POST['name'];
  $nis=$_POST['nis'];
  $kelas=$_POST['kelas'];$telp=$_POST['telp'];$id=$_POST['id'];
  $reset=$_POST['reset'];
  
 $sChars = "/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/";

 if ($email=='') echo "<font<br>&nbsp;Email yang anda masukkan kosong. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif (!preg_match("$sChars",$email)) echo "<font><br>&nbsp;Email yang anda masukan tidak valid. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif ($name=='') echo "<font><br>&nbsp;Nama Orangtua masih kosong. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif ($telp=='') echo "<font><br>&nbsp;Telp masih kosong. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 else {
  
  if ($save=='1') {
	$pass = rand(1111,9999);
	$password=md5($pass);
  $query = "insert into t_member (nama,kelamin,negara,telp,username,password,email,pengingat,jawaban,kategori,status,nis,kelas,ket) values ('". mysql_escape_string($name)."','m','ID','". mysql_escape_string($telp)."','". mysql_escape_string($email)."','$password','". mysql_escape_string($email)."','1','1','2','1','". mysql_escape_string($nis)."','". mysql_escape_string($kelas)."','Orang Tua')"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  if ($_POST['kirimemail']=='on') echo kirimemail($email,$pass,$email);
  echo"Terima kasih, penambahan data Orang tua berhasil dilakukan. <br><b>Username : $email, Password : $pass </b><br>
  | <a href='admin.php?mode=ortu' >Lihat Data Orang tua</a> |";
  }
  else {
   if ($reset=='on') {
	$pass = rand(1111,9999);
	$password=md5($pass);
	$set="password='$password',";
	}
  $query = "update t_member set $set email='". mysql_escape_string($email)."',telp='". mysql_escape_string($telp)."',nama='". mysql_escape_string($name)."' where userid='". mysql_escape_string($id)."' "; 
  $result = mysql_query ($query) or die (mysql_error()); 
  if ($_POST['kirimemail']=='on') echo kirimemail($email,$pass,$email);
  echo"Terima kasih, perubahan data Orang tua berhasil dilakukan.<br><b>Username : $email, Password : $pass </b>| <a href='admin.php?mode=ortu' >Lihat Data Orang tua</a> |";
  }
  }
}
  
  function ortu_edit() {
  include "koneksi.php";
  $id=$_GET['id'];
  $query = "SELECT * FROM t_member WHERE userid='". mysql_escape_string($id)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  
  echo "<form name='daftar' action='admin.php?mode=ortu_save' method='post'  >
	<table border=0 width='100%' cellspacing='0' cellpadding='3' bordercolor='#000000'><tr><td colspan=3 ><FONT class='ver10'>";
	echo "</font></td></tr>
	<tr><td colspan=3 bgcolor='#DEDEDE'><FONT class='ver11'><b>Membuat Username Orang Tua/Wali Siswa</b></font></td></tr>	
		<tr >
		<td align=right valign=top><FONT class='ver10'>Email/Username</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='email' size=20 value='$row[email]' style='width:180; height:20;'>
		<br><FONT class='ver10'>Masukan email yang benar dan valid.</td>
	</tr>
	<tr >
		<td align=right><FONT class='ver10'>Nama Orang Tua/Wali</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='name' value='$row[nama]' size=20 style='width:180; height:20;'></td>
	</tr>";
	echo"	<tr >
		<td align=right><FONT class='ver10'>NIS</font></td>
		<td><FONT class='ver10'>: $row[nis]</td><input type=hidden name='nis' value='$row[nis]'>
	</tr>";	
	  $query2 = "SELECT nama FROM t_siswa WHERE user_id='$row[nis]'"; 
  $result2 = mysql_query ($query2) or die (mysql_error()); 
  $r = mysql_fetch_array($result2);
  $nmsiswa=$r[nama];
	echo"	<tr >
		<td align=right><FONT class='ver10'>Nama Siswa</font></td>
		<td><FONT class='ver10'>: $nmsiswa</font></td>
	</tr><tr >
		<td align=right><FONT class='ver10'>Kelas</font></td>
		<td><FONT class='ver10'>: $row[kelas]</font</td><input type=hidden name='kelas' value='$row[kelas]'>
	</tr>";	
	echo"<tr >
		<td align=right><FONT class='ver10'>Telp/HP</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name=telp size=20 value='$row[telp]' style='width:180; height:20;'></td>
	</tr>
	<tr >
		<td align=right><FONT class='ver10'>Reset Password</font></td>
		<td><FONT class='ver10'>: <input type='checkbox' name='reset' value='on' /> Ya direset</td>
	</tr>
    	<tr >
		<td align=right valign=top ><FONT class='ver10'>Kirim Email</font></td>
		<td><FONT class='ver10'>&nbsp;<input type='checkbox' name=kirimemail value=on />
	</tr>
	<tr><td>
	<input type=hidden name='save' value='2'><input type=hidden name='id' value='$id'><input type='reset' value='Ulang' > &nbsp;
	<input type='submit' class='button' name=submit value=' Simpan '>&nbsp;&nbsp;
	</td></tr>	
	</table></form>";
  
  }
  function ortu_hap() {
  include "koneksi.php";
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))
		{
			$sql="delete from t_member where userid='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }
 }
 //***************************************** data siswa ********************//
 //---------------------------------------- member siswa ---------------------
  function sismember() {
     include "koneksi.php";
  $kelas=$_GET['kelas'];$hal=$_GET['hal'];
  $program=$_GET['program'];
  
  if($program=='') $program='-';
  
$brs=50;
  $byk_result1=mysql_query("select * from t_siswa where kelas='". mysql_escape_string($kelas)."'");
  $byk=mysql_num_rows($byk_result1);
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
  		
  $query = "SELECT * from t_siswa where kelas='". mysql_escape_string($kelas)."' order by nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"get\" name='siswa'>";
  
	   $data .='<select name=kelas onchange="document.location.href=\'admin.php?mode=sismember&kelas=\'+document.siswa.kelas.value+\'&program=\'+document.siswa.program.value">';
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999'  ><font><center>--- Mendaftarkan Member Siswa ---</center><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      echo 'Jurusan/Program Keahlian <select name="program" onchange="document.location.href=\'admin.php?mode=sismember&program=\'+document.siswa.program.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      	else echo "<option value='$al[program]' >$al[program]</option>";
      }
      echo "</select> &nbsp;&nbsp;";
  }
  else echo "<input type=hidden name=program value='-'/>";
  
  echo " Kelas $data &nbsp;&nbsp;<input type=submit value=' Pilih ' ><input type=hidden name=mode value=sismember ></font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td ><center><font><a href='admin.php?mode=sismember&kelas=$kelas&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=sismember&kelas=$kelas&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=sismember&kelas=$kelas&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "</table></form> <form action='admin.php' method=\"post\" name='siswa2'>
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Kelas</center></font></td>
  <td><font><center>Email Komunitas</center></font></td>
  <td><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[1].elements.length;i++) {
     var e = document.forms[1].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$wali="<center><a href='admin.php?mode=sismember_tam&nis=$row[user_id]'>Daftar</a>";
	$nowali="0";$edit="-";
    $query = "SELECT nama,userid,email,password FROM t_member WHERE nis='$row[user_id]' and kategori='1'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  if($r = mysql_fetch_array($result)) {
  $wali =$r[email];
  $nowali =$r[userid];
  $edit='<a href="admin.php?mode=sismember_edit&id='.$nowali.'" title="Klik untuk edit member." ><img src="../images/edit.gif" border="0" ></a>';
  }
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[user_id]</font></td>
	<td width='30%' ><font>$row[nama]</font></td>
	<td width='10%' ><font>$row[kelas]</font></td><td width='20%' ><font>$wali</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><?php echo $edit;?></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $nowali; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"sismember_hap\">
                <input type=\"submit\" value=\"Hapus\"></form><br><br>";
  
  }
  
  
function sismember_tam() {
include "koneksi.php";
  $nis=$_GET['nis'];
  $query = "SELECT * FROM t_siswa WHERE user_id='".mysql_escape_string($nis)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  $nmsiswa=$row[nama];
  $kelas=$row[kelas];
  if ($row[kelamin]=='L') $kel='m';
  else $kel='f';

  
  echo "<form name='daftar' action='admin.php?mode=sismember_save' method='post'  >
	<table border=0 width='100%' cellspacing='0' cellpadding='3' bordercolor='#000000'><tr><td colspan=3 ><FONT class='ver10'>";
	echo "</font></td></tr>
	<tr><td colspan=3 bgcolor='#DEDEDE'><FONT class='ver11'><b>Membuat Username Siswa</b></font></td></tr>	
	<tr >
		<td align=right valign=top><FONT class='ver10'>Email</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='email' size=20 value='$email' style='width:180; height:20;'>
		<br><FONT class='ver10'>Masukan email yang benar dan valid.</td>
	</tr>";
	echo"	<tr >
		<td align=right><FONT class='ver10'>NIS/Username</font></td>
		<td><FONT class='ver10'>: $nis</td><input type=hidden name='nis' value='$nis'>
	</tr>";	
	echo"<tr >
		<td align=right><FONT class='ver10'>Nama Siswa</font></td>
		<td><FONT class='ver10'>: $nmsiswa</font></td>
	</tr>
	<tr >
		<td align=right><FONT class='ver10'>Kelas</font></td>
		<td><FONT class='ver10'>: $kelas</font</td><input type=hidden name='kelas' value='$kelas'>
	</tr>
	<tr >
		<td align=right><FONT class='ver10'>Tgl Lahir</font></td>
		<td><FONT class='ver10'>: <input type=text name='tgllahir' value='$row[tgl_lahir]'></td>
	</tr>
	<tr >
		<td align=right valign=top ><FONT class='ver10'>Alamat</font></td>
		<td><FONT class='ver10'>&nbsp; <textarea name='alamat' cols='40' rows='5' >$row[alamat]</textarea>
	</tr>
	<tr >
		<td align=right valign=top ><FONT class='ver10'>Kirim Email</font></td>
		<td><FONT class='ver10'>&nbsp;<input type='checkbox' name=kirimemail value=on />
	</tr>";
	echo"<tr><td>
	<input type=hidden name='save' value='1'><input type=hidden name='kel' value='$kel'>
	<input type=hidden name='telp' value='$row[telp]' ><input type=hidden name='nmsiswa' value='$nmsiswa'>
	<input type='submit' class='button' name='submit' value=' Simpan '>&nbsp;&nbsp;
	</td></tr>	
	</table></form>";
  
}

function sismember_save() {
  include "koneksi.php";
  include "../functions/fungsi_pass.php";
  $save=$_POST['save'];$email=$_POST['email'];
  $nis=$_POST['nis'];$nmsiswa=$_POST['nmsiswa'];
  $kelas=$_POST['kelas'];$kel=$_POST['kel'];
  $tgllahir=$_POST['tgllahir'];
  $alamat=$_POST['alamat'];
  $telp=$_POST['telp'];
  $id=$_POST['id'];$reset=$_POST['reset'];
  
 $sChars = "/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/";

 if ($email=='') echo "<font<br>&nbsp;Email yang anda masukkan kosong. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif (!preg_match("$sChars",$email)) echo "<font><br>&nbsp;Email yang anda masukan tidak valid. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 else {
  if ($save=='1') {
	$pass = rand(1111,9999);
	$password=md5($pass);
  $query = "insert into t_member (nama,kelamin,negara,telp,username,password,email,pengingat,jawaban,kategori,status,nis,kelas,ket,sekolah,alamat,tgllahir) values ('". mysql_escape_string($nmsiswa)."','". mysql_escape_string($kel)."','ID','". mysql_escape_string($telp)."','". mysql_escape_string($nis)."','$password','". mysql_escape_string($email)."','1','1','1','1','". mysql_escape_string($nis)."','". mysql_escape_string($kelas)."','Siswa','".mysql_escape_string($nmsekolah)."','".mysql_escape_string($alamat)."','".mysql_escape_string($tgllahir)."')"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  
  if ($_POST['kirimemail']=='on') echo kirimemail($nis,$pass,$email);
  
  echo"<br><font>Terima kasih, penambahan data member siswa berhasil dilakukan.<br><b>Username : $nis, Password : $pass</b><br>| <a href='admin.php?mode=sismember' >Lihat Data Siswa</a> |";
  }
  else {
   if ($reset=='on') {
	$pass = rand(1111,9999);
	$password=md5($pass);
	$set="password='$password',";
	}
  $query = "update t_member set $set email='". mysql_escape_string($email)."',kelas='". mysql_escape_string($kelas)."' where userid='". mysql_escape_string($id)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  
  if ($_POST['kirimemail']=='on') echo kirimemail($nis,$pass,$email);
  
  echo"<br><font>Terima kasih, perubahan data Siswa berhasil dilakukan.<br><b>Username : $nis, Password : $pass</b><br>| <a href='admin.php?mode=sismember' >Lihat Member siswa</a> |";
  }
 }
  
}
  
function sismember_edit() {
  include "koneksi.php";
  $id=$_GET['id'];
  $query = "SELECT * FROM t_member WHERE userid='". mysql_escape_string($id)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  
  echo "<form name='daftar' action='admin.php?mode=sismember_save' method='post'  >
	<table border=0 width='100%' cellspacing='0' cellpadding='3' bordercolor='#000000'><tr><td colspan=3 ><FONT class='ver10'>";
	echo "</font></td></tr>
	<tr><td colspan=3 bgcolor='#DEDEDE'><FONT class='ver11'><b>Membuat Username Siswa</b></font></td></tr>	
		<tr >
		<td align=right valign=top><FONT class='ver10'>Email</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='email' size=20 value='$row[email]' style='width:180; height:20;'>
		<br><FONT class='ver10'>Masukan email yang benar dan valid.</td>
	</tr>";
	echo"	<tr >
		<td align=right><FONT class='ver10'>NIS</font></td>
		<td><FONT class='ver10'>: $row[nis]</td><input type=hidden name='nis' value='$row[nis]'>
	</tr>";	
	echo"	<tr >
		<td align=right><FONT class='ver10'>Nama Siswa</font></td>

		<td><FONT class='ver10'>: $row[nama]</font></td>
	</tr><tr >
		<td align=right><FONT class='ver10'>Kelas</font></td>
		<td><FONT class='ver10'>: $row[kelas]</font</td><input type=hidden name='kelas' value='$row[kelas]'>
	</tr>";	
	echo"<tr >
		<td align=right><FONT class='ver10'>Reset Password</font></td>
		<td><FONT class='ver10'>: <input type='checkbox' name='reset' value='on' /> Ya direset</td>
	</tr>
	<tr >
		<td align=right valign=top ><FONT class='ver10'>Kirim Email</font></td>
		<td><FONT class='ver10'>&nbsp;<input type='checkbox' name=kirimemail value=on />
	</tr>
	<tr><td>
	
	<input type=hidden name='save' value='2'><input type=hidden name='id' value='$id'>
	<input type='submit' class='button' name=submit value=' Simpan '>&nbsp;&nbsp;
	</td></tr>	
	</table></form>";
  
}

function sismember_hap() {
    $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))
		{
			$sql="delete from t_member where userid='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }

 }
 
function siswa() {
     include "koneksi.php";
  $program=$_GET['program'];
  $kelas=$_GET['kelas'];
  $hal=$_GET['hal'];
  
  if($program=='') 	$program='-';
  
$brs=50;
  $byk_result1=mysql_query("select * from t_siswa where kelas='". mysql_escape_string($kelas)."'");
  $byk=mysql_num_rows($byk_result1);
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
  		
  $query = "SELECT * from t_siswa where kelas='". mysql_escape_string($kelas)."' order by nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	

  $data .='<select name=kelas onchange="document.location.href=\'admin.php?mode=siswa&kelas=\'+document.siswa.kelas.value+\'&program=\'+document.siswa.program.value">';
	$sql="select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($kelas==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
  echo "<form action='admin.php' method='get' name='siswa' ><table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% > <tr><td bgcolor='#999999' ><font><center>--- Daftar siswa---</center><br>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
  echo 'Jurusan/Program Keahlian <select name="program" onchange="document.location.href=\'admin.php?mode=siswa&program=\'+document.siswa.program.value">';
  $sql2="select * from t_programahli order by idprog";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
  	else echo "<option value='$al[program]' >$al[program]</option>";
  }
  echo "</select> &nbsp;&nbsp;";
  }
  else echo "<input type=hidden name=program value='-'/>";
  	
  echo " Kelas $data</font>&nbsp;&nbsp;<input type='submit' value=' Pilih ' ><input type=hidden name=mode value=siswa ></td></tr>";
  if ($jml!=0) {
  echo "<tr><td ><center><font><a href='admin.php?mode=siswa&kelas=$kelas&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=siswa&kelas=$kelas&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=siswa&kelas=$kelas&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "</table></form><form action='admin.php' method=\"post\" name='siswa2' >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >";
  echo "<tr><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Kelas</center></font></td>
  <td><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[1].elements.length;i++) {
     var e = document.forms[1].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[user_id]</font></td>
	<td width='40%' ><font>$row[nama]</font></td>
	<td width='10%' ><font>$row[kelas]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=siswa_edit&id=<?php echo $row[user_id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[user_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><input type=\"hidden\" name=\"mode\" value=\"siswa_hap\">
  <input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'> Checklist Semua <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=siswa_tam'>Tambah siswa</a> |</form><br><font >";
  
 }
  function siswa_tam() {
  
    $data .='<select name=kelas >';
	$sql="select * from t_kelas order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		$data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
	echo "<script language='javascript' src='../functions/ssCalendar.js'></script>";
  echo "<form action='admin.php' method=\"post\" name=\"f1\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
           <tr><td colspan='2'><font><b>Pengisian siswa</b><font></td>	</tr>
            <tr> <td width='24%'><font>Nama</font></td>
              <td width='76%'> <input type='text' name='nama' size='30' maxlength='30' >
              </td></tr>
            <tr> <td width='24%'><font>NIS</font></td>
              <td width='76%'> <input type='text' name='nis' size='25' maxlength='25' >
              </td></tr>
			     <tr> <td width='24%'><font>Kelas</font></td>
              <td width='76%'> $data
              </td></tr>
			<tr> <td width='24%'><font>Kelamin</font></td>
              <td width='76%'> <select name='kelamin'><option value='L' selected >Laki-laki</option>
			  <option value='P'>Perempuan</option></select>
              </td></tr>
		            <tr> <td width='24%'><font>Tempat Lahir</font></td>
              <td width='76%'> <input type='text' name='tmp_lahir' size='20' maxlength='50' >
			  </td></tr>
		<tr> <td width='24%'><font>Tgl Lahir</font></td><td>";
			  
			  echo '<input name="tgl_lahir" type="text" id="tgl" value="'.$tgl1.'" readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
            echo "<font>Format tanggal bulan/hari/tahun</td></tr>	
            <tr> <td width='24%'><font>Alamat</font></td>
              <td width='76%'> <input type='text' name='alamat' size='50' maxlength='60' >
              </td></tr>
	            <tr> <td width='24%'><font>Telp</font></td>
              <td width='76%'> <input type='text' name='telp' size='15' maxlength='15' >
              </td></tr>
	            <tr> <td width='24%'><font>Agama</font></td>
              <td width='76%'> <input type='text' name='agama' size='20' maxlength='10' >
              </td></tr>
        	 <tr> <td width='24%'><font>STTB</font></td>
              <td width='76%'> <input type='text' name='sttb' size='10' maxlength='10' >
              </td></tr>
		     <tr> <td width='24%'><font>NEM</font></td>
              <td width='76%'> <input type='text' name='nem' size='10' maxlength='10' >
              </td></tr>
		     <tr> <td width='24%'><font>Wali</font></td>
              <td width='76%'> <input type='text' name='wali' size='30' maxlength='50' >
              </td></tr>
			  			<tr> 
              <td width='24%'><font>File Foto</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' > <font><font>Format file JPG, ukuran 150px x 180px
              </td>
            </tr>";
				
              echo"<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"siswa_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
	                 $m=date("m");  $d=date("d");  $y=date("Y");
  echo '<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "mm/dd/yyyy";
dptgl.anchor = "anctgl";
dptgl.initialize();
</script>';
  
 }
 // Save News
 function siswa_save() {
 include "koneksi.php";
 $edit=$_POST['edit'];$nama=$_POST['nama'];
 $nis=$_POST['nis']; $kelamin=$_POST['kelamin'];
 $alamat=$_POST['alamat'];$telp=$_POST['telp'];
 $tgl_lahir=$_POST['tgl_lahir'];$tmp_lahir=$_POST['tmp_lahir'];
 $kelas=$_POST['kelas'];$agama=$_POST['agama'];
 $sttb=$_POST['sttb'];$nem=$_POST['nem'];
 $wali=$_POST['wali'];
 $FileToUpload = $_FILES['FileToUpload']['tmp_name'];
	if ($edit=='') {	
		$total = $nis;
	 	 $sql = "INSERT INTO t_siswa (user_id,nama,kelamin,alamat,telp,tgl_lahir,tmp_lahir,kelas,agama,sttb,nem,wali) 
				VALUES ('". mysql_escape_string($nis)."', '". mysql_escape_string($nama)."', '". mysql_escape_string($kelamin)."','". mysql_escape_string($alamat)."','". mysql_escape_string($telp)."','". mysql_escape_string($tgl_lahir)."','". mysql_escape_string($tmp_lahir)."','". mysql_escape_string($kelas)."','". mysql_escape_string($agama)."','". mysql_escape_string($sttb)."','". mysql_escape_string($nem)."','". mysql_escape_string($wali)."')";
	}
	else {
		$total=$edit;
		$sql = "UPDATE t_siswa SET nama='". mysql_escape_string($nama)."',kelamin='". mysql_escape_string($kelamin)."',alamat='". mysql_escape_string($alamat)."',telp='". mysql_escape_string($telp)."',tgl_lahir='". mysql_escape_string($tgl_lahir)."',tmp_lahir='". mysql_escape_string($tmp_lahir)."',kelas='". mysql_escape_string($kelas)."',agama='". mysql_escape_string($agama)."',sttb='". mysql_escape_string($sttb)."',nem='". mysql_escape_string($nem)."',wali='". mysql_escape_string($wali)."' WHERE user_id = '". mysql_escape_string($edit)."'";	
	}
	
	 if(!$result = mysql_query($sql)) {
        die("Ada kesalahan penamabahan data dalam menu sebelumnya. Coba kembali dan pilih Back. <BR>$mysql_error()");
     }
    if($FileToUpload== '') {
    $newfile='';
    }
    else {
	if (file_exists($FileToUpload)) {
	$tdk='';
	$newfile="../images/siswa/".$nis.".jpg";
	if (file_exists($newfile)) unlink("../images/siswa/".$nis.".jpg");
    copy($FileToUpload, "../images/siswa/$nis.jpg");
		}
	else {
		$tdk="File foto yang diimputkan tidak ada";
		$newfile='';
		}
	}
	
      echo "<font><b>$tdk</b><br>Penambahan Database telah berhasil ! <br> Silahkan Anda pilih menu sebelah kiri yang diinginkan !.<br></font>| <a href='admin.php?mode=siswa'>Lihat siswa</a> | <a href='admin.php?mode=siswa_tam'>Tambah siswa</a> |";
  
 }
 
 function siswa_edit() {
  $id=$_GET['id'];
  	 $sql="select * from t_siswa where user_id='". mysql_escape_string($id)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);
    
	$data .='<select name=kelas>';
	$sql="select * from t_kelas order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($r=mysql_fetch_array($q)) {
		if ($row[kelas]==$r[kelas]) $data .="<option value='$r[kelas]' selected>$r[kelas]</option>";
		else $data .="<option value='$r[kelas]'>$r[kelas]</option>";
	}
	$data.='</select>&nbsp;&nbsp;';
	if($row[kelamin]=='L') $s1="selected";
	else $s2="selected";	
	echo "<script language='javascript' src='../functions/ssCalendar.js'></script>";
  echo "<form action='admin.php' method=\"post\" name=\"f1\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
         <tr><td colspan='2'><font><b>Pengisian siswa</b><font></td>	</tr>
            <tr> <td width='24%'><font>Nama</font></td>
              <td width='76%'> <input type='text' name='nama' value='$row[nama]' size='30' maxlength='30' >
              </td></tr>
            <tr> <td width='24%'><font>NIS</font></td>
              <td width='76%'> $row[user_id] <input type=hidden name=nis value='$row[user_id]' >
              </td></tr>
			     <tr> <td width='24%'><font>Kelas</font></td>
              <td width='76%'> $data
              </td></tr>
			<tr> <td width='24%'><font>Kelamin</font></td>
              <td width='76%'> <select name=kelamin><option value='L' $s1 >Laki-laki</option>
			  <option value='P'$s2 >Perempuan</option></select>
              </td></tr>
		            <tr> <td width='24%'><font>Tempat Lahir</font></td>
              <td width='76%'> <input type='text' name='tmp_lahir' value='$row[tmp_lahir]' size='20' maxlength='50' >
			  </td></tr><tr> <td width='24%'><font>Tgl Lahir</font></td><td>";
			  
echo '<input name="tgl_lahir" type="text" id="tgl" value="'.$row[tgl_lahir].'" readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
      echo "<font>Format Tanggal : bulan/hari/tahun</td></tr>	
            <tr> <td width='24%'><font>Alamat</font></td>
              <td width='76%'> <input type='text' name='alamat' size='50' value='$row[alamat]' maxlength='60' >
              </td></tr>
	            <tr> <td width='24%'><font>Telp</font></td>
              <td width='76%'> <input type='text' name='telp' size='15' value='$row[telp]' maxlength='15' >
              </td></tr>
	            <tr> <td width='24%'><font>Agama</font></td>
              <td width='76%'> <input type='text' name='agama' size='20' value='$row[agama]' maxlength='10' >
              </td></tr>
        	 <tr> <td width='24%'><font>STTB</font></td>
              <td width='76%'> <input type='text' name='sttb' size='10' value='$row[sttb]' maxlength='10' >
              </td></tr>
		     <tr> <td width='24%'><font>NEM</font></td>
              <td width='76%'> <input type='text' name='nem' size='10' value='$row[nem]' maxlength='10' >
              </td></tr>
		     <tr> <td width='24%'><font>Wali</font></td>
              <td width='76%'> <input type='text' name='wali' size='30' value='$row[wali]' maxlength='50' >
              </td></tr>
			  			<tr> 
              <td width='24%'><font>File Foto</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' > <font>Format file JPG, ukuran 150px x 180px <br>
				<img src='../images/siswa/$row[user_id].jpg' width='110' height='130' >
              </td>
            </tr>";
				
              echo"<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"siswa_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value=\"$id\">
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
		                 $m=date("m");  $d=date("d");  $y=date("Y");
  echo '<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "mm/dd/yyyy";
dptgl.anchor = "anctgl";
dptgl.initialize();
</script>';
  
 }
  function siswa_hap() {
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))
		{
			$sql="delete from t_siswa where user_id='". mysql_escape_string($key)."'";
			//if ($row['file']!='') {
			$nfile="../images/siswa/".$key.".jpg";
			if (file_exists($nfile)) {
			//unlink('../images/berita/gb'.$msgid.'.gif');
				unlink("../images/siswa/".$key.".jpg");
			}
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }

 }
function carisiswa() {
  $hal=$_GET['hal'];
  $nama=$_GET['nama'];
  if ($nama=='') $nama='0';
$brs=50;
  $byk_result1=mysql_query("select * from t_siswa where nama like'%". mysql_escape_string($nama)."%'");
  $byk=mysql_num_rows($byk_result1);
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
  		
  $query = "SELECT * from t_siswa where nama like'%". mysql_escape_string($nama)."%' order by nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	

  echo "<form action='admin.php' method='get' name='siswa' ><table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% > <tr><td bgcolor='#999999' ><font><center>--- Daftar siswa---</center><br>";
  echo " Pencarian Nama Siswa : <input type=text name='nama' ></font>&nbsp;&nbsp;<input type='submit' value=' Cari' ><input type=hidden name=mode value=carisiswa ></td></tr>";
  if ($jml!=0) {
  echo "<tr><td ><center><font><a href='admin.php?mode=carisiswa&nama=$nama&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=carisiswa&nama=$nama&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=carisiswa&nama=$nama&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "</table></form><form action='admin.php' method=\"post\" name='siswa2' >
  <table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >";
  echo "<tr><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Kelas</center></font></td>
  <td><font><center>Member Siswa</center></font></td><td><font><center>Member Ortu</center></font></td>
  <td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[1].elements.length;i++) {
     var e = document.forms[1].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  
   	$wali="<a href='admin.php?mode=sismember_tam&nis=$row[user_id]' title='klik untuk daftar' >Daftar</a>";
    $query = "SELECT nama,userid,email,password FROM t_member WHERE nis='$row[user_id]' and kategori='1'"; 
     $result = mysql_query ($query) or die (mysql_error()); 
    if($r = mysql_fetch_array($result)) {
  		$wali='<a href="admin.php?mode=sismember_edit&id='.$r[userid].'" title="Klik untuk edit member." >'.$r[email].'</a>';
  	} 
   	$wali2="<a href='admin.php?mode=ortu_tam&nis=$row[user_id]'>Daftar</a>";
    $query = "SELECT nama,userid FROM t_member WHERE nis='$row[user_id]' and kategori='2'"; 
     $result = mysql_query ($query) or die (mysql_error()); 
    if($r = mysql_fetch_array($result)) {
  		$wali2='<a href="admin.php?mode=ortu_edit&id='.$r[userid].'" title="Klik untuk edit member." >'.$r[email].'</a>';
  	}
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[user_id]</font></td>
	<td width='30%' ><font>$row[nama]</font></td>
	<td width='10%' align='center'><font>$row[kelas]</font></td>
	<td width='10%' align='center'><font>$wali</font></td>
	<td width='10%' align='center' ><font>$wali2</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[user_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><input type=\"hidden\" name=\"mode\" value=\"siswa_hap\">
  <input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'> Checklist Semua <input type=\"submit\" value=\"Hapus\"> </form><br><font >";
  
 }
 
 
function alumni() {
include "koneksi.php";
$hal=$_GET['hal'];
$brs=50;
  $byk_result1=mysql_query("select * from t_siswa where kelas='Alumni'");
  $byk=mysql_num_rows($byk_result1);
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
  		
  $query = "SELECT * from t_siswa where kelas='Alumni' order by nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\" name='siswa'>";
    echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100%>
  <tr><td bgcolor='#999999' colspan='5' ><font><center>--- Daftar Status Alumni ---</center><br>";
  if ($jml!=0) {
  echo "<tr><td colspan='5'><center><font><a href='admin.php?mode=alumni&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=alumni&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=alumni&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Kelas</center></font></td>
  <td><font><center>Update Status</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[user_id]</font></td>
	<td width='40%' ><font>$row[nama]</font></td>
	<td width='10%' ><font>$row[kelas]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[user_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font><br>
  Mengupdate status siswa menjadi alumni pada sistem komunitas (member).<br>
  Tahun Angkatan <input type=text name='tahun' value='".date("Y")."' size='10'>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"alumni_update\"> <input type=\"submit\" value=\"Update\"></form>
             </font><br><br>";
  
  }
function alumni_update() {
include "koneksi.php";
$id=$_POST['id'];
$tahun=$_POST['tahun'];
	if (!empty($id))  {
	  	while (list($key,$value)=each($id))  {
			$sql="update t_member set kelas='". mysql_escape_string($tahun)."',ket='Alumni',kategori='0' where username='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql);
			$sql="delete from t_siswa where user_id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	}

}

function naikkelas() {
include "koneksi.php";

  $klsasal=$_GET['klsasal'];
  $klsakhir=$_GET['klsakhir'];
  $program1=$_GET['program1'];
  $program2=$_GET['program2'];
  
  if ($klsasal=='') $klsasal='X - 1';
  if ($klsakhir=='') $klsakhir='X - 1';
  if($program1=='') $program1='-';	
  if($program2=='') $program2='-';	
  $query = "SELECT * from t_siswa where kelas='". mysql_escape_string($klsasal)."' order by nama "; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\" name='naik'>";
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
      $data .= 'Jurusan/Prog. Keahlian <select name="program1" onchange="document.location.href=\'admin.php?mode=naikkelas&klsasal=\'+document.naik.klsasal.value+\'&klsakhir=\'+document.naik.klsakhir.value+\'&program1=\'+document.naik.program1.value+\'&program2=\'+document.naik.program2.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program1) $data .= "<option value='$al[program]' selected>$al[program]</option>";
      	else $data .= "<option value='$al[program]' >$al[program]</option>";
      }
      $data .= "</select> &nbsp;&nbsp;";
  }
  else echo "<input type=hidden name='program1' value='-'/>";
  
  $data .='<select name=klsasal onchange="document.location.href=\'admin.php?mode=naikkelas&klsasal=\'+document.naik.klsasal.value+\'&klsakhir=\'+document.naik.klsakhir.value+\'&program1=\'+document.naik.program1.value+\'&program2=\'+document.naik.program2.value" >';
  $data .="<option value='Alumni'>Alumni</option>";
	$sql="select * from t_kelas where program='". mysql_escape_string($program1)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($klsasal==$row[kelas]) $data .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data.='</select>';
  if ($cmstingkat=='sma' or $cmstingkat=='smk') {
	  $data2 .= 'Jurusan/Prog. Keahlian <select name="program2" onchange="document.location.href=\'admin.php?mode=naikkelas&klsasal=\'+document.naik.klsasal.value+\'&klsakhir=\'+document.naik.klsakhir.value+\'&program1=\'+document.naik.program1.value+\'&program2=\'+document.naik.program2.value">';
      $sql2="select * from t_programahli order by idprog";
      $my=mysql_query($sql2);
      while($al=mysql_fetch_array($my)) {
      	if ($al[program]==$program2) $data2 .= "<option value='$al[program]' selected>$al[program]</option>";
      	else $data2 .= "<option value='$al[program]' >$al[program]</option>";
      }
      $data2 .= "</select> &nbsp;&nbsp;";
   }
   else $data2 .= "<input type=hidden name='program2' value='-'/>";
   
	$data2.='<select name=klsakhir onchange="document.location.href=\'admin.php?mode=naikkelas&klsasal=\'+document.naik.klsasal.value+\'&klsakhir=\'+document.naik.klsakhir.value+\'&program1=\'+document.naik.program1.value+\'&program2=\'+document.naik.program2.value" >';
	$data2 .="<option value='Alumni'>Alumni</option>";
	$sql="select * from t_kelas where program='". mysql_escape_string($program2)."' order by kelas";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		if ($klsakhir==$row[kelas]) $data2 .="<option value='$row[kelas]' selected>$row[kelas]</option>";
		else $data2 .="<option value='$row[kelas]'>$row[kelas]</option>";
	}
	$data2.="</select>";
    
    if ($klsakhir=='Alumni') {
        $thn = date("Y");
        $data2.=" <select name='tahun' >";
        for($k=$thn-2;$k<=$thn+3;$k++) {
            if ($thn==$k) $data2 .="<option value='$k' selected >$k</option>";
            else $data2 .="<option value='$k' >$k</option>";
        }
        $data2 .="</select>";
    }
  echo "<br><table cellspacing='0' cellpadding='5' width=100% border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='2' ><font><center>--- Kenaikan Kelas ---</center><br></td></tr>
  <tr><td><font>Pilih Data Asal <br>$data <input type=\"submit\" value=\"Pindah\"></td>
  <td><font>Pilih Data Tujuan <br>$data2</font></td></tr>";
 
  echo "<tr><td width=50% valign='top'><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <table border=1 cellspacing='0' cellpadding='0' bordercolor='#000000'>
  <tr bgcolor=dedede><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Pilih</center></font></td></tr>";
  $j=1;
    echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.naik.elements.length;i++) {
     var e = document.naik.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='8%' ><font>$row[user_id]</font></td>
	<td width='50%' ><font>$row[nama]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[user_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table></td><td valign='top'><table border=1 cellspacing='0' cellpadding='3' bordercolor='#000000'>
  <tr bgcolor=dedede><td><font><center>No</center></font></td><td><font><center>NIS</center></font></td>
  <td><font><center>Nama</center></font></td></tr>";
  $query2 = "SELECT * from t_siswa where kelas='". mysql_escape_string($klsakhir)."' order by nama "; 
  $q= mysql_query ($query2);
    $j=1;
  while ($r = mysql_fetch_array($q))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='8%' ><font>$r[user_id]</font></td>
	<td width='50%' ><font>$r[nama]</font></td>"; 
	$j++;
  }  
  echo "</table></td></tr></table>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"pindahkelas\">
                <input type=\"submit\" value=\"Pindah\"></form><br>";
  
  }
  
 function pindahkelas() {
 	include "koneksi.php";
	$klsasal=$_POST['klsasal'];
	$klsakhir=$_POST['klsakhir'];
	$id=$_POST['id'];
	$tahun = $_POST['tahun'];
    
	if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))
		{
			$sql="update t_siswa set kelas='". mysql_escape_string($klsakhir)."' where user_id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
            
			//$sql="insert into t_niskelas (nis,kelas) values ( '". mysql_escape_string($key)."','". mysql_escape_string($klsasal)."')";
			//$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
                        
            if ($klsakhir=='Alumni') {
             	 $sql="update t_member set kelas='".$tahun."',ket='". mysql_escape_string($klsakhir)."' where nis='". mysql_escape_string($key)."'";
			     $mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
            }
            else {
              	 $sql="update t_member set kelas='".$klsakhir."' where nis='". mysql_escape_string($key)."'";
			     $mysql_result=mysql_query($sql) or die ("Query failed - Mysql");                
            }
		}
	 }
	 echo "<meta http-equiv=\"refresh\" content=\"1;url=admin.php?mode=naikkelas&klsasal=$klsasal&klsakhir=$klsakhir\">\n";
 }

} //akhir
?>