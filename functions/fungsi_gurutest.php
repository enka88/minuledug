<?php

/**
 * @author alanrm82
 * @copyright 2011
 */

function gurutest() {
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
$cetak .= ataslogin("Tes Online - Guru");
  $brs=20;
  $kol=10;
  
  $byk_result=mysql_query("SELECT * FROM soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama 
  and sem='".mysql_real_escape_string($sem)."' and nip='".mysql_real_escape_string($nip)."' 
  and thajar='".mysql_real_escape_string($thajar)."' and kelas='".mysql_real_escape_string($kelas)."'");
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
  
  $query = "SELECT * FROM soal_utama,soal_kelas where soal_utama.idsoalutama=soal_kelas.idsoalutama 
  and sem='".mysql_real_escape_string($sem)."' and nip='".mysql_real_escape_string($nip)."' 
  and thajar='".mysql_real_escape_string($thajar)."' and kelas='".mysql_real_escape_string($kelas)."' order by soal_utama.idsoalutama DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $data2 .=  "<select name='kelas' >";
  $sql2="select DISTINCT kelas from t_mengajar where nip='".mysql_real_escape_string($nip)."' order by kelas";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al[kelas]==$kelas) $data2 .=  "<option value='$al[kelas]' selected>$al[kelas]</option>";
  	else $data2 .=  "<option value='$al[kelas]' >$al[kelas]</option>";
  }
  $data2 .= "</select> &nbsp;";	
  
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
  <input type=hidden name=id value='gurutest'>
  <a href='user.php?id=gurutesttam' id=button2 >Tambah Tes Online</a> <a href='user.php?id=gurubanksoal' id=button2 >Bank Soal</a> <br/>
  <br/>::&nbsp;&nbsp;Kelas : $data2 &nbsp;&nbsp;Th Pelajaran : $data4 &nbsp;&nbsp;Semester :  $data3 
  <input type=submit value=' Pilih ' id=button2 ></form>
  <form action='user.php' method='post' name='tugas' >
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=9 ><center><a href='user.php?id=gurutest&hal=1&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurutest&hal=$back&sem=$sem&thajar=$thajar&kelas=$kelas'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurutest&hal=$i&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurutest&hal=$i&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurutest&hal=$next&sem=$sem&thajar=$thajar&kelas=$kelas'  title='$next'> Next</a> 
  <a href='user.php?id=gurutest&hal=$jml&sem=$sem&thajar=$thajar&kelas=$kelas'  title='Page $jml'> Last</a></font></center></td></tr>";
  }

     $cetak .="<tr class='td0' ><td >No</td><td>Pelajaran</td><td>Materi</td><td>Jenis</td>
     <td>Jml Soal</td><td>Detail</td><td>Hap</td><td>Edit</td></tr>";
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
    
    if ($row['jenis']==1 ) $jenis = 'Harian';
    elseif ($row['jenis']==2 ) $jenis = 'Blok';
    elseif ($row['jenis']==3 ) $jenis = 'MID';
    elseif ($row['jenis']==4 ) $jenis = 'UAS';
    elseif ($row['jenis']==5 ) $jenis = 'Latihan';
    else $jenis = 'Remedial';
    
    $q = mysql_query("select count(*) as jum from soal_test where idsoalutama='".$row['idsoalutama']."'");
    if($r = mysql_fetch_array($q)) $jum = $r['jum'];
    else $jum=0;
    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>$j</td>
   <td width='10%'>$row[pel]</td>
   <td width='30%'>$row[materi]</td>
   <td width='10%'>$jenis</td>
   <td width='5%' align=center >$jum</td>
   <td width='3%' align=center ><a href='user.php?id=gurutestdetail&kode=$row[idsoalutama]' title='klik untuk detail data'><img src='../images/kirim.gif'></a></td>
   <td width='3%' align=center ><input type='checkbox' name='kls[$row[idsoalutama]]' value='on'></td>
   <td width='3%' align=center ><a href='user.php?id=gurutesttam&kode=$row[idsoalutama]' title='klik untuk edit data'><img src='../images/edit.gif'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"id\" value=\"gurutesthapus\"><input type=\"submit\" value=\"Hapus\" id=button2 ></form>";


 $cetak .="</div>";
 
return $cetak;
}

function gurubanksoal() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Bank Soal - Guru");
    $hal=$_GET['hal'];
    $pel=$_GET['pel'];
    $jenis = $_GET['jenis'];
    $nip = konversi_id($userid);
      if ($jenis==1) {$s2='selected';$filter = "( status='1')";}
      else {$s1='selected';  $filter = "(nip='".mysql_real_escape_string($nip)."' and status='2') ";}    
      
    
  $brs=20;
  $kol=10;
  
  $byk_result=mysql_query("SELECT pertanyaan FROM soal_opsi where $filter
  and pelajaran='".mysql_real_escape_string($pel)."'");
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
  
  $query = "SELECT * FROM soal_opsi where $filter
  and pelajaran='".mysql_real_escape_string($pel)."' order by idsoal DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $data1 = "<select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q)) {
        if ($pel==$r[pel]) $data1 .= "<option value='$r[pel]' selected >$r[pel]</option>";
  	     else $data1 .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $data1 .= "</select>";

  $cetak .= "<form action='user.php?' method='get' name='guru'>
  <input type=hidden name=id value='gurubanksoal'> <a href='user.php?id=gurutest' id=button2 >Tes Online</a>
  <a href='user.php?id=gurusoaltam' id=button2 >Tambah Soal</a> <br/><br/>::&nbsp;&nbsp;Pelajaran : $data1 
   &nbsp;Sumber Soal : <select name='jenis' ><option value='2' $s1 >Hanya untuk pribadi</option><option value='1' $s2 >Terbuka untuk guru lain</option></select>
  <input type=submit value=' Pilih ' id=button2 ></form>
  <form action='user.php' method='post' name='tugas' >
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=5 ><center><a href='user.php?id=gurubanksoal&hal=1&pel=$pel&jenis=$jenis'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurubanksoal&hal=$back&pel=$pel&jenis=$jenis'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurubanksoal&hal=$i&pel=$pel&jenis=$jenis'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurubanksoal&hal=$i&pel=$pel&jenis=$jenis'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurubanksoal&hal=$next&pel=$pel&jenis=$jenis'  title='$next'> Next</a> 
  <a href='user.php?id=gurubanksoal&hal=$jml&pel=$pel&jenis=$jenis'  title='Page $jml'> Last</a></font></center></td></tr>";
  }

     $cetak .="<tr class='td0' ><td >No</td><td>Pertanyaan</td><td>Kesulitan</td><td>Hap</td><td>Edit</td></tr>";
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
    $tanya = strip_tags($row['pertanyaan']);
    if ($row['tingkat']=='2') $tingkat = 'Sedang';
    elseif ($row['tingkat']=='2') $tingkat = 'Sukar';
    else $tingkat = 'Mudah';

    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='5%'>$j</td>
   <td width='40%'>$tanya</td>
   <td width='10%'>$tingkat</td>
   <td width='3%' align=center ><input type='checkbox' name='kode[$row[idsoal]]' value='on'></td>
   <td width='3%' align=center ><a href='user.php?id=gurusoaltam&kode=$row[idsoal]' title='klik untuk edit data'><img src='../images/edit.gif'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"id\" value=\"gurusoalhapus\"><input type=\"submit\" value=\"Hapus\" id=button2 ></form>";


    $cetak .="</div>";
 
