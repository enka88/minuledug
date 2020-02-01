<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
function depan() {
include "koneksi.php";

$nama = $_SESSION['User']['nama'];
$userid = $_SESSION['User']['userid'];
$depan .="<script src=\"js/jquery.tabs.pack.js\" type=\"text/javascript\"></script>
<script type=\"text/javascript\">
   $(function() {
       $('#tab1').tabs({ remote: true });
    });

</script>";
$depan .="<div id='depan-tengah'>";
//tab status member
// status member pribadi terakhir
//script jquery untuk menerima status anda....
$depan .= '<script type="text/javascript">
// awal pengiriman muti submit komentar
$(document).ready(function()
{$(".status_button").click(function() {
	var element = $(this);
    var test = $("#stcontent").val();
	var userid = $("#userid3").val();
    var dataString = \'userid=\'+ userid +\'&stcontent=\'+ test;
	if(test==\'\') {
		alert("Status yang Anda isikan masih kosong");
	}
	else if(test==\' Anda saat ini !!!\') {
		alert("Tuliskan status Anda saat ini");
	}
	else {
		$.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=kirimwall";}});
	}
	return false;
});

});
//akhir multi submit komentar
</script>';

$depan .= statusanda($userid);

$depan .="<div id='box-status'>
<form  method='post' name='form' action=''>
<table>
<tr><td>
&nbsp;<textarea name='stcontent' id='stcontent' style='width:450px;height:20px;' onfocus='clearText(this)' onblur='clearText(this)' rel='tooltip' content='Silahkan masukan status Anda saat ini. </br>untuk diketahui temen lain.'  >Apa yang Anda pikirkan?</textarea></td></tr>
<tr><td style=text-align:right><input type=\"submit\"  value=\"Bagikan\"  name=\"submit\" class=\"status_button\"  /><input type='hidden' name='userid3' id='userid3' value='".hex("statusanda,".$userid,$noacak)."' ></td></tr></table></form></div>";
$depan .='<div id="tab1">
            <ul>
                <li><a href="kontentab.php?id=status"><span>Status Teman</span></a></li>
                <li><a href="kontentab.php?id=opini"><span>Opini Terbaru</span></a></li>
                <li><a href="kontentab.php?id=forum"><span>Forum Terbaru</span></a></li>
				<li><a href="kontentab.php?id=member"><span>Member Teraktif</span></a></li>
            </ul>
        </div>';
//batas bawah tengah
$depan .="</div>";
return $depan;
}

?>
