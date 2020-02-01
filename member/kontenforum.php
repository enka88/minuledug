<?php
session_start();
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}
//echo '<link type="text/css" rel="stylesheet" media="all" href="css/kontenbox.css" />';
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
$userid = $_POST['userid'];
$userid = unhex($userid,$noacak);
$value=explode(",",$userid,2);
$kondisi=$value[0];$userid=$value[1];

if ($kondisi=='tambahtopik'){
// tambah topik pada forum diskusi

$kdforum= unhex($_POST['kdforum'],$noacak);
$pesan = $_POST['pesan'];
$topik=$_POST['topik'];
$code=$_POST['code'];
$pesan = htmlentities($pesan);
$topik =strip_tags($topik);

$tgl = date("Y-m-d H:i:s");
	if (strtoupper($code) != $_SESSION['kodeRandom']) {
		echo "Kode keamanan salah, Klik <a href='?id=tambahtopik&kdforum=$kdforum' >disini</a> untuk kembali ke sebelumnya";
	}
	else {
	$q2 = mysql_query("select * from t_forum where forum_id='". mysql_real_escape_string($kdforum)."'");
	$jum = mysql_num_rows($q2);
	 if ($jum > 0) {
		$q = mysql_query("select max(isi_id) as tot from t_forum_isi ");
		$row = mysql_fetch_array($q);
		$total = $row[tot]+1;
		$query = "insert into t_forum_isi (isi_id,isi_judul,isi_body,isi_tgl,userid,forum_id) values ('$total','". mysql_real_escape_string($topik)."','". mysql_real_escape_string($pesan)."','$tgl','". mysql_real_escape_string($userid)."','". mysql_real_escape_string($kdforum)."')";
		$result = mysql_query($query) or die("Query failed");
		echo "Penambahan topik forum berhasil. klik <a href='?id=lihatbalasan&kdtopik=$total&kdforum=$kdforum' >disini</a> untuk melihat topik forum ini<br> ";	
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		$_SESSION['kodeRandom']="";
	  }
	}
}
elseif ($kondisi=='tambahbalas'){
// tambah topik pada forum diskusi

$kdforum= unhex($_POST['kdforum'],$noacak);
$kdtopik= unhex($_POST['kdtopik'],$noacak);
$pesan = $_POST['pesan'];
$code=$_POST['code'];
$pesan = htmlentities($pesan);

$tgl = date("Y-m-d H:i:s");
	if (strtoupper($code) != $_SESSION['kodeRandom']) {
		echo "Kode keamanan salah, Klik <a href='?id=tambahtopik&kdforum=$kdforum' >disini</a> untuk kembali ke sebelumnya";
	}
	else {
	$q2 = mysql_query("select isi_id from t_forum_isi where isi_id='". mysql_real_escape_string($kdtopik)."' and forum_id='". mysql_real_escape_string($kdforum)."'");
	$jum = mysql_num_rows($q2);
	 if ($jum > 0) {
		$query = "insert into t_forum_balas (balas_body,balas_tgl,userid,isi_id,forum_id) values ('". mysql_real_escape_string($pesan)."','$tgl','". mysql_real_escape_string($userid)."','". mysql_real_escape_string($kdtopik)."','". mysql_real_escape_string($kdforum)."')";
		$result = mysql_query($query) or die("Query failed");
		echo "Penambahan balasan topik forum berhasil. klik <a href='?id=lihatbalasan&kdtopik=$kdtopik&kdforum=$kdforum' >disini</a> untuk melihat topik forum ini <br> ";	
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		$_SESSION['kodeRandom']="";
	  }
	}
}

?>