return $cetak;
}

function gurusoaltam() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Bank Soal - Guru");
    $nip = konversi_id($userid);
    
    $kode = $_GET['kode'];
    if(!empty($kode)) {
        $q=mysql_query("select * from soal_opsi where idsoal='".mysql_real_escape_string($kode)."' ");
        if($r=mysql_fetch_array($q)) {
            if($r['tingkat']=='2') $t2='selected';
            elseif($r['tingkat']=='3') $t3='selected';
            else $t1='selected';
            if($r['status']=='1') $s1 = 'selected';
            else $s2 ='selected';
            $pel = $r['pelajaran'];
            
            $pertanyaan = $r['pertanyaan'];
            $opsia = $r['opsia'];$opsib = $r['opsib'];$opsic = $r['opsic'];$opsid = $r['opsid'];
            $jawaban = $r['jawaban'];
            $bahas = $r['pembahasan'];
            $_SESSION['save'] = 'edit';
        }
    }
    else $_SESSION['save'] = 'add';
    
  $data1 = "<select name='pelajaran'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q)) {
        if ($pel==$r['pel']) $data1 .= "<option value='$r[pel]' selected >$r[pel]</option>";
  	     else $data1 .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $data1 .= "</select>";
  
  include "functions_editor.php";
  $cetak .= editor_full();
  
    $cetak .= "<form action='user.php' method='post' ><table border=0 >";
    $cetak .= "<tr><td width='30%'>Pelajaran</td><td>: $data1</td></tr>";
    $cetak .= "<tr><td>Tingkat Kesulitan</td><td>: <select name='tingkat' ><option value='1' $t1 >Mudah</option>
    <option value='2' $t2 >Sedang</option><option value='3' $t3 >Sukar</option></select></td></tr>";
    $cetak .= "<tr><td>Status</td><td>: <select name='status' ><option value='1' $s1 >Terbuka untuk guru lain</option>
    <option value='2' $s2 >Hanya untuk pribadi</option></select></td></tr>";
    $cetak .= "<tr><td >Pertanyaan</td><td><textarea name='pertanyaan' cols=20 rows=10 >".$pertanyaan."</textarea></td></tr>";
    $cetak .= "<tr><td >Opsi yang Benar</td><td><textarea name='jawaban' cols=20 rows=10 >".$jawaban."</textarea></td></tr>";
    $cetak .= "<tr><td >Opsi lain</td><td><textarea name='opsia' cols=20 rows=10 >".$opsia."</textarea></td></tr>";
    $cetak .= "<tr><td >Opsi lain</td><td><textarea name='opsib' cols=20 rows=10 >".$opsib."</textarea></td></tr>";
    $cetak .= "<tr><td >Opsi lain</td><td><textarea name='opsic' cols=20 rows=10 >".$opsic."</textarea></td></tr>";
    $cetak .= "<tr><td >Opsi lain</td><td><textarea name='opsid' cols=20 rows=10 >".$opsid."</textarea></td></tr>";
    $cetak .= "<tr><td >Pembahasan</td><td><textarea name='bahas' cols=20 rows=10 >".$bahas."</textarea></td></tr>";
    $cetak .= "<tr><td ></td><td><input type='submit' value='Simpan' id=button2 /> </td></tr>";
    $cetak .= "<input type=hidden name='id' value='gurusoalsave' /><input type=hidden name='kode' value='$kode' />";
    $cetak .="</table></div>";
 
return $cetak;    
}

