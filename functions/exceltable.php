<?php

function print_error( $msg )
	{
		print <<<END
		<tr>
			<td colspan=5><font color=red><b>Error: </b></font>$msg</td>
			<td><font color=red><b>Rejected</b></font></td>
		</tr>

END;
	}

function getHeader( $exc, $data )
{
		// string
	
		$ind = $data['data'];
		if( $exc->sst[unicode][$ind] )
			return convertUnicodeString ($exc->sst['data'][$ind]);
		else
			return $exc->sst['data'][$ind];

}


function convertUnicodeString( $str )
{
	for( $i=0; $i<strlen($str)/2; $i++ )
	{
		$no = $i*2;
		$hi = ord( $str[$no+1] );
		$lo = $str[$no];
		
		if( $hi != 0 )
			continue;
		elseif( ! ctype_alnum( $lo ) )
			continue;
		else
			$result .= $lo;
	}
	
	return $result;
}

function uc2html($str) {
	$ret = '';
	for( $i=0; $i<strlen($str)/2; $i++ ) {
		$charcode = ord($str[$i*2])+256*ord($str[$i*2+1]);
		$ret .= '&#'.$charcode;
	}
	return $ret;
}



function get( $exc, $data )
{
	switch( $data['type'] )
	{
		// string
	case 0:
		$ind = $data['data'];
		if( $exc->sst[unicode][$ind] )
			return uc2html($exc->sst['data'][$ind]);
		else
			return $exc->sst['data'][$ind];

		// integer
	case 1:
		return $data['data'];

		// float
	case 2:
		return $data['data'];
        case 3:
		return $data['data']; //str_replace ( " 00:00:00", "", gmdate("d-m-Y H:i:s",$exc->xls2tstamp($data[data])) );

	default:
		return '';
	}
}



function fatal($msg = '') {
	echo '[Fatal error]';
	if( strlen($msg) > 0 )
		echo ": $msg";
	echo "<br>\nScript terminated<br>\n";
	if( $f_opened) @fclose($fh);
	exit();
}


function getTableData ( $ws, $exc ) {
	global $status,$kd,$nip,$skbm,$ujianke,$sem,$pel,$kelas,$tgl2,$thajar;
	global $excel_file;
	
	if ( !isset ( $_POST['useheads'] ) )
		$_POST['useheads'] = "";
	
	$data = $ws['cell'];
	
$cetakdata .=  '<form action="" method="POST" name="db_export">
	<table border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666">
	<tr bgcolor="#f1f1f1">';

// Form fieldnames
	$cetakdata .= "</tr><tr><td>NO</td><td>NIS</td><td>Nama</td><td>No LJK</td><td>Kelas</td><td>Nilai</td></tr>";
	foreach( $data as $i => $row ) { // Output data and prepare SQL instructions
		if ($i==0) {

		}
		else {
		
		$cetakdata .= "<tr bgcolor=\"#ffffff\">";
		
		for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
			$cell = get ( $exc, $row[$j] );
			if ($j==1) {$cetakdata .= "<td>".substr($cell,1,25)."</td>";$nisiswa=substr($cell,1,25);}
			elseif ($j==5) $cetakdata .= "<td><input type='text' name='nil[".$nisiswa."]' value='".$cell."' size='5' ></td>";
			elseif ($j==4) {$cetakdata .= "<td>$cell</td>";$kelas =$cell;}
			else $cetakdata .= "<td>$cell</td>";		
		}
		
		$cetakdata .= "</tr>";
		}
		$i++;
	}
$pel = $_POST['pel'];
$sem = $_POST['sem'];
$nip = $_POST['nip'];
$kd = $_POST['kd'];
$status = $_POST['status'];
$tgl2 = $_POST['tgl2'];
//$kelas = $_POST['kelas'];
$ujianke = $_POST['ujianke'];
$skbm = $_POST['skbm'];
$thajar = $_POST['thajar'];		
			
$cetakdata .= "</table><input type=hidden name='pel' value='$pel'><input type=hidden name='sem' value='$sem'>
  <input type=hidden name='nip' value='$nip'><input type=hidden name='kd' value='$kd'>
  <input type=hidden name='status' value='$status'><input type=hidden name='tgl2' value='$tgl2'>
  <input type=hidden name='kelas' value='$kelas'><input type=hidden name='ujianke' value='$ujianke'>
  <input type=hidden name='skbm' value='$skbm'><input type=hidden name='st' value='1'>
  <input type=hidden name='thajar' value='$thajar'>
	<center><input type='submit'  value='Simpan'>
	</form>
	<br>&nbsp;";

return $cetakdata;
} 


function prepareTableData ( $exc, $ws, $fieldcheck, $fieldname ) {
	
			
	$data = $ws['cell'];
	
	foreach( $data as $i => $row ) { // Output data and prepare SQL instructions
		
				
		if ( $i == 0 && $_POST['useheaders'] )
			continue;
		
		$SQL[$i] = "";
		
		for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
		
			if ( isset($fieldcheck[$j]) ) {
			
								
				$SQL[$i] .= $fieldname[$j];
				$SQL[$i] .= "=\"";
				$SQL[$i] .= addslashes ( get ( $exc, $row[$j] ) );
				$SQL[$i] .= "\"";
				
				$SQL[$i] .= ",";
			}
		
				
		}
		
		$SQL[$i] = rtrim($SQL[$i], ',');
		
		$i++;
	}
	
	return $SQL;	
			
} 

?>