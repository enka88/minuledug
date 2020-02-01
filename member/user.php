<?php
session_start();
define("CMSBalitbang",1);
include ("../lib/parsing.php");
include ("../lib/config.php");

if (isset($_SESSION['User'])) {
if ($_GET['id']=='logout') {
include ("../functions/koneksi.php");
	$userid = $_SESSION['User']['userid'];
	unset($_SESSION['User']);
	//session_destroy();
	$q=mysql_query("update t_member set stlogin='0',tgl_login=NOW(),ip='".$_SERVER['REMOTE_ADDR']."' where userid='".mysql_real_escape_string($userid)."' ");
	echo "<html>\n<head>\n<title>.: Login Member :.</title>\n
		<link rel='stylesheet' type='text/css' href='style-index.css'>
		</head>\n<body>\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
	echo "</body>\n</html>\n";
}
elseif ($_SESSION['User']['ket']=='') {
	session_destroy();
	echo "<html>\n<head>\n<title>.: Login Member :.</title>\n
		<link rel='stylesheet' type='text/css' href='style-index.css'>
		</head>\n<body>\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
	echo "</body>\n</html>\n";
}
else {
// isi websitenya
include "../functions/fungsi_pass.php";
require ("../functions/member_menu.php");
require ("../functions/member_konten.php");
require ("../functions/member_layout.php");
include ("../functions/fungsi_konversiuser.php");
include ("../functions/member_status.php");

//$username = $_SESSION['User']['userid'];
//$_SESSION['username'] = user_singkat($_SESSION['User']['userid'];
$_SESSION['username'] = user_singkat($_SESSION['User']['userid']); 
$a["isi"]=isi();
$a["judul"]=$webhost;
$a["menu"]=menu_sim();
$a["head"]=head();
Parse("index-member.html",$a);

}

}
// klo kosong 
else {
	echo "<html>\n<head>\n<title>.: Login Member :.</title>\n
		<link rel='stylesheet' type='text/css' href='style-index.css'>
		</head>\n<body>\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
	echo "</body>\n</html>\n";
}
?>