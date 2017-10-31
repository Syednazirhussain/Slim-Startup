<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
//include_once "ADb.inc.php";
class Transaction
{
	// 	Copyright 2011 Huzoor Bux. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function Transaction($fDebug=0)	
	{
		$this->ADb = new ADb();
		$this->fDebug=$fDebug;
	}
	
function CheckAndAutoTransferAmount ($OID,$CurrentBalance)
{
		
		$strSQL = " select ID,Vendor,LinkAccount,AutoTransfer,AutoTransAmount,Alert from VLinkAccount where LinkAccount=\"$OID\" ";
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		
		if($Result->EOF)
				return -1;
		$ID = $Result->fields[0];
		$Seller = $Result->fields[1];
		$Buyer = $Result->fields[2];
		$Auto = $Result->fields[3];
		$AutoTransAmount = $Result->fields[4];
		$Alert = $Result->fields[5];
		
		$CurrentBalance = $CurrentBalance * (-1);
		
		if($Auto==1 && $AutoTransAmount>0 && $CurrentBalance<=$Alert){
			
				$SellerBalance = $this->getOutStandingBalance($Seller);
				
				$SellerBalance = $SellerBalance * (-1);
				
				if($AutoTransAmount>$SellerBalance){
							return -1;
				}
				
			$Txn = array();
			$TID = $this->getTransactionID($Buyer);
			$Txn['TransactionID']= $TID;
			$Txn['OID']	= $Buyer;
			$Txn['ReferenceID']= $Seller;
			$Txn['Desc']	=  "[DIDx:$Buyer] Payment Received. Via Seller Account # $Seller.";
			$Txn['Type']	= "ACPT";
			$Txn['IsCredit']	= 0;
			$Txn['Amount']	= $AutoTransAmount;
			$Txn['DID']	= "";
			$this->addTransaction($Txn);	
			#print_r($Txn);
			$Txn = array();
			$TID = $this->getTransactionID($Seller);
			$Txn['TransactionID']= $TID;
			$Txn['OID']	= $Seller;
			$Txn['ReferenceID']= $Seller;
			$Txn['Desc']	=  "[Payment To Provider] For DID Purchases. AutoTransfer to Account # $Buyer ";
			$Txn['Type']	= "PPAT";
			$Txn['IsCredit']	= 1;
			$Txn['Amount']	= $AutoTransAmount;
			$Txn['DID']	= "";
			$this->addTransaction($Txn);	
				
			}
		
		return $AutoTransAmount;
		
	}
function getTransactionID($OID)
{
    $strSQL	= "select count(transactionid) from	transaction where oid='$OID'";
    $Result	= $this->ADb->ExecuteQuery($strSQL);
    if($Result->fields[0]==0 || $Result->EOF)
    {
        $ID =   $OID ."0000"; 
    }
    else
	{
        $Count  = $Result->fields[0];
		$Count  = $Count + 1;
		$ID     = $OID . "0000$Count";
    }
    if(strlen($ID)<=6)
    {
        mail("kamal@supertec.com","ERRORRRRR URGENT","\$Count: $Count, \$ID: $ID, \$strSQL: $strSQL, \$Result->fields[0]: ".$Result->fields[0]);
    }
    return $ID;
}
function getTransactionDetails($tID)
{
		$Txn = array();
		$strSQL	= "	select transactionid, oid,	description, type, referenceid,	amount,	iscredit, iscompleted,	date, DIDID
		from transaction where transactionID='$tID'";
		$this->dPrint("\$strSQL: $strSQL");
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		if($Result === false || $Result->EOF)
			return "";
		$Txn['TransactionID'] = $Result->fields[0];
		$Txn['OID'] = $Result->fields[1];
		$Txn['Description'] = $Result->fields[2];
		$Txn['Type'] = $Result->fields[3];
		$Txn['ReferenceID'] = $Result->fields[4];
		$Txn['Amount'] = $Result->fields[5];
		$Txn['IsCredit'] = $Result->fields[6];
		$Txn['IsCompleted'] = $Result->fields[7];
		$Txn['Date'] = $Result->fields[8];
		$Txn['DIDID'] = $Result->fields[9];		
		return $Txn;		
	}
    
function addTransaction2($Hash) 
{
        $IP = $_SERVER['REMOTE_ADDR'];
        
        if($Hash[Date] == '')
            $strSQL    = "    insert into        transaction    (
                        transactionid,
                        oid,
                        description,
                        type,
                        referenceid,
                        amount,
                        iscredit,
                        iscompleted,
                        date,
                        DIDID,SLOC,IP,WHODID,VendorID
                    )
                values (
                        \"$Hash[TransactionID]\",
                        \"$Hash[OID]\",
                        \"$Hash[Desc]\",
                        \"$Hash[Type]\",
                        \"$Hash[ReferenceID]\",
                        \"$Hash[Amount]\",
                        \"$Hash[IsCredit]\",
                        \"$Hash[Buyer]\",
                       \"$Hash[Date]\",
                        \"$Hash[DID]\",\"$Hash[SLOC]\",\"$IP\",\"$Hash[WHODID]\",\"$Hash[VendorID]\"
                    )";
        else{
            $MyDate = $Hash[Date];
             #  \"$MyDate\",
        $strSQL    = "    insert into transaction    (
                        transactionid,
                        oid,
                        description,
                        type,
                        referenceid,
                        amount,
                        iscredit,
                        iscompleted,
                        date,
                        DIDID,SLOC,IP,WHODID,VendorID
                    )
                values (
                        \"$Hash[TransactionID]\",
                        \"$Hash[OID]\",
                        \"$Hash[Desc]\",
                        \"$Hash[Type]\",
                        \"$Hash[ReferenceID]\",
                        \"$Hash[Amount]\",
                        \"$Hash[IsCredit]\",
                        \"$Hash[Buyer]\",
                        
                        \"$Hash[Date]\",
                        \"$Hash[DID]\",\"$Hash[SLOC]\",\"$IP\",\"$Hash[WHODID]\",\"$Hash[VendorID]\"
                    )";
                }
        $this->dPrint("\$strSQL: $strSQL");
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        return $Result;
    }    
    
   
    
    
