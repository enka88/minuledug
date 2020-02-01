<?php
session_start();
define("CMSBalitbang",1);
include ("../lib/parsing.php");
require ("../lib/config.php");
include ("../functions/fungsi_gabung.php");
include ("../functions/fungsi_depan.php");
include ("../functions/fungsi_alumni.php");
include ("../functions/fungsi_temp.php");
function isi($id) {

global $memberlog;
	switch ($id) {
		case "lihat":
			$isi .= atas_isi("Profil Alumni");
			$isi .=lihat();
			$isi .= bawah_isi();
			break;
		case "data":
			$isi .= atas_isi("Direktori Alumni");
			$isi .=data();
			$isi .= bawah_isi();
			break;
		case "info":
			$isi .= atas_isi("Info Alumni");
			$isi .=info($hal);
			$isi .= bawah_isi();
			break;
		case "profil":
			include ("../functions/fungsi_profil.php");
			$isi .=profile();
			break;
		default:
			$isi .=depan('alumni');
			break;
		}
		return $isi;
}
include("../functions/koneksi.php");
  	$sql2="select * from t_pos_menu where posisi='L' and kategori='alumni' and sembunyi='t' order by urut";
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
$a["menu"]=menu("5");
$a["kiri"]=$kiri;
$a["path"]=$alan;
$a["bahasa"]=multibahasa();
Parse($alan."index.tpl.htm",$a);

?>