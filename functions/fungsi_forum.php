<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}

function replyforum() {
include "koneksi.php";
global $memberlog;
$pesan=$_POST['pesan'];
$ket=$_POST['ket'];
$kd= $_GET['kd'];
$kode =$_GET['kode'];
if ($kode=='') $kode=$_POST['kode'];
if ($kd=='') $kd=$_POST['kd'];
$emo="<center>Emoticons</center><br>
&nbsp;<a href=\"javascript:add_emo(' :D ')\" title='Very Happy'><img src='../images/emo/icon1.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :) ')\" title='Smile'><img src='../images/emo/icon2.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :( ')\" title='Sad'><img src='../images/emo/icon3.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :o ')\" title='Surprised'><img src='../images/emo/icon4.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :shock: ')\" title='Shocked'><img src='../images/emo/icon5.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :? ')\" title='Confused'><img src='../images/emo/icon6.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' 8) ')\" title='Cool'><img src='../images/emo/icon7.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :lol: ')\" title='Laughing'><img src='../images/emo/icon8.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :x ')\" title='Mad'><img src='../images/emo/icon9.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :P ')\" title='Razz'><img src='../images/emo/icon10.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :oops: ')\" title='Embarassed'><img src='../images/emo/icon11.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :cry: ')\" title='Crying or Very sad'><img src='../images/emo/icon12.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :evil: ')\" title='Evil or Very Mad'><img src='../images/emo/icon13.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :twisted: ')\" title='Twisted Evil'><img src='../images/emo/icon14.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :roll: ')\" title='Rolling Eyes'><img src='../images/emo/icon15.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :wink: ')\" title='Wink'><img src='../images/emo/icon16.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :!: ')\" title='Exclamation'><img src='../images/emo/icon17.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :E: ')\" title='Question'><img src='../images/emo/icon18.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :idea: ')\" title='Idea'><img src='../images/emo/icon19.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :arrow: ')\" title='Arrow'><img src='../images/emo/icon20.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :| ')\" title='Neutral'><img src='../images/emo/icon21.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :mrgreen: ')\" title='Mr. Green'><img src='../images/emo/icon22.gif'></a>&nbsp;";

$newforum .='<div id="judul">Balas Topik</div>';
$newforum .= "<script language='javascript'>
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
</script><center><table width='98%' border=0 cellspacing='0' cellpadding='0'><TR><TD >
	  ";
if ($ket=="1") {
	if($pesan=="") $ero .="Tidak ada pesan,"; 
	}
if ($ket=="" || $ero<>"") {
$newforum .="<font class='ver10'>$ero<br><table border=1 width=100% bordercolor=#2492AB cellspacing='0' cellpadding='5'>
<form action='index.php' method='post' name='post'>
<tr bgcolor=#2492AB colspan=2>&nbsp;<td></td></tr>
<tr><td bgcolor=#2492AB valign=top><font class='ver10'><B>Pesan:</B><br><br>$emo</td><td><TEXTAREA name='pesan' class=editbox  cols='65' rows='10'>$pesan</TEXTAREA><input type=hidden name='id' value='replyforum' ><input type=hidden name='kode' value='$kode' >
<input type=hidden name='kd' value='$kd' ><input type=hidden name='ket' value='1' ><br>
<img src='../functions/spam.php'><br />Kode Verifikasi<br><input type=text name='code' >
<br><br><input type=submit class=button name=submit value=' Simpan '></td></tr></form></table>";
}
else {
session_start();
$code = $_POST['code'];
if (strtoupper($code) != $_SESSION['kodeRandom']) {
	die ("<body onload=\"alert('Kode keamanan salah');window.history.back()\">");
}
if ($pesan == '' ) {
	die ("<body onload=\"alert('Pesan masih kosong');window.history.back()\">");
}
list($userid,$user,$pass,$kategori) = explode("\|",$memberlog);
$tgl = date("Y-m-d H:i:s");
$pesan = strip_tags($pesan);
	$query = "insert into t_forum_balas (balas_body,balas_tgl,isi_id,userid,forum_id) values ('". mysql_escape_string($pesan)."','". mysql_escape_string($tgl)."','". mysql_escape_string($kd)."','". mysql_escape_string($userid)."','". mysql_escape_string($kode)."')";
    $result = mysql_query($query) or die("Query failed");
	$newforum .="<center><br><font class='ver10'>Konfirmasi, Terima kasih Pesan telah terkirim<br>
	<a href='index.php?id=view_replies&kd=$kd&kode=$kode' >kembali ke topik utama sebelumnya</a></center>";
	    $query1 = "select * from t_member where userid='". mysql_escape_string($userid)."'";
   	$result = mysql_query($query1) or die("Pengambilan gagal pesan");
	$row=mysql_fetch_array($result);
	$jum = $row[point]+3;
	$query1 = "update t_member set point='$jum' where userid='". mysql_escape_string($userid)."'";
   	$result = mysql_query($query1) or die("Pengambilan gagal pesan");
}
$newforum .="</TD></TR></table>";
return $newforum;
}

