<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['User'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}

$st=$_GET['st'];
if ($set=='') $st=$_POST['st'];

if ($st=='') {
include_once ("exceltable.php");
include_once ("excelparser.php");

echo "<br>";
	
	// Uploading file
	
	$excel_file = $_FILES['excel_file'];
	if( $excel_file )
		$excel_file = $_FILES['excel_file']['tmp_name'];

	if( $excel_file == '' ) fatal("No file uploaded");
	
	move_uploaded_file( $excel_file, 'upload/' . $_FILES['excel_file']['name']);	
	$excel_file = 'upload/' . $_FILES['excel_file']['name'];
	
	$fh = @fopen ($excel_file,'rb');
	if( !$fh ) fatal("No file uploaded");
	if( filesize($excel_file)==0 ) fatal("No file uploaded");

	$fc = fread( $fh, filesize($excel_file) );
	@fclose($fh);
	if( strlen($fc) < filesize($excel_file) )
		fatal("Cannot read file");	

	// Check excel file
	
	$exc = new ExcelFileParser;
	$res = $exc->ParseFromString($fc);
	
	switch ($res) {
		case 0: break;
		case 1: fatal("Can't open file");
		case 2: fatal("File too small to be an Excel file");
		case 3: fatal("Error reading file header");
		case 4: fatal("Error reading file");
		case 5: fatal("This is not an Excel file or file stored in Excel < 5.0".$excel_file);
		case 6: fatal("File corrupted");
		case 7: fatal("No Excel data found in file");
		case 8: fatal("Unsupported file version");

		default:
			fatal("Unknown error");
	}
	// Pricessing worksheets
	
	$ws_number = count($exc->worksheet['name']);
	if( $ws_number < 1 ) fatal("Tidak ada worksheet yang aktif.");
	
	$ws_number = 1; // Setting to process only the first worksheet
	
	for ($ws_n = 0; $ws_n < $ws_number; $ws_n++) {
		
		$ws = $exc -> worksheet['data'][$ws_n]; // Get worksheet data	
		if ( !$exc->worksheet['unicode'][$ws_n] )
			$db_table = $ws_name = $exc -> worksheet['name'][$ws_n];
		else 	{
			$ws_name = uc2html( $exc -> worksheet['name'][$ws_n] );
			$db_table = convertUnicodeString ( $exc -> worksheet['name'][$ws_n] );
			}
		echo "<div align=\"center\">Worksheet: <b>$ws_name</b></div><br>";
		$max_row = $ws['max_row'];
		$max_col = $ws['max_col'];
		if ( $max_row > 0 && $max_col > 0 )
			echo getTableData ( &$ws, &$exc ); // Get structure and data of worksheet
		else fatal("Worksheet kosong");
		
	}
}
else {
include "fungsi_konversiuser.php";
$pel = $_POST['pel'];
$sem = $_POST['sem'];
$nip = $_POST['nip'];
$kd = $_POST['kd'];
$status = $_POST['status'];
$tgl2 = $_POST['tgl2'];
$kelas = $_POST['kelas'];
$ujianke = $_POST['ujianke'];
$skbm = $_POST['skbm'];
$thajar = $_POST['thajar'];	
$nil = $_POST['nil'];
	
	$guru = konversi_guru($nip);
     $sql = "SELECT * FROM t_nilai where mid(kd_nilai,1,4)='".$thajar."' order by kd_nilai desc";
     if(!$q1 = mysql_query($sql)) die("Error connecting to the database.");
	 $row=mysql_fetch_array($q1);
	 $no =substr($row['kd_nilai'],5,6);
	 if ($no=='') $no='000001';
	 else $no = strval($no)+1;
	 
	 if (strlen($no)==1) $no = "00000".$no;
	 elseif (strlen($no)==2) $no = "0000".$no;
     elseif (strlen($no)==3) $no = "000".$no;
     elseif (strlen($no)==4) $no = "00".$no;
     elseif (strlen($no)==5) $no = "0".$no;
     
	$kdnilai = $thajar.$no;
  	$sql2 = "insert into t_nilai (kd_nilai,pelajaran,semester,ujian_ke,status,tgl_ujian,skbm,guru,ket,kd_remedial,kelas) values ('".mysql_real_escape_string($kdnilai)."','".mysql_real_escape_string($pel)."','".mysql_real_escape_string($sem)."','".mysql_real_escape_string($ujianke)."','".mysql_real_escape_string($status)."','$tgl2','".mysql_real_escape_string($skbm)."','".mysql_real_escape_string($guru)."','".mysql_real_escape_string($kd)."','0','".mysql_real_escape_string($kelas)."')";
 	if(!$alan=mysql_query($sql2)) die ("Penyimpanan gagal ");	
	//echo "'$kdnilai','$pel','$sem','$ujianke','$status','$tgl1','$skbm','$guru','$kd','0','$kelas'";
	if (count($nil)>0)
	{
		while (list($key,$value)=each($nil))	{
			if ($value>=$skbm) $tuntas='1';
			else $tuntas='0';
			$sql3="insert into t_nilai_detail (kd_nilai,nis,no_ljk,nilai,tuntas) values ('".mysql_real_escape_string($kdnilai)."','$key','-','$value','$tuntas')";
			$mysql_result=mysql_query($sql3) or die ("Penghapusan gagal 2");
		}
		$tdk = "Penambahan Data Nilai kelas $kelas berhasil dilakukan.";
	}
	//header("Location: ../member/user.php?id=simnilai&kd=".$tdk);
    echo "<script>document.location.href = '../member/user.php?id=simnilai&kd=".$tdk."';</script>";
}

?>