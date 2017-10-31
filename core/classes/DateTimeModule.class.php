<?php
class DateTimeModule
{
	// 	Copyright©2017 Niha Siddiqui All rights reserved.
	var $fDebug = 1;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function DateTimeModule($dbg=0)
	{ 
		$this->fDebug = $dbg;
		$this->Db = new ADb();
	}
	
function getCurrentFormatedDateTimePattern1()
{
	
	$strSQL	= "	select date_format(sysdate(),'%W %d %M %H:%i:%S %Y') ";
				
	$this->dPrint("strSQL:$strSQL");	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$this->dPrint("\$Result: {$Result->fields[0]}");			
	return $Result->fields[0];
}	


function getCurrentFormatedDateTimePattern2()
{
	
	$strSQL	= "	select date_format(sysdate(),'%W %d %M %H:%i:%S %Y') ";
				
	$this->dPrint("strSQL:$strSQL");	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$this->dPrint("\$Result: {$Result->fields[0]}");			
	return $Result->fields[0];
}	


function getCurrentFormatedDateTimePattern3()
{
	
	$strSQL	= "select date_format(curdate(),'%d-%b-%Y') ";
				
	$this->dPrint("strSQL:$strSQL");	
	$Result	= $this->Db->ExecuteQuery($strSQL);
		
	return $Result->fields[0];
}

function getCurrentDateMinusMonthCycle($ThisMonthCycle)
{
	
	$strSQL	= "SELECT (TO_DAYS(curdate()) - To_days('$ThisMonthCycle')); ";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	return $Result->fields[0];
}




}
?>