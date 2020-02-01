<?php
$id=$_POST['id'];
if ($id=='') $id=$_GET['id']; 
$userid=$_GET['userid'];

if ($id=='uploadfoto') {
$kdalbum=$_GET['kdalbum'];
echo "<iframe src='uploadfoto.php?id=$id&userid=$userid&kdalbum=$kdalbum' id='target' name='target' style='width:450px;height:240px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='editfoto') {
$kdfoto=$_GET['kdfoto'];
echo "<iframe src='uploadfoto.php?id=editfoto&userid=$userid&kdfoto=$kdfoto' id='target' name='target' style='width:450px;height:270px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='editalbum') {
$kdalbum=$_GET['kdalbum'];
echo "<iframe src='uploadfoto.php?id=editalbum&userid=$userid&kdalbum=$kdalbum' id='target' name='target' style='width:450px;height:120px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='fotoprofil') {
echo "<iframe src='uploadfoto.php?id=fotoprofil&userid=$userid' id='target' name='target' style='width:420px;height:120px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='totmember') {
echo "<iframe src='kontenmember.php?id=totmember' id='target' name='target' style='width:420px;height:200px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='kirimpesan') {
$userid=$_GET['userid'];
$tujuan=$_GET['tujuan'];
echo "<iframe src='kontenpesan.php?id=kirimpesan&userid=$userid&tujuan=$tujuan' id='target' name='target' style='width:420px;height:390px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='tamteman') {
$userid=$_GET['userid'];
$tujuan=$_GET['tujuan'];
echo "<iframe src='kontenteman.php?id=tamteman&userid=$userid&tujuan=$tujuan' id='target' name='target' style='width:420px;height:390px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='groupbaru') {
$userid=$_GET['userid'];
if ($userid=='') $userid=$_POST['userid'];
echo "<iframe src='kontengroup.php?id=groupbaru&userid=".$userid."' id='target' name='target' style='width:420px;height:310px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='editgroup') {
$userid=$_GET['userid'];
$kdgroup =$_GET['kdgroup'];
echo "<iframe src='kontengroup.php?id=editgroup&userid=$userid&kdgroup=$kdgroup' id='target' name='target' style='width:420px;height:430px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='infogroup') {
$userid=$_GET['userid'];
$kdgroup =$_GET['kdgroup'];
echo "<iframe src='kontengroup.php?id=infogroup&userid=$userid&kdgroup=$kdgroup' id='target' name='target' style='width:600px;height:450px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='editinfo') {
$userid=$_GET['userid'];
$kdinfo =$_GET['kdinfo'];
echo "<iframe src='kontengroup.php?id=editinfo&userid=$userid&kdinfo=$kdinfo' id='target' name='target' style='width:600px;height:450px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='hapgroup') {
$kdgroup =$_GET['kdgroup'];
echo "<iframe src='kontengroup.php?id=hapgroup&userid=$userid&kdgroup=$kdgroup' id='target' name='target' style='width:400px;height:310px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='topikgroup') {
$kdgroup =$_GET['kdgroup'];
echo "<iframe src='kontengroup.php?id=topikgroup&userid=$userid&kdgroup=$kdgroup' id='target' name='target' style='width:600px;height:450px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='edittopikgroup') {
$kdgroup =$_GET['kdgroup'];
$kdtopik =$_GET['kdtopik'];
echo "<iframe src='kontengroup.php?id=edittopikgroup&userid=$userid&kdgroup=$kdgroup&kdtopik=$kdtopik' id='target' name='target' style='width:600px;height:450px;border:0px;overflow:hidden;' ></iframe>";
} 
else if ($id=='balastopikgp') {
$kdgroup =$_GET['kdgroup'];
$kdtopik =$_GET['kdtopik'];
echo "<iframe src='kontengroup.php?id=balastopikgp&userid=$userid&kdgroup=$kdgroup&kdtopik=$kdtopik' id='target' name='target' style='width:600px;height:450px;border:0px;overflow:hidden;' ></iframe>";
}
else if ($id=='tamsumber') {
    session_start();
    $_SESSION['idsum'] = '';
   // session_unset('idsum');
    $_SESSION['idbelajar'] = $_GET['idbelajar'];
echo "<iframe src='kontenbelajar.php?id=tamsumber' id='target' name='target' style='width:620px;height:430px;border:0px;' ></iframe>";
}
else if ($id=='editsumber') {
    session_start();
   // session_unset('idbelajar');
    $_SESSION['idsum'] = $_GET['idsum'];
    $_SESSION['idbelajar'] = '';
    $jenis = $_GET['jenis'];
echo "<iframe src='kontenbelajar.php?id=tamsumber&jenis=$jenis' id='target' name='target' style='width:620px;height:430px;border:0px;' ></iframe>";
}
else if ($id=='lihatsoal') {
    $kode =$_GET['kode'];
    echo "<iframe src='kontenbelajar.php?id=lihatsoal&kode=$kode' id='target' name='target' style='width:620px;height:430px;border:0px;overflow:hidden;' ></iframe>";
}
else {
//header("Location:index.php");
echo "<script>document.location.href = 'index.php';</script>";
}
?>

