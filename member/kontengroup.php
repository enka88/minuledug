<?php
session_start();
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}
echo '<link type="text/css" rel="stylesheet" media="all" href="css/kontenbox.css" />';
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
$id=$_POST['id'];
if (empty($id)) $id=$_GET['id'];

if ($id=='groupbaru') {
    $userid = $_GET['userid'];
    if ($userid=='') $userid=$_POST['userid'];
    echo "<div id='fotoupload-atas'>Group Baru</div>";
	$sql = mysql_query("select * from t_membergroup_jenis ");	
	while($r=mysql_fetch_array($sql)) {
		$seleksi .="<option value='$r[idjenis]' >$r[jenis]</option>";
	}

echo "<form action='kontengroup.php' method='post' enctype='multipart/form-data'  > 
<table border='0' id='upload_form'>
<tr><td>Nama Group </td><td><input type='text' name='nama' id='nama' value='' maxlength='100'   ></td></tr>
<tr><td>Jenis Group </td><td><select name='jenis' >$seleksi</select></td></tr>
<tr><td valign=top >File Foto </td><td><input type='file' name='myfile' id='myfile' ><br>
Format file harus JPG. Ukuran file maksimal 200 kbyte.</td></tr>
<tr><td>Keterangan </td><td><textarea name='ket' maxlength='255' cols=30  ></textarea></td></tr>
<tr><td>Status</td><td ><select name='status' ><option value='0' >Terbuka, siapa saja bisa bergabung</option><option value='1' >Tertutup, hanya member tertentu saja</option></select></td></tr>
<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
</table></p><input type=hidden name='userid' value='".$userid."' >
<input type=hidden id='id' name='id' value='simgroup' >
</form>";
}
elseif ($id=='simgroup') {
include "../functions/fungsi_crop.php";

$userid = unhex($_POST['userid'],$noacak);
$nama=$_POST['nama'];
$jenis=$_POST['jenis'];
$nfile=$_FILES['myfile'];
$ket=$_POST['ket'];
$ket=htmlentities($ket);
$ket=nl2br($ket);
$status=$_POST['status'];
$code =$_POST['code'];
	echo "<div id='fotoupload-atas'>Group Baru</div>";
	$kode= $_SESSION['kodeRandom'];
	if (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>";
	}
	elseif (trim($nama)=='') {
		echo "Nama masih kosong <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>";
	}
	elseif (trim($ket)=='') {
		echo "keterangan masih kosong <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>";
	}
	else {
	$sql=mysql_query("select max(idgroup) as total from t_membergroup ");
	$r=mysql_fetch_array($sql);
	$idgroup = $r[total]+1;
	  if (!empty($nfile['name'])) {
 		$ukuran=$nfile['size'];
		$a=$nfile['name'];
		$m=strlen($a);
		$type=strtolower(substr($a,$m-3,3));
	
   		if ($ukuran >= 204800) { // cek ukuran file
			echo  "File terlalu besar, maksimal 200 Kbyte. <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>"; }
		else if ($type <>'jpg') {
			echo "Format file salah, seharusnya jpg. <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>"; }
		else {
			$target_file ="group/group$idgroup.jpg";
			$query=mysql_query("insert into t_membergroup (idgroup,nmgroup,ket,tanggal,stgroup,idjenis,userid) values ('$idgroup','".mysql_real_escape_string($nama)."','".mysql_real_escape_string($ket)."',NOW(),'".mysql_real_escape_string($status)."','".mysql_real_escape_string($jenis)."','".mysql_real_escape_string($userid)."') ");
		 	if(@move_uploaded_file($nfile['tmp_name'], $target_file)) {
				$query=mysql_query("insert into t_membergroup_anggota (idgroup,userid,tanggal,kategori,status) values ('".mysql_real_escape_string($idgroup)."','".mysql_real_escape_string($userid)."',NOW(),'1','1') ");
				$size = getimagesize($target_file); // size[0] itu lebar,,,,,size[1]=tinggi
				$lebar =300;
				if ( $size[0] > $lebar ) {
					$tinggi = round(($size[1]*$lebar)/$size[0],0); // mencari perbandingan ukuran lebar dan tinggi
	  				cropImage($lebar, $tinggi, "$target_file", 'jpg', "$target_file"); // crop file
					
				}
				$q=mysql_query("update t_member set point=point+10 where userid='".mysql_real_escape_string($userid)."' ");
				echo "Foto berhasil diupload dan Penambahan group baru berhasil.";
				$kodeRandom="";
			} 
			
		}
	  }
	  else {
		$query=mysql_query("insert into t_membergroup (idgroup,nmgroup,ket,tanggal,stgroup,idjenis,userid) values ('$idgroup','".mysql_real_escape_string($nama)."','".mysql_real_escape_string($ket)."',NOW(),'".mysql_real_escape_string($status)."','".mysql_real_escape_string($jenis)."','".mysql_real_escape_string($userid)."') ");
		$query=mysql_query("insert into t_membergroup_anggota (idgroup,userid,tanggal,kategori,status) values ('".mysql_real_escape_string($idgroup)."','".mysql_real_escape_string($userid)."',NOW(),'1','1') ");
	
		$q=mysql_query("update t_member set point=point+10 where userid='".mysql_real_escape_string($userid)."' ");
		echo "Penambahan group baru berhasil.";
		$kodeRandom="";
	   }
	}

}
elseif ($id=='editgroup') {
$userid = unhex($_GET['userid'],$noacak);
$kdgroup= unhex($_GET['kdgroup'],$noacak);
echo "<div id='fotoupload-atas'>Edit Group</div>";
	
$sql = mysql_query("select * from t_membergroup where idgroup='". mysql_real_escape_string($kdgroup)."' and userid='". mysql_real_escape_string($userid)."' ");	
if($row=mysql_fetch_array($sql)) {
	$nama=$row[nmgroup];
	$ket=$row[ket];
	$jenis=$row[idjenis];
	if($row[stgroup]=='1') $s2='selected';
	else $s1='selected';
	$sql = mysql_query("select * from t_membergroup_jenis ");	
	while($r=mysql_fetch_array($sql)) {
		if ($jenis==$r[idjenis]) $seleksi .="<option value='$r[idjenis]' selected >$r[jenis]</option>";
		else $seleksi .="<option value='$r[idjenis]' >$r[jenis]</option>";
	}
	$file = "group/group".$kdgroup.".jpg";
	if (file_exists(''.$file.'')) {
	   $gb ="<img src='thumb-user.php?img=$file' width='100' height='100' id='gambar' align=left />";
	}
	echo "<form action='kontengroup.php' method='post' enctype='multipart/form-data'  > 
	<table border='0' id='upload_form'>
	<tr><td>Nama Group </td><td><input type='text' name='nama' id='nama'  maxlength='100' value='$nama'  ></td></tr>
	<tr><td>Jenis Group </td><td><select name='jenis' >$seleksi</select></td></tr>
	<tr><td valign=top >File Foto </td><td>$gb<input type='file' name='myfile' id='myfile' ><br>
	Format file harus JPG. Ukuran file maksimal 200 kbyte.</td></tr>
	<tr><td>Keterangan </td><td><textarea name='ket' maxlength='255' cols=30  >$ket</textarea></td></tr>
	<tr><td>Status</td><td ><select name='status' ><option value='0' $s1>Terbuka, siapa saja bisa bergabung</option><option value='1'$s2 >Tertutup, hanya member tertentu saja</option></select></td></tr>
	<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
	<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
	</table></p><input type=hidden name='userid' value='".hex($userid,$noacak)."' >
	<input type=hidden name='kdgroup' value='".hex($kdgroup,$noacak)."' >
	<input type=hidden id='id' name='id' value='simeditgroup' >
	</form> ";
	
}
else {
	echo "Group ini hanya dapat diubah oleh Pendiri Groupnya";
}
	
}
elseif ($id=='simeditgroup') {
include "../functions/fungsi_crop.php";
$kdgroup = unhex($_POST['kdgroup'],$noacak);
$userid = unhex($_POST['userid'],$noacak);
$nama=$_POST['nama'];
$jenis=$_POST['jenis'];
$nfile=$_FILES['myfile'];
$ket=$_POST['ket'];
$ket=htmlentities($ket);
$ket=nl2br($ket);
$status=$_POST['status'];
$code = $_POST['code'];
		 
	echo "<div id='fotoupload-atas'>Edit Group</div>";
	$kode= $_SESSION['kodeRandom'];
	if (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=editgroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."' id='button' > Kembali </a>";
	}
	elseif (trim($nama)=='') {
		echo "Nama masih kosong <a href='kontengroup.php?id=editgroup&userid=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."' id='button' > Kembali </a>";
	}
	elseif (trim($ket)=='') {
		echo "keterangan masih kosong <a href='kontengroup.php?id=editgroup&=".hex($userid,$noacak)."&kdgroup=".hex($kdgroup,$noacak)."' id='button' > Kembali </a>";
	}
	else {
	  if (!empty($nfile['name'])) {
 		$ukuran=$nfile['size'];
		$a=$nfile['name'];
		$m=strlen($a);
		$type=strtolower(substr($a,$m-3,3));
	
   		if ($ukuran >= 204800) { // cek ukuran file
			echo  "File terlalu besar, maksimal 200 Kbyte. <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>"; }
		else if ($type <>'jpg') {
			echo "Format file salah, seharusnya jpg. <a href='kontengroup.php?id=groupbaru&userid=$userid' id='button' > Kembali </a>"; }
		else {
			$target_file ="group/group$kdgroup.jpg";
			$query=mysql_query("update t_membergroup set nmgroup='".mysql_real_escape_string($nama)."', ket='".mysql_real_escape_string($ket)."',stgroup='".mysql_real_escape_string($status)."',idjenis='".mysql_real_escape_string($jenis)."' where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' ");
		 	if(@move_uploaded_file($nfile['tmp_name'], $target_file)) {
				$size = getimagesize($target_file); // size[0] itu lebar,,,,,size[1]=tinggi
				$lebar =300;
				if ( $size[0] > $lebar ) {
					$tinggi = round(($size[1]*$lebar)/$size[0],0); // mencari perbandingan ukuran lebar dan tinggi
	  				cropImage($lebar, $tinggi, "$target_file", 'jpg', "$target_file"); // crop file
					echo "Perubahan group dan upload gambar berhasil dilakukan.";
					$kodeRandom="";
				}
			} 
			
		}
	  }
	  else {
		$query=mysql_query("update t_membergroup set nmgroup='".mysql_real_escape_string($nama)."', ket='".mysql_real_escape_string($ket)."',stgroup='".mysql_real_escape_string($status)."',idjenis='".mysql_real_escape_string($jenis)."' where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' ");
		echo "Perubahan group berhasil dilakukan";
		$kodeRandom="";
	   }
	}

}
elseif ($id=='infogroup') {
$userid = $_GET['userid'];
$kdgroup = $_GET['kdgroup'];

echo '<script type="text/javascript" src="../functions/ckeditor/ckeditor_basic.js"></script>';
echo "<div id='fotoupload-atas'>Penambahan Info Group</div>";

echo "<form action='kontengroup.php' method='post'   > 
<table border='0' id='upload_form'>
<tr><td>Judul </td><td><input type='text' name='judul' id='judul'  maxlength='100' size=50  ></td></tr>
<tr><td vlign=top >Isi Info </td><td><textarea name='editor1' ></textarea>";
echo "<script type=\"text/javascript\">
				CKEDITOR.replace( 'editor1',{
        customConfig : '../functions/ckeditor/config_basic.js'
    } );
			</script>";
echo "</td></tr>
<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button'  ></td></tr>
</table></p><input type=hidden name='userid' value='".$userid."' >
<input type=hidden id='id' name='id' value='siminfo' ><input type=hidden name='kdgroup' value='".$kdgroup."' >
</form> ";
}
elseif ($id=='siminfo') { // simpan info group
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
	$userid = $_POST['userid'];
	$kdgroup = $_POST['kdgroup'];
	$isi = stripslashes($_POST['editor1']);
	$judul = htmlentities($_POST['judul']);
	$code = $_POST['code'];
	
	$kode= $_SESSION['kodeRandom'];
  	if (trim($isi) == '' ) {
		echo "Isi masih kosong. <a href='kontengroup.php?id=infogroup&kdgroup=$kdgroup&userid=$userid' id='button'  > Kembali </a>";
   	}
	elseif (trim($judul)=='') {
		echo "Judul masih kosong. <a href='kontengroup.php?id=infogroup&kdgroup=$kdgroup&userid=$userid' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=infogroup&kdgroup=$kdgroup&userid=$userid' id='button' > Kembali </a>";
	}
	else {
		echo "<div id='fotoupload-atas'>Tambah Info Group </div>";
		$userid=unhex($userid,$noacak);
		$kdgroup=unhex($kdgroup,$noacak);
		$query=mysql_query("insert into t_membergroup_info (judul,isi,userid,tanggal,idgroup) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($isi)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($kdgroup)."') ");
	
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		
		echo "Penambahan info group berhasil dilakukan. <br>Silahkan tutup tampilan ini.dan refresh browsenya.";
		$kodeRandom="";
	}
	
}
elseif ($id=='editinfo') { // perubahan data info group
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
$userid = $_GET['userid'];
$kdinfo = $_GET['kdinfo'];
$sql = mysql_query("select * from t_membergroup_info where idgroupinfo='". mysql_real_escape_string(unhex($kdinfo,$noacak))."' ");
if($row=mysql_fetch_array($sql)) {
$level=level_group($row[idgroup],unhex($userid,$noacak));
  if ($level=='1') {
echo '<script type="text/javascript" src="../functions/ckeditor/ckeditor_basic.js"></script>';
echo "<div id='fotoupload-atas'>Mengubah Info Group</div>";

echo "<form action='kontengroup.php' method='post' > 
<table border='0' id='upload_form'>
<tr><td>Judul </td><td><input type='text' name='judul' id='judul' value='".$row[judul]."' maxlength='100' size=50  ></td></tr>
<tr><td vlign=top >Isi Info </td><td><textarea name='editor1' >".$row[isi]."</textarea>";
echo "<script type=\"text/javascript\">
				CKEDITOR.replace( 'editor1',{
        customConfig : '../functions/ckeditor/config_basic.js'
    } );
			</script>";
echo "</td></tr>
<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button'  ></td></tr>
</table></p><input type=hidden name='userid' value='".$userid."' ><input type=hidden name='kdinfo' value='".$kdinfo."' >
<input type=hidden id='id' name='id' value='simeditinfo' >
</form> ";
  }
}
else echo "Data yang anda cari tidak ada";
}
elseif ($id=='simeditinfo') { // simpan info group
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
	$userid = $_POST['userid'];
	$kdinfo = $_POST['kdinfo'];
	$isi = stripslashes($_POST['editor1']);
	$judul = htmlentities($_POST['judul']);
	$code = $_POST['code'];
	
	$kode= $_SESSION['kodeRandom'];
  	if (trim($isi) == '' ) {
		echo "Isi masih kosong. <a href='kontengroup.php?id=editinfo&kdinfo=$kdinfo&userid=$userid' id='button'  > Kembali </a>";
   	}
	elseif (trim($judul)=='') {
		echo "Judul masih kosong. <a href='kontengroup.php?id=editinfo&kdinfo=$kdinfo&userid=$userid' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=editinfo&kdinfo=$kdinfo&userid=$userid' id='button' > Kembali </a>";
	}
	else {
		echo "<div id='fotoupload-atas'>Tambah Info Group </div>";
		$userid=unhex($userid,$noacak);
		$kdinfo=unhex($kdinfo,$noacak);
		$query=mysql_query("update t_membergroup_info set judul='".mysql_real_escape_string($judul)."', isi='".mysql_real_escape_string($isi)."',userid='".mysql_real_escape_string($userid)."',tanggal=NOW() where idgroupinfo='".mysql_real_escape_string($kdinfo)."' ");
	
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		
		echo "Perubahan info group berhasil dilakukan. <br>Silahkan tutup tampilan ini dan refresh browsenya.";
		$kodeRandom="";
	}
	
}
elseif ($id=='hapgroup') {
$userid = unhex($_GET['userid'],$noacak);
$kdgroup= unhex($_GET['kdgroup'],$noacak);
echo "<div id='fotoupload-atas'>Hapus Group</div>";
	
$sql = mysql_query("select * from t_membergroup where idgroup='". mysql_real_escape_string($kdgroup)."' and userid='". mysql_real_escape_string($userid)."' ");	
if($row=mysql_fetch_array($sql)) {
	$nama=$row[nmgroup];
	$ket=$row[ket];
	$jenis=$row[idjenis];
	$file = "group/group".$kdgroup.".jpg";
	if (file_exists(''.$file.'')) {
	   $gb ="<img src='thumb-user.php?img=$file' width='100' height='100' id='gambar' align=left />";
	}

	echo "$gb Apakah Anda yakin akan menghapus group <b>$nama</b> ?<br>
	- Menghapus data keanggotaan group<br>
	- Menghapus info group dan komentarnya<br>
	- Menghapus diskusi group <br>
	 <form action='' method=post ><input type=hidden name='userid' value='".hex($userid,$noacak)."' >
	<input type=hidden name='kdgroup' value='".hex($kdgroup,$noacak)."' >
	<img src='../functions/spam.php'  ><br>Kode : <input type='text' name='code' size='12'  >
	<input type=hidden id='id' name='id' value='konhapusgroup' ><input type=submit value='Ya Hapus' id=button >
		</form> ";
	
 }
 else {
	echo "Group ini hanya dapat dihapus oleh Pendiri Groupnya";
  }
	
}
elseif ($id=='konhapusgroup') {
$userid = unhex($_POST['userid'],$noacak);
$kdgroup= unhex($_POST['kdgroup'],$noacak);
echo "<div id='fotoupload-atas'>Hapus Group</div>";
$code = $_POST['code'];
$sql = mysql_query("select * from t_membergroup where idgroup='". mysql_real_escape_string($kdgroup)."' and userid='". mysql_real_escape_string($userid)."' ");	
if($row=mysql_fetch_array($sql)) {
	$nmgroup=$row[nmgroup];	
 }
	$kode= $_SESSION['kodeRandom'];
  	if (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=hapgroup&kdgroup=".hex($kdgroup,$noacak)."&userid=".hex($userid,$noacak)."' id='button' > Kembali </a>";
	}
	else {
		$sql="delete from t_membergroup where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' ";
		if($query=mysql_query($sql)) {
		$file = "group/group".$kdgroup.".jpg";
		if (file_exists(''.$file.'')) {
		   unlink($file);
		}
        $pesan ="Mohon Maaf, Group $nmgroup telah kami hapus.<br>Terima Kasih";
        $sql = mysql_query("select * from t_membergroup_anggota where idgroup='". mysql_real_escape_string($kdgroup)."' and userid<>'".$userid."' ");	
        while($row=mysql_fetch_array($sql)) {
            $tujuan = $row[userid];
            $query=mysql_query("insert into t_member_pesan (judul,pesan,userid,tgl,tujuan_id) values ('Konfirmasi Penghapusan Group','".mysql_real_escape_string($pesan)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($tujuan)."') ");
	     }
		$sql="delete from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."'  ";
		if(!$query=mysql_query($sql)) die ("Penghapusan group  ");
		 $sql2="select * from t_membergroup_info where idgroup='".mysql_real_escape_string($kdgroup)."'  ";
		 if(!$query2=mysql_query($sql2)) die ("Penghapusan group  ");
		 while($row=mysql_fetch_array($query2)) {
			$sql="delete from t_membergroup_infokom where idgroupinfo='".mysql_real_escape_string($row[idgroupinfo])."'  ";
			if(!$query=mysql_query($sql)) die ("Penghapusan group ");
		 }
		 $sql="delete from t_membergroup_info where idgroup='".mysql_real_escape_string($kdgroup)."'  ";
		 if(!$query=mysql_query($sql)) die ("Penghapusan group ");
		// diskusi
		$kodeRandom="";

		echo "Penghapusan data group berhasil dilakukan.";
		}
	}

}
elseif ($id=='topikgroup') {
$userid = $_GET['userid'];
$kdgroup = $_GET['kdgroup'];

echo '<script type="text/javascript" src="../functions/ckeditor/ckeditor_basic.js"></script>';
echo "<div id='fotoupload-atas'>Penambahan Topik Diskusi Group</div>";

echo "<form action='kontengroup.php' method='post'   > 
<table border='0' id='upload_form'>
<tr><td>Topik </td><td><input type='text' name='judul' id='judul'  maxlength='100' size=50  ></td></tr>
<tr><td vlign=top >Isi Topik </td><td><textarea name='editor1' ></textarea>";
echo "<script type=\"text/javascript\">
				CKEDITOR.replace( 'editor1',{
        customConfig : '../functions/ckeditor/config_basic.js'
    } );
			</script>";
echo "</td></tr>
<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
</table></p><input type=hidden name='userid' value='".$userid."' >
<input type=hidden id='id' name='id' value='simtopik' ><input type=hidden name='kdgroup' value='".$kdgroup."' >
</form> ";
}
elseif ($id=='simtopik') { // simpan info group
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
	$userid = $_POST['userid'];
	$kdgroup = $_POST['kdgroup'];
	$isi = stripslashes($_POST['editor1']);
	$judul = htmlentities($_POST['judul']);
	$code = $_POST['code'];
	
	$kode= $_SESSION['kodeRandom'];
  	if (trim($isi) == '' ) {
		echo "Isi masih kosong. <a href='kontengroup.php?id=topikgroup&kdgroup=$kdgroup&userid=$userid' id='button'  > Kembali </a>";
   	}
	elseif (trim($judul)=='') {
		echo "Judul masih kosong. <a href='kontengroup.php?id=topikgroup&kdgroup=$kdgroup&userid=$userid' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=topikgroup&kdgroup=$kdgroup&userid=$userid' id='button' > Kembali </a>";
	}
	else {
		echo "<div id='fotoupload-atas'>Tambah Topik Diskusi Group </div>";
		$userid=unhex($userid,$noacak);
		$kdgroup=unhex($kdgroup,$noacak);
		$query=mysql_query("insert into t_membergroup_diskusi (judul,isi,userid,tanggal,idgroup) values ('".mysql_real_escape_string($judul)."','".mysql_real_escape_string($isi)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($kdgroup)."') ");
	
		$q=mysql_query("update t_member set point=point+2 where userid='".mysql_real_escape_string($userid)."' ");
		$kodeRandom="";
		echo "Penambahan topik diskusi group berhasil dilakukan. <br>Silahkan tutup tampilan ini.dan refresh browsenya.";

	}
	
}
elseif ($id=='edittopikgroup') {
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
$userid = $_GET['userid'];
$kdgroup = $_GET['kdgroup'];
$kdtopik = $_GET['kdtopik'];
echo '<script type="text/javascript" src="../functions/ckeditor/ckeditor_basic.js"></script>';
echo "<div id='fotoupload-atas'>Perubahan Topik Diskusi Group</div>";

$sql = mysql_query("select * from t_membergroup_diskusi where idtopik='". mysql_real_escape_string(unhex($kdtopik,$noacak))."' ");
if($row=mysql_fetch_array($sql)) {
$level=level_group($row[idgroup],unhex($userid,$noacak));
if ($level=='1') {
	echo "<form action='kontengroup.php' method='post'   > 
	<table border='0' id='upload_form'>
	<tr><td>Topik </td><td><input type='text' name='judul' id='judul'  maxlength='100' size=50 value='$row[judul]'  ></td></tr>
	<tr><td vlign=top >Isi Topik </td><td><textarea name='editor1' >$row[isi]</textarea>";
	echo "<script type=\"text/javascript\">
					CKEDITOR.replace( 'editor1',{
			customConfig : '../functions/ckeditor/config_basic.js'
		} );
				</script>";
	echo "</td></tr>
	<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
	<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
	</table></p><input type=hidden name='userid' value='".$userid."' ><input type=hidden name='kdtopik' value='".$kdtopik."' >
	<input type=hidden id='id' name='id' value='simtopikedit' ><input type=hidden name='kdgroup' value='".$kdgroup."' >
	</form> ";
  }
}
}
elseif ($id=='simtopikedit') { // simpan info group
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
	$userid = $_POST['userid'];
	$kdgroup = $_POST['kdgroup'];
	$kdtopik = $_POST['kdtopik'];
	$isi = stripslashes($_POST['editor1']);
	$judul = htmlentities($_POST['judul']);
	$code = $_POST['code'];
	
	$kode= $_SESSION['kodeRandom'];
  	if (trim($isi) == '' ) {
		echo "Isi masih kosong. <a href='kontengroup.php?id=edittopikgroup&kdgroup=$kdgroup&userid=$userid&kdtopik=$kdtopik' id='button'  > Kembali </a>";
   	}
	elseif (trim($judul)=='') {
		echo "Judul masih kosong. <a href='kontengroup.php?id=edittopikgroup&kdgroup=$kdgroup&userid=$userid&kdtopik=$kdtopik' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=edittopikgroup&kdgroup=$kdgroup&userid=$userid&kdtopik=$kdtopik' id='button' > Kembali </a>";
	}
	else {
		echo "<div id='fotoupload-atas'>Perubahan Topik Diskusi Group </div>";
		$kdtopik=unhex($kdtopik,$noacak);
		$query=mysql_query("update t_membergroup_diskusi set judul='".mysql_real_escape_string($judul)."', isi='".mysql_real_escape_string($isi)."',tanggal=NOW() where idtopik='".mysql_real_escape_string($kdtopik)."' ");
		$kodeRandom="";
		echo "Perubahan topik diskusi group berhasil dilakukan. <br>Silahkan tutup tampilan ini.dan refresh browsenya.";

	}
	
}
elseif ($id=='balastopikgp') {
$userid = $_GET['userid'];
$kdgroup = $_GET['kdgroup'];
$kdtopik = $_GET['kdtopik'];
echo '<script type="text/javascript" src="../functions/ckeditor/ckeditor_basic.js"></script>';
echo "<div id='fotoupload-atas'>Balasan Topik Diskusi Group</div>";

echo "<form action='kontengroup.php' method='post'   > 
<table border='0' id='upload_form'>
<tr><td vlign=top >Isi Balasan </td><td><textarea name='editor1' ></textarea>";
echo "<script type=\"text/javascript\">
				CKEDITOR.replace( 'editor1',{
        customConfig : '../functions/ckeditor/config_basic.js'
    } );
			</script>";
echo "</td></tr>
<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
<tr><td></td><td><input type=submit name='submit' value=' Simpan ' id='button' ></td></tr>
</table></p><input type=hidden name='userid' value='".$userid."' ><input type=hidden name='kdtopik' value='".$kdtopik."' >
<input type=hidden id='id' name='id' value='simbalasgp' ><input type=hidden name='kdgroup' value='".$kdgroup."' >
</form> ";
}
elseif ($id=='simbalasgp') { // simpan info group
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
	$userid = $_POST['userid'];
	$kdgroup = $_POST['kdgroup'];
	$kdtopik = $_POST['kdtopik'];
	$isi = stripslashes($_POST['editor1']);
	$code = $_POST['code'];
	
	$kode= $_SESSION['kodeRandom'];
  	if (trim($isi) == '' ) {
		echo "Isi masih kosong. <a href='kontengroup.php?id=balastopikgp&kdgroup=$kdgroup&userid=$userid&kdtopik=$kdtopik' id='button'  > Kembali </a>";
   	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontengroup.php?id=balastopikgp&kdgroup=$kdgroup&userid=$userid&kdtopik=$kdtopik' id='button' > Kembali </a>";
	}
	else {
		echo "<div id='fotoupload-atas'>Balasan Topik Diskusi Group ini</div>";
		$userid=unhex($userid,$noacak);
		$kdtopik=unhex($kdtopik,$noacak);
		$query=mysql_query("insert into t_membergroup_diskusibalas (idtopik,isi,userid,tanggal) values ('".mysql_real_escape_string($kdtopik)."','".mysql_real_escape_string($isi)."','".mysql_real_escape_string($userid)."',NOW() ) ");
	
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
		$kodeRandom="";
		echo "Penambahan balasan topik diskusi group berhasil dilakukan. <br>Silahkan tutup tampilan ini.dan refresh browsenya.";

	}
	
}

?>