<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function balastopik() {
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
$(function() {$(".kirimbalas").click(function() {
	var element = $(this);
    var kdtopik = $("#kdtopik").val();
	var kdforum = $("#kdforum").val();
	var pesan = $("#pesan").val();
	var code = $("#code").val();
	var userid = $("#userid").val();
	var dataString = \'userid=\'+ userid +\'&kdtopik=\'+ kdtopik +\'&pesan=\'+pesan+\'&code=\'+ code+\'&kdforum=\'+ kdforum;	
	if(pesan==\'\') {
		alert("Pesan masih kosong");
	}	
	else if(code==\'\') {
		alert("Kode konfirmasi masih kosong");
	}	
	else {
		$.ajax({type: "POST",url: "kontenforum.php",data: dataString,success: function(html){$("#hasil").append(html);$("#boxpesan").hide();} });
	}
return false;
});

});

</script>';
$kdtopik =$_GET['kdtopik'];
$kdforum =$_GET['kdforum'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$emo="<center>Emoticons</center><br>
&nbsp;<a href=\"javascript:add_emo(' :D ')\" title='Very Happy'><img src='../images/emo/icon1.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :) ')\" title='Smile'><img src='../images/emo/icon2.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :( ')\" title='Sad'><img src='../images/emo/icon3.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :o ')\" title='Surprised'><img src='../images/emo/icon4.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :shock: ')\" title='Shocked'><img src='../images/emo/icon5.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :? ')\" title='Confused'><img src='../images/emo/icon6.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' 8) ')\" title='Cool'><img src='../images/emo/icon7.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :lol: ')\" title='Laughing'><img src='../images/emo/icon8.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :x ')\" title='Mad'><img src='../images/emo/icon9.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :P ')\" title='Razz'><img src='../images/emo/icon10.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :oops: ')\" title='Embarassed'><img src='../images/emo/icon11.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :cry: ')\" title='Crying or Very sad'><img src='../images/emo/icon12.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :evil: ')\" title='Evil or Very Mad'><img src='../images/emo/icon13.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :twisted: ')\" title='Twisted Evil'><img src='../images/emo/icon14.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :roll: ')\" title='Rolling Eyes'><img src='../images/emo/icon15.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :wink: ')\" title='Wink'><img src='../images/emo/icon16.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :!: ')\" title='Exclamation'><img src='../images/emo/icon17.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :E: ')\" title='Question'><img src='../images/emo/icon18.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :idea: ')\" title='Idea'><img src='../images/emo/icon19.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :arrow: ')\" title='Arrow'><img src='../images/emo/icon20.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :| ')\" title='Neutral'><img src='../images/emo/icon21.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :mrgreen: ')\" title='Mr. Green'><img src='../images/emo/icon22.gif'></a>&nbsp;";

$cetak .='<h3>Balas Topik</h3>';
$cetak .= "<script language='javascript'>
function add_emo(text) {
	var txtarea = document.post.pesan;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
		txtarea.focus();
	}
}
</script>";

	$r = mysql_query("SELECT * FROM t_forum where forum_id='". mysql_real_escape_string($kdforum)."'") or die("Query failed");
	$ro = mysql_fetch_array($r);
	$judul = $ro[forum_nama];
	$r = mysql_query("SELECT * FROM t_forum_isi where isi_id='". mysql_real_escape_string($kdtopik)."'") or die("Query failed");
	$ro = mysql_fetch_array($r);
	$judul2 = $ro[isi_judul];
$cetak .="<div id='hasil' ></div><div id='boxpesan' ><a href='?id=forum' >Forum</a> &raquo; <a href='?id=lihattopik&kdforum=$kdforum' >$judul</a> &raquo; <a href='?id=lihatbalasan&kdforum=$kdforum&kdtopik=$kdtopik' >$judul2</a><br><br>";
$cetak .="<table border=0 width=98% id='tablebaru' cellspacing='0' cellpadding='4'>
<form action='' method='post' name='post'>
<tr><td class=td0 valign=top><B>Pesan :</B><br><br>$emo</td><td class=td1 ><TEXTAREA name='pesan' id='pesan'  cols='65' rows='10'></TEXTAREA><input type=hidden name='kdforum' value='".hex($kdforum,$noacak)."' id='kdforum' ><input type=hidden name='userid' value='".hex("tambahbalas,".$userid,$noacak)."' id='userid' ><input type=hidden name='kdtopik' value='".hex($kdtopik,$noacak)."' id='kdtopik' >
<br><img src='../functions/spam.php'><br />Kode Verifikasi<br><input type=text name='code' id='code' >
<br><br><input type=submit id=button2 class='kirimbalas' value=' Simpan '></td></tr></form></table></div>";

$cetak .="</div>";

return $cetak;
}

