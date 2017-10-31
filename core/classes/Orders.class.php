<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
class Orders
{
    var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Orders()
	{
		$this->ADb = new ADb();
	}	
public static function getDocumnet()
{         $counter=1;
          $UID=currentUser();
          $myADb = new ADb();
          userAuthenticationRequired();
           $strSQL = "select comments,date_format(date,'%d-%b-%Y'),ODID from orderdocs where OrderID=\"$UID\" order by date desc limit 0,5 ";
           
       
         $Result = $myADb->ExecuteQuery($strSQL);
       
         $Hash=array();
        
       
        $Hash["comments"] = substr($Result->fields[0],0,40);
        $Hash["Date"]	= $Result->fields[1];
        $Hash["ODID"]	= $Result->fields[2];
        $counter= $counter++;
        $Hash["counter"]=$counter;

        return $Hash;
        

}

public static function getVendorDetail()
{
	$counter=0;
          $UID=currentUser();
          $myADb = new ADb();
          userAuthenticationRequired();
	$strSQL = "select AcNumber,Descc from VendorAcNo where OID=\"$UID\" limit 0,5";
    $Result = $myADb->ExecuteQuery($strSQL);
   
    
      $Hash=array();
        
     $Hash["AccNo"]=$Result->fields[0];
     $Hash["description"]=$Result->fields[1];
     return $Hash;


}





    function getpopup()
    {
        $strSQL	= "select popup from sitesetting";

	    $Result	= $this->ADb->ExecuteQuery($strSQL);
        $Hash=array();
        $Hash["popup"]	= $Result->fields[0];   
        return $Hash;
    }

	function getOrdersInfoByUID2($pUID)
    {
        $strSQL	= "	Select
					O.OID,
					O.CustomerID,
					O.ShipmentID,
					O.ProductType,
					O.PlanType,
					O.PaymentType,
					O.Price,
					O.ShipmentCharges,
					O.SecurityDeposit,
					O.SetupCost,
					O.OtherCost,
					O.OrderStatus,
					O.Referral,
					O.PromoCode,
					O.OrderDate,
					O.IsReseller,
					O.IsDeleted,
					O.IsReturned,
					O.DateReturned,
					O.UID,
					O.VendorRating,
					O.CLimit,
					O.CusType,O.DIDRating, O.TalkTime, O.ChannelOrder, O.CreditLine,O.DIDDoc, 
					O.Confirmation, O.ReqPP, O.ReqPPAmount, O.MB,O.internalcomp, O.BackOrder, O.Premium, O.MyServer, 
					O.SMSURLPost, O.PayPal, O.FailOver, O.roundrobin, O.PayPalPay, O.ReferCode, O.DIDBillingC, O.DupliCC, O.VendorResell, O.GroupCall
				From
					orders O
					
				Where
					
					O.OID=\"$pUID\" ";

   $Result	= $this->ADb->ExecuteQuery($strSQL);
	    
        $Hash=array();
        
        $Hash["OID"]	= $Result->fields[0];
	    $Hash["CustomerID"]	= $Result->fields[1];
	    $Hash["ShipmentID"]	= $Result->fields[2];
	    $Hash["ProductType"]	= $Result->fields[3];
	    $Hash["PlanType"]	= $Result->fields[4];
	    $Hash["PaymentType"]	= $Result->fields[5];
	    $Hash["Price"]	= $Result->fields[6];
	    $Hash["ShipmentCharges"]	= $Result->fields[7];
	    $Hash["SecurityDeposit"]	= $Result->fields[8];
	    $Hash["SetupCost"]	= $Result->fields[9];
	    $Hash["OtherCost"]	= $Result->fields[10];
	    $Hash["OrderStatus"]	= $Result->fields[11];
	    $Hash["Referral"]		= $Result->fields[12];
	    $Hash["PromoCode"]	= $Result->fields[13];
	    $Hash["OrderDate"]	= $Result->fields[14];
	    $Hash["IsReseller"]	= $Result->fields[15];
	    $Hash["IsDeleted"]	= $Result->fields[16];
	    $Hash["IsReturned"]	= $Result->fields[17];
	    $Hash["DateReturned"]	= $Result->fields[18];
	    $Hash["UID"]		= $Result->fields[19];
	    $Hash["VendorRating"]		= $Result->fields[20];
	    $Hash["CLimit"]		= $Result->fields[21];
	    $Hash["CusType"]		= $Result->fields[22];
	    $Hash["DIDRating"]		= $Result->fields[23];
	    $Hash["TalkTime"]		= $Result->fields[24];
	    $Hash["ChannelOrder"]		= $Result->fields[25];
	    $Hash["CLine"]		= $Result->fields[26];
	    $Hash["ForceBuyer"]		= $Result->fields[27];
	    $Hash["Confirmation"]		= $Result->fields[28];
	    $Hash["ReqPP"]		= $Result->fields[29];
	    $Hash["ReqPPAmount"]		= $Result->fields[30];
	    $Hash["MoneyB"]		= $Result->fields[31];
	    $Hash["IntComp"]		= $Result->fields[32];
	    $Hash["BackOrder"]		= $Result->fields[33];
	    $Hash["Premium"]		= $Result->fields[34];
	    $Hash["MyServer"]		= $Result->fields[35];
	    $Hash["SMSURLPOST"]		= $Result->fields[36];
	    $Hash["PayPal"]		= $Result->fields[37];
	    $Hash["FailOver"]		= $Result->fields[38];
	    $Hash["RoundRobin"]		= $Result->fields[39];
	    $Hash["PayPalPay"]		= $Result->fields[40];
	    $Hash["ReferCode"]		= $Result->fields[41];
	    $Hash["BillCycle"]		= $Result->fields[42];
	    $Hash["DupliCC"]		= $Result->fields[43];
	    $Hash["VResell"]		= $Result->fields[44];
	    $Hash["GroupCall"]		= $Result->fields[45];
	    
        return $Hash;
    }	






