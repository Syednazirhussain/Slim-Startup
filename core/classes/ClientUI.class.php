<?php
		//include_once "Const.inc.php" ;
//		include_once "$INCLUDEPATH/ADb.inc.php";
//		include_once "$INCLUDEPATH/Template.inc.php";
//		include_once "$INCLUDEPATH/NewTemplate.inc.php";
               // include_once "ADb.inc.php";
		//include_once "Template.inc.php";
		//include_once "NewTemplate.inc.php";

class ClientUI
{
    public $InboxMessages;
	function ClientUI(){
		
		
		$this->ADb = new ADb();
		$this->Templete = new Template();
		$this->NewTemplete = new NewTemplate();
		$this->InboxMessages;
	}	
	
function getSessionErrorMessage() {

	#print "<center><br>Error: Session expired or Not Logged in. ";
	#print "<br>Redirecting to Login Page...";
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"0\">
<tr>
    <td align=center><a href=\"http://sandbox.didx.net\" ><img border=0 src=\"/images/sessionerror.gif\"></a></td>
  </tr>
</table>";
	echo "<meta http-equiv=refresh content=0;url=/>";
	exit;
	
}
function readfilename()
{
    
    $parts[0] = "/home";
    $parts[1] = "/specialoffers/index.php";
    $parts[2] = "/Reports.php";    
    $parts[3] = "/DIDsActualDaysReport.php";
    $parts[4] ="/TalkTimeUsageReport.php";
    $parts[5] ="/BuyerDIDSReportBySeries2.php";
    $parts[6] ="/YearlyReport.php";
    $parts[7] ="/Tools.php";
    $parts[8] ="/ViewRequestedDID.php";
    $parts[9] ="/PaymentMadeToDIDx.php";
    $parts[10] = "/CallLogs.php";
    $parts[11] ="/MyRefundClaims.php";
    $parts[12] ="/overlimittalktime.php";
    $parts[13] ="/MyPurchasedDIDs.php";
    $parts[14] ="/EditSIP.php";
    $parts[15] ="/UpdateSIP.php";
    $parts[16] ="/RequestDID.php";
    $parts[17] ="/Search.php";
    $parts[18] ="/CDIDInfo.php";
    $parts[19] ="/AddFunds.php";
    $parts[20] ="/AddFundsConfirm.php";
    $parts[21] ="/AddTalkTime.php";
    $parts[22] ="/BillingDID.php";
    $parts[23] ="/AddFunds2Action.php";
    $parts[24]="/PendingPurchasings.php";
    $parts[25]="/NewsFeeds.php";
    $parts[26]="/MyCart.php";
    $parts[27]="/MyChannels.php";
    $parts[28]="/LNPRequest.php";
    $parts[29]="/BuyBulkDIDS.php";
    $parts[30]="/BuyBulkDIDSConfirm.php";
    $parts[31]="/FreeDIDConfirm.php";
    $parts[32]="/BuyBulkDIDSConfirmAction.php";
    $parts[33]="/AdvancedSearch.php";
    $parts[34]="/AdvancedSearchAction.php";
    $parts[35]="/EditSIPAlter.php";
    $parts[36]="/BulkRouting.php";
    $parts[37]="/BulkRoutingCSV.php";
    $parts[38]="/MassEditRingTo.php";
    $parts[39]="/UpdateMassRingTo.php";
    $parts[40]="/BackOrder.php";
    $parts[41]="/BackOrderConfirm.php";
    $parts[42]="/BackOrderAction.php";
    $parts[43]="/MyOfferedDIDs.php";
    $parts[44]="/FundsTransfer.php";
    $parts[45]="/BuyerLinkAccount.php";
    $parts[46]="/PendingSales.php";
    $parts[47]="/BackOrderVendors.php";
    $parts[48]="/SellerReqPmt.php";
    $parts[49]="/ReqPaymentPP.php";
    $parts[50]="/OffertoSale.php";
    $parts[51]="/Uploader.php";
    $parts[52]="/MyDIDSLogs.php";
    $parts[53]="/OfferedTalkTimeReport.php";
    $parts[54]="/ViewStockReportByArea.php";
    $parts[55]="/PayPhoneReport.php";
    $parts[56]="/inbox/index.php";
    $parts[57]="/inbox/c.php";
    $parts[58]="/inbox/ShowLetter.php";
    $parts[59]="/UploaderMultiAction.php";
    $parts[60]="/RemoveDID.php";
    $parts[61]="/OffertoSaleConfirm.php";
    $parts[62]="/RemoveDIDConfirm.php";
    $parts[63]="/OffertoSaleConfirmAction.php";
    $parts[64]="/PurchasedDIDsMonthly.php";
    $parts[65]="/InvoicesMadeByDIDx.php";
    $parts[66]="/ClientCreditIssuedReport.php";
    $parts[67]="/CTesterLog.php";
    $parts[68]="/PaymentInvocesSales.php";
    $parts[69]="/TestResult.php";
    $parts[70]="/GetFreeDID.php";
    $parts[71]="/CTesterLog.php";
    $parts[72]="/VendorInfo.php";
    $parts[73]="/SearchResult.php";
    $parts[74]="/AddToCartNow.php";
    $parts[75]="/ContractDocs.php";
    $parts[76]="/SMSInbox.php";
    $parts[77]="/SearchDID.php";
    $parts[78]="/EditSIPAlterAction.php";
    $parts[79]="/BuyChannel.php"; 
    $parts[80]="/DownloadLogCSV.php"; 
    $parts[81]="/DownloadTalkTimeCSV.php"; 
    $parts[82]="/ChangeDefaultRingto.php";
    $parts[83]="/ChangeDefaultRingtoAction.php";
    $parts[84]="/UserInfo.php";
    $parts[85]="/AdBanner.php";
    $parts[86]="/UploadProfilePic.php";
    $parts[87]="/UploadProfilePicAction.php";
    $parts[88]="/AddCardDetail.php";
    $parts[89]="/ClientSpecialOfferConfirm.php";
    $parts[90]="/FreeDIDConfirm2";
    $parts[91]="/UploadDocs.php";
    $parts[92]="/UploadDocsMsg.php";
    $parts[93]="/UploadMoreDocs.php";
    $parts[94]="/FundsTransferConfirm.php";
    $parts[95]="/AddTalkTimeAction.php";
    $parts[96]="/BuyDID.php";
    $parts[97]="/Purchase.php";
    $parts[98]="/FreeDIDConfirm2.php";
    $parts[99]="/BuyDID_Uploaded.php";
    $parts[100]="/RequestDIDAction.php";
    $parts[101]="/ClientSpecialOfferBuy.php";
    $parts[102]="/ReleaseSPBlock.php";
    $parts[103]="/BuyChannelAction.php";
    $parts[104]="/BuyDID_AddToCart.php";
    $parts[105]="/TestResultMyDID.php";
    $parts[106]="/FeedBackAction.php";
    $parts[107]="/FeedBack.php";
    $parts[108]="/FundsTransferAction.php";
    $parts[109]="/LNPEditUserInfo.php";
    $parts[110]="/OfferDIDNow.php";
    $parts[111]="/RingtoServers.php";
    $parts[112]="/ClientDIDRefundReport.php";
    $parts[113]="/BuyDID_Premium.php";
    $parts[114]="/BeforedaysBilling.php";
    $parts[115]="/RemoveAllDIDsConfirm.php";
    $parts[116]="/RemoveAllDIDs.php";
    $parts[117]="/UploadCCDocument";
    $parts[118]="/OfferedSMSTalkTimeReport.php";
    $parts[119]="/DownloadMyLogCSV.php";
    $parts[120]="/DIDMinutesReport.php";
     
for($i=0;$i<count($parts);$i++)
    {
        $nameoffile= $_SERVER[SCRIPT_NAME];
         //trim($filenamevar[$i])."==".$nameoffile;
//                                 echo "<br>";
       if(trim($parts[$i])==$nameoffile)
       {
           
           //echo "hello im in if";
           $scriptname=$parts[$i];
           //echo $scriptname; 
 
       }
    }
return $scriptname;

}

function getSessionFooter() {
	

	
#	include "http://sandbox.didx.net/tmpl/SessionFooter.htm";
	
	
	//$LocalDir = "/home/httpd/vhosts/didx.net/httpdocs/tmpl";
    $LocalDir = "/var/www/html/local.sandbox.didx/httpdocs/tmpl";
$filenamevar=$this->readfilename();

if($_SERVER[SCRIPT_NAME]==$filenamevar) 
    $filename = "$LocalDir/SessionFooter_New.html";
else	
    $filename = "$LocalDir/SessionFooter.htm";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
#echo "<center>" . $contents . "</center>";

return $contents;
	
#echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionFooter.htm", $PH);	
		#open SessionHead, "$LocalDir/SessionHeader.htm";
		#print <SessionHead> ;
		#close SessionHead;
	

	
}

function GetFacebookBox($FullName,$FBPic,$FBIDResponse,$FBURL){
	
			$html="";
			$Linked="";
			
			if($FBIDResponse!="1")
				$Linked="Associate DIDx account with Facebook";
			else
				$Linked = "Your DIDx is associated with Facebook";
			
			$html="<table width=\"100%\" border=\"0\" >
    <tr>
      <td width=\"5%\" rowspan=\"3\"><div id=\"PRPIC\" style=\"height:50px;width:50px;\">
      <img src=\"$FBPic\" width=\"50\" height=\"50\" /></div></td>
      <td width=\"95%\" height=\"25\"><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small;\"><a href='$FBURL' target=_blank>$FullName</a></span></td>
    </tr>
    <tr height=\"1\" bgcolor=\"#f0f0f0\">
      <td></td>
    </tr>
    <tr>
      <td><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small;\">$Linked</span></td>
    </tr>
  </table>";
  
  return $html;
	
}

function getSessionHeader($pTitle) {
	
	global $UID,$user,$FBIDResponse,$fb;
	
	if($user!="" && $UID==715017){
		
		try{
			$user_details=$fb->api_client->users_getInfo($user, array('last_name','first_name','pic_square','profile_url ')); 
					$FBFirstname =$user_details[0]['first_name']; 
    			$FBLastname  =$user_details[0]['last_name']; 
    			$FullName = "$FBFirstname $FBLastname";
    			$FBPic  =$user_details[0]['pic_square']; 
    			$FBURL  =$user_details[0]['profile_url']; 
		}catch(Exception $e){}
			
		$FacebookBox = $this->GetFacebookBox($FullName,$FBPic,$FBIDResponse,$FBURL);	
		
	}
	
	
	
	//$LocalDir = "/home/httpd/vhosts/didx.net/httpdocs/tmpl";
        $LocalDir = "/var/www/html/local.sandbox.didx/httpdocs/tmpl";
	$PH;
		if($pTitle == "MAINTitle") {
			$pTitle="";
		}
		$PH['[SP_TITLE]']			= "DIDx.Net  $pTitle";
	
		$PH['[SP_WISHPHP]']			= "PHP";
		
		$PH['[SP_FBProfile]'] = $FacebookBox;
		
		$strSQL = "SELECT `Count` FROM `ClientCounts` where CountName='Complains' and uid=$UID";
        #echo $strSQL;
        $ResultRead = $this->ADb->ExecuteQuery($strSQL);
		$InboxRead = $ResultRead->fields[0];
		
		$this->InboxMessages = $InboxRead;
		
		if($InboxRead>0){
			$InboxRead =  "($InboxRead)";
		}
		


////////////////////////////////////////////// 


// $strSQL = "select count(*) from complain where (OID='$UID' and Notify=0 and IsArchive=0 and isann=1) or (NotifyVendor=1 and VOID='$UID' and isann=1) limit 0, 50";
//         #echo $strSQL;
//         $ResultUnRead = $this->ADb->ExecuteQuery($strSQL);
//         $InboxUnRead = $ResultUnRead->fields[0];
        
//         $this->InboxMessages = $InboxUnRead;
        


        // if($InboxRead>0){
        //     $InboxRead =  "($InboxRead)";
        // }
      
        $strSQL = "select count(*) from complain where UID=\"$UID\" and Notify=1 and IsRead=0 and IsAnn=1 and IsArchive=0 limit 0, 50";
        #echo $strSQL;
        $ResultUnRead = $this->ADb->ExecuteQuery($strSQL);
        $IsUnAnn = $ResultUnRead->fields[0];


// $TotalNotify=$InboxUnRead+$IsUnAnn;
$TotalNotify=$IsUnAnn;

//$PH['[SP_TotalNotify]']=$TotalNotify;
$PH['[SP_TotalNotify]']="$TotalNotify";


/////////////////////////////////////////////////////











		$strSQL = "SELECT `Count` FROM ClientCounts` where CountName='Announcement' and uid=$UID";
        #echo $strSQL;
        $ResultRead = $this->ADb->ExecuteQuery($strSQL);
		$IsAnn = $ResultRead->fields[0];
		
		if($IsAnn>0)
				$IsAnn = "($IsAnn)";
		else
				$IsAnn = "";
		
		$strSQL = "select ccompany from customer where uid=\"$UID\" ";
		#echo $strSQL;
		$ResultCom = $this->ADb->ExecuteQuery($strSQL);
		$Comp = $ResultCom->fields[0];
		
		$PH['[SP_Company]']					= $Comp;
		$PH['[SP_InboxTicket]']			= $InboxRead;
		
		$PH['[SP_AnnTop]']			= $IsAnn;
		
		$strSQL = "select custype,premium from orders where oid=\"$UID\" ";
		#echo $strSQL;
		$ResultCom = $this->ADb->ExecuteQuery($strSQL);
		$MyCusType = $ResultCom->fields[0];
		$MyPremimum = $ResultCom->fields[1];
		
		
		$strSQL = "SELECT `Count` FROM `ClientCounts` where CountName='RefundClaimCount' and uid=$UID";
        #echo $strSQL;
        $ResultClaim = $this->ADb->ExecuteQuery($strSQL);
		$Claim = $ResultClaim->fields[0];
				
				if($Claim=="")
						$Claim=0;
		
				$PH['[SP_Claim]']			= "DID Refunds ($Claim)";











		
		$filenamevar=$this->readfilename();
		if($_SERVER[SCRIPT_NAME]=="/home")
        {
            $PH['[SP_Class]']='class="active"';
        } 
        if($_SERVER[SCRIPT_NAME]=="/Search.php" && $_SERVER[SCRIPT_NAME]=="/SearchDID.php")
        {
            $PH['[SP_Class1]']='class="active"';
        } 
        if($_SERVER[SCRIPT_NAME]=="/CDIDInfo.php")
        {
            $PH['[SP_Class2]']='class="active"';
        }
        if($_SERVER[SCRIPT_NAME]=="/AddTalkTime.php" || $_SERVER[SCRIPT_NAME]=="/AddFunds.php" || $_SERVER[SCRIPT_NAME]=="/BillingDID.php")
        {
            $PH['[SP_Class3]']='class="active"';
        } 
         if($_SERVER[SCRIPT_NAME]=="/AddTalkTime.php")
        {
            $PH['[SP_Class4]']='class="active"';
        } 
        if($_SERVER[SCRIPT_NAME]=="/AddFunds.php")
        {
            $PH['[SP_Class5]']='class="active"';
        }
         if($_SERVER[SCRIPT_NAME]=="/BillingDID.php")
        {
            $PH['[SP_Class6]']='class="active"';
        } 
        if($_SERVER[SCRIPT_NAME]=="/inbox/index.php") 
        {
            $PH['[SP_Class7]']='class="active"';
        }  
        if($_SERVER[SCRIPT_NAME]=="/UserInfo.php") 
        {
            $PH['[SP_Class8]']='class="active"';
        }  
                         
		if($MyCusType==1 && $MyPremimum==0)
            if($_SERVER[SCRIPT_NAME]==$filenamevar)
            {
                echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderSeller_New.html", $PH); 
            }   
            else
            {
                
			    echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderSeller.htm", $PH);
            }	    
        if($MyCusType==1 && $MyPremimum==1)
            if($_SERVER[SCRIPT_NAME]==$filenamevar)
                echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderSeller_Premium.html", $PH);
            else
                echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderSeller.htm", $PH);                
		if($MyCusType==0 && $MyPremimum==0)
            if($_SERVER[SCRIPT_NAME]==$filenamevar)
            {
                echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderBuyer_New.html", $PH);   
                } 
            else
            {
                
			    echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderBuyer.html", $PH);	
			}
		if($MyCusType==2 && $MyPremimum==0)
             if($_SERVER[SCRIPT_NAME]==$filenamevar)
             {
                  echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeader.html", $PH);
             }
             else
             {             
			    echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeader.htm", $PH);	
		     }
		if($MyCusType==0 && $MyPremimum==1)
                if($_SERVER[SCRIPT_NAME]==$filenamevar)
                   {
                    echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderPremium_New.html", $PH);
                    } 
                else
                   {
                       
				    echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderPremium.html", $PH);	
				   }
		if($MyCusType==3 && $MyPremimum==0)
            {            
                if($_SERVER[SCRIPT_NAME]==$filenamevar)
            {
                echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderBuyer_New.html", $PH);   
            } 
            else
            {                    
            
			echo $this->Templete->AlterTemplate2Html("$LocalDir/SessionHeaderBuyer.html", $PH);	
			}
            }		










            
		#open SessionHead, "$LocalDir/SessionHeader.htm";
		#print <SessionHead> ;
		#close SessionHead;
	return "";
}

function getSessionHeaderArfeen($pTitle) {
	
	global $UID;
	
	
	
	//$LocalDir = "/home/httpd/vhosts/didx.net/httpdocs/tmpl";
        $LocalDir = "/var/www/html/local.sandbox.didx/httpdocs/tmpl";
	$PH;
		if($pTitle == "MAINTitle") {
			$pTitle="";
		}
		$PH['[SP_TITLE]']			= "DIDx.Net  $pTitle";
	
		$PH['[SP_WISHPHP]']			= "PHP";
		
		$strSQL = "select count(*) from complain where (OID='$UID' and Notify=1 and IsArchive=0 and isann=0) or (NotifyVendor=1 and VOID='$UID' and isann=0)";
		#echo $strSQL;
		$ResultRead = $this->ADb->ExecuteQuery($strSQL);
		$InboxRead = $ResultRead->fields[0];
		
		$this->InboxMessages = $InboxRead;
		
		if($InboxRead>0){
			$InboxRead =  "($InboxRead)";
		}
		
		$strSQL = "select count(*) from complain where UID=\"$UID\" and Notify=1 and IsRead=1 and IsAnn=1 and IsArchive=0 ";
		#echo $strSQL;
		$ResultRead = $this->ADb->ExecuteQuery($strSQL);
		$IsAnn = $ResultRead->fields[0];
		







    

		if($IsAnn>0)
				$IsAnn = "($IsAnn)";
		else
				$IsAnn = "";
		
		$strSQL = "select ccompany from customer where uid=\"$UID\" ";
		#echo $strSQL;
		$ResultCom = $this->ADb->ExecuteQuery($strSQL);
		$Comp = $ResultCom->fields[0];
		
		$PH['[SP_Company]']					= $Comp;
		$PH['[SP_InboxTicket]']			= $InboxRead;
		
		$PH['[SP_AnnTop]']			= $IsAnn;
		
		$strSQL = "select custype,premium from orders where oid=\"$UID\" ";
		#echo $strSQL;
		$ResultCom = $this->ADb->ExecuteQuery($strSQL);
		$MyCusType = $ResultCom->fields[0];
		$MyPremimum = $ResultCom->fields[1];
		
		
		$strSQL = "select count(*)  from RefundClaimCount where oid=\"$UID\" ";
		#echo $strSQL;
		$ResultClaim = $this->ADb->ExecuteQuery($strSQL);
		$Claim = $ResultClaim->fields[0];
				
				if($Claim=="")
						$Claim=0;
		
				$PH['[SP_Claim]']			= "DID Refunds ($Claim)";
		
				
		if($MyCusType==1 && $MyPremimum==0)
			echo $this->NewTemplete->AlterTemplate2Html("$LocalDir/SessionHeaderSeller.htm", $PH);	
		if($MyCusType==0 && $MyPremimum==0)
			echo $this->NewTemplete->AlterTemplate2Html("$LocalDir/SessionHeaderBuyer.html", $PH);	
			
		if($MyCusType==2 && $MyPremimum==0)
			echo $this->NewTemplete->AlterTemplate2Html("$LocalDir/SessionHeader.htm", $PH);	
		
		if($MyCusType==0 && $MyPremimum==1)
				echo $this->NewTemplete->AlterTemplate2Html("$LocalDir/SessionHeaderPremium.html", $PH);	
				
				
		if($MyCusType==3 && $MyPremimum==0)
			echo $this->NewTemplete->AlterTemplate2Html("$LocalDir/SessionHeaderBuyer.html", $PH);	
					
		#open SessionHead, "$LocalDir/SessionHeader.htm";
		#print <SessionHead> ;
		#close SessionHead;
	return "";
}
 

}

?>