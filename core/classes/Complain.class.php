<?php
class Complain
{
	// 	Copyright©2005 Saleem Ahmed Kamboh. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Complain($dbg=0)
	{ 
			// include_once("Const.inc.php");
			// include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
			$this->Db = new ADb();
	}
function getAllComplainsToClientbyOID($pOID)
{
	
	$strSQL	= "	select
					*
				from
					complain
				where
					oid	= '$pOID' and notify=1
				order by datetime desc";
				
	$this->dPrint("strSQL:$strSQL");	
	$Result	= $this->Db->ExecuteQuery($strSQL);
		
	return $Result;
}	

function getThreadQuantity($pComplainID) {
	
	$strSQL=" select count(*) from ComplainThread where ComplainID=\"$pComplainID\" ";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
	
}
#	addComplain
#	IN:	Order ID, Complain Text
#	OUT:	Bool
function addComplain($pComplain) {

	$myADb=new ADb();
	$ComplainID=$pComplain['ComplainID'];
	$OID=$pComplain['OID'];
	$AUID=$pComplain['AUID'];
	$Assign=$pComplain['Assign'];
	$Type=$pComplain['Type'];
	$Complain=$pComplain['Complain'];

	$Notify=$pComplain['Notify'];
	$DID=$pComplain['DID'];

	$AreaID=$pComplain['AreaID'];

	$VOID=$pComplain['VOID'];
	$Cat=$pComplain['Cat'];
	$Oth=$pComplain['Oth'];
	$Tag=$pComplain['Tag'];
	$SubCat=$pComplain['SubCat'];
	$IsAnn=$pComplain['IsAnn'];

	$MyIP = $_SERVER['REMOTE_ADDR'];
	
 	 $strSQL	= "	insert into
					complain(ComplainID, OID, UID, Assign, Type, Complain, IsResolved, Notify, DateTime, DID,AreaID,VOID,IP,Category,Others,Tags,SubCat,IsAnn)
					values (\"$ComplainID\",\"$OID\",\"$AUID\",\"$Assign\",\"$Type\",
					\"$Complain\",0,\"$Notify\",now(),\"$DID\",\"$AreaID\",
					\"$VOID\",\"$MyIP\",\"$Cat\",\"$Oth\",\"$Tag\",\"$SubCat\",\"$IsAnn\"
					)";

	$Result = $myADb->ExecuteQuery($strSQL);

	return 1;
	
} # addComplain

function getNewComplainID($pOID)
{
	return $this->getComplainCount($pOID);
}
function getComplainCount($pOID)
{
	
	$strSQL	= "	select count(*) from complain where oid=\"$pOID\" ";
	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	$TotalCount = $Result->fields[0];
	
	if($TotalCount=='')
			$TotalCount=0;
			
	$TotalCount++;
	
	$TicketNumber=$pOID."000".$TotalCount;
	
	return $TicketNumber;
	

}# end getComplainCount

function getLastTicketNumber($pOID)
{
	$strSQL	= "	select max(complainid) from complain where oid=\"$pOID\" and length(complainid)>9";
	$this->dPrint ("\$strSQL: $strSQL");
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$TicketNumber=$Result->fields[0];
	
	return $TicketNumber;

}

function getComplainByTicketNumber($pTicket)
{
	$strSQL	= "	select complain from complain where complainid=\"$pTicket\" ";
	$this->dPrint ("\$strSQL: $strSQL");
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$TicketMessage = $Result->fields[0];
	
	return $TicketMessage;

}

function getComplainForAdmin($pComplainID) {
	
	$strSQL	= "	select
					*
				from
					complain
				where
					ComplainID	= '$pComplainID' 
				order by datetime desc";
	
	
	
		$Result	= $this->Db->ExecuteQuery($strSQL);
	

	
	$Hash[ComplainID] = $Result->fields[0];
	$Hash[OID] = $Result->fields[1];
	$Hash[UID] = $Result->fields[2];
	$Hash[Assign] = $Result->fields[3];
	$Hash[COMP] = $Result->fields[4];
	$Hash[Complain] = $Result->fields[5];
	$Hash[Status] = $Result->fields[6];
	$Hash[Notify] = $Result->fields[7];
	$Hash[Date] = $Result->fields[8];
	$Hash[DID] = $Result->fields[9];
	$Hash[VOID] = $Result->fields[10];
	$Hash[Tags] = $Result->fields[23];
	
	
	return $Hash;
	
	
	#return @Result;
}# g

function getComplainByTicketNumberFormated($pTicket)
{
	$strSQL	= "	select complain,date_format(datetime,\"%a, %d %b %Y\"),UID from complain where complainid=\"$pTicket\" ";
	$this->dPrint ("\$strSQL: $strSQL");
	$Result	= $this->Db->ExecuteQuery($strSQL);
	$TicketMessage = $Result->fields[0];
	$Date = $Result->fields[1];
	$Admin = $Result->fields[2];
	
			$strSQL	= "	select concat(AUFName,\" \",AULName) from adminuser where UID=\"$Admin\" ";
			$ResultAdmin	= $this->Db->ExecuteQuery($strSQL);
			
			$AdminName = $ResultAdmin->fields[0];
			
			$strSQL	= "	select concat(CFName,\" \",CLName) from customer where UID=\"$Admin\" ";
			$ResultCust	= $this->Db->ExecuteQuery($strSQL);
			
			$Customer = $ResultCust->fields[0];
	
	$Msg = "On $Date $AdminName $Customer wrote \r\n\r\n $TicketMessage ";
	
	return $Msg;

}

function getAssignedOrderComplains($pAUID,$Tier="",$SortBy=0) {
	
		if($SortBy==0)
				$OrderBy = " order by  datetime desc";
				
		if($SortBy==1)
				$OrderBy = " order by  category desc";		
	
		if($Tier!=""){
			$WhereTier=" or Assign='$Tier'";
			
		}
		
		if($Tier==""){
			
			$WhereDate = " or ( 
					date_add(datetime,interval 24 hour) < curdate() 
					and 
					datetime >= date_sub(curdate(),interval 3 day)
					)
					
					";
					
		}
	
