<?php

	$tag .="<ul>";
		$sql="select * from t_download order by visit desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 materi");
	while($row=mysql_fetch_array($query)) {
	$tag .="<li><a href='guru.php?id=lihmateri&kode=$row[id]' title='Didownload $row[visit] kali' >$row[judul]</a></li>";
	}
	$tag.='<ul><div style="text-align:right" >::&nbsp;<a href="guru.php?id=materi" >Selengkapnya</a></div>';
?>