function tambahtopik() {
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
$(function() {$(".kirimtopik").click(function() {
	var element = $(this);
    var topik = $("#topik").val();
	var kdforum = $("#kdforum").val();
	var pesan = $("#pesan").val();
	var code = $("#code").val();
	var userid = $("#userid").val();
	var dataString = \'userid=\'+ userid +\'&topik=\'+ topik+\'&pesan=\'+pesan+\'&code=\'+ code+\'&kdforum=\'+ kdforum;
	if(topik==\'\') {
		alert("Topik masih kosong");
	}	
	else if(pesan==\'\') {
		alert("Pesan masih kosong");
	}	
	else if(code==\'\') {
		alert("Kode konfirmasi masih kosong");
	}	
	else {
		$.ajax({type: "POST",url: "kontenforum.php",data: dataString,success: function(html){$("#hasil").append(html);$("#boxpesan").hide();} });
	}
return false;
});

});

</script>';
$kdforum =$_GET['kdforum'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$emo="<center>Emoticons</center><br>
&nbsp;<a href=\"javascript:add_emo(' :D ')\" title='Very Happy'><img src='../images/emo/icon1.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :) ')\" title='Smile'><img src='../images/emo/icon2.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :( ')\" title='Sad'><img src='../images/emo/icon3.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :o ')\" title='Surprised'><img src='../images/emo/icon4.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :shock: ')\" title='Shocked'><img src='../images/emo/icon5.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :? ')\" title='Confused'><img src='../images/emo/icon6.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' 8) ')\" title='Cool'><img src='../images/emo/icon7.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :lol: ')\" title='Laughing'><img src='../images/emo/icon8.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :x ')\" title='Mad'><img src='../images/emo/icon9.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :P ')\" title='Razz'><img src='../images/emo/icon10.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :oops: ')\" title='Embarassed'><img src='../images/emo/icon11.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :cry: ')\" title='Crying or Very sad'><img src='../images/emo/icon12.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :evil: ')\" title='Evil or Very Mad'><img src='../images/emo/icon13.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :twisted: ')\" title='Twisted Evil'><img src='../images/emo/icon14.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :roll: ')\" title='Rolling Eyes'><img src='../images/emo/icon15.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :wink: ')\" title='Wink'><img src='../images/emo/icon16.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :!: ')\" title='Exclamation'><img src='../images/emo/icon17.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :E: ')\" title='Question'><img src='../images/emo/icon18.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :idea: ')\" title='Idea'><img src='../images/emo/icon19.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :arrow: ')\" title='Arrow'><img src='../images/emo/icon20.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :| ')\" title='Neutral'><img src='../images/emo/icon21.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :mrgreen: ')\" title='Mr. Green'><img src='../images/emo/icon22.gif'></a>&nbsp;";

$cetak.='<h3>Topik Baru</h3>';
$cetak.= "<script language='javascript'>
function add_emo(text) {
	var txtarea = document.post.pesan;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
		txtarea.focus();
	}
}
</script>";
	$r = mysql_query("SELECT * FROM t_forum where forum_id='". mysql_real_escape_string($kdforum)."'") or die("Query failed");
	$ro = mysql_fetch_array($r);
	$judul = $ro[forum_nama];
$cetak .="<div id='hasil' ></div><div id='boxpesan' ><a href='?id=forum' >Forum</a> &raquo; <a href='?id=lihattopik&kdforum=$kdforum' >$judul</a><br><br>";
$cetak .="<table border=0 width=98% id='tablebaru' cellspacing='0' cellpadding='4'>
<form action='' method='post' name='post'>
<tr><td width=30% class=td0 ><B>Topik :</B></td><td class=td1 ><INPUT type='text' id='topik' name='topik' value='' maxlength='64' size='40'>&nbsp;&nbsp;Max: 128 Karakter</td></tr>
<tr><td class=td0 valign=top><B>Pesan :</B><br><br>$emo</td><td class=td1 ><TEXTAREA name='pesan' id='pesan'  cols='65' rows='10'></TEXTAREA><input type=hidden name='kdforum' value='".hex($kdforum,$noacak)."' id='kdforum' ><input type=hidden name='userid' value='".hex("tambahtopik,".$userid,$noacak)."' id='userid' >
<br><img src='../functions/spam.php'><br />Kode Verifikasi<br><input type=text name='code' id='code' >
<br><br><input type=submit id=button2 class='kirimtopik' name=submit value=' Simpan '></td></tr></form></table></div>";

$cetak .="</div>";
return $cetak;
}

