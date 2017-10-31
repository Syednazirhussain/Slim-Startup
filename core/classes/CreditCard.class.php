<?php
class CreditCard
{
	var $fDebug = 1;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function CreditCard($dbg = 0)
	{
		//include_once("Const.inc.php");

		// include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
		// include_once($GLOBALS['INCLUDEPATH']."/User.inc.php");
		// include_once($GLOBALS['INCLUDEPATH']."/Customer.inc.php");
  //       include_once ($GLOBALS['INCLUDEPATH']."/Encryption.inc.php");
		//include_once("ADb.inc.php");
		//include_once("User.inc.php");
		//include_once("Customer.inc.php");
        //include_once ("Encryption.inc.php");
		$this->Db = new ADb();
		$this->myCustomer = new Customer();
		$this->myUser = new User();				
		$this->fDebug = $dbg;
        $this->Encryption  = new Encryption();
//		Date: Wed, 20 Jun 2001 20:18:47 
	}





function getCreditCardInfoByCCIDCustomerID($pCCID,$pCustomerID) {
	
	 // $strSQL	= "	select
		// 			ccid,
		// 			customerid,
		// 			addressid,
		// 			name,
		// 			concat(left(number,4),'....',right(number,4)),
		// 			type,
		// 			vnumber,
		// 			expirymonth,
		// 			expiryyear
		// 		from
		// 			creditcard
		// 		where
		// 			CCID=\"$pCCID\" and CustomerID=\"$pCustomerID\" ";

	 $strSQL	= "	select
					ccid,
					customerid,
					addressid,
					name,
					Number,
					type,
					vnumber,
					expirymonth,
					expiryyear
				from
					creditcard
				where
					CCID=\"$pCCID\" and CustomerID=\"$pCustomerID\" ";

	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	$Hash=array();

	$Hash[CCID]			= $Result->fields[0];
	$Hash[CustomerID]	= $Result->fields[1];
	$Hash[AddressID]	= $Result->fields[2];
	$Hash[Name]			= $Result->fields[3];
	//$Hash[Number]		= $this->Encryption->decrypt($Result->fields[4]);
	$Hash[Number]		= $Result->fields[4];
	$Hash[Type]			= $Result->fields[5];
	// $Hash[VNumber]		= $this->Encryption->decrypt($Result->fields[6]);
	$Hash[VNumber]		= $Result->fields[6];	
	$Hash[ExpiryMonth]	= $Result->fields[7];
	$Hash[ExpiryYear]	= $Result->fields[8];
	
	return $Hash;

}# getCreditCardInfoByCustomerID

function CreditCardStatus($CCID)
{
    $strSQL = "select Number from creditcard where CCID='".$CCID."'";
    $myResult = $this->Db->ExecuteQuery($strSQL);
   //echo $strSQL;
    if($myResult->EOF)
    {
        return 0;
    }
    
    else
    {
       $CCNumber = $myResult->fields['Number'];

       $CCNumber = $this->Encryption->decode($CCNumber);
       $strSQL = "select Status from creditcard_temp where Number='".$CCNumber."'";

       $Res = $this->Db->ExecuteQuery($strSQL);
       $status = $Res->fields[0];
       
       if ($status == 2 || $status == "")
       {
           return 1;
       }
       else
       {
           return 0;
       }
       
    }
}



function addCreditCard_temp($Hash) {
    
     $strSQL    = "    insert into
                    creditcard_temp
                    (
                        ccid,
                    customerid,
                    addressid,
                    name,
                    number,
                    type,
                    vnumber,
                    expirymonth,
                    expiryyear,
                    PriCard,
                    DateTime
                    )
                values (
                    \"$Hash[CCID]\",
                        \"$Hash[CustomerID]\",
                        \"$Hash[AddressID]\",
                        \"$Hash[Name]\",
                        \"$Hash[Number]\",
                        \"$Hash[Type]\",
                        \"$Hash[VNumber]\",
                        \"$Hash[ExpiryMonth]\",
                        \"$Hash[ExpiryYear]\",
                        \"$Hash[PriCard]\",
                        Now()
                        
                    )
                    
                    ";
    $Result    = $this->Db->ExecuteQuery($strSQL);
    return 1;

}
    	
function addCreditCard($Hash) {
	
	 $strSQL	= "	insert into
					creditcard
					(
						ccid,
					customerid,
					addressid,
					name,
					number,
					type,
					vnumber,
					expirymonth,
					expiryyear,
					PriCard
					)
				values (
					\"$Hash[CCID]\",
						\"$Hash[CustomerID]\",
						\"$Hash[AddressID]\",
						\"$Hash[Name]\",
						\"$Hash[Number]\",
						\"$Hash[Type]\",
						\"$Hash[VNumber]\",
						\"$Hash[ExpiryMonth]\",
						\"$Hash[ExpiryYear]\",
						\"$Hash[PriCard]\"
						
					)
					
					";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	return 1;

}
	
	
function getIfCardAlreadyExist($pNumber) {
	
	//$strSQL="select * from creditcard where number=\"$pNumber\" ";
	$strSQL="select * from creditcard_temp where number=\"$pNumber\" ";

	//echo $strSQL;
	$Result	= $this->Db->ExecuteQuery($strSQL);
		
		if($Result->EOF) {
			return 0;
		}else{
			return 1;
		}
	
	
}

function getPrimaryCardDetail($pCustomerID) {
	
	 $strSQL	= "	select
					ccid,
					customerid,
					addressid,
					name,
					Number,
					type,
					vnumber,
					expirymonth,
					expiryyear,
					PriCard
				from
					creditcard
				where
					customerid=\"$pCustomerID\"
				and PriCard=1	
					";

#	print "\$strSQL: $strSQL";

	$Result	= $this->Db->ExecuteQuery($strSQL);
	return $Result;
	
}
	
	public static function setPrimaryCard($customerID,$CCID) {
	
	$myADb = new ADb();
	$strSQL="Update creditcard set PriCard=0 where CustomerID=\"$customerID\" ";

	$Result	= $myADb->ExecuteQuery($strSQL);
	
	$strSQL="Update creditcard set PriCard=1  where ccid=\"$CCID\" and customerid=\"$customerID\" ";

	$Result	= $myADb->ExecuteQuery($strSQL);
	#print "\$strSQL: $strSQL";
	
	
}


// public static function updatecreditcardinfo($UID,$CCName,$CCNumber,$CCVNumber,$CCYEAR,$CCMONTH,$CCMONTH1,$CCTYPE, $customerID)

// {
// 	$myADb = new ADb();

// 	$strsql="update creditcard SET Name='$CCName',Number='$CCNumber',Type='$CCTYPE',VNumber='$CCVNumber',ExpiryMonth='$CCMONTH1',ExpiryYear='$CCYEAR' where CustomerID=
// \"$customerID\" ";

//  $Result = $myADb->ExecuteQuery($strsql);
// }

