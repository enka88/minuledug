<?php

//if(!defined("CMSBalitbang")) {
//	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
//} 

// fungsi member pendaftaran
// function edit profil sendiri
function daftar() {
include "koneksi.php";
include "fungsi_negara.php";
include "fungsi_pass.php";
$cetak .='<script type="text/javascript" src="js/jquery.validate.pack.js" ></script>
<script type="text/javascript">

$(document).ready(function() {
	
	$("#formID").validate({
		rules: {
			password: {
				required: true,
				minlength: 6
			},
			confirm_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
			nfile: {
				required: true,
				accept:"jpg"
			}
		},
		messages: {
			email: {
				required: "E-mail harus diisi",
				email: "Masukkan E-mail yang valid",
				remote: jQuery.validator.format("Email yang anda masukan sudah terdaftar.")
			},
			username: {
				required: "Username harus diisi",
				remote: jQuery.validator.format("Username yang anda masukan tidak valid atau sudah terdaftar.")
			},
			nfile: {
				required: "File harus diisi",
				accept: "Format file salah, seharusnya format Gambar JPG"
			},
			password: {
				required: "Password harus diisi kembali",
				minlength: "Password minimal 6 karakter"
			},
			confirm_password: {
				required: "Password harus diisi kembali",
				minlength: "Password minimal 6 karakter",
				equalTo: "Password tidak sama dengan yang diatas"
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("td"));
		},
		submitHandler: function() {
		
		var dataString = \'userid=\'+ $("#userid").val() + \'&jenis=\'+ $("#jenis").val() + \'&neg=\'+ $("#neg").val() + \'&name=\'+ $("#name").val() + \'&kelamin=\'+ $("#kelamin").val() + \'&nis=\'+ $("#nis").val() + \'&kelas=\' + $("#kelas").val() + \'&email=\'+ $("#email").val() + \'&username=\'+ $("#username").val() + \'&password=\'+ $("#password").val() + \'&pertanyaan=\'+ $("#pertanyaan").val() + \'&jawaban=\'+ $("#jawaban").val() + \'&hari=\'+ $("#hari").val() + \'&bulan=\'+ $("#bulan").val() + \'&tahun=\'+ $("#tahun").val() + \'&kerja=\'+ $("#kerja").val() + \'&alamat=\'+ $("#alamat").val() + \'&sekolah=\'+ $("#sekolah").val() + \'&telp=\'+ $("#telp").val()+ \'&blog=\'+ $("#blog").val() + \'&tentang=\'+ $("#tentang").val()+ \'&country=\'+ $("#country").val()+ \'&stprofil=\'+ $("#stprofil").val()+ \'&stblog=\'+ $("#stblog").val()+ \'&code=\'+ $("#code").val();
		$.ajax({type: "POST",url: "membersave.php",data: dataString,cache: false,
		success: function(html){$("#hasil").append(html);$("#boxpesan").hide();}});
		
		}
		
	});
})
</script>';
$cetak .="<script type=\"text/javascript\">
$(document).ready(function()
{
$('#jenis').click(function(){

var element = $(this);
var jenis = $('#jenis').val();
if (jenis=='Alumni') {
	$('#target').show();
}
else {
	$('#target').hide();
}

return false;});});
</script> ";
 		$query1 = mysql_query ("SELECT * FROM t_kelas order by kelas");
		while($r=mysql_fetch_array($query1)) {
			if ($r[kelas]==$row[kelas]) $kls .="<option value='$r[kelas]' selected>$r[kelas]</option>";
			else $kls .="<option value='$r[kelas]'>$r[kelas]</option>";
		}
	$cetak .="<div id='hasil' ></div><div id='boxpesan' >
    <form name='formID' id='formID' action='daftar.php' method='post'   >
	<input type=hidden name=userid id=userid value='".hex("simtambah,",$noacak)."' >
	<table border=0 width='100%' cellspacing='2' cellpadding='2' id=tablebaru >
	<tr><td colspan=3 bgcolor='#BDC5CC'><div style='float:right;margin-right:10px;margin-top:10px' ><a href='index.php' id='button2' >&nbsp; Login &nbsp;</a></div><img src='../images/icon21.png' width='100' height='100' style='margin:0 20px 0 10px' align=left > <h1>Pendaftaran Member - ".$nmsekolah."</h1>
	Silahkan Anda isi form dibawah ini dengan benar dan jujur. <br><br><br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Prosedur pendaftaran untuk Siswa dan Orang Tua/Wali sebagai berikut : </b><br>
<ol>
<li>Siswa/Orang tua dapat menghubungi langsung di sekolah melalui Tim IT $nmsekolah 
<li>Isi formulir pendaftaran khusus member.
<li>Setelah didaftarkan langsung oleh Admin, silahkan cek email Anda untuk verifikasi data.
<li>Silahkan login ke sistem member tersebut.
<li>Atau pendaftaran melalui email <b>$webmail </b>, dengan menyertakan formulir yang sudah Anda isi dibawah ini<br>Download Formulir <a href='../formulir.doc' >di sini</a>
<li>Tunggu konfirmasi validasi data Anda melalui email Anda.<br>
</ol></td></tr>";

	$cetak .="<tr><td colspan=3 class='td0' >Informasi Khusus</td></tr>	
	<tr >
		<td align=right>Nama</td>
		<td>:</td>
		<td>";
	 $cetak .="<input type='text' name='name' value='$nama' id='name' size=20 maxlength='30' class='required' title='Nama harus diisi' >";
	
	$cetak .="</td>
	</tr>
	<tr >
		<td align=right valign=top >Kelamin</td>
		<td valign=top >:</td>
		<td><SELECT name='kelamin' id='kelamin' class='required' title='Jenis kelamin harus dipilih' >
		<OPTION value='' selected>[Pilih]</option><OPTION $k1 value='m' >Laki-laki<OPTION $k2 value='f' >Perempuan</OPTION></SELECT>
		</td>
	</tr>
	<tr >
		<td align='right' valign='top' >Jenis Member</td>
		<td valign='top' >:</td>
		<td><select name='jenis' id='jenis' ><option value='Tamu'>Tamu</option><option value='Alumni'>Alumni</option></select>
	<div id='target' style='display:none' >Tahun Angkatan <input  id='kelas' type=text name=kelas value='$row[kelas]' size=15  maxlength='4' title='Tahun Lulus harus diisi' ></div></td>
	</tr>";
	
	$cetak .="<tr><td colspan=3  class='td0'>Informasi Login</td></tr>
	<tr >
		<td align='right' valign='top' >Username ID</td>
		<td valign='top'>:</td>
		<td><input id='username' type=text name='username' remote='cekusername.php' size='30' class='required username' ><br>
		Username hanya diperbolehkan kombinasi antara huruf dan angka serta tanpa spasi";
		$neg = datanegara2();
		$cetak .="</td>
	</tr>
	<tr >
		<td align=right valign='top'>Email</td>
		<td valign='top'>:</td>
		<td><input id='email' type=text name='email' remote='cekemail.php' size=30 value='$email' class='required email' >
		<br>Masukan email yang valid dan aktif.</td>
	</tr>
	<tr >
		<td align='right' valign='top'>Password</td>
		<td valign='top'>:</td>
		<td><input  id='password' type='password' name='password' size=20 style='width:180; height:20;' ></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Re-type Password</td>
		<td valign='top'>:</td>
		<td><input id='confirm_password'  type=password name='confirm_password' size=20 style='width:180; height:20;' ></td>
	</tr>
	<tr >
		<td align=right>Tgl Lahir</td>
		<td>:</td>
		<td>
		<SELECT name='hari' id='hari' class='required' title='Tanggal harus dipilih' ><OPTION value=''
        selected></OPTION> <OPTION value=01>1</OPTION> <OPTION 
        value=02>2</OPTION> <OPTION value=03>3</OPTION> <OPTION 
        value=04>4</OPTION> <OPTION value=05>5</OPTION> <OPTION 
        value=06>6</OPTION> <OPTION value=07>7</OPTION> <OPTION 
        value=08>8</OPTION> <OPTION value=09>9</OPTION> <OPTION 
        value=10>10</OPTION> <OPTION value=11>11</OPTION> <OPTION 
        value=12>12</OPTION> <OPTION value=13>13</OPTION> <OPTION 
        value=14>14</OPTION> <OPTION value=15>15</OPTION> <OPTION 
        value=16>16</OPTION> <OPTION value=17>17</OPTION> <OPTION 
        value=18>18</OPTION> <OPTION value=19>19</OPTION> <OPTION 
        value=20>20</OPTION> <OPTION value=21>21</OPTION> <OPTION 
        value=22>22</OPTION> <OPTION value=23>23</OPTION> <OPTION 
        value=24>24</OPTION> <OPTION value=25>25</OPTION> <OPTION 
        value=26>26</OPTION> <OPTION value=27>27</OPTION> <OPTION 
        value=28>28</OPTION> <OPTION value=29>29</OPTION> <OPTION 
        value=30>30</OPTION> <OPTION value=31>31</OPTION></SELECT>
		<SELECT name='bulan' id='bulan' class='required' title='Bulan harus dipilih' ><OPTION value='' 
        selected></OPTION> <OPTION value=01>Jan</OPTION> <OPTION 
        value=02>Feb</OPTION> <OPTION value=03>Mar</OPTION> <OPTION 
        value=04>Apr</OPTION> <OPTION value=05>May</OPTION> <OPTION 
        value=06>Jun</OPTION> <OPTION value=07>Jul</OPTION> <OPTION 
        value=08>Aug</OPTION> <OPTION value=09>Sep</OPTION> <OPTION 
        value=10>Oct</OPTION> <OPTION value=11>Nov</OPTION> <OPTION 
        value=12>Dec</OPTION></SELECT>
		<SELECT id='tahun' name='tahun' class='required' title='Tahun harus dipilih'>
		<OPTION value='' selected ></OPTION> <OPTION 
	  	value=1995>1995</OPTION> <OPTION value=1994>1994</OPTION> <OPTION
		value=1993>1993</OPTION> <OPTION value=1992>1992</OPTION> <OPTION
		value=1991>1991</OPTION> <OPTION value=1990>1990</OPTION> <OPTION
		value=1989>1989</OPTION> <OPTION value=1988>1988</OPTION> <OPTION
        value=1987>1987</OPTION> <OPTION value=1986>1986</OPTION> <OPTION 
        value=1985>1985</OPTION> <OPTION value=1984>1984</OPTION> <OPTION 
        value=1983>1983</OPTION> <OPTION value=1982>1982</OPTION> <OPTION 
        value=1981>1981</OPTION> <OPTION value=1980>1980</OPTION> <OPTION 
        value=1979>1979</OPTION> <OPTION value=1978>1978</OPTION> <OPTION 
        value=1977>1977</OPTION> <OPTION value=1976>1976</OPTION> <OPTION 
        value=1975>1975</OPTION> <OPTION value=1974>1974</OPTION> <OPTION 
        value=1973>1973</OPTION> <OPTION value=1972>1972</OPTION> <OPTION 
        value=1971>1971</OPTION> <OPTION value=1970>1970</OPTION> <OPTION 
        value=1969>1969</OPTION> <OPTION value=1968>1968</OPTION> <OPTION 
        value=1967>1967</OPTION> <OPTION value=1966>1966</OPTION> <OPTION 
        value=1965>1965</OPTION> <OPTION value=1964>1964</OPTION> <OPTION 
        value=1963>1963</OPTION> <OPTION value=1962>1962</OPTION> <OPTION 
        value=1961>1961</OPTION> <OPTION value=1960>1960</OPTION> <OPTION 
        value=1959>1959</OPTION> <OPTION value=1958>1958</OPTION> <OPTION 
        value=1957>1957</OPTION> <OPTION value=1956>1956</OPTION> <OPTION 
        value=1955>1955</OPTION> <OPTION value=1954>1954</OPTION> <OPTION 
        value=1953>1953</OPTION> <OPTION value=1952>1952</OPTION> <OPTION 
        value=1951>1951</OPTION> <OPTION value=1950>1950</OPTION> <OPTION 
        value=1949>1949</OPTION> <OPTION value=1948>1948</OPTION> <OPTION 
        value=1947>1947</OPTION> <OPTION value=1946>1946</OPTION> <OPTION 
        value=1945>1945</OPTION> <OPTION value=1944>1944</OPTION> <OPTION 
        value=1943>1943</OPTION> <OPTION value=1942>1942</OPTION> <OPTION 
        value=1941>1941</OPTION> <OPTION value=1940>1940</OPTION> <OPTION 
        value=1939>1939</OPTION> <OPTION value=1938>1938</OPTION> <OPTION 
        value=1937>1937</OPTION> <OPTION value=1936>1936</OPTION> <OPTION 
        value=1935>1935</OPTION> <OPTION value=1934>1934</OPTION> <OPTION 
        value=1933>1933</OPTION> <OPTION value=1932>1932</OPTION> <OPTION 
        value=1931>1931</OPTION> <OPTION value=1930>1930</OPTION> <OPTION 
        value=1929>1929</OPTION> <OPTION value=1928>1928</OPTION> <OPTION 
        value=1927>1927</OPTION> <OPTION value=1926>1926</OPTION> <OPTION 
        value=1925>1925</OPTION> <OPTION value=1924>1924</OPTION> <OPTION 
        value=1923>1923</OPTION> <OPTION value=1922>1922</OPTION> <OPTION 
        value=1921>1921</OPTION> <OPTION value=1920>1920</OPTION></SELECT>
		</td>
	</tr>
	<tr >
		<td align='right' valign='top'>Pertanyaan</td>
		<td valign='top'>:</td>
		<td>
		<SELECT name='pertanyaan' id='pertanyaan'  class='required' title='Konfirmasi pertanyaan harus dipilih' >
		<OPTION value='' selected>[Pilih Pertanyaan]</option>
		<OPTION $p1 value='1'>Apa nama binatang peliharaan?</option>
		<OPTION $p2 value='2'>Apa nama sekolah anda pertama kali?</option>
		<OPTION $p3 value='3'>Siapa pahlawan anda?</option>
		<OPTION $p4 value='4'>Dimana tempat favorit anda?</option>
		<OPTION $p5 value='5'>Apa hobby anda?</option>
		<OPTION $p6 value='6'>Dimana tempat kerja anda?</option>
		<OPTION $p7 value='7'>Apa warna kesukaan anda?</option>
		<OPTION $p8 value='8'>Apa makanan favorit anda?</option>
		<OPTION $p9 value='9'>Apa binatang kesukaan anda?</OPTION>
		</SELECT>
		</td>
	</tr>
	<tr>
		<td align='right' valign='top'>Jawaban</td>
		<td valign='top'>:</td>
		<td><input id='jawaban'  class='required' type=text name='jawaban' value='$jawaban' size=20 maxlength='30' title='Konfirmasi jawaban harus diisi' ></td>
	</tr>
	
	<tr><td colspan=3 class='td0'>Informasi Pribadi </td></tr>
	<tr >
		<td align='right' valign='top'>Status Informasi</td>
		<td valign='top'>:</td>
		<td><select name='stprofil' id='stprofil' ><option value='open' $stpro1 >Tampilkan ke teman</option>
		<option value='hide' $stpro2 >Sembunyikan</option></select>
		</td>
	</tr>
		<tr >
		<td align=right valign=top>Pekerjaan</td>
		<td valign=top>:</td>
		<td><SELECT name='kerja' id='kerja' class='required' title='Pekerjaan harus dipilih'><OPTION value='Guru' $ke1>Guru</OPTION><OPTION value='Siswa' $ke2>Siswa</OPTION>
		<OPTION value='Mahasiswa' $ke3>Mahasiswa</OPTION><OPTION value='Dosen' $ke4>Dosen</OPTION>
		<OPTION value='Praktisi' $ke5>Praktisi</OPTION>
		<OPTION value='Pegawai Negeri' $ke6>Pegawai Negeri</OPTION><OPTION value='Pegawai Swasta' $ke7>Pegawai Swasta</OPTION>
		<OPTION value='Lain-lain' $ke8>Lain-lain</OPTION></select></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Alamat</td>
		<td valign='top'>:</td>
		<td><textarea name='alamat' id='alamat' class='required' cols='40' rows='6' title='Alamat harus diisi' maxlength='100' >$alamat</textarea></td>
	</tr>
	<tr >
		<td align='right'>Negara</td>
		<td valign='top'>:</td>
		<td>$neg
		</td>
	</tr>

	<tr >
		<td align='right' valign='top'>Sekolah</td>
		<td valign='top'>:</td>
		<td><input  type=text name=sekolah id='sekolah' class='required' title='Sekolah harus diisi' value='$school' size=40  maxlength='50'></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Telp/HP</td>
		<td valign='top'>:</td>
		<td><input  type=text name=telp id='telp' size=20 value='$telp' class='required' style='width:180; height:20;' title='No Telp harus diisi' maxlength='30' ></td>
	</tr>
	<tr >
		<td align='right' valign='top'>Homepage/Blog</td>
		<td valign='top'>:</td>
		<td>http:// <input  type=text name='blog' size=30 id='blog' value='$homepage' maxlength='50'> 
		<br>Tidak menggunakan http://
		<br><input type=checkbox name='stblog' id='stblog' value='on' $stblog > Blog ini mau ditampilkan di Daftar Blog Member  </td>
	</tr>
		<tr >
		<td align='right' valign='top'>Tentang Anda</td>
		<td valign='top'>:</td>
		<td><textarea name='tentang' id='tentang'  cols='40' rows='6'>$ket</textarea>
		<br>Seperti : Hobby,Aktivitas,dsb <br></td>
	</tr>
		</tr>";
$cetak .="<tr><td align='right' valign='top'>Anda Setuju ? &nbsp;&nbsp;<input type=checkbox name=setuju id=setuju class='required' title='Persetujuan belum diceklist' ></td><td ></td><td><textarea name='k'   cols='40' rows='6'>Saya sudah membaca dan menyetujui Ketentuan Layanan ini  dan Kebijakan Privasi , dan bersedia berbagi informasi dalam komunitas ini.
Ketentuan pada layanan ini adalah :
1. Tidak boleh memasukan gambar yang mengandung Pornografi dan fornoaksi
2. Tidak boleh memasukan konten yang berbau sara dan politik praktis
3. Tidak boleh mempromosikan kepentingan pribadi
4. Gambar dan konten yang dimasukan menjadi hak milik komunitas sekolah ini.
5. Untuk Siswa tidak boleh mengganti Username dan Nama member.</textarea><br>
Saya juga sudah membaca dan menyetujui Ketentuan tersebut diatas.</td></tr>
	<tr><td align='right' valign='top'>Kode Konfirmasi</td><td ></td><td><img src='../functions/captcha/captcha.php'> * case sensitive
    <br><input type='text' name='code' size='12' id='code' class='required'  ><br><br><input type='submit' id='button2' name='submit' value='Simpan'>&nbsp;</td></tr>	
	</table></form></div>";
$cetak .="</div>";
return $cetak;
}

