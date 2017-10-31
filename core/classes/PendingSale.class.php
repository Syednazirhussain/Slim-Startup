<?php 

/**
Aes encryption
*/
class PendingSale {

function UpdateID($pID,$pStatus) {
	
	
	$UID=currentUser();
	$myADb=new ADb();
	
	$strSQL="select NotifyURL,DID,UserData,OID from CusDocs where ID=\"$pID\" ";

	$Result	= $myADb->ExecuteQuery($strSQL);
		
		$NotifyURL = $Result->fields[0];
		$DID = $Result->fields[1];
		$USERDATA = $Result->fields[2];
		$OID = $Result->fields[3];

	if($pStatus==3) {
		
		$strSQL="select DID from CusDocs where   KeyID=\"$pID\" ";

		$Result	= $myADb->ExecuteQuery($strSQL);
		$DID = $Result->fields[0];
		
		$strSQL="delete from CusDocs where   KeyID=\"$pID\" ";

	#	print "<br>\$strSQL: $strSQL " . __LINE__;
		$Result	= $myADb->ExecuteQuery($strSQL);
		
		$strSQL="update DIDS set status=0,BOID=\"\",iPurchasedDate=\"\",iURL=\"\",IsChannel=0,groupid=\"\",HaveDocs=0,SuspendDID=0 where DIDNumber=\"$DID\" ";

	#	print "<br>\$strSQL: $strSQL " . __LINE__;
		$Result	= $myADb->ExecuteQuery($strSQL);
		
		if($NotifyURL != ''){

			fopen("$NotifyURL?DID=$DID&APTYPE=2&USERDATA=$USERDATA","r");
			
		}
		
		fopen($GLOBALS['website_url']."/SendEmailOnDocumentRefusal?OID=$OID&DID=$DID","r");
		
		return 1;
	}
}



    function str_splitNow($string,$string_length=1) {
        if(strlen($string)>$string_length || !$string_length) {

            do {
                $c = strlen($string);
                $parts[] = substr($string,0,$string_length);
                $string = substr($string,$string_length);
            } while($string !== false);
        } else {
            $parts = array($string);
        }
       
        return $parts;
    }


    function Ifwithin15Days($pDate) {
	
		 $myADb=new ADb();
	
	$strSQL = "select date_add(\"$pDate\",Interval 15 day) > curdate() ";
	

	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result->fields[0];
	
	
}

function GetDocsRights($UID) {
	
	$myADb = new ADb();
	
	$strSQL = "select DocsRights from orders where OID=\"$UID\" ";
	

	$Result = $myADb->ExecuteQuery($strSQL);
	$Hash=array();
	$DocsRight = $Result->fields[0];
	

	$CheckArray = $this->str_splitNow($DocsRight);
	

			if($CheckArray[0] == '1'){
				$Hash['See']		= "1";
			}
				if($CheckArray[1] == '1'){
				$Hash['Accept']		= "1";
			}
			if($CheckArray[2] == '1'){
				$Hash['Refuse']		= "1";
			}
			if($CheckArray[3] == '1'){
				$Hash['Ticket']		= "1";
			}
			#print "\$CheckArray[4]: $CheckArray[4]";
			if($CheckArray[4] == '1'){
				$Hash['Delete']		= "1";
			}
			if($CheckArray[5] == '1'){
				$Hash['Email']		= "1";
			}
			if($CheckArray[6] == '1'){
				$Hash['View']		= "1";
			}

		return $Hash;
	
	
}



     function MyPendingPurchasesDIDS($HashRights) {
	
	$myADb=new ADb();
	$UID=currentUser();
	
	
	$strSQL = "select CusDocs.ID,CusDocs.OID,CusDocs.DID as di,CusDocs.DID,CusDocs.Type,
	date_format(CusDocs.Date,'%d-%b-%Y') as Date,CusDocs.KeyID,CusDocs.AreaID,CusDocs.Status,DIDS.Status as DST, DIDS.iPurchasedDate,
	DIDS.CountryN,DIDS.City
	 from CusDocs,DIDS 
	where DIDS.DIDNumber = CusDocs.DID and DIDS.OID=\"$UID\" ";


	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;
}


function RemoveFromMinutesInfo($DIDNumber){
	
	$myADb=new ADb();
	
	$strSQL = "delete from MinutesInfo where DID=\"$DIDNumber\" ";
	
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;
	
	
}

function DeAllocateClient($DIDNumber)
{
		$myADb=new ADb();
		$myGeneral=new General();
	    $strSQL = "select substring(iPurchasedDate,1,10),BOID,OID from DIDS where DIDNumber=\"$DIDNumber\" and status=2";

		$Result	= $myADb->ExecuteQuery($strSQL);

		$BOID = $Result->fields[1];
		$PurDate = $Result->fields[0];
		$Seller = $Result->fields[2];

		//if($BOID != $ffOID){
		//	
		//	#print "OrderID Mismatched";
		//	print "<meta http-equiv=refresh content='0;url=PendingSales.cgi'>";
		//	exit;	
		//	
		//}

		$strSQL = "update transaction set isdeleted=1 
							where (type like 'PVPL%' or  type like 'ACTV') and OID=\"$BOID\" and
							description like '%:$DIDNumber%' and date>= \"$PurDate\" ";

		$Result	= $myADb->ExecuteQuery($strSQL);


		#print "<br>\$strSQL: $strSQL";


		$strSQL = "update transaction set isdeleted=1 
								where (type like 'PAY%' or  type like 'ACTP') and 
								description like '%:$DIDNumber%' and date>= \"$PurDate\" and OID=\"$Seller\"";

		$Result	= $myADb->ExecuteQuery($strSQL);
		#print "<br>\$strSQL: $strSQL";


		$myGeneral->RecordBuy($DIDNumber,$BOID,"SellerRelease","","Released","WebUI-DeAllocate-DisApproved");

		$strSQL = "update DIDS set Status=0,BOID=\"\",iPurchasedDate=\"\",iURL=\"\",HaveDocs=0,OurSetupCost=SpSetup,OurMonthlyCharges=SpMonthly,IsChannel=0,groupid=\"\",Type=\"0\",HaveDocs=0,SuspendDID=0 where DIDNUmber=\"$DIDNumber\" ";

		$Result	= $myADb->ExecuteQuery($strSQL);
		$strSQL = "delete from CusDocs where OID=\"$ffOID\" and  DID=\"$DIDNumber\" ";

		$Result	= $myADb->ExecuteQuery($strSQL);
		return;
}

}