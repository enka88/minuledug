<?php
session_start();
if ( !isset($_SESSION['User']['userid']) )
{
echo "<h1><p align='center'>Permission Denied<br>by Ansari Saleh Ahmar</p></h1><p align='center'><br><br>You don't have permission to access the this page. <br>(Anda tidak memiliki akses untuk halaman ini.)</p>";
exit;
}
?>

<html>
<head>
<title>upload file</title>
<style type="text/css" media="screen">
#fotoupload-atas {
	background-color:#6A849D;
	height:15px;
	padding:5px;
	font-size:14px;
	color:#FFFFFF;
	font-weight:bold;
	margin-bottom:10px;
	font-family:Arial, Helvetica, sans-serif;
}
#button   {
	border: 1pt solid #6A849D;
	font: normal 12px tahoma;
	color: #FFFFFF;
	background-color:#6A849D;
	margin:0px 0px 5px 0px;
	padding:2px 6px 2px 6px;
	text-decoration:none;
}
#button:hover  {
	border: 1pt solid #245F98;
	font: normal 12px tahoma;
	color: #FFFFFF;
	background-color:#245F98;
	margin:0px 0px 5px 0px;
	cursor:pointer;
	text-decoration:none;
}
#upload_process{
   z-index:100;
   position:absolute;
   text-align:center;
   width:400px;
   font-family:Arial, Helvetica, sans-serif;
}
#upload_form {
	font-size: 12px;
	font-weight: normal;
	color: #666666;
	font-family:Arial, Helvetica, sans-serif;
}
</style> 
</head>
<body>
<?php
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
$id=$_POST['id'];
if (empty($id)) $id=$_GET['id']; 
$userid = unhex($_GET['userid'],$noacak);

