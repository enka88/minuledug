<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}

function gurubelajar() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$hal=$_GET['hal'];
$sem=$_GET['sem'];
$thajar=$_GET['thajar'];
$kelas=$_GET['kelas'];

if ($sem=='') $sem=1;
$nip = konversi_id($userid);
$cetak .= ataslogin("Belajar Online - Guru");
  $brs=20;
  $kol=10;
  
if ($kategori=='Guru') {
  $byk_result=mysql_query("SELECT * FROM t_belajar,t_belajar_kls where t_belajar.idbelajar=t_belajar_kls.idbelajar 
  and t_belajar.sem='".mysql_real_escape_string($sem)."' and t_belajar.nip='".mysql_real_escape_string($nip)."' 
  and t_belajar.thajar='".mysql_real_escape_string($thajar)."' ");
  $byk=mysql_num_rows($byk_result); 
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
  
  if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_belajar,t_belajar_kls where t_belajar.idbelajar=t_belajar_kls.idbelajar 
  and t_belajar.sem='".mysql_real_escape_string($sem)."' and t_belajar.nip='".mysql_real_escape_string($nip)."' 
  and t_belajar.thajar='".mysql_real_escape_string($thajar)."' order by t_belajar.idbelajar DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['thajar']==$thajar) $data4 .=  "<option value='$al[thajar]' selected>$al[thajar]</option>";
  	else $data4 .=  "<option value='$al[thajar]' >$al[thajar]</option>";
  }
  $data4 .= "</select> &nbsp;";	
    
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='gurubelajar'>
  <a href='user.php?id=gurubeltambah' id=button2 >Tambah Belajar</a> ::&nbsp;&nbsp;Th Pelajaran : $data4 &nbsp;&nbsp;Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form>
  <form action='user.php' method='post' name='tugas' >
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=9 ><center><a href='user.php?id=gurubelajar&hal=1&sem=$sem&thajar=$thajar'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurubelajar&hal=$back&sem=$sem&thajar=$thajar'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurubelajar&hal=$i&sem=$sem&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurubelajar&hal=$i&sem=$sem&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurubelajar&hal=$next&sem=$sem&thajar=$thajar'  title='$next'> Next</a> 
  <a href='user.php?id=gurubelajar&hal=$jml&sem=$sem&thajar=$thajar'  title='Page $jml'> Last</a></font></center></td></tr>";
  }

     $cetak .="<tr class='td0' ><td >No</td><td>Pelajaran</td><td>Kelas</td><td>Keterangan</td><td>Status</td>
     <td>Log</td><td>Detail</td><td>Hap</td><td>Edit</td></tr>";
   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;
  $cetak .="<script language='JavaScript'>
function gCheckAll(chk) {
   for (var i=0;i < document.tugas.elements.length;i++) {
     var e = document.tugas.elements[i];
     if (e.type == 'checkbox') {
        e.checked = chk.checked  }
    }
}
</script>";
  while ($row = mysql_fetch_array($query_result_handle))
  {
  	$warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
    
    if($row[status]=='0') $st = 'Sembunyi';
    else $st = "Tampil";
	$isi =strip_tags($row['ket']);
    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>$j</td>
   <td width='10%'>$row[pelajaran]</td>
   <td width='10%'>$row[kelas]</td>
   <td width='30%'>$isi</td>
   <td width='5%'>$st</td>
   <td width='5%' align=center ><a href='user.php?id=gurubellog&kd=$row[idbelajar]&kls=$row[kelas]' title='klik untuk lihat log siswa'><img src='../images/mod.gif'></a></td>
   <td width='3%' align=center ><a href='user.php?id=gurubeldetail&kd=$row[idbelajar]' title='klik untuk detail data'><img src='../images/kirim.gif'></a></td>
   <td width='3%' align=center ><input type='checkbox' name='kls[$row[id]]' value='on'></td>
   <td width='3%' align=center ><a href='user.php?id=gurubeledit&kode=$row[idbelajar]&kls=$row[kelas]' title='klik untuk edit data'><img src='../images/edit.gif'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"id\" value=\"gurubelhapus\"><input type=\"submit\" value=\"Hapus\" id=button2 ></form>";
}
else $cetak .="Mohon maaf anda tidak diperkenankan mengaksesn fasilitas ini";

 $cetak .="</div>";
 
return $cetak;
}

function gurubeltambah() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Tambah Belajar - Guru");

$nip = konversi_id($userid);

