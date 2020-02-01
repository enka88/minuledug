<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}
// fungsi-fungsi pengiriman  pesan dan penerimaan pesan
// create alamrm

//fungsi menampilkan pesan
function pesan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];

$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function happesan(userid,kdpesan) {
	if(confirm("Apakah Anda yakin akan menghapus pesan ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdpesan=\'+ kdpesan ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=pesan";}});
	}
}
</script>';
$cetak .="<div id='depan-tengah'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$sql = "SELECT * from t_member_pesan where tujuan_id='". mysql_real_escape_string($userid)."' or semua='1' ";
	$hal = $_GET['hal'];
	$byk=15;
	if ($hal=='') $hal=1;
	$awal = ($hal-1)*$byk;
	
	$query = mysql_query($sql);
	$n=mysql_num_rows($query);
	$jml = intval($n/$byk);
	if (($n % $byk)>0) $jml=$jml+1; 
	
	if ($jml >= 2) {
	  for($i=1;$i<=$jml;$i++) {
		if ($hal==$i) $pag .="<a class='sel'>$i</a>";
		else $pag .="<a href='user.php?id=pesan&hal=$i' title='Hal ke $i dari $jml' >$i</a>";
	  }
	}
	$cetak .="<h3>Pesan Masuk</h3>";
	$cetak .= "<div id='pag' align=right >$pag</div>";
	if ($n > 0 ) {
	$cetak .= "<script language='JavaScript'>
	function gCheckAll(chk) {
	   for (var i=0;i < document.forms[0].elements.length;i++) {
		 var e = document.forms[0].elements[i];
		 if (e.type == 'checkbox') {
			e.checked = chk.checked  }
		}
	}
	</script>";
	$sql3="select userid from t_member_pesan where (tujuan_id='". mysql_real_escape_string($userid)."' or semua='1') and status='0' ";
	$query3=mysql_query($sql3);
	$k = mysql_num_rows($query3);
	$cetak .="Pesan belum dibaca : $k &nbsp;&nbsp;&nbsp;Total Pesan : $n<br><form action='user.php' method=\"post\">";
	$cetak .= "<table border=0 width=100% id='tablebaru' cellspacing=2 cellpadding=2 >";
	 $query = mysql_query($sql." order by tgl desc limit $awal,$byk");
	  while($row=mysql_fetch_array($query)) {
	  	$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
		
		if ($row[status]=='0') $img="<img src='../images/letter.png' align=left >";
		else $img="<img src='../images/letter_open.png' align=left >";
		$gb=fotouser($row[userid]);
		$selisih = ambilselisih(strtotime($row[tgl]), time());
		$nama = member_nama($row[userid]);
		$cetak .="<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\">
		<td width='10' ><input type='checkbox' name='kdpesan[".$row[id]."]' value='on'></td><td width=50 >".$gb."</td><td ><img src='../images/user.png' align=left > &nbsp;".$nama." - ".$selisih."<div style='float:right' ><a href='#' onclick=\"happesan('".hex("happesan,".$userid,$noacak)."','".hex($row[id],$noacak)."')\" title='Klik untuk menghapus pesan ini' id='hapuslink' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div><hr style='border:1px dashed #999999;'>
		".$img."&nbsp;<a href='?id=lihpesan&kdpesan=$row[id]' title='Klik untuk lihat pesan ini' >".$row[judul]."</a></td></tr>";
	  }
	  $cetak .= "</table ><br><input type=hidden name='id' value='hapuspesan' >
	  &nbsp;&nbsp;<input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Cek Semua <input type=submit value='Hapus' id=button2 ></form>";
	}
	$cetak .= "<div id='pag' align=right >$pag</div>";
$cetak .="</div>";
return $cetak;
} 

//hapus pesan
function hapuspesan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kdpesan=$_POST['kdpesan'];
	if (!empty($kdpesan))
	  {
	  	while (list($key,$value)=each($kdpesan))
		{
			$sql="delete from t_member_pesan where id='".mysql_real_escape_string($key)."' and tujuan_id='".mysql_real_escape_string($userid)."'";
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }
}

//fungsi lihat pesan
function lihpesan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kdpesan = $_GET['kdpesan'];
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function happesan(userid,kdpesan) {
	if(confirm("Apakah Anda yakin akan menghapus pesan ini ?")) {
    var dataString = \'userid=\'+ userid +\'&kdpesan=\'+ kdpesan ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=pesan";}});
	}
}


</script>';
$cetak .="<div id='depan-tengah'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
	$sql="select * from t_member_pesan where id='". mysql_real_escape_string($kdpesan)."' ";

	$query=mysql_query($sql);
	if($row = mysql_fetch_array($query)) {
		$cetak .="<div style='float:right;' ><a href='?id=kirimpesan&kdpesan=".hex($row[id],$noacak)."' title='Klik untuk menjalin hubungan pertemanan'  id='button2' >Teruskan </a>&nbsp;&nbsp;&nbsp;<a href='boxframe.php?id=kirimpesan&userid=".hex($userid,$noacak)."&tujuan=".hex($row[userid],$noacak)."' rel=\"facebox\" id='button2' >Balas Pesan</a></div><h3>Lihat Pesan</h3>";
		$gb=fotouser($row[userid]);
		$selisih = ambilselisih(strtotime($row[tgl]), time());
		$nama = member_nama($row[userid]);
        
		$cetak .= "<table border=0 width=100% id='tablebaru' cellspacing=2 cellpadding=2 >";
		$cetak .= "<tr class='td2'><td width=50 >".$gb."</td><td ><img src='../images/user.png' align=left > &nbsp;".$nama." - ".$selisih."<hr style='border:1px dashed #999999;'><img src='../images/letter_open.png' align=left >&nbsp;".$row[judul]."</td></tr>";
		$cetak .= "<tr class='td1'><td valign=top >Pesan</td><td >".filter_pesan($row[pesan])."</td></tr>";
		$cetak .= "</table>";
		$q=mysql_query("update t_member_pesan set status='1' where id='".mysql_real_escape_string($kdpesan)."' ");
	}
	else 	$cetak .="<h3>Lihat Pesan</h3>Pesan yang akan Anda lihat kosong";