function addTransaction($Hash) 
{
	$myADb=new ADb();
	
	    $TransactionID=$Hash['TransactionID'];
		$OID=$Hash['OID'];
		$Desc=$Hash['Desc'];
		$Type=$Hash['Type'];
		$ReferenceID=$Hash['ReferenceID'];
		$Amount=$Hash['Amount'];
		$IsCredit=$Hash['IsCredit'];
		$Buyer=$Hash['Buyer'];
        $Date=$Hash['Date'];
        $DID=$Hash['DID'];
        $SLOC=$Hash['SLOC'];
        $WHODID=$Hash['WHODID'];
        $WHODID=$Hash['VendorID'];

		$IP = $_SERVER['REMOTE_ADDR'];
		
		if($Date == '')
			$strSQL	= "	insert into		transaction	(
						transactionid,
						oid,
						description,
						type,
						referenceid,
						amount,
						iscredit,
						iscompleted,
						date,
						DIDID,SLOC,IP,WHODID,VendorID
					)
				values (
						\"$TransactionID\",
						\"$OID\",
						\"$Desc\",
						\"$Type\",
						\"$ReferenceID\",
						\"$Amount\",
						\"$IsCredit\",
						\"$Buyer\",
						curdate(),
						\"$DID\",
						\"$SLOC\",
						\"$IP\",
						\"$WHODID\",
						\"$VendorID\"
					)";
				
		
		else
			// $MyDate = $Hash[Date];
			 #  \"$MyDate\",
		$strSQL	= "	insert into	transaction	(
						transactionid,
						oid,
						description,
						type,
						referenceid,
						amount,
						iscredit,
						iscompleted,
						date,
						DIDID,
						SLOC,
						IP,
						WHODID,
						VendorID
					)
				values (
						\"$TransactionID\",
						\"$OID\",
						\"$Desc\",
						\"$Type\",
						\"$ReferenceID\",
						\"$Amount\",
						\"$IsCredit\",
						\"$Buyer\",
						\"$Date\",
                        \"$DID\",
                        \"$SLOC\",
                        \"$IP\",
                        \"$WHODID\",
                        \"$VendorID\"
					)";
					
		$Result = $myADb->ExecuteQuery($strSQL);
		
		return $Result;
	}
function getOutStandingBalance($OID)
    {
		$myADb=new ADb();
		$strSQL	= "select sum(amount) from transaction where isdeleted=0 and iscredit=1 and 
		           oid='$OID'";

		$Result1	= $this->ADb->ExecuteQuery($strSQL);
		
		$CreditSum = $Result1->fields[0];
		$strSQL	= "select sum(amount) from transaction where isdeleted=0 and iscredit=0 and
		           oid='$OID'";
		         
		$Result2	= $this->ADb->ExecuteQuery($strSQL);
		$DebitSum = $Result2->fields[0];
		$OutStandingBalance = sprintf("%2.2f",$CreditSum - $DebitSum);
		return $OutStandingBalance;
		
	}
