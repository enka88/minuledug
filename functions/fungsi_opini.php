<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

function project2() {
include "koneksi.php";

$kd=$_GET['kode'];

	$opini='Opini';$penulis='Penulis';$daftar='Daftar Komentar';$tambah='Tambah Komentar';$kembali='Kembali ke Atas';
	$dibaca='Dibaca';

	$sql="select * from t_project where id='".mysql_real_escape_string($kd)."'";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	$row = mysql_fetch_array($alan);
  		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama = $ro[nama];
		//$negara="From ".negara($ro[negara]);
		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		//$link="#";
		//if($userid<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/project/p$row[id].jpg";
		if (file_exists(''.$file.'')) {
	        $gbp="<img src='$file' align=left id=gambar width='300' height='225'>";
		}		
	if(!$a=mysql_query("select * from t_project where userid='".$row[userid]."'")) die ("Pengambilan gagal");
	$pro = mysql_num_rows($a);
	$baca=number_format($row[visit],0,".",",");
	$project .= "<table border=0 width='100%' border='0' cellspacing='4' cellpadding='2' align=center ><tr><td><center><b>$row[judul]</font></b></center>
	<font class='ver10'><img src='../images/arrow.gif'>$penulis <b>$nama</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tgl<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$dibaca</b> : $baca
	<br>--------------------------------------------------------------------------------------------------------------------------------------<br>";
		//	$link="";
		//if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/member/gb$row[userid].jpg";
		$gb="<img src='../images/member/kosong.jpg' width='60' height='75' id='gambar' align=left>";
		if (file_exists(''.$file.'')) {
	        $gb="<a href='$link' title='Lihat Profil' ><img src='$file' width='60' height='75' align=left id=gambar ></a>";
		}		
	$project .= "$gbp$row[deskripsi]<br><br><a href='index.php?id=project2&kode=$kd' class='ver10'>$kembali</a> <br></td></tr></table>";
return $project;
}

function project_com() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kd=$_GET['kode'];
$hal=abs((int)$_GET['hal']);
  $query=mysql_query("select * from t_project where id='$kd'");
  $row=mysql_fetch_array($query);
		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama = $ro[nama];
		//$negara="From ".negara($ro[negara]);
		$isi =strip_tags($row[deskripsi]);
  		$isi  =  substr($isi, 20, 300);
		$isib = substr($isi,300,320);
		$isib = strtok($isib," ");
		$isi .= $isib."...";
		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		//$link="#";
		//if($userid<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/project/p$row[id].jpg";
		$gb="";
		if($n==1) {$pos="right";$n=0;}
		else {$pos="left";$n=1;}
		if (file_exists(''.$file.'')) {
	        $gb="<a href='index.php?id=project&kode=$row[id]' class='ver10' title='$row[visit] to read'><img src='$file' width='80' height='80' align=$pos></a>";
		}
		$project .="<table width='100%'  border=0 cellspacing='0'  cellpadding='0'><tr><td>$gb Penulis <b>$nama</b>, $tgl<br><b>
		<a href='index.php?id=project&kode=$row[id]' class='ver10' title='Dibaca $row[visit]'>$row[judul]</a></b>,
		$isi<br></td></tr>
		<tr><td height=2 background='../images/gris_user.gif'></td></tr></table><br>";
  $brs=10;
  $kol=10;
  $byk_result=mysql_query("select * from t_project_com where id_project='".mysql_real_escape_string($kd)."'");
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
  
  $query = "SELECT * FROM t_project_com where id_project='".mysql_real_escape_string($kd)."' order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $project .= "<table width='100%' border='0' >";
  if ($jml!=0) {
  $project .= "<tr><td  ><center><a href='index.php?id=projectcom&kode=$kd&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal</a> 
  <a href='index.php?id=projectcom&kode=$kd&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$project .= "<b><a href='index.php?id=projectcom&kode=$kd&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$project .= "<a href='index.php?id=projectcom&kode=$kd&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $project .= "<a href='index.php?id=projectcom&kode=$kd&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='index.php?id=projectcom&kode=$kd&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a>
  </center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {

		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama=$ro[nama];
		$mod="";
			$email=$ro[email];
	$lan= strlen($email);
	for($m=1;$m<$lan;$m++) {
		if (substr($email,$m,1)=="@") {break;}
	}
	$ymid=substr($email,0,$m);
		//if ($ro[kategori]==4) $mod="<img src='../images/mod.gif' title='Coordinator' align=center>&nbsp;&nbsp;";
		//$del="";
		//if($kategori==4) $del="<a href='index.php?id=hapuscom&kode=$kd&kd=$row[id]' class=ver10 title='Hapus Komentar'>Hapus</a>";

		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		//$negara="From ".negara($ro[negara]);
		//$link="";
		//if ($userid<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/member/gb$ro[userid].jpg";
		$gb="<img src='../images/member/kosong.jpg' width='50' height='60' id=gambar align=left>";
		if (file_exists(''.$file.'')) {
	        $gb="<img src='$file' width='50' height='60' id=gambar align=left>";
		}		
	$project .="<table width='100%' class='art-article' CELLPADDING=4 CELLSPACING=0>
		<tr><td width=40%>$mod<font class='ver11p'><b>$nama</font></td><td align=right><font class='ver10'>$del&nbsp;&nbsp;Komentar</font></td></tr>
		<tr><td colspan=2 ><table width=100%><tr><td width=40% valign=top>$gb$tgl<br>&nbsp;&nbsp;<a href='ymsgr:sendIM?$ymid' title='$ymid'>
		<img src='http://opi.yahoo.com/online?u=$ymid&m=g&t=1' border='0' width='64' height='16' alt='$ymid' /></a></td>
		<td valign=top><font class='ver10'>$row[komentar]</font></td></tr></table></td></tr></table><br>";

 }        
  $project .= "</table>";

	
$project .= "</center>&nbsp;&nbsp;&nbsp;<img src='../images/mod.gif' title='Koordinator' r>&nbsp;&nbsp;Koordinator<br>";

return $project;
}

