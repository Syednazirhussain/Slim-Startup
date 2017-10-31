<?php
class LNPRequest
{
	function getMyRequests($pUID) {
	
	$myADb=new ADb();

	
	$strSQL = "select ID,OID,DIDnumber,date_format(CompletionDate,'%d-%b-%Y') as CompletionDate,PortWith,Charges,date_format(Date,'%d-%b-%Y') as Date,Monthly,Status from LNPRequest where OID=\"$pUID\"";
	
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;							
	
	}


	function RateCenter($NPA,$NXX)
	{
		$myADb=new ADb();
		$strSQL = " select RateCenter from USAreas where NPA=\"$NPA\" and NXX=\"$NXX\" ";
	    $Result = $myADb->ExecuteQuery($strSQL);
        $RateCenter = $Result->fields[0];
        return $RateCenter;

	}

	function State($NPA)
	{	$myADb=new ADb();
		$strSQL = " select DIDArea.Description as DIDState from DIDArea,DIDCountries 
	                where StateCode=\"$NPA\" and CountryID=DIDCountries.ID and countrycode=1";
	
	   $ResultArea = $myADb->ExecuteQuery($strSQL);
       $State = $ResultArea->fields[0];
       return $State;
	}

	function GlobalCross($Prefix)
	{	 $myADb=new ADb();
		 $strSQL = " select * from GlobalCrossingNew where NPA_NXX=\"$Prefix\" ";
	     $Result = $myADb->ExecuteQuery($strSQL);

	     return $Result ;
	}

