<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
class downloadclass {
 //-------------------------- soal
 function soal_edit() {
 $idn=$_GET['id'];

  $query = "SELECT * FROM t_soal WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  echo "<form action='../functions/fungsi_sim.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Materi Uji</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' value='$row[judul]' size='50' maxlength='180' >
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" >$row[deskripsi]</textarea>
              </td></tr> 
			  <tr><td width='24%'><font>Kategori</font></td>
			  <td width='76%'><select name=kategori>";
	$query = "SELECT idpel,pel FROM t_pelajaran  order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {

		if ($row[kategori]==$k[pel]) echo"<option value='$k[pel]' selected >$k[pel]</option>";
		else echo"<option value='$k[pel]'>$k[pel]</option>";
	}			  
	$query = "SELECT * FROM t_kategori where jenis='2' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[kategori]==$k[kategori]) echo"<option selected value='$k[kategori]'>$k[kategori]</option>";
		else echo"<option value='$k[kategori]'>$k[kategori]</option>";
	}
              echo "</select></td></tr> 
			 <tr><td width='24%'><font>File Download</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP.
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"soal_save\">
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Update\">
              </td></tr></table></form>";

 } 

 // tambah soal
 function soal_tam() {
      echo "<form action='../functions/fungsi_sim.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Kumpulan Soal</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='50' maxlength='180' >
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" ></textarea> 
              </td></tr> 
			  <tr><td width='24%'><font>Kategori</font></td>
			  <td width='76%'><select name=kategori>";
	$query = "SELECT idpel,pel FROM t_pelajaran  order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {

		if ($row[kategori]==$k[pel]) echo"<option value='$k[pel]' selected >$k[pel]</option>";
		else echo"<option value='$k[pel]'>$k[pel]</option>";
	}			  
	$query = "SELECT * FROM t_kategori where jenis='2' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[kategori]==$k[kategori]) echo"<option selected value='$k[kategori]'>$k[kategori]</option>";
		else echo"<option value='$k[kategori]'>$k[kategori]</option>";
	}
              echo "</select></td></tr> 
			<tr><td width='24%'><font>File Download</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP.
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"soal_save\">
				<input type=\"hidden\" name=\"edit\" value='2'>
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
 } 

 //------------------ lihat soal--------------------------
 function soal() {
 include "koneksi.php";
  $hal=$_GET['hal'];
  $kode=$_GET['kode'];
  $brs=30;
  $kol=10;
  $byk_result1=mysql_query("select * from t_soal where kategori='". mysql_real_escape_string($kode)."'");
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
    		
  $query = "SELECT * from t_soal where kategori='$kode' order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='7' align='center'><font>--- Daftar Kumpulan Soal ---</font></td></tr>
  <tr><td colspan='7' align='center'>
  <table border=0  width=100% ><tr><td><center>| ";
 	$query = "SELECT idpel,pel FROM t_pelajaran order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[pel]) echo "<b><a href='admin.php?mode=soal&kode=$k[pel]' class=ver11>$k[pel]</a></b> | ";
		else echo "<a href='admin.php?mode=soal&kode=$k[pel]' class=ver10>$k[pel]</a> | ";
	}
	$query = "SELECT * FROM t_kategori where jenis='2' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[id_kategori]) echo "<b><a href='admin.php?mode=soal&kode=$k[kategori]' class=ver11>$k[kategori]</a></b> | ";
		else echo "<a href='admin.php?mode=soal&kode=$k[kategori]' class=ver10>$k[kategori]</a> | ";
	}
	echo"</td></tr></table></td></tr>";
  
  if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font class='ver10'><a href='admin.php?mode=soal&kode=$kode&hal=1' class='ver10' title='Page 1'>First </a> 
  <a href='admin.php?mode=soal&kode=$kode&hal=$back' class='ver10' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	echo "<b><a href='admin.php?mode=soal&kode=$kode&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=soal&kode=$kode&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=soal&kode=$kode&hal=$next' class='ver10' title='$next'> Next</a> 
  <a href='admin.php?mode=soal&kode=$kode&hal=$jml' class='ver10' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Judul</center></font></td>
  <td><font><center>File</center></font></td>  
    <td><font><center>Ukuran</center></font></td>  <td><font><center>Visit</center></font></td>
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
    <td width='40%' ><font>$row[judul]</center></td>
	<td width='10%' ><font>$row[file]</font></td>
	<td width='15%' ><font>$row[ukuran]</font></td>
	<td width='5%' ><font>$row[visit]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=soal_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
";
  echo "<input type=\"hidden\" name=\"mode\" value=\"soal_hap\">
                <input type=\"submit\" value=\"Hapus\">| <a href='admin.php?mode=soal_tam'>Tambah Materi Uji</a> |</form><br>";
  
 } 

