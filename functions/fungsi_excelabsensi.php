<?php
include "koneksi.php";

$format=$_POST['format'];
if ($format=='') $format=$_GET['format'];

if ($format=='siswa') {
$file_type = "vnd.ms-excel";
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=FormatAbsensi.xls;");
header("Pragma: no-cache");
header("Expires: 0");
    
    if ($_POST['kelas']=='') {
        $data = "kelas in (select kelas from t_kelas where program='".mysql_real_escape_string($_POST['program'])."')";
    }
    else $data = "kelas='".mysql_real_escape_string($_POST['kelas'])."'";
    
	echo "NO\tNIS\tNAMA\tKELAS";
    for($i=1;$i<32;$i++) {
        echo "\t".$i;
    }
    echo "\n";
	$i=1;
	$sql ="select user_id,nama,kelas from t_siswa where $data order by kelas,nama";
	$result = mysql_query($sql) ;
    while ($row = mysql_fetch_array($result))
    {
		$schema .="$i\t'$row[user_id]\t$row[nama]\t$row[kelas]\t\n";
		$i++;
    }

}
elseif ($format=='semua') {
$file_type = "vnd.ms-excel";
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=Absensi.xls;");
header("Pragma: no-cache");
header("Expires: 0");
  $bln = $_GET['bln'];
  $th  = $_GET['th'];
  $kelas = $_GET['kelas'];
  
  if($bln=='01') $bulan='Januari';
  elseif($bln=='02') $bulan='Februari';
  elseif($bln=='03') $bulan='Maret';
  elseif($bln=='04') $bulan='April';
  elseif($bln=='05') $bulan='Mei';
  elseif($bln=='06') $bulan='Juni';
  elseif($bln=='07') $bulan='Juli';
  elseif($bln=='08') $bulan='Agustus';
  elseif($bln=='09') $bulan='September';
  elseif($bln=='10') $bulan='Oktober';
  elseif($bln=='11') $bulan='November';
  elseif($bln=='12') $bulan='Desember';
  
   $data = "kelas='".mysql_real_escape_string($kelas)."'";
    echo "REKAP ABSENSI \n";
    echo "Kelas : $kelas Bulan : $bulan Tahun : $th \n";
    
	echo "NO\tNIS\tNAMA\tHadir\tSakit\tIzin\tAlpha\tTerlambat\n";
	$i=1;
	$sql ="select user_id,nama,kelas from t_siswa where $data order by kelas,nama";
	$result = mysql_query($sql) ;
    while ($row = mysql_fetch_array($result))
    {
        $hadir=0;$sakit=0;$alpa=0;$izin=0;$lambat=0;
         $query = mysql_query("select count(stabsen) as jum,stabsen from t_absensi where nis='".$row['user_id']."' and month(tglabsen)='$bln' and year(tglabsen)='$th' 
         and stabsen in ('','S','T','A','I') group by stabsen ");
         while($r=mysql_fetch_array($query)) {
            if ($r['stabsen']=='')  $hadir =$r['jum'];
            if ($r['stabsen']=='S') $sakit =$r['jum'];
            if ($r['stabsen']=='A') $alpa  =$r['jum'];
            if ($r['stabsen']=='I') $izin  =$r['jum'];
            if ($r['stabsen']=='T') $lambat=$r['jum'];
    
         }  
		$schema .="$i\t'$row[user_id]\t$row[nama]\t$hadir\t$sakit\t$izin\t$alpa\t$lambat\n";
		$i++;
    }

}
else {
$file_type = "vnd.ms-excel";
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=FormatNilai.xls;");
header("Pragma: no-cache");
header("Expires: 0");


}
	print($schema);

?>