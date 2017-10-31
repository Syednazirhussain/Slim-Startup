<?php
class OffertoSale
{

	function getAllowDIDSAdd($OID) {

			$myADb=new ADb();
			$strSQL =" select AddDID from EmailPref where UID=\"$OID\" ";									
			$Result	= $myADb->ExecuteQuery($strSQL);		
	
			return $Result->fields[0];
    }

	function MyAddTotaldids($UID)
	{
		$myADb=new ADb();
		$strSQL = "select count(*) from DIDS where OID=\"$UID\" ";
		$ResultDIDS = $myADb->ExecuteQuery($strSQL);
        return $ResultDIDS->fields[0];
	}


function GetAddRights($RightsCode) {
	
	
	$myADb=new ADb();
	$UID=currentUser();	
	$strSQL = " select CodeVal from GeneralRights where OID=\"$UID\" and Code=\"$RightsCode\" ";
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result->fields[0];
	
}	

function CountDIDs($UID)
{
	$myADb=new ADb();
	$strSQL="select count(DIDNumber) from DIDS where OID=\"$UID\" "; 
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result->fields[0];					
}

function getRatecenter($Number){
	
	$myADb=new ADb();
	
	$NPA = substr($Number,1,3);
	$NXX = substr($Number,4,3);
	
	$strSQL = "select Ratecenter from USAreas where NPA=\"$NPA\" and NXX=\"$NXX\" ";
   
	$Result	= $myADb->ExecuteQuery($strSQL);		
	
	return $Result->fields[0];
	
}


function OffertoSaledata($ffMultipleNumber1){

	$myADb=new ADb();
	$strSQL = "select CountryID,concat(CountryCode,StateCode) as completecode,CountryCode,StateCode,DIDCountries.Description as a,
			DIDArea.description as b, length(CountryCode), length(Statecode), length(concat(countrycode,stateCode)) as codelength, DIDArea.ID as MyAreaID
			from DIDCountries,DIDArea where
			 CountryID=DIDCountries.ID and CountryCode like concat(substring(\"$ffMultipleNumber1\",1,1),'%')
			and substring(\"$ffMultipleNumber1\",1,length(concat(countrycode,stateCode)))= concat(CountryCode,StateCode)
			order by codelength desc limit 0,1";
	$Result	= $myADb->ExecuteQuery($strSQL);		
	return $Result;
}

function Offer($ffMultipleNumber1)
{
	$myADb=new ADb();
	$strSQL = "select CountryID,concat(CountryCode,StateCode) as completecode,CountryCode,StateCode,DIDCountries.Description as a,DIDArea.description as b, length(CountryCode), length(Statecode), length(concat(countrycode,stateCode)) as codelength, DIDArea.ID as MyAreaID from DIDCountries,DIDArea where
		CountryID=DIDCountries.ID and CountryCode like concat(substring(\"$ffMultipleNumber1\",1,1),'%')
		and substring(\"$ffMultipleNumber1\",1,length(concat(countrycode)))= concat(CountryCode)
		and StateCode='-99' order by codelength desc limit 0,1";
	$Result	= $myADb->ExecuteQuery($strSQL);		
	return $Result;
}

function OffertoSaleSingleNumber($ffSingleNumber)
{
	$myADb=new ADb();
	$strSQL = "select CountryID,concat(CountryCode,StateCode) as completecode,CountryCode,StateCode,DIDCountries.Description as a,
			DIDArea.description as b, length(CountryCode), length(Statecode), length(concat(countrycode,stateCode)) as codelength, DIDArea.ID as MyAreaID
			from DIDCountries,DIDArea where
			 CountryID=DIDCountries.ID and CountryCode like concat(substring(\"$ffSingleNumber\",1,1),'%')
			and substring(\"$ffSingleNumber\",1,length(concat(countrycode,stateCode)))= concat(CountryCode,StateCode)
			order by codelength desc limit 0,1";
	$Result	= $myADb->ExecuteQuery($strSQL);		
	return $Result;
}

function OffertoSaleSingle($ffSingleNumber)
{
	$myADb=new ADb();
	$strSQL = "select CountryID,concat(CountryCode,StateCode) as completecode,CountryCode,StateCode,DIDCountries.Description as a,DIDArea.description as b, length(CountryCode), length(Statecode), length(concat(countrycode,stateCode)) as codelength, DIDArea.ID as MyAreaID from DIDCountries,DIDArea where
		CountryID=DIDCountries.ID and CountryCode like concat(substring(\"$ffSingleNumber\",1,1),'%')
		and substring(\"$ffSingleNumber\",1,length(concat(countrycode)))= concat(CountryCode) and StateCode='-99'
		order by codelength desc limit 0,1";
	$Result	= $myADb->ExecuteQuery($strSQL);		
	return $Result;

}

function MinMonthly($ffArea)
{
	$myADb=new ADb();
	$strSQL="select min(MonthlyCharges) from DIDS where AreaId=\"$ffArea\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
    return $Result;
	
}

function GetIfThisisUSA($pAreaID) {
	
    $myADb=new ADb();
	
	$strSQL = "select IsUSA from DIDCountries,DIDArea where DIDArea.ID=\"$pAreaID\"  and DIDArea.CountryID=DIDCountries.Id";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	$IsUSA = $Result->fields[0];
	
	return $IsUSA;
	
	
}

function Qty($ffArea,$ffMonthlyCharges)
{
	$myADb=new ADb();
    $strSQL = "select OID,monthlycharges  from DIDS where  AreaId=\"$ffArea\" 
						 and monthlycharges=\"$ffMonthlyCharges\" group by OID order by monthlycharges   limit 1,10";
    $ResultQty = $myADb->ExecuteQuery($strSQL);
    return $ResultQty;
}


function GetCountryInformation($pNumber,$ffArea) {
	
	$myADb=new ADb();
	
	
	$USStateName;
	$RateCenter;
	$MYUSNXX;
	
	
	if(substr($pNumber,0,1)=='1'){
		
		$strSQL = "select RateCenter,State, NXX from USAreas where NPA=substring(\"$pNumber\",2,3)
		and NXX=substring(\"$pNumber\",5,3)";
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$RateCenter = $Result->fields[0];
		$USStateName = $Result->fields[1];
		$MYUSNXX = $Result->fields[2];
		
	}
	
	$strSQL = "select DIDCountries.Description as a,DIDArea.Description,DIDCountries.countrycode,DIDArea.StateCode,DIDCountries.ID
								from DIDCountries,DIDArea where DIDArea.CountryID=DIDCountries.ID 
								and DIDArea.Id=\"$ffArea\" ";
	
	$Result = $myADb->ExecuteQuery($strSQL);
	
	$CountryName = $Result->fields[0];
	$AreaName= $Result->fields[1];
	$CountryCode = $Result->fields[2];
	$AreaCode = $Result->fields[3];
	$MyCountryID = $Result->fields[4];
	
	if($AreaCode=='-99'){
		$AreaCode="";
	}
	
	$Hash;
	
	$Hash['CountryName'] = $CountryName;
	$Hash['StateName'] = $AreaName;
	$Hash['CountryCode'] = $CountryCode;
	$Hash['AreaCode'] = $AreaCode;
	$Hash['RateCenter'] = $RateCenter;
	$Hash['USStateName'] = $USStateName;
	$Hash['MyCountryID'] = $MyCountryID;
	$Hash['MYNXX'] = $MYUSNXX;
	
	return $Hash;
	
	
}

function GetDIDNumbertemp($tempNumber)
{
	$myADb=new ADb();
	$strSQL="select * from DIDS where didnumber=\"$tempNumber\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
    return $Result;
}

function GetDIDNumberSingle($ffSingleNumber)
{
	$myADb=new ADb();
	$strSQL="select * from DIDS where didnumber=\"$ffSingleNumber\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
    return $Result;
}

function AddNumber($SingleNumber,$ffSingleNumber, $ffMultipleNumber1, $ffMultipleNumber2 , $ffSetupCost	, $ffMonthlyCharges	, $ffPerMinuteCharges, $ffCountry,$ffArea,$ffCodec, $ffNetwork, $ffSoldCard, $ffAfterFree, $ffChannels,$ffFreeMinutes , $ffTenDID,$ffFiftyDID, $ffHundDID, $ffTwo50DID, $ffFiveHundDID, $ffThousandDID, $ffTenKDID, $ffGoWithThis , 
	$CallerID, $CallerIDName,$T38, $Voice, $SMS, $G729 , $G723	, $G711, $GroupCall, 
	$ffCallBackTrigger , $UID , $ffMonthlyFreeSMS , $ffPersmschargeafterfreesms ,
    $ffMonthlyFreeTrigger , $ffPertrigerchargeafterfreetigger , $ffCallBackTrigger, 
    $ffdtmf, $OurSetupCost,$OurMonthlyCharges,$AfterFreeMinutes,$CompleteCountryCode,
    $CompleteCityCode,$CompleteCityName,$CompleteCountryName, $USStateName,$RateCenter,
    $MyCountryID,$MYNXX,$VendorRating)
       {
	      $myADb=new ADb();
	      $myGeneral=new General();
	      $UID=currentUser();

	 	  if($SMS==1)
         {
         	
        	
            if($ffMonthlyFreeSMS !=0 || $ffPersmschargeafterfreesms != 0)
            {

                $strSQLCheck = "Select * from SMSRateMnager where oid='$UID' and AreaID='$ffArea'";
                $ResultCheck = $myADb->ExecuteQuery($strSQLCheck);
                if($ResultCheck->EOF)
                {

                    $strSQL = "insert INTO SMSRateMnager (OID,AreaID,FreeSMS,PerSMSAfterFree) VALUES ('$UID', '$ffArea', '$ffMonthlyFreeSMS', '$ffPersmschargeafterfreesms')";

                    $myADb->ExecuteQuery($strSQL);
                }
                
            }
        }
	  

		$strSQLMax = "select max(Id) from DIDS";

		$ResultMax = $myADb->ExecuteQuery($strSQLMax);                   
		$id = $ResultMax->fields[0] + 1;

			if($ffChannels=='' || $ffChannels==0) {
			$ffChannels=3;
		}
		
		$strSQLSingle = "insert into DIDS 
				( Id,DIDNumber, AreaID, Status, SetupCost, MonthlyCharges, PerMinuteCharges, OID, OfferDate,
				OurSetupCost,OurMonthlyCharges,OurPerMinuteCharges,UnderCheck,Type,iCodec,iNetwork,iCallCard,FreeMin,iChannel,
				ip10,ip50,ip100,ip250,ip500,ip1K,ip10K,SpSetup,SpMonthly,SPPerMin,VendorRating,CountryCD,AreaCD,City,CountryN,StateName,RCenter,MyCountryID,NXX,
				CallerID,CallerIDName,iT38,G729,G723,G711,Voice,SMSEnable,groupVendor,FreeSMS,SMSCharge,TriggerMin,TriggerRate,CallBack,DTMF)
				values
				( \"$id\",\"$SingleNumber\", \"$ffArea\", 
				\"0\", \"$ffSetupCost\", \"$ffMonthlyCharges\", \"$ffPerMinuteCharges\",\"$UID\",sysdate(),
				\"$OurSetupCost\",\"$OurMonthlyCharges\",\"$AfterFreeMinutes\",\"1\",\"6\",
				\"$ffCodec\",\"$ffNetwork\",\"$ffSoldCard\",\"$ffFreeMinutes\",\"$ffChannels\",
				\"$ffTenDID\",\"$ffFiftyDID\",\"$ffHundDID\",\"$ffTwo50DID\",\"$ffFiveHundDID\",\"$ffThousandDID\",\"$ffTenKDID\",
				\"$OurSetupCost\",\"$OurMonthlyCharges\",\"$AfterFreeMinutes\",\"$VendorRating\",
				\"$CompleteCountryCode\",
				\"$CompleteCityCode\",
				\"$CompleteCityName\",
				\"$CompleteCountryName\",\"$USStateName\",\"$RateCenter\",\"$MyCountryID\", \"$MYNXX\",
				\"$CallerID\", \"$CallerIDName\", \"$T38\", \"$G729\", \"$G723\", \"$G711\",\"$Voice\",\"$SMS\",\"$GroupCall\",\"$ffMonthlyFreeSMS\",
				 \"$ffPersmschargeafterfreesms\",\"$ffMonthlyFreeTrigger\",\"$ffPertrigerchargeafterfreetigger\",\"$ffCallBackTrigger\",\"$ffdtmf\"
				)
				" ;
				
	$ResultSingle = $myADb->ExecuteQuery($strSQLSingle);
    
    $myGeneral->RecordLoggedClient($UID,161,$UID,"","$SingleNumber","","");
    $myGeneral->RecordBuy($SingleNumber,$UID,"Offered","","AddDID","Web");	

       }



    function GetDocsRights($pVOID) {
	
	    $myADb=new ADb();
	
		$strSQL = "SELECT DocsRights from orders where OID=\"$pVOID\" ";
		$ResultRight	= $myADb->ExecuteQuery($strSQL);
		
		$Rights = $ResultRight->fields[0];
		
		$SeeRight = substr($Rights,0,1);
		
	
	
		if($SeeRight!=1 ) {
			
			return 0;
		}else{
			
			return 1;
		}
    }


   function GetCusDocsKeyID($DID,$UID) {
	
	$myADb=new ADb();
	
	$strSQL = "select KeyID from CusDocs where did='$DID' and VOID='$UID'";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
	
   }
    
   function GetDIDSList($AreaID,$Criteria,$DIDNumber,$Sort) {

	 $myADb=new ADb();
	 $UID=currentUser();
	 $Temp;

	if($Sort==1)
			$SortBy = "  DIDNumber";
	
	if($Sort==2)
			$SortBy = "  CountryN";
			
	if($Sort==3)
			$SortBy = " City";
			
	if($Sort==4)
			$SortBy = "  SetupCost";				
	
	if($Sort==5)
			$SortBy = "  MonthlyCharges";				
			
	if($Sort==6)
			$SortBy = "  DIDS.Status";						

	$WhereClause="";

	if($AreaID!="")
		$WhereClause .= " and DIDS.AreaID=\"$AreaID\" ";
	
	if($DIDNumber!="")
			$WhereClause .= " and DIDNumber like '$DIDNumber%'";
	
	if($Criteria==2)		
			$WhereClause .= " and DIDS.status=0 ";
			
	if($Criteria==3)		
			$WhereClause .= " and DIDS.status=2 ";		
	#	echo "<br>\$Criteria: $Criteria";

			$Docrights = $this->GetDocsRights($UID);
			// $WhereClause;
			// exit;
			
	if($Criteria!=4){
		
		$strSQL = "select count(*)
								from DIDS
								where 
								DIDS.OID=\"$UID\" $WhereClause ";

								#echo "<br>\$strSQL: $strSQL " . __LINE__;

		$ResultCount	= $myADb->ExecuteQuery($strSQL);
		

		$TotalCount = $ResultCount->fields[0];
		
		
		$strSQL = "select DIDNumber,CountryN as 		a,
								City,SetupCost,MonthlyCharges,
								PerMinuteCharges,Status,DIDS.Id,CountryCD,
								AreaCD,DIDS.CheckStatus,FreeMin,HaveDocs
								from DIDS
								where 
								
								DIDS.OID=$UID $WhereClause 
								order by $SortBy ";
		//echo "<br>\$strSQL: $strSQL " . __LINE__;
		$Result	= $myADb->ExecuteQuery($strSQL);
	
		
	}else if($Criteria==4){
		
		 $strSQL	= "select count(*)
								from DIDS,CusDocs
								where 
								DIDS.Status=2 and CusDocs.DID=DIDNumber and  CusDocs.Status=1 and
								DIDS.OID=\"$UID\"   $WhereClause
									 ";     
			#echo "<br>\$strSQL: $strSQL " . __LINE__;						 
	 $ResultCount	= $myADb->ExecuteQuery($strSQL);
	 $TotalCount = $ResultCount->fields[0];
		
		
		
			$strSQL = "select DIDNumber,CountryN as a,
								City,SetupCost,MonthlyCharges,
								PerMinuteCharges,DIDS.Status,DIDS.Id,CountryCD,
								AreaCD,DIDS.CheckStatus,FreeMin,HaveDocs
								from DIDS,CusDocs
								where CusDocs.DID=DIDNumber and 
								DIDS.Status=2 and CusDocs.Status=1 and
								DIDS.OID=\"$UID\"  $WhereClause
								order by $SortBy ";

	#	echo "<br>\$strSQL: $strSQL " . __LINE__;
		$Result	= $myADb->ExecuteQuery($strSQL);
	}

	$nIndex = "0";

    
	
	 $Temp .= "<br><form name='form1' id='form1' method='POST' action='RemoveAllDIDsConfirm'>
<div class=\"table table-responsive\">
	 <table class=\"table table-striped\">
                <tr class=\"grey\"> 
                <td><div align='left'><strong>#</strong></div></td>
                <td><div align='center'><b><input type=checkbox name=\"checkAllAuto\" id=\"checkAllAuto\" onclick=\"selection();\"></b></div></td>
                <td><div align='left'><strong><a href=\"#\" OnClick=\"MyDIDS_GetDIDList(1);\">DID Number</a></strong></div></td>
                <td><div align='center'><strong><a href=\"#\" OnClick=\"MyDIDS_GetDIDList(2);\">Country</a></strong></div></td>
                <td><div align='center'><strong><a href=\"#\" OnClick=\"MyDIDS_GetDIDList(3);\">Area</a></strong></div></td>
                <td><div align='center'><strong><a href=\"#\" OnClick=\"MyDIDS_GetDIDList(4);\">Setup ($)</a></strong></div></td>
                <td><div align='center'><strong><a  href=\"#\" OnClick=\"MyDIDS_GetDIDList(5);\">Monthly Charges ($)</strong></div></td>
                <td><div align='center'><strong>Free Minutes</strong></div></td>
                <td><div align='center'><strong>Per Min. After<br>Free Min.($)</strong></div></td>
                <td><div align='left'><strong><a href=\"#\" OnClick=\"MyDIDS_GetDIDList(6);\">Status</a></strong></div></td>
                <td><div align='left'><strong>Rating</strong></div></td>
                <td><div align='left'><strong>Action</strong></div></td>
                <td style=\"
    border-bottom: 1px solid #dddddd;
\"><div align=center><strong>Customer Documents</strong></div></td></tr>";
   
if($Result->EOF)
    {
        
            $Temp .= "  <tr> 
                            <td colspan='10'><div align=\"center\">Record(s) Not Found</div></td></tr>";
    }	
   
	while(!$Result->EOF) //@todo
	{  

			 $nIndex++;	 


			 $DIDNumber = $Result->fields[0];	

			 $lenCountry = strlen($Result->fields[8]);
			 $lenArea = strlen($Result->fields[9]);
			 $AreaPosition = $lenArea + $lenCountry +1;
			
			 $tempor = substr($DIDNumber,0,$lenCountry) . "-" . 
			 			substr($DIDNumber,$lenCountry,$lenArea) . "-" .
			 			substr($DIDNumber,$lenCountry+$lenArea,strlen($DIDNumber)-($lenCountry+$lenArea));
			 $didTemp = $Result->fields[0];
		 	 $didRating = $Result->fields[10];
			
			if($didRating == 0) 
				{ $didRating = "Test Failed"; }

			 $Exist = $Result->fields[12];

			
			if($Exist!="0"){
				
				if($Docrights==1){
					$KeyIDDocs =$this->GetCusDocsKeyID($DIDNumber,$UID);
				$UploadLink="<a href=\"#\" onClick=\"window.open('/ShowDocs2Vendor?id=$KeyIDDocs&void=$UID','window','width=500,height=600')\">Show</a> ";
				}else{
				$UploadLink="&nbsp;";
				}
			}else{

			$UploadLink="none";
		}

		
		$DIDStatus = $Result->fields[6];

		
		if($DIDStatus==1) {
			
			$Status = "Reserved";
			$CallLogs="&nbsp;";
			// $RemoveLink="";
						
		}
		if($DIDStatus==2) {
			
				$Status = "Sold";
			
			// $RemoveLink = "<a href='/RemoveDIDConfirm?op=".$Result->fields[7]."'>Remove </a>";		
			
		      $CallLogs = "<a href='/MyDIDSLogs?PDids=$didTemp' targe=_blank>CallLogs</a>";
			$UploadLink="";            
						
		}


		if($DIDStatus==0) {
			
		
			$Status = "Available";
			$CallLogs="&nbsp;"; 
			$RemoveLink = "<a href='/RemoveDIDConfirm?op=".$Result->fields[7]."'>Remove </a>";	
			// $RemoveLink="";
		#	$UploadLine = "<td align=center><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$UploadLink</td>";	
						
		}
		
		
		

						$Temp .="                 
		                <tr>
				 		<td align=center><div align=center>$nIndex</DIV></td>
                        <td><input type=checkbox value='$DIDNumber' name='DIDID[]'></td>
		                <td align=center><div align=center>
		                 <a class='autoTooltip' title='Click to view DID Info' href=/CDIDInfo?did=".$Result->fields[0].">$tempor</A></DIV></td>
		                  <td><div align=center>".$Result->fields[1]."</DIV></td>
		                  <td><div align=center>".$Result->fields[2]."</DIV></td>
		                  <td align=center><div align=center>".$Result->fields[3]."</DIV></td>
		                  <td align=center><div align=center>".$Result->fields[4]."</DIV></td>
		                  <td align=center><div align=center>".$Result->fields[11]."</DIV></td>
		                  <td align=center><div align=center>".$Result->fields[5]."</DIV></td>
		                  <td align=center><div align=center>$Status</DIV></td>
		                  <td align=center><div align=center>$didRating</DIV></td>
		                  <td align=center><div align=center>$RemoveLink<br>
		                  <center><a href='/TestSingleDID?OID=$UID&DID=$didTemp' targe=_blank>Test</a></center>
		                  </DIV>
		                  </td>
		                  
		                   <td align=center><div align=center>$UploadLink</div></td>
		                </tr>
		                
		        ";
		
		
	          
	             
       $Result->MoveNext();
	 }
	
		$Temp .="</table><br/><input class='btn btn-default' type='submit' id='deletebtn' name='deletebtn' Value='Delete Selected'></form><br>";
		

	return $Temp ;
}


function GetMyOfferedCountryList() {
	
	$myADb=new ADB();
	$UID=currentUser();
	
	
	$strSQL = "select MyCountryID,CountryCD,CountryN from DIDS where OID=\"$UID\" group by MyCountryID ";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	$Count = $Result->RecordCount();
	

	
	$html="";
	
	while(!$Result->EOF){
		
		$CountryID = $Result->fields[0];
		$CountryCode = $Result->fields[1];
		$CountryName = $Result->fields[2];
		
		$html .= "<option value=\"$CountryID\">$CountryCode - $CountryName</option>";
		
		$Result->MoveNext();
	}
	

		#$html .= "<option value=\"-1\" >-- Select Country --</option>";
		$html .= "<option value=\"-2\" selected >All Countries</option>";
	
	
	return $html;
	
	
}

//
//


function GetDataforRemove($ffDIDID)
{
	$myADb=new ADb();
	$UID=currentUser();
	$strSQL ="select DIDNumber,Status from DIDS	 where Id='$ffDIDID' and OID=\"$UID\" ";					
	$ResultDIDNumber = $myADb->ExecuteQuery($strSQL);
	return $ResultDIDNumber;
}


function GetRemoveDID($ffDIDID)
{
	$myADb=new ADb();
	$UID=currentUser();
	$strSQL = "select DIDNumber,OfferDate,CheckStatus,MonthlyCharges,
				SetupCost,curdate() from DIDS where Id=\"$ffDIDID\" and status=0 and OID=\"$UID\"  ";

	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;
}

function InertdelDID($DIDNumber,$UID,$OfferDate,$Tested,$UID,$Monthly,$Setup,$ffDIDID)
{
	$myADb=new ADb();
	$UID=currentUser();
	$myGeneral=new General();
	$strSQLInsert = "insert into RemovedDIDS
							(DIDNumber,OID,RemovalDate,OfferDate,Tested,Admin,Monthly,Setup)
							values
							(\"$DIDNumber\",\"$UID\",sysdate(),\"$OfferDate\",$Tested,\"$UID\",$Monthly,$Setup)";
                            $ResultInsert = $myADb->ExecuteQuery($strSQLInsert);
                            
                            $myGeneral->RecordLoggedClient($UID,160,$UID,"","$DIDNumber","","");
                            $myGeneral->RecordBuy($DIDNumber,$UID,"ClientDelete",$OfferDate,"Delete","WebUI-SingleDeletedVendor-$UID");
		
		
		$strSQL = "DELETE FROM DIDS WHERE Id = \"$ffDIDID\" and OID=\"$UID\" ";
		// echo "\$strSQL: $strSQL";
		// exit;
		$ResultInsert = $myADb->ExecuteQuery($strSQL);
		return $ResultInsert;

    }

		function getTestFailedMSG($pDID,$pOID) {
			
			
			
		$html ="

		The DID Test Failed.

		This is a very simple test, we call the number that you are offering, and it must be sent to us 
		over IP in the following format.

		You must send the call in e164 format, IE for this number $pDID you should send the call to 
		$pDID\@sip.didx.net or our ip 

		$pDID\@67.15.180.14

		The Call should be in SIP

		If you are sending in IAX2 format send it to

		guest\@iax.didx.net/$pDID

		or

		guest\@67.15.180.14/$pDID<br><br>
		<a href=\"TestSingleDID?OID=$pOID&DID=$pDID\">Click here to Try it one more time.</a>\"

		";

		return $html;
			
		}

		function GetDIDNumberforConfirmRemove($DIDP)
		{
			$UID=currentUser();
			$myADb=new ADb();
			$strSQL = "select DIDNumber from DIDS where DIDNumber='$DIDP' and status=0 and OID=\"$UID\" ";
            $ResultDIDNumber = $myADb->ExecuteQuery($strSQL);
            return $ResultDIDNumber;
		}

		function GetRemoveALLDID($DIDP)
		{
			$UID=currentUser();
			$myADb=new ADb();
		    $strSQL = "select DIDNumber,OfferDate,CheckStatus,MonthlyCharges,
                       SetupCost,curdate() from DIDS where DIDNumber=\"$DIDP\" and status=0 and OID=\"$UID\"  ";
            $Result = $myADb->ExecuteQuery($strSQL);
            return $Result;

		}

		function InsertdelinRemoveDID($DIDNumber,$UID,$OfferDate,$Tested, $UID,$Monthly,$Setup)
		{
			$UID=currentUser();
			$myADb=new ADb();
			$strSQLInsert = "INSERT INTO RemovedDIDS (
                              DIDNumber,
                              OID,
                              RemovalDate,
                              OfferDate,
                              Tested,
                              Admin,
                              Monthly,
                              Setup)
                        VALUES (
                            \"$DIDNumber\",
                            \"$UID\",
                            sysdate(),
                            \"$OfferDate\",
                            \"$Tested\",
                            \"$UID\",
                            \"$Monthly\",
                            \"$Setup\")";
                          
			  $ResultInsert = $myADb->ExecuteQuery($strSQLInsert);
			  $strSQL = "DELETE FROM DIDS WHERE DIDNumber = \"$DIDP\" and OID=\"$UID\" ";
		    #    echo "\$strSQL: $strSQL";
		      $ResultInsert = $myADb->ExecuteQuery($strSQL);
		      return $ResultInsert;
		}


		function Current_Date(){
	
			// $NowYear;
			$myADb=new ADb();
			
			$strSQL = "Select curdate()";
			$Result = $myADb->ExecuteQuery($strSQL);
			$Temp = $Result->fields[0];
			// $NowYear = substr($Temp,0,4);
			return $Temp;
    }

        function GetYearList($pYear){
        	$YearHTML="";
			$Temp=$this->Current_Date();
			$NowYear = substr($Temp,0,4);
			for($nIndex=2005;$nIndex<=$NowYear;$nIndex++){
				$YearHTML	.= "<option value='$nIndex'>$nIndex</option>";
			}
			
			$YearHTML	.= "<option value='%'>ALL</option>";
			$YearHTML	= str_replace("'$pYear'","'$pYear' selected ",$YearHTML);
			return $YearHTML;		
	}

	function GetMonthList($pMonth) {
	
			$MonthHTML	= "
				              <option value='01'>January</option>
				              <option value='02'>February</option>
				              <option value='03'>March</option>
				              <option value='04'>April</option>
				              <option value='05'>May</option>
				              <option value='06'>June</option>
				              <option value='07'>July</option>
				              <option value='08'>August</option>
				              <option value='09'>September</option>
				              <option value='10'>October</option>
				              <option value='11'>November</option>
				              <option value='12'>December</option>
				              <option value='%'>ALL</option>
				              
				             ";
				$MonthHTML	= str_replace("'$pMonth'","'$pMonth' selected ",$MonthHTML);

				return $MonthHTML;
			
		
	}

	function GetIfOld($pDate){
	
			$myADb=new ADb();
			
			$strSQL = "select \"2013-10-31\" > \"$pDate\" ";
			$Result = $myADb->ExecuteQuery($strSQL);
			
			return $Result->fields[0];
	
	}

	function GetDIDLogs($TableCDRS,$whereClause, $orderClause){
		 $myADb=new ADb();
		 $UID=currentUser();
		 $strSQL= "select ringto,callerid,callednum,trunk,disposition,billseconds,
		            OfferRate,callstart,OID,id,calleridname,uniqueid,fromip,
					TotalMinutes,TalkTimeWas,TalkTimeCut,TalkTImeRemain,ExpiryDate,
					MinutesTotalUsed,date_format(callstart,'%d-%m-%Y %h:%i')
					as Date,RecordKey,IsPayFone,CEIL(Billseconds/60) as ceils from $TableCDRS 
					where Vendor=\"$UID\" $whereClause $orderClause limit 0, 100";	

        $Result	= $myADb->ExecuteQuery($strSQL);
		return $Result;
	}

	function CountResult($whereClause,$TableCDRS){
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL	= "select count(*) from $TableCDRS where OID=\"$UID\" $whereClause ";	
		$CountResult	= $myADb->ExecuteQuery($strSQL);
		return $CountResult;
	}

	function GetFreeMinutesOfDD($pDID,$whereClause) {
	
	$myADb=new ADb();
	
	$whereClause = "";
	if ($pDID != '' || $pDID != 'All') {
		$whereClause = " where DIDNumber=\"$pDID\"";
	}	
	
	$strSQL = " select FreeMin from DIDS $whereClause";
	$Result	= $myADb->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
	
}
function GetOfferedMinutes($pDID) {
	
	$myADb=new ADb();
	
	$strSQL = " select PerMinuteCharges from DIDS where DIDNumber=\"$pDID\" ";
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result->fields[0];
	
}
function DIDInsertinRemoveDID($DIDNumber,$UID,$OfferDate,$Tested, $UID,$Monthly,$Setup){
	$myADb=new ADb();
	$UID=currentUser();
	$strSQLInsert = "INSERT INTO RemovedDIDS (
                              DIDNumber,
                              OID,
                              RemovalDate,
                              OfferDate,
                              Tested,
                              Admin,
                              Monthly,
                              Setup)
                        VALUES (
                            $DIDNumber,
                            $UID,
                            sysdate(),
                            \"$OfferDate\",
                            $Tested,
                            $UID,
                            $Monthly,
                            $Setup)";

    $ResultInsert = $myADb->ExecuteQuery($strSQLInsert);


    $strSQL = "DELETE FROM DIDS WHERE DIDNumber = \"$DIDNumber\" and OID=\"$UID\" ";
       
    $ResultInsert = $myADb->ExecuteQuery($strSQL);
}


function Download(){
	$UID=currentUser();
	$myADb=new ADb();
	$strSQL = "select OID,Status,FileName from RequestBillCSV where OID=\"$UID\" and Type=5   ";
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;
}

function URequestBill($ffMonth,$ffYear,$DID){
	$UID=currentUser();
	$myADb=new ADb();
	$strSQL = "update RequestBillCSV set Status=0,Month=\"$ffMonth\",Year=\"$ffYear\",DID=\"$DID\"  where OID=\"$UID\"  and Type=5 ";
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;
}

function IRequestBill($ffMonth,$ffYear,$DID){
	$UID=currentUser();
	$myADb=new ADb();
	$strSQL = "insert into RequestBillCSV (OID,Date,Status,Month,Year,Type,DID) values(\"$UID\",sysdate(),0,\"$ffMonth\",\"$ffYear\",\"5\",\"$DID\")";
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;
}

function GetMonthYear(){
	$UID=currentUser();
	$myADb=new ADb();
	$strSQL = " select substring(curdate(),6,2) as Month,substring(curdate(),1,4) as Year";
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;
}

function GetRequestBill(){
	$UID=currentUser();
	$myADb=new ADb();
	$strSQL = "select Status,FileName,date_format(Date,'%d-%b-%Y') as Date from RequestBillCSV where OID=\"$UID\"  and Type=5 ";
	$Result	= $myADb->ExecuteQuery($strSQL);
	return $Result;
}

function GetMyDIDS($pUID) {
	
	$myADb=new ADb();
	$html="";
	
	$strSQL = " select DIDNumber from DIDS where OID=\"$pUID\" ";
	$Result	= $myADb->ExecuteQuery($strSQL);
	
	while(!$Result->EOF){
	
		$DIDNumber  = $Result->fields[0];
		$html .= "<option value= $DIDNumber >$DIDNumber </option>";
		
		$Result->MoveNext();
	
	}
	
	$html .= "<option value= '-1' >ALL</option>";
	
	return $html;
	
}

function GetYears($pYear) {
	
	 
	$html="";
	
	for($nIndex=2004;$nIndex<=$pYear;$nIndex++) {
	
		if($pYear == $nIndex) {
			
			$selected = " selected ";
			
		}else{
			$selected = "  ";
		}
	$html .= "<option value=$nIndex $selected>$nIndex</option>";
	
	}
	
	$html .= "<option value='%'>ALL</option>";
	
	return $html;
	
}

function output_file($file, $name, $mime_type='')
{
	// echo $file;
	// exit;
 /*
 This function takes a path to a file to output ($file), 
 the filename that the browser will see ($name) and 
 the MIME type of the file ($mime_type, optional).
 
 If you want to do something on download abort/finish,
 register_shutdown_function('function_name');
 */
 if(!is_readable($file)) die('File not found or inaccessible!');
  
 $size = filesize($file);
 $name = rawurldecode($name);
  
 /* Figure out the MIME type (if not specified) */
 $known_mime_types=array(
/*    "pdf" => "application/pdf",
    "csv" => "application/csv",
    "txt" => "text/plain",
    "html" => "text/html",
    "htm" => "text/html",
    "exe" => "application/octet-stream",
    "zip" => "application/zip",
    "doc" => "application/msword",
    "xls" => "application/vnd.ms-excel",
    "ppt" => "application/vnd.ms-powerpoint",
    "gif" => "image/gif",
    "png" => "image/png",
    "jpeg"=> "image/jpg",
    "jpg" =>  "image/jpg",
    "php" => "text/plain"*/
    "csv" => "application/csv",

 );
       
 if($mime_type==''){
     $file_extension = strtolower(substr(strrchr($file,"."),1));
     if(array_key_exists($file_extension, $known_mime_types)){
        $mime_type=$known_mime_types[$file_extension];
     } else {
        $mime_type="application/force-download";
     };
 };
  
 @ob_end_clean(); //turn off output buffering to decrease cpu usage
  
 // required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');
   
 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');
  
 /* The three lines below basically make the 
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 2097 05:00:00 GMT");
 
 // multipart-download and download resuming support
 if(isset($_SERVER['HTTP_RANGE']))
 {
    list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
    list($range) = explode(",",$range,2);
    list($range, $range_end) = explode("-", $range);
    $range=intval($range);
    if(!$range_end) {
        $range_end=$size-1;
    } else {
        $range_end=intval($range_end);
    }
 
    $new_length = $range_end-$range+1;
    header("HTTP/1.1 206 Partial Content");
    header("Content-Length: $new_length");
    header("Content-Range: bytes $range-$range_end/$size");
 } else {
    $new_length=$size;
    header("Content-Length: ".$size);
 }
 
 /* output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r'))
 {
    if(isset($_SERVER['HTTP_RANGE']))
    fseek($file, $range);
     
    while(!feof($file) && 
        (!connection_aborted()) && 
        ($bytes_send<$new_length)
          )
    {
        $buffer = fread($file, $chunksize);
        print($buffer); //echo($buffer); // is also possible
        flush();
        $bytes_send += strlen($buffer);
    }
 fclose($file);
 } else die('Error - can not open file.');
  
die();
} 
}
?>