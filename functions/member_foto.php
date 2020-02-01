<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi - fungsi yang berhubungan dengan album foto
// koleksifoto, koleksifotodetail

// fotohapus, upload foto, buat album
// hapus komentar foto

// koleksi foto pribadi
function koleksifoto() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$koleksi .="<div id='depan-tengah'>";
$koleksi .= statusanda($userid);
$koleksi .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })
  
$(document).ready(function()
{
$('.tambahtom').click(function(){

var element = $(this);

$('#tambahalbum').slideToggle(300);
$(this).toggleClass('active'); 

return false;});});

</script> ";
$koleksi .= '<script type="text/javascript">
// awal pengiriman tambah album
$(function() {$(".tamalbum").click(function() {
	var element = $(this);
    var test = $("#album").val();
	var userid = $("#userid").val();
    var dataString = \'userid=\'+ userid +\'&album=\'+ test;
	if(test==\'\') {
		alert("Nama Album masih kosong");
	}
	else if(test==\'Tuliskan Nama Albumnya...\') {
		alert("Nama Album masih kosong");
	}	
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=koleksifoto";}});
	}
	return false;
});

});
function albumhapus(kode,kdalbum) {
	if(confirm("Apakah Anda yakin akan menghapusnya ?")) {
    var dataString = \'userid=\'+ kode +\'&kdalbum=\' + kdalbum;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=koleksifoto";}});
	}
}
</script>';
$koleksi .="<hr style='border: thin solid #6A849D;'>";
$koleksi .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' > &nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
$koleksi .="<h3>Koleksi Foto</h3><a href='#' class='tambahtom' id=button2 >Tambah Album</a>&nbsp;&nbsp;&nbsp;<a href=\"boxframe.php?id=uploadfoto&userid=".hex($userid,$noacak)."&kdalbum=".hex($kdalbum,$noacak)."\" rel=\"facebox\" id='button2' >Upload Foto</a>&nbsp;&nbsp;&nbsp;<a href=\"boxframe.php?id=fotoprofil&userid=".hex($userid,$noacak)."\" rel=\"facebox\" id='button2' >Ganti Foto Profil</a> &nbsp;&nbsp;<a href=\"user.php?id=fotofacebook&userid=".hex($userid,$noacak)."\" id='button2' >Foto Profil Facebook</a>";
$koleksi .="<div id='tambahalbum' ><form method=post action='' ><input type='text' name='album' value='Tuliskan Nama Albumnya...' id='album' size='40' maxlength=255 onfocus='clearText2(this)' onblur='clearText2(this)' rel='tooltip' content='Silahkan masukan nama album yang akan anda buat.' ><input type=hidden id='userid' value='".hex("tamalbum,".$userid,$noacak)."' > <input type='submit' value='Tambah' class='tamalbum' id='button2' ></form></div>";
//seleksi halaman dibatasi hanya 30 status yang bisa dilihat
$hal = $_GET['hal'];
$byk=6;
if ($hal=='') $hal=1;
$awal = ($hal-1)*$byk;

$sql2 = mysql_query("SELECT * FROM t_memberfoto_album where userid='".mysql_real_escape_string($userid)."' ");
$n=mysql_num_rows($sql2);
$jml = intval($n/$byk);
if (($n % $byk)>0) $jml=$jml+1; 

