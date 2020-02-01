<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
// -------------------- fungsi berhubungan dengan guru-----------------
class guruclass {
//************************************** data guru mengajar **************************//
function mengajar_detail() {
include "koneksi.php";
  $nip =$_GET['nip'];
$i=1;
  	$sql1="select nama from t_staf where nip='". mysql_escape_string($nip)."'";
  $mysql=mysql_query($sql1);
  $r=mysql_fetch_array($mysql);
  $nama=$r[nama];
  echo "<font><b><center> --- Daftar Guru Mengajar Detail ---- </center>
  <br></b><form action='admin.php' method=\"post\" name=mengajar >
  <table border='1' bordercolor='#000000' cellpadding='2' cellspacing='0' width='100%'>
  <tr><td colspan=5 ><font><b>NIP : $nip <br>Nama : $nama</td></tr>
  <tr bgcolor='#dcddcc'><td><font><b>No</td><td><font><b>Pelajaran</td><td><font><b>Kelas</td><td><font><center><b>Edit</td><td><font><b>Hapus</td></tr>";
  $sql="select * from t_mengajar where nip='$nip' order by pel,kelas";
  $mysql_result=mysql_query($sql);
   echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while($row=mysql_fetch_array($mysql_result)) {
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td><td><font>$row[pel]</td><td><font>$row[kelas]</td><td><center><font><a href='admin.php?mode=edit_mengajar&id=$row[idajar]'><img src='../images/edit.gif' border=0></a></td><td><input type='checkbox' name='kd[$row[idajar]]' value='on'></td>
	</tr>";
 	$i++;
 }
  echo"</table><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=hidden name=mode value='del_mengajar' ><input type=hidden name=nip value='$nip' >
<input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=tam_mengajar&nip=$nip'>Tambah Mengajar</a> 
| <a href='admin.php?mode=mengajar'>Lihat Mengajar</a> |";

}

function mengajar() {
include("koneksi.php");
  $hal=$_GET['hal'];
$brs=30;
  $byk_result1=mysql_query("select distinct(nip) from t_mengajar");
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
  		
  $query = "SELECT distinct(nip) from t_mengajar order by nip  LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data info di Database <br><a href='admin.php?mode=tam_mengajar'>Tambah Guru Mengajar</a></font>"));
  // tambah alan untuk delete multiple	

  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999' colspan='5' align='center'><font>--- Daftar Guru Mengajar ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='5'><center><font><a href='admin.php?mode=mengajar&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=mengajar&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=mengajar&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>NIP</center></font></td>
  <td><font><center>Nama</center></font></td> <td><font><center>Pelajaran/Kelas</center></font></td> 
  <td><font><center>Edit/Tambah kelas</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
   $sql1="select nama from t_staf where nip='$row[nip]'";
  $mysql=mysql_query($sql1);
  $r=mysql_fetch_array($mysql);
  $nama=$r[nama];
  $sql1="select distinct(pel) as pel,nip,kelas from t_mengajar where nip='$row[nip]' group by nip,kelas order by pel";
  $mysql=mysql_query($sql1);
  $pel='';
  while($r=mysql_fetch_array($mysql)) {
	$pel .=$r[pel]."($r[kelas]),";
  }
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[nip]</font></td>
	<td width='20%' ><font>$nama</font></td>
	<td width='30%' ><font>$pel</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=mengajar_detail&nip=<?php echo $row[nip]; ?>" title="Klik untuk edit data"><img src="../images/edit.gif" border="0" ></a></td>
  </tr>
  <?php
  }  
  echo "</table><br><font color=red class='important'></font>";
  echo "<br><font>| <a href='admin.php?mode=tam_mengajar'>Tambah Mengajar</a> | </font>";

}

