<?php
 if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
//*88888888888888888888888888888888   user member 88888888888888888888888888888888 /
include('../lib/config.php');
class userclass {
function member_pass() {
include "koneksi.php";
$kode=$_GET['id'];
	$pass = rand(1111,9999);
	$password=md5($pass);
	$query="update t_member set password='$password' where userid='". mysql_escape_string($kode)."'";
    $result = mysql_query($query);
	$query="select * from t_member where userid='". mysql_escape_string($kode)."'";
    $result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if(!@mail($row[email],"Konfirmasi Password","Password Baru Anda= $pass<br> Terima kasih webmaster $webhost","From: $webmail")) {
	   echo "Gagal kirim email<br>";
	}
	
	echo "<font>Data Password member telah diupdate. Silahkan Cek email.<br> <b>Username : $row[username], Password : $pass</b><br>
	| <a href='admin.php?mode=member' >Lihat Member</a> |";
	
}

function member_hap() {
include "koneksi.php";
$kode=$_GET['id'];
     if(!$r = mysql_query("SELECT * FROM t_member where userid='". mysql_escape_string($kode)."'")) die("Error connecting to the database.");
     $row = mysql_fetch_array($r);
//	if(!@mail($row[email],"Konfirmasi bergabung ke $webhost","Data tidak lengkap. Member anda kami hapus dari $webhost<br>Terima kasih Webmaster","From: $webmail")) {
//	   echo "Gagal kirim email<br>";
//	}
	 
	$query = "delete from t_member WHERE userid='". mysql_escape_string($kode)."'";
    $result = mysql_query($query) or die("Query failed");
	$file= "../member/profil/gb".$kode.".jpg";
	if (file_exists($file)) {
	    unlink($file);
	 }
}

function member_valid() {
include "koneksi.php";
$kode=$_GET['id'];
    $query = "SELECT * FROM t_member WHERE userid='". mysql_escape_string($kode)."'";
    $result = mysql_query($query) or die("Query failed");
    $rows = mysql_fetch_array($result);
	$kd="1";
	if($rows[status]=='1') $kd="0";
	$query = "UPDATE t_member SET status='$kd' WHERE userid='". mysql_escape_string($kode)."'";
    $result = mysql_query($query) or die("Query failed");
	if ($kd==1) {
	$pesan="Selamat Datang $row[nama] di $webhost<br>Sekarang ada telah diberikan akses untuk LOGIN di $webhost.<br>
	Silahkan pergunakan fasilitas ini sebagaimana mestinya.<br>Username = $row[username]";
	   if(!@mail($row[email],"Selamat Datang di $webhost",$pesan,"From: $webmail")) {
	       echo "Gagal kirim email<br>";
	   }
	}
	else {
	$pesan="Terima kasih $row[nama].<br>Untuk sementara LOGIN anda di $webhost ditutup. Silahkan konfirmasi ulang
	ke alamat email kami.";
    //	if(!@mail($row[email],"Terima kasih untuk $row[nama]",$pesan,"From: $webmail")) {
    //	   echo "Gagal kirim email<br>";
    //	}
	}
}

function member_mod() {
include "koneksi.php";
$kode=$_GET['id'];
	$query = "UPDATE t_member SET kategori='4',ket='Admin' WHERE userid='". mysql_escape_string($kode)."'";
    $result = mysql_query($query) or die("Query failed");
}
function mod_forum() {
include "koneksi.php";
$kode=$_GET['kode'];
$id=$_GET['id'];
echo"<table width=80%><tr><td colspan=2><font>SET FORUM Koordinator</td></tr>
<tr><td colspan=2><font></td></tr>";

if ($kode=="") {
	$result = mysql_query("SELECT * FROM t_member where userid='". mysql_escape_string($id)."' and kategori='2'") or die("Query failed 1");
    $r= mysql_fetch_array($result);
	echo"<form action='admin.php?mode=mod_forum' method='get'>
	<input type=hidden name='id' value='$id' ><input type=hidden name='mode' value='mod_forum' >
	<tr><td ><font>Koordinator</td><td colspan=2><font>$r[nama]</td></tr>
	<tr><td valign=top><font>Topik Forum </td><td ><select name='kode'>";
    $query = "SELECT * FROM t_forum";
    $result = mysql_query($query) or die("Query failed 1");
    while($rows = mysql_fetch_array($result)) {
		echo "<option value='$rows[forum_id]'>$rows[forum_nama]</option>";
	}
	echo "</select><br><br><input type='reset' value='Ulang' > &nbsp;
	<input type=submit value=' Simpan ' ></td></tr></form>";
}
else {
	if ($id<>'') {
	$query = "insert into t_forum_moderator (userid,forum_id) values ('". mysql_escape_string($id)."','". mysql_escape_string($kode)."')";
    $result = mysql_query($query) or die("Query failed $id,$kode");
	echo "<tr><td colspan=2><font>Sukses</td></tr>";
	}
	else echo "<tr><td colspan=2><font>Data gagal $id</td></tr>";
}
echo"<tr><td colspan=2><table width=100% border=1 bordercolor='#dddddd'>
<tr bgcolor='#ddddaa'><td ><font>Topik Forum</td><td ><font>Coordinator</td><td ><font>Status</td></tr>";
    $query = "SELECT * FROM t_forum_moderator,t_forum,t_member where t_forum.forum_id=t_forum_moderator.forum_id and t_forum_moderator.userid=t_member.userid";
    $result = mysql_query($query) or die("Query failed 1");
    while($row = mysql_fetch_array($result)) {
		echo"<tr><td ><font>$row[forum_nama]</td><td ><font>$row[nama]</td><td ><font><a href='admin.php?mode=modforum_hap&id=$row[moderator_id]'>Delete</a></td></tr>";
	}
	echo"</table><br><br></td></tr>";
echo"</table>";
	
}
function modforum_hap() {
include "koneksi.php";
$id=$_GET['id'];
	$query = "delete from t_forum_moderator WHERE moderator_id='". mysql_escape_string($id)."'";
    $result = mysql_query($query) or die("Query failed 1");
}

function member() {
include "koneksi.php";
$hal=$_GET['hal'];
$ket=$_GET['ket'];
echo "<img src='../images/panah.gif'> <font class='ari12' style='color:#ff9900'><b>MEMBERS</B></font><br>";

if ($ket=='') $ket='Tamu';
$brs=30;
$kol=10;
  $byk_result1=mysql_query("select * from t_member where ket='". mysql_escape_string($ket)."'");
   
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
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * from t_member where ket='". mysql_escape_string($ket)."' order by status,nama LIMIT ".$awal.",".$brs.""; 
  $q= mysql_query ($query) or die (mysql_error()); 
  $n = mysql_num_rows ($q);
  if ($ket=='Tamu') $s1='selected';
  elseif ($ket=='Alumni') $s2='selected';
  elseif ($ket=='Siswa') $s3='selected';
  elseif ($ket=='Orang Tua') $s4='selected';
  elseif ($ket=='Admin') $s6='selected';
  elseif ($ket=='Guru') $s5='selected';
  echo "<center><form action='admin.php?mode=member' method='post' name=member >";
  echo '<font>Pilih jenis member : <select name="ket" onchange="document.location.href=\'admin.php?mode=member&ket=\'+document.member.ket.value">';
  echo "<option value='Tamu' $s1>Tamu</option><option value='Alumni' $s2>Alumni</option>
	<option value='Siswa' $s3>Siswa</option><option value='Orang Tua' $s4>Orang Tua</option><option value='Guru' $s5>Guru</option>
	<option value='Admin' $s6>Admin/Kepsek</option></select></form> <br>";
	
    if ($jml!=0) {
    echo "<font class='ver10'><a href='admin.php?mode=member&ket=$ket&hal=1' class=ver10 title='Page 1'>First </a> <a href='admin.php?mode=member&ket=$ket&hal=$back' class='ver10' title='$back'>Back </a> |"; 
  	for($i=$mulai;$i<=$batas;$i++)
  	{
  		if ($i==$hal) 
	echo "<b><a href='admin.php?mode=member&ket=$ket&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
		else
  	    echo "<a href='admin.php?mode=member&ket=$ket&hal=$i'  class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  	}
  	echo "<a href='admin.php?mode=member&ket=$ket&hal=$next' class='ver10' title='$next'> Next</a> 
  	<a href='admin.php?mode=member&ket=$ket&hal=$jml' class='ver10' title='Page $jml'> Last</a></font></center>";
  }
  while($row=mysql_fetch_array($q)) {
		$file ="../member/profil/gb$row[userid].jpg";
		$gb="<a href='#' title='$row[nama]'><img src='../member/profil/kosong.jpg' width='60' height='75' align=left></a>";
		if (file_exists(''.$file.'')) {
	        $gb="<a href='#' title='$row[nama]'><img src='$file' width='60' height='75' align=left></a>";
		}
		if ($row[kelamin]=="m") $kelamin="Male";
		else $kelamin="Female";
		
		$tgllogin= date("d-m-Y s:i", strtotime($row[tgl_login]));
		$v="Valid";
		$warna="#0066cc";
		$valid="";
		if ($row[status]=='0') {$v="<b>No Valid</b>";$warna="#990000";}
		$valid="<a href='admin.php?mode=member_mod&id=$row[userid]' class='ver10' title='Change Member to Admin member'>Set Admin/Kepsek</a> | <a href='admin.php?mode=member_pass&id=$row[userid]' class='ver10' title='Change Password'>Reset Password</a> | <a href='admin.php?mode=member_valid&id=$row[userid]' class='ver10' title='Access Validation'>$v</a> | <a href='admin.php?mode=member_hap&id=$row[userid]' class='ver10'>Delete</a>";
		echo "<table width='98%' BORDER=1 bordercolor=$warna CELLPADDING=4 CELLSPACING=0>
		<tr><td bgcolor=$warna width=50%><font class='ver10'><b>$row[nama]</font></td><td align=right><font class='ver10'>$valid</font></td></tr>
		<tr><td colspan=2><table width=100%><tr><td width=50%><font class='ver10'>$gb$row[username]<br>$kelamin<br>$row[email]<br>Login terakhir : $tgllogin
		<br>Total login : $row[totlogin]</font></td><td width=50% valign=top><font class='ver10'>$row[alamat]</font></td></tr></table></td></tr></table><br>";
	}

}
//------------------ cari member--------------------------
function carimember() {
include "koneksi.php";
$hal=$_GET['hal'];
$cari=$_GET['cari'];
$jenis=$_GET['jenis'];
echo "<img src='../images/panah.gif'> <font class='ari12' style='color:#ff9900'><b>CARI MEMBERS</B></font><br>";

if ($cari<>'') {
if ($jenis=='nama') $sintak =" where nama like '%".mysql_escape_string($cari) ."%'";
elseif($jenis=='username') $sintak=" where username like '%".mysql_escape_string($cari) ."%'";
else $sintak=" where email like '%".mysql_escape_string($cari) ."%'";
}
else $sintak =" where nama='1'";
$brs=5;
$kol=10;
  $byk_result1=mysql_query("select * from t_member $sintak ");
   
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
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * from t_member $sintak order by status,nama LIMIT ".$awal.",".$brs.""; 
  $q= mysql_query ($query) or die (mysql_error()); 
  $n = mysql_num_rows ($q);
	
	if($jenis=='nama')$s1="selected";
	elseif($jenis=='username') $s2="selected";
	else $s3="selected";
	
  echo "<center><form action='admin.php?' method='get' name=member >";
  echo '<font>Pilih jenis member : <select name="jenis" >';
  echo "<option value='nama' $s1>Nama</option><option value='username' $s2>Username</option>
	<option value='email' $s3>Email</option></select>  Kunci Pencarian <input type=text name='cari' value=$cari > 
	<input type=submit value=' Cari '><input type=hidden name=mode value=carimember ></form><br>";
	
    if ($jml!=0) {
    echo "<font class='ver10'><a href='admin.php?mode=carimember&jenis=$jenis&cari=$cari&hal=1' class=ver10 title='Page 1'>First </a> <a href='admin.php?mode=carimember&jenis=$jenis&cari=$cari&hal=$back' class='ver10' title='$back'>Back </a> |"; 
  	for($i=$mulai;$i<=$batas;$i++)
  	{
  		if ($i==$hal) 
	echo "<b><a href='admin.php?mode=carimember&jenis=$jenis&cari=$cari&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
		else
  	    echo "<a href='admin.php?mode=carimember&jenis=$jenis&cari=$cari&hal=$i'  class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  	}
  	echo "<a href='admin.php?mode=carimember&jenis=$jenis&cari=$cari&hal=$next' class='ver10' title='$next'> Next</a> 
  	<a href='admin.php?mode=carimember&jenis=$jenis&cari=$cari&hal=$jml' class='ver10' title='Page $jml'> Last</a></font></center>";
  }
  while($row=mysql_fetch_array($q)) {
		$file ="../member/profil/gb$row[userid].jpg";
		$gb="<a href='#' title='$row[nama]'><img src='../member/profil/kosong.jpg' width='60' height='75' align=left></a>";
		if (file_exists(''.$file.'')) {
	        $gb="<a href='#' title='$row[nama]'><img src='$file' width='60' height='75' align=left></a>";
		}
		if ($row[kelamin]=="m") $kelamin="Male";
		else $kelamin="Female";
		
		$tgllogin= date("d-m-Y s:i", strtotime($row[tgl_login]));
		$v="Valid";
		$warna="#0066cc";
		$valid="";
		if ($row[status]=='0') {$v="<b>No Valid</b>";$warna="#990000";}
		$valid="<a href='admin.php?mode=member_mod&id=$row[userid]' class='ver10' title='Change Member to Admin member'>Set Admin/kepsek</a> | <a href='admin.php?mode=member_pass&id=$row[userid]' class='ver10' title='Change Password'>Reset Password</a> | <a href='admin.php?mode=member_valid&id=$row[userid]' class='ver10' title='Access Validation'>$v</a> | <a href='admin.php?mode=member_hap&id=$row[userid]' class='ver10'>Delete</a>";
		echo "<table width='98%' BORDER=1 bordercolor=$warna CELLPADDING=4 CELLSPACING=0>
		<tr><td bgcolor=$warna width=50%><font class='ver10'><b>$row[nama]</font></td><td align=right><font class='ver10'>$valid</font></td></tr>
		<tr><td colspan=2><table width=100%><tr><td width=50%><font class='ver10'>$gb$row[username]<br>$kelamin<br>$row[email]<br>Login terakhir : $tgllogin
		<br>Total login : $row[totlogin]</font></td><td width=50% valign=top><font class='ver10'>$row[alamat]</font></td></tr></table></td></tr></table><br>";
	}

}

//------------------------------ moderator .----------------------------//
function moderator() {
include "koneksi.php";
$hal=$_GET['hal'];
echo "<img src='../images/panah.gif'> <font class='ari12' style='color:#ff9900'><b>Admin/Kepsek</B></font><br>";

$brs=10;
$kol=10;
  $byk_result1=mysql_query("select * from t_member where kategori='4'");
   
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
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * from t_member where kategori='4' order by status,nama LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  $n = mysql_num_rows ($query_result_handle);
 if(!$num_of_rows = mysql_num_rows ($query_result_handle)) 
    echo "<font class='ver10'> No member </font>";
 else {
    if ($jml!=0) {
    echo "<center><font class='ver10'><a href='admin.php?mode=moderator&hal=1' class=ver10 title='Page 1'>First </a>  
    <a href='admin.php?mode=moderator&hal=$back' class='ver10' title='$back'>Back </a> |"; 
  	for($i=$mulai;$i<=$batas;$i++)
  	{
  		if ($i==$hal) 
	 		echo "<b><a href='admin.php?mode=moderator&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
		else
  			echo "<a href='admin.php?mode=moderator&hal=$i'  class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  	}
  	echo "<a href='admin.php?mode=moderator&hal=$next' class='ver10' title='$next'> Next</a> 
  	<a href='admin.php?mode=moderator&hal=$jml' class='ver10' title='Page $jml'> Last</a></font></center>";
   }
  
  while($row=mysql_fetch_array($query_result_handle)) {
		$file ="../member/profil/gb$row[userid].jpg";
		$gb="<a href='#' title='$row[nama]'><img src='../member/profil/kosong.jpg' width='60' height='75' align=left></a>";
		if (file_exists(''.$file.'')) {
	        $gb="<a href='#' title='$row[nama]'><img src='$file' width='60' height='75' align=left></a>";
		}
		if ($row[kelamin]=="m") $kelamin="Male";
		else $kelamin="Female";
		
		//$neg=negara("ID");
		$v="Valid";
		$warna="#0066cc";
		$valid="";
		if ($row[status]=='0') {$v="<b>No Valid</b>";$warna="#aacc00";}
		$valid="<a href='admin.php?mode=mod_forum&id=$row[userid]' class='ver10' title='Set Admin Forum'>Admin Forum</a> | <a href='admin.php?mode=moderator_pass&id=$row[userid]' class='ver10' title='Change Password'>Reset Password</a> | <a href='admin.php?mode=moderator_valid&id=$row[userid]' class='ver10' title='Access Validation'>$v</a> | <a href='admin.php?mode=member_hap&id=$row[userid]' class='ver10'>Delete</a>";
		echo "<table width='96%' BORDER=1 bordercolor=$warna CELLPADDING=4 CELLSPACING=0>
		<tr><td bgcolor=$warna width=40%><font class='ver10'><b>$row[nama]</font></td><td align=right><font class='ver10'>$valid</font></td></tr>
		<tr><td colspan=2><table width=100%><tr><td width=50%><font class='ver10'>$gb$row[username]<br>$kelamin<br>$row[email]</font></td><td width=50% valign=top><font class='ver10'>$row[alamat]</font></td></tr></table></td></tr></table><br>";
	}

  }
 
}

///--------------------------project-----------------------//
function project_hap() {
      include("koneksi.php");
	  $kode=$_POST['id'];
	  if (!empty($kode))
	  {
	  	while (list($key,$value)=each($kode))		{
			$sql="delete from t_project where id='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../images/project/p".$key.".jpg";
			if (file_exists($file)) {
			unlink($file);
			}
		}
	  }
}
function project_valid() {
include "koneksi.php";
$kode=$_GET['id'];
    $query = "SELECT * FROM t_project WHERE id='". mysql_escape_string($kode)."'";
    $result = mysql_query($query) or die("Query failed");
    $rows = mysql_fetch_array($result);
	$kd="1";
	if($rows[status]=='1') $kd="0";
	$query = "UPDATE t_project SET status='$kd' WHERE id='". mysql_escape_string($kode)."'";
    $result = mysql_query($query) or die("Query failed");
}

function project() {
include "koneksi.php";
$hal=$_GET['hal'];

  $brs=25;
  $kol=10;
  $byk_result=mysql_query("select * from t_project ");
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
  
  $query = "SELECT * FROM t_project  order by status asc,id desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
 echo "<form action='admin.php' method='post' name='project' >";
  echo"<table width='100%' border='1' bordercolor='#000000' cellspacing='0' cellpadding='5' >
  <tr><td colspan=6 align=center ><b><font>-- Opini --- </td></tr>";
  if ($jml!=0) {
  echo "<tr  ><td colspan=6 ><center><font class='ver10'><a href='admin.php?mode=project&hal=1' style='color:000000;text-decoration:none' title='Page 1'>First </a> 
  <a href='admin.php?mode=project&hal=$back' style='color:000000;text-decoration:none' title='$back'>Previous </a>  |"; 
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
	  	echo "<b><a href='admin.php?mode=project&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a></b> |";		
	else
  	echo "<a href='admin.php?mode=project&hal=$i' style='color:000000;text-decoration:none' title='Page $i from $byk Data'> $i </a> |";		
  }
  echo"<a href='admin.php?mode=project&hal=$next' style='color:000000;text-decoration:none' title='$next'> Next</a> 
  <a href='admin.php?mode=project&hal=$jml' style='color:000000;text-decoration:none' title='Page $jml'> Last</a>
  </font></center></td></tr>";
  }
    echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  echo "<tr bgcolor='#4c96da' ><td><font><b>No</td><td><font><b>Tanggal</td><td><font><b>Member</td><td><font><b>Judul</td><td><font><b>Status</td><td><font><b>Hapus</td></tr>";
  if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  		if(!$q=mysql_query("select * from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama = $ro[nama];
		$tgl=strtotime($row[tanggal]);
		$tgl= date('d-m-Y',$tgl);
		$v="Valid";$t="No Valid";
		$valid="";
		$warna="";
		if ($row[status]=='0') {$v="<b>No Valid</b>";$warna="#ADD2FF";$t="Valid";}
		echo"<tr bgcolor=$warna><td width=5% ><font>$j</td><td width=10% ><font >$tgl </td><td width=20%><font>$nama</td><td width=40%><font>$row[judul]</td>
		<td width=5%><font><a href='admin.php?mode=project_valid&id=$row[id]' title='Diubah menjadi $t' class='ver10'>$v</a></td><td width=5%>
		 <input title='Hapus' type='checkbox' name='id[$row[id]]' value='on'></td></tr>";
		 $j++;
 }        
  echo "</table><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"mode\" value=\"project_hap\"><input type=\"submit\" value=\"Hapus\">";
   
}
function stmember() { ///daftar member dan hapus member
include "koneksi.php";
$hal=$_GET['hal'];
$ket=$_GET['ket'];
echo "<img src='../images/panah.gif'> <font class='ari12' style='color:#ff9900'><b>STATUS MEMBERS</B></font><br>";

if ($ket=='') $ket='Tamu';
$brs=30;
$kol=10;
  $byk_result1=mysql_query("select * from t_member where ket='". mysql_escape_string($ket)."'");
   
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
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * from t_member where ket='". mysql_escape_string($ket)."' order by nama LIMIT ".$awal.",".$brs.""; 
  $q= mysql_query ($query) or die (mysql_error()); 
  $n = mysql_num_rows ($q);
  if ($ket=='Tamu') $s1='selected';
  elseif ($ket=='Alumni') $s2='selected';
  elseif ($ket=='Siswa') $s3='selected';
  elseif ($ket=='Orang Tua') $s4='selected';
  elseif ($ket=='Admin') $s6='selected';
  elseif ($ket=='Guru') $s5='selected';
  echo "<center><form action='admin.php?mode=member' method='post' name=member >";
  echo '<font>Pilih jenis member : <select name="ket" onchange="document.location.href=\'admin.php?mode=stmember&ket=\'+document.member.ket.value">';
  echo "<option value='Tamu' $s1>Tamu</option><option value='Alumni' $s2>Alumni</option>
	<option value='Siswa' $s3>Siswa</option><option value='Orang Tua' $s4>Orang Tua</option><option value='Guru' $s5>Guru</option>
	<option value='Admin' $s6>Admin/Kepsek</option></select></form> <br>";
	
    if ($jml!=0) {
    echo "<font class='ver10'><a href='admin.php?mode=stmember&ket=$ket&hal=1' class=ver10 title='Page 1'>First </a> <a href='admin.php?mode=stmember&ket=$ket&hal=$back' class='ver10' title='$back'>Back </a> |"; 
  	for($i=$mulai;$i<=$batas;$i++)
  	{
  		if ($i==$hal) 
	echo "<b><a href='admin.php?mode=stmember&ket=$ket&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
		else
  	    echo "<a href='admin.php?mode=stmember&ket=$ket&hal=$i'  class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  	}
  	echo "<a href='admin.php?mode=stmember&ket=$ket&hal=$next' class='ver10' title='$next'> Next</a> 
  	<a href='admin.php?mode=stmember&ket=$ket&hal=$jml' class='ver10' title='Page $jml'> Last</a></font></center>";
  }
  echo "Keterangan : Data akan dihapus yaitu 10 record data terakhir.<br><br>
  <table width=100% border=1 CELLPADDING=4 CELLSPACING=0 >
  <tr><td bgcolor='#DEDEDE' >Nama Member</td><td bgcolor='#DEDEDE'>Username</td><td bgcolor='#DEDEDE' width=15% >Total Status</td><td bgcolor='#DEDEDE' width=15% >Total Komentar</td></tr>";
  while($row=mysql_fetch_array($q)) {
		$warna="#ADD2FF";
		if(!$sql=mysql_query("select * from t_memberstatus where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$totstatus=mysql_num_rows($sql);
		if(!$sql=mysql_query("select * from t_memberstatus_kom where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$totkom=mysql_num_rows($sql);
		echo "<tr><td><font class='ver10'>$row[nama]</font></td><td><font class='ver10'>$row[username]</font></td>
		<td align=center><font class='ver10'>$totstatus  <a href='admin.php?mode=statushapus&kode=$row[userid]' title='Hapus per 10 data terakhir'  ><img src='../images/hapus1.png' border=0 align=right ></a></font></td><td align=center ><font class='ver10'>$totkom <a href='admin.php?mode=stkomhapus&kode=$row[userid]' title='Hapus per 10 data terakhir'  ><img src='../images/hapus1.png' border=0 align=right ></a></font></td></tr>";
	}
	echo "</table>";

}
function statushapus() {
include("koneksi.php");
	  $kode=$_GET['kode'];
	  if (!empty($kode))
	  {
	  	$n=0;
	  	if(!$sql=mysql_query("select * from t_memberstatus where userid='".$kode."' order by idstatus desc limit 0,10")) die ("Pengambilan gagal1 member");
		while($row=mysql_fetch_array($sql)) {
			$no[$n]=$row[idstatus];
			$n++;
		}
		for($i=0;$i<$n;$i++) {
			$sql="delete from t_memberstatus where idstatus='". mysql_escape_string($no[$i])."' ";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$sql="delete from t_memberstatus_kom where idstatus='". mysql_escape_string($no[$i])."' ";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			
		}
	  }
}
function stkomhapus() {
include("koneksi.php");
	  $kode=$_GET['kode'];
	  if (!empty($kode))
	  {
	  	$n=0;
	  	if(!$sql=mysql_query("select * from t_memberstatus_kom where userid='".$kode."' order by idstatuskom desc limit 0,10")) die ("Pengambilan gagal1 member");
		while($row=mysql_fetch_array($sql)) {
			$no[$n]=$row[idstatuskom];
			$n++;
		}
		for($i=0;$i<$n;$i++) {
			$sql="delete from t_memberstatus_kom where idstatuskom='". mysql_escape_string($no[$i])."' ";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			
		}
	  }
}

function pesanmember() { // daftar member dan menghapus pesan member
include "koneksi.php";
$hal=$_GET['hal'];
$ket=$_GET['ket'];
echo "<img src='../images/panah.gif'> <font class='ari12' style='color:#ff9900'><b>PESAN MEMBERS</B></font><br>";

if ($ket=='') $ket='Tamu';
$brs=30;
$kol=10;
  $byk_result1=mysql_query("select * from t_member where ket='". mysql_escape_string($ket)."'");
   
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
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * from t_member where ket='". mysql_escape_string($ket)."' order by nama LIMIT ".$awal.",".$brs.""; 
  $q= mysql_query ($query) or die (mysql_error()); 
  $n = mysql_num_rows ($q);
  if ($ket=='Tamu') $s1='selected';
  elseif ($ket=='Alumni') $s2='selected';
  elseif ($ket=='Siswa') $s3='selected';
  elseif ($ket=='Orang Tua') $s4='selected';
  elseif ($ket=='Admin') $s6='selected';
  elseif ($ket=='Guru') $s5='selected';
  echo "<center><form action='admin.php?mode=stmember' method='post' name=member >";
  echo '<font>Pilih jenis member : <select name="ket" onchange="document.location.href=\'admin.php?mode=pesanmember&ket=\'+document.member.ket.value">';
  echo "<option value='Tamu' $s1>Tamu</option><option value='Alumni' $s2>Alumni</option>
	<option value='Siswa' $s3>Siswa</option><option value='Orang Tua' $s4>Orang Tua</option><option value='Guru' $s5>Guru</option>
	<option value='Admin' $s6>Admin/Kepsek</option></select></form> <br>";
	
    if ($jml!=0) {
    echo "<font class='ver10'><a href='admin.php?mode=pesanmember&ket=$ket&hal=1' class=ver10 title='Page 1'>First </a> <a href='admin.php?mode=pesanmember&ket=$ket&hal=$back' class='ver10' title='$back'>Back </a> |"; 
  	for($i=$mulai;$i<=$batas;$i++)
  	{
  		if ($i==$hal) 
	echo "<b><a href='admin.php?mode=pesanmember&ket=$ket&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
		else
  	    echo "<a href='admin.php?mode=pesanmember&ket=$ket&hal=$i'  class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  	}
  	echo "<a href='admin.php?mode=pesanmember&ket=$ket&hal=$next' class='ver10' title='$next'> Next</a> 
  	<a href='admin.php?mode=pesanmember&ket=$ket&hal=$jml' class='ver10' title='Page $jml'> Last</a></font></center>";
  }
  echo "Keterangan : Data akan dihapus yaitu 10 record data terakhir.<br><br>
  <table width=100% border=1 CELLPADDING=4 CELLSPACING=0 >
  <tr><td bgcolor='#DEDEDE' >Nama Member</td><td bgcolor='#DEDEDE'>Username</td><td bgcolor='#DEDEDE' width=15% >Total Pesan</td></tr>";
  while($row=mysql_fetch_array($q)) {
		$warna="#ADD2FF";
		if(!$sql=mysql_query("select * from t_member_pesan where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$totstatus=mysql_num_rows($sql);

		echo "<tr><td><font class='ver10'>$row[nama]</font></td><td><font class='ver10'>$row[username]</font></td>
		<td align=center><font class='ver10'>$totstatus  <a href='admin.php?mode=pesanhapusmem&kode=$row[userid]' title='Hapus per 10 data terakhir'  ><img src='../images/hapus1.png' border=0 align=right ></a></font></td></tr>";
	}
	echo "</table>";

}

function pesanhapusmem() {
include("koneksi.php");
	  $kode=$_GET['kode'];
	  if (!empty($kode))
	  {
	  	$n=0;
	  	if(!$sql=mysql_query("select * from t_member_pesan where userid='".$kode."' order by id desc limit 0,10")) die ("Pengambilan gagal1 member");
		while($row=mysql_fetch_array($sql)) {
			$no[$n]=$row[id];
			$n++;
		}
		for($i=0;$i<$n;$i++) {
			$sql="delete from t_member_pesan where id='". mysql_escape_string($no[$i])."' ";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");	
		}
	  }
}

function groupmember() { // daftar group member
include "koneksi.php";
$hal=$_GET['hal'];
$ket=$_GET['ket'];
echo "<img src='../images/panah.gif'> <font class='ari12' style='color:#ff9900'><b>GROUP MEMBERS</B></font><br>";

$brs=30;
$kol=10;
  $byk_result1=mysql_query("select * from t_membergroup ");
   
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
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * from t_membergroup order by nmgroup LIMIT ".$awal.",".$brs.""; 
  $q= mysql_query ($query) or die (mysql_error()); 
  $n = mysql_num_rows ($q);
	
    if ($jml!=0) {
    echo "<font class='ver10'><a href='admin.php?mode=groupmember&ket=$ket&hal=1' class=ver10 title='Page 1'>First </a> <a href='admin.php?mode=groupmember&ket=$ket&hal=$back' class='ver10' title='$back'>Back </a> |"; 
  	for($i=$mulai;$i<=$batas;$i++)
  	{
  		if ($i==$hal) 
	echo "<b><a href='admin.php?mode=groupmember&ket=$ket&hal=$i' class='ver10' title='Page $i from $byk Data'> $i </a></b> |";		
		else
  	    echo "<a href='admin.php?mode=pesanmember&ket=$ket&hal=$i'  class='ver10' title='Page $i from $byk Data'> $i </a> |";		
  	}
  	echo "<a href='admin.php?mode=groupmember&ket=$ket&hal=$next' class='ver10' title='$next'> Next</a> 
  	<a href='admin.php?mode=groupmember&ket=$ket&hal=$jml' class='ver10' title='Page $jml'> Last</a></font></center>";
  }
  echo "Keterangan : Apabila data group dihapus semua data info dan diskusi group terhapus<br><br>
  <table width=100% border=1 CELLPADDING=4 CELLSPACING=0 >
  <tr><td bgcolor='#DEDEDE' >Nama Group</td><td bgcolor='#DEDEDE' >Keterangan</td><td bgcolor='#DEDEDE' width='15%'>Total Member</td><td bgcolor='#DEDEDE' width=15% >Total info</td><td bgcolor='#DEDEDE' width=15% >Total Topik</td><td bgcolor='#DEDEDE' width=5% >Hapus</td></tr>";
  while($row=mysql_fetch_array($q)) {
		$warna="#ADD2FF";
		if(!$sql=mysql_query("select * from t_membergroup_info where idgroup='".$row[idgroup]."'")) die ("Pengambilan gagal info");
		$totinfo=mysql_num_rows($sql);
	if(!$sql=mysql_query("select * from t_membergroup_anggota where idgroup='".$row[idgroup]."'")) die ("Pengambilan gagal member");
		$totmember=mysql_num_rows($sql);
		if(!$sql=mysql_query("select * from t_membergroup_diskusi where idgroup='".$row[idgroup]."'")) die ("Pengambilan gagal diskusi");
		$totdiskusi=mysql_num_rows($sql);
		echo "<tr><td><font class='ver10'>$row[nmgroup]</font></td>
		<td><font class='ver10'>$row[ket]</font></td><td><font class='ver10'>$totmember</font></td>
		<td><font class='ver10'>$totinfo</font></td><td><font class='ver10'>$totdiskusi</font></td>
		<td align=center><font class='ver10'><a href='admin.php?mode=hapusgroup&kode=$row[idgroup]' title='Hapus group'  ><img src='../images/hapus1.png' border=0  ></a></font></td></tr>";
	}
	echo "</table>";

}

function hapusgroup() {
include("koneksi.php");
	  $kode=$_GET['kode'];
	  if (!empty($kode))
	  {
		$sql="delete from t_membergroup where idgroup='". mysql_escape_string($kode)."' ";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal group");	
		$sql="delete from t_membergroup_info where idgroup='". mysql_escape_string($kode)."' ";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal group info");	
		$sql="delete from t_membergroup_anggota where idgroup='". mysql_escape_string($kode)."' ";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal group");
		if(!$sql2=mysql_query("select * from t_membergroup_diskusi where idgroup='".$kode."' ")) die ("Pengambilan gagal1 member");
		while($row=mysql_fetch_array($sql2)) {
			$sql="delete from t_membergroup_diskusibalas where idtopik='". mysql_escape_string($row[idtopik])."' ";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal group topik balas");	
		}
		$sql="delete from t_membergroup_diskusi where idgroup='". mysql_escape_string($kode)."' ";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal group topik");	
		
	  }
}

 function games_edit() {
  include "koneksi.php";
  echo "<script type='text/javascript' src='../member/js/jquery.js'></script><script type=\"text/javascript\">
$(document).ready(function()
{
$('#jenis').click(function(){

var element = $(this);
var jenis = $('#jenis').val();
if (jenis=='0') {
	$('#filegames').show();
	$('#linkgames').hide();
}
else {
	$('#filegames').hide();
	$('#linkgames').show();
}

return false;});});

</script>";
 $idn=$_GET['id'];
  $query = "SELECT * FROM t_member_games WHERE idgames='". mysql_escape_string($idn)."'"; 
  $result = mysql_query ($query) or die (mysql_error()); 
  $row = mysql_fetch_array($result);
  $ket=$row[kategori];
if ($ket=='') $ket='Olahraga';
if ($ket=='Olahraga') $s1='selected';
elseif ($ket=='Balapan') $s2='selected';
elseif ($ket=='Petualangan') $s3='selected';
elseif ($ket=='Teka-teki') $s4='selected';
elseif ($ket=='Lain-lain') $s5='selected';
if ($row[jenis]=='0') { $sj1='selected';$buka2="style='display:none;'";}
else { $sj2='selected'; $link=$row[link];$buka1="style='display:none;'";}


  echo "<form action='admin.php'  method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Pengisian Games </b><font></td>	</tr>
            <tr> <td width='24%'><font>Nama Games</font></td>
              <td width='76%'> <input type='text' name='judul' size='20' maxlength='50' value='$row[judul]'>
              </td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <input type='text' name='ket' size='20' maxlength='200' value='$row[ket]'>
              </td></tr> 
            <tr><td width='24%'><font>Kategori</font></td>
              <td width='76%'> <select name=kategori >
			  <option value='Olahraga' $s1 >Olahraga</option><option value='Balapan' $s2 >Balapan</option>
<option value='Petualangan' $s3 >Petualangan</option><option value='Teka-teki' $s4 >Teka-teki</option>
<option value='Lain-lain' $s5 >Lain-lain</option></select>
              </td></tr> 
           <tr><td width='24%' valign=top ><font>Jenis Link</font></td>
              <td width='76%'><select name=jenis id='jenis' >
			  <option value='0' $sj1>Upload ke website sendiri</option>
			  <option value='1' $sj2>Link dari website lain</option>
			  </select>
              <div id='filegames' $buka1 ><font>File Games : <input type=\"file\" name=\"swffile\"> Format File  SWF </font></div>
              <div id='linkgames' $buka2 ><font>Link : <br><textarea name='link' id='link' cols=60 rows=10 >$link</textarea> <br>
			  Diisi apabila anda mengambil link dari website lain<br>Masukan scriptnya tanpa frame atau iframe </div>
              </td></tr> 
			  <tr><td width='24%'><font>Gambar Games</font></td>
              <td width='76%'> <input type=\"file\" name=\"imgfile\"><font>Format File JPG 120px x 120px
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"games_save\">
				<input type=\"hidden\" name=\"edit\" value='1'>
                <input type=\"hidden\" name=\"idn\" value=\"$row[idgames]\"><input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
  
 } 

 // perubahan simpan games
 function games_save() {
 $idn=$_POST['idn'];$judul=$_POST['judul'];$ket=$_POST['ket'];$link=$_POST['link'];
 $kategori=$_POST['kategori'];$edit=$_POST['edit'];$jenis=$_POST['jenis'];
 $filegames = $_FILES['swffile'];$linkgames = $_FILES['imgfile'];
 
  include "koneksi.php";
 
 $admin = $_SESSION['Admin']['userid'];
 if ($edit!=1) {
     $sql = "SELECT max(idgames) AS total FROM t_member_games";
     if(!$r = mysql_query($sql)) die("Error connecting to the database.");
     list($total) = mysql_fetch_array($r);
     $total += 1;
    if($filegames['name'] == '' and $jenis=='0') {
		echo "File games swf masih kosong";
    }
	else if ($jenis=='1' and $link=='') {
		echo "Script link games masih kosong";
	}
	else if ($judul=='') {
		echo "Judul games masih kosong";
	}
	else if ($linkgames['name']=='') {
		echo "File gambar games masih kosong";
	}
    else {
	$target_img = "../member/games/gm".$total.".jpg";	
	$target_swf = "../member/games/".$total.".swf";
   		if(@move_uploaded_file($linkgames['tmp_name'], $target_img)) {
			if ($jenis=='0') {
			if(@move_uploaded_file($filegames['tmp_name'], $target_swf)) { 
				echo "File games swf berhasil diupload<br>";$link='';
			}
			}
			
			$sql = "insert into t_member_games (idgames,judul,ket,kategori,jenis,link) values ('$total','". mysql_escape_string($judul)."','". mysql_escape_string($ket)."','". mysql_escape_string($kategori)."','".mysql_escape_string($jenis)."','". mysql_escape_string($link)."')";
  if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  			echo "<font>Penambahan games berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=games'>Lihat Games</a> | <a href='admin.php?mode=games_tam'>Tambah Games</a> |</font>"; 
		}
	}
  
 }
 else {
   // if($filegames['name'] == '' and $jenis=='0') {
	//	echo "File games swf masih kosong";
    //}
	if ($jenis=='1' and $link=='') {
		echo "Script link games masih kosong";
	}
	else if ($judul=='') {
		echo "Judul games masih kosong";
	}
	//else if ($linkgames['name']=='') {
	//	echo "File gambar games masih kosong";
	//}
    //else {
	$target_img = "../member/games/gm".$total.".jpg";	
	$target_swf = "../member/games/".$total.".swf";
   		if(@move_uploaded_file($linkgames['tmp_name'], $target_img)) {
			echo "File gambar games berhasil diupload";
		}
			if ($jenis=='0') {
			if(@move_uploaded_file($filegames['tmp_name'], $target_swf)) { 
				echo "File games swf berhasil diupload<br>";$link='';
			}
			}
			
			$sql = "update t_member_games set judul='". mysql_escape_string($judul)."',ket='". mysql_escape_string($ket)."',kategori='". mysql_escape_string($kategori)."',link='". mysql_escape_string($link)."',jenis='". mysql_escape_string($jenis)."' where idgames='". mysql_escape_string($idn)."'";
  			if(!$alan=mysql_query($sql)) die ("Penyimpanan gagal1");
  			echo "<font>Perubahan games berhasil<br>Silahkan pilih menu kembali !!!<br><br>
  | <a href='admin.php?mode=games'>Lihat Games</a> | <a href='admin.php?mode=games_tam'>Tambah Games</a> |</font>"; 
		
	}
  
  
 } 

 // tambah games
 function games_tam() {
  echo "<script type='text/javascript' src='../member/js/jquery.js'></script><script type=\"text/javascript\">
$(document).ready(function()
{
$('#jenis').click(function(){

var element = $(this);
var jenis = $('#jenis').val();
if (jenis=='0') {
	$('#filegames').show();
	$('#linkgames').hide();
}
else {
	$('#filegames').hide();
	$('#linkgames').show();
}

return false;});});

</script>";
   echo "<form action='admin.php'  method=\"post\" enctype=\"multipart/form-data\">
		 <table border='0' cellpadding='0' cellspacing='6' width='100%'>
            <tr><td colspan='2'><font><b>Penambahan Games </b><font></td>	</tr>
            <tr> <td width='24%'><font>Nama Games</font></td>
              <td width='76%'> <input type='text' name='judul' size='20' maxlength='50' value='$row[judul]'>
              </td></tr>
            <tr><td width='24%'><font>Keterangan</font></td>
              <td width='76%'> <input type='text' name='ket' size='60' maxlength='200' value='$row[ket]'>
              </td></tr> 
            <tr><td width='24%'><font>Kategori</font></td>
              <td width='76%'> <select name=kategori >
			  <option value='Olahraga' $s1 >Olahraga</option><option value='Balapan' $s2 >Balapan</option>
<option value='Petualangan' $s3 >Petualangan</option><option value='Teka-teki' $s4 >Teka-teki</option>
<option value='Lain-lain' $s5 >Lain-lain</option></select>
              </td></tr> 
            <tr><td width='24%' valign=top ><font>Jenis Link</font></td>
              <td width='76%'><select name=jenis id='jenis' >
			  <option value='0' $sj1>Upload ke website sendiri</option>
			  <option value='1' $sj2>Link dari website lain</option>
			  </select>
              <div id='filegames' ><font>File Games : <input type=\"file\" name=\"swffile\"> Format File  SWF </font></div>
              <div id='linkgames' style='display:none;' ><font>Link : <br><textarea name='link' id='link' cols=60 rows=10 ></textarea> <br>
			  Diisi apabila anda mengambil link dari website lain<br>Masukan scriptnya tanpa frame atau iframe </div>
              </td></tr> 
			  <tr><td width='24%'><font>Gambar Games</font></td>
              <td width='76%'> <input type=\"file\" name=\"imgfile\"><font>Format File JPG 120 x 120 px
              </td></tr>
			<tr> <td colspan='2'>
                <input type=\"hidden\" name=\"mode\" value=\"games_save\">
				<input type='reset' value='Ulang' > &nbsp;
                <input type=\"submit\" value=\"Simpan\">
              </td></tr></table></form>";
 } 

 //------------------ lihat games--------------------------
 function games() {
  // ditambah alan untuk seleksi halaman
   include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=20;
  $byk_result1=mysql_query("select * from t_member_games");
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
  		
  $query = "SELECT * from t_member_games order by idgames desc LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data banner di Database <br><a href='admin.php?mode=games_tam'>Tambah Games</a></font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='7' align='center'><font>--- Daftar Games ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font><a href='admin.php?mode=games&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=games&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=games&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Games</center></font></td>
  <td><font><center>Nama Games</center></font></td><td><font><center>Jenis</center></font></td>
  <td><font><center>Visits</center></font></td>
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
  if($row[jenis]=='0') $jenis='Upload ke website sendiri';
  else $jenis='Link dari website lain';
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
    <td width='2%' align='center'><font>$j</font></td>
	<td width='4%' align='center'><img src='../member/games/gm".$row[idgames].".jpg' width=100 height=100  ></td>
    <td width='20%' valign=top ><b>$row[judul]</b><br>$row[ket]</td>
	<td width='10%' valign=top ><font>$jenis</font></td>
	<td width='5%' ><font><center>$row[visit]</center></font></td>"; 
	$j++;
	 ?>
  <td width="5%" align="center"><font><a href="admin.php?mode=games_edit&id=<?php echo $row[idgames]; ?>"><img src="../images/edit.gif" border="0" ></a></td> 
  <td width="5%" align="center"><input type='checkbox' name='id[<?php echo $row[idgames]; ?>]' value='on'> </td>
  </tr>
  <?php
  }  
  echo "</table><br><font ><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua";
  echo "<input type=\"hidden\" name=\"mode\" value=\"games_hap\">
                <input type=\"submit\" value=\"Hapus\"> | <a href='admin.php?mode=games_tam'>Tambah Games</a> |</form><br>";
  
 } 

//hapus games
 function games_hap() {
	include "koneksi.php";
	$id=$_POST['id'];
	  if (!empty($id))
	  {
	  	while (list($key,$value)=each($id))		{
			$sql="select * from t_member_games where idgames='". mysql_escape_string($key)."'";
			if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 games");
			$row=mysql_fetch_array($query);
			$jenis= $row[jenis];
			$sql="delete from t_member_games where idgames='". mysql_escape_string($key)."'";
			$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			$file= "../member/games/gm".$key.".jpg";
			if (file_exists($file)) {
			unlink($file);
			}
			if ($jenis=='0') {
			$file= "../member/games/".$key.".swf";
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
	  }

 } 
 
//**********************************akhir class******************************************//
}
?>