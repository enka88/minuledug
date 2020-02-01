<?php
session_start();
require '../functions/koneksi.php';
define("Balitbang",1);

echo "<html>
<head><title>Login Administrator</title>
<link rel='stylesheet' type='text/css' href='admin.css'>
</head>
<body topmargin='0' leftmargin='0'>";
?>
<script type="text/javascript">
/* <![CDATA[ */
SetCookie('didgettingstarted',1);

function setDisplayMenu(idName)
{
    if (idName == '') {
        // '' is news, and etc.    
        idName = 'o';
    }

    if ( idName !=null) {
        closeMenuDiv();
        openMenuDiv(idName);
    } else {
        closeMenuDiv();
    }
}

function clickOpenMenu(idName)
{
	closeMenuDiv();
	openMenuDiv(idName);
}

function closeMenuDiv()
{
	var aObjDiv = document.getElementsByTagName("div");
	var numDiv = aObjDiv.length;

	for(i=0; i < numDiv; i++) 
	{
		var idName = aObjDiv[i].getAttribute("id");
		
		if(idName)
		{
			var isMenu = idName.match(/SubCat/i);
					
			if(isMenu !=null) 
			{				
				document.getElementById(idName).style.visibility = "hidden";
				document.getElementById(idName).style.position = "absolute";
			}
		}
	}

}

function openMenuDiv(idName)
{
	document.getElementById('SubCat_'+idName).style.visibility = "visible";
	document.getElementById('SubCat_'+idName).style.position = "static";
}