function edit_mengajar() {
  include "koneksi.php";
  $save=$_GET['save'];
  $id=$_GET['id'];
  $nip=$_GET['nip'];
  $kelas=$_POST['kelas'];
  $pel=$_POST['pel'];
  $program=$_GET['program'];
  
  if ($save=="") {
  $sql="select * from t_mengajar where idajar='". mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
  $nip=$row[nip];
  if ($program=='' ) $program=$row[program];
  //else $program=$row[program];
	echo"<font > <b>----> Perubahan Data Mengajar </b><br><br><form action='admin.php?mode=edit_mengajar&save=1&id=$id&nip=$row[nip]' method=\"post\" name=\"f1\" ><font>";
    if ($cmstingkat=='sma' or $cmstingkat=='smk') {
    	echo 'Jurusan/Program &nbsp;: <select name=program onchange="document.location.href=\'admin.php?id='.$id.'&mode=edit_mengajar&program=\'+document.f1.program.value">';
    	  $sql2="select * from t_programahli order by idprog";
      	$my=mysql_query($sql2);
     	 while($al=mysql_fetch_array($my)) {
    	 	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      		else echo "<option value='$al[program]' >$al[program]</option>";
      	}
  	     echo "</select> &nbsp;&nbsp;<br>";
    }	
    else echo "<input type=hidden name=program value='-'/>";
    
    echo"Pelajaran &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <select name='pel'>";
	$mysql=mysql_query("select * from t_pelajaran where program='". mysql_escape_string($program)."' or program='-' order by program,pel");
  	while($r=mysql_fetch_array($mysql)) {
		if($r[pel]==$row[pel]) echo "<option value='$r[pel]' selected>$r[pel]</option>";
		else echo "<option value='$r[pel]' >$r[pel]</option>";
	}
 	echo"</select><br>Kelas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <select name='kelas'>";
	$mysql=mysql_query("select * from t_kelas where program='". mysql_escape_string($program)."' order by kelas");
  	while($r=mysql_fetch_array($mysql)) {
		if($r[kelas]==$row[kelas]) echo "<option value='$r[kelas]' selected>$r[kelas]</option>";
		else echo "<option value='$r[kelas]' >$r[kelas]</option>";
	}
	echo"</select><br>";
		echo"Nama Guru &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ";
	  echo " <select name='nip' >";
    $sql2="select nip,nama from t_staf order by nama";
    $my=mysql_query($sql2);
    while($al=mysql_fetch_array($my)) {
		if ($al[nip]==$nip) echo "<option value='$al[nip]' selected>$al[nama]</option>";
  	  	else echo "<option value='$al[nip]' >$al[nama]</option>";
    }
    echo "</select><br>";	
	echo "<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value=' Simpan ' ></form>";
  }
  else {
  $sql = "UPDATE t_mengajar SET kelas='".mysql_escape_string($kelas)."',nip='".mysql_escape_string($nip)."',pel='".mysql_escape_string($pel)."' WHERE idajar='".mysql_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Perubahan Guru Mengajar berhasil<br>Silahkan pilih menu kembali !!! <br><br>
  | <a href='admin.php?mode=mengajar'>Lihat Mengajar</a> | <a href='admin.php?mode=tam_mengajar'>Tambah Mengajar</a> | </font>"; 
  }
  
}
  //hapus 
