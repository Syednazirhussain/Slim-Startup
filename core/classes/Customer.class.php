<?php
//include "Const.inc.php";
//include_once $INCLUDEPATH."/ADb.inc.php";
class Customer
{
    // 	Copyright© 2011 Huzoor Bux Panhwar. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Customer()
    {
        $this->Db = new ADb();
    }
    function getPrimaryCardDetail($pCustomerID)
    {
        $strSQL	= "	select
					ccid,
					customerid,
					addressid,
					name,
					right(number,4),
					type,
					vnumber,
					expirymonth,
					expiryyear,
					PriCard
		from
					creditcard
		where
					customerid=\"$pCustomerID\"
				    and PriCard=1";
        
        $Result	= $this->Db->ExecuteQuery($strSQL);
        return $Result;
	}
    function getCustomerCompany($pUID)
    {
        $strSQL = "select CCompany from customer where uid=\"$pUID\" ";
		$Result	= $this->Db->ExecuteQuery($strSQL);	
		return $Result->fields[0];
	}
    function getCustomerNameByUID($pUID)
    {
        $strSQL	= "	select
					concat(cfname,' ',
					cmname,' ',
					clname)
				from
					customer
				where
					uid=\"$pUID\"";
        $Result	= $this->Db->ExecuteQuery($strSQL);
        return $Result->fields[0];
    }
    function getCustomerEmailbyUID($pUID)
    {
        $strSQL	= "	select
                    cemail
				from
					customer
				where
					uid=\"$pUID\"";
        $Result	= $this->Db->ExecuteQuery($strSQL);
        return $Result->fields[0];
    }
	function getCustomerInfoByUID($pUID) 
	{
      $strSQL	= "	select		customerid,		uid,	addressid,	csalutation,		cfname,			cmname,			clname,			cemail,CCompany	,Ctelhome
		,cteloff,ccell,cfax,cwebsite,ExtNo
				from			customer		where		uid	=\"$pUID\"";
		$Result	= $this->Db->ExecuteQuery($strSQL);

		$Customer = array();
		if(!$Result->EOF)
		{
            $Customer['CustomerID']	= $Result->fields[0];
			$Customer['UID']	= $Result->fields[1];
			$Customer['AddressID']	= $Result->fields[2];
			$Customer['Salutation']	= $Result->fields[3];	
			$Customer['FName']	= $Result->fields[4];
			$Customer['MName']	= $Result->fields[5];
			$Customer['LName']	= $Result->fields[6];
			$Customer['CEmail']	= $Result->fields[7];
			$Customer['CCompany']	= $Result->fields[8];
			$Customer['CTel']	= $Result->fields[9];
			$Customer['TelOff']	= $Result->fields[10];
			$Customer['Cell']	= $Result->fields[11];
			$Customer['Fax']	= $Result->fields[12];			
            $Customer['CWebSite']    = $Result->fields[13];        
			$Customer['ExtNo']	= $Result->fields[14];		
		}
		//print_r($Customer);
		return $Customer;	
	}
    function getCustomerEmailPref($pUID) 
	{
        $strSQL = "select uid,NewsLetter,ReqDID,DIDProb,DIDSold,
					 TechContactName,
					 TechContactEmail,
					 TechContactPhone,
					 DIDBought,ServerType,ServerIP,AccountName,AccountEmail,DefaultRingtoType,DefaultRingto,InvPayNote,SendDIDRel,
					 MSN,yahoo,icq,skype,PayPal,TTAutoPay,TicketEmail, DefaultRingToType, DefaultRingTo, H323, TTMinsEmail,
                     TesterFailed,TesterPassed, fbprofile,moneybookersid, CXID, CXPass,SecureSite,TalkTimeAlert,missed_calls
					 from EmailPref where uid=\"$pUID\" ";
					 

		$Result = $this->Db->ExecuteQuery($strSQL);
        $Hash = array();	
		$Hash['UID']        = $Result->fields[0];
		$Hash['NewsLetter'] = $Result->fields[1];
		$Hash['ReqDID']     = $Result->fields[2];
		$Hash['DIDProb']    = $Result->fields[3];
		$Hash['DIDSold']    = $Result->fields[4];
		$Hash['TechName']   = $Result->fields[5];
		$Hash['TechEmail']  = $Result->fields[6];
		$Hash['TechPhone']  = $Result->fields[7];
		$Hash['DIDBought']  = $Result->fields[8];
		$Hash['ServerType'] = $Result->fields[9];
		$Hash['ServerIP']   = $Result->fields[10];
		$Hash['AccountName']= $Result->fields[11];	
		$Hash['AccountEmail']= $Result->fields[12];	
		$Hash['RingToType'] = $Result->fields[13];	
		$Hash['RingTo']     = $Result->fields[14];	
		$Hash['InvPayNote'] = $Result->fields[15];	
		$Hash['SendDIDRel'] = $Result->fields[16];	
		$Hash['MSN']        = $Result->fields[17];	
		$Hash['Yahoo']      = $Result->fields[18];	
		$Hash['ICQ']        = $Result->fields[19];	
		$Hash['Skype']      = $Result->fields[20];	
		$Hash['PayPal']     = $Result->fields[21];	
		$Hash['AutoPay']    = $Result->fields[22];	
		$Hash['TicketEmail']= $Result->fields[23];	
		$Hash['DefaultRingtoType']= $Result->fields[24];	
		$Hash['DefaultRingto']= $Result->fields[25];	
		$Hash['H323']       = $Result->fields[26];	
		$Hash['MinEmail']   = $Result->fields[27];	
		$Hash['DIDFail']    = $Result->fields[28];	
		$Hash['DIDPass']    = $Result->fields[29];	
		$Hash['FBProfile']  = $Result->fields[30];	
		$Hash['MBID']       = $Result->fields[31];	
		$Hash['CXID']       = $Result->fields[32];	
        $Hash['CXPass']     = $Result->fields[33];    
        $Hash['SecureSite']     = $Result->fields[34];    
		$Hash['TalkTimeAlert']     = $Result->fields[35];	
		$Hash['missed_calls']     = $Result->fields[36];	
		return $Hash;	
	}
    function editCustomer($pHash) 
    {
        $strSQL = "Update customer Set
                    uid        = \"$pHash[UID]\",
                    addressid    = \"$pHash[AddressID]\",
                    csalutation    = \"$pHash[CSalutation]\",
                    cfname        = \"$pHash[CFName]\",
                    cmname        = \"$pHash[CMName]\",
                    clname        = \"$pHash[CLName]\",
                    cemail        = \"$pHash[CEmail]\",
                    cssn        = \"$pHash[CSSN]\",
                    ccompany    = \"$pHash[CCompany]\",
                    cwebsite    = \"$pHash[CWebSite]\",
                    cteloff        = \"$pHash[CTelOff]\",
                    ctelhome    = \"$pHash[CTelHome]\",
                    ccell        = \"$pHash[CCell]\",
                    cfax        = \"$pHash[CFax]\"
                Where
                    customerid = \"$pHash[CustomerID]\"";
        //echo $strSQL;
        $Result = $this->Db->ExecuteQuery($strSQL);
        return $Result;
    }
    function getCustomerInfo($pCustomerID) 
    {
        $strSQL    = "    select        customerid,    uid,    addressid,    csalutation,    cfname,    cmname,    clname,        cemail,    cssn,    ccompany,    cteloff,
                    ctelhome,    ccell,    cfax, cwebsite
                    from        customer        where        customerid='$pCustomerID'";
        $Result    = $this->Db->ExecuteQuery($strSQL);
        $Customer = array();
        
            $Customer["CustomerID"]    = $Result->fields[0];
            $Customer["UID"]    = $Result->fields[1];
            $Customer["AddressID"]    = $Result->fields[2];
            $Customer["Salutation"]    = $Result->fields[3];
            $Customer["FName"]    = $Result->fields[4];
            $Customer["MName"]    = $Result->fields[5];
            $Customer["LName"]    = $Result->fields[6];
            $Customer["CEmail"]    = $Result->fields[7];
            $Customer["SSN"]    = $Result->fields[8];
            $Customer["Company"]    = $Result->fields[9];
            $Customer["TelOff"]    = $Result->fields[10];
            $Customer["TelHome"]    = $Result->fields[11];
            $Customer["Cell"]    = $Result->fields[12];
            $Customer["Fax"]    = $Result->fields[13];
            $Customer["WebSite"]    = $Result->fields[14];
        return $Customer;
    }
    