function getOutStandingBalanceByDate($OID,$Date)
{
		
		$strSQL	= "select sum(amount) from transaction where isdeleted=0 and iscredit=1 and oid='$OID' and date<=\"$Date\" ";
		$Result1	= $this->ADb->ExecuteQuery($strSQL);
		
		$CreditSum = $Result1->fields[0];
		
		
		
		$strSQL	= "select sum(amount) from transaction where isdeleted=0 and iscredit=0 and oid='$OID' and date<=\"$Date\"";
		$Result2	= $this->ADb->ExecuteQuery($strSQL);
		$DebitSum = $Result2->fields[0];
		
		$OutStandingBalance = sprintf("%2.2f",$CreditSum - $DebitSum);
		
		return $OutStandingBalance;
		
	}
function getOutStandingBalanceByDatePeriod($OID,$Date1,$Date2)
{
		
		$strSQL	= "select sum(amount) from transaction where isdeleted=0 and iscredit=1 and oid='$OID' 
		and date>=\"$Date1\" and date<=\"$Date2\" ";
		$Result1	= $this->ADb->ExecuteQuery($strSQL);
		
		$CreditSum = $Result1->fields[0];
		
		
		
		$strSQL	= "select sum(amount) from transaction where isdeleted=0 and iscredit=0 and oid='$OID' and date>=\"$Date1\" and date<=\"$Date2\" ";
		$Result2	= $this->ADb->ExecuteQuery($strSQL);
		$DebitSum = $Result2->fields[0];
		
		$OutStandingBalance = sprintf("%2.2f",$CreditSum - $DebitSum);
		
		return $OutStandingBalance;
		
	}
function getDaysDueForTermination($OID)
{
		
		$strSQL	= "select Decline from cc_decline where oid='$OID'";
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		
		$Days = $Result->fields[0];
		
		return $Days;
		
		
	}
function getTransactionInfo($pTransactionID)
{
	 
	$strSQL	= "	select
					transactionid,
					oid,
					description,
					type,
					referenceid,
					amount,
					iscredit,
					iscompleted,
					date
				from
					transaction
				where
					transactionid=\"$pTransactionID\"";

	

	$Result	= $this->ADb->ExecuteQuery($strSQL);
	

	$Hash=array();

	$Hash['TransactionID']	= $Result->fields[0];
	$Hash['OID']		= $Result->fields[1];
	$Hash['Desc']		= $Result->fields[2];
	$Hash['Type']		= $Result->fields[3];
	$Hash['ReferenceID']	= $Result->fields[4];
	$Hash['Amount']		= $Result->fields[5];
	$Hash['IsCredit']		= $Result->fields[6];
	$Hash['IsCompleted']	= $Result->fields[7];
	
	$Hash['Date']		= $Result->fields[8];

	return $Hash;

}