function lihatbalasan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .='<h3>Forum Diskusi</h3>';
$kdforum=$_GET['kdforum'];
$kdtopik=$_GET['kdtopik'];
$cetak .='<script type="text/javascript">
function hapusbalas(userid,kdbalas) {
	if(confirm("Apakah Anda yakin akan menghapus balasan topik ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdbalas=\'+ kdbalas ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihatbalasan&kdforum='.$kdforum.'&kdtopik='.$kdtopik.'";}});
	}
	
}
</script>';
	$r = mysql_query("SELECT * FROM t_forum where forum_id='". mysql_real_escape_string($kdforum)."'") or die("Query failed");
	$ro = mysql_fetch_array($r);
	$judul = $ro[forum_nama];
	$r = mysql_query("SELECT * FROM t_forum_isi where isi_id='". mysql_real_escape_string($kdtopik)."'") or die("Query failed");
	$row = mysql_fetch_array($r);
	$judul2 = $row[isi_judul];
		
		$tgl = ambilselisih(strtotime($row[isi_tgl]), time());
		$r = mysql_query("SELECT * FROM t_forum_isi where userid='$row[userid]'") or die("Query failed");
		$t = mysql_num_rows($r);
		$r = mysql_query("SELECT * FROM t_forum_balas where userid='$row[userid]'") or die("Query failed");
		$tot = mysql_num_rows($r);
		$tot = $tot + $t;
		$nama =member_nama($row[userid]);
		$gmb = fotouser($row[userid]);
		$sign = array(" ",":D",":)",":(",":oops:",":shock:",":?","8)",":lol:",":x",":P",":o",":cry:",":evil:",":twisted:",":roll:",":wink:",":!:",":E:",":idea:",":arrow:",":|",":mrgreen:");
		
        $isi= filter_pesan($row[isi_body]);
		for ($i=1;$i<=22;$i++) {
		$emo="<img src='../images/emo/icon$i.gif'>";
		$isi =str_replace($sign[$i],$emo,$isi);
		}
	$cetak .="<a href='?id=forum' >Forum</a> &raquo; <a href='?id=lihattopik&kdforum=$kdforum' >$judul</a> &raquo; $judul2
	<div style='float:right;'><a href='?id=tambahtopik&kdforum=$kdforum' id=button2 >Tambah Topik Baru</a> &nbsp;&nbsp;&nbsp;<a href='?id=balastopik&kdforum=$kdforum&kdtopik=$kdtopik' id=button2 >Balas Topik</a></div><br><br>";
	
  $sql="SELECT * FROM t_forum_balas where isi_id='". mysql_real_escape_string($kdtopik)."' ";
 	$hal = $_GET['hal'];
	$byk=20;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=lihatbalasan&kdforum=$kdforum&kdtopik=$kdtopik&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
  
  $query = $sql." order by balas_id asc LIMIT ".$awal.",".$byk.""; 
  $result = mysql_query ($query) or die (mysql_error()); 

 	$cetak .="<table width='100%' border=0 id='tablebaru' cellpadding='3'>
	<TR class='td0' ><td width=70 >Pengirim</td><td><b>Topik :</b> $judul2</td></tr>";
 	$cetak .="<TR class='td2'><td valign=top >$gmb<br><br><br><br>Total Kirim: $tot<br>$mod</td><td  valign= top ><img src='../images/user.png' align=left > &nbsp;$nama - $tgl<hr style='border:1px dashed #999999;'>$isi</td></tr>";
    while($row = mysql_fetch_array($result)) {
		if ($level=='Guru') $hapus="<div style='float:right' ><a href='#' onclick=\"hapusbalas('".hex("hapusbalas,".$userid,$noacak)."','".hex($row[balas_id],$noacak)."')\" title='Klik untuk menghapus topik ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>";
		else $hapus="";
		$tgl = ambilselisih(strtotime($row[balas_tgl]), time());
		$r = mysql_query("SELECT * FROM t_forum_isi where userid='$row[userid]'") or die("Query failed");
		$t = mysql_num_rows($r);
		$r = mysql_query("SELECT * FROM t_forum_balas where userid='$row[userid]'") or die("Query failed");
		$tot = mysql_num_rows($r);
		$tot = $tot + $t;		
		$nama =member_nama($row[userid]);
		$gmb = fotouser($row[userid]);
		$sign = array(" ",":D",":)",":(",":oops:",":shock:",":?","8)",":lol:",":x",":P",":o",":cry:",":evil:",":twisted:",":roll:",":wink:",":!:",":E:",":idea:",":arrow:",":|",":mrgreen:");
		$isi= filter_pesan($row[balas_body]);
		for ($i=1;$i<=22;$i++) {
		$emo="<img src='../images/emo/icon$i.gif'>";
		$isi =str_replace($sign[$i],$emo,$isi);
		}
		$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
	$cetak .="<TR class='$warna'><td valign= top >$gmb <br><br><br><br>Total Kirim: $tot<br>$mod</td><td valign= top ><img src='../images/user.png' align=left > &nbsp;$nama - $tgl $hapus<hr style='border:1px dashed #999999;'>$isi </td></tr>";   

	}
