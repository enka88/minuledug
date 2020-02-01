<?php
	$tag .='<ul >';
	$sql="select * from t_silabus order by visit desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 materi");
	while($row=mysql_fetch_array($query)) {
		$tag .="<li><a href='guru.php?id=silabus&kode=$row[id]' itle='Didownload $row[visit] kali' >$row[pelajaran] - $row[kelas]</a></li>";
	}
	$tag .="</ul>";
	$tag .="<div style='text-align:right' >:: <a href='guru.php?id=silabus' >Selengkapnya</a>&nbsp;&nbsp;</div>";
	
?>