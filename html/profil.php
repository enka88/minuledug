<?php
session_start();
define("CMSBalitbang",1);
include ("../lib/parsing.php");
require ("../lib/config.php");
include ("../functions/fungsi_gabung.php");
include ("../functions/fungsi_profil.php");
include ("../functions/fungsi_depan.php");
include ("../functions/fungsi_temp.php");
function isi($id) {
global $memberlog;
	switch ($id) {
		case "profil":
			$isi .=profile();
			break;
		default:
			$isi .=depan('profil');
			break;
		}
		return $isi;
}
	
include("../functions/koneksi.php");
  	$sql2="select * from t_pos_menu where posisi='L' and kategori='profil' and sembunyi='t' order by urut";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu");
	while ($rows=mysql_fetch_array($query2)) {
		$sql1="select * from t_temp_menu where idtemp='".$rows['idtemp']."'";
		if(!$query1=mysql_query($sql1)) die ("Pengambilan gagal1 menu posisi kiri");
		$r=mysql_fetch_array($query1);
		$kiri .= $r['temp_atas']; //atas
		//tag tengah
		$kiri .= $rows['menu'];
		$kiri .= $r['temp_tengah']; //tengah
	// tag awal source isi
		$fungsi ="../modul/tag_".$rows['fungsi'].".php";
		include ("$fungsi");
		$kiri.= $tag;
		// bawah
		$kiri.= $r['temp_bawah']; //bawah
		$tag="";
		
	}
//if ($_SESSION['tempbaru']<>'') $alan = "../temp/".$_SESSION['tempbaru']."/";
$id = $_GET['id'];
if ($id=='')  $id = $_POST['id'];

$a["nmsekolah"]=$nmsekolah;
$a["nmweb"]=$webhost;
$a["isi"]=isi($id);
$a["atas"]=banneratas();
$a["menu"]=menu("2");
$a["kiri"]=$kiri;
$a["path"]=$alan;
$a["bahasa"]=multibahasa();
Parse($alan."index.tpl.htm",$a);

?>