<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
/****************************************** profil ***************************************************/ 
class profilclass {
// profil
function profil() {
include("koneksi.php");

  $m=1;
  echo "<font><b>--> Daftar Menu & Profil</b><br><br><br>
  <table border='1' bordercolor='#000000' cellpadding='2' cellspacing='0' width='100%'>
  <tr bgcolor='#dcddcc'><td width='20'><font><b>No</td><td width='100' ><font><b>Menu</td><td width='100'><font><b>Submenu 1</td>
  <td width='100' ><font><b>Submenu 2</td><td width='100'><font><b>Submenu 3</td>
  <td width='80'><font><b>Posisi</b></td><td width='20'><font><center><b>Status</td><td width='20'><font><center><b>ID Tag</td><td width='60'><font><center><b>Edit</td><td width='20'><font><b>Hapus</td></tr>";
  $sql="select id,judul,link,urut,parent,hide from t_profil where parent='0'   order by urut";
  $mysql_result=mysql_query($sql);
  while($row=mysql_fetch_array($mysql_result)) {
  	//cek ditampilkan atau tidak ( 0=tampil, 1=hide)
	if ($row[hide]=='0') $hide="<a href='admin.php?mode=hideprofil&kode=$row[id]&status=1' title='Sembunyikan menu' ><img src='../images/hide.gif' border=0 ></a>";
	else $hide="<a href='admin.php?mode=hideprofil&kode=$row[id]&status=0' title='Tampilkan menu' ><img src='../images/show.gif' border=0 ></a>";
   
	// panah untuk pindah ke atas,bawah, kiri kanan
	$atas = "<a href='admin.php?mode=pindahprofil&kode=$row[id]&st=atas&p=$row[parent]&pos=".($row[urut]-1)."' title='Pindah ke atas' ><img src='../images/arrow_top.png' border=0 ></a>";
	$bwh = "<a href='admin.php?mode=pindahprofil&kode=$row[id]&st=bwh&p=$row[parent]&pos=".($row[urut]+1)."' title='Pindah ke bawah' ><img src='../images/arrow_down.png' border=0 ></a>";
	$kanan = "<a href='admin.php?mode=submenuprofil&kode=$row[id]&pos=".$idsblm."' title='Pindah ke submenu 1' ><img src='../images/arrow_next.png' border=0 ></a>";
	$kiri = "<a href='admin.php?mode=submenuprofil&kode=$row[id]&pos=".$psblm."' title='Pindah ke menu' ><img src='../images/arrow_back.png' border=0 ></a>";
	if ($row[urut]==1) { $atas="";$kanan="";}
	if ($row[parent]==0) $kiri="";
	if ($row[urut]==0) {$atas="";$bwh="";$kanan="";$kiri="";}
	$idsblm = $row[id]; // id sebelumnya
	$psblm = $row[parent]; // parent pada id sebelumnya
	
	echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td><font>$m</td>
	<td><font>$row[judul]</td><td>-</td><td>-</td><td>-</td><td><font><center>$kiri $atas $bwh $kanan</center></font></td><td><font><center>$hide</center></font></td><td><font><center>$row[id]</center></font></td>
	<td><center><font><a href='admin.php?mode=edit_profil&id=$row[id]' ><img src='../images/doc.gif' border=0 title='Editor khusus' ></a>&nbsp;&nbsp;
	<a href='admin.php?mode=html_profil&id=$row[id]' ><img src='../images/edit.gif' border=0 title='Source HTML' ></a></td><td><font><center>
	<a href='admin.php?mode=del_profil&kd=$row[id]' onClick=\"return confirm('Anda yakin Data Profil $row[judul] akan dihapus ?');\">Hapus</a></center></font></td>
	</tr>";
	$m++;
	$s1=1;
	// submenu 1
	$sql2="select id,judul,link,urut,parent,hide from t_profil where parent='$row[id]'  order by urut";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu 2");
	while($r=mysql_fetch_array($query2)) {
		 //cek ditampilkan atau tidak ( 0=tampil, 1=hide)
		if ($r[hide]=='0') $hide="<a href='admin.php?mode=hideprofil&kode=$r[id]&status=1' title='Sembunyikan menu' ><img src='../images/hide.gif' border=0 ></a>";
		else $hide="<a href='admin.php?mode=hideprofil&kode=$r[id]&status=0' title='Tampilkan menu' ><img src='../images/show.gif' border=0 ></a>";
	    
		if ($psblm1 =='') $psblm1 =$row[parent];

		// panah untuk pindah ke atas,bawah, kiri kanan
		$atas = "<a href='admin.php?mode=pindahprofil&kode=$r[id]&st=atas&p=$r[parent]&pos=".($r[urut]-1)."' title='Pindah ke atas' ><img src='../images/arrow_top.png' border=0 ></a>";
		$bwh = "<a href='admin.php?mode=pindahprofil&kode=$r[id]&st=bwh&p=$r[parent]&pos=".($r[urut]+1)."' title='Pindah ke bawah' ><img src='../images/arrow_down.png' border=0 ></a>";
		$kanan = "<a href='admin.php?mode=submenuprofil&kode=$r[id]&pos=".$idsblm1."' title='Pindah ke submenu 2' ><img src='../images/arrow_next.png' border=0 ></a>";
		$kiri = "<a href='admin.php?mode=submenuprofil&kode=$r[id]&pos=".$psblm1."' title='Pindah ke menu' ><img src='../images/arrow_back.png' border=0 ></a>";
		if ($r[urut]==1) { $atas="";$kanan="";}
		if ($r[parent]==0) $kiri="";
		if ($r[urut]==0) {$atas="";$bwh="";$kanan="";$kiri="";}
		$idsblm1 = $r[id]; // id sebelumnya
		$psblm1 = $row[parent]; // parent pada id sebelumnya
		
		echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td>&nbsp;</td>
			<td align=right ><font>$s1</font></td><td>$r[judul]</td><td>-</td><td>-</td><td><font><center>$kiri $atas $bwh $kanan</center></font></td><td><font><center>$hide</center></font></td><td><font><center>$r[id]</center></font></td>
			<td><center><font><a href='admin.php?mode=edit_profil&id=$r[id]' ><img src='../images/doc.gif' border=0 title='Editor khusus' ></a>&nbsp;&nbsp;
			<a href='admin.php?mode=html_profil&id=$r[id]' ><img src='../images/edit.gif' border=0 title='Source HTML' ></a></td><td><font><center>
			<a href='admin.php?mode=del_profil&kd=$r[id]' onClick=\"return confirm('Anda yakin Data Profil $r[judul] akan dihapus ?');\">Hapus</a></center></font></td></tr>";
 		$s1++;
		$s2=1;
		// submenu 2
		$sql3="select id,judul,link,urut,parent,hide from t_profil where parent='$r[id]'  order by urut";
		if(!$query3=mysql_query($sql3)) die ("Pengambilan gagal1 menu 2");
		while($r2=mysql_fetch_array($query3)) {
			 //cek ditampilkan atau tidak ( 0=tampil, 1=hide)
			if ($r2[hide]=='0') $hide="<a href='admin.php?mode=hideprofil&kode=$r2[id]&status=1' title='Sembunyikan menu' ><img src='../images/hide.gif' border=0 ></a>";
			else $hide="<a href='admin.php?mode=hideprofil&kode=$r2[id]&status=0' title='Tampilkan menu' ><img src='../images/show.gif' border=0 ></a>";
			
			if ($psblm2 =='') $psblm2 =$r[parent];
			   
			// panah untuk pindah ke atas,bawah, kiri kanan
			$atas = "<a href='admin.php?mode=pindahprofil&kode=$r2[id]&st=atas&p=$r2[parent]&pos=".($r2[urut]-1)."' title='Pindah ke atas' ><img src='../images/arrow_top.png' border=0 ></a>";
			$bwh = "<a href='admin.php?mode=pindahprofil&kode=$r2[id]&st=bwh&p=$r2[parent]&pos=".($r2[urut]+1)."' title='Pindah ke bawah' ><img src='../images/arrow_down.png' border=0 ></a>";
			$kanan = "<a href='admin.php?mode=submenuprofil&kode=$r2[id]&pos=".$idsblm2."' title='Pindah ke submenu 2' ><img src='../images/arrow_next.png' border=0 ></a>";
			$kiri = "<a href='admin.php?mode=submenuprofil&kode=$r2[id]&pos=".$psblm2."' title='Pindah ke menu' ><img src='../images/arrow_back.png' border=0 ></a>";
			if ($r2[urut]==1) { $atas="";$kanan="";}
			if ($r2[parent]==0) $kiri="";
			if ($r2[urut]==0) {$atas="";$bwh="";$kanan="";$kiri="";}
			$idsblm2 = $r2[id]; // id sebelumnya
			$psblm2 = $r[parent]; // parent pada id sebelumnya
			
			echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td>&nbsp;</td>
				<td  >&nbsp;</td><td align=right ><font>$s2</font></td><td><font>$r2[judul]</font></td><td>-</td>
				<td><font><center>$kiri $atas $bwh $kanan</center></font></td><td><font><center>$hide</center></font></td><td><font><center>$r2[id]</center></font></td>
				<td><center><font><a href='admin.php?mode=edit_profil&id=$r2[id]' ><img src='../images/doc.gif' border=0 title='Editor khusus' ></a>&nbsp;&nbsp;
				<a href='admin.php?mode=html_profil&id=$r2[id]' ><img src='../images/edit.gif' border=0 title='Source HTML' ></a></td><td><font><center>
				<a href='admin.php?mode=del_profil&kd=$r2[id]' onClick=\"return confirm('Anda yakin Data Profil $r2[judul] akan dihapus ?');\">Hapus</a></center></font></td></tr>";
			$s2++;
			$s3=1;
				// submenu 3
			$sql4="select id,judul,link,urut,parent,hide from t_profil where parent='$r2[id]'  order by urut";
			if(!$query4=mysql_query($sql4)) die ("Pengambilan gagal1 menu 3");
			while($r3=mysql_fetch_array($query4)) {
				 //cek ditampilkan atau tidak ( 0=tampil, 1=hide)
				if ($r3[hide]=='0') $hide="<a href='admin.php?mode=hideprofil&kode=$r3[id]&status=1' title='Sembunyikan menu' ><img src='../images/hide.gif' border=0 ></a>";
				else $hide="<a href='admin.php?mode=hideprofil&kode=$r3[id]&status=0' title='Tampilkan menu' ><img src='../images/show.gif' border=0 ></a>";
				
				if ($psblm3 =='') $psblm3 =$r2[parent];
				   
				// panah untuk pindah ke atas,bawah, kiri kanan
				$atas = "<a href='admin.php?mode=pindahprofil&kode=$r3[id]&st=atas&p=$r3[parent]&pos=".($r3[urut]-1)."' title='Pindah ke atas' ><img src='../images/arrow_top.png' border=0 ></a>";
				$bwh = "<a href='admin.php?mode=pindahprofil&kode=$r3[id]&st=bwh&p=$r3[parent]&pos=".($r3[urut]+1)."' title='Pindah ke bawah' ><img src='../images/arrow_down.png' border=0 ></a>";
				$kanan = "<a href='admin.php?mode=submenuprofil&kode=$r3[id]&pos=".$idsblm3."' title='Pindah ke submenu 3' ><img src='../images/arrow_next.png' border=0 ></a>";
				$kiri = "<a href='admin.php?mode=submenuprofil&kode=$r3[id]&pos=".$psblm2."' title='Pindah ke submenu 1' ><img src='../images/arrow_back.png' border=0 ></a>";
				if ($r3[urut]==1) { $atas="";$kanan="";}
				if ($r3[parent]==0) $kiri="";
				if ($r3[urut]==0) {$atas="";$bwh="";$kanan="";$kiri="";}
				$idsblm3 = $r3[id]; // id sebelumnya
				$psblm3 = $r2[parent]; // parent pada id sebelumnya
				
				echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"><td>&nbsp;</td>
					<td  >&nbsp;</td><td  >&nbsp;</td><td align=right><font>$s3</font></td><td><font>$r3[judul]</font></td>
					<td><font><center>$kiri $atas $bwh $kanan</center></font></td><td><font><center>$hide</center></font></td><td><font><center>$r3[id]</center></font></td>
					<td><center><font><a href='admin.php?mode=edit_profil&id=$r3[id]' ><img src='../images/doc.gif' border=0 title='Editor khusus' ></a>&nbsp;&nbsp;
					<a href='admin.php?mode=html_profil&id=$r3[id]' ><img src='../images/edit.gif' border=0 title='Source HTML' ></a></td><td><font><center>
					<a href='admin.php?mode=del_profil&kd=$r3[id]' onClick=\"return confirm('Anda yakin Data Profil $r3[judul] akan dihapus ?');\">Hapus</a></center></font></td></tr>";
				$s3++;
				
			} // tutup submenu 3
		} // tutup submenu 2
	} // tutup submenu 1
  }
  echo"</table><br><br>| <a href='admin.php?mode=tam_profil'>Tambah Profil</a> |<br><br>Menu Selayang pandang, Lokasi Sekolah, dan petasitus mohon jangan dihapus<br>Apabila menu petasitus tidak ada, tambahkan menu dengan judul petasitus untuk membuat link peta situs<br><br>";
  
}
//pindah menu profil
function pindahprofil() {
$kode=$_GET['kode'];
$pos=$_GET['pos'];
$p=$_GET['p'];
$st=$_GET['st'];
if ($st=='atas') $urut = $pos + 1;
else $urut = $pos - 1; 
	$sql2="select id,urut from t_profil where parent='". mysql_escape_string($p)."' and urut='". mysql_escape_string($pos)."'";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu 2");
	if($row=mysql_fetch_array($query2)) {
		$sql = "UPDATE t_profil SET urut='".$urut."' WHERE id ='". mysql_escape_string($row[id])."'";
  		if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
	}
  $sql = "UPDATE t_profil SET urut='". mysql_escape_string($pos)."' WHERE id ='". mysql_escape_string($kode)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  
}
//pindah kiri dan kanan menu profil
function submenuprofil() {
$kode=$_GET['kode'];
$pos=$_GET['pos'];

  	$sql2="select id,urut from t_profil where parent='". mysql_escape_string($pos)."' order by urut desc ";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu 2");
	if($row=mysql_fetch_array($query2)) {
		$urut=$row[urut]+1;
	}
	else $urut=1;
	$sql = "UPDATE t_profil SET urut='".$urut."',parent='". mysql_escape_string($pos)."' WHERE id ='". mysql_escape_string($kode)."'";
  	if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
	
	$sql2="select id,urut,parent from t_profil where id='". mysql_escape_string($pos)."' ";
	if(!$query2=mysql_query($sql2)) die ("Pengambilan gagal1 menu 2");
	if($row=mysql_fetch_array($query2)) {
		$parent=$row[parent];
	}
	$n=1;
	$sql3="select id,urut from t_profil where parent='". mysql_escape_string($parent)."' and urut<>0  order by urut ";
	if(!$query3=mysql_query($sql3)) die ("Pengambilan gagal1 menu 2");
	while($r=mysql_fetch_array($query3)) {
		$sql4 = "UPDATE t_profil SET urut='".$n."' WHERE id ='". mysql_escape_string($r[id])."'";
  		if(!$alan=mysql_query($sql4)) die ("Penyimpanan gagal");
		$n++;
	}
	echo "perubahan berhasil dilakukan | <a href='admin.php?mode=profil' >Menu & Profil</a> | $parent";
}
//tampilkan menu profil
function hideprofil() {
$kode=$_GET['kode'];
$status=$_GET['status'];
  $sql = "UPDATE t_profil SET hide='". mysql_escape_string($status)."' WHERE id ='". mysql_escape_string($kode)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  
}

function html_profil() {
  include "koneksi.php";
  $id=$_GET['id'];
  $save=$_GET['save'];
 // $isi=str_replace("'","`",$_POST['richEdit0']);
  $isi=stripslashes($_POST['richEdit0']);
  $judul=$_POST['judul'];
  $hide=$_POST['hide'];
  $urut=$_POST['urut'];
  $target=$_POST['target'];
  $link=$_POST['link'];
  $parent=$_POST['parent'];
  
  if ($save=="") {
  $sql="select * from t_profil where id='".mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);
  
	echo"<form action='admin.php?mode=html_profil&save=1&id=$id' method=\"post\" name=\"f1\" >";
    echo"<font>Judul : <input type=text name=judul value='$row[judul]' size=40 maxlenght=100><br><br>
	Isi <textarea cols=100 name='richEdit0' rows=20 >$row[isi]</textarea>";
	echo "<table border=0 ><tr><td>Menu Utama </td><td>: <select name='parent' ><option value='0' selected >Tidak ada</option>";
  	$sql2="select id,judul from t_profil where urut<>'0'  order by parent,urut";
  	$mysql2=mysql_query($sql2);
  	while($r=mysql_fetch_array($mysql2)) {
		if ($r[id]==$row[parent]) echo "<option value='$r[id]' selected  >$r[judul]</option>";
		else echo "<option value='$r[id]'  >$r[judul]</option>";
	}	
	echo "</select></td></tr>";
	if($row[hide]=='0') $s1='selected';
	else $s2='selected';
	if ($row[target]=='_self') $st1='selected';
	else $st2='selected';
	
	echo"<tr><td>Tampilkan </td><td>: <select name='hide' ><option value='0' $s1 >Tampilkan</option><option value='1' $s2 >Sembunyikan</option></select></td></tr>";
	echo "<tr><td>Link </td><td>: <input typ=text name=link value='$row[link]'> Link diisi 0 apabila tombol menu/submenu berupa konten profil</td></tr>";
	echo "<tr><td>Target </td><td>: <select name='target' ><option value='_self' $st1 >Self</option><option value='_blank' $st2 >Blank</option></select></td></tr>";
	echo"<tr><td>Urut </td><td>: <input typ=text name=urut value='$row[urut]' size=5 ></td></tr></table>
	&nbsp;Urut dan Link diisi 0 apabila profil digunakan oleh modul khusus<br><br><input type='reset' value='Ulang' > &nbsp;
	<input type=submit class=button name=submit value=' Simpan ' ></form>";
  }
  else {
  $sql = "UPDATE t_profil SET isi= '".mysql_escape_string($isi)."',judul='". mysql_escape_string($judul)."',target='". mysql_escape_string($target)."',urut='". mysql_escape_string($urut)."',hide='". mysql_escape_string($hide)."',link='". mysql_escape_string($link)."',parent='". mysql_escape_string($parent)."' WHERE id ='". mysql_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Perubahan Profil Lembaga berhasil<br>Silahkan pilih menu kembali !!!<br>
  | <a href='admin.php?mode=profil' >Lihat Profil</a> |</font>"; 
  }
  
}
function edit_profil() {
  include "koneksi.php";
  $id=$_GET['id'];
  $save=$_GET['save'];

  $isi=stripslashes($_POST['richEdit0']);
  $judul=$_POST['judul'];
  $hide=$_POST['hide'];
  $urut=$_POST['urut'];
  $target=$_POST['target'];
  $link=$_POST['link'];
  $parent=$_POST['parent'];
  
  if ($save=="") {
  $sql="select * from t_profil where id='".mysql_escape_string($id)."'";
  $mysql_result=mysql_query($sql);
  $row=mysql_fetch_array($mysql_result);

 include "functions_editor.php";
 echo editor_full();

	echo"<form action='admin.php?mode=edit_profil&save=1&id=$id' method=\"post\" name=\"f1\" >";
    echo"<font>Judul : <input type=text name=judul value='$row[judul]' size=40 maxlenght=100><br><br>";
    

echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%">'.$row['isi'].'</textarea>';
	echo "<table border=0 ><tr><td>Menu Utama </td><td>: <select name='parent' ><option value='0' selected >Tidak ada</option>";
  	$sql2="select id,judul from t_profil where urut<>'0'  order by parent,urut";
  	$mysql2=mysql_query($sql2);
  	while($r=mysql_fetch_array($mysql2)) {
		if ($r[id]==$row[parent]) echo "<option value='$r[id]' selected  >$r[judul]</option>";
		else echo "<option value='$r[id]'  >$r[judul]</option>";
	}	
	echo "</select></td></tr>";
	if($row[hide]=='0') $s1='selected';
	else $s2='selected';
	if ($row[target]=='_self') $st1='selected';
	else $st2='selected';
	echo"<tr><td>Tampilkan </td><td>: <select name='hide' ><option value='0' $s1 >Tampilkan</option><option value='1' $s2 >Sembunyikan</option></select></td></tr>";
	echo "<tr><td>Link </td><td>: <input typ=text name=link value='$row[link]'> Link diisi 0 apabila tombol menu/submenu berupa konten profil</td></tr>";
	echo "<tr><td>Target </td><td>: <select name='target' ><option value='_self' $st1 >Self</option><option value='_blank' $st2 >Blank</option></select></td></tr>";
	echo"<tr><td>Urut </td><td>: <input typ=text name=urut value='$row[urut]' size=5 ></td></tr></table>
	&nbsp;Urut dan Link diisi 0 apabila profil digunakan oleh modul khusus<br><br><input type='reset' value='Ulang' > &nbsp;
	<input type=submit class=button name=submit value=' Simpan ' ></form>";
  }
  else {
  $sql = "UPDATE t_profil SET isi= '".mysql_escape_string($isi)."',judul='". mysql_escape_string($judul)."',target='". mysql_escape_string($target)."',urut='". mysql_escape_string($urut)."',hide='". mysql_escape_string($hide)."',link='". mysql_escape_string($link)."',parent='". mysql_escape_string($parent)."' WHERE id ='". mysql_escape_string($id)."'";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Perubahan Profil Lembaga berhasil<br>Silahkan pilih menu kembali !!!<br>
  | <a href='admin.php?mode=profil' >Lihat Profil</a> |</font>"; 
  }
  
}
  //hapus 
