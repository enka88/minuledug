<?php
if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
/********************************************* Informasi **********************************************/
class infoclass {
 //-------------------------- info
 function info_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_info WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  if ($row[kode]=='1')
  	$sel1="selected";
  else 
  	$sel2="selected";	

 include "functions_editor.php";
 echo editor_full();
  echo "<form action='admin.php' method=\"post\" name=\"f1\"  enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Informasi</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='40' maxlength='50' value='$row[subject]'>
              </td></tr>
            <tr><td colspan=2><font>Info</font><br>";

	echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%">'.$row['isi'].'</textarea>';
             echo" </td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"info_save\">
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
  
 } 

 // perubahan simpan informasi
function info_save() {
include "koneksi.php";
$idn=$_POST['idn'];$judul=$_POST['judul'];
  $info=stripslashes($_POST['richEdit0']);
$edit=$_POST['edit'];
 
 $tanggal = date("d/m/Y");
 $jam = date("H:i:s");
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_info";
     if(!$r = mysql_query($sql)) die("pengambilan gagal");
     list($total) = mysql_fetch_array($r);
     $total += 1;
  $sql = "insert into t_info values ('". mysql_escape_string($total)."','". mysql_escape_string($info)."','". mysql_escape_string($judul)."','". mysql_escape_string($tanggal)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>Penambahan informasi berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=info'>Lihat Info sekolah</a> | <a href='admin.php?mode=info_tam'>Tambah info</a> |</font>"; 
 }
 else {

  $sql = "update t_info set subject='". mysql_escape_string($judul)."',isi='". mysql_escape_string($info)."',postdate='". mysql_escape_string($tanggal)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>Perubahan informasi berhasil<br>Silahkan pilih menu kembali !!! <br> <br>
  | <a href='admin.php?mode=info'>Lihat Info sekolah</a> | <a href='admin.php?mode=info_tam'>Tambah info</a> |</font>";
 }    
  
 } 

 // tambah informasi
 function info_tam() {
    include "functions_editor.php";
    echo editor_full();

    echo "<form action='admin.php' method=\"post\" name=\"f1\"  enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
           <tr><td colspan='2'><font><b>Pengisian Informasi</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='40' maxlength='50' >
              </td></tr>
            <tr><td colspan=2><font>Info</font><br>";

	echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%"></textarea>';
              echo"</td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"info_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
  
 } 
 
function html_info() {
  include "koneksi.php";
  $id=$_GET['id'];
  $save=$_GET['save'];
  $isi=stripslashes($_POST['richEdit0']);
  $judul=$_POST['judul'];
  $status=$_POST['status'];
  $urut=$_POST['urut'];
  
  if ($save=="") {
  $sql="select * from t_info where id='".mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
	echo"<form action='admin.php?mode=html_info&save=1&id=$id' method=\"post\" name=\"f1\" >";
    echo"<font>Judul : <input type=text name=judul value='$row[subject]' size=40 maxlenght=100><br><br>
	Isi <textarea cols=100 name='richEdit0' rows=20 >$row[isi]</textarea>";
	echo"<br><br><input type='reset' value='Ulang' > &nbsp;
	<input type=submit class=button name=submit value=' Simpan ' ></form>";
  }
  else {
  $sql = "UPDATE t_info SET isi= '". mysql_real_escape_string($isi)."',subject='". mysql_escape_string($judul)."' WHERE id ='". mysql_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Perubahan info berhasil<br>Silahkan pilih menu kembali !!!<br>
  | <a href='admin.php?mode=info'>Lihat Info sekolah</a> | <a href='admin.php?mode=info_tam'>Tambah info</a> |</font>"; 
  }
  
}
 //------------------ lihat informasi--------------------------
 function info() {
   // ditambah alan untuk seleksi halaman
    include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_info");
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
  		
  $query = "SELECT * from t_info order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data info di Database <br><a href='admin.php?mode=info_tam'>Tambah info</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='6' align='center'><font>--- Daftar Informasi---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='6'><center><font><a href='admin.php?mode=info&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=info&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=info&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Judul</center></font></td><td><font><center>HTML</center></font></td>
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
    <td width='10%' ><font>$row[postdate]</font></td>
	<td width='40%' ><font>$row[subject]</font></td>"; 
	$j++;
	 ?>
     <td width="10%" align="center"><font><a href="admin.php?mode=html_info&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border=0 title="HTML Code" ></a></td> 
  <td width="10%" align="center"><font><a href="admin.php?mode=info_edit&id=<?php echo $row[id]; ?>"><img src="../images/doc.gif" border="0" title="Editor khusus" ></a></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"info_hap\">
        <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=info_tam'>Tambah Informasi</a> |</font></form>";
  
 } 

//hapus informasi
 function info_hap() {
      include("koneksi.php");
	  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_info where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }

 } 