function gurusoalsave() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Bank Soal - Guru");
    $nip = konversi_id($userid);

    $pertanyaan  = (stripslashes($_POST['pertanyaan']));
    $opsia       = (stripslashes($_POST['opsia']));
    $opsib       = (stripslashes($_POST['opsib']));
    $opsic       = (stripslashes($_POST['opsic']));
    $opsid       = (stripslashes($_POST['opsid']));
    $jawaban     = (stripslashes($_POST['jawaban']));
    $bahas       = (stripslashes($_POST['bahas']));

    if ($_SESSION['save']=='add') {
        $q = mysql_query("insert into soal_opsi (pelajaran,nip,status,tingkat,pertanyaan,opsia,opsib,opsic,opsid,pembahasan,jawaban) 
        values ('".mysql_real_escape_string($_POST['pelajaran'])."','".mysql_real_escape_string($nip)."',
        '".mysql_real_escape_string($_POST['status'])."','".mysql_real_escape_string($_POST['tingkat'])."',
        '".$pertanyaan."','".$opsia."','".$opsib."','".$opsic."','".$opsid."','".$bahas."',
        '".$jawaban."')");
        $cetak .= "Penambahan berhasil dilakukan<br><br>
        <a href='user.php?id=gurubanksoal' id=button2 title='Lihat data bank soal' >Bank Soal</a> &nbsp
        <a href='user.php?id=gurusoaltam' id=button2 title='Tambah Soal' >Tambah Soal</a>";
    }
    elseif($_SESSION['save']=='edit') {
        $q = mysql_query("update soal_opsi 
        set pelajaran='".mysql_real_escape_string($_POST['pelajaran'])."',
        status='".mysql_real_escape_string($_POST['status'])."',tingkat='".mysql_real_escape_string($_POST['tingkat'])."',
        pertanyaan='".$pertanyaan."',opsia='".$opsia."',opsib='".$opsib."',opsic='".$opsic."',opsid='".$opsid."',
        pembahasan='".$bahas."',jawaban='".$jawaban."' where idsoal='".mysql_real_escape_string($_POST['kode'])."'");
        $cetak .= "Perubahan berhasil dilakukan<br><br>
        <a href='user.php?id=gurubanksoal' id=button2 title='Lihat data bank soal' >Bank Soal</a> &nbsp
        <a href='user.php?id=gurusoaltam' id=button2 title='Tambah Soal' >Tambah Soal</a>";
    }
    else {
        $cetak .= "<script>document.location.href = 'user.php?id=gurubanksoal';</script>";
    }
    $_SESSION['save']='';
     
    $cetak .="</div>";
 
return $cetak;         
}


function gurusoalhapus() {
include "koneksi.php";

$id=$_POST['kode'];

if (!empty($id)) {
	while (list($key,$value)=each($id)) {

    	$sql="delete from soal_opsi where idsoal='".mysql_real_escape_string($key)."'";
    	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
     	$sql="delete from soal_test where idsoal='".mysql_real_escape_string($key)."'";
    	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");   

   }
	
 }
    echo "<script>document.location.href = 'user.php?id=gurubanksoal';</script>";
	return 0;
} 
// batas bang soal

function gurutesttam() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Tes Online - Guru");
    $nip = konversi_id($userid);    
  $kode = $_GET['kode'];
    if(!empty($kode)) {
        $q=mysql_query("select * from soal_utama where idsoalutama='".mysql_real_escape_string($kode)."' ");
        if($r=mysql_fetch_array($q)) {
            
            if($r['jenis']=='2') $t2 = 'selected';
            elseif($r['jenis']=='3') $t3 = 'selected';
            elseif($r['jenis']=='4') $t4 = 'selected';
            elseif($r['jenis']=='5') $t5 = 'selected';
            elseif($r['jenis']=='6') $t6 = 'selected';
            else $s1 ='selected';
            
            if($r['metode']=='2') $s2='selected';
            else $s1='selected';
            
            $pel        = $r['pel'];
            $sem        = $r['sem'];
            $thajar     = $r['thajar '];
            $waktu      = $r['waktu'];

            $jmltampil  = $r['jml_tampil'];
            $materi     = $r['materi'];
            $password   = $r['psswd_soal'];
            $kesempatan = $r['kesempatan'];
            $tglmulai   = date("Y-m-d",strtotime($r['tgl_mulai']));
            $tglakhir   = date("Y-m-d",strtotime($r['tgl_akhir']));
            $_SESSION['save'] = 'edit';
        }
    }
    else $_SESSION['save'] = 'add';
    
      $data1 = "<select name='pel'>";
      $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
      while($r=mysql_fetch_array($q)) {
            if ($pel==$r['pel']) $data1 .= "<option value='$r[pel]' selected >$r[pel]</option>";
      	     else $data1 .= "<option value='$r[pel]'>$r[pel]</option>";
      }
      $data1 .= "</select>";

      $data2 = "<select name='sem'>";
      $q=mysql_query("select semester from t_semester  ");
      while($r=mysql_fetch_array($q)) {
            if ($sem==$r['semester']) $data2 .= "<option value='$r[semester]' selected >$r[semester]</option>";
      	     else $data2 .= "<option value='$r[semester]'>$r[semester]</option>";
      }
      $data2 .= "</select>";

      $data3 = "<select name='thajar'>";
      $q=mysql_query("select thajar from t_thajar  ");
      while($r=mysql_fetch_array($q)) {
            if ($thajar==$r['thajar']) $data3 .= "<option value='$r[thajar]' selected >$r[thajar]</option>";
      	     else $data3 .= "<option value='$r[thajar]'>$r[thajar]</option>";
      }
      $data3 .= "</select>";     
    $cetak .='<script language="javascript" src="../functions/ssCalendar.js"></script>';     
    $cetak .= "<form action='user.php' method='post' ><table border=0 >";
    $cetak .= "<tr><td width='20%'>Th Pelajaran</td><td>: $data3</td></tr>";
    $cetak .= "<tr><td >Semester</td><td>: $data2</td></tr>";
    $cetak .= "<tr><td >Pelajaran</td><td>: $data1</td></tr>";
    $cetak .= "<tr><td>Jenis</td><td>: <select name='jenis' ><option value='1' $t1 >Ulangan Harian</option>
    <option value='2' $t2 >Ulangan Blok</option><option value='3' $t3 >Ulangan MID Semester</option>
    <option value='4' $t4 >Ulangan Akhir Semester</option><option value='5' $t5 >Latihan Soal</option>
    <option value='6' $t6 >Remedial</option></select></td></tr>";
    $cetak .= "<tr><td >Materi</td><td>: <input type=text name=materi value='$materi' size=50 maxlength='50' /></td></tr>";
    $cetak .= "<tr><td>Tgl Awal</td><td>";
    $m=date("m");
    $d=date("d");
    $y=date("Y");
    $cetak .=': <input name="tglmulai" type="text" id="tgl" value="'.$tglmulai.'"  readonly />
                <a href="#" id="anctgl"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br /><div id="dtDivtgl" border="0" class="calCanvas"></div>';
    $cetak.="</td></tr>";
    $cetak .="<tr><td>Tgl Akhir</td><td>";
    $cetak .=': <input name="tglakhir" type="text" id="tgl2" value="'.$tglakhir.'"   readonly />
                <a href="#" id="anctgl2"><img border=0 style="width:16px;height:16px" src="../images/ssCalendar.jpg" /></a><br />
                <div id="dtDivtgl2" border="0" class="calCanvas"></div>';
    $cetak .="</td></tr>";
    $cetak .= "<tr><td >Jumlah Soal yg ditampilkan</td><td>: <input type=text name=jmltampil value='$jmltampil' size=5 maxlength='3' /></td></tr>";
    $cetak .= "<tr><td >Password</td><td>: <input type=text name=password value='$password' size=20 maxlength='30' /></td></tr>";
    $cetak .= "<tr><td >Waktu</td><td>: <input type=text name=waktu value='$waktu' size=5 maxlength='3' /> menit</td></tr>";
    $cetak .= "<tr><td>Metode</td><td>: <select name='metode' ><option value='1' $s1 >Berurutan</option>
    <option value='2' $s2 >Acak</option></select></td></tr>";
    $cetak .= "<tr><td >Kesempatan</td><td>: <input type=text name=kesempatan value='$kesempatan' size=5 maxlength='2' /></td></tr>"; 
    $cetak .= "<tr><td valign=top >Kelas</td><td><table>";

      $q=mysql_query("select distinct kelas from t_mengajar where nip='".$nip."' order by kelas");
      while($r=mysql_fetch_array($q)) {
        $q2 = mysql_query("select * from soal_kelas where kelas='".$r['kelas']."' and idsoalutama='".mysql_real_escape_string($kode)."'");
        if($r2=mysql_fetch_array($q2)) {
            $cetak .= "<tr><td>$r[kelas]</td><td><input type='checkbox' name='kls[$r[kelas]]' value='on' checked='true' ></td></tr>";
        }
        else $cetak .= "<tr><td>$r[kelas]</td><td><input type='checkbox' name='kls[$r[kelas]]' value='on' ></td></tr>";
      }
      $cetak .="</table></td></tr>";
    $cetak .= "<tr><td ></td><td><input type='submit' value='Simpan' id=button2 /> </td></tr>";
    $cetak .= "<input type=hidden name='id' value='gurutestsave' /><input type=hidden name='kode' value='$kode' />";
    
    $cetak .="</table></div>";
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
return $cetak;        
}


