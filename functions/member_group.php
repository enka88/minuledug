<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi yang berhubungan dengan group

//daftar undangan  group
function undangan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function konfirmasi(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan bergabung dengan group "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=undangan";}});
	}
}
function tolak(userid,kode,nama) {
	if(confirm("Apakah Anda yakin menolak bergabung dengan group "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=undangan";}});
	}
}
</script>';
$cetak .="<div id='depan-tengah'>";

$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

$sql = "SELECT idgroup FROM t_membergroup_anggota where status='0' and userid='". mysql_real_escape_string($userid)."' ";
$query = mysql_query($sql);
$n=mysql_num_rows($query);
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=undangan&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	$cetak .="<h3>Anda mempunyai $n undangan group</h3>Silahkan klik konfirmasi untuk bergabung ke dalam group tersebut.";
	 $query = mysql_query($sql." limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb = fotogroup($row[idgroup]);
		$query2=mysql_query("select t_membergroup.nmgroup,t_membergroup_jenis.jenis from t_membergroup, t_membergroup_jenis where t_membergroup_jenis.idjenis=t_membergroup.idjenis and t_membergroup.idgroup='".$row[idgroup]."' ");
		$r=mysql_fetch_array($query2);
		$nama =$r[nmgroup];
		$jenis=$r[jenis];
		$query2=mysql_query("select idgroup from t_membergroup_anggota where idgroup='$row[idgroup]' and userid='". mysql_real_escape_string($userid)."'");
		$jum =mysql_num_rows($query2);
		$cetak .="<div id='carimember' >".$gb." $nama<br>Jenis : $jenis<br>Anggota : $jum orang<br><div style='margin-top:5px;' ><a href='#' onclick=\"konfirmasi('".hex("kongabung,".$userid,$noacak)."','".hex($row[idgroup],$noacak)."','$nama')\" title='Klik untuk bergabung dengan group ini'  id='button2' >Konfirmasi</a>&nbsp;&nbsp;&nbsp;<a href='#' onclick=\"tolak('".hex("tolakgroup,".$userid,$noacak)."','".hex($row[idgroup],$noacak)."','$nama')\" title='Klik untuk menolak bergabung dengan group ini' id='button2' > &nbsp;Tolak&nbsp; </a></div></div>";
	  }
	
	}
	else {
		$cetak .="<h3>Anda tidak mempunyai undangan group.</h3>";
	}

$cetak .="</div>";
return $cetak;
}

// fungsi menampilkan group yang diikutin
function lihgroup() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$keycari = $_GET['keycari'];
$cari = $_GET['cari'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function konfirmasi(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan keluar dari group "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihgroup";}});
	}
}
function gabung(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan bergabung dengan group "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihgroup";}});
	}
}
</script>';

$cetak .="<div id='depan-tengah'>";

