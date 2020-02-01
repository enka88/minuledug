<?php
/* program ditambah alan */
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
include('../lib/config.php');
include ("voting_conf.php");

// main admin section
function admin() {
    
	  echo "<font>Selamat Datang di Menu Administrasi $nmsekolah<br><bR><br>Silahkan pilih menu sebelah kiri sesuai dengan keinginan.<br>Admin diberi hak akses terhadap menu sesuai dengan level administrasinya</font>";
	
}

function error($error) {
echo $error;

}
// Use $variable = stripslashes_array($variable_array);

function stripslashes_array($arr = array()) {
        $rs = array();

	while (list($key,$val) = each($arr)) {
		$rs[$key] = stripslashes($val);
	}

	return $rs;
}

/******************************************* Open Table *********************************************/

// global open function
 function RTESafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = trim($strText);
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	
	return $tmpString;
}

function kirimemail($username,$pass,$email) {
include "koneksi.php";
	$wel="welcome".rand(3,1).".jpg";

$message = <<<EOF
<html>
<body>
<table cellSpacing="0" cellPadding="4" bgColor="#A5697B" border="0">
  <tr>
    <td>
    <table cellSpacing="0" cellPadding="0" width="600" bgColor="#A5697B" border="0">
      <tr>
        <td>
        <a href="http://$webhost" target="_blank">
        <img alt="Komunitas $nmsekolah" src="http://$webhost/images/komunitas.jpg" border="0" width="230" height="48"></a>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<table cellSpacing="0" cellPadding="4" bgColor="#A5697B" border="0">
  <tr>
    <td width="600">
    <table cellSpacing="0" cellPadding="10" width="600" bgColor="#ffffff">
      <tr>
        <td><strong>
        <font face="Verdana,Arial,Helvetica,sans-serif" color="#A5697B" size="+1">
        SELAMAT DATANG DI KOMUNITAS <br>
        $nmsekolah</font></strong>
          <table cellSpacing="15" cellPadding="0" width="100%" border="0">
          <tr>
            <td vAlign="top">
            <img alt="Foto Sekolah" src="http://$webhost/images/$wel"></td>
            <td vAlign="top">
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2">
              Terima kasih Anda telah melakukan pendaftar di komunitas $nmsekolah.</font></p>
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2"> Nama : $name<br>
Username : $username<br>
Password : $pass <br>
                    <br>
                   	Silahkan manfaatkan fasilitas komunitas ini untuk kepentingan pendidikan.<br>
Klik dibawah ini untuk login <strong>LOGIN MEMBER</strong> Anda.<br>
                <br>
                <br>
                <a style="font-weight: bold; font-size: 90%; color: #ffffff; font-family: verdana; white-space: nowrap; text-decoration: none; border: 4px solid #f0f0f0; margin: 0px; padding-left: 16px; padding-right: 16px; padding-top: 4px; padding-bottom: 4px; background-color: #7b849c" href="http://$webhost/member/" target="_blank">
                VALIDASI MEMBER</a> <br>
                <br>
&nbsp;</font></p></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <table cellSpacing="0" cellPadding="1" width="100%" align="center" border="0">
          <tr>
            <td bgColor="#A5697B">
            <table cellSpacing="0" cellPadding="0" width="100%" border="0">
              <tr>
                <td bgColor="#f4f4f4">
                <table cellSpacing="0" cellPadding="4" border="0">
                  <tr>
                    <td>
                    <a href="http://$webhost/" target="_blank">
                    <img style="margin-bottom: 5px" alt src="http://$webhost/images/logo231.jpg" align="center" border="1"  width="88" height="88"></a>
                    </td>
                    <td style="line-height: 110%" vAlign="center">
                    <font face="Verdana,Arial,Helvetica,sans-serif" color="#ff9900" size="2">
                    <strong>Terima Kasih.... </strong> <br>
                    <font color="#000000" size="1">$webmail</font> </font></td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<table cellSpacing="0" cellPadding="0" width="600" border="0">
  <tr>
    <td align="middle">
    <a href="http://$webhost" target="_blank">
    </a>
    <br>
    <font face="Verdana,Arial,Helvetica,sans-serif" color="#7b849c" size="-2">
    Copyright 2011 $nmsekolah. All rights reserved. <br>
    $almtsekolah </font></td>
  </tr>
</table>
</body>
</html>
EOF;
   //end of message
//$email ="alanrm82@yahoo.com";
    $headers  = "From: \"Komunitas $nmsekolah\" <$webmail>\r\n";
    $headers .= "Content-type: text/html\r\n";

 	if(!@mail($email, "Konfirmasi Username Member di $nmsekolah", $message, $headers)){
 	  return "Gagal kirim email<br>";
 	}

}


function hakakses($menu) {
include "koneksi.php";
	$query = "SELECT * FROM user_level WHERE userid='".$_SESSION['Admin']['userid']."' and menu='$menu'"; 
  	$result = mysql_query ($query) or die (mysql_error()); 
	$data = mysql_num_rows($result);
return $data;
}