function del_mengajar() {
include("koneksi.php");
$kd=$_POST['kd'];
  if (!empty($kd)) {
	  	while (list($key,$value)=each($kd)) {
			$sql="delete from t_mengajar where idajar='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
  }
} 

function tam_mengajar() {
  include "koneksi.php";
  $save=$_GET['save'];
  $kelas=$_GET['kelas'];
  $nip=$_GET['nip'];
  $pel=$_GET['pel'];
  $program=$_GET['program'];
  
  if ($save=="") {
  
  if ($program=='') $program='-';
  $sql1="select nama from t_staf where nip='$nip'";
  $mysql=mysql_query($sql1);
  $r=mysql_fetch_array($mysql);
  $nama=$r[nama];
	echo"<font > <b>----> Perubahan Data Mengajar </b><br><br><form action='admin.php' method='get' name=\"f1\" ><font><input type=hidden name='mode' value='tam_mengajar' >
	<input type=hidden name='save' value='1' ><input type=hidden name='nip' value='$nip' >";
    if ($cmstingkat=='sma' or $cmstingkat=='smk') {
    	echo 'Jurusan/Program &nbsp;: <select name=program onchange="document.location.href=\'admin.php?mode=tam_mengajar&program=\'+document.f1.program.value" >';
    	  $sql2="select * from t_programahli order by idprog";
      	$my=mysql_query($sql2);
     	while($al=mysql_fetch_array($my)) {
    	 	if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
      		else echo "<option value='$al[program]' >$al[program]</option>";
      	}
      	echo "</select> &nbsp;&nbsp;<br>";	
    }
    else  echo "<input type=hidden name=program value='-'/>";
    
    echo"Pelajaran &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <select name='pel'>";
	$mysql=mysql_query("select * from t_pelajaran where program='". mysql_escape_string($program)."' or program='-' order by program,pel");
  	while($r=mysql_fetch_array($mysql)) {
		echo "<option value='$r[pel]' >$r[pel]</option>";
	}
 	echo"</select><br>Kelas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <select name='kelas'>";
	$mysql=mysql_query("select * from t_kelas  where program='". mysql_escape_string($program)."' order by kelas");
  	while($r=mysql_fetch_array($mysql)) {
		echo "<option value='$r[kelas]' >$r[kelas]</option>";
	}
	echo"</select><br>";
		echo"Nama Guru &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ";
	echo " <select name='nip' >";
    $sql2="select nip,nama from t_staf  order by nama";
    $my=mysql_query($sql2);
    while($al=mysql_fetch_array($my)) {
		if ($nip==$al[nip]) echo "<option value='$al[nip]' selected>$al[nama]</option>";
  	  	else echo "<option value='$al[nip]' >$al[nama]</option>";
    }
    echo "</select><br>";	
	echo "<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";
  }
  else {
  $sql = "insert into t_mengajar (kelas,nip,pel) values ('".mysql_escape_string($kelas)."','".mysql_escape_string($nip)."','".mysql_escape_string($pel)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal 1");
  echo "<font>Penambahan mengajar berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=mengajar'>Lihat Mengajar</a> | <a href='admin.php?mode=tam_mengajar'>Tambah Mengajar</a> |</font>"; 
  }
 
}
function guru() {
include "koneksi.php";
$hal=$_GET['hal'];
$nama=$_GET['nama'];
$brs=30;
if ($nama=='') $byk_result1=mysql_query("select * from t_staf ");
else $byk_result1=mysql_query("select * from t_staf where nama like'%".$nama."%'");

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
  		
if ($nama=='')  $query = "SELECT * from t_staf order by nama LIMIT ".$awal.",".$brs.""; 
else $query = "SELECT * from t_staf where nama like'%".$nama."%' order by nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data guru di Database <br><a href='admin.php?mode=guru_tam'>Tambah Guru</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"get\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999' align='center'><font>--- Daftar Guru---</font></td></tr>
  <tr><td bgcolor='#999999'><font>Pencarian Nama Guru : <input type='text' name='nama' >&nbsp;&nbsp;
  <input type=submit value=' Cari ' ><input type=hidden name=mode value='guru' ></font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td ><center><font><a href='admin.php?mode=guru&nama=$nama&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=guru&nama=$nama&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=guru&nama=$nama&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "</table></form> <form action='admin.php' method=\"post\"><table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td><font><center>No</center></font></td><td><font><center>NIP</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Kategori</center></font></td>
  <td><font><center>Username Member</center></font></td>
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
  if ($row[kategori]=='1') $kat='TU';
  else $kat='Guru';
	  $wali="<center><a href='admin.php?mode=guru_member&nip=$row[nip]' title='Daftar untuk mendapatkan username member'>Daftar</a>";
		$nowali="0";$edit="-";
		$query = "SELECT nama,userid,username,password FROM t_member WHERE nis='$row[nip]'"; 
	  $result = mysql_query ($query) or die (mysql_error()); 
	  if($r = mysql_fetch_array($result)) {
	  $wali ="<a href='admin.php?mode=guru_member&nip=$row[nip]&edit=1' title='klik untuk edit username member'>$r[username]</a>";
     }
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[nip]</font></td>
	<td width='25%' ><font>$row[nama]</font></td>
	<td width='5%' ><font>$kat</font></td>
	<td width='20%' ><font>$wali</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=guru_edit&id=<?php echo $row[user_id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[user_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"guru_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=guru_tam'>Tambah Guru</a> |</form></font><br><br>";
  
  }
function guru_tam() {
  
  echo "<script language='javascript' src='../functions/ssCalendar.js'></script>";
  echo "<form action='admin.php' method=\"post\" name=\"f1\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
           <tr><td colspan='2'><font><b>Pengisian Guru</b><font></td>	</tr>
            <tr> <td width='24%'><font>Nama</font></td>
              <td width='76%'> <input type='text' name='nama' size='30' maxlength='30' >
              </td></tr>
            <tr> <td width='24%'><font>NIP</font></td>
              <td width='76%'> <input type='text' name='nip' size='30' maxlength='25' >
              </td></tr>
                <tr> <td width='24%'><font>NUPTK</font></td>
              <td width='76%'> <input type='text' name='nuptk' size='30' maxlength='20' >
              </td></tr>
			     <tr> <td width='24%'><font>Kategori</font></td>
              <td width='76%'> <select name=kategori><option value='0' seleccted>Guru</option>
			  <option value='1'>Pegawai TU</option></select>
              </td></tr>
			<tr> <td width='24%'><font>Kelamin</font></td>
              <td width='76%'> <select name=kelamin><option value='L' selected>Laki-laki</option>
			  <option value='P'>Perempuan</option></select>
              </td></tr>
            <tr> <td width='24%'><font>Alamat</font></td>
              <td width='76%'> <input type='text' name='alamat' size='50' maxlength='60' >
              </td></tr>
			<tr> <td width='24%'><font>Tugas Tambahan</font></td>
              <td width='76%'> <input type='text' name='tugas' size='30' maxlength='30' >
              </td></tr>
	            <tr> <td width='24%'><font>Telp</font></td>
              <td width='76%'> <input type='text' name='telp' size='15' maxlength='15' >
              </td></tr>
            <tr> <td width='24%'><font>HP</font></td>
              <td width='76%'> <input type='text' name='hp' size='30' maxlength='15' >
              </td></tr>
	            <tr> <td width='24%'><font>Email</font></td>

              <td width='76%'> <input type='text' name='email' size='30' maxlength='50' >
              </td></tr>
	            <tr> <td width='24%'><font>Pelajaran</font></td>
              <td width='76%'> <input type='text' name='pelajaran' size='30' maxlength='50' >
              </td></tr>
		<tr> <td width='24%'><font>Tempat Lahir </font></td>
              <td width='76%'> <input type='text' name='tmp_lahir' size='20' maxlength='50' >
			  </td></tr>
		            <tr> <td width='24%'><font>Tanggal Lahir </font></td>
              <td width='76%'>";
echo '<input name="tgl_lahir" type="text" id="tgl" value="'.$tgl1.'" readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
             echo " <font>Format tanggal bulan/hari/tahun</td></tr>
		            <tr> <td width='24%'><font>Pangkat/Gol</font></td>
              <td width='76%'> <input type='text' name='pangkat' size='30' maxlength='50' >
              </td></tr>
			  			<tr> 
              <td width='24%'><font>File Foto</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' > <font>Format file JPG, ukuran 150px x 180px
              </td>
            </tr>
			<tr> <td width='24%'><font>Profil/Prestasi</font></td>
              <td width='76%'> <textarea name='prestasi' cols=\"70\" rows=\"10\" ></textarea>
              </td></tr>";
				
              echo"<tr> <td colspan='2'><input type='reset' value='Ulang' > &nbsp;
                <input type=\"hidden\" name=\"mode\" value=\"guru_save\">
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
function guru_save() {
	//if (substr($tgl_lahir,4,1)=="-") $tgl_lahir= substr($tgl_lahir,6,2)."/".substr($tgl_lahir,9,2)."/".substr($tgl_lahir,0,4);
$edit=$_POST['edit'];$nama=$_POST['nama'];$nip=$_POST['nip'];
$kelamin=$_POST['kelamin'];$alamat=$_POST['alamat'];
$tugas=$_POST['tugas'];$telp=$_POST['telp'];$hp=$_POST['hp'];
$email=$_POST['email'];$pelajaran=$_POST['pelajaran'];$tgl_lahir=$_POST['tgl_lahir'];
$tmp_lahir=$_POST['tmp_lahir'];$pangkat=$_POST['pangkat'];
$kategori=$_POST['kategori'];$prestasi=$_POST['prestasi'];
$FileToUpload = $_FILES['FileToUpload']['tmp_name'];
$nuptk = $_POST['nuptk'];
	if ($edit=='') {	
	$sql = "SELECT max(user_id) AS total FROM t_staf";
     if(!$r = mysql_query($sql))
       die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1; 
	  $prestasi = htmlentities($prestasi);
 	$prestasi = nl2br($prestasi);
	 	 $sql = "INSERT INTO t_staf (user_id,nama,nip,kelamin,alamat,tugas,telp,hp,email,pelajaran,tgl_lahir,
          tmp_lahir,pangkat,kategori,profilstaf,nuptk) 
				VALUES ('".$total."', '".mysql_escape_string($nama)."', '".mysql_escape_string($nip)."', '".mysql_escape_string($kelamin)."','".mysql_escape_string($alamat)."', '".mysql_escape_string($tugas)."','".mysql_escape_string($telp)."','".mysql_escape_string($hp)."','".mysql_escape_string($email)."','".mysql_escape_string($pelajaran)."','".mysql_escape_string($tgl_lahir)."','".mysql_escape_string($tmp_lahir)."','".mysql_escape_string($pangkat)."',
                '".mysql_escape_string($kategori)."','".mysql_escape_string($prestasi)."','".mysql_escape_string($nuptk)."')";
	}
	else {
		$total=$edit;
		$prestasi = htmlentities($prestasi);
 		$prestasi = nl2br($prestasi);
		$sql = "UPDATE t_staf SET nama='".mysql_escape_string($nama)."',nip='".mysql_escape_string($nip)."',kelamin='".mysql_escape_string($kelamin)."',alamat='".mysql_escape_string($alamat)."',tugas='".mysql_escape_string($tugas)."',
		telp='".mysql_escape_string($telp)."',hp='".mysql_escape_string($hp)."',email='".mysql_escape_string($email)."',pelajaran='".mysql_escape_string($pelajaran)."',tgl_lahir='".mysql_escape_string($tgl_lahir)."',tmp_lahir='".mysql_escape_string($tmp_lahir)."',
		pangkat='".mysql_escape_string($pangkat)."',kategori='".mysql_escape_string($kategori)."', nuptk='".mysql_escape_string($nuptk)."',profilstaf='".mysql_escape_string($prestasi)."' WHERE user_id = '$edit'";	
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
	$newfile= "../images/staf/$nip.jpg";
	if (file_exists($newfile)) unlink($newfile);
    copy($FileToUpload, "../images/staf/$nip.jpg");
		}
	else {
		$tdk="File foto yang diimputkan tidak ada";
		$newfile='';
		}
	}
	
      echo "<font><b>$tdk</b><br>Penambahan Database telah berhasil ! <br> Silahkan Anda pilih menu sebelah kiri yang diinginkan !.<br>| <a href='admin.php?mode=guru'>Lihat Guru</a> | <a href='admin.php?mode=guru_tam'>Tambah Guru</a> |</font> ";
  
 }
 function guru_edit() {
  $id=$_GET['id'];
  	 $sql="select * from t_staf where user_id=".mysql_escape_string($id)."";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);
	if($row[kategori]=='0') $k1="selected";
	else $k2="selected";
	if($row[kelamin]=='L') $s1="selected";
	else $s2="selected";	
echo "<script language='javascript' src='../functions/ssCalendar.js'></script>";
  echo "<form action='admin.php' method=\"post\" name=\"f1\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
           <tr><td colspan='2'><font><b>Pengisian Guru</b><font></td>	</tr>
            <tr> <td width='24%'><font>Nama</font></td>
              <td width='76%'> <input type='text' name='nama' value='$row[nama]' size='30' maxlength='30' >
              </td></tr>
            <tr> <td width='24%'><font>NIP</font></td>
              <td width='76%'> <input type='text' name='nip' value='$row[nip]' size='30' maxlength='25' >
              </td></tr>
                <tr> <td width='24%'><font>NUPTK</font></td>
              <td width='76%'> <input type='text' name='nuptk' value='$row[nuptk]' size='30' maxlength='20' >
              </td></tr>
			     <tr> <td width='24%'><font>Kategori</font></td>
              <td width='76%'> <select name=kategori><option value='0' $k1>Guru</option>
			  <option value='1' $k2>Pegawai TU</option></select>
              </td></tr>
			<tr> <td width='24%'><font>Kelamin</font></td>
              <td width='76%'> <select name=kelamin><option value='L' $s1>Laki-laki</option>
			  <option value='P' $s2>Perempuan</option></select>
              </td></tr>
            <tr> <td width='24%'><font>Alamat</font></td>
              <td width='76%'> <input type='text' name='alamat' size='50' value='$row[alamat]' maxlength='60' >
              </td></tr>
			<tr> <td width='24%'><font>Tugas Tambahan</font></td>
              <td width='76%'> <input type='text' name='tugas' size='30' value='$row[tugas]' maxlength='30' >
              </td></tr>
	            <tr> <td width='24%'><font>Telp</font></td>
              <td width='76%'> <input type='text' name='telp' size='15' value='$row[telp]' maxlength='15' >
              </td></tr>
            <tr> <td width='24%'><font>HP</font></td>
              <td width='76%'> <input type='text' name='hp' size='30' value='$row[hp]' maxlength='15' >
              </td></tr>
	            <tr> <td width='24%'><font>Email</font></td>
              <td width='76%'> <input type='text' name='email' size='30' value='$row[email]' maxlength='50' >
              </td></tr>
	            <tr> <td width='24%'><font>Pelajaran</font></td>
              <td width='76%'> <input type='text' name='pelajaran' size='30' value='$row[pelajaran]' maxlength='50' >
              </td></tr>
		            		<tr> <td width='24%'><font>Tempat Lahir </font></td>
              <td width='76%'> <input type='text' name='tmp_lahir' value='$row[tmp_lahir]' size='20' maxlength='50' >
			  </td></tr>
		            <tr> <td width='24%'><font>Tanggal Lahir </font></td>
              <td width='76%'>";
echo '<input name="tgl_lahir" type="text" id="tgl" value="'.$row[tgl_lahir].'" readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
            echo "<font>Format tanggal bulan/hari/tahun </td></tr>
		            <tr> <td width='24%'><font>Pangkat/Gol</font></td>
              <td width='76%'> <input type='text' name='pangkat' size='30' value='$row[pangkat]' maxlength='50' >
              </td></tr>
			  			<tr> 
              <td width='24%'><font>File Foto</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' > <font>Format file JPG ukuran 150px x 180px
              </td>
            </tr>
			<tr> <td width='24%'><font>Profil/Prestasi</font></td>
              <td width='76%'> <textarea name='prestasi' cols=\"70\" rows=\"10\" >$row[profilstaf]</textarea>
              </td></tr>
			  <tr> <td width='24%'><font></font></td>
              <td width='76%'> <img src='../images/staf/$row[nip].jpg' width='110' height='130'>
              </td></tr>";
				
              echo"<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"guru_save\"><input type='reset' value='Ulang' > &nbsp;
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

function guru_hap() {
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))
		{
		  	 $sql="select * from t_staf where user_id='". mysql_escape_string($key)."'";
			if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
			$row=mysql_fetch_array($query);
			$nip =$row[nip];
			$sql="delete from t_staf where user_id='". mysql_escape_string($key)."'";
			$nfile="../images/staf/$nip.jpg";
			if (file_exists("$nfile")) {
				unlink($nfile);
				echo $nfile;
			}
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }
 }
 
