<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

$tag = '<link type="text/css" rel="stylesheet" href="../plugins/news/base.css"/>
<link type="text/css" rel="stylesheet" href="../plugins/news/theme.css"/>
<script src="../js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.accessible-news-slider.js"></script>

 <script type="text/javascript">
// when the DOM is ready, convert the feed anchors into feed content
jQuery(document).ready(function() {

 jQuery("#newsslider1").accessNews(
 {
 title:"",
 subtitle:"",

 });

});
</script>';

$tag .='<div style="width:445px;margin:auto; background-color:#FFFFFF;">
<ul id="newsslider1">';
  	$sql="select * from t_news order by id desc limit 0,8";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 berita $id");
	while ($row=mysql_fetch_array($query)) {
	    $isi = strip_tags($row[isi]);
		$max = 200; // maximal 300 karakter 
		$min = 150; // minimal 150 karakter 
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
		$nfile = "../images/berita/gb$row[id].jpg";
		$gb="";
		if (file_exists($nfile)) {
	        $gb='<img src="'.$nfile.'" width="82" height="50" id="gbr"  >';
		}
        $tag .= '<li>
			<a href="index.php?id=berita&kode='.$row['id'].'">'.$gb.'</a>
			<p class="title"><a href="index.php?id=berita&kode='.$row['id'].'">'.$row['subject'].'</a></p>
			<p class="description">'.$isi.'<br /><a href="index.php?id=berita&kode='.$row['id'].'"> &raquo; selengkapnya</a></p>
		      </li>';
	}
$tag .="</ul></div>";
?>