if ($jml >= 2) {
for($i=1;$i<=$jml;$i++) {
	if ($hal==$i) $pag .="<a class='sel'>$i</a>";
	else $pag .="<a href='user.php?id=koleksifoto&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
}
}
$koleksi .= "<div id='pag' align=right >$pag</div>";
$koleksi .="<div class='foto' ><ul>";
$sql = mysql_query("SELECT * FROM t_memberfoto_album where userid='".mysql_real_escape_string($userid)."' order by idalbum desc limit $awal,$byk");
while($row=mysql_fetch_array($sql))
{
	$sql2="select count(*) as jum from t_memberfoto where idalbum='".mysql_real_escape_string($row[idalbum])."'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal jumlah foto");
	$r = mysql_fetch_array($query2);
	$jmlfoto = $r[jum];
	$koleksi .="<li><a href='?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdalbum=$row[idalbum]' title='Klik untuk lihat semua foto' >$row[keterangan] ( $jmlfoto foto ) </a> &nbsp;&nbsp;<a href=\"boxframe.php?id=editalbum&userid=".hex($userid,$noacak)."&kdalbum=".hex($row[idalbum],$noacak)."\" rel=\"facebox\" id='editlink' title='Klik untuk mengubah album ini'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;<a href='#' id='hapuslink' onclick=\"albumhapus('".hex("hapalbum,",$noacak)."','".hex($row[idalbum],$noacak)."')\" title='Klik untuk menghapus album ini'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>";
	$sql2="select * from t_memberfoto where idalbum='".mysql_real_escape_string($row[idalbum])."' order by idfoto desc limit 0,4 ";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal jumlah foto");
	while($r=mysql_fetch_array($query2)) {
		$path ="foto/foto$r[idfoto].jpg";
		if (file_exists(''.$path.'')) {
		$selisih = ambilselisih(strtotime($r[tanggal]), time());
		$koleksi .="<a href='?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=$r[idfoto]' rel=\"tooltip\" content=\"Foto $r[judul] <br> Upload : ".$selisih." \"  ><img src='thumb.php?img=$path' id=gambar /></a>";
		}
		else $koleksi .="<img src='foto/kosong.jpg' height='70' width='100' id='gambar' > ";
	}
	$koleksi .="</li>";
}
$koleksi .="</ul>
</div>";
$koleksi .= "<div id='pag' align=right >$pag</div><br>";
$koleksi .="</div>";

return $koleksi;
}

// koleksi foto detail kdfoto=id foto dan kdalbum = id album
function koleksifotodetail() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kdfoto = $_GET['kdfoto'];
$kdalbum = $_GET['kdalbum'];
$koleksi .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })
  </script>";
$koleksi .= '<script type="text/javascript">
// awal pengiriman komentar
$(function() {$(".komenfoto").click(function() {
	var element = $(this);
    var kdfoto = $("#kdfoto").val();
	var userid = $("#userid2").val();
	var komentar = $("#komentar").val();
    var dataString = \'userid=\'+ userid +\'&kdfoto=\'+ kdfoto +\'&pesan=\'+ komentar ;
	if(komentar==\'\') {
		alert("Komentar masih kosong");
	}
	else if(komentar==\'Tuliskan komentar Anda....\') {
		alert("Komentar masih kosong ");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=koleksifotodetail&kode='.hex($userid,$noacak).'&kdfoto="+kdfoto;}});
	}
	return false;
});

});
function fotohapus(kode,userid,kdfoto,kdalbum) {
	if(confirm("Apakah Anda yakin akan menghapusnya ?")) {
    var dataString = \'userid=\'+ kode +\'&kdfoto=\'+ kdfoto + \'&kdalbum=\' + kdalbum;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=koleksifotodetail&kode="+userid+"&kdalbum="+kdalbum;}});
	}
}
function fotokomhapus(kode,userid,kdkom,kdfoto) {
	if(confirm("Apakah Anda yakin akan menghapusnya ?")) {
    var dataString = \'userid=\'+ kode +\'&kdfoto=\'+ kdfoto + \'&kdkom=\' + kdkom;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=koleksifotodetail&kode="+userid+"&kdfoto="+kdfoto;}});
	}
}

