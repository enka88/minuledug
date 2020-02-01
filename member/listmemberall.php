<?php

/**
 * @author Hendi Ahmad Hidayat
 * @copyright 2011
 */
// get names (eg: database)
// the format is: 
// id, searchable plain text, html (for the textboxlist item, if empty the plain is used), html (for the autocomplete dropdown)
session_start();
$userid = $_SESSION['User']['userid'];
include "../functions/koneksi.php";
include "../functions/fungsi_pass.php";
if(isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];		
    if(strlen($queryString) >0) {
        $query="select t_member.nama,t_member.userid from t_member where t_member.nama LIKE '%$queryString%' limit 20";
        $result=mysql_query($query);
            while($row=mysql_fetch_array($result)){
                
            $file = "profil/gb".$row[userid].".jpg";
            $fotouser ="<img src='profil/kosong.jpg' width='50' height='60' style='padding-right: 10px;' />";
            if (file_exists(''.$file.'')) {
	           //$fotouser="<img src='thumb-user.php?img=$file' width='50' height='50' style='padding-right: 10px;' />";
               $fotouser="<img src='$file' width='50' height='60' style='padding-right: 10px;' />";
	        }
            //echo "<li onClick='fill(\"$row[nama]\");'>";
            echo "";
            echo "<table>";
            echo "<tr>";
            echo "<td>";
            echo "<a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."' style='text-decoration:none;color:white'>".$fotouser."</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='user.php?id=lih_profil&kode=".hex($row[userid],$noacak)."' style='text-decoration:none;color:white'>".$row['nama']."</a>";
            echo "</td>";
            echo "</tr>";
            echo "</table>"; 
            echo ""; 
            }
        }        
}
?>