<?php
require '../lib/config.php';
if($cmsmember<>"ya") {
	echo "<script>document.location.href = '../index.php';</script>";
	exit;
}
session_start();
if ( isset($_SESSION['User']) )
{
	
	echo "<html>\n<head>\n<title>.: Login Member :.</title>\n</head>\n<body>\n";
	echo "Maaf Anda harus login .. redirecting\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=user.php\">\n";
	echo "</body>\n</html>\n";
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="alanrm82" />
<title>Login Member - <?php  echo $nmsekolah ?></title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
<link rel="stylesheet" href="css/jquery.tabs.css" type="text/css" media="print, projection, screen">
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link href="css/facebox.css" media="screen" rel="stylesheet" type="text/css">

        <!-- Additional IE/Win specific style sheet (Conditional Comments) -->
        <!--[if lte IE 7]>
        <link rel="stylesheet" href="css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
        <![endif]-->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/chat.js"></script>

<script>
$(document).ready(function()
{
	$("#login_form").submit(function()
	{
        $("#msgbox").removeClass().addClass('messagebox').fadeIn();
		$.post("../member/ajax_login.php",{ user_name:$('#username').val(),password:$('#password').val(),rand:Math.random() } ,function(data)
        {
		  if(data=='yes') 
		  {
		  	$("#msgbox").fadeTo(200,0.1,function() 
			{ 
              $(this).html('<img src="../member/css/loading2.gif"><br/>').addClass('messageboxerror').fadeTo(900,1).fadeOut(4000,
              function()
			  { 
				 document.location='user.php';
			  });
			  
			});
		  }
		  else 
		  {
		  	$("#msgbox").fadeTo(200,1,function() 
			{ 
			 $("#msgbox2").removeClass().addClass('messagebox').text('').fadeIn();
              $(this).html('<img src="../member/css/loading2.gif"><br/>').addClass('messageboxerror').fadeOut(4000,function()
              {
               $("#msgbox2").fadeTo(200,1,function() 
			{ 
              $(this).html('<font color=white>kombinasi User dan Password salah ...</font>').addClass('messageboxerror').fadeOut(3000);
			});		 
              }
              
              
              );
			});
          }
				
        });
 		return false; //not to post the  form physically
	});
	//now call the ajax also focus move from 
	$("#password").blur(function()
	{
		$("#login_form").trigger('submit');
	});
});
</script>

</head>
<body>
<form method="post" action="" id="login_form">
<div id='container'>
        <div id='header'>
            <div id='header_wrapper' style="height: 70px;">
                <div id='logo'><?php  echo $nmsekolah ?></div>
                <div id='ii' style="float: right;margin-right: 20px;">
                      <table>
                        <tr>
                            <td><span id="msgbox" style="display:none"></span></td><td>  
                        <table>
                            <tr><td align="left" style="color: white;font-family: sans-serif;font-size: 11px;">Username</td><td align="left" style="color: white;font-family: sans-serif;font-size: 11px;">Password</td></tr>
                            <tr><td align="left"><input type="text" class="textinput" id="username" name="username" style="width: 160px;"/></td><td align="left"><input  type="password" class="textinput" id="password" name="password" style="width: 160px;"/></td><td align="right"> &nbsp;&nbsp;<input name="Submit" type="submit" id="submit" value="Login" style="margin-left:-10px; height:23px" /></td></tr>    
                        </table>
                        </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><span id="msgbox2" style="display:none"></span></td>
                        </tr>
                      </table>
                </div>
            </div>
        </div>
            <div id='upper' style="height: 500px;">
            <div id='upper_wrapper'>
                <div style="float: left;width:400px;">            
                    <h2 style="font-family: sans-serif;">Selamat Datang di Sistem Komunitas <?php echo $nmsekolah ?></h2><font style="font-family: sans-serif;">Didalam sistem ini Anda dapat menggunakan beberapa fitur yaitu :</font>
                    <ol style="font-family: sans-serif;">
                    <li>Menambahkan status kegiatan Anda saat ini</li>
                    <li>Menambahkan link Blog Anda</li>
                    <li>Menambahkan foto-foto pribadi Anda</li>
                    <li>Mendownload file materi ajar dan bahan ujian</li>
                    <li>Komunikasi sesama pengguna (forum dan chating)</li>
                    <li>Melihat detail informasi guru dan siswa</li>
                    <li>Sistem Informasi meliputi Nilai,Absensi, Iuran dan Penugasan siswa</li>
                    <li>Bermain games</li>
                    </ol>
                </div>
                <div style="float: right;width:500px;">
                <p align="center">
                <a href="daftar.php?id=lupa" style="background-color:#3B5998;
border-color:#D9DFEA #0E1F5B #0E1F5B #D9DFEA;
border-style:solid;
border-width:1px;
color:#FFFFFF;
padding:8px 15px 3px;
text-align:center;height: 25px;font: bold sans-serif;display: block;width: 250px;">LUPA PASSWORD USER</a><br /><br />
                <a href="daftar.php" style="background-color:#3B5998;
border-color:#D9DFEA #0E1F5B #0E1F5B #D9DFEA;
border-style:solid;
border-width:1px;
color:#FFFFFF;
padding:8px 15px 3px;
text-align:center;height: 25px;font: bold sans-serif;display: block;width: 250px;">DAPATKAN ACCOUNT MEMBER</a><font style="color: #3B5998;" size="2">*hanya untuk member Alumni dan Tamu</font><br/></p>            
                    <font style="font-family: sans-serif;">Prosedur pendaftaran untuk Siswa dan Orang Tua/Wali sebagai berikut :</font> 
<ol style="font-family: sans-serif;">
<li>Siswa/Orang tua dapat menghubungi langsung di sekolah melalui Tim IT <?php echo $nmsekolah ?>.</li>
<li>Isi formulir pendaftaran khusus member.</li>
<li>Setelah didaftarkan langsung oleh Admin, silahkan cek email Anda untuk verifikasi data.</li>
<li>Silahkan login ke sistem member tersebut.</li>
<li>Atau pendaftaran melalui email <?php echo $webmail ?> , dengan menyertakan formulir yang sudah Anda isi dibawah ini Download Formulir di sini.</li>
<li>Tunggu konfirmasi validasi data Anda melalui email Anda.</li>
</ol>
                </div>
                   
            </div>
        </div>
        <div id='footer'>
            <div class='left_footer'><?php  echo $nmsekolah ?></div>
            <div class='right_footer'>Website engine's code is copyright @ 2011 Tim Balitbang Kemdikbud versi 3.5.3</div>
        </div>
</div>
</form>
</body>
</html>
<?php
}
?>