		function GetSummaryOfCardByCCID($pCustomerID) {
	
	$strSQL	= "	select
					ccid,
					customerid,
					addressid,
					name,
					Number,
					type,
					vnumber,
					expirymonth,
					expiryyear,
					PriCard,number, monthname(concat(expiryyear,'-',expirymonth,'-','01'))
				from
					creditcard
				where
					CCID=\"$pCustomerID\"";

	
#echo $strSQL;

	$Result	= $this->Db->ExecuteQuery($strSQL);
#	echo $strSQL;

	$htmlResult="";
	
			if($Result->EOF) {
				
					$htmlResult .= "(no card added yet)";
			} else {
	
	while(!$Result->EOF) {
		
		 $Number = $Result->fields[10];
//		 $strSQL = "select date_format(max(DateTime),'%d-%b-%Y') from cchistory where uid=\"$pUID\" 
//								and StatusMessage=\"APPROVED\" and Type=\"CHRG\" and CCNumber=\"$Number\" ";
//						#		echo $strSQL;
//		 $ResultLastCC	= $this->Db->ExecuteQuery($strSQL);
//		
//		 $LastDate = $ResultLastCC->fields[0];
//		 $LastDateString ;
//		
//		if($LastDate == ''){
//			$LastDateString = " - Last Charged on: $LastDate";
//		}
		$Type =$Result->fields[5];		
		$LastFour = $Result->fields[4];
        // Decryption Start //
        $getDecryptCC =  $this->Encryption->decrypt($Result->fields[4]);
        // Decryption End //

        // Get last 4 CC digit //
        $decrypLastFour =  substr($getDecryptCC,strlen($getDecryptCC)-4,4);

		$Expiry = $Result->fields[11]."-".$Result->fields[8];
        $String = "$Type ....$LastFour (Expiry: $Expiry)";
		$String = "$Type *****".$decrypLastFour;
		$CCID = $Result->fields[0];
	
		$htmlResult .= "$String";
		
		$Result->MoveNext();
	}
	
}
	
	return $htmlResult;
	
	
}
  function getAllCardsInfoByCustomerID($pCustomerID,$pUID) {
	   $myEnc=new Encryption();

	   $strSQL	= "	select
					ccid,
					customerid,
					addressid,
					name,
                    Number,
					type,
					vnumber,
					expirymonth,
					expiryyear,
					PriCard,number, monthname(concat(expiryyear,'-',expirymonth,'-','01'))
				from
					creditcard
				where
					customerid=\"$pCustomerID\"";

	
#echo $strSQL;

	$Result	= $this->Db->ExecuteQuery($strSQL);
#	echo $strSQL;

	$htmlResult="";
	
			if($Result->EOF) {
				
					$htmlResult .= "<option value=\"\">(no card added yet)</option>";
			} else {
	
		while(!$Result->EOF) {
		
		 $Number = $Result->fields[10];
		 $strSQL = "select date_format(max(DateTime),'%d-%b-%Y') from cchistory where uid=\"$pUID\" and StatusMessage=\"APPROVED\" and Type=\"CHRG\" and CCNumber=\"$Number\" ";
			#		echo $strSQL;
		 $ResultLastCC	= $this->Db->ExecuteQuery($strSQL);
		
		 $LastDate = $ResultLastCC->fields[0];
		  		 $LastDateString ;
		
		if($LastDate == ''){
			$LastDateString = " - Last Charged on: $LastDate";
		}
		
		$Type =$Result->fields[5];		
		$LastFour = $Result->fields[4];

        // Decryption Start //
        $getDecryptCC =  $myEnc->decrypt($LastFour);
        // Decryption End //

        // Get last 4 CC digit //
        $decrypLastFour = $this->Encryption->DecryptCCLastFirst4($getDecryptCC);
        $String = "$Type *****".$decrypLastFour['Last'];


        $Expiry = $Result->fields[11]."-".$Result->fields[8];
        #$String = "$Type ....$LastFour (Expiry: $Expiry)";
		$CCID = $Result->fields[0];
	
		$htmlResult .= "<option value=\"$CCID\">$String $LastDateString</option>";


		$Result->MoveNext();
	}
	
}
	//echo $htmlResult;
	return $htmlResult;
	
	
}
	
		function addCCHistory($Hash) {
	

	$strSQL	= "	insert into
					cchistory
					(
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
					)
				values (
					\"$Hash[LinkPointID]\",
						\"$Hash[UID]\",
						\"$Hash[UserID]\",
						\"$Hash[CName]\",
						\"$Hash[CAddress]\",
						\"$Hash[CTel]\",
						\"$Hash[CEmail]\",
						\"$Hash[CCNumber]\",
						\"$Hash[CCType]\",
						\"$Hash[CCVNumber]\",
						\"$Hash[CCExpiryMonth]\",
						\"$Hash[CCExpiryYear]\",
						\"$Hash[StatusCode]\",
						\"$Hash[StatusMessage]\",
						\"$Hash[StatusApproval]\",
						\"$Hash[AVSCode]\",
						\"$Hash[TrackingID]\",
						\"$Hash[Amount]\",
						\"$Hash[Type]\",
						\"$Hash[Comments]\",
						\"$Hash[IsCharged]\",
						now()
					)
					
					";
		#echo $strSQL;
		$Result	= $this->Db->ExecuteQuery($strSQL);
		

}# 
	function getCreditCardInfoByCCID ($pCustomerID) 
	{	
		$strSQL	= "	select	ccid,	customerid,	addressid,	name, number,	type,	vnumber,	expirymonth,
						expiryyear
					from	creditcard	where	ccid='$pCustomerID' ";
		$this->dPrint("\$strSQL: $strSQL");
		$Result	= $this->Db->ExecuteQuery($strSQL);
		if($Result ===false || $Result->EOF)
			return "";
		$Hash = array();
		
			$Hash[CCID]			= $Result->fields[0];
			$Hash[CustomerID]	= $Result->fields[1];
			$Hash[AddressID]	= $Result->fields[2];
			$Hash[Name]			= $Result->fields[3];
			$Hash[Number]		= $this->Encryption->decrypt($Result->fields[4]);
			$Hash[Type]			= $Result->fields[5];
			$Hash[VNumber]		= $this->Encryption->decrypt($Result->fields[6]);
			$Hash[ExpiryMonth]	= $Result->fields[7];
			$Hash[ExpiryYear]	= $Result->fields[8];
			$Hash[Rating]		= $Result->fields[9];
			$Hash['last4digits'] = substr($Hash[Number], strlen($Hash[Number])-4);
		
		return $Hash;	
	}# getCreditCardInfoByCustomerID
	
