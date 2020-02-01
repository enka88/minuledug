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
<style>
#hjudul {
	background:#D1DCEB;
}
#tjudul {
	background: #80A9EA url(../images/tablebg.gif) repeat-x top left;
    COLOR: #FFFFFF;
	FONT: 11px Verdana, Tahoma;
	text-align:center;
	height:25px;
}
.row1
{
	BACKGROUND-COLOR: #EDF4FF;
	COLOR: #000000;
	FONT-SIZE: 11px;
	FONT-FAMILY: Verdana, Arial, Helvetica;
}

.row2
{
	BACKGROUND-COLOR: #F7FAFF;
	COLOR: #000000;
	FONT-SIZE: 11px;
	FONT-FAMILY: Verdana, Arial, Helvetica;
}
.row3
{
	BACKGROUND-COLOR: red;
	COLOR: #000000;
	FONT-SIZE: 11px;
	FONT-FAMILY: Verdana, Arial, Helvetica;
}
</style>
<?

$userid   = $_SESSION['User']['userid'];
$namaguru   = $_SESSION['User']['nama'];
$nosoal  = $_GET['no'];
$modul	= $_POST["modul"];
$kelas	= $_POST["kelas"];
echo "<table width=\"80%\" border=\"0\" cellSpacing=\"7\" cellPadding=\"7\" bgColor=\"#ffffff\" align=\"center\" style=\"border: 1px solid #666666\">
	<tr><td>";

echo "<a href='javascript:printWindow()'><img src='../icon/printer.png' width=48 height=48 border=0></a>";
echo "<h3><center>DAFTAR NILAI SISWA $nmsekolah";
echo "<br>Materi : $modul";
echo "<br>Kelas : $kelas</center></h3><br>";
echo '<table width="80%" cellspacing="1" cellpadding="1" align="center" cellspacing=\"1\" cellpadding=\"1\" id=hjudul>';
echo "<tr id=tjudul><td>No</td><td>NIS</td><td>Nama</td><td>Materi</td><td>Nilai</td></tr>";
$no=1;
$rowclass = 'row1';
$qe="select * from hasil where untuk='$modul'and kelas='$kelas' order by nama asc";
$query = mysql_query($qe);

while ($rows=mysql_fetch_array($query)){
if ($rows['nilai']< 72){ $rowclass = 'row3'; }else{ $rowclass = 'row1'; }   
//if ($rowclass == 'row1'){ $rowclass = 'row2'; }else{ $rowclass = 'row1'; }
echo "<tr class=\"" .$rowclass . "\"><td style='text-align:center'>";
   echo"$no";
   $no++;
   echo"</td>";
   echo"<td style=\"text-align:center\">$rows[userid]</td>";
   echo"<td>$rows[nama]</td>";
   echo"<td>$rows[jenis_tes]</td>";	
   echo"<td style=\"text-align:center\">$rows[nilai]</td>";
}

echo "</table>";
echo "<div style=\"float:right;font-size:12px;margin-top:20px;text-align:left;\">Bandung, ";echo date('d-M-Y');
echo "<br>Guru Matepelajaran,";
echo "<br /><br /><br /><br />";
echo $namaguru;
echo"</div>";



echo "<tr><td colspan=\"4\">&nbsp;</td></tr>";				  
echo "</table>";
$no++;
}
echo"</td></tr></table>";

?>