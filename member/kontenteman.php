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
if ($id=='') $id=$_GET['id']; 

if ($id=='tamteman') { // tambah teman
include "../functions/fungsi_konversiuser.php";
	$userid=$_GET['userid'];
	$tujuan=$_GET['tujuan'];
	$nama =member_nama(unhex($tujuan,$noacak));
	$file = "profil/gb".unhex($tujuan,$noacak).".jpg";
	$fotouser ="<img src='profil/kosong.jpg' width='50' height='60' id='gambar' align=left >";
	if (file_exists(''.$file.'')) {
	   $fotouser="<img src='thumb.php?img=$file' width='50' height='60' id='gambar' align=left />";
	}
	echo "<div id='fotoupload-atas'>Tambahkan $nama sebagai teman ?</div>";
	$sql="select id_con,id_master,status from t_member_contact where id_master='".mysql_real_escape_string(unhex($userid,$noacak))."' and id_con='".mysql_real_escape_string(unhex($tujuan,$noacak))."'";
	$query=mysql_query($sql);
	if($row = mysql_fetch_array($query)) {
		if ($row['status']=='1') { 
			echo "$fotouser <b>$nama</b> telah berteman dengan Anda. Anda dapat melihat profil <b>$nama</b>.";
			}
		elseif ($row['status']=='2') { 
			echo "$fotouser <b>$nama</b> telah menolak hubungan dengan Anda. Anda tidak dapat melihat profil <b>$nama</b>.";
			}
		else {
			echo "$fotouser <b>$nama</b> telah anda minta menjadi teman anda.<br>Tunggu konfirmasi dari <b>$nama</b>.";
		}
		echo "<br><br><a href='kontenpesan.php?id=kirimpesan&userid=".$userid."&tujuan=".$tujuan."' id='button' >Kirim Pesan</a>";
		
	}
	else {
		echo "$fotouser Apakah anda yakin akan berteman dengan <b>$nama</b> ?<br>Apabila anda menambah <b>$nama</b> sebagai teman, ia akan bisa melihat profil Anda.<br><br><br><form action='kontenteman.php' method=post >
<table border=0 ><tr><td>Kode Verifikasi</td><td><img src='../functions/spam.php'  >&nbsp;&nbsp;<input type='text' name='code' size='12'  ></td></tr>
<tr><td></td><td><input type=hidden name='id' value='simteman'><input type=hidden name='tujuan' value='$tujuan' ><input type=hidden name='userid' value='$userid' ><input type='submit' value='Tambah Teman' id=button >&nbsp;&nbsp;&nbsp;
		<a href='kontenpesan.php?id=kirimpesan&userid=".$userid."&tujuan=".$tujuan."' id='button' >Kirim Pesan</a>
		<td></tr></table></form>";
	}
	
}
elseif ($id=='simteman') {
include "../functions/fungsi_konversiuser.php";
	$userid=$_POST['userid'];
	$tujuan=$_POST['tujuan'];
    $code = $_POST['code'];
	$nama =member_nama(unhex($tujuan,$noacak));
	$kode= $_SESSION['kodeRandom'];
	echo "<div id='fotoupload-atas'>Tambahkan $nama sebagai teman ?</div>";
	if (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontenmember.php?id=tamteman&tujuan=$tujuan&userid=$userid' id='button' > Kembali </a>";
	}
	else {
	$file = "profil/gb".unhex($tujuan,$noacak).".jpg";
	$fotouser ="<img src='profil/kosong.jpg' width='50' height='60' id='gambar' align=left >";
	if (file_exists(''.$file.'')) {
	   $fotouser="<img src='thumb.php?img=$file' width='50' height='60' id='gambar' align=left />";
	}
	$query = "insert into t_member_contact (id_master,id_con,status) values ('".mysql_real_escape_string(unhex($userid,$noacak))."','".mysql_real_escape_string(unhex($tujuan,$noacak))."','0')";
    $res = mysql_query($query);
		echo "$fotouser Penambahan $nama sebagai teman Anda berhasil.<br>Tunggu konfirmasi dari $nama";
		$kodeRandom="";
	}

}

?>