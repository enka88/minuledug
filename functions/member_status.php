<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// penghapusan status member
// penghapusan komentar member
function sthapus() {
include "koneksi.php";
$kode = $_GET['kode'];
$userid = $_SESSION['User']['userid'];
$sql="delete from t_memberstatus where idstatus='".mysql_real_escape_string($kode)."' and userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan status member");
$sql="delete from t_memberstatus_kom where idstatus='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan komentar status member");
}

function komhapus() {
include "koneksi.php";
$kode = $_GET['kode'];
$idstatus = $_GET['idstatus'];
$sql="delete from t_memberstatus_kom where idstatuskom='".mysql_real_escape_string($kode)."' and idstatus='".mysql_real_escape_string($idstatus)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan komentar status member");

}
// status member saat ini......................
function statusanda($userid) {
include "koneksi.php";
$pesan ="<i>...</i>";
$nama = member_nama($userid);
$sql="select * from t_memberstatus where userid='".mysql_real_escape_string($userid)."' and jenis='0' and pengirim='".mysql_real_escape_string($userid)."' order by idstatus desc ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal status member");
	if($row=mysql_fetch_array($query)) {
	$tgl=$row[tanggal];
	$tgl = ambilselisih(strtotime($tgl), time())." ";
	$pesan =$row[pesan];
    //tambahan
$x_pesan =$row[pesan];
$data_pesan = explode(" ",$x_pesan);
$x_pesan="";
for ($i=0; $i<count($data_pesan); $i++){

        if (strlen($data_pesan[$i]) >= 20) {
            $data_pesan[$i] = wordwrap($data_pesan[$i], 30, " ", TRUE);
        }
        $x_pesan .= $data_pesan[$i]." ";
}
	$pesan=strip_tags("$x_pesan");
//tutup

	}
    
$anda .="<div id='nama'><img src='../images/user.png' > $nama</div>
<img src='../images/time.png' align='top'> &nbsp; $pesan $tgl <br>";

return $anda;
}
// status pesan member setiap orang
function statusmember($userid,$jenis,$pesan) {
include "koneksi.php";
	if ($jenis=='1') {
		list($album,$target) = explode("|",$pesan);
		$nfile = explode(",",$target,3);
		for($i=0;$i<count($nfile);$i++) {
				if(!$query=mysql_query("select t_memberfoto.idfoto,t_memberfoto.judul,t_memberfoto.tanggal,t_memberfoto_album.keterangan from t_memberfoto,t_memberfoto_album where t_memberfoto.idalbum=t_memberfoto_album.idalbum and t_memberfoto.idfoto='$nfile[$i]' "));
				if($r=mysql_fetch_array($query)) {
					$judul = $r[judul]; $ket=$r['keterangan'];$tgl=ambilselisih(strtotime($r[tanggal]), time());
				}
				$path ="foto/foto$nfile[$i].jpg";
				if (file_exists(''.$path.'')) {
					$foto .="<a href='user.php?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=$nfile[$i]' rel=\"tooltip\" content=\"Foto $judul <br> Upload $tgl\"  ><img src='thumb.php?img=$path' id=gambar /></a>";
				}
				else $foto .="<img src='foto/kosong.jpg' height='70' width='100' id='gambar' > ";
		}
		$msg = "Penambahan foto dari album \"$ket\"<br>$foto";
	}
	else {
	   
	//$msg=$pesan;
        //tambahan
        $x_pesan =$pesan;
        $data_pesan = explode(" ",$x_pesan);
        $x_pesan="";
        for ($i=0; $i<count($data_pesan); $i++){
        
                if (strlen($data_pesan[$i]) >= 20) {
                    $data_pesan[$i] = wordwrap($data_pesan[$i], 30, " ", TRUE);
                }
                $x_pesan .= $data_pesan[$i]." ";
        }
        	$msg=strip_tags("$x_pesan");
        //tutup
    }

return $msg;
}

function tambahteman($userid) {
include "koneksi.php";
$saya = $_SESSION['User']['userid'];
$nama = member_nama($userid);
	$sql="select * from t_member_contact where id_master='$saya' and id_con='$userid'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal cek teman");
	if($row = mysql_fetch_array($query)) {
		if ($row[status]=='0') $teman .="$nama telah anda minta menjadi teman anda.<br>Tunggu konfirmasi dari $nama.<br><br><a href='boxframe.php?id=kirimpesan&userid=".hex($saya,$noacak)."&tujuan=".hex($userid,$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a>";
	}
	else  {
	$teman .="Anda belum berteman dengan $nama sehingga tidak bisa melihat profilnya.<br>
	Apakah anda akan berteman dengan $nama ? <br><br>
	<a href='boxframe.php?id=tamteman&userid=".hex($saya,$noacak)."&tujuan=".hex($userid,$noacak)."' rel=\"facebox\" id='button2' >Tambah sebagai teman</a>&nbsp;&nbsp;&nbsp;<input type=hidden name='id' value='tamteman' ><a href='boxframe.php?id=kirimpesan&userid=".hex($saya,$noacak)."&tujuan=".hex($userid,$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a>";
	}
return $teman;
}

function user_gagal() {

$user_gagal .="<center><table border=0 class='art-article' width=400 height=100 cellspacing='0' cellpadding='5'>
 	<tr><td valign=top  ><h3><center>KONFIRMASI AKSES</center></h3></td></tr>
	<tr><td style='background-color:#FFFFFF' ><center><img src='../images/error.gif' style='border:0' align=left>&nbsp;&nbsp;Anda tidak diperkenankan mengakses fasilitas ini. <br>Silahkan daftar menjadi member dan login terlebih dahulu.</td></tr></table>
	<br><br>";

return $user_gagal;
}
function user_gagal1() {

$user_gagal .="<center><table border=0 class='art-article' width=400 height=100 cellspacing='0' cellpadding='5'>
 	<tr><td valign=top height=20 class='td0' ><center><font class='ver11' style='color:ff9900'><b>ACCESS CONFIRMATIONS</B></font></td></tr>
	<tr><td bgcolor='#ffffff'><center><img src='../images/error.gif' align=left >&nbsp;&nbsp;<font class='ver10' >You are not allowed to access this facility. Please Join us and login</font></td></tr></table><br><br>";

return $user_gagal;
}

?>