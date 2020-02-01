<?php 
require '../lib/config.php';
include "../functions/koneksi.php";
session_start();
$kate = $_SESSION['User']['ket'];
if ($kate<>'Guru') {
	$pesan = "Maaf anda tidak punya akses ke halaman ini";
	$kembali = "../index.php";
	echo "<script>alert('$pesan');location.href='$kembali'</script>";
	exit;
	
}else{
	$userclass	= $anticrack["id"];
	$classuser	= $anticrack["nama"];

echo '		</td>
		<td width="5px"></td>
        <td valign="top">
		<!-- Tengah -->';
	?>
<script>
function printWindow(){
   bV = parseInt(navigator.appVersion)
   if (bV >= 4) window.print()
}
</script>
<?

$userid   = $_SESSION['User']['userid'];
$nosoal  = $_GET['no'];
$qe="select * from opsi_soal where nosoal='$nosoal' AND userid= '$userid'";
$result = mysql_query($qe);
while ($row = mysql_fetch_array($result))
{
   $guru=$row['nama'];
   $lembaga=$row['lembaga'];
   $alamat=$row['alamat'];
   $untuk=$row['untuk'];
   $materi=$row['materi'];
   $jumlahsoal=$row['jumlahsoal'];
   $disajikan=$row['disajikan'];
   $opsi=$row['opsi'];
   $metode=$row['metode'];
   $waktu=$row['waktu'];
   $password=$row['password'];
}
if ($metode="acak")
{
    $query=mysql_query("select * from soal where nosoal='$nosoal' order by id");
}
else
{
    $query=mysql_query("select * from soal where nosoal='$nosoal' order by id");
}

$jumlah=mysql_num_rows($query);
echo "<table width=\"80%\" border=\"0\" cellSpacing=\"7\" cellPadding=\"7\" bgColor=\"#ffffff\" align=\"center\" style=\"border: 1px solid #666666\">
	<tr><td>";

echo "<a href='javascript:printWindow()'><img src='../icon/printer.png' width=48 height=48 border=0></a>";
echo '<table width="80%" border="0" cellspacing="1" cellpadding="1" align="center">';
     echo "<tr><td>Materi Ujian</td>";
     echo "<td>:<b>$materi</b></td></tr>";
     echo "<tr><td>Jenis Tes</td>";
     echo "<td>:<b>$untuk</b></td>";
     echo "<tr><td>Guru</td>";
     echo "<td>:<b>$guru</b></td>";
echo "</table><hr>";

$no=1;
if($jumlah<>0)
{
/*while ($row = mysql_fetch_array($query))
{
$pertanyaan=$row['pertanyaan'];
echo '<table width="80%" border="0" cellspacing="1" cellpadding="1" align="center">';
          $ary=$row['id'];
          echo "<tr><td valign=\"top\" width=\"25\">";
          echo "<b><p>$no.</p>";
          $no++;
          echo "</b>";
          echo "</td><td>";
          echo "<b>".($pertanyaan)."</b>";
          echo "</td></tr><tr><td></td><td>";
          echo "a. ";
          echo $row['opsia'];
          echo "<br>";
          echo "b. ";
          echo $row['opsib'];
          echo "<br>";
          echo "c. ";
          echo $row['opsic'];
          echo "<br>";
          if ($row[opsid] != "")
          {
              echo "d. ";
             echo $row['opsid'];
             echo "<br>";
			 } else {
			 echo "";
          }
           
	 	 if ($row[opsie] != "")
          {
             echo "e. ";
             echo $row['opsie'];
             echo "<br>";
			} else { echo ""; 
		  }
		  echo "</td></tr><tr><td>";
		  echo "<tr><td class=\"garis\" colspan=\"2\"></td></tr>";
echo "</table>";
}*/
while ($row=mysql_fetch_array($query))
{
$pertanyaan=$row['pertanyaan'];
echo '<table border="0" cellspacing="1" cellpadding="1" style="color:black;font-size:12px;" >';
          $ary=$row['id'];
		  //$jawab="jwb[$ary]";
          echo "<tr>";
          echo "<td valign=\"middle\" width=\"15\"><b>$no.</b></td>";
          echo "<td colspan=\"3\"><b>$pertanyaan</b></td></tr>";
          echo "<tr><td></td>";
          echo"<td width=\"10\">a.</td>";
          echo "<td>$row[opsia]</b></td></tr>";
          echo "<tr><td></td>";
          echo"<td width=\"10\">b.</td>";
          echo "<td>$row[opsib]</b></td></tr>";
          echo "<tr><td></td>";
          echo"<td width=\"10\">c.</td>";
          echo "<td>$row[opsic]</b></td></tr>";
          if ($row[opsid] != "")
          {
          echo "<tr><td></td>";
          echo"<td width=\"10\">d.</td>";
          echo "<td>$row[opsid]</b></td></tr>";
			 } else {
			 echo "";
          }
           
	 	 if ($row[opsie] != "")
          {
          echo "<tr><td></td>";
          echo"<td width=\"10\">e.</td>";
          echo "<td>$row[opsie]</b></td></tr>";
             //echo "<br>";
			} else { echo ""; 
		  }
echo "<tr><td colspan=\"4\">&nbsp;</td></tr>";				  
echo "</table>";
$no++;
}

}
else echo"<center><strong>Belum ada soal-soal untuk modul ini</strong></center>";
}
echo"</td></tr></table>";

?>