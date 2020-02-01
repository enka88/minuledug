<?php
define("CMSBalitbang",1);
session_start();
echo '<link type="text/css" rel="stylesheet" media="all" href="css/kontenbox.css" />';

include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
if (!isset($_SESSION['User'])) {
    echo "Maaf Anda tidak diperkenankan untuk mengakses fitur ini.";
    exit;
}

$id=$_POST['id'];
if ($id=='') $id=$_GET['id']; 

if ($id=='tamsumber') {
//include "../functions/fungsi_konversiuser.php";
if (!empty($_SESSION['idsum'])) {
    $query = mysql_query("select * from t_belajar_detail where iddetail='".mysql_real_escape_string($_SESSION['idsum'])."'");
    if($row = mysql_fetch_array($query)) {
        $isi = html_entity_decode($row['materi']);
        $stshow = $row['stshow'];
        $per = $row['pertemuan'];
        $urut = $row['urut'];
    }
    
}
    $jenis = $_GET['jenis'];
    if ($jenis=='2') {
        
        $s2='selected';
        if ($_GET['kode']<>'') $konten = $nmhost."html/guru.php?id=lihmateri&kode=".$_GET['kode'];
        else { 
            if (!empty($_SESSION['idsum'])) $konten = $isi;
        }
        
        $materi ="<tr><td valign=top >Materi </td><td><input type=text name='materi' size=60 value='".$konten."' /> 
        <a href='kontenbelajar.php?id=materiajar' id='button' >Cari</a><br/>
        Masukan URL Materi ajar yang ada di website sekolah <br/>
        Contoh : http://www.websekolah.sch.id/html/guru.php?id=lihmateri&kode=10</td></tr>";
    }
    elseif ($jenis=='3') {
        $s3='selected';
        
        if (!empty($_SESSION['idsum'])) $konten = $isi;
        
        $materi ="<tr><td valign=top >Materi </td><td><input type=text name='materi' size=55 value='".$konten."' /> 
        <a href='http://ayobelajar.info' id='button' target='_blank' >Akses Ayo Belajar</a><br/>
        Masukan URL Modul dari Ayo Belajar <br/>
        Contoh : http://www.ayobelajar.info/index.php/modul/index/103</td></tr>";
    }
    elseif ($jenis=='4') {
        $s4='selected';
        if (!empty($_SESSION['idsum'])) $konten = $isi;
        
        $materi ="<tr><td valign=top >Materi </td><td><input type=text name='materi' size=65 value='".$konten."'  /> <br/>
        Masukan URL Modul dari website lain <br/>
        Contoh : http://www.kajianwebsite.org/download/modul.zip</td></tr>";
    }
    elseif ($jenis=='5') {
        $s5='selected';
        if ($_GET['kode']<>'') $konten = $nmhost."member/user.php?id=tugasdetail&kd=".$_GET['kode'];
        else {
            if (!empty($_SESSION['idsum'])) $konten = $isi;
        }
        $materi ="<tr><td valign=top >Materi </td><td><input type=text name='materi' size=60 value='".$konten."'  readonly=true /> 
        <a href='kontenbelajar.php?id=tugasonline' id='button' >Cari</a></td></tr>";
    }
    elseif ($jenis=='6') {
        $s6='selected'; 
        if ($_GET['kode']<>'') $konten = $nmhost."member/user.php?id=group&kdgroup=".$_GET['kode'];
        else {
            if (!empty($_SESSION['idsum'])) $konten = $isi;
        }
        $materi ="<tr><td valign=top >Materi </td><td><input type=text name='materi' size=60 value='".$konten."'  readonly=true /> 
        <a href='kontenbelajar.php?id=group' id='button' >Cari</a></td></tr>";
    }
    elseif ($jenis=='7') {
        $s7='selected';
        if ($_GET['kode']<>'') $konten = $nmhost."member/user.php?id=masuktest&idsoal=".$_GET['kode'];
        else {
            if (!empty($_SESSION['idsum'])) $konten = $isi;
        }
        $materi ="<tr><td valign=top >Materi </td><td><input type=text name='materi' size=60 value='".$konten."' readonly=true /> 
        <a href='kontenbelajar.php?id=ujianonline' id='button' >Cari</a></td></tr>";
    }
    else {
        $s1='selected';
        if (!empty($_SESSION['idsum'])) $konten = $isi;
        
        $materi ="<tr><td colspan=2 >Materi <br><textarea id=elm1 name='materi' rows=10 cols=20 style='width: 85%'>".$konten."</textarea></td></tr>";
        include "../functions/functions_editor.php";
        echo editor_full();
    }
    
	echo "<div id='fotoupload-atas'>Tambah Sumber Belajar </div>";
	echo "<form action='kontenbelajar.php' method=post name='form2' >
	<table border=0 >
    <tr><td width=100 >Jenis Sumber </td><td>";
    echo '<select name="jenis" id=jenis onchange="document.location.href=\'kontenbelajar.php?id=tamsumber&jenis=\'+document.form2.jenis.value" >';
    if (!empty($_SESSION['idsum'])) {
        if ($jenis=='1') $j = "Text";
        elseif ($jenis=='2') $j = "Materi Ajar";
        elseif ($jenis=='3') $j = "Ayobelajar.net";
        elseif ($jenis=='4') $j = "URL dari luar";
        elseif ($jenis=='5') $j = "Tugas Online";
        elseif ($jenis=='6') $j = "Group Diskusi";
        elseif ($jenis=='7') $j = "Tes Online";
       echo "<option value='$jenis' >$j</option>";
    }
    else {
    echo "<option value='1' $s1  >Text</option>
        <option value='2' $s2 >Materi Ajar</option>
        <option value='3' $s3 >Ayobelajar.net</option>
        <option value='4' $s4 >URL dari luar</option>
        <option value='5' $s5 >Tugas Online</option>
        <option value='6' $s6 >Group Diskusi</option>
        <option value='7' $s7 >Tes Online</option>
        ";
      }  
    
    if ($stshow=='0') $st2 = 'selected';
    else $st1 = 'selected';
    
        echo "</select></td></tr>
	$materi
    <tr><td  >Status </td><td><select name=status ><option value=1 $st1 >Tampil</option><option value=0 $st2 >Sembunyi</option></select></td></tr>
    <tr><td  >Pertemuan </td><td><select name='pertemuan' >";
    for($i=1;$i<=20;$i++) {
        if ($i==$per) echo "<option value='$i' selected >$i</option>";
        else echo "<option value='$i'  >$i</option>";
    }
    echo "</select> &nbsp;&nbsp;&nbsp;&nbsp;Urut <input type=text name='urut' value='$urut' maxlength='2' size=5 ></td></tr>
	<tr><td valign=top>Kode Verifikasi </td><td><img src='../functions/spam.php'  ><br><input type='text' name='code' size='12'  ></td></tr>
    <input type=hidden name='id' value='simpansumber'>
<tr><td ></td><td><input type='submit' value='Simpan' id=button ></td></tr></table></form>";
}
elseif ($id=='materiajar') {
    $pel = $_GET['pel'];
    echo "<div id='fotoupload-atas'>Tambah Sumber Belajar </div>";
    echo "Pencarian Materi Ajar<br/>";
    echo "<form action='kontenbelajar.php' method='get' >
    Judul/Pelajaran : <input type='cari' name='pel' value='$pel' /> <input type='submit' value=' Cari ' id=button />
    <input type='hidden' name='id' value='materiajar' /> <a href='kontenbelajar.php?id=tamsumber&jenis=2' id=button >Kembali</a></form>";
    if (empty($pel)) {
        $pel = "12345";
    }
    $query2 = mysql_query("select judul,id from t_download where 
    judul like'%".mysql_real_escape_string($pel)."%' or kategori like'%".mysql_real_escape_string($pel)."%'");
    $n=1;
    echo "<table style='border:solid 1px #666666;' width=95% cellspacing=1 cellpadding=3 >
    <tr><td width=10 style='border:solid 1px #666666;' >No</td><td style='border:solid 1px #666666;' >Judul</td><td width='30' style='border:solid 1px #666666;' >Kontrol</td></tr>";
    while($row=mysql_fetch_array($query2)) {
        
        echo "<tr><td valign=top style='border:solid 1px #666666;'  >$n</td><td style='border:solid 1px #666666;' >".$row['judul']."</td>
        <td valign=top style='border:solid 1px #666666;' align=center  ><a href='kontenbelajar.php?id=tamsumber&jenis=2&kode=$row[id]' id=button >Pilih</a></td></tr>";
        $n++;
    }
    echo "</table>";
    
}
elseif ($id=='tugasonline') {
    include "../functions/fungsi_konversiuser.php";
    $thajar = $_GET['thajar'];
    $sem = $_GET['sem'];
    $kelas = $_GET['kelas'];
    $userid = $_SESSION['User']['userid'];
    $nip = konversi_id($userid);

    echo "<div id='fotoupload-atas'>Tambah Sumber Belajar </div>";
    echo "Pencarian Tugas Online<br/>";
  $data2 .=  "<select name='kelas' >";
  $sql2="select distinct(kelas) from t_mengajar where nip='".mysql_real_escape_string($nip)."' ";
  $my=mysql_query($sql2);
  while($al=mysql_fetch_array($my)) {
  	if ($al['kelas']==$kelas) $data2 .=  "<option value='$al[kelas]' selected>$al[kelas]</option>";
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
   
    echo "<form action='kontenbelajar.php' method='get' >
    Kelas : $data2 Th Pelajaran : $data3 Semester : $data4 <input type='submit' value=' Cari ' id=button />
    <input type='hidden' name='id' value='tugasonline' /> <a href='kontenbelajar.php?id=tamsumber&jenis=5' id=button >Kembali</a></form>";
    if (empty($pel)) {
        $pel = "12345";
    }
    $query2 = mysql_query("SELECT * FROM t_tugas,t_tugas_kelas where t_tugas.idtugas=t_tugas_kelas.idtugas 
  and thajar='".mysql_real_escape_string($thajar)."' and t_tugas.sem='".mysql_real_escape_string($sem)."' 
  and t_tugas.nip='".mysql_real_escape_string($nip)."' 
  and kelas='".mysql_real_escape_string($kelas)."' order by t_tugas.tgl_kirim ");
    $n=1;
    echo "<table style='border:solid 1px #666666;' width=95% cellspacing=1 cellpadding=3 >
    <tr><td width=10 style='border:solid 1px #666666;' >No</td><td style='border:solid 1px #666666;' >Judul</td><td width='30' style='border:solid 1px #666666;' >Kontrol</td></tr>";
    while($row=mysql_fetch_array($query2)) {
        
        echo "<tr><td valign=top style='border:solid 1px #666666;'  >$n</td><td style='border:solid 1px #666666;' >".$row['pelajaran']."<br/>".$row['isi']."</td>
        <td valign=top style='border:solid 1px #666666;' align=center  ><a href='kontenbelajar.php?id=tamsumber&jenis=5&kode=$row[idtugas]' id=button >Pilih</a></td></tr>";
        $n++;
    }
    echo "</table>";
}
elseif ($id=='group') {
    $userid = $_SESSION['User']['userid'];
    $group = $_GET['group'];
    echo "<div id='fotoupload-atas'>Tambah Sumber Belajar </div>";
    echo "Pencarian Group Diskusi<br/>";
    echo "<form action='kontenbelajar.php' method='get' >
    Nama Group Diskusi : <input type='cari' name='group' value='$group' /> <input type='submit' value=' Cari ' id=button />
    <input type='hidden' name='id' value='group' /> <a href='kontenbelajar.php?id=tamsumber&jenis=6' id=button >Kembali</a></form>";
//    if (empty($group)) {
//        $group = "12345";
//    }
    $query2 = mysql_query("select nmgroup,idgroup from t_membergroup where userid='$userid' and nmgroup like'%".mysql_real_escape_string($group)."%'");
    $n=1;
    echo "<table style='border:solid 1px #666666;' width=95% cellspacing=1 cellpadding=3 >
    <tr><td width=10 style='border:solid 1px #666666;' >No</td><td style='border:solid 1px #666666;' >Nama Group/Diskusi</td><td width='30' style='border:solid 1px #666666;' >Kontrol</td></tr>";
    while($row=mysql_fetch_array($query2)) {
        $acak = hex($row['idgroup'],$noacak);
        echo "<tr><td valign=top style='border:solid 1px #666666;'  >$n</td><td style='border:solid 1px #666666;' >".$row['nmgroup']."</td>
        <td valign=top style='border:solid 1px #666666;' align=center  ><a href='kontenbelajar.php?id=tamsumber&jenis=6&kode=$acak' id=button >Pilih</a></td></tr>";
        $n++;
    }
    echo "</table>";
}
elseif ($id=='ujianonline') {
    include "../functions/fungsi_konversiuser.php";
    $userid = $_SESSION['User']['userid'];
    $nip = konversi_id($userid);
    $ujian = $_GET['ujian'];
    $materi = $_GET['materi'];
    $pel = $_GET['pel'];
    echo "<div id='fotoupload-atas'>Tambah Sumber Belajar </div>";
    echo "Pencarian Ujian Online<br/>";
      $data1 = "<select name='pel'>";
      $q=mysql_query("select DISTINCT pel from t_mengajar where nip='".mysql_real_escape_string($nip)."' ");
      while($r=mysql_fetch_array($q)) {
            if ($pel==$r['pel']) $data1 .= "<option value='$r[pel]' selected >$r[pel]</option>";
      	     else $data1 .= "<option value='$r[pel]'>$r[pel]</option>";
      }
      $data1 .= "</select>";
    echo "<form action='kontenbelajar.php' method='get' >
    Pelajran : $data1
    &nbsp;&nbsp;Materi Uji : <input type='cari' name='materi' value='$materi' /> <input type='submit' value=' Cari ' id=button />
    <input type='hidden' name='id' value='ujianonline' /> <a href='kontenbelajar.php?id=tamsumber&jenis=7' id=button >Kembali</a></form>";
    
    $query2 = mysql_query("select idsoalutama,pel,materi,jenis from soal_utama where nip='$nip' and pel='".mysql_real_escape_string($pel)."' and materi like'%".mysql_real_escape_string($materi)."%' ");
    $n=1;
    echo "<table style='border:solid 1px #666666;' width=95% cellspacing=1 cellpadding=3 >
    <tr><td width=10 style='border:solid 1px #666666;' >No</td><td style='border:solid 1px #666666;' >Jenis Ujian</td>
    <td width='30' style='border:solid 1px #666666;' >Kontrol</td></tr>";
    while($row=mysql_fetch_array($query2)) {
        if($row['jenis']==1) $jenis = "Ulangan Harian";
        elseif($row['jenis']==2) $jenis = "Ulangan Blok";
        elseif($row['jenis']==3) $jenis = "Ulangan MID Semester";
        elseif($row['jenis']==4) $jenis = "Ulangan Akhir Semester";
        elseif($row['jenis']==5) $jenis = "Latihan Soal";
        else $jenis = "Remedial";
        echo "<tr><td valign=top style='border:solid 1px #666666;'  >$n</td><td style='border:solid 1px #666666;' >$jenis - ".$row['materi']."</td>
        <td valign=top style='border:solid 1px #666666;' align=center  ><a href='kontenbelajar.php?id=tamsumber&jenis=7&kode=$row[idsoalutama]' id=button >Pilih</a></td></tr>";
        $n++;
    }
    echo "</table>";
}
elseif ($id=='simpansumber') {
    $status = $_POST['status'];
    $jenis = $_POST['jenis'];
    //$materi = htmlentities($_POST['materi']);
    $materi = $_POST['materi'];
    $urut   = $_POST['urut'];
    $pertemuan = $_POST['pertemuan'];
	$code = $_POST['code'];
    
	$idbelajar = $_SESSION['idbelajar'];
    $idsum     = $_SESSION['idsum'];
    
	$kode= $_SESSION['kodeRandom'];
  	if (trim($materi) == '' ) {
		echo "Konten materi masih kosong. <a href='kontenbelajar.php?id=tamsumber' id='button'  > Kembali </a>";
   	}
	elseif (trim($urut)=='') {
		echo "Judul masih kosong. <a href='kontenbelajar.php?id=tamsumber' id='button' > Kembali </a>";
	}
	elseif (trim($idbelajar)=='' and trim($idsum)=='') {
		echo "ID Belajar masih kosong. <a href='kontenbelajar.php?id=tamsumber' id='button' > Kembali </a>";
	}
	elseif (strtoupper($code) != $kode) {
		echo "Kode keamanan salah. <a href='kontenbelajar.php?id=tamsumber' id='button' > Kembali </a>";
	}
	else {
	   if ($idsum=='') {
        $query = "insert into t_belajar_detail (idbelajar,materi,jenis,stshow,pertemuan,urut) values ('".mysql_real_escape_string($idbelajar)."',
        '".$materi."','".mysql_real_escape_string($jenis)."','".mysql_real_escape_string($status)."',
        '".mysql_real_escape_string($pertemuan)."','".mysql_real_escape_string($urut)."')" ;
        $a = mysql_query($query);
        echo "Penambahan berhasil dilakukan, silahkan klik close";
        }
      else {
        $query = "update t_belajar_detail set  
        materi='".$materi."',jenis='".mysql_real_escape_string($jenis)."',stshow='".mysql_real_escape_string($status)."',
        pertemuan='".mysql_real_escape_string($pertemuan)."',urut='".mysql_real_escape_string($urut)."' where iddetail='".mysql_real_escape_string($idsum)."'" ;
        $a = mysql_query($query);
        echo "Perubahan berhasil dilakukan, silahkan klik close";        
      }
    }
}
elseif ($id=='lihatsoal') {
    $query = mysql_query("select * from soal_opsi where idsoal ='".mysql_real_escape_string($kode)."'");
    if ($r=mysql_fetch_array($query)) {
        if ($r[tingkat]==1) $tingkat = "Mudah";
        elseif ($r[tingkat]==1) $tingkat = "Sedang";
        else $tingkat = "Sukar";
        echo "<table><tr><td colspan=2 ><b>Pertanyaan :</b></td></tr>
        <tr><td  colspan=2 >$r[pertanyaan]</td></tr>
        <tr><td valign=top width=100 ><b>Opsi Jawaban</td><td>$r[jawaban]</td></tr>
        <tr><td valign=top ><b>Opsi lain</td><td>$r[opsia]</td></tr>
        <tr><td valign=top ><b>Opsi lain</td><td>$r[opsib]</td></tr>
        <tr><td valign=top ><b>Opsi lain</td><td>$r[opsic]</td></tr>
        <tr><td valign=top ><b>Opsi lain</td><td>$r[opsid]</td></tr>
        <tr><td colspan=2 ><b>Pembahasan :</b></td></tr>
        <tr><td colspan=2>$r[pembahasan]</td></tr>
        <tr><td colspan=2><b>Tingkat Kesulitan</b> : $tingkat</td></tr>
        </table>";
    }
}
?>