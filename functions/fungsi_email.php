<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
//fungsi kirim email
function pesan_mail($email,$user,$id,$pengirim,$judul,$pesan) {

include "koneksi.php";

//$url2= $_SERVER['PHP_SELF'];
//$nmfo=explode("/",$url2);
//for($i=0;$i<count($nmfo);$i++) {
//    $nm .= $nmfo[$i]."/";
//    if (file_exists($_SERVER['DOCUMENT_ROOT'].$nm."fckconfig.js")) {
//        $nmhost = "http://".$_SERVER['HTTP_HOST'].$nm;
//        break;
//    }   
//}
	if($id=="admin") {
		$wel="gb".rand(10,1).".jpg";
		$gb="<img src='$nmhost/images/galeri/$wel' alt='Kegiatan di Sekolah'>";
	}
	else {
		$file = "../member/profil/gb".$id.".jpg";
		$gb="<img src='$nmhost/member/profil/kosong.jpg' width='90' height='120' alt='$pengirim' border='1' style='padding:2px;margin:3px;'>";
		if (file_exists($file)) {
	        $gb="<img src='$nmhost/member/profil/gb".$id.".jpg' width='90' height='120' alt='$pengirim' border='1' style='padding:2px;margin:3px;'>";
		}
	}
		$tgl = date("d M Y H:i:s");
		$tahun = date("Y");
$message = <<<EOF
<html>
<body>
<table cellSpacing="0" cellPadding="4" bgColor="#6A849D" border="0">
  <tr>
    <td width="600">
    <table cellSpacing="0" cellPadding="10" width="600" bgColor="#ffffff">
      <tr>
        <td><strong>
        <font face="Verdana,Arial,Helvetica,sans-serif" color="#ff9900" size="+1">
        Hey, $user </font></strong>
          <table cellSpacing="15" cellPadding="0" width="100%" border="0">
          <tr>
            <td vAlign="top">
            $gb<br><br><center><img src="$nmhost/images/igetloveletter.gif" alt="Emoticons" width="55" height="46">
			</center></td>
            <td vAlign="top">
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2">
              Terima kasih Anda masih bergabung dalam Komunitas $nmsekolah.<br>
              Anda mendapatkan kiriman Pesan Baru dari <b>$pengirim</b> : </font></p>
           
              <table width="100%"  border="1" bordercolor="#6A849D" cellspacing="0" cellpadding="3">
                <tr>
                  <td bgcolor="#6A849D"><strong><font color="#ff9900">$judul</font></strong></td>
                </tr>
                <tr>
                  <td bgcolor="#f4f4f4">$tgl<br>$pesan</td>
                </tr>
              </table>
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2"><br>
                    <br>
               	    Silahkan manfaatkan fasilitas komunitas ini untuk kepentingan pendidikan.<br>
                    <br>
                </font></p></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <table cellSpacing="0" cellPadding="1" width="100%" align="center" border="0">
          <tr>
            <td bgColor="#6A849D">
            <table cellSpacing="0" cellPadding="0" width="100%" border="0">
              <tr>
                <td bgColor="#f4f4f4">
                <table cellSpacing="0" cellPadding="4" border="0">
                  <tr>
                    <td>
                    <a href="$nmhost" target="_blank">
                    <img style="margin-bottom: 5px" alt src="$nmhost/images/logo.jpg" align="center" border="1"  width="88" height="88"></a>
                    </td>
                    <td style="line-height: 110%" vAlign="center">
                    <font face="Verdana,Arial,Helvetica,sans-serif" color="#ff9900" size="2">
                    <strong>Terima Kasih.... </strong> <br>
                    <font color="#000000" size="1">$webmail</font> </font></td>
					<td><img src="$nmhost/images/kissingcheek.gif" alt="Emoticons" width="66" height="46"></td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
            </td>
          </tr>
        </table>        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<table cellSpacing="0" cellPadding="0" width="600" border="0">
  <tr>
    <td align="middle">
    <a href="$nmhost" target="_blank">
    </a>
    <br>
    <font face="Verdana,Arial,Helvetica,sans-serif" color="#7b849c" size="-2">
    Copyright $tahun $nmsekolah. All rights reserved. <br>
    $almtsekolah </font></td>
  </tr>
</table>
</body>
</html>
EOF;
	$headers  = "From: \"Komunitas $nmsekolah\" <$webmail>\r\n";
	$headers .= "Content-type: text/html\r\n";
    if(!@mail($email, "Komunitas $nmsekolah :: Pesan baru ::", $message, $headers)) {
        $pesan_mail .="Gagal kirim email<br>";
    }
    
return $pesan_mail;
}

?>