function getTransactionInfoByOIDTableADMIN($pOID,$Mon,$Yr,$Limit,$Type,$pDID,$pDir,$pRun,$pPage,$pTrID,$CusType)
{
    $DateBalance;
    $DateSelected=0;
    if($Yr != 'ALL' && $Mon != 'ALL')
    {
        $DateBalance = $this->GetBalanceByMonthYear($Mon,$Yr,$pOID);
        $DateSelected=1;
    }
    else
    {
        $DateSelected=0;
    }
    
    $Transaction = $this->getTransactionInfoByOIDbyDate($pOID,$Mon,$Yr,$Limit,$Type,$pDID,$pDir,$pTrID);
    $html;
    $Debit    = sprintf("%.2f",0);
    $Credit    = sprintf("%.2f",0);
    $gOID;
    if($Transaction->fields[0] != "")
    {
        $fSwitch=1;
        $RunBalance=$pRun;
        while(!$Transaction->EOF)
        {
            $TransactionID      = $Transaction->fields[0];
            $OID                = $Transaction->fields[1];
            $gOID               = $OID;
            $Desc               = $Transaction->fields[2];
            $Type               = $Transaction->fields[3];
            $ReferenceID        = $Transaction->fields[4];
            $Amount             = $Transaction->fields[5];
            $IsCredit           = $Transaction->fields[6];
            $Date               = $Transaction->fields[7];
            $IsCompleted        = $Transaction->fields[8];
            $IsDeleted          = $Transaction->fields[9];
            $SLOC               = $Transaction->fields[10];
            $WHODEL             = $Transaction->fields[11];
            $bgC;
            if($fSwitch)
            {
                $bgC= "#f0f0f0";
                $fSwitch=0;
            }
            else
            {
                $bgC= "";
                $fSwitch=1;
            }
            if($IsDeleted) 
            {
                $bgC="#FF6600";
            }
            $html    .= "<tr bgcolor=$bgC>";
            # Transaction ID
            $html    .= "<td>";
            $html    .= "<font face=verdana size=-1>";
            $html    .= "<a href='/cgi-bin/virtual/admins/OldTransactions.cgi?TID=$TransactionID' style='{font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; color:#333333; font-weight:bold;}'>$TransactionID</a>";
            $html    .= "</font>";
            $html    .= "</td>";
        
            # Date
            $html    .= "<td nowrap>";
            $html    .= "<font face=verdana size=-1>";
            $html    .= "$Date";
            $html    .= "</font>";
            $html    .= "</td>";
            
            # Type
            $html    .= "<td nowrap>";
            $html    .= "<font face=verdana size=-1>";
            $html    .= "$Type";
            $html    .= "</font>";
            $html    .= "</td>";
        
        
            # Description
            $html    .= "<td nowrap>";
            $html    .= "<font face=verdana size=-1>";
            # The Payment Received Transaction
            if(strpos($Desc, "Thank you"))
            {
                $html    .= "<a href='/cgi-bin/virtual/admins/ViewSingleCCHistory.cgi?LPID=$ReferenceID' target=_blank>$Desc</a>";
            }
           
            elseif(strpos($Desc, "Decline: Due not cleared"))
            {
                $html    .= "<a href='/cgi-bin/virtual/admins/ViewSingleCCHistory.cgi?LPID=$ReferenceID' target=_blank>$Desc</a>";
            }
            elseif(strpos($Desc, "Payment Returned."))
            {
                $html    .= "<a href='/cgi-bin/virtual/admins/ViewSingleCCHistory.cgi?LPID=$ReferenceID' target=_blank>$Desc</a>";
            }
            elseif(strpos($Desc, "Decline: Unable to purchase DID"))
            {
                $html    .= "<a href='/cgi-bin/virtual/admins/ViewSingleCCHistory.cgi?LPID=$ReferenceID' target=_blank>$Desc</a>";
            }elseif(strpos($Desc, "Payment Recieved"))
            {
                $html    .= "<a href='/cgi-bin/virtual/admins/ViewSingleCCHistory.cgi?LPID=$ReferenceID' target=_blank>$Desc</a>";
            }
            # All Other Transactions
            else
            {
                $html    .= "$Desc";
            }
            $html    .= "</font>";
            $html    .= "</td>";
            if(!($IsDeleted))
            {
            if(!$IsCredit)
            {
                $Debit+=$Amount;
                $html    .= "<td align=right width=70 nowrap>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$Amount";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "&nbsp";
                $html    .= "</font>";
                $html    .= "</td>";
                $RunBalance -=$Amount;
            }
            else
            {
                $Credit+=$Amount;
                $html    .= "<td align=right width=70 nowrap>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "&nbsp";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$Amount";
                $html    .= "</font>";
                $html    .= "</td>";
                $RunBalance +=$Amount;
            }
        }
            else
            {
                $html    .= "<td align=right width=70 nowrap>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "&nbsp";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$Amount.00";
                $html    .= "</font>";
                $html    .= "</td>";
            
        }    
            $RunBalance = sprintf("%.2f", $RunBalance);
                #RunBalanec
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$RunBalance";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "<td align=LEFT>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$SLOC";
                $html    .= "</font>";
                $html    .= "</td>";
                
                $html    .= "<td align=center>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$WHODEL";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "</tr>";
                $Transaction->MoveNext();
        }
        $NextLink    =  "<a href=\"ALedger2.cgi?OID=$pOID&DID=$pDID&Month=$Mon&Year=$Yr&page=$pPage&Type=$Type&dir=$pDir&bal=$RunBalance\">Next</a>";
        $htmlDebit    = sprintf("%.2f", $Debit);
        $htmlCredit    = sprintf("%.2f", $Credit);
    
        $TotalCredit = $this->getTotalCredit($pOID);
        $TotalDebit = $this->getTotalDebit($pOID);
        $strSQL = "select date_format('$Yr-$Mon-01','%b-%Y')";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $DateSelectedFormatted = $Result->fields[0];
        if($DateSelected==1)
        {
            $html.= "<tr>
            <td align=right colspan=4>
            <font face=verdana size=-1>Total $DateSelectedFormatted Balance</font>
            </td>
            <td align=right >
            <font face=verdana size=-1>$DateBalance</font>
            </td>
            <td align=right  width=70 nowrap colspan=3>
            <font face=verdana size=-1></font>
            </td>
            </tr>";
            $html    .= "<tr>
            <td align=right colspan=4>
            <font face=verdana size=-1>Total Balance till $DateSelectedFormatted</font>
            </td>
            <td align=right >
            <font face=verdana size=-1>$BalanceTillDate</font>
            </td>
            <td align=right  width=70 nowrap colspan=3>
            <font face=verdana size=-1></font>
            </td>
            </tr>";
        }
        $html.= "<tr>
        <td align=right colspan=4>
        <font face=verdana size=-1>Total This Page</font>
        </td>
        <td align=right >
        <font face=verdana size=-1>\$$htmlDebit</font>
        </td>
        <td align=right  width=70 nowrap>
        <font face=verdana size=-1>\$$htmlCredit</font>
        </td>
        </tr>";
        $html.= "<tr>
        <td align=right colspan=4>
        <font face=verdana size=-1>Total</font>
        </td>
        <td align=right >
        <font face=verdana size=-1>\$$TotalDebit</font>
        </td>
        <td align=right  width=70 nowrap>
        <font face=verdana size=-1>\$$TotalCredit</font>
        </td>
        </tr>";
        $strSQL = "SELECT (SUM(amount) - (SELECT SUM(amount) FROM transaction WHERE isdeleted=0 AND iscredit=1 AND oid='$pOID')) FROM transaction WHERE isdeleted=0 AND iscredit=0 AND oid='$pOID'";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        $nTotalAmount = $Result->fields[0];
        $TotalMessage;
        //echo $nTotalAmount."----";
        if($nTotalAmount<0)
        {
            $nTotalAmount    = sprintf("%.2f", (-1)*$nTotalAmount);
            $TotalMessage    = "Client has to pay: <font color=red>$nTotalAmount</font><br><br>";
            $TotalMessage    .= "<b><a href=\"FriendlyReminder.cgi?OID=$gOID\">Send Friendly Reminder</a>&nbsp;&nbsp;&nbsp;<a href=/cgi-bin/virtual/admins/AChargeNowAction.cgi?OID=$gOID>Charge Now!</a></b>";
            $TotalMessage    .= "<br><b><a href=/cgi-bin/virtual/admins/ChargeViaPayPal.cgi?OID=$gOID>Charge Now Via PayPal</a></b>";
            $TotalMessage    .= "<br><b><a href=/cgi-bin/virtual/admins/AAuthAmount.cgi?OID=$gOID>Authorize Amount Now!</a></b>";
        }
        elseif($nTotalAmount==0)
        {
            $TotalMessage    = "Balance: $nTotalAmount";
        }
        elseif($nTotalAmount>0)
        {
            if($CusType==0 || $CusType==2)
            {
                $TotalMessage    = "The customer has over paid us: $nTotalAmount<br>";
            }
            if($CusType==1 || $CusType==2)
            {
                $TotalMessage   = "We have to Pay to Seller : $nTotalAmount<br>";
            }
            $TotalMessage    .= "<b><a href=/cgi-bin/virtual/admins/ReturnAmount.cgi?OID=$gOID&Amount=$nTotalAmount>Return Amount</a></b>";
        }
        $html    .= "<tr><td align=right colspan=7><font face=verdana size=-1>$TotalMessage</font></td></tr>";
        if($nIndex>=200)
        {
            $html    .= "<tr><td align=right colspan=7><font face=verdana size=-1>$NextLink</font></td></tr>";
        }
    }
    else
    {
        $html    = "<tr><td colspan=5><center><font face=verdana size=-1>No records found</font></center></td></tr>";
    }
    return $html;
}


    function getTransactionInfoByOIDbyDate($pOID,$Mon,$Yr,$Limit,$pType,$pDID,$pDir,$pTrID)
    {
    if($pDID != '')
    {
        $WhereDID = " and description like '%$pDID%' ";            
    }
    if($Mon == 'ALL')
    {
        $Mon="";
    }
    if($Yr == 'ALL')
    {
        $Yr="%";
    }
    if($Limit != '')
    {
        $LimitClause = " $pDir  limit $Limit,200";
    }
    else
    {
        $LimitClause="";
    }
    
    if($pType == 'ALL')
    {
        $TYPEClause="";
    }
    elseif($pType == 'PAY')
    {
        $TYPEClause=" and type like 'PAY%'";
    }
    elseif($pType == 'PVPL')
    {
        $TYPEClause=" and  type like 'PVPL%'";
    }
    elseif($pType == 'PURC')
    {
        $TYPEClause=" and description like \"Payment Received%\" ";
    }
    elseif($pType == 'ACTP')
    {
        $TYPEClause=" and type='ACTP' ";
    }
    elseif($pType == 'ACTV')
    {
        $TYPEClause=" and type='ACTV' ";
    }
    elseif($pType == 'DECL')
    {
        $TYPEClause=" and description like \"%Decline%\" and type=\"CCDL\" ";
    }
    elseif($pType == 'PAID')
    {
        $TYPEClause=" and (type='PAID' or type='PPPP' or type='PPPP' or type='PPCC'  or type='PPWU' or type='PPBC' or type='PPOP' or type='PPWT' or type='PPAH' or type='PPZR' or type='PPBP'  )";    
    }
    if($pTrID != "")
    {
        $pTransIDWhere = " and transactionid=\"$pTrID\" ";
        $Yr="%"; $Mon="%";
    }
    
    $strSQL = "select transactionid,oid,description,type,referenceid,amount,iscredit,date,iscompleted,isdeleted,SLOC,WHODEL from transaction
               where oid=\"$pOID\" and isdeleted!=2 and date like \"$Yr-$Mon%\" $TYPEClause $WhereDID $pTransIDWhere order by date  $LimitClause";
    $Result    = $this->ADb->ExecuteQuery($strSQL);
    return $Result;
}
    function GetBalanceByMonthYear($pMonth,$pYear,$pOID)
    {
        $strSQL = "select sum(amount) from transaction where date like '$pYear-$pMonth-%' and isdeleted=0 and iscredit=1 and OID='$pOID'";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $Credit = $Result->fields[0];
        
        $strSQL = "select sum(amount) from transaction where date like '$pYear-$pMonth-%' and isdeleted=0 and iscredit=0 and OID='$pOID'";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $Debit = $Result->fields[0];
        
        $strSQL = "select date_format('$pYear-$pMonth-01','%b-%Y')";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $DateSelectedFormatted = $Result->fields[0];
        
        $Balance = $Credit - $Debit;
        
        $strSQL = "select sum(amount) from transaction where date <= '$pYear-$pMonth-31' and isdeleted=0 and iscredit=1 and OID='$pOID'";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $Credit = $Result->fields[0];
        
        $strSQL = "select sum(amount) from transaction where date <= '$pYear-$pMonth-31' and isdeleted=0 and iscredit=0 and OID='$pOID'";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $Debit = $Result->fields[0];
        
        $BalanceTillDate = $Credit - $Debit;
        return $Balance;
    }
    function getTotalDebit($pOID)
    {
        $strSQL = "select sum(amount) from transaction where OID=\"$pOID\" and isdeleted=0 and iscredit=0";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $TotalDebit= $Result->fields[0];
        return  number_format($TotalDebit, 2);
    }
    function getTotalCredit($pOID)
    {
        $strSQL = "select sum(amount) from transaction where OID=\"$pOID\" and isdeleted=0 and iscredit=1";
       
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $TotalCredit= $Result->fields[0];
        return  number_format($TotalCredit, 2);
    }
    
    function getCurrentBalanceInfobyUIDFinal($pOID)
    {
        $strSQL     = " select sum(amount) from transaction where OID=\"$pOID\" and isdeleted=0 and IsCredit=1";
        $Result     = $this->ADb->ExecuteQuery($strSQL);
        
        $Credit     = $Result->fields[0];
        
        $strSQL     = " select sum(amount) from transaction where OID=\"$pOID\" and isdeleted=0 and IsCredit=0";
        $Result     = $this->ADb->ExecuteQuery($strSQL);
        
        $Debit  = $Result->fields[0];
        
        $Balance = sprintf("%2.2f",$Credit - $Debit);
        
        return $Balance;    
    }
    
