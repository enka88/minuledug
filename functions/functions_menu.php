<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
// fungsi berhubungan dengan menu web depan
class menuclass {
  /****************************************** posisi menu ****************/
function posmenu() {
include("koneksi.php");

  $i=1;
  echo "<font><b>--> Daftar Posisi Menu Modul<br><br><br>
  <table border='1' bordercolor='#000000' cellpadding='2' cellspacing='0' width='100%'>
  <tr bgcolor='#dcddcc'><td><font><b>No</td><td><font><b>Judul Menu</td><td><font><b>Posisi</td><td><font><b>Urut</td><td><font><b>Kategori</td><td><font><center><b>Script</td><td><font><center><b>Status</td><td><font><center><b>Edit</td><td><font><b>Hapus</td></tr>";
  $sql="select * from t_pos_menu order by kategori,posisi,urut";
  $mysql_result=mysql_query($sql);
  while($row=mysql_fetch_array($mysql_result)) {
  	if ($row[posisi]=='L') $pos='Kiri';
	elseif ($row[posisi]=='R') $pos='Kanan';
	else $pos ='Tengah';
	if ($row[sembunyi]=='t') $stgbr='hide.gif';
	else $stgbr='show.gif';
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td><td><font>$row[menu]</td><td><font>$pos</td><td><font>$row[urut]</td><td><font>$row[kategori]</td><td><font>tag_$row[fungsi].php</td><td><font><center>
	<a href='admin.php?mode=sembunyi_posmenu&kd=$row[id]&st=$row[sembunyi]' title='klik untuk merubah status ditampilkan' ><img src='../images/$stgbr' border=0 ></a></td>
	<td><center><font><a href='admin.php?mode=edit_posmenu&id=$row[id]'><img src='../images/edit.gif' border=0 ></a></td><td><font>
	<a href='admin.php?mode=del_posmenu&kd=$row[id]' onClick=\"return confirm('Anda yakin Data Menu $row[menu] akan dihapus ?');\">Hapus</a></td>
	</tr>";
 	$i++;
  }
  echo"</table><br><br>| <a href='admin.php?mode=tam_posmenu'>Tambah Posisi Menu</a> | <br><br>";
}
  