if ($kategori=='Guru') {
 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
 
$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
$cetak .="<table><form action='user.php' method='post' > <input type=hidden name='id' value='gurubelsimpan' />
  <tr><td><b>Th Pelajaran</td><td><select name='thajar'>";
  $q=mysql_query("select * from t_thajar ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[thajar]'>$r[thajar]</option>";
  }
  $cetak .= "</select></td></tr>
  <tr><td><b>Sem</td><td>$data3</td></tr>
  <tr><td><b>Pelajaran</td><td><select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='$nip' ");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td></tr>

  <tr><td><b>Tgl Awal</td><td>";
    $m=date("m");
  $d=date("d");
  $y=date("Y");
 $cetak .='<input name="tglawal" type="text" id="tgl"  readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl" border="0" class="calCanvas"></div>';
  $cetak.="</td></tr>";

$cetak .="<tr><td><b>Tgl Akhir</td><td>";
 $cetak .='<input name="tglakhir" type="text" id="tgl2"  readonly />
                <a href="#" id="anctgl2"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br />
                <div id="dtDivtgl2" border="0" class="calCanvas"></div>';
$cetak .="</td></tr>
  <tr><td><b>Deskripsi</b></td><td><input type='text' name='ket' size=60 maxlength='200' /></td></tr>
  <tr><td><b>Status</b></td><td><select name=status ><option value='1' >Tampilkan</option><option value='0' >Sembunyikan</option></select></td></tr>
  <tr><td valign=top ><b>Kelas</td><td>";

    $q=mysql_query("select DISTINCT kelas from t_mengajar where nip='$nip' order by kelas");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "$r[kelas] <input type='checkbox' name='kelas[$r[kelas]]' value='on'><br>";
  }
  $cetak .="</td></tr>
   
    <tr><td><b></td><td><input type='reset' value='Ulang' id=button2 >&nbsp;<input type='submit' id=button2 value=' Simpan ' > <input type=hidden name='st' value='tambah'>
  <input type=hidden name='nip' value='$nip'></td></tr>
  </form></table>";
 }
  $cetak.='<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "yyyy-mm-dd";
dptgl.anchor = "anctgl";
dptgl.initialize();
-->
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl2 = new DatePicker();
dptgl2.id = "tgl2";
dptgl2.month = '.$m.';
dptgl2.year = '.$y.';
dptgl2.canvas = "dtDivtgl2";
dptgl2.format = "yyyy-mm-dd";
dptgl2.anchor = "anctgl2";
dptgl2.initialize();
-->
</script>';
$cetak .="</div>";
return $cetak;
}

function gurubelsimpan() {
    include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];

    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Belajar - Guru");
    
    $kelas  = $_POST['kelas'];
    $sem    = $_POST['sem'];
    $pel    = $_POST['pel'];
    $thajar = $_POST['thajar'];
    $tglawal  = $_POST['tglawal'];
    $tglakhir = $_POST['tglakhir'];
    $status   = $_POST['status'];
    $ket   = $_POST['ket'];
if ($kategori=='Guru') {    
    if (count($kelas) == 0 ) {
        $cetak .= "Kelas belum dipilih. silahkan klik tombol kembali.";
    }
    else {
        $query1 = mysql_query("select max(idbelajar) as mak from t_belajar");
        $row = mysql_fetch_array($query1);
        $total = $row['mak']+1;
        
        $nip = konversi_id($userid);
        
        $tgl1 = ($tglawal=='')?"":",'".mysql_real_escape_string($tglawal)."'";
        $tgl2 = ($tglakhir=='')?"":",'".mysql_real_escape_string($tglakhir)."'";
        $query = "insert into t_belajar (idbelajar,thajar,sem,pelajaran,nip,tglawal,tglakhir,status,ket) 
        values ('".$total."','".mysql_real_escape_string($thajar)."','".mysql_real_escape_string($sem)."','".mysql_real_escape_string($pel)."'
        ,'".mysql_real_escape_string($nip)."' $tgl1 $tgl2 ,'".mysql_real_escape_string($status)."','".mysql_real_escape_string($ket)."')";
        $q = mysql_query($query);
			if (!empty($kelas))
			{
				while (list($key,$value)=each($kelas))		{
					$sql="insert into t_belajar_kls (idbelajar,kelas) values ('".mysql_real_escape_string($total)."',
                    '".mysql_real_escape_string($key)."')";
					$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
				}
			}
        $cetak .= "<script>document.location.href = 'user.php?id=gurubeldetail&kd=$total';</script>";
    }   
 }
else $cetak .="Mohon maaf anda tidak diperkenankan mengaksesn fasilitas ini";  

    return $cetak;
}


function gurubeldetail() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Belajar - Guru");
    $kode = $_GET['kd'];
    
