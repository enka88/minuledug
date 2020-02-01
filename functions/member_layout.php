<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi yang menampilkan layout kiri kanan dan fungsi foto
// create alanrm82
// fungsi tampilkan thumbnail foto member yg ukurannya kecil
function fotouser($userid) {
include "koneksi.php";
	$file = "profil/gb$userid.jpg";
	$fotouser ="<a href='user.php?id=lih_profil&kode=".hex($userid,$noacak)."' title='Lihat profil'><img src='profil/kosong.jpg' width='33' height='40' id='gambar' align=left ></a>";
	if (file_exists(''.$file.'')) {
	   $fotouser="<a href='user.php?id=lih_profil&kode=".hex($userid,$noacak)."' title='Lihat profil'><img src='thumb-user.php?img=$file' width='33' height='40' id='gambar' align=left /></a>";
	}
return $fotouser;
}
//fungsi tampilkan foto member ukuran kecil yang ada ket waktu login
function fotouser_waktu($userid,$tanggal) {
include "koneksi.php";
$nama = member_nama($userid);
	$file = "profil/gb$userid.jpg";
	$selisih = ambilselisih(strtotime($tanggal), time());
	$fotouser="<a href=\"user.php?id=lih_profil&kode=".hex($userid,$noacak)."\" rel=\"tooltip\" content=\"Klik disini untuk lihat profil $nama, login terakhir ".$selisih." \". ><img src='profil/kosong.jpg' width='50' height='60' id='gambar' ></a>";
	if (file_exists(''.$file.'')) {
	        $fotouser="<a href=\"user.php?id=lih_profil&kode=".hex($userid,$noacak)."\" rel=\"tooltip\" content=\"Klik disini untuk lihat profil $nama, login terakhir ".$selisih.". \"><img src='thumb-user.php?img=$file' id='gambar' width='50' height='60' /></a>";
	}
return $fotouser;
}
// fungsi foto group
function fotogroup($idgroup) {
include "koneksi.php";
	$file = "group/group$idgroup.jpg";
	$fotouser ="<a href='user.php?id=group&kdgroup=".hex($idgroup,$noacak)."' title='Lihat group'><img src='group/kosong.jpg' width='80' height='80' id='gambar' align=left ></a>";
	if (file_exists(''.$file.'')) {
	   $fotouser="<a href='user.php?id=group&kdgroup=".hex($idgroup,$noacak)."' title='Lihat group'><img src='thumb-user.php?img=$file' width='80' height='80' id='gambar' align=left /></a>";
	}
return $fotouser;
}
// fungsi layout kiri
function kiri() {
include "koneksi.php";
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];

//$depan .='<div id="judul" >Selamat Datang <i>'.$nama.'</i></div>';
$depan .="<div id='depan-kiri'>";
// blok kiri

$depan .="<div id='judul2'>Member Terbaru</div><div style='padding:5px 1px 1px 1px;' >";
// member terbaru
$sql="select userid,nama,status from t_member where status='1' and userid<>'".mysql_real_escape_string($userid)."' order by userid desc limit 0,6";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member");
	while($row=mysql_fetch_array($query)) {
		$depan .=fotouser_waktu($row[userid],$row[tgl_login]);
	}
	$depan .="</div>";
//member online
$skr=strtotime(date("Y-m-d"));
$kmr = date("Y-m-d",$skr - 36000);