function del_profil() {
include("koneksi.php");
$kd=$_GET['kd'];
  if (!empty($kd))
  {
	$sql="delete from t_profil where id='". mysql_escape_string($kd)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
  }
} 
function tam_profil() {
  include "koneksi.php";

  $save=$_GET['save'];
  $isi=stripslashes($_POST['richEdit0']);
  $judul=$_POST['judul'];
  $hide=$_POST['hide'];
  $urut=$_POST['urut'];
  $target=$_POST['target'];
  $link=$_POST['link'];
  $parent=$_POST['parent'];
  if ($save=="") {
	echo"<form action='admin.php?mode=tam_profil&save=1' method=\"post\" name=\"f1\" >";
  	echo"<font>Judul : <input type=text name=judul value='' size=40 maxlenght=100><br><br>";
 include "functions_editor.php";
 echo editor_full();

echo '<textarea id="elm1" name="richEdit0" rows="25" cols="80" style="width: 100%"></textarea>';
echo "<table border=0 ><tr><td>Menu Utama </td><td>: <select name='parent' ><option value='0' selected >Tidak ada</option>";
  	$sql2="select id,judul from t_profil where urut<>'0'  order by parent,urut";
  	$mysql2=mysql_query($sql2);
  	while($r=mysql_fetch_array($mysql2)) {
		echo "<option value='$r[id]'  >$r[judul]</option>";
	}	
	echo "</select></td></tr>";
	echo"<tr><td>Tampilkan </td><td>: <select name='hide' ><option value='0' $s1 >Tampilkan</option><option value='1' $s2 >Sembunyikan</option></select></td></tr>";
	echo "<tr><td>Link </td><td>: <input typ=text name=link value='$row[link]'> Link diisi 0 apabila tombol menu/submenu berupa konten profil</td></tr>";
	echo "<tr><td>Target </td><td>: <select name='target' ><option value='_self' $st1 >Self</option><option value='_blank' $st2 >Blank</option></select></td></tr>";
	echo"<tr><td>Urut </td><td>: <input typ=text name=urut value='' size=5 ></td></tr></table>
	&nbsp;Urut dan Link diisi 0 apabila profil digunakan oleh modul khusus<br><br><input type='reset' value='Ulang' > &nbsp;
	<input type=submit class=button name=submit value=' Simpan ' ></form>";
  }
  else {
  $sql = "insert into t_profil (judul,isi,urut,target,link,hide,parent) values ('". mysql_escape_string($judul)."','".mysql_real_escape_string($isi)."','". mysql_escape_string($urut)."','". mysql_escape_string($target)."','". mysql_escape_string($link)."','". mysql_escape_string($hide)."','". mysql_escape_string($parent)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal");
  echo "<font>Penambahan Profil Lembaga berhasil<br>Silahkan pilih menu kembali !!! <br>
  | <a href='admin.php?mode=profil' >Lihat Profil</a> |</font>"; 
  }
 
}
 
 function cred() {
  
echo '<table width="100%"  border="1" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr bgcolor="#2492AB"> 
    <td colspan="2"> <font>
<center>
        <b>--- TIM Pengembang ---</b>
      </center></font></td></tr>
  <tr> 
    <td width="50%"  > 
<center>  
	<a href="mailto:choirulyogya@yahoo.com" > <img src="../images/choirul.jpg" border="1" width="120" height="150" ></a><br>
	<font>choirulyogya@yahoo.com<br><b>Choirul Anam </b></font></center>
      </td>
    <td width="50%" ><center>  
	<a href="mailto:alanrm82@yahoo.com" > <img src="../images/alan.jpg" border="1" width="120" height="150"  ></a><br>
	<font>alarnm82@yahoo.com<br><b>Alan Ridwan M </b>( alanrm.com )</font></center></td>
		</tr>
<tr> 
    <td width="50%"  > 
<center>  
	<a href="mailto:dody_ibg@yahoo.com" > <img src="../images/dody.jpg" border="1" width="120" height="150" ></a><br>
	<font>dody_ibg@yahoo.com<br><b>Dody Firmansyah</b></font></center>
      </td>
    <td width="50%" ><center>  
	<a href="mailto:taufik_ns@yahoo.com" > <img src="../images/topik.jpg" border="1" width="120" height="150" ></a><br>
	<font>taufik_ns@yahoo.com<br><b>Taufik N. Syah </b></font></center></td>
		</tr>
<tr> 
    <td width="50%"  > 
<center>  
	<a href="mailto:mas_java2@yahoo.com" > <img src="../images/wuryanta.jpg" border="1" width="120" height="150" ></a><br>
	<font>mas_java2@yahoo.com<br><b>Wuryanta</b></font></center>
      </td>
    <td width="50%" ><center>  

	<a href="mailto:sma10malang@telkom.net" > <img src="../images/siswanto.jpg" border="1" width="120" height="150" ></a><br>
	<font>sma10malang@telkom.net<br><b>Siswanto </b></font></center></td>
		</tr>
<tr> 
    <td width="50%"  > 
<center>  
	<a href="mailto:yulianto_sri_utomo@yahoo.com" > <img src="../images/tomi.jpg" border="1" width="120" height="150"  ></a><br>
	<font>yulianto_sri_utomo@yahoo.com<br><b>Yulianto Sri Utomo</b></font></center>
      </td>
    <td width="50%" ><center>  
	<a href="mailto:wardjana@yahoo.com" > <img src="../images/warjana.jpg" border="1"  width="120" height="150" ></a><br>
	<font>wardjana@yahoo.com<br><b>Warjana </b></font></center></td>
		</tr>
<tr> 
    <td width="50%"  > 
<center>  
	<a href="mailto:konsul.cmsbalitbang@gmail.com" > <img src="../images/ansari.jpg" border="1" width="120" height="150"  ></a><br>
	<font>konsul.cmsbalitbang@gmail.com<br><b>Ansari Saleh Ahmar</b></font></center>
      </td>
    <td width="50%" >&nbsp;</td>
		</tr>
	</table>';
	
}
function tahap() {

  echo '<table width="100%"  border="1" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr bgcolor="#2492AB"> 
    <td > <font>
<center>
        <b>--- Tahapan dalam Pengisian Konten Website ---</b>
      </center></font></td></tr>
	  <tr><td>
	  <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr bgcolor="#2492AB">
    <td width="6%"><font><b>No</b></td>
    <td width="35%"><font><b>Tahapan</b></font></td>
    <td width="59%"><font><b>Menu yang diakses</b></font></td>
  </tr>
  <tr>
    <td><font>1</font></td>
    <td><font>Konfigurasi website</font></td>
    <td><font>Menu Setting Admin --&gt; Konfigurasi<br />
    Ubah data config.php yaitu <br />
    $webhost = "www.websekolah.web.id";<br />
$webmail = "admin@websekolah.web.id";<br />
$nmsekolah = "Web Sekolah";<br />
$almtsekolah = "Jalan Gardujati No.20 Telp.022-6011186 Bandung";<br />
Konfigurasi File Upload 
    </font></td>
  </tr>
   <tr>
    <td><font>2</font></td>
    <td><font>Profil Sekolah</font></td>
    <td><font>Menu Setting Admin --&gt; Profil<br />
    Semua menu Profil
    </font></td>
  </tr>
    <tr>
    <td><font>3</font></td>
    <td><font>Menambah Admin dan Operator</font></td>
    <td><font>Menu Setting Admin --&gt; Tambah Admin<br />
    
    </font></td>
  </tr> 
     <tr>
    <td><font>4</font></td>
    <td><font>Data Semester</font></td>
    <td><font>Menu Setting Admin --&gt; Semester dan Tahun pelajaran<br />
    
    </font></td>
  </tr> 
      <tr>
    <td><font>5</font></td>
    <td><font>Data Jurusan/ Program Keahlian</font></td>
    <td><font>Menu Setting Admin --&gt; Jurusan/program<br />
    
    </font></td>
  </tr> 
  <tr>
    <td><font>6</font></td>
    <td><font>Data Pelajaran</font></td>
    <td><font>Menu Setting Admin --&gt;Pelajaran<br />
    </font></td>
  </tr> 
  <tr>
    <td><font>7</font></td>
    <td><font>Data Guru</font></td>
    <td><font>Menu Data Guru --&gt Data Guru atau Import Guru<br />
    </font></td>
  </tr> 
 <tr>
    <td><font>8</font></td>
    <td><font>Data Kelas</font></td>
    <td><font>Menu Setting Admin --&gt;Data Kelas<br />
    </font></td>
  </tr> 
 <tr>
    <td><font>9</font></td>
    <td><font>Data Guru Mengajar</font></td>
    <td><font>Menu Data Guru --&gt;Data Mengajar<br />
    </font></td>
  </tr> 
  <tr>
    <td><font>10</font></td>
    <td><font>Data Siswa</font></td>
    <td><font>Menu Data Siswa --&gt;Data Siswa atau Import Siswa<br />
    </font></td>
  </tr> 
<tr>
    <td><font>11</font></td>
    <td><font>Tambah Fitur lainnya</font></td>
    <td><font>Menu Setting Admin --&gt;Posisi Menu<br />
    Atur tata letak menu
    </font></td>
  </tr> 
 <tr>
    <td><font>12</font></td>
    <td><font>Desain Banner Atas dan template</font></td>
    <td><font>Menu Setting Admin --&gt;Gambar Atas dan Template Menu menggunakan software lain<br />
    </font></td>
  </tr> 
  <tr>
    <td><font>13</font></td>
    <td><font>Isi Konten Umum</font></td>
    <td><font>Isi Berita, Artikel, Info, Galeri, Link Web, Agenda, Materi Ajar, Jajak Pendapat, Banner dsb<br />
    Konten web yang berada di menu Fitur diharapkan diisi.
    </font></td>
  </tr> 
   <tr>
    <td><font>14</font></td>
    <td><font>Daftar Member</font></td>
    <td><font>Daftarkan member guru, siswa, orang tua
    </font></td>
  </tr> 
</table>
	  </td></tr>
	  </table>';
	
}
function help() {
  
  
  echo '<table width="100%"  border="1" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr bgcolor="#2492AB"> 
    <td > <font>
<center>
        <b>--- Tutorial Website CMS Balitbang Depdiknas ---</b>
      </center></font></td></tr>
	  <tr><td><font><br><center>Silahkan Baca Buku Panduan di dalam CD<br> Atau gabung dalam milis : kajianweb_balitbang@yahoogroups.com</center></font></td></tr>
	  </table>';
	
}
function daftarweb() {
include "koneksi.php";
$daf = $_GET['daf'];
if ($daf=='') {
  echo '<table width="100%"  border="1" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr bgcolor="#2492AB"> 
    <td > <font>
<center>
        <b>--- Daftarkan Website Anda ini ke Admin CMS Balitbang Depdiknas ---</b>
      </center></font></td></tr>
	  <tr><td><font><br><center>Silahkan klik <a href="admin.php?mode=daftarweb&daf=1" >daftar</a> untuk mendaftarkan website anda dan mendapatkan update terbaru</center></font></td></tr>
	  </table>';
}
else {

	$headers  = "From: \"Komunitas $nmsekolah\" <$webmail>\r\n";
    $headers .= "Content-type: text/html\r\n";
	if(!@mail("alanrm82@yahoo.com","Webtemp Balitbang yang mendaftar : $nmsekolah","Website : $webhost <br>Email : $webmail <br>Nama Sekolah : $nmsekolah <br>Alamat : $almtsekolah <br>", $headers)) {
	   echo "Gagal kirim email<br>";
	}

}
}
}

?>