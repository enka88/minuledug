<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi daftar game dan menjalankan game dan menambahkan pesan yg disebarkan ke yg laen
// daftar game
function games() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<h3>Main Games Online</h3>";
$ket= $_GET['ket'];
if ($ket=='') $ket='Olahraga';
if ($ket=='Olahraga') $s1='selected';
elseif ($ket=='Balapan') $s2='selected';
elseif ($ket=='Petualangan') $s3='selected';
elseif ($ket=='Teka-teki') $s4='selected';
elseif ($ket=='Lain-lain') $s5='selected';

$cetak .="<div id='box-status' ><form action='' method=get name=game >Pilih kategori <select name='ket' onchange='document.location.href=\"?id=games&ket=\"+document.game.ket.value' >
<option value='Olahraga' $s1 >Olahraga</option><option value='Balapan' $s2 >Balapan</option>
<option value='Petualangan' $s3 >Petualangan</option><option value='Teka-teki' $s4 >Teka-teki</option>
<option value='Lain-lain' $s5 >Lain-lain</option></select></form></div><div id='box-game' >";
	
	$sql="select * from t_member_games where kategori='". mysql_real_escape_string($ket) ."' order by judul asc ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal games");
	if (mysql_num_rows($query)>0) {
	while($row=mysql_fetch_array($query)) {
		$cetak .="<div style='float:left;width:106px;' align=center ><a href='?id=maingames&kdgames=$row[idgames]' rel='tooltip' content='$row[ket]<br>Dimainkan $row[visit] kali.' ><img src='games/gm$row[idgames].jpg' id=gambar width='90' height='90' ></a><br>$row[judul]</div>";
	}
	}
	else $cetak .="Game $ket belum ada";

$cetak .="</div>";
$cetak .="<div id='box-gametop' align=center ><h3>Terpopuler</h3>";
$sql="select * from t_member_games order by visit desc limit 0,5 ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal games");
	while($row=mysql_fetch_array($query)) {
		$cetak .="<div align=center ><a href='?id=maingames&kdgames=$row[idgames]' rel='tooltip' content='$row[ket]' ><img src='games/gm$row[idgames].jpg' id=gambar width='60' height='60' ></a><br>$row[judul]</div>";
	}
$cetak .="</div>";
$cetak .="</div>";
return $cetak;
}
function maingames() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$level = $_SESSION['User']['ket'];
$kdgames = $_GET['kdgames'];
$js=$_GET['js'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .="<h3>Main Games Online </h3>";
$cetak .="<div style='float:left;border: 1px solid #6A849D;width:650px;overflow:auto;' align='center' >";
if ($level=='Siswa' ) {
	$cetak .="<center><h3 style='color:#FF0000' >Apakah Anda telah mengerjakan Tugas atau membaca Materi Ajar dibawah ini ?</h3></center>";
	$sql="select * from t_download order by id desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 materi");
	$i=1;
	$cetak .="<div style='border: 1px solid #6A849D;width:400px;text-align:left' ><table width=100% ><tr><td valign=top width='50%'><ul id='listmateri' >";
	while($row=mysql_fetch_array($query)) {
		if ($i==6) $cetak .="</ul></td><td width='50%' valign=top  ><ul id='listmateri' >";
		$cetak .="<li><a href='../html/guru.php?id=lihmateri&kode=$row[id]' target='_blank' title='Didownload $row[visit] kali' >$row[judul]</a></li>";
		$i++;
	}
	$cetak .='</ul></td></tr></table></div><br><a href="?id=maingames&kdgames='.$kdgames.'&js=lihat" id=button2 >Lanjutkan Main Game</a><br><br>';
}
else $js='lihat';

  if ($js=='lihat') {
	$sql="select * from t_member_games where idgames='". mysql_real_escape_string($kdgames) ."' ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal games");
	if($row=mysql_fetch_array($query)) {
		$cetak .="<b>$row[judul]<b><br>";
		if ($row[jenis]=='0') {
		$cetak .='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"  title="'.$row[judul].'"><param name="movie" value="games/'.$row[idgames].'.swf" width="600" height="450" >
          <param name="quality" value="high">
          <embed src="games/'.$row[idgames].'.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="600" height="450" ></embed>
		  </object>';
		}
		elseif ($row[jenis]=='1') {
			$cetak .="<iframe width='600' height='450'>".$row[link]."</iframe>";
		}
		$q=mysql_query("update t_member_games set visit=visit+1 where idgames='".mysql_real_escape_string($kdgames)."' ");  
	}
  }
$cetak .="</div></div>";
return $cetak;
}
?>