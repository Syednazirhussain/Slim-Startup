<?php
// include_once("Const.inc.php");
// include_once($INCLUDEPATH . "/ADb.inc.php");
// include_once($INCLUDEPATH . "/User.inc.php");
// include_once($INCLUDEPATH . "/Customer.inc.php");
// include_once($INCLUDEPATH . "/CreditCard.inc.php");
// include_once($INCLUDEPATH . "/DID.inc.php");
// include_once($INCLUDEPATH . "/Transaction.inc.php");
// include_once($INCLUDEPATH . "/Complain.inc.php");
// include_once($INCLUDEPATH . "/DateTimeModule.inc.php");
// include_once($INCLUDEPATH . "/Address.inc.php");
// include_once($INCLUDEPATH . "/Orders.inc.php");
// include_once($INCLUDEPATH . "/AdminUser.inc.php");
// include_once($INCLUDEPATH . "/PHPMailer_v5.1/class.phpmailer.php");
// include_once($INCLUDEPATH . "/Encryption.inc.php");


// namespace PHPMailer/PHPMailerAutoload.php;
class Email
{
	public $CMName;
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Email($dbg = 0)
	{
		//include_once("Const.inc.php");
		$this->Db = new ADb();
		$this->fDebug = $dbg;
		$this->myCustomer = new Customer();
		$this->myUser = new User();
		$this->myOrder = new Orders();
		$this->myCreditCard = new CreditCard($this->fDebug);
		$this->did = new DID();
		$this->AdminUser = new AdminUser();
		$this->tr = new Transaction($this->fDebug);
		$this->Comp = new Complain($this->fDebug);
		$this->DTMod = new DateTimeModule($this->fDebug);
		$this->Adrs = new Address($this->fDebug);
		$this->AfterThirtyDays = date('d-M-y', time()+30*24*60*60);
		$this->CountryCode="";
		$this->AreaCode="";
		$this->RateCenter="";
		$this->NXX="";
		$this->TotalTestsLast7="";
		$this->DID = "";
		$this->Encrption = new Encryption();
	}
	function setSiteID($siteID)
	{
		$this->siteID = $siteID;
	}
	function getAllEmails()
	{
		$strSQL	= "select id, title, description, DATE_FORMAT(date_added, '%d-%b-%y'), subject_field,functionused from email_settings order by id";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		return $rs;
	}
	function GetTechnicalEmail($OID)
	{
		//get customer info 
		$strSQL	= "select TechContactEmail,TicketEmail from EmailPref where  uid=\"$OID\" ";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		
		$TickOp = $rs->fields[1];
		
		
				return $rs->fields[0];
		
				
	}
	function GetTesterEmail($OID,$OP)
	{
		//get customer info 
		$strSQL	= "select TesterPassed,TesterFailed from EmailPref where  uid=\"$OID\" ";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		
		if($OP==1)
			return $rs->fields[0];
		if($OP==2)
			return $rs->fields[1];
	}
	function replace()
	{

		// $this->CMName
		$this->CName = "$this->CFName  $this->CLName $this->CMNname";
		

		$this->search_for[] = "\$CalledDID";
		$this->replace_with[] = $this->CalledDID;

	    $this->search_for[] = "\$OID";
		$this->replace_with[] = $this->OID;


		$this->search_for[] = "\$CFName";
		$this->replace_with[] = $this->CFName;
		
		$this->search_for[] = "\$CMName";
		$this->replace_with[] = $this->CMName;
		
		$this->search_for[] = "\$CLName";
		$this->replace_with[] = $this->CLName;
		
		$this->search_for[] = "\$CName";
		$this->replace_with[] = $this->CName;
		
		$this->search_for[] = "\$CSalutation";
		$this->replace_with[] = $this->CSalutation;
		
		$this->search_for[] = "\$CustomerID";
		$this->replace_with[] = $this->CustomerID;
		
		$this->search_for[] = "\$CEmail";
		$this->replace_with[] = $this->CEmail;
		
		$this->search_for[] = "\$AdminListInvolved";
		$this->replace_with[] = $this->AdminListInvolved;
		
		$this->search_for[] = "\$UpdatedThread";
		$this->replace_with[] = $this->UpdatedThread;
		
		$this->search_for[] = "\$MessageComplain";
		$this->replace_with[] = $this->MessageComplain;
		
		$this->search_for[] = "\$PastUpdatedThread";
		$this->replace_with[] = $this->MessageComplainReply;
		
		$this->search_for[] = "\$BadClick";
		$this->replace_with[] = $this->BadLink;
		
		$this->search_for[] = "\$GoodClick";
		$this->replace_with[] = $this->GoodLink;
		
		$this->search_for[] = "\$ENDING";
		$this->replace_with[] = $this->ENDING;
		
		$this->search_for[] = "\$CCTYPE";
		$this->replace_with[] = $this->CCTYPE;
		
		
		
		$this->search_for[] = "\$SERVER_SEND";
		$this->replace_with[] = $this->SERVER_SEND;
		
		$this->search_for[] = "\$SERVER_LIVE";
		$this->replace_with[] = $this->SERVER_LIVE;
		
		$this->search_for[] = "\$RingTo";
		$this->replace_with[] = $this->RingTo;
		
		$this->search_for[] = "\$TotalMinUsed";
		$this->replace_with[] = $this->TotalMinUsed;
		
		$this->search_for[] = "\$TotalMinutes";
		$this->replace_with[] = $this->TotalMinutes;
		
	 	$this->search_for[] = "\$SusDate";
	 	$this->replace_with[] = $this->SusDate;
	 	
	 	$this->search_for[] = "\$TicketHours";
	 	$this->replace_with[] = $this->TicketHours;
	 	
	 	$this->search_for[] = "\$CSVType";
	 	$this->replace_with[] = $this->CSVType;
	 	
		$this->search_for[] = "\$ConfCode";
		$this->replace_with[] = $this->ConfCode;
		
		$this->search_for[] = "\$last4digits";
		$this->replace_with[] = $this->last4digits;
		
		$this->search_for[] = "\$start4digits";
		$this->replace_with[] = $this->start4digits;
		
		$this->search_for[] = "\$MoneyBookersID";
		$this->replace_with[] = $this->MoneyBookersID;
		
		$this->search_for[] = "\$ReleaseDate";
		$this->replace_with[] = $this->ReleaseDate;
		
		
		$this->search_for[] = "\$DocMsg";
		$this->replace_with[] = $this->DocMsg;
		
		$this->search_for[] = "\$CCType";
		$this->replace_with[] = $this->CCType;
		
		$this->search_for[] = "\$doller";
		$this->replace_with[] = "\$";
		
		$this->search_for[] = "\$dollar";
		$this->replace_with[] = "\$";
		
		$this->search_for[] = "\$Password";
		$this->replace_with[] = $this->Password;
		
		$this->search_for[] = "\$CreationDate";
		$this->replace_with[] = $this->CreationDate;

		$this->search_for[] = "\$SuspensionDate";
		$this->replace_with[] = $this->SuspensionDate;
				
		$this->search_for[] = "\$PaypalTID";
		$this->replace_with[] = $this->PaypalTID;
		
		$this->search_for[] = "\$REASON";
		$this->replace_with[] = $this->Reason;
		
                
        $this->search_for[] = "\$CCNUM";
        $this->replace_with[] = $this->ccNum;
        
		$this->search_for[] = "\$ThisReason";
		$this->replace_with[] = $this->Reason;
		
		
		$this->search_for[] = "\$PaypalID";
		$this->replace_with[] = $this->PaypalID;
		
		$this->search_for[]  = "\$TotalTestsLast7";
		#echo "<br>Here: " . $this->TotalTestsLast7;
    $this->replace_with[]  = $this->TotalTestsLast7;	
		
		
		
		
		
		
		$this->search_for[] = "\$TID";
		$this->replace_with[] = $this->TID;	
		
		$this->search_for[] = "\$CallerID";
		$this->replace_with[] = $this->CallerID;
		
		$this->search_for[] = "\$Country";
		$this->replace_with[] = $this->Country;	

		$this->search_for[] = "\$DID";
		$this->replace_with[] = $this->DID;	
		
		$this->search_for[] = "\$Month";
		$this->replace_with[] = $this->Month;	
		
		$this->search_for[] = "\$TrandID";
		$this->replace_with[] = $this->TrandID;	
		
		
		
		$this->search_for[] = "\$CreditLimit";
		$this->replace_with[] = $this->Remaining;	
		
		$this->search_for[] = "\$Date_Time";
		$this->replace_with[] = $this->Date_Time;
		
		$this->search_for[] = "\$Date";
		$this->replace_with[] = $this->Date;
		
		$this->search_for[] = "\$ComplainID";
		$this->replace_with[] = $this->ComplainID;
		
		$this->search_for[] = "\$Complain";
		$this->replace_with[] = $this->Complain;	
		
		$this->search_for[] = "\$CAddress";
		$this->replace_with[] = $this->CAddress;
		
		$this->search_for[] = "\$CName";
		$this->replace_with[] = $this->CName;
		
		$this->search_for[] = "\$CTel";
		$this->replace_with[] = $this->CTel;
		
		$this->search_for[] = "\$CCompany";
		$this->replace_with[] = $this->CCompany;

		$this->search_for[] = "\$AfterThirtyDays";
		$this->replace_with[] = $this->AfterThirtyDays;
		
		$this->search_for[] = "\$Amount";
		$this->replace_with[] = $this->Amount;
		
		$this->search_for[] = "\$pOUTB";
		$this->replace_with[] = $this->pOUTB;
					
		$this->search_for[] = "\$ErrorMessage";
		$this->replace_with[] = $this->ErrorMessage;

		$this->search_for[] = "\$Action";
		$this->replace_with[] = $this->Action;

		
		$this->search_for[] = "\$Name";
		$this->replace_with[] = $this->Name;
		
		$this->search_for[] = "\$Subject";
		$this->replace_with[] = $this->mySubject;
		
		$this->search_for[] = "\$last4numbers";
		$this->replace_with[] = $this->last4numbers;
		
		$this->search_for[] = "\$AVSCodeAll";
		$this->replace_with[] = $this->AVSCodeAll;
		
		$this->search_for[] = "\$CurrentDateTime";
		$this->replace_with[] = $this->CurrentDateTime;
		
		$this->search_for[] = "\$TotalPurchased";
		$this->replace_with[] = $this->PurchasedDIDS;
		
		$this->search_for[] = "\$TotalOffered";
		$this->replace_with[] = $this->OfferedDIDS;
		
		$this->search_for[] = "\$TotalDIDS";
		$this->replace_with[] = $this->TotalDIDS;
		
		$this->search_for[] = "\$OutSBalance";
		$this->replace_with[] = $this->AmountDue;
		
		$this->search_for[] = "\$CReferred";
		$this->replace_with[] = $this->CReferred;

		$this->search_for[] = "\$RefCode";
		$this->replace_with[] = $this->RefCode;
		
		$this->search_for[] = "\$RefEmail";
		$this->replace_with[] = $this->RefEmail;
		
		
		
		$this->search_for[] = "\$DueDays";
		$this->replace_with[] = $this->DueDays;
		
		$this->search_for[] = "\$TicketNumber";
		$this->replace_with[] = $this->TicketNumber;
		
		$this->search_for[] = "\$FormatedDateTime2";
		$this->replace_with[] = $this->FormatedDateTime2;
		
		$this->search_for[] = "\$FormatedDateTime";
		$this->replace_with[] = $this->FormatedDateTime;
		
		$this->search_for[] = "\$MessageComplain";
		$this->replace_with[] = $this->MessageComplain;
		
		$this->search_for[] = "\$FollowUp";
		$this->replace_with[] = $this->MessageComplainReply;
		
		$this->search_for[] = "\$MessageThread";
		$this->replace_with[] = $this->UpdatedThread;
		
		if($this->TestStatus==0) {
					$this->TestStatus =" Failed ";
		} else {
					$this->TestStatus =" Passed ";
		}
		
		$this->search_for[] = "\$TestStatus";
		$this->replace_with[] = $this->TestStatus;
		
		
		$this->search_for[] = "\$CountTesting";
		$this->replace_with[] = $this->CountTesting;
		
		$this->search_for[] = "\$NtWorkinSince";
		$this->replace_with[] = $this->NtWorkinSince;
		
		$this->search_for[] = "\$TimeCurrent";
		$this->replace_with[] = $this->TimeCurrent;
		
		$this->search_for[] = "\$DateFormated";
		$this->replace_with[] = $this->DateFormated; 
		 
		 $this->search_for[] = "\$OPassword";
		$this->replace_with[] = $this->OPassword; 
		
		$this->search_for[]  = "\$Attemp1";
		$this->replace_with[] = $this->C1;
		
		$this->search_for[] = "\$Attemp2";
		$this->replace_with[] = $this->C2;
		
		$this->search_for[]  = "\$Attemp3";
		$this->replace_with[] = $this->C3; 
		
		$this->search_for[]  = "\$Attemp4";
		$this->replace_with[] = $this->C4;
		
		$this->search_for[]  = "\$QtyDID";
        $this->replace_with[] = $this->DIDQty;
        
        $this->search_for[]  = "\$AllocatedQtyDID";
        $this->replace_with[] = $this->AllocatedDIDQty;
        
//        $this->search_for[]  = "\$DIDNumbers";
//        echo "<br>Here: " . $this->DIDNumbers;
//		$this->replace_with[] = $this->DIDNumbers;
		
		$this->search_for[]  = "\$MyServer";
		$this->replace_with[] = $this->MyServer;
		
		$this->search_for[]  = "\$ACTNO";
		$this->replace_with[] = $this->ACTNO;
		
		$this->search_for[]  = "\$Mulk";
		$this->replace_with[]  =$this->CountryCode;
		
		$this->search_for[]  = "\$BackOrder";
		$this->replace_with[]  =$this->BackOrderRef;
		
		
		
		
		$this->search_for[]  = "\$COUNTRYCODE";
		$this->replace_with[]  =$this->CountryCode;
		
		$this->search_for[]  = "\$AreaCode";
    $this->replace_with[]  =$this->AreaCode;		
    
    $this->search_for[]  = "\$Ilaqa ";
    $this->replace_with[]  =$this->AreaCode;		
    
    $this->search_for[]  = "\$RateCenter";
		$this->replace_with[]  =$this->RateCenter;
		
		$this->search_for[]  = "\$NXX";
    $this->replace_with[]  =$this->NXX;	
		
		$this->search_for[]  = "\$Qty";
    $this->replace_with[]  =$this->Qty;	
    
    $this->search_for[]    = "\$BuyDate";
		$this->replace_with[]  = $this->DIDPurchasedDate;
		
		$this->search_for[]    = "\$Vendor";
		$this->replace_with[]  = $this->Vendor;
		
		$this->search_for[]    = "\$Prefix";
		$this->replace_with[]  = $this->Prefix;
		
		
		$this->search_for[]    = "\$AreaName";
		$this->replace_with[]  = $this->AreaName;
		
		$this->search_for[]    = "\$MTF";
		$this->replace_with[]  = $this->MTF;
		
		$this->search_for[]    = "\$YESURL";
		$this->replace_with[]  = $this->YESURL;
		
		$this->search_for[]    = "\$NOURL";
		$this->replace_with[]  = $this->NOURL;
		
		$this->search_for[]    = "\$REMOVEURL";
		$this->replace_with[]  = $this->REMOVEURL;
		
		$this->search_for[]    = "\$QTYTHEYREQUESTED";
		$this->replace_with[]  = $this->QTYTHEYREQUESTED;
		
		$this->search_for[]    = "\$STARTINGPRICE";
		$this->replace_with[]    = $this->STARTINGPRICE;

		// echo str_replace("world","Peter","Hello world!");
		// Hello Peter!
		
		$this->message = str_replace($this->search_for, $this->replace_with, $this->message);
		$this->subject = str_replace($this->search_for, $this->replace_with, $this->subject);

	}
	function getCurrentDateTimeForDisplay() 
    {
	
	$this->CurrentDateTime = date("d-M-Y H:i:s");
	
	
	
	}
	function getInsertMenuHtml()
	{
		$strSQL	= "select search_title, search_id  from text_replacement";//, search_for	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		if($rs && !$rs->EOF)
		{
			$html = $rs->GetMenu('insertMenu','');
		}
		return $html;
	}//function
	function getInsertText($textID)
	{
		$strSQL	= "select search_id, search_title, search_for, replace_with,common from text_replacement where search_id='$textID'";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
				return $rs;
	}
	function getAllInsertText()
	{
		$strSQL	= "select search_id, search_title, search_for, replace_with from text_replacement";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		return $rs;
	}//function
	function updateEmail($updateEmail)
	{
		$strSQL	= "update email_settings set 
		title = \"$updateEmail[emailTitle] \", 
		description= \"$updateEmail[emailDescription]\",
		from_field=\"$updateEmail[emailFromField]\",
		reply_to_field=\"$updateEmail[emailReplyToField]\", 
		cc_field = \"$updateEmail[emailCcField]\", 
		subject_field=\"$updateEmail[emailSubjectField]\",
		contents_field=\"$updateEmail[emailContentsField]\"
		where id=\"$updateEmail[emailID]\"";	
		$this->dPrint ("\$strSQL: $strSQL");
		
		$rs=$this->Db->ExecuteQuery($strSQL);

	}
	function addEmail($newEmail)
	{
		$dated = date("Y-m-d", time());
		$strSQL	= "insert into email_settings (
		title , 
		description,
		from_field,
		reply_to_field, 
		cc_field, 
		subject_field,
		contents_field, 
		date_added)
		values(
		'$newEmail[emailTitle] ', 
		'$newEmail[emailDescription]',
		'$newEmail[emailFromField]',
		'$newEmail[emailReplyToField]', 
		'$newEmail[emailCcField]', 
		'$newEmail[emailSubjectField]',
		'$newEmail[emailContentsField]',
		'$dated')";
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);		
	}	
	function deleteEmail($emailID)
	{
		$strSQL	= "delete from email_settings 
			where id='$emailID'";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);	
	}
	function updateInsertText($updateText)
	{
		$strSQL	= "update text_replacement set 
		search_title = '$updateText[textTitle] ', 
		search_for= '$updateText[textKey]',
		replace_with='$updateText[textReplaceWith]'
		where search_id='$updateText[textID]'";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);		
	}
	function addInsertText($newInsertText)
	{
		$strSQL	= "insert into text_replacement (
		search_title, 
		search_for,
		replace_with
		)
		values(
		'$newInsertText[textTitle]', 
		'$newInsertText[textKey]',
		'$newInsertText[textReplaceWith]'
		)";
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);		
	}	
	function getUsedemails($emailID)
	{
		$Hash=Array();
		$strSQL	= "select id from email_settings where id='$emailID' and length(functionused)<=0";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);	
		if(!$rs->EOF)
		{
			$Hash[isused]= 0;
			}
			else 
		{
				$strSQL	= "select functionused from email_settings where id='$emailID'";	
				$this->dPrint ("\$strSQL: $strSQL");
				$rs=$this->Db->query($strSQL);	
				$Hash[isused]= 1;
				$Hash[functionused]= $rs->fields[0];
		}
		return $Hash;
	} 
	function deleteInsertText($textID)
	{
		$strSQL	= "delete from text_replacement 
			where search_id='$textID'";	
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);	
	}
	function sendParsedEmail($emailID)	
	{
		$dated = date("D, d M Y H:i:s +0500", time());
		$email2bSent = array();
		$email2bSent = $this->getParsedEmail(1);
		$to = $_POST[emailTo];
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";		
		$headers .= "To: $to \r\n";
		$headers .= "Subject: $subject \r\n";
		$headers .= "Date: $dated \r\n";
		$headers .= "Cc: $cc \r\n";
		$done = mail($to, $subject, $message, $headers);
		if($done)
			echo "Mail sent";
		else
			echo "Mail could not be sent";
	}
    function SendEmailOnCCardRefusal($OID,$ccnum,$Reason)
    {
        $this->OID = $OID;
        $this->getParsedEmail('SENDEMAILCCREF');
        $this->getCustomerInfo($OID);
        $this->ccNum = $ccnum;
        $this->Reason = $Reason;
        $this->replace();
        $this->send_mail();
    }
     function SendEmailOnCCardAcceptance($OID,$ccnum)
    {
        $this->OID = $OID;
        $this->getParsedEmail('SENDEMAILCCACC');
        $this->getCustomerInfo($OID);
        $this->ccNum = $ccnum;
        $this->replace();
        $this->send_mail();
    }
	function getTotalDIDSCount($OID)
	{
		//get customer info 
		$this->PurchasedDIDS = $this->did->getTotalPurchasedDIDSCount($OID);
		$this->OfferedDIDS = $this->did->getTotalOfferedDIDSCount($OID);
		$this->TotalDIDS = $this->OfferedDIDS + $this->PurchasedDIDS;
				
	}
	function getAmountDue($OID)
	{
		//get customer info 
		$this->AmountDue = $this->tr->getOutStandingBalance($OID);
		
								
	}
	function getStandardDateFormat()     //23-Jun-2006    - Get Current date
	{
		//get formated date 
		
		#$this->DateFormated   = $this->DTMod->getCurrentFormatedDateTimePattern3();
										
	}
	function getDaysDue($OID)
	{
		//get customer info 
		$this->DueDays = $this->tr->getDaysDueForTermination($OID);
					
	}
	function SendFriendlyReminderEmail($OID)
	{
		//get customer info 
		
		#$this->OutSBalance  = $this->tr->getOutStandingBalance($OID);
			if($this->tr->getOutStandingBalance($OID)<=0) {
				return $this->tr->getOutStandingBalance($OID);
			}
		$this->OID  = $OID;
		$this->OPassword = $PASS;
		$this->getParsedEmail("FRIENDLYREM");
		$this->getAmountDue($OID);
		$this->getCustomerInfo($OID);		
		#$this->getStandardDateFormat();
		$this->DateFormated   = $this->DTMod->getCurrentFormatedDateTimePattern3();
		$this->replace();
		#$this->CEmail = "arfeenster@gmail.com";
		$this->send_mail();		
						
	}
	function SendAutoTalkTimeEmailFormAGI($OID)
	{
		
		$this->OID  = $OID;
	
		$this->getParsedEmail("SENDTTAGI");
	
		$this->getCustomerInfo($OID);		
	
		$this->replace();
		$this->send_mail();		
						
	}
	function SendTalkTimeCharged($OID,$pAmount,$pDate)
	{
		
		$this->getParsedEmail("TlkTmChrg");
		$this->OID  = $OID;
		$this->Amount  = $pAmount;
		$this->Date=$pDate;
		$this->getCustomerInfo($OID);		
		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();	
		$this->getCustomerInfo($OID);		
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$this->CName = $customer['FName'] . " " . $customer['LName'];
		$this->replace();
		$this->send_mail();		
		}
	function SendTalkTimeEmailToClientFromClient($OID,$pAmount)
	{
		
		$this->getParsedEmail("SENDTTCLIENT");
		$this->OID  = $OID;
		$this->Amount  = $pAmount;
		$this->getCustomerInfo($OID);		
		
		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();	
		$this->getCustomerInfo($OID);		
	
		$this->replace();
		$this->send_mail();		
						
	}
	function SendOutstandingBalReminder($OID,$pAmount,$Todays)
	{
		
		$this->getParsedEmail("OutstdBalRem");
		$this->OID  = $OID;
		$this->Amount  = $pAmount;
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$this->CName = $customer['FName'] . " " . $customer['LName'];
		$this->Date = $Todays;
		$this->getCustomerInfo($OID);		
		$this->Amount = $pAmount;
		$this->replace();
		#$this->CEmail = "care@didx.net";
		$this->from = "care@didx.net";
		
		$this->send_mail();		
						
	}
	function SendTTEmailToAdmin($OID,$pAmount)
	{
		
		$this->getParsedEmail("SENDTTADMIN");
		$this->OID  = $OID;
		$this->Amount  = $pAmount;
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$this->CName = $customer['FName'] . " " . $customer['LName'];
		$this->Date = $this->DTMod->getCurrentFormatedDateTimePattern1();	
		$this->getCustomerInfo($OID);		
		$this->Amount = $pAmount;
		$this->replace();
		$this->CEmail = "care@didx.net";
		$this->from = "care@didx.net";
		
		$this->send_mail();		
						
	}
	function SendTalkTimeEmailToClientFromClientByBalance($OID,$pAmount)
	{
		
		$this->getParsedEmail("SENDTTCLIENTB");
		$this->OID  = $OID;
		$this->Amount  = $pAmount;
		$this->getCustomerInfo($OID);		
		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();	
		$this->getCustomerInfo($OID);		
	
		$this->replace();
		$this->send_mail();

	}
	function SendEmailForTestSingleToVendor($OID,$DIDNo,$STA)
    {

		// Sends Mail For Single DID Test, To Vendor.

		$this->OID = $OID;
		$this->TestStatus = $STA;
		$this->DID = $DIDNo;
		$this->GetMaxIDOfTesterLog($DIDNo);
		$this->getParsedEmail("DIDTESTSINGLEV");
		$this->getCustomerInfo($OID);
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->TotalTestsLast7 = $this->TotalTestForLastXDays($DIDNo,7);
		$this->FailedSinceHoursVal = $this->FailedSinceHours($DIDNo) ;
		$MyServer = $this->GetThisServer($OID) ;

		if($MyServer=="")
			$this->MyServer = "sip.didx.net";
		else
			$this->MyServer = $MyServer;


		$MyEmail = $this->GetTechnicalEmail($OID);

		$this->CEmail = $MyEmail;

		$this->replace();
		$this->send_mail();

	}
	function TotalTestForLastXDays($pDID,$pDays)
    {

		$strSQL = "select count(*) from DIDTestLog where did=\"$pDID\" and 
							 TestDate<=now() and TestDate>=date_sub(now(),interval $pDays day)";

		$rs=$this->Db->ExecuteQuery($strSQL);
		#echo "<br>\$rs: " . $rs->fields[0] . "- $strSQL";
		return $rs->fields[0];

	}
	function FailedSinceHours($pDID)
    {

		$strSQL = "select max(TestDate) from DIDTestLog where Status=0 and DID=\"$pDID\"  ";
		$rs=$this->Db->ExecuteQuery($strSQL);

		$MaxFailDate = $rs->fields[0];

		$strSQL = "select max(TestDate) from DIDTestLog where Status=1 and DID=\"$pDID\"   ";
		$rs=$this->Db->ExecuteQuery($strSQL);

		$MaxPassDate = $rs->fields[0];

		$strSQL = "select min(testdate) from DIDTestLog where testdate >= \"$MaxPassDate\"  and DID=\"$pDID\"   "	;
		$rs=$this->Db->ExecuteQuery($strSQL);

		$MinFailedDate = $rs->fields[0];

		$strSQL = "select datediff(now() , \"$MinFailedDate\") ";

		$rs=$this->Db->ExecuteQuery($strSQL);
		#echo "<br>\$rs: " . $rs->fields[0] . "- $strSQL";
		return $rs->fields[0];

	}
	function GetThisServer($pOID)
    {

		$strSQL = "select MyServer from orders where OID=\"$pOID\" ";
		$rs=$this->Db->ExecuteQuery($strSQL);

		return $rs->fields[0];

	}
	function SendEmailForTestSingleToBuyer($OID,$DIDNo,$STA)
    {

		// Sends Mail For Single DID Test, To Buyer.

		$this->OID = $OID;
		$this->TestStatus = $STA;
		$this->DID = $DIDNo;
		$this->getParsedEmail("DIDTESTSINGLEC");
		$this->getCustomerInfo($OID);
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->replace();
		$MyEmail = $this->GetTechnicalEmail($OID);


			$this->CEmail = $MyEmail;
			if($this->GetTesterEmail($OID,1))
					$this->send_mail();


	}
	function SendEmailForTestSingleToBuyerForFailed($OID,$DIDNo,$STA)
    {

		// Sends Mail For Single DID Test, To Buyer, On Fail.

		$this->OID = $OID;
		$this->TestStatus = $STA;
		$this->DID = $DIDNo;
		$this->GetMaxIDOfTesterLog($DIDNo);

		$this->getParsedEmail("DIDTESTSINGLECF");
		$this->getCustomerInfo($OID);
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern1();

			$MyEmail = $this->GetTechnicalEmail($OID);


			$this->CEmail = $MyEmail;

				$this->replace();

			if($this->GetTesterEmail($OID,2))
					$this->send_mail();


	}
	function GetMaxIDOfTesterLog($DID)
    {

		$strSQL = "select max(id) from DIDTestLog where did=\"$DID\" ";
	#	echo "<br>\$strSQL: $strSQL";
		$Result=$this->Db->query($strSQL);

		$MaxID = $Result->fields[0];

		$strSQL = "select c1,c2,c3,c4 from DIDTestLog where did=\"$DID\" and id=\"$MaxID\" ";
	#	echo "<br>\$strSQL: $strSQL";
		$Result=$this->Db->query($strSQL);

		$this->C1 = $Result->fields[0];
		$this->C2 = $Result->fields[1];
		$this->C3 = $Result->fields[2];
		$this->C4 = $Result->fields[3];

		#return $CHash;
	}
	function SendDailyEmailForTestSingleToVendor($OID,$DIDNo,$STA,$FirstTime,$FirstDate,$TotalTest,$FaulSince)
    {

		// Sends Mail For Single DID Test, To Vendor. // One that is sent from Daily Tester Cron - Arfeen

		$this->CountTesting =$TotalTes;
		$this->NtWorkinSince =$FaulSince;
		$this->TimeCurrent   =$FirstTime;
		$this->DateFormated =$FirstDate;

		$this->OID = $OID;
		$this->TestStatus = $STA;
		$this->DID = $DIDNo;
		$this->getParsedEmail("DAILYTESTVENDOR");
		$this->getCustomerInfo($OID);
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->replace();
		$this->send_mail();

	}

	function Send($to,$from,$replyto,$message,$is_gmail = true){

		$this->headers = "";
		$this->headers .= "MIME-Version: 1.0\r\n";
		$this->headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$this->headers .= "Content-Transfer-Encoding: quoted -printable\r\n";
		$this->headers .= "From: $from \r\n";
        $this->headers .= "Cc: syednazir13@gmail.com \r\n";
        $this->headers .= "Reply-To: ".$replyto. "\r\n";
		$this->dated = date("D, d M Y H:i:s", time());
		$this->headers .= "Date: ".$this->dated ."\r\n";

		$header = $this->headers;

		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		
		$mail->SMTPAuth = true;

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		if($is_gmail) {
			$mail->SMTPSecure = 'ssl';
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 465;
			$mail->Username   = "warishai732@gmail.com";  // username
			$mail->Password   = "warishai732@@";

		} else
		{
			$mail->Host = 'smtp.mail.google.com';
			$mail->Username   = "syednazir13@gmail.com";
			$mail->Password   = "pakistan123!@#";
		}

		$mail->IsHTML(true);

		$mail->From = "nadir@supertech.com";

		$mail->FromName = "Syed Nazir Hussain";

		$mail->AddReplyTo("syednazir13@gmail.com", "Jet Brain");


		$mail->AddCC('syednazir13@gmail.com', 'Jet Brain');

		$mail->Subject = 'Test';

		$message .= "\n\n";
		$message = str_replace("\n","<br>",$message);

		$mail->Body = $message;

		$mail->AddAddress($to);

		$mail->header = $header;

		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			return $error ;
		} else {
			return json_encode(['message' => 'Mail Sent']);
		}

	}

    function smtpmailer($is_gmail = true)
    {
		//    	$to="syednazir13@gmail.com";

    	$c= 'C:/xampp/htdocs/didx_customer_web_portal/public/PHPMailer/PHPMailerAutoload.php';


    	$error = '';
    	$to = $this->CEmail;
		$FromFrom = $this->from;
		$subject = $this->subject;




		// 'DIDx.net <support@virtualphoneline.com>'

    	// $error;
    	// $to = $this->CEmail;
    	// $to="warisha@supertec.com";
    	// $subject=$this->subject;


    	$this->headers = "";
        $this->headers .= "MIME-Version: 1.0\r\n";
        $this->headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $this->headers .= "Content-Transfer-Encoding: quoted -printable\r\n";
        $this->headers .= "From: $FromFrom \r\n";
        $this->headers .= "Cc: support@supertec.com,".$this->cc ."\r\n";
        $this->headers .= "Reply-To: ".$this->reply_to. "\r\n";
		$this->dated = date("D, d M Y H:i:s", time());
        $this->headers .= "Date: ".$this->dated ."\r\n";
    	// $from_name = 'Didx.net'
    	$header=$this->headers;
		
        $mail = new PHPMailer();
 
        $mail->IsSMTP();
        $mail->SMTPAuth = true;

          $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            ));
        if($is_gmail) {
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            // $mail->Username = 'support@supertec.com';
            // $mail->Password = 'rehan123';
            $mail->Username   = "warishai732@gmail.com";  // username
        	$mail->Password   = "warishai732@@";
        }
        else
        {
            $mail->Host = 'smtp.mail.google.com';
            // $mail->Username = 'support@supertec.com';
            // $mail->Password = 'rehan123';
            //$mail->Username   = "warishai732@gmail.com";  // username
			$mail->Username   = "warishai732@gmail.com";
        	$mail->Password   = "warishai732@@";
        }
        $mail->IsHTML(true);
        $mail->From="warishai732@gmail.com";
        // $mail->From="care@didx.net";
        $mail->FromName="DIDx.net";
        // $mail->Sender="abc@gmail.com"; // indicates ReturnPath header
        $mail->AddReplyTo("care@didx.net", "Replies for DIDx.net"); // indicates ReplyTo headers
		// for debugging 10/8/2017 By syed Nazir Hussain
		/*echo "<br><br><br>Dear ".$this->CFName." ".$this->CMNname." ".$this->CLName." your conformation code : ".$this->ConfCode." Your membership id : ".$this->OID;
		if (is_string($this->message)){
			echo "<br><br><br>".strtr($this->message, $vars);die();
		}else{
			die();
		}*/


		$vars = array(
			'$CFName'       => $this->CFName,
			'$CMName'        => $this->CMName,
			'$CLName' => $this->CLName ,
			'$ConfCode' => $this->ConfCode,
			'$CCompany' => $this->CCompany,
			'$OID'		=> $this->OID
		);

		$this->message = strtr($this->message, $vars);
		echo $this->message;die();
		
        $this->message .= "\n\n DIDX Email Manager ID";
        $this->message = str_replace("\n","<br>",$this->message);
        $this->message  = str_replace("TotalTestsLast7",$this->TotalTestsLast7,$this->message);

		//   $this->FailedSinceHoursVal @ TODO This property value set by the following function
		//   SendEmailForTestSingleToVendor

		//echo "this->FailedSinceHoursVal ".$this->FailedSinceHoursVal."<br>";
		//echo "this->message <br>".$this->message;die();

        //$this->message  = str_replace("TESTXTEST",$this->FailedSinceHoursVal,$this->message);

        $mail->AddCC('care@didx.net', 'DIDx.net');

		$mail->Subject = $this->subject;

		$mail->Subject = 'Test';


		$mail->Body = $this->message;
        $mail->AddAddress($to);
        $mail->header=$header;
        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo;
            return $error ;
        } else {
            //$error = 'Message sent!';
            //return $error ;
			return true;
        }

    }

	function send_mail()
	{
		//echo "Inside send_mail()";die();
		$this->dated = date("D, d M Y H:i:s", time());
		$this->to = $this->CEmail;

		$this->headers = "";
		$this->headers .= "MIME-Version: 1.0\r\n";
		$this->headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$this->headers .= "Content-Transfer-Encoding: quoted -printable\r\n";

		$FromFrom = $this->from;

		$this->headers .= "From: $FromFrom \r\n";
		$this->headers .= "Cc: support@supertec.com,".$this->cc ."\r\n";

		$this->headers .= "Reply-To: ".$this->reply_to. "\r\n";
		$this->headers .= "Date: ".$this->dated ."\r\n";
		$this->message .= "\n\n DIDX Email Manager ID: $this->id";

		$this->message = str_replace("\n","<br>",$this->message);

		$this->message  = str_replace("TotalTestsLast7",$this->TotalTestsLast7,$this->message);
		// Nazir $this->message  = str_replace("TESTXTEST",$this->FailedSinceHoursVal,$this->message);

		$this->dPrint("\$done = mail($this->to, $this->subject, $this->message, $this->headers)");
		// return $this->smtpmailer($this->to,'warisha@supertec.com', 'Didx.net',$this->subject, $this->message);

		echo "To :".$this->to."<br>Subject : ".$this->subject."<br>Message : ".$this->message."<br><br><br>Header :". $this->headers;die();

		return mail($this->to, $this->subject , $this->message, $this->headers);

	}

	function SendConfirmationCode($OID){
		$this->OID = $OID;
		//echo $this->OID."<br><br><br>";
		$this->getParsedEmail("CONFCODE");
		$this->getCustomerInfo($OID);
		$this->ConfCode = $this->myUser->getConfirmationCode($OID);
		$bool = $this->smtpmailer('gmail');
		return $bool;
		// Nazir $this->replace();
//		$this->send_mail();
//		echo "working";die();
	}
	function getParsedEmail($emailID)
	{
		$dated = date("D, d M Y H:i:s", time());
		$RSEmail = $this->getEmail($emailID);
		//echo "<pre>";
		//print_r($RSEmail);die();
		# https://www.facebook.com/login.php?skip_api_login=1&api_key=966242223397117&signed_next=1&next=https%3A%2F%2Fwww.facebook.com%2Fsharer%2Fsharer.php%3Fu%3Dhttps%253A%252F%252Fwww.bitdegree.org%252Fen%252Ftoken%26t%3DBitDegree&cancel_url=https%3A%2F%2Fwww.facebook.com%2Fdialog%2Freturn%2Fclose%3Ferror_code%3D4201%26error_message%3DUser%2Bcanceled%2Bthe%2BDialog%2Bflow%23_%3D_&display=popup&locale=en_GB
		#echo "Inside getParsedEmail($emailID) function call return <br><br><br>".$RSEmail;die();
		if(!$RSEmail->EOF)
		{
			$this->id = $RSEmail->fields[0];
			$this->title=$RSEmail->fields[1];
			$this->description=$RSEmail->fields[2];
			$this->from=$RSEmail->fields[3];
			$this->reply_to =$RSEmail->fields[4];
			$this->cc=$RSEmail->fields[5];
			$this->subject = $RSEmail->fields[6];
			$this->message = $RSEmail->fields[7];
			$this->mail_status=$RSEmail->fields[8];
			$this->mail_dated=$RSEmail->fields[9];
			$this->sID=$RSEmail->fields[10];
		}
	}
	
	function getEmail($emailID)
	{
		//echo $emailID;die();
		if(is_numeric($emailID)){
			$sql_ = " where id='$emailID'";
		}else {
			$sql_ = " where SID='$emailID'";
		}
		$strSQL	= "select id, title, description, from_field, reply_to_field, cc_field, subject_field, 
		contents_field, status, DATE_FORMAT(date_added, '%d-%b-%y'), sID from email_settings $sql_";
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		// Debugging by Nazir
		//echo "<pre>";
		//echo print_r($rs)."<br><br><br>";
		return $rs;
	}

	function getCustomerInfo($OID)
	{
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);

		//echo '<pre>';
		//print_r($customer);die();

		$this->CustomerID  = $customer['CustomerID'];
		$this->CSalutation  = $customer['Salutation'];
		$this->CFName  = $customer['FName'];
		$this->CMName = $customer['MName'];
		$this->CLName  = $customer['LName'];
		$this->CEmail  = $customer['CEmail'];
		$this->CCompany  = $customer['CCompany'];
	}



	function send_mailHTML()
	{
		$this->dated = date("D, d M Y H:i:s", time());
//		$this->to = 'arfeen@supertec.com';
		$this->to = $this->CEmail;

		$this->headers = "";
		$this->headers .= "MIME-Version: 1.0\r\n";
		$this->headers .= "Content-type: text/html;\r\n";
		$this->headers .= "Content-Transfer-Encoding: quoted -printable\r\n";

		$FromFrom = $this->from;

		$this->headers .= "From: $FromFrom <$FromFrom> \r\n";
		$this->headers .= "Cc: ".$this->cc ."\r\n";
		$this->headers .= "Reply-To: ".$this->reply_to. "\r\n";
		$this->headers .= "Date: ".$this->dated ."\r\n";
		#$this->headers .= "BCc: ".$this->cc .", arfeen@supertec.com\r\n";
		$this->message .= "\n\n DIDX Email Manager ID: $this->id";
//		$this->headers .= "BCc: arfeen@supertec.com\r\n";


		$this->message = str_replace("\n","<br>",$this->message);
		#echo $this->TotalTestsLast7  ;
		$this->message  = str_replace("TotalTestsLast7",$this->TotalTestsLast7,$this->message);
		$this->message  = str_replace("TESTXTEST",$this->FailedSinceHoursVal,$this->message);
		#echo $this->DID;
		#$this->subject  = str_replace("DIDNUMBERVAR",$this->DID,$this->subject);
		#$this->message  = str_replace("DIDNUMBERVAR",$this->DID,$this->message);

		$this->dPrint("\$done = mail($this->to, $this->subject, $this->message, $this->headers)");
		return mail($this->to, $this->subject, $this->message, $this->headers);
	}
	function SendChangemail($OID,$ffNewEmail,$ConfermationKey,$Todays)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail('sendchangeemail');
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$this->dPrint ("\$customerID: $customerID");
		$CName = "$CFName $CMName $CLName";
		$subject = $this->subject;
		#$subject = $email2bSent[Subject];
		$from = $this->from;
		#$cc = $email2bSent[Cc];
		$cc =$this->cc;
		#$reply_to = $email2bSent[Reply-to];
		$reply_to = $this->reply_to;


		######