    function CheckIfIgotApproval($pOID,$pDIDNumber) {
	
	$myADb = new ADb();
	
	$strSQL = " select * from CusDocs where (status=1) and OID=\"$pOID\" and DID=\"$pDIDNumber\" ";
	
	$Result	= $myADb->ExecuteQuery($strSQL);
	
		if(!$Result->EOF) {
		
		return 1;	
			
		}else{
			
			return 0;
		}
} 

    public function checkEmail($email) 
	{
        $strSQL = "select cemail from customer where cemail='".$email."'";
        $Result    = $this->Db->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}

	public function blockEmail($EmailDomain) 
	{
        $strSQL = "select domain from blockeddomain where domain='".$EmailDomain."'";
        $Result    = $this->Db->ExecuteQuery($strSQL); 
		return $Result->fields[0];
	}      

	public function getOldOID() 
	{
        $strSQL = "Select OID From counter";
        $Result    = $this->Db->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}

	public function updateOldOID($NewOID,$OldOID) 
	{
        $strSQL = "Update counter Set OID = \"$NewOID\" Where OID = \"$OldOID\"";
        $Result    = $this->Db->ExecuteQuery($strSQL);       
		return $Result;
	}
	
	public function checkPreviousRecord($pFName,$pLName,$pDomain,$pCompany){
        $strSQL = "select * from customer where (cemail like '%@" . "$pDomain' or (cfname='$pFName' and  cfname='$pLName') or ccompany=\"$pCompany\")";
        $Result    = $this->Db->ExecuteQuery($strSQL);       
		if(!$Result->EOF)
	    {
	        return 1;
	    }
	    else
	    {
	        return 0;
	    }
	} 

