<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}  

$ip      = $_SERVER['REMOTE_ADDR']; // Mendapatkan IP komputer user
$browser = $_SERVER['HTTP_USER_AGENT'];
if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') )
{
   $browser = 'Safari';
}
else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') )
{
   if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') )
   {
     $browser = 'Netscape (Gecko/Netscape)';
   }
   else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') )
   {
     $browser = 'Mozilla Firefox';
   }
   else
   {
     $browser = 'Gecko Mozilla';
   }
}
else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') )
{
   $browser = 'Internet Explorer';
}
else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') === true)
{
   $browser = 'Opera';
}
else
{
   $browser = 'Opera Mini';
}
$proxy   = $_SERVER['HTTP_X_FORWARDED_FOR'];
if ($HTTP_X_FORWARDED_FOR)
{
  $proxy = "  . $HTTP_VIA . ";
}
else
{
  $proxy = " - ";
}

$tanggal = date("Ymd"); // Mendapatkan tanggal sekarang
$waktu   = time(); // 
// Mencek berdasarkan IPnya, apakah user sudah pernah mengakses hari ini 
$s = mysql_query("SELECT * FROM t_statistik WHERE ip='$ip' AND tanggal='$tanggal'");
              // Kalau belum ada, simpan data user tersebut ke database
if(mysql_num_rows($s) == 0){
    mysql_query("INSERT INTO t_statistik(ip, tanggal, hits, online) VALUES('$ip','$tanggal','1','$waktu')");
    } 
    else{
    mysql_query("UPDATE t_statistik SET hits=hits+1, online='$waktu' WHERE ip='$ip' AND tanggal='$tanggal'");
    }

    $pengunjung       = mysql_num_rows(mysql_query("SELECT * FROM t_statistik WHERE tanggal='$tanggal' GROUP BY ip"));
    $totalpengunjung  = mysql_result(mysql_query("SELECT COUNT(hits) FROM t_statistik"), 0); 
    $hits             = mysql_fetch_assoc(mysql_query("SELECT SUM(hits) as hitstoday FROM t_statistik WHERE tanggal='$tanggal' GROUP BY tanggal")); 
    $totalhits        = mysql_result(mysql_query("SELECT SUM(hits) FROM t_statistik"), 0); 
    $tothitsgbr       = mysql_result(mysql_query("SELECT SUM(hits) FROM t_statistik"), 0); 
    $bataswaktu       = time() - 300;
    //$pengunjungonline = mysql_num_rows(mysql_query("SELECT * FROM t_statistik WHERE online > '$bataswaktu'"));
    $pengunjungonline = mysql_result(mysql_query("SELECT SUM(stlogin) FROM t_member"),0);

    $tag .= "<br />
        <table>
        <tr><td class='news-title'><img src='../images/9.gif' > Total Hits </td><td class='news-title'> : $totalhits </td></tr>
        <tr><td class='news-title'><img src='../images/9.gif' > Pengunjung </td><td class='news-title'> : $totalpengunjung </td></tr>
        <tr><td class='news-title'><img src='../images/8.gif' > Hari ini </td><td class='news-title'> : $pengunjung </td></tr>
        <tr><td class='news-title'><img src='../images/9.gif' > Hits hari ini </td><td class='news-title'> : $hits[hitstoday] </td></tr>
        <tr><td class='news-title'><img src='../images/10.gif' > Member Online </td><td class='news-title'> : $pengunjungonline </td></tr>
        <tr><td class='news-title'><img src='../images/10.gif' > IP</td><td class='news-title'> : $ip</td></tr>
        <tr><td class='news-title'><img src='../images/10.gif' > Proxy</td><td class='news-title'> : $proxy</td></tr>
        <tr><td class='news-title'><img src='../images/10.gif' > Browser</td><td class='news-title'> : $browser</td></tr>
        </table>";

$tag .="<center><b>:: Kontak Admin ::</b><br><br>";
$tag  .='<a href="ymsgr:sendIM?'.$email1.'"><img src="http://opi.yahoo.com/online?u='.$email1.'&m=g&t=1" border="0" width="64" height="16" alt="'.$email1.'" /></a> &nbsp;&nbsp;&nbsp;';
$tag  .='<a href="ymsgr:sendIM?'.$eemai2.'"><img src="http://opi.yahoo.com/online?u='.$email2.'&m=g&t=1" border="0" width="64" height="16" alt="'.$email2.'" /></a></center>';

?>