$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<form  method='get' name='form' action='user.php'><div id='box-status'>Pencarian Group lain &nbsp;&nbsp;<input type=text name='keycari' id='keycari' maxlength='50' size='20' title='Silahkan tuliskan nama group yang akan dicari' value='$keycari' >&nbsp;&nbsp;<input type=\"submit\" id=button2 value=\" Cari \" class=\"cari_botton\"  />
<input type=hidden name='cari' value='cari' ><input type=hidden name=id value=lihgroup >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='boxframe.php?id=groupbaru&userid=".hex($userid,$noacak)."' rel=\"facebox\"  title='Klik untuk lihat group yang diikuti' id=button2 >Group Baru</a></div></form>";
if ($keycari=='' && $cari=='cari') $cetak .="Anda belum memasukan nama group pencarian."; 
elseif (strlen(trim($keycari)) < 3  && $cari=='cari' ) $cetak .="Kata yang anda cari kurang tepat.";
else {
	if ($cari=='cari') $sql="SELECT * FROM t_membergroup where nmgroup like '%". mysql_real_escape_string($keycari) ."%'";
	else $sql=" SELECT idgroup FROM t_membergroup_anggota where (status='1' or status='2') and userid='". mysql_real_escape_string($userid)."'";
$query = mysql_query($sql);
$n=mysql_num_rows($query);
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=lihgroup&keycari=$keycari&cari=$cari&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	if ($cari=='cari')  $cetak .="<h3>Anda mencari group lain </h3>";
	else $cetak .="<h3>Anda telah bergabung dengan group</h3>";
	 $query = mysql_query($sql." limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb = fotogroup($row[idgroup]);
		$gabung ="<div style='margin-top:5px;' ><a href='#' onclick=\"gabung('".hex("gbgp,".$userid,$noacak)."','".hex($row[idgroup],$noacak)."','$nama')\" title='Klik untuk bergabung dengan group ini'  id='button2' >Gabung ke group</a></div>";
		$keluar ="<div style='margin-top:5px;' ><a href='#' onclick=\"konfirmasi('".hex("keluar,".$userid,$noacak)."','".hex($row[idgroup],$noacak)."','$nama')\" title='Klik untuk keluar dari group ini'  id='button2' >Keluar Group</a></div>";
		//if ($cari=='cari') {
			$query2=mysql_query("select status from t_membergroup_anggota where idgroup='".$row[idgroup]."' and userid='". mysql_real_escape_string($userid)."' ");
			if($r=mysql_fetch_array($query2)){
				if ($r[status]=='1') $tom = $keluar;
				elseif ($r[status]=='2') $tom = "<b>Menunggu validasi Pengurus group</b>";
				else $tom = "<div style='margin-top:5px;' ><a href='?id=undangan' title='Klik untuk lihat undangan group'  id='button2' >Lihat Undangan Group</a></div>";
			}
			else {
			$tom = $gabung; }
		//}
		//else { 
		//	$tom = $keluar;
		//}
		$query2=mysql_query("select t_membergroup.nmgroup,t_membergroup_jenis.jenis,t_membergroup.stgroup,t_membergroup.userid from t_membergroup, t_membergroup_jenis where t_membergroup_jenis.idjenis=t_membergroup.idjenis and t_membergroup.idgroup='".$row[idgroup]."' ");
		$r=mysql_fetch_array($query2);
		$nama =$r[nmgroup];
		$jenis=$r[jenis];
		if ($r[stgroup]==1) $gr="Status : Tertutup, member tertentu yang dapat mengikuti group ini";
		else $gr="Status : Terbuka, setiap member dapat mengikuti group ini.";
		if ($r[userid]==$userid) $tom="<div style='margin-top:5px;' ><a href=\"boxframe.php?id=hapgroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($row[idgroup],$noacak)."\" title='Klik untuk menghapus group beserta aktivitasnya' rel=\"facebox\" id='button2' >Hapus Group</a></div>";
		$query2=mysql_query("select idgroup from t_membergroup_anggota where idgroup='$row[idgroup]' ");
		$jum =mysql_num_rows($query2);
		$cetak .="<div id='carimember' >".$gb." $nama<br>Jenis : $jenis<br>Anggota : $jum orang<br>$gr<br>$tom</div>";
	  }
	
	}
	else {
		$cetak .="<h3>Anda tidak bergabung dengan group.</h3>";
	}
}
$cetak .="</div>";
return $cetak;
}
//
// fungsi untuk menampilkan group yang didalamnya ada beberapa aktivitas.
function group() {
include "koneksi.php";
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";

$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
	$sql="select * from t_membergroup,t_membergroup_jenis where t_membergroup.idjenis=t_membergroup_jenis.idjenis and t_membergroup.idgroup='".mysql_real_escape_string($kdgroup)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal group");
	if($row=mysql_fetch_array($query)) {
		$tgl =$row[tanggal];
		$tgl = ambilselisih(strtotime($tgl), time())." ";
		$pendiri = $row[userid];
		if ($row[stgroup]==1) $status="Tertutup, hanya member tertentu yang dapat mengikuti group ini";
		else $status="Terbuka, setiap member dapat mengikuti group ini.";
		
		$sql2="select idgroup from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."'";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal group");
		$tot = mysql_num_rows($query2);
		$gb3=fotouser($row[userid]);
		$pengurus ="<a href='?id=lih_profil&kode=".hex($row[userid],$noacak)."' rel=\"tooltip\" content=\"$gb3 Silahkan klik disini untuk melihat profilnya.\" >".member_nama($row[userid])."</a> ( Pendiri ), ";
		
		$sql2="select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and kategori='1' and userid<>'".mysql_real_escape_string($row[userid])."'";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal group");
		while($r = mysql_fetch_array($query2)) {
			$gb3=fotouser($r[userid]);
			$pengurus .="<a href='?id=lih_profil&kode=".hex($r[userid],$noacak)."' rel=\"tooltip\" content=\"$gb3 Silahkan klik disini untuk melihat profilnya.\" >".member_nama($r[userid])."</a>, ";
		}
		$sql2="select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid<>'".mysql_real_escape_string($row[userid])."' and status='1' order by tanggal desc limit 0,5";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal group");
		while($r = mysql_fetch_array($query2)) {
			$gb4=fotouser($r[userid]);
			$anggota .="<a href='?id=lih_profil&kode=".hex($r[userid],$noacak)."' rel=\"tooltip\" content=\"$gb4 Silahkan klik disini untuk melihat profilnya.\" >".member_nama($r[userid])."</a>, ";
		}
		$sql2="select idgroup from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and status='2'";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal group");
		$jmlvalid = mysql_num_rows($query2);
		$level=level_group($kdgroup,$userid);
		if ($jmlvalid >0 and $level=='1' ) $valid="<tr><td valign='top' >Konfirmasi</td><td><a href='?id=anggotagroup&kdgroup=".hex($kdgroup,$noacak)."' >$jmlvalid member meminta untuk menjadi anggota baru</a></td></tr>";
		
		$cetak .="<div id='nama'><img src='../images/group.png' > $row[nmgroup]</div><hr style='border: thin solid #6A849D;'>
		<div id='box-status' ><table border='0' >
		<tr><td colspan='2' ><b>Informasi Umum</b></td></tr><tr><td width=120 >Jenis Group</td><td>$row[jenis]</td></tr>
		<tr><td>Deskripsi</td><td>".filter_pesan($row[ket])."</td></tr><tr><td>Tanggal dibuat</td><td>$tgl</td></tr>
		<tr><td>Status</td><td>".$status."</td></tr><tr><td colspan='2' ><b>Informasi Anggota</b></td></tr>
		<tr><td>Total Anggota</td><td>$tot</td></tr>
		<tr><td valign='top' >Pengurus</td><td>$pengurus</td></tr>
		<tr><td valign='top' >Anggota Terbaru</td><td>$anggota</td></tr>
		".$valid."</table></div>";
	}
// seleksi apakah saya anggota dari group ini
  if ( anggota_group($kdgroup,$userid) =='ya') {
      $cetak .= "<h3>Info Terbaru</h3>";
		$sql2="select *from t_membergroup_info where idgroup='".mysql_real_escape_string($kdgroup)."' order by tanggal desc limit 0,3 ";
		if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal info group");
		while($row = mysql_fetch_array($query2)) {
		 	$isi = strip_tags($row[isi]);
			$max = 300; // maximal 300 karakter 
			$min = 250; // minimal 150 karakter 
			if( strlen( $isi ) > $max ) { 
				$pecah = substr( $isi, 0, $max ); 
				$akhirParagrap = strrpos( $pecah, "\n" ); 
				$akhirKalimat = strrpos( $pecah, '.' ); 
				$akhirSubKalimat = strrpos( $pecah, ',' ); 
				$spasiTerakhir = strrpos( $pecah, ' ' ); 
	 
				if( $akhirParagrap >= $min ) { 
					$potong = $akhirParagrap; 
				}elseif( $akhirKalimat >= $min ) { 
					$potong = $akhirKalimat; 
				}elseif( $akhirSubKalimat >= $min ) { 
					$potong = $akhirSubKalimat; 
				}else { 
					$potong = $spasiTerakhir; 
				} 
				$isi = substr( $isi, 0, $potong+1 )."..."; 
			}
            $isi = filter_pesan($isi);
			$selisih = ambilselisih(strtotime($row[tanggal]), time());
			$nama = member_nama($row[userid]);
			$cetak .="<img src='../images/newsgroup.png' align=top >&nbsp;<b>$row[judul]</b><br>Pengirim : $nama - $selisih <br>
			$isi <a href='?id=infogroup&kdgroup=".hex($kdgroup,$noacak)."&kdinfo=".hex($row[idgroupinfo],$noacak)."' title='Lihat selengkapnya' >Selengkapnya</a><br><hr style='border: 1pt dashed #DEDEDE;'>";
		}
  } // akhir seleksi angota group atau bukan
  
$cetak .="</div>";
return $cetak;
}

