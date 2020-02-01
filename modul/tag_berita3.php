<?php
$n=0;

  	$sql="select * from t_news order by id desc limit 0,8";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 berita $id");
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
		$file = "../images/berita/gb$row[id].jpg";
		$gb="";
		if (file_exists(''.$file.'')) {
	        $gb='<img src="'.$file.'" width=100 height=75 border=1 align=left hspace=5 id=gambar >';
		}
		if ($n<3) {
		$tag .="$gb<div class='ari12'><b><a href='index.php?id=berita&kode=$row[id]' >$row[subject]</a></b></div><p>
			$isi</p><hr color='#D3D3D3' >";
		$n++;
		}
		else {
		if ($n==3) $tag.="<ul>";
		elseif ($n==8) $tag.="</ul>";
		$judul  =  substr($row[subject], 0, 40);
		$isib = substr($row[subject],40,50);
		$isib = strtok($isib," ");
		$judul .= $isib."...";
		$judul =  stripslashes($judul);
		$tag .="<li>&nbsp;<a href='index.php?id=berita&kode=$row[id]'  >
		$judul</a></li>";
		$n++;
		}
	}
	$tag.="</ul>";

?>