function clickOpenPage(URL,target)
{
	window.open(URL, target);
}
</script>
<?php
if ( !isset($_SESSION['Admin']) )
{
	echo "Anda harus login dulu.. redirecting\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
} else {
	if ( isset($_GET['logout']) )
	{
		$username = $_SESSION['Admin']['username'];
		unset($_SESSION['Admin']);
		//session_destroy();
		echo "Terima kasih.. redirecting\n";
		echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\">\n";
	} else {
		  
		echo"<table width='900' border='1' align='center' cellpadding='2' cellspacing='1' bordercolor='#3333cc'  >
    <tr><td colspan='2' ><img src='../images/atas_admin.jpg' width='900' height='100' >
        </td>
    </tr>
    <tr>
      <td width='150' valign='top' bgcolor='#D5D9E4'>";
	  //----------------------menu--------------------------
	  	echo '<div id="LeftMenu">
<div class="LeftMenuHead" onclick="clickOpenPage(\'admin.php\',\'_top\'); return false;" style="cursor: pointer;">HOME
</div>
<div class="LeftMenuline"></div>';
		echo '<div class="LeftMenuHead" onclick="clickOpenPage(\'admin.php?logout\',\'_top\'); return false;" style="cursor: pointer;">Logout</div>';
		echo '<div class="LeftMenuline"></div>
<div class="LeftMenuHead" onclick="clickOpenPage(\'../html/index.php\',\'_blank\'); return false;" style="cursor: pointer;">Tampilkan Web
</div>
<div class="LeftMenuline"></div>
<div class="LeftMenuHead" onclick="clickOpenMenu(\'o\'); return false;" style="cursor: pointer;">Personal</div>
<div style="visibility: hidden; position: absolute;" id="SubCat_o">
<div id="Section_c_ticket" style="cursor: pointer;"><a href="admin.php?mode=editpersonal" class=ver11 >Password & E-mail</a></div>
</div>
<div class="LeftMenuline"></div>
<div class="LeftMenuHead" onclick="clickOpenMenu(\'pf\'); return false;" style="cursor: pointer;">Fitur</div>
<div style="visibility: hidden; position: absolute;" id="SubCat_pf">';
	    $query = "SELECT * FROM user_level WHERE userid='".mysql_escape_string($_SESSION['Admin']['userid'])."' and utama='1' order by menu "; 
  		$result = mysql_query ($query) or die (mysql_error()); 
  		while($row = mysql_fetch_array($result)) {
			if ($row[menu]=='artikel') echo '<div style="cursor: pointer;"><a href="admin.php?mode=artikel" class=ver11 >Artikel</a></div>';
			if ($row[menu]=='agenda') echo '<div style="cursor: pointer;"><a href="admin.php?mode=agenda" class=ver11 >Agenda</a></div>';
			if ($row[menu]=='berita') echo '<div style="cursor: pointer;"><a href="admin.php?mode=rempost" class=ver11  >Berita</a></div>';
			if ($row[menu]=='bukutamu') echo '<div style="cursor: pointer;"><a href="admin.php?mode=buku_tamu" class=ver11 >Buku Tamu</a></div>';
	if ($cmsmember == "ya") {
			if ($row[menu]=='forum') echo '<div style="cursor: pointer;"><a href="admin.php?mode=diskusi" class=ver11 >Forum Diskusi</a></div>';
	}
			if ($row[menu]=='galeri') echo '<div style="cursor: pointer;"><a href="admin.php?mode=album" class=ver11 >Galeri Photo</a></div>';
			if ($row[menu]=='link') echo '<div style="cursor: pointer;"><a href="admin.php?mode=link" class=ver11 >Link Web</a></div>';
	if ($cmsmember == "ya") {
			if ($row[menu]=='infoalumni') echo '<div style="cursor: pointer;"><a href="admin.php?mode=pesan_alm" class=ver11 >Info Alumni</a></div>';
	}
			if ($row[menu]=='infosekolah') echo '<div style="cursor: pointer;"><a href="admin.php?mode=info" class=ver11 >Info Sekolah</a></div>';
			if ($row[menu]=='materiajar') echo '<div style="cursor: pointer;"><a href="admin.php?mode=download" class=ver11 >Materi Ajar</a></div>';
			if ($row[menu]=='kumpulsoal') echo '<div style="cursor: pointer;"><a href="admin.php?mode=soal" class=ver11 >Materi Uji</a></div>';
			if ($row[menu]=='silabus') echo '<div style="cursor: pointer;"><a href="admin.php?mode=silabus" class=ver11 >Silabus</a></div>';
			if ($row[menu]=='prestasi') echo '<div style="cursor: pointer;"><a href="admin.php?mode=prestasi" class=ver11 >Prestasi</a></div>';
			if ($row[menu]=='jajak') echo '<div style="cursor: pointer;"><a href="admin.php?mode=voting" class=ver11 >Jajak Pendapat</a></div>';
			if ($row[menu]=='banner') echo '<div style="cursor: pointer;"><a href="admin.php?mode=banner" class=ver11 >Banner</a></div>';
		}
		echo '</div><div class="LeftMenuline"></div>';
	if ($cmssim == "ya") {
		echo '<div class="LeftMenuHead" onclick="clickOpenMenu(\'m\'); return false;" style="cursor: pointer;">SIM</div>
		<div style="visibility: hidden; position: absolute;" id="SubCat_m">';
		$query = "SELECT * FROM user_level WHERE userid='".mysql_escape_string($_SESSION['Admin']['userid'])."' and utama='2' order by menu "; 
  		$result = mysql_query ($query) or die (mysql_error()); 
  		while($row = mysql_fetch_array($result)) {
			if ($row[menu]=='dtnilai') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=datanilai" class=ver11 >Data Nilai</a></div>';
			if ($row[menu]=='dtmateri') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=datatugas" class=ver11 >Data Materi</a></div>';
			if ($row[menu]=='dtbpbk') echo '<div style="cursor: pointer;"><a href="admin.php?mode=gurubk" class=ver11 >Data BP/BK</a></div>';
			if ($row[menu]=='dtabsensi') echo '<div style="cursor: pointer;"><a href="admin.php?mode=databsen" class=ver11 >Data Absensi</a></div>';
			if ($row[menu]=='dtspp') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=dataspp" class=ver11 >Data SPP/DSP</a></div>';
			if ($row[menu]=='dtlaporan') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=datalaporan" class=ver11 >Data Laporan</a></div>';
		}
		echo '</div><div class="LeftMenuline"></div>';
	}
		echo '<div class="LeftMenuHead" onclick="clickOpenMenu(\'l\'); return false;" style="cursor: pointer;">Setting Admin</div>
<div style="visibility: hidden; position: absolute;" id="SubCat_l">';
		$query = "SELECT * FROM user_level WHERE userid='".mysql_escape_string($_SESSION['Admin']['userid'])."' and utama='3'order by menu "; 
  		$result = mysql_query ($query) or die (mysql_error()); 
  		while($row = mysql_fetch_array($result)) {
			if ($row[menu]=='admin') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=addadmin" class=ver11 >Tambah Admin</a></div>';
			if ($row[menu]=='admin') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=viewadmin&hal=" class=ver11 >Lihat Admin</a></div>';
			if ($row[menu]=='profil') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=profil" class=ver11 >Menu & Profil</a></div>';
			if ($row[menu]=='posisi') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=posmenu" class=ver11 >Posisi Modul</a></div>';
			if ($row[menu]=='template') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=tempmenu" class=ver11 >Template Menu</a></div>';
			if ($row[menu]=='gambar') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=gbdepan" class=ver11 >Gambar Atas</a></div>';
			if ($row[menu]=='kategori') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=kategorilink" class=ver11 >Kategori Link</a></div>';
			if ($row[menu]=='semester') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=semester" class=ver11 >Semester</a></div>';
			if ($row[menu]=='semester') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=thajar" class=ver11 >Thn Pelajaran</a></div>';
			if ($row[menu]=='program') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=program" class=ver11 >Jurusan/Program</a></div>';
			if ($row[menu]=='kelas') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=kelas" class=ver11 >Data Kelas</a></div>';
			if ($row[menu]=='pelajaran') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=pelajaran" class=ver11  >Pelajaran</a></div>';
			if ($row[menu]=='homepage') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=mgmp" class=ver11  >Homepage</a></div><div  style="cursor: pointer;"><a href="admin.php?mode=viewmgmp" class=ver11  >Admin Homepage</a></div>';
		}
		echo '</div><div class="LeftMenuline"></div>
<div class="LeftMenuHead" onclick="clickOpenMenu(\'f\'); return false;" style="cursor: pointer;">Data Guru</div>
<div style="visibility: hidden; position: absolute;" id="SubCat_f">';
		$query = "SELECT * FROM user_level WHERE userid='".mysql_escape_string($_SESSION['Admin']['userid'])."' and utama='4' order by menu "; 
  		$result = mysql_query ($query) or die (mysql_error()); 
  		while($row = mysql_fetch_array($result)) {
			if ($row[menu]=='dtguru') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=guru" class=ver11 >Direktori Guru</a></div>';
			if ($row[menu]=='importguru') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=imguru" class=ver11 >Import Guru</a></div>';
			if ($row[menu]=='dtmengajar') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=mengajar" class=ver11 >Data Mengajar</a></div>';
		}
		echo '</div><div class="LeftMenuline"></div>
<div class="LeftMenuHead" onclick="clickOpenMenu(\'d\'); return false;" style="cursor: pointer;">Data Siswa</div>
<div style="visibility: hidden; position: absolute;" id="SubCat_d">';
		$query = "SELECT * FROM user_level WHERE userid='".mysql_escape_string($_SESSION['Admin']['userid'])."' and utama='5' order by menu "; 
  		$result = mysql_query ($query) or die (mysql_error()); 
  		while($row = mysql_fetch_array($result)) {
			if ($row[menu]=='dtsiswa') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=siswa" class=ver11  >Direktori Siswa</a></div>';
	if ($cmsmember == "ya") {
			if ($row[menu]=='dtortu') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=ortu" class=ver11  >Member Orang Tua</a></div>';
	}
			if ($row[menu]=='dtsiswa') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=carisiswa" class=ver11  >Cari Siswa</a></div>';
			if ($row[menu]=='importsiswa') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=imsiswa" class=ver11 >Import Siswa</a></div>';
	if ($cmsmember == "ya") {
			if ($row[menu]=='membersiswa') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=sismember" class=ver11 >Member Siswa</a></div>';
	}
			if ($row[menu]=='naikkelas') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=naikkelas" class=ver11 >Naik Kelas</a></div>';
			if ($row[menu]=='dtalumni') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=alumni" class=ver11 >Direktori Alumni</a></div>';
		}
		echo '</div><div class="LeftMenuline"></div>';
	if ($cmsmember == "ya") {
		echo '<div class="LeftMenuHead" onclick="clickOpenMenu(\'b\'); return false;" style="cursor: pointer;">Member Komunitas</div>
		<div style="visibility: hidden; position: absolute;" id="SubCat_b">';
		$query = "SELECT * FROM user_level WHERE userid='".mysql_escape_string($_SESSION['Admin']['userid'])."' and utama='6' order by menu "; 
  		$result = mysql_query ($query) or die (mysql_error()); 
  		while($row = mysql_fetch_array($result)) {
			if ($row[menu]=='member') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=member" class=ver11 >Member</a></div><div  style="cursor: pointer;"><a href="admin.php?mode=carimember" class=ver11 >Cari Member</a></div>
			<div  style="cursor: pointer;"><a href="admin.php?mode=stmember" class=ver11 >Status Member</a></div>
			<div  style="cursor: pointer;"><a href="admin.php?mode=pesanmember" class=ver11 >Pesan Member</a></div>
			<div  style="cursor: pointer;"><a href="admin.php?mode=groupmember" class=ver11 >Group Member</a></div>
			<div  style="cursor: pointer;"><a href="admin.php?mode=games" class=ver11 >Games Member</a></div>';
			if ($row[menu]=='pesandepan') echo '<div style="cursor: pointer;"><a href="admin.php?mode=pesan_depan" class=ver11  >Shoutbox Member</a></div>';
			if ($row[menu]=='koordinator') echo '<div  style="cursor: pointer;"><a href="admin.php?mode=moderator" class=ver11 >Admin/Kepsek</a></div>';
			if ($row[menu]=='opini') echo '<div style="cursor: pointer;"><a href="admin.php?mode=project" class=ver11 >Opini Member</a></div>';
		}
		echo '</div><div class="LeftMenuline"></div>';
}

echo '<div class="LeftMenuHead" onclick="clickOpenMenu(\'c\'); return false;" style="cursor: pointer;">Help</div>
<div style="visibility: hidden; position: absolute;" id="SubCat_c">
<div  style="cursor: pointer;"><a href="admin.php?mode=tahap" class=ver11 >Tahapan Pengisian</a></div>
<div  style="cursor: pointer;"><a href="admin.php?mode=help" class=ver11 >Help</a></div>
<div  style="cursor: pointer;"><a href="admin.php?mode=cred" class=ver11 >Webmaster</a></div>
<div  style="cursor: pointer;"><a href="admin.php?mode=daftarweb" class=ver11 >Daftarkan Web</a></div>
</div><div class="LeftMenuline"></div></div>';
	  //-----------------akhir menu ------------------------
	  	echo"</td><td valign='top' width='750'>";
		//------------------------tengah-------------------
require('../lib/config.php');
include('../functions/functions.php');
include('../functions/functions_profil.php');
include('../functions/functions_menu.php');
include('../functions/functions_master.php');
include('../functions/functions_guru.php');
include('../functions/functions_siswa.php');
include('../functions/functions_sim.php');
include('../functions/functions_download.php');
include('../functions/functions_mem.php');
include('../functions/functions_news.php');
include('../functions/functions_info.php');
//include "../functions/functions_home.php";
$adminclass = new adminclass;
$profilclass = new profilclass;
$menuclass = new menuclass;
$masterclass = new masterclass;
$guruclass = new guruclass;
$siswaclass = new siswaclass;
$simclass = new simclass;
$downloadclass = new downloadclass;
$postsclass = new postsclass;
$infoclass = new infoclass;
$userclass = new userclass;
//$homepageclass = new homepageclass;

		if (login_check()==false) {
			echo '<center><div  class="display_red">
			<table cellspacing="0" cellpadding="0" border ="0"> 
			<tr>
			 <td colspan="2"><center>.: Konfirmasi :.</center></td>
			</tr>
            <tr><td ><img src="login.png" width="60" height="60" align="left" ></td>
				<td>Jika tidak ada kegiatan sama sekali, anda akan logout secara otomatis</td>
			</tr>
			</table>
            </div></center>';
			echo "<script src='#' onload='alert(\"Jika tidak ada kegiatan sama sekali, anda akan logout secara otomatis\")'></script>";
			echo "<meta http-equiv=\"refresh\" content=\"1;url=admin.php?logout\">\n";
		}
if (isset($_GET['mode'])) $mode=$_GET['mode'];
else $mode=$_POST['mode'];

switch($mode) {
    default:
    	admin();
    	break;
		//------------------- admin class
/****************************************** Admin Options *******************************************/
// edit personal
	case "editpersonal":
		$adminclass->editpersonal();
    	break;
// save personal
	case "savepersonal":
		$adminclass->savepersonal();
    	break;
    //tambah admin
	case "addadmin":
		if (hakakses("admin")==1) $adminclass->Addadmin();
		else errordata();
    	break;
    //lihat admin
    case "viewadmin":
		if (hakakses("admin")==1) $adminclass->Viewadmin();
		else errordata();
    	break;
	//delete admin
    case "deladmin":
    	if (hakakses("admin")==1) { $adminclass->deladmin();$adminclass->Viewadmin(); }
		else errordata();
    	break;
	//simpan admin
    case "saveadmin":
    	if (hakakses("admin")==1) $adminclass->saveadmin();	            
		else errordata();
    	break;
	//edit admin
    case "editadmin":
    	if (hakakses("admin")==1) $adminclass->editadmin();	            
		else errordata();
    	break;
	//buat admin
    case "createadmin":
		if (hakakses("admin")==1) $adminclass->Createadmin();
		else errordata();
    	break;
//------------ menuclass
   //************************************* posisi menu **************/
	case "edit_posmenu";
		if (hakakses("posisi")==1) $menuclass->edit_posmenu(); 
		else errordata();
    	break;
	case "posmenu";
		if (hakakses("posisi")==1) $menuclass->posmenu();  
    	else errordata();
    	break;
	case "tam_posmenu";
		if (hakakses("posisi")==1) $menuclass->tam_posmenu();  
		else errordata();
    	break;
	case "del_posmenu";
		if (hakakses("posisi")==1) { $menuclass->del_posmenu();  $menuclass->posmenu(); }
		else errordata();
    	break;
	case "sembunyi_posmenu";
		if (hakakses("posisi")==1) { $menuclass->sembunyi_posmenu();  $menuclass->posmenu(); }
		else errordata();
    	break;
	case "konf";
		if (hakakses("posisi")==1) echo "File sudah disimpan...";
		else errordata();
    	break;
//************************ template menu*******************//
	case "edit_tempmenu";
		if (hakakses("template")==1) $menuclass->edit_tempmenu();  
		else errordata();
    	break;
	case "tempmenu";
		if (hakakses("template")==1) $menuclass->tempmenu();  
    	else errordata();
    	break;
	case "tam_tempmenu";
		if (hakakses("template")==1) $menuclass->tam_tempmenu(); 
		else errordata();
    	break;
	case "del_tempmenu";
		if (hakakses("template")==1) { $menuclass->del_tempmenu();  $menuclass->tempmenu(); }
		else errordata();
    	break;
//-------- masterclass
/********************** data kelas ********************/
	case "edit_kelas";
		if (hakakses("kelas")==1) $masterclass->edit_kelas(); 
		else errordata();
    	break;
	case "kelas";
		if (hakakses("kelas")==1) $masterclass->kelas();  
		else errordata();
    	break;
	case "del_kelas";
		if (hakakses("kelas")==1) { $masterclass->del_kelas();  $masterclass->kelas(); }
		else errordata();
    	break;
	case "tam_kelas";
		if (hakakses("kelas")==1) $masterclass->tam_kelas(); 
		else errordata();
    	break;
/********************************************* kategorilink **************************************/
	case "kategorilink";
		if (hakakses("kategori")==1) $masterclass->kategorilink(); 
		else errordata();
    	break;
	case "kategorilink_tam";
		if (hakakses("kategori")==1) $masterclass->kategorilink_tam();  
		else errordata();
    	break;
	case "kategorilink_edit";
		if (hakakses("kategori")==1) $masterclass->kategorilink_edit();  
		else errordata();
    	break;
	case "kategorilink_save";
		if (hakakses("kategori")==1) $masterclass->kategorilink_save();  
		else errordata();
    	break;
	case "kategorilink_hapus";
		if (hakakses("kategori")==1){ $masterclass->kategorilink_hapus();  $masterclass->kategorilink(); }
		else errordata();
    	break;
/********************************************* Program **************************************/
	case "program";
		if (hakakses("program")==1) $masterclass->program();  
		else errordata();
    	break;
	case "program_tam";
		if (hakakses("program")==1) $masterclass->program_tam();  
		else errordata();
    	break;
	case "program_edit";
		if (hakakses("program")==1) $masterclass->program_edit(); 
		else errordata();
    	break;
	case "program_save";
		if (hakakses("program")==1) $masterclass->program_save();  
		else errordata();
    	break;
	case "program_hapus";
		if (hakakses("program")==1) {$masterclass->program_hapus();  $masterclass->program(); }
		else errordata();
    	break;
/********************************************* semester **************************************/
	case "semester";
		if (hakakses("semester")==1) $masterclass->semester();  
		else errordata();
    	break;
	case "semester_tam";
		if (hakakses("semester")==1) $masterclass->semester_tam(); 
		else errordata();
    	break;
	case "semester_edit";
		if (hakakses("semester")==1) $masterclass->semester_edit(); 
		else errordata();
    	break;
	case "semester_save";
		if (hakakses("semester")==1) $masterclass->semester_save(); 
		else errordata();
    	break;
	case "semester_hapus";
		if (hakakses("semester")==1) { $masterclass->semester_hapus(); $masterclass->semester(); }
		else errordata();
    	break;
/********************************************* thajar **************************************/
	case "thajar";
		if (hakakses("semester")==1) $masterclass->thajar();  
		else errordata();
    	break;
	case "thajar_tam";
		if (hakakses("semester")==1) $masterclass->thajar_tam(); 
		else errordata();
    	break;
	case "thajar_edit";
		if (hakakses("semester")==1) $masterclass->thajar_edit(); 
		else errordata();
    	break;
	case "thajar_save";
		if (hakakses("semester")==1) $masterclass->thajar_save(); 
		else errordata();
    	break;
	case "thajar_hapus";
		if (hakakses("semester")==1) { $masterclass->thajar_hapus(); $masterclass->thajar(); }
		else errordata();
    	break;
/********************************************* pelajaran **************************************/
	case "pelajaran";
		if (hakakses("pelajaran")==1) $masterclass->pelajaran();  
		else errordata();
    	break;
	case "pelajaran_tam";
		if (hakakses("pelajaran")==1) $masterclass->pelajaran_tam();
		else errordata();
    	break;
	case "pelajaran_edit";
		if (hakakses("pelajaran")==1) $masterclass->pelajaran_edit();  
		else errordata();
    	break;
	case "pelajaran_save";
		if (hakakses("pelajaran")==1) $masterclass->pelajaran_save();  
		else errordata();
    	break;
	case "pelajaran_hapus";
		if (hakakses("pelajaran")==1) { $masterclass->pelajaran_hapus(); $masterclass->pelajaran(); } 
		else errordata();
    	break;

//---------------- profilclass
/********************************************* profil **********************************************/
	case "edit_profil";
		if (hakakses("profil")==1) $profilclass->edit_profil(); 
		else errordata();
    	break;
	case "html_profil";
		if (hakakses("profil")==1) $profilclass->html_profil(); 
		else errordata();
    	break;
	case "profil";
		if (hakakses("profil")==1) $profilclass->profil(); 
		else errordata();
    	break;
	case "del_profil";
		if (hakakses("profil")==1) { $profilclass->del_profil(); $profilclass->profil(); } 
		else errordata();
    	break;
	case "tam_profil";
		if (hakakses("profil")==1) $profilclass->tam_profil(); 
		else errordata();
    	break;
	case "pindahprofil";
		if (hakakses("profil")==1) { $profilclass->pindahprofil(); $profilclass->profil(); } 
		else errordata();
    	break;		
	case "submenuprofil";
		if (hakakses("profil")==1) { $profilclass->submenuprofil();  } 
		else errordata();
    	break;		
	case "hideprofil";
		if (hakakses("profil")==1) { $profilclass->hideprofil(); $profilclass->profil(); } 
		else errordata();
    	break;		
	//------------------------ homepageclass
	case "lih_home";
		if (hakakses("homepage")==1) $homepageclass->lih_home($id); 
		else errordata();
    	break;
	case "hap_menu";
		if (hakakses("homepage")==1) $homepageclass->hap_menu($kd,$id,$kode);  
		else errordata();
    	break;
	case "lihat_dir";
		if (hakakses("homepage")==1) $homepageclass->lihat_dir(); 
		else errordata();
    	break;
	case "hap_dir";
		if (hakakses("homepage")==1) $homepageclass->hap_dir(); 
		else errordata();
    	break;
	case "mgmp";
		if (hakakses("homepage")==1) $homepageclass->mgmp(); 
		else errordata();
    	break;
	case "hap_mgmp";
		if (hakakses("homepage")==1) $homepageclass->hap_mgmp();  
		else errordata();
    	break;
	case "edit_mgmp";
		if (hakakses("homepage")==1) $homepageclass->edit_mgmp(); 
		else errordata();
    	break;
	case "tam_mgmp";
		if (hakakses("homepage")==1) $homepageclass->tam_mgmp();  
		else errordata();
    	break;
	case "save_mgmp";
		if (hakakses("homepage")==1) $homepageclass->save_mgmp();  
		else errordata();
    	break;
	///-----------admin homepage
	case "addmgmp":
	    if (hakakses("homepage")==1) $homepageclass->Addmgmp();
    	else errordata();
    	break;
    //lihat mgmp
    case "viewmgmp":
    	if (hakakses("homepage")==1) $homepageclass->viewmgmp($user[user_id]);
    	else errordata();
    	break;
	//delete admin
    case "delmgmp":
    	if (hakakses("homepage")==1) { $homepageclass->delmgmp();$homepageclass->viewmgmp($user[user_id]); }
    	else errordata();
    	break;
	//simpan admin
    case "savemgmp":
    	if (hakakses("homepage")==1) { $homepageclass->savemgmp(); }
    	else errordata();
    	break;
	//edit admin
    case "editmgmp":
    	if (hakakses("homepage")==1) $homepageclass->editmgmp();
    	else errordata();
    	break;
	//buat admin
    case "createmgmp":
    	if (hakakses("homepage")==1) { $homepageclass->createmgmp(); }
    	else errordata();
    	break;
// ------------ infoclass -----------------------------
//********************************************* Informasi ********************************************/
	//Buku Tamu
	case "buku_tamu":
    	if (hakakses("bukutamu")==1) $infoclass->buku_tamu();
		else errordata();
    	break;
	case "hapus_buku":
		if (hakakses("bukutamu")==1) { $infoclass->hapus_buku();$infoclass->buku_tamu(); }
		else errordata();
    	break;
	case "buku_jawab":
    	if (hakakses("bukutamu")==1) $infoclass->buku_jawab();
		else errordata();
    	break;	
	case "buku_save":
    	if (hakakses("bukutamu")==1) $infoclass->buku_save();
		else errordata();
    	break;	
	//=============================================pesan depan
	case "pesan_depan":
    	if (hakakses("pesandepan")==1) $infoclass->pesan_depan();
		else errordata();
    	break;
	case "hapus_pesan":
		if (hakakses("pesandepan")==1) { $infoclass->hapus_pesan();$infoclass->pesan_depan(); }
		else errordata();
    	break;
	//==============================================pesan
	case "pesan_alm":
	if ($cmsmember == "ya") {
    	if (hakakses("infoalumni")==1) $infoclass->pesan_alm();
		else errordata();
    } else errordatamember();
    	break;
	case "hapus_alm":
	if ($cmsmember == "ya") {
		if (hakakses("infoalumni")==1) { $infoclass->hapus_alm();$infoclass->pesan_alm(); }
		else errordata();
    } else errordatamember();
    	break;
	//============================================voting
	case "voting":
		if (hakakses("jajak")==1) $infoclass->voting();
		else errordata();
    	break;
	case "del_voting":
		if (hakakses("jajak")==1) { $infoclass->del_voting();$infoclass->voting(); }
		else errordata();
    	break;
	case "tam_voting":
		if (hakakses("jajak")==1) $infoclass->tam_voting();
		else errordata();
    	break;
	case "jawab_voting":
		if (hakakses("jajak")==1) $infoclass->jawab_voting();
		else errordata();
    	break;
	case "sim_voting":
		if (hakakses("jajak")==1) $infoclass->sim_voting();
		else errordata();
    	break;
	case "ak_voting":
		if (hakakses("jajak")==1) { $infoclass->ak_voting();$infoclass->voting(); }
		else errordata();
    	break;
	//=======================================lihat voting
	case "ed_voting":
		if (hakakses("jajak")==1) $infoclass->ed_voting();
		else errordata();
    	break;
	case "simmod_voting":
		if (hakakses("jajak")==1) $infoclass->simmod_voting($edit,$id,$tanya,$jawab,$jml);
		else errordata();
    	break;	
 //-------------------------------- link
	case "link": 
		if (hakakses("link")==1) $infoclass->link_w();
    	else errordata();
    	break;
    case "link_hap":
    	if (hakakses("link")==1)   { $infoclass->link_hap();$infoclass->link_w(); }
    	else errordata();
    	break;
    case "link_tam":
    	if (hakakses("link")==1) $infoclass->link_tam();
    	else errordata();
    	break;        
    case "link_edit":
    	if (hakakses("link")==1) $infoclass->link_edit();
    	else errordata();
    	break;
    case "link_save":
		if (hakakses("link")==1) $infoclass->link_save();
    	else errordata();
    	break; 
	//-------------------------------- banner
	case "banner": 
		if (hakakses("banner")==1) $infoclass->banner();
    	else errordata();
    	break;
	case "banner_ak": 
		if (hakakses("banner")==1) {$infoclass->banner_ak();$infoclass->banner();}
    	else errordata();
    	break;
    case "banner_hap":
    	if (hakakses("banner")==1)  { $infoclass->banner_hap();$infoclass->banner(); }
    	else errordata();
    	break;
    case "banner_tam":
    	if (hakakses("banner")==1) $infoclass->banner_tam();
    	else errordata();
    	break;        
    case "banner_edit":
    	if (hakakses("banner")==1) $infoclass->banner_edit();
    	else errordata();
    	break;
    case "banner_save":
		if (hakakses("banner")==1) $infoclass->banner_save();
    	else errordata();
    	break; 
	// --------------------------------- album galeri
	case "album": 
		if (hakakses("galeri")==1) $infoclass->album();
    	else errordata();
    	break;
    case "album_hap":
    	if (hakakses("galeri")==1) {  $infoclass->album_hap();$infoclass->album(); }
    	else errordata();
    	break;
    case "album_tam":
    	if (hakakses("galeri")==1) $infoclass->album_tam();
    	else errordata();
    	break;        
    case "album_edit":
    	if (hakakses("galeri")==1) $infoclass->album_edit();
    	else errordata();
    	break;   
    case "album_save":
		if (hakakses("galeri")==1) $infoclass->album_save();
    	else errordata();
    	break; 
	//-------------------------------- galeri
	case "galeri": 
		if (hakakses("galeri")==1) $infoclass->galeri();
    	else errordata();
    	break;
    case "galeri_hap":
    	if (hakakses("galeri")==1) {  $infoclass->galeri_hap();$infoclass->galeri(); }
    	else errordata();
    	break;
    case "galeri_tam":
    	if (hakakses("galeri")==1) $infoclass->galeri_tam();
    	else errordata();
    	break;        
    case "galeri_edit":
    	if (hakakses("galeri")==1) $infoclass->galeri_edit();
    	else errordata();
    	break;   
    case "galeri_save":
		if (hakakses("galeri")==1) $infoclass->galeri_save();
    	else errordata();
    	break; 
  //-------------------------------- prestasi
	case "prestasi": 
		if (hakakses("prestasi")==1) $infoclass->prestasi();
    	else errordata();
    	break;	
    case "prestasi_hap":
    	if (hakakses("prestasi")==1)  { $infoclass->prestasi_hap(); $infoclass->prestasi(); }
    	else errordata();
    	break;
    case "prestasi_tam":
    	if (hakakses("prestasi")==1) $infoclass->prestasi_tam();
    	else errordata();
    	break;        
    case "prestasi_edit":
    	if (hakakses("prestasi")==1) $infoclass->prestasi_edit();
    	else errordata();
    	break;    
    case "prestasi_save":
		if (hakakses("prestasi")==1) $infoclass->prestasi_save();
    	else errordata();
    	break; 	
//-------------------------------- info
	case "info": 
		if (hakakses("infosekolah")==1) $infoclass->info();
    	else errordata();
    	break;
    case "info_hap":
   		if (hakakses("infosekolah")==1) { $infoclass->info_hap();$infoclass->info(); }
   		 else errordata();
    	break;
    case "info_tam":
    	if (hakakses("infosekolah")==1) $infoclass->info_tam();
    	else errordata();
    	break;        
    case "info_edit":
    	if (hakakses("infosekolah")==1) $infoclass->info_edit();
    	else errordata();
    	break;
    case "html_info":
    	if (hakakses("infosekolah")==1) $infoclass->html_info();
    	else errordata();
    	break;
    case "info_save":
		if (hakakses("infosekolah")==1) $infoclass->info_save();
    	else errordata();
    	break;    	
	// ------------------------ gambar atas
    case "gbdepan":
    	if (hakakses("gambar")==1) $infoclass->gbdepan() ;
    	else errordata();
    	break;  
    case "gbdepan_tam":
    	if (hakakses("gambar")==1) $infoclass->gbdepan_tam() ;
    	else errordata();
    	break; 
    case "gbdepan_hap":
    	if (hakakses("gambar")==1){ $infoclass->gbdepan_hap() ;$infoclass->gbdepan() ; }
    	else errordata();
    	break; 
    case "gbdepan_save":
    	if (hakakses("gambar")==1) $infoclass->gbdepan_save() ;
    	else errordata();
    	break; 
    case "gbdepan_edit":
    	if (hakakses("gambar")==1) $infoclass->gbdepan_edit() ;
    	else errordata();
    	break; 
//------------- downloadclass
//-------------------------------- soal
	case "soal": 
		if (hakakses("kumpulsoal")==1) $downloadclass->soal();
		else errordata();
    	break;	
    case "soal_hap":
    	if (hakakses("kumpulsoal")==1) { $downloadclass->soal_hap();$downloadclass->soal(); }
		else errordata();
    	break;
    case "soal_tam":
    	if (hakakses("kumpulsoal")==1)$downloadclass->soal_tam();
		else errordata();
    	break;        
    case "soal_edit":
    	if (hakakses("kumpulsoal")==1)$downloadclass->soal_edit();
		else errordata();
    	break;
	//-------------------------------- download
	case "download": 
		if (hakakses("materiajar")==1) $downloadclass->download();
    	else errordata();
    	break;	
    case "download_hap":
    	if (hakakses("materiajar")==1)  { $downloadclass->download_hap();$downloadclass->download(); }
    	else errordata();
    	break;
    case "download_tam":
    	if (hakakses("materiajar")==1) $downloadclass->download_tam();
    	else errordata();
    	break;        
    case "download_edit":
    	if (hakakses("materiajar")==1) $downloadclass->download_edit();
    	else errordata();
    	break;
	//-------------------------------- silabus
	case "silabus": 
		if (hakakses("silabus")==1) $downloadclass->silabus();
    	else errordata();
    	break;
    case "silabus_hap":
    	 if (hakakses("silabus")==1) {  $downloadclass->silabus_hap();$downloadclass->silabus();}
    	else errordata();
    	break;
    case "silabus_tam":
    	if (hakakses("silabus")==1) $downloadclass->silabus_tam();
    	else errordata();
    	break;        
    case "silabus_edit":
    	if (hakakses("silabus")==1) $downloadclass->silabus_edit();
    	else errordata();
    	break;
// ----------------- postsclass --------------------------------
/****************************************** berita *******************************************/
    case "rempost": 
		if (hakakses("berita")==1) $postsclass->remposts();
    	else errordata();
    	break;
    case "delete":
    	if (hakakses("berita")==1)  { $postsclass->delete();$postsclass->remposts();}
    	else errordata();
    	break;
    case "post":
    	if (hakakses("berita")==1) $postsclass->post();
    	else errordata();
    	break;        
    case "modpost":
    	if (hakakses("berita")==1) $postsclass->modpost();
    	else errordata();
    	break;
    case "NewsSave":
    	if (hakakses("berita")==1) $postsclass->NewsSave();
    	else errordata();
    	break;  
    case "ModSave":
    	if (hakakses("berita")==1) $postsclass->modsave();
    	else errordata();
    	break;    
    case "html_news":
    	if (hakakses("berita")==1) $postsclass->html_news();
    	else errordata();
    	break;
//---------------------berita kom
	case "beritakom": 
		if (hakakses("berita")==1) $postsclass->beritakom();
    	else errordata();
    	break;
    case "beritakom_hap":
    	if (hakakses("berita")==1)  { $postsclass->beritakom_hap();$postsclass->beritakom();}
    	else errordata();
    	break;    
//---------------------artikel kom
	case "artikelkom": 
		if (hakakses("artikel")==1) $postsclass->artikelkom();
    	else errordata();
    	break;
    case "artikelkom_hap":
    	if (hakakses("artikel")==1)  { $postsclass->artikelkom_hap();$postsclass->artikelkom();}
    	else errordata();
    	break;
//-------------------------------- artikel
	case "artikel": 
		if (hakakses("artikel")==1) $postsclass->artikel();
    	else errordata();
    	break;
    case "artikel_hap":
    	if (hakakses("artikel")==1)  { $postsclass->artikel_hap();$postsclass->artikel();}
    	else errordata();
    	break;
    case "artikel_tam":
    	if (hakakses("artikel")==1) $postsclass->artikel_tam();
    	else errordata();
    	break;        
    case "artikel_edit":
    	if (hakakses("artikel")==1) $postsclass->artikel_edit();
     	else errordata();
    	break;
    case "artikel_save":
		if (hakakses("artikel")==1) $postsclass->artikel_save();
    	else errordata();
    	break;   
		//-------------------------------- diskusi topik
	case "diskusi2": 
	if ($cmsmember == "ya") {
		if (hakakses("forum")==1) $postsclass->diskusi2();
    	else errordata();
    } else errordatamember();
    	break;
    case "diskusi2_hap":
	if ($cmsmember == "ya") {
    	if (hakakses("forum")==1)   { $postsclass->diskusi2_hap();$postsclass->diskusi2();}
    	else errordata();
    } else errordatamember();
    	break;
		//-------------------------------- diskusi balas
	case "diskusi3": 
	if ($cmsmember == "ya") {
		if (hakakses("forum")==1) $postsclass->diskusi3();
    	else errordata();
    } else errordatamember();
    	break;
    case "diskusi3_hap":
	if ($cmsmember == "ya") {
    	if (hakakses("forum")==1)  { $postsclass->diskusi3_hap();$postsclass->diskusi3();}
    	else errordata();
    } else errordatamember();
    	break;
    case "diskusi2_tam":
	if ($cmsmember == "ya") {
    	if (hakakses("forum")==1) $postsclass->diskusi2_tam();
    	else errordata();
    } else errordatamember();
    	break;        
    case "diskusi2_edit":
	if ($cmsmember == "ya") {
    	if (hakakses("forum")==1) $postsclass->diskusi2_edit();
    	else errordata();
    } else errordatamember();
    	break;   
    case "diskusi2_save":
	if ($cmsmember == "ya") {
		if (hakakses("forum")==1) $postsclass->diskusi2_save();
    	else errordata();
    } else errordatamember();
    	break; 
	//-------------------------------- diskusi 
	case "diskusi": 
	if ($cmsmember == "ya") {
		if (hakakses("forum")==1) $postsclass->diskusi();
    	else errordata();
    } else errordatamember();
    	break;
    case "diskusi_hap":
	if ($cmsmember == "ya") {
    	 if (hakakses("forum")==1) { $postsclass->diskusi_hap();$postsclass->diskusi();}
    	else errordata();
    } else errordatamember();
    	break;
    case "diskusi_tam":
	if ($cmsmember == "ya") {
    	if (hakakses("forum")==1) $postsclass->diskusi_tam();
    	else errordata();
    } else errordatamember();
    	break;        
    case "diskusi_edit":
	if ($cmsmember == "ya") {
    	if (hakakses("forum")==1) $postsclass->diskusi_edit();
    	else errordata();
    } else errordatamember();
    	break;
//-------------------------------- agenda
	case "agenda": 
		if (hakakses("agenda")==1) $postsclass->agenda();
    	else errordata();
    	break;
    case "agenda_hap":
    	if (hakakses("agenda")==1) {  $postsclass->agenda_hap();$postsclass->agenda(); }
    	else errordata();
    	break;
    case "agenda_tam":
    	if (hakakses("agenda")==1) $postsclass->agenda_tam();
    	else errordata();
    	break;
    case "agenda_edit":
    	if (hakakses("agenda")==1) $postsclass->agenda_edit();
    	else errordata();
    	break;
    case "agenda_save":
		if (hakakses("agenda")==1) $postsclass->agenda_save();
    else errordata();
    	break; 
//----------------------- userclass untuk member
	//-------------------------------- username member
	case "member": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) $userclass->member();
    	else errordata();
    } else errordatamember();
    	break;
	case "carimember": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) $userclass->carimember();
    	else errordata();
    } else errordatamember();
    	break;
    case "member_hap":
 	if ($cmsmember == "ya") {
    	if (hakakses("member")==1) {  $userclass->member_hap();
		  $userclass->member() ; }
    	else errordata();
    } else errordatamember();
    	break;
    case "member_valid":
 	if ($cmsmember == "ya") {
    	if (hakakses("member")==1)  { $userclass->member_valid();
		$userclass->member(); }
    	else errordata();
    } else errordatamember();
    	break;
    case "member_mod":
 	if ($cmsmember == "ya") {
    	if (hakakses("member")==1)  { $userclass->member_mod();
		$userclass->member(); }
    	else errordata();
    } else errordatamember();
    	break;
    case "mod_forum":
	if ($cmsmember == "ya") {
     	if (hakakses("member")==1) $userclass->mod_forum();
    	else errordata();
    } else errordatamember();
    	break;
	 case "modforum_hap":
 	if ($cmsmember == "ya") {
    	if (hakakses("member")==1)  { $userclass->modforum_hap();
		$userclass->mod_forum($kode,$id); }
    	else errordata();
    } else errordatamember();
    	break;
    case "member_pass":
 	if ($cmsmember == "ya") {
    	if (hakakses("member")==1) $userclass->member_pass();
    	else errordata();
    } else errordatamember();
    	break;
 //-------------------------------- username moderator
	case "moderator": 
	if ($cmsmember == "ya") {
 		if (hakakses("koordinator")==1) $userclass->moderator();
    	else errordata();
    } else errordatamember();
    	break;
    case "moderator_valid":
 	if ($cmsmember == "ya") {
    	if (hakakses("koordinator")==1)  { $userclass->member_valid();
		$userclass->moderator(); }
    	else errordata();
    } else errordatamember();
    	break;
    case "moderator_pass":
 	if ($cmsmember == "ya") {
    	if (hakakses("koordinator")==1)  { $userclass->member_pass();
		$userclass->moderator(); }
    	else errordata();
    } else errordatamember();
    	break;
 //-------------------------------- username opini
	case "project": 
	if ($cmsmember == "ya") {
 		if (hakakses("opini")==1) $userclass->project();
    	else errordata();
    } else errordatamember();
    	break;
    case "project_valid":
	if ($cmsmember == "ya") {
     	if (hakakses("opini")==1)  { $userclass->project_valid();
		$userclass->project(); }
    	else errordata();
    } else errordatamember();
    	break;
    case "project_hap":
 	if ($cmsmember == "ya") {
    	if (hakakses("opini")==1)  { $userclass->project_hap();
		$userclass->project(); }
    	else errordata();
    } else errordatamember();
    	break;
 //-------------------------------- member status
	case "stmember": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) $userclass->stmember();
    	else errordata();
    } else errordatamember();
    	break;
	case "statushapus": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) { $userclass->statushapus(); $userclass->stmember(); }
    	else errordata();
    } else errordatamember();
    	break;
	case "stkomhapus": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) { $userclass->stkomhapus(); $userclass->stmember(); }
    	else errordata();
    } else errordatamember();
    	break;
	case "pesanmember": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1)  $userclass->pesanmember(); 
    	else errordata();
    } else errordatamember();
    	break;
	case "pesanhapusmem": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1)  { $userclass->pesanhapusmem(); $userclass->pesanmember(); }
    	else errordata();
    } else errordatamember();
    	break;
	case "groupmember": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1)  $userclass->groupmember();
    	else errordata();
    } else errordatamember();
    	break;
	case "hapusgroup": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) { $userclass->hapusgroup(); $userclass->groupmember(); }
    	else errordata();
    } else errordatamember();
    	break;
	//-------------------------------- games
	case "games": 
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) $userclass->games();
    	else errordata();
    } else errordatamember();
    	break;
    case "games_hap":
	if ($cmsmember == "ya") {
     	if (hakakses("member")==1)  { $userclass->games_hap();$userclass->games(); }
    	else errordata();
    } else errordatamember();
    	break;
    case "games_tam":
 	if ($cmsmember == "ya") {
    	if (hakakses("member")==1) $userclass->games_tam();
    	else errordata();
    } else errordatamember();
    	break;        
    case "games_edit":
	if ($cmsmember == "ya") {
     	if (hakakses("member")==1) $userclass->games_edit();
    	else errordata();
    } else errordatamember();
    	break;
    case "games_save":
	if ($cmsmember == "ya") {
 		if (hakakses("member")==1) $userclass->games_save();
    	else errordata();
    } else errordatamember();
    	break; 
