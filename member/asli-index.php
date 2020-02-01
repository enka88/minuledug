<?php
require '../lib/config.php';
session_start();
if ( isset($_SESSION['User']) )
{
	
	echo "<html>\n<head>\n<title>.: Login Member :.</title>\n</head>\n<body>\n";
	echo "Maaf Anda harus login .. redirecting\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=user.php\">\n";
	echo "</body>\n</html>\n";
} else {
	if ( !isset($_POST['username']) || !isset($_POST['password']) )
	{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Login Member :.</title>
<link type="text/css" rel="stylesheet" media="all" href="css/style-index.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script>
$(document).ready(function(){

$('[rel=tooltip]').bind('mouseover', function(){
		
 if ($(this).hasClass('ajax')) {
	var ajax = $(this).attr('ajax');	
			
  $.get(ajax,
  function(theMessage){
$('<div class="tooltip">'  + theMessage + '</div>').appendTo('body').fadeIn('fast');});

 
 }else{
			
	    var theMessage = $(this).attr('content');
	    $('<div class="tooltip">' + theMessage + '</div>').appendTo('body').fadeIn('fast');
		}
		
		$(this).bind('mousemove', function(e){
			$('div.tooltip').css({
				'top': e.pageY - ($('div.tooltip').height() / 2) - 5,
				'left': e.pageX + 15
			});
		});
	}).bind('mouseout', function(){
		$('div.tooltip').fadeOut('fast', function(){
			$(this).remove();
		});
	});
						   });

</script>
</head>
<body>

<form method="POST" action="index.php">
<div id="container">
<div id="header"></div>
<div id="title"><div class="content" id="titlecontent"><?php  echo $nmsekolah ?></div></div>
<div id="body"><div class="content bodycolor" id="bodycontent">
Username:<br/>
<input type="text" class="textinput" id="username" name="username" rel="tooltip" content="Silahkan masukan Username member Anda.<br>Untuk Siswa/Alumni : Nomor Induk Siswa, <br>Guru,Orang Tua,Tamu : Email" />
<br/><br/>
Password:<br/>
<input type="password" class="textinput" id="password" name="password" rel="tooltip" content="Silahkan masukan Password member Anda </br>sesuai dengan pesan di Email yang kami kirim. " />
<br/><br/>
<input type="submit" name="login" Value="Login" id="submit">&nbsp;&nbsp;&nbsp;&nbsp;
| &nbsp;&nbsp;<a href="daftar.php" class="link" rel="tooltip" content="Silahkan bergabung dengan komunitas <?php echo $nmsekolah; ?>, daftarkan diri Anda dan cek email Anda.">Daftar</a> &nbsp;&nbsp;| &nbsp;&nbsp;<a href="daftar.php?id=lupa" class="link" rel="tooltip" content="Apabila Anda lupa password Anda, silahkan klik disini dan cek konfirmasinya lewat email." >Lupa Password</a> &nbsp;&nbsp;| &nbsp;&nbsp;<a href="../html/index.php" class="link" rel="tooltip" content="Kembali ke halaman website utama. " >Kembali ke website</a> &nbsp;&nbsp;|
</div></div>
<div id="footer"></div>
<div id="copyright" align="center">Gunakan browser Mozilla Firefox untuk tampilan yang maksimal 1024 x 768. <br /><?php  echo $webhost; ?> .Website engine's code is copyright © 2009 <br />Tim Balitbang Depdiknas versi <?php echo $versi; ?>
</div>
</form>
</body>
</html>	
		<?php
	} else {
		require '../functions/koneksi.php';
		echo "<html>\n<head>\n<title>.: Login Member :.</title>\n
		<link rel='stylesheet' type='text/css' href='css/style-index.css'>
		</head>\n<body>\n";
		//session_destroy();
		$username = addslashes($_POST['username']);
		$password = addslashes($_POST['password']);
		$username=strtolower($username);
		$password=md5($password);
		$ret = mysql_query("SELECT username,password,status,ket,userid,nis,nama FROM t_member WHERE username='".mysql_real_escape_string($username)."' AND password='".mysql_real_escape_string($password)."'  limit 0,1");
		if (@mysql_num_rows($ret) != 0)
		{
			$ret2 = mysql_fetch_array($ret);
			if($ret2[status]=="1") {
			   $_SESSION['User'] = $ret2;
			   $userid = $_SESSION['User']['userid'];
			   //$kunjung = strval($_SESSION['User']['totlogin']) + 1;
			   $q=mysql_query("update t_member set stlogin='1',tgl_login=NOW(),ip='".$_SERVER['REMOTE_ADDR']."',totlogin=totlogin+1,point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
			   echo "Selamat Datang di Komunitas Sekolah.. redirecting\n";
			   echo "<meta http-equiv=\"refresh\" content=\"1;url=user.php\">\n";
			   $error="";	
			}
			else {
				$error = "Anda tidak diperkenankan untuk mengakses fasilitas ini, <br>Anda belum membuka dan mem-validasi data yang dikirim ke Email Anda";
			}

		} else {
				$error = "Maaf username dan password tidak valid";
		}
		if ($error<>'') {
		echo '<div id="container">
<div id="header"></div>
<div id="title"><div class="content" id="titlecontent" ><center>'.$error.'</center></div></div>
<div id="body"><div class="content bodycolor" id="bodycontent">
</div></div>
<div id="footer"></div>
<div id="copyright" align="center">'.$webhost.'.Website engine\'s code is copyright © 2010 <br />Tim Balitbang Depdiknas versi '.$versi.'
</div>';
		echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
		echo "</body>\n</html>\n";
		}
	}
}

?>