	public function addEmailPref($Hash){
        $strSQL = "insert into EmailPref (
                            UID,NewsLetter,ReqDID,ACK,DIDProb,DIDSold,msn,yahoo,icq,skype,paypal,Agreed,
                            TechContactName,TechContactEmail,TechContactPhone,AccountName,AccountEmail,Servertype,ServerIP,TestFailedEmail,SendSoldEmail,MobileNo,NOCNo)
                            values (
                                '".$Hash["UID"]."',
                                '".$Hash["NewsLetter"]."',
                                '".$Hash["ReqDID"]."',
                                '".$Hash["ACK"]."',
                                '".$Hash["DIDProb"]."',
                                '".$Hash["DIDSold"]."', 
                                '".$Hash["msn"]."',
                                '".$Hash["yahoo"]."',
                                '".$Hash["icq"]."',
                                '".$Hash["skype"]."',
                                '".$Hash["paypal"]."',
                                '".$Hash["Agreed"]."',
                                '".$Hash["TechContactName"]."', 
                                '".$Hash["TechContactEmail"]."',
                                '".$Hash["TechContactPhone"]."',
                                '".$Hash["AccountName"]."',
                                '".$Hash["AccountEmail"]."',
                                '".$Hash["Servertype"]."',
                                '".$Hash["ServerIP"]."',
                                '".$Hash["TestFailedEmail"]."',
                                '".$Hash["SendSoldEmail"]."',
                                '".$Hash["MobileNo"]."',
                                '".$Hash["NOCNo"]."'
                                )";
        $Result    = $this->Db->ExecuteQuery($strSQL);       
	        return $Result;
	    } 

 	function getReferFriendInfo($ffReferral,$ffCEmail) 
    {
        $strSQL   = "select Email,RefCode,OID from ReferFriend where RefCode=\"$ffReferral\" and Email=\"$ffCEmail\"";
       
        $Result   = $this->Db->ExecuteQuery($strSQL);
        $Customer = array();        
        $Customer["CustomerID"]    = $Result->fields[0];
        $Customer["UID"]    = $Result->fields[1];
        $Customer["AddressID"]    = $Result->fields[2];
        $Customer["Salutation"]    = $Result->fields[3];
        $Customer["FName"]    = $Result->fields[4];
        $Customer["MName"]    = $Result->fields[5];
        $Customer["LName"]    = $Result->fields[6];
        $Customer["CEmail"]    = $Result->fields[7];
        $Customer["SSN"]    = $Result->fields[8];
        $Customer["Company"]    = $Result->fields[9];
        $Customer["TelOff"]    = $Result->fields[10];
        $Customer["TelHome"]    = $Result->fields[11];
        $Customer["Cell"]    = $Result->fields[12];
        $Customer["Fax"]    = $Result->fields[13];
        $Customer["WebSite"]    = $Result->fields[14];
        return $Customer;
    }

    function getOrderIDByKey($key) 
    {
        $strSQL    = "select UID from customer where (CEMail='".$key."' or UID='".$key."')";
        $Result    = $this->Db->ExecuteQuery($strSQL);
        return $Result->fields[0];
    }

        function getCustomerInfoByKey($uid) 
    {
        $strSQL    = "select CSalutation,CFName,CLName,CEMail from customer where UID='".$uid."'";
        $Result    = $this->Db->ExecuteQuery($strSQL);
        $hash = array();
        $hash['CSalutation'] = $Result->fields[0];
        $hash['CFName'] = $Result->fields[1];
        $hash['CLName'] = $Result->fields[2];
        $hash['CEMail'] = $Result->fields[3];
        return $hash;
    }

    function addCustomer($Hash) 
	{
        $strSQL = "insert into customer(customerid,uid,addressid,csalutation,cfname,cmname,clname,cemail,cssn,ccompany,cteloff,ctelhome,ccell,cfax,cwebsite)
                    values('".$Hash['CustomerID']."','".$Hash['UID']."','".$Hash['AddressID']."','".$Hash['CSalutation']."','".$Hash['CFName']."','".$Hash['CMName']."','".$Hash['CLName']."',
                    '".$Hash['CEmail']."','".$Hash['CSSN']."','".$Hash['CCompany']."','".$Hash['CTelOff']."','".$Hash['CTelHome']."','".$Hash['CCell']."','".$Hash['CFax']."','".$Hash['CWebSite']."')";
       	$Result    = $this->Db->ExecuteQuery($strSQL);
                    
		return $Result;
	}

	// this method will update customer personal information into database
	public static function updatePersonalInfo($UID,$ffname,$fflname,$ffemail,$ffcompany,$ffwebsite){
		$myADb = new ADb();
	  if($ffname!=""){
         $FNameEdit = ",cfname='$ffname'";	  	
	  } else {
	  	 $FNameEdit="";
	  }
      
      if($fflname!=""){
              $LNameEdit = ",clname='$fflname'";
      } else {
              $LNameEdit="";
      }
      if($ffcompany!=""){
              $CNameEdit = ",ccompany='$ffcompany'";
      } else {
              $CNameEdit="";
      }
      
      $strSQL = "update customer set cwebsite='$ffwebsite' $LNameEdit $FNameEdit $CNameEdit where uid='$UID'";
      $Result = $myADb->ExecuteQuery($strSQL);
      return $Result;
	}


	//for buydid warisha


	public function SearchBackOrderAdminByPrefix($CountryCode,$StateCode,$VOID)
	{
		$myADb = new ADb();
	$strSQL = " select ID
	,Prefix
	,length(Prefix)
	,days
	,CountryCode
	,DIDArea
	,length(CountryCode)
	,length(DIDArea)
	,VendorID
	,Monthly
	,Setup
	,NXX,
	 Ratecenter,
	 VOIDRating
	 from BackOrderAdmin 	
	 where CountryCode='$CountryCode' and DIDArea='$StateCode'  Limit 0,50"; 

	$Result= $myADb->ExecuteQuery($strSQL);
	$DIDList=array();
	if(!$Result->EOF)
		{
	$DIDList['CountryCode']= $Result->fields[4];
	$DIDList['VendorID']= $Result->fields[8];
	$DIDList['VOIDRating']=$Result-> fields[13];
	$DIDList['Monthly']=$Result-> fields[9];

	
	    }
	    return $DIDList;
    }


    public function GetChannelPricing($AreaID,$VOID,$ChannelTableName) {
    
  

    $myADb = new ADb();
    
    $strSQL = "select TotalChannels,Setup,Monthly from $ChannelTableName 
    where OID='$VOID'  and AreaID='$AreaID' ";
  
    $Result = $myADb->ExecuteQuery($strSQL);
    
    if($Result->EOF){
        
            $strSQL = "select TotalChannels,Setup,Monthly from $ChannelTableName 
            where OID='$VOID'  and AreaID='-1' ";
        #echo "<br>$strSQL";
            $Result = $myADb->ExecuteQuery($strSQL);
            
                if($Result->EOF){
                    return "-1";
                }
        
    }
    
    $ChannelPrice =  $Result->fields[2];
    
    return $ChannelPrice;
    
}


