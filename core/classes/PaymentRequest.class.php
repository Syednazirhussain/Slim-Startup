<?php 

/**
Aes encryption
*/
class PaymentRequest {

	function GetAllRequests()
	{
	    global $Date45Before;
		
		$myADb = new ADb();
		$UID=currentUser();
		// $strSQL="select OID,Amount,Date,Status,date_format(Date,'%d-%b-%Y') from PaymentReq where OID=\"$UID\"";
		
		$strSQL="select OID,Amount,Date,Status,date_format(Date,'%d-%b-%Y') as Date,
		date_format(date_sub(Date,INTERVAL 45 day),'%d-%b-%Y') as Date45Before
		 from PaymentReq  where OID=\"$UID\" ";
		 
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function PaymentReqById($UID)
	{
		
		$myADb = new ADb();
    	$strSQL = "select Status,Amount from PaymentReq where OID=\"$UID\" order by date desc limit 0,1 ";
		$Result	= $myADb->ExecuteQuery($strSQL);
		$data=array();
		$data['PaymentStatus']=$Result->fields[0];
		$data['PaymentAmount']=$Result->fields[0];
		return $data;
	}

    function getXDaysPaybleAmount($UID) {
	
	$myADb = new ADb();
	$data['Max'] = "\$100";

	$strSQL = "select date_sub(curdate(),INTERVAL 45 day),date_format(date_sub(curdate(),INTERVAL 45 day),'%d-%b-%Y')";
	#echo "<br>$strSQL" ;
	$Result	= $myADb->ExecuteQuery($strSQL);
	$data=array();
	$data['DateFourtyFive'] = $Result->fields[0];
	$data['DateFourtyFiveFormat'] = $Result->fields[1];
	
	#echo "\$DateFourtyFive: $DateFourtyFive";
	
	$strSQL="select sum(amount) from transaction where IsCredit=0
					 and IsDeleted=0 and OID=\"$UID\" 
					 and date  <= \"$DateFourtyFive\" 
					 group by OID";
	# echo "<br>$strSQL";
	$Result	= $myADb->ExecuteQuery($strSQL);
	$data['Debit'] = $Result->fields[0];
	
	
	$strSQL="select sum(amount) from transaction where IsCredit=1
					 and IsDeleted=0 and OID=\"$UID\" 
					 and date  <=\"$DateFourtyFive\" 
					 group by OID";
#	echo "<br>$strSQL";
	$Result	= $myADb->ExecuteQuery($strSQL);
	$data['Credit'] = $Result->fields[0];
	
	$data['TotalOutStanding'] = sprintf("%2.2f",($Debit - $Credit));
	$TotalOutStanding=$data['TotalOutStanding'];
	
#	print "\$TotalOutStanding $TotalOutStanding";
	
	
	$strSQL = "select sum(amount),type from transaction where iscredit=1 and isdeleted=0 and date > \"$DateFourtyFive\" and	
	(type =\"PPCC\" or type =\"PPWU\" or type =\"PPBC\" or type =\"PPOP\" or type =\"PPWT\" or type =\"PPAH\" 
	or type =\"PPZR\" or type =\"PPBP\" or type =\"PPPP\"   or type =\"PPMB\" or type =\"PPAT\" or type like 'TRFP%') and OID=\"$UID\" group by OID order by type 	";
#echo "<br>$strSQL";
	$Result	= $myADb->ExecuteQuery($strSQL);
	
	
	$data['PaidAmount'] = $Result->fields[0];
	$PaidAmount=$data['PaidAmount'];
	
	if($TotalOutStanding > 0){
	$data['TotalOutStanding'] = $TotalOutStanding - $PaidAmount;
	}
	return $data;

}
	

}
?>