// buku tamu
  function buku_tamu() {
   include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
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
  		
  $query = "SELECT * FROM t_buku order by id_buku DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data Buku Tamu di Database!</font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='7' align='center'><font >--- Daftar Buku Tamu ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font><a href='admin.php?mode=buku_tamu&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=buku_tamu&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=buku_tamu&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>Nama</center></font></td><td><font><center>E-mail</center></font>
  </td><td><font><center>Alamat</center></font></td><td><font><center>Komentar</center></font>
  </td><td align='right'><font><center>Waktu</center></font></td><td><font><center>Jawab</center></font></td><td><font><center>Hapus</center></font></td></tr>";
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
    <td width='15%' valign=top ><font>$row[nama]</font></td>
    <td width='16%' valign=top  ><font>$row[email]</font></td>
    <td width='15%' valign=top ><font>$row[alamat]</font></td>
    <td width='40%' valign=top ><font>$row[komentar]</font></td>
    <td width='10%' align='right' valign=top ><font>$row[postdate]</font></td>
	<td width='10%' valign=top ><center><font><a href='admin.php?mode=buku_jawab&idn=$row[id_buku]' title='klik untuk Jawab Buku tamu'><img src='../images/edit.gif' border=0></a></font></td>"; 
	 ?>
  <td width="4%" align="center" valign=top ><input type='checkbox' name='id_buku[<?php echo $row[id_buku]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font > <input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"hapus_buku\">
                <input type=\"submit\" value=\"Hapus\"></form>";
  
  }
  
 function buku_jawab() {
  include "koneksi.php";
 $idn=$_GET['idn'];
  	 $sql="select * from t_buku where id_buku='". mysql_escape_string($idn)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 buku");
	$row=mysql_fetch_array($query);
  echo "<form action='admin.php'  method=\"post\" >
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Jawaban Buku Tamu</b><font></td>	</tr>
			<tr><td width='24%'><font>Pengirim</font></td>
              <td width='76%'><input type=text value='$row[nama] $row[email]' size=70 >
              </td></tr> 
            <tr><td width='24%'><font>Pesan</font></td>
              <td width='76%'><textarea name='pesan' cols=70 rows=10 >$row[komentar]</textarea>
              </td></tr> 
		      <tr><td width='24%'><font>Jawaban</font></td>
              <td width='76%'><textarea name='jawab' cols=70 rows=10>$row[tanggapan]</textarea>
              </td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"buku_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"idn\" value=\"$row[id_buku]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 
 
  function buku_save() {
   include "koneksi.php";
 $idn=$_POST['idn'];$jawab=$_POST['jawab'];$pesan=$_POST['pesan'];

  $sql = "update t_buku set tanggapan='". mysql_escape_string($jawab)."',komentar='". mysql_escape_string($pesan)."' where id_buku='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>$tdk<br>Perubahan data jawaban Buku tamu berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=buku_tamu'>Lihat Buku Tamu</a> |</font>"; 
  
 } 
 
 function hapus_buku() {
 include "koneksi.php";
 $id_buku=$_POST['id_buku'];
	  if (!empty($id_buku))
	  {
	  	while (list($key,$value)=each($id_buku))
		{
			$sql="delete from t_buku where id_buku='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query salah - Mysql");
		}
	  }

 }
 // bpesan
  function pesan_depan() {
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_pesan");
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
  		
  $query = "SELECT * FROM t_pesan order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data pesan_depan di Database!</font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='4' align='center'><font >--- Daftar Pesan ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='4'><center><font><a href='admin.php?mode=pesan_depan&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=pesan_depan&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=pesan_depan&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>Pengirim</center></font>
  </td><td><font><center>Komentar</center></font></td>
  </td><td align='right'><font><center>Waktu</center></font></td><td><font><center>Hapus</center></font></td></tr>";
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
    <td width='16%' ><font>$row[pengirim]</font></td>
    <td width='40%'><font>$row[pesan]</font></td>
    <td width='10%' align='right'><font>$row[waktu]</font></td>"; 
	 ?>
  <td width="4%" align="center"><input type='checkbox' name='id_buku[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"hapus_pesan\">
                <input type=\"submit\" value=\"Hapus\"></form>";
  
}
	
 function hapus_pesan() {
 $id_buku=$_POST['id_buku'];
      include "koneksi.php";
	  if (!empty($id_buku))
	  {
	  	while (list($key,$value)=each($id_buku))
		{
			$sql="delete from t_pesan where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query salah - Mysql");
		}
	  }

 }
 // pesan alumni
  function pesan_alm() {
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_pesan_alum");
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
  		
  $query = "SELECT * FROM t_pesan_alum order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data pesan alumni di Database!</font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='4' align='center'><font >--- Daftar Info Alumni ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='4'><center><font><a href='admin.php?mode=pesan_alm&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=pesan_alm&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=pesan_alm&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>Pengirim</center></font>
  </td><td><font><center>Komentar</center></font></td>
  </td><td align='right'><font><center>Waktu</center></font></td><td><font><center>Hapus</center></font></td></tr>";
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
    <td width='16%' ><font>$row[pengirim]</font></td>
    <td width='40%'><font>$row[pesan]</font></td>
    <td width='10%' align='right'><font>$row[waktu]</font></td>"; 
	 ?>
  <td width="4%" align="center"><input type='checkbox' name='id_buku[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"hapus_alm\">
                <input type=\"submit\" value=\"Hapus\"></form>";
  
  }
	
 function hapus_alm() {
      include("koneksi.php");
	  $id_buku=$_POST['id_buku'];
	  if (!empty($id_buku))
	  {
	  	while (list($key,$value)=each($id_buku))
		{
			$sql="delete from t_pesan_alum where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Query salah - Mysql");
		}
	  }

 }
 //voting
 function voting() {
 include"koneksi.php";
 	$hal=$_GET['hal'];
	$brs=30;
  	$byk_result1=mysql_query("select * from t_voting_tanya");
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
  		
  	$query = "SELECT * FROM t_voting_tanya order by id_tanya DESC LIMIT ".$awal.",".$brs.""; 
  	$query_result_handle = mysql_query ($query) 
  	or die (mysql_error()); 
  	// check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Administrator could not find the voting! &nbsp;<a href='admin.php?mode=tam_voting' 
				title='Tambah pertanyaan'>[Tambah]</a></font>"));
  	// tambah alan untuk delete multiple	
  	echo "<form action='admin.php' method=\"post\">";
  	echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  	<tr><td bgcolor='#999999' colspan='6' align='center'><font >--- Daftar Jajak Pendapat ---</font></td></tr>";
  	if ($jml!=0) {
  		echo "<tr><td colspan='6'><center><font><a href='admin.php?mode=voting&hal=1' title='Hal 1'>awal </a> |"; 
  		for($i=1;$i<=$jml;$i++)
  		{
  			echo "<a href='admin.php?mode=voting&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  		}
  			echo "<a href='admin.php?mode=voting&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  		}
  	echo "<tr><td><font><center>Pertanyaan</center></font></td><td><font><center>id</center></font>
  	</td><td><font><center>Tanggal</center></font></td><td><font><center>status</center></font>
  	</td><td align='right'><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
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
	if ($row[status]=='1')
		$ak='Aktive';
	else $ak='Tidak';
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    	<td width='40%' ><font>$row[pertanyaan]</font></td>
    	<td width='5%' ><font><center>$row[id_tanya]</center></font></td>
    	<td width='10%'><font><center>$row[tanggal]</center></font></td>";
		?>
    <td width='8%' ><center><font><a href="admin.php?mode=ak_voting&id=<?php echo $row[id_tanya]; ?>" title='Klik untuk status tidak aktive'><?php echo $ak; ?></a></font></center></td> 
	<td width="5%" align="center"><a href="admin.php?mode=ed_voting&id=<?php echo $row[id_tanya]; ?>" title='Lihat Grafik hasil Voting'><img src="../images/edit.gif" border="0"> </a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id_tanya]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font > <input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
 ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"del_voting\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=tam_voting' 
				title='Tambah pertanyaan'>Tambah Jajak Pendapat</a> |</form>";
	
 }
function tam_voting() {

	echo "<br><font>Buat pertanyaan untuk Voting : <br>
		<form action='admin.php' method=\"post\">
		Pertanyaan : &nbsp;<input type='text' name=tanya size='60' maxlength='75'>
		<br>Banyak Pilihan Jawaban : &nbsp;<input type=text name=jml size=5 maxlength='4'>
		<input type=hidden name=mode value='jawab_voting'><input type='reset' value='Ulang' > &nbsp;
		<br><input type=submit value='Simpan'></form></font>";
	
 }
 function jawab_voting() {
 include "koneksi.php";
 $tanya=$_POST['tanya'];$jml=$_POST['jml'];
	echo "<form action='admin.php' method=\"post\"><br><font>Buat Jawaban untuk pertanyaan voting : <br>
	<b>$tanya</b><br><br>";
		for ($i=1;$i<=$jml;$i++) {
		echo "Jawaban ke-$i &nbsp; : &nbsp;<input type=text name=jawab[$i] size=35 maxlength='30'><br>";
		}
	echo "<input type=hidden name=mode value='sim_voting'><input type='reset' value='Ulang' > &nbsp;
		<input type='hidden' name='tanya' value='$tanya' ><input type=hidden name=jml value=$jml>
		<br><input type=submit value='Simpan'></form></font>";
	
 } 
 function sim_voting() {
 include"koneksi.php";
 $tanya=$_POST['tanya'];$jml=$_POST['jml'];
 $jawab=$_POST['jawab'];
     $tgl = date("d/m/Y");
     $sql = "SELECT max(id_tanya) AS total FROM t_voting_tanya";
	 if(!$r = mysql_query($sql, $db))
       die("koneksi database gagal.");
     list($total) = mysql_fetch_array($r);
	 $total += 1;
	 $sql1= "SELECT max(id_jawab) AS tot FROM t_voting_jawab";
	 if(!$r1 = mysql_query($sql1, $db))
       die("koneksi database gagal.");
	 list($tot) = mysql_fetch_array($r1);
	 $tot +=1;   
 	 $query="insert into t_voting_tanya (id_tanya,pertanyaan,tanggal,status) values ('$total','". mysql_escape_string($tanya)."','$tgl','1')";
 	 if(!$result=mysql_query($query)) die ("penyimpanan gagal");
	 for ($i=1;$i<=$jml;$i++) {
	 	$sql2="insert into t_voting_jawab (id_jawab,id_tanya,jawaban) values ('$tot','$total','". mysql_escape_string($jawab[$i])."')";
 	 	if(!$result=mysql_query($sql2)) die ("penyimpanan gagal");
		$tot += 1;
	 }
	 echo "<font><br>Penambahan data voting berhasil<br>Silahkan pilih menu sebelah kiri sesuai dengan keinginan <br>
	 | <a href='admin.php?mode=voting' >Lihat Jajak Pendapat</a> | <a href='admin.php?mode=tam_voting' 
				title='Tambah pertanyaan'>Tambah Jajak Pendapat</a> |</font>";	
 
 }
 //hapus voting
  function del_voting() {
  include"koneksi.php";
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))
		{
			$sql="delete from t_voting_tanya where id_tanya='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan pertanyaan gagal - Mysql");
			$sql1="select * from t_voting_jawab where id_tanya='$key'";
			$result=mysql_query($sql1);
			while ($row=mysql_fetch_array($result)) {
				$ids=$row[id_jawab];
				$sql2="delete from t_voting_jawab where id_jawab='$ids'";
				$mysql_result=mysql_query($sql2) or die ("Penghapusan Jawaban gagal - Mysql");
				$sql3="select * from t_voting_pole where id_jawab='$ids'";
				$result1=mysql_query($sql3);
				while ($row1=mysql_fetch_array($result1)) {
					$idp=$row1[id];
					$sql4="delete from t_voting_pole where id='$idp'";
					$mysql_result=mysql_query($sql4) or die ("Penghapusan voting gagal - Mysql");
				}
			}
		}
	  }

 }
 //aktive voting
 function ak_voting() {	
 include"koneksi.php";
 $id=$_GET['id'];
	$sql="select * from t_voting_tanya where id_tanya='". mysql_escape_string($id)."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	if ($row!='') {
		if ($row[status]=='1') {
			$sql2="update t_voting_tanya set status='0' where id_tanya='". mysql_escape_string($id)."'";
			if(!$mysql_result=mysql_query($sql2)) die("Gagal Perubahan status");
		}			
		else {
			$sql2="update t_voting_tanya set status='1' where id_tanya='". mysql_escape_string($id)."'";
			if(!$mysql_result=mysql_query($sql2)) die("Gagal Perubahan status");
		}
		//echo "Perubahan berhasil dilakukan";
	}
	
 }
 
 //Result / lihat hasil voting *******************************************************
 function ed_voting() {
 include"koneksi.php";
 $id=$_GET['id'];
 $query1="select * from t_voting_tanya where id_tanya='". mysql_escape_string($id)."'";
 $alan=mysql_query($query1);
 if ($rid=mysql_fetch_array($alan)) { 
 echo "<form method='post' action='admin.php?mode=simmod_voting&edit=1'> <table><tr><td clospan=2><font>Perubahan Data Voting :</font></td></tr>
 		<tr><td><font>Pertanyaan : </font></td><td><input type=text maxlength=70 size=75 name='tanya' value='$rid[pertanyaan]'></td></tr>";
    $query2="select * from t_voting_jawab where id_tanya='". mysql_escape_string($id)."'";
 	$alan2=mysql_query($query2);
	$i=1;
 	while ($rid2=mysql_fetch_array($alan2)) { 
		echo "<tr><td><font>Jawaban ke-$i :</font></td><td><input type=text maxlength=30 size=35  name='jawab[$i]' value='$rid2[jawaban]'>
		</td></tr>";
		$i++;
	}
	echo "<tr><td><font>Banyak Tambah Jawaban :</font></td><td><input type=text name=jml size=5 maxlength=4>&nbsp;<font>Isi bila ingin ditambah</font></td></tr>
	<tr><td colspan=2><input type='submit' value='Simpan'><input type=hidden name='id' value='$id'></td></tr></table></form><br>"; 
 	
	$sql="select ifnull(count(ip),0),jawaban,pertanyaan from t_voting_pole p,t_voting_jawab j,t_voting_tanya t
		 where t.id_tanya='". mysql_escape_string($id)."' and p.id_jawab=j.id_jawab and j.id_tanya=t.id_tanya group by jawaban,pertanyaan order by pertanyaan, id desc"; 
  	if (!$result=mysql_query($sql)) die ("gagal database");
	$i=0;
  while($myrow=mysql_fetch_array($result)) {
        $question[$i]=$myrow[2];
		$anwser[$i]=$myrow[1];
		$count[$i]=$myrow[0];
		$i++;
  }
  for($n=0;$n<$i;$n++) {
	if (($question[$n]==$question[$n-1])&&($n!=0)) {
		$total[$g]+=$count[$n];		
		}
	else {
		$g++;
		$total[$g]+=$count[$n];		
		}	
	}
  $g=0;
  echo "<br><center>
	<table width=500 border=0 cellspacing=0 bgcolor=#000000 >  
		 <tr> <td align=Center><FONT class='adminhead'>Grafik Hasil Voting</font>
		   <br><table border=0 cellspacing=0 bgcolor=#cccccc width=100%>";

  for($n=0;$n<$i;$n++) {
	if (($question[$n]==$question[$n-1])&&($n!=0)) {
		if ($total[$g]!=0) {
			$hold=number_format(($count[$n] / $total[$g])*100,0);
			$col=get_color($hold);
			echo "<tr><td align=left width=25%><FONT>$anwser[$n]($count[$n]) </font></td>
			         <td align=left colspan=2 width=100%>
			         <TABLE BORDER=0  align=left CELLSPACING=0 CELLPADDING=0 width=$hold >
			         <tr><td align=right bgcolor='$col' width=$hold ><FONT>$hold </font>&nbsp;<td></tr>
			         </table>
			      	  </td> </tr>";
			}
		else {
			echo "<tr><td align=center><FONT >$anwser[$n]($count[$n])</font></td>
			      <td align=center></td>
			      <td align=center><FONT >No results</font></td></tr>";
			}
		}
	else {
		echo "<TR><TD colspan=3><br></TD></TR>";
		$g++;
		if ($total[$g]!=0) {
			$hold=number_format(($count[$n] / $total[$g])*100,0);
			$col=get_color($hold);
			echo "<tr><td colspan=3><FONT><b>$question[$n]</b></font></td>
			</tr><tr><td align=left><FONT>$anwser[$n]($count[$n])</font></td>
			<td align=left colspan=2 width=100%>
			         <TABLE BORDER=0  align=left CELLSPACING=0 CELLPADDING=0 width=$hold >
			         <tr><td align=right bgcolor=$col width=$hold ><FONT >$hold </font>&nbsp;<td></tr>
			         </table>
			      	  </td> </tr>";
			}
		else {
			echo "<tr><td align=center>
			<FONT >$anwser[$n]</font></td>
			<td align=center>$count[$n]</td><td align=center><FONT >No results</font></td></tr>";
			}
		}	
	}
	echo "</table></td></tr></table></center>";
 }
 
 }
 //simpan perubahan voting *************************
 function simmod_voting() {
 include"koneksi.php";
 $edit=$_GET['edit'];$id=$_POST['id'];$tanya=$_POST['tanya'];
 $jawab=$_POST['jawab'];$jml=$_POST['jml'];
 if ($jml=='' && $edit==1) {
     $sql="update t_voting_tanya set pertanyaan='". mysql_escape_string($tanya)."', status='1' where id_tanya='". mysql_escape_string($id)."'";
	 if(!$alan=mysql_query($sql)) die ("Perubahan pertanyaan gagal ");
	 $sql1="select * from t_voting_jawab where id_tanya='". mysql_escape_string($id)."'";
	 $alan1=mysql_query($sql1);
	 $i=1;
	 while ($rid=mysql_fetch_array($alan1)) {
	 	$a=$rid[id_jawab];
	 	$sql2="update t_voting_jawab set jawaban='". mysql_escape_string($jawab[$i])."' where id_jawab='". mysql_escape_string($a)."'";
		if(!$alan=mysql_query($sql2)) die ("Perubahan jawaban gagal ");
	 $i++;
	 }
	 echo "<font><br>Perubahan data pertanyaan dan jawaban voting berhasil <br>
	 | <a href='admin.php?mode=voting' >Lihat Jajak Pendapat</a> | <a href='admin.php?mode=tam_voting' 
				title='Tambah pertanyaan'>Tambah Jajak Pendapat</a> |</font>";
  }
  elseif ($edit==1) {
  	echo "<form action='admin.php' method=\"post\"><br><font>Buat Jawaban untuk pertanyaan voting : <br>
	<b>$tanya</b><br><br>";
		for ($i=1;$i<=$jml;$i++) {
		echo "Jawaban ke-$i &nbsp; : &nbsp;<input type=text name=jawab[$i] size=35 maxlength='30'><br>";
		}
	echo "<input type=hidden name=mode value='simmod_voting'>
		<input type='hidden' name='id' value='$id' >
		<input type='hidden' name='edit' value='0' ><input type=hidden name=jml value=$jml>
		<br><input type=submit value='Simpan'></form></font>";	
  }
  else {
  	$sql1= "SELECT max(id_jawab) AS tot FROM t_voting_jawab";
	 if(!$r1 = mysql_query($sql1))
       die("koneksi database gagal.");
	 list($tot) = mysql_fetch_array($r1);
	 $tot +=1;   
 	 for ($i=1;$i<=$jml;$i++) {
	 	$sql2="insert into t_voting_jawab (id_jawab,id_tanya,jawaban) values ('$tot','". mysql_escape_string($id)."','". mysql_escape_string($jawab[$i])."')";
 	 	if(!$result=mysql_query($sql2)) die ("penyimpanan gagal");
		$tot += 1;
	 }
	 echo "<br><font>Penambahan data jawaban berhasil<br>
	 | <a href='admin.php?mode=voting' >Lihat Jajak Pendapat</a> | <a href='admin.php?mode=tam_voting' 
				title='Tambah pertanyaan'>Tambah Jajak Pendapat</a> |</font>";	
  }
  	
 }

 //-------------------------- link web
 function link_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_link WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  $al=$row[jenis];
  if ($al==1)
  	$s1="selected";
  else if ($al==2)
  	$s2="selected";
  else if ($al==3)
  	$s3="selected";
  else if ($al==4)
  	$s4="selected";
  else if ($al==5)
  	$s5="selected";
  else if ($al==6)
  	$s6="selected";
  else
  	$s1="selected";
  echo "<form action='admin.php' method=\"post\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Link Web</b><font></td>	</tr>
            <tr> <td width='24%'><font>Alamat</font></td>
              <td width='76%'> <input type='text' name='alamat' size='60' maxlength='200' value='$row[alamat]'><br><font>
			  Ditulias alamat websitenya, tidak perlu menggunakan <b>http://</b>
              </td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"60\" rows=\"20\" >$row[ket]</textarea>
              </td></tr> 
			 <tr><td width='24%'><font>Jenis</font></td>
              <td width='76%'><select name='jenis'>";
	$query = "SELECT * FROM t_kategori where jenis='0'";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[jenis]==$k[id_kategori]) $sel="selected";
		echo"<option $sel value='$k[id_kategori]'>$k[kategori]</option>";
	}
             echo"</select> </td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"link_save\">
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // perubahan simpan link
 function link_save() {
 include "koneksi.php";
 $idn=$_POST['idn'];$alamat=$_POST['alamat'];
 $ket=$_POST['ket'];$jenis=$_POST['jenis'];$edit=$_POST['edit'];
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_link";
     if(!$r = mysql_query($sql, $db)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
  $sql = "insert into t_link (id,alamat,ket,jenis) values ('$total','". mysql_escape_string($alamat)."','". mysql_escape_string($ket)."','". mysql_escape_string($jenis)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>Penambahan link berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=link'>Lihat Link Web</a> | <a href='admin.php?mode=link_tam'>Tambah Link Web</a> |</font>"; 
 }
 else {

  $sql = "update t_link set alamat='". mysql_escape_string($alamat)."',ket='". mysql_escape_string($ket)."',jenis='". mysql_escape_string($jenis)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>Perubahan link berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=link'>Lihat Link Web</a> | <a href='admin.php?mode=link_tam'>Tambah Link Web</a> |</font>";
 }    
  
 } 

 // tambah link
 function link_tam() {
 include "koneksi.php";
  
    echo "<form action='admin.php' method=\"post\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Link Web</b><font></td>	</tr>
            <tr> <td width='24%'><font>Alamat</font></td>
              <td width='76%'> <input type='text' name='alamat' size='60' maxlength='200' ><br><font>
			  Ditulias alamat websitenya, tidak perlu menggunakan <b>http://</b>
              </td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"60\" rows=\"20\" ></textarea>
              </td></tr> 
			 <tr><td width='24%'><font>Jenis</font></td>
              <td width='76%'><select name='jenis'>";
	$query = "SELECT * FROM t_kategori where jenis='0'";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		echo"<option value='$k[id_kategori]'>$k[kategori]</option>";
	}
              echo"</td></tr> 
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"link_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 
 //------------------ lihat link--------------------------
 function link_w() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_link");
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
  		
  $query = "SELECT * from t_link order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data link di Database <br><a href='admin.php?mode=link_tam'>Tambah link</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='6' align='center'><font>--- Daftar link web ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='6'><center><font><a href='admin.php?mode=link&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=link&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=link&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>alamat</center></font></td>
  <td><font><center>Keterangan</center></font></td><td><font><center>Jenis</center></font></td>
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
  $ket=substr($row[ket],0,50)."...";
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[alamat]</font></td>
	<td width='40%' ><font>$ket</font></td>
	<td width='10%' ><font>$row[jenis]</font></td>"; 
	$j++;
	 ?>
  <td width="10%" align="center"><font><a href="admin.php?mode=link_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="10%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
 ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"link_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=link_tam'>Tambah link Web</a> |</form><br>";
  
 } 