function gurutestsave() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tambah Tes Online - Guru");
    $nip = konversi_id($userid);

    if ($_SESSION['save']=='add') {
         $sql = "SELECT max(idsoalutama) AS total FROM soal_utama";
         if(!$r = mysql_query($sql)) die("Error connecting to the database.");
         list($total) = mysql_fetch_array($r);
         $total += 1;

        $q = mysql_query("insert into soal_utama (idsoalutama,nip,thajar,sem,pel,jenis,materi,jml_tampil,metode,psswd_soal,kesempatan,waktu,tgl_mulai,tgl_akhir) 
        values ('".$total."','".mysql_real_escape_string($nip)."','".mysql_real_escape_string($_POST['thajar'])."',
        '".mysql_real_escape_string($_POST['sem'])."','".mysql_real_escape_string($_POST['pel'])."',
        '".mysql_real_escape_string($_POST['jenis'])."','".mysql_real_escape_string($_POST['materi'])."',
        '".mysql_real_escape_string($_POST['jmltampil'])."','".mysql_real_escape_string($_POST['metode'])."',
        '".mysql_real_escape_string($_POST['password'])."','".mysql_real_escape_string($_POST['kesempatan'])."',
        '".mysql_real_escape_string($_POST['waktu'])."','".mysql_real_escape_string($_POST['tglmulai'])."',
        '".mysql_real_escape_string($_POST['tglakhir'])."')");

        $kls = $_POST['kls'];
		if (!empty($kls))
		{
			while (list($key,$value)=each($kls))	{
				$sql="insert into soal_kelas (idsoalutama,kelas) values ('".mysql_real_escape_string($total)."','".mysql_real_escape_string($key)."')";
				$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			}
		}
        $cetak .= "Penambahan berhasil dilakukan<br><br>
        <a href='user.php?id=gurutest' id=button2 title='Lihat data tes online' >Tes Online</a> &nbsp
        <a href='user.php?id=gurutesttam' id=button2 title='Tambah tes online' >Tambah Tes Online</a> &nbsp
        <a href='user.php?id=gurutestdetail&kode=$total' id=button2 title='Tambah soal di tes online' >Tambah Soal di Tes Online</a>";
    }
    elseif($_SESSION['save']=='edit') {
        $q = mysql_query("update soal_utama
        set nip='".mysql_real_escape_string($nip)."',thajar='".mysql_real_escape_string($_POST['thajar'])."',
        sem='".mysql_real_escape_string($_POST['sem'])."',pel='".mysql_real_escape_string($_POST['pel'])."',
        jenis='".mysql_real_escape_string($_POST['jenis'])."',materi='".mysql_real_escape_string($_POST['materi'])."',
        jml_tampil='".mysql_real_escape_string($_POST['jmltampil'])."',metode='".mysql_real_escape_string($_POST['metode'])."',
        psswd_soal='".mysql_real_escape_string($_POST['password'])."',kesempatan='".mysql_real_escape_string($_POST['kesempatan'])."',
        waktu='".mysql_real_escape_string($_POST['waktu'])."',tgl_mulai='".mysql_real_escape_string($_POST['tglmulai'])."',
        tgl_akhir='".mysql_real_escape_string($_POST['tglakhir'])."' where idsoalutama='".mysql_real_escape_string($_POST['kode'])."'");
        $kls = $_POST['kls'];
        $sql = mysql_query("delete from soal_kelas where idsoalutama='".mysql_real_escape_string($_POST['kode'])."'");
		if (!empty($kls))
		{
			while (list($key,$value)=each($kls))	{
				$sql="insert into soal_kelas (idsoalutama,kelas) values ('".mysql_real_escape_string($_POST['kode'])."','".mysql_real_escape_string($key)."')";
				$mysql_result=mysql_query($sql) or die ("Penghapusan gagal");
			}
		}
        $cetak .= "Perubahan berhasil dilakukan<br><br>
        <a href='user.php?id=gurutest' id=button2 title='Lihat data tes online' >Tes Online</a> &nbsp
        <a href='user.php?id=gurutesttam' id=button2 title='Tambah tes online' >Tambah Tes Online</a> &nbsp;
        <a href='user.php?id=gurutestdetail&kode=".$_POST['kode']."' id=button2 title='Tambah soal di tes online' >Tambah Soal di Tes Online</a>";
    }
    else {
        $cetak .= "<script>document.location.href = 'user.php?id=gurutest';</script>";
    }
    $_SESSION['save']='';
     
    $cetak .="</div>";
 
return $cetak;         
}

function gurutesthapus() {
include "koneksi.php";

$id=$_POST['kls'];

if (!empty($id)) {
	while (list($key,$value)=each($id)) {
    	$sql="delete from soal_utama where idsoalutama='".mysql_real_escape_string($key)."'";
    	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
     	$sql="delete from soal_kelas where idsoalutama='".mysql_real_escape_string($key)."'";
    	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");   
     	$sql="delete from soal_test where idsoalutama='".mysql_real_escape_string($key)."'";
    	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");  
   }
	
 }
    echo "<script>document.location.href = 'user.php?id=gurutest';</script>";
	return 0;
} 

function gurutestdetail() {
     include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tes Online - Guru");  
    $query = mysql_query("select * from soal_utama where idsoalutama ='".mysql_real_escape_string($_GET['kode'])."'") ;
    if ($row=mysql_fetch_array($query)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        
        $cetak .= "<table>";
        $cetak .= "<tr><td width='25%' >Th Pelajaran</td><td>: $row[thajar]</td></tr>";
        $cetak .= "<tr><td>Semester</td><td>: $row[sem]</td></tr>";
        $cetak .= "<tr><td>Pelajaran</td><td>: $row[pel]</td></tr>";
        $cetak .= "<tr><td>Jenis</td><td>: $jenis</td></tr>";
        $cetak .= "<tr><td valign='top' >Materi</td><td>: $row[materi]</td></tr>";
        $cetak .= "</table><hr style='border: dotted 1px' />";
        $cetak .= "<a href='user.php?id=gurutestsoal&kode=".$_GET['kode']."' id=button2 title='Menambahkan soal ke tes' >Tambah soal ke tes</a> &nbsp;&nbsp;
        <a href='user.php?id=gurusoaltam' id=button2 title='Menambahkan soal' >Tambah soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestnilai&kode=".$_GET['kode']."' id=button2 title='Data nilai dan log tes online siswa' >Nilai Tes Online</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestcetak&kode=".$_GET['kode']."' id=button2 title='Cetak soal tes online' target='_blank' >Cetak Soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestanalisa&kode=".$_GET['kode']."' id=button2 title='Analisa soal' >Analisa Soal</a><br/><br/>";
        $cetak .= tampilsoaltest($_GET['kode']);
    
    } 
    else $cetak .= "<script>document.location.href = 'user.php?id=gurutest';</script>";
    $cetak .="</div>";
 
return $cetak;     
}

function gurutestsoal() {
     include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Tes Online - Guru");  
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
    $query = mysql_query("select * from soal_utama where idsoalutama ='".mysql_real_escape_string($_GET['kode'])."'") ;
    if ($row=mysql_fetch_array($query)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        
        $cetak .= "<table>";
        $cetak .= "<tr><td width='25%' >Th Pelajaran</td><td>: $row[thajar]</td></tr>";
        $cetak .= "<tr><td>Semester</td><td>: $row[sem]</td></tr>";
        $cetak .= "<tr><td>Pelajaran</td><td>: $row[pel]</td></tr>";
        $cetak .= "<tr><td>Jenis</td><td>: $jenis</td></tr>";
        $cetak .= "<tr><td valign='top' >Materi</td><td>: $row[materi]</td></tr>";
        $cetak .= "</table><hr style='border: dotted 1px' />";
        $cetak .= "<a href='user.php?id=gurutestdetail&kode=".$_GET['kode']."' id=button2 title='Melihat daftar soal yang ada di tes ini' >Soal yang diteskan</a> &nbsp;&nbsp;
        <a href='user.php?id=gurusoaltam' id=button2 title='Menambahkan soal' >Tambah soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestnilai&kode=".$_GET['kode']."' id=button2 title='Data nilai dan log tes online siswa' >Nilai Tes Online</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestcetak&kode=".$_GET['kode']."' id=button2 title='Cetak soal tes online' target='_blank' >Cetak Soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestanalisa&kode=".$_GET['kode']."' id=button2 title='Analisa soal' >Analisa Soal</a><br/><br/>";
        $cetak .="Ceklis soal yang akan dimasukan ke tes online sesuai dengan kebutuhan";
        $cetak .= tampilsoal($_GET['kode']);
     
    } 
    else $cetak .= "<script>document.location.href = 'user.php?id=gurutest';</script>";
    $cetak .="</div>";
 
return $cetak;     
}

function tampilsoal($kode='') {
   include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];

    $hal=$_GET['hal'];
    $pel=$_GET['pel'];
    $jenis = $_GET['jenis'];
    $nip = konversi_id($userid);
      if ($jenis==1) {$s2='selected';$filter = "( status='1')";}
      else {$s1='selected';  $filter = "(nip='".mysql_real_escape_string($nip)."' and status='2') ";}    
      
    
  $brs=20;
  $kol=10;
  
  $byk_result=mysql_query("SELECT pertanyaan FROM soal_opsi where $filter
  and pelajaran='".mysql_real_escape_string($pel)."'");
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
  
  $query = "SELECT * FROM soal_opsi where $filter
  and pelajaran='".mysql_real_escape_string($pel)."' order by idsoal DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $data1 = "<select name='pel'>";
  $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
  while($r=mysql_fetch_array($q)) {
        if ($pel==$r[pel]) $data1 .= "<option value='$r[pel]' selected >$r[pel]</option>";
  	     else $data1 .= "<option value='$r[pel]'>$r[pel]</option>";
  }
  $data1 .= "</select>";

  $cetak .= "<form action='user.php?' method='get' name='guru'><input type=hidden name=kode value='$kode'> 
  <input type=hidden name=id value='gurutestsoal'> ::&nbsp;&nbsp;Pelajaran : $data1 
   &nbsp;Sumber Soal : <select name='jenis' ><option value='2' $s1 >Hanya untuk pribadi</option><option value='1' $s2 >Terbuka untuk guru lain</option></select>
  <input type=submit value=' Pilih ' id=button2 ></form>
  <form action='user.php' method='post' name='tugas' >
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=6 ><center><a href='user.php?id=gurutestsoal&hal=1&pel=$pel&jenis=$jenis&kode=$kode'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurutestsoal&hal=$back&pel=$pel&jenis=$jenis&kode=$kode'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurutestsoal&hal=$i&pel=$pel&jenis=$jenis&kode=$kode'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurutestsoal&hal=$i&pel=$pel&jenis=$jenis&kode=$kode'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurutestsoal&hal=$next&pel=$pel&jenis=$jenis&kode=$kode'  title='$next'> Next</a> 
  <a href='user.php?id=gurutestsoal&hal=$jml&pel=$pel&jenis=$jenis&kode=$kode'  title='Page $jml'> Last</a></font></center></td></tr>";
  }

     $cetak .="<tr class='td0' ><td >No</td><td>Pertanyaan</td><td>Kesulitan</td><td>Status</td><td>Pilih</td><td>Lihat</td></tr>";
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
    $tanya = strip_tags($row['pertanyaan']);
    if ($row['tingkat']=='2') $tingkat = 'Sedang';
    elseif ($row['tingkat']=='2') $tingkat = 'Sukar';
    else $tingkat = 'Mudah';
    $status='-';
    $q = mysql_query("select idsoal from soal_test where idsoal='".$row['idsoal']."' and idsoalutama='".mysql_real_escape_string($kode)."'");
    if($r=mysql_fetch_array($q)) {
        $status='Sudah dipilih';
    }
    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='3%'>$j</td>
   <td width='40%'>$tanya</td>
   <td width='7%'>$tingkat</td>
   <td width='7%'>$status</td>
   <td width='3%' align=center ><input type='checkbox' name='soal[$row[idsoal]]' value='on'></td>
   <td width='3%' align=center ><a href='boxframe.php?id=lihatsoal&kode=$row[idsoal]' rel=\"facebox\"  title='klik untuk melihat detail soal'><img src='../images/cari.png'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"kode\" value=\"$kode\">
  <input type=\"hidden\" name=\"id\" value=\"gurutestpilih\"><input type=\"submit\" value=\"Pilih soal\" id=button2 ></form>";

return $cetak;   
}