</script>';
$koleksi .="<div id='depan-tengahkanan'>";
$koleksi .= statusanda($userid);
$koleksi .="<hr style='border: thin solid #6A849D;'>";
$koleksi .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifoto'\" value=' Koleksi Foto ' > &nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
$koleksi .="<h3>Koleksi Foto</h3>";
if ($kdalbum=='') {
$sql="select * from t_memberfoto,t_memberfoto_album where t_memberfoto.idalbum=t_memberfoto_album.idalbum and t_memberfoto.idfoto='".mysql_real_escape_string($kdfoto)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal album");
	$row = mysql_fetch_array($query);
	$koleksi .="<img src='../images/foto.png' align=left > &nbsp;$row[keterangan] <br>";
	$kdalbum=$row[idalbum];
}
else {
	$sql="select * from t_memberfoto,t_memberfoto_album where t_memberfoto.idalbum=t_memberfoto_album.idalbum and t_memberfoto.idalbum='".mysql_real_escape_string($kdalbum)."' order by t_memberfoto.idfoto desc";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal album");
	$row = mysql_fetch_array($query);
	$koleksi .="<img src='../images/foto.png' align=left > &nbsp;$row[keterangan] <br>";
	$kdfoto = $row[idfoto];
}
if (!empty($kdfoto))  {
	$sql="select * from t_memberfoto where idfoto='".mysql_real_escape_string($kdfoto)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal foto");
	$row = mysql_fetch_array($query);
	$selisih = ambilselisih(strtotime($row[tanggal]), time());
	if ($row[stopen]=='1') $private=' - <font style="color:#FF0000" >( Private )</font>';
	else $private='';
	$file = "foto/foto$row[idfoto].jpg";
	$size = getimagesize($file);
	$lebar = 630;
	$tinggi = round(($size[1]*$lebar)/$size[0],0);
	$koleksi .="<div id='sthapus' ><a href=\"user.php?id=imgprofil&kdfoto=".hex($row[idfoto],$noacak)."\"  id='imgprofil'  title='Klik untuk mengganti foto profil'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;<a href=\"boxframe.php?id=editfoto&userid=".hex($userid,$noacak)."&kdfoto=".hex($row[idfoto],$noacak)."\" rel=\"facebox\" id='editlink'  title='Klik untuk mengubah foto ini'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><a href='#' onclick=\"fotohapus('".hex("hapfoto,",$noacak)."','".hex($userid,$noacak)."','".hex($row[idfoto],$noacak)."','".$row[idalbum]."')\" title='Klik untuk menghapus foto ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
	<center><a href=\"$file\" rel=\"facebox\"><img src='$file' width='$lebar' height='$tinggi' id='gambar' ></a><br>$row[judul] - ".$selisih." $private</center><center>
	<div id='fotodetail' >";
	// tampilkan dafta list foto semua di album ini
	$byk=5;
	$hal=$_GET['hal'];
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$sql2 = mysql_query("SELECT * from t_memberfoto where idalbum='".mysql_real_escape_string($kdalbum)."' ");
	$n=mysql_num_rows($sql2);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=".$kdfoto."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$koleksi .= "<div id='pag' >$pag</div>";
	$sql2="select * from t_memberfoto where idalbum='".mysql_real_escape_string($kdalbum)."' order by idfoto desc limit $awal,$byk";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal foto di album ini");
	while($r = mysql_fetch_array($query2)) {
		$path ="foto/foto$r[idfoto].jpg";
		$selisih = ambilselisih(strtotime($r[tanggal]), time());
		if (file_exists(''.$path.'')) {
		$koleksi .="<a href='?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=$r[idfoto]' rel=\"tooltip\" content=\"Foto $r[judul] <br> Upload : ".$selisih." \"  ><img src='thumb.php?img=$path' id=gambar /></a>";
		}
		else $koleksi .="<a href='#' onclick=\"fotohapus('".hex($r[idfoto],$noacak)."','".$kdalbum."')\" rel=\"tooltip\" content=\"Klik disini untuk menghapus data dari album ini  \"><img src='foto/kosong.jpg' height='70' width='100' id='gambar' ></a> ";
	}
	$koleksi .="</div></center>";
$koleksi .="<br><a href=\"boxframe.php?id=uploadfoto&userid=".hex($userid,$noacak)."&kdalbum=".hex($kdalbum,$noacak)."\" rel=\"facebox\" id='button2' >Upload Foto</a>&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"window.location.reload( true );\" id='button2' >Refresh</a><br>";
$koleksi .="<hr style='border: thin solid #6A849D;'><h3>Komentar :</h3>";
	$sql="select * from t_memberfoto_kom where idfoto='".mysql_real_escape_string($kdfoto)."' order by idfotokom desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal komentar foto");
	while($row = mysql_fetch_array($query)) {
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$nama = member_nama($row[userid]);
		$pesan = $row[komentar];
		$gb=fotouser($row[userid]);
		$koleksi .="<div id='komen3'>$gb <b>$nama</b> :: $selisih <div id='sthapus' ><a href='#' title='Klik untuk menghapus komentar' onclick=\"fotokomhapus('".hex("hapkomfoto,",$noacak)."','".hex($userid,$noacak)."','".hex($row[idfotokom],$noacak)."','".$kdfoto."')\">x</a></div><br>$pesan </div>";

	}
	$koleksi .='<br><form action="" method="post" name="komentar"><textarea style="width:380px;height:20px;" id="komentar" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea> &nbsp;&nbsp;<input type="submit" value="  Kirim  " name="komenfoto" class="komenfoto" id="button2" /><input type=hidden name="kdfoto" value="'.$kdfoto.'" id="kdfoto" ><input type=hidden name="userid2" value="'.hex("komenfoto,".$userid,$noacak).'" id="userid2" >
