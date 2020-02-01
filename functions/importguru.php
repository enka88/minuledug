<?php
session_start();
if (!isset($_SESSION['Admin'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}


include "koneksi.php";

include_once ("exceltableguru.php");
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
		case 5: fatal("This is not an Excel file or file stored in Excel < 5.0");
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


?>