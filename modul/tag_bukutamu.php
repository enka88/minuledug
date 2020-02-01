<?php
$tag .='<table width="97%" border="0" align=center >';
		$query = "SELECT * FROM t_buku order by id_buku desc limit 0,5";
    	$result = mysql_query($query) or die("Query failed banner");
    	while ($row = mysql_fetch_array($result)) {
		$isi = strip_tags($row[komentar]);
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
		$tag .="<tr><td valign=top width=10><img src='../images/mn-panah3.gif' ></td><td>$isi ...</td></tr>";
		}
	$tag .="</table><div class='more'>::&nbsp;<a href='index.php?id=lih_buku' >Selengkapnya</a>&nbsp;&nbsp;&nbsp;&nbsp;</div></center>";
?>