//fungsi lupa
function lupa() {
include "koneksi.php";
include "fungsi_pass.php";
$username=$_POST['username'];
$pertanyaan=$_POST['pertanyaan'];
$jawaban=$_POST['jawaban'];
$bulan=$_POST['bulan'];
$hari=$_POST['hari'];
$tahun=$_POST['tahun'];
$cetak .="<table border=0 width='100%' cellspacing='2' cellpadding='2' id=tablebaru >
	<tr><td colspan=3 bgcolor='#BDC5CC'><div style='float:right;margin-right:10px;margin-top:10px' ><a href='daftar.php?' id='button2' > Daftar </a>&nbsp;&nbsp;<a href='index.php' id='button2' >&nbsp; Login &nbsp;</a></div><img src='../images/icon21.png' width='100' height='100' style='margin:0 20px 0 10px' align=left > <h1>".$nmsekolah."</h1> 
	</td></tr>
	<tr><td colspan=3 class='td0'>Perubahan Password Member </td></tr>
	<tr><td colspan=3 >";
$cetak .="<div id='hasil' ></div><div id='boxpesan' ><form action='daftar.php' method='post' ><center>
<table  width=60% id=tablebaru cellspacing='1' cellpadding='3'>
<input type=hidden name='id' value='lupasim'>
<tr><td class='td1' >Username</td><td><input  type=text name=username id=username size=20 ></td></tr>
<tr><td class='td1'>Tgl Lahir</td><td>
<SELECT name='hari' id='hari' class='required' title='Tanggal harus dipilih' ><OPTION value=''
        selected></OPTION> <OPTION value=01>1</OPTION> <OPTION 
        value=02>2</OPTION> <OPTION value=03>3</OPTION> <OPTION 
        value=04>4</OPTION> <OPTION value=05>5</OPTION> <OPTION 
        value=06>6</OPTION> <OPTION value=07>7</OPTION> <OPTION 
        value=08>8</OPTION> <OPTION value=09>9</OPTION> <OPTION 
        value=10>10</OPTION> <OPTION value=11>11</OPTION> <OPTION 
        value=12>12</OPTION> <OPTION value=13>13</OPTION> <OPTION 
        value=14>14</OPTION> <OPTION value=15>15</OPTION> <OPTION 
        value=16>16</OPTION> <OPTION value=17>17</OPTION> <OPTION 
        value=18>18</OPTION> <OPTION value=19>19</OPTION> <OPTION 
        value=20>20</OPTION> <OPTION value=21>21</OPTION> <OPTION 
        value=22>22</OPTION> <OPTION value=23>23</OPTION> <OPTION 
        value=24>24</OPTION> <OPTION value=25>25</OPTION> <OPTION 
        value=26>26</OPTION> <OPTION value=27>27</OPTION> <OPTION 
        value=28>28</OPTION> <OPTION value=29>29</OPTION> <OPTION 
        value=30>30</OPTION> <OPTION value=31>31</OPTION></SELECT>
		<SELECT name='bulan' id='bulan' class='required' title='Bulan harus dipilih' ><OPTION value='' 
        selected></OPTION> <OPTION value=01>Jan</OPTION> <OPTION 
        value=02>Feb</OPTION> <OPTION value=03>Mar</OPTION> <OPTION 
        value=04>Apr</OPTION> <OPTION value=05>May</OPTION> <OPTION 
        value=06>Jun</OPTION> <OPTION value=07>Jul</OPTION> <OPTION 
        value=08>Aug</OPTION> <OPTION value=09>Sep</OPTION> <OPTION 
        value=10>Oct</OPTION> <OPTION value=11>Nov</OPTION> <OPTION 
        value=12>Dec</OPTION></SELECT>
		<SELECT id='tahun' name='tahun' class='required' title='Tahun harus dipilih'>
		<OPTION value='' selected ></OPTION> <OPTION 
	  	value=1995>1995</OPTION> <OPTION value=1994>1994</OPTION> <OPTION
		value=1993>1993</OPTION> <OPTION value=1992>1992</OPTION> <OPTION
		value=1991>1991</OPTION> <OPTION value=1990>1990</OPTION> <OPTION
		value=1989>1989</OPTION> <OPTION value=1988>1988</OPTION> <OPTION
        value=1987>1987</OPTION> <OPTION value=1986>1986</OPTION> <OPTION 
        value=1985>1985</OPTION> <OPTION value=1984>1984</OPTION> <OPTION 
        value=1983>1983</OPTION> <OPTION value=1982>1982</OPTION> <OPTION 
        value=1981>1981</OPTION> <OPTION value=1980>1980</OPTION> <OPTION 
        value=1979>1979</OPTION> <OPTION value=1978>1978</OPTION> <OPTION 
        value=1977>1977</OPTION> <OPTION value=1976>1976</OPTION> <OPTION 
        value=1975>1975</OPTION> <OPTION value=1974>1974</OPTION> <OPTION 
        value=1973>1973</OPTION> <OPTION value=1972>1972</OPTION> <OPTION 
        value=1971>1971</OPTION> <OPTION value=1970>1970</OPTION> <OPTION 
        value=1969>1969</OPTION> <OPTION value=1968>1968</OPTION> <OPTION 
        value=1967>1967</OPTION> <OPTION value=1966>1966</OPTION> <OPTION 
        value=1965>1965</OPTION> <OPTION value=1964>1964</OPTION> <OPTION 
        value=1963>1963</OPTION> <OPTION value=1962>1962</OPTION> <OPTION 
        value=1961>1961</OPTION> <OPTION value=1960>1960</OPTION> <OPTION 
        value=1959>1959</OPTION> <OPTION value=1958>1958</OPTION> <OPTION 
        value=1957>1957</OPTION> <OPTION value=1956>1956</OPTION> <OPTION 
        value=1955>1955</OPTION> <OPTION value=1954>1954</OPTION> <OPTION 
        value=1953>1953</OPTION> <OPTION value=1952>1952</OPTION> <OPTION 
        value=1951>1951</OPTION> <OPTION value=1950>1950</OPTION> <OPTION 
        value=1949>1949</OPTION> <OPTION value=1948>1948</OPTION> <OPTION 
        value=1947>1947</OPTION> <OPTION value=1946>1946</OPTION> <OPTION 
        value=1945>1945</OPTION> <OPTION value=1944>1944</OPTION> <OPTION 
        value=1943>1943</OPTION> <OPTION value=1942>1942</OPTION> <OPTION 
        value=1941>1941</OPTION> <OPTION value=1940>1940</OPTION> <OPTION 
        value=1939>1939</OPTION> <OPTION value=1938>1938</OPTION> <OPTION 
        value=1937>1937</OPTION> <OPTION value=1936>1936</OPTION> <OPTION 
        value=1935>1935</OPTION> <OPTION value=1934>1934</OPTION> <OPTION 
        value=1933>1933</OPTION> <OPTION value=1932>1932</OPTION> <OPTION 
        value=1931>1931</OPTION> <OPTION value=1930>1930</OPTION> <OPTION 
        value=1929>1929</OPTION> <OPTION value=1928>1928</OPTION> <OPTION 
        value=1927>1927</OPTION> <OPTION value=1926>1926</OPTION> <OPTION 
        value=1925>1925</OPTION> <OPTION value=1924>1924</OPTION> <OPTION 
        value=1923>1923</OPTION> <OPTION value=1922>1922</OPTION> <OPTION 
        value=1921>1921</OPTION> <OPTION value=1920>1920</OPTION></SELECT>
</td></tr>
<tr><td class='td1'>Pertanyaan</td><td>
		<SELECT name=pertanyaan id=pertanyaan ><OPTION 
                    value='' selected>[Pilih Pertanyaan]<OPTION 
                    $p1 value='1'>Apa nama binatang peliharaan?<OPTION 
                    $p2 value='2'>Apa nama sekolah anda pertama kali?<OPTION 
                    $p3 value='3'>Siapa pahlawan anda?<OPTION 
					$p4 value='4'>Dimana tempat favorit anda?<OPTION 
                    $p5 value='5'>Apa hobby anda?<OPTION 
                    $p6 value='6'>Dimana tempat kerja anda?<OPTION 
                    $p7 value='7'>Apa warna kesukaan anda?<OPTION 
                    $p8 value='8'>Apa makanan favorit anda?<OPTION 
                    $p9 value='9'>Apa binatang kesukaan anda?</OPTION>
		</SELECT>
		</td></tr>
		<tr><td class='td1'>Jawaban</td><td>
		<input  type=text name=jawaban value='' size=20 ></td></tr>
		<tr><td class='td1' valign=top >
		</td><td>	
		<input type=submit value=' Submit ' class='simlupa' id='button2'> 
		</td></tr>
</table></center></form></div>";

$cetak .="</td></tr></table>";
	
return $cetak;
}

