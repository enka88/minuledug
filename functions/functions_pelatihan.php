<?php
if(!defined("Balitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
/********************************************* Informasi **********************************************/
class infoclass {
    
// buku tamu
  function pelatihan() {
   include "koneksi.php";
  $hal=$_GET['hal'];
  $brs=30;
  $byk_result1=mysql_query("select * from acara");
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
  		
  $query = "SELECT * FROM acara order by id DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  // check that there is news
    $num_of_rows = mysql_num_rows ($query_result_handle) 
    or die (error("<font>Admin tidak menemukan data Buku Tamu di Database!</font>"));
  // tambah alan untuk delete multiple	
  echo "<form action='admin.php' method=\"post\">";
  echo "<table cellspacing='0' cellpadding='5' border='1' bordercolor='#000000'>
  <tr><td bgcolor='#999999' colspan='7' align='center'><font >--- Daftar Pelatihan ---</font></td></tr>";
  if ($jml!=0) {
  echo "<tr><td colspan='7'><center><font><a href='admin.php?mode=pelatihan&hal=1' title='Hal 1'>awal </a> |"; 
  for($i=1;$i<=$jml;$i++)
  {
  	echo "<a href='admin.php?mode=pelatihan&hal=$i' title='Hal $i dari $byk Data'> $i </a> |";		
  }
  echo "<a href='admin.php?mode=pelatihan&hal=$jml' title='Hal $jml'> akhir</a> </font></center></td></tr>";
  }
  echo "<tr><td><font><center>No</center></font></td><td><font><center>Acara</center></font></td><td><font><center>Tgl Awal</center></font>
  </td><td><font><center>Tgl Akhir</center></font></td><td><font><center>Lokasi</center></font></td><td><font><center>Pendaftar</center></font></td>
  <td><font><center>Edit</center></font></td><td><font><center>Hapus</center></font></td></tr>";
  echo "<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.forms[0].elements.length;i++) {
     var e = document.forms[0].elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
$i=1;
  while ($row = mysql_fetch_array($query_result_handle))
  {
  echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\"> 
  <td width='5%' valign=top ><font>$i</font></td>
    <td width='30%' valign=top ><font>$row[acara]</font></td>
    <td width='10%' valign=top  ><font>".date("d-m-Y",strtotime($row[tglawal]))."</font></td>
    <td width='10%' valign=top ><font>".date("d-m-Y",strtotime($row[tglakhir]))."</font></td>
    <td width='20%' valign=top ><font>$lokasi</font></td>
    <td width='10%'  valign=top ><font><a href='admin.php?mode=pendaftar&idn=$row[idacara]' title='klik untuk lihat pendaftar'>$total</a></font></td>
	<td width='10%' valign=top ><center><font><a href='admin.php?mode=edit_pelatihan&idn=$row[idacara]' title='klik untuk edit'><img src='../images/edit.gif' border=0></a></font></td>"; 
	 ?>
  <td width="4%" align="center" valign=top ><input type='checkbox' name='idacara[<?php echo $row[idacara]; ?>]' value='on'> </td>
  </tr>
  <?php
   $i++;
  }  
  echo "</table><br><font > <input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua ";
  echo "<input type=\"hidden\" name=\"mode\" value=\"hapus_pelatihan\">
                <input type=\"submit\" value=\"Hapus\"></form>";
  
  }
  
 function pelatihan_jawab() {
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
 
  function pelatihan_save() {
include "koneksi.php";
   $idn=$_POST['idn'];$jawab=$_POST['jawab'];$pesan=$_POST['pesan'];
   $headers  = "From: \"Komunitas $nmsekolah\" <$webmail>\r\n"; 
   $headers .= "Content-type: text/html\r\n";
   
     	 $sql="select * from t_buku where id_buku='". mysql_escape_string($idn)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 buku");
	$row=mysql_fetch_array($query);
	$email = $row['email'];
	$message = "Pertanyaan : <br>".$pesan."<br>Jawaban :<br>".$jawab;
    if(!@mail($email, "www.kajianwebsite.org :: Jawaban Buku Tamu kajianwebsite.org ::", $message, $headers)) {
        $pesan_mail .="Gagal kirim email<br>";
    }
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


}


?>