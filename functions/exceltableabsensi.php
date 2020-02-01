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
include "koneksi.php";
	global $excel_file;
   $bulan = $_POST['bulan'];
   $tahun = $_POST['tahun'];
	if ( !isset ( $_POST['useheads'] ) )
		$_POST['useheads'] = "";
	
	$data = $ws['cell'];
	
$cetakdata .=  '<center><a href="../'.$folderadmin.'/admin.php?mode=importabsen">Kembali ke admin</a>
<table border="1" cellspacing="1" cellpadding="2" align="center" >';

// Form fieldnames
//	$cetakdata .= "<tr ><td>NO</td><td>NIS</td><td>Nama</td><td>Kelas</td>";
//    for ($i=1;$i<32;$i++) {
//        $cetakdata .= "<td>$i</td>";
//    }
    
    $cetakdata .= "</tr>";
    $col    = array();
	foreach( $data as $i => $row ) { // Output data and prepare SQL instructions
        $status = array();
        
		if ($i==0) {
		    $cetakdata .= "<tr >";  
            for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
                $cell = get ( $exc, $row[$j] );
                if ($j >= 4) $col[$j-4] = $cell;
                $cetakdata .= "<td>".$cell."</td>";
            }
            $cetakdata .= "</tr>";
		}
		else {
		  $view_st=''; 
		  $cetakdata .= "<tr >";
    		for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
    			$cell = get ( $exc, $row[$j] );
    			if ($j==1) $nis = str_replace("'","",$cell);
    			elseif ($j==2) $nama = str_replace("'","",$cell);
    			elseif ($j==3) $kelas = $cell;
    			elseif ($j==0) $no=$cell;
                else {
                  $n = $col[$j-4];
                  $status[$n] = $cell;
                  $view_st .= "<td>".$cell."</td>";  
                }
    		}
		  $cetakdata .= "<td>$no</td><td>$nis</td><td>$nama</td><td>$kelas</td>".$view_st;	
		  $cetakdata .= "</tr>";
          $aa .= inputdata($nis,$status,$col);
		}
		$i++;
	}
					
$cetakdata .= "</table><center><a href='../".$folderadmin."/admin.php?mode=importabsen'>Kembali ke admin</a>
	</form>$aa
	<br>&nbsp;";

return $cetakdata;
} 

function inputdata($nis,$status,$col) {

   $bulan = $_POST['bulan'];
   $tahun = $_POST['tahun'];
   
	$query = "SELECT user_id FROM t_siswa WHERE user_id='".$nis."'"; 
	$result = mysql_query ($query) or die (mysql_error()); 
	$r = mysql_num_rows($result);
	if ($r>0) {
	   $i=0;
	    while($hari = current($col)) {
           $stabsen = $status[$hari];
           $lambat ='';
           if (strtoupper($stabsen)=='S') $stabsen ='S';
           elseif (strtoupper($stabsen)=='A') $stabsen ='A';
           elseif (strtoupper($stabsen)=='I') $stabsen ='I';
           elseif (strtoupper($stabsen)=='') $stabsen ='';
           else {
                $lambat = $stabsen=='T'?'':$stabsen;
                $stabsen ='T';
           }
           
	       $hari = strlen($hari)==1?"0".$hari:$hari;
	       $tgl = $tahun."-".$bulan."-".$hari;
    	   $sql="insert into t_absensi (nis,stabsen,tglabsen,terlambat) values('$nis','".$stabsen."','$tgl','$lambat')";
    	   $mysql_result=mysql_query($sql) or die ("Query failed - Mysql");
           next($col);
        }
	}
    
    return $cetak;
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