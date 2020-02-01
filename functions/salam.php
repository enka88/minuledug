<?php
session_start();
if (!isset($_SESSION['Admin'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}

if ($pengirim == '' ) {
	die ("<body onload=\"alert('Pengirim harus diisi, jangan kosong');window.history.back()\">");
}
elseif ($pesan == '' ) {
	die ("<body onload=\"alert('Alamat harus diisi, jangan kosong');window.history.back()\">");
}
else {
include "koneksi.php";

$pengirim = $_GET['pengirim'];
if ($pengirim=='') $pengirim =$_POST['pengirim'];
$pesan = $_GET['pesan'];
if ($pesan=='') $pesan = $_POST['pesan'];

 $postdate = date("d/m/Y");
  $posttime = date("H:i:s");
	$waktu ="$postdate $posttime";
  if ($id=='alumni') {
      $query = "insert into t_pesan_alum (pengirim,pesan,waktu) values ('".mysql_real_escape_string($pengirim)."','".mysql_real_escape_string($pesan)."','$waktu')";
    $result = mysql_query($query) or die("Query failed");
  // header("Location:../html/alumni.php");
    echo "<script>document.location.href = '../html/alumni.php';</script>";
  }
  else {
    $query = "insert into t_pesan (pengirim,pesan,waktu) values ('".mysql_real_escape_string($pengirim)."','".mysql_real_escape_string($pesan)."','$waktu')";
    $result = mysql_query($query) or die("Query failed");
  // header("Location:../html/index.php");
   echo "<script>document.location.href = '../html/index.php';</script>";
   }
}

?>