function guru_member() {
  include "koneksi.php";
	$nip=$_GET['nip'];
	$edit=$_GET['edit'];
  if ($edit=='1') {
	  $query = "SELECT * FROM t_member WHERE nis='". mysql_escape_string($nip)."'"; 
	  $result = mysql_query ($query) or die (mysql_error()); 
	  $row = mysql_fetch_array($result);
	  $email=$row[email];$nama=$row[nama];
	  if($row[kelamin]=='L') $kel='m';
	  else $kel='f';
	  $telp=$row[telp];$alamat=$row[alamat];
	  $edit="<input type=hidden name=save value='edit'>";
	  $buat='Mengedit';
	  $edit2="&nbsp;<input type='submit' class='button' name=cancel value=' Hapus '>";
  }
  else {
	   $query = "SELECT * FROM t_staf WHERE nip='". mysql_escape_string($nip)."'"; 
	  $result = mysql_query ($query) or die (mysql_error()); 
	  $row = mysql_fetch_array($result);
	  $email=$row[email];
      $nama=$row[nama];
	  if($row[kelamin]=='L') $kel='m';
	  else $kel='f';
	  $telp=$row[telp];$alamat=$row[alamat];
	  $edit="<input type=hidden name=save value='tambah'>";
	  $buat='Membuat';
	  $edit2="";
  }
  echo "<form name='daftar' action='admin.php?mode=gurumember_save' method='post'  >
	<table border=0 width='100%' cellspacing='0' cellpadding='3' bordercolor='#000000'><tr><td colspan=3 ><FONT class='ver10'>";
	echo "</font></td></tr>
	<tr><td colspan=3 bgcolor='#DEDEDE'><FONT class='ver11'><b>$buat Username Guru</b></font></td></tr>	
		<tr >
		<td align=right valign=top><FONT class='ver10'>Email/Username</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='email' size=20 value='$email' style='width:180; height:20;'>
		<br><FONT class='ver10'>Masukan email yang benar dan valid.</td>
	</tr>
	<tr >
		<td align=right><FONT class='ver10'>Nama Guru</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='nama' value='$nama' size=20 style='width:180; height:20;'></td>
	</tr>";
	echo"	<tr >
		<td align=right><FONT class='ver10'>NIP</font></td>
		<td><FONT class='ver10'>: $nip</td><input type=hidden name='nip' value='$nip'>
	</tr>";	
	echo"
	<tr >
		<td align=right><FONT class='ver10'>Alamat</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name='alamat' value='$alamat' style='width:300; height:20;'></td>
	</tr><tr >
		<td align=right><FONT class='ver10'>Telp/HP</font></td>
		<td><FONT class='ver10'>: <input class='editbox' type=text name=telp size=20 value='$telp' style='width:180; height:20;'></td>
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
	$edit<input type=hidden name=kel value='$kel'>
	<input type='submit' class='button' name=submit value=' Simpan '>&nbsp;&nbsp;
	</td></tr>	
	</table></form><form action='admin.php?mode=gurumember_hap&nip=$nip' method=post >$edit2</form>";
  
}

