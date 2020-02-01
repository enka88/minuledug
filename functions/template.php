<?php
if(preg_match("template.php", $PHP_SELF))
{
    //header("location: index.php");
 echo "<script>document.location.href = 'index.php';</script>";
die;
}
class template
{
var $TAGS=array();
var $THEME;
var $CONTENT;

function define_tag($tagname, $varname)
{
$this->TAGS[$tagname]=$varname;
}
function define_theme($themename)
{
$this->THEME=$themename;
}

function parse()
{
$this->CONTENT=file($this->THEME);
$this->CONTENT=implode("",$this->CONTENT);
while(list($key,$val)=each($this->TAGS))
{
$this->CONTENT=ereg_replace($key,$val,$this->CONTENT);
}
}
function printproccess()
{
echo $this->CONTENT;
}
}
?>