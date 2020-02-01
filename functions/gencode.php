<?php

  $width = 180;
  $height = 60;
  $step = 5;
  $back_color = '#D2F0FF';
  $bar_color = '9999ff';
  $font_color = '000080';
  $font_path = "c:/windows/fonts/";
  $font_name = "cour.ttf";
  $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $pass_length = 10;
  $trans = 0;
  $angle = 10;
  $session_name = '1mAgeGenGeaDer1nf0';
  
  $hash = '';

  # do not access this variable
  $__code = '';

  function mkcolor($image,$color,$alpha = false){ #used for creating colors from html formatted colors
    $color = str_replace("#","",$color);
    $red = hexdec(substr($color,0,2));
    $green = hexdec(substr($color,2,2));
    $blue = hexdec(substr($color,4,2));
    if ($alpha!==false){
      $out = imagecolorallocatealpha($image, $red, $green, $blue, $alpha);
    }else{
      $out = ImageColorAllocate($image, $red, $green, $blue);
    }
    return($out);
  }
  
  function genpass(){   # generates the password (displayed on the image) it also creates the hash value
    $hash = '';
    $__code = '';
    for($p=0; $p < $pass_length; $p++) {
     $__code.=substr($charset,rand(1,strlen($charset)),1);
    }    
      if (strlen($__code)<10){
     $__code.=substr($charset,rand(1,strlen($charset)),1);
    }
    $hash = strrev(md5($__code));
  }
  
  function setpass($pass){ # sets the passwordand re-creates the hash (used for testing inputs)
    $__code = $pass;
    $hash = strrev(md5($__code));
  }
  
  function decodepass(){   # used for retrieving password passed via session
    gen_sess();
    setpass(base64_decode(strrev(unserialize($_SESSION['magic']))));
  }
  
  function encodepass(){   # used for encoding password passed into a session
    gen_sess();
    $_SESSION['magic'] = serialize(strrev(base64_encode($__code)));
  }
  
  function gen_sess(){     # starts the session used for encodepass and decodepass (both of these funstion call this function themselves)
    session_name($session_name);
    session_start();
  }

  function kill_sess(){    # kills the session created by gen_sess (this function calls gen_sess)
    gen_sess();
    session_destroy();
  }
    
  function testpass($hash,$pass){   # tests pass against hash and returns true or false
    return (strrev($hash)==md5($pass));
  }
        
  function image(){   # creates the actual image (you'll want to kill the script after this)
    //Create image and setup come colors            
    $image = imagecreatetruecolor($width, $height);
    $back=mkcolor($image,$back_color);
    $border=mkcolor($image,$bar_color);
    $codice=mkcolor($image,$font_color,$trans);
    imagefilledrectangle($image, 0, 0, $width-1, $height-1, $back);
    imagerectangle($image, 0, 0, $width-1, $height-1, $border);
       
    //Draw code over the image
    imagettftext($image, 20, $angle, (integer)($width/13), (integer)($height/1.15), $codice, $font_path.$font_name, $__code);
    
    //Making background grid
    for ($k=$step;$k<=$width;$k+=$step) {
     imageline($image, $k, 0, $k, $height, $border);
    }
    for ($s=$step;$s<=$height;$s+=$step) {
     imageline($image, 0, $s, $width, $s, $border);
    }

    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image); 
  }


?>