//--------------------- guruclass --------------
//-------------------------------- guru
	case "guru": 
		if (hakakses("dtguru")==1) $guruclass->guru();
    	else errordata();
    	break;
    case "guru_hap":
    	 if (hakakses("dtguru")==1)  { $guruclass->guru_hap();$guruclass->guru(); }
    	else errordata();
    	break;
    case "guru_tam":
    	if (hakakses("dtguru")==1) $guruclass->guru_tam();
    	else errordata();
    	break;        
    case "guru_edit":
    	if (hakakses("dtguru")==1) $guruclass->guru_edit();
    	else errordata();
    	break;
    case "guru_save":
		if (hakakses("dtguru")==1) $guruclass->guru_save();
    	else errordata();
    	break; 
	case "guru_member":
	if ($cmsmember == "ya") {	
		if (hakakses("dtguru")==1) $guruclass->guru_member();
    	else errordata();
	}
	else errordatamember();
    	break;   
	case "gurumember_save":
	if ($cmsmember == "ya") {
     	if (hakakses("dtguru")==1) $guruclass->gurumember_save();
    	else errordata();
    } else errordatamember();
    	break; 
	case "gurumember_hap":
	if ($cmsmember == "ya") {
     	if (hakakses("dtguru")==1) { $guruclass->gurumember_hap();$guruclass->guru();}
    	else errordata();
    } else errordatamember();
    	break;     
	case "imguru":
    	if (hakakses("importguru")==1) $guruclass->imguru();
    	else errordata();
    	break;  