// fungsi info group
function infogroup() {
include "koneksi.php";
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .= '<script type="text/javascript">
// awal pengiriman komentar
$(function() {$(".komeninfo").click(function() {
	var element = $(this);
    var kdinfo = $("#kdinfo").val();
	var kdgroup = $("#kdgroup").val();
	var userid = $("#userid").val();
	var komentar = $("#komentar").val();
    var dataString = \'userid=\'+ userid +\'&kdinfo=\'+ kdinfo +\'&pesan=\'+ komentar ;
	if(komentar==\'\') {
		alert("Komentar masih kosong");
	}
	else if(komentar==\'Tuliskan komentar Anda....\') {
		alert("Komentar masih kosong ");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=group&kdgroup="+ kdgroup +"&kdinfo="+kdinfo;}});
	}
	
});

});
function hapusinfo(userid,kdinfo,kdgroup) {
	if(confirm("Apakah Anda yakin akan menghapus info group ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdinfo=\'+ kdinfo + \'&kdgroup=\' + kdgroup;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=infogroup&kdgroup="+kdgroup;}});
	}
}
function hapusinfokom(userid,kdkom,kdinfo) {
	if(confirm("Apakah Anda yakin akan menghapus komentarnya ?")) {
    var dataString = \'userid=\'+ userid + \'&kdkom=\' + kdkom;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=infogroup&kdgroup='.hex($kdgroup,$noacak).'&kdinfo="+kdinfo;}});
	}
}

