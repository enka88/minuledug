<?php
//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//}

// tambahan agar guru yang jadi  kepesek/admin bisa akses by Ansari Saleh Ahmar 10 Januari 2012
// if ($level=='Guru' or $level=='Admin')

function head() {
include "koneksi.php";
$userid = unhex($_GET['kode'],$noacak);
if (is_null($userid)) $userid = $_SESSION['User']['userid'];
$head ='<style type="text/css">';
$isi = 'body { /* background gambar */
	font-family: "Arial", serif;
	font-size: 76%;
	margin-top: 0px;
	color:#666666;
	;
}
#konten {   /* lebar layout web member */
	width:900px;						
	margin-left: auto;
	margin-right: auto;
	background-color:#FFFFFF;			
}';
    $query = mysql_query("SELECT bgbody FROM t_member_custom where userid='". mysql_real_escape_string($userid)."'") or die("Query failed");
    if($r = mysql_fetch_array($query)) {
		if ($r[bgbody]<>'' ) {
			$isi=$r[bgbody];
		}
	}
	
$head .= $isi.'</style>';
return $head;
}

function isi() {
include "koneksi.php";
$id=$_GET['id'];
if ($id=='') $id=$_POST['id'];
$level = $_SESSION['User']['ket'];
	switch ($id) {
		case "profil";  // ----------------------------------- profil pribadi
			include ("../functions/member_profil.php");
			$isi = kiri_profil();
			$isi .= profil();
			$isi .= kanan();
			break;
		case "infoprofil";
			include ("../functions/member_profil.php");
			$isi = kiri_profil();
			$isi .= infoprofil();
			$isi .= kanan();
			break;
		case "editprofil";
			include ("../functions/member_profil.php");
			$isi = kiri_profil();
			$isi .= editprofil();
			$isi .= kanan();
			break;
		case "sthapus";
			include ("../functions/member_profil.php");
			sthapus();
			$isi = kiri_profil();
			$isi .= profil();
			$isi .= kanan();
			break;	
		case "komhapus";
			include ("../functions/member_profil.php");
			komhapus();
			$isi = kiri_profil();
			$isi .= profil();
			$isi .= kanan();
			break;	
		case "lih_profil";  // ----------------------------------- profil teman
			include ("../functions/member_profil.php");
			if ($_SESSION['User']['userid']==unhex($_GET['kode'],$noacak)) { $isi = kiri_profil();$isi .= profil(); }
			else { $isi = kiri_profil_lain();$isi .= lih_profil(); }
			$isi .= kanan();
			break;
		case "infoprofilmember";
			include ("../functions/member_profil.php");
			$isi = kiri_profil_lain();
			$isi .= infoprofilmember();
			$isi .= kanan();
			break;
		case "koleksifotomember";
			include ("../functions/member_foto.php");
			$isi = kiri_profil_lain();
			$isi .= koleksifotomember();
			$isi .= kanan();
			break;
		case "koleksifoto";  // ----------------------------------- foto
			include ("../functions/member_foto.php");
			$isi = kiri_profil();
			$isi .= koleksifoto();
			$isi .= kanan();
			break;
		case "koleksifotodetail";
			include ("../functions/member_foto.php");
			if (!empty($_GET['kode'])) $userid=unhex($_GET['kode'],$noacak);
			if ($userid==$_SESSION['User']['userid']) { $isi = kiri_profil(); $isi .= koleksifotodetail(); }
			else { $isi = kiri_profil_lain();$isi .= koleksifotodetailmember(); }
			//$isi .= kanan();
			break;
		case "uploadfoto";
			include ("../functions/member_foto.php");
			$isi = kiri_profil();
			$isi .= uploadfoto();
			$isi .= kanan();
			break;
		case "fotofacebook";  // ----------------------------------- foto facebook
			include ("../functions/member_facebook.php");
			$isi = kiri_profil();
			$isi .= fotofacebook();
			$isi .= kanan();
			break;
		case "copyfoto";  // ----------------------------------- foto facebook
			include ("../functions/member_facebook.php");
			$isi = kiri_profil();
			$isi .= copyfoto();
			$isi .= kanan();
			break;
		case "kirimwall";  // ----------------------------------- kirims status facebook
			include ("../functions/member_facebook.php");
			$isi = kiri_profil();
			$isi .= kirimwall();
			$isi .= kanan();
			break;  
		case "logprofil";
			include ("../functions/member_profil.php");
			$isi = kiri_profil();
			$isi .= logprofil();
			$isi .= kanan();
			break;
		case "loghapus";
			include ("../functions/member_profil.php");
			loghapus();
			$isi = kiri_profil();
			$isi .= logprofil();
			$isi .= kanan();
			break;
		case "customprofil";
			include ("../functions/member_profil.php");
			$isi = kiri_profil();
			$isi .= customprofil();
			$isi .= kanan();
			break;
		case "simpancustom";
			include ("../functions/member_profil.php");
			$isi = kiri_profil();
			$isi .= simpancustom();
			$isi .= kanan();
			break;
		case "imgprofil";
			include ("../functions/member_foto.php");
			$isi = kiri_profil();
			$isi .= imgprofil();
			//$isi .= kanan();//batas seleksi lama		
			break;
		case "member";  // ----------------------------------- teman dan cari member
			include ("../functions/member_teman.php");
			$isi = kiri_profil();
			$isi .= member();
			$isi .= kanan();
			break;
		case "teman"; 
			include ("../functions/member_teman.php");
			$isi = kiri_profil();
			$isi .= teman();
			$isi .= kanan();
			break;	
		case "temanlain";
			include ("../functions/member_teman.php");
			$isi = kiri_profil_lain();
			$isi .= temanlain();
			$isi .= kanan();
			break;			
		case "permintaan";
			include ("../functions/member_teman.php");
			$isi = kiri_profil();
			$isi .= permintaan();
			$isi .= kanan();
			break;	
		case "tolakteman";
			include ("../functions/member_teman.php");
			$isi = kiri_profil();
			$isi .= tolakteman();
			$isi .= kanan();
			break;	
		case "undangan";
			include ("../functions/member_group.php");
			$isi = kiri_profil();
			$isi .= undangan();
			$isi .= kanan();
			break;	
		case "lihgroup";
			include ("../functions/member_group.php");
			$isi = kiri_profil();
			$isi .= lihgroup();
			$isi .= kanan();
			break;								
		case "group";  // ----------------------------------- GROUP
			include ("../functions/member_group.php");
			$isi = group();
			$isi .= kanangroup();
			break;
		case "infogroup";
			include ("../functions/member_group.php");
			$isi = infogroup();
			$isi .= kanangroup();
			break;	
		case "anggotagroup";
			include ("../functions/member_group.php");
			$isi = anggotagroup();
			$isi .= kanangroup();
			break;	
		case "undangmember";
			include ("../functions/member_group.php");
			$isi = undangmember();
			$isi .= kanangroup();
			break;	
		case "undangsimpan";
			include ("../functions/member_group.php");
			$isi = undangsimpan();
			$isi .= kanangroup();
			break;		
		case "diskusigroup";
			include ("../functions/member_group.php");
			$isi = diskusigroup();
			$isi .= kanangroup();
			break;	
		case "topikdiskusigp";
			include ("../functions/member_group.php");
			$isi = topikdiskusigp();
			$isi .= kanangroup();
			break;		
		case "pesan";  // ----------------------------------- pesan
			include ("../functions/member_pesan.php");
			$isi = kiri_profil();
			$isi .= pesan();
			$isi .= kanan();
			break;	
		case "hapuspesan";
			include ("../functions/member_pesan.php");
			hapuspesan();
			$isi = kiri_profil();
			$isi .= pesan();
			$isi .= kanan();
			break;	
		case "lihpesan";
			include ("../functions/member_pesan.php");
			$isi = kiri_profil();
			$isi .= lihpesan();
			$isi .= kanan();
			break;		
		case "kirimpesan";
			include ("../functions/member_pesan.php");
			$isi = kiri_profil();
			$isi .= kirimpesan();
			$isi .= kanan();
			break;	
		case "forum"; // -----------------------------------forum diskusi
			include ("../functions/member_forum.php");
			$isi = kiri_profil();
			$isi .= forum();
			break;		
		case "lihattopik";
			include ("../functions/member_forum.php");
			$isi = kiri_profil();
			$isi .= lihattopik();
			break;	
		case "lihatbalasan";
			include ("../functions/member_forum.php");
			$isi = kiri_profil();
			$isi .= lihatbalasan();
			break;		
		case "tambahtopik";
			include ("../functions/member_forum.php");
			$isi = kiri_profil();
			$isi .= tambahtopik();
			break;		
		case "balastopik";
			include ("../functions/member_forum.php");
			$isi = kiri_profil();
			$isi .= balastopik();
			break;		
		case "infoalumni"; // =========================== info almuni
			include ("../functions/member_alumni.php");
			$isi = kiri_profil();
			$isi .= infoalumni();
			break;	
		case "siminfoalumni"; 
			include ("../functions/member_alumni.php");
			$isi = kiri_profil();
			$isi .= siminfoalumni();
			break;	
		case "opinisaya";  //=============================== opini saya
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			$isi .= opinisaya();
			break;	
		case "tamopini";  
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			$isi .= tamopini();
			break;	
		case "simopini"; 
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			$isi .= simopini();
			break;	
		case "editopini";  
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			$isi .= editopini();
			break;	
		case "simeditopini"; 
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			$isi .= simeditopini();
			break;	
		case "hapusopini"; 
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			hapusopini();
			$isi .= opinisaya();
			break;	
		case "veriopini"; 
			include ("../functions/member_opini.php");
			$isi = kiri_profil();
			$isi .= veriopini();
			break;	
		case "lihopini"; 
			include ("../functions/member_opini.php");
			if (!empty($_GET['kode'])) {
				$userid=unhex($_GET['kode'],$noacak);
				if ($userid==$_SESSION['User']['userid'])  $isi = kiri_profil(); 
				else $isi = kiri_profil_lain();
			}
			else $isi = kiri_profil(); 
			$isi .= lihopini(); 
			break;	
		case "opiniteman"; 
			include ("../functions/member_opini.php");
			$isi = kiri_profil_lain();
			$isi .= opiniteman();
			break;	
		case "tamartikel";   // -------------------------------- tambah artikel
			include ("../functions/member_artikel.php");
			$isi = kiri_profil();
			$isi .= tamartikel();
			break;	
		case "simartikel"; 
			include ("../functions/member_artikel.php");
			$isi = kiri_profil();
			$isi .= simartikel();
			break;					
		case "games"; 
			include ("../functions/member_games.php");
			$isi = kiri_profil();
			$isi .= games();
			break;	
		case "maingames"; 
			include ("../functions/member_games.php");
			$isi = kiri_profil();
			$isi .= maingames();
			break;
		case "tamdownload";   // -------------------------------- tambah materi ajar
			include ("../functions/member_materi.php");
			$isi = kiri_profil();
			$isi .= tamdownload();
			break;	
		case "downloadsave";  
			include ("../functions/member_materi.php");
			$isi = kiri_profil();
			$isi .= downloadsave();
			break;	
		case "tamsoal";   // -------------------------------- tambah soal 
			include ("../functions/member_materi.php");
			$isi = kiri_profil();
			$isi .= tamsoal();
			break;
		case "soalsave";   
			include ("../functions/member_materi.php");
			$isi = kiri_profil();
			$isi .= soalsave();
			break;		
		case "datanilai": //------------------------data SIM siswa dan orangtua
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil(); 
			if ($level=="Siswa" or $level=="Orang Tua") $isi .=datanilai(); //-----nilai
			else $isi .=user_gagal();
			break;	
		case "dataabsen":
			include ("../functions/fungsi_absen.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Orang Tua")	$isi .=dataabsen();
			else $isi .=user_gagal();
			break;
		case "rekapabsen":
			include ("../functions/fungsi_absen.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Orang Tua") $isi .=rekapabsen();
			else $isi .=user_gagal();
			break;
		case "databk":
			include ("../functions/fungsi_bk.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Orang Tua") $isi .=databk();
			else $isi .=user_gagal();
			break;
		case "dataspp":
			include ("../functions/fungsi_spp.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Orang Tua")	$isi .=dataspp();
			else $isi .=user_gagal();
			break;
		case "datatugas":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Orang Tua") $isi .=datatugas();
			else $isi .=user_gagal();
			break;
		case "tugasdetail":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Orang Tua")	$isi .=tugasdetail();
			else $isi .=user_gagal();
			break;
		case "simmateri":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Siswa" or $level=="Guru" or $level=="Admin")	$isi .=simmateri(); // Tambahan Ansari 22/11/2012
			else $isi .=user_gagal();
			break;
		case "guruabsen": //---------------------------------  SIM login Guru------------
			include ("../functions/fungsi_absen.php");
			$isi = kiri_profil();
			if ($level=="Guru"  or $level=="Admin") $isi .=guruabsen();
			else $isi .=user_gagal();
			break;
		case "guruabsendetail": //---detail absensi
			include ("../functions/fungsi_absen.php");
			$isi = kiri_profil();
			if ($level=="Guru"  or $level=="Admin") $isi .=guruabsendetail();
			else $isi .=user_gagal();
			break;
		case "guruspp": // ------------------lihat spp/dsp
			include ("../functions/fungsi_spp.php");
			$isi = kiri_profil();
			if ($level=="Guru"  or $level=="Admin") $isi .=guruspp();
			else $isi .=user_gagal();
			break;
		case "gurutugas": //---------------------------------- tugas
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Guru"  or $level=="Admin")	$isi .=gurutugas();
			else $isi .=user_gagal();
			break;
		case "gurutughapus":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Guru"  or $level=="Admin") $isi .=gurutughapus();
			else $isi .=user_gagal();
			break;
		case "gurutugtambah":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin") $isi .=gurutugtambah();
			else $isi .=user_gagal();
			break;
		case "gurutugedit":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutugedit();
			else $isi .=user_gagal();
			break;	
		case "gurutugmasuk":
			include ("../functions/fungsi_tugas.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin") $isi .=gurutugmasuk();
			else $isi .=user_gagal();
			break;	
		case "gurulap"://--------------------- laporan guru ke kepsek
			include ("../functions/fungsi_laporan.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurulap();
			else $isi .=user_gagal();
			break;
		case "gurulaphapus":
			include ("../functions/fungsi_laporan.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin"){$isi .=gurulaphapus(); $isi .=gurulap(); }
			else $isi .=user_gagal();
			break;	
		case "gurulaptambah":
			include ("../functions/fungsi_laporan.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurulaptambah();
			else $isi .=user_gagal();
			break;		
		case "gurulapedit":
			include ("../functions/fungsi_laporan.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurulapedit();
			else $isi .=user_gagal();
			break;	
		case "gurunilai": //------------------- masukan nilai guru
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurunilai();
			else $isi .=user_gagal();
			break;	
	
		case "guruniltambah":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin") $isi .=guruniltambah();
			else $isi .=user_gagal();
			break;	
	
		case "guruniltam2":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=guruniltam2();
			else $isi .=user_gagal();
			break;
		case "simnilai":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin") $isi .=simnilai();
			else $isi .=user_gagal();
			break;
		case "gurunilhapus":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurunilhapus();
			else $isi .=user_gagal();
			break;	
		case "guruniledit":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=guruniledit();
			else $isi .=user_gagal();
			break;	
		case "imporexcel":
			include ("../functions/impornilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=impornilai();
			else $isi .=user_gagal();
			break;
		case "nilaiwali": // ---------------------- nilai guru wali kelas
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=nilaiwali();
			else $isi .=user_gagal();
			break;
		case "nilaiwalilihat":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin") $isi .=nilaiwalilihat();
			else $isi .=user_gagal();
			break; 
		case "nilaiadmin": //------------------ nilai admin
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Admin" ) $isi .=nilaiadmin();
			else $isi .=user_gagal();
			break;
		case "nilaiadminlihat":
			include ("../functions/fungsi_nilai.php");
			$isi = kiri_profil();
			if ($level=="Admin" ) $isi .=nilaiadminlihat();
			else $isi .=user_gagal();
			break;
		case "gurubk": //------------------------- input data BK guru
			include ("../functions/fungsi_bk.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubk();
			else $isi .=user_gagal();
			break;	
		case "gurubkhapus":
			include ("../functions/fungsi_bk.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubkhapus();
			else $isi .=user_gagal();
			break;		
		case "gurubktambah":
			include ("../functions/fungsi_bk.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin") $isi .=gurubktambah();
			else $isi .=user_gagal();
			break;		
		case "gurubkedit":
			include ("../functions/fungsi_bk.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubkedit();
			else $isi .=user_gagal();
			break;	
		case "gurubk_save":
			include ("../functions/fungsi_bk.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubk_save();
			else $isi .=user_gagal();
			break;	
		case "adminspp": // --------------------- data spp Admin
			include ("../functions/fungsi_spp.php");
			$isi = kiri_profil();
			if ($level=="Admin" or $level=="Admin") $isi .=adminspp();
			else $isi .=user_gagal();
			break;	
		case "adminlap": // ------------------ data laporan admin
			include ("../functions/fungsi_laporan.php");
			$isi = kiri_profil();
			if ($level=="Admin" or $level=="Admin")	$isi .=adminlap();
			else $isi .=user_gagal();
			break;
		case "adminstatus":
			include ("../functions/fungsi_laporan.php");
			$isi = kiri_profil();
			if ($level=="Admin" ) $isi .=adminstatus();
			else $isi .=user_gagal();
			break;	
// penambahan hendy
        case "siswatest":
			include ("../functions/member_test.php");
			$isi = kiri_profil();
			if ($level=="Siswa") $isi .=datatest();
			else $isi .=user_gagal();
			break;
       case "masuktest"; 
            include ("../functions/member_test.php");
			$isi = kiri_profil();
			if ($level=="Siswa" ) $isi .=masuk();
			else $isi .=user_gagal();
			break;	          
       	case "kerjakan"; 
            include ("../functions/member_test.php");
			if ($level=="Siswa") $isi .=kerjakan();
			else $isi .=user_gagal();
			break;
        case "selesaites"; 
            include ("../functions/member_test.php");
			$isi = kiri_profil();
			if ($level=="Siswa") $isi .=selesaites();
			else $isi .=user_gagal();
			break;
        case "v_nilai"; 
            include ("../functions/member_test.php");
			$isi = kiri_profil();
			if ($level=="Siswa") $isi .= vnilai();
			else $isi .=user_gagal();
			break;
        case "hasildetail"; 
            include ("../functions/member_test.php");
			$isi = kiri_profil();
			if ($level=="Siswa") $isi .= hasildetail();
			else $isi .=user_gagal();
			break;	  
        case "v_detail"; 
            include ("../functions/member_test.php");
			$isi = kiri_profil();
			if ($level=="Siswa") $isi .= vdetail();
			else $isi .=user_gagal();
			break;	  	
// menambah menu pembelajaran
        case "gurubelajar"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubelajar();
			else $isi .=user_gagal();
			break;  
        case "gurubeltambah"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubeltambah();
			else $isi .=user_gagal();
			break;    
        case "gurubelsimpan"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubelsimpan();
			else $isi .=user_gagal();
			break;    
        case "gurubelhapus"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubelhapus();
			else $isi .=user_gagal();
			break;  
        case "gurubeledit"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubeledit();
			else $isi .=user_gagal();
			break;  
        case "gurubeleditsimpan"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubeleditsimpan();
			else $isi .=user_gagal();
			break;  
        case "gurubeldetail"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubeldetail();
			else $isi .=user_gagal();
			break;  
        case "gurusumberedit"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurusumberedit();
			else $isi .=user_gagal();
			break; 
        case "gurusumberhapus"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurusumberhapus();
			else $isi .=user_gagal();
			break;
        case "siswabelajar"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			$isi .= siswabelajar();
			break; 
        case "siswabeldetail"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			$isi .= siswabeldetail();
			break; 
        case "gurubellog"; 
			include ("../functions/fungsi_belajar.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubellog();
			else $isi .=user_gagal();
			break; 
        case "gurutest"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutest();
			else $isi .=user_gagal();
			break; 
        case "gurubanksoal"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurubanksoal();
			else $isi .=user_gagal();
			break; 
        case "gurusoaltam"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurusoaltam();
			else $isi .=user_gagal();
			break; 
        case "gurusoalsave"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurusoalsave();
			else $isi .=user_gagal();
			break;
        case "gurusoalhapus"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurusoalhapus();
			else $isi .=user_gagal();
			break; 
        case "gurutesttam"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutesttam();
			else $isi .=user_gagal();
			break; 
        case "gurutestsave"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestsave();
			else $isi .=user_gagal();
			break; 
        case "gurutesthapus"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutesthapus();
			else $isi .=user_gagal();
			break; 
        case "gurutestdetail"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestdetail();
			else $isi .=user_gagal();
			break; 
        case "gurutestsoal"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestsoal();
			else $isi .=user_gagal();
			break; 
        case "gurutestpilih"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestpilih();
			else $isi .=user_gagal();
			break;
        case "gurutestpilihhap"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestpilihhap();
			else $isi .=user_gagal();
			break; 
        case "gurutestnilai"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestnilai();
			else $isi .=user_gagal();
			break; 
        case "gurutestnilaihap"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestnilaihap();
			else $isi .=user_gagal();
			break; 
        case "gurutestcetak"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestcetak();
			else $isi .=user_gagal();
			break; 
        case "jsdisabled"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			$isi .=jsdisabled();
			break; 
        case "gurutestanalisa"; 
			include ("../functions/fungsi_gurutest.php");
			$isi = kiri_profil();
			if ($level=="Guru" or $level=="Admin")	$isi .=gurutestanalisa();
			else $isi .=user_gagal();
			break; 
		default:
			include ("../functions/member_depan.php");
            $isi = kiri_profil();
			//$isi .= kiri();
			$isi .= depan();
			$isi .= kanan();
			break;
		}		
		return $isi;
}


?>