//***************************************** data kelas *********************//
	case "edit_mengajar";
		if (hakakses("dtmengajar")==1) $guruclass->edit_mengajar();  
		else errordata();
    	break;
	case "mengajar";
		if (hakakses("dtmengajar")==1) $guruclass->mengajar(); 
		else errordata();
    	break;
	case "mengajar_detail";
		if (hakakses("dtmengajar")==1) $guruclass->mengajar_detail();  
		else errordata();
    	break;
	case "del_mengajar";
		if (hakakses("dtmengajar")==1) { $guruclass->del_mengajar();  $guruclass->mengajar();  }
		else errordata();
    	break;
	case "tam_mengajar";
		if (hakakses("dtmengajar")==1) $guruclass->tam_mengajar(); 
		else errordata();
    	break;
// ---------------------  siswaclass ------------------------
	//--------------------------- orang tua
	case "ortu": 
		if (hakakses("dtortu")==1) $siswaclass->ortu();
    	else errordata();
    	break;	
	case "ortu_tam": 
	if ($cmsmember == "ya") {
		if (hakakses("dtortu")==1) $siswaclass->ortu_tam();
    	else errordata();
    } else errordatamember();
    	break;	
	case "ortu_save": 
	if ($cmsmember == "ya") {
		if (hakakses("dtortu")==1) $siswaclass->ortu_save();
    	else errordata();
    } else errordatamember();
    	break;	
	case "ortu_edit": 
	if ($cmsmember == "ya") {
		if (hakakses("dtortu")==1) $siswaclass->ortu_edit();
    	else errordata();
    } else errordatamember();
    	break;	
	case "ortu_hap": 
	if ($cmsmember == "ya") {
		if (hakakses("dtortu")==1) { $siswaclass->ortu_hap();$siswaclass->ortu();}
    	else errordata();
    } else errordatamember();
    	break;	
	case "sismember": 
	if ($cmsmember == "ya") {
		if (hakakses("membersiswa")==1) $siswaclass->sismember();
    	else errordata();
    } else errordatamember();
    	break;	
	case "sismember_tam": 
	if ($cmsmember == "ya") {
		if (hakakses("membersiswa")==1) $siswaclass->sismember_tam();
    	else errordata();
    } else errordatamember();
    	break;	
	case "sismember_save": 
	if ($cmsmember == "ya") {
		if (hakakses("membersiswa")==1) $siswaclass->sismember_save();
    	else errordata();
    } else errordatamember();
    	break;	
	case "sismember_edit": 
	if ($cmsmember == "ya") {
		if (hakakses("membersiswa")==1) $siswaclass->sismember_edit();
    	else errordata();
    } else errordatamember();
    	break;	
	case "sismember_hap": 
	if ($cmsmember == "ya") {
		if (hakakses("membersiswa")==1) { $siswaclass->sismember_hap();$siswaclass->sismember();}
    	else errordata();
    } else errordatamember();
    	break;	
	case "imsiswa":
    	if (hakakses("importsiswa")==1) $siswaclass->imsiswa();
    	else errordata();
    	break;   
	case "alumni": 
		if (hakakses("dtalumni")==1) $siswaclass->alumni();
    	else errordata();
    	break;
	case "carisiswa": 
		if (hakakses("dtsiswa")==1) $siswaclass->carisiswa();
    	else errordata();
    	break;
	case "siswa": 
		if (hakakses("dtsiswa")==1) $siswaclass->siswa();
    	else errordata();
    	break;
    case "siswa_hap":
    	if (hakakses("dtsiswa")==1) { $siswaclass->siswa_hap();$siswaclass->siswa();}
    	else errordata();
    	break;
    case "siswa_tam":
    	if (hakakses("dtsiswa")==1) $siswaclass->siswa_tam();
    	else errordata();
    	break;        
    case "siswa_edit":
    	if (hakakses("dtsiswa")==1) $siswaclass->siswa_edit();
   		else errordata();
    	break;
    case "siswa_save":
		if (hakakses("dtsiswa")==1) $siswaclass->siswa_save();
    	else errordata();
    	break; 
    case "naikkelas":
    	if (hakakses("naikkelas")==1) $siswaclass->naikkelas();
    	else errordata();
    	break;  
    case "pindahkelas":
    	if (hakakses("naikkelas")==1)  $siswaclass->pindahkelas();
    	else errordata();
    	break; 
    case "alumni_update":
    	if (hakakses("dtalumni")==1) { $siswaclass->alumni_update();$siswaclass->alumni();}
    	else errordata();
    	break;  
