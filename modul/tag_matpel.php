<?php
$tag .='<ul>';
		$sql="select * from t_download order by id desc limit 0,5";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 berita $id");
	while ($row=mysql_fetch_array($query)) {
			$judul=  $row[judul];
			$judul =  stripslashes($judul);
			$h = strval(substr($row[tanggal],0,2));
			$b = strval(substr($row[tanggal],3,2));
			$h1 = strval(date("d"));
			$b1 = strval(date("m"));
			if ($h > $h1) { $h2 = ($h1+30) - $h;$b1= $b1 -1;}
			else { $h2 = $h1 - $h; }
			if ($b1<>$b) $h2=$h2+10; 
			$new ="";
			if ($h2 < 10) $new ="<img src='../images/baru.gif'>";
		$tag .="<li><a href='guru.php?id=lihmateri&kode=$row[id]' >
		$judul</a> $new </li>";
	}		
	$tag.='<ul><div style="text-align:right" >::&nbsp;<a href="guru.php?id=materi"  >Selengkapnya</a></div>';
?>