function newforum() {
include "koneksi.php";
global $memberlog;

$kode=$_POST['kode'];
$pesan=$_POST['pesan'];
$kd=$_POST['kd'];
$judul=$_POST['judul'];
if ($kode=='') $kode=$_GET['kode'];

$emo="<center>Emoticons</center><br>
&nbsp;<a href=\"javascript:add_emo(' :D ')\" title='Very Happy'><img src='../images/emo/icon1.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :) ')\" title='Smile'><img src='../images/emo/icon2.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :( ')\" title='Sad'><img src='../images/emo/icon3.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :o ')\" title='Surprised'><img src='../images/emo/icon4.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :shock: ')\" title='Shocked'><img src='../images/emo/icon5.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :? ')\" title='Confused'><img src='../images/emo/icon6.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' 8) ')\" title='Cool'><img src='../images/emo/icon7.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :lol: ')\" title='Laughing'><img src='../images/emo/icon8.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :x ')\" title='Mad'><img src='../images/emo/icon9.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :P ')\" title='Razz'><img src='../images/emo/icon10.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :oops: ')\" title='Embarassed'><img src='../images/emo/icon11.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :cry: ')\" title='Crying or Very sad'><img src='../images/emo/icon12.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :evil: ')\" title='Evil or Very Mad'><img src='../images/emo/icon13.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :twisted: ')\" title='Twisted Evil'><img src='../images/emo/icon14.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :roll: ')\" title='Rolling Eyes'><img src='../images/emo/icon15.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :wink: ')\" title='Wink'><img src='../images/emo/icon16.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :!: ')\" title='Exclamation'><img src='../images/emo/icon17.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :E: ')\" title='Question'><img src='../images/emo/icon18.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :idea: ')\" title='Idea'><img src='../images/emo/icon19.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :arrow: ')\" title='Arrow'><img src='../images/emo/icon20.gif'></a><br>
&nbsp;<a href=\"javascript:add_emo(' :| ')\" title='Neutral'><img src='../images/emo/icon21.gif'></a>&nbsp;
&nbsp;<a href=\"javascript:add_emo(' :mrgreen: ')\" title='Mr. Green'><img src='../images/emo/icon22.gif'></a>&nbsp;";

$newforum .='<div id="judul">Topik Baru</div>';
$newforum = "<script language='javascript'>
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
</script>
<center>";

if ($kd=="1") {
	if($judul=="") $ero .="Tidak ada judul,";
	if($pesan=="") $ero .="Tidak ad pesan,"; 
	}
if ($kd=="" || $ero<>"") {
$newforum .="<font class='ver10'>$ero<br><table border=1 width=98% bordercolor=#2492AB cellspacing='0' cellpadding='5'>
<form action='index.php' method='post' name='post'>
<tr><td bgcolor=#88CBDA width=100><font class='ver10'><B>Judul:</B></td><td><INPUT type='text' class=editbox name='judul' value='$judul' maxlength='64' size='40'><FONT class='ver10'>&nbsp;&nbsp;Max: 128 Karakter</FONT></td></tr>
<tr><td bgcolor=#88CBDA valign=top><font class='ver10'><B>Pesan:</B><br><br>$emo</td><td><TEXTAREA name='pesan' class=editbox  cols='65' rows='10'>$pesan</TEXTAREA><input type=hidden name='id' value='newforum' ><input type=hidden name='kode' value='$kode' >
<input type=hidden name='kd' value='1' ><br><img src='../functions/spam.php'><br />Kode Verifikasi<br><input type=text name='code' >
<br><br><input type=submit class=button name=submit value=' Simpan '></td></tr></form></table>";
}
else {

session_start();
$code = $_POST['code'];
if (strtoupper($code) != $_SESSION['kodeRandom']) {
	die ("<body onload=\"alert('Kode keamanan salah');window.history.back()\">");
}
if ($pesan == '' ) {
	die ("<body onload=\"alert('Pesan masih kosong');window.history.back()\">");
}
if ($judul == '' ) {
	die ("<body onload=\"alert('Judul pesan masih kosong');window.history.back()\">");
}
list($userid,$user,$pass,$kategori) = explode("\|",$memberlog);
$tgl = date("Y-m-d H:i:s");
$pesan = strip_tags($pesan);
$judul =strip_tags($judul);
	$query = "insert into t_forum_isi (isi_judul,isi_body,isi_tgl,userid,forum_id) values ('". mysql_escape_string($judul)."','". mysql_escape_string($pesan)."','$tgl','". mysql_escape_string($userid)."','". mysql_escape_string($kode)."')";
    $result = mysql_query($query) or die("Query failed");
$newforum .='<table width="100%"  border=0 cellspacing=0  cellpadding=0 >
  <tr>
    <td width=30><img src="../images/bg-tgh15.gif"></td>
    <td width="100%" background="../images/bg-tgh14.gif"><font class="tah12" style="color:#ffffff">&nbsp;<b>Topik Baru</td>
  </tr>
</table><center>';
	$newforum .="<br><font class='ver10'>Konfirmasi, Terima kasih Pesan telah terkirim <br>
	<a href='index.php?id=view_threads&kode=$kode' >Kembali ke Forum sebelumnya</a></center>";

    $query1 = "select * from t_member where userid='". mysql_escape_string($userid)."'";
   	$result = mysql_query($query1) or die("Pengambilan gagal pesan");
	$row=mysql_fetch_array($result);
	$jum = $row[point]+3;
	$query1 = "update t_member set point='$jum' where userid='". mysql_escape_string($userid)."'";
   	$result = mysql_query($query1) or die("Pengambilan gagal pesan");
}

return $newforum;
}

