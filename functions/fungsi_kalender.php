<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
/************************************************************
	Developer From THAILAND 
	* Modified and Creative by bemler panuwat 2006*
	*Email contact  bemler84@hotmail.com	*

*	PHP Calendar by Scott Richardson, ScanOnline June 2002 	*
*	srichardson@scanonline.com	*
************************************************************/

/*############ฟังก์ชันแสดงปฏิทิน##########################*/

//Example
//$CurrentMonth=date("m/d/Y");
//$pageLink="Example.php";
//$style="big|small" big=Show detail and small=show for fit area

Function DisplayCalendar($CurrentMonth,$pageLink,$style,$width,$height,$host,$user,$pwd,$db)
{



####### config DB #######
$hostLH = $host;     
$userLH =$user;       
$passLH =$pwd;    
$dbLH = $db;   
####### config #######

$bgOtherMonth="#B5CDE5";/*สีพื้นเดือนอื่น*/
$bgToday="#F4E6B4";/*สีวันที่ปัจจุบัน*/
$bgbody="#DAEBE1";/*สีพื้นของปฏิทิน*/
$bghilight1="#B5CDE5";



$bgcolorEvent=array("#AFF5E2","#FF9933","#99FF00","#FFCCFF","#F7F8BA","#CC9900","#E3BDF4","#CED7EC","#33CCFF","#FFFFCC");
$Thaiday=  array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
	//date sent to calendar
	if( $CurrentMonth =="") {		$WorkDate = date("m/d/Y");	}
	else{		$WorkDate = str_replace("-","/",$CurrentMonth);	}


	$PrevMonth = date("m-01-Y", strtotime($WorkDate . "-1 month"));
	$CurrentMonth = date("m-01-Y", strtotime($WorkDate));
	$NextMonth = date("m-01-Y", strtotime($WorkDate . "+1 month"));

$PrevMonth1 = date("m/01/Y", strtotime($WorkDate . "-1 month"));
$NextMonth1 = date("m/01/Y", strtotime($WorkDate . "+1 month"));

	$Today = date("j",strtotime($WorkDate));
	$DaysThisMonth = DaysInMonth(date("m/d/Y",strtotime($WorkDate)));
	$DaysLastMonth = DaysInMonth(date("m/d/Y", strtotime ($PrevMonth1)));
	list($CurrentDate,$InThisMonth) =explode('-', GetStartingPoint($DaysLastMonth,$WorkDate));


$mm=0;$nn=0;/*ค่าเริ่มต้นของวันที่ เริ่มต้นมีกิจกรรม*/
$mlast=true;$mnext=false;/*ค่าเริ่มต้นของเดือนมีกิจกรรม*/
$mlast=($CurrentDate==1) ? false:true;
$InThisMonth=($CurrentDate==1) ? 1:0;


//print  $DaysLastMonth;exit(0);


	$TableCalendar= "<table bgcolor='#2492AB' height='$height' width='$width' border='0' cellpadding=0  cellspacing=1 ><tr>";
$TableCalendar.= "<td colspan='7' height='8%' align='center' >";

if($style=="big")
{$next="<img src=\"../images/bulet4.gif\" border=0>";
$prev="<img src=\"../images/bulet8.gif\" border=0>";}
else
{$next="<img src=\"../images/bulet4.gif\" border=0>";
$prev="<img src=\"../images/bulet8.gif\" border=0>";}

$TableCalendar.= "	<table height='100%' width='100%' border='0'><tr>";
$TableCalendar.= "<td align='left'></td>";
$TableCalendar.= "	<td align='center'><center>";
$TableCalendar.= "	<b><A HREF=\"#\" class=tah11 title='Klik tanggalnya untuk melihat detail Agenda'>".date("d F Y",strtotime($WorkDate))."</A></b></td>";
$TableCalendar.= "	<td align='right'></td></tr></table>";

$TableCalendar.= "	</td></tr><tr bgcolor='#D1E3F8'>";
$TableCalendar.= "	<td height='4%' align='center' valign='top' width='14%' ><center><font class=tah11><b>M</b></font></td>";
$TableCalendar.= "	<td align='center' valign='top' width='14%' ><center><font class=tah11><b>S</b></font></td>";
$TableCalendar.= "	<td align='center' valign='top' width='14%'><center><font class=tah11><b>S</b></font></td>";
$TableCalendar.= "	<td align='center' valign='top' width='14%'><center><font class=tah11><b>R</b></font></td>";
$TableCalendar.= "	<td align='center' valign='top' width='14%' ><center><font class=tah11><b>K</b></font></td>";
$TableCalendar.= "	<td align='center' valign='top' width='14%' ><center><font class=tah11><b>J</b></font></td>";
$TableCalendar.= "	<td align='center' valign='top' width='14%' ><center><font class=tah11><b>S</b></font></td></tr>";

/*########### ($style=="big")#############*/
/*ถ้าเป็นปฏิทินใหญ่*/
if(($style=="big")||($style=="small"))
{$TableCalendar.= "	<tr ><td colspan=7></td></tr>";


/*สำหรับเชื่อมต่อกับฐานข้อมูล*/
mysql_connect($hostLH,$userLH,$passLH) or die("Connection Fail ".mysql_error());
mysql_select_db($dbLH)or die("ขอโทษค่ะติดต่อTable ไม่ได้ค่ะ");
/*สำหรับเชื่อมต่อกับฐานข้อมูล*/

//๓๓๓๓๓๓๓๓๓๓๓๓วันทท้ายสุดของปฏิทิน
if($CurrentDate!=1)
	{$dateEndnextmonth=42-($DaysLastMonth-$CurrentDate+1)-$DaysThisMonth;}
else
	{$dateEndnextmonth=42-$DaysThisMonth;}
//๓๓๓๓๓๓๓๓๓๓๓๓วันทท้ายสุดของปฏิทิน

if( $CurrentMonth =="") 
{//$sql="SELECT * FROM sipakk_calendarevent where (date_start  between  '".date("Y-m")."-01'  and '".date("Y-m")."-".$DaysThisMonth."')";

if($CurrentDate==1){$datetimebegin=strtotime($WorkDate);}else{$datetimebegin=strtotime($WorkDate . "-1 month");}
$sqlNewIdea="SELECT * FROM calendarevent where (date_start  between  '".date("Y-m-",$datetimebegin ).$CurrentDate."'  AND '".date("Y-m-", strtotime($WorkDate . "+1 month")).$dateEndnextmonth."') order by date_start ASC";
if($CurrentDate==1)
{$datebeginCalendar=date("Y-m-", strtotime($WorkDate)).$CurrentDate;/*วันที่เริ่มต้นของปฏิทิน*/}
else{$datebeginCalendar=date("Y-m-", strtotime($WorkDate . "-1 month")).$CurrentDate;/*วันที่เริ่มต้นของปฏิทิน*/}
$dateendCalendar=date("Y-m-", strtotime($WorkDate . "+1 month")).$dateEndnextmonth;/*วันที่lสิ้นสุดของปฏิทิน*/


/*เดือนที่แล้ว*/
$sql_last="SELECT * FROM calendarevent where (date_start  between '".date("Y-m-", strtotime($WorkDate . "-1 month")).$CurrentDate."' AND '".date("Y-m-", strtotime($WorkDate . "-1 month")).$DaysLastMonth."') OR (date_end between '".date("Y-m-", strtotime($WorkDate . "-1 month")).$CurrentDate."' AND '".date("Y-m-", strtotime($WorkDate . "-1 month")).$DaysLastMonth."')";
/*เดือนถัดไป*/
//$dateEndnextmonth1=DateAdd('d',41,strtotime(date($GLOBALS["m/".$CurrentDate."/"."Y"])));
//$dateEndnextmonth=date('d',$dateEndnextmonth1);
/*http://www.phpbuilder.com/columns/akent20000610.php3?page=6*/
$sql_next="SELECT * FROM calendarevent where(date_start  between '".date("Y-m-")."01 ' AND '".date("Y-m-", strtotime($WorkDate . "+1 month")).$dateEndnextmonth."')";

}
	else
{	//$sql="SELECT * FROM calendarevent where (date_start  between  '".date("Y")."-".substr($CurrentMonth,0,2)."-01'  and '".date("Y")."-".substr($CurrentMonth,0,2)."-".$DaysThisMonth."')";

if($CurrentDate==1){$datetimebegin=strtotime($WorkDate);}else{$datetimebegin=strtotime($WorkDate . "-1 month");}
$sqlNewIdea="SELECT * FROM calendarevent where (date_start  between  '".date("Y-m-", $datetimebegin).$CurrentDate."'  AND '".date("Y-m-", strtotime($WorkDate . "+1 month")).$dateEndnextmonth."') order by date_start ASC";
if($CurrentDate==1)
{$datebeginCalendar=date("Y-m-", strtotime($WorkDate)).$CurrentDate;/*วันที่เริ่มต้นของปฏิทิน*/}
else{$datebeginCalendar=date("Y-m-", strtotime($WorkDate . "-1 month")).$CurrentDate;/*วันที่เริ่มต้นของปฏิทิน*/}
$dateendCalendar=date("Y-m-", strtotime($WorkDate . "+1 month")).$dateEndnextmonth;/*วันที่lสิ้นสุดของปฏิทิน*/

/*เดือนที่แล้ว*/
$sql_last="SELECT * FROM calendarevent where (date_start  between '".date("Y-m-", strtotime($WorkDate . "-1 month")).$CurrentDate."' AND '".date("Y-m-", strtotime($WorkDate . "-1 month")).$DaysLastMonth."') OR (date_end  between '".date("Y-m-", strtotime($WorkDate . "-1 month")).$CurrentDate."' AND '".date("Y-m-", strtotime($WorkDate . "-1 month")).$DaysLastMonth."')";
/*เดือนถัดไป*/
//$dateEndnextmonth1=DateAdd('d',41,strtotime(date($GLOBALS["m/".$CurrentDate."/"."Y"])));
//$dateEndnextmonth=strftime('%d',$dateEndnextmonth1);
/*http://www.phpbuilder.com/columns/akent20000610.php3?page=6*/
$sql_next="SELECT * FROM calendarevent where(date_start  between '".date("Y-m-", strtotime($WorkDate . "+1 month"))."01' AND '".date("Y-m-", strtotime($WorkDate . "+1 month")).$dateEndnextmonth."')";
}


//print  $sql_last."-".$sql_next."-".$sqlNewIdea;
//exit(0);


/*คำนวนวันที่ของกิจกรรมภายในเดือนนี้*/
$result=mysql_query($sqlNewIdea) or die(mysql_error()."Error query date".$sqlNewIdea);
$numofRecord=mysql_num_rows($result);
(int) $iii=0;
while($row=mysql_fetch_array($result))
	{ 
$dateEventStart[]=getDatestr($row["date_start"]);
$dateEventEnd[]=getDatestr($row["date_end"]);
/*####แสดงวันที่ใน tooltip######################*/
$dateEventStart1[]= thaidate($row["date_start"]);
$dateEventEnd1[]= thaidate($row["date_end"]);
/*####แสดงวันที่ใน tooltip######################*/
//print strtotime(dbTime2Globaltime($row["date_start"]))."<BR>";
/*DBtime2Globaltime จะ Convert จาก Y-m-d สุ่ ,m/d/Y 2006-01-23 สู่ 01/23/2005*/
/*##############คำนวนในปฏิทิน##################*/
$dateEventStarttime[$iii]=strtotime(dbTime2Globaltime($row["date_start"]));
				if($dateEventStarttime[$iii]<strtotime(dbTime2Globaltime($datebeginCalendar)))
					{$dateEventStarttime[$iii]=strtotime(dbTime2Globaltime($datebeginCalendar));}
$dateEventEndtime[$iii]=strtotime(dbTime2Globaltime($row["date_end"]));
				if($dateEventEndtime[$iii]>strtotime(dbTime2Globaltime($dateendCalendar)))
					{$dateEventEndtime[$iii]=strtotime(dbTime2Globaltime($dateendCalendar));}
/*##############คำนวนในปฏิทิน##################*/

$bghilight[]=$row["color"];
$titleActivities[]=$row["eventTitle"];
$EventDetail[]=substr($row["EventDetail"],0,100)."...";
$EventID[]=$row["id"];
$result1=mysql_query("SELECT * FROM calendarevent_picture WHERE (calendarevent_id='".$row["id"]."')") or die(mysql_error()."Error query date");
if($row1=mysql_fetch_array($result1))
$picsm[]=$row1["picture_sm_name"];
else
$picsm[]="noname.gif";

//print "<BR>$iii  date start ".$row["date_start"]."(".getDatestr($row["date_start"]).") to ".$row["date_end"]."(".getDatestr($row["date_end"]).") DayEvent =".(DateDiff("d",$row["date_start"],$row["date_end"])+1);

$iii++;/*Counter index loop*/
	}


//print "<BR>".$sql.$numofRecord;/*คำนวนวันที่ของกิจกรรมภายในเดือนนี้*/
/*$bb=0; //เปลี่ยน time เป็น date
while($bb<count($dateEventStarttime))
	{print $dateEventStarttime[$bb]."-".strftime("%Y-%m-%d",$dateEventStarttime[$bb])."===".$dateEventEndtime[$bb]."-".strftime("%Y-%m-%d",$dateEventEndtime[$bb])."<BR>";

$bb++;}
*/



if($style=="small")
	{$tbShowStart="<table border=0  cellspacing=0 cellpadding=0 width=100%><tr><td align=\"center\" >";}
else
	{$tbShowStart="<table border=0  cellspacing=0 cellpadding=0 width=100%><tr><td align=\"right\" >";}
	}/*###########จบ ($style=="big")#############*/


	for($x=1;$x<7;$x++){
	$TableCalendar.= "	<tr>";
			for($i=1;$i<8;$i++){
				$fontColor=($i==1) ? "#FF0000":"#000000";/*สีแดงในวันอาทิตย์ ในวันอื่นเป็นสีทั่วๆไป*/

					$TableCalendar.= "		<td align='center' valign='top' height='14%' ";

/*###########ไม่ใช่วันที่ของเดือนนี้#######*/
if($InThisMonth==0)
{

			if($mlast)
				{$xxx=strtotime(date("m/".$CurrentDate."/Y", strtotime($WorkDate."-1 month")));
				$MonthCMP=date("m", strtotime($WorkDate."-1 month"));
				$YearCMP=date("Y", strtotime($WorkDate."-1 month"));
				}
			elseif($mnext)
				{$xxx=strtotime(date("m/".$CurrentDate."/Y", strtotime($WorkDate."+1 month")));
				$MonthCMP=date("m", strtotime($WorkDate."+1 month"));
				$YearCMP=date("Y", strtotime($WorkDate."+1 month"));
				}


			if(count($dateEventStart)>0)
				{
			/*print $xxx."||".date("d/m/Y",$xxx)."-".$CurrentDate."+".date("d-m-Y",$dateEventStarttime[$mm])."*".date("d-m-Y",$dateEventEndtime[$mm])."-->".$mm.$t."<BR>";*/


					if(($xxx>=$dateEventStarttime[$mm])&&($xxx<=$dateEventEndtime[$mm]))
					{
							$n="A $mm";
							$TableCalendar.= "bgcolor='".$bghilight[$mm]."' onMouseOver=\"ddrivetip('Agenda: ".$titleActivities[$mm]."<br> Mulai ".$dateEventStart1[$mm]." Selesai ".$dateEventEnd1[$mm]."','".$bgcolorEvent[$mm]."',200);\" onmouseout=\"hideddrivetip();\"><center>".$tbShowStart."<font size='1'  face=\"MS Sans Serif\" color=\"".$fontColor."\">";
					}else{
							//$n="B $mm";
							$TableCalendar.= "    bgcolor='".$bgOtherMonth."'><center>".$tbShowStart."<font size='1'  color=\"".$fontColor."\">";
					}	

			}else{
					$TableCalendar.= "   bgcolor='".$bgOtherMonth."'>".$tbShowStart."<font size='1'  color=\"".$fontColor."\"><center>";}	
					/* จบ if count($dateEventStart)>0 ตรงนี้*/
					//$TableCalendar.= "			bgcolor='".$bgOtherMonth."'>".$tbShowStart."";
					//$TableCalendar.= "[".$n."]";
					$TableCalendar.=$CurrentDate++;
					
			}//end if/*###########จบ IFไม่ใช่วันที่ของเดือนนี้#######*/

else{		/*ถ้าเป็นวันที่อยู่ในเดือนนี้*/
			$MonthCMP=date("m",strtotime($WorkDate));
			$YearCMP=date("Y",strtotime($WorkDate));
			if( $CurrentDate == date("j") && date("n",strtotime($WorkDate)) == date("n") && date("y",strtotime($WorkDate)) == date("y") )
			{	
				$TableCalendar.= "bgcolor='".$bgToday."' onMouseOver=\"ddrivetip(' <center><B>Sekarang</B></center><br>Hari ".$Thaiday[date("w")]." Tanggal ".thaidate(date("Y/m/d"))."','".$bgcolorEvent[$nn]."',200);\" onmouseout=\"hideddrivetip()\"><center>".$tbShowStart."<font size='1'  face=\"MS Sans Serif\" color=\"".$fontColor."\"><center>";
			
			}	/*END IF $CurrentDate == date("j")*/
				else	/*ถ้าไม่ใช่วันที่ปัจจุบัน*/
			{

					/*๒๒๒๒๒๒๒๒๒ถ้าวันที่ของปฏิทินตรงกับกิจกรรม๒๒๒๒๒๒๒๒๒๒๒*/
					$xxx=strtotime(date("m",strtotime($WorkDate))."/".$CurrentDate."/".date("Y",strtotime($WorkDate)));
					//print $xxx."-".$CurrentDate."-".$dateEventStarttime[$mm]."*".$dateEventEndtime[$mm]."-->".$mm."<BR>";
							if(count($dateEventStart)>0)
							{
									if(($xxx>=$dateEventStarttime[$mm])&&($xxx<=$dateEventEndtime[$mm]))
										{
											$TableCalendar.= "bgcolor='".$bghilight[$mm]."' onMouseOver=\"ddrivetip('Agenda: ".$titleActivities[$mm]."<br> ".$EventDetail[$mm]." <br>Mulai ".$dateEventStart1[$mm]." Selesai ".$dateEventEnd1[$mm]."','".$bgcolorEvent[$mm]."',200);\" onclick=\"detailagen(".$EventID[$mm].")\" onmouseout=\"hideddrivetip();\"><center>".$tbShowStart."<font size='1'  face=\"MS Sans Serif\" color=\"".$fontColor."\"><center>";
										
										}else{
											
											$TableCalendar.= "bgcolor='".$bgbody."'><center>".$tbShowStart."<font size='1'  face=\"MS Sans Serif\" color=\"".$fontColor."\"><center>";
										}	
							}else{
								
								$TableCalendar.= "bgcolor='".$bgbody."'><center>".$tbShowStart."<font size='1'  face=\"MS Sans Serif\" color=\"".$fontColor."\"><center>";
							}	
						/* จบ if count($dateEventStart)>0 ตรงนี้*/
			}/*#########จบ Else ที่ 2/*ถ้าไม่ใช่วันที่ปัจจุบัน*/#####################*/



//$TableCalendar.= "[".$InThisMonth.": $mm]";
$TableCalendar.= $CurrentDate++;
}// end else ใหญ่(เดือนปัจจุบัน)



/*ถ้าเป็นปฏิทินใหญ่*/
if($style=="big")
{
		if(count($dateEventStart)>0)
			{		$cmpDate=strtotime(date($MonthCMP."/".($CurrentDate-1)."/".$YearCMP));
				if($cmpDate==$dateEventStarttime[$mm])
				{	
					$tbShowendTB="</td></tr><tr><td><center><img src=\"/images/".$picsm[$mm]."\" width=110 height=70 style=\"cursor:pointer\" onclick=\"location.replace('CalendarDetail.php?Eventid=".$EventID[$mm]."')\"></td></tr></table>";
				}else{
					$tbShowendTB="</td></tr><tr><td width=110 height=70>&nbsp;</td></tr></table>";
				}
		}else{
				$tbShowendTB="</td></tr><tr><td width=110 height=70>&nbsp;</td></tr></table>";
		}

}else{
		$tbShowendTB="</td></tr></table>";
}



				if($mm>count($dateEventStart)-1)
					{$mm=0;}

				if(($xxx>=$dateEventEndtime[$mm]))
					{$mm=$mm+1;}
				else
					{$mm=$mm;}

$TableCalendar.= "</font>".$tbShowendTB."</td>";

			if( $InThisMonth == 0 && $CurrentDate > $DaysLastMonth ){
				$CurrentDate = 1;
				$InThisMonth = 1;
				$mlast=false;$mnext=false;
			}elseif($InThisMonth == 1 && $CurrentDate > $DaysThisMonth ){
				$CurrentDate = 1;
				$InThisMonth = 0;
				$mlast=false;$mnext=true;
			}		

		}
		$TableCalendar.= "</tr>";
	}

/*###########คำอธิบายสี###########*/
/*for($cnt1=0;$cnt1<=count($titleActivities)-1;$cnt1++)
	{$TableCalendar.= "<tr bgcolor=\"#FFFFFF\"><td bgcolor=\"$bghilight[$cnt1]\"></td><td colspan=6> =".$titleActivities[$cnt1]."</td></tr>";}
$TableCalendar.= "<tr bgcolor=\"#FFFFFF\"><td bgcolor=\"$bgToday\"></td><td colspan=6>=สีของวันที่ปัจจุบัน</td></tr>";
*//*###########คำอธิบายสี###########*/
		$TableCalendar.= "</table>";



//exit(0);



	Return $TableCalendar;

}