function gurumember_save() {
include "koneksi.php";
include "../functions/fungsi_pass.php";
$save=$_POST['save'];
$email=$_POST['email'];
$nama=$_POST['nama'];
$nip=$_POST['nip'];
$telp=$_POST['telp'];
$reset=$_POST['reset'];
$alamat=$_POST['alamat'];
$kel=$_POST['kel'];

   $sChars = "/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/";

 if ($email=='') echo "<font<br>&nbsp;Email yang anda masukkan kosong. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif (!preg_match("$sChars",$email)) echo "<font><br>&nbsp;Email yang anda masukan tidak valid. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif ($nama=='') echo "<font><br>&nbsp;Nama Guru masih kosong. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif ($telp=='') echo "<font><br>&nbsp;Telp masih kosong. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 elseif ($alamat=='') echo "<font><br>&nbsp;Alamat masih kosong. Silahkan cek kembali. <a href='#' onclick='window.history.back();return false;' >klik kembali disini</a>";
 else {
  if ($save=='tambah') {
	$pass = rand(1111,9999);
	$password=md5($pass);
  $query = "insert into t_member (nama,kelamin,negara,alamat,telp,username,password,email,pengingat,jawaban,kategori,status,nis,kelas,ket,sekolah) values ('". mysql_escape_string($nama)."','". mysql_escape_string($kel)."','ID','". mysql_escape_string($alamat)."','". mysql_escape_string($telp)."','". mysql_escape_string($email)."','$password','". mysql_escape_string($email)."','1','1','3','1','". mysql_escape_string($nip)."','-','Guru','". mysql_escape_string($nmsekolah)."')"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  if ($_POST['kirimemail']=='on') echo kirimemail($email,$pass,$email);
  echo"Terima kasih, penambahan data Member Guru berhasil dilakukan. <br><b>Username : $email, Password : $pass </b><br>
   | <a href='admin.php?mode=guru' >Lihat Data Guru</a> |";
  }
  else {
   if ($reset=='on') {
	$pass = rand(1111,9999);
	$password=md5($pass);
	$set="password='$password',";
	}
  $query = "update t_member set $set email='". mysql_escape_string($email)."',username='". mysql_escape_string($email)."',telp='". mysql_escape_string($telp)."',nama='". mysql_escape_string($nama)."',alamat='". mysql_escape_string($alamat)."' where nis='". mysql_escape_string($nip)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  if ($_POST['kirimemail']=='on') echo kirimemail($email,$pass,$email);
  echo"Terima kasih, perubahan data Member Guru berhasil dilakukan. <br><b>Username : $email, Password : $pass </b>| <a href='admin.php?mode=guru' >Lihat Data Guru</a> |";
  }
  }
}
  