    function getOrdersInfoByUID($pUID)
    {
    	$pUID = currentUser();
        $strSQL	= "	Select
					O.OID,
					O.CustomerID,
					O.ShipmentID,
					O.ProductType,
					O.PlanType,
					O.PaymentType,
					O.Price,
					O.ShipmentCharges,
					O.SecurityDeposit,
					O.SetupCost,
					O.OtherCost,
					O.OrderStatus,
					O.Referral,
					O.PromoCode,
					O.OrderDate,
					O.IsReseller,
					O.IsDeleted,
					O.IsReturned,
					O.DateReturned,
					O.UID,
					O.VendorRating,
					O.CLimit,
					O.Recording,
					O.RecShow,
					O.CusType,O.DIDRating, O.TalkTime, O.ChannelOrder, O.CreditLine,O.DIDDoc, 
					O.Confirmation, O.ReqPP, O.ReqPPAmount, O.MB,O.internalcomp, O.BackOrder, O.Premium, O.MyServer, 
					O.SMSURLPost, O.PayPal, O.FailOver, O.roundrobin, O.PayPalPay, O.ReferCode, O.DIDBillingC, O.DupliCC, O.VendorResell, O.GroupCall
				From
					orders O
					
				Where
					
					O.OID=\"$pUID\" ";
				
					

	    $Result	= $this->ADb->ExecuteQuery($strSQL);
	    
        $Hash=array();

        $Hash["OID"]	= $Result->fields[0];
		$Hash["CustomerID"]	= $Result->fields[1];
	    $Hash["ShipmentID"]	= $Result->fields[2];
	    $Hash["ProductType"]	= $Result->fields[3];
	    $Hash["PlanType"]	= $Result->fields[4];
	    $Hash["PaymentType"]	= $Result->fields[5];
	    $Hash["Price"]	= $Result->fields[6];
	    $Hash["ShipmentCharges"]	= $Result->fields[7];
	    $Hash["SecurityDeposit"]	= $Result->fields[8];
	    $Hash["SetupCost"]	= $Result->fields[9];
	    $Hash["OtherCost"]	= $Result->fields[10];
	    $Hash["OrderStatus"]	= $Result->fields[11];
	    $Hash["Referral"]		= $Result->fields[12];
	    $Hash["PromoCode"]	= $Result->fields[13];
	    $Hash["OrderDate"]	= $Result->fields[14];
	    $Hash["IsReseller"]	= $Result->fields[15];
	    $Hash["IsDeleted"]	= $Result->fields[16];
	    $Hash["IsReturned"]	= $Result->fields[17];
	    $Hash["DateReturned"]	= $Result->fields[18];
	    $Hash["UID"]		= $Result->fields[19];
	    $Hash["VendorRating"]		= $Result->fields[20];
	    $Hash["CLimit"]		= $Result->fields[21];
	    $Hash["Recording"]		= $Result->fields[22];
	    $Hash["RecShow"]		= $Result->fields[23];
        $Hash["CusType"]		= $Result->fields[24];
	    $Hash["DIDRating"]		= $Result->fields[25];
	    $Hash["TalkTime"]		= $Result->fields[26];
		$Hash["ChannelOrder"]		= $Result->fields[27];
	    $Hash["CLine"]		= $Result->fields[28];
	    $Hash["DIDDoc"]		= $Result->fields[29];
	    $Hash["Confirmation"]		= $Result->fields[30];
	    $Hash["ReqPP"]		= $Result->fields[31];
	    $Hash["ReqPPAmount"]		= $Result->fields[32];
	    $Hash["MoneyB"]		= $Result->fields[33];
	    $Hash["IntComp"]		= $Result->fields[34];
	    $Hash["BackOrder"]		= $Result->fields[35];
	    $Hash["Premium"]		= $Result->fields[36];
	    $Hash["MyServer"]		= $Result->fields[37];
	    $Hash["SMSURLPOST"]		= $Result->fields[38];
	    $Hash["PayPal"]		= $Result->fields[39];
	    $Hash["FailOver"]		= $Result->fields[40];
	    $Hash["RoundRobin"]		= $Result->fields[41];
	    $Hash["PayPalPay"]		= $Result->fields[42];
	    $Hash["ReferCode"]		= $Result->fields[43];
	    $Hash["BillCycle"]		= $Result->fields[44];
	    $Hash["DupliCC"]		= $Result->fields[45];
	    $Hash["VResell"]		= $Result->fields[46];
	    $Hash["GroupCall"]		= $Result->fields[47];
	
	  
        return $Hash;
    }
    