/*#####ฟังก์ชันช่วย###*/
	function DaysInMonth($dt) {
		return date("t",strtotime($dt));
	}

	function GetStartingPoint($DLM,$WorkDate){
		$today = getdate(strtotime($WorkDate));
		$mday = $today['mday'];
		$mday-=1;
		$FirstOfMonth = date("m/d/Y",strtotime($WorkDate . "-" . $mday . " days"));

		switch(date("l",strtotime($FirstOfMonth))){
		case "Sunday":
			$CD = 1;
			$InThisMonth = 1;
			break;

		case "Monday":
		$CD = $DLM;
		$InThisMonth = 0;
			break;

		case "Tuesday":
			$CD = $DLM-1;
		$InThisMonth = 0;
			break;

		case "Wednesday":
			$CD = $DLM-2;
		$InThisMonth = 0;
			break;

		case "Thursday":
			$CD = $DLM-3;
		$InThisMonth = 0;
			break;

		case "Friday":
			$CD = $DLM-4;
		$InThisMonth = 0;
			break;

		case "Saturday":
			$CD = $DLM-5;
		$InThisMonth = 0;
		}

		return $CD."-".$InThisMonth;
	}


	function GetThisDate($SelectedDay){
		$today = getdate(strtotime($GLOBALS['WorkDate']));
		$mon = $today['mon'];
		$myear = $today['year'];
		return date("m/d/Y", strtotime($mon."/".$SelectedDay."/".$myear));
	}