function getCurrentBalanceInfobyUIDFinal2($pOID) {
    
    //global $myADb;
    
    $strSQL = " select sum(amount) from transaction where OID=\"$pOID\" and isdeleted=0 and IsCredit=1";
    $Result    = $this->ADb->ExecuteQuery($strSQL);
    
    $Credit = $Result->fields[0];
    
    $strSQL = " select sum(amount) from transaction where OID=\"$pOID\" and isdeleted=0 and IsCredit=0";
    $Result    = $this->ADb->ExecuteQuery($strSQL);
    
    $Debit = $Result->fields[0];
    
    
    $Balance = number_format($Credit - $Debit,2);
    
    
    return $Balance;
    
    
}

    
    function getTransactionInfoByOIDByDateAdmin($pOID,$pMonth,$pYear,$pDID)
    {
        if($pDID != '')
        {
            $WhereDID = " and description like '%$pDID%' ";
        }
        $strSQL    = "    select
                    transactionid,
                    oid,
                    description,
                    type,
                    referenceid,
                    amount,
                    iscredit,
                    date,
                    iscompleted,
                    isdeleted
                from
                    transaction
                where
                    oid=\"$pOID\" and
                    isdeleted!=2 $WhereDID
                    and date like '$pYear-$pMonth-%'    
                order by date
                ";
                
                $Result    = $this->ADb->ExecuteQuery($strSQL);
                
                return $Result;
    }
    function getTransactionInfoByOIDTableAdminDelete($pOID,$pMonth,$pYear,$pDID)
    {
        $Transaction        = $this->getTransactionInfoByOIDByDateAdmin($pOID,$pMonth,$pYear,$pDID);
        
        $Debit    = sprintf("%.2f",0);
        $Credit    = sprintf("%.2f",0);
        $html = "";
        if($Transaction->EOF == "")
        {
            $fSwitch=1;
            while(!$Transaction->EOF)
            {
                $TransactionID      = $Transaction->fields[0];
                $OID                = $Transaction->fields[1];
                $Desc               = $Transaction->fields[2];
                $Type               = $Transaction->fields[3];
                $ReferenceID        = $Transaction->fields[4];
                $Amount             = $Transaction->fields[5];
                $IsCredit           = $Transaction->fields[6];
                $Date               = $Transaction->fields[7];
                $IsCompleted        = $Transaction->fields[8];
                $IsDeleted          = $Transaction->fields[9];
            if($IsDeleted) 
            {
//                $Transaction->MoveNext();
            }
            if($fSwitch)
            {
                $html    .= "<tr bgcolor=#f0f0f0>";
                $fSwitch=0;
            }
            else
            {
                $html    .= "<tr>";
                $fSwitch=1;
            }
            # Transaction ID
            $html    .= "<td>";
            $html    .= "<font face=verdana size=-1>";
            $html    .= "<a href='/admins/OldTransactions.php?TID=$TransactionID' style='{font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; color:#333333; font-weight:bold;}'>$TransactionID</a>";
            $html    .= "</font>";
            $html    .= "</td>";
            # Date
            $html    .= "<td nowrap>";
            $html    .= "<font face=verdana size=-1>";
            $html    .= "$Date";
            $html    .= "</font>";
            $html    .= "</td>";             
            # Description
            $html    .= "<td nowrap>";
            $html    .= "<font face=verdana size=-1>";
            $html    .= "$Desc";
            $html    .= "</font>";
            $html    .= "</td>";
            if(!$IsCredit)
            {
                $Debit+=$Amount;
                
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$Amount.00";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "&nbsp";
                $html    .= "</font>";
                $html    .= "</td>";
            }
            else
            {
                $Credit+=$Amount;
                
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "&nbsp";
                $html    .= "</font>";
                $html    .= "</td>";
                $html    .= "<td align=right>";
                $html    .= "<font face=verdana size=-1>";
                $html    .= "$Amount.00";
                $html    .= "</font>";
                $html    .= "</td>";
            }
            
            # Delete Check Box
            $html    .= "<td align=right>";
            $html    .= "<font face=verdana size=-1><center>";
            $html    .= "<input type=checkbox name='TransactionID' value='$TransactionID'>";
            $html    .= "</font>";
            $html    .= "</center></td>";
            
            # Edit Transaction
            $html    .= "<td align=right>";
            $html    .= "<font face=verdana size=-1><center>";
            $html    .= "<a href='/admins/OldTransactions.php?TID=$TransactionID' style='{font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px; color:#333333; font-weight:bold;}'>Edit</a>";
            $html    .= "</font>";
            $html    .= "</center></td>";
            $html    .= "</tr>";
            $Transaction->MoveNext();
        }
        $htmlDebit    = sprintf("%.2f", $Debit);
        $htmlCredit    = sprintf("%.2f", $Credit);
        #Total Credit Total Debit
        $html    .= "<tr>
                <td align=right colspan=4>
                    <font face=verdana size=-1>\$$htmlDebit</font>
                </td>
                <td align=right>
                    <font face=verdana size=-1>\$$htmlCredit</font>
                </td>
        </tr>";

        $html    .= "<tr><td align=right colspan=7><font face=verdana size=-1><INPUT type=submit name=Delete value='Delete'></font></td></tr>";
        
        $nTotalAmount    = sprintf("%.2f", $Debit-$Credit);
        $TotalMessage;
        if($nTotalAmount<0)
        {
            $nTotalAmount    = sprintf("%.2f", (-1)*$nTotalAmount);
            $TotalMessage    = "Client has to pay: <font color=red>$nTotalAmount</font><br><br>";
            $TotalMessage    .= "<b><a href=\"FriendlyReminder.php?OID=$gOID\">Send Friendly Reminder</a>&nbsp;&nbsp;&nbsp;<a href=/cgi-bin/virtual/admins/AChargeNowAction.cgi?OID=$gOID>Charge Now!</a></b>";
        }
        elseif($nTotalAmount==0)
        {
            $TotalMessage    = "Balance: $nTotalAmount";
        }
        elseif($nTotalAmount>0)
        {
            $TotalMessage    = "The customer has over paid us: $nTotalAmount<br>";
            $TotalMessage    .= "<br><br>Return Amount";
        }
        $html    .= "<tr><td align=right colspan=7><font face=verdana size=-1>$TotalMessage</font></td></tr>";
        
        
    }
    else
    {
        $html    = "<tr><td colspan=5><center><font face=verdana size=-1>No records found</font></center></td></tr>";
    
    }
    return $html;
}