$cetak .="</table><br>";
$cetak .="</div>";
return $cetak;
}

function lihattopik() {
include "koneksi.php";

$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .='<h3>Forum Diskusi</h3>';
$kode =$_GET['kdforum'];

$cetak .='<script type="text/javascript">
function hapustopik(userid,kdtopik) {
	if(confirm("Apakah Anda yakin akan menghapus topik ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdtopik=\'+ kdtopik ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=lihattopik&kdforum='.$kode.'";}});
	}
	
}
</script>';
$r = mysql_query("SELECT * FROM t_forum where forum_id='". mysql_escape_string($kode)."'") or die("Query failed");
$ro = mysql_fetch_array($r);
$judul = $ro[forum_nama];
$cetak .="<a href='?id=forum' class=ver10>Forum</a> &raquo; $judul<div style='float:right;' ><a href='?id=tambahtopik&kdforum=$kode' id=button2 >Tambah Topik Baru</a></div><br><br>";

  $hal = $_GET['hal'];
	$byk=30;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	$sql ="SELECT * FROM t_forum_isi where forum_id='". mysql_real_escape_string($kode)."' ";
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=lihattopik&kdforum=$kode&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
	
    $query = $sql." order by isi_id desc LIMIT ".$awal.",".$byk.""; 
    $result = mysql_query ($query); 
	$cetak .="<table width='100%' border=0 id='tablebaru' cellpadding=3 ><TR class='td0' ><td width=50% > Topik</td><td align=center> Pengirim</td><td align=center>Balas</td><td align=center>Tgl Pengirim</td></tr>";
    while($row = mysql_fetch_array($result)) {
		if ($level=='Guru') $hapus="<div style='float:right' ><a href='#' onclick=\"hapustopik('".hex("hapustopik,".$userid,$noacak)."','".hex($row[isi_id],$noacak)."')\" title='Klik untuk menghapus topik ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>";
		else $hapus="";
		$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
		$tgl=date("d-m-Y",strtotime($row[isi_tgl]));
		$tgl2=date("d-m-Y H:i",strtotime($row[isi_tgl]));
		$nama =member_nama($row[userid]);
		$r = mysql_query("SELECT * FROM t_forum_balas where isi_id='$row[isi_id]'") or die("Query failed");
		$jml_r = mysql_num_rows($r);
		if ($jml_r==0) $jml_r="-";
		$skr = date("d-m-Y");
		if ($tgl==$skr) $gb="<img src='../images/frm-b.png'>";
		else if ($jml_r >= 25) $gb="<img src='../images/frm-y-f.png'>";
		else if ($jml_r >= 50) $gb="<img src='../images/frm-b-f.png'>";
		else $gb="<img src='../images/frm-y.png'>";
	$cetak .="<TR class='$warna'><td>$gb <a href='?id=lihatbalasan&kdtopik=$row[isi_id]&kdforum=$row[forum_id]' >$row[isi_judul]</a></td><td align=center ><a href='?id=lih_profil&kode=".hex($row[userid],$noacak)."' title='Lihat Profil'>$nama</a></td><td align=center ><center>$jml_r</center></td><td align=center valign=top >$tgl2 $hapus</td></tr>";
	
	}
