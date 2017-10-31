<?php
class FundTransfer
{
	function VLinkAccount()
	{
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL = "select * from VLinkAccount where Vendor=\"$UID\" ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function GetAmount($BOID)
	{
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL = "select LinkAccount,AmountLimit from VLinkAccount where Vendor=\"$UID\" and LinkAccount=\"$BOID\" ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function GetBOID($BOID)
	{
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL = "select OID from orders where OID=\"$BOID\" ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

}
?>