function getAdminUser() 
	{
		 $myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= "select concat(aufname,' ',aulname) from adminuser where UID=\"$pUID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}

function getCustName() 
	{
		 $myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= "select concat(cfname,' ',clname) from customer where UID=\"$pUID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}

function getCustUser() 
	{
		 $myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= "select UID from adminuser where MyPic is not null  and UID=\"$pUID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function getccompany() 
	{
		 $myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= "select ccompany from customer where UID=\"$pUID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	function getEmailPref() 
	{
		 $myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= "select UID from EmailPref where MyPic is null and UID=\"$pUID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function getInboxDateDuration($pDate) 
	{
		 $myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= "select date_add(\"$pDate\",interval 2 week) > curdate()";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}
				



	public static function GetPurchassedDIDSListWhere($conditions_array){

		$myADb = new ADb();
		$WHEREClause = "";
		// buyer ID
		if (array_key_exists('BOID', $conditions_array)) {
		    $UID = $conditions_array['BOID'];
		    $WHEREClause .= " and DIDS.BOID = \"$UID\" ";
		}
		// vendor ID
		if (array_key_exists('OID', $conditions_array)) {
			$VOID = $conditions_array['OID'];
			if($VOID != ''){
				if(is_numeric($VOID)) {
					$WHEREClause .= " and DIDS.OID = \"$VOID\" ";
				}
			}
		}

		// DIDNumber 
		if (array_key_exists('DIDNumber', $conditions_array)) {
			$DID = $conditions_array['DIDNumber'];
		   if($DID != '') {
				$DID = str_replace("-","",$DID);
				$DID = str_replace(",","",$DID);
				$DID = str_replace(" ","",$DID);
				if(!is_numeric($DID)) {
					$DID="";
				}
						$WHEREClause .= " and DIDNumber like \"$DID%\" ";
				
			}
		}

		// DIDGroupLabel
		if (array_key_exists('DIDGroupLabel', $conditions_array)) {
			$BatchShow = $conditions_array['DIDGroupLabel'];
			if($BatchShow!=""){
					$WHEREClause .= " and DIDGroupLabel=\"$BatchShow\" ";
			}
		}
		//limit
		if (array_key_exists('Sort', $conditions_array)) {
			$Sort = $conditions_array['Sort'];
			if($Sort!=""){
					$WHEREClause .= "  limit 0,$Sort";
			}
		}



		// @todo limit query to add based on our pagination 
		$strSQL = "select DIDNumber,CountryN as Country,
										City,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,
										DIDS.iURL,DIDS.Id as DIDID,DIDS.id as ID,DIDS.iFlag,
										countrycd,areacd,
										DIDS.Type,date_format(iPurchasedDate,'%d-%b-%Y') as PurchasedDate,DIDS.ID as DDID ,DIDS.OID as VOID,DIDS.AreaID, HaveDocs, 
										NeedDocs, NeedDocsType, NeedDocsID,NeedDocsMsg, DIDGroupLabel
										from DIDS where Status=2 $WHEREClause"; 
			 							// $WHEREClause $OrderBy limit $start,20

		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;


	}

	public static function insertintocchistory($data){

		$myADb = new ADb();
		$TransactionID = $data['TransactionID'];
		$OID = $data['OID'];
		$CustomerName = $data['CustomerName'];
		$Street = $data['Street'];
		$City = $data['City'];
		$State = $data['State'];
		$Country = $data['Country'];
		$PayerEmail = $data['PayerEmail'];
		$StatusCode = $data['StatusCode'];
		$ShortMessage = $data['ShortMessage']; 
		$LongMessage = $data['LongMessage']; 
		$GrossAmount = $data['GrossAmount']; 
		$YesNo = $data['YesNo']; 

		// @todo limit query to add based on our pagination 
		$strSQL = "insert into 	 (LinkPointID,UID,UserID,CName,CAddress,CTel,
            CEMail,CCNumber,CCType,CCVNumber,CCExpiryMonth,CCExpiryYear,StatusCode,StatusMessage,
            StatusApproval,AVSCode,TrackingID,Amount,Type,Comments,isCharged,DateTime)
            values(\"$TransactionID\",'$OID','$OID','$CustomerName','$Street $City $State $Country','00','$PayerEmail'
            ,'','','','','','$StatusCode','$ShortMessage','$LongMessage','','$TransactionID','$GrossAmount','PPA','Charged by Customer $UID, for Amount $GrossAmount',$YesNo,now())"; 
			 							// $WHEREClause $OrderBy limit $start,20
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;


	}

	public static function getEmailAndCTelHome(){
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL = "select CEmail,CTelHome from customer where UID='$UID'"; 
		$Result = $myADb->ExecuteQuery($strSQL);
		$data = array();
		$data['CusEmail'] = $Result->fields[0];
		$data['Tel'] = $Result->fields[1];
		return $data;
	}

	function IfToMakePayment($pOID) {
		$myADb = new ADb();
		$strSQL = "select * from BillExempt where OID=\"$pOID\" ";
		$Result	= $myADb->ExecuteQuery($strSQL);
		if(!$Result->EOF){ return 1; }
		else{ return 0; }			
	}

	function RemoveFromMinutesInfo($pDID) {
		$myADb = new ADb();
		$strSQL="delete from MinutesInfo where DID=\"$pDID\" ";
		$Result	= $myADb->ExecuteQuery($strSQL);
		
		$strSQL="delete from ChannelBuy where didnumber=\"$pDID\" ";	
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function RemoveCusDocsToFreeDID($pDID) {
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="delete from  CusDocs  where OID=\"$UID\" and DID=\"$pDID\"";
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}
	Public function GetTodayLogs(){
			$myADb = new ADb();
			$UID = currentUser();
			$Year=date('Y');
			$Month=date('m');
			$Date=date('d');
			 $strSQL="SELECT *,DATE_FORMAT(`Date`,'%H:%i') as LoginTime FROM AdminMasterLog WHERE oid =$UID AND STR_TO_DATE(`date`,'%Y-%m-%d')='$Year-$Month-$Date' ORDER BY id DESC";
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}
	Public function GetYesterdayLogs() {
			$myADb = new ADb();
			$UID = currentUser();
			$Year=date('Y');
			$Month=date('m');
			$Date=date('d',strtotime("-1 days"));
		    $strSQL="SELECT *,DATE_FORMAT(`Date`,'%H:%i') as LoginTime FROM AdminMasterLog WHERE oid =$UID AND STR_TO_DATE(`date`,'%Y-%m-%d')='$Year-$Month-$Date' ORDER BY id DESC";
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
		 
	}
	Public function GetByDateLogs($Year,$Month,$Date) {
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="SELECT *,DATE_FORMAT(`Date`,'%H:%i:%s') as LoginTime FROM AdminMasterLog 

		WHERE oid =$UID AND STR_TO_DATE(`date`,'%Y-%m-%d')=
		'$Year-$Month-$Date' ORDER BY id DESC";		
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}
	Public function GetUserActivity() {
		$myADb = new ADb();
		$UID = currentUser();
		$Date=date('d');
		$Year=date('Y');
		$Month=date('m');
		$strSQL="SELECT *,DATE_FORMAT(`Date`,'%H:%i:%s') as LoginTime FROM AdminActivities INNER JOIN AdminMasterLog ON AdminActivities.ActivityID=AdminMasterLog.Activity WHERE oid=$UID AND STR_TO_DATE(`date`,'%Y-%m-%d')=
		'$Year-$Month-$Date'";		
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

  
  Public function GetUserActivityById($CatID,$Month,$Year,$Date) {
		$myADb = new ADb();
		$UID = currentUser();
		 $strSQL="SELECT *,DATE_FORMAT(`Date`,'%H:%i:%s') as LoginTime FROM AdminActivities INNER JOIN AdminMasterLog ON AdminActivities.ActivityID=AdminMasterLog.Activity WHERE oid=$UID and MyCatID=$CatID AND STR_TO_DATE(`date`,'%Y-%m-%d')=
		'$Year-$Month-$Date'";		
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}
  Public function GetUserActivityBeforeToday($date) {
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="SELECT *,DATE_FORMAT(`Date`,'%H:%i:%s') as LoginTime FROM AdminActivities INNER JOIN AdminMasterLog ON AdminActivities.ActivityID=AdminMasterLog.Activity WHERE oid=$UID AND STR_TO_DATE(`date`,'%Y-%m-%d')=
		'$date'";		
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

  function GetCusDocsKeyID($DID) {
	
	$myADb = new ADb();
	$UID = currentUser();
	$strSQL = "select KeyID from CusDocs where did='$DID' and OID='$UID'";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
	
}
//FOR UPLOAD DOCS

function getDocs(){
    
    $htmlData="";
    $myADb=new ADb();
    $strSQL = "select * from DocsName ";

    $Result    = $myADb->ExecuteQuery($strSQL);
    
           while(!$Result->EOF){
                
                    $DocName = $Result->fields[1];
                
                            $htmlData .= "<option value=\"".$Result->fields[0]."\">$DocName</option>";
           $Result->MoveNext();
            }
            
    return  $htmlData;
    
}

function getCountryAreaNameByAreaID($pAreaID) 
{
    $myADb=new ADb();
        
    $strSQL = " select DIDCountries.Description,DIDArea.Description as a from DIDCountries,DIDArea where
                            DIDArea.ID=\"$pAreaID\" and DIDArea.COuntryID=DIDCountries.ID";
                            
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $Hash;
    
    $Hash['CountryCode'] = $Result->fields[0];
    $Hash['AreaCode'] = $Result->fields[1];
    
    return $Hash;
    
}


function getOldInfo($pAreaID,$pVOID) {
    
    $myADb=new ADb();  
    $UID=currentUser();  
    $strSQL = "select Prefix from DocMsg where OID=\"$pVOID\" and (Prefix=\"$pAreaID\" or Prefix=\"-1\")   ";
    $Result    = $myADb->ExecuteQuery($strSQL);
    
    $AdminPrefix = $Result->fields[0];
    
    if($AdminPrefix == '-1') {    
                                 $strSQL = "select date_format(Date,'%d-%b-%Y'),KeyID,DocNumber,DocName from CusDocs 
                                    where OID=\"$UID\"  and status=1 and VOID=\"$pVOID\" and type!=\"text/plain\" ";
                                 
    }else{
        
         $strSQL = "select date_format(Date,'%d-%b-%Y'),KeyID,DocNumber,DocName from CusDocs 
                                    where OID=\"$UID\" and AreaID=\"$pAreaID\" and status=1 and VOID=\"$pVOID\" and type!=\"text/plain\" ";
                                
        
    }    
                                 
#    print "<br>\$strSQL: $strSQL";                                 
    $Result    = $myADb->ExecuteQuery($strSQL);
    
    $htmlData;
    
    $htmlData = "<select name=oldinfo>";
    if($Result->EOF) 
        {
            $OldButton = " disabled ";
        }
    while(!$Result->EOF)
        {
            
            $Date = $Result->fields[0];
            $KeyID = $Result->fields[1];
            $DocNumber = $Result->fields[2];
            $DocName = $Result->fields[3];
            
            
            $htmlData .= "<option value=\"$KeyID\">Dated: $Date - $DocName - Id No. $DocNumber</option>";
           $Result->MoveNext(); 
        }
        
        $htmlData .= "</select>";
        
        
        
        return $htmlData;
    
}


function GetIfAlready($pOID,$pDID) {
    
    $myADb=new ADb();  
    $strSQL = "select * from CusDocs where OID=\"$pOID\" and DID=\"$pDID\" ";
    
    $Result    = $myADb->ExecuteQuery($strSQL);
    
        if(!$Result->EOF){
            return 1;
        }else{
            return 0;
        }
}

function getKey($var) {
    return substr(md5(dechex($var).time(). rand().$var), 0, 10);
    }
//FOR UPLOAD DOCS




	
}
?>