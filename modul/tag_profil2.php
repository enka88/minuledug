<?php
// modul lokasi
	$sql="select * from t_profil where id='10'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);
	$tag .= $row[isi];
?>