$cetak .="</div>";
return $cetak;
}

//fungsi teruskan pesan
function kirimpesan() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kdpesan = unhex($_GET['kdpesan'],$noacak);
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })
$(document).ready(function()
{
$('#semua').click(function(){

var element = $(this);
var semua = $('#semua').val();
if (semua=='1') {
$('#target').slideToggle(300);
$(this).toggleClass('active'); 
}

return false;});});

</script> ";


$cetak .='<link rel="stylesheet" href="css/TextboxList.css" type="text/css" media="screen" charset="utf-8" />';
$cetak .='<script src="js/GrowingInput.js" type="text/javascript" charset="utf-8"></script>';
$cetak .='<script src="js/TextboxList.js" type="text/javascript" charset="utf-8"></script>';
$cetak .='<script src="js/TextboxList.Autocomplete.js" type="text/javascript" charset="utf-8"></script>';
$cetak .="<script type=\"text/javascript\" charset=\"utf-8\">		
			$(function(){
				// Autocomplete initialization
				var t4 = new TextboxList('#tujuan', {unique: true, plugins: {autocomplete: {}}});

				t4.getContainer().addClass('textboxlist-loading');				
				$.ajax({url: 'listeman.php', dataType: 'json', success: function(r){
					t4.plugins['autocomplete'].setValues(r);
					t4.getContainer().removeClass('textboxlist-loading');
				}});				
			});
		</script>";
$cetak .='<script type="text/javascript">
$(function() {$(".kirimpesan").click(function() {
	var element = $(this);
    var tujuan = $("#tujuan").val();
	var judul = $("#tema").val();
	var pesan = $("#pesan").val();
	var code = $("#code").val();
	var semua = $("#semua").val();
	var email = $("#email").val();
	var userid = $("#userid").val();
	var dataString = \'id=2&userid=\'+ userid +\'&tujuan=\'+ tujuan +\'&judul=\'+ judul+\'&pesan=\'+pesan+\'&semua=\'+ semua+\'&email=\'+ email+\'&code=\'+ code;
	if((tujuan== \'\' || tujuan==\'.\') && semua==\'1\') {
		alert("Tujuan masih kosong");
	}
	else if(judul==\'\') {
		alert("Judul masih kosong");
	}	
	else if(pesan==\'\') {
		alert("Pesan masih kosong");
	}	
	else if(code==\'\') {
		alert("Kode konfirmasi masih kosong");
	}	
	else {
		$.ajax({type: "POST",url: "kontenmember.php",data: dataString,success: function(html){$("#hasil").append(html);$("#boxpesan").hide();} });
	}
return false;
});

});

</script>';
$cetak .="<div id='depan-tengah'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$kirim = "Kirim Pesan";
	$sql="select * from t_member_pesan where id='". mysql_real_escape_string($kdpesan)."' ";

	$query=mysql_query($sql);
	if($row = mysql_fetch_array($query)) {
		$gb=fotouser($row[userid]);
		$nama = member_nama($row[userid]);
		$kirim="Teruskan Pesan";
		$isi = "

Pengirim dari ".$nama." - ".date("d-m-Y",strtotime($row[tgl]))."<br> ------------------------------------------<br> ". $row[pesan];
		$judul ="Re:".$row[judul];
	}
	$cetak .="<h3>$kirim</h3><div id='hasil' ></div><div id='boxpesan' >";
	$cetak .= "<form action='' method=\"post\" accept-charset=\"utf-8\">
	<table border=0 width=100% id='tablebaru' cellspacing=2 cellpadding=2 >";
	$cetak .= "<tr ><td width=50  >Tujuan</td><td ><select name='semua' id='semua' ><option value='1' >Pilih Teman</option>
	<option value='2' >Kirim ke Semua Teman</option></select>
	<div id='target' ><input type=\"text\" name=\"tujuan\"  id=\"tujuan\" /></div></td></tr>
		<tr class='td2'><td width=50 >Judul<td ><input type='text' name='judul' id='tema' value='$judul' maxlength='60' size=50 ></td></tr>";
	$cetak .= "<tr class='td1'><td ></td><td ><textarea cols='48' rows='10' id='pesan' name='pesan' >$isi</textarea></td></tr>
	<tr class='td1' ><td  valign=top >Kode Konfirmasi</td><td ><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12' id='code'  ></td></tr>
	<tr class='td1' ><td  valign=top ></td><td ><input type='submit' value='Kirim' id=button2 class='kirimpesan' ></td></tr><input type='hidden' id='userid' value='".hex("sim2pesan,".$userid,$noacak)."' >";
	$cetak .= "</table></form></div>";
	
$cetak .="</div>";
return $cetak;
}


?>