    function getOrdersInfo($pOID)
    {
        $strSQL	= "	Select
					OID,
					CustomerID,
					ShipmentID,
					ProductType,
					PlanType,
					PaymentType,
					Price,
					ShipmentCharges,
					SecurityDeposit,
					SetupCost,
					OtherCost,
					OrderStatus,
					Referral,
					PromoCode,
					OrderDate,
					IsReseller,
					IsDeleted,
					IsReturned,
					DateReturned,
					Choice1,
					Choice2,
					UID,
					VendorRating,
					TLimit,
					CLimit,
					Recording,
					RecShow,
					PayPhone,CusType,DIDRating,TalkTime, DIDDoc, Confirmation, ReqPP, ReqPPAmount, Failover, MB, Premium, 
					MyServer, PayPalPay,ReferOID, DIDBillingC, DupliCC, VendorResell, GroupCall
				From
					orders
				Where
					OID=\"$pOID\"";
	

	    $Result	= $this->ADb->ExecuteQuery($strSQL);
	    
        $Hash=array();
        
        $Hash["OID"]	= $Result->fields[0];
	    $Hash["CustomerID"]	= $Result->fields[1];
	    $Hash["ShipmentID"]	= $Result->fields[2];
	    $Hash["ProductType"]	= $Result->fields[3];
	    $Hash["PlanType"]	= $Result->fields[4];
	    $Hash["PaymentType"]	= $Result->fields[5];
	    $Hash["Price"]	= $Result->fields[6];
	    $Hash["ShipmentCharges"]	= $Result->fields[7];
	    $Hash["SecurityDeposit"]	= $Result->fields[8];
	    $Hash["SetupCost"]	= $Result->fields[9];
	    $Hash["OtherCost"]	= $Result->fields[10];
	    $Hash["OrderStatus"]	= $Result->fields[11];
	    $Hash["Referral"]		= $Result->fields[12];
	    $Hash["PromoCode"]	= $Result->fields[13];
	    $Hash["OrderDate"]	= $Result->fields[14];
	    $Hash["IsReseller"]	= $Result->fields[15];
	    $Hash["IsDeleted"]	= $Result->fields[16];
	    $Hash["IsReturned"]	= $Result->fields[17];
	    $Hash["DateReturned"]	= $Result->fields[18];
	    $Hash["Choice1"]		= $Result->fields[19];
	    $Hash["Choice2"]		= $Result->fields[20];
	    $Hash["UID"]		= $Result->fields[21];
	    $Hash["VendorRating"]		= $Result->fields[22];
	    $Hash["TLimit"]		= $Result->fields[23];
	    $Hash["CLimit"]		= $Result->fields[24];
	    $Hash["Recording"]		= $Result->fields[25];
	    $Hash["RecShow"]		= $Result->fields[26];
	    $Hash["PayPhone"]		= $Result->fields[27];
	    $Hash["CusType"]		= $Result->fields[28];
	    $Hash["DIDRating"]		= $Result->fields[29];
	    $Hash["TalkTime"]		= $Result->fields[30];
	    $Hash["ForceBuyer"]		= $Result->fields[31];
	    $Hash["Confirmation"]		= $Result->fields[32];
	    $Hash["ReqPP"]		= $Result->fields[33];
	    $Hash["ReqPPAmount"]		= $Result->fields[34];
	    $Hash["Failover"]		= $Result->fields[35];
	    $Hash["MoneyB"]		= $Result->fields[36];
	    $Hash["Premium"]		= $Result->fields[37];
	    $Hash["MyServer"]		= $Result->fields[38];
	    $Hash["PayPalPay"]		= $Result->fields[39];
	    $Hash["ReferOID"]		= $Result->fields[40];
	    $Hash["BillCycle"]		= $Result->fields[41];
	    $Hash["DupliCC"]		= $Result->fields[42];
	    $Hash["VResell"]		= $Result->fields[43];
	    $Hash["GroupCall"]		= $Result->fields[44];
	    
	    return $Hash;
    }
    function editOrders($pHash) 
    {
        $strSQL = "Update orders Set ProductType    = \"$pHash[ProductType]\",PlanType = \"$pHash[PlanType]\",PaymentType= \"$pHash[PaymentType]\",Price= \"$pHash[Price]\",ShipmentCharges    = $pHash[ShipmentCharges],
                    SecurityDeposit    = $pHash[SecurityDeposit],SetupCost    = $pHash[SetupCost],OtherCost    = $pHash[OtherCost],OrderStatus    = \"$pHash[OrderStatus]\",
                    Referral    = \"$pHash[Referral]\",PromoCode    = \"$pHash[PromoCode]\",OrderDate    = \"$pHash[OrderDate]\",IsReseller    = $pHash[IsReseller],
                    IsDeleted    = $pHash[IsDeleted],IsReturned    = $pHash[IsReturned],DateReturned    = \"$pHash[DateReturned]\",TLimit    = \"$pHash[TLimit]\",
                    CLimit    = \"$pHash[CLimit]\",CreditLine    = \"$pHash[CLine]\",VendorResell    = \"$pHash[VResell]\" Where OID = \"$pHash[OID]\"";
                    //echo $strSQL;
        return $this->ADb->ExecuteQuery($strSQL);
    }
    function addOrders($Hash)
    {              
    	
        $strSQL = "Insert into
                    orders
                    (
                    OID,
                    UID,
                    CustomerID,
                    ShipmentID,
                    ProductType,
                    PlanType,
                    PaymentType,
                    Price,
                    ShipmentCharges,
                    SecurityDeposit,
                    SetupCost,
                    OtherCost,
                    OrderStatus,
                    Referral,
                    PromoCode,
                    Choice1,
                    Choice2,
                    OrderDate,
                    IsReseller,
                    IsDeleted,
                    IsReturned,
                    DateReturned,
                    VendorRating,
                    CusType,Confirmation
                    )
                Values (
                        '".$Hash['OID']."',
                        '".$Hash['UID']."',
                        '".$Hash['CustomerID']."',
                        '".$Hash['ShipmentID']."',
                        '".$Hash['ProductType']."',
                        '".$Hash['PlanType']."',
                        '".$Hash['PaymentType']."',
                        '".$Hash['Price']."',
                        '".$Hash['ShipmentCharges']."',
                        '".$Hash['SecurityDeposit']."',
                        '".$Hash['SetupCost']."',
                        '".$Hash['OtherCost']."',
                        '".$Hash['OrderStatus']."',
                        '".$Hash['Referral']."',
                        '".$Hash['PromoCode']."',
                        '".$Hash['Choice1']."',
                        '".$Hash['Choice2']."',
                        sysdate(),
                        '".$Hash['IsReseller']."',
                        '".$Hash['IsDeleted']."',
                        '".$Hash['IsReturned']."',
                        '".$Hash['DateReturned']."',
                        '".$Hash['VendorRating']."',
                        '".$Hash['CustomerType']."', '".$Hash['Confirmation']."')";
 			return $this->ADb->ExecuteQuery($strSQL); 
    }