//hapus link
 function link_hap() {
 include "koneksi.php";
 $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_link where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 
   //-------------------------- banner
 function banner_edit() {
  include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_banner WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  if ($row[jenis]=='gif') $s1="selected";
  elseif ($row[jenis]=='jpg') $s2="selected";
  else $s3="selected";
  
  echo "<form action='admin.php'  method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Banner</b><font></td>	</tr>
            <tr> <td width='24%'><font>Alamat URL</font></td>
              <td width='76%'> <input type='text' name='url1' size='40' maxlength='200' value='$row[url]'>
              <font>Misl : http://www.websekolah.web.id</td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <input type='text' name='alt' size='40' maxlength='29' value='$row[alt]'>
              </td></tr> 
            <tr><td width='24%'><font>Posisi Banner</font></td>
              <td width='76%'> <select name=status>";
			    $query2 = "SELECT * FROM t_kategori WHERE jenis='3'"; 
  				$r2 = mysql_query ($query2) or die (mysql_error()); 
  				while($r = mysql_fetch_array($r2)) {
					if ($row[status]==$r[id_kategori]) echo "<option value=$r[id_kategori] selected >$r[kategori]</option>";
					else  echo "<option value=$r[id_kategori] >$r[kategori]</option>";
				}
			  
			  echo "</select>
              </td></tr> 
            <tr><td width='24%'><font>Jenis File</font></td>
              <td width='76%'> <select name=jenis >
			  <option value='gif' $s1>GIF</option>
			  <option value='jpg' $s2>JPG</option>
			  <option value='swf' $s3>SWF</option>
			  </select>
              </td></tr> 
			 <tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name=\"fileban\"><font>File Gambar format Gif,JPG, dan SWF
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"banner_save\">
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // perubahan simpan banner
 function banner_save() {
 $idn=$_POST['idn'];$url1=$_POST['url1'];$alt=$_POST['alt'];
 $status=$_POST['status'];$edit=$_POST['edit'];$jenis=$_POST['jenis'];
 include "koneksi.php";
 
 $fileban = $_FILES['fileban'];

 $admin = $_SESSION['Admin']['userid'];
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_banner";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
    if($fileban['tmp_name'] == '') {
    }
    else {
	if (file_exists($fileban['tmp_name'])) {
	$newfile="bn".$total.".".$jenis;
	$gmbr = "../images/banner/bn".$total.".".$jenis;
		if (file_exists($gmbr)) {
			unlink($gmbr);
		}
    copy($fileban['tmp_name'], "../images/banner/$newfile");
		}
	else {
		$tdk="File gambar yang diimputkan tidak ada";
		}
	}
  $sql = "insert into t_banner (id,alt,url,status,aktif,jenis,admin) values ('$total','". mysql_escape_string($alt)."','". mysql_escape_string($url1)."','". mysql_escape_string($status)."','1','".mysql_escape_string($jenis)."','". mysql_escape_string($admin)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>$tdk<br>Penambahan banner berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=banner'>Lihat Banner</a> | <a href='admin.php?mode=banner_tam'>Tambah Banner</a> |</font>"; 
 }
 else {
   if($fileban['tmp_name'] =='') {
    }
    else {
	if (file_exists($fileban['tmp_name'])) {
	$tdk='';
	$gmbr = "../images/banner/bn".$idn.".".$jenis;
		if (file_exists($gmbr)) {
			unlink($gmbr);
		}
    copy($fileban['tmp_name'], "../images/banner/bn".$idn.".".$jenis);
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		}
	}
  $sql = "update t_banner set status='". mysql_escape_string($status)."',alt='". mysql_escape_string($alt)."',url='". mysql_escape_string($url1)."',admin='". mysql_escape_string($admin)."',jenis='". mysql_escape_string($jenis)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql,$db)) die ("Perubahan gagal");
  echo "<font>$tdk<br>Perubahan banner berhasil<br>Silahkan pilih menu kembali !!!<br><br>
   | <a href='admin.php?mode=banner'>Lihat Banner</a> | <a href='admin.php?mode=banner_tam'>Tambah Banner</a> |</font>";
 }    
  
 } 

 // tambah banner
 function banner_tam() {
  
    echo "<form action='admin.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Banner</b><font></td>	</tr>
            <tr> <td width='24%'><font>Alamat URL</font></td>
              <td width='76%'> <input type='text' name='url1' size='40' maxlength='200' >
              <font>Misl : http://www.avit-solution.com</td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <input type='text' name='alt' size='20' maxlength='29' >
              </td></tr> 
            <tr><td width='24%'><font>Posisi Banner</font></td>
              <td width='76%'> <select name=status>";
			    $query2 = "SELECT * FROM t_kategori WHERE jenis='3'"; 
  				$r2 = mysql_query ($query2) or die (mysql_error()); 
  				while($r = mysql_fetch_array($r2)) {
					echo "<option value=$r[id_kategori] >$r[kategori]</option>";
				}
			  
			  echo " </select>
              </td></tr> 
	        <tr><td width='24%'><font>Jenis File</font></td>
              <td width='76%'> <select name=jenis >
			  <option value='gif' $s1>GIF</option>
			  <option value='jpg' $s2>JPG</option>
			  <option value='swf' $s3>SWF</option>
			  </select>
              </td></tr> 
			<tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name=\"fileban\"><font>File Gambar format Gif,Jpg, dan SWF
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"banner_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 //------------------ lihat banner--------------------------
 function banner() {
  // ditambah alan untuk seleksi halaman
   include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=20;
  $byk_result1=mysql_query("select * from t_banner");
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
  		
  $query = "SELECT * from t_banner order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data banner di Database <br><a href='admin.php?mode=banner_tam'>Tambah banner</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='9' align='center'><font>--- Daftar Banner ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='9'><center><font><a href='admin.php?mode=banner&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=banner&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=banner&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Banner</center></font></td>
  <td><font><center>Kode tag</center></font></td><td><font><center>Jenis</center></font></td>
  <td><font><center>Visits</center></font></td><td><font><center>Status</center></font></td>
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
  $ket='-';
  if ($row[url]!="") $ket=$row[url];
  $st="Tidak";
  if ($row[aktif]=="1") $st="Aktive";
  
	$query2 = "SELECT * FROM t_kategori WHERE id_kategori='$row[status]'"; 
  	$r2 = mysql_query ($query2) or die (mysql_error()); 
  	if($r = mysql_fetch_array($r2)) {
		$jenis=$r[kategori];
	}

	$atas='';
	if ($row[jenis]=='swf') {
		$atas ='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="150" height="60" title="'.$row[judul].'"><param name="movie" value="../images/banner/bn'.$row[id].'.swf">
          <param name="quality" value="high">
          <embed src="../images/banner/bn'.$row[id].'.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="150" height="60"></embed>
		  </object>';
		}
	else {
		$atas = "<a href='$ket' target='_blank' ><img src='../images/banner/bn$row[id].$row[jenis]' title='$row[judul]' width='150' height='60' border=0 ></a>";}
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='20%' ><center>$atas</center></td>
	<td width='20%' ><font>per banner : id='$row[id]' atau <br> per jenis : status='$row[status]' </font></td>
	<td width='10%' ><font>$jenis</font></td>
	<td width='5%' ><font><center>$row[visits]</center></font></td>
	<td width='5%' ><font><center><a href='admin.php?mode=banner_ak&id=$row[id]'>$st</a></center></font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=banner_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
 ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"banner_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=banner_tam'>Tambah banner</a> |</form><br>";
  echo "Kolom kode tag merupakan sintak SQL yang dapat digunakan dalam modul/widget menu <br>Contoh : 
  <b>Select * from t_banner where id='1' <br><br>";
 } 

//hapus banner
 function banner_hap() {
	include "koneksi.php";
	$id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="select * from t_banner where id='". mysql_escape_string($key)."'";
			if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 artikel");
			$row=mysql_fetch_array($query);
			$jenis= $row[jenis];
			$sql="delete from t_banner where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../images/banner/bn".$key.".".$jenis;
			if (file_exists($file)) {
			unlink($file);
			}
		}
	  }

 } 
  //aktive banner
 function banner_ak() {
 $id=$_GET['id'];	
	$sql="select * from t_banner where id='". mysql_escape_string($id)."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	if ($row!='') {
		if ($row[aktif]=='1') {
			$sql2="update t_banner set aktif='0' where id='". mysql_escape_string($id)."'";
			if(!$mysql_result=mysql_query($sql2)) die("Gagal Perubahan status");
		}			
		else {
			$sql2="update t_banner set aktif='1' where id='". mysql_escape_string($id)."'";
			if(!$mysql_result=mysql_query($sql2)) die("Gagal Perubahan status");
		}
	}

 }
 //------------------ lihat gambar depan --------------------------
 function gbdepan() {
  // ditambah alan untuk seleksi halaman
   include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=20;
  $byk_result1=mysql_query("select * from t_gambaratas");
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
  		
  $query = "SELECT * from t_gambaratas order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='6' align='center'><font>--- Daftar Gambar Banner Atas ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='9'><center><font><a href='admin.php?mode=gbdepan&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=gbdepan&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=gbdepan&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Banner</center></font></td>
  <td><font><center>Judul</center></font></td><td><font><center>File</center></font></td>
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
  if ($row[jenis]=='swf') {
		$atas ='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="364" height="64" title="'.$row[judul].'"><param name="movie" value="../images/banneratas/gbanner'.$row[id].'.swf">
          <param name="quality" value="high">
          <embed src="../images/banneratas/gbanner'.$row[id].'.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="364" height="64"></embed>
		  </object>';
		}
	else {
		$atas = "<img src='../images/banneratas/gbanner$row[id].$row[jenis]' title='$row[judul]' width='364' height='64'>";}
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='2%' align='center'><font>$j</font></td>
    <td width='50%' ><center>$atas</center></td>
	<td width='10%' ><font>$row[judul]</font></td>
	<td width='5%' ><font>gbanner$row[id].$row[jenis]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=gbdepan_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
";
  echo "<input type=\"hidden\" name=\"mode\" value=\"gbdepan_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=gbdepan_tam'>Tambah Gambar Atas</a> |</form><br>";
  
 } 
 // tambah gb daepan
 function gbdepan_tam() {
  
    echo "<form action='admin.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Gambar Banner Atas</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='20' maxlength='20' >
              <font></td></tr>
            <tr><td width='24%'><font>Jenis File</font></td>
              <td width='76%'> <select name='status' >
			  <option value='jpg' selected>JPG</option>
			  <option value='gif' >GIF</option>
			  <option value='swf' >SWF</option>
			  </select>
              </td></tr> 
			<tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name='fileban'><font><br>Ukuran 900 pixel 152 pixel File Gambar format Gif,JPG, atau SWF
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"gbdepan_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 
  //-------------------------- gambar atas ubah
 function gbdepan_edit() {
  include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_gambaratas WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  if ($row[jenis]=='jpg') $s1="selected";
  elseif ($row[jenis]=='gif') $s2="selected";
  else $s3="selected";

  if ($row[jenis]=='swf') {
		$atas ='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="364" height="64" title="'.$row[judul].'"><param name="movie" value="../images/banneratas/gbanner'.$row[id].'.swf">
          <param name="quality" value="high">
          <embed src="../images/banneratas/gbanner'.$row[id].'.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="364" height="64"></embed>
		  </object>';
		}
	else {
		$atas = "<img src='../images/banneratas/gbanner$row[id].$row[jenis]' title='$row[judul]' width='364' height='64'>";}
		
  echo "<form action='admin.php'  method='post' enctype='multipart/form-data' >
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Perubahan Gambar atas</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='20' maxlength='20' value='$row[judul]'>
              <font></td></tr>
            <tr><td width='24%'><font>Jenis</font></td>
              <td width='76%'> <select name='status' >
			  <option value='jpg' $s1>JPG</option>
			  <option value='gif' $s2>GIF</option>
			  <option value='swf' $s3>SWF</option>
			  </select>
              </td></tr> 
            <tr><td width='24%'><font>Gambar</font></td>
              <td width='76%'>$atas
              </td></tr> 
			 <tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name=\"fileban\"><font><br>Ukuran 900 pixel 152 pixel File Gambar format Gif,JPG, atau SWF
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"gbdepan_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 
// perubahan simpan gambar atas
 function gbdepan_save() {
  include "koneksi.php";
 $idn=$_POST['idn'];$judul=$_POST['judul'];$status=$_POST['status'];$jenis=$_POST['jenis'];
 $edit=$_POST['edit'];
  $fileban = $_FILES['fileban']['tmp_name'];
  
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_gambaratas";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
    if($fileban== '') {
    }
    else {
	if (file_exists($fileban)) {
	$newfile="gbanner".$total.".$status";
	$gmbr = "../images/banneratas/gbanner".$total.".$status";
		if (file_exists($gmbr)) {
			unlink($gmbr);
		}
    	copy($fileban, "../images/banneratas/$newfile");
		}
	else {
		$tdk="File gambar yang diimputkan tidak ada";
		}
	}
  $sql = "insert into t_gambaratas (id,judul,jenis) values ('$total','". mysql_escape_string($judul)."','". mysql_escape_string($status)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font><br>Penambahan Gambar atas berhasil<br>Silahkan pilih menu kembali !!!$fileban<br><br>
  | <a href='admin.php?mode=gbdepan'>Lihat Gambar Atas</a> | <a href='admin.php?mode=gbdepan_tam'>Tambah Gambar Atas</a> |</font>"; 
 }
 else {
   if($fileban== '') {
    }
    else {
	if (file_exists($fileban)) {
	$tdk='';
	$gmbr = "../images/banneratas/gbanner".$idn.".$status";
		if (file_exists($gmbr)) {
			unlink($gmbr);
		}
    copy($fileban, "../images/banneratas/gbanner".$idn.".$status");
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		}
	}
  $sql = "update t_gambaratas set jenis='". mysql_escape_string($status)."',judul='". mysql_escape_string($judul)."' where id='$idn'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>$tdk<br>Perubahan Gambar atas berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=gbdepan'>Lihat Gambar Atas</a> | <a href='admin.php?mode=gbdepan_tam'>Tambah Gambar Atas</a> |</font>";
 }    
  
 } 
  function gbdepan_hap() {
   include "koneksi.php";
   $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
  			$query = "SELECT * FROM t_gambaratas WHERE id='". mysql_escape_string($key)."'"; 
  			$result = mysql_query ($query) or die (mysql_error()); 
  			$row = mysql_fetch_array($result);
			$status=$row[jenis];
			$sql="delete from t_gambaratas where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../images/banneratas/gbanner".$key.".$status";
			if (file_exists($file)) {
			unlink($file);
			}
		}
	  }

 } 
