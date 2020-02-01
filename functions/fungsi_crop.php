<?php

// sumber ilmu website com
function cropImage($nw, $nh, $source, $stype, $dest) {
    $size = getimagesize($source); // ukuran gambar
	$w = $size[0];
	$h = $size[1];
	switch($stype) { // format gambar
		case 'gif':
			$simg = imagecreatefromgif($source);
			break;
		case 'jpg':
			$simg = imagecreatefromjpeg($source);
			break;
		case 'png':
			$simg = imagecreatefrompng($source);
			break;
		}
	$dimg = imagecreatetruecolor($nw, $nh); // menciptakan image baru
	$wm = $w/$nw;
	$hm = $h/$nh;
	$h_height = $nh/2;
	$w_height = $nw/2;
	if($w > $h) {
		$adjusted_width = $w / $hm;
		$half_width = $adjusted_width / 2;
		$int_width = $half_width - $w_height;
		imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
	} 
	elseif(($w < $h) || ($w == $h)) {
		$adjusted_height = $h / $wm;
		$half_height = $adjusted_height / 2;
		$int_height = $half_height - $h_height;
		imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
	} 
	else {
		imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
	}
	imagejpeg($dimg,$dest,100);
}

function potongimageprofil($source, $stype, $dest,$nw,$nh, $x1,$y1) {

	//$w = $asli_w;
	//$h = $asli_h;
	switch($stype) { // format gambar
		case 'gif':
			$simg = imagecreatefromgif($source);
			break;
		case 'jpg':
			$simg = imagecreatefromjpeg($source);
			break;
		case 'png':
			$simg = imagecreatefrompng($source);
			break;
		}
	$dimg = imagecreatetruecolor($nw, $nh); // menciptakan image baru
	imagecopyresampled($dimg,$simg,0,0,$x1,$y1,$nw,$nh,$nw,$nh);
	imagejpeg($dimg,$dest,100);
}

function crop_image($src, $dest = "", $width = 0, $height = 0, $valign = "") {
	if(!file_exists($src)) {
		return false;
	}
	if(!in_array(array_pop(explode(".", strtolower($src))), array("jpg", "png", "gif"))) {
		return false;
	}
	if(!in_array(array_pop(explode(".", strtolower($dest))), array("jpg", "png", "gif")) & !empty($dest)) {
		return false;
	}
	$y = 0;
	if(!in_array($valign, array("top", "middle", "bottom")) || empty($valign)) {
		$valign = "top";
	}
	$fp   = fopen($src, "rb");
	$data = fread($fp, filesize($src) + 1);
	fclose($fp);
 	$tmp    = imagecreatefromstring($data);
 	$w      = imagesx($tmp);
 	$h      = imagesy($tmp);
 	$wd     = $width;
 	$hd     = $height;
 	$width  = !empty($width) ? $width : $w;
 	$height = !empty($height) ? $height : $h;
 	if($wd > 0 && $w <> $width) {
		$height = round(($h * $width) / $w);
		if($hd > 0 && $height > $hd) {
			$hs     = $h;
			$height = $hd;
			$h      = round(($hd * $w) / $width);
			switch($valign) {
			case "top"    : $y = 0; break;
			case "middle" : $y = round(($hs - $h) / 2);	break;
			case "bottom" : $y = $hs - $h; break;
			}
		}
	}
	if($hd > 0 && $h <> $height) {
		$width = round(($w * $height) / $h);
		if($wd > 0 && $witdh > $wd) {
			$width = $wd;
			$w     = round(($wd * $h) / $height);
		}
	}
	$im = imagecreatetruecolor($width, $height);
	imagecopyresized($im, $tmp, 0, 0, 0, $y, $width, $height, $w, $h);
	switch(array_pop(explode(".", strtolower($dest)))) {
	case "png" :
		if(empty($dest)) { header("Content-Type: image/png"); }
		@imagepng($im, !empty($dest) ? $dest : null);
		break;
	case "gif" :
		if(empty($dest)) { header("Content-Type: image/gif"); }
		@imagegif($im, !empty($dest) ? $dest : null);
		break;
	default :
		if(empty($dest)) { header("Content-Type: image/jpeg");}
		@imagejpeg($im, !empty($dest) ? $dest : null);
		break;
	}
}

?>