	 $strSQL	= "	select
					*
				from
					complain
				where (
					(assign	= \"$pAUID\" $WhereTier ) $WhereDate 
					)
					
				and
					type	= \"COMP\"
				and
					isresolved <> 1 $OrderBy
				";
	
	#print "\$strSQL: $strSQL";
	
$Result	= $this->Db->ExecuteQuery($strSQL);
	

	
	return $Result;
}

function getAssignedOrderComplains2($pAUID,$SortBy=0,$Tier1=0,$Tier2=0,$Tier3=0,$pDir) {
	
	
		if($pDir==0)
				$pDir= " desc ";
		else
				$pDir= " asc ";
				
	
	
				
		if($Tier1)
				$AndTier .= "or Assign='Tier1'";
	
		if($Tier2)
				$AndTier .= "or Assign='Tier2'";
				
		if($Tier3)
				$AndTier .= "or Assign='Tier3'";		
		
		if($SortBy==0)
				$OrderBy = " order by  datetime ";
				
		if($SortBy==1)
				$OrderBy = " order by  category ";		
	
		if($AndTier!=""){
			$AndTier = 
			$WhereTier=" or (1!=1 $AndTier) ";
			
		}
		
	
			
			$WhereDate = " or ( (notify=1 and uid like '7%') or (notify=1 and oid=assign) )
					
					";
	
	
	 $strSQL	= "	select
					*
				from
					complain
				where (
					(assign	= \"$pAUID\" $WhereTier ) $WhereDate 
					)
					
				and
					type	= \"COMP\"
				and
					isresolved <> 1   $OrderBy $pDir
				";
	
#print "\$strSQL: $strSQL";
	
$Result	= $this->Db->ExecuteQuery($strSQL);
	

	
	return $Result;
}

function getAllComplains ($pOID) {
	
	
	$strSQL	= "	select
					*
				from
					complain
				where
					oid	= '$pOID'
				order by datetime desc";
	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	return $Result;
}# getAllComplains

function getAllInboxMsgs ($ComplainID) {
	
	$UID = currentUser();
	$strSQL	= "select ComplainID,OID,UID,Assign,Type,Complain,IsResolved,Notify,
date_format(DateTime,'%b %d, %Y %H:%i') as DateTime,IsRead,IsAnn,IP,date_format(readtime,'%d-%b-%Y %H:%i:%s') as ReadTime,IsArchive,
NotifyVendor,VOID,DID,resolvedate,date_format(resolvedate,'%d-%b-%Y') from complain 
where (OID=\"$UID\" and Notify=1 and ComplainID=\"$ComplainID\" ) or  (NotifyVendor=1 and VOID=\"$UID\" and ComplainID=\"$ComplainID\" ) limit 0,10";
	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	return $Result;
}

function getUserComplains ($where) {
	
	$UID = currentUser();
	$strSQL	= "select ComplainID,OID,UID,Assign,Type,Complain,IsResolved,Notify,
date_format(DateTime,'%b %d, %Y %H:%i') as Date,IsRead,IsAnn,NotifyVendor,VOID,DID from complain where (OID=\"$UID\" and Notify=1  $where  and isann=0) or (NotifyVendor=1 and VOID=\"$UID\" and isann=0) 
order by datetime  desc,PostDate desc limit 0,10";
	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	return $Result;
}

function getTotalComplainThread ($ComplainID) {
	
	$UID = currentUser();
	$strSQL	= "select count(*) from ComplainThread where (ComplainID=\"$ComplainID\" and Notify=1 and OID=\"$UID\" )
					 or (ComplainID=\"$ComplainID\" and Notify=2 and VendorOID=\"$UID\" )
			order by date desc ";
	#echo $strSQL;exit;
	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
}


function GetAllThreadsByTicketNumber ($pComplainID,$ThID) {
	
	$strSQL = " select Remarks,WrittenBy,date_format(Date,'%W, %d %M %Y'),IsReplied from ComplainThread 
	where ComplainID=\"$pComplainID\" and Notify=1 and ID!=\"$ThID\" order by date desc";
	$ResultA	= $this->Db->ExecuteQuery($strSQL);
	
	$Html="";
	
		while(!$ResultA->EOF){
			
			$Msg = $ResultA->fields[0];
			$WrBy = $ResultA->fields[1];
			$Date = $ResultA->fields[2];
			$IsReplied = $ResultA->fields[3];
			
			#print "\n\$AmindCus: $AmindCus";
			
			
			
			$strSQL	= "	select concat(AUFName,\" \",AULName) from adminuser where UID=\"$WrBy\" ";
			$ResultAdmin	= $this->Db->ExecuteQuery($strSQL);
			
			$AdminName = $ResultAdmin->fields[0];
			
			$strSQL	= "	select concat(CFName,\" \",CLName) from customer where UID=\"$WrBy\" ";
			$ResultCust	= $this->Db->ExecuteQuery($strSQL);
			
			$Customer = $ResultCust->fields[0];
				
				
			#	print "\$AmindCus: $AmindCus";
				
				
			$Html .= "<br><br> On $Date $AdminName $Customer wrote: <br> $Msg <br>";
			
			$ResultA->MoveNext();
			
			
		}
		
		return $Html;
	
	
	
	

}


function Annocement(){
	$UID=currentUser();
	$myADb=new ADb();
	$strSQL = "select ComplainID,OID,UID,Assign,Type,Complain,IsResolved,Notify,
	date_format(DateTime,'%b %d, %Y %H:%i') as Date,IsRead,IsAnn,NotifyVendor,VOID,DID from complain where OID=\"$UID\" and IsAnn=1
	order by datetime desc,PostDate desc limit 0,10";
    $Result	= $myADb->ExecuteQuery($strSQL);
    return $Result;
 }

 public function threadlist($ComplainID)
 {
 	
	$myADb=new ADb();
	$strSQL=" select complainID,Remarks,WrittenBy,AssignTo,date_format(Date,'%d-%b-%Y') as Date,Status from ComplainThread where ComplainID=\"$ComplainID\" and Notify=1 order by date desc";

	$ResultThread = $myADb->ExecuteQuery($strSQL);
	return $ResultThread;

 }
 


function GetThreadByTicketNumber ($pComplainID,$ThID) {
	
	

	
	$strSQL = " select Remarks,WrittenBy,date_format(Date,'%W, %d %M %Y'),IsReplied from 
	ComplainThread where ComplainID=\"$pComplainID\" and Notify=1 and ID=\"$ThID\" ";
	#print "\$strSQL: $strSQL";
	$ResultA	= $this->Db->ExecuteQuery($strSQL);
	
	$Html="";
	
		while(!$ResultA->EOF){
			
			$Msg = $ResultA->fields[0];
			$WrBy = $ResultA->fields[1];
			$Date = $ResultA->fields[2];
			$IsReplied = $ResultA->fields[3];
			
			#print "\n\$AmindCus: $AmindCus";
			
			
			
			$strSQL	= "	select concat(AUFName,\" \",AULName) from adminuser where UID=\"$WrBy\" ";
			$ResultAdmin	= $this->Db->ExecuteQuery($strSQL);
			
			$AdminName = $ResultAdmin->fields[0];
			
			$strSQL	= "	select concat(CFName,\" \",CLName) from customer where UID=\"$WrBy\" ";
			$ResultCust	= $this->Db->ExecuteQuery($strSQL);
			
			$Customer = $ResultCust->fields[0];
				
				
			#	print "\$AmindCus: $AmindCus";
				
				
			$Html .= "<br>$AdminName $Customer writes on On $Date : <br> $Msg <br>\r\n";
			
			$ResultA->MoveNext();
			
			
		}
		
		return $Html;
	
	
	
	

}


		function getOIDbyComplainID($pComplainID) {
		        
		        $myADb = new ADb();
		    
		        $strSQL = " select OID from complain where complainid=\"$pComplainID\" ";
		        $Result = $myADb->ExecuteQuery($strSQL);
		        
		        return $Result->fields[0];
		    
		}

		function updatearchive($ffComplainID){
			$myADb = new ADb();
			$strSQL = "update complain set IsArchive=1 where ComplainID=\"$ffComplainID\" ";
		    $Result = $myADb->ExecuteQuery($strSQL);
		    $Msg="Message Archived";
		    return $Msg;
		}
		function AddThreadToComplain($pComplainID,$pComplain,$pAdmin,$pAssignTo,$pNotify,$pOID) {
	
			if($pNotify == '') {
				$pNotify=0;
				
			}
			
			$myADb = new ADb();
			
			$strSQL="insert into ComplainThread (ComplainID,Remarks,WrittenBy,AssignTo,SentToClient,Date,Notify,OID)
								values(\"$pComplainID\",\"$pComplain\",\"$pAdmin\",\"$pAssignTo\",\"0\",now(),$pNotify,\"$pOID\") ";
			
			
			$Result	= $myADb->ExecuteQuery($strSQL);

			$strSQL = "select max(ID) from ComplainThread where ComplainID=\"$pComplainID\" ";
			$Result	= $myADb->ExecuteQuery($strSQL);
			
			$ThreadID = $Result->fields[0];

			
			#$strSQL = "update complain set IsRead=1,IsUpdate=1 where ComplainID=\"$pComplainID\" ";
			#$Result	= $myADb->ExecuteQuery($strSQL);
			
			return $ThreadID;
			
			
		}
			
/*	package Complain;
# 	Copyright©2002 Ahmed Shaikh Memon. All rights reserved.
$fDebug = 0;
sub dPrint { if ($fDebug) {print "@_ <br>";} }

require Exporter;
@ISA=(Exporter);
@EXPORT	= qw (
			addComplain
			getAllComplains
			getComplainCount
			resolveComplain
			getOIDByComplainID
			getAssignedOrderComplains
			postComplain
			sendAutomatedUpdateWithoutAcknowledgingClient
			getAllComplainsToClientbyOID
			);
#use Db;
use Orders;
use Customer;
use Address;
use Shipment;
use User;
use Email;
use AdminUser;
#use 
#	addComplain
#	IN:	Order ID, Complain Text
#	OUT:	Bool
sub addComplain {
	my (%pComplain)	= @_;
	# Debug

 	my $strSQL	= "	insert into
					complain(ComplainID, OID, UID, Assign, Type, Complain, IsResolved, Notify, DateTime, DID)
					values (
					\"$pComplain{ComplainID}\",
					\"$pComplain{OID}\",
					\"$pComplain{AUID}\",
					\"$pComplain{Assign}\",
					\"$pComplain{Type}\",
					'$pComplain{Complain}',
					0,
					\"$pComplain{Notify}\",
					sysdate(),
					\"$pComplain{DID}\"
					)";
	dPrint "\$strSQL: $strSQL";
	
	my @Result	= Db->ExecuteQuery($strSQL);
	
	dPrint "\$Result[0]: $Result[0]";
	return $Result[0];
} # addComplain

# 	getAllComplains
#	IN:	Order ID
#	OUT:	List of Complain
sub getAllComplains {
	my ($pOID)	= @_;

	dPrint "\$pOID: $pOID";
	
	my $strSQL	= "	select
					*
				from
					complain
				where
					oid	= '$pOID'
				order by datetime desc";
	
	dPrint "\$strSQL: $strSQL";
	
	my @Result	= Db->ExecuteQuery($strSQL);
	
	dPrint "\$Result[1][0]: $Result[1][0]";
	
	return @Result;
}# getAllComplains


#	getComplainCount
#	IN:	OrderID
#	OUT:	new ComplainID
sub getComplainCount{
	my ($pOID)	= @_;

	dPrint "\$pOID: $pOID";
	
	my $strSQL	= "	select
					count(*)
				from
					complain
				where
					oid	= \"$pOID\"";
	
	dPrint "\$strSQL: $strSQL";
	
	my @Result	= Db->ExecuteQuery($strSQL);
	
	dPrint "\$Result[1][0]: $Result[1][0]";
	if ($Result[1][0] eq '')
	{
		$Result[1][0]=0;
	}
	local $TicketNumber	= $Result[1][0];
	$TicketNumber++;
	dPrint "\$TicketNumber: $TicketNumber";

	if ($TicketNumber < 10) {
		$TicketNumber	= "0$TicketNumber";
	}
	$TicketNumber	= "$pOID$TicketNumber";
	dPrint "\$TicketNumber: $TicketNumber";
	while (1)
	{
		$TicketNumber++;
		dPrint "\$TicketNumber: $TicketNumber";
		my $strSQL	= "	select
						complainid
					from
						complain
					where
						complainid = '$TicketNumber'";
		dPrint "\$strSQL: $strSQL";
		
		my @Result	= Db->ExecuteQuery($strSQL);
		
		dPrint "\$Result[1][0]: $Result[1][0]";
		if ($Result[1][0] eq '')
		{
			return $TicketNumber;
		}
	}
	dPrint "\$TicketNumber: $TicketNumber";
	return $TicketNumber;
}# end getComplainCount

#	resolveComplain
#	IN:	ComplainID
#	OUT:	Bool
sub resolveComplain {
	my ($pOID)	= @_;


 	my $strSQL	= "	update complain
 				set
 					isresolved = 1
 				where
 					complainid = \"$pOID\"";
	dPrint "\$strSQL: $strSQL";
	
	my @Result	= Db->ExecuteQuery($strSQL);
	
	dPrint "\$Result[0]: $Result[0]";
	
	return $Result[0];
}#resolveComplain

sub getOIDByComplainID {
	my ($pComplainID)	= @_;
	my $strSQL	= "	select
					oid
				from
					complain
				where
					complainid=\"$pComplainID\"";
	dPrint "\$strSQL: $strSQL";
	
	my @Result = Db->ExecuteQuery($strSQL);
	dPrint "\$Result[1][0]: $Result[1][0]";
	return $Result[1][0];
}

#	getAssignedOrderComplains
#	IN:	Admin ID
#	OUT:	List of Assigned Complain
sub getAssignedOrderComplains {
	my ($pAUID)	= @_;

	dPrint "\$pAUID: $pAUID";
	
	my $strSQL	= "	select
					*
				from
					complain
				where
					assign	= \"$pAUID\"
				and
					type	= \"COMP\"
				and
					isresolved <> 1
				order by datetime desc";
	
	dPrint "\$strSQL: $strSQL";
	
	my @Result	= Db->ExecuteQuery($strSQL);
	
	dPrint "\$Result[1][0]: $Result[1][0]";
	
	return @Result;
}# getAssignedOrderComplains

#	postComplain
#	IN:	hash
#	OUT:	Status
sub postComplain{
	my (%pComplain)	= @_;
	my %Order		= getOrdersInfo($pComplain{OID});
	my %Customer		= getCustomerInfo($Order{CustomerID});
	my %Address		= getAddressInfo($Customer{AddressID});
	my %Shipment		= getShipmentInfo($Order{ShipmentID});
	my %ShipmentAddr	= getAddressInfo($Shipment{AddressID});

	my $TicketNumber	= getComplainCount($pComplain{OID});
	
	my %ThisAdminUser	= getAdminUser($pComplain{ByAUID});

	# Add Complain In Database
	my %Complain;
	$Complain{ComplainID}	= $TicketNumber;
	$Complain{OID}		= $pComplain{OID};
	$Complain{AUID}		= $ThisAdminUser{UID};
	$Complain{Assign}	= $pComplain{AssignToAUID};
	$Complain{Type}		= $pComplain{Type};
	my $ClientToSegment;
	if ($pComplain{SendToClient}){
		if (($Customer{CEmail} ne "")&&($Customer{CEmail} ne "N/A")) {
			$ClientToSegment = "<br><b>Email Sent to client</b>";
			# Send an email now
			open (MAIL , "| /usr/sbin/sendmail -t") || die return 0;
			# Send the header.
			print MAIL "From: support\@mysuperphone.com\n";
			print MAIL "To: $Customer{CEmail}\n";
			#print MAIL "To: ahmed\@mysuperphone.com\n";
			print MAIL "Reply-to: $ThisAdminUser{Email}\n";
			print MAIL "Subject: A Ticket has been issued by our admin the Ticket No. is $TicketNumber.\n";
			print MAIL "\n";
			
			# Order Detail
			print MAIL "Dear $Customer{CFName} $Customer{CLName},\n\n";
			print MAIL "A Ticket has been submitted by our admin $ThisAdminUser{FName} $ThisAdminUser{LName}.\n";
			print MAIL "We are sending you this email just to make you acknowledged.\n\n";
			print MAIL "The text submitted by $ThisAdminUser{FName} $ThisAdminUser{LName} is as follows;\n\n";
			print MAIL "Ticket # $TicketNumber.\n";
			print MAIL "Order # $pComplain{OID}.\n";
			print MAIL "$pComplain{Complain}.\n\n\n";
			print MAIL "Thanks and Best Regards,\n";
			print MAIL "Customer Support Department\n";
			print MAIL "Super Technologies Inc\n\n";
			close MAIL;
		}
	}
	$Complain{Complain}	= $pComplain{Complain} . $ClientToSegment;


	my $fComplainAdded	= addComplain(%Complain);
	if (!$fComplainAdded) {
		print "Complain not added. Contact webmaster<br>";
#		return 0;
	}
	#Email
	my %Email;
	if ($pComplain{SendCopyTo} ne "\@mysuperphone.com") {
		$Email{AEmail}	= "$ThisAdminUser{Email}, $pComplain{SendCopyTo}";
	}
	else {
		$Email{AEmail}	= $ThisAdminUser{Email};
	}
	$Email{AName}		= $ThisAdminUser{FName} . " " . $ThisAdminUser{LName};
	$Email{Complain}	= $pComplain{Complain};
	$Email{OID}		= $pComplain{OID};
	if ($pComplain{AssignToAUID} ne '') {
		my %AssignTo	= getAdminUser($pComplain{AssignToAUID});
		$Email{Assign}	= "$AssignTo{FName} $AssignTo{LName}";
		$Email{AssignEmail}= $AssignTo{Email};
	}
	else {
		$Email{AssignEmail}= "";
	}
	$Email{CSalutation}	= $Customer{Salutation};
	$Email{CName}		= "$Customer{CFName} $Customer{CLName}";
	$Email{CAddress}	= "$Address{Street1}, $Address{City}, $Address{State}, $Address{Country}.";
	
	$Email{CEmail}		= $Customer{CEmail};
	$Email{CTel}		= $Customer{CTelHome};
	$Email{CCompany}	= $Customer{Company};
	$Email{SName}		= $Shipment{Name};
	$Email{SAddress}	= "$ShipmentAddr{Street1}, $ShipmentAddr{City}, $ShipmentAddr{State}, $ShipmentAddr{Country}.";
	$Email{STel}		= $Shipment{ShipmentTel};
	$Email{TicketNumber}	= $TicketNumber;
	sendOrderComplaints(%Email);
}#postComplain

#	sendAutomatedUpdateWithoutAcknowledgingClient
#	IN:	Message
#	OUT:	Complain has been sent
sub sendAutomatedUpdateWithoutAcknowledgingClient {
	my %Complain;
	$Complain{OID}		= $_[0];
	$Complain{ByAUID}	= "A101";
	$Complain{Complain}	= $_[1];
	$Complain{AssignToAUID}	= "";
	$Complain{Type}		= "UPDA";
	$Complain{SendToClient}	= 0;
	$Complain{SendCopyTo}	= "\@mysuperphone.com";
	&postComplain(%Complain);
}

sub getAllComplainsToClientbyOID {
	my ($pOID)	= @_;

	dPrint "\$pOID: $pOID";
	
	my $strSQL	= "	select
					*
				from
					complain
				where
					oid	= '$pOID' and notify=1
				order by datetime desc";
		
	my @Result	= Db->ExecuteQuery($strSQL);
		
	return @Result;
	
}
1;
*/

}
?>