//			$this->from=$RSEmail->fields[3];
//			$this->reply_to =$RSEmail->fields[4];
//			$this->cc=$RSEmail->fields[5];
		######
		#$message=$email2bSent[Contents];
		$message=$this->message;
		#echo "<br>\$message $message";
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$Cemail", $CEmail, $message);
		$message = str_replace("\$newCemail",$ffNewEmail, $message);
		$message = str_replace("\$confCode", $ConfermationKey, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$Date", $Todays, $message);
		$to = $ffNewEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		//$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function SendChangemailConfirm($OID,$OldEmail)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail('sendchgemailcon');
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$this->dPrint ("\$customerID: $customerID");
		$CName = "$CFName $CMName $CLName";
		$subject = $this->subject;
		#$subject = $email2bSent[Subject];
		$from = $this->from;
		#$cc = $email2bSent[Cc];
		$cc =$this->cc;
		#$reply_to = $email2bSent[Reply-to];
		$reply_to = $this->reply_to;


		######
//			$this->from=$RSEmail->fields[3];
//			$this->reply_to =$RSEmail->fields[4];
//			$this->cc=$RSEmail->fields[5];
		######
		#$message=$email2bSent[Contents];
		$message=$this->message;
		#echo "<br>\$message $message";
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$CEmail", $CEmail, $message);
		$message = str_replace("\$OldEmail ",$OldEmail, $message);

//		$message = str_replace("\$OID", $OID, $message);
//		$message = str_replace("\$Date", $Todays, $message);
		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		//$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		 mail($to, $subject, $message, $headers);
	}
    function SendChangingEmail($OID,$OldEmail,$ConCode)
    {
        $dated = date("D, d M Y H:i:s", time());
        $email2bSent = $this->getParsedEmail('SendChangingEma');
        //get customer info
        $customer = $this->myCustomer->getCustomerInfoByUID($OID);
        $CustomerID  =$customer[CustomerID];
        $CSalutation  =$customer[Salutation];
        $CFName  =$customer[FName];
        $CLName  =$customer[LName];
        $CEmail  =$customer[CEmail];
        $this->dPrint ("\$customerID: $customerID");
        $CName = "$CFName $CMName $CLName";
        $subject = $this->subject;
        #$subject = $email2bSent[Subject];
        $from = $this->from;
        #$cc = $email2bSent[Cc];
        $cc =$this->cc;
        #$reply_to = $email2bSent[Reply-to];
        $reply_to = $this->reply_to;


        ######
//            $this->from=$RSEmail->fields[3];
//            $this->reply_to =$RSEmail->fields[4];
//            $this->cc=$RSEmail->fields[5];
        ######
        #$message=$email2bSent[Contents];
        $message=$this->message;
        #echo "<br>\$message $message";
        //replacing variables
        $message = str_replace("\$CName", $CName, $message);
        $message = str_replace("\$CEmail", $CEmail, $message);
        $message = str_replace("\$OldEmail ",$OldEmail, $message);
        $message=str_replace("\$ConfirmationC",$ConCode,$message);

//        $message = str_replace("\$OID", $OID, $message);
//        $message = str_replace("\$Date", $Todays, $message);
        $to = $CEmail;
        $headers = "";
        $headers .= "From: $from \r\n";
        $headers .= "Reply-To: $reply_to \r\n";
        $headers .= "Date: $dated \r\n";
        $this->dPrint("\$dated: $dated");
        //$headers .= "Cc: $cc \r\n";
        //$headers .= "MIME-Version: 1.0\r\n";
        $this->dPrint("\$done = mail($to, $subject, $message, $headers)");
         mail($to, $subject, $message, $headers);
    }
	function SendBulkOrderEmailToClient($OID,$Qty,$DIDNumbers){

		$this->OID = $OID;
		$this->getParsedEmail("SDBULKORDER");
		$this->getCustomerInfo($OID);

        $this->DIDQty = $Qty;
        $this->DID = $DIDNumbers;

		$this->replace();
		$this->send_mail();


	}
    //Huzoor Bux 30-07-2011
    function SendBulkOrderIncompleteEmailToClient($OID,$Qty,$DIDNumbers,$Allocated){

        $this->OID = $OID;
        $this->getParsedEmail("SDBULKORDERALLO");
        $this->getCustomerInfo($OID);

        $this->DIDQty = $Qty;
        $this->DID = $DIDNumbers;
        $this->AllocatedDIDQty = $Allocated;

        $this->replace();
        $this->send_mail();


    }
    function SendBulkOrderZeroDIDSEmailToClient($OID,$Qty){

        $this->OID = $OID;
        $this->getParsedEmail("SDBULKORDERZERO");
        $this->getCustomerInfo($OID);

        $this->DIDQty = $Qty;

        $this->replace();
        $this->send_mail();


    }
    //Huzoor Bux 30-07-2011
	function SendBackOrderEmailToVendor($OID,$CCode,$ACode,$RC,$NXX,$Qty)
	{
		$this->OID = $OID;
		$this->getParsedEmail("SBKTOVENDOR");
		$Vendor = $this->getVendorEmail($OID);

		$this->CEmail = $Vendor['Email'];
	#s	$this->CEmail = "arfeenster@gmail.com";
		$this->CCompany = $Vendor['Company'];
	#	echo "<br>\$CCode: $CCode"; echo "\$ACode: $ACode" ; echo "\$RC: $RC"; echo "\$NXX: $NXX" ;
		$this->CountryCode = $CCode;
		$this->AreaCode		 = $ACode;
		$this->RateCenter	 = $RC;
		$this->Qty	 = $Qty;
		#echo $this->CountryCode; echo $this->AreaCode; echo $this->RateCenter; echo $this->NXX;
		$this->NXX	 = $NXX;

		$this->replace();
		$this->send_mail();
	}
	function SendBackOrderEmailToUser($OID,$CCode,$ACode,$RC,$NXX,$Qty,$BO)
	{
		$this->OID = $OID;
		$this->getParsedEmail("SENDBOUSER");
		$this->getCustomerInfo($OID);
	#	echo "<br>\$CCode: $CCode"; echo "\$ACode: $ACode" ; echo "\$RC: $RC"; echo "\$NXX: $NXX" ;
		$this->CountryCode = $CCode;
		$this->AreaCode		 = $ACode;
		$this->RateCenter	 = $RC;
		$this->Qty	 = $Qty;
		#echo $this->CountryCode; echo $this->AreaCode; echo $this->RateCenter; echo $this->NXX;
		$this->NXX	 = $NXX;
			$this->BackOrderRef	 = $BO;

		$this->replace();
		$this->send_mail();
	}
	function getVendorEmail($pOID){

		$strSQL = "select backorderemail,ccompany from customer,EmailPref where 
EmailPref.UID=customer.UID and EmailPref.UID = \"$pOID\"   ";
							#echo $strSQL;
		$rs=$this->Db->query($strSQL);

		$email = $rs->fields[0];
		$company = $rs->fields[1];
		#echo "\$email: $email";
		$Hash['Email'] = $email;
		$Hash['Company'] = $company;

		return $Hash;
	}
	function SendUploadedDocEmail($OID,$DID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("SENDDOCEMAIL");
		$this->getCustomerInfo($OID);
		$this->DID = $DID;
		$this->replace();
		$this->send_mail();
	}
	function SendVendorEmailForNodeID($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("VENDORNODEEMAIL");
		$this->getCustomerInfo($OID);
		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);
		$this->replace();
		$this->send_mail();
	}
	function SendLostPassword($OID)
    {
        $this->OID = $OID;
        $this->getParsedEmail("SENDPASS");
        $this->getCustomerInfo($OID);
        $this->Password = $this->myUser->getPassCode($OID);

        $this->replace();
        // $this->send_mail();
        $this->smtpmailer('gmail');
    }
    function SendLostPasswordLink($OID)
	{

		$myCustomer = new Customer();
		$this->OID = $OID;
		$this->getParsedEmail("SENDPASSLINK");
		$this->getCustomerInfo($OID);
		$this->Password = $GLOBALS['website_url']."/lost?encrypt=".md5(base64_encode(date("d-m-Y"))."__++__".(date("d")+$OID+20000)."__++__DIDX.Net")."$OID";
		$this->replace();

		$this->smtpmailer('gmail');
	}
	function SendChangedPassword($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("SENDCHPASS");
		$this->getCustomerInfo($OID);
		$this->Password = $this->myUser->getPassCode($OID);
		$this->replace();
		$this->send_mail();
	}
	function SendTalkTimeMail($OID,$pDID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("SENDTTAGI");
		$this->getCustomerInfo($OID);
		$this->DID = $pDID;
		$this->replace();
		$this->send_mail();
	}
	function SendIncomingMinutesEMail($OID,$pDID)
	{
		$strSQL = "select TTMinsEmail from EmailPref where UID=\"$OID\" ";
		$rs=$this->Db->query($strSQL);
		$IfSend = $rs->fields[0];

		if($IfSend==0)
				return;

		$strSQL = "select DIDS.FreeMin,MinSpent from MinutesInfo,DIDS
 		where MinutesInfo.oid=\"$OID\"  and did=\"$pDID\" and DIDNumber=DID ";
		$rs=$this->Db->query($strSQL);
		$this->TotalMinutes = $rs->fields[0];
		$this->TotalMinUsed = $rs->fields[1];

		$this->OID = $OID;
		$this->getParsedEmail("SENDMINEMAIL");
		$this->getCustomerInfo($OID);
		$this->DID = $pDID;



		$this->replace();
		$this->send_mail();
	}
	function SendIncomingTriggersEMail($OID,$pDID)
	{
		$strSQL = "select TTMinsEmail from EmailPref where UID=\"$OID\" ";
		$rs=$this->Db->query($strSQL);
		$IfSend = $rs->fields[0];

		if($IfSend==0)
				return;

		$strSQL = "select DIDS.TriggerMin,TriggersUsed from MinutesInfo,DIDS
 		where MinutesInfo.oid=\"$OID\"  and did=\"$pDID\" and DIDNumber=DID ";
		$rs=$this->Db->query($strSQL);
		$this->TotalMinutes = $rs->fields[0];
		$this->TotalMinUsed = $rs->fields[1];

		$this->OID = $OID;
		$this->getParsedEmail("SENDTRIGEMAIL");
		$this->getCustomerInfo($OID);
		$this->DID = $pDID;



		$this->replace();
		$this->send_mail();
	}
	function SendReferFriendEmail($OID,$Ref,$RefEmail,$RCode)
	{
		$this->OID = $OID;
		$this->getParsedEmail("REFERFREN");
		$this->getCustomerInfo($OID);

		$customer = $this->myCustomer->getCustomerInfoByUID($OID);

		$CEmail  =$customer[CEmail];
		$this->from = $CEmail ;
		$this->CReferred = $Ref;
		$this->RefCode = $RCode;
		$this->RefEmail = $RefEmail;
		$this->replace();
		$this->CEmail = $RefEmail;

		$this->send_mail();
	}
	function SendSuspensionEmailOnNonPayment($OID,$pSusDate)
	{
		$this->OID = $OID;

		$strSQL = "select CusType from orders where OID=\"$OID\" ";
		$rs=$this->Db->query($strSQL);

		if($rs->fields[0]==1)
							return;

		$this->getParsedEmail("SUSONDUE");
		$this->getCustomerInfo($OID);
		$this->getDaysDue($OID);
		$this->getAmountDue($OID);
		$this->SusDate = $pSusDate;
		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);
		$this->replace();
		$this->send_mail();
	}
	function SendtransactionApproved($OID,$Amount,$Name,$AVSCodeAll,$Subject,$last4numbers) {



//		$this->CountTesting =$TotalTes;
//		$this->NtWorkinSince =$FaulSince;
//		$this->TimeCurrent   =$FirstTime;
//		$this->DateFormated =$FirstDate;

		$this->Amount =$Amount;
		$this->Name =$Name;
		$this->AVSCodeAll =$AVSCodeAll;
		$this->mySubject=$Subject;
		$this->last4numbers=$last4numbers;
		$this->OID = $OID;
//		$this->TestStatus = $STA;
//		$this->DID = $DIDNo;
		$this->getParsedEmail("tranApp");
		$this->getCustomerInfo($OID);
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->replace();
		$this->send_mail();

	}
	function SendMonthlyInvoiceMail($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("MONINV");
		$this->getCustomerInfo($OID);
		#$this->getCustomerInfo($OID);
		$strSQL = "select MTC from EmailPref where UID=\"$OID\" ";
		$rs=$this->Db->query($strSQL);
		$this->MTF = $rs->fields[0];
		$this->replace();
		$this->send_mail();
	}
	function SendDIDSWarningEmail($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("WARN");
		$this->getCustomerInfo($OID);

		$this->getTotalDIDSCount($OID);

		$this->replace();
		$this->send_mail();
	}
	function SendAwaitingToActiveEmail($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail("SENDACFRMAW");
		$this->getCustomerInfo($OID);

		$this->replace();
		$this->send_mail();
	}
	function SendWelcomeMessage($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('WELCOME');
		$this->getCustomerInfo($OID);
		$user = $this->myUser->getUserInfo($OID);
		$this->Password  = $user[Pass];



		$this->replace();
		$this->send_mail();
	}
	function SendCCExpiryEmail($OID,$CCID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDCCEXPIRE');
		$this->getCustomerInfo($OID);

		$strSQL = "SELECT SUBSTRING(Number,LENGTH(number)-3,4),Type FROM creditcard WHERE ccid=\"$CCID\" ";
		//echo $strSQL;
		$rs=$this->Db->query($strSQL);

		$Ending = $this->Encrption->decrypt($rs->fields[0]);
		$TYPE = $rs->fields[1];

		$this->ENDING = $Ending;
		$this->CCTYPE = $TYPE;
		$this->AmountDue = $this->tr->getOutStandingBalance($OID);
		$this->OID = $OID;

		$this->replace();
	#	$this->CEmail = "arfeenster@gmail.com";
		$this->send_mail();
	}
	function SendRefundEmail($OID,$DID,$Month,$TrID,$Amount)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDRFEMAIL');
		$this->getCustomerInfo($OID);

		$this->TID = $TrID;
		$this->Month = $Month;
		$this->DID = $DID;
		$this->Amount = $Amount;

		$this->replace();
		$this->send_mail();
	}
	function SendServerMisMatchEmail($OID,$ServerSending,$ServerLive,$DID)
	{
		$this->OID = $OID;

		#if($OID!="701353")
			#return;
		$this->getParsedEmail('SENDADMINSERVER');

		if($ServerSending==$ServerLive)
				return;

		$strSQL	= "select CallCount from CountVendorCalls where OID=\"$OID\" ";
	#	echo "<br>\$strSQL: $strSQL";
		$rs=$this->Db->query($strSQL);

		if($rs->EOF){

			$strSQL	= "insert into CountVendorCalls(OID,CallCount,serversending)values(\"$OID\",\"1\",\"$ServerLive\") ";
		#	echo "<br>\$strSQL: $strSQL";
			$rs=$this->Db->query($strSQL);

		}else{

			$strSQL	= "update CountVendorCalls set CallCount=CallCount+1,serversending=\"$ServerLive\" where OID=\"$OID\"  ";
		#	echo "<br>\$strSQL: $strSQL";
			$rs=$this->Db->query($strSQL);

				$strSQL	= "select CallCount from CountVendorCalls where OID=\"$OID\" ";
			#	echo "<br>\$strSQL: $strSQL";
				$rs=$this->Db->query($strSQL);

				if($rs->fields[0]>=10){
					if($ServerSending=="")
							$ServerSending="<a href='http://sandbox.didx.net/cgi-bin/virtual/admins/GeneralClient.cgi?OID=$OID'>(server not set, set now)</a>";
					$this->SERVER_LIVE = $ServerLive;
					$this->SERVER_SEND = $ServerSending;
					$this->DID = $DID;

					$this->CEmail = "dan@supertec.com";

					$this->replace();

				#	echo "Sending Now";
					$this->send_mailHTML();

					$strSQL	= "update CountVendorCalls set CallCount=0 where OID=\"$OID\"  ";
				#echo "<br>\$strSQL: $strSQL";
					$rs=$this->Db->query($strSQL);

					return;
				}



		}




	}
	function SendMoneyBookerPaymentEmail($OID,$MBID,$Amount)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDMBPAYMENT');
		$this->getCustomerInfo($OID);
		$this->Amount=$Amount;
		$this->MoneyBookersID=$MBID;
		$this->OID = $OID;
		$this->replace();
		$this->send_mail();
	}
	function SendEmailOnFreeDIDRelease($OID,$DID,$Date)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDFREEREL');
		$this->getCustomerInfo($OID);

		$this->DID = $DID;
		$this->Date = $Date;

		$this->replace();
		$this->send_mail();
	}
	function SendEmailToBuyerForPaymentTransfer($OID,$Amount,$ACTNO)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDPTBUYER');
		$this->getCustomerInfo($OID);

		$this->ACTNO = $ACTNO;
		$this->Amount = $Amount;

		$this->replace();
		$this->send_mail();
	}
	function SendEmailToSellerForPaymentTransfer($OID,$Amount,$ACTNO)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDPTSELLER');
		$this->getCustomerInfo($OID);

		$this->ACTNO = $ACTNO;
		$this->Amount = $Amount;

		$this->replace();
		$this->send_mail();
	}
	function SendEmailViaPayPalPayment($OID,$Amount,$TrID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDPAYVIAPP');
		$this->getCustomerInfo($OID);

		$this->PaypalTID = $TrID;
		$this->Amount = $Amount;

		$this->replace();
		$this->send_mail();
	}
	function SendEmailViaMoneyBookersPayment($OID,$Amount,$TrID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDVIAMB');
		$this->getCustomerInfo($OID);

		$this->PaypalTID = $TrID;
		$this->Amount = $Amount;

		$this->replace();
		$this->send_mail();
	}
	function SendAccountApproveEmail($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDEMAILAPP');
		$this->getCustomerInfo($OID);


		$this->replace();
		$this->send_mail();
	}
	function SendAccountDisApproveEmail($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDDISAPP');
		$this->getCustomerInfo($OID);


		$this->replace();
		$this->send_mail();
	}
	function SendEmailOnCreditUsed($OID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDCRLIMIT');
		$this->getCustomerInfo($OID);
		$MyOrders = $this->myOrder->getOrdersInfoByUID($OID);

		$CreditLimit = $MyOrders[CLimit];
		$this->getAmountDue($OID);
	#	echo $CreditLimit;
		if($this->AmountDue>0 && $this->AmountDue<=$CreditLimit){
				$this->Remaining = $CreditLimit - $this->AmountDue;

				$this->replace();
				$this->send_mail();

		}
	}
	function SendVedorToAckPolReq($OID,$DID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDVENREQPOL');
		$this->getCustomerInfo($OID);
	#	$user = $this->myUser->getUserInfo($OID);
	#	$this->Password  = $user[Pass];
		$this->DID = $DID;
		$this->replace();
		$this->send_mail();
	}
	function SendEmailToReqPersonForAvail($MyID,$OID,$Price,$Qty,$Country,$Area)
	{
		$this->OID=$OID;
		$this->getParsedEmail('SENDREQMAIL');
		$this->getCustomerInfo($OID);
		$this->STARTINGPRICE=$Price;
		$this->QTYTHEYREQUESTED=$Qty;

		$this->Country=$Country;
		$this->AreaName=$Area;
		$this->YESURL="<a href=\"http://sandbox.didx.net/RequestFill.php?id=$MyID\">Yes</a>";
		$this->NOURL="<a href=\"http://sandbox.didx.net/RemoveMe.php?id=$MyID\">No</a>";
		$this->REMOVEURL="<a href=\"http://sandbox.didx.net/RemoveMe.php?id=$MyID\">Please remove my Request</a>";
		$this->replace();
		$this->send_mailHTML();
	}
	function SendWarningLevelEmail($OID,$AreaID,$Qty){

		$this->GetAreaDetaiByAreaID($AreaID);


		$this->Vendor = $OID;
	#	$this->Prefix = $Prefix;
		#$this->AreaName = $AreaName;
		$this->DIDQty = $Qty;

		$this->getParsedEmail('SENDWARLEVEL');




		$this->replace();
		$this->CEmail = "care@didx.net";
		$this->send_mail();

	}
	function GetAreaDetaiByAreaID($pArea){


		$strSQL = "select DIDCountries.CountryCode,DIDCountries.Description, DIDArea.StateCode,DIDArea.Description as a
		from DIDCountries,DIDArea where DIDArea.ID=\"$pArea\" and DIDCountries.Id=DIDArea.CountryID";
		#echo $strSQL;
		$rs=$this->Db->query($strSQL);

		$CountryCode = $rs->fields[0];
		$CountryName = $rs->fields[1];

		$AreaCode 	 = $rs->fields[2];
		$AreaName		 = $rs->fields[3];

		$this->Prefix = "$CountryCode - $AreaCode";
		$this->AreaName = "$CountryName - $AreaName";



	}
	function SendEmailOnDocumentApproval($OID,$DID)
	{

			$strSQL	= "select SendDIDRel from EmailPref where UID=\"$OID\" ";

		$rs=$this->Db->query($strSQL);

			if(!$rs->fields[0])
					return;

		$this->OID = $OID;
		$this->getParsedEmail('SENDEMAILDOCAPP');
		$this->getCustomerInfo($OID);
		$this->DID = $DID;

		$this->replace();
		$this->send_mail();
	}
	function SendEmailOnDocumentRefusal($OID,$DID,$Reason)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDEMAILDOCREF');
		$this->getCustomerInfo($OID);
		$this->DID = $DID;
		$this->Reason = $Reason;
		$this->replace();
		$this->send_mail();
	}
	function SendRefundMail($OID,$DID,$TransID,$pAmount)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDREFUNDTEST');
		$this->getCustomerInfo($OID);
		$user = $this->myUser->getUserInfo($OID);


		$this->DID = $DID;
		$this->TrandID = $TransID;
		$this->Amount = $pAmount;

		$this->replace();
		$this->send_mail();
	}
	function SendDocsNotice($OID,$ID)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDDOCNOTICE');
		$this->getCustomerInfo($OID);
		$user = $this->myUser->getUserInfo($OID);
		$this->Password  = $user[Pass];

		$strSQL	= "select DocMsg from DocMsg where ID=\"$ID\" ";
		$rs=$this->Db->query($strSQL);

		$this->DocMsg =  $rs->fields[0];

		$this->replace();
		$this->send_mail();
	}
	function SendEmailOnDeletetion($OID,$pDID)
	{
		$this->OID = $OID;
		$this->DID = $pDID;
		$this->getParsedEmail('SENDDELEMAIL');
		$this->getCustomerInfo($OID);
		#$user = $this->myUser->getUserInfo($OID);
		#$this->Password  = $user[Pass];
		$this->replace();
		// $this->send_mail();
		$this->smtpmailer('gmail');
	}
	function SendCSVPrepared($OID,$FileType)
	{
		$this->OID = $OID;
		$this->getParsedEmail('SENDEMAILCSV');
		$this->getCustomerInfo($OID);
		$user = $this->myUser->getUserInfo($OID);

			if($FileType==0)
					$this->CSVType = "Purchased DIDs";

			if($FileType==1)
					$this->CSVType = "Account Ledger";

			if($FileType==2)
					$this->CSVType = "DID Call Logs";

			if($FileType==6)
                    $this->CSVType = "DID Billing Report";

            if($FileType==10)
					$this->CSVType = "Talk Time Usage Report";

		$this->replace();
		$this->send_mail();
	}
	function SendSuspensionEmailOnCall($OID,$DID)
	{
		$this->OID = $OID;
		$this->CalledDID = $DID;
		$this->getParsedEmail('SUSONCALL');
		$this->getCustomerInfo($OID);
		$this->getCurrentDateTimeForDisplay();
		$this->replace();
		$this->send_mail();
	}
	function SendYourDIDSold($DID)
	{
		$this->getParsedEmail('DIDSOLD');
		$this->DID = $DID;
		$this->OID = $this->did->getVendorIDByDID($DID);
		$EmailPref = $this->myCustomer->getCustomerEmailPref($this->OID);
		if($EmailPref['DIDSold'] == 1)
		{
			#$this->getParsedEmail('DIDSOLD');
			$this->getCustomerInfo($this->OID);
			#$this->replace();

		}else {

			return 1;
			#$this->getCustomerInfo($this->OID);


		#	$this->CEmail = $this->cc;
		}
		$this->replace();
		$this->send_mail();
	}
	function TicketEmailToAdmin($OID,$MSG,$TCK,$ADEMAIL)
	{
		$this->OID = $OID;
		$this->MessageComplain = $MSG;
		$this->getParsedEmail("SENDTICADMIN");
		$this->getCustomerInfo($OID);
		$this->TicketNumber = $TCK;
		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();
		#$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern2();
		$this->CTel = $this->Adrs->getTelephoneNumber($OID);
		$this->CAddress = $this->Adrs->getAddressString($OID);
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$this->CCompany  = $customer[CCompany];
		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);
		$this->replace();
		$this->CEmail = $ADEMAIL;
		$this->send_mail();
	}
