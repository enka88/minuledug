<?php
session_start();
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}
include "../functions/koneksi.php";
include "../functions/fungsi_konversiuser.php";
include "../functions/fungsi_pass.php";
include "../functions/member_layout.php";
include "../functions/member_status.php";
$id= $_GET['id'];
if ($id=='status') {
// status member
$userid = $_SESSION['User']['userid'];
//$userhex = hex($userid,$noacak);
echo '<script type="text/javascript">
//fungsi untuk show dan hide panel komentar
$(document).ready(function()
{
$(".comment_button").click(function(){

var element = $(this);
var I = element.attr("id");

$("#slidepanel"+I).slideToggle(300);
$(this).toggleClass("active"); 

return false;});});

$(document).ready(function()
{
$(".comment2").click(function(){

var element = $(this);
var I = element.attr("id");

$("#komenpanel"+I).slideToggle(300);
$(this).toggleClass("active"); 

return false;});});
</script>
<script type="text/javascript">

// awal pengiriman muti submit komentar
$(document).ready(function()
{
$(".commentkirim").click(function(){

var element = $(this);
var Id = element.attr("id");

var test = $("#textboxcontent"+Id).val();
var userid = $("#userid2").val();
var dataString = \'userid=\'+ userid +\'&textcontent=\'+ test + \'&idstatus=\' + Id;

if(test==\'\')
{
alert("Tuliskan komentarnya");
}
else if(test==\'Tuliskan komentar Anda....\')
{
alert("Tuliskan komentarnya");
}
else
{
$("#flash"+Id).show();
$("#flash"+Id).fadeIn(400).html(\'<img src="css/ajax-loader.gif" align="absmiddle"> loading.....\');

$("#textboxcontent"+Id).val(\'Tuliskan komentar Anda....\');
$.ajax({
type: "POST",
url: "kontenstatus.php",
data: dataString,
cache: false,
success: function(html){
$("#loadplace"+Id).append(html);
$("#flash"+Id).hide();

}
});

}

return false;});});
//akhir multi submit komentar
</script>';

echo '<ul  id="update" class="timeline">';

//khusus untuk member teman saja seleksi belum dilakukan
$sql = mysql_query("SELECT 
 DISTINCT (t_memberstatus.idstatus),
  t_memberstatus.tanggal,
  t_memberstatus.pengirim,
  t_memberstatus.pesan,t_memberstatus.jenis,
  t_member_contact.`status`,
  t_member_contact.id_master,
  t_member_contact.id_con,
  t_memberstatus.userid
FROM
 t_member_contact LEFT OUTER JOIN t_memberstatus ON (t_member_contact.id_con = t_memberstatus.userid) where 
  t_member_contact.`status` = '1' AND 
  t_member_contact.id_master = '".mysql_real_escape_string($userid)."' order by t_memberstatus.tanggal desc limit 0,10");
while($row=mysql_fetch_array($sql))
{
	$msg = statusmember($row[pengirim],$row[jenis],$row[pesan]);
	
	$msgid=$row['idstatus'];
	$nama = member_nama($row[pengirim]);
	
	$gb=fotouser($row[pengirim]);
	
	$selisih = ambilselisih(strtotime($row[tanggal]), time());
	$sql2 = mysql_query("SELECT idstatus FROM t_memberstatus_kom where idstatus='$msgid' order by idstatuskom ");
	$tot = mysql_num_rows($sql2);
	if ($tot <=3 ) { $lihat =""; $batas = 0; }
	else { $lihat = '<a href="#" class="comment2" id="'.$msgid.'" style="color:#6A849D;">Lihat komentar lain ';
		$batas=$tot-3;
		if ($tot <= 13) $lihat .= "(".($tot-3).")</a>";
		else { $lihat .= "</a>"; $batas=10;}	
	}
	echo '<li>'.$gb.'<b>'.$nama.'</b> :: '.$selisih.'<br>'.$msg.'<br>
			<a href="#" class="comment_button" id="'.$msgid.'" style="color:#6A849D;" >Tambah Komentar</a>
			'.$lihat.'
		  </li>';
		  echo"<div class='komen' id=\"komenpanel".$msgid."\">";
		  $n=0;
		  	$byk=10;
			if ($tot >= $byk )  $awal = $tot-$byk;  
			else  $awal =0;
	$sql2 = mysql_query("SELECT * FROM t_memberstatus_kom where idstatus='$msgid' order by idstatuskom limit $awal,$byk");	  
		  while($r=mysql_fetch_array($sql2)) {
		  	if ($n == $batas )   echo "</div><div class='komen2' >"; 
				$nama = member_nama($r[userid]);
				$pesan = $r[pesan];
                
                $x_pesan =$pesan;
                $data_pesan = explode(" ",$x_pesan);
                $x_pesan="";
                for ($i=0; $i<count($data_pesan); $i++){
                
                        if (strlen($data_pesan[$i]) >= 20) {
                            $data_pesan[$i] = wordwrap($data_pesan[$i], 30, " ", TRUE);
                        }
                        $x_pesan .= $data_pesan[$i]." ";
                }
               	$pesan=strip_tags("$x_pesan");
                    
            	$gb=fotouser($r[userid]);
				$selisih = ambilselisih(strtotime($r[tanggal]), time());
				echo "<div id='komen3' >$gb <b>$nama</b> :: $selisih <br>$pesan </div>";
				$n++;
		  }
		  echo"</div>
		  <div  id=\"loadplace".$msgid."\" ></div>";
echo "<div id=\"flash".$msgid."\" class='flash_load'></div>
<div class='panel' id=\"slidepanel".$msgid."\">";
echo '<form action="" method="post" name="'.$msgid.'"><table><tr><td><textarea style="width:400px;height:20px;" id="textboxcontent'.$msgid.'" onfocus="clearText(this)" onblur="clearText(this)" maxlength="255" >Tuliskan komentar Anda....</textarea></td></tr><tr><td style=text-align:right><input type="submit" value="  Kirim  " class="commentkirim" id="'.$msgid.'" /></td></tr></table>
<input type=hidden name="userid2" value="'.hex("komstatus,".$userid,$noacak).'" id="userid2" >
</form>
</div>';

} 

echo "</ul>";

}
elseif ($id=='opini') {

echo "<table width=100% border=0 >";
$sql="select * from t_project order by id desc limit 0,10";
	if(!$alan=mysql_query($sql)) die ("Pengambilan project");
	while ($row=mysql_fetch_array($alan)) {
		if(!$q=mysql_query("select nama from t_member where userid='".$row[userid]."'")) die ("Pengambilan gagal1 member");
		$ro=mysql_fetch_array($q);
		$nama = $ro[nama];
		$isi =strip_tags($row[deskripsi]);
		$max = 300; // maximal 300 karakter 
		$min = 200; // minimal 150 karakter 
		if( strlen( $isi ) > $max ) { 
			$pecah = substr( $isi, 0, $max ); 
			$akhirParagrap = strrpos( $pecah, "\n" ); 
			$akhirKalimat = strrpos( $pecah, '.' ); 
			$akhirSubKalimat = strrpos( $pecah, ',' ); 
			$spasiTerakhir = strrpos( $pecah, ' ' ); 
 
			if( $akhirParagrap >= $min ) { 
    			$potong = $akhirParagrap; 
			}elseif( $akhirKalimat >= $min ) { 
   				$potong = $akhirKalimat; 
			}elseif( $akhirSubKalimat >= $min ) { 
   				$potong = $akhirSubKalimat; 
			}else { 
   				$potong = $spasiTerakhir; 
			} 
 
			$isi = substr( $isi, 0, $potong+1 )."..."; 
 		}
		$baru="";
		$tgl=strtotime($row[tanggal]);
		$tgl= date('j M Y - H:i',$tgl);
		if($row[status]=='0') $baru ="<img src='../images/baru.gif'>(No Valid)";
		$file = "../images/project/p$row[id].jpg";
		$gb="";
		if (file_exists(''.$file.'')) {
	        $gb="<a href='user.php?id=lihopini&kdopini=".hex($row[id],$noacak)."&kode=".hex($row[userid],$noacak)."'  title='Dibaca $row[visit] kali'><img src='$file' width='80' height='80' align=right id=gambar ></a>";
		}
		echo "<tr><td>$gb<font class='ver10'>$penulis <b><a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."'  title='Lihat Profil'><img src='../images/user.png' align='left' height='14' >&nbsp;&nbsp;$nama</a></b>,&nbsp;$tgl $baru<br><b>
		<a href='user.php?id=lihopini&kdopini=".hex($row[id],$noacak)."&kode=".hex($row[userid],$noacak)."' class='ver10' title='Dibaca $row[visit] kali'>$row[judul]</a></b>,
		$isi</font></td></tr>
		<tr><td height=2 background='../images/gris_user.gif'></td></tr>";
	}
	echo "</table>";
}
elseif ($id=='forum') {
echo "<table width=100% border=0 >";	
	$sql="select * from t_forum_isi,t_forum_balas where t_forum_isi.isi_id=t_forum_balas.isi_id order by t_forum_balas.balas_id desc limit 0,10";
	if(!$alan=mysql_query($sql)) die ("Pengambilan project");
	while ($row=mysql_fetch_array($alan)) {	
		$tgl=strtotime($row[isi_tgl]);
		$tgl= date('j M Y - H:i',$tgl);
		$isi =strip_tags($row[isi_body]);
		$isi  =  substr($isi, 0, 100)."...";
		$r = mysql_query("SELECT nama,userid FROM t_member where userid='$row[userid]'") or die("Query failed");
		$ro = mysql_fetch_array($r);
		$nama =$ro[nama];
		echo "<tr><td>Topik : <b><a href='user.php?id=lihatbalasan&kdtopik=$row[isi_id]&kdforum=$row[forum_id]'  title='Lihat Forum'>$row[isi_judul]</a></b>
		<br><img src='../images/user.png' align='left' height='14' >&nbsp;
		<b><a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."' class='ver10' title='Lihat profil'>$nama</a></b>, $tgl <br>$isi <br><i><div align=right ><a href='user.php?id=lihatbalasan&kdtopik=$row[isi_id]&kdforum=$row[forum_id]' title='Lihat Forum'>Selengkapnya</a></div></i></td></tr>
		<tr><td height=2 background='../images/gris_user.gif'></td></tr>";
	}
echo "</table>";
}
elseif ($id=='member') {

echo "<table width=100% border=0 height=100% id='tablebaru' ><tr><td width=40% class='td0'><b>Member</b></td><td width=8% class='td0'><center><b>YM</b></center></td><td width=15% class='td0'><center><b>Point</b></center></td><td class='td0' width=35%><b><center>Login Terakhir</center></b></td></tr>"; // total point
$r=1;
  	$sql="select tgl_login,status,point,userid,email,nama from t_member where status='1' order by point desc limit 0,10";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal member");
	while($row=mysql_fetch_array($query)) {
		$warna = "td1";
	if ($j==1) {
	$warna = "td2";
	$j=0; }
	else $j=1;
	$email=$row[email];
	$lan= strlen($email);
	for($m=1;$m<$lan;$m++) {
		if (substr($email,$m,1)=="@") {break;}
	}
	$ymid=substr($email,0,$m);
	$selisih = ambilselisih(strtotime($row[tgl_login]), time());
	echo "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"><td >$r. <a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."' title='Lihat profil'>$row[nama]</a></td><td><center><a href='ymsgr:sendIM?$ymid' title='Chatting by YM'><img src='http://opi.yahoo.com/online?u=$ymid&m=g&t=0' border='0' alt='$ymid' /></a></center></td>
	<td><center>$row[point]</center></td><td><center>".$selisih." </center></td></tr>";
	$r++;
	}
echo "</table><br>";
}

?>