$depan .="<div id='judul2'>Member lain Online - Chat</div><div style='padding:5px 1px 1px 1px;' >";	
  	$sql="select userid,nama,status,tgl_login,username from t_member where status='1' and tgl_login >='".$kmr."' and stlogin='1' and userid<>'".mysql_real_escape_string($userid)."' order by tgl_login desc limit 0,6 ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member online");
	$jum = mysql_num_rows($query);
	if ($jum==0) $depan .="&nbsp;&nbsp;Tidak ada teman yang online";
	while($row=mysql_fetch_array($query)) {
		$file = "profil/gb$row[userid].jpg";
		$selisih = ambilselisih(strtotime($row[tgl_login]), time());
		$gb="<a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".user_singkat($row[userid])."')\" rel=\"tooltip\" content=\"Silahkan klik disini untuk chating dengan $row[nama], login terakhir ".$selisih." \". ><img src='profil/kosong.jpg' width='50' height='60' id='gambar' ></a>";
		if (file_exists(''.$file.'')) {
	        $gb="<a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".user_singkat($row[userid])."')\" rel=\"tooltip\" content=\"Silahkan klik disini untuk chating dengan $row[nama], login terakhir ".$selisih.". \"><img src='thumb-user.php?img=$file' id='gambar' width='50' height='60' /></a>";
		}
		
		$depan .= $gb;
	}
//batas bawah blok kiri
$depan .="</div></div>";
return $depan;
}

// awal blok kanan
function kanan() {
include "koneksi.php";

$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
//awal shoutbox
$depan .="<div id='depan-kanan'>";
$depan .= '<script type="text/javascript">
// awal pengiriman muti submit pesan singkat
$(document).ready(function()
{$(".pesan_button").click(function() {
	var element = $(this);
    var test = $("#pesan").val();
	var userid = $("#userid4").val();
    var dataString = \'userid=\'+ userid +\'&pesan=\'+ test;
	if(test==\'\') {
		alert("Pesan masih kosong");
	}
	else if(test==\'Pesan\') {
		alert("Tuliskan pesan singkat Anda ");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php";}});
	}
	return false;
});

});
//akhir multi submit komentar
</script>';
$depan .="<div id='judul3'>Pesan Singkat</div>";	
 $depan .='<div id="shoutbox"><form action="" method="post" name="form">
 <marquee direction="up" onMouseover="this.stop()" onMouseOut="this.start()"
	scrollAmount=2 scrollDelay=90 direction=up height=140 align="center">';
	$sql="select * from t_pesan order by id desc limit 0,10";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal pesan pinggir");
	while ($row=mysql_fetch_array($alan)) {
		$pengirim=member_nama($row[pengirim]);
		$pesan=strip_tags($row[pesan]);
		$tgl = ambilselisih(strtotime($row[waktu]), time())." ";
		$depan .="<b>$pengirim</b>
		<br><font style='font-size:9px' >$tgl</font><br>$pesan<br>";
	}
	$depan .="</marquee><br><input type=text name='pesan' onfocus='clearText2(this)' onblur='clearText2(this)' value='Pesan' id='pesan' maxlength='100' size='20' rel='tooltip' content='Pesan atau Salam yang akan disampaikan.'><input type='hidden' name='userid' id='userid4' value='".hex("shoutbox,".$userid,$noacak)."' >
<input type=\"submit\"  value=\"Kirim\"  name=\"submit\" class=\"pesan_button\" id='button2'/>
	</form></div>";
	//akhir shoutbox
	// tampilkan materi download terbaru
	$sql="select * from t_download order by id desc limit 0,5";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 materi");
	$i=1;
	$depan .="<div id='judul3'>Materi Ajar Terbaru</div>";
	$depan .="<div id='shoutbox'><ul id='listmateri' >";
	while($row=mysql_fetch_array($query)) {
		$depan .="<li><a href='../html/guru.php?id=lihmateri&kode=$row[id]' target='_blank' title='Didownload $row[visit] kali' >$row[judul]</a></li>";
		$i++;
	}
	$depan.='</ul></div>';
	
	// tampilkan banner
	$sql="select * from t_banner where status='14' and aktif='1' order by id desc limit 0,5";
	if(!$result=mysql_query($sql)) die ("Pengambilan gagal banner");
	while ($rows = mysql_fetch_array($result)) {
			$xfile = $rows[jenis];
    		$src= "../images/banner/bn".$rows[id].".".$xfile;
			$link=$rows[url];
			$vlink1=$rows[id];
			$alt=$rows[alt];		
			if (!empty($link)) {
				$vlink="../functions/visit.php?id=$vlink1' target='_blank";
			}
			else $vlink="#";
			if ($xfile=='swf') {
			$depan .='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="160" height="60" title="'.$row[judul].'"><param name="movie" value="../images/banner/bn'.$row[id].'.swf">
          <param name="quality" value="high">
          <embed src="../images/banner/bn'.$row[id].'.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="160" height="60"></embed>
		  </object>';
				}
			else { $depan.="<div id='banner' ><a href='$vlink' ><img src='$src' alt='$alt' border='0' width='160' height='60'></a></div>"; }
    	}

$depan .="</div>";
//akhir dari blok kanan-------------------------
return $depan;
}