//	function TicketEmailToClient($OID,$MSG,$TCK)
//	{
//		$this->OID = $OID;
//
//		$this->MessageComplain= $this->Comp->getComplainByTicketNumber($TCK);
//		$this->getParsedEmail("SENDTICCLIENT");
//		$this->getCustomerInfo($OID);
//		$this->TicketNumber = $TCK;
//		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();
//		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern2();
//		$this->CTel = $this->Adrs->getTelephoneNumber($OID);
//		$this->CAddress = $this->Adrs->getAddressString($OID);
//		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);
//		$this->replace();
//		$this->send_mail();
//	}
	function TicketEmailToClient($OID,$MSG,$TCK)
	{
		$this->OID = $OID;

		$this->MessageComplain= $this->Comp->getComplainByTicketNumber($TCK);
		$this->getParsedEmail("SENDTICCLIENT");
		$this->getCustomerInfo($OID);
		$this->TicketNumber = $TCK;
		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern2();
		$this->CTel = $this->Adrs->getTelephoneNumber($OID);
		$this->CAddress = $this->Adrs->getAddressString($OID);
		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);
		$this->replace();
		$this->send_mail();
	}
	function SendFreshTicketEmailToClient($OID,$TCK)
	{
		$this->OID = $OID;

		#$this->MessageComplain= $this->Comp->getComplainByTicketNumber($TCK);
		$this->getParsedEmail("SENDTCLIENT");
		$this->getCustomerInfo($OID);
		$this->TicketNumber = $TCK;
		$this->replace();
		$this->send_mail();
	}
	function SendEmailToCustomerWhenTicketIsOpened($OID,$TCK,$Date)
	{
		$this->OID = $OID;


		$this->getParsedEmail("SENDOPTICKREMIN");
		$this->getCustomerInfo($OID);
		$this->TicketNumber = $TCK;
		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern2();

		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);
		$this->replace();
		$this->send_mail();
	}
	function ThreadReplyToClient($OID,$TCK,$THID,$From="ADMIN")
	{
		$this->OID = $OID;
		$this->MessageComplain= $this->Comp->getComplainByTicketNumberFormated($TCK);
		$this->getParsedEmail("SENDTHREPLY");
		$this->getCustomerInfo($OID);
		$this->TicketNumber = $TCK;
		$this->MessageComplainReply = $this->Comp->GetAllThreadsByTicketNumber($TCK,$THID);
		$this->UpdatedThread =$this->Comp->GetThreadByTicketNumber($TCK,$THID);
		$this->MessageComplainReply = str_replace("<br>","\r\n",$this->MessageComplainReply);
		$this->UpdatedThread = str_replace("<br>","\r\n",$this->UpdatedThread);

		$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();
		$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern2();
		$this->CTel = $this->Adrs->getTelephoneNumber($OID);
		$this->CAddress = $this->Adrs->getAddressString($OID);
		#$this->ConfCode = $this->myUser->getConfirmationCode($OID);

        $strSQL    = "select TechContactEmail,TicketEmail from EmailPref where  uid=\"$OID\" ";
        $this->dPrint ("\$strSQL: $strSQL");
        $rs=$this->Db->query($strSQL);
        $TickOp     = $rs->fields[1];
        $TickEmail  = $rs->fields[0];
        if($TickOp==2 && $TickEmail !="")
        {
            $this->CEmail = $TickEmail;
        }
        else if($TickOp==3 && $TickEmail!="")
        {
            $this->CEmail .= ",".$TickEmail;
        }
    	$this->replace();
		if($From=="CLIENT")
			$this->CEmail = "care@didx.net";
		$this->send_mail();
	}
	function ThreadClosedToClient($OID,$TCK)
	{
		$this->OID = $OID;
		#$this->MessageComplain= $this->Comp->getComplainByTicketNumberFormated($TCK);
		$this->getParsedEmail("SENDEMAILRESOLV");
		$this->getCustomerInfo($OID);
		$this->TicketNumber = $TCK;

		$this->AdminListInvolved = $this->GetAdminInvolved($TCK);
		$this->TicketHours = $this->GetTicketHours($TCK);
		$this->BadLink = $this->GetLoginLink(0,$OID,$TCK);
		$this->GoodLink = $this->GetLoginLink(1,$OID,$TCK);

		#$this->FormatedDateTime = $this->DTMod->getCurrentFormatedDateTimePattern1();
		#$this->FormatedDateTime2 = $this->DTMod->getCurrentFormatedDateTimePattern2();

		$this->replace();

		$this->send_mail();
	}
	function GetAdminInvolved($TCK){
		$strSQL = "select writtenby,id from ComplainThread where ComplainID=\"$TCK\" group by WrittenBy";
#		echo $strSQL;
		$Result = $this->Db->ExecuteQuery($strSQL);

			while(!$Result->EOF){
				$Admin = $Result->fields[0];

				if(is_numeric($Admin) || $Admin==""){

				}else{

				$AdminList .= "\n" . $this->AdminUser->getAdminNameByUIDAUID($Admin) . "\r\n";
				}

				$Result->MoveNext();
			}

		return $AdminList;
	}
	function GetLoginLink($GoodBad,$OID,$TCK) {

		$strSQL = "select md5(concat(UID,Pass,'1000')),md5(UID) from user where uid=\"$OID\" ";
		#echo $strSQL;
		$Result = $this->Db->ExecuteQuery($strSQL);

		$Login = $Result->fields[0];
		$Key = $Result->fields[1];



		$LoginLink = "<a href='http://sandbox.didx.net/LoginAction.php?login=$Login&keyval=$Key&go=http://sandbox.didx.net/FeedBack.php?complainid=$TCK&rate=$GoodBad'>click here</a>";

			return $LoginLink;

	}
	function GetTicketHours($TicketNumber) {

	$myADb = new ADb();

	$strSQL = "select datetime from complain where complainid=\"$TicketNumber\" ";
#	echo $strSQL;
	$Result = $this->Db->ExecuteQuery($strSQL);

	$pDateFrom = $Result->fields[0];


	$strSQL=" SELECT to_days(now())-to_days('$pDateFrom')";
	#echo "\$strSQL: $strSQL";
	$Result	= $this->Db->ExecuteQuery($strSQL);

	$Days = $Result->fields[0];

	if($Days<=0){

	$strSQL=" SELECT period_diff(substring(now(),12,8),substring('$pDateFrom',12,8))";
	#echo "\$strSQL: $strSQL";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$Hours = $Result->fields[0];

	}else{

	$strSQL=" SELECT period_diff(substring(now(),12,8),substring('$pDateFrom',12,8))";
	#echo "\$strSQL: $strSQL";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$HoursMore = $Result->fields[0];

	$Hours = $Days * 24;

	$Hours = $Hours + $HoursMore;

	}

	return $Hours;




	}
	function SendCreditCardReceivedCustomer($transactionID)
	{
		$this->getParsedEmail('CCRCVD');
		$Txn = $this->tr->getTransactionDetails($transactionID);
		$this->OID = $Txn['OID'];
		$this->amount = $Txn['Amount'];
		$this->Amount = $Txn['Amount'];
		$this->getCustomerInfo($this->OID);
		$Hash = $this->myCreditCard->getCreditCardStart4Last4Digit($this->OID);
		$this->last4digits = $Hash['EndFour'];
		$this->start4digits = $Hash['StartFour'];
		$this->CCType =  $Hash['Type'];
		$this->replace();
		$this->send_mail();
	}
	function SendOutstandingbal($OID,$pOUTB)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail('Outstandingbal');
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer['CustomerID'];
		$OID				 =$customer['UID'];
		$CSalutation  =$customer['Salutation'];
		$CFName  =$customer['FName'];
		$CLName  =$customer['LName'];
		$CEmail  =$customer['CEmail'];
		$this->dPrint ("\$customerID: $customerID");
		$CName = "$CFName $CMName $CLName";
		#$subject = $email2bSent[Subject];
		$subject = $this->subject;
		#$from = $email2bSent[From];
		$from = $this->from;
		#$cc = $email2bSent[Cc];
		$cc = $this->cc;
		#$reply_to = $email2bSent[Reply-to];
		$reply_to = $this->reply_to;
		#$message=$email2bSent[Contents];
		$message=$this->message;
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$CEmail", $CEmail, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$pOUTB", $pOUTB, $message);
		$message = str_replace("\$doller", "$", $message);
		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Cc: $cc \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		//echo "\$done = mail($to, $subject, $message, $headers)";
		return mail($to, $subject, $message, $headers);
	}
	function SendCCDeclined($LPID, $Amount,$ErrorMessage,$Action)
	{
		$this->getParsedEmail('CCDECLINED');
		$Hash = $this->myCreditCard->getCCHistoryInfoByLPID($LPID);
		$this->OID = $Hash['UserID'];
		#$this->OID = 702845; # sarmad's OID

		$this->amount = $Amount;
		$this->Amount = $Amount;
		$this->Action = $Action;
		$this->ErrorMessage = $ErrorMessage;

		$this->getCustomerInfo($this->OID);
		$CCard = $this->myCreditCard->getCreditCardInfoByCustomerID($this->CustomerID);
		$this->last4digits = $CCard['last4digits'];
		$this->replace();
		$this->send_mail();
	}
	function send2STARZONE2($OID)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail('2STARZONE2');
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$this->dPrint ("\$customerID: $customerID");
		$CName = "$CFName $CMName $CLName";
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$CEmail", $CEmail, $message);
		$message = str_replace("\$OID", $OID, $message);
		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function send4STARZONE3($OID)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail('4STARZONE3');
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$this->dPrint ("\$customerID: $customerID");
		$CName = "$CFName $CMName $CLName";
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$CEmail", $CEmail, $message);
		$message = str_replace("\$OID", $OID, $message);
		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		//$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function sendTicket($ComplainID)/////####Email id 2
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail(2);/////####Email id 2
		//get complain info
		$strSQL = "select OID, UID, Assign, Complain, Notify, DID from complain where ComplainID='$ComplainID'";
		$this->dPrint ("\$strSQL: $strSQL");
		$this->dPrint ("\$Notify: $Notify");
		$rs=$this->Db->query($strSQL);
		if($rs->EOF)	die("An error occured while sending ticket::1<br>");
		$OID= $rs->fields[0];
		$UID= $rs->fields[1];
		$Assign= $rs->fields[2];
		$Complain= $rs->fields[3];
		$Notify= $rs->fields[4];
		$DID= $rs->fields[5];
		$this->dPrint("list($OID, $UID, $Assign, $Complain, $Notify, $DID)");
		//get assignTo Info
		$to = "";
		//check if assigned to Tiers then mail to Tier members
		if($Assign=="Tier1" || $Assign=="Tier2" || $Assign=="Tier3")
		{
			$strSQL = "select AUEmail from virtual.adminuser where $Assign='1'";
			$this->dPrint("\$strSQL: $strSQL");
			$rs=$this->Db->query($strSQL);
			if($rs->EOF)	die("An error occured while sending ticket::4<br>");
			while(!$rs->EOF)
			{
				$AUEmail=$rs->fields[0];
				$to .= "$AUEmail, ";
				$rs->MoveNext();
			}
			if(!$to)	$to = "support@virtualphoneline.com";
		}
		else
		{
			$strSQL = "select AUFName, AULName, AUEmail from virtual.adminuser where AUID='$Assign'";
			$this->dPrint("\$strSQL: $strSQL");
			$rs=$this->Db->query($strSQL);
			if($rs->EOF)	die("An error occured while sending ticket::5<br>");
			$AUFName=$rs->fields[0];
			$AULName=$rs->fields[1];
			$AUEmail=$rs->fields[2];
			$to .= "$AUEmail";
			$Assign = "$AUFName $AULName";
		}
		//get submitter info
		//check in adminUser
		$strSQL = "select AUFName, AULName from adminuser where UID='$UID'";
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		if(!$rs->EOF)
		{
			$AUFName= $rs->fields[0];
			$AULName= $rs->fields[1];
			$AName = "$AUFName $AULName";
		}
		else
			$AName = "$UID";
		//get customer info
		$strSQL = "select CustomerID, concat(CSalutation,' ', CFName, ' ', CMName, ' ', CLName) CName, CEmail, CTelHome, AddressID, CCompany from customer where UID='$OID'";
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		if(!$rs)	die("An error occured while sending ticket::2<br>");

		$CustomerID = $rs->fields[0];
		$CName=$rs->fields[1];
		$CEmail=$rs->fields[2];
		$CTel=$rs->fields[3];
		$AddressID=$rs->fields[4];
		$CCompany=$rs->fields[5];
		$this->dPrint ("\$CustomerID: $CustomerID");
		//get ADDress
		$strSQL = "select Street1, Street2, City, State, ZIPCode, Country from address where AddressID='$AddressID'";
		$this->dPrint ("\$strSQL: $strSQL");
		$rs=$this->Db->query($strSQL);
		if($rs->EOF)	die("An error occured while sending ticket::3<br>");
		$Street1=$rs->fields[0];
		$Street2=$rs->fields[1];
		$City=$rs->fields[2];
		$State=$rs->fields[3];
		$ZIPCode=$rs->fields[4];
		$Country=$rs->fields[5];
		$CAddress = "$Street1, $City, $State, $Country";
		$subject = $email2bSent[Subject];
		$subject = str_replace("\$OID",$OID, $subject);//replace OID in subject
		$subject = str_replace("\$ComplainID",$ComplainID, $subject);//replace ComplainID in subject
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		$this->dPrint("\$message: $message");
		//replacing variables
		$message = str_replace("\$AName", $AName, $message);
		$message = str_replace("\$Assign", $Assign, $message);
		$message = str_replace("\$ComplainID", $ComplainID, $message);
		$message = str_replace("\$Complain", $Complain, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$DID", $DID, $message);
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$CAddress", $CAddress, $message);
		$message = str_replace("\$CEmail", $CEmail, $message);
		$message = str_replace("\$CTel", $CTel, $message);
		$message = str_replace("\$CCompany", $CCompany, $message);

		$headers = "";
		$headers .= "From: support@virtualphoneline.com\r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
//		$headers .= "Cc: $cc \r\n";
//		$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		mail($to, $subject, $message, $headers);
		//till now complain sent to customer

		//now mail to client
		if($Notify=='1')
		{
			$email2bSent = $this->getParsedEmail(3);/////####Email id 3
			$subject = $email2bSent[Subject];
			$subject = str_replace("\$OID",$OID, $subject);//replace OID in subject
			$subject = str_replace("\$ComplainID",$ComplainID, $subject);//replace ComplainID in subject
			$from = $email2bSent[From];
			$cc = $email2bSent[Cc];
			$reply_to = $email2bSent[Reply-to];
			$message=$email2bSent[Contents];
			$this->dPrint("\$message: $message");
			//replacing variables
			$message = str_replace("\$ComplainID", $ComplainID, $message);
			$message = str_replace("\$Complain", $Complain, $message);
			$message = str_replace("\$OID", $OID, $message);
			$message = str_replace("\$CName", $CName, $message);

			$headers = "";
			$headers .= "From: support@virtualphoneline.com\r\n";
			$headers .= "Reply-To: $reply_to \r\n";
			$headers .= "Date: $dated \r\n";
			$this->dPrint("\$dated: $dated");
			$headers .= "Cc: $cc \r\n";
			$to = "$CEmail";
			$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
			mail($to, $subject, $message, $headers);
		}
	}
