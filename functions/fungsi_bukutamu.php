<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);
*/

/*********************************************************** buku tamu ***********************************/
function buku_tamu() {
//global $img;
include "../lib/config.php";
//tambahan verifikasi eksekusi - agung jogja
$buku_tamu .='<script language="JavaScript">
	function kirpesan() {
		if(document.formpesan.komentar.value=="") {
			alert("Kolom komentar belum diisi");
			return false;
		}
		if(document.formpesan.nama.value=="") {
			alert("Kolom nama belum diisi");
			return false;
		}
		if(document.formpesan.email.value=="") {
			alert("Kolom Email belum diisi");
			return false;
		}
		if(document.formpesan.alamat.value=="") {
			alert("Kolom alamat belum diisi");
			return false;
		}
		if(document.formpesan.code.value=="") {
			alert("Kolom kode vertifikasi salah belum diisi");
			return false;
		}
		return true;

}
</script>';
//tutup tambahan
$buku_tamu .="<form action='index.php' method='post' enctype='multipart/form-data' name='formpesan' onSubmit='return kirpesan();'>
<table><tr><td class='bg1'><font class='ver10'>Nama</font></td><td><input type='text' name='nama' maxlenght=20 size=30 class='editbox'></td></tr>
<tr><td class='bg1'><font class='ver10'>E-mail</font></td><td><input type='text' name='email' maxlenght=20 size=30 class='editbox'></td></tr>
<tr><td class='bg1'><font class='ver10'>Alamat</font></td><td><input type='text' class='editbox' name='alamat' maxlenght=30 size=55></td></tr>
<tr><td VALIGN=TOP class='bg1'><font class='ver10'>Komentar</font></td><td><textarea name='komentar' cols='55' rows='8' class='editbox'></textarea></td></tr>
<tr><td VALIGN=TOP class='bg1'><font class='ver10'></font></td><td><img src='../functions/captcha/captcha.php'><br>
Kode Verifikasi <br><input type='text' name='code' size='12' class='editbox' ></td></tr>
<input type='hidden' name='id' value='sim_buku'>
</table>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' name='submit' value='Kirim' class='art-button'>&nbsp;<input type='reset' class='art-button' name='reset' value='Ulang'></form>
<center>| <a href='index.php?id=lih_buku' >LIHAT BUKU TAMU</a> |<br><br></center>";

return $buku_tamu;
}

