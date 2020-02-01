<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
/****************************************** Berita Options ********************************************/
require('../lib/config.php');
class postsclass {
  // memanggil berita
 function modpost() {
 include "koneksi.php";
 $msgid=$_GET['msgid'];
  $query = "SELECT * FROM t_news  WHERE id='". mysql_escape_string($msgid)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $msg = mysql_fetch_array($result);
  
 include "functions_editor.php";
 echo editor_full();
  $msg[isi] = str_replace("<BR>", "\n", $msg[isi]);
  echo "<font><form action='admin.php' method=\"post\" name=\"f1\"  enctype=\"multipart/form-data\">
          <table  border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr> 
              <td width='15%' ><font>Topik:</font></td>
              <td width='76%'> 
                <input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"100\" value=\"$msg[subject]\">
              </td>
            </tr>
            <tr> 
              <td colspan=2 height=\"21\"><font>Isi Berita:</font><br> ";

echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%">'.$msg[isi].'</textarea>'; 
 echo" </td>
            </tr>
			<tr> 
              <td width='24%' valign=top ><font>File Gambar</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' > <font>Format file JPG (Lebar 435px x Tinggi 200px)<br>
				| <input type='checkbox' name='cropgbr' value='on' checked > Gambar di Crop atau dipotong menjadi 435 x 200 px |
				<br>| <input type='checkbox' name='delgbr' value='on' > Hapus Gambar |<br>
              </td>
            </tr>
            <tr> 
              <td width='100%' colspan='2'> 
                <input type=\"hidden\" name=\"mode\" value=\"ModSave\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"hidden\" name=\"msgid\" value=\"$msgid\">
                <input type=\"submit\" value=\"Simpan\" >
              </td>
            </tr>
          </table>
  </form></font>";
  
 } 

 // Saving edit berita
 function modsave() {
 include "koneksi.php";
 include "fungsi_crop.php";
 $FileToUpload = $_FILES['FileToUpload']['tmp_name'];
  $news=stripslashes($_POST['richEdit0']);
  $subject=$_POST['subject']; $msgid=$_POST['msgid'];
  $delgbr=$_POST['delgbr'];
  $cropgbr=$_POST['cropgbr'];
  
  $subject = addslashes($subject);
  $news= str_replace("\n", "<BR>", $news);
  $gb="../images/berita/gb$msgid.jpg";
   	if ($delgbr=='on') {
			if (file_exists($gb)) unlink($gb);
	}
    if($FileToUpload == '') {
    }
    else {
	if (file_exists($FileToUpload)) {
	$tdk='';
		if (file_exists($gb)) unlink($gb);
		
		  if($cropgbr=='on')  {
			$newfile="../images/berita/temp.jpg";
			if (file_exists($newfile)) unlink($newfile);
			copy($FileToUpload, $newfile);
		//	cropImage(435, 200, "$newfile", 'jpg', "$gb");
            crop_image($newfile,$gb,435,200);
		  }
		  else {
    		copy($FileToUpload, $gb);
		 }
		
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		$newfile='';
		}
	}
  $sql = "UPDATE t_news SET isi='".mysql_real_escape_string($news)."', subject='". mysql_real_escape_string($subject)."' WHERE id = '". mysql_real_escape_string($msgid)."'";
   if(!$result = mysql_query($sql)) {
	 die("Ada kesalahan perubahan data<hr>Data tidak dapat dirubah. Silahkan coba kembali dan pilih Back.");
      }
  
  echo "<font><b>$tdk</b><br>Perubahan Data Berita berhasil! <br><br>
  | <a href='admin.php?mode=rempost'>Lihat Berita</a> | <a href='admin.php?mode=post'>Tambah Berita</a> |<br></font>";
  
 } 