//$nip = konversi_id($userid);
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
$cetak .='<script type="text/javascript">
function hapusteman(userid,kode,nama) {
	if(confirm("Apakah Anda yakin akan memutuskan hubungan dengan "+ nama +" ?")) {
    var dataString = \'userid=\'+ userid +\'&kode=\'+ kode ;
    $.ajax({type: "POST",url: "kontenstatus.php",data: dataString,cache: false,success: function(html){window.location="user.php?id=teman&ket='.$ket.'&keycari='.$keycari.'";}});
	}
}
</script>';
if ($kategori=='Guru') {
  $q=mysql_query("select * from t_belajar where idbelajar='".mysql_real_escape_string($kode)."'");  
  $row = mysql_fetch_array($q) ;
  $sem= $row['sem'];
  $thajar = $row['thajar'];
  $tglawal = date("d-m-Y",strtotime($row['tglawal']));
  $tglakhir = date("d-m-Y",strtotime($row['tglakhir']));
  $ket      = $row['ket'];
    if($row['status']=='0') $st = 'Sembunyi';
    else $st = "Tampil";
  $pel      = $row['pelajaran'];
$cetak .="<table>
  <tr><td><b>Th Pelajaran</td><td width='150' >: $thajar </td><td ><b>Sem</b></td><td>: $sem</td></tr>";
  $cetak .= "<tr><td><b>Pelajaran</td><td>: $pel </td><td><b>Status</b></td><td>: $st</td></tr>";
  $cetak .= "<tr><td><b>Tgl Awal</td><td>: $tglakhir</td><td><b>Tgl Akhir</td><td>: $tglakhir</td></tr>";

  $cetak .="<tr><td valign=top ><b>Deskripsi</b></td><td>: $ket</td></tr>
   <tr><td valign=top ><b>Kelas</b></td><td>";
  $q = mysql_query("select * from t_belajar_kls where idbelajar='".mysql_real_escape_string($kode)."'");
  while($r=mysql_fetch_array($q)) {
    $kls .= $r[kelas].", ";
  }
  $cetak .=": $kls</td></tr></table><hr style='border:dotted 1px;'/>";
  
  $cetak .= "<a href='boxframe.php?id=tamsumber&idbelajar=$kode' rel=\"facebox\" id='button2' >Tambah Sumber Belajar</a><br/><br/>";
  
  $q2 = mysql_query("select count(*) as jum from t_belajar_detail where idbelajar='".mysql_real_escape_string($kode)."'");
  $jum = mysql_fetch_array($q2);
  for($i=1;$i<=$jum['jum'];$i++) {
      
      $q = mysql_query("select * from t_belajar_detail where pertemuan='$i' and idbelajar='".mysql_real_escape_string($kode)."' order by urut ");
      if (mysql_num_rows($q)>0) {
          $cetak .="<table style='border: dotted 1px #666666;' width='100%' cellspacing='3' cellpadding='5' >";
          $cetak .= "<tr><td bgcolor='#b9c7d5' colspan=2 ><b>Pertemuan $i</b></td></tr>";
          while($r=mysql_fetch_array($q)) {
            if ($r['jenis']==2 ) {
                $ico = "<img src='../images/folder.png' align=left />&nbsp;";
                //$materi = html_entity_decode($r['materi']) ;
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("kode=","",$kd[1]);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk download file' >".cekmateriajar($kd2)."</a>";
            }
            elseif ($r['jenis']==3 ) {
                $ico = "<img src='../images/email2.gif' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']);
                if (ceksimpelweb()==false) {
                    $materi = "<a href='".$materi."' target='_blank' title='Klik untuk link ke Ayo Belajar' >".$materi."</a>";
                }
                else {
                    $materi = filtersimpelweb($materi);
                }
            }
            elseif ($r['jenis']==4 ) {
                $ico = "<img src='../images/link.png'  align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk download file' >".$materi."</a>";
            }
            elseif ($r['jenis']==5 ) {
                $ico = "<img src='../images/imgprofil1.png'  align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("kd=","",$kd[1]);
                $materi = "<b><a href='".$materi."' target='_blank' title='Klik untuk melihat detail tugas yang harus dikerjakan' >
                Lihat Tugas :</a></b>".cektugas($kd2)."";
            }
            elseif ($r['jenis']==6 ) {
                $ico = "<img src='../images/group.png' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("kdgroup=","",$kd[1]);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk bergabung dengan group diskusi ini' >
                <b>Group Diskusi</b> - ".cekgroup($kd2)."</a>";
            }
            elseif ($r['jenis']==7 ) {
                $ico = "<img src='../images/kirim.gif' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("idsoal=","",$kd[1]);
                $hasil = cekujian($kd2);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk mengerjakan ujian online' >
                <b>".$hasil['jenis']."</b></a><br/>".$hasil['tgl']."<br/>".$hasil['materi']."";
            }
            else {$ico='';
                $materi = html_entity_decode($r['materi']) ;
            }
            
            if ($r['stshow']=='0') {
                $tampil = "<a href='user.php?id=gurusumberedit&kode=$r[iddetail]&show=1&kd=$kode' title='Tampilkan sumber' ><img src='../images/show.gif' /></a>";
            } 
            else {
                $tampil = "<a href='user.php?id=gurusumberedit&kode=$r[iddetail]&show=0&kd=$kode' title='Sembunyikan sumber' ><img src='../images/hide.gif' /></a>";
            }
            $cetak .= "<tr><td style='border: dotted 1px #666666;' >$ico ".$materi."</td>
            <td valign=top width='60' align=center style='border: dotted 1px #666666;' >
            $tampil
            <a href='boxframe.php?id=editsumber&idsum=$r[iddetail]&jenis=$r[jenis]' rel=\"facebox\" title='Ubah sumber belajar' ><img src='../images/edit1.png' /></a> 
            <a href='user.php?id=gurusumberhapus&kode=$r[iddetail]&kd=$kode' title='Hapus sumber belajar' ><img src='../images/hapus1.png' /></a></td></tr>";
          }
          $cetak .="</table><br/>";
      }
  }
  $cetak .="</table>";
  // akhir if guru
 }

