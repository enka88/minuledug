<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// terdiri dari fungsi yng berhubungan dengan teman dan member lain
// created by alan

//fungsi menampilkan member
function member() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$keycari = $_GET['keycari'];
$ket = $_GET['ket'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .="<div id='depan-tengah'>";

if ($ket=='email') $s2='selected';
else $s1='selected';
if ($keycari=='') $keycari='Nama/email orang';

$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<form  method='get' name='form' action='user.php'><div id='box-status'><img src='../images/cari.png' align='left'>Temukan orang lain yang Anda kenal, cari berdasarkan nama atau email orang lain <br><br>Pencarian  &nbsp;<select name='ket' ><option value='nama' $s1>Nama</option><option value='email' $s2>Email</option></select>&nbsp;&nbsp;<input type=text name='keycari' id='keycari' maxlength='50' onfocus='clearText2(this)' onblur='clearText2(this)' title='Silahkan tulis kunci yang akan dicari' value='$keycari' >&nbsp;&nbsp;<input type=\"submit\" id=button2 value=\" Cari \"  name=\"cari\" class=\"cari_botton\"  /><input type=hidden name=id value=member >&nbsp;&nbsp;&nbsp;<a href='boxframe.php?id=totmember' rel=\"facebox\" title='Klik untuk lihat total member di komunitas ini.' >Total Member</a></div></form>";

if ($keycari=='') $cetak .="Anda belum memasukan kunci pencarian."; 
elseif (strlen(trim($keycari)) < 3 ) $cetak .="Kata yang anda cari kurang tepat.";
elseif ($keycari=='Nama/email orang') $cetak .="Silahkan tulis nama atau email teman yang akan dicari.";
else {
	
	if($ket=='nama') $data = " and nama like '%". mysql_real_escape_string($keycari) ."%'";
	else $data = " and email like '%". mysql_real_escape_string($keycari) ."%'";
	$sql = "select userid,nama,ket,tgl_login from t_member where  userid<>'". mysql_real_escape_string($userid) ."' and status='1' $data ";
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
		else $pag .="<a href='user.php?id=member&keycari=$keycari&ket=$ket&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	$cetak .="<h3>Pencarian member lain</h3>";
	 $query = mysql_query($sql." order by nama limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb=fotouser($row[userid]);
		$selisih = ambilselisih(strtotime($row[tgl_login]), time());
		if (teman_bukan($row[userid],$userid)=='bukan') $tambah ="<a href='boxframe.php?id=tamteman&userid=".hex($userid,$noacak)."&tujuan=".hex($row[userid],$noacak)."' rel=\"facebox\" id='button2' >Tambah sebagai teman</a>";
			else $tambah ="Anda telah berteman";
		$cetak .="<div id='carimember' >".$gb." <b>$row[nama]</b><br>Terakhir login : $selisih<br>Status member : $row[ket]<div id='tombol-kanan' >$tambah&nbsp;&nbsp;&nbsp;<a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($row[userid],$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a></div></div>";
	  }
	}
	else  {
		$cetak .="Data yang anda cari tidak ada.";
	}
	
}

$cetak .="</div>";
return $cetak;
}

//fungsi tampilkan semua teman
function teman() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$keycari = $_GET['keycari'];
$ket=$_GET['ket'];
if ($ket=='semua') $keycari='Nama'; //seleksi klo tampilkan semua
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function hapusteman(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan memutuskan hubungan dengan "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=teman&ket='.$ket.'&keycari='.$keycari.'";}});
	}
}
</script>';
$cetak .="<div id='depan-tengah'>";

if ($keycari=='') $keycari='Nama teman';
	$query=mysql_query("select id_con from t_member_contact where id_master='". mysql_real_escape_string($userid) ."' and status='1'");
	$total=mysql_num_rows($query);
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<form  method='get' name='form' action='user.php'><div id='box-status'><img src='../images/cari.png' align='left'> &nbsp;Pencarian &nbsp;&nbsp;<input type=text name='keycari' id='keycari' maxlength='50' onfocus='clearText2(this)' onblur='clearText2(this)' title='Silahkan tulis kunci yang akan dicari' value='$keycari' >&nbsp;&nbsp;<input type=\"submit\" id=button2 value=\" Cari \"  name=\"cari\" class=\"cari_botton\"  /><input type=hidden name=id value=teman >&nbsp;&nbsp;&nbsp;Total : $total teman <a href='user.php?id=teman&ket=semua' id='button2' >Lihat Semua</a></div></form>";