</script>';
$kdinfo =$_GET['kdinfo'];
$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
$nmgroup = nama_group($kdgroup);
$cetak .="<div id='nama'><img src='../images/group.png' > ".$nmgroup."</div><hr style='border: thin solid #6A849D;'>";
$level=level_group($kdgroup,$userid);
if ( anggota_group($kdgroup,$userid) =='ya') { // seleksi apakah anda anggota dari group ini ?
if ($level=='1') // seleksi level pengurus baru bisa tambah info
	$cetak .="<div style='float:right;' ><a href='boxframe.php?id=infogroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."' rel=\"facebox\"  title='Klik untuk tambah info group ini' id=button2 align=right >Tambah Info Group</a></div>";
	$cetak .="<h3>Info Group</h3>";
//seleksi dulu menampilkan semua info atau satu persatu
 if ($kdinfo=='') {
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$sql2 = mysql_query("SELECT idgroupinfo FROM t_membergroup_info where idgroup='".mysql_real_escape_string($kdgroup)."' ");
	$n=mysql_num_rows($sql2);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='?id=infogroup&kdgroup=".hex($kdgroup,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	}
	}
	$cetak .= "<div id='pag'>$pag</div>";
	if ($n > 0) {
	  $cetak .= "<ul>";
	  $sql2="select * from t_membergroup_info where idgroup='".mysql_real_escape_string($kdgroup)."' order by tanggal desc limit $awal,$byk ";
	  if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal info group");
	  while($row = mysql_fetch_array($query2)) {
	  	$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$nama = member_nama($row[userid]);
		$cetak .="<li><a href='?id=infogroup&kdgroup=".hex($kdgroup,$noacak)."&kdinfo=".hex($row[idgroupinfo],$noacak)."' >$row[judul]</a> - <i>( pengirim : $nama - $selisih )</i>";
	  }
	  $cetak .= "</ul>";
	}
	else $cetak .="Data info group masih kosong";
 }
  else { // apabila menampilkan hanya satu informasi
	$kdinfo=unhex($kdinfo,$noacak);
	if ($level=='1') // seleksi level pengurus baru bisa tambah info
	$cetak .="<div style='float:right;' ><a href=\"boxframe.php?id=editinfo&userid=".hex($userid,$noacak)."&kdinfo=".hex($kdinfo,$noacak)."\" rel=\"facebox\" id='editlink'  title='Klik untuk mengubah info group ini'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><a href='#' onclick=\"hapusinfo('".hex("hapinfo,".$userid,$noacak)."','".hex($kdinfo,$noacak)."','".hex($kdgroup,$noacak)."')\" title='Klik untuk menghapus info group ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>";
	$sql2="select * from t_membergroup_info where idgroupinfo='".mysql_real_escape_string($kdinfo)."' ";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal info group");
	if($row = mysql_fetch_array($query2)) {
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$nama = member_nama($row[userid]);
		$cetak .="<center><h3>$row[judul]</h3></center>Pengirim : $nama - $selisih <hr style='border: 1pt dashed #DEDEDE;'>
		".filter_pesan($row[isi])." <br><br><a href='#' >Kembali ke atas</a>";
		// tambah komentar
		$cetak .="<hr style='border: thin solid #6A849D;'><h3>Komentar :</h3>";
		$sql="select * from t_membergroup_infokom where idgroupinfo='".mysql_real_escape_string($kdinfo)."' order by tanggal desc limit 0,15";
		if(!$query=mysql_query($sql)) die ("Pengambilan gagal komentar info");
		while($r = mysql_fetch_array($query)) {
			$selisih = ambilselisih(strtotime($r[tanggal]), time());
			$nama = member_nama($r[userid]);
			$pesan = filter_pesan($r[komentar]);
			$gb=fotouser($r[userid]);
			$cetak .="<div id='komen3'>$gb <b>$nama</b> :: $selisih ";
			if ($level=='1') $cetak .="<div id='sthapus' ><a href='#' title='Klik untuk menghapus komentar' onclick=\"hapusinfokom('".hex("hapinfokom,".$userid,$noacak)."','".hex($r[idinfokom],$noacak)."','".hex($kdinfo,$noacak)."')\">x</a></div>";
			$cetak .="<br>$pesan </div>";
	
		}
		$cetak .='<br><form action="" method="post" name="komentar"><textarea style="width:380px;height:20px;" id="komentar" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea> &nbsp;&nbsp;<input type="submit" value="  Kirim  " name="komeninfo" class="komeninfo" id="button2" /><input type=hidden name="kdinfo" value="'.hex($kdinfo,$noacak).'" id="kdinfo" ><input type=hidden name="kdgroup" value="'.hex($kdgroup,$noacak).'" id="kdgroup" ><input type=hidden name="userid" value="'.hex("kominfo,".$userid,$noacak).'" id="userid" >
	</form><br>';
	}
 }
 } //  akhir seleksi cek keanggotaan
 else $cetak .="Anda bukan anggota dari group ini. Terima kasih";
 