function errordata() {
    echo $_SESSION['Admin']['userid'];
	echo "<br><br><center><table border=1 bordercolor='#000000' width='300' height='120' cellspacing='0'  cellpadding='1'>
	<tr height='30' bgcolor='#0066ff' ><td><b><center>Konfirmasi</center></b></td></tr>
	<tr><td bgcolor='#ffffff' ><center><img src='../images/error.gif' align='left' ><b>Maaf anda tidak berhak mengakses fasilitas ini</a></center></td></tr></table>";
}

function errordatasim() {
    echo $_SESSION['Admin']['userid'];
	echo "<br><br><center><table border=1 bordercolor='#000000' width='300' height='120' cellspacing='0'  cellpadding='1'>
	<tr height='30' bgcolor='#0066ff' ><td><b><center>Konfirmasi</center></b></td></tr>
	<tr><td bgcolor='#ffffff' ><center><img src='../images/error.gif' align='left' ><b>Maaf anda tidak berhak mengakses fasilitas ini karena Anda belum mengaktifkan Fitur SIM Anda</a></center></td></tr></table>";
}
function errordatamember() {
    echo $_SESSION['Admin']['userid'];
	echo "<br><br><center><table border=1 bordercolor='#000000' width='300' height='120' cellspacing='0'  cellpadding='1'>
	<tr height='30' bgcolor='#0066ff' ><td><b><center>Konfirmasi</center></b></td></tr>
	<tr><td bgcolor='#ffffff' ><center><img src='../images/error.gif' align='left' ><b>Maaf anda tidak berhak mengakses fasilitas ini karena Anda belum mengaktifkan Fitur MEMBER Anda</a></center></td></tr></table>";
}

function login_check() {
    $exp_time = $_SESSION["expires_by"];
    if (time() < $exp_time) {
        $timeout = 9000;
    	$_SESSION["expires_by"] = time() + $timeout;
        return true;
    } else {
        unset($_SESSION["expires_by"]);
        return false;
    }
}
/****************************************** Admin Options ********************************************/

class adminclass {

 function deladmin() {
    $result = mysql_query("DELETE FROM user WHERE userid = '". mysql_escape_string($_GET['adminid'])."'");
	$result = mysql_query("DELETE FROM user_level WHERE userid = '". mysql_escape_string($_GET['adminid'])."'");
	return 0;
 }