if ($keycari=='') $cetak .="Anda belum memasukan nama pencarian."; 
elseif (strlen(trim($keycari)) < 3 ) $cetak .="Nama yang anda cari kurang tepat.";
elseif ($keycari=='Nama teman') $cetak .="Silahkan tulis nama teman yang akan dicari.";
else {
   if ($ket=='semua') $data="";
   else $data =" and t_member.nama like '%". mysql_real_escape_string($keycari) ."%' ";
$sql = "SELECT t_member.userid,t_member.nama,t_member.ket, t_member.point,t_member.tgl_login, t_member_contact.id_master,
  t_member_contact.id_con,t_member_contact.`status` FROM t_member RIGHT OUTER JOIN t_member_contact ON (t_member.userid = t_member_contact.id_con) where t_member_contact.id_master='". mysql_real_escape_string($userid)."' and t_member_contact.status='1' and host='0' $data ";
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
		else $pag .="<a href='user.php?id=teman&keycari=$keycari&ket=$ket&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	$cetak .="<h3>Pencarian teman</h3>";
	 $query = mysql_query($sql." order by nama limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb=fotouser($row[userid]);
		$selisih = ambilselisih(strtotime($row[tgl_login]), time());
		$cetak .="<div id='carimember' >".$gb." <table border=0 width='90%' cellpadding='0' cellspacing='0'><tr><td width='80'>Nama </td><td width='190'>: <b>$row[nama]</b></td>
		<td width=30 ></td><td width=30></td><td align=right ><a href='#' onclick=\"hapusteman('".hex("haptem,".$userid,$noacak)."','".hex($row[userid],$noacak)."','$row[nama]')\" title='Klik untuk memutuskan hubungan pertemanan' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td></tr>
		<tr><td>Status </td><td>: $row[ket]</td><td></td><td></td><td></td></tr>
		<tr><td>Terakhir login</td><td>: $selisih</td><td>Point</td><td>: $row[point]</td><td align=right ><a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($row[userid],$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a></td></tr></table></div>";
	  }
	}
	else  {
		$cetak .="Data yang anda cari tidak ada.";
	}
	
}

$cetak .="</div>";
return $cetak;
}