//-----------project
function project() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kd=$_GET['kode'];
$hal=abs((int)$_GET['hal']);
if($lang=='en') {
	$opini='Opinion';$penulis='Written';$daftar='Commentary List';$tambah='Commentary Add';$kembali='Back to Top';
	$dibaca='Read';
}
else {
	$opini='Opini';$penulis='Penulis';$daftar='Daftar Komentar';$tambah='Tambah Komentar';$kembali='Kembali ke Atas';
	$dibaca='Dibaca';
}


//$project .= "<div style='padding:7' >";

if ($kd=='') {
  $brs=10;
  $kol=10;
  $byk_result=mysql_query("select * from t_project where  status='1'");
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
  
  $query = "SELECT * FROM t_project where  status='1' order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $project .= "<table width='100%' border='0' cellspacing='4' cellpadding='2' align=center >";
  if ($jml!=0) {
  $project .= "<tr><td  ><center><font class='ver10'><a href='index.php?id=project&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='index.php?id=project&hal=$back' style='color:000000;text-decoration:none' title='$back'>Sebelum </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$project .= "<b><a href='index.php?id=project&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$project .= "<a href='index.php?id=project&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $project .= "<a href='index.php?id=project&hal=$next' style='color:000000;text-decoration:none' title='$next'> Lanjut</a> 
  <a href='index.php?id=project&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a>
  </font></center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {
  		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama = $ro[nama];
		//$negara="From ".negara($ro[negara]);
		$isi =strip_tags($row[deskripsi]);
  		$isi  =  substr($isi, 20, 300);
		$isib = substr($isi,300,320);
		$isib = strtok($isib," ");
		$isi .= $isib."...";
		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		//$link="#";
		//if($userid<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/project/p$row[id].jpg";
		$gb="";
		if($n==1) {$pos="right";$n=0;}
		else {$pos="left";$n=1;}
		if (file_exists(''.$file.'')) {
	        $gb="<a href='index.php?id=project&kode=$row[id]' class='ver10' title='Dibaca $row[visit]'><img src='$file' width='80' height='80' align=$pos></a>";
		}
		$project .="<tr><td>$gb<font class='ver10'>$penulis <b>$nama</b>, $tgl<br><b>
		<a href='index.php?id=project&kode=$row[id]' class='ver10' title='Dibaca $row[visit]'>$row[judul]</a></b>,
		$isi</font></td></tr>
		<tr><td height=2 background='../images/gris_user.gif'></td></tr>";
		
 }        
  $project .= "</table>";
}
else {
    $query = "SELECT * FROM t_project WHERE id='".mysql_real_escape_string($kd)."'";
    $result = mysql_query($query) or die("Query failed");
    
    $rows = mysql_fetch_array($result);
    
    $visits = $rows[visit] + 1;
    $query = "UPDATE t_project SET visit='$visits' WHERE id='".mysql_real_escape_string($kd)."'";
    $result = mysql_query($query) or die("Query failed");

	$sql="select * from t_project where id='".mysql_real_escape_string($kd)."'";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	$row = mysql_fetch_array($alan);
  		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama = $ro[nama];
		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		//$link="#";
		//if($userid<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../images/project/p$row[id].jpg";
		if (file_exists(''.$file.'')) {
	        $gbp="<img src='$file' align=left id=gambar width='300' height='225'>";
		}		
	if(!$a=mysql_query("select * from t_project where userid='".$row[userid]."'")) die ("Pengambilan gagal");
	$pro = mysql_num_rows($a);
	$baca=number_format($row[visit],0,".",",");
	$project .= "<table border=0 width='100%' border='0' cellspacing='4' cellpadding='2' align=center ><tr><td><center><b><font class='ver12'>$row[judul]</font></b></center>
	<font class='ver10'><img src='../images/arrow.gif'>$penulis <b>$nama</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tgl<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$dibaca</b> : $baca
	<br>------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>";
		//$link="";
		//if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../member/profil/gb$row[userid].jpg";
		$gb="<img src='../member/profil/kosong.jpg' width='60' height='75' id='gambar' align=left>";
		if (file_exists(''.$file.'')) {
	        $gb="<img src='$file' width='60' height='75' align=left id=gambar >";
		}		
	$project .= "$gbp$row[deskripsi]<br><br><a href='index.php?id=project&kode=$kd' class='ver10'>$kembali</a> </font><br></td></tr></table>";
    $project .="Komentar Opini :<br>";
	$sql="select * from t_project_com where id_project='".$row[id]."' order by id desc limit 0,10";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	while($row = mysql_fetch_array($alan)) {
		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama=$ro[nama];
		$mod="";
		$email=$ro[email];
	$lan= strlen($email);
	for($m=1;$m<$lan;$m++) {
		if (substr($email,$m,1)=="@") {break;}
	}
	$ymid=substr($email,0,$m);
		if ($ro[kategori]==2) $mod="<img src='../images/mod.gif' title='Coordinator' align=center>&nbsp;&nbsp;";
		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		//$link="";
		//if ($memberlog<>"") $link="index.php?id=lih_profil&kode=$row[userid]";
		$file = "../member/profil/gb$ro[userid].jpg";
		$gb="<img src='../member/profil/kosong.jpg' width='50' height='60' id='gambar' align=left >";
		if (file_exists(''.$file.'')) {
	        $gb="<img src='$file' width='50' height='60' align='left' id='gambar' >";
		}		
	$project .="<table width='97%' class='art-article' CELLPADDING=4 CELLSPACING=0 >
		<tr><td  bgcolor=#85A5B7 width=40%>$mod<font class='ver11p'><b>$nama</font></td><td align=right><font class='ver10'>Komentar</font></td></tr>
		<tr><td colspan=2 ><table width=100%><tr><td width=40% valign=top>$gb$tgl</td><td valign=top><font class='ver10'>$row[komentar]</font></td></tr></table></td></tr></table><br>";
	}
	$project .= "</center><font class='ver10'>&nbsp;&nbsp;&nbsp;<img src='../images/mod.gif' title='Kordinator' >&nbsp;&nbsp;Kordinator<br><br>&nbsp;&nbsp;&nbsp;<a href='index.php?id=project&kode=$kd' class='ver10'>$kembali</a></font><br>";

}
	
$project .= "</font><br>";

return $project;
}

function simkomentar() {
include "koneksi.php";
session_start();
$kode=$_POST['kode'];
$komentar=$_POST['komentar'];
$userid = $_SESSION['User']['userid'];

$code = $_POST['code'];
if (strtoupper($code) != $_SESSION['kodeRandom']) {
	die ("<body onload=\"alert('Kode keamanan salah');window.history.back()\">");
}

	$komentar = strip_tags($komentar);
	$komentar = nl2br($komentar);
	//$tgl = date("Y-m-d H:i:s");
    $query1 = "insert into t_project_com (komentar,id_project,userid,tanggal) values ('".mysql_real_escape_string($komentar)."','".mysql_real_escape_string($kode)."','$userid',NOW())";
   	$result = mysql_query($query1) or die("Pengambilan gagal artikel");

	$query1 = "update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."'";
   	$result = mysql_query($query1) or die("Pengambilan gagal pesan");
}

?>