//----------------------- album galeri
function album() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from t_galerialbum");
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
  		
  $query = "SELECT * from t_galerialbum order by idalbum desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle);
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='6' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='8' align='center'><font><b>Daftar Album Photo</b> </td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='8'><center><font><a href='admin.php?mode=album&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=album&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=album&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Tanggal</center></font></td>
  <td><font><center>Judul Album</center></font></td><td><font><center>JML</center></font></td>
  <td><font><center>Tambah Photo</center></font></td><td><font><center>Lihat Photo</center></font></td>
  <td><font><center>Edit</center></font></td> <td><font><center>Hapus</center></font></td></tr>";
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
  	$jml=0;
  	$sql2="select count(*) as jum from t_galeri where idalbum='$row[idalbum]'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 gambar atas");
	$r = mysql_fetch_array($query2);
	$jml=$r[jum];
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\" > 
    <td width='5%' align='center'><font>$j</font></td>
    <td width='10%' ><font>$row[tanggal]</font></td>
	<td width='40%' ><font>$row[album]</font></td>
	<td width='5%' ><center>$jml</center></td>
	<td width='5%' ><center><a href='admin.php?mode=galeri_tam&id=$row[idalbum]' title='Tambah Photo' ><img src='../images/kirim.gif' border=0 ></a></center></td>	<td width='5%' ><center><a href='admin.php?mode=galeri&id=$row[idalbum]' title='Lihat Photo' ><img src='../images/jpg.gif' border=0 ></a></center></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=album_edit&id=<?php echo $row[idalbum]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[idalbum]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"album_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=album_tam'>Tambah Album</a> |</form>";
  
 } 
  function album_hap() {
	include "koneksi.php";
  $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_galerialbum where idalbum='".mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$query = "SELECT * FROM t_galeri WHERE idalbum='". mysql_escape_string($key)."'"; 
  			$result = mysql_query ($query) or die (mysql_error()); 
  			while($row = mysql_fetch_array($result)) {
				$file= "../images/galeri/gb".$row[id].".jpg";
				if (file_exists($file)) {
				unlink($file); }
			}
			$sql="delete from t_galeri where idalbum='".mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }

 } 
 //-------------------------- album
 function album_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_galerialbum WHERE idalbum='".mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  $tgl1=$row[tanggal];
