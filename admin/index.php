<?php
session_start();
if (isset($_SESSION['Admin']))
 {
    session_destroy();
    echo "Anda telah melakukan login, silahkan untuk login kembali";
    echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
    }
 else {
	if ( !isset($_POST['username']) || !isset($_POST['password']) )
	{
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> .: Login Administrator :. </title>
<link rel="stylesheet" type="text/css" href="style.css"/>

</head>
<body>
<!-- This P tag shows Error mesage -->

<form method="POST" action="index.php">
	<center>
    <div  class="display_green">
			<table cellspacing="0" cellpadding="0" border ="0"> 
			<tr>
			 <td colspan="3"><center>.: Login Administrator :.</center></td>
			</tr>
            <tr><td rowspan="3"><img src="login.png" width="60" height="60" align="left" /></td>
				<td>Username </td><td><input type="text" name="username" id="uname"/></td>
			</tr>
			<tr>
				<td>Password </td><td><input type="password" name="password" id="pass"/></td>
			</tr>
			<tr>
				<td align="left"><input type="submit" name="login" value="Login" id="submit"/></td>
			</tr>
			<tr>
			</table>
            </div>
	</center>
</form>
</body>
</html>		
		<?php
	} else {
		require '../functions/koneksi.php';
		include "../functions/fungsi_pass.php";
		echo "<html>\n<head>\n<title>Login Administrator</title>\n
		<link rel='stylesheet' type='text/css' href='style.css'>
		</head>\n<body>\n";
		
		$username = addslashes($_POST['username']);
		$password = hex(addslashes($_POST['password']),82);
		$ret = mysql_query("SELECT * FROM user WHERE username = 
		'".mysql_escape_string($username)."' AND password = '". mysql_escape_string($password) ."' and status='1' limit 0,1");
		if (@mysql_num_rows($ret) != 0)
		{
			$ret = mysql_fetch_array($ret);
			$_SESSION['Admin'] = $ret;
			$timeout = 9000;
    		$_SESSION["expires_by"] = time() + $timeout;
			$username = $_SESSION['Admin']['username'];
			$kunjung = strval($_SESSION['Admin']['kunjung']) + 1;
			$tgl = date("H:i:s")." ".date("d/m/Y");
			$q=mysql_query("update user set waktu='$tgl',ip='".$_SERVER['REMOTE_ADDR']."',kunjung='$kunjung' where username='$username' ");
			echo "Selamat Datang $username.. redirecting\n";
			echo "<meta http-equiv=\"refresh\" content=\"1;url=admin.php\">\n";
			
		} else {
			echo '<center><div  class="display_red">
			<table cellspacing="0" cellpadding="0" border ="0"> 
			<tr>
			 <td colspan="2"><center>.: Login Administrator :.</center></td>
			</tr>
            <tr><td ><img src="login.png" width="60" height="60" align="left" ></td>
				<td>Maaf username dan password salah</td>
			</tr>
			</table>
            </div></center>';
			echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
		}
		echo "</body>\n</html>\n";
	}
}

?>