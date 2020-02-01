<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi member profil pribadi dan lihat profil member lain
// 14 september 2009 : alanrm82
// terdiri dari fungsi profil, fungsi edit profil, fungsi simpan profil
// fungsi info pribadi/, fungsi lihat profil orang,

function profil() {
include "koneksi.php";
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
//$userhex = hex($userid,$noacak);
$hal = $_GET['hal'];
$depan .="<div id='depan-tengah'>";
// status member pribadi terakhir
//script jquery untuk menerima status anda....
$depan .='<script type="text/javascript">
//fungsi untuk show dan hide panel komentar
$(document).ready(function()
{
$(".comment_button").click(function(){

var element = $(this);
var I = element.attr("id");

$("#slidepanel"+I).slideToggle(300);
$(this).toggleClass("active"); 

return false;});});

$(document).ready(function()
{
$(".comment2").click(function(){

var element = $(this);
var I = element.attr("id");

$("#komenpanel"+I).slideToggle(300);
$(this).toggleClass("active"); 

return false;});});
</script>
<script type="text/javascript">

// awal pengiriman muti submit komentar
$(document).ready(function()
{
$(".commentkirim").click(function(){


var element = $(this);
var Id = element.attr("id");

var test = $("#textboxcontent"+Id).val();
var userid = $("#userid2").val();
var dataString = \'userid=\'+ userid +\'&textcontent=\'+ test + \'&idstatus=\' + Id;

if(test==\'\')
{
alert("Tuliskan komentarnya");
}
else if(test==\'Tuliskan komentar Anda....\')
{
alert("Tuliskan komentarnya");
}
else
{
$("#flash"+Id).show();
$("#flash"+Id).fadeIn(400).html(\'<img src="css/ajax-loader.gif" align="absmiddle"> loading.....\');

$("#textboxcontent"+Id).val(\'Tuliskan komentar Anda....\');

$.ajax({
type: "POST",
url: "kontenstatus.php",
data: dataString,
cache: false,
success: function(html){
$("#loadplace"+Id).append(html);
$("#flash"+Id).hide();

}
});

}

return false;});});
//akhir multi submit komentar
</script>';
$depan .= '<script type="text/javascript">
// awal pengiriman muti submit komentar
$(document).ready(function()
{
$(".status_button").click(function(){
var element = $(this);

var cont = $("#stcontent").val();
var userid = $("#userid3").val();
var dataString = \'userid=\'+ userid +\'&stcontent=\'+ cont ;

if(cont==\'\')
{
alert("Status yang Anda isikan masih kosong");
}
else if(cont==\'Tuliskan status Anda saat ini !!!\')
{
alert("Tuliskan status Anda saat ini");
}
else {

$.ajax({
type: "POST",
url: "kontenstatus.php",
data: dataString,
cache: false,
success: function(html){window.location="user.php?id=kirimwall";}
});

}
return false;
});});
//akhir multi submit komentar
</script>';
$depan .= statusanda($userid);
/*$depan .="<form  method='post' name='form' action=''><div id='box-status'><img src='../images/status.png' align='left'> &nbsp;<textarea name='content' id='stcontent' style='width:380px;height:20px;' maxlength='255' onfocus='clearText(this)' onblur='clearText(this)' rel='tooltip' content='Silahkan masukan status Anda saat ini. </br>untuk diketahui temen lain.' >Tuliskan status Anda saat ini !!! </textarea> 
<input type=hidden name='userid3' id='userid3' value='".hex("statusanda,".$userid,$noacak)."'>&nbsp;&nbsp;<input type=\"submit\"  value=\"Simpan\"  name=\"submit\" class=\"status_button\"  /></div></form>";*/
$depan .="<div id='box-status'>
<form  method='post' name='form' action=''>
<table>
<tr><td>
&nbsp;<textarea name='stcontent' id='stcontent' style='width:450px;height:20px;' onfocus='clearText(this)' onblur='clearText(this)' rel='tooltip' content='Silahkan masukan status Anda saat ini. </br>untuk diketahui temen lain.'  >Apa yang Anda pikirkan?</textarea></td></tr>
<tr><td style=text-align:right><input type=\"submit\"  value=\"Bagikan\"  name=\"submit\" class=\"status_button\"  /><input type='hidden' name='userid3' id='userid3' value='".hex("statusanda,".$userid,$noacak)."' ></td></tr></table></form></div>";

$depan .="<hr style='border: thin solid #6A849D;'>";
$depan .="<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
if ($level=='Guru') {
$depan .="<br><input type='button' id='button2' onclick=\"location.href='user.php?id=gurutugtambah'\" value='Tambah Tugas' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=tamdownload'\" value='Tambah Materi Ajar' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=tamsoal'\" value='Tambah Materi Uji/Soal' >";
}

// konfirmasi pesan baru yg masuk, 
// konfirmasi menjadi teman anda, 
// konfirmasi anda menjadi teman siapa?
// konfirmasi invite group
// konfirmasi undangan group baru
// konfirmasi berita group terbaru
// konfrimasi komentar baru dari group
$depan .="<h3>Aktivitas terbaru</h3>";
$sql = mysql_query("SELECT id FROM t_member_pesan where status='0' and (semua='1' or tujuan_id='".mysql_real_escape_string($userid)."') ");
$jum=mysql_num_rows($sql); // pesan terbaru
if ($jum>0) $depan .="<img src='../images/letter1.png' align=top >&nbsp;<a href='?id=pesan' title='Klik untuk lihat pesan' >$jum Pesan masuk terbaru</a><br>";
$sql = mysql_query("SELECT id FROM t_member_contact where status='0' and id_con='".mysql_real_escape_string($userid)."' ");
$jum=mysql_num_rows($sql); //permintaan menjadi teman
if ($jum>0) $depan .="<img src='../images/adduser.png' align=top >&nbsp;<a href='?id=permintaan' title='Klik untuk lihat permintaan' >$jum Permintaan menjadi teman</a><br>";
$sql = mysql_query("SELECT id FROM t_member_contact where status='2' and id_master='".mysql_real_escape_string($userid)."' ");
$jum=mysql_num_rows($sql); //penolakan menjadi teman
if ($jum>0) $depan .="<img src='../images/userblok.png' align=top >&nbsp;<a href='?id=tolakteman' title='Klik untuk lihat data penolakan menjadi teman' >$jum Penolakan menjadi teman</a><br>";
$sql = mysql_query("SELECT idgroup FROM t_membergroup_anggota where status='0' and userid='".mysql_real_escape_string($userid)."' ");
$jum=mysql_num_rows($sql); // undangan group
if ($jum>0) $depan .="<img src='../images/group.png' align=top >&nbsp;<a href='?id=undangan' title='Klik untuk lihat undangan group' >$jum Undangan group</a><br>";
$sql = mysql_query("SELECT id_con FROM t_member_contact where status='1' and id_master='".mysql_real_escape_string($userid)."' order by id desc limit 0,2 ");
while($row=mysql_fetch_array($sql)) { //anda telah menjadi teman member lain
	$gb3=fotouser($row[id_con]);
	$depan .="<img src='../images/adduser.png' align=top >&nbsp;Anda dan <a href='?id=lih_profil&kode=".hex($row[id_con],$noacak)."' rel=\"tooltip\" content=\"$gb3 Silahkan klik disini untuk melihat profilnya.\" >".member_nama($row[id_con])."</a> sekarang berteman<br>";
}
$skr=strtotime(date("Y-m-d"));
$kmr = date("Y-m-d",$skr - (86400*3));
// info terbaru
$sql = mysql_query("SELECT count(t_membergroup_info.idgroupinfo) as jum, t_membergroup_anggota.idgroup FROM t_membergroup_info,t_membergroup_anggota where t_membergroup_anggota.idgroup=t_membergroup_info.idgroup and t_membergroup_anggota.userid='".mysql_real_escape_string($userid)."' and  t_membergroup_info.tanggal >='".$kmr."' group by t_membergroup_anggota.idgroup ");
while($row=mysql_fetch_array($sql)) { // info group terbaru
	$depan .="<img src='../images/newsgroup.png' align=top >&nbsp;<a href='?id=group&kdgroup=".hex($row[idgroup],$noacak)."' title='Klik untuk lihat info baru dari group ini' >$row[jum] Info baru dari group ".nama_group($row[idgroup])."</a><br>";
}
// diskusi terbaru
$sql = mysql_query("SELECT count(t_membergroup_diskusibalas.idbalas) as jum,t_membergroup_anggota.idgroup 
FROM t_membergroup_diskusi,t_membergroup_diskusibalas,t_membergroup_anggota
where t_membergroup_diskusibalas.idtopik=t_membergroup_diskusi.idtopik and t_membergroup_anggota.idgroup=t_membergroup_diskusi.idgroup and t_membergroup_anggota.userid='".mysql_real_escape_string($userid)."' 
and  t_membergroup_diskusibalas.tanggal >='".$kmr."' group by  t_membergroup_anggota.idgroup ");
while($row=mysql_fetch_array($sql)) { // info group terbaru
	$depan .="<img src='../images/group.png' align=top >&nbsp;<a href='?id=diskusigroup&kdgroup=".hex($row[idgroup],$noacak)."' title='Klik untuk lihat diskusi baru dari group ini' >$row[jum] balasan diskusi baru dari group ".nama_group($row[idgroup])."</a><br>";
}

// cek apakah ada tugas yang belum dikerjakan ini....
if ($level=='Siswa') {
	$nis = konversi_id($userid);
	$kelas = konversi_kls($nis);
 $sql = mysql_query("SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas  and t_tugas_kelas.kelas='".mysql_real_escape_string($kelas)."' order by t_tugas.tgl_kirim DESC limit 0,5");
 while($row=mysql_fetch_array($sql)) { 
 	if ($row[jenis]=='1') {
		$sql2 = mysql_query("select * from t_tugas_siswa where idtugas='".$row[idtugas]."' and nis='".$nis."'");
		if(!$r=mysql_fetch_array($sql2)) {
		$depan .="<img src='../images/nilai.png' align=top >&nbsp;<a href='?id=tugasdetail&kd=".$row[idtugas]."' title='Klik untuk lihat Tugas tersebut' >Anda belum menyelesaikan Tugas ".$row[pelajaran]."</a><br>";
		}
	}
	else {
		$depan .="<img src='../images/nilai.png' align=top >&nbsp;<a href='?id=tugasdetail&kd=".$row[idtugas]."' title='Klik untuk lihat Tugas tersebut' >Anda mendapatkan Tugas terbaru Pelajaran ".$row[pelajaran]."</a><br>";
	}
 }

}
//seleksi halaman dibatasi hanya 30 status yang bisa dilihat
$byk=10;
if ($hal=='1')  $awal=0; 
else if ($hal=='2') $awal=10;
else if ($hal=='3') $awal=10;
else if ($hal=='4') $awal=10;
else if ($hal=='5') $awal=10;
else { $awal=0; $hal=1;} 
$sql2 = mysql_query("SELECT * FROM t_memberstatus where userid='".mysql_real_escape_string($userid)."' order by idstatus");
$n=mysql_num_rows($sql2);
$jml = intval($n/10);
if (($n % 10)>0) $jml=$jml+1; 

if ($jml >= 2) {
for($i=1;$i<=$jml;$i++) {
	if ($hal==$i) $pag .="<a class='sel'>$i</a>";
	else $pag .="<a href='user.php?id=profil&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
}
}

// paging halaman
$depan .= "<div id='pag' align=right >$pag</div>";
$depan .="<hr style='float:none;border:#dedede dashed thin;'>";
$depan .='<ul  id="update" class="timeline">';
//khusus untuk member teman saja seleksi belum dilakukan
$sql = mysql_query("SELECT * FROM t_memberstatus where userid='".mysql_real_escape_string($userid)."' order by tanggal desc limit $awal,$byk");
while($row=mysql_fetch_array($sql))
{
	$msg = statusmember($row[pengirim],$row[jenis],$row[pesan]);
	
	$msgid=$row['idstatus'];
	$nama = member_nama($row[pengirim]);
	$gb=fotouser($row[pengirim]);
	
	$selisih = ambilselisih(strtotime($row[tanggal]), time());
	$sql2 = mysql_query("SELECT idstatus FROM t_memberstatus_kom where idstatus='$msgid' order by idstatuskom ");
	$tot = mysql_num_rows($sql2);
	if ($tot <=3 ) { $lihat =""; $batas = 0; }
	else { $lihat = '<a href="#" class="comment2" id="'.$msgid.'" style="color:#6A849D;">Lihat komentar lain ';
		$batas=$tot-3;
		if ($tot <= 13) $lihat .= "(".($tot-3).")</a>";
		else { $lihat .= "</a>"; $batas=10;}	
	}
	$depan .='<li>'.$gb.'<b>'.$nama.'</b> :: '.$selisih.'<div id="sthapus" ><a href="user.php?id=sthapus&kode='.$row[idstatus].'" title="Klik untuk menghapus status">x</a></div><br>'.$msg.'<br>
			<a href="#" class="comment_button" id="'.$msgid.'" style="color:#6A849D;" >Tambah Komentar</a>
			 '.$lihat.'
		  </li>';
		  $depan .="<div class='komen' id=\"komenpanel".$msgid."\">";
		  $n=0;
		  	$byk=13;
			if ($tot >= $byk )  $awal = $tot-$byk;  
			else  $awal =0;
	$sql2 = mysql_query("SELECT * FROM t_memberstatus_kom where idstatus='$msgid' order by idstatuskom limit $awal,$byk");	  
		  while($r=mysql_fetch_array($sql2)) {
				if ($n == $batas )   $depan .="</div><div class='komen2' >"; 
				$nama = member_nama($r[userid]);
				$pesan = $r[pesan];
                				    //tambahan
                $x_pesan =$r[pesan];
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
                $gb=fotouser($r[userid]);
				$selisih = ambilselisih(strtotime($r[tanggal]), time());
				$depan .= "<div id='komen3'>$gb <b>$nama</b> :: $selisih <div id='sthapus' ><a href='user.php?id=komhapus&kode=$r[idstatuskom]&idstatus=$r[idstatus]' title='Klik untuk menghapus komentar'>x</a></div><br>$pesan </div>";
				$n++;
		  }
		  $depan .="</div>
		  <div  id=\"loadplace".$msgid."\" ></div>";
$depan .= "<div id=\"flash".$msgid."\" class='flash_load'></div>
<div class='panel' id=\"slidepanel".$msgid."\">";
$depan .='<form action="" method="post" name="'.$msgid.'"><table><tr><td><textarea style="width:400px;height:20px" id="textboxcontent'.$msgid.'" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea></td></tr><tr><td style=text-align:right><input type="submit" value="  Kirim  "  class="commentkirim" id="'.$msgid.'" /><input type=hidden name="userid2" value="'.hex("komstatus,".$userid,$noacak).'" id="userid2" ></td></tr></table>
</form>
</div>';

} 

$depan .="</ul>";
$depan .= "<div id='pag' align=right >$pag</div><br>";

//batas bawah tengah
$depan .="</div>";
return $depan;
}

function infoprofil() {
include "koneksi.php";
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$pesan ="<i>...</i>";
$info .="<div id='depan-tengah'>";

$info.= statusanda($userid);

$info .="<hr style='border: thin solid #6A849D;'>";
$info .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;
<input type='button' id='button2' onclick=\"location.href='user.php?id=editprofil'\" value=' Edit Profil ' >&nbsp;&nbsp;&nbsp;
<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
$info .="<hr style='float:none;border:#dedede dashed thin;'>";
	$sql="select * from t_member where userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal status member");
	if($row=mysql_fetch_array($query)) {
	 //if ($row[stprofil]=='open'){  khusus untuk lihat profil
		if ($row[kelamin]=='m') $kel ='Laki-laki';
		else $kel='Perempuan';
	  if ($row[ket]=='Siswa') $nis ="<tr><td>NIS</td><td><b>$row[nis]</b></td></tr><tr><td>Kelas</td><td><b>$row[kelas]</b></td></tr>";
	  else if ($row[ket]=='Guru' && $row[ket]=='Admin') $nis ="<tr><td>NIP</td><td><b>$row[nis]</b></td></tr>";
	  else if ($row[ket]=='Alumni') $nis ="<tr><td>Angkatan Tahun</td><td><b>$row[kelas]</b></td></tr>";
	  else $nis ="";
	  if ($row[ket]=='Orang Tua') { 
	     $sql2="select nama,userid,nis,kelas from t_member where nis='".mysql_real_escape_string($row[nis])."' and ket='Orang Tua' ";
		 if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal status member");
		 if($r=mysql_fetch_array($query2)) {
		  	$ortu ="<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>Informasi Keluarga</b></td></tr>
			<tr><td>Orang Tua dari</td><td><b>".$r[nama]."</b></td></tr>
			<tr><td>NIS</td><td><b>$r[nis]</b></td></tr><tr><td>Kelas</td><td><b>$r[kelas]</b></td></tr>";
	  	  }
	  }
	$info .="<table border=0 ><tr><td colspan=2><b>Informasi Umum</b></td></tr>
	<tr><td width='100' >Nama</td><td><b>$row[nama]</b></td></tr>$nis<tr><td>Jenis Kelamin</td><td><b>$kel</b></td></tr>
	<tr><td>Tanggal Lahir</td><td><b>$row[tgllahir]</b></td></tr><tr><td>Pekerjaan</td><td><b>$row[kerja]</b></td></tr>
	<tr><td>Alamat</td><td><b>$row[alamat]</b></td></tr><tr><td>Negara</td><td><b>$row[negara]</b></td></tr>
	<tr><td>Telp</td><td><b>$row[telp]</b></td></tr><tr><td>Sekolah</td><td><b>$row[sekolah]</b></td></tr>
	<tr><td>Email</td><td><b>$row[email]</b></td></tr><tr><td>Homepage/Blog</td><td><b><a href='http://$row[homepage]' target='_blank' >$row[homepage]</a></b></td></tr>
	<tr><td>Tentang Saya</td><td><b>$row[profil]</b></td></tr>$ortu<tr><td colspan=2>&nbsp;</td></tr>
	<tr><td colspan=2><b>Informasi Khusus</b></td></tr><tr><td>Status Login</td><td><b>$row[ket]</b></td></tr>
	<tr><td>Point</td><td><b>$row[point]</b></td></tr>
	<tr><td>Total Login</td><td><b>$row[totlogin]</b></td></tr><tr><td>Login Terakhir</td><td><b>".date("d M Y H:i",strtotime($row[tgl_login]))."</b></td></tr><tr><td>IP Kom. Terakhir</td><td><b>$row[ip]</b></td></tr>
	</table>
	";
	
	}
//batas akhir bag tengah
$info .="</div>";
return $info;
}

// function edit profil sendiri
function editprofil() {
include "koneksi.php";
include "fungsi_negara.php";
$userid = $_SESSION['User']['userid'];
$profil .="<div id='depan-tengah'>";
$profil .= statusanda($userid);
$profil .='<script type="text/javascript" src="js/jquery.validate.pack.js" ></script>
<script type="text/javascript">

$(document).ready(function() {
	
	$("#formID").validate({
		rules: {
			password: {
				required: true,
				minlength: 6
			},
			confirm_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
			nfile: {
				required: true,
				accept:"jpg"
			}
		},
		messages: {
			email: {
				required: "E-mail harus diisi",
				email: "Masukkan E-mail yang valid",
				remote: jQuery.validator.format("Email yang anda masukan sudah terdaftar.")
			},
			nfile: {
				required: "File harus diisi",
				accept: "Format file salah, seharusnya format Gambar JPG"
			},
			password: {
				required: "Password harus diisi kembali",
				minlength: "Password minimal 6 karakter"
			},
			confirm_password: {
				required: "Password harus diisi kembali",
				minlength: "Password minimal 6 karakter",
				equalTo: "Password tidak sama dengan yang diatas"
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("td"));
		},
		submitHandler: function() {
		
			var dataString = \'userid=\'+ $("#userid").val() + \'&jenis=\'+ $("#jenis").val() + \'&neg=\'+ $("#neg").val() + \'&tgl=\'+ $("#tgl").val() + \'&name=\'+ $("#name").val() + \'&kelamin=\'+ $("#kelamin").val() + \'&nis=\'+ $("#nis").val() + \'&kelas=\' + $("#kelas").val() + \'&email=\'+ $("#email").val() + \'&password=\'+ $("#password").val() + \'&pertanyaan=\'+ $("#pertanyaan").val() + \'&jawaban=\'+ $("#jawaban").val() + \'&hari=\'+ $("#hari").val() + \'&bulan=\'+ $("#bulan").val() + \'&tahun=\'+ $("#tahun").val() + \'&kerja=\'+ $("#kerja").val() + \'&alamat=\'+ $("#alamat").val() + \'&sekolah=\'+ $("#sekolah").val() + \'&telp=\'+ $("#telp").val()
+ \'&blog=\'+ $("#blog").val() + \'&tentang=\'+ $("#tentang").val()+ \'&country=\'+ $("#country").val()+ \'&stprofil=\'+ $("#stprofil").val()+ \'&stblog=\'+ $("#stblog").val()+ \'&face=\'+ $("#face").val();
		$.ajax({type: "POST",url: "membersave.php",data: dataString,cache: false,
		success: function(html){window.location="user.php?id=infoprofil";}});
		alert("Perubahan data berhasil dilakukan !!! ");
		}
	});
})
</script>';
$profil .="<hr style='border: thin solid #6A849D;'>";
$profil .="<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
	$query = "SELECT * FROM t_member where userid='".mysql_real_escape_string($userid)."'";
    $result = mysql_query($query) or die("Query failed member");
    if($row = mysql_fetch_array($result)) {
	$name=$row[nama];$kelamin=$row[kelamin];$username=$row[username];
	if ($kelamin=='m') $kel1="selected";
	else $kel2="selected";
	$pertanyaan=$row[pengingat];$jawaban=$row[jawaban];
    if ($pertanyaan=='1') $p1="selected";
    elseif ($pertanyaan=='2') $p2="selected"; 
	elseif ($pertanyaan=='3') $p3="selected"; 
	elseif ($pertanyaan=='4') $p4="selected"; 
    elseif ($pertanyaan=='5') $p5="selected"; 
    elseif ($pertanyaan=='6') $p6="selected";
    elseif ($pertanyaan=='7') $p7="selected";
    elseif ($pertanyaan=='8') $p8="selected";
    elseif ($pertanyaan=='9') $p9="selected";		
	$name=$row[nama];
	$tgl=($row[tgllahir]);
	$alamat=$row[alamat];
	$country=negara($row[negara]);
	$school=$row[sekolah];
	$telp=$row[telp];
	$email=$row[email];
	$homepage=$row[homepage];
	$ket=strip_tags($row[profil]);
	if ($row[color]=='1') $warna='';
	else $warna =$row[color];
	if($row[kerja]=='Guru') $ke1="selected";
	elseif($row[kerja]=='Siswa') $ke2="selected";
	elseif($row[kerja]=='Mahasiswa') $ke3="selected";
	elseif($row[kerja]=='Dosen') $ke4="selected";
	elseif($row[kerja]=='Praktisi') $ke5="selected";
	elseif($row[kerja]=='Pegawai Negeri') $ke6="selected";
	elseif($row[kerja]=='Pegawai Swasta') $ke7="selected";
	elseif($row[kerja]=='Lain-lain') $ke8="selected";
	
	if ($row[stblog]=='1') $stblog='checked';
	else $stblog='';
	if ($row[stprofil]=='open') $stpro1="selected";
	else $stpro2="selected";

    $kls = $row[kelas];
    
    if($row['setfacebook']=='ya') $f1='selected';
    else $f2 = 'selected';
    
// 		$query1 = mysql_query ("SELECT * FROM t_kelas order by kelas");
//		while($r=mysql_fetch_array($query1)) {
//			if ($r[kelas]==$row[kelas]) $kls .="<option value='$r[kelas]' selected>$r[kelas]</option>";
//			else $kls .="<option value='$r[kelas]'>$r[kelas]</option>";
//		}
	}
	$profil .="<div id='result' >Perubahan Data Profil Anda</div>
	<form name='formID' id='formID' action='' method='post'   >
	<table border=0 width='98%' cellspacing='2' cellpadding='2' bordercolor='#000000'><tr><td colspan=3 >";
	$profil .="<input type=hidden name=neg value='$country' id='neg'><input type=hidden name=tgl value=$tgl id='tgl'>
	<input type=hidden name='userid' value='".hex("simedit,".$userid,$noacak)."' id='userid'>
	<input type=hidden name='jenis' value='".hex($row[ket],$noacak)."' id='jenis'>";
	$profil .="<tr><td colspan=3 ><div id='blok-editprofil' >Informasi Khusus </div></td></tr>	
	<tr >
		<td align=right>Nama</td>
		<td>:</td>
		<td>";
	if ($row[ket]=='Guru' or $row[ket]=='Siswa') $profil .=" $name <input type=hidden name=name value='$name' id='name'>";
	else $profil .="<input type='text' name='name' value='$name' id='name' size=20 maxlength='30' class='required' title='Nama harus diisi' >";
	
	$profil .="</td>
	</tr>
	<tr >
		<td align=right valign=top >Kelamin</td>
		<td valign=top >:</td>
		<td><SELECT name='kelamin' id='kelamin' class='required' title='Jenis kelamin harus dipilih' >
		<OPTION value='' >[Pilih]</option><OPTION $kel1 value='m' >Laki-laki</option><OPTION $kel2 value='f' >Perempuan</OPTION></SELECT>
		</td>
	</tr>";
	if ($row[ket]=='Guru'){
	$profil .="<tr >
		<td align=right valign=top >NIP</td>
		<td valign=top >:</td>
		<td><input  id='nis' type=text name=nis value='$row[nis]' size=15  readonly >
		</td>
	</tr>"; }
	elseif ($row[ket]=='Siswa'){
	$profil .="<tr >
		<td align='right' valign='top' >NIS</td>
		<td valign='top' >:</td>
		<td><input id='nis' type=text name='nis' value='$row[nis]' size=15  readonly >
		</td>
	</tr>
	<tr >
		<td align='right' valign='top' >Kelas</td>
		<td valign='top' >:</td>
		<td><input type=hidden name='kelas' value='$kls' id=kelas >$kls
		</td>
	</tr>"; }	
	elseif ($row[ket]=='Alumni'){
	$profil .="<tr >
		<td align='right' valign='top' >Tahun Angkatan</td>
		<td valign='top' >:</td>
		<td><input  id='kelas' type=text name=kelas value='$row[kelas]' size=15  class='required' maxlength='4' title='Tahun Angkatan harus diisi' >
		</td>
	</tr>"; }
	elseif ($row[ket]=='Orang Tua'){
		$sql2="select nama,userid,kelas from t_member where nis='".mysql_real_escape_string($row[nis])."' and ket='Siswa' ";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal member ortu");
		$rows=mysql_fetch_array($query2);
		$nmsiswa=$rows[nama];
		$nmkelas=$rows[kelas];
	$profil .="<tr >
		<td align='right' valign='top' ><b>Orang Tua dari</b></td>
		<td valign='top' ></td>
		<td></td>
	</tr>
	<tr >
		<td align='right' valign='top' >Nama Siswa</td>
		<td valign='top' >:</td>
		<td>$nmsiswa</td>
	</tr>
	<tr >
		<td align='right' valign='top' >Kelas</td>
		<td valign='top' >:</td>
		<td>$nmkelas</td>
	</tr>"; }
	$profil .="<tr><td colspan=3 ><div id='blok-editprofil' >Informasi Login</div></td></tr>
	<tr >
		<td align='right' valign='top' >Username ID</td>
		<td valign='top'>:</td>
		<td>$username";
		$neg = datanegara2();
		$profil .="</td>
	</tr>
	<tr >
		<td align=right valign='top'>Email</td>
		<td valign='top'>:</td>
		<td><input id='email' type=text name='email' remote='cekemail.php' size=30 value='$email' class='required email' >
		<br>Masukan email yang valid dan aktif.</td>
	</tr>
	<tr >
		<td align='right' valign='top'>Password</td>
		<td valign='top'>:</td>
		<td><input  id='password' type='password' name='password' size=20 style='width:180; height:20;' ></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Re-type Password</td>
		<td valign='top'>:</td>
		<td><input id='confirm_password'  type=password name='confirm_password' size=20 style='width:180; height:20;' ></td>
	</tr>
	<tr >
		<td align=right>Tgl Lahir</td>
		<td>:</td>
		<td>";
        $profil .="<FONT class='ver10'><SELECT name='hari' id='hari' >";
        for($p=1;$p<=31;$p++) {
            if ( strlen($p)==1) $hr = "0".$p;
            else $hr = $p;
            
            if ($hr==substr($tgl,0,2)) $profil .="<OPTION value='$hr' selected >$hr</OPTION>";
            else $profil .="<OPTION value='$hr' >$hr</OPTION>";
        }
        
        $profil .="</SELECT>";
         $profil .="&nbsp;<SELECT name='bulan' id='bulan' >";
        for($p=1;$p<=12;$p++) {
            if (strlen($p)==1) $bl = "0".$p;
            else $bl = $p;
            if ($bl==substr($tgl,3,2)) $profil .="<OPTION value='$bl' selected >$bl</OPTION>";
            else $profil .="<OPTION value='$bl' >$bl</OPTION>";
        }           
		$profil .="</SELECT>";
        
		$profil .="<SELECT id='tahun' name='tahun'>";
        for($p=1920;$p<=2000;$p++) {
            if ($p==intval(substr($tgl,6,4))) $profil .="<OPTION value='$p' selected >$p</OPTION>";
            else $profil .="<OPTION value='$p' >$p</OPTION>";
        }   
        
		$profil .="</SELECT>
		</td>
	</tr>
	<tr >
		<td align='right' valign='top'>Pertanyaan</td>
		<td valign='top'>:</td>
		<td>
		<SELECT name='pertanyaan' id='pertanyaan'  class='required' title='Konfirmasi pertanyaan harus dipilih' >
		<OPTION value='' selected>[Pilih Pertanyaan]</option>
		<OPTION $p1 value='1'>Apa nama binatang peliharaan?</option>
		<OPTION $p2 value='2'>Apa nama sekolah anda pertama kali?</option>
		<OPTION $p3 value='3'>Siapa pahlawan anda?</option>
		<OPTION $p4 value='4'>Dimana tempat favorit anda?</option>
		<OPTION $p5 value='5'>Apa hobby anda?</option>
		<OPTION $p6 value='6'>Dimana tempat kerja anda?</option>
		<OPTION $p7 value='7'>Apa warna kesukaan anda?</option>
		<OPTION $p8 value='8'>Apa makanan favorit anda?</option>
		<OPTION $p9 value='9'>Apa binatang kesukaan anda?</OPTION>
		</SELECT>
		</td>
	</tr>
	<tr>
		<td align='right' valign='top'>Jawaban</td>
		<td valign='top'>:</td>
		<td><input id='jawaban'  class='required' type=text name='jawaban' value='$jawaban' size=20 maxlength='30' title='Konfirmasi jawaban harus diisi' ></td>
	</tr>
	
	<tr><td colspan=3 ><div id='blok-editprofil' >Informasi Pribadi </div></td></tr>
	<tr >
		<td align='right' valign='top'>Status Informasi</td>
		<td valign='top'>:</td>
		<td><select name='stprofil' id='stprofil' ><option value='open' $stpro1 >Tampilkan ke teman</option>
		<option value='hide' $stpro2 >Sembunyikan</option></select>
		</td>
	</tr>
		<tr >
		<td align=right valign=top>Pekerjaan</td>
		<td valign=top>:</td>
		<td><SELECT name='kerja' id='kerja' class='required' title='Pekerjaan harus dipilih'><OPTION value='Guru' $ke1>Guru</OPTION><OPTION value='Siswa' $ke2>Siswa</OPTION>
		<OPTION value='Mahasiswa' $ke3>Mahasiswa</OPTION><OPTION value='Dosen' $ke4>Dosen</OPTION>
		<OPTION value='Praktisi' $ke5>Praktisi</OPTION>
		<OPTION value='Pegawai Negeri' $ke6>Pegawai Negeri</OPTION><OPTION value='Pegawai Swasta' $ke7>Pegawai Swasta</OPTION>
		<OPTION value='Lain-lain' $ke8>Lain-lain</OPTION></select></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Alamat</td>
		<td valign='top'>:</td>
		<td><textarea name='alamat' id='alamat' class='required' cols='40' rows='6' title='Alamat harus diisi' maxlength='100' >$alamat</textarea></td>
	</tr>
	<tr >
		<td align='right'>Negara</td>
		<td valign='top'>:</td>
		<td>$country&nbsp;&nbsp;
		$neg
		</td>
	</tr>

	<tr >
		<td align='right' valign='top'>Sekolah</td>
		<td valign='top'>:</td>
		<td><input  type=text name=sekolah id='sekolah' class='required' title='Sekolah harus diisi' value='$school' size=40  maxlength='50'></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Telp/HP</td>
		<td valign='top'>:</td>
		<td><input  type=text name=telp id='telp' size=20 value='$telp' class='required' style='width:180; height:20;' title='No Telp harus diisi' maxlength='30' ></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Homepage/Blog</td>
		<td valign='top'>:</td>
		<td>http:// <input  type=text name='blog' size=30 id='blog' value='$homepage' maxlength='50'> 
		<br>Tidak menggunakan http://
		<br><input type=checkbox name='stblog' id='stblog' value='on' $stblog > Blog ini mau ditampilkan di Daftar Blog Member  </td>
	</tr>
		<tr >
		<td align='right' valign='top'>Tentang Anda</td>
		<td valign='top'>:</td>
		<td><textarea name='tentang' id='tentang'  cols='40' rows='6'>$ket</textarea>
		<br>Seperti : Hobby,Aktivitas,dsb <br></td>
	</tr>";
    
$profil .="<tr >
		<td align='right' valign='top'>Status Anda</td>
		<td valign='top'>: </td>
		<td valign='top'><select name='face' id='face' ><option value='ya' $f1 >Ya</option><option value='tidak' $f2 >Tidak</option></select> kirim ke Facebook </td>
	</tr>
		</tr>";
$profil .="
	<tr><td colspan=3 >
	<input type='submit' id='button2' name='submit' value='Simpan'>&nbsp;
	</td></tr>	
	</table></form>";
$profil .="</div>";
return $profil;
}

function konvdigit($data) {
    if (len($data)==1) $data ="0".$data;
    return $data;
}
// menampilkan data log siapa saja yang melihat data anda.......
function logprofil() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$hal = $_GET['hal'];
$depan .="<div id='depan-tengah'>";
// status member pribadi terakhir
//script jquery untuk menerima status anda....
$depan .= statusanda($userid);
$depan .="<hr style='border: thin solid #6A849D;'>";
$depan .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";

//seleksi halaman dibatasi hanya 30 status yang bisa dilihat
$byk=20;
if ($hal=='1')  $awal=0; 
else if ($hal=='2') $awal=10;
else if ($hal=='3') $awal=10;
else if ($hal=='4') $awal=10;
else if ($hal=='5') $awal=10;
else { $awal=0; $hal=1;} 
$sql2 = mysql_query("SELECT * FROM t_memberlihat where userid='".mysql_real_escape_string($userid)."' order by idlihat");
$n=mysql_num_rows($sql2);
$jml = intval($n/10);
if (($n % 10)>0) $jml=$jml+1; 

if ($jml >= 2) {
for($i=1;$i<=$jml;$i++) {
	if ($hal==$i) $pag .="<a class='sel'>$i</a>";
	else $pag .="<a href='user.php?id=logprofil&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
}
}
$depan .="<h3>Member lain yang melihat profil anda </h3>";
$depan .= "<div id='pag' align=right >$pag</div>";
$depan .="<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
$depan .="<form action='user.php' method=\"post\">";
$depan .="<table width=100% border=0 height=100% id='tablebaru' ><tr><td width=30% class='td0'><b>Member</b></td>
<td class='td0' width=35%><b><center>Lihat profil anda terakhir</center></b></td><td class='td0' width=35%><b><center>Login Terakhir</center></b></td><td class='td0' width=5%><b><center>Hapus</center></b></td></tr>";
//khusus untuk member teman saja seleksi belum dilakukan
$sql = mysql_query("SELECT * FROM t_memberlihat where userid='".mysql_real_escape_string($userid)."' order by tanggal desc limit $awal,$byk");
while($row=mysql_fetch_array($sql))
{
	$nama = member_nama($row[userlihat]);
	$tgllogin = member_login($row[userlihat]);
	$tgllogin = ambilselisih(strtotime($tgllogin), time());
	$selisih = ambilselisih(strtotime($row[tanggal]), time());
	$depan .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td ><a href='user.php?id=lih_profil&kode=".hex($row[userlihat],$noacak)."' title='Lihat profil'>$nama</a></td>
<td><center>".$tgllogin." </center></td><td><center>".$selisih." </center></td><td><center><input type='checkbox' name='kode[$row[idlihat]]' value='on'></center></td></tr>";
} 

$depan .="</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
<input type=\"hidden\" name=\"id\" value=\"loghapus\"><input type=\"submit\" value=\"Hapus\" id=button2 ></form>";
$depan .= "<div id='pag' align=right >$pag</div>";

//batas bawah tengah
$depan .="</div>";
return $depan;
}
// hapus log profilnya....
function loghapus() {
 $kode=$_POST['kode'];
	if (!empty($kode)) {
	  	while (list($key,$value)=each($kode)) {
			$sql="delete from t_memberlihat where idlihat='". mysql_real_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan log member gagal");
		}
	}
 } 
// setting template custom ................................. 
function customprofil() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$depan .="<div id='depan-tengah'>";

// status member pribadi terakhir
//script jquery untuk menerima status anda....
$depan .= statusanda($userid);
$depan .="<hr style='border: thin solid #6A849D;'>";
$depan .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
$depan .="<h3>Custom template</h3>Perubahan Template web ini hanya pada sisi background saja. Anda bisa ganti dan upload gambar background tersebut dengan format berupa CSS.<br><b>Standar CSS yang diberikan ke semua member :</b><br>";
$depan .='<pre style="font-size:12px;">body { /* background gambar */
	font-family: "Arial", serif;  ------> 1
	font-size: 76%;               ------> 2
	margin-top: 0px;              ------> 3
	color:#666666;                ------> 4
	background: #fff url(back.jpg) repeat-x; --> 5
}
#konten {   /* lebar layout web tengah */
	width:900px;	              ------> 6					
	margin-left: auto;            ------> 7
	margin-right: auto;           ------> 8
	background-color:#FFFFFF;	  ------> 9	
}

keterangan :
1. jenis huruf bisa diganti
2. ukuran huruf bisa diganti misal : 12px
3. jarak atas layout, usahakan jangan diganti
4. warna huruf bisa diganti
5. background warna #FFF bisa diganti warna lain
   url(back.jpg) adalah alamat background gambar bisa diganti
   repeat-x adalah background diulang secara horizontal bisa dihapus
6. lebar layout dalam usahakan tetap
7. jarak kiri layout, diusakan tetap supaya posisi di tengah
8. jarak kanan layout
9. background hanya warna #FFFFFF (putih) 
   background pada box bagian dalam layout. bisa ditambah background 
   gambar seperti yg diatas	</pre>';
$isi ='body { /* background gambar */
	font-family: "Arial", serif;
	font-size: 76%;
	margin-top: 0px;
	color:#666666;
	background: #fff url(back.jpg) repeat-x;
}
#konten {   /* lebar layout web tengah */
	width:900px;				
	margin-left: auto;
	margin-right: auto;
	background-color:#FFFFFF;
}';
$st='tambah';
	$sql="select bgbody from t_member_custom where userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal status member");
	if($row=mysql_fetch_array($query)) {
		if ($row[bgbody]<>'') {  
			$isi =$row[bgbody];
		}
		$st='edit';
	}
	$depan .="<form action='user.php' method='post' id='form' enctype='multipart/form-data' >
		<textarea cols=55 rows=13 name='pesan' id='pesan' >$isi</textarea><br>
		<input type='hidden' name='jenis' id=jenis value='$st' >
		<input type='hidden' name='id' id=id value='simpancustom' >
		File Backgound Body : <input type=file name=nfile id='nfile' > <br>Format file JPG (maks 200 kbyte)<br>
		File yang di-upload akan diberi nama <b>\"back$userid.jpg\"</b>. <br>
		Apabila ingin mengganti background gambar, anda harus meng-upload file gambarnya. <br>
		Serta ganti sintak pada nomor 5 : <b>(back.jpg)</b> menjadi <b>(profil/back$userid.jpg)</b><br>
		------------------------------------------------------------------------------------------------------------------<br>
		File Background #Konten : <input type=file name=kfile id='kfile' ><br>Format file JPG (maks 200 kbyte)<br>
		File yang di-upload akan diberi nama <b>\"konten$userid.jpg\"</b>. <br>
		Apabila ingin mengganti background gambar #konten, anda harus meng-upload file gambarnya.
		Serta ganti sintak pada nomor 9 : <br>
		<b>background-color:#FFFFFF;</b> menjadi <b>background: url(profil/konten$userid.jpg);</b><br><br>
		<input type=submit name=tempsimpan value='Simpan' class='tempsimpan' id='button2' ></form>";
	$depan .="<br>Catatan :<br>Anda dapat menambahkan gambar atau CSS yang lain, tapi jangan menambahkan sintak <b>javascript</b> yang dapat merusak tampilan template anda.<br>Apabila terjadi kerusakan template anda kosongkan atau hapus sintak pada box diatas, maka
	setting template akan kembali ke standar CSS (default) <br><br>";
//batas bawah tengah
$depan .="</div>";
return $depan;
}


function simpancustom() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$depan .="<div id='depan-tengah'>";
$depan .= statusanda($userid);
$depan .="<hr style='border: thin solid #6A849D;'>";
$depan .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
$depan .="<h3>Custom template</h3><br>";
	$jenis =$_POST['jenis'];
	$pesan = $_POST['pesan'];
	$nfile = $_FILES['nfile'];
	$kfile = $_FILES['kfile'];
	
	  if ($jenis=='edit') $sql ="update t_member_custom set bgbody='$pesan' where userid='".mysql_real_escape_string($userid)."'";
	  else $sql ="insert into t_member_custom (bgbody,userid) values ('$pesan','".mysql_real_escape_string($userid)."')";
	  $query=mysql_query($sql);
	  $depan .="Perubahan custom template berhasil dilakukan";
	  // file background body
	if (!empty($nfile['name'])) {
 		$ukuran=$nfile['size'];
		$a=$nfile['name'];
		$m=strlen($a);
		$type=strtolower(substr($a,$m-3,3));
	
   		if ($ukuran >= 204800) { // cek ukuran file
			$depan .= "File background body terlalu besar, maksimal 200 Kbyte "; }
		else if ($type <>'jpg') {
			$depan .="Format file background body salah, seharusnya format gambar jpg "; }
		else {
			$target_file ="profil/back$userid.jpg";
		 if(@move_uploaded_file($nfile['tmp_name'], $target_file)) {
		 	$depan .="<br>Upload file background body berhasil dilakukan ";
		 }
		}
	}
	// file background konten
	if (!empty($kfile['name'])) {
 		$ukuran=$kfile['size'];
		$a=$kfile['name'];
		$m=strlen($a);
		$type=strtolower(substr($a,$m-3,3));
	
   		if ($ukuran >= 204800) { // cek ukuran file
			$depan .= "File background #konten terlalu besar, maksimal 200 Kbyte "; }
		else if ($type <>'jpg') {
			$depan .="Format file background #konten salah, seharusnya format gambar jpg "; }
		else {
			$target_file ="profil/konten$userid.jpg";
		 if(@move_uploaded_file($kfile['tmp_name'], $target_file)) {
		 	$depan .="<br>Upload file background #konten berhasil dilakukan ";
		 }
		}
	}
$depan .="</div>";
return $depan;
}
///////////////////////////////////////////////// member lainnnnn
// melihat data profil orang lainnnnnn.....harus diproteksi apabila member ini tidak berstatus teman
function lih_profil() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
//$userhex = hex($userid,$noacak);
$kode = unhex($_GET['kode'],$noacak);
$hal = $_GET['hal'];
$depan .="<div id='depan-tengah'>";
$depan .= statusanda($kode);
$depan .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$sql2="select * from t_member_contact where id_master='".mysql_real_escape_string($kode)."' and id_con='". mysql_real_escape_string($userid)."' and status='1' ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal apakah saya teman kamu ?");
if(mysql_num_rows($query2) > 0) {
// status member pribadi terakhir
//script jquery untuk menerima status anda....
$depan .= '<script type="text/javascript">
// awal pengiriman muti submit komentar
$(document).ready(function()
{$(".status_button").click(function() {
	var element = $(this);
    var test = $("#content").val();
	var userid = $("#userid3").val();
	var pengirim = $("#pengirim").val();
    var dataString = \'userid=\'+ userid +\'&pengirim=\'+ pengirim +\'&stcontent=\'+ test;
	if(test==\'\') {
		alert("Pesan yang Anda isikan masih kosong");
	}
	else if(test==\'Tuliskan pesan Anda !!!\') {
		alert("Tuliskan pesan Anda ");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lih_profil&kode='.hex($kode,$noacak).'";}});
	}
	return false;
});

});
//akhir multi submit komentar
</script>';

$depan .="<div id='box-status'><form  method='post' name='form' action=''>
<table>
<tr><td>
<textarea name='content' id='' style='width:470px;height:20px;' onfocus='clearText(this)' onblur='clearText(this)' rel='tooltip' content='Silahkan masukan pesan Anda </br>untuk diketahui temen lain.' maxlength='255' >Tuliskan pesan Anda !!!</textarea>
</td></tr>
<tr><td style=text-align:right>
<input type=\"submit\"  value=\"Bagikan\"  name=\"submit\" class=\"status_button\" id='button2' /><input type='hidden' name='userid3' id='userid3' value='".hex("statusanda,".$kode,$noacak)."' ><input type='hidden' name='pengirim' id='pengirim' value='".hex($userid,$noacak)."' >
</td></tr></table>
</form></div>";

$depan .="<hr style='border: thin solid #6A849D;'>";
$depan .="<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofilmember&kode=".hex($kode,$noacak)."'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;
<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifotomember&kode=".hex($kode,$noacak)."'\" value=' Koleksi Foto ' > &nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opiniteman&kode=".hex($kode,$noacak)."'\" value=' Opini Pribadi ' > <div style='float:right;' ><a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($kode,$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a></div>";

$depan .='<script type="text/javascript">
//fungsi untuk show dan hide panel komentar
$(document).ready(function()
{
$(".comment_button").click(function(){

var element = $(this);
var I = element.attr("id");

$("#slidepanel"+I).slideToggle(300);
$(this).toggleClass("active"); 

return false;});});

$(document).ready(function()
{
$(".comment2").click(function(){

var element = $(this);
var I = element.attr("id");

$("#komenpanel"+I).slideToggle(300);
$(this).toggleClass("active"); 

return false;});});
</script>
<script type="text/javascript">

// awal pengiriman muti submit komentar
$(document).ready(function()
{
$(".comment_submit").click(function(){


var element = $(this);
var Id = element.attr("id");

var test = $("#textboxcontent"+Id).val();
var userid = $("#userid2").val();
var dataString = \'userid=\'+ userid +\'&textcontent=\'+ test + \'&idstatus=\' + Id;

if(test==\'\')
{
alert("Tuliskan komentarnya");
}
else if(test==\'Tuliskan komentar Anda....\')
{
alert("Tuliskan komentarnya");
}
else
{
$("#flash"+Id).show();
$("#flash"+Id).fadeIn(400).html(\'<img src="css/ajax-loader.gif" align="absmiddle"> loading.....\');

$("#textboxcontent"+Id).val(\'Tuliskan komentar Anda....\');

$.ajax({
type: "POST",
url: "kontenstatus.php",
data: dataString,
cache: false,
success: function(html){
$("#loadplace"+Id).append(html);
$("#flash"+Id).hide();

}
});

}

return false;});});
//akhir multi submit komentar
</script>';

//seleksi halaman dibatasi hanya 30 status yang bisa dilihat
$byk=10;
if ($hal=='1')  $awal=0; 
else if ($hal=='2') $awal=10;
else if ($hal=='3') $awal=10;
else if ($hal=='4') $awal=10;
else if ($hal=='5') $awal=10;
else { $awal=0; $hal=1;} 
$sql2 = mysql_query("SELECT * FROM t_memberstatus where userid='".mysql_real_escape_string($kode)."' order by idstatus");
$n=mysql_num_rows($sql2);
$jml = intval($n/10);
if (($n % 10)>0) $jml=$jml+1; 

if ($jml >= 2) {
for($i=1;$i<=$jml;$i++) {
	if ($hal==$i) $pag .="<a class='sel'>$i</a>";
	else $pag .="<a href='user.php?id=lih_profil&kode=".hex($kode,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
}
}

$depan .= "<div id='pag' align=right >$pag</div>";
$depan .="<hr style='float:none;border:#dedede dashed thin;'>";
$depan .='<ul  id="update" class="timeline">';
//khusus untuk member teman saja seleksi belum dilakukan
$sql = mysql_query("SELECT * FROM t_memberstatus where userid='".mysql_real_escape_string($kode)."' order by idstatus desc limit $awal,$byk");
while($row=mysql_fetch_array($sql))
{
	$msg = statusmember($row[pengirim],$row[jenis],$row[pesan]);
	
	$msgid=$row['idstatus'];
	$nama = member_nama($row[pengirim]);

	$gb=fotouser($row[pengirim]);

	$selisih = ambilselisih(strtotime($row[tanggal]), time());
	$sql2 = mysql_query("SELECT idstatus FROM t_memberstatus_kom where idstatus='$msgid' order by idstatuskom ");
	$tot = mysql_num_rows($sql2);
	if ($tot <=3 ) { $lihat =""; $batas = 0; }
	else { $lihat = '<a href="#" class="comment2" id="'.$msgid.'" style="color:#6A849D;">Lihat komentar lain ';
		$batas=$tot-3;
		if ($tot <= 13) $lihat .= "(".($tot-3).")</a>";
		else { $lihat .= "</a>"; $batas=10;}	
	}

	$depan .='<li>'.$gb.'<b>'.$nama.'</b> :: '.$selisih.'<br>'.$msg.'<br>
			<a href="#" class="comment_button" id="'.$msgid.'" style="color:#6A849D;" >Tambah Komentar</a>
			'.$lihat.'
		  </li>';
		  $depan .="<div class='komen' id=\"komenpanel".$msgid."\">";
			$n=0;
		  	$byk=13;
			if ($tot >= $byk )  $awal = $tot-$byk;  
			else  $awal =0;
	$sql2 = mysql_query("SELECT * FROM t_memberstatus_kom where idstatus='$msgid' order by idstatuskom limit $awal,$byk");	  
		  while($r=mysql_fetch_array($sql2)) {
			if ($n == $batas )   $depan .="</div><div class='komen2' >"; 
				$nama2 = member_nama($r[userid]);
				$pesan2 = $r[pesan];
                                    //tambahan agung
                $x_pesan =$r[pesan];
                $data_pesan = explode(" ",$x_pesan);
                $x_pesan="";
                for ($i=0; $i<count($data_pesan); $i++){
                
                        if (strlen($data_pesan[$i]) >= 20) {
                            $data_pesan[$i] = wordwrap($data_pesan[$i], 30, " ", TRUE);
                        }
                        $x_pesan .= $data_pesan[$i]." ";
                }
                	$pesan2=strip_tags("$x_pesan");
                //tutup
				$gb2=fotouser($r[userid]);
				$selisih2 = ambilselisih(strtotime($r[tanggal]), time());
				$depan .= "<div id='komen3' >$gb2<b>$nama2</b> :: $selisih2 <br>$pesan2 </div>";
			$n++;
		  }
		  $depan .="</div>
		  <div  id=\"loadplace".$msgid."\" ></div>";
$depan .= "<div id=\"flash".$msgid."\" class='flash_load'></div>
<div class='panel' id=\"slidepanel".$msgid."\">";
$depan .='<form action="" method="post" name="'.$msgid.'"><textarea style="width:330px;height:20px" id="textboxcontent'.$msgid.'" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea> &nbsp;&nbsp;<input type="submit" value="  Kirim  "  class="comment_submit" id="'.$msgid.'" /><input type=hidden name="userid2" value="'.hex("komstatus,".$userid,$noacak).'" id="userid2" >
</form>
</div>';

} 

$depan .="</ul>";
$depan .= "<div id='pag' align=right >$pag</div><br>";

}// batas apakah saya teman anda.............
else {
$depan .="<hr style='border: thin solid #6A849D;'>";
$depan .= tambahteman($kode);
}
//batas bawah tengah
$depan .="</div>";
return $depan;
}
// informasi profil temann-----------------------
function infoprofilmember() {
include "koneksi.php";
$userid = unhex($_GET['kode'],$noacak);
$saya = $_SESSION['User']['userid'];
$info .="<div id='depan-tengah'>";

$info .= statusanda($userid);

$sql2="select * from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and id_con='". mysql_real_escape_string($saya)."' and status='1' ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal apakah saya teman kamu ?");
if(mysql_num_rows($query2) > 0) {
// seleksi apakah user ini teman anda atau bukan
$info .="<hr style='border: thin solid #6A849D;'>";
$info .="<input type='button' id='button2' onclick=\"location.href='user.php?id=lih_profil&kode=".hex($userid,$noacak)."'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;
<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifotomember&kode=".hex($userid,$noacak)."'\" value=' Koleksi Foto ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opiniteman&kode=".hex($userid,$noacak)."'\" value=' Opini Pribadi ' >";
$info .="<hr style='float:none;border:#dedede dashed thin;'>";
	$sql="select * from t_member where userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal status member");
	if($row=mysql_fetch_array($query)) {
	  if ($row[stprofil]=='open'){  // khusus untuk lihat profil
		if ($row[kelamin]='m') $kel ='Laki-laki';
		else $kel='Perempuan';
	  if ($row[ket]=='Siswa') $nis ="<tr><td>NIS</td><td><b>$row[nis]</b></td></tr><tr><td>Kelas</td><td><b>$row[kelas]</b></td></tr>";
	  else if ($row[ket]=='Guru' && $row[ket]=='Admin') $nis ="<tr><td>NIP</td><td><b>$row[nis]</b></td></tr>";
	  else if ($row[ket]=='Alumni') $nis ="<tr><td>Angkatan Tahun</td><td><b>$row[kelas]</b></td></tr>";
	  else $nis ="";
	  if ($row[ket]=='Orang Tua') { 
	     $sql2="select nama,userid,nis,kelas from t_member where nis='".mysql_real_escape_string($row[nis])."' and ket='Siswa' ";
		 if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal status member");
		 if($r=mysql_fetch_array($query2)) {
		  	$ortu ="<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>Informasi Keluarga</b></td></tr>
			<tr><td>Orang Tua dari</td><td><b>$r[nama]</b></td></tr>
			<tr><td>NIS</td><td><b>$r[nis]</b></td></tr><tr><td>Kelas</td><td><b>$r[kelas]</b></td></tr>";
	  	  }
	  }
	$info .="<table border=0 ><tr><td colspan=2><b>Informasi Umum</b></td></tr>
	<tr><td width='100' >Nama</td><td><b>$row[nama]</b></td></tr>$nis<tr><td>Jenis Kelamin</td><td><b>$kel</b></td></tr>
	<tr><td>Tanggal Lahir</td><td><b>$row[tgllahir]</b></td></tr><tr><td>Pekerjaan</td><td><b>$row[kerja]</b></td></tr>
	<tr><td>Alamat</td><td><b>$row[alamat]</b></td></tr><tr><td>Negara</td><td><b>$row[negara]</b></td></tr>
	<tr><td>Telp</td><td><b>$row[telp]</b></td></tr><tr><td>Sekolah</td><td><b>$row[sekolah]</b></td></tr>
	<tr><td>Email</td><td><b>$row[email]</b></td></tr><tr><td>Homepage/Blog</td><td><b><a href='http://$row[homepage]' target='_blank' >$row[homepage]</a></b></td></tr>
	<tr><td>Tentang Saya</td><td><b>$row[profil]</b></td></tr>$ortu<tr><td colspan=2>&nbsp;</td></tr>
	<tr><td colspan=2><b>Informasi Khusus</b></td></tr><tr><td>Status Login</td><td><b>$row[ket]</b></td></tr>
	<tr><td>Point</td><td><b>$row[point]</b></td></tr>
	<tr><td>Total Login</td><td><b>$row[totlogin]</b></td></tr><tr><td>Login Terakhir</td><td><b>".date("d M Y H:i",strtotime($row[tgl_login]))."</b></td></tr><tr><td>IP Kom. Terakhir</td><td><b>$row[ip]</b></td></tr>
	</table><br>";
	// penambahan history member.....................
		$sql2="select * from t_memberlihat where userid='".mysql_real_escape_string($userid)."' and userlihat='".mysql_real_escape_string($saya)."' and day(tanggal)='".date('d')."' and month(tanggal)='".date('m')."' and year(tanggal)='".date('Y')."'";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal lihat history");
		if($rows = mysql_fetch_array($query2)) {
			$query = "update t_memberlihat set tanggal=NOW() where userid='".mysql_real_escape_string($userid)."' and userlihat='".mysql_real_escape_string($saya)."'";
		}
		else {
			$query = "insert into t_memberlihat (userid,userlihat,tanggal) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string($saya)."',NOW())";
		}
    	$res = mysql_query($query);
		/// batas akhir....
	}
	else {
		$info .="<br><center><img src='../images/lock.png' width='150' height='150' ><br><br>Maaf profil ini tidak dapat diakses permintaan dari pengguna.</center>";
	}
	}
}
else {
	$info .="<hr style='border: thin solid #6A849D;'>";
	$info .= tambahteman($userid);
}
//batas akhir bag tengah
$info .="</div>";
return $info;
}


?>