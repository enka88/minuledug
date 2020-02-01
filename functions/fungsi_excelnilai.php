<?php
include "koneksi.php";

$format=$_POST['format'];
if ($format=='') $format=$_GET['format'];

if ($format=='siswa') {
$file_type = "vnd.ms-excel";
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=FormatSiswa.xls;");
header("Pragma: no-cache");
header("Expires: 0");

	echo "NO\tNIS\tNAMA\tKELAS\tTGLLAHIR\tTMPLAHIR\tKELAMIN\tAGAMA\tALAMAT\tTELP\tWALI\n";
}
elseif($format=='guru') {
$file_type = "vnd.ms-excel";
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=FormatGuru.xls;");
header("Pragma: no-cache");
header("Expires: 0");

	echo "NO\tNIP\tNAMA\tKELAMIN\tTGLLAHIR\tTMPLAHIR\tALAMAT\tTELP\tHP\tTUGAS\tEMAIL\tPELAJARAN\tPANGKAT\tSTATUS\n";
}
elseif ($format=='nilai') {
    session_start();
    include "../functions/fungsi_konversiuser.php";
    $userid = $_SESSION['User']['userid'];
    $kd = $_GET['kd'];
    $nip = konversi_id($userid);
    
    $file_type = "vnd.ms-excel";
    header("Content-Type: application/$file_type");

    if (!empty($kd)) {

    	$sql ="select * from t_nilai where kd_nilai='".$kd."'";
    	$result = mysql_query ($sql) ;
        if($data = mysql_fetch_array($result)) {
            $pel = $data['pelajaran'];
            $sem = $data['semester'];
            $kelas = $data['kelas'];
            $ket  = $data['ket'];
            $kkm  = $data['skbm'];
            $nama = konversi_guru($nip);
            $thajar = "20".substr($data['kd_nilai'],0,2)."/20".substr($data['kd_nilai'],2,2);
        }
        header("Content-Disposition: attachment; filename=Nilai-".$kelas.".xls;");
        header("Pragma: no-cache");
        header("Expires: 0");        
        echo "Daftar Nilai Siswa\nPelajaran : $pel\t\t\tGuru : $nama\n";
        echo "Thn Pelajaran : $thajar\t\t\tKelas : $kelas\n";
        echo "KKM : $kkm\t\t\tMateri : $ket\n\n";
    	echo "NO\tNIS\tNAMA\tKELAS\tNILAI\n";
    	$i=1;
    	$sql ="select * from t_nilai,t_nilai_detail where t_nilai.kd_nilai=t_nilai_detail.kd_nilai and t_nilai.kd_nilai='".$kd."'";
    	$result = mysql_query ($sql) ;
        while ($row = mysql_fetch_array($result))
        {
            $query = mysql_query("select nama from t_siswa where user_id='".$row['NIS']."'");
            $r = mysql_fetch_array($query);
    		$schema .="$i\t'$row[NIS]\t$r[nama]\t$row[kelas]\t$row[nilai]\n";
    		$i++;
        }
    }   
}
elseif ($format=='semuanilai') {
    session_start();
    include "../functions/fungsi_konversiuser.php";
    $userid = $_SESSION['User']['userid'];
    $sem = $_GET['sem'];
    $thpel = $_GET['thpel'];
    $pel = $_GET['pel'];
    $kelas = $_GET['kelas'];
    
    $nip = konversi_id($userid);
    
    $file_type = "vnd.ms-excel";
    header("Content-Type: application/$file_type");
    
    	$sql ="select * from t_nilai where guru='".$nip."' and semester='".$sem."' and kelas='".$kelas."' and pelajaran='".$pel."' and left(kd_nilai,4)='".$thpel."'";
    	$result = mysql_query ($sql) ;
        if($data = mysql_fetch_array($result)) {
            $pel = $data['pelajaran'];
            $sem = $data['semester'];
            $kelas = $data['kelas'];
            $ket  = $data['ket'];
            $kkm  = $data['skbm'];
            $nama = konversi_guru($nip);
            $thajar = "20".substr($data['kd_nilai'],0,2)."/20".substr($data['kd_nilai'],2,2);
        }
        header("Content-Disposition: attachment; filename=Nilai-".$kelas.".xls;");
        header("Pragma: no-cache");
        header("Expires: 0");        
        echo "Daftar Nilai Siswa\nPelajaran : $pel\t\t\tGuru : $nama\n";
        echo "Thn Pelajaran : $thajar\t\t\tKelas : $kelas\n\n";
    	echo "NO\tNIS\tNAMA\tKELAS\tKKM\tNILAI\tKeterangan\n";
    	$i=1;
    	$sql ="select * from t_nilai,t_nilai_detail where t_nilai.kd_nilai=t_nilai_detail.kd_nilai and guru='".$nip."' and semester='".$sem."' and kelas='".$kelas."' and pelajaran='".$pel."' and left(t_nilai.kd_nilai,4)='".$thpel."'";
    	$result = mysql_query ($sql) ;
        while ($row = mysql_fetch_array($result))
        {
            $query = mysql_query("select nama from t_siswa where user_id='".$row['NIS']."'");
            $r = mysql_fetch_array($query);
    		$schema .="$i\t'$row[NIS]\t$r[nama]\t$row[kelas]\t$row[skbm]\t$row[nilai]\t$row[ket]\n";
    		$i++;
        }
  
}
else {
$file_type = "vnd.ms-excel";
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=FormatNilai.xls;");
header("Pragma: no-cache");
header("Expires: 0");

	echo "NO\tNIS\tNAMA\tNO_LJK\tKELAS\tNILAI\n";
	$i=1;
	$sql ="select * from t_siswa where kelas='".mysql_real_escape_string($kelas)."' order by nama";
	$result = mysql_query ($sql) ;
    while ($row = mysql_fetch_array($result))
    {
		$schema .="$i\t'$row[user_id]\t$row[nama]\t$row[no_ljk]\t$row[kelas]\t\n";
		$i++;
    }
}
	print($schema);

?>