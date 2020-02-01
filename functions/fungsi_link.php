<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

function link_web() {
include "koneksi.php";
$kode=$_GET['kode'];
if (empty($kode)) $kode="1";

	$link_web .= "<center>| ";
if($kode=='') $kode='1';
	$query = "SELECT * FROM t_kategori where jenis='0'";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[id_kategori]) $link_web .="<b><a href='index.php?id=link&kode=$k[id_kategori]' >$k[kategori]</a></b> | ";
		else $link_web .="<a href='index.php?id=link&kode=$k[id_kategori]' >$k[kategori]</a> | ";
	}
  $link_web .="</center><br>";
	$query="select * from  t_link where jenis='".mysql_real_escape_string($kode)."' order by id";
	$alan=mysql_query($query) or die ("query gagal");
	while($row=mysql_fetch_array($alan)) {
	$link_web .= "<img src='../images/home.gif' > <a href='http://$row[alamat]' class='ver10' target='_blank'>$row[alamat]</a><br>$row[ket]
	<hr style='border: dashed 1px;'>";
	}
$link_web .= "Bergabung lah bersama kami, <br>
Kirim alamat website anda ke email : <a href='mailto:$webmail' >$webmail</a>";
	return $link_web;
}

?>