function tampilsoaltest($kode='') {
   include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];

    $hal=$_GET['hal'];
    $pel=$_GET['pel'];
    $jenis = $_GET['jenis'];
    $nip = konversi_id($userid);
      
    
  $brs=20;
  $kol=10;
  
  $byk_result=mysql_query("SELECT idsoal FROM soal_test where idsoalutama='".mysql_real_escape_string($kode)."'");
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
  
  $query = "SELECT * FROM soal_test where idsoalutama='".mysql_real_escape_string($kode)."' order by idsoal DESC LIMIT ".$awal.",".$brs.""; 
  $query_result_handle = mysql_query ($query) 
  or die (mysql_error()); 

  $cetak .= "<form action='user.php' method='post' name='tugas' >
  <table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
  if ($jml!=0) {
  $cetak .= "<tr><td colspan=5 ><center><a href='user.php?id=gurutestdetail&hal=1&kode=$kode'  title='Hal 1'>First </a> 
  <a href='user.php?id=gurutestdetail&hal=$back&kode=$kode'  title='$back'>Previous </a> |";  
  for($i=$mulai;$i<=$batas;$i++)
  {
  	if ($i==$hal)
  	$cetak .= "<b><a href='user.php?id=gurutestdetail&hal=$i&kode=$kode'  title='Hal $i dari $byk Data'> $i </a></b> |";		
	else
  	$cetak .= "<a href='user.php?id=gurutestdetail&hal=$i&kode=$kode'  title='Hal $i dari $byk Data'> $i </a> |";		
  }
  $cetak .= "<a href='user.php?id=gurutestdetail&hal=$next&kode=$kode'  title='$next'> Next</a> 
  <a href='user.php?id=gurutestdetail&hal=$jml&kode=$kode'  title='Page $jml'> Last</a></font></center></td></tr>";
  }

     $cetak .="<tr class='td0' ><td >No</td><td>Pertanyaan</td><td>Kesulitan</td><td>Hap</td><td>Lihat</td></tr>";
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
    $query2 = mysql_query("select pertanyaan,tingkat from soal_opsi where idsoal='".$row[idsoal]."'");
    if ($r=mysql_fetch_array($query2)) {
        $tanya = strip_tags($r['pertanyaan']);
        if ($r['tingkat']=='2') $tingkat = 'Sedang';
        elseif ($r['tingkat']=='2') $tingkat = 'Sukar';
        else $tingkat = 'Mudah'; 
    }
    else {
        $tingkat='';$tanya='';
    }


    $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\"
