<?php
session_start();
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}
echo '<link type="text/css" rel="stylesheet" media="all" href="css/kontenbox.css" />';
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
$id=$_POST['id'];
if (empty($id)) $id=$_GET['id']; 

if ($id=='totmember') { // total member
	echo "<div id='fotoupload-atas'>Total Member </div>";
	$query=mysql_query("select userid from t_member where ket='Siswa' and status='1'");
	$siswa=mysql_num_rows($query);
	$query=mysql_query("select userid from t_member where (ket='Guru' or ket='Admin') and status='1'");
	$guru=mysql_num_rows($query);
	$query=mysql_query("select userid from t_member where ket='Orang Tua' and status='1'");
	$ortu=mysql_num_rows($query);
	$query=mysql_query("select userid from t_member where ket='Tamu' and status='1'");
	$tamu=mysql_num_rows($query);
	$query=mysql_query("select userid from t_member where ket='Alumni' and status='1'");
	$alumni=mysql_num_rows($query);
	$query=mysql_query("select userid from t_member where status='0'");
	$veri=mysql_num_rows($query);
	$total = $siswa+$guru+$ortu+$alumni+$tamu+$veri;
	$query=mysql_query("select sum(point) as tot from t_member ");
	$r=mysql_fetch_array($query);
	$point=$r[tot];	
	$query=mysql_query("select id_con from t_member_contact where status='1' ");
	$hub=mysql_num_rows($query);
	$query=mysql_query("select idgroup from t_membergroup  ");
	$totgroup=mysql_num_rows($query);
	echo "<table border=0 width=100% ><tr><td width='20%'>Siswa</td><td width='2%'>:</td><td width='10%'>$siswa</td>
	<td width='20%'>Guru</td><td width='2%'>:</td><td width='10%'>$guru</td>
	<td width='20%'>Orang Tua</td><td width='2%'>:</td><td width='10%'>$ortu</td></tr>
	<tr><td width='20%'>Tamu</td><td width='2%'>:</td><td width='10%'>$tamu</td>
	<td width='20%'>Alumni</td><td width='2%'>:</td><td width='10%'>$alumni</td>
	<td width='20%'></td><td width='2%'></td><td width='10%'></td></tr>
	<tr><td colspan=4 >Member yang belum verifikasi </td><td>: </td><td colspan=4 >$veri</td></tr>
	<tr><td colspan=4 >Total Member </td><td>: </td><td colspan=4 >$total</td></tr>
	<tr><td colspan=4 >Total Group </td><td>: </td><td colspan=4 >$totgroup</td></tr>
	<tr><td colspan=4 >Total Point semua member </td><td>: </td><td colspan=4 >$point</td></tr>
	<tr><td colspan=4 >Total hubungan pertemanan </td><td>: </td><td colspan=4 >$hub</td></tr><table>";
	
}
elseif ($id=='2') {
$userid = $_POST['userid'];
$userid = unhex($userid,$noacak);
$value=explode(",",$userid,2);
$kondisi=$value[0];$userid=$value[1];
  if ($kondisi=='sim2pesan') {
  	$code = $_POST['code'];
	$judul = $_POST['judul'];
	$pesan = htmlentities($_POST['pesan']);
	$tujuan = $_POST['tujuan'];
	$semua = $_POST['semua'];
	$email =$_POST['email'];
	$kode= $_SESSION['kodeRandom'];
	if (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='user.php?id=kirimpesan' id='button' > Kembali </a>";
	}
	else {
		if ($semua=='2') {
		$q = "insert into t_member_pesan (judul,pesan,tgl,userid,semua,tujuan_id) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($pesan)."',NOW(),'".mysql_real_escape_string($userid)."','1','0')";
		$res = mysql_query($q) or die("Pengambilan gagal pesan");
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		//if ($email=='1') $message = pesan_mail($row[email],$row[nama],$userid,$user,$judul,$pesan);
		echo "Pengiriman pesan ke semua teman berhasil dilakukan.";
		$kodeRandom="";
		}
		else {
			$id = explode(",",$tujuan);
			
			for($i=0;$i<count($id);$i++) {
				$query=mysql_query("select email,nama,userid from t_member where userid='".mysql_real_escape_string($id[$i])."'");
				if($row=mysql_fetch_array($query)) {
					$q = "insert into t_member_pesan (judul,pesan,tgl,userid,semua,tujuan_id) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($pesan)."',NOW(),'".mysql_real_escape_string($userid)."','0','".mysql_real_escape_string($row[userid])."')";
					$res = mysql_query($q) or die("Pengambilan gagal pesan");
				
				//	if ($email=='2') $message = pesan_mail($row[email],$row[nama],$userid,$saya,$judul,$pesan);
					$nama.="- $row[nama]<br>";
					
				}
			}
			if ($nama<>'')	{ 
				echo "Pengiriman pesan ke teman berhasil dilakukan, yaitu <br>$nama";
				$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
				$kodeRandom="";
			}
			else echo "Pengiriman pesan tidak berhasil, coba cek tujuan pengirim.";
		}
	}
  }// kondisi pengiriman pesan
}


?>