function lupasim() {
include "koneksi.php";
include "fungsi_pass.php";
$username=$_POST['username'];
$pertanyaan=$_POST['pertanyaan'];
$jawaban=$_POST['jawaban'];
$bulan=$_POST['bulan'];
$hari=$_POST['hari'];
$tahun=$_POST['tahun'];
$lupa .="<table border=0 width='100%' cellspacing='2' cellpadding='2' id=tablebaru >
	<tr><td colspan=3 bgcolor='#BDC5CC'><div style='float:right;margin-right:10px;margin-top:10px' ><a href='daftar.php?' id='button2' > Daftar </a>&nbsp;&nbsp;<a href='index.php' id='button2' >&nbsp; Login &nbsp;</a></div><img src='../images/icon21.png' width='100' height='100' style='margin:0 20px 0 10px' align=left > <h1>".$nmsekolah."</h1> 
	</td></tr>
	<tr><td colspan=3 class='td0'>Perubahan Password Member </td></tr>
	<tr><td colspan=3 >";
$tgl ="$hari-$bulan-$tahun";
	$r = mysql_query("SELECT * FROM t_member where username='". mysql_real_escape_string($username)."' and pengingat='". mysql_escape_string($pertanyaan)."' and jawaban='". mysql_real_escape_string($jawaban)."' and tgllahir='$tgl'") or die("Query failed");
	$ro =mysql_num_rows($r);
	if ($ro>0) {
	$row = mysql_fetch_array($r);
	$p = date("d");
	$pass = hex($p,$noacak);
	$password=md5($pass);
	$user=hex($username,$noacak);

	$query="update t_member set password='". mysql_real_escape_string($password)."' where username='". mysql_real_escape_string($username)."'";
    $result = mysql_query($query) or die("Query failed 1");
	//$username =$row[username];
$url2= $_SERVER['PHP_SELF'];
$nmfo=explode("/",$url2);
for($i=0;$i<count($nmfo);$i++) {
    $nm .= $nmfo[$i]."/";
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$nm."fckconfig.js")) {
        $nmhost = "http://".$_SERVER['HTTP_HOST'].$nm;
        break;
    }   
}
	$file = "http://$nmhost/member/profil/gb$row[userid].jpg";
		$gb="<img src='http://$nmhost/member/profil/kosong.jpg' width='90' height='120' alt='$row[username]' border='1' style='padding:2px;margin:3px;'>";
		if (file_exists(''.$file.'')) {
	        $gb="<img src='$file' width='90' height='120' alt='$row[username]' border='1' style='padding:2px;margin:3px;' >";
		}
        
