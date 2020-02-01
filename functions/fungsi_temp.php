<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
// atas isi untuk detail konten
function atas_isi($judul) {
include "koneksi.php";
if ($kolom=='3') $lebar='style="width:75%"';

$atas .='		   <div class="art-content" '.$lebar.' >
                        <div class="art-Post">
			    <div class="art-Post-tl"></div>
                            <div class="art-Post-tr"></div>
                            <div class="art-Post-bl"></div>
                            <div class="art-Post-br"></div>
                            <div class="art-Post-tc"></div>
                            <div class="art-Post-bc"></div>
                            <div class="art-Post-cl"></div>
                            <div class="art-Post-cr"></div>
                            <div class="art-Post-cc"></div>
                            <div class="art-Post-body"> 
					<div class="art-Post-inner">
					<div class="art-PostMetadataHeader">
					<h2 class="art-PostHeader">'.$judul.'</h2>
					</div>';
return $atas;
}
// batas bawahnya
function bawah_isi() {

$bawah .='</div>                 
                            </div>
                        </div>
                        
                    </div>';

return $bawah;
}


?>