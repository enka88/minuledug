<?php

	$sql="select * from t_prestasi ";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 prestasi");
	$n = mysql_num_rows($query);
	$n =rand(1,$n);
	$sql="select * from t_prestasi where id=$n";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 prestasi");
	$row=mysql_fetch_array($query);
	$file ="../images/prestasi/".$row[id].".jpg";
	$gbr="<img src='../images/noava.jpg' id='gambar' align=left >";
	if (file_exists($file)) {
	     $gbr="<img src='../images/prestasi/$row[id].jpg' width=180 height=150 hspace='5' border=1 align=left >";
	}
	$tag .="$gbr <b>$row[judul]</b><br>".$row[ket]."<br>:: <a href='siswa.php?id=prestasi' >Selengkapnya</a>";
?>