if ($id=='uploadfoto') { // upload file foto

$kdalbum = unhex($_GET['kdalbum'],$noacak);
echo "<div id='fotoupload-atas'>Upload Foto </div>";
	$sql = mysql_query("select keterangan,idalbum from t_memberfoto_album where userid='". mysql_real_escape_string($userid). "'");	
	while($r=mysql_fetch_array($sql)) {
		if ($r[idalbum]==$kdalbum) $seleksi .="<option value='$r[idalbum]' selected >$r[keterangan]</option>";
		else $seleksi .="<option value='$r[idalbum]' >$r[keterangan]</option>";
	}

echo "<form action='uploadfoto.php' method='post' enctype='multipart/form-data'  > 
<p id='upload_form'>Pilih file gambar dari komputer anda.<br>Format file harus JPG. Ukuran file maksimal 300 kbyte.<br>
<table border='0' id='upload_form'>
<tr><td>Album </td><td>: <select name='kdalbum' >$seleksi</select></td></tr>
<tr><td>File Foto </td><td>: <input type='file' name='myfile' id='myfile' ></td></tr>
<tr><td>Keterangan </td><td>: <input type='text' name='ket' id='ket'  maxlength='255'  size='50'  ></td></tr>
<tr><td>&nbsp;</td><td >&nbsp; <input type='checkbox' name='private' value='on' /> Foto khusus pribadi / private <img src='../images/open1.png' ><br>&nbsp; <input type='checkbox' name='crop' value='on' /> Apabila file terlalu besar maka gambar akan dipotong.</td></tr>
<tr><td></td><td><br>&nbsp;&nbsp;<input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
</table></p><input type=hidden name='userid' value='".hex($userid,$noacak)."' >
<input type=hidden id='id' name='id' value='2' >
</form> ";
//batas bawah id untuk upload file
}
elseif ($id==2) {
include "../functions/koneksi.php";
include "../functions/fungsi_crop.php";
$userid = unhex($_POST['userid'],$noacak);
$myfile = $_FILES['myfile'];
$kdalbum =$_POST['kdalbum'];
$ket =$_POST['ket'];
$ket = htmlentities($ket);
$crop =$_POST['crop'];
$private =$_POST['private'];
if ($private=='on') $private='1';
else $private='0';
echo "<div id='fotoupload-atas'>Upload Foto</div>
<p id='upload_form'>";
$result='';
$sukses='';
 //cek filenya kosong ega
 if (!empty($myfile['name'])) {
 	$ukuran=$myfile['size'];
	$a=$myfile['name'];
	$m=strlen($a);
	$type=strtolower(substr($a,$m-3,3));
	
   if ($ukuran >= 307200 and $crop=='' ) { // cek ukuran file
   		$result = "File gambar terlalu besar, maksimal 300 Kbyte. Silahkan klik close dan klik upload foto kembali";
   }
   else if ($kdalbum=='') {
   		$result = "Album masih kosong. Silahkan klik close dan klik upload foto kembali";
   }
   else if ($type<>'jpg') {
   		$result = "Format gambar salah, harus JPG. Silahkan klik close dan klik upload foto kembali";
   }
   else {
 	// insert sql
	$sql = mysql_query("select max(idfoto) as id from t_memberfoto");	
	$r=mysql_fetch_array($sql);
	$total = $r[id]+1;
 	$sql2 = mysql_query("insert into t_memberfoto (idfoto,idalbum,tanggal,judul,stopen) values ('$total','".mysql_real_escape_string($kdalbum)."',NOW(),'".mysql_real_escape_string($ket)."','".mysql_real_escape_string($private)."') ");	
   $target_file = "foto/foto".$total.".jpg";	
   	if(@move_uploaded_file($myfile['tmp_name'], $target_file)) { //file di upload
		//file diubah ukurannya
		$size = getimagesize($target_file); // size[0] itu lebar,,,,,size[1]=tinggi
		$lebar = 600;
		if ( $size[0] > $lebar ) {
			$tinggi = round(($size[1]*$lebar)/$size[0],0); // mencari perbandingan ukuran lebar dan tinggi
	  		cropImage($lebar, $tinggi, "$target_file", 'jpg', "$target_file"); // crop file
			$sukses ="File gambar berhasil dipotong dan diperkecil.<br> ";
		}
		
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		// penambahan informasi bahwa anda telah mengupload foto baru
		// kecuali file foto private 
		if ($private=='0') {
		$sql2=mysql_query("select *  from t_memberstatus where pengirim='".mysql_real_escape_string($userid)."' and jenis='1' order by idstatus desc limit 0,5 ");
		$pesan = "$kdalbum|$total"; 
		while($r=mysql_fetch_array($sql2)) {
			list($album,$target) = explode("\|",$r[pesan]);
			if ($album==$kdalbum) {
				$nfile = explode(",",$target,3);
				$n=count($nfile);
				if ($n>2) $n=1;
				else $n=$n-1;
				for($i=$n;$i>=0;$i--) {
					$tar .= ",".$nfile[$i];
				}
				$pesan = "$kdalbum|$total".$tar;
				$idstatus=$r[idstatus];
			}
		}
		if ($idstatus=='') { $sql ="insert into t_memberstatus (userid,pengirim,tanggal,pesan,jenis) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string($userid)."',NOW(),'$pesan','1' )"; }
		else { $sql ="update t_memberstatus set tanggal=NOW(),pesan='$pesan' where idstatus='".mysql_real_escape_string($idstatus)."' "; }
		$q2=mysql_query($sql);
		// batas akhir input status klo tidak private
		}
		
		$sukses .= "File gambar berhasil diupload. <br>Silahkan klik Close dan refresh koleksi album foto tersebut.";
   	}
   }
   sleep(1);
  }
  else $result ="File Gambar kosong. <a href='#' id='button' onClick='history.back()' > Kembali </a>";
  echo "$result $sukses </p>";
}
elseif ($id=='editfoto') {

$kdfoto = unhex($_GET['kdfoto'],$noacak);
	echo "<div id='fotoupload-atas'>Perubahan Data Foto</div><p id='upload_form'>";
$sql = mysql_query("select * from t_memberfoto where idfoto='". mysql_real_escape_string($kdfoto). "'");	
if($row=mysql_fetch_array($sql)) {
	$kdalbum=$row[idalbum];
	$sql = mysql_query("select keterangan,idalbum from t_memberfoto_album where userid='". mysql_real_escape_string($userid). "'");	
	while($r=mysql_fetch_array($sql)) {
		if ($r[idalbum]==$kdalbum) $seleksi .="<option value='$r[idalbum]' selected >$r[keterangan]</option>";
		else $seleksi .="<option value='$r[idalbum]' >$r[keterangan]</option>";
	}
	$nfile ="foto/foto$kdfoto.jpg";
	if (file_exists(''.$nfile.'')) {
		$file ="<img src='thumb.php?img=$nfile' width='100' height='80'>";
	}
	if ($row[stopen]=='1') $private ='checked="checked"';
	else $private='';
	
echo "<form action='uploadfoto.php' method='post'  > 
<p id='upload_form'><table border='0' id='upload_form'>
<tr><td>Album </td><td>: <select name='kdalbum' >$seleksi</select></td></tr>
<tr><td vlign=top >File Foto </td><td>: $file</td></tr>
<tr><td>Keterangan </td><td>: <input type='text' name='ket' id='ket' value='$row[judul]' maxlength='255'  size='50'  ></td></tr>
<tr><td>&nbsp;</td><td >&nbsp; <input type='checkbox' name='private' value='on' $private /> Foto khusus pribadi / private <img src='../images/open1.png' ><br>&nbsp; <input type='checkbox' name='tgl' value='on' /> Apakah tanggal upload diupdate juga ?</td></tr><input type=hidden name='kdfoto' value='".hex($kdfoto,$noacak)."' >
<tr><td></td><td><br>&nbsp;&nbsp;<input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
</table></p><input type=hidden name='userid' value='".hex($userid,$noacak)."' >
<input type=hidden id='id' name='id' value='editfotosave' >
</form> ";
  }
	echo "</p>";
}
elseif ($id=='editfotosave') {

	$kdalbum =$_POST['kdalbum'];
	$kdfoto = unhex($_POST['kdfoto'],$noacak);
	$ket =$_POST['ket'];
	$ket = htmlentities($ket);
	$private =$_POST['private'];
	$tgl =$_POST['tgl'];
	if ($private=='on') $private='1';
	else $private='0';
	if ($tgl=='on') $tgl = ",tanggal=NOW() ";
	else $tgl='';
	$q=mysql_query("update t_memberfoto set idalbum='".mysql_real_escape_string($kdalbum)."', judul='".mysql_real_escape_string($ket)."',stopen='".mysql_real_escape_string($private)."' $tgl where idfoto='".mysql_real_escape_string($kdfoto)."' ");
	echo "<div id='fotoupload-atas'>Perubahan Data Foto</div><p id='upload_form'>";
	echo "Perubahan berhasil dilakukan. ";
}
elseif ($id=='editalbum') {
	$kdalbum = unhex($_GET['kdalbum'],$noacak);

	echo "<div id='fotoupload-atas'>Perubahan Data Album </div><p id='upload_form'>";
$sql = mysql_query("select * from t_memberfoto_album where idalbum='". mysql_real_escape_string($kdalbum). "'");	
if($row=mysql_fetch_array($sql)) {
	
echo "<form action='uploadfoto.php' method='post'  > 
<p id='upload_form'><table border='0' id='upload_form'>
<tr><td>Nama Album </td><td>: <input type=text name='ket' value='$row[keterangan]' maxlength='255'  size='50' ></td></tr>
<input type=hidden name='kdalbum' value='".hex($kdalbum,$noacak)."' >
<tr><td></td><td><br>&nbsp;&nbsp;<input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
</table></p><input type=hidden name='userid' value='".hex($userid,$noacak)."' >
<input type=hidden id='id' name='id' value='editalbumsave' >
</form> ";
  }
	echo "</p>";

}
elseif ($id=='editalbumsave') {

	$kdalbum =unhex($_POST['kdalbum'],$noacak);
	$ket =$_POST['ket'];
	$ket = htmlentities($ket);
	$userid = unhex($_POST['userid'],$noacak);
	$q=mysql_query("update t_memberfoto_album set keterangan='".mysql_real_escape_string($ket)."' where idalbum='".mysql_real_escape_string($kdalbum)."' and userid='".mysql_real_escape_string($userid)."' ");
	echo "<div id='fotoupload-atas'>Perubahan Data Album</div><p id='upload_form'>";
	echo "Perubahan berhasil dilakukan.";
}
elseif ($id=='fotoprofil') {

echo "<div id='fotoupload-atas'>Ganti Foto Profil</div>";
echo "<form action='uploadfoto.php' method='post' enctype='multipart/form-data'  > 
<p id='upload_form'>Pilih file gambar dari komputer anda.<br>Format file harus JPG. Ukuran file maksimal 300 kbyte.<br>
File Foto : <input type='file' name='myfile' id='myfile' >&nbsp;&nbsp;<input type=submit name='submit' value=' Simpan ' id='button' ></p><input type=hidden name='userid' value='".hex($userid,$noacak)."' ><input type=hidden id='id' name='id' value='simfotoprofil' ></form> ";

}
elseif ($id=='simfotoprofil') {
include "../functions/koneksi.php";
include "../functions/fungsi_crop.php";
$userid = unhex($_POST['userid'],$noacak);
$myfile = $_FILES['myfile'];
echo "<div id='fotoupload-atas'>Ganti Foto Profil</div>
<p id='upload_form'>";
$result='';
$sukses='';
 //cek filenya kosong ega
 if (!empty($myfile['name'])) {
 	$ukuran=$myfile['size'];
	$a=$myfile['name'];
	$m=strlen($a);
	$type=strtolower(substr($a,$m-3,3));
	
   if ($ukuran >= 307200 and $crop=='' ) { // cek ukuran file
   		$result = "File gambar terlalu besar, maksimal 300 Kbyte. <a href='#' id='button' onClick='history.back()' > Kembali </a>";
   }
   else if ($type<>'jpg') {
   		$result = "Format gambar salah, harus JPG. <a href='#' id='button' onClick='history.back()' > Kembali </a>";
   }
   else {
    $target_file = "profil/gb".$userid.".jpg";	
   	if(@move_uploaded_file($myfile['tmp_name'], $target_file)) { //file di upload
		//file diubah ukurannya
		$size = getimagesize($target_file); // size[0] itu lebar,,,,,size[1]=tinggi
		$lebar = 200;
		if ( $size[0] > $lebar ) {
			$tinggi = round(($size[1]*$lebar)/$size[0],0); // mencari perbandingan ukuran lebar dan tinggi
	  		cropImage($lebar, $tinggi, "$target_file", 'jpg', "$target_file"); // crop file
			$sukses ="File gambar berhasil dipotong dan diperkecil.<br> ";
		}
		$sukses .= "File gambar berhasil diupload. <br>Silahkan klik Close dan refresh koleksi album foto tersebut.";
   	}
   }
   sleep(1);
  }
  else $result ="File Gambar kosong. <a href='#' id='button' onClick='history.back()' > Kembali </a>";
  echo "$result $sukses </p>";
}
?>

</body>
</html>