/*	function sendWelcomeMessage($OID)
	{
		$this->dPrint ("\$OID: $OID");
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail(5);
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$this->dPrint ("\$CEmail: $CEmail");
		$user = $this->myUser->getUserInfo($OID);
		$Password  = $user[Pass];

		//replacing variables
		$message = str_replace("\$CFName", $CFName, $message);
		$message = str_replace("\$CMName", $CMName, $message);
		$message = str_replace("\$CLName", $CLName, $message);
		$message = str_replace("\$CSalutation", $CSalutation, $message);
		$message = str_replace("\$CEmail", $CEmail, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$Password", $Password, $message);

		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		$headers .= "Cc: $cc \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}*/
	function sendInternationalNotAccepted($OID)
	{
		$this->dPrint ("\$OID: $OID");
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail(9);
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$CName = "$CFName $CMName $CLName";
		$CCard = $this->myCreditCard->getCreditCardInfoByCustomerID($CustomerID);
		$last4digits = substr($CCard[Number], strlen($CCard[Number])-4);
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$last4digits", $last4digits, $message);

		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function sendAccountSuspended($OID)
	{
		$this->dPrint ("\$OID: $OID");
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail(11);
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$CName = "$CFName $CMName $CLName";
		//get order date
		$strSQL = "select date_format(orderdate, '%d-%b-%Y') from orders where oid='$OID'";
		$this->dPrint("\$strSQL:$strSQL");
		$rs=$this->Db->query($strSQL);
		if(!$rs->EOF)
		{
			$CreationDate = $rs->fields[0];
		}
		$this->dPrint("\$CreationDate:$CreationDate");
		$strSQL = "select date_format(Date, '%d-%b-%Y') from DeleteOrderLog where OID='$OID' and ChangedStatus=6 order by date desc limit 1";
		$rs=$this->Db->query($strSQL);
		if(!$rs->EOF)
		{
			$SuspensionDate = $rs->fields[0];
		}
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$CreationDate", $CreationDate, $message);
		$message = str_replace("\$SuspensionDate", $SuspensionDate, $message);

		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function SendPaypalReceived($invoiceID)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail(12);
		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];

		$strSQL = "select UID, payment_gross, txn_id from paypal_txn where invoice_id='$invoiceID'";
		$this->dPrint("\$strSQL:$strSQL");
		$rs=$this->Db->query($strSQL);
		if(!$rs->EOF)
		{
			$OID = $rs->fields[0];
			$amount = $rs->fields[1];
			$PaypalTID = $rs->fields[2];
		}
		$strSQL = "select transactionID from transaction where referenceID like '%$PaypalTID%'";
		$this->dPrint("\$strSQL:$strSQL");
		$rs=$this->Db->query($strSQL);
		if(!$rs->EOF)
			$TID = $rs->fields[0];
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[MName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$CName = "$CFName $CMName $CLName";
		$this->dPrint ("\$customerID: $customerID");

		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$OID", $OID, $message);
		$message = str_replace("\$amount", $amount, $message);
		$message = str_replace("\$doller", "\$", $message);
		$message = str_replace("\$invoiceID", "$invoiceID", $message);
		$message = str_replace("\$TID", "$TID", $message);
		$message = str_replace("\$PaypalTID", "$PaypalTID", $message);
		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
		//$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function sendMissedCall($mcid)
	{
		$dated = date("D, d M Y H:i:s", time());
		$email2bSent = $this->getParsedEmail(15);
		$strSQL = "select OID, CallerID, Country, DID, date_format(Date_Time, '%d-%b-%Y %H:%i:%s') from missed_calls where id='$mcid'";
		$this->dPrint("\$strSQL : $strSQL ");
		$Result=$this->Db->query($strSQL);
		if(!$Result->EOF)
		{
			$OID = $Result->fields[0];
			$CallerID = $Result->fields[1];
			$Country = $Result->fields[2];
			$DID = $Result->fields[3];
			$Date_Time = $Result->fields[4];
		}
		//get customer info
		$customer = $this->myCustomer->getCustomerInfoByUID($OID);
		$CustomerID  =$customer[CustomerID];
		$CSalutation  =$customer[Salutation];
		$CFName  =$customer[FName];
		$CLName  =$customer[LName];
		$CEmail  =$customer[CEmail];
		$CName = "$CFName $CMName $CLName";
		$this->dPrint ("\$customerID: $customerID");

		$subject = $email2bSent[Subject];
		$from = $email2bSent[From];
		$cc = $email2bSent[Cc];
		$reply_to = $email2bSent[Reply-to];
		$message=$email2bSent[Contents];
		//replacing variables
		$message = str_replace("\$CName", $CName, $message);
		$message = str_replace("\$CSalutation", $CSalutation, $message);
		$message = str_replace("\$CallerID", $CallerID, $message);
		$message = str_replace("\$Country", $Country, $message);
		$message = str_replace("\$DID", $DID, $message);
		$message = str_replace("\$Date_Time", $Date_Time, $message);
		$to = $CEmail;
		$headers = "";
		$headers .= "From: $from \r\n";
		$headers .= "Reply-To: $reply_to \r\n";
		$headers .= "Date: $dated \r\n";
		$this->dPrint("\$dated: $dated");
//		$headers .= "Cc: $cc \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		$this->dPrint("\$done = mail($to, $subject, $message, $headers)");
		return mail($to, $subject, $message, $headers);
	}
	function SendDIDPurchased($DID)
	{
		//$dated = date("D, d M Y H:i:s", time());
		$this->DID = $DID;
		$this->OID = $this->did->getOIDByDID($DID);
		$this->RingTo = $this->did->getRingTo($DID);
		$this->getParsedEmail('DIDPURCHASED');
		//get customer info
		$this->getCustomerInfo($this->OID);
        $EmailPref = $this->myCustomer->getCustomerEmailPref($this->OID);
        if($EmailPref['DIDBought'] == 1)
        {
            $this->getCustomerInfo($this->OID);
        }
        else
        {
            return 1;
        }
        $this->replace();
		$this->send_mail();
	}	
	function SendCRECARDDEC($DID,$OID,$Balance)
    {
        //$dated = date("D, d M Y H:i:s", time());
        $this->DID = $DID;
        $this->OID = $OID;
        $this->Amount= $Balance;
        $this->getParsedEmail('CREDITCARDDEC');
        //get customer info 
        $this->getCustomerInfo($this->OID);
        $this->replace();
        $this->send_mail();
    }
    function sendCCDeclineEmailToCustomer($pHash)
    {
        $this->OID = $pHash['OID'];
        $this->CCLast=$pHash['CCLast'];
        $this->Password=$pHash['USERPASS'];
        $this->PhoneVPL=$pHash['VPL'];
        $this->OutstandingBal=$pHash['OutstandingBal'];
        $this->getParsedEmail("SENDEMAILCCDEC");
        $this->getCustomerInfo($pHash['OID']);        
            
        $this->replace();
        $this->send_mail();
    }
	function SendDIDReleaseEmail($DID)
	{
        $this->DID = $DID;
		$HashDID = $this->did->getDIDINfoByDUD($DID);
		$this->DIDPurchasedDate = $HashDID['PurchasedDate'];
		$this->OID =  $HashDID['VOID'];
		$OID = $this->OID;
		$strSQL	= "select SendDIDRel from EmailPref where UID=\"$OID\" ";	
	    $rs=$this->Db->query($strSQL);
		if($rs->fields[0]!=1)
        {
            return 0;
        }
		
			$strSQL	= "select SendDIDRel,DATE_FORMAT(curdate(), '%d-%b-%y') from EmailPref where UID=\"$OID\" ";	
	#	echo $strSQL;
		$rs=$this->Db->query($strSQL);
		
			if(!$rs->fields[0])
					return;
		$Cur = $rs->fields[1];
		$this->ReleaseDate = $Cur;
		$this->getParsedEmail('SENDDIDREL');
		//get customer info 
		$this->getCustomerInfo($this->OID);
		$this->replace();
		// $this->send_mail();
		$this->smtpmailer('gmail');
	}
    function checkEmailTypePaypal()
	{
        $UID = currentUser();
		$strSQL	= "SELECT EmailPref.paypal FROM EmailPref WHERE EmailPref.UID=$UID";	
	    $rs=$this->Db->query($strSQL);
        return $rs->fields[0];
	}
	function GetServer($ffOID)
	{
		$myADb=new ADb();
		$strSQL= "SELECT ServerType, ServerIP FROM EmailPref where UID=$ffOID";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;

	}
}
?>