><td width='3%'>$j</td>
   <td width='40%'>$tanya</td>
   <td width='7%'>$tingkat</td>
   <td width='3%' align=center ><input type='checkbox' name='soal[$row[idsoal]]' value='on'></td>
   <td width='3%' align=center ><a href='boxframe.php?id=lihatsoal&kode=$row[idsoal]' rel=\"facebox\"  title='klik untuk melihat detail soal'><img src='../images/cari.png'></a></td></tr>";
	$j++;
 }        
  $cetak .= "</table><br><input type='checkbox' name='cekall' value='on' onClick='gCheckAll(cekall);'>Checklist Semua</font>
  <input type=\"hidden\" name=\"kode\" value=\"$kode\">
  <input type=\"hidden\" name=\"id\" value=\"gurutestpilihhap\"><input type=\"submit\" value=\"Hapus\" id=button2 ></form>";
    
return $cetak;   
}

function gurutestpilih() {
    include "koneksi.php";
    $soal = $_POST['soal'];
    $kode   = $_POST['kode'];
    if (!empty($kode)) {
    	while (list($key,$value)=each($soal)) {
        	$sql=mysql_query("select idsoal from soal_test where idsoal='".mysql_real_escape_string($key)."' and idsoalutama='".mysql_real_escape_string($kode)."'");
            if (mysql_num_rows($sql)==0) {
     	       $sql2="insert into soal_test (idsoalutama,idsoal) values ('".mysql_real_escape_string($kode)."','".mysql_real_escape_string($key)."')";
    	       $mysql_result=mysql_query($sql2) or die ("tambah gagal 1");   
            }
       }
    	
     }
    echo "<script>document.location.href = 'user.php?id=gurutestdetail&kode=$kode';</script>";
	return 0;
}

function gurutestpilihhap() {
    include "koneksi.php";
    $soal = $_POST['soal'];
    $kode   = $_POST['kode'];
    if (!empty($kode)) {
    	while (list($key,$value)=each($soal)) {
     	       $sql2="delete from soal_test where idsoalutama='".mysql_real_escape_string($kode)."' and idsoal='".mysql_real_escape_string($key)."'";
    	       $mysql_result=mysql_query($sql2) or die ("penghapusan gagal 1 $kode $key");   
       }
    	
     }
    echo "<script>document.location.href = 'user.php?id=gurutestdetail&kode=$kode';</script>";
	return 0;
}