$cetak .="</div>";
    return $cetak;
}

function filtersimpelweb($data ='') {
    
    $simpelweb = "ayobelajar.info";
    
    if($tags = @get_meta_tags($data)) {
    $cetak .= "<b><a href='$data' target='_blank' title='Klik untuk link ke $simpelweb' >Sumber $simpelweb</a></b><br>";
    $cetak .= "Nama Modul : ".$tags['keywords']."<br>";
    $cetak .= "Kontributor &nbsp;&nbsp;&nbsp;: ".$tags['author']."<br>";
    $n = count($tags);
    for($i=1;$i<=$n;$i++) {
        //$cek = "content_".$i;
        if (!empty($tags["content_".$i])) {
            if (empty($tags["file_".$i])) $cetak .= html_entity_decode($tags["content_".$i])."<br/>";
            else {
                $file = html_entity_decode($tags["file_".$i]);
                $cetak .= "<p ><img src='../images/link.png' align=left /> &nbsp;<a href='".$file."' target='_blank' title='Klik untuk mendownload sumber' >".$tags["content_".$i]."</a></p>";
            }
        }
        else {
            $file = html_entity_decode($tags["file_".$i]);
            //$type = str_replace(".","",substr($file,strlen($file)-4,4));
            $type = array_pop(explode(".", $file));
            if ($type=='swf') {
                $cetak .= '<p align=center ><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="450" height="350"  >
          <param name="movie" value="'.$file.'">
          <param name="quality" value="high">
          <param name="wmode" value="transparent">
          <embed src="'.$file.'" wmode="transparent" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="450" height="350"></embed>
		  </object></p>';
            }
            elseif ($type=='jpg') {
                $cetak .= "<p align='center'><img src='".$file."' width='450'  /></p>";
            }
            else {
                $cetak .= "<p ><a href='".$file."' target='_blank' title='Klik untuk mendownload sumber' >".$type."</a></p>";
            }
        }
    }
    }
    else $cetak = "Link file tidak diketemukan.";
    return $cetak;
}

function gurusumberedit() {
    include "koneksi.php";
    $kode = $_GET['kode'];
    $st = $_GET['show'];
    $kd = $_GET['kd'];
    
    $q = mysql_query("update t_belajar_detail set stshow='".mysql_real_escape_string($st)."' where iddetail='".mysql_real_escape_string($kode)."'"); 
    $cetak = "<script>document.location.href = 'user.php?id=gurubeldetail&kd=$kd';</script>";
    return $cetak;
}

function gurusumberhapus() {
    include "koneksi.php";
    $kode = $_GET['kode'];
    $kd   = $_GET['kd'];
    
    $q = mysql_query("delete from t_belajar_detail  where iddetail='".mysql_real_escape_string($kode)."'"); 
    $cetak = "<script>document.location.href = 'user.php?id=gurubeldetail&kd=$kd';</script>";
    return $cetak;
}

function ceksimpelweb() {
    $simpelweb = "ayobelajar.info";
    
    $connect = @fsockopen($simpelweb, 80, $errno, $errstr, 5);
    if(!$connect) {
        return false;
    }
    else return true;
    fclose($connect);      
}

