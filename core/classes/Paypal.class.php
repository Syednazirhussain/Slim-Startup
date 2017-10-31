<?php
class Paypal
{
	// 	Copyright©2017 Niha. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Paypal($dbg=0)
	{ 
			// include_once("Const.inc.php");
			// include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
			$this->Db = new ADb();
	}
	function getPayPalInfo()
	{
		exit('niha');
		$strSQL	= "SELECT paypalemailcheck,paypalfname,paypallname,paypalzipcode,paypalphone FROM paypal_settings";
		$this->dPrint("strSQL:$strSQL");	
		$Result	= $this->Db->ExecuteQuery($strSQL);
		$data = array();
		$data['EmailCheck'] = $Result->fields[0];
		$data['FNameCheck'] = $Result->fields[1];
		$data['LNameCheck'] = $Result->fields[2];
		$data['ZipCodeCheck'] = $Result->fields[3];
		return $data;
	}	

}

?>