    function getOrdersHistory() {
	
	   $myADb=new ADb();
	   $UID=currentUser();
	
       $strSQL = " select id,oid,countryid,areaid,date_format(date,'%d-%b-%Y'),qty,status,Funds,VOID,SPID
						from BulkOrder where OID=\"$UID\" and SPID!='' and SPID is not null order by date";	
						
		$Result= $myADb->ExecuteQuery($strSQL);
		$nIndex=0;
		$htmlA="";
		while(!$Result->EOF){
			$nIndex++;
			$OID = 					$Result->fields[1];
			$CountryCode = 	$Result->fields[2];
			$AreaCode = 		$Result->fields[3];
			$Date = 				$Result->fields[4];
			$Qty = 					$Result->fields[5];
			$Status = 			$Result->fields[6];
			$Funds = 			$Result->fields[7];
			$VOID = 			$Result->fields[8];
			$SPID = 			$Result->fields[9];
			
			$strSQL = "select Comments from SpecialOffer where id=\"$SPID\" ";
			$ResultSP = $myADb->ExecuteQuery($strSQL);	
			$Detail = $ResultSP->fields[0];

			$Hash = $this->getCountryAndAreaCodeByID($AreaCode);
			
			$Country = $Hash['CountryCode'];
			$Area = $Hash['AreaCode']; 
				
			if($Funds==1) {
			
			$ChoiceLink = "Add Funds";	
			}else{
				$ChoiceLink = "";	
			}
			
			
			
				if($Status==1) {
					
					$StatusLink = " Pending ($ChoiceLink)";
				}else{
					$StatusLink = " Completed ";						
				}
			
			
			
			$htmlA .= "<tr> 
		                <td><DIV >$nIndex</DIV></td>
		                <td ><DIV >$Date</DIV></td>
		                <td><DIV >($Hash[CountryCode])  $Hash[Country] ($Hash[AreaCode])  $Hash[Area]</DIV></td>
		                <td><DIV >$Detail</DIV></td> 
		                <td ><DIV >$StatusLink</DIV></td>
		                </tr>";
			
			$Result->MoveNext();
		}
	
	    return $htmlA;
	}

public static function getOrdersHistory2()
    {

    	$myADb = new ADb();
        $UID= currentUser();
       

        $strSQL="SELECT b.id,b.oid,b.countryid,b.areaid,DATE_FORMAT(b.date,'%d-%b-%Y') 
                 AS DATE,b.qty,b.status,b.Funds,b.VOID,b.SPID,s.`Comments`,s.`id`,
                 coun.description AS countryname,coun.countrycode,areas.description 
                 AS Areaname,areas.StateCode FROM BulkOrder AS b 
                 LEFT JOIN SpecialOffer AS s ON b.SPID=s.id 
                 LEFT JOIN DIDCountries AS coun ON coun.id=b.countryid 
                 LEFT JOIN DIDArea AS areas ON areas.id=b.areaid
                 WHERE  b.OID=\"$UID\" AND  b.SPID!='' AND  b.SPID IS NOT NULL ORDER BY 
                 DATE";

				    
        $Result= $myADb->ExecuteQuery($strSQL);

        return $Result;
    }

