<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
/********************************************************** profil *********************************/
function profile() {
include "koneksi.php";
$kode=$_POST['kode'];
if ($kode=='') $kode=$_GET['kode'];

$sql="select * from t_profil where id='".mysql_real_escape_string($kode)."'";
if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
$row=mysql_fetch_array($query);
$profile .= atas_isi($row[judul]);
$profile .= "<table width=97% id='table-no' ><tr><td>$row[isi]</td></tr></table>";
$profile .= bawah_isi();
return $profile;
}


?>