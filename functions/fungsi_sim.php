<?php
session_start();
if (!isset($_SESSION['Admin'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}
include("koneksi.php");
$mode=$_POST['mode'];
$edit=$_POST['edit'];
$file = $_FILES['file']['tmp_name'];
$file_name = $_FILES['file']['name'];

if ($mode=="download_save") {
	 $judul = $_POST['judul'];
	 $ket = $_POST['ket'];
	 $kategori =$_POST['kategori'];
	 if ($judul=='') $judul=$_GET['judul'];
	 if ($ket=='') $ket=$_GET['ket'];
	 if ($kategori=='') $kategori=$_GET['kategori'];
  $tgl = date("d/m/Y")." ".date("H:i:s");

   
 if ($edit==2) {
     $sql = "SELECT max(id) AS total FROM t_download";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	//$total = rand(1,5);

	 
	if (file_exists($file)) {

		$p = array_pop(explode(".", $file_name));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx' && $p<>'pptx' && $p<>'ppt' && $p<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../".$folderadmin."/admin.php?mode=download'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

    	$newfile="al".$total.".".$p;
    	$ukuran =filesize($file);
    	$n=strlen($ukuran);
    	if ($n>3) $ukuran=substr($ukuran,0,$n-3).",".substr($ukuran,$n-3,2)." Kbytes";
    	else $ukuran.=" bytes";
        move_uploaded_file($file, "../download/$newfile");

	}
	else {
		$tdk="File download yang diimputkan tidak ada";
		}
    $sql = "insert into t_download (id,judul,deskripsi,kategori,file,ukuran,tanggal) values ('$total','".mysql_real_escape_string($judul)."','".mysql_real_escape_string($ket)."','".mysql_real_escape_string($kategori)."','$newfile','$ukuran','".mysql_real_escape_string($tgl)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal 1 ");
  //echo "<font>$tdk<br> Penambahan download berhasil<br>Silahkan pilih menu kembali !!!</font>"; 
 }
 elseif ($edit==1) {
    $idn = $_POST['idn'];
	if ($idn=='') $idn =$_GET['idn'];
   if(empty($file)) {
		
   $sql = "update t_download set deskripsi='".mysql_real_escape_string($ket)."',judul='".mysql_real_escape_string($judul)."',kategori='".mysql_real_escape_string($kategori)."',tanggal='$tgl' where id='".mysql_real_escape_string($idn)."'";
    }
    else {
	if (file_exists($file)) {
	$tdk='';
	$p = array_pop(explode(".", $file_name));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx' && $p<>'pptx' && $p<>'ppt' && $p<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../".$folderadmin."/admin.php?mode=download'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

    // cek file lama dihapus
	   $q = mysql_query("select file from t_download where id='".mysql_real_escape_string($idn)."'");
        if($r=mysql_fetch_array($q)) {
            if(file_exists("../download/".$r['file'])) {
                unlink("../download/".$r['file']);
            }
        }
    move_uploaded_file($file, "../download/al".$idn.".".$p);
	$ukuran =filesize($file);
	$n=strlen($ukuran);
	$nmfile ="al".$idn.".".$p;
	if ($n>3) $ukuran=substr($ukuran,0,$n-3).",".substr($ukuran,$n-3,2)." Kbytes";
	else $ukuran.=" bytes";
	}
	else {
		$tdk="Perubahan File download tidak berhasil ! File tidak diketemukan";
		}
		 $sql = "update t_download set deskripsi='".mysql_real_escape_string($ket)."',judul='".mysql_real_escape_string($judul)."',kategori='".mysql_real_escape_string($kategori)."',ukuran='$ukuran',file='$nmfile',tanggal='$tgl'  where id='".mysql_real_escape_string($idn)."'";

    }
	if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
 // echo "<font>$tdk<br>Perubahan download berhasil<br>Silahkan pilih menu kembali !!!</font>";
 }    
 //echo $sql;
 echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=download';</script>";
 //header("Location: ../".$folderadmin."/admin.php?mode=download");
 }
elseif ($mode=="soal_save") {
	 $judul = $_POST['judul'];
	 $ket = $_POST['ket'];
	 $kategori =$_POST['kategori'];
	 if ($judul=='') $judul=$_GET['judul'];
	 if ($ket=='') $ket=$_GET['ket'];
	 if ($kategori=='') $kategori=$_GET['kategori'];
$tgl = date("d/m/Y")." ".date("H:i:s");
 if ($edit==2) {
     $sql = "SELECT max(id) AS total FROM t_soal";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	//$total = rand(1,5);
	 
	if (file_exists($file)) {
		$p = array_pop(explode(".", $file_name));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx' && $p<>'pptx' && $p<>'ppt' && $p<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../".$folderadmin."/admin.php?mode=soal'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

    	$newfile="soal".$total.".".$p;
    	$ukuran =filesize($file);
    	$n=strlen($ukuran);
    	if ($n>3) $ukuran=substr($ukuran,0,$n-3).",".substr($ukuran,$n-3,2)." Kbytes";
    	else $ukuran.=" bytes";
	
        move_uploaded_file($file, "../soal/$newfile");
		}
	else {
		$tdk="File soal yang diimputkan tidak ada";
		}
    $sql = "insert into t_soal (id,judul,deskripsi,kategori,file,ukuran,tanggal) values ('$total','".mysql_real_escape_string($judul)."','".mysql_real_escape_string($ket)."','".mysql_real_escape_string($kategori)."','$newfile','$ukuran','$tgl')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal 1 ");
  //echo "<font>$tdk<br> $file_name, $file,$ukuran, $newfile, $p Penambahan download berhasil<br>Silahkan pilih menu kembali !!!</font>"; 
 }
 elseif ($edit==1) {
    $idn = $_POST['idn'];
	if ($idn=='') $idn =$_GET['idn'];
   if($file == '') {
   $sql = "update t_soal set deskripsi='".mysql_real_escape_string($ket)."',judul='".mysql_real_escape_string($judul)."',kategori='".mysql_real_escape_string($kategori)."',tanggal='$tgl' where id='".mysql_real_escape_string($idn)."'";
    }
    else {
	if (file_exists($file)) {
	    $tdk='';
		$p = array_pop(explode(".", $file_name));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx' && $p<>'pptx' && $p<>'ppt' && $p<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../".$folderadmin."/admin.php?mode=soal'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

    // cek file lama dihapus
	   $q = mysql_query("select file from t_soal where id='".mysql_real_escape_string($idn)."'");
        if($r=mysql_fetch_array($q)) {
            if(file_exists("../soal/".$r['file'])) {
                unlink("../soal/".$r['file']);
            }
        }
        move_uploaded_file($file, "../soal/soal".$idn.".".$p);
	$ukuran =filesize($file);
	$n=strlen($ukuran);
	$nmfile ="al".$idn.".".$p;
	if ($n>3) $ukuran=substr($ukuran,0,$n-3).",".substr($ukuran,$n-3,2)." Kbytes";
	else $ukuran.=" bytes";
	}
	else {
		$tdk="Perubahan File soal tidak berhasil ! File tidak diketemukan";
		}
		 $sql = "update t_soal set deskripsi='".mysql_real_escape_string($ket)."',judul='".mysql_real_escape_string($judul)."',kategori='".mysql_real_escape_string($kategori)."',ukuran='$ukuran',file='$nmfile',tanggal='$tgl'  where id='".mysql_real_escape_string($idn)."'";
	}
	if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
 // echo "<font>$tdk<br>Perubahan download berhasil<br>Silahkan pilih menu kembali !!!</font>";
 }    
 //header("Location: ../".$folderadmin."/admin.php?mode=soal");
  echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=soal';</script>";
 } 
 
else {
$tgl = date("d/m/Y")." ".date("H:i:s");
	 $pelajaran = $_POST['pelajaran'];
	 $ket = $_POST['ket'];
	 $kelas =$_POST['kelas'];
	 $sem =$_POST['sem'];
	 if ($pelajaran=='') $pelajaran=$_GET['pelajaran'];
	 if ($ket=='') $ket=$_GET['ket'];
	 if ($kelas=='') $kelas=$_GET['kelas'];
 if ($edit==2) {
     $sql = "SELECT max(id) AS total FROM t_silabus";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	//$total = rand(1,5);
	 
	if (file_exists($file)) {
		$p = array_pop(explode(".", $file_name));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx' && $p<>'pptx' && $p<>'ppt' && $p<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../".$folderadmin."/admin.php?mode=silabus'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

	   $newfile="sil".$total.".".$p;

        move_uploaded_file($file, "../silabus/$newfile");
		}
	else {
		$tdk="File silabus yang diimputkan tidak ada";
		}
    $sql = "insert into t_silabus (id,pelajaran,ket,kelas,file,tanggal,semester) values ('$total','".mysql_real_escape_string($pelajaran)."','".mysql_real_escape_string($ket)."','".mysql_real_escape_string($kelas)."','$newfile','$tgl','".mysql_real_escape_string($sem)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal 1 ");
  //echo "<font>$tdk<br> Penambahan silabus berhasil<br>Silahkan pilih menu kembali !!!</font>"; 
 }
 elseif ($edit==1) {
    $idn = $_POST['idn'];
	if ($idn=='') $idn =$_GET['idn'];
   if($file == '') {
   $sql = "update t_silabus set semester='".mysql_real_escape_string($sem)."',ket='".mysql_real_escape_string($ket)."',pelajaran='".mysql_real_escape_string($pelajaran)."',kelas='".mysql_real_escape_string($kelas)."',tanggal='$tgl' where id='".mysql_real_escape_string($idn)."'";
    }
    else {
	   if (file_exists($file)) {
	   $tdk='';
		$p = array_pop(explode(".", $file_name));

//Penambahan Fitur Filter File yang bisa di upload  By Ansari Saleh Ahmar 31/10/2013
		if ($p<>'doc' && $p<>'xls' && $p<>'pdf' && $p<>'zip' && $p<>'docx' && $p<>'xlsx' && $p<>'pptx' && $p<>'ppt' && $p<>'swf' )
		{
		//echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='../".$folderadmin."/admin.php?mode=silabus'>Kembali</a>";
		echo "Format harus file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP., <a href='javascript:history.back()'> Kembali</ a>";
		exit;}
// Akhir

        move_uploaded_file($file, "../silabus/sil".$idn.".".$p);
	   $nmfile ="sil".$idn.".".$p;
           // cek file lama dihapus
	   $q = mysql_query("select file from t_silabus where id='".mysql_real_escape_string($idn)."'");
        if($r=mysql_fetch_array($q)) {
            if(file_exists("../silabus/".$r['file'])) {
                unlink("../silabus/".$r['file']);
            }
        }
	}
	else {
		$tdk="Perubahan File silabus tidak berhasil ! File tidak diketemukan";
		}
		 $sql = "update t_silabus set semester='".mysql_real_escape_string($sem)."',ket='".mysql_real_escape_string($ket)."',pelajaran='".mysql_real_escape_string($pelajaran)."',kelas='".mysql_real_escape_string($kelas)."',file='$nmfile',tanggal='$tgl' where id='".mysql_real_escape_string($idn)."'";
	}
	if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
 // echo "<font>$tdk<br>Perubahan silabus berhasil<br>Silahkan pilih menu kembali !!!</font>";
 }    
// header("Location: ../".$folderadmin."/admin.php?mode=silabus");
 echo "<script>document.location.href = '../".$folderadmin."/admin.php?mode=silabus';</script>";
 
 }
 
 //echo "<font>$tdk<br>".$_FILES['file']['name']." $file, $newfile Penambahan download berhasil<br>Silahkan pilih menu kembali !!!</font>";
?>