	function getCreditCardInfoByCustomerID($pCustomerID) 
	{	
		//$myEnc=new Encryption();

		  $strSQL	= "	select	ccid,	customerid,	addressid,	name, number,	type,	vnumber,	expirymonth,
						expiryyear,PriCard
					from	creditcard	where	customerid='".$pCustomerID."' and PriCard=1";


		$Result	= $this->Db->ExecuteQuery($strSQL);
		//return $Result;


		if($Result ===false || $Result->EOF)
			   return "";


		$Hash = array();

			$Hash['CCID']			= $Result->fields[0];
			$Hash['CustomerID']	= $Result->fields[1];
			$Hash['AddressID']	= $Result->fields[2];
			$Hash['Name']			= $Result->fields[3];
			$Hash['Number']		= $Result->fields[4];
			// $Hash['Number']		= $this->Encryption->encode($Result->fields[4]);
			$Hash['Type']			= $Result->fields[5];
			$Hash['VNumber']		= $this->Encryption->decrypt($Result->fields[6]);
			$Hash['ExpiryMonth']	= $Result->fields[7];
			$Hash['ExpiryYear']	= $Result->fields[8];
			// $Hash['Rating']		= $Result->fields[9];
			$Hash['last4digits'] = substr($Hash['Number'], strlen($Hash['Number'])-4);
			$Hash['PriCard']=$Result->fields[9];
		return $Hash;	
	}# getCreditCardInfoByCustomerID
	function getCCHistoryInfoByLPID($LPID)
	{
		$strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					LinkPointID=\"$LPID\"";
	// $this->dPrint("\$strSQL: $strSQL");					
	$Result	= $this->Db->ExecuteQuery($strSQL);
	if($Result ===false || $Result->EOF)
		return "";
	$Hash['LinkPointID']	= $Result->fields[0];
	$Hash['UID']			= $Result->fields[1];
	$Hash['UserID']			= $Result->fields[2];
	$Hash['CName']			= $Result->fields[3];
	$Hash['CAddress']		= $Result->fields[4];
	$Hash['CTel']			= $Result->fields[5];
	$Hash['CEmail']			= $Result->fields[6];
	$Hash['CCNumber']		= $Result->fields[7];
	$Hash['CCType']			= $Result->fields[8];
	$Hash['CCVNumber']		= $Result->fields[9];
	$Hash['CCExpiryMonth']	= $Result->fields[10];
	$Hash['CCExpiryYear']	= $Result->fields[11];
	$Hash['StatusCode']		= $Result->fields[12];
	$Hash['StatusMessage']	= $Result->fields[13];
	$Hash['StatusApproval']	= $Result->fields[14];
	$Hash['AVSCode']		= $Result->fields[15];
	$Hash['TrackingID']		= $Result->fields[16];
	$Hash['Amount']			= $Result->fields[17];
	$Hash['Type']			= $Result->fields[18];
	$Hash['Comments']		= $Result->fields[19];
	$Hash['DateTime']		= $Result->fields[20];
	return $Hash;

}# getCCHistoryInfoByOID

function getCreditCardStart4Last4Digit($pOID) {
	 $myEnc = new Encryption();
	 $strSQL = "select substring(number,1,4),right(number,4),type from creditcard,customer 
							where customer.uid='$pOID'	and 
							creditcard.customerid=customer.customerid and creditcard.pricard=1";
	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$Hash['StartFour'] 	= $myEnc->decrypt($Result->fields[0]); //Decrypt Start Four Digits
	$Hash['EndFour'] 	= $myEnc->decrypt($Result->fields[1]);   //Decrypt End Four Digits
	$Hash['Type'] = $Result->fields[2];
	return $Hash;
}

 
function getSingleCCTransaction($pLPID) {
   
    $strSQL    = "    select
                    linkpointid,
                    uid,
                    userid,
                    cname,
                    caddress,
                    ctel,
                    cemail,
                    ccnumber,
                    cctype,
                    ccexpirymonth,
                    ccexpiryyear,
                    statuscode,
                    statusmessage,
                    statusapproval,
                    avscode,
                    trackingid,
                    amount,
                    type,
                    comments,
                    ischarged,
                    datetime
                from
                    cchistory
                where
                    linkpointid='$pLPID'
                ";

   
    $Result    = $this->Db->ExecuteQuery($strSQL);
   $Hash;
    
    $Hash['LPID']        	= $Result->fields[0];
    $Hash['UID']        	= $Result->fields[1];
    $Hash['UserID']        	= $Result->fields[2];
    $Hash['CName']        	= $Result->fields[3];
    $Hash['CAddress']       = $Result->fields[4];
    $Hash['CTel']        	= $Result->fields[5];
    $Hash['CEmail']        	= $Result->fields[6];
    $Hash['CCNumber']       = $Result->fields[7];
    $Hash['CCType']        	= $Result->fields[8];
    $Hash['CCExpiryMonth']  = $Result->fields[10];
    $Hash['CCExpiryYear']   = $Result->fields[11];
    $Hash['StatusCode']    	= $Result->fields[12];
    $Hash['StatusMessage']  = $Result->fields[13];
    $Hash['StatusApproval'] = $Result->fields[14];
    $Hash['AVSCode']        = $Result->fields[15];
    $Hash['TrackingID']    	= $Result->fields[16];
    $Hash['Amount']        	= $Result->fields[17];
    $Hash['Type']        	= $Result->fields[18];
    $Hash['Comments']       = $Result->fields[19];
    $Hash['IsCharged']    	= $Result->fields[20];
    $Hash['DateTime']       = $Result->fields[21];
    
    return $Hash;

}

public function DecryptCCLastFirst4($pCCNumber) {
         $Hash=array();
         $Hash['First']    = substr($pCCNumber,0,4);
         $Hash['Last'] = substr($pCCNumber,strlen($pCCNumber)-4,4);
        return $Hash;
    }


function EXPIRYMONTH($CCList) {
		
	$str="select ExpiryMonth from creditcard where CCID='$CCList'";
	//echo $str;
	$res= $this->Db->ExecuteQuery($str);
	
	return $res->fields[0];
}

function EXPIRY_year($CCList) {
	$str="select ExpiryYear from creditcard where CCID='$CCList'";
	//echo $str;
	$res= $this->Db->ExecuteQuery($str);  
	return $res->fields[0];
}


function getSingleCCTransactionForCharge($pLPID) {

     $strSQL    = " select
                    linkpointid,
                    uid,
                    userid,
                    cname,
                    caddress,
                    ctel,
                    cemail,
                    ccnumber,
                    cctype,
                    ccvnumber,
                    ccexpirymonth,
                    ccexpiryyear,
                    statuscode,
                    statusmessage,
                    statusapproval,
                    avscode,
                    trackingid,
                    amount,
                    type,
                    comments,
                    ischarged,
                    datetime
                from
                    cchistory
                where
                    linkpointid='$pLPID' and
                    type=\"CHRG\"
                ";
            //echo $strSQL;
   
    $Result    = $this->Db->ExecuteQuery($strSQL);
    $Hash;
    
    $Hash['LPID']        	= $Result->fields[0];
    $Hash['UID']        	= $Result->fields[1];
    $Hash['UserID']        	= $Result->fields[2];
    $Hash['CName']        	= $Result->fields[3];
    $Hash['CAddress']       = $Result->fields[4];
    $Hash['CTel']        	= $Result->fields[5];
    $Hash['CEmail']        	= $Result->fields[6];
    $Hash['CCNumber']       = $Result->fields[7];
    $Hash['CCType']        	= $Result->fields[8];
    $Hash['CCVNumber']    	= $Result->fields[9];
    $Hash['CCExpiryMonth']  = $Result->fields[10];
    $Hash['CCExpiryYear']   = $Result->fields[11];
    $Hash['StatusCode']    	= $Result->fields[12];
    $Hash['StatusMessage']  = $Result->fields[13];
    $Hash['StatusApproval'] = $Result->fields[14];
    $Hash['AVSCode']        = $Result->fields[15];
    $Hash['TrackingID']   	= $Result->fields[16];
    $Hash['Amount']        	= $Result->fields[17];
    $Hash['Type']        	= $Result->fields[18];
    $Hash['Comments']       = $Result->fields[19];
    $Hash['IsCharged']    	= $Result->fields[20];
    $Hash['DateTime']       = $Result->fields[21];
    
    return $Hash;

}

function getIfCardExpired($CCMonth,$CCYear) {
	
	$strSQL="select curdate()";
	$ResultDate	= $this->Db->ExecuteQuery($strSQL);
	
	$TodaysCurrent = $ResultDate->fields[0];
	
	$ThisMonth = substr($TodaysCurrent,5,2);
	$ThisYear = substr($TodaysCurrent,0,4);
	
	if($ThisYear > $CCYear) {
		return 1;
	}
	
	if($ThisYear==$CCYear && $ThisMonth>$CCMonth) {
		return 1;
	}
	
	return 0;
}

function getCurrentYear($CCMonth,$CCYear) {
	$data = array();
	if($CCMonth=="" && $CCYear==""){
	    $strSQL = "select curdate()";
	    $Result = $this->Db->ExecuteQuery($strSQL);
	    $CCMonth = substr($Result->fields[0],5,2);
	    $CCYear = substr($Result->fields[0],0,4);   
	    $NowYear = $CCYear;
	    $data['Month'] = $CCMonth;
	    $data['year'] = $CCYear;
	}else{
	    $strSQL = "select curdate()";
	    $Result = $this->Db->ExecuteQuery($strSQL);
	    $NowYear = substr($Result->fields[0],0,4);	
	    $CCMonth = substr($Result->fields[0],5,2);
	    $data['year'] = $NowYear;
	    $data['Month'] = $CCMonth;    
	}
	return $data;
}


function authorizePayment($Hash)
{
       
        // include_once "/var/www/vhosts/didx.net/httpdocs/lphp.php";
        // global $PemFileName;
        // global $StoreNo;
        // global $DeclinedReson;
        // global $myAVS;
        // global $myGeneral;
        
        // $DoPerformNNN = $Hash['DoNNN'];
        //         $DoPerformXXX = $Hash['DoXXX'];
        
        $mylphp = new Lphp();
        $myGeneral = new General(); 
        $myCreditCard = new CreditCard();
        $myADb   =  new ADb();

        # constants
        

        $myorder["host"]       = LP_HOST;
        $myorder["port"]       = LP_PORT;
        $myorder["keyfile"]    = LP_CERT; # Change this to the name and location of your certificate file 
        $myorder["configfile"] = LP_STORE;        # Change this to your store number 

        # transaction details
        $myorder["ordertype"]         = 'PREAUTH'; #SALE
        $myorder["result"]            = LP_MODE;  // GOOD for test and LIVE for ....)^(
        $myorder["transactionorigin"] = 'ECI';
        $myorder["oid"]               = $Hash["LPID"];
        $myorder["ponumber"]          = $Hash["OID"];
        $myorder["taxexempt"]         = 'N';
        $myorder["terminaltype"]      = 'UNSPECIFIED';
        $myorder["ip"]                = $Hash["IP"];

        # totals
        $myorder["subtotal"]    = $Hash["subtotal"];
        $myorder["tax"]         = '0.00';
        $myorder["shipping"]    = $Hash["shipping"];
        $myorder["vattax"]      = '0.00';
        $myorder["chargetotal"] = $Hash["Amount"];

        # card info
        $myorder["cardnumber"]   = $Hash["Number"];
        $myorder["cardexpmonth"] = $Hash["ExpiryMonth"];
        $myorder["cardexpyear"]  = substr($Hash["ExpiryYear"],2,2);
        $myorder["cvmindicator"] = 'provided';
        $myorder["cvmvalue"]     = $Hash["VNumber"];

        # BILLING INFO
        $myorder["name"]     = $Hash["Name"];
        $myorder["company"]  = $Hash["Company"];
        $myorder["address1"] = $Hash["Street1"];
        $myorder["address2"] = $Hash["Street2"];
        $myorder["city"]     = $Hash["City"];
        $myorder["state"]    = $Hash["State"];
        $myorder["country"]  = $Hash["Country"];
        $myorder["phone"]    = $Hash["Phone"];
        $myorder["fax"]      = $Hash["Fax"];
        $myorder["email"]    = $Hash["Email"];
        $myorder["addrnum"]  = $Hash["addrnum"];
        $myorder["zip"]      = $Hash["ZipCode"];

        # SHIPPING INFO
        $myorder["sname"]     = $Hash["sname"];
        $myorder["saddress1"] = $Hash["saddress1"];
        $myorder["saddress2"] = $Hash["saddress2"];
        $myorder["scity"]     = $Hash["scity"];
        $myorder["sstate"]    = $Hash["sstate"];
        $myorder["szip"]      = $Hash["szip"];
        $myorder["scountry"]  = $Hash["scountry"];

        # MISC
        $myorder["comments"] = $Hash["comments"];
        $myorder["referred"] = $Hash["referred"];

        # ITEMS AND OPTIONS
        # there are several ways to pass items and options; see sample SALE_MAXINFO.php

        $myorder["items"][item1]["id"]          = $Hash["id"];
        $myorder["items"][item1]["description"] = $Hash["description"];
        $myorder["items"][item1]["quantity"]    = $Hash["quantity"];
        $myorder["items"][item1]["price"]       = $Hash["price"];

        $myorder["items"][item1]["option1"]["name"]  = $Hash["name1"];
        $myorder["items"][item1]["option1"]["value"] = $Hash["value1"];
        $myorder["items"][item1]["option2"]["name"]  = $Hash["name2"];
        $myorder["items"][item1]["option2"]["value"] = $Hash["value2"];
        
        // if ($_SERVER['REMOTE_ADDR'] == "115.42.65.12") {
        //     echo "<pre>";
        //     print_r($myorder);
        //     echo "<pre>";
        //     #return "Live Testing | Team is now working   ".$_SERVER['REMOTE_ADDR'];

        // }
        


        
        if ($Hash["debugging"])
                $myorder["debugging"]="true";

        $CP_Hash = $mylphp->curl_process($myorder);  

        
                $AVSCode                            =    $CP_Hash['r_avs'];
                $StatusCode                        = $CP_Hash['r_code'];
                $StatusMessage                = $CP_Hash['r_approved'];
                $TrackingID                        = "Reference No: $CP_Hash[r_ref]"; 
                $TransactionDATETIME    = $CP_Hash['r_time'];
                $Error                                = $CP_Hash['r_error'];
//            
            
        
                    if(substr($Error,0,10)=="SGS-002303"){
                        
                        $DeclinedReson = "Invalid credit card number.";
                        return "-5006";
                        
                    }

                    $Hash['AVSCODE'] = $AVSCode;
                    $myAVS = $AVSCode;
                  $Action                = "Authorization for DIDx.net Accounts - [" . $Hash[LPID] . "]";
         
    
                    $WebMessage        = "STrack     : $Hash[SCRITPTRACK]
                    Status Code            : $StatusCode.
                    Status Message    : $StatusMessage
                    AVS Code                : $AVSCode
                    Trans.DATETIME    :    $TransactionDATETIME
                    Error                        : $Error
                    Tracking ID            : $TrackingID.";

                $EmailMessage    = $WebMessage;
                $DbMessage        = $WebMessage;
                
                

            $CCHistory;
            $CCHistory['LinkPointID']        = $myorder['oid'];
            $CCHistory['UID']                        = $myorder['ponumber'];
            $CCHistory['UserID']                = $myorder['ponumber'];
            $CCHistory['Comments']            = $myorder['comments'];
                      
            $CCHistory['Amount']                = $Hash['Amount'];
                      
            $CCHistory['CName']                    = $myorder['name'];
            $CCHistory['CEmail']                = $myorder['email'];
            $CCHistory['CTel']                    = $myorder['phone'];
        
            $CCHistory['CCNumber']            = $myorder['cardnumber'];
            $CCHistory['CCType']                = $Hash['Type'];
            $CCHistory['CCVNumber']            = $myorder['cvmvalue'];
            $CCHistory['CCExpiryMonth']    = $myorder['cardexpmonth'];
            $CCHistory['CCExpiryYear']    = $myorder['cardexpyear'];
            
            $CountryName =    $myorder['country'];
            
            $CCHistory['CAddress']            = $myorder["address1"] . " " . $myorder["address2"] . "," . $myorder[city].", ". $myorder[state]."-".$myorder[zip] . "," . $CountryName . ".";
            $CCHistory['IP']                        = $myorder['ip'];
            $CCHistory['StatusCode']        = $StatusCode;
            $CCHistory['StatusMessage']    = $StatusMessage;
            $CCHistory['StatusApproval']= $CP_Hash['statusApproval'];
            $CCHistory['AVSCode']                = $AVSCode;
            $CCHistory['TrackingID']        = $TrackingID;
            $CCHistory['Type']                    = "AUTH";
            $CCHistory['DateTime']            = $myADb-> currentDbDate();
            
            $fCCHistAdded    = $myCreditCard->addCCHistory($CCHistory);
            
            
            $fCCError            = 1;
            $ErrorMessage    ="";
            $ReturnError        ="";
        
        
        if(substr($CP_Hash['r_avs'],0,10)=="SGS-005005"){
            
            $DeclinedReson = "Please contact support and mention this error code: E5005.";
            return "-5005";
            
    }
    
    if(substr($CP_Hash['r_avs'],0,10)=="SGS-000002"){
            
            
            $DeclinedReson = "Declined by the issuing credit card bank. <br>Customer needs to contact credit card issuing bank to have them allow the transaction";
            return "-5002";
            
    }
    

    if ($StatusMessage == 'APPROVED'){
            $ReturnError    =1;
            return $ReturnError;
        
    }else if($StatusMessage == 'DECLINED' || $StatusMessage == 'DUPLICATE' ) {
            $ReturnError    =-1;
            return $ReturnError;
    
    
    }    
            return $ReturnError;
        
        
        
}


function chargeCreditCard($Hash)
{
     
     
        // include_once "/var/www/vhosts/didx.net/httpdocs/lphp.php";
        
        
        // global $DeclinedReson;
       // global $myAVS;
        $myGeneral = new General();
        $UID =  currentUser();
        $myCreditCard = new CreditCard();
        $myADb  = new ADb();
        
	     $HostName    = LP_HOST;
	     $StoreName    = LP_STORE;
	     $Port            = LP_PORT;
	     $KeyFile        = LP_CERT;#"../LPERL/530298.pem";
	     $Mode          = LP_MODE;
                 
        $mylphp = new Lphp();
        //global $myGeneral,$myCreditCard;
        
       
        $BP_TransactionHash =array( 
        	"host" => $HostName,
        	"port" => $Port,
        	"keyfile" => $KeyFile, 
        	"configfile"   => $StoreName,             
        	"ordertype"    => 'POSTAUTH',
        	"result"       => $Mode,                      
        	"chargetotal"  => $Hash['Amount'],
        	"cardnumber"   => $Hash['Number'],
        	"cardexpmonth" => $Hash['ExpiryMonth'],
        	"cardexpyear"  => substr($Hash['ExpiryYear'],2,2),
            "oid"          => $Hash['LPID'],         
           );
           
           
        $CP_Hash    = $mylphp->curl_process($BP_TransactionHash);
    
         $AVSCode                            =    $CP_Hash['r_avs'];
         $StatusCode                    = $CP_Hash['r_code'];
         $StatusMessage                = $CP_Hash['r_approved'];
         $TrackingID                    = "Reference No:$CP_Hash[r_ref]"; 
         $TransactionDATETIME    = $CP_Hash['r_time'];
         $Error                                = $CP_Hash['r_error'];
         $ACCEPTEDMSG                    = $CP_Hash['r_message'];
         
         
            
        if ($AVSCode == '')
        {
            $AVSCode    = "N/A";
        }
        if ($StatusCode == '')
        {
            $StatusCode    = "N/A";
        }
        if ($TrackingID == '')
        {
            $TrackingID    = "N/A";
        }

    

     $fUpdated    = $this->updateAuthorization($Hash['LPID']);
    
    $CCHistory;
    $CCHistory['LinkPointID']    = $Hash['LPID'];
    $CCHistory['UID']                    = $Hash['UID'];
    $CCHistory['UserID']            = $Hash['OID'];
    $CCHistory['Comments']        = $Hash['Comments'];
    $CCHistory['Amount']            = $Hash['Amount'];
    $CCHistory['CName']                = $Hash['Name'];
    $CCHistory['CEmail']            = $Hash['Email'];
    $CCHistory['CTel']                = $Hash['Phone'];
    $CCHistory['CAddress']        = $Hash[address1] ." " .$Hash[address1].", ".$Hash[city].", ". $Hash[state]." - ".$Hash[zip].", " .$Hash['country'] ;
    $CCHistory['CCNumber']        = $Hash['Number'];
    $CCHistory['CCType']            = $Hash['Type'];
    $CCHistory['CCVNumber']        = $Hash['VNumber'];
    $CCHistory['CCExpiryMonth']= $Hash['ExpiryMonth'];
    $CCHistory['CCExpiryYear']= $Hash['ExpiryYear'];
    $CCHistory['IP']                    = $Hash['IP'];
    $CCHistory['StatusCode']    = $StatusCode;
    $CCHistory['StatusMessage']= $StatusMessage;
    $CCHistory['StatusApproval']= $CP_Hash['statusApproval'];
    $CCHistory['AVSCode']            = $AVSCode;
    $CCHistory['TrackingID']    = $TrackingID;
    $CCHistory['Type']                = "CHRG";
    $CCHistory['DateTime']        = $myADb->currentDbDate();
    $LastFour = substr($Hash['Number'],strlen($Hash['Number'])-4,4);
    if ( $StatusMessage == 'APPROVED' || $ACCEPTEDMSG == 'ACCEPTED'  )
    {
        $CCHistory[IsCharged]=1;
    } else {
        $CCHistory[IsCharged]=0;
    }
    $fCCHistAdded                = $myCreditCard->addCCHistory($CCHistory);
    
    

    if ( $StatusMessage == 'APPROVED' || $ACCEPTEDMSG =='ACCEPTED')
    {
                    
        $OID=$Hash['OID'];
        $Amount=$Hash['Amount'];
        $LPID=$Hash['LPID'];

        (file_get_contents($GLOBALS['website_url']."/SendtransactionApproved?OID=$UID&Amount=$Hash[Amount]&Name=$Hash[Name]&AVSCodeAll=$AVSCodeAll&last4numbers=$LastFour"));         
        return 1;
      
    }
    else
    {
        return -1;
    }
    
}



function updateAuthorization($LPID) {
    
            //global $myADb;
    
    $strSQL    = "    update cchistory
                set
                    ischarged=1
                where
                    linkpointid='$LPID'
                And
                    ischarged=0
                And
                    type='AUTH'
                And
                    statuscode='1'
            ";
    
    $Result    = $this->Db->ExecuteQuery($strSQL);
    
    return 1;

}

function GetDecline($pOID) {
    
    // global $myADb;
    
    $strSQL="select decline from cc_decline where oid=\"$pOID\" and susdate=curdate() ";
    $Result    = $this->Db->ExecuteQuery($strSQL);
    
    if($Result->fields[0]>=10){
            return "-1";
    }else{
            return "1";
    }
        
    
    

}


function AddDecline($pOID) {
    
    //global $myADb;
    
    $strSQL="select * from cc_decline where oid=\"$pOID\" and susdate=curdate() ";
    $Result    = $this->Db->ExecuteQuery($strSQL);
    //$Result    = $myADb->ExecuteQuery($strSQL);
    
    if(!$Result->EOF){
        
        $strSQL="update cc_decline set Decline=Decline+1 where OID='$pOID' and susdate=curdate()";
       // $Result    = $myADb->ExecuteQuery($strSQL);
        $Result    = $this->Db->ExecuteQuery($strSQL);
        
    }else{
        
        $strSQL="insert into cc_decline(OID,Decline,SusDate)value('$pOID','1',curdate())";
        //$Result    = $myADb->ExecuteQuery($strSQL);
        $Result    = $this->Db->ExecuteQuery($strSQL);
    }
        
    

}


	
}//class

