<?php
$tag .="<table align=center width=100% ><tr><td>";
	$sql="select * from t_profil where id='3'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 profil");
	$row=mysql_fetch_array($query);
	$isi = strip_tags($row[isi]);
	$isi  =  substr($isi, 0, 600);
	$isib = substr($row[isi],600,620);
	$isib = strtok($isib," ");
	$isi .= $isib."...";
	$isi =  stripslashes($isi);
	$tag .= $isi;
        $tag .= "<br><img src='../images/mn-panah3.gif' align='top' border=0><a href='profil.php?id=profil&kode=3' class='ver10' >Selengkapnya</a>
		</td/tr></table>";
?>