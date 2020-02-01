<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
date("Y-m-d H:i:s", mktime(date("H")+1, date("i"), date("s"), date("m"), date("d"), date("Y")));

$webhost = "tuliskan nama domain sekolah disini";
$webmail = "tuliskan alamat email admin disini";
$nmsekolah = "SD Negeri 01 Jakarta";
$almtsekolah = "Jl. Jenderal Sudirman (tulis alamat sekolah disini)";
$jum_spp= "0";
$jum_dsp= "0";

/* LOCAL DATABASE CONNECTION config */
// database constant
// change below setting according to your database configuration
$dbhost = "localhost";
$dbuser = "root";
$dbpasswd = "";
$dbname = "database";

$dbprefix  = "t_";
$alan = "../temp/sdbawah/";
$email1 = "ppws_online";
$email2 = "yulianto_sri_utomo";
$noacak = "82";
$kolom="3";
$versi="3.5";
$cmsmember = "ya"; //diisi ya atau tidak
$cmssim = "ya"; //diisi ya atau tidak
$cmstingkat = "smp"; // diisi sd,smp,sma,smk,lain
$folderadmin="admin";
$multibahasa="tidak"; // diisi "ya" apabila akan dijadikan multi bahasa dan 
//diisi "tidak" apabila tidak akan mengaktifkan multibahasa
$nmhost = "http://$webhost/";
// konfigurasi ID aplikasi Facebook
$appid  = ''; // contoh  169993669715242
$secret = ''; // contoh 81158039568d1a5f8d7990b70f7186c9

?>