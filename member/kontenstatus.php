<?php
session_start();
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}
$userid = $_POST['userid'];
//$kondisi = substr($userid,0,1);
//$userid = unhex(substr($userid,1,30),$noacak);
$userid = unhex($userid,$noacak);
$value=explode(",",$userid,2);
$kondisi=$value[0];$userid=$value[1];
if ($kondisi=='komstatus') { //penyimpanan komentar status
include "../functions/member_layout.php";
	if(isset($_POST['textcontent'])) 	{
		$textcontent=$_POST['textcontent'];
		$textcontent = htmlentities($textcontent);
 		$textcontent = nl2br($textcontent);
		$idstatus = $_POST['idstatus'];
		// Sql data insert values into comment table
		$q=mysql_query("insert into t_memberstatus_kom (idstatus,tanggal,userid,pesan) values ('".mysql_real_escape_string($idstatus)."',NOW(),'".mysql_real_escape_string($userid)."','".mysql_real_escape_string($textcontent)."') ");
		
	}
	$sql2 = mysql_query("SELECT nama,userid FROM t_member where userid='".mysql_real_escape_string($userid)."' ");
	if($r=mysql_fetch_array($sql2)) {
		$nama = $r[nama];
		$gb=fotouser($r[userid]);
		$selisih = date("d M Y H:i");
		$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
	}
	
echo "<div class=\"load_comment\"><div style='border-bottom:#FFFFFF dashed 1px;min-height:60px;'>$gb <b>$nama</b> :: $selisih <br>$textcontent</div></div>";
}
elseif ($kondisi=='statusanda'){
// penyimpanan status member 2
$content=$_POST['stcontent'];
$content = htmlentities($content );
$content = nl2br($content);
$pengirim =$_POST['pengirim'];
if ($pengirim=='') $pengirim=$userid;
else $pengirim = unhex($pengirim,$noacak);
$q=mysql_query("insert into t_memberstatus (userid,tanggal,pengirim,pesan) values ('".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($pengirim)."','".mysql_real_escape_string($content)."') ");
$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
   

}
elseif ($kondisi=='shoutbox'){
// penyimpanan pesan shoutbox
$pesan=$_POST['pesan'];
$pesan = htmlentities($pesan);
$pesan = nl2br($pesan);
if ($pesan=='PESAN' or trim($pesan)==''){ }
elseif ($userid=='NAMA' or trim($userid)=='') { }
else {
	$q=mysql_query("insert into t_pesan (pengirim,waktu,pesan) values ('".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($pesan)."') ");
}
//$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
}
elseif ($kondisi=='komenfoto'){
// komentar album foto - simpan
$pesan=$_POST['pesan'];
$kdfoto=$_POST['kdfoto'];
$pesan = htmlentities($pesan);
$pesan = nl2br($pesan);
$q=mysql_query("insert into t_memberfoto_kom (idfoto,userid,tanggal,komentar) values ('".mysql_real_escape_string($kdfoto)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($pesan)."') ");
$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
}
elseif ($kondisi=='hapfoto'){
// hapus foto pribadi
$kdalbum=$_POST['kdalbum'];
$kdfoto = unhex($_POST['kdfoto'],$noacak);
	$sql="delete from t_memberfoto where idfoto='".mysql_real_escape_string($kdfoto)."' and idalbum='".mysql_real_escape_string($kdalbum)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan foto  ");
	$sql="delete from t_memberfoto_kom where idfoto='".mysql_real_escape_string($kdfoto)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan foto  ");	
	$file ="foto/foto$kdfoto.jpg";
	if (file_exists(''.$file.'')) {
		unlink($file);
	}
}
elseif ($kondisi=='hapkomfoto'){
// hapus komentar foto pribadi
$kdkom=unhex($_POST['kdkom'],$noacak);
	$sql="delete from t_memberfoto_kom where idfotokom='".mysql_real_escape_string($kdkom)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan komentar foto  ");	

}
elseif ($kondisi=='tamalbum'){
// menambah album foto
$album = $_POST['album'];
$album = htmlentities($album);
	$sql="insert into t_memberfoto_album (userid,keterangan) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string($album)."') ";
	if(!$query=mysql_query($sql)) die ("enambahan album foto  ");	
$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");

}
elseif ($kondisi=='hapalbum'){
// hapus album foto dan fotonya dan komentarnya
$kdalbum=unhex($_POST['kdalbum'],$noacak);
	$sql="delete from t_memberfoto_album where idalbum='".mysql_real_escape_string($kdalbum)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan album foto  ");
	$sql="select * from t_memberfoto where idalbum='".mysql_real_escape_string($kdalbum)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan foto  ");
	while($row=mysql_fetch_array($query)) {
		$sql2="delete from t_memberfoto_kom where idfoto='".mysql_real_escape_string($row[idfoto])."' ";
		if(!$query2=mysql_query($sql2)) die ("Penghapusan foto  ");	
		$file ="foto/foto$row[idfoto].jpg";
		if (file_exists(''.$file.'')) {
			unlink($file);
		}
	}
	$sql="delete from t_memberfoto where idalbum='".mysql_real_escape_string($kdalbum)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan album foto  ");
}
elseif ($kondisi=='haptem') {
	// hapus teman 
	$kode=unhex($_POST['kode'],$noacak);
	$sql="delete from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and id_con='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 1 ");	
	$sql="delete from t_member_contact where id_con='".mysql_real_escape_string($userid)."' and id_master='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");	
}
elseif ($kondisi=='hub') {
	// konfirmasi permintaan teman
	$kode=unhex($_POST['kode'],$noacak);
	$sql="insert into t_member_contact (id_master,id_con,status) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string($kode)."','1') ";
	if(!$query=mysql_query($sql)) die ("Penambahan teman 1 ");	
	$sql="update t_member_contact set status='1' where id_master='".mysql_real_escape_string($kode)."' and id_con='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");	
}
elseif ($kondisi=='tolak') {
	// tolak pertemanan
	$kode=unhex($_POST['kode'],$noacak);
	$sql="update t_member_contact set status='2' where id_master='".mysql_real_escape_string($kode)."' and id_con='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
}
elseif ($kondisi=='konhub') {
	// konfirmasi ulang
	$kode=unhex($_POST['kode'],$noacak);
	$sql="update t_member_contact set status='0' where id_master='".mysql_real_escape_string($userid)."' and id_con='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
}
elseif ($kondisi=='haptol') {
	// hapus pertemanna
	$kode=unhex($_POST['kode'],$noacak);
	$sql="delete from t_member_contact where id_master='".mysql_real_escape_string($userid)."' and id_con='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
}
elseif ($kondisi=='kongabung') {
	// konfirmasi group
	$kode=unhex($_POST['kode'],$noacak);
	$sql="update t_membergroup_anggota set status='1' where userid='".mysql_real_escape_string($userid)."' and idgroup='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
}
elseif ($kondisi=='tolakgroup') {
	// total konfirmasi group
	$kode=unhex($_POST['kode'],$noacak);
	$sql="delete from t_membergroup_anggota where userid='".mysql_real_escape_string($userid)."' and idgroup='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
}
elseif ($kondisi=='keluar') {
	// total konfirmasi group
	$kdgroup=unhex($_POST['kode'],$noacak);
$sql="select userid from t_membergroup where idgroup='".mysql_real_escape_string($kdgroup)."' ";
	if(!$query=mysql_query($sql));
	if($row=mysql_fetch_array($query)) {
	  if ($row[userid]<>$userid) {
		$sql="delete from t_membergroup_anggota where userid='".mysql_real_escape_string($userid)."' and idgroup='".mysql_real_escape_string($kdgroup)."' ";
		if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
	  }
	}
	
}
elseif ($kondisi=='gbgp') {
	// konfirmasi group
	$kode=unhex($_POST['kode'],$noacak);
	$sql="select stgroup from t_membergroup where idgroup='".mysql_real_escape_string($kode)."' ";
	if(!$query=mysql_query($sql));
	$row=mysql_fetch_array($query);
	if ($row[stgroup]=='1') $status='2';
	else $status='1';
	$sql="select * from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kode)."' and userid='".mysql_real_escape_string($userid)."'  ";
	if(!$query=mysql_query($sql));
	if(mysql_num_rows($query)==0) {
	$sql="insert into t_membergroup_anggota (status,userid,idgroup,tanggal,kategori) values ('$status','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($kode)."',NOW(),'0') ";
	if(!$query=mysql_query($sql)) die ("Penghapusan teman 2 ");
	}
}
elseif ($kondisi=='kominfo'){
// tambah komentar info - simpan
$pesan =$_POST['pesan'];
$kdinfo =unhex($_POST['kdinfo'],$noacak);
$pesan = htmlentities($pesan);
$pesan = nl2br($pesan);
$q=mysql_query("insert into t_membergroup_infokom (idgroupinfo,userid,tanggal,komentar) values ('".mysql_real_escape_string($kdinfo)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($pesan)."') ");
$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
}
elseif ($kondisi=='hapinfo'){
// hapus info group
$kdinfo = unhex($_POST['kdinfo'],$noacak);
	$sql="delete from t_membergroup_info where idgroupinfo='".mysql_real_escape_string($kdinfo)."'  ";
	if(!$query=mysql_query($sql)) die ("Penghapusan foto  ");
	$sql="delete from t_membergroup_infokom where idgroupinfo='".mysql_real_escape_string($kdinfo)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan info  ");	

}
elseif ($kondisi=='hapinfokom'){
// hapus komentar info  group
$kdkom=unhex($_POST['kdkom'],$noacak);
	$sql="delete from t_membergroup_infokom where idinfokom='".mysql_real_escape_string($kdkom)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan komentari info  ");	

}
elseif ($kondisi=='hpanggota'){
// hapus anggota dari group

$kdgroup=unhex($_POST['kdgroup'],$noacak);
	$sql="select userid from t_membergroup where idgroup='".mysql_real_escape_string($kdgroup)."' ";
	if(!$query=mysql_query($sql));
	if($row=mysql_fetch_array($query)) {
		if ($row[userid]<>$userid) {
	$sql="delete from t_membergroup_anggota where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan anggota group ");	
		}
	}

}
elseif ($kondisi=='konfgp'){
// hapus anggota dari group

$kdgroup=unhex($_POST['kdgroup'],$noacak);
	$sql="update t_membergroup_anggota set status='1' where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan anggota group ");	

}
elseif ($kondisi=='levanggota'){
// hapus anggota dari group
$kdgroup=unhex($_POST['kdgroup'],$noacak);
$ket=$_POST['ket'];
if ($ket=='0') $ket='1';
else $ket='0';
	$sql="select userid from t_membergroup where idgroup='".mysql_real_escape_string($kdgroup)."' ";
	if(!$query=mysql_query($sql));
	if($row=mysql_fetch_array($query)) {
		if ($row[userid]<>$userid) {
		$sql="update t_membergroup_anggota set kategori='".mysql_real_escape_string($ket)."' where idgroup='".mysql_real_escape_string($kdgroup)."' and userid='".mysql_real_escape_string($userid)."' ";
		if(!$query=mysql_query($sql)) die ("Penghapusan anggota group ");	
		}
	}
}
elseif ($kondisi=='hptopik'){
// hapus topik dan isinya
$kdtopik=unhex($_POST['kdtopik'],$noacak);
$kdgroup=unhex($_POST['kdgroup'],$noacak);
	$sql="delete from t_membergroup_diskusi where idgroup='".mysql_real_escape_string($kdgroup)."' and idtopik='".mysql_real_escape_string($kdtopik)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan anggota group ");	
	$sql="delete from t_membergroup_diskusibalas where idtopik='".mysql_real_escape_string($kdtopik)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan anggota group ");	
}
elseif ($kondisi=='hpbalasgp'){
// hapus balasan topik group
$kdtopik=unhex($_POST['kdtopik'],$noacak);
$kdgroup=unhex($_POST['kdgroup'],$noacak);
$kdbalas=unhex($_POST['kdbalas'],$noacak);
	$sql="delete from t_membergroup_diskusibalas where idtopik='".mysql_real_escape_string($kdtopik)."' and idbalas='".mysql_real_escape_string($kdbalas)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan anggota group ");	
}
elseif ($kondisi=='happesan'){
// hapus pesan
$kdpesan=unhex($_POST['kdpesan'],$noacak);
	$sql="delete from t_member_pesan where id='".mysql_real_escape_string($kdpesan)."'  ";
	if(!$query=mysql_query($sql)) die ("Penghapusan pesan ");	
}
elseif ($kondisi=='hapustopik'){
// hapus topik dikusi umum
$kdtopik=unhex($_POST['kdtopik'],$noacak);
	$sql="delete from t_forum_isi where isi_id=".mysql_real_escape_string($kdtopik)." ";
	if(!$query=mysql_query($sql)) die ("Penghapusan topik ");
	$sql2="delete from t_forum_balas where isi_id='".mysql_real_escape_string($kdtopik)."' ";
	if(!$query=mysql_query($sql2)) die ("Penghapusan balasan topik ");	
}
elseif ($kondisi=='hapusbalas'){
// hapus balasan topik dikusi umum
$kdbalas=unhex($_POST['kdbalas'],$noacak);
	$sql2="delete from t_forum_balas where balas_id='".mysql_real_escape_string($kdbalas)."' ";
	if(!$query=mysql_query($sql2)) die ("Penghapusan balasan topik ");		
}
elseif ($kondisi=='komopini'){
// tambah komentar opini - simpan
$pesan =$_POST['pesan'];
$kdopini =unhex($_POST['kdopini'],$noacak);
$pesan = htmlentities($pesan);
$pesan = nl2br($pesan);
$q=mysql_query("insert into t_project_com (id_project,userid,tanggal,komentar) values ('".mysql_real_escape_string($kdopini)."','".mysql_real_escape_string($userid)."',NOW(),'".mysql_real_escape_string($pesan)."') ");
$q=mysql_query("update t_member set point=point+1 where userid='".mysql_real_escape_string($userid)."' ");
}
elseif ($kondisi=='hapopinikom'){
// hapus komentar opini
$kdkom=unhex($_POST['kdkom'],$noacak);
	$sql="delete from t_project_com where id='".mysql_real_escape_string($kdkom)."' ";
	if(!$query=mysql_query($sql)) die ("Penghapusan komentari opini  ");	

}
else {
	echo "eroooorr ";
}
?>