$cetak .="</div>";
return $cetak;
}

// fungsi anggota group
function anggotagroup() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$nama = $_SESSION['User']['nama'];
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function hapanggota(userid,kdgroup) {
	if(confirm("Apakah Anda yakin akan menghapus member dari keanggotaannya?")) {
    var dataString = \'userid=\'+ userid +\'&kdgroup=\' + kdgroup;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=anggotagroup&kdgroup="+kdgroup;}});
	}
}
function konfirgroup(userid,kdgroup) {
	if(confirm("Apakah Anda yakin akan mengizikan keanggotaan member ini?")) {
    var dataString = \'userid=\'+ userid +\'&kdgroup=\' + kdgroup;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=anggotagroup&kdgroup="+kdgroup;}});
	}
}
function levelanggota(userid,kdgroup,ket) {
	if(confirm("Apakah Anda yakin akan mengubah level kepengurusan member ini?")) {
    var dataString = \'userid=\'+ userid +\'&kdgroup=\' + kdgroup+\'&ket=\' + ket;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=anggotagroup&kdgroup="+kdgroup;}});
	}
}
</script>';
$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
$nmgroup = nama_group($kdgroup);
$level=level_group($kdgroup,$userid);
$cetak .="<div id='nama'><img src='../images/group.png' > ".$nmgroup."</div><hr style='border: thin solid #6A849D;'>";
$cetak .="<h3>Anggota Group</h3>";
	$sql = "select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid<>'". mysql_real_escape_string($userid) ."' and (status='1' or status='2') ";
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=anggotagroup&kdgroup=".hex($kdgroup,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	 $query = mysql_query($sql." order by status desc, tanggal asc limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb=fotouser($row[userid]);
		$query2=mysql_query("select nama,userid,ket,tgl_login from t_member where userid='".$row[userid]."'");
  		if($r=mysql_fetch_array($query2)) {
			$selisih = ambilselisih(strtotime($r[tgl_login]), time());
			if (teman_bukan($r[userid],$userid)=='bukan') $tambah ="<a href='boxframe.php?id=tamteman&userid=".hex($userid,$noacak)."&tujuan=".hex($r[userid],$noacak)."' rel=\"facebox\" id='button2' >Tambah sebagai teman</a>";
			else $tambah ="Anda telah berteman";
		  $kotak="anggota";$valid="";$hapus="";
		  if ($row[kategori]=='1') $kategori = "( Pengurus )";
		  else $kategori ="";
		  if ($level=='1' ) { // klo pengurus makan bisa hapus dan konfirmasi
			if ($row[status]=='2') {
				$kotak ="anggotanovalid";
				$valid="<a href='#' onclick=\"konfirgroup('".hex("konfgp,".$r[userid],$noacak)."','".hex($kdgroup,$noacak)."')\" id='button2' title='Klik ini untuk mengizinkan member ini bergabung menjadi anggota group' >Konfirmasi</a>";
			}
			$hapus="<a href='#' onclick=\"levelanggota('".hex("levanggota,".$r[userid],$noacak)."','".hex($kdgroup,$noacak)."','".$row[kategori]."')\" id='imgprofil' title='Klik ini untuk mengubah level anggota group' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;<a href='#' onclick=\"hapanggota('".hex("hpanggota,".$r[userid],$noacak)."','".hex($kdgroup,$noacak)."')\" id='hapuslink' title='Klik ini untuk menghapus member ini dari anggota group' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>";
		  }
		  $cetak .="<div id='$kotak' >".$gb." <b>$r[nama]</b> ".$kategori."<div id='tombol-kanan' >".$hapus."</div><br>Terakhir login : ".$selisih."<br>Status member : $r[ket]<div id='tombol-kanan' >".$valid."&nbsp;&nbsp;".$tambah."&nbsp;&nbsp;<a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($r[userid],$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a></div></div>";
		}
	  }
	  
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";

$cetak .="</div>";
return $cetak;
}

