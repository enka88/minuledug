<?php

// get names (eg: database)
// the format is: 
// id, searchable plain text, html (for the textboxlist item, if empty the plain is used), html (for the autocomplete dropdown)
session_start();
$userid = $_SESSION['User']['userid'];
include "../functions/koneksi.php";
$response = array();
$query2=mysql_query("select t_member.nama,t_member.userid from t_member_contact,t_member where t_member_contact.id_con=t_member.userid and t_member_contact.id_master='". mysql_real_escape_string($userid) ."' and t_member_contact.status='1' ");
while($r=mysql_fetch_array($query2)) {
	$file = "profil/gb".$r[userid].".jpg";
	$fotouser ="<img src='profil/kosong.jpg' width='30' height='30' style='padding-right: 10px;' >";
	if (file_exists(''.$file.'')) {
	   $fotouser="<img src='thumb-user.php?img=$file' width='30' height='30' style='padding-right: 10px;' />";
	}
	$response[] = array($r[userid],$r[nama],null,$fotouser.$r[nama]);
}

header('Content-type: application/json');
echo json_encode($response);

?>