// awal blok kanan group
function kanangroup() {
include "koneksi.php";
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$userid = $_SESSION['User']['userid'];
$depan .='<script type="text/javascript">
function konfirmasi(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan keluar dari group "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=group&kdgroup='.hex($kdgroup,$noacak).'";}});
	}
}
function gabung(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan bergabung dengan group "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihgroup";}});
	}
}
</script>';

$depan .="<div id='depan-kanan'>";
	$file = "group/group".$kdgroup.".jpg";
	$gb1="<img src='group/kosong.jpg' width='190' height='240'  >";
	if (file_exists(''.$file.'')) {
		$size = getimagesize($file);
		$lebar = 190;
		$tinggi = round(($size[1]*$lebar)/$size[0],0);
		$gb1="<img src='$file' width='$lebar' height='$tinggi'  >";
	}
	$sql2="select idgroup,kategori from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal group");
	if($r = mysql_fetch_array($query2)) {
		$status = $r[kategori];
	}
	$depan .= "<div id='box-profil-img' >$gb1</div>";

	$depan .="<div id='judulgroup'>Menu Group</div>";	
if ( anggota_group($kdgroup,$userid) =='ya') { // seleksi apakah anda anggota dari group ini ?
	$depan .="<div class='box-group' ><ul>
	<li><a href='?id=group&kdgroup=".hex($kdgroup,$noacak)."' >Profil Group</a></li>
	<li><a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=group".hex($kdgroup,$noacak)."' rel=\"facebox\"  title='Klik untuk lihat mengirim pesan ' >Kirim Pesan ke Semua Anggota</a></li>
	<li><a href='?id=anggotagroup&kdgroup=".hex($kdgroup,$noacak)."' title='Klik untuk melihat anggota group' >Lihat Anggota</a></li>
	<li><a href='?id=infogroup&kdgroup=".hex($kdgroup,$noacak)."' title='Klik untuk melihat info group' >Info Group</a></li>
	<li><a href='?id=diskusigroup&kdgroup=".hex($kdgroup,$noacak)."' title='Klik untuk melihat info group' >Diskusi Group</a></li>";
	if ($status=='1') {
	$depan .="<li><a href='boxframe.php?id=editgroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."' rel=\"facebox\"  title='Klik untuk mengubah group ' >Edit Group</a></li>
	<li><a href='?id=undangmember&kdgroup=".hex($kdgroup,$noacak)."' title='Klik untuk mengundang member lain' >Undang Teman bergabung</a></li>";
	}
	$depan .="<li><a href='#' onclick=\"konfirmasi('".hex("keluar,".$userid,$noacak)."','".hex($kdgroup,$noacak)."','ini')\" title='Klik untuk keluar dari group ini' >Keluar Group</a></li>
	</ul></div>";
 }
 else { 
 	$depan .="<div class='box-group' ><ul><li><a href='?id=anggotagroup&kdgroup=".hex($kdgroup,$noacak)."' title='Klik untuk melihat anggota group' >Lihat Anggota</a></li>
	<li><a href='#' onclick=\"gabung('".hex("gbgp,".$userid,$noacak)."','".hex($kdgroup,$noacak)."','ini')\" title='Klik untuk bergabung dengan group ini' >Gabung ke Group</a></li></ul>
	</div>";
 }