//fungsi mengundang teman
function undangmember() {
include "koneksi.php";
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$userid = $_SESSION['User']['userid'];
$nama = $_SESSION['User']['nama'];
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
$nmgroup = nama_group($kdgroup);
$level=level_group($kdgroup,$userid);
$cetak .="<div id='nama'><img src='../images/group.png' > ".$nmgroup."</div><hr style='border: thin solid #6A849D;'>";
$cetak .="<h3>Undang Teman untuk bergabung </h3>";
$cetak .='<link rel="stylesheet" href="css/TextboxList.css" type="text/css" media="screen" charset="utf-8" />';
$cetak .='<script src="js/GrowingInput.js" type="text/javascript" charset="utf-8"></script>';
$cetak .='<script src="js/TextboxList.js" type="text/javascript" charset="utf-8"></script>';
$cetak .='<script src="js/TextboxList.Autocomplete.js" type="text/javascript" charset="utf-8"></script>';
$cetak .="<script type=\"text/javascript\" charset=\"utf-8\">		
			$(function(){
				// Autocomplete initialization
				var t4 = new TextboxList('#form_tags_input', {unique: true, plugins: {autocomplete: {}}});

				t4.getContainer().addClass('textboxlist-loading');				
				$.ajax({url: 'listeman.php', dataType: 'json', success: function(r){
					t4.plugins['autocomplete'].setValues(r);
					t4.getContainer().removeClass('textboxlist-loading');
				}});				
			});
		</script>";
$cetak .="<div id='box-status'><form action='user.php' method=\"get\" accept-charset=\"utf-8\">
Pilih Teman <input type=\"text\" name=\"teman\" value='' id=\"form_tags_input\" /><br>
<img src='../functions/spam.php'  >&nbsp;&nbsp;Kode Verifikasi <input type='text' name='code' size='12'  >
<input type='submit' value=' Kirim ' id='button2' /><input type=hidden name='id' value='undangsimpan' >
<input type=hidden name='kdgroup' value='".hex($kdgroup,$noacak)."' ></form></div>";

$cetak .="</div>";
return $cetak;
}

// simpan undangan teman
function undangsimpan() {
include "koneksi.php";
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$userid = $_SESSION['User']['userid'];
$nama = $_SESSION['User']['nama'];
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
$nmgroup = nama_group($kdgroup);
$level=level_group($kdgroup,$userid);
$cetak .="<div id='nama'><img src='../images/group.png' > ".$nmgroup."</div><hr style='border: thin solid #6A849D;'>";
$cetak .="<h3>Undang Teman untuk bergabung </h3>";
$code = $_GET['code'];
$teman = $_GET['teman'];
$kode= $_SESSION['kodeRandom'];
  	if (trim($teman) == '' ) {
	$cetak .= "Teman yang diundang masih kosong. <a href='user.php?id=undangmember&kdgroup=".hex($kdgroup,$noacak)."' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		$cetak .="Kode keamanan salah. <a href='user.php?id=undangmember&kdgroup=".hex($kdgroup,$noacak)."' id='button' > Kembali </a>";
	}
	else {
		$id = explode(",",$teman);
		
		for($i=0;$i<count($id);$i++) {
			$sql="select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($id[$i])."'  ";
			if(!$query=mysql_query($sql));
			if(mysql_num_rows($query)==0) {
				$query=mysql_query("insert into t_membergroup_anggota (idgroup,userid,tanggal,status) values ('".mysql_real_escape_string($kdgroup)."','".mysql_real_escape_string($id[$i])."',NOW(),'0') ");
				$cetak .="Undangan untuk bergabung dengan group ini berhasil dikirim.";
			}
			else $cetak .="Member ini telah bergabung dengan group.";
		}
	}
$cetak .="</div>";
return $cetak;
}