//hapus soal
 function soal_hap() {
 $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="select * from t_soal where id=$key";
			if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 soal");
			$row=mysql_fetch_array($query);
			$file= "../soal/".$row[file];
			if (file_exists($file)) {
			unlink($file);
			}
			$sql="delete from t_soal where id=$key";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");

		}
	  }

 } 
 
    //-------------------------- download
 function download_edit() {
  include "koneksi.php";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_download WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  echo "<form action='../functions/fungsi_sim.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Download Materi Ajar</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' value='$row[judul]' size='50' maxlength='180' >
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" >$row[deskripsi]</textarea>
              </td></tr> 
			  <tr><td width='24%'><font>Kategori</font></td>
			  <td width='76%'><select name=kategori>";
			  
	$query = "SELECT idpel,pel FROM t_pelajaran  order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {

		if ($row[kategori]==$k[pel]) echo"<option value='$k[pel]' selected >$k[pel]</option>";
		else echo"<option value='$k[pel]'>$k[pel]</option>";
	}			  
	$query = "SELECT * FROM t_kategori where jenis='1' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[kategori]==$k[kategori]) echo"<option selected value='$k[kategori]'>$k[kategori]</option>";
		else echo"<option value='$k[kategori]'>$k[kategori]</option>";
	}
              echo "</select></td></tr> 
			 <tr><td width='24%'><font>File Download</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP.
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"download_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // tambah download
 function download_tam() {
  include "koneksi.php";
  
      echo "<form action='../functions/fungsi_sim.php' method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Download Materi Ajar</b><font></td>	</tr>
            <tr><td width='24%'><font>Judul</font></td>
              <td width='76%'> <input type='text' name='judul' size='50' maxlength='180' >
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" ></textarea> 
              </td></tr> 
			  <tr><td width='24%'><font>Kategori</font></td>
			  <td width='76%'><select name=kategori>";
	$query = "SELECT idpel,pel FROM t_pelajaran  order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {

		if ($row[kategori]==$k[pel]) echo"<option value='$k[pel]' selected >$k[pel]</option>";
		else echo"<option value='$k[pel]'>$k[pel]</option>";
	}			  
	$query = "SELECT * FROM t_kategori where jenis='1' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		$sel="";
		if ($row[kategori]==$k[kategori]) echo"<option selected value='$k[kategori]'>$k[kategori]</option>";
		else echo"<option value='$k[kategori]'>$k[kategori]</option>";
	}
              echo "</select></td></tr> 
			<tr><td width='24%'><font>File Download</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP. 
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"download_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='2'>
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
  
 } 

 //------------------ lihat download--------------------------
 function download() {
  // ditambah alan untuk seleksi halaman
  include "koneksi.php";
  $hal=$_GET['hal'];
  $kode=$_GET['kode'];
  $brs=30;
  $kol=10;
  $byk_result1=mysql_query("select * from t_download where kategori='$kode'");
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
    		
  $query = "SELECT * from t_download where kategori='$kode' order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='7' align='center'><font>--- Daftar Download Materi Ajar---</font></td></tr>
  <tr><td colspan='7' align='center'>
  <table border=0  width=100% ><tr><td><center>| ";
 	$query = "SELECT idpel,pel FROM t_pelajaran order by pel";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[pel]) echo "<b><a href='admin.php?mode=download&kode=$k[pel]' class=ver11>$k[pel]</a></b> | ";
		else echo "<a href='admin.php?mode=download&kode=$k[pel]' class=ver10>$k[pel]</a> | ";
	}
	$query = "SELECT * FROM t_kategori where jenis='1' order by kategori";
    $res = mysql_query($query) or die("Query failed");
    while($k = mysql_fetch_array($res)) {
		if ($kode==$k[id_kategori]) echo "<b><a href='admin.php?mode=download&kode=$k[kategori]' class=ver11>$k[kategori]</a></b> | ";
		else echo "<a href='admin.php?mode=download&kode=$k[kategori]' class=ver10>$k[kategori]</a> | ";
	}
	echo"</td></tr></table></td></tr>";
  
    if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font class='ver10'><a href='admin.php?mode=download&kode=$kode&hal=1' class='ver10' title='Page 1'>First </a> 
  <a href='admin.php?mode=download&kode=$kode&hal=$back' class='ver10' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	echo "<b><a href='admin.php?mode=download&kode=$kode&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  		echo "<a href='admin.php?mode=download&kode=$kode&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=download&kode=$kode&hal=$next' class='ver10' title='$next'> Next</a> 
  <a href='admin.php?mode=download&kode=$kode&hal=$jml' class='ver10' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Judul</center></font></td>
  <td><font><center>File</center></font></td>  
    <td><font><center>Ukuran</center></font></td>  <td><font><center>Visit</center></font></td>
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
    <td width='40%' ><font>$row[judul]</center></td>
	<td width='10%' ><font>$row[file]</font></td>
	<td width='15%' ><font>$row[ukuran]</font></td>
	<td width='5%' ><font>$row[visit]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=download_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
