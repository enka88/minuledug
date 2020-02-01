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
		return $data['data'];

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
include "../lib/config.php";
	global $excel_file;
	
	if ( !isset ( $_POST['useheads'] ) )
		$_POST['useheads'] = "";
	
	$data = $ws['cell'];
	
$cetakdata .=  '<center><a href="../'.$folderadmin.'/admin.php?mode=siswa">Kembali ke admin</a><table border="1" cellspacing="1" cellpadding="2" align="center" >';

// Form fieldnames
	$cetakdata .= "<tr ><td>NO</td><td>NIS</td><td>Nama</td><td>Kelas</td><td>Tgl Lahir</td>
	<td>Tmp Lahir</td><td>Kelamin</td><td>Agama</td><td>Alamat</td><td>Telp</td><td>Wali</td></tr>";
	foreach( $data as $i => $row ) { // Output data and prepare SQL instructions
		if ($i==0) {

		}
		else {
		
		$cetakdata .= "<tr bgcolor=\"#ffffff\">";
		
		for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
			$cell = get ( $exc, $row[$j] );
			if ($j==4) { 
				$tgl = str_replace(".","", substr($cell,0,2));
				if (strlen($cell)==8) { $bln=str_replace(".","",substr($cell,3,2));$thn=str_replace(".","","19".substr($cell,6,2));}
				else {$bln=str_replace(".","",substr($cell,2,2));$thn=str_replace(".","","19".substr($cell,4,3));}
				if (strlen($tgl)==1) $tgl="0".$tgl;
				if (strlen($bln)==1) $bln="0".$bln;
				$tgllahir ="$tgl/$bln/$thn";
			}
			elseif ($j==1) $nis=$cell;
			elseif ($j==2) $nama=str_replace("'","",$cell);
			elseif ($j==3) $kelas=$cell;
			elseif ($j==5) $tmplahir=$cell;
			elseif ($j==6) $kel=strtoupper($cell);
			elseif ($j==7) $agama=$cell;
			elseif ($j==8) $alamat=str_replace("'","",$cell);
			elseif ($j==9) $telp=$cell;
			elseif ($j==10) $wali=str_replace("'","",$cell);
			else $no=$cell;

		}
		$cetakdata .= "<td>$no</td><td>$nis</td><td>$nama</td><td>$kelas</td><td>$tgllahir</td><td>$tmplahir</td><td>$kel</td><td>$agama</td><td>$alamat</td><td>$telp</td><td>$wali</td>";
			inputdata($nis,$nama,$kelas,$tgllahir,$tmplahir,$kel,$agama,$alamat,$telp,$wali);	
		$cetakdata .= "</tr>";
		}
		$i++;
	}
					
$cetakdata .= "</table><center><a href='../".$folderadmin."/admin.php?mode=siswa'>Kembali ke admin</a>
	</form>
	<br>&nbsp;";

return $cetakdata;
} 

function inputdata($nis,$nama,$kelas,$tgllahir,$tmplahir,$kel,$agama,$alamat,$telp,$wali) {
include "koneksi.php";
	$query = "SELECT user_id FROM t_siswa WHERE user_id='$nis'"; 
	$result = mysql_query ($query) or die (mysql_error()); 
	$r = mysql_num_rows($result);
	if ($r==0) {
	$sql="insert into t_siswa (user_id,nama,kelas,tgl_lahir,tmp_lahir,kelamin,agama,alamat,telp,wali) values('$nis','$nama','$kelas','$tgllahir','$tmplahir','$kel','$agama','$alamat','$telp','$wali')";
	$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
	}
	else {
		$sql="update  t_siswa set nama='$nama',kelas='$kelas',tgl_lahir='$tgllahir',tmp_lahir='$tmplahir',kelamin='$kel',agama='$agama',alamat='$alamat',telp='$telp',
		wali='$wali' where user_id='$nis'";
	$mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
	}
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