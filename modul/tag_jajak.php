<?php
	
		$sql="select distinct t.id_tanya,pertanyaan from ".$dbprefix."voting_tanya t, ".$dbprefix."voting_jawab j where  t.status='1'  and t.id_tanya=j.id_tanya order by t.id_tanya desc ";
		$result=mysql_query($sql);
		$num_rows=mysql_num_rows($result);
		if ($num_rows==0) { $tag .= "No Poles setup.";}
			$p=1;
		while($myrow=mysql_fetch_array($result))
		{
			$am_id=$myrow[id_tanya];
			$am_name=$myrow[pertanyaan];
			$tag .= "<FORM action='index.php' method='post' >
					$am_name
		   			<br>
		     	<table border=0 cellspacing=0 width='100%' >";
				$sql_def="select * from t_voting_jawab where id_tanya=$am_id";
				$result_def=mysql_query($sql_def);
				while($myrow=mysql_fetch_array($result_def))
				{
					$ad_name=$myrow[jawaban];
					$ad_id=$myrow[id_jawab];
					$tag .= "<tr>
						<td>
							<FONT class='ari13' >$ad_name</font>
						</td>
						<TD>
							<input type=hidden name='file' value=$file><input type=hidden name=am_id value=$am_id>
							<input type=hidden name=lt_id value=$lt_id><input type=hidden name=t_id value=$t_id>
							<INPUT TYPE=radio name='guest' value=$ad_id>
						</TD>
					</TR>";
				}
				$tag .= "</table>
						<input  type=hidden name='id' value='tam_vot'> <input class='art-button' type=submit name=Submit value='Pilih'> &nbsp;
						<a href='../html/index.php?id=lih_voting&kd=$am_id'  class='art-button' >Lihat</a>
		    	</form>  	";
		}	
		
?>