";
  echo "<input type=\"hidden\" name=\"mode\" value=\"download_hap\">
                <input type=\"submit\" value=\"Hapus\">| <a href='admin.php?mode=download_tam'>Tambah Materi Ajar</a> |</form><br>";
  
 } 

//hapus download
 function download_hap() {
 include "koneksi.php";
 $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="select * from t_download where id='". mysql_escape_string($key)."'";
			if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 download");
			$row=mysql_fetch_array($query);
			$file= "../download/".$row[file];
			if (file_exists($file)) {
			unlink($file);
			}
			$sql="delete from t_download where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");

		}
	  }

 } 
   //-------------------------- silabus
 function silabus_edit() {
 include "koneksi.php";
 $idn=$_GET['id'];$program=$_GET['program'];
	
  $query = "SELECT * FROM t_silabus WHERE id='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
      $data1 ="<select name='pelajaran' >";
	if ($program=='') $sql="select pel,program from t_pelajaran order by program,pel";
	else  $sql="select pel,program from t_pelajaran where program='$program' or program='-' order by program,pel";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 pelajaran ");
	while($al=mysql_fetch_array($q)) {
		if ($row[pelajaran]==$al[pel])  $data1.="<option value='$al[pel]' selected>$al[pel]</option>";
		else $data1.="<option value='$al[pel]'>$al[pel]</option>";
	}
	$data1 .='</select>';
  echo "<form action='../functions/fungsi_sim.php' method=\"post\" enctype=\"multipart/form-data\" name=silabus >
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian silabus </b><font></td>	</tr>";
             if ($cmstingkat=='sma' or $cmstingkat=='smk') {
                echo " <tr><td width='24%'><font>Jurusan\Program studi</font></td>
              <td width='76%'>";
                $data ='<select name="program" onchange="document.location.href=\'admin.php?mode=silabus_edit&id='.$idn.'&program=\'+document.silabus.program.value">';
            	$sql="select * from t_programahli order by idprog";
            	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 program ");
            	while($al=mysql_fetch_array($q)) {
              		if ($al[program]==$program)echo  "<option value='$al[program]' selected>$al[program]</option>";
              		else echo "<option value='$al[program]' >$al[program]</option>";
            	}
            	echo '</select></td></tr> ';
              }
              else {
                echo "<input type=hidden name=program value='-'/>";
              }
            echo "<tr><td width='24%'><font>Pelajaran</font></td>
              <td width='76%'> $data1
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" >$row[ket]</textarea>
              </td></tr> 
			  <tr><td width='24%'><font>Kelas</font></td>
			  <td width='76%'><input type='text' name='kelas' size='10' maxlenght='20' value='$row[kelas]'>";
  	$data2 .="<select name=sem >";
	$sql2="select * from t_semester ";
	if(!$q1=mysql_query($sql2)) die ("Pengambilan gagal1 kelas ");
	while($r=mysql_fetch_array($q1)) {
		if ($row[semester]==$r[semester]) $data2 .="<option value='$r[semester]' selected>$r[semester]</option>";
		else $data2.="<option value='$r[semester]'>$r[semester]</option>";
	}
	$data2 .='</select>';
              echo "</select></td></tr> 
			 			 <tr><td width='24%'><font>Semester</font></td>
              <td width='76%'> $data2
              </td></tr>
			 <tr><td width='24%'><font>File silabus</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP. 
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"silabus_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[id]\">
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // tambah silabus
 function silabus_tam() {
 include "koneksi.php";
  $program=$_GET['program'];
  if ($program=='') $program='-';
  
    $data1 ="<select name='pelajaran' >";
	$sql="select pel from t_pelajaran where program='". mysql_escape_string($program)."' or program='-' order by program,pel";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 pelajaran ");
	while($row=mysql_fetch_array($q)) {
		 $data1.="<option value='$row[pel]'>$row[pel]</option>";
	}
	$data1 .='</select>';

      echo "<form action='../functions/fungsi_sim.php' method=\"post\" enctype=\"multipart/form-data\" name=silabus >
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Silabus Materi Ajar</b><font></td>	</tr>";
            if ($cmstingkat=='sma' or $cmstingkat=='smk') {
                echo "<tr><td width='24%'><font>Jurusan/Program studi</font></td>
              <td width='76%'>";
                echo  '<select name="program" onchange="document.location.href=\'admin.php?mode=silabus_tam&program=\'+document.silabus.program.value">';
            	$sql="select * from t_programahli order by idprog";
            	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 program ");
            	while($al=mysql_fetch_array($q)) {
              		if ($al[program]==$program) echo "<option value='$al[program]' selected>$al[program]</option>";
              		else echo  "<option value='$al[program]' >$al[program]</option>";
            	}
            	echo '</select> </td></tr> ';
              }
              else {
                echo "<input type=hidden name=program value='-'/>";
              }
            echo "<tr><td width='24%'><font>Pelajaran</font></td>
              <td width='76%'> $data1
              </td></tr> <tr><td width='24%'><font>Kelas</font></td>
              <td width='76%'> <input type=text name=kelas size='10' maxlenght='20'>
              </td></tr> 
			 <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'><textarea name='ket' cols=\"40\" rows=\"5\" ></textarea>
              </td></tr> ";
  	$data2 ="<select name='sem' >";
	$sql="select * from t_semester ";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 kelas ");
	while($row=mysql_fetch_array($q)) {
		 $data2.="<option value='$row[semester]'>$row[semester]</option>";
	}
	$data2 .='</select>';
              echo " 
			 <tr><td width='24%'><font>Semester</font></td>
              <td width='76%'> $data2
              </td></tr>
			<tr><td width='24%'><font>File silabus</font></td>
              <td width='76%'> <input type=\"file\" name=\"file\"> <font><font>Format file .DOC, .DOCX, .XLSX, .XLS, .PDF., .SWF, atau .ZIP.
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"silabus_save\"><input type='reset' value='Ulang' > &nbsp;
				<input type=\"hidden\" name=\"edit\" value='2'>
                <input type=\"submit\" value=\"Simpan\" >
              </td></tr></table></form>";
  
 } 

 //------------------ lihat silabus--------------------------
 function silabus() {
 include "koneksi.php";
  // ditambah alan untuk seleksi halaman
  $hal=$_GET['hal'];$semester=$_GET['semester'];
  if ($semester=='') $semester='1';
    $data ='<select name="semester" onchange="document.location.href=\'admin.php?mode=silabus&semester=\'+document.silabus.semester.value">';
	$sql="select * from t_semester order by semester";
	if(!$q=mysql_query($sql)) die ("Pengambilan gagal1 program ");
	while($al=mysql_fetch_array($q)) {
  		if ($al[semester]==$semester)$data .= "<option value='$al[semester]' selected>$al[semester]</option>";
  		else $data .="<option value='$al[semester]' >$al[semester]</option>";
	}
	$data .='</select>';

	
  $brs=30;
  $kol=10;
  $byk_result1=mysql_query("select * from t_silabus where semester='". mysql_escape_string($semester)."'");
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
    		
  $query = "SELECT * from t_silabus where semester='". mysql_escape_string($semester)."' order by id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\" name=silabus >";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000' width=100% >
  <tr><td bgcolor='#999999' colspan='7' align='center'><font>--- Daftar Silabus---</font></td></tr>
  <tr><td bgcolor='#999999' colspan='7' ><font>Semester $data </font></td></tr>";
  
    if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font class='ver10'><a href='admin.php?mode=silabus&semester=$semester&hal=1' class='ver10' title='Page 1'>First </a> 
  <a href='admin.php?mode=silabus&semester=$semester&hal=$back' class='ver10' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	echo "<b><a href='admin.php?mode=silabus&semester=$semester&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=silabus&semester=$semester&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=silabus&semester=$semester&hal=$next' class='ver10' title='$next'> Next</a> 
  <a href='admin.php?mode=silabus&semester=$semester&hal=$jml' class='ver10' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Pelajaran</center></font></td>
  <td><font><center>Kelas</center></font></td><td><font><center>File</center></font></td>  
    <td><font><center>Visit</center></font></td>
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
    <td width='30%' ><font>$row[pelajaran]</center></td>
	<td width='5%' ><font>$row[kelas]</font></td>
	<td width='10%' ><font>$row[file]</font></td>
	<td width='5%' ><font>$row[visit]</font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=silabus_edit&id=<?php echo $row[id]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[id]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua
";
  echo "<input type=\"hidden\" name=\"mode\" value=\"silabus_hap\">
                <input type=\"submit\" value=\"Hapus\">| <a href='admin.php?mode=silabus_tam'>Tambah Silabus</a> |</form><br>";
  
 } 

//hapus silabus
 function silabus_hap() {
 include "koneksi.php";
 $id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="select * from t_silabus where id='". mysql_escape_string($key)."'";
			if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 silabus");
			$row=mysql_fetch_array($query);
			$file= "../silabus/".$row[file];
			if (file_exists($file)) {
			unlink($file);
			}
			$sql="delete from t_silabus where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
		}
	  }
 } 

} //-akhir
?>