// -------------------------- simclass -------------------------
//--------------------------------sim laporan
	case "datalaporan":
	if ($cmssim == "ya") {	
    	if (hakakses("dtlaporan")==1) $simclass->datalaporan();
    	else errordata();
	}
	else {errordatasim();}
    	break;

	case "datalaporhapus":
	if ($cmssim == "ya") {	
    	if (hakakses("dtlaporan")==1) { $simclass->datalaporhapus(); $simclass->datalaporan(); }
    	else errordata();
	}
	else {errordatasim();}
    	break;
//--------------------------------sim data materi tugas
	case "datatugas":
	if ($cmssim == "ya") {	
    	if (hakakses("dtmateri")==1) $simclass->datatugas();
    	else errordata();
	}
	else {errordatasim();}
    	break;

	case "datatughapus":
	if ($cmssim == "ya") {	
    	if (hakakses("dtmateri")==1) {$simclass->datatughapus();$simclass->datatugas();}
    	else errordata();
	}
	else {errordatasim();}
    	break;
//--------------------------------sim data nilai
	case "datanilai":
	if ($cmssim == "ya") {	
    	if (hakakses("dtnilai")==1) $simclass->datanilai();
    	else errordata();
	}
	else {errordatasim();}
    	break;
	case "datanilhapus":
	if ($cmssim == "ya") {	
    	if (hakakses("dtnilai")==1) {$simclass->datanilhapus();$simclass->datanilai();}
    	else errordata();
	}
	else {errordatasim();}
    	break;