</form><br>';
$koleksi .="<hr style='border: #dedede dashed 1px;'><br>";
}
else { //klo kondisi album kosong
	$koleksi .="<br><a href=\"boxframe.php?id=uploadfoto&userid=".hex($userid,$noacak)."&kdalbum=".hex($kdalbum,$noacak)."\" rel=\"facebox\" id='button2' >Upload Foto</a>&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"window.location.reload( true );\" id='button2' >Refresh</a><br>";
	$koleksi .="<hr style='border: #dedede dashed 1px;'><br>";
}
$koleksi .="</div>";

return $koleksi;
}

// crop foto untuk menjadi profil
function imgprofil() {
$userid = $_SESSION['User']['userid'];
include "koneksi.php";
include "fungsi_crop.php";
$profil .='<script type="text/javascript" src="js/jquery.imgareaselect.min.js"></script>';
$profil .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$profil .="<script type=\"text/javascript\">
$(function () {
    $('.imgprofil').imgAreaSelect({ 
        onSelectEnd: function (img, selection) {
            $('input[name=x1]').val(selection.x1);
            $('input[name=y1]').val(selection.y1);
            $('input[name=x2]').val(selection.x2);
            $('input[name=y2]').val(selection.y2);
        }
    });
});
</script>";
$profil .="<div id='depan-tengahkanan'>";
$profil .= statusanda($userid);
$profil .="<hr style='border: thin solid #6A849D;'>";
$profil .="<a href=\"user.php?id=koleksifoto\" id='button2' >Koleksi Foto</a>&nbsp;&nbsp;&nbsp;<a href=\"boxframe.php?id=fotoprofil&userid=".hex($userid,$noacak)."\" rel=\"facebox\" id='button2' >Upload Foto Profil</a>";
$profil .="<h3>Perubahan foto profil</h3>
Silahkan seleksi gambar yang akan di Crop <br>";

  if ($_POST["crop"]=="save") {
  	
  	$kdfoto = unhex($_POST['kdfoto'],$noacak);
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	if (!empty($x1)) {
	$asli_lebar= $_POST['lebar'];//650;
	$asli_tinggi = $_POST['tinggi'];//round(($size[1]*$asli_lebar)/$size[0],0);
	$file_master="foto/foto".$kdfoto.".jpg";
	cropImage($asli_lebar, $asli_tinggi, "$file_master", 'jpg', "foto/temp".$kdfoto.".jpg");
	$file_hasil ="profil/gb".$userid.".jpg";
	//$cropped = resizeThumbnailImage($file_hasil, $file_master,$w,$h,$x1,$y1,$scale);
	$lebar = $x2-$x1;
	$tinggi = $y2-$y1;
	//$size = getimagesize($file_master);
	
	potongimageprofil("foto/temp".$kdfoto.".jpg","jpg",$file_hasil,$lebar,$tinggi,$x1,$y1);
	$profil .="<br><br>Pemotongan berhasil dilakukan. <br>Silahkan klik menu profil anda sekarang atau refresh browser anda.<br>";
  	}
	else $profil .="Tidak ada seleksi pada foto tersebut";
  }
  else {
	$kdfoto = unhex($_GET['kdfoto'],$noacak);
	$nfile ="foto/foto$kdfoto.jpg";
	if (file_exists(''.$nfile.'')) {
		$size = getimagesize($nfile);
		$lebar=650;
		$tinggi = round(($size[1]*$lebar)/$size[0],0);
		$mfile ="<img class='imgprofil' src='$nfile'  width='$lebar' height='$tinggi' alt='koleksi foto'  title='seleksi bagian yang akan dipotong untuk dijadikan profil' id=gambar >";
		
	}
	$profil .= $mfile.'<br><br>Seleksi bagian yang akan dipotong untuk dijadikan profil<br>
	<form action="user.php" method="post">
   <input id="x1" type="hidden" name="x1" value="" />
   <input id="y1" type="hidden" name="y1" value="" />
   <input id="x2" type="hidden" name="x2" value="" /><input type="hidden" name="lebar" value="'.$lebar.'"  />
   <input type="hidden" name="tinggi" value="'.$tinggi.'"  />
   <input id="y2" type="hidden" name="y2" value="" /><input type="hidden" name="kdfoto" value="'.hex($kdfoto,$noacak).'"  />
   <input type="hidden" name="crop" value="save"  /><input type="hidden" name="id" value="imgprofil"  />
   <input type="submit" name="submit" value="Simpan" id=button2 />
  </form>';
  }
  $profil .="</div>";
return $profil;
}
// member lain---------------------------teman
// fungsi melihat koleksi foto member lain------------
function koleksifotomember() {
include "koneksi.php";
$userid = unhex($_GET['kode'],$noacak);
$saya = $_SESSION['User']['userid'];
$koleksi .="<div id='depan-tengah'>";
$sql2="select * from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and id_con='". mysql_real_escape_string($saya)."' and status='1' ";
if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal apakah saya teman kamu ?");

$koleksi .= statusanda($userid); //cetak status
$koleksi .="<hr style='border: thin solid #6A849D;'>";
if(mysql_num_rows($query2) > 0) {
// seleksi apakah user ini teman anda atau bukan
$koleksi .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";

$koleksi .="<input type='button' id='button2' onclick=\"location.href='user.php?id=lih_profil&kode=".hex($userid,$noacak)."'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofilmember&kode=".hex($userid,$noacak)."'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opiniteman&kode=".hex($userid,$noacak)."'\" value=' Opini Pribadi ' >";
$koleksi .="<h3>Koleksi Foto</h3>";
//seleksi halaman dibatasi hanya 30 status yang bisa dilihat
$hal = $_GET['hal'];
$byk=6;
if ($hal=='') $hal=1;
$awal = ($hal-1)*$byk;

$sql2 = mysql_query("SELECT * FROM t_memberfoto_album where userid='".mysql_real_escape_string($userid)."' ");
$n=mysql_num_rows($sql2);
$jml = intval($n/$byk);
if (($n % $byk)>0) $jml=$jml+1; 

if ($jml >= 2) {
for($i=1;$i<=$jml;$i++) {
	if ($hal==$i) $pag .="<a class='sel'>$i</a>";
	else $pag .="<a href='user.php?id=koleksifotomember&kode=".hex($userid,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
}
}
if ($n==0) $koleksi .="Koleksi foto belum ada.";

$koleksi .= "<div id='pag' align=right >$pag</div>";
$koleksi .="<div class='foto' ><ul>";
$sql = mysql_query("SELECT * FROM t_memberfoto_album where userid='".mysql_real_escape_string($userid)."' order by idalbum desc limit $awal,$byk");
while($row=mysql_fetch_array($sql))
{
	$sql2="select count(*) as jum from t_memberfoto where idalbum='".mysql_real_escape_string($row[idalbum])."' and stopen='0'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal jumlah foto");
	$r = mysql_fetch_array($query2);
	$jmlfoto = $r[jum];
	$koleksi .="<li><a href='?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdalbum=$row[idalbum]' title='Klik untuk lihat semua foto' >$row[keterangan] ( $jmlfoto foto ) </a> <br>";
	$sql2="select * from t_memberfoto where idalbum='".mysql_real_escape_string($row[idalbum])."' and stopen='0' order by idfoto desc limit 0,4 ";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal jumlah foto");
	while($r=mysql_fetch_array($query2)) {
		$path ="foto/foto$r[idfoto].jpg";
		if (file_exists(''.$path.'')) {
		$selisih = ambilselisih(strtotime($r[tanggal]), time());
		$koleksi .="<a href='?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=$r[idfoto]' rel=\"tooltip\" content=\"Foto $r[judul] <br> Upload : ".$selisih." \"  ><img src='thumb.php?img=$path' id=gambar /></a>";
		}
		else $koleksi .="<img src='foto/kosong.jpg' height='70' width='100' id='gambar' > ";
	}
	$koleksi .="</li>";
}
$koleksi .="</ul>
</div>";
$koleksi .= "<div id='pag' align=right >$pag</div><br>";

}
else {
	$koleksi .="<hr style='border: thin solid #6A849D;'>";
	$koleksi .=tambahteman($userid);
}
$koleksi .="</div>";
return $koleksi;
}