	function VerizonNew($RateCenter)
	{
		$myADb=new ADb();
		$strSQL = " select * from VerizonNew where RateCenter=\"$RateCenter\" ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function IristelNew($Prefix)
	{	
		$myADb=new ADb();
		$strSQL    = "select * from IristelNew WHERE NPA_NXX='$Prefix'";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
	}

	function DatafromDID($NPA,$NXX)
	{	$myADb=new ADb();
		$strSQL = " select * from DIDS where OID=700444  and DIDNumber like '1$NPA$NXX%' group by AreaID";
		#print $strSQL;
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	function CheckIfCanRequest($UID) {
	
	$myADb=new ADb();
	$myOrder=new Orders();
	$Orders = $myOrder->getOrdersInfoByUID($UID);
	$strSQL = " select count(DIDNumber) from DIDS where STatus=2 and BOID=\"$UID\" ";
	$Result = $myADb->ExecuteQuery($strSQL);
	$TotalPurchased = $Result->fields[0];
	
	$strSQL = " select count(DIDNumber) from DIDS where OID=\"$UID\" ";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	
	$TotalOffered = $Result->fields[0];
	
	$Total = $TotalPurchased + $TotalOffered;


	if($Total<50 || $Orders['IsDeleted']==3){
		
		return 0;
	}
	if($Total>=50){
		
		return 1;
	}

	
}



function SaveUserInfo($endusername,$streetnumber,$streetname,$suffix,$city,$state,$zip,$county,$cellphone,$callerid,$authname,$car,$pDID) {
	
	$myADb=new ADb();
	
	$strSQL = " insert into LNPUserInfo(oid,endusername, streetnumber,streetname,suffix,city,state,zip,
	county,cellphone,callerid,authname,carrier,StreetDir,SuiteFloor,DID)
	values
	(\"$UID\",\"$endusername\",\"$streetnumber\",\"$streetname\",\"$suffix\",\"$city\",\"$state\",\"$zip\",\"$county\"
	,\"$cellphone\",\"$callerid\",\"$authname\",\"$car\",\"$StrDir\", \"$Suite\", \"$pDID\",\ ) ";
	
	$Result = $myADb->ExecuteQuery($strSQL);
	
}

public function SaveUserInfoWithFiles($oid,$endusername,$streetnumber,$streetname,$suffix,$city,$state,$zip,$county,$cellphone,$callerid,$authname,$car,$pDID,$uFile1,$uFile2,$pStrDir,$pSuite,$ext1,$ext2) {
	
	$myADb = new ADb();
	
	$strSQL = " insert into LNPUserInfo(oid,endusername, streetnumber,streetname,suffix,city,state,zip,
	county,cellphone,callerid,authname,carrier,StreetDir,SuiteFloor,DID,Doc1,Doc2,DocType1,DocType2)
	values
	(\"$oid\",\"$endusername\",\"$streetnumber\",\"$streetname\",\"$suffix\",\"$city\",\"$state\",\"$zip\",\"$county\"
	,\"$cellphone\",\"$callerid\",\"$authname\",\"$car\",\"$pStrDir\", \"$pSuite\", \"$pDID\",\"$uFile1\",\"$uFile2\",\"$ext1\",\"$ext2\") ";
	
	
	$Result = $myADb->ExecuteQuery($strSQL);

	return $Result;
	
}  

function TotalNumber($UID)
{
	$myADb=new ADb();
	$strSQL = "select count(*) from DIDS where BOID=\"$UID\" and status=2 ";

	$ResultCount = $myADb->ExecuteQuery($strSQL);
	$TotalNumbers = $ResultCount->fields[0];
	return $TotalNumbers;
}

function forAllowLNP($UID)
{
   $myADb=new ADb();
   $sql="select AllowLNP,AllowLNPDID from orders where OID=\"$UID\" ";
   $Result    = $myADb->ExecuteQuery($sql);
   return $Result;
}

function IfExist($pOID,$pDID) {
	
	$myADb=new ADb();
	
	$strSQL = "select * from LNPRequest where DIDNumber=\"$pDID\"  ";
	
	
	$Result = $myADb->ExecuteQuery($strSQL);

	if(!$Result->EOF) {
	
		return 1;

	}else{
		
		return 0;
		
	}

}

function RequestNow($pOID,$pDID,$UID) {
	$myADb=new ADb();
	$myTransaction=new Transaction();
	$myComplain=new Complain();
	$myGeneral=new General();
	
	$strSQL = "select date_add(curdate(),INTERVAL CompletionDate day),Charges,Monthly from LNPAdmin where OID=\"$pOID\" ";

	$Result = $myADb->ExecuteQuery($strSQL);
	
	
	$CDate = $Result->fields[0];
	$Charges = $Result->fields[1];
	$Monthly = $Result->fields[2];
	


	$strSQL = "select * from LNPRequest where DIDNumber=\"$pDID\" and OID=\"$UID\" ";

	$Result = $myADb->ExecuteQuery($strSQL);

	if($Result->EOF) {
	
	
	
$Complain="";
$TicketNumber	= $myComplain->getComplainCount($UID);
$Complain['ComplainID']	= $TicketNumber;
$Complain['OID']		= $UID;
$Complain['AUID']		= $UID;
$Complain['Assign']	= "Tier1";
$Complain['Type']		= "COMP";
$Complain['Notify'] = 1;
$Complain['Complain']	= "Customer has submitted LNP Request for Number $pDID.";

$myComplain->addComplain($Complain);
// echo $myComplain;
// exit;

$Transaction;
$Transaction['TransactionID']	= $myTransaction->getTransactionID($UID);

$Transaction['OID']						= $UID;
$Transaction['Desc']					= "[DIDx:$pDID]Porting Charges. ";
$Transaction['Type']					= "OINV";
$Transaction['ReferenceID']		= $UID;
$Transaction['Amount']				= $Charges;
$Transaction['IsCredit']			= 1;
$Transaction['DID']			=$pDID;
$myTransaction->addTransaction($Transaction);

$TrID = $Transaction['TransactionID'];

#RecordLoggedClient($UID,119,$UID,"",$pDID,"",""];
$myGeneral->RecordLoggedClient($UID,119,$UID,"",$pDID,"","");


$strSQL = "insert into LNPRequest(OID,DIDNumber,CompletionDate,Portwith,Charges,date,Monthly,TrID)
							values(\"$UID\", \"$pDID\", \"$CDate\", \"$pOID\",\"$Charges\",sysdate(),\"$Monthly\",\"$TrID\")  ";
	
	$ResultInsert = $myADb->ExecuteQuery($strSQL);


	}
	
}


function GetLNPUserInfo($DID,$UID)
 {  
 	$myADb=new ADb();
 	$strSQL = "select * from LNPUserInfo where DID=\"$DID\"  and OID=\"$UID\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
     if($Result->EOF){
		
		         $strSQL = "insert into LNPUserInfo(DID,OID)values(\"$DID\",\"$UID\")";
		
		          $Result = $myADb->ExecuteQuery($strSQL);
	          }
    return $Result;

 }
 function GetAllLNPUserInfo($DID,$UID)
 {
 	$myADb=new ADb();
 	$strSQL = "select EndUsername,StreetNumber,StreetName,StreetDir,SuiteFloor,Suffix,City,
			   State,County,CallerID,AuthName,Carrier,Zip,CellPhone,ID from LNPUserInfo 
               where OID=\"$UID\" and DID=\"$DID\" ";

    $Result = $myADb->ExecuteQuery($strSQL);
    return $Result;
 }

 function UpdateUserInfo($oid,$endusername,$streetnumber,$streetname,$suffix,$city,$state,$zip,$county,$cellphone,$callerid,$authname,$car,$pDID,$pStrDir,$pSuite) {
	
	$myADb = new ADb();
	
	$strSQL = " update LNPUserInfo set endusername=\"$endusername\", streetnumber=\"$streetnumber\",streetname=\"$streetname\",
	suffix=\"$suffix\",city=\"$city\",state=\"$state\",zip=\"$zip\",
	county=\"$county\",cellphone=\"$cellphone\",callerid=\"$callerid\",authname=\"$authname\",
	carrier=\"$car\",StreetDir=\"$pStrDir\",SuiteFloor=\"$pSuite\"
	 where OID=\"$oid\"  and DID=\"$pDID\" ";
	
	$Result = $myADb->ExecuteQuery($strSQL);
	
} 

	function SaveUserInfoImg1($oid,$pDID,$uFile1,$ext1) {
	
	$myADb = new ADb();
	
	$strSQL = " update LNPUserInfo set Doc1=\"$uFile1\",DocType1=\"$ext1\" 
	 where OID=\"$oid\"  and DID=\"$pDID\" ";
	
	$Result = $myADb->ExecuteQuery($strSQL);
	
} 

 function SaveUserInfoImg2($oid,$pDID,$uFile2,$ext2) {
	
	$myADb = new ADb();
	
	$strSQL = " update LNPUserInfo set Doc2=\"$uFile2\",DocType2=\"$ext2\" 
	 where OID=\"$oid\"  and DID=\"$pDID\" ";
	#print "\ $strSQL:  $strSQL";
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;
	
 }  

 function ShowImage($OID,$Op,$DID,$ID){

 	$myADb = new ADb();
 	$strSQL="select Doc$Op,DocType$Op from LNPUserInfo where OID=\"$OID\" and DID=\"$DID\" and ID=\"$ID\" ";
			
	 $Result	= $myADb->ExecuteQuery($strSQL);
	 return $Result;
 }

}




?>