echo "<script language='javascript' src='../functions/ssCalendar.js'></script>";
  echo "<form action='admin.php' method=\"post\" name='album'>
		 <table border='0' cellpadding='0' cellspacing='6' width='100%' >
            <tr><td colspan='2'><font><b>Pengisian Album Photo</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul Album</font></td>
              <td width='76%'> <input type='text' name='judul' size='50' maxlength='100' value='$row[album]'>
              </td></tr>
		    <tr> <td width='24%'><font>Tanggal </font></td>
              <td width='76%'> ";
  echo ' <input name="tanggal" type="text" id="tgl" value="'.$tgl1.'" readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
              echo "</td></tr>
			  <tr><td colspan=2 >
                <input type=\"hidden\" name=\"mode\" value=\"album_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[idalbum]\">
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
dptgl.format = "dd-mm-yyyy";
dptgl.anchor = "anctgl";
dptgl.initialize();
</script>';
  
 } 

 // perubahan simpan diskusi_topic
 function album_save() {
 include "koneksi.php";
 $idn=$_POST['idn'];$judul=$_POST['judul'];$tanggal=$_POST['tanggal'];
$edit = $_POST['edit'];
 if ($edit!=1) {
     $sql = "SELECT max(idalbum) AS total FROM t_galerialbum";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
  $sql = "insert into t_galerialbum (idalbum,tanggal,album) 
  values ('".mysql_escape_string($total)."','".mysql_escape_string($tanggal)."','".mysql_escape_string($judul)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>Penambahan Album berhasil <br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=galeri_tam&id=$total'>Tambah Photo untuk Album ini</a> |<br>
  | <a href='admin.php?mode=album'>Lihat Album</a> | <a href='admin.php?mode=album_tam'>Tambah Album</a> |<br></font>"; 
 }
 else {

  $sql = "update t_galerialbum set tanggal='".mysql_escape_string($tanggal)."',album='".mysql_escape_string($judul)."' where idalbum='".mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>Perubahan Album berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=galeri_tam&id=$idn'>Tambah Photo untuk Album ini</a> |<br>
  | <a href='admin.php?mode=album'>Lihat Album</a> | <a href='admin.php?mode=album_tam'>Tambah Album</a> |<br></font>";
 }    
  
 } 
// tambah ansari
 // tambah album
 function album_tam() {

  echo '<script language="javascript" src="../functions/ssCalendar.js"></script>';
  echo "<form action='admin.php' method=\"post\" name='album'>
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian album</b><font></td>	</tr>
            <tr> <td width='24%'><font>Judul Album</font></td>
              <td width='76%'> <input type='text' name='judul' size='50' maxlength='100' >
              </td></tr>
		    <tr> <td width='24%'><font>Tanggal </font></td>
              <td width='76%'> <font> ";
  echo ' <input name="tanggal" type="text" id="tgl"  readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br><div id="dtDivtgl" border="0" class="calCanvas"></div>';
  echo "</td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"album_save\"><input type='reset' value='Ulang' > &nbsp;
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
dptgl.format = "dd-mm-yyyy";
dptgl.anchor = "anctgl";
dptgl.initialize();
</script>';
  
 }  
   //-------------------------- galeri
 function galeri_edit() {
  include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_galeri WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  echo "<form action='admin.php'  method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian galeri photo</b><font></td>	</tr>
	            <tr><td width='24%'><font>Judul Album</font></td>
              <td width='76%'>";
	$sql="select * from t_galerialbum ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 galeri ");
	while($r=mysql_fetch_array($q)) {
		if ($row[idalbum]==$r[idalbum]) $data .="<option value='$r[idalbum]' selected>$r[album]</option>";
		else $data .="<option value='$r[idalbum]'>$r[album]</option>";
	}
			  
              echo "<select name=idalbum >$data</select></td></tr> 
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <input type='text' name='ket' size='50'  value='$row[ket]'>
              </td></tr> 
			 <tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name=\"fileban\"><br><font>File Gambar format JPG 640 x 480 px, ukuran maksimal 200 kbyte<br>| <input type='checkbox' name='cropgbr' value='on' checked > Gambar di Crop atau dipotong menjadi 640 x 480 px |
              </td></tr>
			  <tr> <td></td><td ><img src='../images/galeri/gb$row[id].jpg' width='200' height='150' ></td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"galeri_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 
//tambah ansari
 // perubahan simpan galeri
function galeri_save() {
$idn=$_POST['idn'];
$ket=$_POST['ket'];
$ket1=$_POST['ket1'];
$ket2=$_POST['ket2'];
$ket3=$_POST['ket3'];
$edit=$_POST['edit'];
$idalbum=$_POST['idalbum'];
$cropgbr=$_POST['cropgbr'];

$cek1=$_POST['cek1'];
$cek2=$_POST['cek2'];
$cek3=$_POST['cek3'];
$cek4=$_POST['cek4'];

  include "koneksi.php";
  include "fungsi_crop.php";

  $fileban = $_FILES['fileban']['tmp_name'];
 $fileban1 = $_FILES['fileban1']['tmp_name'];
 $fileban2 = $_FILES['fileban2']['tmp_name'];
 $fileban3 = $_FILES['fileban3']['tmp_name'];

 $fileban_name = $_FILES['fileban']['name'];
 $fileban1_name = $_FILES['fileban1']['name'];
 $fileban2_name = $_FILES['fileban2']['name'];
 $fileban3_name = $_FILES['fileban3']['name'];
 
 $admin = $user[user_id];
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_galeri";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
	if($fileban_name = '') {
		$tdk="File gambar yang diinputkan tidak ada";
		$newfile='';
    }
    else {
        $gb="../images/galeri/gb$total.jpg";
	   if (file_exists($gb)) unlink($gb);
	if($cek1=='on') {
		  if($cropgbr=='on')  {
			$newfile="../images/galeri/temp.jpg";
			if (file_exists($newfile)) unlink($newfile);
			copy($fileban, $newfile);
			//	cropImage(640, 480, "$newfile", 'jpg', "../images/galeri/gb".$total.".jpg");
            crop_image($newfile,"../images/galeri/gb".$total.".jpg",640,480);
		  }
		  else {
    		copy($fileban, "../images/galeri/gb$total.jpg");
		   }

  $sql = "insert into t_galeri (id,ket,idalbum) values ('$total','". mysql_escape_string($ket)."','". mysql_escape_string($idalbum)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
    }
    else {		
		$tdk4="File gambar 1 yang diinputkan tidak ada";
	}
}
    $total += 1;
	if($fileban1_name = '') {
		$tdk="File gambar yang diinputkan tidak ada";
		$newfile1='';
    }
    else {
        $gb="../images/galeri/gb$total.jpg";
	   if (file_exists($gb)) unlink($gb);
	if($cek2=='on') {
		  if($cropgbr=='on')  {
			$newfile1="../images/galeri/temp.jpg";
			if (file_exists($newfile1)) unlink($newfile1);
			copy($fileban1, $newfile1);
			//	cropImage(640, 480, "$newfile", 'jpg', "../images/galeri/gb".$total.".jpg");
            crop_image($newfile1,"../images/galeri/gb".$total.".jpg",640,480);
		  }
		  else {
    		copy($fileban1, "../images/galeri/gb$total.jpg");
		   }

  $sql = "insert into t_galeri (id,ket,idalbum) values ('$total','". mysql_escape_string($ket1)."','". mysql_escape_string($idalbum)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
    }
    else {		
		$tdk1="File gambar 2 yang diinputkan tidak ada";
	}
}
    $total += 1;
	if($fileban2_name = '') {
		$tdk="File gambar yang diinputkan tidak ada";
		$newfile2='';
    }
    else {
        $gb="../images/galeri/gb$total.jpg";
	   if (file_exists($gb)) unlink($gb);
	if($cek3=='on') {
		  if($cropgbr=='on')  {
			$newfile2="../images/galeri/temp.jpg";
			if (file_exists($newfile2)) unlink($newfile2);
			copy($fileban2, $newfile2);
			//	cropImage(640, 480, "$newfile", 'jpg', "../images/galeri/gb".$total.".jpg");
            crop_image($newfile2,"../images/galeri/gb".$total.".jpg",640,480);
		  }
		  else {
    		copy($fileban2, "../images/galeri/gb$total.jpg");
		   }

  $sql = "insert into t_galeri (id,ket,idalbum) values ('$total','". mysql_escape_string($ket2)."','". mysql_escape_string($idalbum)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
    }
    else {		
		$tdk2="File gambar 3 yang diinputkan tidak ada";
	}
}
    $total += 1;
	if($fileban3_name = '') {
		$tdk="File gambar yang diinputkan tidak ada";
		$newfile3='';
    }
    else {
        $gb="../images/galeri/gb$total.jpg";
	   if (file_exists($gb)) unlink($gb);
	if($cek4=='on') {
		  if($cropgbr=='on')  {
			$newfile3="../images/galeri/temp.jpg";
			if (file_exists($newfile3)) unlink($newfile3);
			copy($fileban3, $newfile3);
			//	cropImage(640, 480, "$newfile", 'jpg', "../images/galeri/gb".$total.".jpg");
            crop_image($newfile3,"../images/galeri/gb".$total.".jpg",640,480);
		  }
		  else {
    		copy($fileban3, "../images/galeri/gb$total.jpg");
		   }

  $sql = "insert into t_galeri (id,ket,idalbum) values ('$total','". mysql_escape_string($ket3)."','". mysql_escape_string($idalbum)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
    }
    else {		
		$tdk3="File gambar 4 yang diinputkan tidak ada";
	}
  
  echo "<font>$tdk4<br>$tdk1<br>$tdk2<br>$tdk3<br><br><br>Penambahan galeri berhasil<br>Silahkan pilih menu kembali !!!<br><br>
   | <a href='admin.php?mode=galeri&id=$idalbum'>Lihat Photo di Album ini</a> | 
   <a href='admin.php?mode=galeri_tam&id=$idalbum'>Tambah Photo di Album ini</a> |<br>
   <br>| <a href='admin.php?mode=album'>Lihat Album</a> | </font>"; 
 }
}
 else {
   if($fileban_name = '') {
   }
   else {
	if (file_exists($fileban)) {
		$gb="../images/galeri/gb$idn.jpg";
		if (file_exists($gb)) unlink($gb);
		
		  if($cropgbr=='on')  {
			$newfile="../images/galeri/temp.jpg";
			if (file_exists($newfile)) unlink($newfile);
			copy($fileban, $newfile);
			//cropImage(640, 480, "$newfile", 'jpg', "../images/galeri/gb".$idn.".jpg");
            crop_image($newfile, "../images/galeri/gb".$idn.".jpg",640,480);
		  }
		  else {
    		copy($fileban, "../images/galeri/gb$idn.jpg");
		   }
	
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		}
   }
  $sql = "update t_galeri set ket='". mysql_escape_string($ket)."',idalbum='". mysql_escape_string($idalbum)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");

   if($fileban_name = '') {
   }
   else {
	if (file_exists($fileban1)) {
		$gb="../images/galeri/gb$idn.jpg";
		if (file_exists($gb)) unlink($gb);
		
		  if($cropgbr=='on')  {
			$newfile1="../images/galeri/temp.jpg";
			if (file_exists($newfile1)) unlink($newfile1);
			copy($fileban1, $newfile1);
			//cropImage(640, 480, "$newfile", 'jpg', "../images/galeri/gb".$idn.".jpg");
            crop_image($newfile1, "../images/galeri/gb".$idn.".jpg",640,480);
		  }
		  else {
    		copy($fileban1, "../images/galeri/gb$idn.jpg");
		   }
	
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		}
   }
  $sql = "update t_galeri set ket='". mysql_escape_string($ket)."',idalbum='". mysql_escape_string($idalbum)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");

  echo "<font>$tdk<br>Perubahan galeri berhasil<br>Silahkan pilih menu kembali !!!<br><br>
   | <a href='admin.php?mode=galeri&id=$idalbum'>Lihat Photo di Album ini</a> | 
   <a href='admin.php?mode=galeri_tam&id=$idalbum'>Tambah Photo di Album ini</a> |<br>
   <br>| <a href='admin.php?mode=album'>Lihat Album</a> |</font>";
 }    
  
 } 

 // tambah galeri
 function galeri_tam() {
    $id=$_GET['id'];
    echo "<form action='admin.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Galeri Photo</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul Album</font></td>
              <td width='76%'>";
	$sql="select * from t_galerialbum ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 galeri ");
	while($row=mysql_fetch_array($q)) {
		if ($id==$row[idalbum]) $data .="<option value='$row[idalbum]' selected>$row[album]</option>";
		else $data .="<option value='$row[idalbum]'>$row[album]</option>";
	}
			  

              echo "<select name=idalbum >$data</select></td></tr> 
            <tr><td width='24%'><font>Keterangan Gambar 1</font></td>
              <td width='76%'> <input type='text' name='ket' size='50'  >
              </td></tr> 
			<tr><td width='24%'><font>File Gambar 1</font></td>
              <td width='76%'><input type=\"file\" name=\"fileban\"><input type='checkbox' name='cek1' value='on' checked >Ceklis jika ada file gambar yang akan diinput
            <tr><td width='24%'><font>Keterangan Gambar 2</font></td>
              <td width='76%'><input type='text' name='ket1' size='50'  >
              </td></tr> 
			<tr><td width='24%'><font>File Gambar 2</font></td>
              <td width='76%'><input type=\"file\" name=\"fileban1\"><input type='checkbox' name='cek2' value='on' checked >Ceklis jika ada file gambar yang akan diinput
            <tr><td width='24%'><font>Keterangan Gambar 3</font></td>
              <td width='76%'> <input type='text' name='ket2' size='50'  >
              </td></tr> 
			<tr><td width='24%'><font>File Gambar 3</font></td>
              <td width='76%'><input type=\"file\" name=\"fileban2\"><input type='checkbox' name='cek3' value='on' checked >Ceklis jika ada file gambar yang akan diinput
            <tr><td width='24%'><font>Keterangan Gambar 4</font></td>
              <td width='76%'> <input type='text' name='ket3' size='50'  >
              </td></tr> 
			<tr><td width='24%'><font>File Gambar 4</font></td>
              <td width='76%'><input type=\"file\" name=\"fileban3\"><input type='checkbox' name='cek4' value='on' checked >Ceklis jika ada file gambar yang akan diinput<br><br><br><br><font>File Gambar format JPG 640 x 480 px, ukuran maksimal 200 kbyte<br><input type='checkbox' name='cropgbr' value='on' checked > Gambar di Crop atau dipotong menjadi 640 x 480 px
              </td></tr>

			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"galeri_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 //------------------ lihat galeri--------------------------
 function galeri() {
  // ditambah alan untuk seleksi halaman\
   include "koneksi.php";
  $hal=$_GET['hal'];$id=$_GET['id'];
    $query = "SELECT * FROM t_galerialbum WHERE idalbum='". mysql_escape_string($id)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $r = mysql_fetch_array($result);
  $album = $r[album];
  $brs=20;
  $kol=10;
  $byk_result1=mysql_query("select * from t_galeri where idalbum='". mysql_escape_string($id)."'");
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
    		
  $query = "SELECT * from t_galeri where idalbum='". mysql_escape_string($id)."' order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='5' align='center'><font>Daftar Photo dari Album <b>$album</b></font></td></tr>";
  
    if ($jml!=0) {
  echo "<tr><td colspan='5'><center><font class='ver10'><a href='admin.php?mode=galeri&id=$id&hal=1' class='ver10' title='Page 1'>First </a> 
  <a href='admin.php?mode=galeri&id=$id&hal=$back' class='ver10' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	echo "<b><a href='admin.php?mode=galeri&id=$id&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=galeri&id=$id&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=galeri&id=$id&hal=$next' class='ver10' title='$next'> Next</a> 
  <a href='admin.php?mode=galeri&id=$id&hal=$jml' class='ver10' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Photo</center></font></td>
  <td><font><center>Ket</center></font></td>
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
    <td width='10%' ><center><img src='../images/galeri/gb$row[id].jpg' width='100' height='60'></center></td>
	<td width='30%' ><font>$row[ket]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=galeri_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
";
  echo "<input type=\"hidden\" name=\"mode\" value=\"galeri_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=galeri_tam&id=$id'>Tambah Photo</a> |
				<a href='admin.php?mode=album'>Lihat Album Photo</a> |</form><br>";
  
 } 

//hapus galeri
 function galeri_hap() {
 include "koneksi.php";
 $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_galeri where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../images/galeri/gb".$key.".jpg";
			if (file_exists($file)) {
			unlink($file);
			}
		}
	  }

 } 
 
    //-------------------------- prestasi
 function prestasi_edit() {
  include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_prestasi WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  echo "<form action='admin.php'  method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian prestasi</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='20' maxlength='200' value='$row[judul]'>
              </td></tr> 
			   <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <textarea name=ket rows=10 cols=50>$row[ket]</textarea>
              </td></tr> 
			 <tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name=\"fileban\"><font> File Gambar format jpg 200 x 170 px
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"prestasi_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // perubahan simpan prestasi
 function prestasi_save() {
  include "koneksi.php";
 $idn=$_POST['idn'];$judul=$_POST['judul'];$ket=$_POST['ket'];$edit=$_POST['edit'];
 $admin = $user[user_id];
  $fileban = $_FILES['fileban']['tmp_name'];
 if ($edit!=1) {
     $sql = "SELECT max(id) AS total FROM t_prestasi";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
    if($fileban == '') {
    }
    else {
	if (file_exists($fileban)) {
	$newfile="".$total.".jpg";
	$gb="../images/prestasi/".$total.".jpg";
	if (file_exists($gb)) unlink($gb);
    copy($fileban, "../images/prestasi/$newfile");
		}
	else {
		$tdk="File gambar yang diimputkan tidak ada";
		}
	}
  $sql = "insert into t_prestasi (id,judul,ket) values ('$total','". mysql_escape_string($judul)."','". mysql_escape_string($ket)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  echo "<font>$tdk<br>Penambahan prestasi berhasil<br>Silahkan pilih menu kembali !!!<br><br>
   | <a href='admin.php?mode=prestasi'>Lihat Prestasi</a> | <a href='admin.php?mode=prestasi_tam'>Tambah Prestasi</a> |</font>"; 
 }
 else {
   if($fileban== '') {
    }
    else {
	if (file_exists($fileban)) {
	$tdk='';
	$gb="../images/prestasi/".$idn.".jpg";
	if (file_exists($gb)) unlink($gb);
    copy($fileban, "../images/prestasi/".$idn.".jpg");
	}
	else {
		$tdk="Perubahan File gambar tidak berhasil ! File tidak diketemukan";
		}
	}
  $sql = "update t_prestasi set judul='". mysql_escape_string($judul)."',ket='". mysql_escape_string($ket)."' where id='". mysql_escape_string($idn)."'";
  if(!$alan=mysql_query($sql)) die ("Perubahan gagal");
  echo "<font>$tdk<br>Perubahan prestasi berhasil<br>Silahkan pilih menu kembali !!!<br><br>
   | <a href='admin.php?mode=prestasi'>Lihat Prestasi</a> | <a href='admin.php?mode=prestasi_tam'>Tambah Prestasi</a> |</font>";
 }    
  
 } 

 // tambah prestasi
 function prestasi_tam() {
  
    echo "<form action='admin.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian prestasi</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='20' maxlength='200' >
              </td></tr> 
	            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <textarea name=ket rows=10 cols=50></textarea>
              </td></tr> 
			<tr><td width='24%'><font>File Gambar</font></td>
              <td width='76%'> <input type=\"file\" name=\"fileban\"><font> File Gambar format jpg 200 x 170 px
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"prestasi_save\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 //------------------ lihat prestasi--------------------------
 function prestasi() {
  // ditambah alan untuk seleksi halaman
   include "koneksi.php";
	$hal=$_GET['hal'];  
  $brs=20;
  $kol=10;
  $byk_result1=mysql_query("select * from t_prestasi");
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
    		
  $query = "SELECT * from t_prestasi order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data prestasi di Database <br><a href='admin.php?mode=prestasi_tam'>Tambah prestasi</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='5' align='center'><font>--- Daftar prestasi ---</font></td></tr>";
  
    if ($jml!=0) {
  echo "<tr><td colspan='5'><center><font class='ver10'><a href='admin.php?mode=prestasi&hal=1' class='ver10' title='Page 1'>First </a> 
  <a href='admin.php?mode=prestasi&hal=$back' class='ver10' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	echo "<b><a href='admin.php?mode=prestasi&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=prestasi&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=prestasi&hal=$next' class='ver10' title='$next'> Next</a> 
  <a href='admin.php?mode=prestasi&hal=$jml' class='ver10' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Photo</center></font></td>
  <td><font><center>Ket</center></font></td>
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
    <td width='10%' ><center><img src='../images/prestasi/$row[id].jpg' width='100' height='60'></center></td>
	<td width='30%' ><font>$row[judul]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=prestasi_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"prestasi_hap\">
                <input type=\"submit\" value=\"Hapus\">| <a href='admin.php?mode=prestasi_tam'>Tambah prestasi</a> |</form><br>";
  
 } 

//hapus prestasi
 function prestasi_hap() {
 include "koneksi.php";
 $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="delete from t_prestasi where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../images/prestasi/".$key.".jpg";
			if (file_exists($file)) {
			unlink($file);
			}
		}
	  }

 } 

 
}

?>