function cekujian($kode = '') {
    include "koneksi.php";
    $q = mysql_query("select jenis,materi,idsoalutama,tgl_mulai,tgl_akhir from soal_utama where idsoalutama='".mysql_real_escape_string($kode)."'"); 
    $r = mysql_fetch_array($q);
    if (!is_null($r['tgl_mulai'])) $tglmulai = " Tgl ".date("d-m-Y",strtotime($r['tgl_mulai']));
    if (!is_null($r['tgl_akhir'])) $tglakhir = " s.d ".date("d-m-Y",strtotime($r['tgl_akhir']));
    $hasil['materi'] = $r['materi'];
        if($r['jenis']==1) $jenis = "Ulangan Harian";
        elseif($r['jenis']==2) $jenis = "Ulangan Blok";
        elseif($r['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($r['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($r['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
    $hasil['jenis'] = $jenis;
    $hasil['tgl'] = $tglmulai.$tglakhir;;
    return $hasil;
}

function cektugas($kode = '') {
    include "koneksi.php";
    $q = mysql_query("select isi,tgl_kumpul from t_tugas where idtugas='".mysql_real_escape_string($kode)."'"); 
    $r = mysql_fetch_array($q);
    return $r['isi']."Tgl terakhir dikumpulkan ".date("d-m-Y",strtotime($r['tgl_kumpul']));
}

function cekmateriajar($kode = '') {
    include "koneksi.php";
    $q = mysql_query("select judul from t_download where id='".mysql_real_escape_string($kode)."'"); 
    $r = mysql_fetch_array($q);
    return $r['judul'];
}

function cekgroup($kode = '') {
    include "koneksi.php";
    
    $kode = unhex($kode,$noacak);
    $q = mysql_query("select nmgroup from t_membergroup where idgroup='".mysql_real_escape_string($kode)."'"); 
    $r = mysql_fetch_array($q);
    return $r['nmgroup'];
}

function gurubelhapus() {
include "koneksi.php";

$kls=$_POST['kls'];

if (!empty($kls)) {
	while (list($key,$value)=each($kls)) {
    	$kd='';
    	$sql1="select idbelajar from t_belajar_kls where id='".mysql_real_escape_string($key)."'";
    	$result=mysql_query($sql1);
    	$row=mysql_fetch_array($result);
    	$kd=$row['idbelajar'];
	
    	$sql="delete from t_belajar_kls where id='".mysql_real_escape_string($key)."'";
    	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
    
    	$sql="select idbelajar from t_belajar_kls where idbelajar='$kd'";
    	$result=mysql_query($sql) or die ("Penghapusan gagal 2");
    	$row =mysql_num_rows($result);
    	if ($row==0) {
    		$sql="delete from t_belajar where idbelajar='".mysql_real_escape_string($kd)."'";
    		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 3");
            $sql="delete from t_belajar_log where idbelajar='".mysql_real_escape_string($kd)."'";
            $mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
    	}

   }
	
 }
    //Header("Location: user.php?id=gurutugas&kd=$row");
    echo "<script>document.location.href = 'user.php?id=gurubelajar';</script>";
	return 0;
} 
 

function gurubeledit() {
include "koneksi.php";
$userid = $_SESSION['User']['userid'];
$kategori = $_SESSION['User']['ket'];
$cetak .="<div id='depan-tengahkanan'>";
$cetak .= statusanda($userid);
$cetak .="<hr style='border: thin solid #6A849D;'>";
$cetak .= ataslogin("Tambah Belajar - Guru");

$nip = konversi_id($userid);
$kode = $_GET['kode'];
$kls = $_GET['kls'];

if ($kategori=='Guru') {
  $q=mysql_query("select * from t_belajar where idbelajar='".mysql_real_escape_string($kode)."'");  
  $row = mysql_fetch_array($q) ;
  $sem= $row[sem];
  $thajar = $row['thajar'];
  $tglawal = date("Y-m-d",strtotime($row['tglawal']));
  $tglakhir = date("Y-m-d",strtotime($row['tglakhir']));
  $ket = $row['ket'];
  if ($row['status']=='1') $st1 = 'selected';
  else $st2= 'selected';
 $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
 
$cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';
$cetak .="<table><form action='user.php' method='post' > <input type=hidden name='id' value='gurubeleditsimpan' />
  <tr><td><b>Th Pelajaran</td><td><select name='thajar'>";
  $q=mysql_query("select * from t_thajar ");
  while($r=mysql_fetch_array($q)) {
    if ($r['thajar']==$thajar) $cetak .= "<option value='$r[thajar]' selected >$r[thajar]</option>";
  	else $cetak .= "<option value='$r[thajar]'>$r[thajar]</option>";
  }
  $cetak .= "</select></td></tr>
  <tr><td><b>Sem</td><td>$data3</td></tr>
  <tr><td><b>Pelajaran</td><td><select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='$nip' ");
  while($r=mysql_fetch_array($q)) {
    if ($r['pel']==$pel ) $cetak .= "<option value='$r[pel]' selected >$r[pel]</option>";
  	else $cetak .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $cetak .= "</select></td></tr>

  <tr><td><b>Tgl Awal</td><td>";
    $m=date("m");
  $d=date("d");
  $y=date("Y");
 $cetak .='<input name="tglawal" type="text" id="tgl" value="'.$tglawal.'"  readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl" border="0" class="calCanvas"></div>';
  $cetak.="</td></tr>";

$cetak .="<tr><td><b>Tgl Akhir</td><td>";
 $cetak .='<input name="tglakhir" type="text" id="tgl2" value="'.$tglakhir.'"   readonly />
                <a href="#" id="anctgl2"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br />
                <div id="dtDivtgl2" border="0" class="calCanvas"></div>';
$cetak .="</td></tr>
  <tr><td><b>Deskripsi</b></td><td><input type='text' value='$ket' name='ket' size=60 maxlength='200' /></td></tr>
  <tr><td><b>Status</b></td><td><select name=status ><option value='1' $st1 >Tampilkan</option><option value='0'$st2 >Sembunyikan</option></select></td></tr>
   <tr><td valign=top ><b>Kelas</td><td>";
  $kls='';
  $q = mysql_query("select * from t_belajar_kls where idbelajar='".mysql_real_escape_string($kode)."'");
  while($r=mysql_fetch_array($q)) {
    if ($kls<>'') $kls.=",";
    $kls .= "'$r[kelas]'";
  }
  $q=mysql_query("select DISTINCT kelas from t_mengajar where nip='$nip' and kelas not in ($kls) order by kelas");
  while($r=mysql_fetch_array($q)) {
  	$cetak .= "$r[kelas] <input type='checkbox' name='kelas[$r[kelas]]' value='on'><br>";
  }
  $cetak .="</td></tr>  
    <tr><td><b></td><td><input type='reset' value='Ulang' id=button2 >&nbsp;<input type='submit' id=button2 value=' Simpan ' > 
    </td></tr><input type=hidden name=kode value='$kode' />
  </form></table>";
 }
  $cetak.='<script language="javascript"><!--
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl = new DatePicker();
dptgl.id = "tgl";
dptgl.month = '.$m.';
dptgl.year = '.$y.';
dptgl.canvas = "dtDivtgl";
dptgl.format = "yyyy-mm-dd";
dptgl.anchor = "anctgl";
dptgl.initialize();
-->
  var currYear = '.$y.';
var currMonth = '.$m.';
var dptgl2 = new DatePicker();
dptgl2.id = "tgl2";
dptgl2.month = '.$m.';
dptgl2.year = '.$y.';
dptgl2.canvas = "dtDivtgl2";
dptgl2.format = "yyyy-mm-dd";
dptgl2.anchor = "anctgl2";
dptgl2.initialize();
-->
</script>';
$cetak .="</div>";
return $cetak;
}

function gurubeleditsimpan() {

    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    //$kategori = $_SESSION['User']['ket'];

    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Belajar - Guru");
    
    $kelas  = $_POST['kelas'];
    $sem    = $_POST['sem'];
    $pel    = $_POST['pel'];
    $thajar = $_POST['thajar'];
    $tglawal  = $_POST['tglawal'];
    $tglakhir = $_POST['tglakhir'];
    $status   = $_POST['status'];
    $ket   = $_POST['ket'];
    $kode   = $_POST['kode'];
        
        $tgl1 = ($tglawal=='')?"": ",tglawal='".mysql_real_escape_string($tglawal)."'";
        $tgl2 = ($tglakhir=='')?"":",tglawal='".mysql_real_escape_string($tglakhir)."'";
        
        $query = "update t_belajar set thajar='".mysql_real_escape_string($thajar)."',sem='".mysql_real_escape_string($sem)."',
        pelajaran='".mysql_real_escape_string($pel)."' $tgl1 $tgl2 , status='".mysql_real_escape_string($status)."',ket='".mysql_real_escape_string($ket)."' where idbelajar='".mysql_real_escape_string($kode)."'";
        $q = mysql_query($query);
			if (!empty($kelas))
			{
				while (list($key,$value)=each($kelas))		{
					$sql="insert into t_belajar_kls (idbelajar,kelas) values ('".mysql_real_escape_string($kode)."',
                    '".mysql_real_escape_string($key)."')";
					$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
				}
			}
        $cetak .= "<script>document.location.href = 'user.php?id=gurubelajar';</script>";
    //}   

    return $cetak;
}

function siswabelajar() {
    
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    //$kategori = $_SESSION['User']['ket'];

    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Belajar Online");
    $hal=$_GET['hal'];
    $sem=$_GET['sem'];
    $thajar=$_GET['thajar'];
    $nis = konversi_id($userid);
    $kelas  = konversi_kls($nis);
  if ($sem=='') $sem=1;
  $brs=20;
  $kol=10;

  $byk_result=mysql_query("SELECT * FROM t_belajar,t_belajar_kls where t_belajar.idbelajar=t_belajar_kls.idbelajar 
  and t_belajar.sem='".mysql_real_escape_string($sem)."' and t_belajar_kls.kelas='".mysql_real_escape_string($kelas)."' 
  and t_belajar.thajar='".mysql_real_escape_string($thajar)."'  and status='1' ");
  
  $byk=mysql_num_rows($byk_result); 
  if ($byk<=$brs)
  	$jml=0;
  else
  {
  	$jml=floor($byk / $brs);
	$sisa= $byk % $brs;
	if ($sisa!=0)
		$jml++;	
  }
  if ($hal=="")
  	$awal=0;
  else
  	$awal=$brs*($hal-1);
  
  if ($hal=="") $hal=1;
  $back=$hal-1;
  $next=$hal+1;
  if ($hal==1) $back=1;
  if ($hal==$jml) $next=$jml;
  $mulai=1;
  $batas=$jml;
  if ($jml>$kol)
  	$batas=$kol;
  
  if ($hal>$kol) {
  $mulai=1+$hal-$kol;
  $batas=$hal;
  }
  
  $query = "SELECT * FROM t_belajar,t_belajar_kls where t_belajar.idbelajar=t_belajar_kls.idbelajar 
  and t_belajar.sem='".mysql_real_escape_string($sem)."' and t_belajar_kls.kelas='".mysql_real_escape_string($kelas)."'
  and t_belajar.thajar='".mysql_real_escape_string($thajar)."' and status='1' order by t_belajar.idbelajar DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 
  $data3 .=  "<select name='sem' >";
  $sql2="select * from t_semester order by semester";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[semester]==$sem) $data3 .=  "<option value='$al[semester]' selected>$al[semester]</option>";
  	else $data3 .=  "<option value='$al[semester]' >$al[semester]</option>";
  }
  $data3 .= "</select> &nbsp;";	
  
  $data4 .=  "<select name='thajar' >";
  $sql2="select * from t_thajar order by idthajar";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['thajar']==$thajar) $data4 .=  "<option value='$al[thajar]' selected>$al[thajar]</option>";
  	else $data4 .=  "<option value='$al[thajar]' >$al[thajar]</option>";
  }
  $data4 .= "</select> &nbsp;";	
    
  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='siswabelajar'>
  ::&nbsp;&nbsp;Th Pelajaran : $data4 &nbsp;&nbsp;Semester :  $data3 <input type=submit value=' Pilih ' id=button2 ></form>";
  if ($jml!=0) {
  $cetak .= "<center><a href='user.php?id=siswabelajar&hal=1&sem=$sem&thajar=$thajar'  title='Hal 1'>First </a> 
  <a href='user.php?id=siswabelajar&hal=$back&sem=$sem&thajar=$thajar'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=siswabelajar&hal=$i&sem=$sem&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=siswabelajar&hal=$i&sem=$sem&thajar=$thajar'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=siswabelajar&hal=$next&sem=$sem&thajar=$thajar'  title='$next'> Next</a> 
  <a href='user.php?id=siswabelajar&hal=$jml&sem=$sem&thajar=$thajar'  title='Page $jml'> Last</a></font></center>";
  }

   if ($hal==1 || $hal=='')
  $j=1;
  else
  	$j=($brs*($hal-1))+1;

  while ($row = mysql_fetch_array($query_result_handle))
  {
    $guru = konversi_guru($row['nip']);
	$isi =strip_tags($row['ket']);
    $cetak .= "<table style='border: dotted 1px #666666;' width='100%' cellspacing='0' cellpadding='5' >
   <tr ><td bgcolor='#dedede' width='70%' ><a href='user.php?id=siswabeldetail&kode=$row[idbelajar]' title='klik untuk melihat materi pembelajaran' ><b>
   <img src='../images/mod.gif' align=left /> &nbsp;&nbsp;$row[pelajaran]</b> - Guru : $guru </a>
   <td width='180' colspan=2 align=right bgcolor='#dedede' ><img src='../images/status.png' align=center /> &nbsp;".date("d M Y",strtotime($row['tglawal']))." s.d ".date("d M Y",strtotime($row['tglakhir']))."</td></tr>
   <tr><td colspan=2 >$isi</td><td width='100' valign='top' align='right'><a href='user.php?id=siswabeldetail&kode=$row[idbelajar]' id=button2 title='klik untuk melihat materi pembelajaran' >Mulai Belajar</a></td></tr></table><br/>";
 }        

    $cetak .="</div>";
    return $cetak;    
}

function siswabeldetail() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    //$kategori = $_SESSION['User']['ket'];
    $kode = $_GET['kode'];
    
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Belajar Online");
    //input log belajar
    $nis = konversi_id($userid);
    $q2 = mysql_query("insert into t_belajar_log values ('".date("Y-m-d h:i:s")."','".$nis."','".$kode."','mulai')");
    
  $q=mysql_query("select * from t_belajar where idbelajar='".mysql_real_escape_string($kode)."'");  
  $row = mysql_fetch_array($q) ;
  $sem= $row['sem'];
  $thajar = $row['thajar'];
  $tglawal = date("d-m-Y",strtotime($row['tglawal']));
  $tglakhir = date("d-m-Y",strtotime($row['tglakhir']));
  $ket      = $row['ket'];
    if($row['status']=='0') $st = 'Sembunyi';
    else $st = "Tampil";
  $pel      = $row['pelajaran'];
$cetak .="<table>
  <tr><td><b>Th Pelajaran</td><td width='150' >: $thajar </td><td ><b>Sem</b></td><td>: $sem</td></tr>";
  $cetak .= "<tr><td><b>Pelajaran</td><td>: $pel </td><td><b>Status</b></td><td>: $st</td></tr>";
  $cetak .= "<tr><td><b>Tgl Awal</td><td>: $tglakhir</td><td><b>Tgl Akhir</td><td>: $tglakhir</td></tr>";

  $cetak .="<tr><td valign=top ><b>Deskripsi</b></td><td>: $ket</td></tr>
   <tr><td valign=top ><b>Kelas</b></td><td>";
  $q = mysql_query("select * from t_belajar_kls where idbelajar='".mysql_real_escape_string($kode)."'");
  while($r=mysql_fetch_array($q)) {
    $kls .= $r[kelas].", ";
  }
  $cetak .=": $kls</td></tr></table><br/>";
  
  $q2 = mysql_query("select count(*) as jum from t_belajar_detail where idbelajar='".mysql_real_escape_string($kode)."'");
  $jum = mysql_fetch_array($q2);
  for($i=1;$i<=$jum['jum'];$i++) {
      
      $q = mysql_query("select * from t_belajar_detail where pertemuan='$i' and idbelajar='".mysql_real_escape_string($kode)."' order by urut ");
      if (mysql_num_rows($q)>0) {
          $cetak .="<table style='border: dotted 1px #666666;' width='100%' cellspacing='3' cellpadding='5' >";
          $cetak .= "<tr><td bgcolor='#b9c7d5'  ><b>Pertemuan $i</b></td></tr>";
          while($r=mysql_fetch_array($q)) {
            if ($r['jenis']==2 ) {
                $ico = "<img src='../images/folder.png' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("kode=","",$kd[1]);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk download file' >".cekmateriajar($kd2)."</a>";
            }
            elseif ($r['jenis']==3 ) {
                $ico = "<img src='../images/email2.gif' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']);
                if (ceksimpelweb()==false) {
                    $materi = "<a href='".$materi."' target='_blank' title='Klik untuk link ke simpelweb' >".$materi."</a>";
                }
                else {
                    $materi = filtersimpelweb($materi);
                }
            }
            elseif ($r['jenis']==4 ) {
                $ico = "<img src='../images/link.png'  align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk download file' >".$materi."</a>";
            }
            elseif ($r['jenis']==5 ) {
                $ico = "<img src='../images/imgprofil1.png'  align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("kd=","",$kd[1]);
                $materi = "<b><a href='".$materi."' target='_blank' title='Klik untuk melihat detail tugas yang harus dikerjakan' >
                Lihat Tugas :</a></b>".cektugas($kd2)."";
            }
            elseif ($r['jenis']==6 ) {
                $ico = "<img src='../images/group.png' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("kdgroup=","",$kd[1]);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk bergabung dengan group diskusi ini' >
                <b>Group Diskusi</b> - ".cekgroup($kd2)."</a>";
            }
            elseif ($r['jenis']==7 ) {
                $ico = "<img src='../images/kirim.gif' align=left />&nbsp;";
                $materi = html_entity_decode($r['materi']) ;
                $kd= explode("&",$materi);
                $kd2 = str_replace("idsoal=","",$kd[1]);
                $hasil = cekujian($kd2);
                $materi = "<a href='".$materi."' target='_blank' title='Klik untuk mengerjakan ujian online' >
                <b>".$hasil['jenis']."</b></a><br/>".$hasil['tgl']."<br/>".$hasil['materi']."";
            }
            else {$ico='';
                $materi = html_entity_decode($r['materi']) ;
            }
            
            if ($r['stshow']=='0') {
                $tampil = "<a href='user.php?id=gurusumberedit&kode=$r[iddetail]&show=1&kd=$kode' title='Tampilkan sumber' ><img src='../images/show.gif' /></a>";
            } 
            else {
                $tampil = "<a href='user.php?id=gurusumberedit&kode=$r[iddetail]&show=0&kd=$kode' title='Sembunyikan sumber' ><img src='../images/hide.gif' /></a>";
            }
            $cetak .= "<tr><td  >$ico ".$materi."</td></tr>";
          }
          $cetak .="</table><br/>";
      }
  }
  $cetak .="</table>";
  
    $cetak .="</div>";
    return $cetak;    
}


