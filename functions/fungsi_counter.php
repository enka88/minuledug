<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
function coun() {

$IPnum = "0.0.0.0"; //Set as a String
$userStatus = 0;

// Get the current IP number ------------------------------
$IPnum = getenv("REMOTE_ADDR");
//Get stored IP's from a file --------------------------------
//Compare it to the ones stored in ip_data.dat ---
$perintah = "SELECT * FROM counter WHERE id=1";
$hasil = mysql_query( $perintah );
while ($data = mysql_fetch_row($hasil)) {
	$IPdata=$data[1];
	$theCount=$data[2];
	$hits=$data[3];
}

$IParray = explode("-",$IPdata); //Make array of IPs
// Start comparing IPs
for($ipCount=0;$ipCount<count($IParray);$ipCount++){
	if($IParray[$ipCount]==$IPnum){$userStatus = 1;}//Been before                                 
}// End for loop

// OK it's a new visitor
// Store the IP number in case they ever come back.
// The counter, give it one.
$IPdata="";
if($userStatus == 0){
		$IPdata="$IPnum-";
		for ($i=0; $i<$maxadmindata; $i++):
			$IPdata .= "$IParray[$i]-";		
		endfor;
		$theCount++;
		$perintah="UPDATE counter SET ip='$IPdata',counter='$theCount' WHERE id=1";
		$hasil = mysql_query( $perintah );
}
$hits++;
$perintah="UPDATE counter SET hits='$hits' WHERE id=1";
$hasil = mysql_query( $perintah );
                                                                       
$coun ="<b>".$theCount."</b>";
return $coun;
}

function User_Online($minutes, $NamaFile){
//$ip = $REMOTE_ADDR;
$ip = getenv("HTTP_X_FORWARDED_FOR");
if (getenv("HTTP_X_FORWARDED_FOR") == ''){
$ip = getenv("REMOTE_ADDR");	
}
$time = time();
//$minutes = 15;
$found = 0;
$users = 0;
$user  = Array();

//$tmpdata = $DOCUMENT_ROOT."/online/data";
$tmpdata = "";

if (!is_file("$NamaFile"))	
	{
	$s = fopen("$NamaFile","w");
	fclose($s);
	chmod("$NamaFile",0666);
	}

$f = fopen("$NamaFile","r+");
flock($f,2);

while (!feof($f))
	{
	$user[] = chop(fgets($f,65536));
	}
fseek($f,0,SEEK_SET);
ftruncate($f,0);
array_pop($user);
foreach ($user as $line){
	list($savedip,$savedtime) = explode("|",$line);
	if ($savedip == $ip) {$savedtime = $time;$found = 1;}
	if ($time < $savedtime + ($minutes * 60)) 
		{
		fputs($f,"$savedip|$savedtime\n");
		$users = $users + 1;
		}
}

if ($found == 0) 
	{
	fputs($f,"$ip|$time\n");
	$users = $users + 1;
	}

fclose ($f);
return "<b>$users</b>";
}

function Hitung_counter(){
    $ROOT = 'mod';	
    if(isset($visitor)) {
        if ($visitor=="visited"){
            $file1 = fopen("counter.txt","r+");
            return fread($file1, filesize("counter.txt"));
            fclose($file1);
        }
    }
    else {
        $files = fopen("counter.txt","r+");
        $nilai = fread($files, filesize("counter.txt"));
        fclose($files);
        $nilai += 1;
        $file = fopen("counter.txt","w+");
        fputs($file, $nilai);
        fclose($file);
        $file4 = fopen("counter.txt","r+");
        return fread($file4, filesize("counter.txt"));
        fclose($file4);
    }
}
?>