function view_replies() {
global $memberlog;
$kd= $_GET['kd'];
$kode =$_GET['kode'];
if ($kode=='') $kode=$_POST['kode'];
if ($kd=='') $kd=$_POST['kd'];
$hal=$_GET['hal'];

include "koneksi.php";
$view .='<div id="judul">Forum Diskusi</div>';
$view .= "<center><table width='100%' border=0 cellspacing='0' cellpadding='0'><TR><TD >";
	$r = mysql_query("SELECT * FROM t_forum where forum_id='". mysql_real_escape_string($kode)."'") or die("Query failed");
	$ro = mysql_fetch_array($r);
	$judul = $ro[forum_nama];
	$r = mysql_query("SELECT * FROM t_forum_isi where isi_id='". mysql_real_escape_string($kd)."'") or die("Query failed");
	$row = mysql_fetch_array($r);
	$judul2 = $row[isi_judul];
		$a=strtotime($row[isi_tgl]);
		$tgl=date("M d,Y",$a);
		$hari=date("l",$a);
		$jam=date("H:i:s",$a);
		$r = mysql_query("SELECT * FROM t_forum_isi where userid='$row[userid]'") or die("Query failed");
		$t = mysql_num_rows($r);
		$r = mysql_query("SELECT * FROM t_forum_balas where userid='$row[userid]'") or die("Query failed");
		$tot = mysql_num_rows($r);
		$tot = $tot + $t;
		$r = mysql_query("SELECT * FROM t_member where userid='$row[userid]'") or die("Query failed");
		$ro = mysql_fetch_array($r);
		$nama =$ro[nama];
		$mod="";
		if($ro[kategori]=="3" or $ro[kategori]=="4") $mod ="<br>Kordinator";
		$neg = negara($ro[negara]);	
		if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/member/gb$ro[userid].jpg";
		$gmb="<a href='index.php?id=lih_profil&kode=$ro[userid]'><img src='../images/member/kosong.jpg'  width='60' height='75'></a>";
		if (file_exists(''.$file.'')) {
	        $gmb="<a href='index.php?id=lih_profil&kode=$ro[userid]' ><img src='$file'  width='60' height='75' ></a>";
		}

		$sign = array(" ",":D",":)",":(",":oops:",":shock:",":?","8)",":lol:",":x",":P",":o",":cry:",":evil:",":twisted:",":roll:",":wink:",":!:",":E:",":idea:",":arrow:",":|",":mrgreen:");
		$isi=$row[isi_body];
		for ($i=1;$i<=22;$i++) {
		$emo="<img src='../images/emo/icon$i.gif'>";
		$isi =str_replace($sign[$i],$emo,$isi);
		}
	$view .="<font class='ver10'><b><a href='index.php?id=forum' class=ver10>Forum</a> <img src='../images/panah2.gif'> <a href='index.php?id=view_threads&kode=$kode' class=ver10>$judul</a> <img src='../images/panah2.gif'> $judul2
	<div align=right><a href='index.php?id=newforum&kode=$kode' class=ver10>Tambah Topik Baru</a> | <a href='index.php?id=replyforum&kode=$kode&kd=$kd' class=ver10>Balas Topik</a></div></b><br>";
	
  $brs=10;
  $kol=10;
  $byk_result=mysql_query("SELECT * FROM t_forum_balas where isi_id='". mysql_real_escape_string($kd)."' ");
  $byk=mysql_num_rows($byk_result);
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
  
    if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_forum_balas where isi_id='". mysql_real_escape_string($kd)."' order by balas_id asc LIMIT ".$awal.",".$brs.""; 
  $result = mysql_query ($query) or die (mysql_error()); 
if ($jml!=0) {
  $page .= "<center><font class='ver10'><a href='index.php?id=view_replies&kd=$kd&kode=$kode&hal=1' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='index.php?id=view_replies&kd=$kd&kode=$kode&hal=$back' style='color:000000;text-decoration:none' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$page .= "<b><a href='index.php?id=view_replies&kd=$kd&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	$page .= "<a href='index.php?id=view_replies&kd=$kd&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  $page .= "<a href='index.php?id=view_replies&kd=$kd&kode=$kode&hal=$next' style='color:000000;text-decoration:none' title='$next'> Next</a> <a href='index.php?id=view_replies&kd=$kd&kode=$kode&hal=$jml' style='color:000000;text-decoration:none' title='Page $jml'> Last</a></font></center>";
}
 	$view .="$page<br><table width='100%' border=1 bordercolor='#2492AB' cellspacing='2' cellpadding='2'><TR bgcolor=#2492AB ><td width=300><font class='ver10'><b>Pengirim</td><td><font class='ver10'><b>Topik: $judul2</td></tr>";
 	$view .="<TR bgcolor=#dddddd><td valign=top ><font class='ver10'><b>$nama</b><br>$gmb<br>
	Total Kirim: $tot<br>dari $neg$mod</td><td width=80% valign= top ><font class='ver10'>$hari,$tgl $jam<hr>$isi</td></tr>";
    while($row = mysql_fetch_array($result)) {
		$tgl="";$hari="";$jam="";
		$a=strtotime($row[balas_tgl]);
		$tgl=date("M d,Y",$a);
		$hari=date("l",$a);
		$jam=date("H:i:s",$a);
		$r = mysql_query("SELECT * FROM t_forum_isi where userid='$row[userid]'") or die("Query failed");
		$t = mysql_num_rows($r);
		$r = mysql_query("SELECT * FROM t_forum_balas where userid='$row[userid]'") or die("Query failed");
		$tot = mysql_num_rows($r);
		$tot = $tot + $t;		
		$r = mysql_query("SELECT * FROM t_member where userid='$row[userid]'") or die("Query failed");
		$ro = mysql_fetch_array($r);
		$nama =$ro[nama];
		$neg = negara($ro[negara]);
		$mod="";
	$email=$ro[email];
	$lan= strlen($email);
	for($m=1;$m<$lan;$m++) {
		if (substr($email,$m,1)=="@") {break;}
	}
	$ymid=substr($email,0,$m);
		if($ro[kategori]=="3" or $ro[kategori]=="4") $mod ="<br>Kordinator";
		
		if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/member/gb$ro[userid].jpg";
		$gmb="<a href='index.php?id=lih_profil&kode=$ro[userid]'><img src='../images/member/kosong.jpg'  width='60' height='75'></a>";
		if (file_exists(''.$file.'')) {
	        $gmb="<a href='index.php?id=lih_profil&kode=$ro[userid]' ><img src='$file'  width='60' height='75' ></a>";
		}
		$sign = array(" ",":D",":)",":(",":oops:",":shock:",":?","8)",":lol:",":x",":P",":o",":cry:",":evil:",":twisted:",":roll:",":wink:",":!:",":E:",":idea:",":arrow:",":|",":mrgreen:");
		$isi=$row[balas_body];
		for ($i=1;$i<=22;$i++) {
		$emo="<img src='../images/emo/icon$i.gif'>";
		$isi =str_replace($sign[$i],$emo,$isi);
		}
	$view .="<TR bgcolor='#D1E3F8'><td valign= top ><font class='ver10'><b>$nama</b><br>$gmb<br>
	Total Kirim: $tot<br>From $neg$mod<br><a href='ymsgr:sendIM?$ymid' title='$ymid'><img src='http://opi.yahoo.com/online?u=$ymid&m=g&t=1' border='0' width='64' height='16' alt='$ymid' /></a></td><td valign= top ><font class='ver10'>$hari,$tgl $jam<hr>$isi</td></tr>";   

	}
$view .="</table><br></TD></TR></table>$page<br>";
return $view;
}

