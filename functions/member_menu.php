<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}

function menu_sim() {
 include "koneksi.php";
$kate = $_SESSION['User']['ket'];
if ($kate=='Siswa') { //siswa
$menu ='<li><a href="#">Belajar<!--[if gte IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="user.php?id=datatugas" title="Lihat data penugasan">Tugas</a></li>
    <li><a href="user.php?id=siswabelajar" title="Lihat data pembelajaran">Belajar Online</a></li>
    <li><a href="user.php?id=siswatest" title="Tes Online">Tes Online</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>';
if ($cmssim == "ya"){ 
$menu .='<li><a href="#">S I M<!--[if gte IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="../html/siswa.php?id=dbsiswa" target="_blank" title="Lihat data siswa">Data Siswa</a></li>
	<li><a href="../html/guru.php?id=dbguru" target="_blank" title="Lihat data guru">Data Guru</a></li>
    <li><a href="user.php?id=datanilai" title="Lihat data nilai">Data Nilai</a></li>
    <li><a href="user.php?id=dataabsen" title="Lihat data absensi">Data Absensi</a></li>
    <li><a href="user.php?id=databk" title="Lihat data BP/BK">Data BP/BK</a></li>
    <li><a href="user.php?id=dataspp" title="Lihat data SPP/DSP">Data SPP/DSP</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>';
}
// end
}
elseif ($kate=='Orang Tua' && $cmssim=='ya' ) { //orangtua
$menu ='<li><a href="#">S I M<!--[if gte IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="../html/siswa.php?id=dbsiswa" target="_blank" title="Lihat data siswa">Data Siswa</a></li>
	<li><a href="../html/guru.php?id=dbguru" target="_blank" title="Lihat data guru">Data Guru</a></li>
    <li><a href="user.php?id=datanilai" title="Lihat data nilai">Data Nilai</a></li>
    <li><a href="user.php?id=dataabsen" title="Lihat data absensi">Data Absensi</a></li>
    <li><a href="user.php?id=databk" title="Lihat data BP/BK">Data BP/BK</a></li>
    <li><a href="user.php?id=dataspp" title="Lihat data SPP/DSP">Data SPP/DSP</a></li>
	<li><a href="user.php?id=datatugas" title="Lihat data penugasan">Tugas</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>';
}
elseif ($kate=='Guru') { //guru
$menu ='<li><a href="#">Belajar<!--[if gte IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="user.php?id=tamdownload"  title="Tambah Materi Ajar">Tambah Materi Ajar</a></li>
	<li><a href="user.php?id=tamsoal"  title="Tambah Kumpulan Soal">Tambah Materi Uji</a></li>
    <li><a href="user.php?id=gurutest" title="Lihat data tes online">Tes Online</a></li>
    <li><a href="user.php?id=gurutugas" title="Lihat data penugasan">Tugas</a></li>
    <li><a href="user.php?id=gurubelajar"  title="Pembelajaran online">Belajar Online</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>';
// list pembelajaran ditambah
if ($cmssim == "ya"){ 
$menu .='<li><a href="#">S I M<!--[if gte IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="../html/siswa.php?id=dbsiswa" target="_blank" title="Lihat data siswa">Data Siswa</a></li>
	<li><a href="../html/guru.php?id=dbguru" target="_blank" title="Lihat data guru">Data Guru</a></li>
    <li><a href="user.php?id=gurunilai" title="Lihat data nilai">Data Nilai</a></li>
    <li><a href="user.php?id=guruabsen" title="Lihat data absensi">Data Absensi</a></li>
    <li><a href="user.php?id=gurubk" title="Lihat data BP/BK">Data BP/BK</a></li>
    <li><a href="user.php?id=guruspp" title="Lihat data SPP/DSP">Data SPP/DSP</a></li>
    <li><a href="user.php?id=gurulap" title="Lihat data laporan">Data Laporan</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>';
}
// end

}
elseif ($kate=='Admin' && $cmssim=='ya') { //kepsek
$menu ='<li><a href="#">S I M<!--[if gte IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="../html/siswa.php?id=dbsiswa" target="_blank" title="Lihat data siswa">Data Siswa</a></li>
	<li><a href="../html/guru.php?id=dbguru" target="_blank" title="Lihat data guru">Data Guru</a></li>
    <li><a href="user.php?id=nilaiadmin" title="Lihat data nilai">Data Nilai</a></li>
    <li><a href="user.php?id=guruabsen" title="Lihat data absensi">Data Absensi</a></li>
     <li><a href="user.php?id=adminspp" title="Lihat data SPP/DSP">Data SPP/DSP</a></li>
    <li><a href="user.php?id=adminlap" title="Lihat data laporan">Data Laporan</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>';
}
else {
$menu = "";
}

return $menu;
}

?>