$cetak .="</table>";
$cetak .= "<div id='pag' align=right >$pag</div><br>";
$cetak .="<img src='../images/frm-y.png'> = Topik lama<br><img src='../images/frm-b.png'> = Topik hari ini<br>
 <img src='../images/frm-y-f.png'> = Topik terbaik dengan 25+ balasan<br><img src='../images/frm-b-f.png'> = Topik terbaik hari ini";
$cetak .="</div>";
return $cetak;
}

// menampilkan forum diskusi
function forum() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .='<h3>Forum Diskusi</h3>';

$cetak .="<table width='100%' border=0 id='tablebaru' >
<TR ><td class='td0'><b>Forum</b></td><td align=center class='td0'><b>Topik</b></td><td align=center class='td0'><b>Balas</b></td>
	<td align=center class='td0'><b>Terakhir</b></td></tr>";
    $result = mysql_query("SELECT * FROM t_forum ") or die("Query failed");
    while($row = mysql_fetch_array($result)) {
		$r = mysql_query("SELECT * FROM t_forum_isi where forum_id='".$row[forum_id]."'order by isi_id desc") or die("Query failed");
		$jml_t = mysql_num_rows($r);
		$ro = mysql_fetch_array($r);
		$idu=$ro[userid];
		$selisih = ambilselisih(strtotime($ro[isi_tgl]), time());

		$nama =member_nama($ro[userid]);
		$r = mysql_query("SELECT * FROM t_forum_balas where isi_id='".$ro[isi_id]."'") or die("Query failed");
		$jml_r = mysql_num_rows($r);
		if ($jml_r==0) $jml_r="-";
		$cetak .="<TR  class='td1'><td valign=top><table><tr><td><img src='../images/forum.gif' align=left ></td><td>
		<b><a href='?id=lihattopik&kdforum=$row[forum_id]' > $row[forum_nama]</a></b><br>$row[forum_ket]</td></tr></table></td><td align=center><center>$jml_t</td><td ><center>$jml_r</td><td align=center >".$selisih."<br><a href='?id=lih_profil&kode=".hex($idu,$noacak)."' title='Lihat Profil'>$nama</a></td></tr>";
	}
$cetak .="</table>";
$cetak .="</div>";
return $cetak;
}
?>