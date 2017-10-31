<?php

class SMSLog
{

	function TotalRates()
	{
   	 $myADb=new ADb();
	 $UID=currentUser();
     $strSQL="select SUM(rate) as Total FROM SMSLog WHERE OID=\"$UID\"";
     $Result=$myADb->ExecuteQuery($strSQL);
     $Result=$Result->fields[0];
     return $Result;
    }


   
    function TotalPageRate()
    {
	 $ThisPageTotal="0";
     $myADb=new ADb();
	 $UID=currentUser();
     $strSQL="SELECT rate FROM SMSLog WHERE OID=\"$UID\" ORDER BY DATETIME DESC LIMIT 0,100";
     $Result = $myADb->ExecuteQuery($strSQL);
      while(!$Result->EOF)
      {
      	$Rate = $Result->fields[0]/100;
	    	$ThisPageTotal = $ThisPageTotal+$Rate;
		    $Result->MoveNext();
      }
     return $ThisPageTotal;
    }

        function getSMSRate($AreaID,$OID)
    {
     $myADb=new ADb();
     $strSQL="SELECT `FreeSMS`,`PerSMSAfterFree` FROM SMSRateMnager where (AreaID='$AreaID' OR AreaID='-1') AND OID='$OID'";
     $Result = $myADb->ExecuteQuery($strSQL);
     $data = array();
      while(!$Result->EOF)
      {
        $data['FreeSMS'] = $Result->fields[0];
        $data['PerSMSAfterFree'] = $Result->fields[1];
        $Result->MoveNext();
      }
     return $data;
    }


        function getSMSFilterList()
    {
     $myADb=new ADb();
     $UID = currentUser();
     $strSQL="SELECT Id, DIDNumber, ondate FROM SMSFilter WHERE OID=\"$UID\" order by Id desc";
     $Result = $myADb->ExecuteQuery($strSQL);
    
     return $Result;
    }

    function addNumber($OID,$DIDNumber){
        $myADb=new ADb();
     $strSQL="insert into SMSFilter(OID,DIDNumber)values('".$OID."','".$DIDNumber."')";
     $Result = $myADb->ExecuteQuery($strSQL);    
     return $Result;
    }

    function deleteNumber($ID,$OID){
        $myADb=new ADb();
        $strSQL="delete  from SMSFilter where Id='".$ID."' and OID='".$OID."'";

        $Result = $myADb->ExecuteQuery($strSQL);    
        return $Result;
    }
    
}
?>