<?php
session_start();
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
$userid = $_POST['userid'];
$userid = unhex($userid,$noacak);
$value=explode(",",$userid,2);
$kondisi=$value[0];$userid=$value[1];

if ($kondisi=='simedit') {
	$jenis = $_POST['jenis'];
	$jenis = unhex($jenis,$noacak);
	$neg = $_POST['neg']; $tgl = $_POST['tgl']; $name = $_POST['name']; $kelamin = $_POST['kelamin'];
	$nis = $_POST['nis']; $kelas = $_POST['kelas'];$email = $_POST['email'];$password = $_POST['password'];
	$pertanyaan = $_POST['pertanyaan'];$jawaban = $_POST['jawaban'];$hari = $_POST['hari'];$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];$kerja = $_POST['kerja'];$alamat = $_POST['alamat'];$sekolah = $_POST['sekolah'];
	$telp = $_POST['telp'];$blog = $_POST['blog'];$tentang = $_POST['tentang'];$country = $_POST['country'];
	$stprofil = $_POST['stprofil'];$stblog=$_POST['stblog'];
	$face = $_POST['face'];
    
	if ($tahun=='' or $bulan=='') $tgl=$tgl;
	else $tgl="$hari-$bulan-$tahun";
	
	if ($country=='') $negara=$neg;
	else $negara=$country;
	
	if ($stblog=='on') $stblog = '1';
	else $stblog='0';
	//klo siswa tidak bisa ngubah nis,nama,
	//klo guru sama kaya siswa
	$password=md5($password);
	$data =" negara='".mysql_real_escape_string($negara)."', tgllahir='".mysql_real_escape_string($tgl)."', 
    kelamin='".mysql_real_escape_string($kelamin)."', kelas='".mysql_real_escape_string($kelas)."', 
    email='".mysql_real_escape_string($email)."', password='".mysql_real_escape_string($password)."', 
    pengingat='".mysql_real_escape_string($pertanyaan)."', jawaban='".mysql_real_escape_string($jawaban)."', 
    kerja='".mysql_real_escape_string($kerja)."', alamat='".mysql_real_escape_string($alamat)."', 
    sekolah='".mysql_real_escape_string($sekolah)."', telp='".mysql_real_escape_string($telp)."', 
    homepage='".mysql_real_escape_string($blog)."', profil='".mysql_real_escape_string($tentang)."',
    stprofil='".mysql_real_escape_string($stprofil)."',stblog='".mysql_real_escape_string($stblog)."',
    setfacebook='".mysql_real_escape_string($face)."'";
	if ($jenis=='Siswa') $tambah =" ";
	else if ($jenis=='Orang Tua') $tambah=", nama='".mysql_real_escape_string($name)."' ";
	else if ($jenis=='Guru') $tambah =" ";
	else if ($jenis=='Admin') $tambah =" ";
	else if ($jenis=='Alumni') $tambah =",nama='".mysql_real_escape_string($name)."',nis='' ";
	else if ($jenis=='Tamu') $tambah =", nama='".mysql_real_escape_string($name)."',nis='' ";
	else {
		$data="";$tambah="";
	}
	$query = "update t_member set $data $tambah where userid='".mysql_real_escape_string($userid)."'";
    $res = mysql_query($query);
}
elseif ($kondisi=='simtambah') {

$code = $_POST['code'];
if ($code != $_SESSION['captcha']) {
	die ("<body onload=\"alert('Kode keamanan salah');window.history.back()\">");
}
else {
	$jenis = $_POST['jenis'];
	//$jenis = unhex($jenis,$noacak);
    $username =$_POST['username'];
	$tgl = $_POST['tgl']; $name = $_POST['name']; $kelamin = $_POST['kelamin'];
	$nis = $_POST['nis']; $kelas = $_POST['kelas'];$email = $_POST['email'];$password = $_POST['password'];
	$pertanyaan = $_POST['pertanyaan'];$jawaban = $_POST['jawaban'];$hari = $_POST['hari'];$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];$kerja = $_POST['kerja'];$alamat = $_POST['alamat'];$sekolah = $_POST['sekolah'];
	$telp = $_POST['telp'];$blog = $_POST['blog'];$tentang = $_POST['tentang'];$country = $_POST['country'];
	$stprofil = $_POST['stprofil'];$stblog=$_POST['stblog'];
	
	if ($tahun=='' or $bulan=='') $tgl=$tgl;
	else $tgl="$hari-$bulan-$tahun";
	
	if ($stblog=='on') $stblog = '1';
	else $stblog='0';
	if ($jenis=='Tamu') $kelas='';
	//klo siswa tidak bisa ngubah nis,nama,
	//klo guru sama kaya siswa
	$p=hex($password,$noacak);
	$user=hex($username,$noacak);
	$pass= $password;
	$password=md5($password);
$query = "insert into t_member (nama,tgllahir,kelamin,kerja,alamat,negara,telp,sekolah,homepage,profil,username,password ,email,pengingat,jawaban,status,nis,kelas,ket,stblog,stprofil) values ('".mysql_real_escape_string($name)."','".mysql_real_escape_string($tgl)."','".mysql_real_escape_string($kelamin)."', '".mysql_real_escape_string($kerja)."','".mysql_real_escape_string($alamat)."','".mysql_real_escape_string($country)."', '".mysql_real_escape_string($telp)."','".mysql_real_escape_string($sekolah)."','".mysql_real_escape_string($blog)."','".mysql_real_escape_string($tentang)."','".mysql_real_escape_string($username)."','".mysql_real_escape_string($password)."',
'".mysql_real_escape_string($email)."','".mysql_real_escape_string($pertanyaan)."','".mysql_real_escape_string($jawaban)."','0','','".mysql_real_escape_string($kelas)."', 
'".mysql_real_escape_string($jenis)."','".mysql_real_escape_string($stblog)."', '".mysql_real_escape_string($stprofil)."') ";
    $res = mysql_query($query);
	$kodeRandom='';
	
	echo "<table id=tablebaru width='100%' cellspacing='0' cellpadding='3' >
	<tr><td bgcolor='#BDC5CC'><img src='../images/icon21.png' width='100' height='100' style='margin:0 20px 0 10px' align=left > <h1>Pendaftaran Member - ".$nmsekolah."</h1>
	Silahkan Anda isi form dibawah ini dengan benar dan jujur. </td></tr>
	<tr><td align=center class='td1' ><center>Terima kasih Anda telah bergabung dalam komunitas $nmsekolah<br>
	Silahkan Cek Email Anda untuk Validasi Data yang Anda masukan, ikuti petunjuk sesuai pesan konfirmasi tersebut.<br>
	Anda tidak dapat Login sebelum membuka dan mem-validasi Email tersebut. 
	<br><br><table border=1 width=200 height=100 bordercolor='#000000'><tr><td align=center><FONT class='ver10'>
	Name : <b>$name</b><br>
	Username : <b>$username</b><br>
	Email : <b>$email</b><br>
	</font></td></tr></table>
	</td></tr>
	</table>";
	$wel="gb".rand(10,1).".jpg";
	$tahun = date("Y");
