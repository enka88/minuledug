<?php

/**
 * @author alanrm82
 * @copyright 2011
 */
function bacafb($trends_url='') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $trends_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $curlout = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($curlout, true);
    return $response;
}

function CekFacebook() {
    include "../lib/config.php";
    
    if (empty($appid) or empty($secret)) {
       return false; 
    }
    $connect = @fsockopen("www.facebook.com", 80, $errno, $errstr, 5);
    if(!$connect) {
        return false;
    }
    fclose($connect);    
    require ("facebook.php");
    $facebook = new Facebook(array(
      'appId' => $appid,
      'secret' => $secret,
      'cookie' => true,
    )); 
    return $facebook;
}

function LoginFacebook($facebook) {

    $session = $facebook->getSession();

    if(!empty($session)) {
        
    	$sesi = $_GET['session'];
        $cari = array("{","}",'\"','\"');
        $sesi = str_replace($cari,"",$sesi);
        $sesi = explode(",",$sesi);
        
        $sesikey = explode(":",$sesi[0]);
        $uid     = explode(":",$sesi[1]);
        $expires = explode(":",$sesi[2]);
        $secret  = explode(":",$sesi[3]);
        $domain  = explode(":",$sesi[4]);
        $token   = explode(":",$sesi[5]);
        $sig     = explode(":",$sesi[6]);
        
        $data['sesikey'] = $sesikey['1'];
        $data['uid']     = $uid['1'];
        $data['expires'] = $expires['1'];
        $data['secret']  = $secret['1'];
        $data['domain']  = $domain['1'];
        $data['token']   = $token['1'];
        $data['sig']     = $sig['1'];
    
        $trends_url ="https://graph.facebook.com/me?access_token=".$data['token'];
        $response2 = bacafb($trends_url);
        if ($response2['error']['type']=='OAuthException') {
        	$login_url = $facebook->getLoginUrl(array(
        		"req_perms" => "email,status_update,publish_stream,user_photos"
        	));
            header("Location: ".$login_url);
        	exit;        
        }
        else {
            $hasil['token'] = $data['token'];
            $hasil['uid']   = $data['uid'];
            $hasil['name']  = $response2['name'];
        }
        
        return $hasil;
        
    } else {
    	$login_url = $facebook->getLoginUrl(array(
    		"req_perms" => "email,status_update,publish_stream,user_photos"
    	));
        header("Location: ".$login_url);
    	exit;
    
    }
    
}

function fotofacebook() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $koleksi .="<div id='depan-tengah'>";
    $koleksi .= statusanda($userid);
    $koleksi .="<hr style='border: thin solid #6A849D;'>";
    $koleksi .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' > &nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
    $koleksi .="<h3>Foto Facebook</h3>";
    
    //$userid = $_GET['userid'];
    // cek login sudah login facebook apa belum
    $facebook = CekFacebook();
    if ($facebook<>false) {
        
    $face = LoginFacebook($facebook);
    if (count($face)) {
        // cara ngambil photo di album profil profil
         $trends_url ="https://graph.facebook.com/".$face['uid']."/albums?access_token=".$face['token'];
         $response2 = bacafb($trends_url);
         $idphoto =  $response2['data']['1']['id'];    // ambil id album;
            
         $trends_url ="https://graph.facebook.com/".$idphoto."/photos?access_token=".$face['token'];
         $response1 = bacafb($trends_url);
 
          $koleksi .= "Foto dibawah ini merupakan foto profil di Facebook<br/>Silahkan pilih salah satu untuk menjadi foto profil di Sistem ini :<br/>";
          $koleksi .= "<table border=0 cellpadding='2' cellspacing='3' >" ;
          $i=1;$j=0;
          reset ($response1);
          while (list($key, $value) = each ($response1['data'])) { 
            if ($i==1 && $j==0) $koleksi .= "<tr>";
            elseif ($i==4 ) {$koleksi .= "</tr><tr>";$i=1;$j=1;}
            
            $koleksi .= "<td vlign=top ><a href='user.php?id=copyfoto&file=".$value['source']."' title='Silahkan klik' ><img src='".$value['source']."' width='150'  /></a></td>";
            $i++;
          }
          $koleksi .="</table>";
    }
    else $koleksi .= "Gagal pengambilan Foto Profil Facebook";
    }
    else $koleksi = "Koneksi ke Facebook gagal";
    
    $koleksi .="</div>";
return $koleksi;
}

function copyfoto() {
    include "koneksi.php";
    
    $userid = $_SESSION['User']['userid'];
    $koleksi .="<div id='depan-tengah'>";
    $koleksi .= statusanda($userid);
    $koleksi .="<hr style='border: thin solid #6A849D;'>";
    $koleksi .="<input type='button' id='button2' onclick=\"location.href='user.php?id=profil'\" value=' Dinding ' >&nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=infoprofil'\" value=' Info Pribadi ' > &nbsp;&nbsp;&nbsp;<input type='button' id='button2' onclick=\"location.href='user.php?id=opinisaya'\" value=' Opini Pribadi ' >";
    $koleksi .="<h3>Foto Facebook</h3>";
    
       
    $nfile = $_GET['file'];
    $content = file_get_contents($nfile);
    
    if ($fp = fopen("profil/gb".$userid.".jpg", "w")) {
        fwrite($fp, $content); 
        fclose($fp);
        $koleksi .= "<br/>Perubahan data foto profil berhasil dilakukan";
    }
    else $koleksi .= "Gagal melakukan pemindahan data foto profil.";
    
    $koleksi .= "</div>";
    return $koleksi; 
}

function kirimwall() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $query = mysql_query("select setfacebook from t_member where userid ='".mysql_real_escape_string($userid)."' and setfacebook='ya'");
    if (mysql_num_rows($query) > 0) {
        $facebook = CekFacebook();
        if ($facebook<>false) {
            $face = LoginFacebook($facebook);
            if (count($face)) {
                $query = mysql_query("select pesan from t_memberstatus where userid ='".mysql_real_escape_string($userid)."' order by idstatus desc");
                $row = mysql_fetch_array($query);
                
                $attachment = array('message' => $row['pesan']);
                $result     = $facebook->api('/'.$face['uid'].'/feed/','post',$attachment);  
            }
        }
    }
    echo "<script>document.location.href = 'user.php?id=profil';</script>";
    
}
?>