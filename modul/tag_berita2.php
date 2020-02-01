<?php
 $tag .="<div style='padding:0px 5px 5px 5px;'>";
  	$sql="select * from t_news order by id desc limit 0,3";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 artikel $id");
	while ($row=mysql_fetch_array($query)) {
 		$isi = strip_tags($row[isi]);
		$max = 300; // maximal 300 karakter 
		$min = 200; // minimal 150 karakter 
		if( strlen( $isi ) > $max ) { 
			$pecah = substr( $isi, 0, $max ); 
			$akhirParagrap = strrpos( $pecah, "\n" ); 
			$akhirKalimat = strrpos( $pecah, '.' ); 
			$akhirSubKalimat = strrpos( $pecah, ',' ); 
			$spasiTerakhir = strrpos( $pecah, ' ' ); 
 
			if( $akhirParagrap >= $min ) { 
    			$potong = $akhirParagrap; 
			}elseif( $akhirKalimat >= $min ) { 
   				$potong = $akhirKalimat; 
			}elseif( $akhirSubKalimat >= $min ) { 
   				$potong = $akhirSubKalimat; 
			}else { 
   				$potong = $spasiTerakhir; 
			} 
 
			$isi = substr( $isi, 0, $potong+1 )."..."; 
 		}
		$tag .="$gb<b>$row[subject]</b><br>
			$isi<br><div class='more' >:: <a href='index.php?id=berita&kode=$row[id]' >
			Selengkapnya</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<hr id='garis' >";
	}
	$tag .="</div>";
?>