$depan .="</div>";
//akhir dari blok kanan-------------------------
return $depan;
}

// kiri profil sendiri
function kiri_profil() {
include "koneksi.php";
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];

//$depan .='<div id="judul" >Selamat Datang <i>'.$nama.'</i></div>';
$depan .="<div id='depan-kiri'>";
// blok kiri
// foto profil pribadi
	$file = "profil/gb$userid.jpg";
	$gb1="<img src='profil/kosong.jpg' width='190' height='240'  >";
	if (file_exists(''.$file.'')) {
		$size = getimagesize($file);
		$lebar = 190;
		$tinggi = round(($size[1]*$lebar)/$size[0],0);
		$gb1="<img src='$file' width='$lebar' height='$tinggi'  >";
	}

	$depan .= "<div id='box-profil-img' >$gb1</div>";
$sql="select userid,nis,kelas,totlogin,point,username,tgllahir,email,ket,tgl_login from t_member where userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member pribadi ");
	$r=mysql_fetch_array($query);
$depan .="<div id='judul2'>Informasi Pribadi</div>";
// profil pribadi
	if ($r[ket]=='Siswa') $data="NIS : <b>$r[nis]</b><br>Kelas : <b>$r[kelas]</b><br>Email : <b>$r[email]</b><br>";
	else if ($r[ket]=='Orang Tua') {
		$sql2="select nama,userid,kelas from t_member where nis='".mysql_real_escape_string($r[nis])."' and ket='Siswa' ";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal member ortu");
		$rows=mysql_fetch_array($query2);
		$data="Oran Tua dari : <br><b>$rows[nama]</b><br>Kelas : <b>$rows[kelas]</b><br>
		<br>Email : <b>$r[email]</b><br>";
	}
	else if ($r[ket]=='Guru' or $r[ket]=='Admin' ) {
		$data="NIP : <b>$r[nis]</b><br>Email : <b>$r[email]</b><br>";
	}
	else if ($r[ket]=='Alumni') {
		$data="Alumni Angkatan : <b>$r[kelas]</b><br>Email : <b>$r[email]</b><br>";
	}
	else { $data="Status : <b>Tamu</b><br>Email : <b>$r[email]</b><br>"; }
	$selisih = ambilselisih(strtotime($r[tgl_login]), time());
	$depan .="<div id='box-profil'>$data <br>Point : <b>$r[point]</b><br>Login Terakhir : <br><b>$selisih</b></div>";
$sql2="select * from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and status='1'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal hitung teman");
	$tot = mysql_num_rows($query2);
// teman terupdate
$depan .="<div id='judul2'>$tot Teman  -  <a href='user.php?id=teman&ket=semua' style='color:#FFFFFF;' title='Lihat data semua teman' >Lihat Semua</a></div><div style='padding:5px 1px 1px 1px;' >";
$sql="SELECT t_member.userid,t_member.nama,t_member.tgl_login,t_member.username,t_member_contact.id_con,t_member_contact.id_master
FROM t_member_contact LEFT OUTER JOIN t_member ON (t_member_contact.id_con = t_member.userid) where t_member_contact.id_master='".mysql_real_escape_string($userid)."' and t_member_contact.status='1' order by tgl_login desc limit 0,6";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member online");
	$jum = mysql_num_rows($query);
	if ($jum==0) $depan .="&nbsp;&nbsp;Tidak ada teman ";
	while($row=mysql_fetch_array($query)) {
		$depan .=fotouser_waktu($row[userid],$row[tgl_login]);
	}
	$depan .="</div>";
//member online
$skr=strtotime(date("Y-m-d"));
$kmr = date("Y-m-d",$skr - 36000);