    //Warisha
function getOrdersHistoryy() {
	
		$myADb=new ADb();
		$UID=currentUser();
	
		$strSQL = " select id,oid,countryid,areaid,date_format(date,'%d-%b-%Y'),qty,status,Funds,VOID
								from BulkOrder where OID=\"$UID\" and (spid='' or spid is null)  order by date";	
		#echo $strSQL;
		$Result= $myADb->ExecuteQuery($strSQL);
		    if($Result->EOF)
		    {
		           $htmlA .= "<tr colspan='16'> 
		                <td>&nbsp;</td>
		                <td>&nbsp;</td>	
		                <td>&nbsp;</td>	
		                <td><span>Record(s) not found</span></td>	
		                <td>&nbsp;</td>	
		                <td>&nbsp;</td>	
		                <td>&nbsp;</td>	



		                </tr>";
		    }	
		    $nIndex=0;
		    $bgcolor="grey" ;

				while(!$Result->EOF){
			$nIndex++;
			$OID = 					$Result->fields[1];
			$CountryCode = 	$Result->fields[2];
			$AreaCode = 		$Result->fields[3];
			$Date = 				$Result->fields[4];
			$Qty = 					$Result->fields[5];
			$Status = 			$Result->fields[6];
			$Funds = 			$Result->fields[7];
			$VOID = 			$Result->fields[8];
			
			$Hash = $this->getCountryAndAreaCodeforBulk($CountryCode,$AreaCode);
			
			$Country = $Hash['CountryCode'];
			$Area = $Hash['AreaCode']; 
				
			if($Funds==1) {
			
			$ChoiceLink = "Pending-Add Funds";	
			}else{
				$ChoiceLink = "";	
			}
			
			
			
				if($Status==1) {
					
					$StatusLink = " Pending";
				}else{
					$StatusLink = " Completed ";						
				}
			
			   if($bgcolor=="grey")
		       {
		        $bgcolor="";
		        }
		        else
		        {
		        $bgcolor="grey";
		        }
		    
			
			$htmlA .= "<tr class='$bgcolor'> 
		                <td><DIV align=center>$nIndex</DIV></td>
		                <td ><DIV align=center>$Date</DIV></td>
		                <td><DIV align=center>($Hash[CountryCode])  $Hash[Country]</DIV></td>
		                <td><DIV align=center>($Hash[AreaCode])  $Hash[Area]</DIV></td>
		                <td><DIV align=center>$VOID</DIV></td>
		                <td><DIV align=center>$Qty</DIV></td>
		                <td><DIV align=center>$StatusLink</DIV></td>
		                </tr>";
			
			$Result->MoveNext();
		}
			
			return $htmlA;
	
}

function getCountryAndAreaCodeforBulk($pCode,$pArea) {
	
	$myADb=new ADb();
	
	$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode from DIDCountries,DIDArea where
							DIDCountries.Id = \"$pCode\" and DIDArea.ID=\"$pArea\" and DIDArea.COuntryID=DIDCountries.ID";
							
	$Result= $myADb->ExecuteQuery($strSQL);
	
	$Hash;
	
	$Hash['Country'] = $Result->fields[0];
	$Hash['Area'] = $Result->fields[1];
	
		$Hash['CountryCode'] = $Result->fields[2];
	$Hash['AreaCode'] = $Result->fields[3];
	
	return $Hash;
	
}