 // tambah berita
 function post() {
  include "functions_editor.php";
  echo editor_full();
  
  echo "<font><form action='admin.php' method=\"post\" name=\"f1\"  enctype=\"multipart/form-data\">
          <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr> 
              <td width='24%'><font>Topik:</font></td>
              <td width='76%'> 
                <input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"100\">
              </td>
            </tr>
            <tr> 
              <td colspan=2 height=\"21\"><font>Isi Berita:</font><br> ";

echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%"></textarea>';
              echo"</td>
            </tr>
			 <tr> 
              <td width='24%' valign=top ><font>File Gambar</font></td>
              <td width='76%'  ><input type=\"file\" name='FileToUpload' > <font> Format file JPG (Lebar 435px x Tinggi 200px)<br>
				| <input type='checkbox' name='cropgbr' value='on' checked > Gambar di Crop atau dipotong menjadi 435 x 200 px |
              </td>
            </tr>
            <tr> 
              <td width='100%' colspan='2'> 
                <input type=\"hidden\" name=\"mode\" value=\"NewsSave\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\"/>
              </td>
            </tr>
          </table>
  </form></font>";
  
 } 
 // Save News
 function NewsSave() {
 include "koneksi.php";
 include "fungsi_crop.php";
    $FileToUpload = $_FILES['FileToUpload']['tmp_name'];
	$poster_id=$_SESSION['Admin']['userid']; 
	$news=stripslashes($_POST['richEdit0']);
	$subject=$_POST['subject'];
	$cropgbr=$_POST['cropgbr'];
 	$subject = addslashes($subject);
     $postdate = date("m/d/Y");
     $posttime = date("H:i:s");
     $sql = "SELECT max(id) AS total FROM t_news";
     if(!$r = mysql_query($sql))
       die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;    
    if($FileToUpload== '') {
    $newfile='';
    }
    else {
	if (file_exists($FileToUpload)) {
	$tdk='';
		
	$gb="../images/berita/gb$total.jpg";
	if (file_exists($gb)) 
		unlink($gb);
		
		  if($cropgbr=='on')  {
		  	
			$newfile="../images/berita/temp.jpg";
			if (file_exists($newfile)) unlink($newfile);
			copy($FileToUpload, $newfile);
			//cropImage(435, 200, "$newfile", 'jpg', "../images/berita/gb".$total.".jpg");
            crop_image($newfile,"../images/berita/gb".$total.".jpg",435,200);
		  }
		  else {
    		copy($FileToUpload, "../images/berita/gb$total.jpg");
		   }
		
		}
	else {
		$tdk="File gambar yang diimputkan tidak ada";
		$newfile='';
		}
	}
	 $sql = "INSERT INTO t_news (id, isi, subject, pengirim, postdate, posttime) 
				VALUES ('". mysql_escape_string($total)."', '".mysql_real_escape_string($news)."', '". mysql_escape_string($subject)."', '". mysql_escape_string($poster_id)."','". mysql_escape_string($postdate)."', '". mysql_escape_string($posttime)."')";

     if(!$result = mysql_query($sql)) {
        die("Ada kesalahan penamabahan data dalam menu sebelumnya. Coba kembali dan pilih Back. <BR>$sql<BR>$mysql_error()");
     }
	
  
      echo "<font><b>$tdk</b><br>Penambahan Database telah berhasil ! <br> Silahkan Anda pilih menu sebelah kiri yang diinginkan !.<br><br>| <a href='admin.php?mode=rempost'>Lihat Berita</a> | <a href='admin.php?mode=post'>Tambah Berita</a> |<br></font>";
  
 }

 // remove posts
 function remposts() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result=mysql_query("select * from t_news");
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
  		
  $query = "SELECT * FROM t_news order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data berita di database!<br>| <a href='?mode=post'>Tambah Berita</a> |</font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\" name='berita' >";
  echo "<table cellspacing='1' cellpadding='3' border=1 >
   <tr><td bgcolor='#999999' colspan='7' align='center'><font>--- Daftar Berita ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font><a href='admin.php?mode=rempost&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=rempost&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=rempost&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  // get news from db
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  echo "<tr><td><font>No</td><td><font>Judul</td><td><font>Tanggal</td><td><font>Komentar</td>
  <td><font>HTML</td><td><font>Edit</td><td><font>Hapus</td></tr>";
  $i=1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  $subject = substr(strip_tags($row[subject]), 0, 70) . "...";
	$query = "SELECT * FROM t_news_kom WHERE id='$row[id]'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $jml = mysql_num_rows($result);
 ?>
   <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" > 
    <td width="2%" ><font><?php echo $i; ?></font></td>
    <td width="60%"><font> 
      <?php echo "<a href='admin.php?mode=beritakom&id=$row[id]' title='Lihat Komentar' >$subject</a>"; ?>
      </font></td>
    <td width="10%"><font> 
      <?php echo $row[postdate]; ?>
      </font></td>
          <td width="5%" align="center"><font> 
      <?php echo $jml; ?>
      </font></td>
      <td width="5%" align="center"><font><a href="admin.php?mode=html_news&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border=0 title="HTML Code" ></a></td> 
  <td width="5%" align="center"><font><a href="admin.php?mode=modpost&msgid=<?php echo $row[id]; ?>"><img src="../images/doc.gif" border="0" title="Edit <?php echo $row[subject]; ?>"></a> </font></td><td width="5%">
    &nbsp;&nbsp; <input  title="Hapus <?php echo $row[subject]; ?>"type='checkbox' name='msgid[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  $i++;
  }  
  echo "</table><br>
  <font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"delete\">
                <input type=\"submit\" value=\"Hapus\"> <font>| <a href='?mode=post'>Tambah Berita</a> |</font> </form>";
  
 }