  function edit_posmenu() {
  include "koneksi.php";
  $id=$_GET['id'];
  $sql="select * from t_pos_menu where id='".mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
  	echo"<font><b>==> Perubahan Posisi Menu</b><br><br>";
	echo"<form action='../functions/simmenu.php?save=edit&id=$id' method=\"post\" enctype='multipart/form-data'>";
    echo"<font>Judul Menu &nbsp;: <input type=text name=menu value='$row[menu]' size=40 maxlenght=100><br>";

	if ($row[posisi]=='R') $s0="selected";
	elseif ($row[posisi]=='L') $s1="selected";
	else $s2="selected";
	if ($row[kategori]=='depan') $k1="selected";
	elseif ($row[kategori]=='profil') $k2="selected";
	elseif ($row[kategori]=='guru') $k3="selected";
	elseif ($row[kategori]=='siswa') $k4="selected";
	elseif ($row[kategori]=='alumni') $k5="selected";
	elseif ($row[kategori]=='fitur') $k6="selected";
	else $k7="selected";	
	echo"Posisi Menu : <select name=posisi><option value='R' $s0>Kanan</option><option value='L' $s1>Kiri</option>
	<option value='T' $s2>Tengah</option></select><br>
			<script language=\"javascript1.2\">
	          function sintak() {
	    	    window.open('../functions/editfile.php?file=$row[fungsi]','Edit Script fungsi','width=700,height=400,resizable=no,scrollbars=no'); 
              }	
	          </script>
	Kategori &nbsp;&nbsp;&nbsp;&nbsp;: <select name=kategori><option value='depan' $k1>Depan</option><option value='profil' $k2>Profil</option>
	<option value='guru' $k3>Guru</option><option value='siswa' $k4>Siswa</option><option value='alumni' $k5>Alumni</option><option value='fitur' $k6>Fitur</option></select><br>";
	echo"Urut &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input typ=text name=urut value='$row[urut]' size=5><br><br>
	File Script Fungsi : <input type='file' name='nfile' > <a href='#'  onclick='sintak()' >Edit Script fungsi</a><br>
	Pilih Script Fungsi yg ada : <select name='pilfungsi'><option value='-'>Tidak Pilih</option>";
	$sql="select distinct(fungsi) from t_pos_menu ";
  	$mysql_result=mysql_query($sql);
  	while($r=mysql_fetch_array($mysql_result)) {
		if ($r[fungsi]==$row[fungsi]) echo "<option value='$r[fungsi]' selected>$r[fungsi]</option>";
		else echo "<option value='$r[fungsi]'>$r[fungsi]</option>";
	}	
	echo"</select><br>Template Menu : <br><input type=radio value='0' name='idtemp' checked>  Tidak Ada<br>";
	
	$sql="select * from t_temp_menu ";
  	$mysql_result=mysql_query($sql);
  	while($r=mysql_fetch_array($mysql_result)) {
	if ($r[idtemp]==$row[idtemp]) { echo "<table bgcolor='blue'><tr><td><input type=radio value='$r[idtemp]' name='idtemp' checked></td><td>	$r[temp_atas]judul$r[temp_tengah]isi$r[temp_bawah]</td></tr></table>"; }
	else { echo "<table><tr><td><input type=radio value='$r[idtemp]' name='idtemp' ></td><td>$r[temp_atas]judul$r[temp_tengah]isi$r[temp_bawah]</td></tr></table>"; }
	}
	echo "<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";


  
}
  //hapus 
function del_posmenu() {
include("koneksi.php");
$kd=$_GET['kd'];
  if (!empty($kd))
  {
	$sql="delete from t_pos_menu where id='".mysql_escape_string($kd)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
  }

} 
function sembunyi_posmenu() {
include("koneksi.php");
$kd=$_GET['kd'];
$st=$_GET['st'];
  if (!empty($kd))
  {
  	if ($st=='y') $st='t';
	else $st='y';
	$sql="update t_pos_menu set sembunyi='$st' where id='".mysql_escape_string($kd)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
  }
} 
function tam_posmenu() {
  include "koneksi.php";
  	echo"<font><b>==> Penambahan Posisi Menu</b><br><br>";
	echo"<form action='../functions/simmenu.php' method=\"post\" enctype='multipart/form-data'>";
    echo"<font>Judul Menu &nbsp;: <input type=text name=menu value='$row[menu]' size=40 maxlenght=100><br>
		<script language=\"javascript1.2\">
	          function contoh() {
	    	    window.open('../functions/contohfile.php','Contoh','width=700,height=400,resizable=no,scrollbars=no'); 
              }	
	          </script>";
	echo"Posisi Menu : <select name=posisi><option value='R' >Kanan</option><option value='L' >Kiri</option>
	<option value='T'>Tengah</option></select><br>
	Kategori &nbsp;&nbsp;&nbsp;&nbsp;: <select name=kategori><option value='depan'>Depan</option><option value='profil'>Profil</option>
	<option value='guru' >Guru</option><option value='siswa' >Siswa</option><option value='alumni'>Alumni</option><option value='fitur'>Fitur</option></select><br>";
	echo"Urut &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input typ=text name=urut  size=5><br><br>
	File Script Fungsi : <input type='file' name='nfile' > <a href='#' onclick='contoh()' >Contoh Penulisan Script</a><br>
	Pilih Script Fungsi yg ada : <select name='pilfungsi'><option value='-'>Tidak Pilih</option>";
	$sql="select distinct(fungsi) from t_pos_menu ";
  	$mysql_result=mysql_query($sql);
  	while($r=mysql_fetch_array($mysql_result)) {
	 echo "<option value='$r[fungsi]'>$r[fungsi]</option>";
	}	
	echo"</select><br>Template Menu : <br><input type=radio value='0' name='idtemp' checked>  Tidak Ada<br>";
	$sql="select * from t_temp_menu ";
  	$mysql_result=mysql_query($sql);
  	while($r=mysql_fetch_array($mysql_result)) {
		 echo "<table><tr><td><input type=radio value='$r[idtemp]' name='idtemp' ></td><td>$r[temp_atas]judul$r[temp_tengah]isi$r[temp_bawah]</td></tr></table>";
	}
	echo "<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";

 
}

//****************************************** template menu **************************//
function tempmenu() {
  include("koneksi.php");
  $i=1;
  echo "<font><b>--> Daftar Template Menu<br><br><br>
  <table border='1' bordercolor='#000000' cellpadding='2' cellspacing='0' width='98%'>
  <tr bgcolor='#dcddcc'><td><font><b>No</td><td><font><b>Posisi</td><td><font><b>Template</td><td><font><center><b>Edit</td><td><font><b>Hapus</td></tr>";
  $sql="select * from t_temp_menu ";
  $mysql_result=mysql_query($sql);
  while($row=mysql_fetch_array($mysql_result)) {
  	if ($row[status]=='L') $pos='Kiri';
	elseif ($row[status]=='R') $pos='Kanan';
	else $pos ='Tengah';
  	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$i</td><td><font>$pos</td><td><table width=200><tr><td>$row[temp_atas]Judul$row[temp_tengah]isi$row[temp_bawah]</td></tr></table></td><td><center><font><a href='admin.php?mode=edit_tempmenu&id=$row[idtemp]'><img src='../images/edit.gif' border=0></a></td><td><font>
	<a href='admin.php?mode=del_tempmenu&kd=$row[idtemp]' onClick=\"return confirm('Anda yakin Template Menu ini akan dihapus ?');\">Hapus</a></td>
	</tr>";
 	$i++;
  }
  echo"</table><br><br><a href='admin.php?mode=tam_tempmenu'>Tambah Template Menu</a>";
  

}
 function edit_tempmenu() {
  include "koneksi.php";
  $id=$_GET['id'];
  
  $sql="select * from t_temp_menu where idtemp='".mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
  	echo"<font><b>==> Perubahan Template Menu</b><br><br>";
	echo"<form action='../functions/simtemp.php?save=edit&id=$id' method=\"post\" >";
	if ($row[posisi]=='L') $s1="selected";
	else $s2="selected";
	echo"Posisi Menu : <select name=posisi><option value='L' $s1>Kiri</option>
	<option value='T' $s2>Tengah</option></select><br>
	<script language=\"javascript1.2\">
		     function upload() {
	    	    window.open('../functions/uploadfile.php','Upload Gambar','width=500,height=330,resizable=no,scrollbars=no'); 
              }	
	          </script>
	File Images/Gambar diupload di sini <a href='#' onclick='upload()' >Upload Gambar</a><br>";
	echo"Template dibagi 3 bagian yaitu :<br>
	<b>Template Atas &nbsp;&nbsp;: <br><textarea name='atas' cols=70 rows=10 >$row[temp_atas]</textarea><br><br>
	Template Tengah : <br><textarea name='tengah' cols=70 rows=10 >$row[temp_tengah]</textarea><br><br>
	Template Bawah &nbsp;: <br><textarea name='bawah' cols=70 rows=10 >$row[temp_bawah]</textarea><br> ";
	
	echo "<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";
}
  //hapus 
function del_tempmenu() {
include("koneksi.php");
$kd=$_GET['kd'];
  if (!empty($kd))
  {
	$sql="delete from t_temp_menu where idtemp='".mysql_escape_string($kd)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
  }

} 
 function tam_tempmenu() {
  	echo"<font><b>==> Penambahan Template Menu</b><br><br>";
	echo"<form action='../functions/simtemp.php?save=tambah' method=\"post\" >";
	if ($row[posisi]=='L') $s1="selected";
	else $s2="selected";
	echo"Posisi Menu : <select name=posisi><option value='L' $s1>Kiri</option>
	<option value='T' $s2>Tengah</option></select><br>
	<script language=\"javascript1.2\">
		     function upload() {
	    	    window.open('../functions/uploadfile.php','Upload Gambar','width=500,height=330,resizable=no,scrollbars=no'); 
              }	
	          </script>
	File Images/Gambar diupload di sini <a href='#' onclick='upload()' >Upload Gambar</a><br>";
	echo"Template dibagi 3 bagian yaitu :<br>
	<b>Template Atas &nbsp;&nbsp;: <br><textarea name='atas' cols=70 rows=10 ></textarea><br><br>
	Template Tengah : <br><textarea name='tengah' cols=70 rows=10 ></textarea><br><br>
	Template Bawah &nbsp;: <br><textarea name='bawah' cols=70 rows=10 ></textarea><br> ";
	
	echo "<input type='reset' value='Ulang' > &nbsp;<input type=submit class=button name=submit value='Simpan' ></form>";

}

}
?>