 function Viewadmin() {
    $hal =$_GET['hal'];
	$userid=$_SESSION['Admin']['userid'];
	//ditambah alan untuk pengaturan halaman
	$brs=30;
  	$byk_result=mysql_query("select * from user WHERE userid <> '". mysql_escape_string($userid)."'");
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
    
	// mengakses database users 
    $query = "SELECT * FROM user WHERE userid <> '". mysql_escape_string($userid)."' order by userid limit ".$awal.",".$brs.""; 
    $query_result_handle = mysql_query ($query) 
    or die (mysql_error()); 


    echo "<table width='100%' cellspacing='0'  cellpadding='1' border=1 ><tr><td bgcolor='cccccc' colspan='7' align=center ><b>Data Administrator</b></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font><a href='admin.php?mode=viewadmin&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=viewadmin&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=viewadmin&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
$i=1;
    echo "<tr bgcolor='#0066ff'><td width=5 ><b>No</b></td><td width=150 ><b>Username</b></td><td  ><b>Fasilitas Menu</b></td>
	<td width=15 ><b>Visit</b></td><td width=150 ><b>Tanggal</b></td><td width=5 ><b>Edit</b></td><td width=5 ><b>Hapus</b></td></tr>";
    while ($row = mysql_fetch_array($query_result_handle))
    {
	$level="";
	$sql="select * from user_level where userid='$row[userid]'";
	$query = mysql_query ($sql);
		while($r= mysql_fetch_array($query)) {
			$level .= $r[menu].", ";
		}
	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">
      <td  >$i</td>
      <td >$row[username]</td>
      <td >$level</td>
      <td >$row[kunjung]</td>
	  <td >$row[waktu]</td>
  <td ><a href='admin.php?mode=editadmin&adminid=$row[userid]'><img src='../images/edit.gif' border=0 ></a></td><td> ";
  ?>
    <a href="admin.php?mode=deladmin&adminid=<?php echo $row[userid]; ?>"><img src="../images/delete.gif" border="0" title="Delete <?php echo $row[username]; ?>"  onClick="return confirm('Anda yakin ?\n'+
                                       '_____________________________________________________________\n\n'+
                                       'Data yang akan dihapus oleh Admins : <?php echo $row[username]; ?> \n'+
                                       'Pilih Ok untuk melanjutkan.');"></a></font></td>
    </tr>
    <?php 
	$i++;
     }  
    echo "</table>";
    echo "<br><br><br>";
    
 }

 function Addadmin() {
    ?>
<form method="post" action="admin.php" name="admin" >
  <table width="100%" border="0" cellpadding="0" cellspacing="6">
    <tr><td colspan="2" bgcolor="#999999"><b>Penambahan Data Admin</b></td></tr>
    <tr><td width="20%"><font face='Verdana' size='2'>Username:</font></td>
		<td ><input type="text" name="newusername" size="30"></td>
    </tr>
    <tr><td ><font face='Verdana' size='2'>Password:</font></td>
        <td ><input type="password" name="newpassword" size="30"></td>
    </tr>
    <tr><td ><font face='Verdana' size='2'>Ulang Password:</font></td>
        <td ><input type="password" name="newpassword2" size="30">
        </td>
    </tr>
    <tr><td ><font face='Verdana' size='2'>Alamat Email:</font></td>
        <td ><input type="text" name="newemail" size="30"> </td>
    </tr>
    <tr><td  valign="top"><font face='Verdana' size='2' >Hak Akses :</font></td>
       <td ><table>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Fitur</b></font></td></tr>
          <tr><td><input name="artikel" type="checkbox" value="on" /> Artikel </td>
          		<td><input name="agenda" type="checkbox" value="on" /> Agenda </td> 
          		<td><input name="berita" type="checkbox" value="on" /> Berita </td> 
          		<td><input name="bukutamu" type="checkbox" value="on" /> Buku Tamu </td></tr>
<?php
if ($cmsmember == "ya") {
echo '
          <tr><td><input name="forum" type="checkbox" value="on" /> Forum Diskusi </td> 
';
}
?>
		  <td><input name="galeri" type="checkbox" value="on" /> Galeri </td>
          		<td><input name="link" type="checkbox" value="on" /> Link Web </td>  
<?php
if ($cmsmember == "ya") {
echo '
          		<td><input name="infoalumni" type="checkbox" value="on" /> Info Alumni </td></tr>
';
}
?>
          <tr><td><input name="infosekolah" type="checkbox" value="on" /> Info Sekolah</td>
              	<td><input name="materiajar" type="checkbox" value="on" /> Materi Ajar </td>
          		<td><input name="kumpulsoal" type="checkbox" value="on" /> Materi Uji </td>
          		<td><input name="silabus" type="checkbox" value="on" /> Silabus </td> </tr> 
          <tr><td><input name="prestasi" type="checkbox" value="on" /> Prestasi </td>      
          		<td><input name="pesandepan" type="checkbox" value="on" /> Pesan Depan </td> 
          		<td><input name="jajak" type="checkbox" value="on" /> Jajak Pendapat </td>    
          		<td><input name="banner" type="checkbox" value="on" /> Banner </td></tr>        
<?php
if ($cmssim == "ya") {
echo '
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>S I M</b></font></td></tr>
          <tr><td><input name="dtnilai" type="checkbox" value="on" /> Data Nilai </td>
          	<td><input name="dtmateri" type="checkbox" value="on" /> Data Materi </td>
            <td><input name="dtbpbk" type="checkbox" value="on" /> Data BP/BK </td>
          	<td><input name="dtabsensi" type="checkbox" value="on" /> Data Absensi </td></tr>
          <tr><td><input name="dtspp" type="checkbox" value="on" /> Data SPP/DSP </td>
            <td><input name="dtlaporan" type="checkbox" value="on" /> Data Laporan </td><td></td><td></td></tr>            
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Setting Admin</b></font></td></tr>
          <tr><td><input name="admin" type="checkbox" value="on" /> Tambah Admin </td>
            <td><input name="profil" type="checkbox" value="on" /> Profil </td>       
          	<td><input name="posisi" type="checkbox" value="on" />  Posisi Menu </td>
          	<td><input name="template" type="checkbox" value="on" /> Template Menu </td></tr>
          <tr><td><input name="gambar" type="checkbox" value="on" /> Gambar Depan </td>
            <td><input name="kategori" type="checkbox" value="on" /> Kategori Link</td>
          	<td><input name="semester" type="checkbox" value="on" /> Semester &amp; Tahun Pelajaran</td>
            <td><input name="program" type="checkbox" value="on" /> Program/Jurusan </td></tr>
          <tr><td><input name="kelas" type="checkbox" value="on" /> Kelas </td>
          	<td><input name="pelajaran" type="checkbox" value="on" /> Pelajaran </td>
            <td></td><td></td></tr>
';
}
?>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Data Guru</b></font></td></tr>
          <tr><td><input name="dtguru" type="checkbox" value="on" />  Data Guru </td>
          	<td><input name="importguru" type="checkbox" value="on" />  Import Guru</td>
            <td><input name="dtmengajar" type="checkbox" value="on" /> Data Mengajar</td><td></td></tr>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Data Siswa</b></font></td></tr>
          <tr><td><input name="dtsiswa" type="checkbox" value="on" />  Data Siswa</td>
          	<td><input name="dtortu" type="checkbox" value="on" />  Data Orang Tua</td>
            <td><input name="importsiswa" type="checkbox" value="on" /> Import Siswa</td>
<?php
if ($cmsmember == "ya") {
echo '
          	<td><input name="membersiswa" type="checkbox" value="on" /> Member Siswa</td></tr>
';
}
?>
			<tr><td><input name="naikkelas" type="checkbox" value="on" /> Naik Kelas</td>
             <td><input name="dtalumni" type="checkbox" value="on" /> Data Alumni</td><td></td><td></td></tr>
<?php
if ($cmsmember == "ya") {
echo '
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Member Komunitas</b></font></td></tr>
          <tr><td><input name="member" type="checkbox" value="on" />  Member</td>
          	<td><input name="koordinator" type="checkbox" value="on" />  Admin/Kepsek</td>
            <td><input name="opini" type="checkbox" value="on" /> Opini </td><td></td></tr>
';
}
?>
		  </table><br />
          &nbsp;<input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
          </td>
        </tr> 
      </table>
      <p> 
        <input type="hidden" name="mode" value="createadmin"><input type='reset' value='Ulang' > &nbsp;
        <input type="submit" name="Submit" value="Simpan">
    </p>
       <script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.admin.elements.length;i++) {
     var e = document.admin.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>
      </form>
    <?php
 }
 function editpersonal() {
 include "koneksi.php";
  $query = "SELECT * FROM user WHERE userid = '".mysql_escape_string($_SESSION['Admin']['userid'])."'"; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  $userinfo = mysql_fetch_array($query_result_handle);
    ?>
    <form method="post" action="admin.php" name="admin" ><font>
      <table width="100%" border="0" cellspacing="6" cellpadding="0" height="99">
        <tr> 
          <td colspan="2" bgcolor="#999999"><b>Perubahan Data Personal</b>
          </td>
        </tr>
        <tr> 
          <td height="29" width="25%"><font>Password Baru: </font></td>
          <td height="29" width="75%"> 
            <input type="password" name="password" size="30"> * (Diisi bila ingin dirubah) 
          </td>
        </tr>
        <tr> 
          <td height="29"><font>Ulang Password Baru: </font></td>
        <td height="29"> 
            <input type="password" name="password2" size="30"> * (Diisi bila ingin dirubah) 
          </td>
        </tr>
        <tr> 
          <td height="29"><font>Alamat E-mail: <font></td>
          <td height="29"> 
            <input type="text" name="email" value="<?php echo $userinfo[email]; ?>" size="30">
          </td>
        </tr>
        <tr>
        <tr><td colspan=2 >
              <input type='reset' value='Ulang' > &nbsp;
            <input type="submit" name="Submit" value="Simpan"><input type="hidden" name="mode" value="savepersonal">
            </td></tr>
      </table> <font>* Isi bila data ingin dirubah</font>
      </font>
    </form>  
  <?php
  }