function html_news() {
  include "koneksi.php";
  $id=$_GET['id'];
  $save=$_GET['save'];
  $isi=stripslashes($_POST['richEdit0']);
  $judul=$_POST['judul'];
  $status=$_POST['status'];
  $urut=$_POST['urut'];
  
  if ($save=="") {
  $sql="select * from t_news where id='".mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
	echo"<form action='admin.php?mode=html_news&save=1&id=$id' method=\"post\" name=\"f1\" >";
    echo"<font>Judul : <input type=text name=judul value='$row[subject]' size=40 maxlenght=100><br><br>
	Isi <textarea cols=100 name='richEdit0' rows=20 >$row[isi]</textarea>";
	echo"<br><br><input type='reset' value='Ulang' > &nbsp;
	<input type=submit class=button name=submit value=' Simpan ' ></form>";
  }
  else {
  $sql = "UPDATE t_news SET isi= '". mysql_real_escape_string($isi)."',subject='". mysql_escape_string($judul)."' WHERE id ='". mysql_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Perubahan Berita berhasil<br>Silahkan pilih menu kembali !!!<br>
  | <a href='admin.php?mode=rempost'>Lihat Berita</a> | <a href='admin.php?mode=post'>Tambah Berita</a> |</font>"; 
  }
  
}

 function delete() {
  include("koneksi.php");
  $msgid=$_POST['msgid'];
	  if (!empty($msgid))
	  {
	  	while (list($key,$value)=each($msgid))
		{
		 $sql1="select * from t_news where id='". mysql_escape_string($key)."'";
		 $result=mysql_query($sql1);
		 $row=mysql_fetch_array($result);
			$sql="delete from t_news where id='". mysql_escape_string($key)."'";
			//if ($row['file']!='') {
			$nfile="../images/berita/gb".$key.".jpg";
			if (file_exists($nfile)) {
			//unlink('../images/berita/gb'.$msgid.'.gif');
				unlink("../images/berita/gb".$key.".jpg");
			}
			$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
		}
	  }

 }
 
 function beritakom() {
 include "koneksi.php";
  $hal=$_GET['hal'];
  $id=$_GET['id'];
  if($id=='')$id=$_POST['id'];
  $brs=30;
  $query = "SELECT subject FROM t_news WHERE id='".mysql_real_escape_string($id)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $r = mysql_fetch_array($result);
  $judul =$r[subject];
  
  $byk_result1=mysql_query("select * from t_news_kom where id='".mysql_real_escape_string($id)."' order by idkom desc");
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
  		
  $query = "SELECT * from t_news_kom where id='".mysql_real_escape_string($id)."' order by idkom desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999' colspan='6' align='center'><font>--- Daftar Komentar Berita ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='6'><center><font><a href='admin.php?mode=beritakom&id=$id&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=beritakom&id=$id&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=beritakom&id=$id&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td bgcolor='#999999' colspan='8' ><font>Judul : $judul</font></td></tr>
  <tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Email</center></font></td>
  <td><font><center>Komentar</center></font></td>
  <td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";	
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[tgl]</font></td>
	<td width='10%' ><font>$row[nama]</td>
	<td width='10%' ><font>$row[email]</td>
	<td width='40%' ><font>$row[komentar]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><input type='checkbox' name='idkom[<?php echo $row[idkom]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"beritakom_hap\"><input type=\"hidden\" name=\"id\" value=\"$id\">
                <input type=\"submit\" value=\"Hapus\"></form>";
 }