// fungsi tampilkan diskusi
function diskusigroup() {
include "koneksi.php";
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .= '<script type="text/javascript">
function hapusdiskusi(userid,kdgroup,kdtopik) {
	if(confirm("Apakah Anda yakin akan menghapus topik diskusi group ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdtopik=\'+ kdtopik + \'&kdgroup=\' + kdgroup;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=diskusigroup&kdgroup="+kdgroup;}});
	}
}
</script>';
$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
$nmgroup = nama_group($kdgroup);
$cetak .="<div id='nama'><img src='../images/group.png' > ".$nmgroup."</div><hr style='border: thin solid #6A849D;'>";
$level=level_group($kdgroup,$userid);
if ( anggota_group($kdgroup,$userid) =='ya') { // seleksi apakah anda anggota dari group ini ?
$cetak .="<div style='float:right;' ><a href='boxframe.php?id=topikgroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."' rel=\"facebox\"  title='Klik untuk tambah topik diskusi' id=button2 align=right >Tambah Topik Diskusi</a></div>";
	if ($level=='1') $kolhapus = "<td class='td0' width=3% >Edit</td>";
	else $kolhapus ="";
	$cetak .="<h3>Diskusi Group</h3>";
//seleksi dulu menampilkan semua info atau satu persatu
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$sql2 = mysql_query("SELECT idtopik FROM t_membergroup_diskusi where idgroup='".mysql_real_escape_string($kdgroup)."' ");
	$n=mysql_num_rows($sql2);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='?id=diskusigroup&kdgroup=".hex($kdgroup,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	}
	}
	$cetak .= "<div id='pag'>$pag</div>";
	if ($n > 0) {
	  $cetak .= "<table id='tablebaru' cellpadding='3' cellspacing='2' width=100% >
	  <tr ><td width='55%' class='td0' >Topik</td><td width='5%'class='td0'>Balas</td><td width='30%' align='center' class='td0' >Pengirim Terakhir</td>".$kolhapus."</tr>";
	  $sql2="select * from t_membergroup_diskusi where idgroup='".mysql_real_escape_string($kdgroup)."' order by tanggal desc limit $awal,$byk ";
	  if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal info group");
	  while($row = mysql_fetch_array($query2)) {
	  	if ($level=='1') {
			$hapus="<td><a href='boxframe.php?id=edittopikgroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."&kdtopik=".hex($row[idtopik],$noacak)."' rel=\"facebox\"  title='Klik untuk tambah topik diskusi' id='editlink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;<a href='#' onclick=\"hapusdiskusi('".hex("hptopik,".$userid,$noacak)."','".hex($kdgroup,$noacak)."','".hex($row[idtopik],$noacak)."')\" id='hapuslink' title='Klik ini untuk menghapus topik diskusi group' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>";
		}
		else $hapus="";
		$warna = "td1";
		if ($j==1) {
		$warna = "td2";	$j=0; }
		else $j=1;
		$jum=0;
		$terakhir='';
		$sql3="select tanggal,userid from t_membergroup_diskusibalas where idtopik='".mysql_real_escape_string($row[idtopik])."' order by tanggal desc";
	    $query3=mysql_query($sql3);
		$jum = mysql_num_rows($query3);
		if($r=mysql_fetch_array($query3)){
			$selisih = ambilselisih(strtotime($r[tanggal]), time());
			$gb3=fotouser($r[userid]);
			$terakhir = "<a href='?id=lih_profil&kode=".hex($r[userid],$noacak)."' rel=\"tooltip\" content=\"$gb3 Silahkan klik disini untuk melihat profilnya.\" >".member_nama($r[userid])."</a> - ".$selisih;
		}
		else {
		$gb3=fotouser($row[userid]);
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$terakhir = "<a href='?id=lih_profil&kode=".hex($row[userid],$noacak)."' rel=\"tooltip\" content=\"$gb3 Silahkan klik disini untuk melihat profilnya.\" >".member_nama($row[userid])."</a> - ".$selisih;
		}
		$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\" ><td>
		<img src='../images/folder.png' align='left' >&nbsp;&nbsp;<a href='?id=topikdiskusigp&kdgroup=".hex($kdgroup,$noacak)."&kdtopik=".hex($row[idtopik],$noacak)."' >".filter_pesan($row[judul])."</a></td><td align=center >".$jum."</td><td>".$terakhir."</td>".$hapus."</tr>";
	  }
	  $cetak .= "</table>";
	}
	else $cetak .="Data diskusi group masih kosong";

 } //  akhir seleksi cek keanggotaan
 else $cetak .="Anda bukan anggota dari group ini. Terima kasih";
 