$message = <<<EOF
<html>
<body>

<table cellSpacing="0" cellPadding="4" bgColor="#6B79A0" border="0">
  <tr>
    <td width="600">
    <table cellSpacing="0" cellPadding="10" width="600" bgColor="#ffffff">
      <tr>
        <td><strong>
        <font face="Verdana,Arial,Helvetica,sans-serif" color="#6A849D" size="+1">
        KONFIRMASI USERNAME $nmsekolah</font></strong>
          <table cellSpacing="15" cellPadding="0" width="100%" border="0">
          <tr>
            <td vAlign="top">
            $gb</td>
            <td vAlign="top">
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2">
              Terima kasih Anda telah bergabung dalam komunitas $nmsekolah. 
			  Anda telah melakukan konfirmasi perubahan Password. Perubahan data dibawah ini :</font></p>
              <p><font face="Verdana,Arial,Helvetica,sans-serif" color="#000000" size="2"> Username : $username<br>
Password : $pass <br>
                    <br>
                   	Silahkan manfaatkan fasilitas komunitas ini untuk kepentingan pendidikan.<br>
                <br>
                <br>
                <a style="font-weight: bold; font-size: 90%; color: #ffffff; font-family: verdana; white-space: nowrap; text-decoration: none; border: 4px solid #f0f0f0; margin: 0px; padding-left: 16px; padding-right: 16px; padding-top: 4px; padding-bottom: 4px; background-color: #7b849c" href="http://$webhost/member/index.php?username=$user&password=$password" target="_blank">
                LOG IN NOW</a> <br>
                <br>