//hapus beritakom
 function beritakom_hap() {
  include("koneksi.php");
  $id=$_POST['idkom'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_news_kom where idkom='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 
//-------------------------- artikel
 function artikel_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_artikel WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);

 include "functions_editor.php";
 echo editor_full();

  echo "<form action='admin.php' method=\"post\" name=\"f1\"  enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Artikel</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='40' maxlength='90' value='$row[judul]'>
              </td></tr>
            <tr><td width='24%' colspan=2><font>Isi</font><br>";

echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%">'.$row[isi].'</textarea>';
              echo"</td></tr>
			  			<tr> 
              <td width='24%' valign=top ><font>File Gambar</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' ><font> Format file JPG (Lebar 300px x Tinggi 225px)<br>
				| <input type='checkbox' name='cropgbr' value='on' checked > Gambar di Crop atau dipotong menjadi 300 x 225 px |<br>
				| <input type='checkbox' name='delgbr' value='on' > Hapus Gambar |
              </td>
            </tr> 
			<tr><td width='24%'><font>Pengirim</font></td>
              <td width='76%'> <input type='text' value='$row[pengirim]' name='pengirim' size='25' maxlength='80'>
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"artikel_save\">
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
  
 } 

 // perubahan simpan artikel
 function artikel_save() {
 include("koneksi.php");
 include "fungsi_crop.php";
 $FileToUpload = $_FILES['FileToUpload']['tmp_name'];
 //echo $FileToUpload;
 //exit;
 $idn=$_POST['idn'];$judul=$_POST['judul'];$pengirim=$_POST['pengirim'];
 $isi=stripslashes($_POST['richEdit0']);
 $edit=$_POST['edit'];$delgbr=$_POST['delgbr'];
 $adminid=$_SESSION['Admin']['userid'];$cropgbr=$_POST['cropgbr'];
  $tanggal = date("d-m-Y H:i");
 // $isi = addslashes($isi);
  //$news = str_replace("\n", "<BR>", $isi);
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_artikel";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
   if($FileToUpload == '') {
    }
   else {
    
	if (file_exists($FileToUpload) ) {
	$gb="../images/artikel/gb$total.jpg";
	if (file_exists($gb)) unlink($gb);
		
		  if($cropgbr=='on')  {
			$newfile="../images/artikel/temp.jpg";
			if (file_exists($newfile)) unlink($newfile);
			copy($FileToUpload, $newfile);
		//	cropImage(300, 225, "$newfile", 'jpg', "../images/artikel/gb".$total.".jpg");
            crop_image($newfile,"../images/artikel/gb".$total.".jpg",300,225);
		  }
		  else {
    		copy($FileToUpload, "../images/artikel/gb$total.jpg");
		  }
		
	}	
	else {
		$tdk="File gambar yang diimputkan tidak ada";
		$newfile='';
		}
  }
  $sql = "insert into t_artikel (id,tanggal,judul,isi,pengirim,admin) values ('$total','". mysql_escape_string($tanggal)."','". mysql_escape_string($judul)."','". mysql_real_escape_string($isi)."','". mysql_escape_string($pengirim)."','". mysql_escape_string($adminid)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>$tdk<br>Penambahan Artikel berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=artikel'>Lihat Artikel</a> | <a href='admin.php?mode=artikel_tam'>Tambah Artikel</a> |<br></font>"; 
 }
 else {
 	$gb="../images/artikel/gb$idn.jpg";
 	 	if ($delgbr=='on')  {
			if (file_exists($gb)) unlink($gb);
		}
   if($FileToUpload== '') {
    }
   else {
	if (file_exists($FileToUpload)) {
	$tdk='';
		if (file_exists($gb)) unlink($gb);
		
		  if($cropgbr=='on')  {
			$newfile="../images/artikel/temp.jpg";
			if (file_exists($newfile)) unlink($newfile);
			copy($FileToUpload, $newfile);
		//	cropImage(300, 225, "$newfile", 'jpg', "$gb");
            crop_image($newfile,$gb,300,225);
		  }
		  else {
    		copy($FileToUpload, $gb);
		 }
		
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		$newfile='';
		}
	}
  $sql = "update t_artikel set judul='". mysql_real_escape_string($judul)."',isi='".mysql_real_escape_string($isi)."',pengirim='". mysql_escape_string($pengirim)."',tanggal='". mysql_escape_string($tanggal)."',admin='". mysql_escape_string($adminid)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>$tdk<br>Perubahan Artikel berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=artikel'>Lihat Artikel</a> | <a href='admin.php?mode=artikel_tam'>Tambah Artikel</a> |<br></font>";
 }    
  
 } 

 // tambah artikel
 function artikel_tam() {
  
 include "functions_editor.php";
 echo editor_full();

    echo "<form action='admin.php' method=\"post\" name=\"f1\"  enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Artikel</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='40' maxlength='90' >
              </td></tr>
            <tr><td width='24%' colspan=2><font>Isi</font><br>";
		  
echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%"></textarea>';
            echo"  </td></tr> 
						<tr> 
              <td width='24%'><font>File Gambar</font></td>
              <td width='76%'> 
                <input type=\"file\" name='FileToUpload' ><font>Format file JPG (Lebar 300px x Tinggi 225px)<br>
				| <input type='checkbox' name='cropgbr' value='on' checked > Gambar di Crop atau dipotong menjadi 300 x 225 px |
              </td>
            </tr>
			<tr><td width='24%'><font>Pengirim</font></td>
              <td width='76%'> <input type='text'  name='pengirim' size='25' maxlength='80'>
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"artikel_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\" />
              </td></tr></table></form>";
  
 } 

 //------------------ lihat artikel--------------------------
 function artikel() {
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;

  $byk_result1=mysql_query("select * from t_artikel");
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
  		
  $query = "SELECT * from t_artikel order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data artikel di Database <br><a href='admin.php?mode=artikel_tam'>Tambah Artikel</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='8' align='center'><font>--- Daftar Artikel ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='8'><center><font><a href='admin.php?mode=artikel&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=artikel&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=artikel&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Judul</center></font></td><td><font><center>Komentar</center></font></td>
  <td><font><center>Baca</center></font></td><td><font><center>Admin</center></font></td>
  <td><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";	
  while ($row = mysql_fetch_array($query_result_handle))
  {
    $query = "SELECT * FROM t_artikel_kom WHERE id='$row[id]'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $jml = mysql_num_rows($result);
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[tanggal]</font></td>
	<td width='40%' ><font><a href='admin.php?mode=artikelkom&id=$row[id]' title='Lihat Komentar' >$row[judul]</a></font></td>
	<td width='5%' ><font><center>$jml</center></font></td>
	<td width='5%' ><font><center>$row[visits]</center></font></td>
    <td width='5%' ><font><center>$row[admin]</center></font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=artikel_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"artikel_hap\">
                <input type=\"submit\" value=\"Hapus\"> <font>| <a href='admin.php?mode=artikel_tam'>Tambah Artikel</a> |</font></form>";
  
 } 
//hapus artikel
 function artikel_hap() {
  include("koneksi.php");
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_artikel where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../images/artikel/gb".$key.".jpg";
			if (file_exists($file)) {
			unlink($file);
			}
		}
	  }
 } 
 function artikelkom() {
 include "koneksi.php";
  $hal=$_GET['hal'];
  $id=$_GET['id'];
  if($id=='')$id=$_POST['id'];
  $brs=30;
  $query = "SELECT judul FROM t_artikel WHERE id='".mysql_real_escape_string($id)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $r = mysql_fetch_array($result);
  $judul =$r[judul];
  
  $byk_result1=mysql_query("select * from t_artikel_kom where id='".mysql_real_escape_string($id)."' order by idkom desc");
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
  		
  $query = "SELECT * from t_artikel_kom where id='".mysql_real_escape_string($id)."' order by idkom desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999' colspan='6' align='center'><font>--- Daftar Komentar Artikel ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='6'><center><font><a href='admin.php?mode=artikelkom&id=$id&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=artikelkom&id=$id&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=artikelkom&id=$id&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td bgcolor='#999999' colspan='8' ><font>Judul : $judul</font></td></tr>
  <tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Nama</center></font></td><td><font><center>Email</center></font></td>
  <td><font><center>Komentar</center></font></td>
  <td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";	
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[tgl]</font></td>
	<td width='10%' ><font>$row[nama]</td>
	<td width='10%' ><font>$row[email]</td>
	<td width='40%' ><font>$row[komentar]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><input type='checkbox' name='idkom[<?php echo $row[idkom]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>";
  echo "<input type=\"hidden\" name=\"mode\" value=\"artikelkom_hap\"><input type=\"hidden\" name=\"id\" value=\"$id\">
                <input type=\"submit\" value=\"Hapus\"></form>";
 }