function view_threads() {
include "koneksi.php";
global $memberlog;
$kode =$_GET['kode'];
if ($kode=='') $kode=$_POST['kode'];
$hal=$_GET['hal'];

$view .='<div id="judul">Faorum Diskusi</div>';
$view .= "<center><table width='100%' border=0 cellspacing='0' cellpadding='0'><TR><TD >";
	$r = mysql_query("SELECT * FROM t_forum where forum_id='". mysql_escape_string($kode)."'") or die("Query failed");
	$ro = mysql_fetch_array($r);
	$judul = $ro[forum_nama];
	$view .="<font class='ver10'><b><a href='index.php?id=forum' class=ver10>Forum</a> <img src='../images/panah2.gif'> $judul<div align=right><a href='index.php?id=newforum&kode=$kode' class=ver10>Tambah Topik Baru</a></div></b>";
$brs=10;
  $kol=10;
  $byk_result=mysql_query("SELECT * FROM t_forum_isi where forum_id='". mysql_real_escape_string($kode)."' ");
  $byk=mysql_num_rows($byk_result);
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
  
    if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_forum_isi where forum_id='". mysql_real_escape_string($kode)."' order by isi_id asc LIMIT ".$awal.",".$brs.""; 
  $result = mysql_query ($query) or die (mysql_error()); 
if ($jml!=0) {
  $page .= "<center><font class='ver10'><a href='index.php?id=view_threads&kode=$kode&hal=1' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='index.php?id=view_threads&kode=$kode&hal=$back' style='color:000000;text-decoration:none' title='$back'>Previous </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$page .= "<b><a href='index.php?id=view_threads&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	$page .= "<a href='index.php?id=view_threads&kode=$kode&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  $page .= "<a href='index.php?id=view_threads&kode=$kode&hal=$next' style='color:000000;text-decoration:none' title='$next'> Next</a> <a href='index.php?id=view_threads&kode=$kode&hal=$jml' style='color:000000;text-decoration:none' title='Page $jml'> Last</a></font></center>";
}	

	$view .="$page<br><table width='100%' border=1 bordercolor='#2492AB' cellspacing='2' cellpadding='2'><TR bgcolor=#2492AB ><td width=50% ><font class='ver10'><b>Topik</td><td align=center><font class='ver10'><b>Pengirim</td><td align=center><font class='ver10'><b>Balas</td>
	<td align=center><font class='ver10'><b>Tgl Pengirim</td></tr>";

    while($row = mysql_fetch_array($result)) {
		$a=strtotime($row[isi_tgl]);
		$tgl=date("M d,Y",$a);
		$hari=date("l",$a);
		$jam=date("H:i:s",$a);
		$r = mysql_query("SELECT * FROM t_member where userid='$row[userid]'") or die("Query failed");
		$ro = mysql_fetch_array($r);
		$nama =$ro[nama];
		$neg = negara($ro[negara]);
		if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$ro[userid]";
		$r = mysql_query("SELECT * FROM t_forum_balas where isi_id='$row[isi_id]'") or die("Query failed");
		$jml_r = mysql_num_rows($r);
		if ($jml_r==0) $jml_r="-";
		$skr = date("M d,Y");
		if ($tgl==$skr) $gb="<img src='../images/frm-b.png'>";
		else if ($jml_r >= 25) $gb="<img src='../images/frm-y-f.png'>";
		else if ($jml_r >= 50) $gb="<img src='../images/frm-b-f.png'>";
		else $gb="<img src='../images/frm-y.png'>";
	$view .="<TR ><td bgcolor='#D1E3F8'><font class='ver10'>$gb <b><a href='index.php?id=view_replies&kd=$row[isi_id]&kode=$row[forum_id]' class=ver10>$row[isi_judul]</a></b>
	</td><td align=center bgcolor='#88CBDA'><font class='ver10'><b><a href='$link' class=ver10 title='From $neg'>$nama</a></td><td align=center bgcolor='#D1E3F8'><center><font class='ver10'>$jml_r</td>
	<td align=center bgcolor='#2492AB'><font class='ver10'>$hari,$tgl $jam </td></tr>";
	
	}
$view .="</table><br>$page<br><font class='ver10'>
 <img src='../images/frm-y.png'> = Older threads<br><img src='../images/frm-b.png'> = Today's threads<br>
 <img src='../images/frm-y-f.png'> = Hot thread with 25+ replies<br><img src='../images/frm-b-f.png'> = Hot thread from today<br>
</TD></TR></table>";
return $view;
}
function forum() {
include "koneksi.php";
global $memberlog;

list($userid,$user,$pass,$kategori) = explode("\|",$memberlog);
$forum .='<div id="judul">Forum Diskusi</div>';
$forum .= "<center><table width='100%' border=0 cellspacing='0' cellpadding='0'><TR><TD >";

	$forum .="<table width='100%' border=1 bordercolor='#2492AB' cellspacing='2' cellpadding='2'><TR bgcolor=#2492AB ><td ><font class='ver10'><b>Forum</td><td align=center><font class='ver10'><b>Topik</td><td align=center><font class='ver10'><b>Balas</td>
	<td align=center><font class='ver10'><b>Terakhir</td><td align=center><font class='ver10'><b>Kordinator</td></tr>";
    $result = mysql_query("SELECT * FROM t_forum ") or die("Query failed");
    while($row = mysql_fetch_array($result)) {
		$r = mysql_query("SELECT * FROM t_forum_isi where forum_id='$row[forum_id]'order by isi_id desc") or die("Query failed");
		$jml_t = mysql_num_rows($r);
		$ro = mysql_fetch_array($r);
		$idu=$ro[userid];
		$a=strtotime($ro[isi_tgl]);
		$tgl=date("M d,Y",$a);
		$hari=date("l",$a);
		$jam=date("H:i:s",$a);
		$link="#";
		if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$idu";
		$r = mysql_query("SELECT * FROM t_member where userid='$ro[userid]'") or die("Query failed");
		$ro = mysql_fetch_array($r);
		$nama =$ro[nama];
		$neg = negara($ro[negara]);
		$r = mysql_query("SELECT * FROM t_forum_balas where forum_id='$row[forum_id]'") or die("Query failed");
		$jml_r = mysql_num_rows($r);
		if ($jml_r==0) $jml_r="-";
	$forum .="<TR><td valign=top bgcolor='#D1E3F8'><table><tr><td ><img src='../images/forum.gif'></td><td><font class='ver10'><b><a href='index.php?id=view_threads&kode=$row[forum_id]' class=ver10>$row[forum_nama]</a></b><br>
	$row[forum_ket]</td></tr></table></td><td align=center bgcolor='#88CBDA'><center><font class='ver10'>$jml_t</td><td align=center bgcolor='#D1E3F8'><center><font class='ver10'>$jml_r</td>
	<td align=center bgcolor='#88CBDA'><font class='ver10'>$hari,$tgl<br>$jam by <b><a href='$link' class=ver10 title='From $neg'>$nama</a></td><td align=center bgcolor='#D1E3F8'><font class='ver10'><b>";

		$r = mysql_query("SELECT * FROM t_forum_moderator, t_member WHERE t_forum_moderator.userid = t_member.userid and t_forum_moderator.forum_id='$row[forum_id]'") or die("Query failed 1");
		while($ro = mysql_fetch_array($r)) {
		$mod=$ro[username];
		$neg2 = negara($ro[negara]);
		$link2="#";
		if ($memberlog<>"") $link2="index.php?id=lih_profil&kode=$ro[userid]";
		$forum .="<a href='$link2' class=ver10 title='From $neg2'>$mod</a>,";
	    }
		$forum .="</td></tr>";
	}
$forum .="</table></TD></TR></table>";
return $forum;
}
?>