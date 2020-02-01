<?php
  	$sql="select * from t_artikel order by id desc limit 0,4";
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
		$tag .="$gb<b>$row[judul]</b><br>
			$isi<br><div style='text-align:right'><a href='index.php?id=artikel&kode=$row[id]' >
			:: Selengkapnya</a>&nbsp;&nbsp;</div>
			<hr color='#D3D3D3' ><br>";
	}
?>