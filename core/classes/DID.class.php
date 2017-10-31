<?php
// include_once("Const.inc.php");
// include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
class DID
{
	// 	Copyright©2005 Saleem Ahmed Kamboh. All rights reserved.
	//TEST
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function DID()
	{
		$this->ADb = new ADb();
	}

	function Current_Date() {
	
		$myADb=new ADb();
		$strSQL = "select now()";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result->fields[0];
		
    } 

    function getdidforctester(){
    	$myADb=new ADb();
    	$UID=currentUser();
    	$strSQL = "select DIDNumber,iPurchasedDate from DIDS where (BOID=\"$UID\") and status=2 order by DIDNumber";
        $Result = $myADb->ExecuteQuery($strSQL);
      
        return $Result;
    }

	function getDIDINfoByDUD($did){
		
		$DIDS = array();
		
		$strSQL = "select Id,DIDNumber,Status,OurSetupCost,OurMonthlyCharges,date_format(iPurchasedDate,'%d-%b-%Y'),OID,iURL,BOID 
		from DIDS where didnumber=\"$did\" 		";
		#echo $strSQL;
		$result = $this->ADb->Execute($strSQL);
		
		if ($result === false) die("failed"); 
		
		if(!$result->EOF) {
		
		$DIDS['PurchasedDate']	= $result->fields[5];
		$DIDS['VOID']	= $result->fields[6];
			
		}
		
		return $DIDS;
		
	}
	function getDIDInfoByIDOld($did)
	{
		$Codes = array();
		$strSQL	= "SELECT 
		id,
		didnumber,
		vendorid,
		typeid,
		date,
		info,
		OrderID,
		SetupCost,
		MonthlyCost,
		IsMonthlyTerminated,
		PerMinuteOrignation,
		country,
		city,
		status,
		carrier,
		mappedAddress,
		mappedRate,
		dialstring,
		talkTimeBalance,
		minutes,
		callType
		From did where id=$did";
		//echo $strSQL;
		$result = $this->ADb->Execute($strSQL);
		if ($result === false) die("failed"); 
		$newDID = array();
		if(!$result->EOF) 
		{
			$newDID[id]=$result->fields[0];
			$newDID[DID]=$result->fields[1];
			$newDID[vendorid]=$result->fields[2];
			$newDID[typeid]=$result->fields[3];
			$newDID['date']=$result->fields[4];
			$newDID[info]=$result->fields[5];
			$newDID[OrderID]=$result->fields[6];
			$newDID[SetupCost]=$result->fields[7];
			$newDID[MonthlyCost]=$result->fields[8];
			$newDID[IsMonthlyTerminated]=$result->fields[9];
			$newDID[PerMinuteOrignation]=$result->fields[10];
			$newDID[Country]=$result->fields[11];
			$newDID[city]=$result->fields[12];
			$newDID[status]=$result->fields[13];
			$newDID[carrier]=$result->fields[14];
			$newDID[MappedAddress]=$result->fields[15];
			$newDID[mappedRate]=$result->fields[16];
			$newDID[dialstring]=$result->fields[17];
			$newDID[TalkTimeBalance]=$result->fields[18];
			$newDID[minutes]=$result->fields[19];
			$newDID[CallType]=$result->fields[20];
		}
		return $newDID;
	}	
	function getOIDByDID($DID)
	{
		$strSQL	= 	"select BOID from DIDS where DIDNumber = \"$DID\" ";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		if($Result === false || $Result->EOF)
			return false;
		return $Result->fields[0];
	}

	

	function getVendorOIDByDID($DID)
	{
		$strSQL	= 	"select DIDS.OID from DIDS where DIDNumber = \"$DID\"";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		if($Result === false || $Result->EOF)
			return false;
			
			#echo $Result->fields[0];
		return $Result->fields[0];		
	}
	function getVendorIDByDID($DID)
	{
		return $this->getVendorOIDByDID($DID);
	}
	function getRingTo($DID)	
	{
		$strSQL	= 	"select iurl from DIDS  where DIDNumber = \"$DID\" ";	
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		if($Result === false || $Result->EOF)
			return false;
		return $Result->fields[0];
	}
	
	function getTotalPurchasedDIDSCount($OID)	
	{
		$strSQL	= 	"select count(*) from DIDS where BOID=\"$OID\" and Status=2";		
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		if($Result === false || $Result->EOF)
			return 0;
		return $Result->fields[0];
	}
	
	function getTotalOfferedDIDSCount($OID)	
	{
		$strSQL	= 	"select count(*) from DIDS where OID=\"$OID\" ";	
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		if($Result === false || $Result->EOF)
			return 0;
		return $Result->fields[0];
	}
	