function gurubellog() {
    
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    //$kategori = $_SESSION['User']['ket'];

    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Belajar Online");
    $kls=$_GET['kls'];
    $kd=$_GET['kd'];
   $q = mysql_query("select *  from t_belajar where idbelajar='".mysql_real_escape_string($kd)."' ");
   $r = mysql_fetch_array($q);
   $thajar = $r['thajar'];$pel = $r['pelajaran'];$sem = $r['sem'];$ket= $r['ket'];
   
  $query = "SELECT user_id,nama FROM t_siswa where kelas='".mysql_real_escape_string($kls)."' order by nama "; 
  $query_result_handle = mysql_query ($query)  or die (mysql_error()); 
  $i=1;
  $cetak .= "<table><tr><td>Th Pelajaran</td><td>: $thajar</td><td>Semester</td><td>: $sem</td></tr>
  <tr><td>Kelas</td><td>: $kls</td></tr><td>Pelajaran</td><td>: $pel <a href='user.php?id=gurubelajar&thajar=$thajar&sem=$sem' id=button2  >Kembali</a></td></table><br>";
   $cetak .="<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3' >
   <tr class='td0'><td width=10>No</td><td width='80' >NIS</td><td>Nama</td><td width=80>Total akses</td><td width=120>Tgl terakhir akses</td></tr>";
   while ($row = mysql_fetch_array($query_result_handle)) {
  	$warna = "td1";
	if ($x==1) {
	$warna = "td2";
	$x=0; }
	else $x=1;
        $q = mysql_query("select count(nis) as jum, max(tglakses) as tgl  from t_belajar_log where nis='".$row['user_id']."' and idbelajar='".mysql_real_escape_string($kd)."' ");
        $r = mysql_fetch_array($q);
        if (!empty($r['tgl'])) $tgl = date("d-m-Y h:i:s",strtotime($r['tgl']));
        else $tgl='';
        
        $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$warna'\">
        <td>$i</td><td>$row[user_id]</td><td>$row[nama]</td><td><center>$r[jum]</center></td><td>".$tgl."</td></tr>";
        $i++;
   }        
   $cetak .= "</table><br>";
    $cetak .="</div>";
    return $cetak;    
}

?>