<?php
include "../functions/fungsi_pass.php";
include "../functions/koneksi.php";
$err= '<html>
<head>
<style>
a:link			{font:8pt/11pt verdana; color:red}
a:visited		{font:8pt/11pt verdana; color:#4e4e4e}
</style>
<meta HTTP-EQUIV="Content-Type" Content="text-html; charset=Windows-1252">
<title>Cannot find server</title>
</head>

<body bgcolor="white">

<table width="400" cellpadding="3" cellspacing="5">
  <tr>
    <td id="tableProps" valign="top" align="left"><img id="pagerrorImg" SRC="res://shdoclc.dll/pagerror.gif"
    width="25" height="33"></td>
    <td id="tableProps2" align="left" valign="middle" width="360"><h1 id="textSection1"
    style="COLOR: black; FONT: 13pt/15pt verdana"><span id="errorText">The page cannot be displayed</span></h1>
    </td>
  </tr>
  <tr>
    <td id="tablePropsWidth" width="400" colspan="2"><font
    style="COLOR: black; FONT: 8pt/11pt verdana">The page you are looking for is currently
    unavailable. The Web site might be experiencing technical difficulties, or you may need to
    adjust your browser settings.</font></td>
  </tr>
  <tr>
    <td id="tablePropsWidth" width="400" colspan="2"><font id="LID1"
    style="COLOR: black; FONT: 8pt/11pt verdana"><hr color="#C0C0C0" noshade>
    <p id="LID2">Please try the following:</p><ul>
      <li id="instructionsText1">Click the 
      <a xhref="javascript:location.reload()" target="_self">
      <img border=0 src="res://shdoclc.dll/refresh.gif" width="13" height="16"
        alt="refresh.gif (82 bytes)" align="middle"></a> <a xhref="javascript:location.reload()" target="_self">Refresh</a> button, or try again later.<br>
      </li>
      
      <li id="instructionsText2">If you typed the page address in the Address bar, make sure that
        it is spelled correctly.<br>
      </li>
      <li id="instructionsText3">To check your connection settings, click the <b>Tools</b> menu, and then click
        <b>Internet Options</b>. On the <b>Connections</b> tab, click <b>Settings</b>.
        The settings should match those provided by your local area network (LAN) administrator or Internet service provider (ISP). </li>
     <li ID="list4">See if your Internet connection settings are being detected. You can set Microsoft Windows to examine your network and automatically discover network connection settings (if your network administrator has enabled this setting).
        <OL> 
        <li id="instructionText6">Click the <b>Tools</b> menu, and then click <B>Internet Options</b>. </li>
        <li id="instructionText7">On the <b>Connections</b> tab, click <b>LAN Settings</b>.</li> 
        <li id="instructionText8">Select <b>Automatically detect settings</b>, and then click <b>OK</b>.</li>
        </OL>
      </li>
    <li id="instructionsText5">
       Some sites require 128-bit connection security. Click the <b>Help</b> menu and then click <b> About Internet Explorer </b> to determine what strength security you have installed.
    </li>
    <li id="instructionsText4">
       If you are trying to reach a secure site, make sure your Security settings can support it. Click the <B>Tools</b> menu, and then click <b>Internet Options</b>.  On the Advanced tab, scroll to the Security section and check settings for SSL 2.0, SSL 3.0, TLS 1.0, PCT 1.0. 
    </li>
     <li id="list3">Click the <a href="javascript:history.back(1)"><img valign=bottom border=0 src="res://shdoclc.dll/back.gif"> Back</a> button to try another link. </li>    
      
      
    </ul>
    <p><br>
    </p>
    <h2 id="IEText" style="font:8pt/11pt verdana; color:black">Cannot find server or DNS Error<BR> Internet Explorer 
	
    </h2>
    </font></td>
  </tr>
</table>
</body>
</html>';
$id =$_GET['id'];
$p=$_GET['p'];

if ($id<>'' and $p<>'') {
	$user =unhex($id,$noacak);
	$pass2=unhex($p,$noacak);
	$pass =md5($pass2);
	$query = "SELECT * FROM t_member where username='".mysql_real_escape_string($user)."' and password='$pass'";
    $result = mysql_query($query) or die("Query failed member");
    $r = mysql_num_rows($result);
	if ($r==1) {
		
		// === tambahan ansari 31/10/2013 untuk validasi mencegah hacker mendaftar menggunakan akun member guru ===
		$cekvalid=mysql_fetch_array($result);
		$ketmember = $cekvalid[ket];
		if ($ketmember = 'Guru') {
			echo $err;
		}
		else {
			$query = "update t_member set status='1' where username='".mysql_real_escape_string($user)."'";
			$result = mysql_query($query) or die("Query failed member");
			echo"<center><table width='50%' height='50%'  border=1 cellspacing='4' cellpadding='6' bordercolor='6A849D'>
			<tr><td bgcolor='#6A849D'><center>KONFIRMASI VALIDASI DATA MEMBER</td></tr>
			<tr><td><center><img src='../images/logo.jpg' alt='Logo Sekolah'><br>TERIMA KASIH ANDA TELAH MELAKUKAN VALIDASI DATA MEMBER<BR><br>
			Klik <a href='http://$webhost/member/index.php'>Lanjutkan</a> untuk melanjutkan LOGIN MEMBER.<br></td></tr></table>";
		}
	}
	else echo $err;
	}
else echo $err;

?>