	public static function TopTenDIDs(){
		$myADb = new ADb();
		$strSQL	= "SELECT  SQL_CACHE DIDCountries.CountryCode,DIDCountries.Description,	DIDS.OID,DIDS.VendorRating,MIN(OurMonthlyCharges) AS Monthly, CountryID, MAX(offerdate) AS dateadded FROM DIDCountries,DIDS,DIDArea WHERE DIDS.AreaID=DIDArea.id AND DIDArea.CountryID=DIDCountries.id AND STATUS = 0 AND  DIDS.VendorRating>0 AND DIDS.VendorRating<10 AND CheckStatus = 1 GROUP BY DIDCountries.ID ORDER BY dateadded DESC, DIDCountries.CountryCode LIMIT 0,10";

		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	
	public static function TopTenDIDsByDIDNumber($UID,$DIDNumber)
	{
		$myADb = new ADb();
		$strSQL = "select AreaID,OID,NXX,AreaCD from DIDS where DIDNumber 
		           like \"$DIDNumber%\" and status=0 and VendorRating>=1 
		           and VendorRating<=9 and checkstatus=1 limit 0,1";
		$Result = $myADb->ExecuteQuery($strSQL);

	    if ($Result->EOF)
		   {
		
		     echo "Could not find DIDNumber According !";
		     exit;
		
		   }

	    $AreaID = $Result->fields[0];
	    $NXX = $Result->fields[2];
	    $AreaCD = $Result->fields[3];

	    $strSQL = "select CountryCode,StateCode,DIDCountries.Description,
	              DIDArea.Description as a,DIDCountries.ID from 
	              DIDCountries,DIDArea where DIDArea.CountryID=DIDCountries.ID 
                  and DIDArea.ID=\"$AreaID\" ";

        $Result = $myADb->ExecuteQuery($strSQL);
	    $CountryCode = $Result->fields[0];
	    $StateCode = $Result->fields[1];
	    $CountryName = $Result->fields[2];
	    $AreaName = $Result->fields[3];
	    $CountryID = $Result->fields[4];
	
	    $strSQL="select UID from EmailPref where EmailPref.BuyersOnly!=''";
	    $Result = $myADb->ExecuteQuery($strSQL);
	    $UID = $Result->fields[0];

       if ($CountryCode==1)
		    {
	   
	           $strSQL = "select CountryN,City,RCenter,CountryCd,AreaCD,StateName,
	                      substring(DIDNumber,5,3) as NXX,DIDS.OID,VendorRating,
                          min(OurSetupCost) as Setup,min(OurMonthlyCharges) as Monthly
                          ,Count(*) as Quantity,TriggerRate from DIDS, ChannelAdmin where
                          DIDS.AreaID=\"$AreaID\" and status=0 and checkstatus=1 and 
                          VendorRating>=1 and VendorRating<=9  and DIDS.OID!=\"$UID\"
                          group by substring(DIDNumber,5,3),DIDS.OID";

		
		    }
	   else
		    {
			
		       $strSQL = "select CountryN,City,RCenter,CountryCd,AreaCD,StateName,
		                  substring(DIDNumber,5,3) as NXX ,DIDS.OID,VendorRating,
            			  min(OurSetupCost) as Setup,min(OurMonthlyCharges) as Monthly,
            			  Count(*) as Quantity,TriggerRate from DIDS, ChannelAdmin where
                          DIDS.AreaID=\"$AreaID\" and status=0 and checkstatus=1 and 
                          VendorRating>=1 and VendorRating<=9 and DIDS.OID!=\"$UID\"
                          group by DIDS.OID";
		
		    }
		
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	public static function getSearchCountriesList()
	{
		$myADb = new ADb();
		$strSQL="select * from CountriesAvail order by cast(countrycode as unsigned)";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	public static function getDIDRefunds()
	{
		$UID = currentUser();
		$myADb = new ADb();

		$strSQL = "SELECT d.DIDNUmber,DATE_FORMAT(d.iPurchasedDate,'%d-%b-%Y') as iPurchasedDate,SUBSTRING(d.iPurchasedDate,1,10) as PurchasedDate,r.status  FROM DIDS AS d JOIN RefundRequests AS r ON (r.did=d.DIDNUmber AND d.BOID=r.OID) WHERE d.BOID=\"$UID\" AND d.status=2 AND d.CheckStatus=0 ";
		$Result = $myADb->ExecuteQuery($strSQL);
		
		return $Result;
	}

		public static function getDIDTestLog($ffDID)
	{

		$UID = currentUser();
		$myADb = new ADb();

		$strSQL = "select id,did,date_format(testdate,'%d-%b-%Y %h:%i:%s') as TestDateTime,status,testperson,datestamp,failedon,passedon,location,hours,cx,c1,c2,c3,c4,
	   testeranswer,box,MyOID
	   from DIDTestLog where DID=\"$ffDID\" order by TestDate DESC LIMIT 0,5";
	
		$Result = $myADb->ExecuteQuery($strSQL);
		
		return $Result;
	}

			public static function getDIDgroupId($DIDNumber,$BOID)
	{
		$myADb = new ADb();
		$strSQL = "select ChannelBuy.Qty,ChannelBuy.GroupID from DIDS,ChannelBuy where DIDS.IsChannel='1' and DIDS.DIDNumber='$DIDNumber' and DIDS.GroupID=ChannelBuy.GroupID and ChannelBuy.OID='$BOID'  group by DIDS.GroupID";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
		
	}

		public static function getDIDInfo($ffDID)
		{
		$myADb = new ADb();
		$pOID = currentUser();
		$strSQL = "SELECT ID,DIDnumber,AreaID,Status,SetupCost,MonthlyCharges,PerMinuteCharges,OID,OfferDate,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,CheckStatus,VendorRating,Type,FreeMin,iCallCard,iCodec,INetwork,IChannel,CallerID,iT38,BOID,IPurchasedDate,iURL,iFlag,iSoldCC,BoxName,g729,g723,g711,SpSetup,SpMonthly,SpPerMin,iTrigger,GroupID,IsChannel,NXX,NPA,City,StateName,RCenter,CountryCD,AreaCD,CountryN,myCountryID,DIDRating,CallerIDName,NeedDocs,NeedDocsMsg,SuspendDID,NumberHide,POSTURL,PostURLVar,SMSEnable,Voice,CallBack,TriggerMin,TriggerRate,OnTrigger from DIDS d where DIDNumber=\"$ffDID\"";
		
			$Result = $myADb->ExecuteQuery($strSQL);

			if($Result->EOF){	
				
				$NotFound=1;		
				$strSQL = "select OID,RemovalDate from RemovedDIDS where DIDNumber=\"$ffDID\" ";
				$Result = $myADb->ExecuteQuery($strSQL);
			}	

			return $Result;
		}

	public static function DIDRating($ffDID)
	{

	$myADb = new ADb();
	$pOID = currentUser();

	$strSQL = "select Rating from DIDRating where OID=\"$pOID\" and DID=\"$ffDID\"";
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;
	}


	public static function getRefundsRequest($pDID)
	{
		$UID = currentUser();
		$myADb = new ADb();
		$strSQL = "SELECT status FROM RefundRequests WHERE did=\"$pDID\" AND OID=\"$UID\"";
		$Result = $myADb->ExecuteQuery($strSQL);		
		return $Result->fields[0];
	}

		public static function DIDMinutesInfo($pDID)
	{
		$UID = currentUser();
		$myADb = new ADb();
		$strSQL = "select MinSpent,date_format(expiry,'%d-%b-%Y') as expiry,Triggers,TriggersUsed from MinutesInfo where DID=\"$pDID\" and OID=\"$UID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		$data = array(
			'MinSpent' => $Result->fields[0],
			'expiry' => $Result->fields[1],
			'Triggers' => $Result->fields[2],
			'TriggersUsed' => $Result->fields[3]
			);
		return $data;
	}


	public static function MyPendingPurchasesDIDS()
	{
		$myADb = new ADb();
		$UID=currentUser();

		$strSQL="select CusDocs.ID,CusDocs.OID,CusDocs.DID AS DIDNumber,CusDocs.Type, date_format(CusDocs.Date,'%d-%b-%Y')as Date
            ,CusDocs.KeyID,CusDocs.AreaID,CusDocs.Status,DIDS.Status as DST,DIDS.ID as DIDID, OurMonthlyCharges,OurSetupCost 
            from CusDocs,DIDS where DIDS.Status!=2 and CusDocs.OID='$UID' and DIDS.DIDNumber=CusDocs.DID order by CusDocs.Date desc limit 0,50";
            
		// $strSQL="SELECT CusDocs.ID,CusDocs.OID,CusDocs.DID AS DIDNumber,CusDocs.Type,
  //                DATE_FORMAT(CusDocs.Date,'%d-%b-%Y') as Date,CusDocs.KeyID,CusDocs.AreaID,CusDocs.Status,
  //                DIDS.Status AS DST,DIDS.ID AS DIDID, OurMonthlyCharges,OurSetupCost ,
  //                DIDCountries.Description,DIDArea.Description AS AreaName FROM CusDocs,DIDS,DIDCountries,
  //                DIDArea WHERE DIDArea.ID=CusDocs.AreaID
  //                AND DIDArea.COuntryID=DIDCountries.ID 
  //                AND DIDS.Status!=2 AND CusDocs.OID=$UID
  //                AND  DIDS.DIDNumber=CusDocs.DID ORDER BY CusDocs.Date DESC LIMIT 0,50";
  //                echo $strSQL;
  //                exit;
	 	$Result = $myADb->ExecuteQuery($strSQL);
	 	return $Result;

	}


	public static function MyPendingPurchasesDIDSCount()
	{
		$myADb = new ADb();
		$UID=currentUser();
		$strSQL="SELECT COUNT(CusDocs.ID) AS totalpendings FROM CusDocs,DIDS,DIDCountries,
                 DIDArea WHERE DIDArea.ID=CusDocs.AreaID
                 AND DIDArea.COuntryID=DIDCountries.ID 
                 AND DIDS.Status!=2 AND CusDocs.OID=$UID
                 AND  DIDS.DIDNumber=CusDocs.DID ORDER BY CusDocs.Date DESC LIMIT 0,1";
	 	$Result = $myADb->ExecuteQuery($strSQL);
	 	$Hash=array();
	 	$Hash['TotalPendingPurchases']=$Result->fields[0];
	 	return $Hash;

	}
	

 

		public static function boxName($pDID)
	{
		$myADb = new ADb();
		
		$strSQL = "SELECT SystemBox FROM cdrs WHERE callednum=\"$pDID\" ORDER BY callstart DESC LIMIT 0,1";
	    $Result	= $myADb->ExecuteQuery($strSQL);
	
		$Domain = $Result->fields['SystemBox'];
		
		$strSQL = "SELECT IP FROM DIDxServers where ServerName=\"$Domain\" ";
		
		$Result	= $myADb->ExecuteQuery($strSQL);
		
		$IP = $Result->fields['IP'];
		
		return "$Domain ($IP)";
	}

	 	public static function getCountVendorDID($pVID)
	{
		$myADb = new ADb();
		$strSQL = "select count(*) from DIDS where OID=\"$pVID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}
	

	 	public static function GetAlternateRingTo($pDID,$pBOID)
	{
		$myADb = new ADb();
		$strSQL = "select CondType,RingTo,Flag from AlterRingTo where DID=\"$pDID\" and OID=\"$pBOID\"  ";
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$data = array(
		 'aType' => $Result->fields[0],
		 'aRingTo' => $Result->fields[1],
		 'aFlag' =>$Result->fields[2]
		);
		return $data;
	}

		public static function GetAlternateRingTo2($pDID,$pBOID)
	{
		$myADb = new ADb();
		$strSQL = "select CondType,RingTo,Flag from AlterRingTo where DID=\"$pDID\" and OID=\"$pBOID\"  ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
		
	}
	 	public static function RequestedDIDSList()
	{
		$myADb = new ADb();
		$UID = currentUser();
		$nIndex = 0;
		$strSQL="select DIDCountries.CountryCode,DIDCountries.Description,DIDArea.StateCode,DIDArea.Description as AreaName,OID,RequestDate,
		Qty,RequestedDIDSNew.ID
		from RequestedDIDSNew,DIDArea,DIDCountries where DIDArea.ID=RequestedDIDSNew.AreaID 
		and DIDCountries.ID=DIDArea.CountryID
		order by CountryCode ";
		// echo $strSQL;
		// exit;
		// $start
		$Result = $myADb->ExecuteQuery($strSQL);	
		return $Result;

	}
	

	 	public static function searchRequestedDIDS($fromDate,$toDate)
	{
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="select DIDCountries.CountryCode,DIDCountries.Description,DIDArea.StateCode,DIDArea.Description as AreaName,OID,RequestDate,
        Qty,RequestedDIDSNew.ID
        from RequestedDIDSNew,DIDArea,DIDCountries where DIDArea.ID=RequestedDIDSNew.AreaID 
        and DIDCountries.ID=DIDArea.CountryID and RequestedDIDSNew.RequestDate between '$fromDate' and '$toDate'
        order by CountryCode limit 0,100";
	#	echo $strSQL;$start
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;

	}
	 	public static function searchDIDS_byUID()
	{
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="select DIDCountries.CountryCode,DIDCountries.Description,DIDArea.StateCode,DIDArea.Description as AreaName,OID,RequestDate,
		Qty,RequestedDIDSNew.ID
		from RequestedDIDSNew,DIDArea,DIDCountries where DIDArea.ID=RequestedDIDSNew.AreaID 
		and DIDCountries.ID=DIDArea.CountryID and OID='$UID'
		  order by CountryCode";
	#	echo $strSQL;$start
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	 	public static function delDIDS($ID)
	{
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="delete from RequestedDIDSNew where ID='$ID' and OID='$UID'";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}	

	 	public static function RequestedDIDSNew($ffArea,$ffNXX)
	{
		$myADb = new ADb();
		$UID = currentUser();		
		$strSQL="SELECT DIDCountries.CountryCode,DIDArea.StateCode,DIDArea.Id FROM DIDS,DIDArea,DIDCountries WHERE  DIDS.AreaID=DIDArea.id AND (SELECT RequestedDIDSNew.`DID` FROM RequestedDIDSNew WHERE OID=\"$UID\" AND AreaID=\"$ffArea\" AND NXX=\"$ffNXX\") AND DIDArea.CountryID=DIDCountries.id  AND VendorRating>=1 AND VendorRating<=9 AND DIDS.AreaID=\"$ffArea\" AND STATUS = 0 AND CheckStatus = 1  AND SUBSTRING(DIDNumber,5,3)=\"$ffNXX\" GROUP BY DIDS.AreaID ORDER BY DIDNumber LIMIT 0,1";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	 	public static function insertRequestedDID($ffArea,$ffNXX,$ffPrice,$ffQty,$ffChannel,$ffComments)
	{
		$myADb = new ADb();
		$UID = currentUser();		
		$strSQL="insert into RequestedDIDSNew
							(AreaID,OID,RequestDate,NXX,Status,Monthlyprice,Qty,Channel,Notes)
							values
							(\"$ffArea\",\"$UID\",sysdate(),\"$ffNXX\",1,$ffPrice,$ffQty,\"$ffChannel\",\"$ffComments\")";
							
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}	

	 	public static function checkRequestedDID($ffArea,$ffNXX)
	{
		$myADb = new ADb();
		$UID = currentUser();		
		$strSQL="select * from RequestedDIDSNew where OID=\"$UID\" and AreaID=\"$ffArea\" and NXX=\"$ffNXX\"";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	public static function Getresultcountry($ffArea){
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL="select DIDCountries.CountryCode,DIDArea.StateCode,
				 DIDCountries.Description as a,DIDArea.Description
				from DIDArea,DIDCountries where 
				 DIDCountries.id=DIDArea.CountryID and DIDArea.Id=\"$ffArea\" ";
		$ResultCountry = $myADb->ExecuteQuery($strSQL);
					
		$NPA = $ResultCountry->fields[1];
		return $NPA;

	}
	 public static function GetNXX($ffNXX,$NPA){
	 	$myADb = new ADb();
	 	$strSQL="select NXX,RateCenter from USAreas where NXX=\"$ffNXX\" and NPA=\"$NPA\" ";

		$ResultNXX = $myADb->ExecuteQuery($strSQL);
		return $ResultNXX;
	 }

	 	public static function checkCountry($Country,$Area,$WhereVendor,$Qty)
	{
		
		$myADb = new ADb();
		$UID = currentUser();		
		$strSQL="select DIDNumber,OurSetupCost,OurMonthlyCharges,AreaID,DIDS.OID from DIDS,DIDCountries,DIDArea	where DIDArea.ID=DIDS.AreaID and DIDArea.CountryID = DIDCountries.ID and DIDS.CheckStatus=1 and  DIDArea.CountryID = \"$Country\" and DIDArea.ID=\"$Area\" $WhereVendor and VendorRating>=1 and VendorRating<=9 and DIDS.Status=0 limit 0,$Qty";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	 	public static function checkCountry_Area($Country,$Area)
	{
		
		$myADb = new ADb();
		$UID = currentUser();		
		$strSQL="select DIDCountries.CountryCode,DIDCountries.Description,DIDArea.StateCode,DIDArea.Description as A from DIDArea,DIDCountries
					where DIDCountries.ID=\"$Country\" and DIDArea.Id=\"$Area\" and DIDArea.CountryID=DIDCountries.Id";
		$Result = $myADb->ExecuteQuery($strSQL);
		$res = array(
		'CountryName' => $Result->fields[0]." - ".$Result->fields[1],
		'AreaName'   => $Result->fields[2]." - ".$Result->fields[3]
		);
		return $res;
	}	

	public static function getccompany()
	{
		
		$myADb = new ADb();
		$pUID = currentUser();
		$strSQL	= 	"select ccompany from customer where UID=\"$pUID\"";
		$Result = $myADb->ExecuteQuery($strSQL);
	
		return $Result->fields[0];
	}
		


	public static function SearchAllDIDSFromStock($T38Clause,$WHEREClause,$T38Clause,$ChannelsClause,$FreeClause,$CallerIDClause,$CallingCardNameClause,$CallingIDNameClause,$OrderBy,$LIKEDID,$LIKEDID2,$VRatingTo, $VRatingFrom,$Qty,$SMSClause,$DocsNeed)
   {
   	$myADb =new ADb();
   	$UID= currentUser();
   	$strSQL = "select DIDNumber,CountryN as	Country,
							City,OurSetupCost,OurMonthlyCharges,
							Status,DIDS.OID,DIDS.CheckStatus,DIDS.Id as b,DIDS.VendorRating, iChannel, OurPerMinuteCharges
				 			,FreeMin, NumberHide, CountryCD,AreaCD,ID, NeedDocs, NeedDocsMsg
							from DIDS where  status=0 and DIDS.VendorRating>=$VRatingFrom  and DIDS.VendorRating<=$VRatingTo
							 and CheckStatus = 1 $LIKEDID $LIKEDID2 $WHEREClause $T38Clause $ChannelsClause $FreeClause $CallerIDClause $CallingCardNameClause
							 $CallingIDNameClause $SMSClause $DocsNeed $OrderBy limit 0,$Qty";
  
												
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;


   }	


   function GetSummary($pUID,$TotalDIDS){
	
	
	$myADb = new ADb();
	
	$html="";
	                                          
	global $TotalDIDS;

	$strSQL  = "select concat(substring(DIDNumber,1,1),'-',substring(DIDNumber,2,3),'-',
	            substring(DIDNumber,5,3),'-',substring(DIDNumber,8,1)),
	            count(DIDNumber),Min(OurMonthlyCharges),Max(OurMonthlyCharges),
                Min(MonthlyCharges),Max(MonthlyCharges),date_format(iPurchasedDate,'%d-%b-%Y')
                from DIDS where BOID=\"$pUID\"  and status=2 and DIDNumber like '1%' 
                group by substring(DIDNumber,1,8) order by cast(DIDNumber as unsigned) ";

	$Result = $myADb->ExecuteQuery($strSQL);
	
	$Sno=0;
	$bg="grey";

	while(!$Result->EOF){
		
        if($bg=="grey")
        {
            $bg="";
        }
        elseif($bg=="")
        {
            $bg="grey";
            
        }
		$Sno++;
		$Series = $Result->fields[0];
		$Count = $Result->fields[1];
		$MinSel = $Result->fields[2];
		$MaxSel = $Result->fields[3];
		$MinPur = $Result->fields[4];
		$MaxPur = $Result->fields[5];
		$PurDate = $Result->fields[6];
		
		// $TotalDIDS = $TotalDIDS + $Count; 
		
		$Prefix = str_replace("-","",$Series);
		
		$html .= "<tr class=$bg>
                <td align=\"center\">$Sno</td>
                <td align=\"center\">
                <a href=BuyerDIDSReportBySeries2?mdid=$Prefix>$Series</a></td> 
                <td align=\"center\">$PurDate</td>
                <td align=\"center\">$Count</td>
                <td align=\"center\">$MinSel</td>
                <td align=\"center\">$MaxSel</td>
              </tr>
";
	
		$Result->MoveNext();
		
		
	}
	
	
	$strSQL = "select concat(DIDCountries.CountryCode,'-',DIDArea.StateCode),count(DIDNumber),
	           Min(OurMonthlyCharges),Max(OurMonthlyCharges),Min(MonthlyCharges),
	           Max(MonthlyCharges),date_format(iPurchasedDate,'%d-%b-%Y') from DIDS,
	           DIDCountries,DIDArea where BOID=\"$pUID\"  and status=2 and DIDNumber 
	           not like '1%' and DIDArea.ID = DIDS.AreaID and DIDCountries.ID = DIDArea.CountryID
               group by substring(DIDNumber,1,8) order by cast(DIDNumber as unsigned)";



	$Result = $myADb->ExecuteQuery($strSQL);
	if($Result->EOF)
    {
        $html .= "<tr>
                <td align=\"center\" colspan=\"6\">Records not found</td></tr>";
        
    }
	while(!$Result->EOF){
		
        if($bg=="grey")
        {
            $bg="";
        }
        elseif($bg=="")
        {
            $bg="grey";
        }
		$Sno++;
		$Series = $Result->fields[0];
		$Count = $Result->fields[1];
		$MinSel = $Result->fields[2];
		$MaxSel = $Result->fields[3];
		$MinPur = $Result->fields[4];
		$MaxPur = $Result->fields[5];
		$PurDate = $Result->fields[6];
		
		// $TotalDIDS = $TotalDIDS + $Count;
		
		$Prefix = str_replace("-","",$Series);
		
		
		$html .= "<tr class=$bg>
                <td align=\"center\">$Sno</td>
              <td align=\"center\"><a href=BuyerDIDSReportBySeries2?mdid=$Prefix>$Series</a></td> 
              <td align=\"center\">$PurDate</td>
              <td align=\"center\">$Count</td>
              <td align=\"center\">$MinSel</td>
              <td align=\"center\">$MaxSel</td>                
              </tr>
";
		
		
		$Result->MoveNext();

	}
	return $html;
}


// function getLastPassFailedDate($pStatus,$pDID) {

// 	$myADb=new ADb();
	
// 	$strSQL=" select date_format(TestDate,'%d-%b-%Y') as Lastdate from DIDTestLog where did=\"$pDID\"  and status=\"$pStatus\" order by TestDate desc  limit 0,1";	
	
// 	$Result	= $myADb->ExecuteQuery($strSQL);
	
// 	if($pStatus==1) {
// 	return $Result->fields[0];
// 		}else{
// 			return $Result->fields[0];
// 		}
// }

// public function getLastStatus($pDID,$pStatus){
	
// 	$myADb=new ADb();
	
// 	$strSQL=" select count(*) from DIDTestLog where did=\"$pDID\"  ";
// 	#echo "<br>\$strSQL: $strSQL";
// 	$Result	= $myADb->ExecuteQuery($strSQL);
// 	$CountTest = $Result->fields[0];
	
// 	$strSQL=" select max(testdate) from DIDTestLog where did=\"$pDID\"  ";
// 	#echo "<br>\$strSQL: $strSQL";
// 	$Result	= $myADb->ExecuteQuery($strSQL);
// 	$TestMax = $Result->fields[0];
	
		
// 	$strSQL=" select max(id) from DIDTestLog where did=\"$pDID\"  and Status=\"$pStatus\"";
// 	#echo "<br>\$strSQL: $strSQL";
// 	$Result	= $myADb->ExecuteQuery($strSQL);
	
// 	$ID = $Result->fields[0];
	
	
// 	$strSQL="select DID,TestDate,(to_days(curdate())-to_days(TestDate))*24 as Hours,
// 						hour(now())-hour(testDate),now()
// 					 from DIDTestLog where id=\"$ID\"  and DID=\"$pDID\" and Status=\"$pStatus\" ";
// #	echo "<br>\$strSQL: $strSQL";	
// 	$Result	= $myADb->ExecuteQuery($strSQL);
	
// 		$TestDate = $TestMax ;
// 		$Hours = 	$Result->fields[2]+ $Result->fields[3];
		

	
// 	if($Hours == '' || $Hours<=0){
// 		$Hash[Hour]="-";
// 	}else{
// 		$Hash[Hour]= "$Hours Hrs";
// 	}


// 	$Hash[Date]=$TestDate;
// 	$Hash[CountTest]=$CountTest;

// 	return $Hash;
	
// }


function CheckDownTime($pDID,$pUID,$pDate) {
	
	 $myADb=new ADb();
	
	$strSQL = "select max(id) from DIDTestLog where 
	did=\"$pDID\" and datestamp>=\"$pDate\" and status =1 and MyOID='$pUID' group by DID;";
	#print "<br>\$strSQL: $strSQL";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	if($Result->EOF){
		
		$strSQL = "select MIN(id) from DIDTestLog where 
		did=\"$pDID\" and datestamp>=\"$pDate\" and status =0 group by DID;";
		#print "<br>\$strSQL: $strSQL";
		$Result = $myADb->ExecuteQuery($strSQL);
		
			$ID = $Result->fields[0];
			
			$strSQL = "select status,datestamp from DIDTestLog where ID=\"$ID\" ";
			#print "<br>\$strSQL: $strSQL";
			$Result = $myADb->ExecuteQuery($strSQL);
			
			$LastTest = $Result->fields[1];
			$LastStatus = $Result->fields[0];
			
			if($LastStatus == '0'){
				
				$strSQL = "select (to_days(now())-to_days(\"$LastTest\"))* 24";
				#print "<br>\$strSQL: $strSQL";
				$Result = $myADb->ExecuteQuery($strSQL);
			}
			
			$TotalHours = $Result->fields[0];
			
			
			return $TotalHours;
		
	}
	
	$ID = $Result->fields[0];
	
	
	$strSQL = "select id from DIDTestLog where id>\"$ID\" and did='$pDID' and status=0 order by datestamp limit 0,1 ";
	#print "<br>\$strSQL: $strSQL";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	
	$ID = $Result->fields[0];
	
	$strSQL = "select status,datestamp from DIDTestLog where ID=\"$ID\" ";
	#print "<br>\$strSQL: $strSQL";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	$LastTest = substr($Result->fields[1],0,10);
	$LastStatus = $Result->fields[0];
	
	if($LastStatus == '0'){
		
		$strSQL = "select (to_days(now())-to_days(\"$LastTest\"))* 24";
		#print "<br>\$strSQL: $strSQL";
		$Result = $myADb->ExecuteQuery($strSQL);
	}
	
	$TotalHours = $Result->fields[0];
	
	
	return $TotalHours;
	
	
	
}

function GetDetailSummary($pUID,$mm,$TotalDIDS){
    
 #  echo "OID : ".$pUID." DIDNumber".$mm ;
    $myADb = new ADb();
    
    $html="";
    
    global $TotalDIDS;

    $strSQL  = "select DIDNumber,date_format(iPurchasedDate,'%d-%b-%Y'),min(OurMonthlyCharges),Max(OurMonthlyCharges),count(DIDNumber)   from DIDS where DIDNumber Like \"$mm%\" and status =2 and   BOID=\"$pUID\"  group by DIDNumber ";
    
    $Result = $myADb->ExecuteQuery($strSQL);
        if($Result->EOF)
    {
       $html="<tr>
                <td colspan=\"3\">Records Not found</td></tr>" ;
    } 
    $Sno=0;
    
    while(!$Result->EOF){
        
        $Sno++;
        $Series = $Result->fields[0];
        $PurDate = $Result->fields[1];
        $Count = $Result->fields[4];
        $MinSel = $Result->fields[2];
        $MaxSel = $Result->fields[3];
//        $MinPur = $Result->fields[4];
//        $MaxPur = $Result->fields[5];
        
        
        $TotalDIDS = $TotalDIDS + $Count; 
        
        $Prefix = str_replace("-","",$Series);
        
        $html .= "<tr>
                <td align=\"center\">$Sno</td>
                <td align=\"center\">$Series</td> 
                <td align=center>$PurDate</td>
                <td align=\"center\">$Count</td>
                <td align=\"center\">$MinSel</td>
                <td align=\"center\">$MaxSel</td>
              </tr>
";
        
        $Result->MoveNext();
        
        
    }
      
    return $html;
    
}		
					
	public static function getTalkTimeList($year)
   {
   		$myADb =new ADb();
   		$UID= currentUser();
	   	$strSQL = "SELECT DIDNumber,SetupCost,PerMinuteCharges,iPurchasedDate,FreeMin,MinSpent FROM DIDS, MinutesInfo WHERE MinutesInfo.MinSpent>DIDS.FreeMin AND DIDS.boid='$UID' AND MinutesInfo.DID=DIDS.DIDNumber AND DIDS.iPurchasedDate LIKE '%$year%' ";
		$Result	= $myADb->ExecuteQuery($strSQL);		
		return $Result;
	}

	public function GetMyDIDS($pDID){
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL = "select DIDNumber from DIDS where BOID=\"$UID\" and Status=2 and isChannel=0 order by DIDNumber";
		$Result	= $myADb->ExecuteQuery($strSQL);
		$htmlDID="";
		while(!$Result->EOF){
				
			$DID = $Result->fields[0];
			if($pDID == $DID){
				$htmlDID .= "<option  value=\"$DID\" selected >$DID</option>";
			}else{
				$htmlDID .= "<option  value=\"$DID\" >$DID</option>";
			}
			$Result->MoveNext();       
		}
		$htmlDID .= "<option  value=\"All\" selected >All</option>";
		return $htmlDID;	
	}

	public function GetourPerMinuteCharges($pDID){
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL = "select ourPerMinuteCharges from DIDS where DIDNumber=\"$pDID\" ";
		$ResultDIDS	= $myADb->ExecuteQuery($strSQL);
		return $ResultDIDS->fields[0];	
	}

	public function GetTalkTimeCut($pYear,$WhereDID,$MonthClause){
		$UID = currentUser();
		$strSQL = "select (sum(talktimecut)/100) as talktimecuts from cdrs,DIDS where billcost>0  and cdrs.OID=\"$UID\" and cdrs.OID=DIDS.BOID and DIDS.status=2 and Year=\"$pYear\" $MonthClause  and cdrs.callednum=DIDS.DIDNumber and disposition='ANSWER' $WhereDID";
		$ResultDIDS	= $myADb->ExecuteQuery($strSQL); 
		if($Result->EOF){
            $strSQL = "select (sum(talktimecut/100) as talktimecuts,count(*) from cdrs2013,DIDS where billcost>0  and cdrs2008.OID=\"$UID\" and cdrs2008.OID=DIDS.BOID and DIDS.status=2
                    and  Year=\"$pYear\" $MonthClause   and cdrs2008.callednum=DIDS.DIDNumber and disposition='ANSWER'  $WhereDID";
            #print "<br>\$strSQL: $strSQL"; 
            $Result = $myADb->ExecuteQuery($strSQL);
        }
        return $Result->fields[0];
	}

    public function GetcdrsBillseconds($pYear,$WhereDID,$MonthClause,$TotalThisMonthTalkTime,$MyTotalDur,$DIDPerMin,$OrigMonth){
    	$htmlTable = '';
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL = "select sum(talktimecut) from cdrs,DIDS where billcost>0  and cdrs.OID=\"$UID\" and cdrs.OID=DIDS.BOID and DIDS.status=2 and Year=\"$pYear\" $MonthClause  and cdrs.callednum=DIDS.DIDNumber and disposition='ANSWER' $WhereDID";
        $Result = $myADb->ExecuteQuery($strSQL);
        if($Result->EOF){
            $strSQL = "select sum(talktimecut),count(*) from cdrs2013,DIDS where billcost>0  and cdrs2008.OID=\"$UID\" and cdrs2008.OID=DIDS.BOID and DIDS.status=2
                    and  Year=\"$pYear\" $MonthClause   and cdrs2008.callednum=DIDS.DIDNumber and disposition='ANSWER'  $WhereDID";
            // print "<br>\$strSQL: $strSQL"; 
            $Result = $myADb->ExecuteQuery($strSQL);
        }
        $TotalThisMonthTalkTime = sprintf("%2.2f",$Result->fields[0]/100);

        $strSQL = "select date_format(callstart,'%d-%b-%Y'),billseconds,billcost,
                       talktimecut,minutestotalused,callednum from cdrs,DIDS where  DIDS.status=2 $WhereDID and cdrs.OID=\"$UID\" 
                             and  Year=\"$pYear\" $MonthClause and cdrs.OID=DIDS.BOID   
                         and cdrs.callednum=DIDS.DIDNumber   and disposition='ANSWER'
                             order by callstart desc";
          // print "<br>\$strSQL: $strSQL";
        $Result = $myADb->ExecuteQuery($strSQL);
        $strSQL = " select count(*) ,sum(billseconds)from cdrs,DIDS where  DIDS.status=2 $WhereDID and cdrs.OID=\"$UID\" 
                                    and  Year=\"$pYear\" $MonthClause and cdrs.OID=DIDS.BOID   
                                and cdrs.callednum=DIDS.DIDNumber   and disposition='ANSWER'
                                ";
                    
          // print "<br>\$strSQL: $strSQL";
        $ResultCount = $myADb->ExecuteQuery($strSQL);
        $MyCount = $ResultCount->fields[0];
        $MyTotalDur = $ResultCount->fields[1];
        if($Result->EOF){

            $strSQL = "select date_format(callstart,'%d-%b-%Y'),billseconds,billcost,talktimecut,minutestotalused,callednum from cdrs2008,DIDS where DIDS.status=2 $WhereDID and cdrs2008.OID=\"$UID\" and  Year=\"$pYear\" $MonthClause and cdrs2008.OID=DIDS.BOID and cdrs2008.callednum=DIDS.DIDNumber  and disposition='ANSWER' order by callstart";
                // print "<br>\$strSQL: $strSQL";
         $Result = $myADb->ExecuteQuery($strSQL);

         $strSQL = "select count(*),sum(billseconds) from cdrs2013,DIDS where DIDS.status=2 $WhereDID and cdrs2008.OID=\"$UID\" 
                            and  Year=\"$pYear\" $MonthClause and cdrs2008.OID=DIDS.BOID 
                            and cdrs2008.callednum=DIDS.DIDNumber  and disposition='ANSWER'
                            ";    
                              // print "<br>\$strSQL: $strSQL";
        $ResultCount = $myADb->ExecuteQuery($strSQL);    
        $htmlTable .= "<tr>     
                        <td align=center colspan=7><DIV align=center>result(s) not found</td>
                        </tr>";  
        }

        
        $MyTotalDur = sprintf("%2d",$MyTotalDur/60);
        $MyTotalDur = ceil($TotalThisMonthTalkTime/$DIDPerMin);
        $ThisPageTotalMin = 0;
        $ThisPageTotalCost = 0;
        while(!$Result->EOF){
            $Date = $Result->fields[0];
            $Duration = $Result->fields[1];
            $Cost = $Result->fields[2];
            $TotalCut = $Result->fields[3];
            $DIDNumber = $Result->fields[5];
            $billMin=sprintf ("%2.0f",sprintf("%d",$Duration/60));
            $billSec=sprintf ("%2.0f",$Duration%60);
            $ChargeableMin = ceil($Duration/60);
            $TotoalMin=$billMin;    
            if($billSec>0)
            {
                $TotoalMin++;
            }
            $ThisPageTotalMin = $ThisPageTotalMin + $ChargeableMin;
            $TotalCost = sprintf("%2.2f",$Cost * $ChargeableMin);
            $ThisPageTotalCost = $ThisPageTotalCost + $TotalCost;
            $htmlTable .= "<tr class='$bgcolor'>
                <td align=center><DIV align=center>$DIDNumber</td>
                <td align=center><DIV align=center>$Date</td>
                <td align=center><DIV align=center>$billMin:$billSec</td>           
                <td align=right><DIV align=center>$ChargeableMin</td>
                <td align=right><DIV align=center>$Cost</td>
                <td align=right><DIV align=right>$TotalCost</td>
                <td align=right><DIV align=right>$ThisPageTotalCost</td>
                </tr> ";
            $Result->MoveNext();
        }
        
        $htmlTable .= "<tr>
            <td align=right colspan=2><b>Total Duration This Page</b> </td>
            <td align=center><b>$ThisPageTotalMin min(s)</b></td>
            <td align=right><b>Total Cost This Page</b></td>
            <td align=right><b>\$ $ThisPageTotalCost</b></td>
            <td colspan=\"6\" align=right><b>&nbsp;</b></td>
            </tr>";
                    return $htmlTable;
	}

	function GetTalkTimeUsageLogsAll2($pMonth,$pYear) {
    $myADb = new ADb();
	$htmlTable = '';
    $UID = currentUser();

    // global $myADb,$UID,$TotalThisMonthTalkTime,$PageLink,$OrigPage,$MyTotalDur,$DIDPerMin;
    $strSQL = "select DIDNumber from DIDS where BOID=\"$UID\" and status='2'";
    $DIDResult = $myADb->ExecuteQuery($strSQL);
	    while(!$DIDResult->EOF)
	    {
	    	
	     	$DIDNumber = $DIDResult->fields[0];
	        $PPCSQL = "select ourPerMinuteCharges from DIDS where DIDNumber=\"$DIDNumber\"";
	        $PPCResult = $myADb->ExecuteQuery($PPCSQL);
	     	$PerMinuteCharges = $PPCResult->fields[0];
	    	$Date = $pMonth."-".$pYear;
	        
	        $str = "select SUM(billseconds),SUM(billcost) from cdrs,DIDS where DIDS.status=2 AND cdrs.`callednum`=\"$DIDNumber\" and cdrs.OID=\"$UID\" and  Year=\"$pYear\" AND MONTH=\"$pMonth\" and cdrs.OID=DIDS.BOID and cdrs.callednum=DIDS.DIDNumber  and disposition='ANSWER' order by callstart";
	       $str."<br/>";                              
	       $ResultStr = $myADb->ExecuteQuery($str);
	       $Duration = $ResultStr->fields[0];
	       $TotalDuration = ceil($Duration/60);
	       $TotalCost = $ResultStr->fields[1];
	       $TotalCost = $TotalDuration * $PerMinuteCharges;
	        $htmlTable .= "<tr>
	            <td align=center><DIV align=center>$DIDNumber</td>
	            <td align=center><DIV align=center>$Date</td>
	            <td align=center><DIV align=center>$TotalDuration</td>         
	            <td align=right><DIV align=center>$PerMinuteCharges</td>
	            <td align=right><DIV align=center>$TotalCost</td>
	            </tr> ";
	        $DIDResult->MoveNext();
	    }
    return $htmlTable;
}

public function GetCSVDownloadLink(){
		$UID = currentUser();
		$myADb = new ADb();
		$strSQL = "select count(*) from RequestBillCSV where OID=\"$UID\"  and Type=10 and Status=1";
		$Result	= $myADb->ExecuteQuery($strSQL);
		if($Result->fields[0]>0)
			$link = $Result->fields[0];	
		else
			$link = '';
			return $link;
	}

	public function insertPaymentintoDB($hash){
		$UID = currentUser();
		$myADb = new ADb();
		$TransactionID = $hash['TransactionID'];
		$GrossAmount = $hash['GrossAmount'];
		$UID = $hash['UID'];
		$PayerEmail = $hash['PayerEmail'];
		$CustomerName = $hash['CustomerName'];
		$City = $hash['City'];
		$PostalCod = $hash['PostalCod'];
		$Street = $hash['Street'];
		$strSQL = "INSERT INTO didx.OnlinePayments 
                                        (MBID, 
                                        amount, 
                                        OID, 
                                        DATE, 
                                        contact, 
                                        company, 
                                        gmail, 
                                        phone, 
                                        gfname, 
                                        glname, 
                                        city, 
                                        zip, 
                                        address1, 
                                        address2, 
                                        IsCharged, 
                                        TransactionID, 
                                        PayType
                                        )value(\"$TransactionID\", \"$GrossAmount\", \"$UID\",now(),\"\",\"\",\"$PayerEmail\",\"\",\"$CustomerName\",\"\",\"$City\",
                                        \"$PostalCod\", \"$Street\",\"\",\"1\",\"$MyTransactionID\",\"1\");
                                        )";
		$Result	= $myADb->ExecuteQuery($strSQL);
			return $Result;
	}

public function GetSPOfferCodeGroupID($ffDID){
		$UID = currentUser();
		$myADb = new ADb();
		$strSQL = "select DIDnumber,SPOffer,SPOfferCode,GroupID from DIDS where DIDS.id=\"$ffDID\" and status=2 and BOID=\"$UID\"";
		$ResultID	= $myADb->ExecuteQuery($strSQL);
		$data = array();
		$data['DIDNumber'] = $ResultID->fields[0];
		$data['SPOffer'] = $ResultID->fields[1];
		$data['SPOfferCode'] = $ResultID->fields[2];
		$data['GroupID'] = $ResultID->fields[3];
		return $data;
	}

	public function checkDIDID($ffDIDID){
		$UID = currentUser();
		$myADb = new ADb();
		$strSQL = "select DIDnumber,OID,Type,substring(DIDS.iPurchasedDate,9,2) as CycleDay,substring(curdate(),6,2), 
				 substring(date_add(curdate(),INTERVAL 1 month),6,2),
				substring(curdate(),3,2),substring(curdate(),1,4),SPOffer,SpOfferCode from DIDS where DIDS.id=\"$ffDIDID\" and status=2 and BOID=\"$UID\"";
		$ResultID	= $myADb->ExecuteQuery($strSQL);
		$data = array();
		$data['SellerID'] = $ResultID->fields[1];
		$data['DIDNumber'] = $ResultID->fields[0];
		$data['DIDType'] = $ResultID->fields[2];	
		$data['CycleDay'] = $ResultID->fields[3];		
		$data['ThisMonth'] = $ResultID->fields[4];		
		$data['NextMonth'] = $ResultID->fields[5];		
		$data['ThisYearDigit'] = $ResultID->fields[6];		
		$data['ThisYear'] = $ResultID->fields[7];		
		$data['SpOffer'] = $ResultID->fields[8];		
		$data['SpOfferCode'] = $ResultID->fields[9];
		return $data;
	}



	public function getPurchaseDate($ffDIDID){
		$UID = currentUser();
		$myADb = new ADb();
		$strSQL = "select DIDS.ipurchaseddate,DIDS.BOID  from DIDS where   
						DATE_ADD(ipurchaseddate, INTERVAL 36 HOUR) > curdate() and BOID=\"$UID\"
						and ID=\"$ffDIDID\"";
		$ResultID	= $myADb->ExecuteQuery($strSQL);
		$data = array();
		$data['ipurchaseddate'] = $ResultID->fields[0];
		$data['BOID'] = $ResultID->fields[1];
		return $data;
	}

	public function insertPurchasedateToFreeDID($UID,$SellerID,$DIDNumber,$ipurchaseddate){
		$myADb = new ADb();
		$strSQL = "INSERT INTO `DIDReleaseIn36Hours` (`oid`,`void`,`did`,`purchasedate`)
                        VALUES ('$UID','$SellerID','$DIDNumber','".$ipurchaseddate."')";
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	public function getALLDataFromDIDS($pDID){
		$myADb = new ADb();
		$strSQL = "select DIDS.BOID,DIDS.iPurchasedDate,DIDS.iURL,DIDS.DIDNUmber,
						DIDS.OID as seller, BillingDate as CycleDay,
						substring(curdate(),6,2), substring(date_add(curdate(),INTERVAL 1 month),6,2),
						substring(curdate(),3,2),substring(curdate(),1,4) 
						from DIDS where DIDNumber='$pDID'
						and
						DATE_ADD(SUBSTRING(iPurchasedDate,1,10),INTERVAL 1 MONTH)<=CURDATE()";
		$Result	= $myADb->ExecuteQuery($strSQL);
		
		$data = array();
		$data['Buyer'] = $Result->fields[0];
		$data['PurchasedDate'] = $Result->fields[1];
		$data['URL'] = $Result->fields[2];
		$data['DIDNUmber'] = $Result->fields[3];
		$data['Seller'] = $Result->fields[4];
		$data['CycleDay'] = $Result->fields[5];
		$data['ThisMonth'] = $Result->fields[6];
		$data['NextMonth'] = $Result->fields[7];
		$data['ThisYearDigit'] = $Result->fields[8];
		$data['ThisYear'] = $Result->fields[8];
		return $data;
	}


	public function updateDIDSToFree($SetClause,$ffDIDID){
		$myADb = new ADb();
		$strSQL = "update DIDS set Status =1,BOID=\"\",iURL=\"\, OurSetupCost=SpSetup,SPOffer='0', OurMonthlyCharges=SpMonthly, IsChannel=0,groupid=\"\",Type=\"0\",SuspendDID=0,PostURLVar=\"\",PostURL=\"\"$SetClause where id = \"$ffDIDID\"";
		$Result	= $myADb->ExecuteQuery($strSQL);		
		return $Result;
	}
	public function updateDIDSToFree2($ffDIDID){
		$myADb = new ADb();
		$strSQL = "update DIDS set Status =1,BOID=\"\",iURL=\"\", OurSetupCost=SpSetup,SPOffer='0', OurMonthlyCharges=SpMonthly, IsChannel=0,groupid=\"\",Type=\"0\",SuspendDID=0,PostURLVar=\"\",PostURL=\"\" where id = \"$ffDIDID\"";
		$Result	= $myADb->ExecuteQuery($strSQL);		
		return $Result;
	}


	public function updateDIDSDataToFree($SpOffer,$SpOfferCode){
		$myADb = new ADb();
		$UID = currentUser();
		$strSQL = "update DIDS set OurSetupCost=SpSetup,SPOffer='0',SPOfferCode='',
                                    OurMonthlyCharges=SpMonthly            where BOID='$UID' and SpOffer=\"$SpOffer\" and SPOfferCode=\"$SpOfferCode\"";
		$Result	= $myADb->ExecuteQuery($strSQL);		
		return $data;
	}


	public function updateBufferLog($DIDNumber,$Buyer){
		$myADb = new ADb();
		$strSQL = "insert into logforbuffer (DIDNumber,OID,Date) values ($DIDNumber,$Buyer,now())";
		$Result	= $myADb->ExecuteQuery($strSQL);		
		return $Result;
	}
	function TotalDIDS($ffOID)
	{
		$myADb = new ADb();
		$strSQLCount="select count(*)  from DIDS where DIDS.oid=$ffOID ";
        $ResultCount = $myADb->ExecuteQuery($strSQLCount);
		$TotalDIDS = $ResultCount->fields[0];
        return $TotalDIDS;
        

	}

	function TotalSoldDIDs($ffOID)
	{
		$myADb = new ADb();
		$strSQLCount="select count(DIDNumber) from DIDS where OID=\"$ffOID\" and Status=2";
           $ResultCount = $myADb->ExecuteQuery($strSQLCount);
           $TotalSoldDIDS = $ResultCount->fields[0];
           return $TotalSoldDIDS;
	}

	function PurchasedDID($ffOID){
		$myADb=new ADb();
		$ResultCount = $myADb->ExecuteQuery("select count(OID) from DIDS where BOID=$ffOID");
        $PurchasedDID = $ResultCount->fields[0];
        return $PurchasedDID;
	}

	function TotalCount($ffOID)
	{
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL = "select count(*) from DIDS where OID=\"$ffOID\" and BOID=\"$UID\" and status=2  ";
        $Result1 = $myADb->ExecuteQuery($strSQL);
        $TotalCount = $Result1->fields[0];
        return $TotalCount;
	}


	function GetAverageLikeness($pOID)
	{
	    $myADb = new ADb();
	    $strSQL = "select cast((sum(Rating)/sum(Times))*10 as unsigned) from VRating where 
	                                 VID=\"$pOID\"  ";
	    $Result = $myADb->ExecuteQuery($strSQL);
	    $TotalAverage = $Result->fields[0];
	    return $TotalAverage;
	}

	function MyAverageLikeness($pOID)
	{
	    $myADb = new ADb();
	    $UID=currentUser();
	    $strSQL = "select cast((sum(Rating)/sum(Times)) as unsigned) from VRating where 
	               OID=\"$UID\" and VID=\"$pOID\"  ";

	    $Result = $myADb->ExecuteQuery($strSQL);
	    $TotalAverage = $Result->fields[0];
	    return $TotalAverage;
	}

	function TimesRating($ffOID){
		$myADb = new ADb();
	    $UID=currentUser();
		$strSQL = "select `Times` from `VRating` where `VID`=\"$ffOID\" and `OID`=\"$UID\"  ";
		
        $Result = $myADb->ExecuteQuery($strSQL);

         $TimesRating = $Result->fields[0];
         return $TimesRating;
	}
	//FUNCTION FOR SEARCH RESULT PAGE
	

	function TotalDIDSs($ffDID,$ffOID){
		$myADb = new ADb();
		if($ffDID !=''){
			 $strSQL = "select count(DIDNumber) as Total from DIDS where DIDS.VendorRating>=1 and DIDS.VendorRating<=9 and DIDS.DIDNumber like '$ffDID%' and Status = 0 and CheckStatus = 1";

        $Result = $myADb->ExecuteQuery($strSQL);
        }
        else{
        	$strSQL = "select count(DIDNumber) as Total from DIDS where DIDS.oid=$ffOID 
                    and Status = 0 and CheckStatus = 1 and DIDS.OID=\"$ffOID\" ";

           $Result = $myADb->ExecuteQuery($strSQL);
        }

        return $Result=$Result->fields['Total'];
        
		
	}

	function Displaydata($ffDID,$ffOID){
		$myADb = new ADb();
		if ($ffDID != ''){
			$strSQL = "select DIDNumber,CountryN as a,City,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,Status,DIDS.Id,DIDS.OID,countrycd,
                    areacd,DIDS.VendorRating,DIDS.CheckStatus,countrycd,DIDS.AreaID,FreeMin,RCenter, NumberHide
                    from DIDS where DIDS.VendorRating>=1 and DIDS.VendorRating<=9 and DIDS.DIDNumber like '$ffDID%' and Status = 0 and
                    CheckStatus = 1 order by didnumber asc limit 10,10";

        $Result    = $myADb->ExecuteQuery($strSQL);


		}
		else{
			 $strSQL = "select DIDNumber,CountryN as a,City,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,Status,DIDS.Id,DIDS.OID,countrycd,areacd,
                    DIDS.VendorRating,DIDS.CheckStatus,countrycd,DIDS.AreaID,FreeMin,RCenter, NumberHide from DIDS 
                    where DIDS.oid=$ffOID  and Status = 0 and CheckStatus = 1 order by didnumber asc limit 10,10"; 
                    
        $Result    = $myADb->ExecuteQuery($strSQL);
		}
		return $Result;


	}
	function GetDIDMSGForAll($pOID,$pAreaID)
	{
	    
	    $myADb=new ADb();

	    $strSQL = "select DocMsg from DIDMsg where Prefix=\"$pAreaID\" and OID=\"$pOID\" ";
	    
	    $Result    = $myADb->ExecuteQuery($strSQL);                    
	    
	        if($Result->EOF){
	            
	             $strSQL = "select DocMsg from DIDMsg where Prefix=\"-1\" and OID=\"$pOID\" ";
	    
	            $Result    = $myADb->ExecuteQuery($strSQL);                    
	            
	                if(!$Result->EOF){
	                    return $Result->fields[0];
	                }
	    
	        }                                                                                    
	    
	    return $Result->fields[0];
	    
	}

	function GetDIDforRoutingCSV($DIDNumber)
	{
		 $myAdb=new ADb();
		 $UID=currentUser();
		 $strSQL = "SELECT DIDS.DIDNumber FROM DIDS WHERE BOID=$UID and DIDNumber=$DIDNumber";
		
         $Result = $myAdb->ExecuteQuery($strSQL);
         return $Result;
                
	}

	function updatedataforrouting($RingTo,$Flag,$DIDNumber){
		$myAdb=new ADb();
		$UID=currentUser();
		$strSQLUpdate = "Update DIDS set DIDS.iURL='$RingTo',DIDS.iFlag='$Flag'  WHERE BOID=$UID and DIDNumber=$DIDNumber";
        $Result=$myAdb->ExecuteQuery($strSQLUpdate);
        return $Result;
	}
	
	function getdataforspecial($SPID){
		$myAdb=new ADb();
		$strSQL = "select id,AreaID,VOID,Setup,Monthly,date,Qty,comments from SpecialOffer where ID=\"$SPID\" ";		
		$Result= $myAdb->ExecuteQuery($strSQL);
		return $Result;
	}

	function GetBatchList(){
		$myADb=new ADb();
		$UID=currentUser();	
		$strSQL = "select DIDGroupLabel from DIDS where BOID='$UID' and Status=2 and DIDGroupLabel!=''  group by DIDGroupLabel";
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$html="<select name='BatchList' id='BatchList'>";
		$html .= "<option value=''>(none)</option>";
		while(!$Result->EOF){
				$BatchName = $Result->fields[0];
				$html .= "<option value='$BatchName'>$BatchName &nbsp;</option>";
				
				$Result->MoveNext();
		}
		return $html;
    }


	function GetIFDIDSExist($OID,$Area){
	
	$myAdb=new ADb();


	// $UID=currentUser();
	if(substr($Area,0,2) == '-1')
    {

        $CountryID = substr($Area,3);
	    $strSQL = "select count(*) from DIDS,DIDCountries,DIDArea where DIDArea.COuntryID=DIDCountries.ID and DIDS.OID='$OID'  and DIDS.VendorRating>=1 and DIDS.CheckStatus=1 and DIDS.status=0 and DIDCountries.ID=\"$CountryID\" and DIDS.AreaID=DIDArea.ID";
                    $Result =$myAdb->ExecuteQuery($strSQL);
    }
    else
    {

         $strSQL = "SELECT count(*) FROM DIDS WHERE DIDS.OID='$OID' AND DIDS.VendorRating>=1 AND DIDS.CheckStatus=1 AND DIDS.status=0 AND DIDS.AreaID=\"$Area\"";
         $Result = $myAdb->ExecuteQuery($strSQL);
         
    }

	// $Result = $myADb->ExecuteQuery($strSQL);

	$TotalCount = $Result->fields[0];
	
	return $TotalCount;
	
}

function GetCountryDetail($AreaID){
	$myADb=new ADb();
	$strSQL = "SELECT CountryCode,StateCode,DIDCountries.Description,DIDArea.Description AS DIDArea FROM DIDCountries,DIDArea WHERE DIDArea.CountryID=DIDCountries.ID AND DIDArea.ID=\"$AreaID\" ";

    $Result= $myADb->ExecuteQuery($strSQL);
    
    return $Result;
}

function getCountryAndAreaCode($pArea) {
	
	$myADb=new ADb();
	
	if(substr($pArea,0,2) == '-1'){
		
		$CountryID = substr($pArea,3);
			
			$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode , DIDCountries.ID
							from DIDCountries,DIDArea where  DIDCountries.ID=\"$CountryID\" and DIDArea.COuntryID=DIDCountries.ID";
				// $strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode 
				// 			from DIDCountries,DIDArea where  DIDCountries.ID=\"$CountryID\" and DIDArea.COuntryID=DIDCountries.ID";
							

							
							$Result             = $myADb->ExecuteQuery($strSQL);
							$Hash;
	
							$Hash['Country']      = $Result->fields[0];
							$Hash['Area']         = "All Areas";
							$Hash['CountryCode']  = $Result->fields[2];
							$Hash['AreaCode']     = "";
							$Hash['CountryID'] = $Result->fields[4];
							
							return $Hash;
							
		
		
	}else{
					
		$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode  , DIDCountries.ID
							from DIDCountries,DIDArea where  DIDArea.ID=\"$pArea\" and DIDArea.COuntryID=DIDCountries.ID";
	// $strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode 
	// 						from DIDCountries,DIDArea where  DIDArea.ID=\"$pArea\" and DIDArea.COuntryID=DIDCountries.ID";
							
						}
							
	$Result= $myADb->ExecuteQuery($strSQL);
	
	$Hash;
	
	$Hash['Country']      = $Result->fields[0];
	$Hash['Area']         = $Result->fields[1];
	
	$Hash['CountryCode']  = $Result->fields[2];
	$Hash['AreaCode']     = $Result->fields[3];
	$Hash['CountryID'] = $Result->fields[4];
	return $Hash;
	
}



function GetSpecialOffer($SpID) {
	
	$myADb=new ADb();
	global $TotalBalance;
	global $UniverseMsgErr;
	
	$strSQL = " select * from SpecialOffer where cron=1 and shownow=1 and ID!=\"$SpID\" order by date desc";	
	#echo $strSQL;
	$Result= $myADb->ExecuteQuery($strSQL);	


	if($Result->EOF){
		
		return "No Special Offers at this time. Please check back later.";
	}

	$SnoLine=0;
	$SNO=0;
	$htmlNew = "<table class=\"table table-striped\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";

	while(!$Result->EOF){
	$SnoLine++;
	$SNO++;
	$MyID = $Result->fields[0];
	$AreaID = 	$Result->fields[1];
	
	$VOID = 	$Result->fields[3];
	$Setup = 	$Result->fields[4];
	$Monthly = $Result->fields[5];
	$Date = $Result->fields[6];
	$Qty = 	$Result->fields[7];
	$Text = 	$Result->fields[10];
	$Hash = $this->getCountryAndAreaCode($AreaID);
	
	$Country = $Hash[CountryCode];
	$Area = $Hash[AreaCode]; 
		
	$TotalSetup = $Qty * $Setup;
	$TotalMonthly = $Qty * $Monthly;
	
	$Total = $TotalSetup + $TotalMonthly ;
	
	$TBalance = $TotalBalance * (-1);
	$ThisDisabled;
	$ThisOfferErr;
	
	if($UniverseMsgErr == ''){
	
			if($TBalance>=$Total) {
				$ThisOfferErr = "";
				$ThisDisabled = "  ";
			}else{
				$ThisOfferErr = "You do not have enough funds for this offer.";
				$ThisDisabled = " disabled ";
					
			}
	
    }
	$htmlNew .= "<td>";
    
	$hidden="<input type=\"hidden\" name=ThisID value=\"$MyID\">";
	
	if($Area!="-99")
			$AreaTagString = "$Area - $Hash[Area]";
	else
			$AreaTagString = "";
	
	$htmlNew .= $this->GetSpecialOfferBox("$Country - $Hash[Country]",$AreaTagString,$Qty,$TotalMonthly,$TotalSetup,$Total,$Text,$MyID,$ThisOfferErr,$SNO);
	if($SnoLine>=2){
        $SnoLine=0;
        $htmlNew .= "</td></tr><tr>";
    }else{
        $htmlNew .= "</td>";        
    }
	
	
	$htmlA .= "<form action=/ClientSpecialOfferConfirm method=POST >
                <tr class='grey'>
                <td width=\"50%\"><strong>Detail of the Offer</strong></td>                          
                <td width=\"50%\"><strong>$Text</strong></td>                                  
                </tr>
                <tr>
                <td width=\"50%\">Country</td> 
                <td width=\"50%\">$Country - $Hash{Country}</td>
                </tr>
                <tr class='grey'>
                <td width=\"50%\">Area</td>
                <td width=\"50%\">$Area - $Hash{Area}</td>
                </tr>
                <tr>
                <td width=\"50%\">Quantity</td>
                <td width=\"50%\">$Qty</td>
                </tr>
                <tr class='grey'>
                <td width=\"50%\">Setup</td>
                <td width=\"50%\">\$ $Setup</td>
                </tr>
                <tr>
                <td width=\"50%\">Monthly</td>
                <td width=\"50%\">\$ $Monthly</td>
                </tr>
                <tr class='grey'>
                <td width=\"50%\">Total One Time Charges</td>
                <td width=\"50%\">\$ $TotalSetup</td>
                </tr>
                <tr>
                <td width=\"50%\">Total Monthly Charges</td>
                <td width=\"50%\">\$ $TotalMonthly</td>
                </tr>          
                <tr class='grey'>
                <td width=\"50%\">Total Charges</td>  
                <td width=\"50%\">\$ $Total</td>
                </tr>
                <tr>
                <td><font color=red size=-2 face=verdana></td>
                <td><input name='BuyButton' type=submit value=\"Buy Now\" $Disabled $ThisDisabled></td>
                </tr><input type=\"hidden\" name=ThisID value=\"$MyID\" >
                          ";
	$Result->MoveNext();
	}
		
		$htmlNew .= "</table>";
		return $htmlNew;
		
}

function GetSpecialOfferBox($Country,$Area,$Qty,$Monthly,$Setup,$Total,$Details,$Hidden,$ErrorMsg="",$SNO){
	
	if($ErrorMsg!=""){
		$BuyNowButton="<input type=image src=\"/new_images/buy.gif\" width=\"120\" height=\"41\">";
	}else{
		$BuyNowButton="<input type=image src=\"/new_images/buy.gif\" width=\"120\" height=\"41\">";
	}
	
	$html = "<form action=\"\" method=POST >

     <div class=\"row\">
              <div class=\"col-md-8 col-md-offset-1\">   
                  <div class=\"panel\">
                    <div class=\"panel-title text-center\">
                      $Qty DIDs \$$Monthly/ month
                    </div>
                    <div class=\"panel-body text-center\">  
                      <h3 style=\"margin-top:2px; margin-bottom: 2px;\">
                      $Country</h3>
                      <p><strong>$Area</strong></p> 
                      <br />
                      <p>One Time Charges \$$Setup<br />
                      Total Deal \$$Total</p><br />
                      <p>$Details</p><br />
                      <a href=\"/ClientSpecialOfferConfirm?ThisID=$Hidden\" class=\"btn btn-default\">Buy Now</a>
                    </div>
                  </div>
                  </div></div></form>";
	
	
	return $html;
}

function GetDidInfoByDidID($ID) {
	$myADb=new ADb();
	$strSQL="SELECT DIDnumber FROM DIDS WHERE id=$ID";
    $Result= $myADb->ExecuteQuery($strSQL);	
    return $Result->fields[0];
}

function EmailFreeDidAlert($UID,$CusEmail,$Tel){
        $to = "mi@supertec.com,sales@supertec.com";
        $GetSubject ="$UID Want To Remove did";
        $subject = $GetSubject;
        $contents_feild="$UID wants to remove did number $DIDNumber please contact him at Email: $CusEmail No: $Tel";
		$from_feild = "care@didx.net";
		$cc_feild = "ua@supertec.com";
		$reply_to_feild = "care@didx.net";
		$Title="Delete DID";

         $message = "
        <html>
        <head>
        <title>$Title</title>
        </head>
        <body>
        <img src='https://www.didx.net/assets/site/images/logo.png'>
        <p>$description</p><br>
        <p>$from_feild</p><br><br>        
        <p>$contents_feild</p><br>        
        </body>
        </html>
        ";
        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        // Additional headers
        $headers[] = "To:<$reply_to_feild>";
        $headers[] = "From:<$from_feild>";
        $headers[] = "Cc: $cc_feild";
        // Mail it
        mail($to, $subject, $message, implode("\r\n", $headers));


    }

    public function RemovePurchaseDIDs($DIDID){
    	$myADb=new ADb();
    	$strSQL = "update DIDS set Status =1  where id = \"$ffDIDID\"";
		$Result	= $myADb->ExecuteQuery($strSQL);		
		return $Result;

    }

    function RemoveFromMinutesInfo($pDID) {
	
	 $myADb=new ADb();
	
	$strSQL="delete from MinutesInfo where DID=\"$pDID\" ";
	$Result	= $myADb->ExecuteQuery($strSQL);
	
	$strSQL="delete from ChannelBuy where didnumber=\"$pDID\" ";	
	$Result	= $myADb->ExecuteQuery($strSQL);
   }


   function GetDIDRating($pOID,$pDID) {
    
    $myADb=new ADb();
    
    $strSQL = "select Rating from DIDRating where OID=\"$pOID\" and DID=\"$pDID\" ";
#   print $strSQL;
    $Result = $myADb->ExecuteQuery($strSQL);
    
    return $Result->fields[0];   
    
   }

   function DoDIDRating($pOID,$pDID,$pRating) {
    
        $myADb=new ADb();
    
        $strSQL = "select Rating from DIDRating where OID=\"$pOID\" and DID=\"$pDID\" ";
        $Result = $myADb->ExecuteQuery($strSQL);
    
	    if($Result->EOF){
	        
	        $strSQL = "insert into DIDRating(OID,DID,Rating,Date)values
	        (\"$pOID\", \"$pDID\", \"$pRating\", sysdate() )";
	        $Result = $myADb->ExecuteQuery($strSQL);
	    
	    }else{
	        
	        $strSQL = "update DIDRating set Rating=\"$pRating\" where OID=\"$pOID\" and DID=\"$pDID\" ";
	        #print $strSQL;
	        $Result = $myADb->ExecuteQuery($strSQL);
	        
    }
     
  }

  function updatePosturl($UID,$ffDID,$URL)
  {
  	 $myADb=new ADb();
  	 $UID=currentUser();
  	 $strSQL = "update DIDS set PostURL=\"$URL\" where DIDNumber=\"$ffDID\" and BOID=\"$UID\"  ";
  	 $Result = $myADb->ExecuteQuery($strSQL);


  }

  function getposturl($ffDID,$UID){
  	 $myADb=new ADb();
  	 $UID=currentUser();
  	 $strSQL = "select PostURL from DIDS where didnumber=\"$ffDID\" and BOID=\"$UID\"  ";
  	 $Result = $myADb->ExecuteQuery($strSQL);
  	 return $Result;
  }

  function updatePostURLVar($URL,$ffDID,$UID){
  	 $myADb=new ADb();
  	 $UID=currentUser();
  	 $strSQL = "update DIDS set PostURLVar=\"$URL\" where DIDNumber=\"$ffDID\" and BOID=\"$UID\"  ";
  	 $Result = $myADb->ExecuteQuery($strSQL);
  }

  function getPostURLVar($ffDID,$UID){
     $myADb=new ADb();
  	 $UID=currentUser();
  	 $strSQL = "select PostURLVar from DIDS where didnumber=\"$ffDID\" and BOID=\"$UID\"  ";
     $Result = $myADb->ExecuteQuery($strSQL);
     return $Result;
  }

  public function SaveNotes($ffDID,$mynotes){
		$myADb=new ADb();
		$UID=currentUser();
		$mynotes = urlencode($mynotes);
		
	    $strSQL = "select Notes from DIDNotes where DID=\"$ffDID\" and OID='$UID'";

	    $Result	= $myADb->ExecuteQuery($strSQL);
	
	   if($Result){
		
		$strSQL = "insert into  DIDNotes (OID,DID,Notes,DateUpdated)
		values(\"$UID\", \"$ffDID\", \"$mynotes\", now() )";
				 
		$Result	= $myADb->ExecuteQuery($strSQL);
		
	}else{
		$strSQL = "update DIDNotes set Notes=\"$mynotes\",DateUpdated=now() where OID='$UID' and DID='$ffDID'";		
			
		$Result	= $myADb->ExecuteQuery($strSQL);
		
	}
    $strSQL = "select Notes from DIDNotes where DID=\"$ffDID\" and OID='$UID'";

    $Result    = $myADb->ExecuteQuery($strSQL);
    
    $Notes = urldecode($Result->fields['Notes']);
    //echo $strSQL;
    return $Notes;  
//print "<meta http-equiv=refresh content='0;url=CDIDInfo.php?did=$ffDID'>";
}

   public function CheckDuplication($ffArea,$ffNXX){
   	  $UID = currentUser();
   	  $myADb=new ADb();
   	  $strSQL="select * from RequestedDIDSNew where 
					 	OID=\"$UID\" and AreaID=\"$ffArea\" and NXX=\"$ffNXX\" ";

		$ResultC	= $myADb->ExecuteQuery($strSQL);
		return $ResultC;					
   }


   public function ShowDidIfAvliable($SQLClause,$WhereNXX){
   	$UID = currentUser();
   	$myADb=new ADb();
   	$strSQL="select DIDCountries.CountryCode,DIDArea.StateCode,DIDArea.Id 
					 from DIDS,DIDArea,DIDCountries where DIDS.AreaID=DIDArea.id and 
					 DIDArea.CountryID=DIDCountries.id and			
					 VendorRating>=1 and VendorRating<=9 
					 $SQLClause								 
					 Status = 0 and
					 CheckStatus = 1  $WhereNXX
					 group by DIDS.AreaID
					 order by DIDNumber
					 limit 0,1";
					 

	$Result	= $myADb->ExecuteQuery($strSQL);	
		$Hash=array();
		 $Hash['CountryCode'] = $Result->fields[0];
		 $Hash['StateCode'] = $Result->fields[1];	
		return $Hash;
   }

   public function getnotes($DID){
   	$myADb=new ADb();
   	$UID=currentUser();
   	$strSQL = "select Notes from DIDNotes where DID=\"$DID\" and OID='$UID'";
	$Result	= $myADb->ExecuteQuery($strSQL);
	$Notes = urldecode($Result->fields[0]);
	return $Notes;
   }

    public function searchdidbyoid($DID){
    	$myADb=new ADb();
    	$UID=currentUser();
    	    $strSQL="select select DIDNumber,CountryN,City,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,DIDS.iURL,DIDS.Id,DIDS.id as ID,DIDS.iFlag,countrycd,areacd,
             DIDS.Type,date_format(iPurchasedDate,'%d-%b-%Y') as Date,DIDS.ID as DDID ,DIDS.OID as VOID,DIDS.AreaID, HaveDocs,NeedDocs, NeedDocsType, NeedDocsID,NeedDocsMsg,DIDGroupLabel from DIDS where OID=$UID $DID";
         $Result	= $myADb->ExecuteQuery($strSQL);
         return $Result;
    }

    public function searchdidbyboid($DID){
    	$myADb=new ADb();
     	$UID=currentUser();
     	$strSQL="select DIDNumber,CountryN,City,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,DIDS.iURL,DIDS.Id,DIDS.id as ID,DIDS.iFlag,countrycd,areacd,
             DIDS.Type,date_format(iPurchasedDate,'%d-%b-%Y') as Date,DIDS.ID as DDID ,DIDS.OID as VOID,DIDS.AreaID, HaveDocs,NeedDocs, NeedDocsType, NeedDocsID,NeedDocsMsg,DIDGroupLabel from DIDS where BOID = $UID $DID";
         $Result	= $myADb->ExecuteQuery($strSQL);
         return $Result;
    }

    public function getdidbyoid($DID){
    	$myADb=new ADb();
     	$UID=currentUser();
    	$strSQL="select * from DIDS where OID=$UID $DID";
    	
    	$Result	= $myADb->ExecuteQuery($strSQL);
        return $Result;
    }

    public function getdidbyboid($DID){
    	$myADb=new ADb();
     	$UID=currentUser();
    	$strSQL="select * from DIDS where BOID = $UID $DID";
    	
    	$Result	= $myADb->ExecuteQuery($strSQL);
        return $Result;

    }

    public function ShowAlternate($SQLClause,$ffNXX){
   	//$UID = currentUser();
   	$myADb=new ADb();
   	 $strSQL="select DIDCountries.CountryCode,DIDArea.StateCode,DIDArea.Id
					 from DIDS,DIDArea,DIDCountries where DIDS.AreaID=DIDArea.id and 
					 DIDArea.CountryID=DIDCountries.id and							
					 VendorRating>=1 and VendorRating<=9 
					 $SQLClause								 
					 Status = 0 and
					 CheckStatus = 1  
					 group by DIDS.AreaID
					 order by DIDNumber
					 limit 0,1";

		$Result	= $myADb->ExecuteQuery($strSQL);
		$Hash=array();
		$Hash['CCode'] = $Result->fields[0];
		$Hash['SCode'] = $Result->fields[1];	
		return $Hash;					
   }

   function Updateringto($RingTo,$RingType,$DIDNumber){
   	$myADb=new ADb();
    $UID=currentUser();
    $strSQL = "update DIDS set iURL=\"$RingTo\",iFlag=\"$RingType\" where DIDNumber=\"$DIDNumber\"  and BOID=\"$UID\"   ";
    $Result = $myADb->ExecuteQuery($strSQL);

    $strSQL = "update DIDS set iURL=replace(iURL,'DID',DIDNumber) where DIDNumber=\"$DIDNumber\"   and BOID=\"$UID\"   ";
	$Result = $myADb->ExecuteQuery($strSQL);
    return $Result;
   }


   function Updateringbybatch($RingTo,$RingType,$batchName){
      	$myADb=new ADb();
        $UID=currentUser();
        $strSQL = "update DIDS set iURL=\"$RingTo\",iFlag=\"$RingType\" where DIDGroupLabel=\"$batchName\"  and BOID=\"$UID\"   ";

        $Result = $myADb->ExecuteQuery($strSQL);
        
        $strSQL="";
        
        $strSQL = "update DIDS set iURL=replace(iURL,'DID',DIDNumber) where DIDGroupLabel=\"$batchName\" and  BOID=\"$UID\"   ";

        $Result = $myADb->ExecuteQuery($strSQL);
	return $Result;
   }

   function updategroupname($GroupName,$DIDNumber){
   		$myADb=new ADb();
        $UID=currentUser();
        $strSQL = "update DIDS set DIDGroupLabel=\"$GroupName\" where DIDNumber=\"$DIDNumber\"  and BOID=\"$UID\"   ";
        # echo "\$strSQL: $strSQL";
          $Result = $myADb->ExecuteQuery($strSQL);
          return $Result;
   }

   function getDIDsid($DIDNumber){
   	    $myADb=new ADb();
   		$strSQLID = "select id from DIDS where DIDNumber='$DIDNumber'";
                #echo $strSQL;
        $ResultDIDSID   = $myADb->ExecuteQuery($strSQLID);
         $ffDID = $ResultDIDSID->fields['id'];
         return $ffDID;
   }

   function UpdateDIDStatus($pDID,$pKeyID) {
	
				$myADb=new ADb();
				$UID=currentUser();
				
				$strSQL = "SELECT status,channelQty,channelGroupID FROM CusDocs WHERE DID=\"$pDID\"  and KeyID=\"$pKeyID\"   ";

				$ResultDIDS	= $myADb->ExecuteQuery($strSQL);
				
				if(!$ResultDIDS->EOF){
						if($ResultDIDS->fields[0]==1){
				
						$strSQL = "update DIDS set status=0 where DIDNumber=\"$pDID\"  ";
					#	echo $strSQL . "<br>";
						$myADb->ExecuteQuery($strSQL);
                        $ArrRes['Status']   = $ResultDIDS->fields[0];    
                        $ArrRes['ChaQTY']   = $ResultDIDS->fields[1];    
                        $ArrRes['GroupID']  = $ResultDIDS->fields[2];	
						return $ArrRes;
						}else{
							return 2;
						}
				}else{
					
				
					
				}
				
				return -1;
	
    }

	function getDID2($ffDID){
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL = "select DIDNumber from DIDS where ResRelOID='$UID' and IsResRel=1 and id=\"$ffDID\"  ";

	    $ResultDIDS2    = $myADb->ExecuteQuery($strSQL);
	  
	    return $ResultDIDS2;
	}

	function upldateDID($ffDID){
		 $myADb=new ADb();
		 $strSQL = "update DIDS set Status=0 where id=\"$ffDID\"  ";
	     $ResultDIDS3 = $myADb->ExecuteQuery($strSQL);
	     return $ResultDIDS3;
	}

	function getDIDNumber($ffDID){
		$myADb=new ADb();
		$strSQL = "select DIDNumber from DIDS where id=\"$ffDID\"  ";

	    $ResultDIDS3    = $myADb->ExecuteQuery($strSQL);
	    $TempDID = $ResultDIDS3->fields[0];

	    $strSQL = "select DIDNumber from ShoppingCart where DIDNumber=\"$TempDID\"  ";

	    $ResultDIDS3    = $myADb->ExecuteQuery($strSQL);

	    return $ResultDIDS3;

	}

	function updateDIDs($ffDID){
		 $myADb=new ADb();
		 $strSQL = "update DIDS set Status=0 where id=\"$ffDID\"  ";
	     $ResultDIDS3 = $myADb->ExecuteQuery($strSQL);
	     return $ResultDIDS3;
	     }

	 function getdatafromDID($ffDID,$ffDIDNumber){
	 	$myADb=new ADb();
	 	 $strSQL = "select DIDNumber,OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,FreeMin,CountryCD,AreaCD,CountryN,City,RCenter,date_format(OfferDate,'%d-%b-%Y') as OfferDate,AreaID,OID from DIDS where ID=\"$ffDID\" and  DIDNumber=\"$ffDIDNumber\" and status IN (0,1)";


		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	 }
	 //Warisha
	function ResultDIDS($ffDID){
		$myADb=new ADb();
		$UID=currentuser();
		$strSQL = "select DIDNUmber from DIDS where ResRelOID='$UID' and IsResRel=1 and id=\"$ffDID\"  ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;

	}

	function UpdateStatus($ffDID){
		$myADb=new ADb();
		$strSQL = "update DIDS set Status=0 where id=\"$ffDID\"  ";
        $ResultDIDS3 = $myADb->ExecuteQuery($strSQL);

	}

	function getDIDbyffDID($ffDID){
		$myADb=new ADb();
		$strSQL = "select DIDNumber from DIDS where id=\"$ffDID\"  ";
        $ResultDIDS3    = $myADb->ExecuteQuery($strSQL);
        return $ResultDIDS3;
	}

	function getdidbycart($TempDID){
		$myADb=new ADb();
		$strSQL = "select DIDNumber from ShoppingCart where DIDNumber=\"$TempDID\"  ";
        $ResultDIDS3	= $myADb->ExecuteQuery($strSQL);
        return $ResultDIDS3;

	}
	//Warisha

	 function GetVendorRatingByDID($pDIDNumber) {
	
		$myADb=new ADb();
		$UID=currentUser();
			
    	 $strSQL = "select VendorRating from DIDS
    							where DIDS.DIDNumber=\"$pDIDNumber\" ";
    						
    	 $Result	= $myADb->ExecuteQuery($strSQL);
    	
    	 $VRating = $Result->fields['VendorRating'];
    	
    	 $strSQL = "select DIDRating from orders where OID=\"$UID\"  ";
    	 #echo $strSQL;
    	 $Result	= $myADb->ExecuteQuery($strSQL);
    	
    	 $Rating = $Result->fields['DIDRating'];
    	
    	if($VRating < $Rating ){
    		
    		return -1;
    	}
    	
    	return $Rating;
      }

      

	   function GetChannelPricing($AreaID,$VOID,$ChannelTableName) {
		
			 $myADb=new ADb();
			
			$strSQL = "select TotalChannels,Setup,Monthly,ID from $ChannelTableName 
			where OID=\"$VOID\"  and AreaID=\"$AreaID\" ";

			$Result	= $myADb->ExecuteQuery($strSQL);
			
			if($Result->EOF){
				
					$strSQL = "select TotalChannels,Setup,Monthly,ID from $ChannelTableName 
					where OID=\"$VOID\"  and AreaID=\"-1\" ";
			
					$Result	= $myADb->ExecuteQuery($strSQL);
					
						if($Result->EOF){
							return "";
						}
				
			}
			
			$ChannelPriceMonthly =  $Result->fields[2];
			$ChannelPriceSetup =  $Result->fields[1];
			$ChannelAdminID =  $Result->fields[3];
			
			$Hash = array();
			
			$Hash['Setup'] = $ChannelPriceSetup;
			$Hash['Monthly'] = $ChannelPriceMonthly;
			$Hash['ChannelID'] = $ChannelAdminID;

			return $Hash;
			
			
			$htmlChannel ="<tr class=\"grey\">
		          <td width=\"10%\">&nbsp;</td>
		          <td width=\"50%\"><strong>Channel Setup Price ($)</strong></td>
		          <td width=\"50%\" align=\"left\">$ChannelPriceSetup</td>
		          <td width=\"10%\">&nbsp;</td>
		        </tr>
		        <tr>
		        <td width=\"10%\">&nbsp;</td>
		          <td width=\"50%\"><strong>Per Channel Monthly Price ($)</strong></td>
		          <td width=\"50%\" align=\"left\">$ChannelPriceMonthly</td>
		          <td width=\"10%\">&nbsp;</td>
		          </tr>
		";

			return $htmlChannel;
			
		}

	function GetRingToHTML() {
		
		
		$myCustomer=new Customer();
		$UID=currentUser();
		$hSip="";
		$Customer = $myCustomer->getCustomerEmailPref($UID);

		$DefaultRingToType = $Customer['DefaultRingtoType'] + 10;
		
			if($DefaultRingToType==11)
						$DefaultType = "SIP";
			
			if($DefaultRingToType==12)
						$DefaultType = "IAX";
			
			if($DefaultRingToType==13)
						$DefaultType = "H323";
						
		$DefaultRingTo = $Customer['DefaultRingto'];
	#	print "\$DefaultRingTo: $DefaultRingTo";
		$H323 = $Customer['H323'];
		
		if($H323)
			$hSip = "<option value='3'>H323</option>";
		
		$GetRingTo = "<select ID='RINGTOTYPE' name='RINGTOTYPE' OnChange=\"GetDefaultRingTo('$DefaultRingTo');\">
		<option value='1'>SIP</option>
		<option value='2'>IAX</option>
		<option value='$DefaultRingToType' selected >Default:$DefaultType</option>
		$hSip
		<option value='10'>Test:SIP</option>
		</Select> &nbsp;
		<input type=text value='$DefaultRingTo' name='RingTo' ID='RingTo' size=30>
		";
		
		$html = "<tr class=\"grey\">
	      <td width=\"10%\">&nbsp;</td>
	    <td width=\"50%\"><strong>Set Ring To</strong></td>
	    <td width=\"50%\" align=\"left\">$GetRingTo</td>
	    <td width=\"10%\">&nbsp;</td>
	  </tr>";
	  
	  
	  return $html;
	
}

	public static function UpdateRouting($ffRangeFrom,$ffRangeTo,$ffDIDFrom,$ffDIDTo,$ffIP,$ffFlag)
	 {
	 	$myADb=new ADb();
	    $UID=currentuser();
	 	$SnoNumber = $ffRangeFrom;

	 for($nIndex=$ffDIDFrom;$nIndex<=$ffDIDTo;$nIndex++)
	    {
		  $strSQL="select ID,DIDNumber,BOID from DIDS where DIDNumber=\"$nIndex\" and Status=2
		           and  BOID='$UID' ";

  		  $Result = $myADb->ExecuteQuery($strSQL);
		  $DIDNumber = $Result->fields[1];
          $URL = "$SnoNumber@$ffIP";

          $strSQL="Update DIDS set iURL=\"$URL\",iFlag=\"$ffFlag\" where BOID=\"$UID\" 
                  and  DIDNUmber=\"$DIDNumber\" and Status=2";
                // echo $strSQL;
                // exit; 
		 $Result = $myADb->ExecuteQuery($strSQL);		
		 $SnoNumber++;
	     // return;

	}
}

	function GetMinutesInfoByDID($pDID,$pUID,$pFree) {
	
		//$myADb = new ADb();
		
		$strSQL="select MinSpent from MinutesInfo where DID=\"$pDID\" and OID=\"$pUID\"   ";
		$RemainMin = $this->ADb->ExecuteQuery($strSQL);
		
		$Remain = $pFree - $RemainMin->fields[0];
		
		return $Remain;
		
	}

	//for TESTPURCHASED.CGI
    function  GetDIDByDIDID($pDIDID){
		$myADb=new ADb();
	    $strSQL = "select DIDNumber,NumberHide,CountryCd,AreaCD from DIDS where ID=\"$pDIDID\" ";
		$Result	= $myADb->ExecuteQuery($strSQL);
		
		$DIDNumber = $Result->fields[0];
		$NumberHide = $Result->fields[1];
		$CountryCode = $Result->fields[2];
		$AreaCode = $Result->fields[3];
		
		return $Result->fields[0];	
    }


    function currentDate() {
	$pDate		= gmtime();
	$DateTime	= $this->parseGMTRaw($pDate);
	return "$DateTime->{Date}-$DateTime->{Month}-$DateTime->{Year} $DateTime->{Time}";
    } # End currentDate

#	parseGMTRaw
#	IN:	GMT (Sat Jul 27 19:39:44 2002)
#	OUT:	Hash: Date, Month, Year and Time
    function  parseGMTRaw($pDate) {
	
	$aTodayDate	= explode('/', $pDate);
	$DateTime;
	$DateTime['Day']	= $aTodayDate[0];
	$DateTime['Month']= $aTodayDate[1];
	$DateTime['Date']	= $aTodayDate[2];
	$DateTime['Time']	= $aTodayDate[3];
	$DateTime['Year']	= $aTodayDate[4];
	return $DateTime;
    }

	//TESTPURCHASED.CGI
	

	function GetTestLog($ffDID) {
	
			//global $myADb;
			
			$strSQL ="select id,did,date_format(testdate,'%d-%b-%Y %h:%i:%s'),status,testperson,datestamp,failedon,passedon,location,hours,cx,c1,c2,c3,c4,
			testeranswer,box,MyOID
			 from DIDTestLog where DID=\"$ffDID\" order by TestDate Desc limit 0,5";
			#	echo $strSQL;				
			$Result = $this->ADb->ExecuteQuery($strSQL);

			$nIndex=0;

			while(!$Result->EOF){
			
				$Loc = $Result->fields[8];
			
			if($Result->fields[3]==1) {
				$Status="Pass";
				$TotalPass++;
			}else {
				$Status="Fail";
				$TotalFail++;
			}
			
			$C1 = $Result->fields[11];
			$C2 = $Result->fields[12];
			$C3 = $Result->fields[13];
			$C4 = $Result->fields[14];

		$Answer = $Result->fields[15];
			
			if(is_numeric($Result->fields[4])) {		
				$Runner = $Result->fields[4];		
			} else {
				$Runner = $Result->fields[4];		
			}
			
			$TestDateTime = $Result->fields[2];
			
			$nIndex++;
			
				if($bgcolor == '#C3D9FF'){
						
						$bgcolor="#E0ECFF";
						
					}else{
						
						$bgcolor="#C3D9FF";
						
					}
					
			
			$html .=" <tr class='bg'> 
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$nIndex</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$TestDateTime</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$Status</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$Runner</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$Loc</td>  
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$C1</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$C2</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$C3</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$C4</td>
		                <td align=center style=\"border-bottom:1px solid #F8F8F8;\">$Answer</td>
		              </tr>";
			
			
			
			$Result->MoveNext();
		}

		#$html .= "<tr><td colspan=10 align=right><a href=\"http://didx.net/cgi-bin/virtual/admins/TesterLog.cgi?DID=$ffDID\">More ...</a></td></tr>";

		return  $html;
			
		}


   function RequestDidEmail($ffArea,$ffNXX,$ffPrice,$ffQty,$ffChannel,$ffComments){
   $EmailOrders      = $myOrder->getOrdersInfoByUID($UID);
   $EmailCustomer    = $myCustomer->getCustomerInfo($EmailOrders['CustomerID']);



// define variables
    $From = 'care@didx.net';
    $cc   = 'kamal@supertec.com';
    $To   = 'sales@didx.net';
    $Subject ="DIDX [Request: DID is requested by Client $ffNXX UID: $UID for unavailable area]\n";

// define headers
    $headers = "From: " . strip_tags($From) . "\r\n";
    $headers .="Reply-to: $cc\n";
    $headers .= "CC: $cc\r\n";

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
 //   $headers .="\n";

    $message = <<<EDO
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Email from DIDX</title>
<style type="text/css">

    body {margin:0px; padding:0px; background-color:#ffffff; color:#777777; font-family:Arial, Helvetica, sans-serif; font-size:12px; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:100% !important;}
    a, a:link, a:visited {color:#ee7716; text-decoration:underline;}
    a:hover, a:active {text-decoration:none; color:#985118 !important;}
    h1, h2, h3, h1 a, h2 a, h3 a {color:#ee7716 !important;}
    h2 {padding:0px 0px 10px 0px; margin:0px 0px 10px 0px;}
    h2.name {padding:0px 0px 7px 0px; margin:0px 0px 7px 0px;}
    h3 {padding:0px 0px 5px 0px; margin:0px 0px 5px 0px;}
    p {margin:0 0 14px 0; padding:0;}
    img {border:0; -ms-interpolation-mode: bicubic; max-width:100%;}
    a img {border:none;}
    table td {border-collapse:collapse;}
    td.quote {font-family:Georgia, Times New Roman, Times, serif; font-size:18px; line-height:20pt; color:#ee7716;}
    span.phone a, span.noLink a{color:ee7716; text-decoration:none;}
    
    /* Hotmail */
    .ReadMsgBody {width: 100%;}
    .ExternalClass {width: 100%;}
    /* / Hotmail */
    
    /* Media queries */
    @media (max-width: 767px) {
        td[class=shareContainer], td[class=topContainer], td[class=container] {padding-left:20px !important; padding-right:20px !important;}
        table[class=row] {width:100% !important; max-width:600px !important;}
        img[class=wideImage], img[class=banner] {width:100% !important; height:auto !important; max-width:100%;}
    }
    @media (max-width: 560px) {
        td[class=twoFromThree] {display:block; width:100% !important;}
        td[class=inner2], td[class=authorInfo] {padding-right:30px !important;}
        td[class=socialIconsContainer] {display:block; width:100% !important; border-top:0px !important;}
        td[class=socialIcons], td[class=socialIcons2] {padding-top:0px !important; text-align:left !important; padding-left:30px !important; padding-bottom:20px !important;}
    }
    @media (max-width: 480px) {
        html, body {margin-right:auto; margin-left:auto;}
        td[class=oneFromTwo] {display:block; width:100% !important;}
        td[class=inner] {padding-left:30px !important; padding-right:30px !important;}
        td[class=inner_image] {padding-left:30px !important; padding-right:30px !important; padding-bottom:25px !important;}
        img[class=wideImage] {width:auto !important; margin:0 auto;}
        td[class=viewOnline] {display:none !important;}
        td[class=date] {font-size:14px !important; padding:10px 30px !important; background-color:#f4f4f4; text-align:left !important;}
        td[class=title] {font-size:24px !important; line-height:32px !important;}
        table[class=quoteContainer] {width:100% !important; float:none;}
        td[class=quote] {padding-right:0px !important;}
        td[class=spacer] {padding-top:18px !important;}
    }
    @media (max-width: 380px) {
        td[class=shareContainer] {padding:0px 10px !important;}
        td[class=topContainer] {padding:10px 10px 0px 10px !important; background-color:#e9e9e9 !important;}
        td[class=container] {padding:0px 10px 10px 10px!important;}
        table[class=row] {min-width:240px !important;}
        img[class=wideImage] {width:100% !important; max-width:255px;}
        td[class=authorInfo], td[class=socialIcons2] {text-align:center !important;}
        td[class=spacer2] {display:none !important;}
        td[class=spacer3] {padding-top:23px !important;}
        table[class=iconContainer], table[class=iconContainer_right] {width:100% !important; float:none !important;}
        table[class=authorPicture] {float:none !important; margin:0px auto !important; width:80px !important;}
        td[class=icon] {padding:5px 0px 25px 0px !important; text-align:center !important;}
        td[class=icon] img {display:inline !important;}
        img[class=buttonRight] {float:none !important;}
        img[class=bigButton] {width:100% !important; max-width:224px; height:auto !important;}
        h2[class=website] {font-size:22px !important;}
    }
    /* / Media queries */

</style>

<!-- Internet Explorer fix -->
<!--[if IE]>
<style type="text/css">
@media (max-width: 560px) {
    td[class=twoFromThree], td[class=socialIconsContainer] {float:left; padding:0px;}
}
@media only screen and (max-width: 480px) {
    td[class=oneFromTwo] {float:left; padding:0px;}
}
@media (max-width: 380px) {
    span[class=phone] {display:block !important;}
}
</style>
<![endif]-->
<!-- / Internet Explorer fix -->

<!-- Windows Mobile 7 -->
<!--[if IEMobile 7]>
<style type="text/css">
    td[class=shareContainer], td[class=topContainer], td[class=container] {padding-left:10px !important; padding-right:10px !important;}
    table[class=row] {width:100% !important; max-width:600px !important;}
    td[class=oneFromTwo], td[class=twoFromThree] {float:left; padding:0px; display:block; width:100% !important;}
    td[class=socialIconsContainer] {float:left; padding:0px; display:block; width:100% !important; border-top:0px !important;}
    td[class=socialIcons], td[class=socialIcons2] {padding-top:0px !important; text-align:left !important; padding-left:30px !important; padding-bottom:20px !important;}
    td[class=inner], td[class=inner2], td[class=authorInfo] {padding-left:30px !important; padding-right:30px !important;}
    td[class=inner_image] {padding-left:30px !important; padding-right:30px !important; padding-bottom:25px !important;}
    td[class=viewOnline] {display:none !important;}
    td[class=date] {font-size:14px !important; padding:10px 30px !important; background-color:#f4f4f4; text-align:left !important;}
    td[class=title] {font-size:24px !important; line-height:32px !important;}
    table[class=quoteContainer] {width:100% !important; float:none;}
    td[class=quote] {padding-right:0px !important;}
    td[class=spacer] {padding-top:18px !important;}
    span[class=phone] {display:block !important;}
    img[class=banner] {width:100% !important; height:auto !important; max-width:100%;}
    img[class=wideImage] {width:auto !important; margin:0 auto;}
</style>
<![endif]-->
<!-- / Windows Mobile 7 -->

<!-- Outlook -->
<!--[if gte mso 15]>
<style type="text/css">
.iconContainer, .quoteContainer {mso-table-rspace:0px; border-right:1px solid #ffffff;}
.iconContainer_right {mso-table-rspace:0px; border-right:1px solid #ffffff; padding-right:1px;}
.authorPicture {mso-table-rspace:0px; border-right:1px solid #f4f4f4;}
</style>
<![endif]-->
<!-- / Outlook -->

</head>

<body>

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
    <!-- Start of view online, tweet and share -->

    <!-- End of view online, tweet and share -->
    
    <!-- Start of logo and date -->
  
    <!-- End of logo and date -->

    <!-- Start of main container -->
    <tr>
        <td class="container" style="padding-left:5px; padding-right:5px; padding-bottom:20px; background-color:#e9e9e9;">
            
            <!-- Start of title on the color background -->
            <table class="row" width="600" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; text-align:left; border-spacing:0; max-width:100%;">
                <tr>
                    <td height="2" style="padding-top:3px; font-size:2px; line-height:2px;">&nbsp;
<!--                     <img alt="Logo" src="http://sandbox.didx.net/niha/images/banner.jpg" border="0" align="left" vspace="0" hspace="0" style="display:block;">
 -->                    </td>
                </tr>
                <tr>
                    <td class="title" bgcolor="#be9e1d" style="padding-top:0px; padding-right:30px; padding-bottom:0px; padding-left:30px; font-family:Segoe UI, Helvetica Neue, Helvetica, Arial, sans-serif; font-size:27px; line-height:26px; color:#ffffff; font-weight:300; text-align:left;">
                   <img src="https://www.didx.net/wp-content/themes/didx_1.0v/images/logo.png">
                    </td>
                </tr>
                <tr>
                    <td height="2" style="padding-top:3px; font-size:2px; line-height:2px; border-bottom:1px #dddddd dotted;">&nbsp;</td>
                </tr>
            </table>
            <!-- End of title on the color background -->  
            
            <!-- Start of letter content -->
            <table class="row" width="600" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; text-align:left; border-spacing:0; max-width:100%;">
                <tr>
                    <td style="padding-top:25px; padding-left:30px; padding-right:30px; padding-bottom:20px; font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:15pt; color:#777777;">
                        Dear Sales,

                    </td>
                </tr>
                <tr>
                  <td style="padding-left:30px; padding-right:30px; padding-bottom:25px; font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:15pt; color:#777777;">
                        
Following request is placed by client for DID on our website DIDX.net
<br/><br/>

                  </td>
                </tr>
                <tr><td>Area : </td> <td>$ffArea</td></tr>
                <tr><td>NXX : </td> <td>$ffNXX</td></tr>
                <tr><td>Price : </td> <td>$ffPrice</td></tr>

                <tr><td>UID: </td>$UID<td></td></tr>
		<tr><td>Name : </td>$EmailCustomer[CFName]  $EmailCustomer[CLName]<td></td></tr>
		<tr><td>Company: </td>$EmailCustomer[CCompany] <td></td></tr>
                <tr><td>Quantity : </td> <td>$ffQty</td></tr>
                <tr><td>Channel : </td> <td>$ffChannel</td></tr>
                <tr><td>Comments : </td> <td>$ffComments</td></tr>
                   <tr>
                                                             

                                                               
                                <td class="quote" style="padding-right:30px; padding-bottom:1px; padding-left:30px; font-family:Georgia, Times New Roman, Times, serif; font-size:18px; line-height:20pt; color:#be9e1d;">
                                </td>
                            </tr>
             
            </table>
            
            
                        
            
 
            
            
            <table class="row" width="600" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; text-align:left; border-spacing:0; max-width:100%;">
                <tbody>
                
            
            </tbody>
            
            </table>
            

                  
            <!-- Start of footer -->
            <table class="row" width="600" bgcolor="#f4f4f4" align="center" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; text-align:left; border-spacing:0; max-width:100%;">
               
                <tr>
                    <td class="twoFromThree" width="65%" valign="top" style="border-top:1px #dddddd dotted;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; border-spacing:0;">
                            <tr>
                                <td class="authorInfo" style="padding-top:25px; padding-left:30px; padding-right:15px; padding-bottom:25px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:15pt; color:#777777;">
                                    <table class="authorPicture" width="110" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; border-spacing:0;">
                                        <tr>
                                            <td colspan="2" height="2" style="padding-top:2px; font-size:2px; line-height:2px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px; background-color:#ffffff; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;">
                                                <img alt="image" src="http://sandbox.didx.net/niha/images/images.png" height="80" width="70" border="0" vspace="0" hspace="0" style="display:block;" />
                                            </td>
                                            <td class="spacer2" width="2" style="padding-right:28px; font-size:2px; line-height:0px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="spacer3" colspan="2" height="2" style="font-size:2px; line-height:2px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                
                                    <span style="line-height:15pt; display:inline-block;     padding-top: 20px;">
                                        <img alt="Website:" src="http://sandbox.didx.net/niha/images/homeIcon.png" border="0" height="12" width="18" style="vertical-align:-1px;" /><a style="text-decoration:none; color:#ee7716;" href="#">www.didx.net</a> 
                                    </span>
                                    <br/>
                                    <span style="line-height:15pt; display:inline-block;">
                                        <img alt="Email:" src="http://sandbox.didx.net/niha/images/emailIcon.png" border="0" height="8" width="17" /><a style="text-decoration:none; color:#ee7716;" href="mailto:">sales@didx.net</a> 
                                    </span>
                                    <br/>
                                    <span style="line-height:15pt; display:inline-block;">
                                        <img alt="Phone:" src="http://sandbox.didx.net/niha/images/phoneIcon.png" border="0" height="10" width="15" /><span class="noLink" style="color:#ee7716;">+1-850-433-8555</span>
                                    </span>
                               <br/>
                                    <span style="line-height:15pt; display:inline-block;">
                                        <span class="noLink" style="color:#ee7716;">6005 Keating Road, Pensacola, Florida â 32504</span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="socialIconsContainer" width="35%" valign="bottom" style="border-top:1px #dddddd dotted;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; border-spacing:0; min-width:210px;">
                            <tr>
                                <td class="socialIcons2" style="padding-top:25px; padding-left:15px; padding-right:30px; padding-bottom:25px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:15pt; color:#777777; text-align:right;">
                                    <a href="https://www.facebook.com/DIDxGlobal"><img alt="Facebook" src="http://sandbox.didx.net/niha/images/facebookIcon.png" border="0" vspace="0" hspace="0" /></a>&nbsp;&nbsp;
                                    <a href="https://twitter.com/didxglobal"><img alt="Twitter" src="http://sandbox.didx.net/niha/images/twitterIcon.png" border="0" vspace="0" hspace="0" /></a>&nbsp;&nbsp;
                                    <a href="https://plus.google.com/111382738877008451324"><img alt="Google Plus" src="http://sandbox.didx.net/niha/images/googlePlusIcon.png" border="0" vspace="0" hspace="0" /></a>&nbsp;&nbsp;
                                    <a href="http://www.linkedin.com/company/didx"><img alt="Linkedin" src="http://sandbox.didx.net/niha/images/linkedinIcon.png" border="0" vspace="0" hspace="0" /></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:25px; padding-left:30px; padding-right:30px; padding-bottom:25px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:15pt; color:#777777; border-top:1px #dddddd dotted;">
                        
                    Copyright <img alt="Â©" src="http://sandbox.didx.net/niha/images/copyright.png" border="0" height="12" width="11" style="vertical-align:-1px;" /> 1999-2016 <a style="text-decoration:none; color:#ee7716;" href="http://www.didx.net">www.didx.net</a>, All rights reserved. 
                        <br/>
                        DIDx is the trademark of Super Technologies Inc. United States.</a>.
                    </td>
                </tr>
            </table>        
        </td>
    </tr>
    </table>
</body>
</html>
EDO;


// send email to client
$done = mail("sales@didx.net,kamalpanhwar@gmail.com,anas@supertec.com,sales@supertec.com,hm@supertec.com, ahsan@supertec.com", $Subject, $message, $headers);



   }



}
?>