//--------------------------------sim data spp/ dsp
	case "dataspp":
	if ($cmssim == "ya") {	
    	if (hakakses("dtspp")==1) $simclass->dataspp();
    	else errordata();
	}
	else {errordatasim();}
    	break;
	case "dataspp_save":
	if ($cmssim == "ya") {	
    	if (hakakses("dtspp")==1) $simclass->dataspp_save();
    	else errordata();
	}
	else {errordatasim();}
    	break;
//-------------------------------- sim absensi
	case "databsen":
	if ($cmssim == "ya") {	
    	if (hakakses("dtabsensi")==1) $simclass->databsen();
    	else errordata();
	}
	else {errordatasim();}
    	break;
	case "detailabsen":
	if ($cmssim == "ya") {	
    	if (hakakses("dtabsensi")==1) $simclass->detailabsen();
    	else errordata();
	}
	else {errordatasim();}
    	break;
	case "saveabsen":
	if ($cmssim == "ya") {	
    	if (hakakses("dtabsensi")==1) $simclass->saveabsen();
    	else errordata();
	}
	else {errordatasim();}
    	break;
	case "importabsen":
	if ($cmssim == "ya") {	
    	if (hakakses("dtabsensi")==1) $simclass->importabsen();
    	else errordata();
	}
	else {errordatasim();}
    	break;

//---------------------------------------sim ----- bp bk
	case "gurubk":
	if ($cmssim == "ya") {	
    	if (hakakses("dtbpbk")==1) $simclass->gurubk();
    	else errordata();
	}
	else {errordatasim();}
    	break;
	case "gurubkhapus":
	if ($cmssim == "ya") {	
    	if (hakakses("dtbpbk")==1) {$simclass->gurubkhapus(); $simclass->gurubk();}
    	else errordata();
	}
	else {errordatasim();}
    	break;
		/****************************************** Misc Options ********************************************/
    case "cred":
    	$profilclass->cred();
    	break;
    case "tahap":
    	$profilclass->tahap();
    	break;
    case "help":
    	$profilclass->help();
    	break;
    case "daftarweb":
    	$profilclass->daftarweb();
    	break;
  }
		
		//----------------------tutup-------------
		echo "</td></tr>
		<tr><td colspan='2' bgcolor='#4c96da' height=50 ><center><font class='adminhead'>$webhost.Website engine's code is copyright © 2010 <a href='mailto:divisidata@gmail.com'  >Tim Balitbang Kemdikbud</a> versi 3.5.3<br><br></font></center></td></tr></table>";
	}
}

echo "</body>
</html>";

?>