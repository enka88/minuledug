<?php
session_start();
define("CMSBalitbang",1);
include ("../lib/parsing.php");
require ("../lib/config.php");
include ("../functions/fungsi_utama.php");
include ("../functions/fungsi_gabung.php");
include ("../functions/fungsi_temp.php");
function isi($id) {
$userid = $_SESSION['User']['userid'];
$lang="in";

	switch ($id) {
		case "agenda":
		include ("../functions/fungsi_agenda.php");
			$isi .= atas_isi("Agenda");
			$isi .=agenda();
			$isi .= bawah_isi();
			break;
		case "album":
		include ("../functions/fungsi_galeri.php");
			$isi .= atas_isi("Galeri Photo");
			$isi .=album();
			$isi .= bawah_isi();
			break;
		case "galeri":
		include ("../functions/fungsi_galeri.php");
			$isi .= atas_isi("Galeri Photo");
			$isi .=galeri();
			$isi .= bawah_isi();
			break;
		case "link":
		include ("../functions/fungsi_link.php");
			$isi .= atas_isi("Link Web");
			$isi .=link_web();
			$isi .= bawah_isi();
			break;
		case "berita":
		include ("../functions/fungsi_berita.php");
			$isi .= atas_isi("Berita");
			$isi .=berita();
			$isi .= bawah_isi();
			break;
		case "simkom_berita":
		include ("../functions/fungsi_berita.php");
			$isi .= atas_isi("Berita");
			$isi .= simkom_berita();
			$isi .= berita();
			$isi .= bawah_isi();
			break;
		case "buku":
		    include ("../functions/fungsi_bukutamu.php");
			$isi .= atas_isi("Buku Tamu");
			$isi .=buku_tamu();
			$isi .= bawah_isi();
			break;
		case "sim_buku":
		    include ("../functions/fungsi_bukutamu.php");
			$isi .= atas_isi("Buku Tamu");
			$isi .=sim_buku();
			$isi .= bawah_isi();
			break;
		case "lih_buku":
		    include ("../functions/fungsi_bukutamu.php");
			$isi .= atas_isi("Buku Tamu");
			$isi .=lih_buku();
			$isi .= bawah_isi();
			break;
		case "lih_voting":
			$isi .= atas_isi("Jajak Pendapat");
			$isi .=lih_voting();
			$isi .= bawah_isi();
			break;
		case "tam_vot":
			$isi .= atas_isi("Konfirmasi");
			$isi .=tam_vot();
			$isi .= bawah_isi();
			break;
		case "artikel":
		include ("../functions/fungsi_artikel.php");
			$isi .= atas_isi("Artikel");
			$isi .=artikel();
			$isi .= bawah_isi();
			break;
		case "simkom_artikel":
		include ("../functions/fungsi_artikel.php");
			$isi .= atas_isi("Artikel");
			$isi .= simkom_artikel();
			$isi .= artikel();
			$isi .= bawah_isi();
			break;
		case "cari":
		include ("../functions/fungsi_cari.php");
			$isi .= atas_isi("Pencarian");
			$isi .= search();
			$isi .= bawah_isi();
			break;
		case "profil":
		include ("../functions/fungsi_profil.php");
			$isi .=profile();
			break;
		case "info":
		include ("../functions/fungsi_info.php");
			$isi .= atas_isi("Info Sekolah");
			$isi .=info();
			$isi .= bawah_isi();
			break;
		case "project":
		include ("../functions/fungsi_opini.php");
			$isi .= atas_isi("Opini");
			$isi .=project();
			$isi .= bawah_isi();
			break;
		case "projectcom":		
		include ("../functions/fungsi_opini.php");
		include ("../functions/member_status.php");
			if ($userid<>"")	{ $isi .= atas_isi("Opini");$isi .=project_com();$isi .= bawah_isi();}
			else { $isi .= atas_isi("Konfirmasi"); $isi .=user_gagal();$isi .= bawah_isi(); }
			break;
		case "simkomentar":
		include ("../functions/fungsi_opini.php");	
		include ("../functions/member_status.php");		
			if ($userid<>"")	{
				$isi .=simkomentar();
				$isi .=project();
			}
			else { $isi .= atas_isi("Konfirmasi"); $isi .=user_gagal();$isi .= bawah_isi(); }
			break;
		case "fasil":	
		include ("../functions/fungsi_depan.php");	
			$isi .=depan('fitur');
			break;
		case "webmaster":
		include ("../functions/fungsi_depan.php");
			$isi .= atas_isi("Web Master");
			$isi .=webmaster();
			$isi .= bawah_isi();
			break;
		case "site":
		include ("../functions/fungsi_depan.php");
			$isi .= atas_isi("Peta Situs");
			$isi .=peta();
			$isi .= bawah_isi();
			break;
		case "kunjungblog":
		include ("../functions/fungsi_blog.php");
		$isi .= atas_isi("Konfirmasi");
			$isi .=kunjungblog();
			$isi .= bawah_isi();
			break;
		case "dafblog":
		include ("../functions/fungsi_blog.php");
		$isi .= atas_isi("Daftar Blog");
			$isi .= dafblog();
			$isi .= bawah_isi();
			break;	                     	
		default:
		include ("../functions/fungsi_depan.php");
			$isi .=depan('depan');
			break;
		}
	
		return $isi;
}
	include("../functions/koneksi.php");
  	$sql2="select * from t_pos_menu where posisi='L' and kategori='depan' and sembunyi='t' order by urut";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu");
	while ($rows=mysql_fetch_array($query2)) {
		$sql1="select * from t_temp_menu where idtemp='".$rows['idtemp']."'";
		if(!$query1=mysql_query($sql1)) die ("Pengambilan gagal1 menu posisi kiri");
		if($r=mysql_fetch_array($query1)) {
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
	}
//if ($_SESSION['tempbaru']<>'') $alan = "../temp/".$_SESSION['tempbaru']."/";
$id = $_GET['id'];
if ($id=='')  $id = $_POST['id'];

$a["nmsekolah"]=$nmsekolah;
$a["nmweb"]=$webhost;
$a["isi"]=isi($id);
$a["atas"]=banneratas();
$a["menu"]=menu($id);
$a["kiri"]=$kiri;
$a["path"]=$alan;
$a["bahasa"]=multibahasa();
Parse($alan."index.tpl.htm",$a);

?>