&nbsp;</font></p></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <table cellSpacing="0" cellPadding="1" width="100%" align="center" border="0">
          <tr>
            <td bgColor="#6A849D">
            <table cellSpacing="0" cellPadding="0" width="100%" border="0">
              <tr>
                <td bgColor="#f4f4f4">
                <table cellSpacing="0" cellPadding="4" border="0">
                  <tr>
                    <td>
                    <a href="$nmhost" target="_blank">
                    <img style="margin-bottom: 5px" alt src="$nmhost/images/logo.jpg" align="center" border="1"  width="88" height="88"></a>
                    </td>
                    <td style="line-height: 110%" vAlign="center">
                    <font face="Verdana,Arial,Helvetica,sans-serif" color="#ff9900" size="2">
                    <strong>Terima Kasih.... </strong> <br>
                    <font color="#000000" size="1">$webmail</font> </font></td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<table cellSpacing="0" cellPadding="0" width="600" border="0">
  <tr>
    <td align="middle">
    <a href="http://$webhost" target="_blank">
    </a>
    <br>
    <font face="Verdana,Arial,Helvetica,sans-serif" color="#7b849c" size="-2">
    Copyright $tahun $nmsekolah. All rights reserved. <br>
    $almtsekolah </font></td>
  </tr>
</table>
</body>
</html>
EOF;
   //end of message
//$email ="alanrm82@yahoo.com";
    $headers  = "From: \"Komunitas $nmsekolah\" <$webmail>\r\n";
    $headers .= "Content-type: text/html\r\n";
 	if(!@mail($row[email], "Konfirmasi Username Member di $nmsekolah", $message, $headers)) {
 	  $lupa .= "Gagal kirim email";
 	}
	
	$lupa .="<center>Silahkan cek email anda kembali, konfirmasi kami melalui email.<br>";
	}
	else {
	$lupa .="Data yang anda masukkan tidak benar. ";
	}

$lupa .="</td></tr></table>";
return $lupa;
}
?>