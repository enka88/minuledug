<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
include "voting_conf.php";
include "../lib/config.php";

/**************************************************** Voting ************************************/
function lih_voting() {
include "koneksi.php";

$kd = $_GET['kd'];
$sql="select ifnull(count(ip),0),jawaban,pertanyaan from t_voting_pole p,t_voting_jawab j,t_voting_tanya t
	 where t.id_tanya='".mysql_real_escape_string($kd)."' and p.id_jawab=j.id_jawab and j.id_tanya=t.id_tanya group by jawaban,pertanyaan order by pertanyaan, id desc"; 
  	if (!$result=mysql_query($sql)) die ("gagal database");
	$i=0;
  while($myrow=mysql_fetch_array($result)) {
        $question[$i]=$myrow[2];
		$anwser[$i]=$myrow[1];
		$count[$i]=$myrow[0];
		$i++;
  }
  for($n=0;$n<$i;$n++) {
	if (($question[$n]==$question[$n-1])&&($n!=0)) {
		$total[$g]+=$count[$n];		
		}
	else {
		$g++;
		$total[$g]+=$count[$n];		
		}	
	}
  $g=0;
  $lih_voting .= "<center>
	<table width=95% border=0 cellspacing=0  >  
		 <tr> <td align=Center>
		   <table border=0 cellspacing=0  width=100%>";

  for($n=0;$n<$i;$n++) {
	if (($question[$n]==$question[$n-1])&&($n!=0)) {
		if ($total[$g]!=0) {
			$hold=number_format(($count[$n] / $total[$g])*100,0);
			$col=get_color($hold);
			$lih_voting .= "<tr><td align=left width=25%><FONT class='ver10'>$anwser[$n]($count[$n]) </font></td>
			         <td align=left colspan=2 width=100%>
			         <TABLE BORDER=0  align=left CELLSPACING=0 CELLPADDING=0 width=$hold%>
			         <tr><td align=right bgcolor='$col' width=$hold%><FONT class='ver10'>$hold%</font>&nbsp;<td></tr>
			         </table>
			      	  </td> </tr>";
			}
		else {
			$lih_voting .= "<tr><td align=center><FONT class='ver10'>$anwser[$n]($count[$n])</font></td>
			      <td align=center></td>
			      <td align=center><FONT class='ver10'>No results</font></td></tr>";
			}
		}
	else {
		$lih_voting .= "<TR><TD colspan=3><br></TD></TR>";
		$g++;
		if ($total[$g]!=0) {
			$hold=number_format(($count[$n] / $total[$g])*100,0);
			$col=get_color($hold);
			$lih_voting .= "<tr><td colspan=3><FONT class='ver10'><b>$question[$n]</b></font></td>
			</tr><tr><td align=left><FONT class='ver10'>$anwser[$n]($count[$n])</font></td>
			<td align=left colspan=2 width=100%>
			         <TABLE BORDER=0  align=left CELLSPACING=0 CELLPADDING=0 width=$hold%>
			         <tr><td align=right bgcolor=$col width=$hold%><FONT class='ver10'>$hold%</font>&nbsp;<td></tr>
			         </table>
			      	  </td> </tr>";
			}
		else {
			$lih_voting .= "<tr><td align=center>
			<FONT class='ver10'>$anwser[$n]</font></td>
			<td align=center><FONT class='ver10'>$count[$n]</td><td align=center><FONT class='ver10'>No results</font></td></tr>";
			}
		}	
	}
	$lih_voting .= "</table></td></tr></table></center><br><br>";

return $lih_voting;
}

function tam_vot() {

$guest = $_POST['guest'];
$am_id = $_POST['am_id'];
$tgl = date("dmy");
$sql="select * from t_voting_pole p,t_voting_jawab j where j.id_jawab=p.id_jawab and j.id_tanya='".mysql_real_escape_string($am_id)."' and ip='".$_SERVER['REMOTE_ADDR']."' and tanggal='$tgl' ";
$result=mysql_query($sql);	
$myrow=mysql_num_rows($result);
	if (isset($guest)) {
	    if ($myrow > 0 ) {
			$tam_vot .="<br><br><center>Mohon Maaf, Anda telah melakukan Jajak Pendapat sebelumnya.<br><br>
	| <a href='index.php?id=lih_voting&kd=$am_id' style='color:000000;text-decoration:underline'>lihat Hasil</a> |</center></font><br><br><br>";
		}
		else {
			$sql="insert into t_voting_pole (id_jawab,ip,tanggal) values ('".mysql_real_escape_string($guest)."','".$_SERVER['REMOTE_ADDR']."','$tgl')";
			$result=mysql_query($sql);			
			$tam_vot .="<br><br><center>Terima Kasih, Anda telah menggunakan fasilitas Jajak Pendapat.<br><br>
	| <a href='index.php?id=lih_voting&kd=$am_id' style='color:000000;text-decoration:underline'>lihat Hasil</a> |</center></font><br><br><br>";
	    }
	}
return $tam_vot;	
}

?>