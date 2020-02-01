<?php 
session_start();
function strrand($length){
$str = "";
while(strlen($str)<$length){
$random=rand(48,122);
if( ($random>47 && $random<58)  ){ 
$str.=chr($random); } }
return $str; 
}

$text = $_SESSION['string']=strrand(6);
header("Content-type: image/png");
$im = imagecreatefrompng("latar.png");
$white = imagecolorallocate($im, 255, 255, 255);
$font = '04B_03B.ttf';
$fontsize=20;
imagettftext($im,  $fontsize, 0, 20, 30, $white, $font, $text );
imagepng($im);
imagedestroy($im); 

?> 