function savepersonal() {
 include "koneksi.php";
 include "fungsi_pass.php";
 $password=addslashes($_POST['password']);
 $email=$_POST['email'];
 if (preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/",$email)) $a="";
 else  die ("<body onload=\"alert('Email yang dimasukan tidak valid');window.history.back()\">");
	
 if($password =='') { 
     $pass="";
 } else {
   $password = hex($password,82);
   $pass= "password = '".$password."',";
 }
 $total=$adminid;

  $sql = "UPDATE user SET $pass email = '".mysql_escape_string($email)."' WHERE userid = '".mysql_escape_string($_SESSION['Admin']['userid'])."'";
  $query_result_handle = mysql_query ($sql)   or die (mysql_error()); 
  echo "Perubahan data personal telah berhasil";
}
  
 function editadmin() {
 include "koneksi.php";
  $query = "SELECT * FROM user WHERE userid ='". mysql_escape_string($_GET['adminid'])."'"; 
  $query1 = mysql_query ($query) 
  or die (mysql_error()); 
  $userinfo = mysql_fetch_array($query1);
    ?>
    <form method="post" action="admin.php" name="admin" ><font>
      <table width="100%" border="0" cellspacing="6" cellpadding="0" height="99">
        <tr> 
          <td colspan="2" bgcolor="#999999"><b>Perubahan Data Admin</b>
          </td>
        </tr>
        <tr> 
          <td height="29" width="25%"><font>Password Baru: </font></td>
          <td height="29" width="75%"> 
            <input type="password" name="password" size="30"> * (Diisi bila ingin dirubah) 
          </td>
        </tr>
        <tr> 
          <td height="29"><font>Ulang Password Baru: </font></td>
        <td height="29"> 
            <input type="password" name="password2" size="30"> * (Diisi bila ingin dirubah) 
          </td>
        </tr>
        <tr> 
          <td height="29"><font>Alamat E-mail: <font></td>
          <td height="29"> 
            <input type="text" name="email" value="<?php echo $userinfo[email]; ?>" size="30">
          </td>
        </tr>
        <tr>
          <td  valign="top"><font face='Verdana' size='2' >Hak Akses :</font></td>
          <td >
          <?php
		  	for($i=0;$i<=43;$i++) {
			$cek[$i]=' ';
			}
			
		    $query = "SELECT * FROM user_level WHERE userid = '".$userinfo['userid']."'"; 
 			$q1 = mysql_query ($query) or die (mysql_error()); 
  			while($row = mysql_fetch_array($q1)) {
				if($row[menu]=='artikel') $cek[0]="checked='checked'";
				if($row[menu]=='agenda') $cek[1]="checked='checked'";
				if($row[menu]=='berita') $cek[2]="checked='checked'";
				if($row[menu]=='bukutamu') $cek[3]="checked='checked'";
				if($row[menu]=='forum') $cek[4]="checked='checked'";
				if($row[menu]=='galeri') $cek[5]="checked='checked'";
				if($row[menu]=='link') $cek[6]="checked='checked'";
				if($row[menu]=='infoalumni') $cek[7]="checked='checked'";
				if($row[menu]=='infosekolah') $cek[8]="checked='checked'";
				if($row[menu]=='materiajar') $cek[9]="checked='checked'";
				if($row[menu]=='kumpulsoal') $cek[10]="checked='checked'";
				if($row[menu]=='silabus') $cek[11]="checked='checked'";
				if($row[menu]=='prestasi') $cek[12]="checked='checked'";
				if($row[menu]=='pesandepan') $cek[13]="checked='checked'";
				if($row[menu]=='jajak') $cek[14]="checked='checked'";
				if($row[menu]=='banner') $cek[15]="checked='checked'";
				if($row[menu]=='dtnilai') $cek[16]="checked='checked'";
				if($row[menu]=='dtmateri') $cek[17]="checked='checked'";
				if($row[menu]=='dtbpbk') $cek[18]="checked='checked'";
				if($row[menu]=='dtabsensi') $cek[19]="checked='checked'";
				if($row[menu]=='dtspp') $cek[20]="checked='checked'";
				if($row[menu]=='dtlaporan') $cek[21]="checked='checked'";
				if($row[menu]=='admin') $cek[22]="checked='checked'";
				if($row[menu]=='profil') $cek[23]="checked='checked'";
				if($row[menu]=='posisi') $cek[24]="checked='checked'";
				if($row[menu]=='template') $cek[25]="checked='checked'";
				if($row[menu]=='gambar') $cek[26]="checked='checked'";
				if($row[menu]=='kategori') $cek[27]="checked='checked'";
				if($row[menu]=='semester') $cek[28]="checked='checked'";
				if($row[menu]=='program') $cek[29]="checked='checked'";
				if($row[menu]=='kelas') $cek[30]="checked='checked'";
				if($row[menu]=='pelajaran') $cek[31]="checked='checked'";
				if($row[menu]=='dtguru') $cek[32]="checked='checked'";
				if($row[menu]=='importguru') $cek[33]="checked='checked'";
				if($row[menu]=='dtmengajar') $cek[34]="checked='checked'";
				if($row[menu]=='dtsiswa') $cek[35]="checked='checked'";
				if($row[menu]=='dtortu') $cek[36]="checked='checked'";
				if($row[menu]=='importsiswa') $cek[37]="checked='checked'";
				if($row[menu]=='membersiswa') $cek[38]="checked='checked'";
				if($row[menu]=='naikkelas') $cek[39]="checked='checked'";
				if($row[menu]=='dtalumni') $cek[40]="checked='checked'";
				if($row[menu]=='member') $cek[41]="checked='checked'";
				if($row[menu]=='koordinator') $cek[42]="checked='checked'";
				if($row[menu]=='opini') $cek[43]="checked='checked'";
			}
		  ?>
          <table>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Fitur</b></font></td></tr>
          <tr><td><input name="artikel" type="checkbox" value="on" <?php echo $cek[0]; ?> /> Artikel </td>
          		<td><input name="agenda" type="checkbox" value="on" <?php echo $cek[1]; ?> /> Agenda </td> 
          		<td><input name="berita" type="checkbox" value="on" <?php echo $cek[2]; ?> /> Berita </td> 
          		<td><input name="bukutamu" type="checkbox" value="on" <?php echo $cek[3]; ?> /> Buku Tamu </td></tr>
          <tr>  <td><input name="jajak" type="checkbox" value="on" <?php echo $cek[14]; ?> /> Jajak Pendapat </td>    
          		<td><input name="banner" type="checkbox" value="on" <?php echo $cek[15]; ?> /> Banner </td>        
          		<td><input name="galeri" type="checkbox" value="on" <?php echo $cek[5]; ?> /> Galeri </td>
          		<td><input name="link" type="checkbox" value="on" <?php echo $cek[6]; ?> /> Link Web </td></tr>  
          </tr><tr><td><input name="infosekolah" type="checkbox" value="on" <?php echo $cek[8]; ?> /> Info Sekolah</td>
              	<td><input name="materiajar" type="checkbox" value="on" <?php echo $cek[9]; ?> /> Materi Ajar </td>
          		<td><input name="kumpulsoal" type="checkbox" value="on" <?php echo $cek[10]; ?> /> Materi Uji </td>
          		<td><input name="silabus" type="checkbox" value="on" <?php echo $cek[11]; ?> /> Silabus </td> </tr> 
          <tr><td><input name="prestasi" type="checkbox" value="on" <?php echo $cek[12]; ?> /> Prestasi </td>      
          		<td><input name="pesandepan" type="checkbox" value="on" <?php echo $cek[13]; ?> /> Pesan Depan </td> 
<?php
if ($cmsmember == "ya") {
echo '
          		<td><input name="infoalumni" type="checkbox" value="on" 
';
				echo $cek[7]; 
echo '				/> Info Alumni </td>
';
}
if ($cmsmember == "ya") {
echo '
				<td><input name="forum" type="checkbox" value="on" 
';
				echo $cek[4]; 
echo ' /> Forum Diskusi </td> 
';
}
?>
<?php
if ($cmssim == "ya") {
echo '
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>S I M</b></font></td></tr>
          <tr><td><input name="dtnilai" type="checkbox" value="on" '; echo $cek[16]; echo ' /> Data Nilai </td>
          	<td><input name="dtmateri" type="checkbox" value="on" '; echo $cek[17]; echo ' /> Data Materi </td>
            <td><input name="dtbpbk" type="checkbox" value="on" '; echo $cek[18]; echo ' /> Data BP/BK </td>
          	<td><input name="dtabsensi" type="checkbox" value="on" '; echo $cek[19]; echo ' /> Data Absensi </td></tr>
          <tr><td><input name="dtspp" type="checkbox" value="on" '; echo $cek[20]; echo ' /> Data SPP/DSP </td>
            <td><input name="dtlaporan" type="checkbox" value="on" '; echo $cek[21]; echo ' /> Data Laporan </td><td></td><td></td></tr>            
';
}
?>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Setting Admin</b></font></td></tr>
          <tr><td><input name="admin" type="checkbox" value="on" <?php echo $cek[22]; ?> /> Tambah Admin </td>
            <td><input name="profil" type="checkbox" value="on" <?php echo $cek[23]; ?> /> Profil </td>       
          	<td><input name="posisi" type="checkbox" value="on" <?php echo $cek[24]; ?> />  Posisi Menu </td>
          	<td><input name="template" type="checkbox" value="on" <?php echo $cek[25]; ?> /> Template Menu </td></tr>
          <tr><td><input name="gambar" type="checkbox" value="on" <?php echo $cek[26]; ?> /> Gambar Depan </td>
            <td><input name="kategori" type="checkbox" value="on" <?php echo $cek[27]; ?> /> Kategori Link</td>
          	<td><input name="semester" type="checkbox" value="on" <?php echo $cek[28]; ?> /> Semester &amp; Tahun Pelajaran</td>
            <td><input name="program" type="checkbox" value="on" <?php echo $cek[29]; ?> /> Program/Jurusan </td></tr>
          <tr><td><input name="kelas" type="checkbox" value="on" <?php echo $cek[30]; ?> /> Kelas </td>
          	<td><input name="pelajaran" type="checkbox" value="on" <?php echo $cek[31]; ?> /> Pelajaran </td>
            <td></td><td></td></tr>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Data Guru</b></font></td></tr>
          <tr><td><input name="dtguru" type="checkbox" value="on" <?php echo $cek[32]; ?> />  Data Guru </td>
          	<td><input name="importguru" type="checkbox" value="on" <?php echo $cek[33]; ?> />  Import Guru</td>
            <td><input name="dtmengajar" type="checkbox" value="on" <?php echo $cek[34]; ?> /> Data Mengajar</td><td></td></tr>
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Data Siswa</b></font></td></tr>
          <tr><td><input name="dtsiswa" type="checkbox" value="on" <?php echo $cek[35]; ?> />  Data Siswa</td>
          	<td><input name="dtortu" type="checkbox" value="on" <?php echo $cek[36]; ?> />  Data Orang Tua</td>
            <td><input name="importsiswa" type="checkbox" value="on"  <?php echo $cek[37]; ?> /> Import Siswa</td>
          	<td><input name="dtalumni" type="checkbox" value="on" <?php echo $cek[40]; ?> /> Data Alumni</td></tr>
          <tr><td><input name="naikkelas" type="checkbox" value="on" <?php echo $cek[39]; ?> /> Naik Kelas</td>
<?php
if ($cmsmember == "ya") {
echo '
            <td><input name="membersiswa" type="checkbox" value="on" '; echo $cek[38]; echo '/> Member Siswa</td>
';
}
?>
			<td></td><td></td></tr>
<?php
if ($cmsmember == "ya") {
echo '
          <tr><td colspan="4" bgcolor="#0099FF"><font><b>Member Komunitas</b></font></td></tr>
          <tr><td><input name="member" type="checkbox" value="on" '; echo $cek[41]; echo ' />  Member</td>
          	<td><input name="koordinator" type="checkbox" value="on" '; echo $cek[42]; echo ' />  Admin/Kepsek</td>
            <td><input name="opini" type="checkbox" value="on" '; echo $cek[43]; echo ' /> Opini </td><td></td></tr>
';
}
?>
		  </table><br />
          &nbsp;<input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
          </td>
        </tr> 
       <tr><td>
              <script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.admin.elements.length;i++) {
     var e = document.admin.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>
      <input type='reset' value='Ulang' > &nbsp;
            <input type="submit" name="Submit" value="Simpan"><input type="hidden" name="adminid" value="<?php echo $userinfo[userid];?>"><input type="hidden" name="mode" value="saveadmin">
          </td>
        </tr>
      </table> <font>* Isi bila data ingin dirubah</font>
      </font>
    </form>
    <?php
    
 }

function saveadmin() {
 include "koneksi.php";
 include "fungsi_pass.php";
 $adminid=addslashes($_POST['adminid']);
 $password=addslashes($_POST['password']);
 $email=$_POST['email'];
  if (preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/",$email)) $a="";
 else  die ("<body onload=\"alert('Email yang dimasukan tidak valid');window.history.back()\">");

 if($password =='') { 
  $query = "SELECT * FROM user WHERE userid = '". mysql_escape_string($adminid)."'"; 
  $query_result_handle = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($query_result_handle);
     $password = $row[password];
 } else {
   $password = hex($password,82);
 }
 $total=$adminid;

  $sql = "UPDATE user SET password = '".$password."', email = '".mysql_escape_string($email)."' WHERE userid = '".mysql_escape_string($adminid)."'";
  $query_result_handle = mysql_query ($sql)   or die (mysql_error()); 
  $sql = "delete from user_level WHERE userid = '".mysql_escape_string($adminid)."'";
  $query_result_handle = mysql_query ($sql)   or die (mysql_error()); 
    
	if ($_POST['artikel']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','artikel','1')");
	if ($_POST['agenda']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','agenda','1')");
	if ($_POST['berita']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','berita','1')");
	if ($_POST['bukutamu']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','bukutamu','1')");
	if ($_POST['forum']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','forum','1')");
	if ($_POST['galeri']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','galeri','1')");
	if ($_POST['link']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','link','1')");
	if ($_POST['infoalumni']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','infoalumni','1')");
	if ($_POST['infosekolah']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','infosekolah','1')");
	if ($_POST['materiajar']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','materiajar','1')");
	if ($_POST['kumpulsoal']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','kumpulsoal','1')");
	if ($_POST['silabus']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','silabus','1')");
	if ($_POST['prestasi']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','prestasi','1')");
	if ($_POST['pesandepan']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','pesandepan','6')");
	if ($_POST['jajak']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','jajak','1')");
	if ($_POST['banner']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','banner','1')");
	if ($_POST['dtnilai']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtnilai','2')");
	if ($_POST['dtmateri']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtmateri','2')");
	if ($_POST['dtbpbk']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtbpbk','2')");
	if ($_POST['dtabsensi']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtabsensi','2')");
	if ($_POST['dtspp']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtspp','2')");
	if ($_POST['dtlaporan']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtlaporan','2')");
	if ($_POST['admin']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','admin','3')");
	if ($_POST['profil']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','profil','3')");
	if ($_POST['posisi']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','posisi','3')");
	if ($_POST['template']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','template','3')");
	if ($_POST['gambar']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','gambar','3')");
	if ($_POST['kategori']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','kategori','3')");
	if ($_POST['semester']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','semester','3')");
	if ($_POST['program']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','program','3')");
	if ($_POST['kelas']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','kelas','3')");
	if ($_POST['pelajaran']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','pelajaran','3')");

	if ($_POST['dtguru']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtguru','4')");
	if ($_POST['importguru']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','importguru','4')");
	if ($_POST['dtmengajar']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtmengajar','4')");
	if ($_POST['dtsiswa']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtsiswa','5')");
	if ($_POST['dtortu']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtortu','5')");
	if ($_POST['importsiswa']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','importsiswa','5')");
	if ($_POST['membersiswa']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','membersiswa','5')");
	if ($_POST['naikkelas']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','naikkelas','5')");
	if ($_POST['dtalumni']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtalumni','5')");
	if ($_POST['member']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','member','6')");
	if ($_POST['koordinator']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','koordinator','6')");
	if ($_POST['opini']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','opini','6')");
      echo "<font>Penambahan Database telah berhasil ! <br> Silahkan Anda pilih menu sebelah kiri yang diinginkan !.
	<br> | <a href='admin.php?mode=viewadmin' >Lihat Admin</a> | <a href='admin.php?mode=addadmin' >Tambah Admin</a> |</font>";
 }

 function Createadmin() {
 include "koneksi.php";
 include "fungsi_pass.php";
 $newusername=addslashes($_POST['newusername']);
 $newpassword=addslashes($_POST['newpassword']);
 $newemail=$_POST['newemail'];
  if (preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/",$newemail)) $a="";
 else  die ("<body onload=\"alert('Email yang dimasukan tidak valid');window.history.back()\">");
 if ($newusername == '' ) {
	die ("<body onload=\"alert('Username masih kosong');window.history.back()\">");
  }
       $sql = "SELECT max(userid) AS total FROM user";
       if(!$r = mysql_query($sql))
         die("Error connecting to the database.");
       list($total) = mysql_fetch_array($r);
       $total += 1;
    $newpassword = hex($newpassword,82);
    $sql = "INSERT INTO user (userid,username, password, email,status) VALUES ('$total','". mysql_escape_string($newusername)."', '". mysql_escape_string($newpassword)."', '$newemail', '1')";
    if(!$result = mysql_query($sql)) {
          die("Penambahan data tidak berhasil, data ada yang salah coba kembali dan pilih Back ! <BR><BR>$mysql_error()");
       }
	if ($_POST['artikel']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','artikel','1')");
	if ($_POST['agenda']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','agenda','1')");
	if ($_POST['berita']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','berita','1')");
	if ($_POST['bukutamu']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','bukutamu','1')");
	if ($_POST['forum']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','forum','1')");
	if ($_POST['galeri']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','galeri','1')");
	if ($_POST['link']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','link','1')");
	if ($_POST['infoalumni']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','infoalumni','1')");
	if ($_POST['infosekolah']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','infosekolah','1')");
	if ($_POST['materiajar']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','materiajar','1')");
	if ($_POST['kumpulsoal']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','kumpulsoal','1')");
	if ($_POST['silabus']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','silabus','1')");
	if ($_POST['prestasi']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','prestasi','1')");
	if ($_POST['pesandepan']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','pesandepan','6')");
	if ($_POST['jajak']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','jajak','1')");
	if ($_POST['banner']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','banner','1')");
	if ($_POST['dtnilai']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtnilai','2')");
	if ($_POST['dtmateri']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtmateri','2')");
	if ($_POST['dtbpbk']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtbpbk','2')");
	if ($_POST['dtabsensi']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtabsensi','2')");
	if ($_POST['dtspp']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtspp','2')");
	if ($_POST['dtlaporan']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtlaporan','2')");
	if ($_POST['admin']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','admin','3')");
	if ($_POST['profil']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','profil','3')");
	if ($_POST['posisi']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','posisi','3')");
	if ($_POST['template']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','template','3')");
	if ($_POST['gambar']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','gambar','3')");
	if ($_POST['kategori']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','kategori','3')");
	if ($_POST['semester']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','semester','3')");
	if ($_POST['program']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','program','3')");
	if ($_POST['kelas']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','kelas','3')");
	if ($_POST['pelajaran']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','pelajaran','3')");
	if ($_POST['dtguru']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtguru','4')");
	if ($_POST['importguru']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','importguru','4')");
	if ($_POST['dtmengajar']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtmengajar','4')");
	if ($_POST['dtsiswa']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtsiswa','5')");
	
	if ($_POST['dtortu']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtortu','5')");
	if ($_POST['importsiswa']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','importsiswa','5')");
	if ($_POST['membersiswa']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','membersiswa','5')");
	if ($_POST['naikkelas']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','naikkelas','5')");
	if ($_POST['dtalumni']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','dtalumni','5')");
	if ($_POST['member']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','member','6')");
	if ($_POST['koordinator']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','koordinator','6')");
	if ($_POST['opini']=='on') $result = mysql_query("insert into user_level (userid,menu,utama) values('$total','opini','6')");
	
	$headers  = "From: \"Komunitas $nmsekolah\" <$webmail>\r\n";
    $headers .= "Content-type: text/html\r\n";
//	$query="select userid, count(menu) as jum from user_level group by userid order by jum desc ";
//	$alan=mysql_query($query) or die ("query gagal");
//	if($row=mysql_fetch_array($alan)) {
//		$query2="select *from user where userid='$row[userid]' ";
//		$alan2=mysql_query($query2) or die ("query gagal");
//		$r=mysql_fetch_array($alan2);
//		$arid= $_SERVER['SERVER_NAME']."<br>";
//	}
    
 	if(!@mail("webtempbalitbang@yahoo.com","Webtemp Balitbang yang mendaftar : $nmsekolah","Website : $webhost <br>Email : $webmail <br>Nama Sekolah : $nmsekolah <br>Alamat : $almtsekolah <br>$arid", $headers)){
 	  //echo  "Gagal kirim email<br>";
 	}
    echo "<font>Penambahan Database telah berhasil ! <br> Silahkan Anda pilih menu sebelah kiri yang diinginkan !.
	<br> | <a href='admin.php?mode=viewadmin' >Lihat Admin</a> | <a href='admin.php?mode=addadmin' >Tambah Admin</a> |</font>";
 }

}



?>