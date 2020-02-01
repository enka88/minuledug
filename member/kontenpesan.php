<?php
session_start();
echo '<link type="text/css" rel="stylesheet" media="all" href="css/kontenbox.css" />';
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";

if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}

$id=$_POST['id'];
if ($id=='') $id=$_GET['id']; 

if ($id=='kirimpesan') {
include "../functions/fungsi_konversiuser.php";
	$userid=$_GET['userid'];
	$tujuan=$_GET['tujuan'];
	if (substr($tujuan,0,5)=="group") { $kdgroup =substr($tujuan,5,50); $nama="Semua Anggota"; $tujuan="group"; }
	else $nama =member_nama(unhex($tujuan,$noacak));
	
	echo "<div id='fotoupload-atas'>Kirim Pesan </div>";
	echo "<form action='kontenpesan.php' method=post >
	<table border=0 ><tr><td width=25% >Kepada </td><td><input type=text name='nama' value='$nama' readonly ></td></tr>
	<tr><td  >Judul </td><td><input type=text name='judul' maxlength='60' ></td></tr>
	<tr><td valign=top >Pesan </td><td><textarea rows='10' cols='33' name='pesan' ></textarea></td></tr>
	<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr><input type=hidden name='id' value='simpanpesan'>
<input type=hidden name='tujuan' value='$tujuan' ><input type=hidden name='userid' value='$userid' >
<input type=hidden name='kdgroup' value='$kdgroup' >
<tr><td ></td><td><input type='submit' value='Kirim' id=button ></td></tr></table></form>";
}
elseif ($id=='simpanpesan') {
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
	//$userid = unhex($_POST['userid'],$noacak);
	$userid = $_POST['userid'];
    $tujuan = $_POST['tujuan'];
	$kdgroup = $_POST['kdgroup'];
	$pesan = htmlentities($_POST['pesan']);
	$judul = stripslashes($_POST['judul']);
	$code = $_POST['code'];
	
	$kode= $_SESSION['kodeRandom'];
  	if (trim($pesan) == '' ) {
		echo "Pesan masih kosong. <a href='kontenpesan.php?id=kirimpesan&tujuan=$tujuan&userid=$userid' id='button'  > Kembali </a>";
   	}
	elseif (trim($judul)=='') {
		echo "Judul masih kosong. <a href='kontenpesan.php?id=kirimpesan&tujuan=$tujuan&userid=$userid' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontenpesan.php?id=kirimpesan&tujuan=$tujuan&userid=$userid' id='button' > Kembali </a>";
	}
	else {
		$userid = unhex($_POST['userid'],$noacak);
        echo "<div id='fotoupload-atas'>Kirim Pesan </div>";
		if($tujuan=='group' ) {
			$sql2="select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string(unhex($kdgroup,$noacak))."' and  userid<>'".mysql_real_escape_string($userid)."'";
			if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal anggota ");
			while($r=mysql_fetch_array($query2)) {
				$nmgroup = nama_group($r[idgroup]);
				$query=mysql_query("insert into t_member_pesan (judul,pesan,userid,tgl,tujuan_id) values ('".mysql_real_escape_string($judul)."','Pesan Group $nmgroup <br>".mysql_real_escape_string($pesan)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($r[userid])."') ");
			}
			echo "Pengiriman pesan berhasil dilakukan. <br>Silahkan tutup tampilan ini.";
		}
		else {
			$tujuan=unhex($tujuan,$noacak);
			//$userid=unhex($userid,$noacak);
		$query=mysql_query("insert into t_member_pesan (judul,pesan,userid,tgl,tujuan_id) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($pesan)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($tujuan)."') ");
	
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		
		echo "Pengiriman pesan berhasil dilakukan. <br>Silahkan tutup tampilan ini.";
		}
	}
	
}
?>