$depan .="<div id='judul2'>Teman Online - Chat</div><div style='padding:5px 5px 2px 5px;' >";	
  	$sql="SELECT t_member.userid,t_member.nama,t_member.ket,t_member.status ,t_member.tgl_login,t_member.username,t_member.stlogin, t_member_contact.id_con,t_member_contact.id_master,t_member.kerja
FROM t_member_contact LEFT OUTER JOIN t_member ON (t_member_contact.id_con = t_member.userid) where t_member_contact.id_master='".mysql_real_escape_string($userid)."' and t_member.status='1' and t_member.tgl_login >='".$kmr."' and t_member.stlogin='1' order by t_member.tgl_login desc ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member online");
	$jum = mysql_num_rows($query);
	if ($jum==0) $depan .="&nbsp;&nbsp;Tidak ada member online";
	while($row=mysql_fetch_array($query)) {
		$file = "profil/gb$row[userid].jpg";
		$selisih = ambilselisih(strtotime($row[tgl_login]), time());
		/*$gb="<a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".user_singkat($row[userid])."')\" rel=\"tooltip\" content=\"Silahkan klik disini untuk chating dengan $row[nama], login terakhir ".$selisih." \" ><img src='profil/kosong.jpg' width='50' height='60' id='gambar' ></a>";*/
        $gb="<a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".user_singkat($row[userid])."')\" rel=\"tooltip\" content=\"Silahkan klik disini untuk chating dengan $row[nama], login terakhir ".$selisih." \" ><img src='../member/css/cicle.jpg' height=10 widht=10>&nbsp;&nbsp;$row[nama] ($row[ket])</a><br/>";
		/*if (file_exists(''.$file.'')) {
	        $gb="<a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".user_singkat($row[userid])."')\" rel=\"tooltip\" content=\"Silahkan klik disini untuk chating dengan $row[nama], login terakhir ".$selisih.". \"><img src='thumb-user.php?img=$file' id='gambar' width='50' height='60' /></a>";
		}*/
		$depan .="$gb";
	}
//batas bawah blok kiri
$depan .="</div></div>";
return $depan;
}


function kiri_profil_lain() {
include "koneksi.php";
$userid = unhex($_GET['kode'],$noacak);
$saya = $_SESSION['User']['userid'];
$nama = $_SESSION['User']['nama'];
//$depan .='<div id="judul" >Selamat Datang <i>'.$nama.'</i></div>';
$depan .="<div id='depan-kiri'>";
// foto profil pribadi
	$file = "profil/gb$userid.jpg";
	$gb1="<img src='profil/kosong.jpg' width='190' height='240'  >";
	if (file_exists(''.$file.'')) {
		$size = getimagesize($file);
		$lebar = 190;
		$tinggi = round(($size[1]*$lebar)/$size[0],0);
		$gb1="<img src='$file' width='$lebar' height='$tinggi'  >";
	}
	$depan .= "<div id='box-profil-img' >$gb1</div>";
		
$sql2="select * from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and id_con='". mysql_real_escape_string($saya)."' and status='1' ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal apakah saya teman kamu ?");
if(mysql_num_rows($query2) > 0) {
//seleksi apakah saya adalah teman anda.........

// blok kiri
$sql="select userid,nis,kelas,totlogin,point,username,tgllahir,email,ket,tgl_login,nama,stlogin from t_member where userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member pribadi");
	$r=mysql_fetch_array($query);
