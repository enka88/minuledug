<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}


//********************************* menu *************************//
function menu($id) {
include "koneksi.php";

if ($id=='') $id=1;

if ($id=='1' or $id=='2' or $id=='3' or $id=='4' or $id=='5') $ak=$id;
else $ak='6';

$menu ='<div class="l"></div><div class="r"></div>
		<ul class="art-menu">';

$sql="select id,judul,link,urut,target from t_profil where parent='0' and urut<>'0' and hide='0' order by urut";
if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 menu");
while($row=mysql_fetch_array($query)) {
	$menu .='<li>';
 		if ($row['link']=='0' or trim($row[link])=='') $link = "index.php?id=profil&kode=$row[id]&profil=$row[judul]";
		else $link=$row[link];
           
	if ($ak==$row[id]) $menu .='<a href="'.$link.'" target="'.$row['target'].'" class="active" ><span class="l"></span><span class="r"></span><span class="t">'.$row[judul].'</span></a>';
	else $menu .='<a href="'.$link.'" target="'.$row['target'].'" ><span class="l"></span><span class="r"></span><span class="t">'.$row[judul].'</span></a>';
	
    $menu .='<ul>';
	$sql2="select id,judul,link,target from t_profil where parent='$row[id]' and hide='0'  order by urut";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu 2");
	while($r=mysql_fetch_array($query2)) {
		if ($r[link]=='0' ) $link = $row['link']."?id=profil&kode=$r[id]&profil=$r[judul]";
        elseif ($r[link]=='') $link = "?id=profil&kode=$r[id]&profil=$r[judul]";
		else $link=$r[link];
		$menu .='<li>';
		$menu .="<a href='$link' target='$r[target]' >$r[judul]</a>";
		$menu .='<ul>';
		$sql3="select id,judul,link,target from t_profil where parent='$r[id]' and hide='0'  order by urut";
		if(!$query3=mysql_query($sql3)) die ("Pengambilan gagal1 submenu 3");
		while($r3=mysql_fetch_array($query3)) {
			if ($r3[link]=='0') $link = $row['link']."?id=profil&kode=$r3[id]&profil=$r3[judul]";
            elseif ($r3[link]=='') $link = "?id=profil&kode=$r3[id]&profil=$r3[judul]";
			else $link=$r3[link];
			$menu .="<li><a href='$link' target='$r3[target]' >$r3[judul]</a>
			<ul>";
			$sql4="select id,judul,link,target  from t_profil where parent='$r3[id]' and hide='0'  order by urut";
			if(!$query4=mysql_query($sql4)) die ("Pengambilan gagal1 submenu 4");
			while($r4=mysql_fetch_array($query4)) {
				if ($r4[link]=='0' ) $link = $row['link']."?id=profil&kode=$r4[id]&profil=$r4[judul]";
                elseif ($r4[link]=='') $link = "?id=profil&kode=$r4[id]&profil=$r4[judul]";
				else $link=$r3[link];
				$menu .="<li><a href='$link' target='$r4[target]' >$r4[judul]</a></li>";
			}
			$menu .='</ul></li>';
		}
		$menu .='</ul>';
		$menu .='</li>';
		
	}
	$menu .='</ul>';
	$menu .='</li>';
}
//$menu .= "<li><a href='a' target='a' ><span class='l'></span><span class='r'></span><span class='t'>".multibahasa()."</span></a></li>";

$menu .='</ul>';
return $menu;
}

function banneratas() {
include "koneksi.php";
	$sql="select id from t_gambaratas ";
	$i=1;
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 gambar atas");
	while($rows = mysql_fetch_array($query)) {
		$photo[$i]=$rows[id];
		$i++;
	}
	$jum = count($photo);
	$n = rand(1,$jum);
	
	$sql="select * from t_gambaratas where id='".mysql_real_escape_string($photo[$n])."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 gambar atas");
	if ($row=mysql_fetch_array($query)) {
		if ($row[jenis]=='swf') {
		$atas ='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="974" height="150" title="'.$row[judul].'">
          <param name="movie" value="../images/banneratas/gbanner'.$row[id].'.swf">
          <param name="quality" value="high">
          <param name="wmode" value="transparent">
          <embed src="../images/banneratas/gbanner'.$row[id].'.swf" wmode="transparent" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="974" height="150"></embed>
		  </object>';
		}
		else {
		$atas = "<img src='../images/banneratas/gbanner$row[id].$row[jenis]' title='$row[judul]' width='974' height='150'>";}
	}
	
return $atas;
}

function multibahasa() {
include "../lib/config.php";
if (strtolower($multibahasa)=='ya' ) {
	
$data .='<div id="translate-this" style="margin-top:10px;margin-left:6px;" ><a href="http://translateth.is/" class="translate-this-button" >Multi Bahasa</a></div>';

$data .='<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="../temp/translate-this.js"></script>';

$data .= "<script type=\"text/javascript\">
TranslateThis({
    GA : false, // Google Analytics tracking
    //cookie : 'tt-lang', // Name of the cookie - set to 0 to disable
    
    panelText : 'Terjemahkan ke :', // Panel header text
    moreText : '36 Bahasa lainnya »', // More link text
    busyText : 'Tunggu sedang diterjemahkan...',
    cancelText : 'Batal',
    doneText : 'Translated by the', // Completion message text
    undoText : 'Undo »', // Text for untranslate link
    
    fromLang : 'id', // Native language of your site
    
    ddLangs : [ // Languages in the dropdown
        'en',
        'de',
        'zh-CN',
        'id',
        'ja',
        'es'
    ],
    
    noBtn : false, //whether to disable the button styling
    btnImg : '../images/tt-btn1.png',
    btnWidth : 180,
    btnHeight : 18,
    
    noImg : false, // whether to disable flag imagery
    imgHeight : 12, // height of flag icons
    imgWidth : 12, // width of flag icons
    bgImg : 'http://x.translateth.is/tt-sprite.png',
    
    maxLength : 180, // maxLength of strings passed to Google
    reparse : true // whether to reparse the DOM for each translation
    
});
</script>";

}
else $data ="";

    return $data;
}


?>