<?php
 $n=0;
  	$sql="select * from t_artikel order by id desc limit 0,5";
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
		if ($n<3) {
		$tag .="<b>$row[judul]</b><br>
			$isi<br><div style='text-align:right'><a href='index.php?id=artikel&kode=$row[id]' >
			:: Selengkapnya</a>&nbsp;&nbsp;</div>
			<hr color='#D3D3D3' >";
			$n++;
		}
	 	else {
		if ($n==3) $tag.="<ul>";
		elseif ($n==5) $tag.="</ul>";
		$judul  =  substr($row[judul], 0, 40);
		$isib = substr($row[judul],40,50);
		$isib = strtok($isib," ");
		$judul .= $isib."...";
		$judul =  stripslashes($judul);
		$tag .="<li>&nbsp;<a href='index.php?id=artikel&kode=$row[id]' >
		$judul</a></li>";
		$n++;
		}
	}
?>