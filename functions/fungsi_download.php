<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}

/* Tambahan Ansari Saleh Ahmar 09 April 212 
Agar tidak muncul error pada saat mengakses : index.php?id=namaid&hal=-1
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '-30,30' at line 1
$hal=abs((int)$_GET['hal']); menjadi $hal=abs((int)$_GET['hal']);

buka materi ajar halaman ke-2 dan seterusnya kok ga bisa, masuknya kok ke profil guru

"guru.php?id=download&kode=$kode&hal=" menjadi "guru.php?id=down&kode=$kode&hal="
*/


function download() {
include "koneksi.php";
$kode=$_GET['kode'];
$hal=abs((int)$_GET['hal']);

    if ($kode=='') {
    	$query="select idpel,pel from t_pelajaran order by pel";
    	$alan=mysql_query($query) or die ("query gagal");
    	if ($row=mysql_fetch_array($alan)) {
    	   $kode =$row[pel];   
    	}
    }

$download .= "<table border=0 width=100% class='art-article'><tr><td ><center>| ";
	$query = "SELECT idpel,pel FROM t_pelajaran order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[pel]) $download .="<b><a href='guru.php?id=materi&kode=$k[pel]' class=ver11>$k[pel]</a></b> | ";
		else $download .="<a href='guru.php?id=materi&kode=$k[pel]' class=ver10>$k[pel]</a> | ";
	}
	$query = "SELECT * FROM t_kategori where jenis='1' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[kategori]) $download .="<b><a href='guru.php?id=materi&kode=$k[kategori]' class=ver11>$k[kategori]</a></b> | ";
		else $download .="<a href='guru.php?id=materi&kode=$k[kategori]' class=ver10>$k[kategori]</a> | ";
	}
	$download .="</center></td></tr></table><br>";

  $brs=20;
  $kol=10;
  $byk_result=mysql_query("select * from t_download where kategori='".mysql_real_escape_string($kode)."'");
  $byk=mysql_num_rows($byk_result);
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
  
  $query = "SELECT * FROM t_download where kategori='".mysql_real_escape_string($kode)."' order by 'judul'  LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) ;
	if ($num_of_rows==0) {$download .="<table border=0 class='art-article' width=100% ><tr><td  ><center>
	<br>Materi Ajar untuk Kategori <b>$kate</b> belum ada, masih dalam penyusunan.<br>
	Silahkan kirim materi yang anda miliki ke Email kami <b><a href='mailto:$webmail' class=ver10>$webmail</a></b>
	<br>----- Terima Kasih -----<br></center></td></tr></table>";}
  // tambah alan untuk delete multiple	
  $download .= "<br><table width='100%' border=0 class='art-article'  >";
  if ($jml!=0) {
  $download .= "<tr><td colspan=2 ><center><font class='ver10'><a href='guru.php?id=down&kode=$kode&hal=1' class=ver10 title='Hal 1'>Awal </a> 
  <a href='guru.php?id=down&kode=$kode&hal=$back' class=ver10 title='$back'>Sebelum </a> |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$download .= "<b><a href='guru.php?id=down&kode=$kode&hal=$i' class=ver10 title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$download .= "<a href='guru.php?id=down&kode=$kode&hal=$i' class=ver10 title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $download .= "<a href='guru.php?id=down&kode=$kode&hal=$next' class=ver10 title='$next'> Lanjut</a> 
  <a href='guru.php?id=down&kode=$kode&hal=$jml' class=ver10 title='Hal $jml'> Akhir</a></font></center></td></tr>";
  }
  while ($row = mysql_fetch_array($query_result_handle))
  {
  $subject = $row[judul];
  	$n=strlen($row[file]);
	$p=strtolower(substr($row[file],$n-3,3));
	if ($p=='xls') $file='../images/xls.gif';
	elseif ($p=='pdf') $file='../images/pdf.gif';
	elseif ($p=='avi') $file='../images/avi.gif';
	elseif ($p=='mp3') $file='../images/avi.gif';
	elseif ($p=='bmp') $file='../images/bmp.gif';
	elseif ($p=='doc') $file='../images/doc.gif';
	elseif ($p=='exe') $file='../images/exe.gif';
	elseif ($p=='swf') $file='../images/swf.gif';
	elseif ($p=='jpg') $file='../images/jpg.gif';
	elseif ($p=='ppt') $file='../images/ppt.gif';
	elseif ($p=='rar') $file='../images/rar.gif';
	elseif ($p=='txt') $file='../images/txt.gif';
	elseif ($p=='zip') $file='../images/zip.gif';
	else $file='../images/dll.png';
   $download .= "<tr > <td width='5%' valign=top ><a href='guru.php?id=down&kode=$row[id]'><img src='$file' style='border:0' align=left title='klik untuk mendownload file'><a/></td>
    <td ><font class='ver10'><b><a href='guru.php?id=lihmateri&kode=$row[id]' class=ver10>$subject</a></b><br>$row[deskripsi]<BR>
	<I>File ini berukuran $row[ukuran] </i>&nbsp;&nbsp;<br><i>Telah diakses oleh pengunjung sebanyak $row[visit] kali</i>
	</font></td></tr>";
 }        
  $download .= "</table><br>";

return $download;
}

function down() {
$kode=$_GET['kode'];
	$query="select * from  t_download where id='".mysql_real_escape_string($kode)."'";
	$alan=mysql_query($query) or die ("query gagal");
	$row=mysql_fetch_array($alan);
	$n =$row[visit];
	$n++;
		$query="update t_download set visit='$n' where id='".mysql_real_escape_string($kode)."'";
	$alan=mysql_query($query) or die ("query gagal");
	//header("Location: ../download/$row[file]");
    echo "<script>document.location.href = '../download/$row[file]';</script>";
    
return $down;
}

function lihmateri() {
$kode=$_GET['kode'];
$sql="select * from t_download where id='".mysql_real_escape_string($kode)."'";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal");
	$row = mysql_fetch_array($alan);
	  	$n=strlen($row[file]);
	$p=strtolower(substr($row[file],$n-3,3));
	if ($p=='xls') $file='../images/xls.gif';
	elseif ($p=='pdf') $file='../images/pdf.gif';
	elseif ($p=='avi') $file='../images/avi.gif';
	elseif ($p=='mp3') $file='../images/avi.gif';
	elseif ($p=='bmp') $file='../images/bmp.gif';
	elseif ($p=='doc') $file='../images/doc.gif';
	elseif ($p=='exe') $file='../images/exe.gif';
	elseif ($p=='swf') $file='../images/swf.gif';
	elseif ($p=='jpg') $file='../images/jpg.gif';
	elseif ($p=='ppt') $file='../images/ppt.gif';
	elseif ($p=='rar') $file='../images/rar.gif';
	elseif ($p=='txt') $file='../images/txt.gif';
	elseif ($p=='zip') $file='../images/zip.gif';
	else $file='../images/dll.png';
	
	$materi .= "<h3><center>$row[judul]</center></h3>
	<img src='../images/arrow.gif'> Tanggal  : $row[tanggal]<hr style='border: dashed 1px;'>";
	$materi .= "$row[deskripsi]<br><br><center><table border=0 class='art-article'  width=300  ><tr><td>
	<center><img src='$file' style='border:0' ></td><td><center><a href='guru.php?id=down&kode=$row[id]' target='_blank' class='art-button' >Download File</a></center></td></tr>
	<tr><td colspan=2><center>	<I>File berukuran $row[ukuran] </i>&nbsp;<br>Telah didownload $row[visit] kali</td></tr></table>
	</center>";
	
return $materi;
}
?>