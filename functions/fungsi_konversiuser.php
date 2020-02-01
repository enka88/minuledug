<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function konversi_wali($userid) {
include "koneksi.php";

  $query=mysql_query("select kelas from t_kelas where nip='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[kelas];
return $konversi;
}

function konversi_kls($userid) {
include "koneksi.php";

  $query=mysql_query("select kelas from t_siswa where user_id='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[kelas];
return $konversi;
}

function konversi_id($userid) {
include "koneksi.php";

  $query=mysql_query("select nis from t_member where userid='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[nis];
return $konversi;
}

function konversi_nis($userid) {
include "koneksi.php";

  $query=mysql_query("select userid from t_member where nis='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row['userid'];
return $konversi;
}

function member_nama($userid) {
include "koneksi.php";
  $query=mysql_query("select nama,userid from t_member where userid='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[nama];
return $konversi;
}
function user_singkat($userid) {
include "koneksi.php";
  $query=mysql_query("select nama,userid from t_member where userid='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $nama = substr($row[nama],0,20);
  $nama = str_replace(".","",$nama);
  $nama = str_replace(" ","",$nama);
  $konversi = str_replace("'","",$nama);
  $konversi = str_replace(",","",$nama);
return $konversi;
}
function member_email($userid) {
include "koneksi.php";
  $query=mysql_query("select email,userid from t_member where userid='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[email];
return $konversi;
}
function teman_bukan($userid,$saya) {
include "koneksi.php";
  $query=mysql_query("select * from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and id_con='".mysql_real_escape_string($saya)."' and status='1'");
  if($row=mysql_fetch_array($query)) $konversi = "ya";
  else $konversi = "bukan";
return $konversi;
}
function nama_group($kdgroup) {
include "koneksi.php";
  $query=mysql_query("select nmgroup from t_membergroup where idgroup='".mysql_real_escape_string($kdgroup)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[nmgroup];
return $konversi;
}
function level_group($kdgroup,$userid) {
include "koneksi.php";
  $query=mysql_query("select kategori from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[kategori];
return $konversi;
}
function anggota_group($kdgroup,$userid) { // cek anggota bukan
include "koneksi.php";
  $query=mysql_query("select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' and status='1'");
  $jum =mysql_num_rows($query);
  if($jum > 0) $konversi = "ya";
  else $konversi = "bukan";
return $konversi;
}
function member_login($userid) {
include "koneksi.php";
  $query=mysql_query("select tgl_login,userid from t_member where userid='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[tgl_login];
return $konversi;
}

function konversi_nama($userid) {
include "koneksi.php";

  $query=mysql_query("select * from t_siswa where user_id='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[nama];
return $konversi;
}
function konversi_program($kelas) {
include "koneksi.php";

  $query=mysql_query("select * from t_kelas where kelas='".mysql_real_escape_string($kelas)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[program];
return $konversi;
}
function konversi_guru($userid) {
include "koneksi.php";

  $query=mysql_query("select * from t_staf where nip='".mysql_real_escape_string($userid)."'");
  $row=mysql_fetch_array($query);
  $konversi = $row[nama];
return $konversi;
}
function ataslogin($judul) {
$atas ='<div id="judul" style="width:630px;" >'.$judul.'</div>';
return $atas;
}
function ambilselisih($timestamp1, $timestamp2)
{
if( !is_integer($timestamp1) || ($timestamp1 <= 0) || !is_integer($timestamp2) || ($timestamp2 <= 0) )
{
//trigger_error('Nilai timestamp untuk selisih waktu tidak benar', E_USER_WARNING);
return false;
}
$ret = '';
$s_hari='';
$h_hari='';
$jam='';
$menit='';
$detik='';
$s_jam='';
$sel = ( $timestamp1 > $timestamp2 ) ? $timestamp1 - $timestamp2 : $timestamp2 - $timestamp1;

$h_hari = intval($sel / 86400);
$s_hari = $sel % 86400;
$setengah = intval($s_hari / 43200);

// hari  
$skrng = date("d M Y H:i",$timestamp2);     
$nmhari = date("D",$timestamp1);
$pukul = date("H:i",$timestamp1);
if ( $h_hari > 30 ) { $waktu= date("d M Y H:i",$timestamp1); }// "$hari lebih dari 30 hari maka dicetak tanggal tdk dihitung"; }
else {
	// apabila hari kurang dari 30 maka dihitung selisihnya per hari
	if ($h_hari <= 31 && $h_hari >= 29  ) { $waktu = "1 bulan yang lalu"; }
	else if ($h_hari > 7 ) { $waktu = "$h_hari hari yang lalu"; }
	else if ($h_hari == 7 ) { $waktu = "Seminggu yang lalu"; }
	else if ($h_hari < 7 && $h_hari >= 2 ) { 
		if ($nmhari=='Mon') $nmhari='Senin';
		else if ($nmhari=='Tue') $nmhari='Selasa';
		else if ($nmhari=='Wed') $nmhari='Rabu';
		else if ($nmhari=='Thu') $nmhari='Kamis';
		else if ($nmhari=='Fri') $nmhari='Jumat';
		else if ($nmhari=='Sat') $nmhari='Sabtu';
		else $nmhari='Minggu';
		$waktu = "$nmhari pukul ".$pukul;
	}
	else if ($h_hari=='1' ) { 
		if($setengah=='1') $waktu = "Kemarin Lusa jam ".$pukul; 
		else $waktu = "Kemarin jam ".$pukul; 
		}
	else if ($h_hari=='0' and $setengah=='1' ) { 
		$waktu = "Kemarin jam ".$pukul; 
	}
	else {
		// 41579 sisa detik
		// kurang dari 2 hari maka dihitung jamnya
		$jam = intval($s_hari / 3600);
		$s_jam = $s_hari % 3600;
		$menit = intval($s_jam / 60);
		$detik = $s_jam % 60;
		if( $jam > 1 ) { $waktu = "$jam jam $menit menit yang lalu "; }
		else {
			if( $menit < 45 && $menit > 20 ) { $waktu = "Setengah jam yang lalu"; }
			else if( $menit > 5 && $jam > 0 ) { $waktu = "$jam jam $menit menit yang lalu"; }
			else if( $menit <= 5 && $menit > 0 ) { $waktu = "$menit menit yang lalu"; }
			else {
				$waktu = "$detik detik yang lalu"; 
			}
		}
		//$waktu = "$jam jam $menit menit $detik detik , $s_jam";
	}
}
//$waktu = date("d M Y H:i",$timestamp1)." = h=".$h_hari." , s=".$s_hari." , jam=".$jam.", s_jam=".$s_jam;
return $waktu;
}

function filter_pesan($x_pesan) {
    $data_pesan = explode(" ",$x_pesan);
    $x_pesan="";
    for ($i=0; $i<count($data_pesan); $i++){
        if (strlen($data_pesan[$i]) >= 20) {
            $data_pesan[$i] = wordwrap($data_pesan[$i], 30, " ", TRUE);
         }
        $x_pesan .= $data_pesan[$i]." ";
    }
    $pesan=strip_tags("$x_pesan");
    return $pesan;
}

?>