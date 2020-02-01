<?php
include "koneksi.php";
$id = $_GET['id'];
if($id=='') $id=$_POST['id'];

	$sql="select * from t_banner where id='".mysql_real_escape_string($id)."'";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	$row=mysql_fetch_array($alan);
	$visits = $row[visits] + 1;
    $query = "UPDATE t_banner SET visits='$visits' WHERE id='".mysql_real_escape_string($id)."'";
    $result = mysql_query($query) or die("Query failed");
//	header("location:$row[url]");
     echo "<script>document.location.href = '".$row['url']."';</script>";
?>