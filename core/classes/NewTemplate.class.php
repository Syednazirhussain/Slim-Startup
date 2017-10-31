<?php
// File Created by Muhammad Atif at 21-3-2006
#include_once $INCLUDEPATH."/ADb.inc.php";
class NewTemplate
{
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function NewTemplate()
	{
	#	$this->ADb = new ADb();
	$this->a=1;
	}	
function AlterTemplate2Html($FileName,$Hash)
{
	$Buffer;
	$HTML;
	$Handle = fopen($FileName,"r");
	if(!$Handle)
	{
		echo "Cant Open $FileName";
		exit;
	}
  $Buffer = fread($Handle, filesize($FileName)+10);
  fclose($Handle);
  
  foreach ($Hash as $key => $val) {
  	
  	 #print "<br>$key: $val"; 	
  	 $key = preg_replace("/\[/","\\[",$key);
  	 $key = preg_replace("/\]/","\\]",$key);
  	$Buffer = preg_replace("/".$key."/",$val,$Buffer);
  	#print $Buffer;
  	
  #	print "<br>$Buffer = preg_replace(\"/\[SP_".$key."\]/\",$val,$Buffer); ";
  	
  }
  #print $Buffer;
  return $Buffer;
	
}
} #Class Template
?>