// tampilkan permintaan menjadi teman
function permintaan() {
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
	if(confirm("Apakah Anda yakin akan menjalin hubungan dengan "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=teman&ket=semua";}});
	}
}
function tolak(userid,kode,nama) {
	if(confirm("Apakah Anda yakin menolak pertemanan dengan "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=permintaan";}});
	}
}
</script>';
$cetak .="<div id='depan-tengah'>";

$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

$sql = "SELECT * from t_member_contact where id_con='". mysql_real_escape_string($userid)."' and t_member_contact.status='0' ";
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
		else $pag .="<a href='user.php?id=permintaan&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	$cetak .="<h3>Anda mempunyai $n permintaan pertemanan</h3>Silahkan klik konfirmasi untuk melakukan menambahkan member tersebut sebagai teman Anda.";
	 $query = mysql_query($sql." limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb=fotouser($row[id_master]);
		$nama=member_nama($row[id_master]);
		$cetak .="<div id='carimember' >".$gb." $nama<br><div style='margin-top:5px;' ><a href='#' onclick=\"konfirmasi('".hex("hub,".$userid,$noacak)."','".hex($row[id_master],$noacak)."','$nama')\" title='Klik untuk menjalin hubungan pertemanan'  id='button2' >Konfirmasi</a>&nbsp;&nbsp;&nbsp;<a href='#' onclick=\"tolak('".hex("tolak,".$userid,$noacak)."','".hex($row[id_master],$noacak)."','$nama')\" title='Klik untuk menolak hubungan pertemanan' id='button2' > &nbsp;Tolak&nbsp; </a>&nbsp;&nbsp;&nbsp;<a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($row[id_master],$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a></div></div>";
	  }
	
	}
	else {
		$cetak .="<h3>Anda tidak mempunyai permintaan pertemanan baru.</h3>";
	}

$cetak .="</div>";
return $cetak;
}
// daftar penolakan menjadi teman anda
function tolakteman() {
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
	if(confirm("Apakah Anda yakin akan menjalin hubungan dengan "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=tolakteman";}});
	}
}
function hapustolak(userid,kode,nama) {
	if(confirm("Apakah Anda yakin menolak pertemanan dengan "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=tolakteman";}});
	}
}
</script>';
$cetak .="<div id='depan-tengah'>";

$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";

$sql = "SELECT * from t_member_contact where id_master='". mysql_real_escape_string($userid)."' and t_member_contact.status='2' ";
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
		else $pag .="<a href='user.php?id=tolak&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	$cetak .="<h3>Anda mempunyai $n penolakan pertemanan</h3>Silahkan klik konfirmasi untuk melakukan konfirmasi ulang dalam penambahan member tersebut sebagai teman Anda.";
	 $query = mysql_query($sql." limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb=fotouser($row[id_con]);
		$nama=member_nama($row[id_con]);
		$cetak .="<div id='carimember' >".$gb." $nama<br><div style='margin-top:5px;' ><a href='#' onclick=\"konfirmasi('".hex("konhub,".$userid,$noacak)."','".hex($row[id_con],$noacak)."','$nama')\" title='Klik untuk menjalin hubungan pertemanan'  id='button2' >Konfirmasi</a>&nbsp;&nbsp;&nbsp;<a href='#' onclick=\"hapustolak('".hex("haptol,".$userid,$noacak)."','".hex($row[id_con],$noacak)."','$nama')\" title='Klik untuk menghapus hubungan pertemanan' id='button2' > &nbsp;Hapus&nbsp; </a>&nbsp;&nbsp;&nbsp;<a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($row[id_con],$noacak)."' rel=\"facebox\" id='button2' >Kirim Pesan</a></div></div>";
	  }
	
	}
	else {
		$cetak .="<h3>Anda tidak mempunyai penolakan pertemanan.</h3>";
	}

$cetak .="</div>";
return $cetak;
}
//fungsi tampilkan semua teman member lain
function temanlain() {
include "koneksi.php";
$userid = unhex($_GET['kode'],$noacak);
$saya = $_SESSION['User']['userid'];

$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .="<div id='depan-tengah'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$sql = "SELECT t_member.userid,t_member.nama,t_member.ket, t_member.point,t_member.tgl_login, t_member_contact.id_master,
  t_member_contact.id_con,t_member_contact.`status` FROM t_member RIGHT OUTER JOIN t_member_contact ON (t_member.userid = t_member_contact.id_con) where t_member_contact.id_master='". mysql_real_escape_string($userid)."' and t_member_contact.id_con<>'". mysql_real_escape_string($saya)."' and t_member_contact.status='1'  ";
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
		else $pag .="<a href='user.php?id=temanlain&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=center >$pag</div>";
	if ($n > 0 ) {
	$cetak .="<h3>Data Teman </h3>";
	 $query = mysql_query($sql." order by nama limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
		$gb=fotouser($row[userid]);
		$selisih = ambilselisih(strtotime($row[tgl_login]), time());
		$cetak .="<div id='carimember' >".$gb." <table border=0 width='90%' cellpadding='0' cellspacing='0'><tr><td width='80'>Nama </td><td width='190'>: <b>$row[nama]</b></td>
		<td width=30 ></td><td width=30></td><td align=right ><a href='boxframe.php?id=tamteman&userid=".hex($saya,$noacak)."&tujuan=".hex($row[userid],$noacak)."' rel=\"facebox\" id='button2' >Tambah teman</a></td></tr>
		<tr><td>Status </td><td>: $row[ket]</td><td></td><td></td><td></td></tr>
		<tr><td>Terakhir login</td><td>: $selisih</td><td>Point</td><td>: $row[point]</td><td align=right ><a href='boxframe.php?id=kirimpesan&userid=".hex($saya,$noacak)."&tujuan=".hex($row[userid],$noacak)."' rel=\"facebox\" id='button2' >&nbsp;&nbsp;&nbsp;Kirim Pesan&nbsp;&nbsp;</a></td></tr></table></div>";
	  }
	}
	else  {
		$cetak .="Data yang anda cari tidak ada.";
	}
	

$cetak .="</div>";
return $cetak;
}
?>