// detail koleksi foto member lain
// koleksi foto detail kdfoto=id foto dan kdalbum = id album
function koleksifotodetailmember() {
include "koneksi.php";
$userid = unhex($_GET['kode'],$noacak);
$saya = $_SESSION['User']['userid'];
$kdfoto = $_GET['kdfoto'];
$kdalbum = $_GET['kdalbum'];
$koleksi .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

  </script>";
$koleksi .= '<script type="text/javascript">
// awal pengiriman komentar
$(function() {$(".komenfoto").click(function() {
	var element = $(this);
    var kdfoto = $("#kdfoto").val();
	var userid = $("#userid").val();
	var komentar = $("#komentar").val();
    var dataString = \'userid=\'+ userid +\'&kdfoto=\'+ kdfoto +\'&pesan=\'+ komentar ;
	if(komentar==\'\') {
		alert("Komentar masih kosong ");
	}
	else if(komentar==\'Tuliskan komentar Anda....\') {
		alert("Komentar masih kosong ");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=koleksifotodetail&kode='.hex($userid,$noacak).'&kdfoto="+kdfoto;}});
	}
	return false;
});

});

</script>';
$koleksi .="<div id='depan-tengahkanan'>";
$koleksi .= statusanda($userid);
$koleksi .="<hr style='border: thin solid #6A849D;'>";
$koleksi .="<input type='button' id='button2' onclick=\"location.href='user.php?id=lih_profil&kode=".hex($userid,$noacak)."'\" value=' Profil ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofilmember&kode=".hex($userid,$noacak)."'\" value=' Info Pribadi ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=koleksifotomember&kode=".hex($userid,$noacak)."'\" value=' Koleksi Foto ' >";
$koleksi .="<h3>Koleksi Foto</h3>";
if ($kdalbum=='') {
$sql="select * from t_memberfoto,t_memberfoto_album where t_memberfoto.idalbum=t_memberfoto_album.idalbum and t_memberfoto.idfoto='".mysql_real_escape_string($kdfoto)."' and t_memberfoto.stopen='0'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal album");
	$row = mysql_fetch_array($query);
	$koleksi .="<img src='../images/foto.png' align=left > &nbsp;$row[keterangan] <br>";
	$kdalbum=$row[idalbum];
}
else {
	$sql="select * from t_memberfoto,t_memberfoto_album where t_memberfoto.idalbum=t_memberfoto_album.idalbum and t_memberfoto.idalbum='".mysql_real_escape_string($kdalbum)."' and t_memberfoto.stopen='0' order by t_memberfoto.idfoto desc";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal album");
	$row = mysql_fetch_array($query);
	$koleksi .="<img src='../images/foto.png' align=left > &nbsp;$row[keterangan] <br>";
	$kdfoto = $row[idfoto];
}
if (!empty($kdfoto))  {
	$sql="select * from t_memberfoto where idfoto='".mysql_real_escape_string($kdfoto)."' and stopen='0' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal foto");
	$row = mysql_fetch_array($query);
	$selisih = ambilselisih(strtotime($row[tanggal]), time());
	$file = "foto/foto$row[idfoto].jpg";
	$size = getimagesize($file);
	$lebar = 630;
	$tinggi = round(($size[1]*$lebar)/$size[0],0);
	$koleksi .="<center><a href=\"$file\" rel=\"facebox\"><img src='$file' width='$lebar' height='$tinggi' id='gambar' ></a><br>$row[judul] - ".$selisih." </center>
	<center><div id='fotodetail' >";
	// tampilkan dafta list foto semua di album ini
	$byk=5;
	$hal=$_GET['hal'];
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$sql2 = mysql_query("SELECT * from t_memberfoto where idalbum='".mysql_real_escape_string($kdalbum)."' ");
	$n=mysql_num_rows($sql2);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</span>";
		else $pag .="<a href='user.php?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=".$kdfoto."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$koleksi .= "<div id='pag' >$pag</div>";
	$sql2="select * from t_memberfoto where idalbum='".mysql_real_escape_string($kdalbum)."' and stopen='0' order by idfoto desc";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal foto di album ini");
	while($r = mysql_fetch_array($query2)) {
		$path ="foto/foto$r[idfoto].jpg";
		$selisih = ambilselisih(strtotime($r[tanggal]), time());
		if (file_exists(''.$path.'')) {
		$koleksi .="<a href='?id=koleksifotodetail&kode=".hex($userid,$noacak)."&kdfoto=$r[idfoto]' rel=\"tooltip\" content=\"Foto $r[judul] <br> Upload : ".$selisih." \"  ><img src='thumb.php?img=$path' id=gambar /></a>";
		}
		else $koleksi .="<img src='foto/kosong.jpg' height='70' width='100' id='gambar' > ";
	}
	$koleksi .="</div></center>";