    //Warisha

function getCountryAndAreaCodeByID($pArea) {
	
	$myADb=new ADb();
	
	if(substr($pArea,0,2) == '-1'){
		$CountryID = substr($pArea,3);
			
				$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode 
							from DIDCountries,DIDArea where  DIDCountries.ID=\"$CountryID\" and DIDArea.COuntryID=DIDCountries.ID";
							
					#		echo $strSQL;
							
							$Result= $myADb->ExecuteQuery($strSQL);
							$Hash;
	
							$Hash['Country'] = $Result->fields[0];
							$Hash['Area'] = "All Areas";
							$Hash['CountryCode'] = $Result->fields[2];
							$Hash['AreaCode'] = "";
							
							return $Hash;
							
		
		
	}else{
					
	
	$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode 
							from DIDCountries,DIDArea where  DIDArea.ID=\"$pArea\" and DIDArea.COuntryID=DIDCountries.ID";
								#echo $strSQL;
						}
							
	$Result= $myADb->ExecuteQuery($strSQL);
	
	$Hash;
	
	$Hash['Country'] = $Result->fields[0];
	$Hash['Area'] = $Result->fields[1];
	
	$Hash['CountryCode'] = $Result->fields[2];
	$Hash['AreaCode'] = $Result->fields[3];
	
	return $Hash;
	
}


   //  public static function GetSpecialOffer() 
   //  {
	
	  //  $myADb = new ADb();
   //     $UID= currentUser();
	
   //     $strSQL="SELECT s.id,s.AreaID,s.Prefix,s.VOID,s.Setup,s.Monthly,s.Qty,s.Amount,s.Cron,
   //          s.Comments,s.ShowNow,s.Commission,areas.StateCode,areas.description AS AreaName,
   //          coun.description AS CountryName ,coun.countrycode ,(Qty * Monthly) AS TotalMonthly,
   //          (Qty *Setup) AS TotalSetup,((Qty * Monthly)+(Qty *Setup))AS Total
			// FROM SpecialOffer AS s
   //          JOIN DIDArea   AS areas ON s.AreaID=areas.id 
   //          JOIN DIDCountries AS coun ON coun.id=areas.CountryID
   //          WHERE cron=1 AND shownow=1 ORDER BY DATE DESC";
         
   //    $Result= $myADb->ExecuteQuery($strSQL);	
   //    return $Result;
   // }

   public static function getCountryAndAreaCode($pArea) {
	
		$myADb=new ADb();
		
		if(substr($pArea,0,2) == '-1'){
			$CountryID = substr($pArea,3);
				
					$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode 
								from DIDCountries,DIDArea where  DIDCountries.ID=\"$CountryID\" and DIDArea.COuntryID=DIDCountries.ID";
								
							#	echo $strSQL;
								
								$Result= $myADb->ExecuteQuery($strSQL);
								$Hash;
		
								$Hash['Country'] = $Result->fields[0];
								$Hash['Area'] = "All Areas";
								$Hash['CountryCode'] = $Result->fields[2];
								$Hash['AreaCode'] = "";
								
								return $Hash;
								
			
			
		}else{
						
		
		$strSQL = " select DIDCountries.Description,DIDArea.Description as a,DIDCountries.CountryCode,DIDArea.StateCode 
								from DIDCountries,DIDArea where  DIDArea.ID=\"$pArea\" and DIDArea.COuntryID=DIDCountries.ID";
								
							}
								
		$Result= $myADb->ExecuteQuery($strSQL);
		
		$Hash;
		
		$Hash['Country'] = $Result->fields[0];
		$Hash['Area'] = $Result->fields[1];
		$Hash['CountryCode'] = $Result->fields[2];
		$Hash['AreaCode'] = $Result->fields[3];
		
		return $Hash;
		
	}



    public static function checkBulkOrder($Area,$Vendor) 
    {
	
	   $myADb = new ADb();
       $UID= currentUser();
       $strSQL="select * from BulkOrder where OID=\"$UID\" and AreaID=\"$Area\" and VOID=\"$Vendor\"   and status=1";
      $Result= $myADb->ExecuteQuery($strSQL);	
      return $Result;
   }

    public static function getOID($UID) 
    {
	
	   $myADb = new ADb();
       $strSQL="select OID from orders where UID='".$UID."'";
      $Result= $myADb->ExecuteQuery($strSQL);	
      return $Result->fields[0];
   }          