function addTransaction_niha($Hash) 
{
	print_r($Hash);
		$IP = $_SERVER['REMOTE_ADDR'];
		
		if($Hash[Date] == '')
			$strSQL	= "	insert into		transaction	(
						transactionid,
						oid,
						description,
						type,
						referenceid,
						amount,
						iscredit,
						iscompleted,
						date,
						DIDID,SLOC,IP,WHODID,VendorID
					)
				values (
						\"$Hash[TransactionID]\",
						\"$Hash[OID]\",
						\"$Hash[Desc]\",
						\"$Hash[Type]\",
						\"$Hash[ReferenceID]\",
						\"$Hash[Amount]\",
						\"$Hash[IsCredit]\",
						\"$Hash[Buyer]\",
						curdate(),
						\"$Hash[DID]\",
						\"$Hash[SLOC]\",
						\"$IP\",
						\"$Hash[WHODID]\",
						\"$Hash[VendorID]\"
					)";
				
		
		else
			// $MyDate = $Hash[Date];
			 #  \"$MyDate\",
		$strSQL	= "	insert into	transaction	(
						transactionid,
						oid,
						description,
						type,
						referenceid,
						amount,
						iscredit,
						iscompleted,
						date,
						DIDID,
						SLOC,
						IP,
						WHODID,
						VendorID
					)
				values (
						\"$Hash[TransactionID]\",
						\"$Hash[OID]\",
						\"$Hash[Desc]\",
						\"$Hash[Type]\",
						\"$Hash[ReferenceID]\",
						\"$Hash[Amount]\",
						\"$Hash[IsCredit]\",
						\"$Hash[Buyer]\",
						\"$Hash[Date]\",
                        \"$Hash[DID]\",
                        \"$Hash[SLOC]\",
                        \"$IP\",
                        \"$Hash[WHODID]\",
                        \"$Hash[VendorID]\"
					)";
				
		print_r($this->dPrint("\$strSQL: $strSQL")); 
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		// print_r($Result); exit;
		return $Result;
	}

	function getTransactionInfoByOIDClient($pOID,$pDID,$pMonth,$pYear,$pSort,$pDir,$OrderBy,
	$WhereDID,$Balance) {
          $myADb=new ADb(); 

  $strSQL	= "	select
					transactionid,
					oid,
					description,
					type,
					referenceid,
					amount,
					iscredit,
					date_format(date,'%d-%b-%Y') as date,
					iscompleted,
					isdeleted
				from
					transaction
				where
					oid=\"$pOID\" and
					isdeleted=0 and
					Date like '$pYear-$pMonth%'
					$WhereDID			
				    $OrderBy 
				";
// echo $strSQL;
// exit;

// // exit;

	$Result	= $this->ADb->ExecuteQuery($strSQL);
	
	return $Result;

}




	function GetYearMonth($What){
	
	$strSQL = "select substring(curdate(),1,4),substring(curdate(),6,2)";

	$Result	= $this->ADb->ExecuteQuery($strSQL);

	if($What=="Month")

		return $Result->fields[1];
	if($What=="Year")
		
		return $Result->fields[0];
   }


	function updateForFreeDID($DIDNumber,$purchaseddate,$typeCheck){
		$UID = currentUser();
		$strSQL = "update transaction set isdeleted=1,whodel=\"$UID\",WHODELDATE=now() where oid=\"$UID\" and 
		description like \"%:$DIDNumber%\" and substring(date,1,10) >='$purchaseddate' and ($typeCheck)";
		$Result	= $this->ADb->ExecuteQuery($strSQL);
    }

	function updatePurchaseddateToFreeDID($UID,$SellerID,$ffDIDID,$purchaseddate){
		$strSQL = "update transaction set isdeleted=1 ,whodel=\"$UID\",WHODELDATE=now()
				where oid=\"$SellerID\" and (type like 'PAY%' or type like 'ACTP%' or type='CHAP' or type like 'CHP%' ) and DIDID=\"$ffDIDID\" and substring(date,1,10) >='$purchaseddate'";
		$Result	= $this->ADb->ExecuteQuery($strSQL);
		return $Result;
    }
   
   
	function getIfDelTransAllowed($pOID) {
    
	     $myADb=new ADb();
	    
	    $strSQL = " select ShowDelTrans from EmailPref where uid=\"$pOID\" ";

	    $Result = $myADb->ExecuteQuery($strSQL);
	    
	    $IsAllowed = $Result->fields[0];
	    
        return $IsAllowed;
    
	}

	function updateTransToFreeDID($UID,$SellerID,$ffDIDID,$InvPayType) {
	     $myADb=new ADb();
	    $strSQL = "update transaction set isdeleted=1,whodel=\"$UID\",
						WHODELDATE=now() where oid=\"$SellerID\" and DIDID=\"$ffDIDID\" 
						and(type='$InvPayType')";

	    $Result = $myADb->ExecuteQuery($strSQL);
	    
	    $IsAllowed = $Result->fields[0];
	    
        return $IsAllowed;
    
	}



}



?>