//$url2= $_SERVER['PHP_SELF'];
//$nmfo=explode("/",$url2);
//for($i=0;$i<count($nmfo);$i++) {
//    $nm .= $nmfo[$i]."/";
//    if (file_exists($_SERVER['DOCUMENT_ROOT'].$nm."fckconfig.js")) {
//        $nmhost = "http://".$_SERVER['HTTP_HOST'].$nm;
//        break;
//    }   
//}
$nmhost = substr($nmhost,0,-1);
$message = <<<EOF
<html>
<body>

<table cellSpacing="0" cellPadding="4" bgColor="#6A849D" border="0">
  <tr>
    <td width="600">
    <table cellSpacing="0" cellPadding="10" width="600" bgColor="#ffffff">
      <tr>
        <td><strong>
        <font face="Verdana,Arial,Helvetica,sans-serif" color="#6A849D" size="+1">
        SELAMAT DATANG DI KOMUNITAS <br>
        $nmsekolah</font></strong>
          <table cellSpacing="15" cellPadding="0" width="100%" border="0">
          <tr>
            <td vAlign="top">
            <img alt="Foto Sekolah" src="$nmhost/images/galeri/$wel" width="150" height="120" style="padding:10px;" ></td>
            <td vAlign="top">
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2">
              Terima kasih Anda telah melakukan pendaftar di komunitas $nmsekolah.</font></p>
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2"> Nama : $name<br>
Username : $username<br>
Password : $pass <br>
                    <br>
                   	Silahkan manfaatkan fasilitas komunitas ini untuk kepentingan pendidikan.<br>
Klik dibawah ini untuk validasi status <strong>LOGIN MEMBER</strong> Anda.<br>
                <br>
                <br>
                <a style="font-weight: bold; font-size: 90%; color: #ffffff; font-family: verdana; white-space: nowrap; text-decoration: none; border: 4px solid #f0f0f0; margin: 0px; padding-left: 16px; padding-right: 16px; padding-top: 4px; padding-bottom: 4px; background-color: #7b849c" href="$nmhost/valid/index.php?id=$user&p=$p" target="_blank">
                VALIDASI MEMBER</a> <br>
                <br>
&nbsp;</font></p></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <table cellSpacing="0" cellPadding="1" width="100%" align="center" border="0">
          <tr>
            <td bgColor="#6A849D">
            <table cellSpacing="0" cellPadding="0" width="100%" border="0">
              <tr>
                <td bgColor="#f4f4f4">
                <table cellSpacing="0" cellPadding="4" border="0">
                  <tr>
                    <td>
                    <a href="$nmhost/" target="_blank">
                    <img style="margin-bottom: 5px" src="$nmhost/images/logo.jpg" align="center" border="1"  width="88" height="88"></a>
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
    Copyright $tahun $nmsekolah. All rights reserved. <br>
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
 	if (!@mail($email, "Konfirmasi Username Member di $nmsekolah", $message, $headers)) {
 	  echo "Gagal kirim email";
 	}
}

	
}
?>