$koleksi .="<hr style='border: thin solid #6A849D;'><h3>Komentar :</h3>";
	$sql="select * from t_memberfoto_kom where idfoto='".mysql_real_escape_string($kdfoto)."' order by idfotokom desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal komentar foto");
	while($row = mysql_fetch_array($query)) {
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$nama = member_nama($row[userid]);
		$pesan = $row[komentar];
		$gb=fotouser($row[userid]);
		$koleksi .="<div style='border-bottom:#dedede dashed 1px;min-height:50px;'>$gb <b>$nama</b> :: $selisih <br>$pesan </div>";

	}
	$koleksi .='<br><form action="" method="post" name="komentar"><textarea style="width:380px;height:20px;" id="komentar" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea> &nbsp;&nbsp;<input type="submit" value="  Kirim  " name="komenfoto" class="komenfoto" id="button2" /><input type=hidden name="kdfoto" value="'.$kdfoto.'" id="kdfoto" ><input type=hidden name="userid" value="'.hex("komenfoto,".$saya,$noacak).'" id="userid" >
</form><br>';
$koleksi .="<hr style='border: #dedede dashed 1px;'><br>";
}
else { //klo kondisi album kosong
	$koleksi .="<br>Data Foto kosong";
	$koleksi .="<hr style='border: #dedede dashed 1px;'><br>";
}
$koleksi .="</div>";

return $koleksi;
}
?>