/*

package CreditCard;
# 	Copyright�2002 Ahmed Shaikh Memon. All rights reserved.
$fDebug = 0;
sub dPrint { if ($fDebug) {print "@_ <br>";} }

require Exporter;
@ISA=(Exporter);
@EXPORT	= qw (
			addCreditCard
			addCCHistory
			getCreditCardInfoByCustomerID
			getCCHistoryInfoByOID
			editCreditCard
			getCCHistoryInfo
			getCCHistoryHTML
			getCCPendingAuthorizationTable
			getPendingAuthorization
			getChargeableAuthorization
			updateAuthorization
			getPendingAuthorizationOfLast30Days
			getSingleCCTransaction
			editCreditCardWithOutNo

			);
use Db;
use General;

#	addCreditCard
#	IN:	
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub addCreditCard {
	my (%Hash) = @_;

	my $strSQL	= "	insert into
					creditcard
					(
						ccid,
					customerid,
					addressid,
					name,
					number,
					type,
					vnumber,
					expirymonth,
					expiryyear
					)
				values (
					\"$Hash{CCID}\",
						\"$Hash{CustomerID}\",
						\"$Hash{AddressID}\",
						\"$Hash{Name}\",
						\"$Hash{Number}\",
						\"$Hash{Type}\",
						\"$Hash{VNumber}\",
						\"$Hash{ExpiryMonth}\",
						\"$Hash{ExpiryYear}\"
					)
					
					";
	
	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[0]: $Result[0]";

	return $Result[0];

}# addCreditCard


#	addCCHistory
#	IN:	
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub addCCHistory {
	my (%Hash) = @_;

	my $strSQL	= "	insert into
					cchistory
					(
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
					)
				values (
					\"$Hash{LinkPointID}\",
						\"$Hash{UID}\",
						\"$Hash{UserID}\",
						\"$Hash{CName}\",
						\"$Hash{CAddress}\",
						\"$Hash{CTel}\",
						\"$Hash{CEmail}\",
						\"$Hash{CCNumber}\",
						\"$Hash{CCType}\",
						\"$Hash{CCVNumber}\",
						\"$Hash{CCExpiryMonth}\",
						\"$Hash{CCExpiryYear}\",
						\"$Hash{StatusCode}\",
						\"$Hash{StatusMessage}\",
						\"$Hash{StatusApproval}\",
						\"$Hash{AVSCode}\",
						\"$Hash{TrackingID}\",
						\"$Hash{Amount}\",
						\"$Hash{Type}\",
						\"$Hash{Comments}\",
						0,
						\"$Hash{DateTime}\"
					)
					
					";
	
	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[0]: $Result[0]";

	return $Result[0];

}# addCCHistory

#	updateAuthorization
#	IN:	LinkPointID
#	OUT:	true/false
sub updateAuthorization{
	my $strSQL	= "	update cchistory
				set
					ischarged=1
				where
					linkpointid='$_[0]'
				And
					ischarged=0
				And
					type='AUTH'
				And
					statuscode='1'
			";
	
	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[0]: $Result[0]";

	return $Result[0];

}#updateAuthorization


#	getCreditCardInfoByCustomerID
#	IN:	CustomerID
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed

#	getCCHistoryInfoByOID
#	IN:	LinkPointID
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub getCCHistoryInfoByOID {
	my ($pOID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					userid=\"$pOID\"";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";
	return @Result;
#
#	my %Hash;
#
#	$Hash{LinkPointID}	= $Result[1][0];
#	$Hash{UID}	= $Result[1][1];
#	$Hash{UserID}	= $Result[1][2];
#	$Hash{CName}	= $Result[1][3];
#	$Hash{CAddress}	= $Result[1][4];
#	$Hash{CTel}	= $Result[1][5];
#	$Hash{CEmail}	= $Result[1][6];
#	$Hash{CCNumber}	= $Result[1][7];
#	$Hash{CCType}	= $Result[1][8];
#	$Hash{CCVNumber}	= $Result[1][9];
#	$Hash{CCExpiryMonth}	= $Result[1][10];
#	$Hash{CCExpiryYear}	= $Result[1][11];
#	$Hash{StatusCode}	= $Result[1][12];
#	$Hash{StatusMessage}	= $Result[1][13];
#	$Hash{StatusApproval}	= $Result[1][14];
#	$Hash{AVSCode}	= $Result[1][15];
#	$Hash{TrackingID}	= $Result[1][16];
#	$Hash{Amount}	= $Result[1][17];
#	$Hash{Type}	= $Result[1][18];
#	$Hash{Comments}	= $Result[1][19];
#	$Hash{DateTime}	= $Result[1][20];
#	
#	return %Hash;

}# getCCHistoryInfoByOID



#	getPendingAuthorization
#	IN:	OID
#	OUT:	Hash
#	DateTime: Wed Jun 18 00:15:29 2003
#	Author:	Ahmed
sub getPendingAuthorization {
	my ($pOID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					userid='$pOID'
				And
					type='AUTH'
				And
					ischarged=0
				";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";

	#my %Hash;
	#
	#$Hash{LinkPointID}	= $Result[1][0];
	#$Hash{UID}	= $Result[1][1];
	#$Hash{UserID}	= $Result[1][2];
	#$Hash{CName}	= $Result[1][3];
	#$Hash{CAddress}	= $Result[1][4];
	#$Hash{CTel}	= $Result[1][5];
	#$Hash{CEmail}	= $Result[1][6];
	#$Hash{CCNumber}	= $Result[1][7];
	#$Hash{CCType}	= $Result[1][8];
	#$Hash{CCVNumber}	= $Result[1][9];
	#$Hash{CCExpiryMonth}	= $Result[1][10];
	#$Hash{CCExpiryYear}	= $Result[1][11];
	#$Hash{StatusCode}	= $Result[1][12];
	#$Hash{StatusMessage}	= $Result[1][13];
	#$Hash{StatusApproval}	= $Result[1][14];
	#$Hash{AVSCode}	= $Result[1][15];
	#$Hash{TrackingID}	= $Result[1][16];
	#$Hash{Amount}	= $Result[1][17];
	#$Hash{Type}	= $Result[1][18];
	#$Hash{Comments}	= $Result[1][19];
	#$Hash{DateTime}	= $Result[1][20];
	#
	return @Result;

}# getPendingAuthorization


#	getAllCCHistoryInfoByOID
#	IN:	LinkPointID
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub getAllCCHistoryInfoByOID {
	my ($pOID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					userid='$pOID'
				order by linkpointid

				";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	return @Result;
}#getAllCCHistoryInfoByOID

#	editCreditCard
#	IN:	CCID
#	OUT:	bool
#	DateTime: Tuesday Mar 30 2:08:33 2004
#	Author:	Arif
sub editCreditCardWithOutNo {
	my (%pHash) = @_;

	#foreach $k (keys %pHash){
	#	print "\$pHash{$k}: $pHash{$k}";
	#}

	my $strSQL	= "	update creditcard
				set
					customerid	= \"$pHash{CustomerID}\",
					addressid	= \"$pHash{AddressID}\",
					name		= \"$pHash{Name}\",
					type		= \"$pHash{Type}\",
					vnumber		= \"$pHash{VNumber}\",
					expirymonth	= \"$pHash{ExpiryMonth}\",
					expiryyear	= \"$pHash{ExpiryYear}\"
					
				where
					ccid = '$pHash{CCID}'

					
					";
	
	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[0]: $Result[0]";

	return $Result[0];

}# editCreditCard

#	editCreditCard
#	IN:	CCID
#	OUT:	bool
#	DateTime: Wed Mar 12 22:54:33 2003
#	Author:	Ahmed
sub editCreditCard {
	my (%pHash) = @_;

	#foreach $k (keys %pHash){
	#	print "\$pHash{$k}: $pHash{$k}";
	#}

	my $strSQL	= "	update creditcard
				set
					customerid	= \"$pHash{CustomerID}\",
					addressid	= \"$pHash{AddressID}\",
					name		= \"$pHash{Name}\",
					number		= \"$pHash{Number}\",
					type		= \"$pHash{Type}\",
					vnumber		= \"$pHash{VNumber}\",
					expirymonth	= \"$pHash{ExpiryMonth}\",
					expiryyear	= \"$pHash{ExpiryYear}\"
					
				where
					ccid = '$pHash{CCID}'

					
					";
	
	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[0]: $Result[0]";

	return $Result[0];

}# editCreditCard

#	getCCHistoryInfo
#	IN:	LinkPointID
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub getCCHistoryInfo {
	my ($pLinkPointID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					linkpointid=\"$pLinkPointID\"";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";

	my %Hash;

	$Hash{LinkPointID}	= $Result[1][0];
	$Hash{UID}	= $Result[1][1];
	$Hash{UserID}	= $Result[1][2];
	$Hash{CName}	= $Result[1][3];
	$Hash{CAddress}	= $Result[1][4];
	$Hash{CTel}	= $Result[1][5];
	$Hash{CEmail}	= $Result[1][6];
	$Hash{CCNumber}	= $Result[1][7];
	$Hash{CCType}	= $Result[1][8];
	$Hash{CCVNumber}	= $Result[1][9];
	$Hash{CCExpiryMonth}	= $Result[1][10];
	$Hash{CCExpiryYear}	= $Result[1][11];
	$Hash{StatusCode}	= $Result[1][12];
	$Hash{StatusMessage}	= $Result[1][13];
	$Hash{StatusApproval}	= $Result[1][14];
	$Hash{AVSCode}	= $Result[1][15];
	$Hash{TrackingID}	= $Result[1][16];
	$Hash{Amount}	= $Result[1][17];
	$Hash{Type}	= $Result[1][18];
	$Hash{Comments}	= $Result[1][19];
	$Hash{IsCharged}= $Result[1][20];
	$Hash{DateTime}	= $Result[1][21];
	
	return %Hash;

}# getCCHistoryInfo

#	getCCHistoryHTML
#	IN:	OID
#	OUT:	CC History HTML
sub getCCHistoryHTML {
	my @History	= getAllCCHistoryInfoByOID($_[0]);
	my $html	= "<table width=700>";
	$html	.= "<tr bgcolor=#FF9900>";
		$html	.= "<td><font face=arial size=-1>LPID</font></td>";
		$html	.= "<td><font face=arial size=-1>Comments</font></td>";
		$html	.= "<td><font face=arial size=-1>StatusCode</font></td>";
		$html	.= "<td><font face=arial size=-1>StatusMessage</font></td>";
		$html	.= "<td><font face=arial size=-1>StatusApproval</font></td>";
		$html	.= "<td><font face=arial size=-1>AVSCode</font></td>";
		$html	.= "<td><font face=arial size=-1>Amount</font></td>";
		$html	.= "<td><font face=arial size=-1>Type</font></td>";
		$html	.= "<td><font face=arial size=-1>DateTime</font></td>";
	$html	.= "</tr>";

	if ($History[1][0] eq ''){
		$html	= "<tr><td colspan=6 align=center><font face=verdana size=-1 color=red>No entry</font></td></tr>";
	}
	else {
		my $fColor	= 1;
		for ($nIndex=1; $nIndex<=$#History; $nIndex++){
			$LPID		= $History->fields[0];
			$StatusCode	= $History->fields[12];
			$StatusMessage	= $History->fields[13];
			$StatusApproval	= $History->fields[14];
			$AVSCode	= $History->fields[15];
			$TrackingID	= $History->fields[16];
			$Amount		= $History->fields[17];
			$TransType	= $History->fields[18];
			$Comments	= $History->fields[19];
			$DateTime	= convertDbToFormDate($History->fields[20]);

			# New Row
			if ($fColor){
				$html	.= "<tr>";
				$fColor	=0;
			}
			else {
				$html	.= "<tr bgcolor=#FFFFCC>";
				$fColor	=1;
			}
			
			# LPID
			$html	.= "<td align=center><font face=verdana size=-1>$LPID</font></td>";

			# Comments
			$html	.= "<td align=center><font face=verdana size=-1>$Comments</font></td>";

			# StatusCode
			$html	.= "<td align=center><font face=verdana size=-1>$StatusCode</font></td>";

			# StatusMessage
			$html	.= "<td align=center><font face=verdana size=-1>$StatusMessage</font></td>";

			# StatusApproval
			$html	.= "<td align=center><font face=verdana size=-1>$StatusApproval</font></td>";

			# AVSCode
			$html	.= "<td align=center><font face=verdana size=-1>$AVSCode</font></td>";

#			# TrackingID
#			$html	.= "<td align=center><font face=verdana size=-1>$TrackingID</font></td>";

			# Amount
			$html	.= "<td align=right><font face=verdana size=-1>\$$Amount.00</font></td>";

			# Type
			if ($TransType eq "AUTH"){
				$html	.= "<td align=right><font face=verdana size=-1>Authorize</font></td>";
			}
			else {
				$html	.= "<td align=right><font face=verdana size=-1>Charge</font></td>";
			}

			# DateTime
			$html	.= "<td align=center><font face=verdana size=-1>$DateTime</font></td>";

			# End of row
			$html	.= "</tr>";
		}# For
	}# else
	$html	.= "</table>";
	return $html;
}#getCCHistoryHTML


#	getCCTransactionsToChargeHTML
#	IN:	OID
#	OUT:	CC History HTML
sub getCCTransactionsToChargeHTML {
	my @History	= getAllCCHistoryInfoByOID($_[0]);
	my $html	= "<table width=700>";
	$html	.= "<tr bgcolor=#FF9900>";
		$html	.= "<td><font face=arial size=-1>LPID</font></td>";
		$html	.= "<td><font face=arial size=-1>Comments</font></td>";
		$html	.= "<td><font face=arial size=-1>StatusCode</font></td>";
		$html	.= "<td><font face=arial size=-1>StatusMessage</font></td>";
		$html	.= "<td><font face=arial size=-1>StatusApproval</font></td>";
		$html	.= "<td><font face=arial size=-1>AVSCode</font></td>";
		$html	.= "<td><font face=arial size=-1>Amount</font></td>";
		$html	.= "<td><font face=arial size=-1>Type</font></td>";
		$html	.= "<td><font face=arial size=-1>DateTime</font></td>";
	$html	.= "</tr>";

	if ($History[1][0] eq ''){
		$html	= "<tr><td colspan=6 align=center><font face=verdana size=-1 color=red>No entry</font></td></tr>";
	}
	else {
		my $fColor	= 1;
		for ($nIndex=1; $nIndex<=$#History; $nIndex++){
			$LPID		= $History->fields[0];
			$StatusCode	= $History->fields[12];
			$StatusMessage	= $History->fields[13];
			$StatusApproval	= $History->fields[14];
			$AVSCode	= $History->fields[15];
			$TrackingID	= $History->fields[16];
			$Amount		= $History->fields[17];
			$TransType	= $History->fields[18];
			$Comments	= $History->fields[19];
			$DateTime	= convertDbToFormDate($History->fields[20]);

			# New Row
			if ($fColor){
				$html	.= "<tr>";
				$fColor	=0;
			}
			else {
				$html	.= "<tr bgcolor=#FFFFCC>";
				$fColor	=1;
			}
			
			# LPID
			$html	.= "<td align=center><font face=verdana size=-1>$LPID</font></td>";

			# Comments
			$html	.= "<td align=center><font face=verdana size=-1>$Comments</font></td>";

			# StatusCode
			$html	.= "<td align=center><font face=verdana size=-1>$StatusCode</font></td>";

			# StatusMessage
			$html	.= "<td align=center><font face=verdana size=-1>$StatusMessage</font></td>";

			# StatusApproval
			$html	.= "<td align=center><font face=verdana size=-1>$StatusApproval</font></td>";

			# AVSCode
			$html	.= "<td align=center><font face=verdana size=-1>$AVSCode</font></td>";

#			# TrackingID
#			$html	.= "<td align=center><font face=verdana size=-1>$TrackingID</font></td>";

			# Amount
			$html	.= "<td align=right><font face=verdana size=-1>\$$Amount.00</font></td>";

			# Type
			if ($TransType eq "AUTH"){
				$html	.= "<td align=right><font face=verdana size=-1>Authorize</font></td>";
			}
			else {
				$html	.= "<td align=right><font face=verdana size=-1>Charge</font></td>";
			}

			# DateTime
			$html	.= "<td align=center><font face=verdana size=-1>$DateTime</font></td>";

			# End of row
			$html	.= "</tr>";
		}# For
	}# else
	$html	.= "</table>";
	return $html;
}#getCCTransactionsToChargeHTML


#	getCCTransactionsToCharge
#	IN:	LinkPointID
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub getCCTransactionsToCharge {
	my ($pOID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					datetime
				from
					cchistory
				where
					userid='$pOID'
				And
					type ='AUTH'
				And
					statusapproval='APPROVED'
				order by linkpointid

				";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	return @Result;
}#getCCTransactionsToCharge


#	getChargeableAuthorization
#	IN:	LinkPointID
#	OUT:	Hash
#	DateTime: Fri Feb 28 00:15:29 2003
#	Author:	Ahmed
sub getChargeableAuthorization{
	my ($pLinkPointID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					linkpointid='$pLinkPointID'
				And
					statuscode='1'
				And
					type='AUTH'
				And
					ischarged=0
					";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";

	my %Hash;

	$Hash{LinkPointID}	= $Result[1][0];
	$Hash{UID}	= $Result[1][1];
	$Hash{UserID}	= $Result[1][2];
	$Hash{CName}	= $Result[1][3];
	$Hash{CAddress}	= $Result[1][4];
	$Hash{CTel}	= $Result[1][5];
	$Hash{CEmail}	= $Result[1][6];
	$Hash{CCNumber}	= $Result[1][7];
	$Hash{CCType}	= $Result[1][8];
	$Hash{CCVNumber}	= $Result[1][9];
	$Hash{CCExpiryMonth}	= $Result[1][10];
	$Hash{CCExpiryYear}	= $Result[1][11];
	$Hash{StatusCode}	= $Result[1][12];
	$Hash{StatusMessage}	= $Result[1][13];
	$Hash{StatusApproval}	= $Result[1][14];
	$Hash{AVSCode}	= $Result[1][15];
	$Hash{TrackingID}	= $Result[1][16];
	$Hash{Amount}	= $Result[1][17];
	$Hash{Type}	= $Result[1][18];
	$Hash{Comments}	= $Result[1][19];
	$Hash{IsCharged}= $Result[1][20];
	$Hash{DateTime}	= $Result[1][21];
	
	return %Hash;

}# getChargeableAuthorization



#	getPendingAuthorizationOfLast30Days
#	IN:	OID
#	OUT:	Hash
#	DateTime: Wed Jun 18 00:15:29 2003
#	Author:	Ahmed
sub getPendingAuthorizationOfLast30Days {
	my ($pOID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					userid='$pOID'
				And
					type='AUTH'
				And
					ischarged=0
				And
					StatusMessage='APPROVED'
				And
					avscode not like '%NNN'
				And
					TO_DAYS(NOW()) - TO_DAYS(datetime) <= 30
				and
					trackingid <> 'WIRETRANSFER'
				";
#above code added by Danish Moosa
#and
#TrackingID <> 'WIRETRANSFER'
	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";
	return @Result;

}# getPendingAuthorizationOfLast30Days


#	getSingleCCTransaction
#	IN:	LPID
#	OUT:	Hash
#	DateTime: Wed Jun 18 00:15:29 2003
#	Author:	Ahmed
sub getSingleCCTransaction {
	my ($pLPID) = @_;

	my $strSQL	= "	select
					linkpointid,
					uid,
					userid,
					cname,
					caddress,
					ctel,
					cemail,
					ccnumber,
					cctype,
					ccvnumber,
					ccexpirymonth,
					ccexpiryyear,
					statuscode,
					statusmessage,
					statusapproval,
					avscode,
					trackingid,
					amount,
					type,
					comments,
					ischarged,
					datetime
				from
					cchistory
				where
					linkpointid='$pLPID'
				";

	dPrint "\$strSQL: $strSQL";

	my @Result	= Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";

	my %Hash;
	
	$Hash{LPID}		= $Result[1][0];
	$Hash{UID}		= $Result[1][1];
	$Hash{UserID}		= $Result[1][2];
	$Hash{CName}		= $Result[1][3];
	$Hash{CAddress}		= $Result[1][4];
	$Hash{CTel}		= $Result[1][5];
	$Hash{CEmail}		= $Result[1][6];
	$Hash{CCNumber}		= $Result[1][7];
	$Hash{CCType}		= $Result[1][8];
	$Hash{CCVNumber}	= $Result[1][9];
	$Hash{CCExpiryMonth}	= $Result[1][10];
	$Hash{CCExpiryYear}	= $Result[1][11];
	$Hash{StatusCode}	= $Result[1][12];
	$Hash{StatusMessage}	= $Result[1][13];
	$Hash{StatusApproval}	= $Result[1][14];
	$Hash{AVSCode}		= $Result[1][15];
	$Hash{TrackingID}	= $Result[1][16];
	$Hash{Amount}		= $Result[1][17];
	$Hash{Type}		= $Result[1][18];
	$Hash{Comments}		= $Result[1][19];
	$Hash{IsCharged}	= $Result[1][20];
	$Hash{DateTime}		= $Result[1][21];
	
	return %Hash;

}# getSingleCCTransaction


*/



?>
