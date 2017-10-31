<?php
// File Created by Muhammad Atif at 21-3-2006
#include_once $INCLUDEPATH."/ADb.inc.php";
class Template
{
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Template()
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
	$Buffer = explode(" ",$Buffer);
	$Position;	
	$Position2;
	for($Counter=0; $Counter<count($Buffer); $Counter++)
	{
		$Str = $Buffer[$Counter];
		if(strpos($Str,"[SP_") !== FALSE) // !== identical match 'same type' and equal
		{
			$Position = strpos($Str,"[SP_");
			$Position2 = strpos($Str,"]");
			$Substr = substr($Str, $Position, $Position2-$Position + 1);
			$Replacement = $Hash[$Substr];
			$Str = str_replace($Substr,$Replacement,$Str);
		}
		$HTML=$HTML." ".$Str; //reverse of explode function
	}
	return $HTML;
}
} #Class Template
?>