//hapus artikelkom
 function artikelkom_hap() {
  include("koneksi.php");
  $id=$_POST['idkom'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_artikel_kom where idkom='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 
 
 //-------------------------- diskusi_topic
 function diskusi2_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_forum WHERE forum_id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  echo "<form action='admin.php' method=\"post\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Diskusi Topic</b><font></td>	</tr>
            <tr> <td width='24%'><font>Topik</font></td>
              <td width='76%'> <input type='text' name='nama' size='40' maxlength='50' value='$row[forum_nama]'>
              </td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='keterangan' cols=\"60\" rows=\"8\" >$row[forum_ket]</textarea>
              </td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"diskusi2_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[forum_id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // perubahan simpan diskusi_topic
 function diskusi2_save() {
 include "koneksi.php";
 $idn=$_POST['idn'];$nama=$_POST['nama'];
 $keterangan=$_POST['keterangan'];$edit=$_POST['edit'];
 if ($edit!=1) {
     $sql = "SELECT max(forum_id) AS total FROM t_forum";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
  $sql = "insert into t_forum (forum_id,forum_nama,forum_ket) values ('$total','". mysql_escape_string($nama)."','". mysql_escape_string($keterangan)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>Penambahan diskusi_topic berhasil<br>Silahkan pilih menu kembali !!!<br><a href='admin.php?mode=diskusi'>Lihat Forum Diskusi</a><br></font>"; 
 }
 else {

  $sql = "update t_forum set forum_nama='". mysql_escape_string($nama)."',forum_ket='". mysql_escape_string($keterangan)."' where forum_id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>Perubahan diskusi_topic berhasil<br>Silahkan pilih menu kembali !!!<br><a href='admin.php?mode=diskusi'>Lihat Forum Diskusi</a><br></font>";
 }    
  
 } 

 // tambah diskusi
 function diskusi2_tam() {
  
    echo "<form action='admin.php' method=\"post\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Diskusi_topic</b><font></td>	</tr>
            <tr> <td width='24%'><font>Topik</font></td>
              <td width='76%'> <input type='text' name='nama' size='40' maxlength='50' >
              </td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='keterangan' cols=\"60\" rows=\"8\" ></textarea>
              </td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"diskusi2_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 //------------------ lihat diskusi -------------------------
 function diskusi2() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_forum");
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
  		
  $query = "SELECT * from t_forum order by forum_id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data diskusi_topic di Database <br><a href='admin.php?mode=diskusi2_tam'>Tambah diskusi_topic</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='5' align='center'><font><a href='admin.php?mode=diskusi'>Daftar Diskusi</a> | <a href='admin.php?mode=diskusi3'>Reply Diskusi </a> | <a href='admin.php?mode=diskusi2'><b>Topik Diskusi</b></a></font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='5'><center><font><a href='admin.php?mode=diskusi2&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=diskusi2&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=diskusi2&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Topik</center></font></td>
  <td><font><center>Keterangan</center></font></td>
   <td><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='20%' ><font>$row[forum_nama]</font></td>
	<td width='40%' ><font>$row[forum_ket]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=diskusi2_edit&id=<?php echo $row[forum_id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[forum_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"diskusi2_hap\">
       <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=diskusi2_tam'>Tambah diskusi_topic</a> |</font></form>";
  
 } 

//hapus diskusi_topic
 function diskusi2_hap() {
      include("koneksi.php");
	  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_forum where forum_id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 
 //------------------ lihat diskusi -------------------------
 function diskusi() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_forum_isi");
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
  		
  $query = "SELECT * from t_forum_isi order by isi_id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='5' align='center'><font><a href='admin.php?mode=diskusi'><b>Daftar Diskusi</b></a> | <a href='admin.php?mode=diskusi3'>Reply Diskusi </a> | <a href='admin.php?mode=diskusi2'>Topik Diskusi</a></font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='5'><center><font><a href='admin.php?mode=diskusi&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=diskusi&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=diskusi&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Judul</center></font></td><td><font><center>Keterangan</center></font></td>
 <td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  $jud=substr($row[isi_judul], 0, 20) . "...";
  $isi =substr($row[isi_body], 0, 100) . "...";
  $tgl=date("d-m-Y  H:i",strtotime($row[isi_tgl]));
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='20%' ><font>$tgl</font></td>
	<td width='40%' ><font><a href='admin.php?mode=diskusi3&id=$row[isi_id]'>$jud</a></font></td>
	<td width='20%' ><font>$isi</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[isi_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua";
  echo "<input type=\"hidden\" name=\"mode\" value=\"diskusi_hap\">
                <input type=\"submit\" value=\"Hapus\"> 
				| <a href='admin.php?mode=diskusi2'>Topik Diskusi</a> |</font></form>";
  
 } 

//hapus diskusi
 function diskusi_hap() {
      include("koneksi.php");
	$id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_forum_isi where isi_id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 
 function diskusi3() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];$id=$_GET['id'];
  $brs=30;
  if ($id=='') $byk_result1=mysql_query("select * from t_forum_balas ");
  else   $byk_result1=mysql_query("select * from t_forum_balas where isi_id='". mysql_escape_string($id)."'");
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
  		
  if ($id=='') $query = "SELECT * from t_forum_balas  order by balas_id desc LIMIT ".$awal.",".$brs.""; 
  else   $query = "SELECT * from t_forum_balas where isi_id='". mysql_escape_string($id)."' order by balas_id desc LIMIT ".$awal.",".$brs.""; 

  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) ;
    //or die (error("<font>Admin tidak menemukan data diskusi di Database <br></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='4' align='center'><font><a href='admin.php?mode=diskusi'>Daftar Diskusi</a> | <a href='admin.php?mode=diskusi3'><b>Reply Diskusi</b> </a> | <a href='admin.php?mode=diskusi2'>Topik Diskusi</a></font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='4'><center><font><a href='admin.php?mode=diskusi3&id=$id&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=diskusi3&id=$id&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=diskusi3&id=$id&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
<td><font><center>Keterangan</center></font></td>
 <td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  $isi =substr($row[balas_body], 0, 100) . "...";
  $tgl=date("d-m-Y  H:i",strtotime($row[balas_tgl]));
  
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='20%' ><font>$tgl</font></td>
	<td width='50%' ><font>$isi</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[balas_id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"diskusi3_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=diskusi2'>Topik Diskusi</a> | </form>";
  
 } 
 function diskusi3_hap() {
      include("koneksi.php");
	  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_forum_balas where balas_id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 
 function agenda() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from calendarevent");
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
  		
  $query = "SELECT * from calendarevent order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='6' align='center'><font><b>Daftar Agenda</b> </td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='6'><center><font><a href='admin.php?mode=agenda&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=agenda&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=agenda&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Judul</center></font></td>
  <td><font><center>Kegiatan</center></font></td><td><font><center>Edit</center></font></td>
 <td><font><center>Hapus</center></font></td></tr>";
  // get news from db
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  $tgl1=date("d-m-Y",strtotime($row[date_start]));
  $tgl2=date("d-m-Y",strtotime($row[date_end]));
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\" > 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='20%' ><font>$tgl1 s/d <br>$tgl2</font></td>
	<td width='20%' ><font>$row[eventTitle]</font></td>
	<td width='40%' ><font>$row[EventDetail]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=agenda_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"agenda_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=agenda_tam'>Tambah Agenda</a> |</form>";
  
 } 
  function agenda_hap() {
	include "koneksi.php";
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from calendarevent  where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }

 } 
 //-------------------------- diskusi_topic
 function agenda_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM calendarevent WHERE id='".mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  $tgl1=date("Y-m-d",strtotime($row[date_start]));
  $tgl2=date("Y-m-d",strtotime($row[date_end]));
echo "<script language='javascript' src='../functions/ssCalendar.js'></script>";
  echo "<form action='admin.php' method=\"post\" name='agenda'>
		 <table border='0' cellpadding='0' cellspacing='6' width='100%' >
            <tr><td colspan='2'><font><b>Pengisian Agenda</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='40' maxlength='50' value='$row[eventTitle]'>
              </td></tr>
		    <tr> <td width='24%'><font>Tanggal </font></td>
              <td width='76%'> <font>";
  echo ' Awal &nbsp;<input name="awal" type="text" id="tgl" value="'.$tgl1.'" readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
 echo ' Akhir <input name="akhir" type="text" id="tgl2" value="'.$tgl2.'" readonly />
                <a href="#" id="anctgl2"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl2" border="0" class="calCanvas"></div>';
			echo "	  <script language='javascript1.2'>
	  function add_warna() {
	    window.open('pal.htm','Legends','width=250,height=170,resizable=no,scrollbars=no'); 
      }	
	  </script>";
              echo "</td></tr>
            <tr><td width='24%'><font>Kegiatan</font></td>
              <td width='76%'><textarea name='kegiatan' cols=\"60\" rows=\"8\" >$row[EventDetail]</textarea>
              </td></tr> 
	            <tr> <td width='24%'><font>Warna</font></td>
              <td width='76%'> <input  type=text name='color' size=10 value='$row[color]' maxlength=0 readonly > &nbsp;<input type=button  value='  Warna  ' onclick='add_warna()'>
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"agenda_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
    $m=date("m");  $d=date("d");  $y=date("Y");
  echo '<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "yyyy-mm-dd";
dptgl.anchor = "anctgl";
dptgl.initialize();

var dptgl2 = new DatePicker();
dptgl2.id = "tgl2";
dptgl2.month = '.$m.';
dptgl2.year = '.$y.';
dptgl2.canvas = "dtDivtgl2";
dptgl2.format = "yyyy-mm-dd";
dptgl2.anchor = "anctgl2";
dptgl2.initialize();
-->
</script>';
  
 } 

 // perubahan simpan diskusi_topic
 function agenda_save() {
 include "koneksi.php";
 $idn=$_POST['idn'];$judul=$_POST['judul'];$awal=$_POST['awal'];
 $akhir=$_POST['akhir'];$kegiatan=$_POST['kegiatan'];$color=$_POST['color'];$edit=$_POST['edit'];
 $h = substr($awal,8,2);
 $b = substr($awal,5,2);
 $t = substr($awal,0,4);
 $awal = $t."/".$b."/".$h;
 $h = substr($akhir,8,2);
 $b = substr($akhir,5,2);
 $t = substr($akhir,0,4);
 $akhir = $t."/".$b."/".$h;
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM calendarevent";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
  $sql = "insert into calendarevent (id,date_start,date_end,eventTitle,EventDetail,color,status,picture_id,file_id) 
  values ('".mysql_escape_string($total)."','".mysql_escape_string($awal)."','".mysql_escape_string($akhir)."','".mysql_escape_string($judul)."','".mysql_escape_string($kegiatan)."','".mysql_escape_string($color)."','1','1','1')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>Penambahan agenda berhasil <br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=agenda'>Lihat Agenda</a> | <a href='admin.php?mode=agenda_tam'>Tambah Agenda</a> |<br></font>"; 
 }
 else {

  $sql = "update calendarevent set date_start='".mysql_escape_string($awal)."',date_end='".mysql_escape_string($akhir)."',eventTitle='".mysql_escape_string($judul)."',EventDetail='".mysql_escape_string($kegiatan)."',color='".mysql_escape_string($color)."' where id='".mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>Perubahan Agenda berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=agenda'>Lihat Agenda</a> | <a href='admin.php?mode=agenda_tam'>Tambah Agenda</a> |<br></font>";
 }    
  
 } 

 // tambah diskusi
 function agenda_tam() {
  
  echo '<script language="javascript" src="../functions/ssCalendar.js"></script>';
  echo "<form action='admin.php' method=\"post\" name='agenda'>
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Agenda</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='40' maxlength='50' >
              </td></tr>
		    <tr> <td width='24%'><font>Tanggal </font></td>
              <td width='76%'> <font> ";
  echo ' Awal &nbsp;<input name="awal" type="text" id="tgl"  readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
 echo ' Akhir <input name="akhir" type="text" id="tgl2" readonly />
                <a href="#" id="anctgl2"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl2" border="0" class="calCanvas"></div>';
	echo "	  <script language='javascript1.2'>
	  function add_warna() {
	    window.open('pal.htm','Legends','width=250,height=170,resizable=no,scrollbars=no'); 
      }	
	  </script>";
  echo "</td></tr>
            <tr><td width='24%'><font>Kegiatan</font></td>
              <td width='76%'><textarea name='kegiatan' cols=\"60\" rows=\"8\" ></textarea>
              </td></tr> 
	            <tr> <td width='24%'><font>Warna</font></td>
              <td width='76%'> <input  type=text name='color' size=10  maxlength=0 readonly > &nbsp;<input type=button  value='  Warna  ' onclick='add_warna()'>
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"agenda_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
    $m=date("m");  $d=date("d");  $y=date("Y");
  echo '<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "yyyy-mm-dd";
dptgl.anchor = "anctgl";
dptgl.initialize();

var dptgl2 = new DatePicker();
dptgl2.id = "tgl2";
dptgl2.month = '.$m.';
dptgl2.year = '.$y.';
dptgl2.canvas = "dtDivtgl2";
dptgl2.format = "yyyy-mm-dd";
dptgl2.anchor = "anctgl2";
dptgl2.initialize();
-->
</script>';
  
 } 
/**************************** akhir info class***********************/ 
}
?>