/*from  2005-11-05 16:54:47 to 05/11/2005 */
 function getDatestr($date){
	     $dateBegin=substr($date,8,2);
return  (int)$dateBegin;
		}

/*from 05/11/2005 to 2005-11-05 16:54:47*/
function Normaltime2dbTime($dateBegin)
{
$dateBegin=substr($dateBegin,6,4)."-".substr($dateBegin,3,2)."-".substr($dateBegin,0,2)." ".date("G:i:s");
//$dateBegin=substr($dateBegin,6,4)."-".substr($dateBegin,3,2)."-".substr($dateBegin,0,2)." 00:00:00";
return  $dateBegin;
}
/*from  2005-11-05 16:54:47 to 05/11/2005 */
function dbTime2Normaltime($dateBegin)
{$dateBegin=substr($dateBegin,8,2)."/".substr($dateBegin,5,2)."/".substr($dateBegin,0,4);
return  $dateBegin;
}
/*from  2005-11-05 16:54:47 to 05/11/2005 */
function dbTime2Globaltime($dateBegin)
{$dateBegin=substr($dateBegin,5,2)."/".substr($dateBegin,8,2)."/".substr($dateBegin,0,4);
return  $dateBegin;
}
/*$date="2005/07/05";
echo "<br>". substr($date,8,2)."/".substr($date,5,2)."/".substr($date,0,4)."</h1>" ;
echo"<br> Test function send date is".$date;
echo"<br> Return function of send date".$date." is<b>".thaidate($date)."</b>";
*/
 function thaidate($date){
     /*  $date  =    date("Y/m/d");  $date="2005/07/05";*/
	 // 01234567890123456789
	 // 2006-02-20 14:48:57         
	 $day = substr("$date",8,2);
	 $month = substr("$date",5,2);
	 
	 $month = (int)$month - 1;
	 $year = substr("$date",0,4);
	 //$year = $year + 543 ;
	//echo $month;
	$thaimonth = array("Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");  
	$Engmonth = array("January","Febuary","March","April","May","June","July","August","September","October","November","December"); 
	$Intermonth = array("01","02","03","04","05","06","07","08","09","10","11","12"); 
	$month =  $thaimonth[$month];

	return    $day."  ".$month."  ".$year;   
	}

/*#####ฟังก์ชันช่วย###*/
/*############จบฟังก์ชันแสดงปฏิทิน##########################*/



?>