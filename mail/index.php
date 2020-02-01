<? 
include "../lib/config.php";
  $user = $_POST["username"]; 
  $pass = $_POST["password"]; 
  if($user!=""&&$pass!=""){ 
  $domain = str_replace("www.","",$webhost); 

  
   if($_POST["tipe"]=="horde"){ 
     $horde = "http://".$user."+".$domain.":".$pass."@".$domain.":2095/"."horde/index.php"; 
     print '<meta http-equiv="refresh" content="0;url='.$horde.'">'; 
   }else if($_POST["tipe"]=="s"){ 
     $squire = "http://".$user."+".$domain.":".$pass."@".$domain.":2095/3rdparty/squirrel/index.php"; 
     print '<meta http-equiv="refresh" content="0;url='.$squire.'">'; 
   }else if($_POST["tipe"]=="cpanel"){ 
    $cpanel = "http://".$user."+".$domain.":".$pass."@".$domain.":2095/"; 
     print '<meta http-equiv="refresh" content="0;url='.$cpanel.'">'; 
   } 
   exit; 
 } 
?> 

<!-- form --> 
<form action="" method="post"> 
    <table class=default width="30%" bgcolor=#E8E8E8> 
      <tr> 
         <td width="30%" align=center>web login</td> 
         <td width="70%" align=center><input type="text" name="username"></td> 
      </tr> 
      <tr> 
         <td align=center>password</td> 
         <td align=center><input type="password" name="password"></td> 
      </tr> 
      <tr> 
         <td colspan=2 align=center><input type="submit" value="login"></td> 
      </tr> 
    </table> 
  <br>Choose web  
  <input type="radio" name="tipe" value="horde" checked>Horde 
  <input type="radio" name="tipe" value="s">SquirrelMail 
  <input type="radio" name="tipe" value="cpanel">Go to CPanel 
  <br><br><br> 
 </form> 