    public static function InsertBulkOrder($Country,$Area,$Vendor,$Qty) 
    {
	
	   $myADb = new ADb();
       $UID= currentUser();
       $strSQL="insert into BulkOrder(OID,CountryID,AreaID,VOID,Qty,Date)
                                     values(\"$UID\",\"$Country\",\"$Area\",\"$Vendor\",\"$Qty\",sysdate())";

      return ($Result= $myADb->ExecuteQuery($strSQL));	
   }


    public static function getAutoPay() 
    {
	   $myADb = new ADb();
       $UID= currentUser();
       $strSQL="select UID,AutoPay from EmailPref where uid=\"$UID\"";
       $ResultProfile= $myADb->ExecuteQuery($strSQL);	
       return ($ResultProfile->fields[1]);
   }

    public static function getRequestBillCSV() 
    {
       $myADb = new ADb();
       $UID= currentUser();
       $strSQL="select Status,date_format(Date,'%d-%b-%Y') from RequestBillCSV where OID=\"$UID\" and Type=0";
     #  echo $strSQL;
       $Result= $myADb->ExecuteQuery($strSQL); 
        $Hash=array();
        
        $Hash["Status"] = $Result->fields[0];
        $Hash["date"] = $Result->fields[1];
     
        return $Hash;
   }

   public static function GetBackOrderHistory()
   {
	   $myADb = new ADb();
	   $UID=currentUser();
	   $strSQL = " select id,oid,countrycode,areacode,
	                date_format(date,'%d-%b-%Y') as Date,quantity,status,Choice 
					from BackOrder where OID=\"$UID\" order by CountryCode,AreaCode";	
	   $Result= $myADb->ExecuteQuery($strSQL);	
       return $Result;

   }

   function getPayPalInfo()
	{
		$myADb = new ADb();
		$strSQL	= "SELECT paypalemailcheck,paypalfname,paypallname,paypalzipcode,paypalphone FROM paypal_settings";
		$this->dPrint("strSQL:$strSQL");	
		$Result	= $myADb->ExecuteQuery($strSQL);
		$data = array();
		$data['EmailCheck'] = $Result->fields[0];
		$data['FNameCheck'] = $Result->fields[1];
		$data['LNameCheck'] = $Result->fields[2];
		$data['ZipCodeCheck'] = $Result->fields[3];
		return $data;
	}	

	function isdeleteforfundstransfer($BOID)
	{
		$myADb = new ADb();
		$strSQL = "select isdeleted from orders where OID='".mysql_real_escape_string($BOID)."'";
        $Result = $myADb->ExecuteQuery($strSQL);
        $IsDeleted = $Result->fields[0];
        return $IsDeleted;

	}

	function updateorderfund($BOID)
	{
		$myADb = new ADb();
		$strSQL = "update orders set IsDeleted=0 where OID='".mysql_real_escape_string($BOID)."'";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
	}


	function ReqMinPayment($UID)
	{
		$myADb = new ADb();
		$strSQL	= "select date_format(concat(Year,'-',Month,'-01'),'%b-%Y') AS Period,amount,Minutes,
		  date_format(date,'%d-%b-%Y') AS P_Date,Status,ID from ReqMinPayment where oid=\"$UID\" order by date asc";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
	}
	
	// function PMCPayment($UID)
	// {
	// 	$myADb = new ADb();	
	// 	$strSQL = "select Payment,date_format(Date,\"%d-%b-%Y\"),PaymentTill,User,TotalMinutes,id from PMCPayment where oid=\"$UID\" ";
	// 	$Result	= $myADb->ExecuteQuery($strSQL);
	// 	return $Result;
	// }
	//
	function GetPaymentLogsForTalkTime($UID) {
	
	
	$myADb=new ADb();
	$strSQL = " select Payment,date_format(Date,\"%d-%b-%Y\"),PaymentTill,User,TotalMinutes,id from PMCPayment where oid=\"$UID\" ";
	#print "<br>\$strSQL : $strSQL ";						  
	$Result = $myADb->ExecuteQuery($strSQL);
	
    if($Result->EOF)
    {
        
            $htmlTable .= "  <tr> 
                            <td colspan='10'><div align=\"center\">Record(s) Not Found</div></td></tr>";
    }	
		while(!$Result->EOF){
			
			$Payment = $Result->fields[0];
			$Date = $Result->fields[1];
			$PaymentTill = $Result->fields[2];
			$TotalMinutes = $Result->fields[4]; 	
			$ID = $Result->fields[5]; 	
			$Running = $Running + $Payment;
			
			#print "\$ID: $ID";
	
	$Date = "$Date";
	$Payment = "$Payment";
	$PaymentTill = "$PaymentTill";
	$TotalMinutes = "$TotalMinutes";
	$Payment = "$Payment</a>";
	#$Running = "<a href=\"javascript:void(0)\" title='Click to view payment breakup'>$Running</a>";
	#$Running = "<a href=\"javascript:void(0)\" title='Click to view payment breakup'>$Running</a>";
	
#	$Date = "$Date";
#	$Payment = "$Payment";
#	$PaymentTill = "$PaymentTill";
#	$TotalMinutes = "$TotalMinutes";
#	$Payment = "$Payment";
#	#$Running = "$Running";
	
	$nIndex++;
	
			$htmlTable .= "  <tr> 
                            <td><div align=center>$nIndex</div></td>
                            <td><div align=center>$Date</div></td>
                            <td><div align=center>$PaymentTill</div></td>
                            <td><div align=center>$TotalMinutes</div></td>
                            <td><div align=center>$Payment</div></td>
                            <td><div align=center>$Running</div></td>
                            </tr>";
			$Result->MoveNext();
			
		}
        
		
			$htmlTable .= "  <tr>  
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>";
                          
         $htmlTable .= "<tr> 
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>Total</td>
         <td><div align=center>\$ $Running</div></td>
         <td>&nbsp;</td>
         </tr>";
	
	#print "\$htmlTable: $htmlTable";
	return $htmlTable;
	
}
	//


	function ReqSMSPayments($UID,$Month,$Year,$status)
	{ //feteh payments by month year

		$myADb = new ADb();
	    $strSQL	= "select Amount,Minutes,date_format('$Year-$Month-01','%b-%Y'),ID from ReqMinPayment where OID='$UID' and Month=\"$Month\"  and Year=\"$Year\" and 
	        status=\"$status\" ";
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	function SmsTranscition($UID,$TTPV)
	{ //feteh payments by month year
		$myADb = new ADb();
		$strSQL	= "select * from transaction where OID='$UID' and Type=\"$TTPV\" 
		and isdeleted=0";
		$Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}


	function InsertSMSPayments($UID,$Month,$Year)
	{ //feteh payments by month year
		$myADb = new ADb();
		$strSQL	= "insert into ReqSMSPayment(OID,Month,Year)values(\"$UID\", \"$Month\", \"$Year\")";
		$Result	= $myADb->ExecuteQuery($strSQL);
	}

	function getAllBackOrders($UID)
	{ //feteh payments by month year
		$myADb = new ADb();
        $strSQL = " select id,
        			oid,
        			countrycode,
        			areacode,
					date,
					quantity,
					status,
					Vendor,
					NXX,
					BackAdminID,
					Amount,
					date_format(date,'%d-%b-%Y')		
					from BackOrder  where 
					Vendor=\"$UID\" 
					order by CountryCode,AreaCode ";		
					$Result	= $myADb->ExecuteQuery($strSQL);
					return $Result;
	}


	function GetCurDate($Month,$Year){

		 $ffMonth = date('m');//get current month
		 $ffYear  =  date('Y');//get current year
		 if($ffMonth==$Month && $Year==$ffYear){
		 return -1;

		}
	
}

	function GetvendorRating($ffOID)
	{
		
		$myADb=new ADb();
		
		$strSQL="select OID,VendorRating from orders where OID=$ffOID ";
		
            $Result = $myADb->ExecuteQuery($strSQL);
            $VendorRating    =    $Result->fields[1];
            return $VendorRating;

	}

	function Insertforbuyoffer($UID,$CountryID,$AreaID,$VOID,$Qty,$SPID,$TrID){
		$myADb=new ADb();
		$strSQL = "insert into BulkOrder(OID,CountryID,AreaID,VOID,Qty,Date,SPID,TRID)values
				(\"$UID\",\"$CountryID\",\"$AreaID\",\"$VOID\",\"$Qty\",sysdate(),\"$SPID\",\"$TrID\" )";
		$Result= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function GetDocsRights($pVOID) {
	
		 $myADb = new ADb();
	
		$strSQL = "select DocsRights from orders where OID=\"$pVOID\" ";
	 # print " $strSQL";
		$ResultRight	= $myADb->ExecuteQuery($strSQL);
		
		$Rights = $ResultRight->fields[0];
		
		$SeeRight = substr($Rights,0,1);
		
	#	print "<br>\$SeeRight: $SeeRight";
	
		if($SeeRight!=1 ) {
			
			return 0;
		}else{
			
			return 1;
		}
	}

	function GetVendorResell($VOID){
	
		// global $myOrder;
		
		$Orders = $this->getOrdersInfoByUID($VOID);
		
		if($Orders['VResell']==1)
				return "YES";
		else
				return "NO";
		
	}

  
  
}
?> 