function gurutestnilai() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $nip = konversi_id($userid);
    
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Nilai Tes Online - Guru");  
    $query = mysql_query("select * from soal_utama where idsoalutama ='".mysql_real_escape_string($_GET['kode'])."'") ;
    if ($row=mysql_fetch_array($query)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        $jml = $row['kesempatan'];
        if (strtotime($row['tgl_akhir']) < mktime(0,0,0,date("m"),date("d"),date("Y")) )
        $proses = "<input type=submit value='Proses Nilai' id=button2 /><input type=hidden name='id' value='gurunilaisave' />";
        $thajar = substr($row['thajar'],2,2).substr($row['thajar'],7,2);
        $sem    = $row['sem'];
        $pel    = $row['pel'];
        $materi = $row['materi'];
        $cetak .= "<table>";
        $cetak .= "<tr><td width='25%' >Th Pelajaran</td><td>: $row[thajar]</td></tr>";
        $cetak .= "<tr><td>Semester</td><td>: $row[sem]</td></tr>";
        $cetak .= "<tr><td>Pelajaran</td><td>: $row[pel]</td></tr>";
        $cetak .= "<tr><td>Jenis</td><td>: $jenis</td></tr>";
        $cetak .= "<tr><td>Tanggal</td><td>: ".date("d-m-Y",strtotime($row['tgl_mulai']))." s.d ".date("d-m-Y",strtotime($row['tgl_akhir']))."</td></tr>";
        $cetak .= "<tr><td valign='top' >Materi</td><td>: $row[materi]</td></tr>";
        $cetak .= "</table><hr style='border: dotted 1px' />";
        $cetak .= "<a href='user.php?id=gurutestsoal&kode=".$_GET['kode']."' id=button2 title='Menambahkan soal ke tes' >Tambah soal ke tes</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestdetail&kode=".$_GET['kode']."' id=button2 title='Melihat daftar soal yang ada di tes ini' >Soal yang diteskan</a> &nbsp;&nbsp;
        <a href='user.php?id=gurusoaltam' id=button2 title='Menambahkan soal' >Tambah soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestcetak&kode=".$_GET['kode']."' id=button2 title='Cetak soal tes online' target='_blank' >Cetak Soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestanalisa&kode=".$_GET['kode']."' id=button2 title='Analisa soal' >Analisa Soal</a><br/><br/>";
        $cetak .= "<form action='user.php' method='get' ><input type=hidden name='id' value='gurutestnilai' />
        <input type=hidden name='kode' value='".$_GET['kode']."' />";
        $cetak .= "Kelas : <select name='kelas' >";
        $q=mysql_query("select * from soal_kelas where idsoalutama='".mysql_real_escape_string($_GET['kode'])."'  order by kelas");
        while($r=mysql_fetch_array($q)) {
            if($_GET['kelas']==$r['kelas']) { $cetak .= "<option value='$r[kelas]' selected >$r[kelas]</option>"; $stproses = $r['proses']; $idsoalkelas=$r['id'];}
      	    else $cetak .= "<option value='$r[kelas]' >$r[kelas]</option>";
        }
        $cetak .= "</select> ";
        $data1 = "<select name='ambil' >";
        for($i=1;$i<=$jml;$i++) {
            if($_GET['ambil']==$i) $data1 .= "<option value='$i' selected >Nilai $i</option>";
            else $data1 .= "<option value='$i' >Nilai $i</option>";
        }
        if ($_GET['ambil']=='mak') $s1='selected';
        elseif ($_GET['ambil']=='rata') $s2='selected';
        $data1 .= "<option value='mak' $s1 >Maksimal</option>";
        $data1 .= "<option value='rata' $s2 >Rata-rata</option>";
        $data1 .= "</select>";
        $cetak .= "&nbsp;&nbsp;Nilai yang diambil $data1 &nbsp;<input type='submit' value='Pilih' id=button2 /></form>";  
        $cetak .= "<form action='../functions/simnilaitest.php' method=post name='data' id='data' >";
        $cetak .= "<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3' >";
        $cetak .="<tr class='td0' ><td width=10 rowspan=2 >No</td><td width=60 rowspan=2 align=center >NIS</td><td rowspan=2 >Nama</td><td colspan=$jml align=center >Kesempatan</td>
        <td rowspan=2 align=center  >Mak</td><td rowspan=2 align=center  >Rata2</td><td rowspan=2 align=center  >Nilai</td></tr>";
        $cetak .="<tr class='td0' >";
        for($i=1;$i<=$jml;$i++) $cetak .="<td align=center width=30 >$i</td>";
        $cetak .="</tr>";
        $sql = mysql_query("select user_id,nama from t_siswa where kelas='".mysql_real_escape_string($_GET[kelas])."' order by nama");
        $j=1;
        while($r=mysql_fetch_array($sql)) {
            $warna = "td1";
        	if ($x==1) {
        	$warna = "td2";
        	$x=0; }
        	else $x=1;
            $rata =0;$n=0;$mak=0;
            $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\">
            <td>$j</td><td>$r[user_id]</td><td>$r[nama]</td>";
            $nilai = array();
            for($i=1;$i<=$jml;$i++) {
                $q = mysql_query("select nilai from soal_hasil where kesempatanjawab='$i' and nis='".$r['user_id']."' and idsoalutama='".mysql_real_escape_string($_GET['kode'])."'");
                if($data = mysql_fetch_array($q)) {
                    $nilai[$i] = $data['nilai'];
                    if ($mak < $data['nilai']) $mak = $data['nilai'];
                    $rata += $data['nilai'];
                    $n++;
                }
                $cetak .="<td align=center >".$nilai[$i]."</td>";
            }
            if($n > 0) $rata = round($rata/$n,0);
            $nilambil = '0';
            if ($_GET['ambil']=='mak') $nilambil = $mak;
            elseif ($_GET['ambil']=='rata') $nilambil = $rata;
            else $nilambil = $nilai[$_GET['ambil']];
            
            $cetak .="<td align=center >$mak</td>
            <td align=center >$rata</td>
            <td align=center ><input type='text' name='nilai[$r[user_id]]' value='$nilambil' size='1' maxlength='3' /> </td></tr>";
            $j++;
        }
        $cetak .="</table><br/>";
        $cetak .= "Nilai dapat diproses apabila tanggal tes telah berakhir<br/>";
        if ($stproses<>'0') {
            $proses ="Nilai tes ini sudah diproses dalam sistem.<br/><br/>Untuk mengubah data nilai klik 
            <a href='user.php?id=guruniledit&kd=$stproses' id=button2 >Edit Nilai</a><br/><br/>
            Untuk menghapus data nilai klik 
            <a href='user.php?id=gurutestnilaihap&kd=$stproses&idsoalkelas=$idsoalkelas&idsoal=".$_GET['kode']."' id=button2 >Hapus Nilai</a><br/>";
        }
        else {
            $form = "<input type='hidden' name='thajar' value='$thajar' /><input type='hidden' name='sem' value='$sem' />
        <input type='hidden' name='pel' value='$pel' /><input type='hidden' name='kelas' value='".$_GET['kelas']."' />
        <input type='hidden' name='nip' value='$nip' /><input type='hidden' name='kode' value='tambah' />
        <input type='hidden' name='idsoalkelas' value='$idsoalkelas' /><hr/>
        <table border=0 >
        <tr><td>Jenis</td><td>: <select name='status'>
  <option value='0' >U.Harian</option><option value='1' >Tgs.Kognitif</option>
  <option value='2' >Remedial</option><option value='3' >Tugas</option>
  <option value='4' >Praktikum</option><option value='5' >U.Umum</option>
  <option value='5' >Lain-lain</option></select></td></tr>
        <tr><td>Ujian Ke</td><td>: <input type=text name='ujianke' size=5 maxlength=3 /></td></tr>
        <tr><td>KKM</td><td>: <input type=text name='skbm' size=5 maxlength=3 /></td></tr>
        <tr><td>Kompetensi Dasar</td><td>: <input type=text name='ket' value='$materi' size=60 maxlength=250 /></td></tr>";
        }
        $cetak .= "".$form."<tr><td></td><td>".$proses."</table></form><br/>";
    } 
    $cetak .= "</div>";

    return $cetak;  
}

function gurutestnilaihap() {
include "koneksi.php";
$kd=$_GET['kd'];
$idsoal=$_GET['idsoal'];
$idsoalkelas=$_GET['idsoalkelas'];
	$sql="delete from t_nilai_detail where kd_nilai='".mysql_real_escape_string($kd)."'";
	$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 1");
		$sql="delete from t_nilai where kd_nilai='".mysql_real_escape_string($kd)."'";
		$mysql_result=mysql_query($sql) or die ("Penghapusan gagal 2");
    $sql3 = mysql_query("update soal_kelas set proses='0' where id='".mysql_real_escape_string($idsoalkelas)."'");
    
    echo "<script>document.location.href = 'user.php?id=gurutestnilai&kode=$idsoal';</script>";
	return 0;
}