$cetak .="</div>";
return $cetak;
}
// tampikan detail topik group
function topikdiskusigp() {
include "koneksi.php";
$kdgroup = unhex($_GET['kdgroup'],$noacak);
$kdtopik = unhex($_GET['kdtopik'],$noacak);
$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .= '<script type="text/javascript">
function hapusbalasan(userid,kdgroup,kdtopik,kdbalas) {
	if(confirm("Apakah Anda yakin akan menghapus balasan topik diskusi ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdtopik=\'+ kdtopik + \'&kdgroup=\' + kdgroup + \'&kdbalas=\' + kdbalas;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=topikdiskusigp&kdgroup="+kdgroup+"&kdtopik="+kdtopik;}});
	}
}
</script>';
$cetak .='<div id="judul" >Selamat Datang  <i>'.$nama.'</i></div>';
$cetak .="<div id='depan-tengahkiri'>";
$nmgroup = nama_group($kdgroup);
$cetak .="<div id='nama'><img src='../images/group.png' > ".$nmgroup."</div><hr style='border: thin solid #6A849D;'>";
$level=level_group($kdgroup,$userid);
if ( anggota_group($kdgroup,$userid) =='ya') { // seleksi apakah anda anggota dari group ini ?
$cetak .="<div style='float:right;' ><a href='boxframe.php?id=balastopikgp&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."&kdtopik=".hex($kdtopik,$noacak)."' rel=\"facebox\"  title='Klik untuk balas topik diskusi' id=button2 align=right >Balas Topik</a></div>";
$cetak .="<h3>Diskusi Group</h3>";
$sql = mysql_query("SELECT * FROM t_membergroup_diskusi where idtopik='".mysql_real_escape_string($kdtopik)."' ");
if($r=mysql_fetch_array($sql)) {
$nama= member_nama($r[userid]);
$selisih = ambilselisih(strtotime($r[tanggal]), time());
if ($level=='1') {$kolhapus = "<td class='td0' width=3% >Hapus</td>";$kolhapus2 ="<td></td>";}
else {$kolhapus ="";$kolhapus2 ="";}
	$file = "profil/gb".$r[userid].".jpg";
	$fotouser ="<a href='user.php?id=lih_profil&kode=".hex($r[userid],$noacak)."' title='Lihat profil'><img src='profil/kosong.jpg' width='50' height='55' id='gambar' ></a>";
	if (file_exists(''.$file.'')) {
	   $fotouser="<a href='user.php?id=lih_profil&kode=".hex($r[userid],$noacak)."' title='Lihat profil'><img src='thumb-user.php?img=$file' width='50' height='55' id='gambar'  /></a>";
	}
$cetak .= "<table id='tablebaru' cellpadding='3' cellspacing='2' width=100% >
<tr ><td width='20%' class='td0' >Pengirim</td><td width='80%' class='td0' >Topik: ".filter_pesan($r[judul])."</td>".$kolhapus."</tr>";
$cetak .="<tr class='td2' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='td2'\" ><td>$nama<br>
".$fotouser."</td><td valign='top' ><img src='../images/time.png' width='14' height='14' align=left >&nbsp;&nbsp;".$selisih."
<hr style='border:1px dashed #999999;'>".filter_pesan($r[isi])."</td>".$kolhapus2."</tr>";
}
//seleksi dulu menampilkan semua info atau satu persatu
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$sql2 = mysql_query("SELECT * FROM t_membergroup_diskusibalas where idtopik='".mysql_real_escape_string($kdtopik)."' ");
	$n=mysql_num_rows($sql2);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='?id=topikdiskusigp&kdgroup=".hex($kdgroup,$noacak)."&kdtopik=".hex($kdtopik,$noacak)."&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	}
	}
	$cetak .= "<div id='pag'>$pag</div>";
	if ($n > 0) {
	 
	  $sql2="select * from t_membergroup_diskusibalas where idtopik='".mysql_real_escape_string($kdtopik)."' order by tanggal asc limit $awal,$byk ";
	  if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal info group");
	  while($row = mysql_fetch_array($query2)) {
	  	if ($level=='1') {
			$hapus="<td valign=top align=center ><a href='#' onclick=\"hapusbalasan('".hex("hpbalasgp,".$userid,$noacak)."','".hex($kdgroup,$noacak)."','".hex($row[idtopik],$noacak)."','".hex($row[idbalas],$noacak)."')\" id='hapuslink' title='Klik ini untuk menghapus balasan topik diskusi group' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>";
		}
		else $hapus="";
		$warna = "td1";
		if ($j==1) {
		$warna = "td2";	$j=0; }
		else $j=1;
		$jum=0;
		$file = "profil/gb".$row[userid].".jpg";
		$fotouser ="<a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."' title='Lihat profil'><img src='profil/kosong.jpg' width='50' height='55' id='gambar' ></a>";
		if (file_exists(''.$file.'')) {
	   	$fotouser="<a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."' title='Lihat profil'><img src='thumb-user.php?img=$file' width='50' height='55' id='gambar'  /></a>";
		}
		$nama= member_nama($row[userid]);
		$selisih = ambilselisih(strtotime($row[tanggal]), time());
		$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\" ><td>
		".$nama."<br>".$fotouser."</td><td valign=top ><img src='../images/time.png' width='14' height='14' align=left >&nbsp;&nbsp;".$selisih."<hr style='border:1px dashed #999999;'>". filter_pesan($row[isi])."</td>".$hapus."</tr>";
	  }
	  
	}
 $cetak .= "</table>";
 } //  akhir seleksi cek keanggotaan
 else $cetak .="Anda bukan anggota dari group ini. Terima kasih";
 
$cetak .="</div>";
return $cetak;
}
?>