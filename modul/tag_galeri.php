<?php

  $sql="select * from t_galeri order by id DESC limit 0,10";
  $mysql_result=mysql_query($sql);
  $i=1;
  $n=mysql_num_rows($mysql_result);
  while($row=mysql_fetch_array($mysql_result)) {
	$gbr .='["../images/galeri/gb'.$row[id].'.jpg", "", "", "'.$row[ket].'"]';
	if ($i<>$n) $gbr .=",";
	$i++;
  }
   $tag .='
 <table border=0>
<tr> <td height=160 width=196 valign=top>
 ';
$tag .='<script type="text/javascript">
var flashyshow=new flashyslideshow({ //create instance of slideshow
	wrapperid: "myslideshow", //unique ID for this slideshow
	wrapperclass: "flashclass", //desired CSS class for this slideshow
	imagearray: [
		'.$gbr.'
	],
	pause: 3000, //pause between content change (millisec)
	transduration: 1000 //duration of transition (affects only IE users)
})
</script>';
 $tag .='
</td></tr></table>
 ';
?>