function sim_buku(){
include "koneksi.php";
$nama=$_POST['nama'];
$email=$_POST['email'];$alamat=$_POST['alamat'];
$komentar=$_POST['komentar'];

if ($nama == '' ) {
	die ("<body onload=\"alert('Nama masih kosong');window.history.back()\">");
}
elseif ($alamat == '' ) {
	die ("<body onload=\"alert('Alamat masih kosong');window.history.back()\">");
}
if ($komentar == '' ) {
	die ("<body onload=\"alert('Komentar masih kosong');window.history.back()\">");
}
session_start();
$code = $_POST['code'];
if ($code != $_SESSION['captcha']) {
	die ("<body onload=\"alert('Kode keamanan salah');window.history.back()\">");
}

if ($email == '' ) {
	die ("<body onload=\"alert('email masih kosong');window.history.back()\">");
}
else {
if (preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/",$email)) $a="";
else  die ("<body onload=\"alert('Email yang dimasukan tidak valid');window.history.back()\">");
	
 $sql = "SELECT max(id_buku) AS total FROM t_buku";
 if(!$r = mysql_query($sql))
 die("Error connecting to the database.");
 list($total) = mysql_fetch_array($r);
 $total += 1;
 $postdate = date("d/m/Y");
  $posttime = date("H:i:s");
 $komentar = htmlentities($komentar);
 $komentar = nl2br($komentar);

//====================================== Tambahan Ansari Saleh Ahmar Tgl. 18 Juni 2011
$potong_teks = explode(" ",$komentar);
$jumlah_kata = count($potong_teks);
$max = 30;

for($i = 0; $i <= $jumlah_kata; $i++){
if(strlen($potong_teks[$i]) >= $max){
for($j = 0; $j <= strlen($potong_teks[$i]); $j++){
$char[$j] = substr($potong_teks[$i],$j,1);

if(($j % $max == 0) && ($j != 0)){
$komen .= $char[$j] . ' ';
}else{
$komen .= $char[$j];
}

}
}else{
$komen .= " " . $potong_teks[$i] . " ";
}
} 
 $ip=$_SERVER['REMOTE_ADDR'];

$sim_buku .= "<table width='100%'  border=0  cellspacing='4' cellpadding='6'><tr><td height='25' ><font><b><center>Konfirmasi</center></b></font></td></tr><tr><td >";	   
$sql="INSERT INTO t_buku (id_buku,nama,email,alamat,komentar,posttime,postdate,ipkom) values ('$total','".mysql_real_escape_string($nama)."','".mysql_real_escape_string($email)."','".mysql_real_escape_string($alamat)."','".mysql_real_escape_string($komen)."','$posttime','$postdate','$ip')";
 if(!$result = mysql_query($sql)) {
          die("Pengisian Buku Tamu tidak berhasil, data ada yang salah coba kembali dan pilih Back ! <BR>$sql<BR>$mysql_error()</td></tr></table> ");
       }
 //$sim_buku .= "<font class='ver10'><center>Terima kasih Anda telah mengisi buku tamu ini<br><a href='index.php?id=lih_buku' style='color:000000;text-decoration:underline'>VIEW GUESTBOOK</a><br></center></font></td></tr></table>";						
 //header("Location:index.php?id=lih_buku");
  echo "<script>document.location.href = 'index.php?id=lih_buku';</script>";
 }
 
return $sim_buku;
}

function lih_buku() {
include "koneksi.php"; 
$hal=abs((int)$_GET['hal']);
$brs=20;
$kol=10;
  $byk_result1=mysql_query("select * from t_buku");
  $byk=mysql_num_rows($byk_result1);
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
 
     if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_buku order by id_buku DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  $lih_buku .= "<center>| <a href='index.php?&id=buku' >ISI BUKU TAMU</a> |</center><br>
  <table width='100%' cellspacing='1' cellpadding='2' class='art-article'  >  ";
  
  if ($jml!=0) {
  $lih_buku .="<tr><td colspan=2><center><a href='index.php?id=lih_buku&hal=1' style='color:000000;text-decoration:none' title='Hal 1'>Awal </a> 
  <a href='index.php?id=lih_buku&hal=$back' style='color:000000;text-decoration:none' title='Hal $back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	$lih_buku .= "<b><a href='index.php?id=lih_buku&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  		$lih_buku .= "<a href='index.php?id=lih_buku&hal=$i' style='color:000000;text-decoration:none' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $lih_buku .="<a href='index.php?id=lih_buku&hal=$next' style='color:000000;text-decoration:none' title='Hal $next'> Lanjut</a> 
  <a href='index.php?id=lih_buku&hal=$jml' style='color:000000;text-decoration:none' title='Hal $jml'> Akhir</a></center></td></tr>";
  }
	$lih_buku .="<tr><th>Pengirim</th><th>Komentar</th></tr>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
		$warna = "td1";
		if ($j==1) {
		$warna = "td2";
		$j=0; }
		else $j=1;
		$em = substr($row[email],0,15);
		if ($row[tanggapan]=='') $tanggap="";
		else $tanggap="<br><b>:: Jawaban ::</b><br><i>$row[tanggapan]</i>";
  $lih_buku .="<tr > 
    <td width='30%' valign=top class='$warna'><img src='../images/buku.gif' style='border:0;margin:0;' ><b>$row[nama]</b><br>
	<img src='../images/email2.gif'  style='border:0;margin:0;' >&nbsp;<a href='mailto:$row[email]' title='$row[email]' >$em</a> <br>
	Lokasi : $row[alamat]<br>
	<img src='../images/ip.gif' style='border:0;margin:0;'  >&nbsp; $row[ipkom]<br></td>
	<td valign=top class='$warna'><font class='ver10'><img src='../images/post.gif' style='border:0;margin:0;' > $row[postdate], $row[posttime]<br>
<hr style='border: dashed 1px;'>
$row[komentar]$tanggap</td>"; 
	  }  
  $lih_buku .= "</table><br><center>| <a href='index.php?&id=buku' >ISI BUKU TAMU</a> |<br></center><br>";						
return $lih_buku;
}
?>