function gurumember_hap() {
 include "koneksi.php";
 $nip=$_GET['nip'];
	  if ($nip<>'') {
			$sql="delete from t_member where nis='". mysql_escape_string($nip)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
	  }

}

  //************************** data import  guru **********************//
function imguru() {
 
 echo "<table width=98% ><tr bgcolor=dedede><td><font><b>Membuat Format Data Guru<b></td></tr>
 <tr><td><font><form action='../functions/fungsi_excelnilai.php' method='post' >Pilih Format Data <input type=submit value='Format'> <input type=hidden name=format value='guru' ></form><br><br>
 <font><b>File berupa format Execl dengan nama SHEET harus FormatGuru </b></font></td></tr>
 <tr bgcolor=dedede><td><font><b>Import Data Guru</td></tr>
 <tr><td><br><font><form action='../functions/importguru.php' method='post' enctype=\"multipart/form-data\"'>Pilih File Import <input type=file name='excel_file' >&nbsp;<input type=submit value='Import' >
	 <input type=hidden name=st value='1'><br></form></td><tr></table><br>
	 <font> &nbsp;<b>Keterangan</b><br>
	 <ul>
	 <li>Format Tanggal Lahir : bulan/hari/tahun
	 <li>Format Kelamin berupa L atau P 
	 <li>Kolom Status diisi dengan GURU atau TU
     <li>Kolom TUGAS diisi dengan Tugas / jabatan di sekolah misal Kepala sekolah, atau Wakasek, atau Wali kelas
	 <li>Kolom PANGKAT diisi dengan Pangkat/Golongan
	 <li>Apabila ada kosong diisi dengan tanda ( - )
	 <li>Nama tidak boleh mengandung tanda petik satu ( ' )
	 <li>Field NIP harus diisi tidak boleh kosong karena NIP merupakan Primary Key dari Data Guru
	 </ul>";

 
}

} //akhir
?>