function gurutestcetak() {
    include "koneksi.php";
    require_once('html2pdf/html2pdf.class.php');
 	ob_start();
 	$content = '<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">
	<page_footer>
		<table style="width: 100%; border: solid 1px black;">
			<tr>
				<td style="text-align: left;	width: 50%">Soal Tes Online - '.date("d-m-Y h:i:s").'</td>
				<td style="text-align: right;	width: 50%">hal [[page_cu]]/[[page_nb]]</td>
			</tr>
		</table>
	</page_footer>
    <img src="../images/kopsurat.jpg" width=100% /><hr/>';
    $query = mysql_query("select * from soal_utama where idsoalutama ='".mysql_real_escape_string($_GET['kode'])."'") ;
    if ($row=mysql_fetch_array($query)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        
        $content .= '<h2 style="text-align:center;" >'.$jenis.'</h2>
        <table style="width: 100%; border: solid 0px #FFFFFF;" cellspacing="2" cellpadding="3"  >';
        $content .= "<tr><td>Th Pelajaran</td><td>: $row[thajar]</td></tr>";
        $content .= "<tr><td>Semester</td><td>: $row[sem]</td></tr>";
        $content .= "<tr><td>Pelajaran</td><td>: $row[pel]</td></tr>";
        $content .= "<tr><td>Jenis</td><td>: $jenis</td></tr>";
        $content .= "<tr><td>Materi</td><td>: $row[materi]</td></tr>";
        $content .= "<tr><td>Waktu</td><td>: $row[waktu] menit</td></tr>";
        $content .= '</table><hr/><ol>';
        $q = mysql_query("select * from soal_test,soal_opsi where soal_test.idsoal=soal_opsi.idsoal and soal_test.idsoalutama='".mysql_real_escape_string($_GET['kode'])."' order by RAND()");
        while($r=mysql_fetch_array($q)) {
            $alt_6=$r["jawaban"];
           	$alt_1=$r["opsia"];
           	$alt_2=$r["opsib"];
           	$alt_3=$r["opsic"];
           	$alt_4=$r["opsid"];
            $input=array($alt_1,$alt_2,$alt_3,$alt_4,$alt_6);
            sort($input, SORT_STRING);
            srand ((float)microtime()*1000000);
            shuffle($input);
            $content .= "<li>".html_entity_decode($r['pertanyaan'])."<table style='boder:0px'>";
            for ($j = 0; $j < 5; $j++){
                if ($j==0) $no = "A.";
                elseif ($j==1) $no = "B.";
                elseif ($j==2) $no = "C.";
                elseif ($j==3) $no = "D.";
                else $no = "E.";
                $content .= "<tr><td valign='top'>$no</td><td valign='center'>".html_entity_decode($input[$j])."</td></tr>";
            }
            $content .= "</table></li>";
        }
           $content .="</ol>";
    }
    $content .= '</page>';
	$content .= ob_get_clean();
	try
	{
		$html2pdf = new HTML2PDF('P','A4', 'en', false, 'ISO-8859-15', 3);
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('soal.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	return 0;
}

function gurutestanalisa() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
$cetak .="<script src=\"js/facebox.js\" type=\"text/javascript\"></script>
  <script type=\"text/javascript\">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
      }) 
    })

</script> ";
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= ataslogin("Analisa Soal - Guru");  
    $query = mysql_query("select * from soal_utama where idsoalutama ='".mysql_real_escape_string($_GET['kode'])."'") ;
    if ($row=mysql_fetch_array($query)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        
        $cetak .= "<table>";
        $cetak .= "<tr><td width='25%' >Th Pelajaran</td><td>: $row[thajar]</td></tr>";
        $cetak .= "<tr><td>Semester</td><td>: $row[sem]</td></tr>";
        $cetak .= "<tr><td>Pelajaran</td><td>: $row[pel]</td></tr>";
        $cetak .= "<tr><td>Jenis</td><td>: $jenis</td></tr>";
        $cetak .= "<tr><td valign='top' >Materi</td><td>: $row[materi]</td></tr>";
        $cetak .= "</table><hr style='border: dotted 1px' />";
        $cetak .= "<a href='user.php?id=gurutestsoal&kode=".$_GET['kode']."' id=button2 title='Menambahkan soal ke tes' >Tambah soal ke tes</a> &nbsp;&nbsp;
        <a href='user.php?id=gurusoaltam' id=button2 title='Menambahkan soal' >Tambah soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestnilai&kode=".$_GET['kode']."' id=button2 title='Data nilai dan log tes online siswa' >Nilai Tes Online</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestcetak&kode=".$_GET['kode']."' id=button2 title='Cetak soal tes online' target='_blank' >Cetak Soal</a> &nbsp;&nbsp;
        <a href='user.php?id=gurutestanalisa&kode=".$_GET['kode']."' id=button2 title='Analisa soal' >Analisa Soal</a><br/><br/>";
 
          $query = "SELECT * FROM soal_test where idsoalutama='".mysql_real_escape_string($_GET['kode'])."' order by idsoal "; 
          $query_result_handle = mysql_query ($query) 
          or die (mysql_error()); 
          $j=1;
          $cetak .= "<table width='100%' id='tablebaru' cellspacing='1' cellpadding='3'>";
          $cetak .="<tr class='td0' ><td align=center  >No</td><td>Pertanyaan</td><td align=center >Kesulitan</td>
          <td align=center >Jawab Benar</td><td align=center  >Jawab Salah</td><td align=center  >Lihat</td></tr>";
          while ($row = mysql_fetch_array($query_result_handle))
          {
          	$warna = "td1";
        	if ($x==1) {
        	$warna = "td2";
        	$x=0; }
        	else $x=1;
            $query2 = mysql_query("select pertanyaan,tingkat from soal_opsi where idsoal='".$row['idsoal']."'");
            if ($r=mysql_fetch_array($query2)) {
                $tanya = strip_tags($r['pertanyaan']);
                if ($r['tingkat']=='2') $tingkat = 'Sedang';
                elseif ($r['tingkat']=='2') $tingkat = 'Sukar';
                else $tingkat = 'Mudah'; 
            }
            else {
                $tingkat='';$tanya='';
            }
            $benar =0;$salah=0;
            $sql = mysql_query("select count(idsoal) as jum,ket from soal_jawab where idsoal='".$row['idsoal']."' GROUP BY ket");
            while ($r=mysql_fetch_array($sql)) {
                if($r['ket']=='Benar') $benar = $r['jum'];
                else $salah = $r['jum'];
            }
                
            $cetak .= "<tr class='$warna' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$warna."'\" >
            <td width='3%'>$j</td>
           <td width='40%'>$tanya</td>
           <td width='7%' align=center >$tingkat</td>
           <td width='5%' align=center >$benar</td><td width='5%' align=center >$salah</td>
           <td width='3%' align=center ><a href='boxframe.php?id=lihatsoal&kode=$row[idsoal]' rel=\"facebox\"  title='klik untuk melihat detail soal'><img src='../images/cari.png'></a></td></tr>";
        	$j++;
         }        
          $cetak .= "</table><br>";
    } 
    else $cetak .= "<script>document.location.href = 'user.php?id=gurutest';</script>";
    $cetak .="</div>";
    return $cetak;
}
function jsdisabled() {
    include "koneksi.php";
    $userid = $_SESSION['User']['userid'];
    $kategori = $_SESSION['User']['ket'];
    $cetak .="<div id='depan-tengahkanan'>";
    $cetak .= statusanda($userid);
    $cetak .="<hr style='border: thin solid #6A849D;'>";
    $cetak .= "<br/><center><h2>Mohon maaf, javascript di browser ini tidak aktif.</h2></center>";
    $cetak .= "</div>";
    return $cetak; 
}
?>