$depan .="<div id='judul2'>Informasi Pribadi </div>";
// profil pribadi
	if ($r[ket]=='Siswa') $data="NIS : <b>$r[nis]</b><br>Kelas : <b>$r[kelas]</b><br>Email : <b>$r[email]</b><br>";
	else if ($r[ket]=='Orang Tua') {
		$sql2="select nama,userid,kelas from t_member where nis='".mysql_real_escape_string($r[nis])."' and ket='Siswa' ";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal member ortu");
		$rows=mysql_fetch_array($query2);
		$data="Oran Tua dari : <br><b>$rows[nama]</b><br>Kelas : <b>$rows[kelas]</b><br>---------------------------------
		<br>Email : <b>$r[email]</b><br>";
	}
	else if ($r[ket]=='Guru' or $r[ket]=='Admin' ) {
		$data="NIP : <b>$r[nis]</b><br>Email : <b>$r[email]</b><br>";
	}
	else if ($r[ket]=='Alumni') {
		$data="Alumni Angkatan : <b>$r[kelas]</b><br>Email : <b>$r[email]</b><br>";
	}
	else { $data="Status : <b>Tamu</b><br>Email : <b>$r[email]</b><br>"; }
	$selisih = ambilselisih(strtotime($r[tgl_login]), time());
	if ($r[stlogin]=='1') {
	$online="<a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".$r[username]."')\" rel=\"tooltip\" content=\"Silahkan klik disini untuk chating dengan $r[nama] \" ><b>Mau ngobrol dengan saya</b></a>";
	}
	$depan .="<div id='box-profil'>$data --------------------------------------------<br>Point : <b>$r[point]</b><br>Login Terakhir : <br><b>$selisih</b><br>$online</div>";
$sql2="select * from t_member_contact where id_master='".mysql_real_escape_string($userid)."'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal hitung teman");
	$tot = mysql_num_rows($query2);
// teman terupdate
$depan .="<div id='judul2'>$tot Teman  -  <a href='user.php?id=temanlain&kode=".hex($userid,$noacak)."' style='color:#FFFFFF;' title='Lihat data semua teman' >Lihat Semua</a></div><div style='padding:5px 1px 1px 1px;' >";
$sql="SELECT t_member.userid,t_member.nama,t_member.tgl_login,t_member.username,t_member_contact.id_con,t_member_contact.id_master
FROM t_member_contact LEFT OUTER JOIN t_member ON (t_member_contact.id_con = t_member.userid) where t_member_contact.id_master='".mysql_real_escape_string($userid)."' order by tgl_login desc limit 0,6";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member online");
	$jum = mysql_num_rows($query);
	if ($jum==0) $depan .="&nbsp;&nbsp;Tidak ada teman yang online";
	while($row=mysql_fetch_array($query)) {
		$depan .=fotouser_waktu($row[userid],$row[tgl_login]);
	}
	$depan .="</div>";
//teman yang sama 
$n=0;
$teman='';
$sql="select id_master,id_con,status from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and status='1'";
if(!$query=mysql_query($sql)) die ("Pengambilan gagal member online");
	while($row=mysql_fetch_array($query)) {
		$sql2="select id_master,id_con,status from t_member_contact where id_master='".mysql_real_escape_string($saya)."' and status='1' and id_con='$row[id_con]'";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal member online");
		if ( mysql_num_rows($query2)==1 and $n <=6 )
		{
			$query3=mysql_query("select userid,tgl_login,nama from t_member where userid='$row[id_con]'");
			$r=mysql_fetch_array($query3);
			$teman .=fotouser_waktu($r[userid],$r[tgl_login]);
		$n++;
		}

	}
  $depan .="<div id='judul2'>$n Teman yang sama</div><div style='padding:5px 1px 1px 1px;' >$teman";	
  $depan .="</div>";
} /// batas seleksi apakah saya teman anda
else {
$depan .="<div id='judul2'>Informasi Pribadi</div>";
$depan .="<div id='box-profil'>Maaf Anda tidak dapat mengakses informasi ini</div>";
}
//batas bawah blok kiri
$depan .="</div>";
return $depan;
}

function konfirmasi($ket,$kode) {
include "koneksi.php";
$konfir .="<div id='depan-tengah'>";
$kode = unhex($kode,$noacak);
$konfir .= statusanda($kode);
$konfir .="<hr style='border: thin solid #6A849D;'><br>";
$konfir .= $ket;
$konfir .="</div>";
return $konfir;
}
?>