<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}  

$tag .='<link rel="stylesheet" type="text/css" href="'.$nmhost.'plugins/statmember/ticker.css">
<script type="text/javascript" src="'.$nmhost.'plugins/statmember/newsticker.js"></script>';

$tag .="<ul id='listticker'>";
	    $sekilas=mysql_query("SELECT
		t_member.userid,
		t_member.nama,
		t_memberstatus.pesan,
		t_member.ket,
		t_memberstatus.tanggal
		FROM
		t_member
		Inner Join t_memberstatus ON t_member.userid = t_memberstatus.userid
		ORDER BY
		t_memberstatus.tanggal DESC LIMIT 0,20");

            while($s=mysql_fetch_array($sekilas)){

			$isi = strip_tags($s[pesan]);
			$max = 150; // maximal 300 karakter 
			$min = 100; // minimal 150 karakter 
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
			$file ="../member/profil/gb".$s[userid].".jpg";
            //$tag .= $file;
            $gbr="<img src='".$nmhost."member/profil/kosong.jpg' width='50' height='50' >";
			if (file_exists($file)) {
			     $gbr="<img src='".$file."' width='50' height='50' />";
			}
				$tag .="<li>$gbr<span class='news-text'><strong>$s[nama] ($s[ket])</strong><br/>$s[tanggal]<br/><i>$isi</i></span></li>";
			}
$tag .="</ul>";
?>