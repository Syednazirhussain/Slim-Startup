<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
class General
{
    
    var $fDebug = 1;
    function dPrint($str)
    {
        if ($this->fDebug)
            echo "$str<br>\n";
    }
    function General($dbg = 0)
    {
        // include_once("Const.inc.php");
        // include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
        // include_once($GLOBALS['INCLUDEPATH']."/User.inc.php");
        // include_once($GLOBALS['INCLUDEPATH']."/Customer.inc.php");
        // include_once($GLOBALS['INCLUDEPATH']."/Transaction.inc.php");
        
        //include_once("Const.inc.php");
        // include_once("ADb.inc.php");
        // include_once("User.inc.php");
        // include_once("Customer.inc.php");
        // include_once("Transaction.inc.php");
        $this->ADb           = new ADb();
        $this->myCustomer    = new Customer();
        $this->myUser        = new User();
        $this->myTransaction = new Transaction();
        $this->fDebug        = $dbg;
        
    }
    
    function GetTriggerPricing($AreaID, $VOID)
    {
        
        $strSQL = "select Rate,Triggers from TriggerAdmin 
    where VOID=\"$VOID\"  and (AreaID=\"$AreaID\" or AreaID=\"-1\") ";
        #echo "<br>$strSQL";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        if ($Result->EOF) {
            return "-1";
        }
        
        $Rate = $Result->fields[0];
        
        return $Rate;
        
    }
    
    function CreateAdminAlert($Msg, $ID, $Type = 0)
    {
        
        $strSQL    = "select * from AdminAlerts where MessageID = '$ID' ";
        $ResultLog = $this->ADb->ExecuteQuery($strSQL);
        
        if ($ResultLog->EOF) {
            $strSQL    = "insert into AdminAlerts(MessageID)values('$ID') ";
            $ResultLog = $this->ADb->ExecuteQuery($strSQL);
        }
        
        $strSQL    = "update AdminAlerts set Message='$Msg',Type='$Type',Date=now() where MessageID = '$ID' ";
        $ResultLog = $this->ADb->ExecuteQuery($strSQL);
        
        
    }
    
    function GetSpecialDIDMSG($AreaID, $VOID)
    {
        
        $strSQL    = "select DocMsg from DIDMsg where OID=\"$VOID\" and (Prefix=\"$AreaID\" or Prefix=\"-1\") ";
        $ResultLog = $this->ADb->ExecuteQuery($strSQL);
        
        return $ResultLog->fields[0];
        
        
    }
    function WriteInActivtyLog($pUID, $pAct, $pOID)
    {
        $strSQL = "insert into activitylog(uid, orderid, activity, timestamp, ip, proxy)values('$pUID', '$pOID', '$pAct', sysdate(), '$ENV{REMOTE_ADDR}', '$ENV{HTTP_X_FORWARDED_FOR}')";
        $Result = $this->ADb->ExecuteQuery($strSQL);
    }
    function UpdateComplainView($ComplainID, $Admin, $OID, $Loc)
    {
        
        if ($ComplainID != "") {
            
            $strSQL    = "select ComplainID from ComplainView where ComplainID='$ComplainID' and Admin='$Admin' ";
            $ResultLog = $this->ADb->ExecuteQuery($strSQL);
            
            if ($ResultLog->EOF) {
                $strSQL    = "insert into ComplainView(ComplainID,Admin,Location,OID)values
                            ('$ComplainID','$Admin','$Loc','$OID')";
                //echo "hello".$strSQL;
                $ResultLog = $this->ADb->ExecuteQuery($strSQL);
            }
        }
        
        
    }
    
    function parseTelephoneWithDash($pTelephone)
    {
        $pTelephone = split('-', $pTelephone);
        return $pTelephone;
    }
    
    function getSalutationList($pSalutation)
    {
        $htmlSalutation = "
    <option value='Mr'>Mr.</option>
    <option value='Mrs'>Mrs.</option>
    <option value='Ms'>Ms.</option>
    ";
        $Pat            = "'$pSalutation'>";
        $Rep            = "'$pSalutation' selected>";
        $htmlSalutation = str_replace("'$Pat'", "'$Rep' selected ", $htmlSalutation);
        return $htmlSalutation;
    }
    
    function RemoveAdminAlert($ID, $Type = 0) //@todo $Msg,
    {
        
        $strSQL    = "delete from AdminAlerts where MessageID = '$ID' ";
        $ResultLog = $this->ADb->ExecuteQuery($strSQL);
        return $ResultLog;
        
        
        
        
    }
    
    function getFormatedDateByDate($pDate)
    {
        
        $myADb  = new ADb();
        $strSQL = " select '$pDate',date_format('$pDate','%d-%M-%Y'),date_format('$pDate','%M-%Y')";
        $Result = $myADb->ExecuteQuery($strSQL);
        
        $Hash;
        
        $Hash[Format1] = $Result->fields[0];
        $Hash[Format2] = $Result->fields[1];
        $Hash[Format3] = $Result->fields[2];
        
        return $Hash;
    }
    function validate4CreditCardTransaction($pHash)
    {
        
        
        $strError;
        # Credit Card
        $CCMonth = $pHash['ExpiryMonth'];
        $CCYear  = $pHash['ExpiryYear'];
        if ($CCYear < date("Y")) {
            $strError .= "The Credit Card is expired.<br>";
        }
        
        $isValidCard = $this->validateCreditCardTypeAndNumber($pHash['Type'], $pHash['Number']);
        if ($isValidCard != "1") {
            $strError .= "Credit Card's Type and Number are not valid<br>";
        }
        # Address
        if (($pHash['Street1'] == "N/A") || ($pHash['Street1'] == "")) {
            $strError .= "Your address is incompleted. Please go to 'User Profile' and enter your complete address by entring Street1.<br>";
        }
        if (($pHash['City'] == "N/A") || ($pHash['City'] == "")) {
            $strError .= "Your address is incompleted. Please go to 'User Profile' and enter your complete address by entring your CITY.<br>";
        }
        if (($pHash['ZipCode'] == "N/A") || ($pHash['ZipCode'] == "")) {
            $strError .= "Your address is incompleted. Please go to 'User Profile' and enter your complete address by entring your Zip Code<br>";
        }
        if (($pHash['State'] == "N/A") || ($pHash['State'] == "")) {
            $strError .= "Your address is incompleted. Please go to 'User Profile' and enter your complete address by entring your State.<br>";
        }
        if (($pHash['Country'] == "N/A") || ($pHash['Country'] == "")) {
            $strError .= "Your address is incompleted. Please go to 'User Profile' and enter your complete address by entring your Country.";
        }
        return $strError;
        
    }
    function validateCreditCardTypeAndNumber($pCCType, $pCCNumber)
    {
        
        
        $strError;
        if ($pCCType == "MAST") {
            # Starting
            //if ($pCCNumber !~ /^5/){
            if (substr($pCCNumber, 0, 1) != 5) {
                $strError .= "Master Card should start with digit 5.";
            }
            //if (length($pCCNumber) != 16){
            if (strlen($pCCNumber) != 16) {
                
                $strError .= " Master Card number should be 16 digit long.";
            }
        } elseif ($pCCType == "VISA") {
            # Starting
            if (substr($pCCNumber, 0, 1) != 4) {
                $strError .= "VISA Card should start with digit 4. ";
            }
            if (strlen($pCCNumber) != 16) {
                $strError .= "VISA Card number should be 16 digit long.";
            }
        } elseif ($pCCType == "AMEX") {
            # Starting
            #if ($pCCNumber !~ /^34|7/){
            if (substr($pCCNumber, 0, 2) != 34 && substr($pCCNumber, 0, 2) != 37) {
                
                $strError .= "Internal Server Message: American Express Card should start with digits 34 or 37. ";
            }
            if (strlen($pCCNumber) != 15) {
                $strError .= "American Express Card number should be 16 digit long.";
            }
        } elseif ($pCCType == "DISC") {
            # Starting
            #if ($pCCNumber !~ /^60/){
            if (substr($pCCNumber, 0, 2) != 60) {
                $strError .= "Discovery Card should start with digits 60.";
            }
            if (strlen($pCCNumber) != 16) {
                $strError .= "Discovery Card number should be 16 digit long.";
            }
        } elseif (($pCCType == "") || ($pCCNumber == "N/A")) {
            $strError .= "No Credit Card Information Available.";
        }
        if ($strError == "") {
            $strError = 1;
        }
        //    echo $strError;
        return $strError;
    }
    
    function IsValidEmail($email)
    {
        if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) > 0) {
            return true;
        } else {
            return false;
        }
    }
    function getCCTypeDesc($pCCType)
    {
        
        if ($pCCType == "VISA") {
            return "VISA";
        } elseif ($pCCType == "MAST") {
            return "Master";
        } elseif ($pCCType == "AMEX") {
            return "American Express";
        } elseif ($pCCType == "DISC") {
            return "Discovery";
        }
    }
    
    function getIfCardExpired($CCMonth, $CCYear)
    {
        
        $strSQL     = "select curdate()";
        $ResultDate = $this->ADb->ExecuteQuery($strSQL);
        
        $TodaysCurrent = $ResultDate->fields[0];
        
        $ThisMonth = substr($TodaysCurrent, 5, 2);
        $ThisYear  = substr($TodaysCurrent, 0, 4);
        
        if ($ThisYear > $CCYear) {
            return 1;
        }
        
        if ($ThisYear == $CCYear && $ThisMonth > $CCMonth) {
            return 1;
        }
        
        return 0;
    }
    
    public static function GetAllMySMS()
    {
        $myADb  = new ADb();
        $UID    = currentUser();
        // @todo limit query to add based on our pagination 
        $strSQL = "SELECT ID,DID,`text`,`SMSFrom`,uniid,DATE_FORMAT(`Datetime`,'%d-%b-%Y %h:%i:%s') as Date,
              (rate/100) as rate FROM SMSLog WHERE OID=\"$UID\" order by Datetime desc limit 0,100 ";
        
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
        
        
    }
    
    function GetCurrentProratedInformation($TotalAmount, $OID, $VOID, $Payment, $DIDNumber, $DIDID)
    {
        
        $strSQL = "select DATEDIFF(Last_Day(curdate()),curdate()),date_format(Last_Day(curdate()),'%d-%b-%Y'),YEAR(CURDATE()),SUBSTRING(CURDATE(),6,2),date_format(curdate(),'%d-%b-%Y')";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $TotalRemDays = $Result->fields[0];
        $Period       = $Result->fields[1];
        $Year         = substr($Result->fields[2], 2, 2);
        $Month        = $Result->fields[3];
        
        $Span = $Result->fields[4] . " to " . $Result->fields[1];
        
        $TypePVPL = "PVPL$Month$Year";
        $TypePAY  = "PAY$Month$Year";
        
        $PerDayCharge = $TotalAmount / 30;
        
        $PerDayChargePay = $Payment / 30;
        
        if ($PerDayCharge < 0.01)
            $DecimalB = "6";
        else
            $DecimalB = "3";
        
        
        if ($PerDayChargePay < 0.01)
            $DecimalP = "6";
        else
            $DecimalP = "3";
        
        $BillCharge = number_format($PerDayCharge * $TotalRemDays, $DecimalB);
        
        $PaymentCharge = number_format($PerDayChargePay * $TotalRemDays, $DecimalP);
        
        
        if (!$this->IsAlreadyExits($VOID, $TypePAY, $DIDNumber)) {
            $TID                  = $this->myTransaction->getTransactionID($VOID);
            $Txn['TransactionID'] = $TID;
            $Txn['OID']           = $VOID;
            $Txn['ReferenceID']   = $VOID;
            $Txn['Desc']          = "[DIDx:$DIDNumber] Monthly Payment $Span";
            $Txn['Type']          = $TypePAY;
            $Txn['IsCredit']      = 0;
            $Txn['Amount']        = $PaymentCharge;
            $Txn['DID']           = $DIDID;
            $Txn['SLOC']          = "CLIENT_DASH";
            $Txn['WHODID']        = $OID;
            $Txn['VendorID']      = $VOID;
            $Txn['Buyer']         = $OID;
            $this->myTransaction->addTransaction($Txn);
            
            
        }
        
        $TID                  = $this->myTransaction->getTransactionID($OID);
        $Txn['TransactionID'] = $TID;
        $Txn['OID']           = $OID;
        $Txn['ReferenceID']   = $OID;
        $Txn['Desc']          = "[DIDx:$DIDNumber] Monthly Bill $Span";
        $Txn['Type']          = $TypePVPL;
        $Txn['IsCredit']      = 1;
        $Txn['Amount']        = $BillCharge;
        $Txn['DID']           = $DIDID;
        $Txn['SLOC']          = "CLIENT_DASH";
        $Txn['WHODID']        = $OID;
        $Txn['VendorID']      = $VOID;
        $Txn['Buyer']         = $OID;
        $this->myTransaction->addTransaction($Txn);
        
        
        return 1;
        
    }
    
    function CheckPurOnly($pOID)
    {
        $strsql = "SELECT PurchasedOnly from EmailPref where UID='$pOID'";
        $Result = $this->ADb->ExecuteQuery($strsql);
        return $Result->fields[0];
    }
    
    function CheckVendorOnly($pOID)
    {
        $strsql = "SELECT VendorsOnly from EmailPref where UID='$pOID'";
        $Result = $this->ADb->ExecuteQuery($strsql);
        return $Result->fields[0];
    }
    function CheckBuyerOnly($pOID)
    {
        $strsql_COUNT = "SELECT count(*) from EmailPref where BuyersOnly!=''";
        $Result_Count = $this->ADb->ExecuteQuery($strsql_COUNT);
        
        $strsql = "SELECT UID from EmailPref where BuyersOnly!=''";
        // return $strsql;
        $Result = $this->ADb->ExecuteQuery($strsql);
        $counts = $Result_Count->fields[0];
        ;
        //echo "new".$counts;
        //echo $strsql;
        
        $counting = 1;
        while (!$Result->EOF) {
            for ($i = 1; $i <= $counts; $i++) {
                $comma = "";
                if ($counting != $counts) {
                    $comma = ",";
                }
            }
            
            $HTML     = $Result->fields[0] . $comma;
            $counting = $counting + 1;
            
            $Result->MoveNext();
        }
        
        // echo  $HTML;
        return $HTML;
    }
    function IsAlreadyExits($pOID, $pType, $pDID)
    {
        
        $myADb = new ADb();
        
        $strSQL = "select * from transaction     where OID=\"$pOID\"
                                 and Type = \"$pType\"     and description like \"%$pDID%\" and IsDeleted=0";
        
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        if ($Result->EOF)
            return 0;
        else
            return 1;
        
    }
    
    
    function getCountryList()
    {
        $html;
        
        $strSQL = "select * from DIDCountries order by CountryCode";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        While (!$Result->EOF) {
            
            $html .= "<option value=" . $Result->fields[0] . ">" . $Result->fields[2] . " - " . $Result->fields[1] . "</option>";
            $Result->MoveNext();
        }
        
        return $html;
        
        
    }
    
    
    function getCountryList_name()
    {
        $html;
        
        $strSQL = "select * from DIDCountries order by CountryCode";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        $html .= "<option value=\"All\">--All--</option>";
        While (!$Result->EOF) {
            
            $html .= "<option value=" . $Result->fields[1] . ">" . $Result->fields[2] . " - " . $Result->fields[1] . "</option>";
            $Result->MoveNext();
        }
        
        return $html;
        
        
    }
    
    function getAllCountryListByName()
    {
        $html = '';

        $strSQL = "select * from DIDCountries order by Description";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        $html .= "<option value=\"All\">--All--</option>";
        While (!$Result->EOF) {
            
            $html .= "<option value=" . $Result->fields[1] . ">" . $Result->fields[1] . "</option>";
            $Result->MoveNext();
        }
        
        return $html;
        
        
    }

    function getCountriesPhone()
    {
        $html='';

        $strSQL = "select CountryCode,Description from DIDCountries order by CountryCode";
        $Result = $this->ADb->ExecuteQuery($strSQL);
       while(!$Result->EOF)
        {
            $Desc = $Result->fields[1];
            $Code = $Result->fields[0];
            // if($Desc==$CurrCountry)
            // {
            //     $html .= "<option value=\"$Code\" selected >$Code - $Desc</option>";
            // }
            // else
            // {
                $html .= "<option value=\"$Code\"  >$Code - $Desc</option>";
            // }
            $Result->MoveNext();
        }
        

    
        return $html;
        
        
    }


    
    
    function StartMinutesCount($pDID, $pOID, $Triggers = 0)
    {
        $myADb=new ADb();
        
        $strSQL = "insert into MinutesInfo(OID,DID,Expiry,Triggers)values(\"$pOID\", \"$pDID\", date_add(curdate(),interval 1 month),\"$Triggers\" )";
        #echo $strSQL;
        $Result =  $myADb->ExecuteQuery($strSQL);
        return $Result;
        
    }
    
    function UpdateReferCode($pOID)
    {
        
        $strSQL = "select ReferCode from orders where OID='$pOID'  ";
        #echo $strSQL;
        $Result = $this->ADb->ExecuteQuery($strSQL);
        if ($Result->fields[0] == "") {
            
            $strSQL = "update orders set ReferCode=substring(md5(concat(OID,CustomerID)),1,15) where OID='$pOID'  ";
            #    echo $strSQL;
            $Result = $this->ADb->ExecuteQuery($strSQL);
            
        }
        
        
    }
    
    function getRateCenterLists($pAreaID)
    {
        $html="";
        $strSQL = "select StateCode from DIDArea where Id=\"$pAreaID\" ";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $strSQL    = "select NXX,RateCenter from USAreas where NPA='" . $Result->fields[0] . "' ";
        $ResultNXX = $this->ADb->ExecuteQuery($strSQL);
        
        $html .= "<option value=\"ANY\"  >Any</option>\n";
        
        
        while (!$ResultNXX->EOF) {
            
            $selected = "";
            $Area     = $ResultNXX->fields[1];
            if ($Area == '/N' || $Area == '') {
                
                $Area = "";
            }
            
            
            $html .= "<option value=" . $ResultNXX->fields[0] . "> " . $ResultNXX->fields[0] . " - " . $Area . "</option>\n";
            
            $ResultNXX->MoveNext();
            
        }
        return $html;
        
    }

    function getDefinedNXXListByID($ID,$ffNXX) {
    
        //global $myADb;
        
        $strSQL = "select CountryCode,DIDArea from BackOrderAdmin where ID='$ID'";
        #echo $strSQL ;
        $Result= $this->ADb->ExecuteQuery($strSQL);
    
        
        $pCountry = $Result->fields[0];
        $pArea = $Result->fields[1];

        $html; #.= "<option value=\"\">-Select-</option>";
        $WhereClause = "  where CountryCode = \"$pCountry\" and DIDArea=\"$pArea\" ";
    
        $strSQL = " select NXX,RateCenter from BackOrderAdmin $WhereClause group by NXX order by cast(NXX as unsigned)";    
    #   echo $strSQL ;
        $Result= $this->ADb->ExecuteQuery($strSQL);
    
        $html = "";
        $Selected = "";
    
        while(!$Result->EOF){
            
            
                $NXX = $Result->fields[0];
                $RateCenter = $Result->fields[1];
            
                $AreaDesc = "$NXX - $RateCenter";
            
                if($ffNXX==$NXX)            
                        $html .= "<option value=\"$NXX\" selected >$AreaDesc</option>";
                else
                        $html .= "<option value=\"$NXX\" >$AreaDesc</option>";
                
                $Result->MoveNext();
            
        }
        
        $html .= "<option value=\"-1\" $Selected>ANY</option>";
        
        return $html;
    
    }
    
    function GetCountryAreaByAreaID($AreaID)
    {
        
        $strSQL = "select DIDCountries.CountryCode,DIDCountries.Description,DIDArea.StateCode,DIDArea.Description as a from DIDCountries,DIDArea where
                        DIDArea.ID=\"$AreaID\"  and DIDCountries.ID=DIDArea.CountryID    ";
        
        #    echo $strSQL;
        
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $CountryCode = $Result->fields[0];
        $Country     = $Result->fields[1];
        $AreaCode    = $Result->fields[2];
        $AreaName    = $Result->fields[3];
        
        
        return "($CountryCode) $Country - ($AreaCode) $Areaname";
        
        
    }
    
    function getAreaLists($CountryID, $AreaID)
    {
        
        $strSQL = "select DIDArea.id,DIDArea.description,DIDArea.StateCode    
                         from DIDArea,DIDCountries where 
                         CountryID = DIDCountries.id
                         and CountryID='$CountryID'
                         order by length(StateCode),DIDArea.StateCode
                        ";
        
        #    echo $strSQL;
        
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $html = "";
        
        
        while (!$Result->EOF) {
            
            if ($Result->fields[2] == "-99") {
                $selected = "";
                if ($Result->fields[0] == $AreaID) {
                    $selected = " selected ='selected' ";
                }
                $html .= "<option value = " . $Result->fields[0] . " $selected> No Area Code Required</option>\n";
            }
            
            else {
                $selected = "";
                if ($Result->fields[0] == $AreaID) {
                    $selected = " selected ='selected' ";
                }
                $html .= "<option value=" . $Result->fields[0] . " $selected> " . $Result->fields[2] . " - " . $Result->fields[1] . "</option>\n";
            }
            
            $Result->MoveNext();
        }
        return $html;
        
    }
    
    function getCountriesListbyId($Code)
    {
        
        
        $strSQL = "select * from DIDCountries order by countrycode";
        # echo $strSQL;
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $html = "<option>Select Country</option>";
        
        while (!$Result->EOF) {
            
            $selected = "";
            if ($Result->fields[0] == $Code)
                $selected = " selected ='selected'";
            else
                $selected = "";
            
            $html .= "<option value=" . $Result->fields[0] . "$selected>" . $Result->fields[2] . "-" . $Result->fields[1] . "</option>\n";
            
            $Result->MoveNext();
            
        }
        
        
        
        
        return $html;
    }
    
       function getCountriesListbyId2($Code)
    {
         $strSQL = "select * from CountriesAvail order by cast(countrycode as unsigned)";
        
        //$strSQL = "select * from DIDCountries order by countrycode";
        # echo $strSQL;
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $html = "";
        
        while (!$Result->EOF) {
            
            $selected = "";
            if ($Result->fields[0] == $Code)
                $selected = " selected ='selected'";
            else
                $selected = "";
            
            $html .= "<option value=" . $Result->fields[0] . "$selected>" . $Result->fields[1] . "-" . $Result->fields[2] . "</option>\n";
            
            $Result->MoveNext();
            
        }
        
        
        
        
        return $html;
    } 
    
    
    // function GetIfChannelCanBePurchased($pAreaID, $OID)
    // {
        
    //     $myADb = new ADb();
        
    //     $strSQL = "select Setup,Monthly  from ChannelAdmin where oid=\"$OID\" and  AreaID=\"$pAreaID\" and  AreaID!=\"-1\" ";
    //     $Result = $myADb->ExecuteQuery($strSQL);
        
    //     $ChannelRate            = array();
    //     $ChannelRate['Setup']   = $Result->fields[0];
    //     $ChannelRate['Monthly'] = $Result->fields[0];
        
    //     return $ChannelRate;
        
    // }
    
    function GetIfChannelCanBePurchased($pAreaID,$OID){
    
    
    $myADb = new ADb();
    
    $strSQL = "select Setup,Monthly  from ChannelAdmin where oid=\"$OID\" and  AreaID=\"$pAreaID\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
    //echo $strSQL; 
            if($Result->EOF){
                
                $strSQL = "select Setup,Monthly  from ChannelAdmin where oid=\"$OID\" and  AreaID=\"-1\" ";
                $Result = $myADb->ExecuteQuery($strSQL);
                
                    if($Result->EOF)
                            return 0;
            }
            
            $ChannelRate=array();
            $ChannelRate['Setup'] = $Result->fields[0];
            $ChannelRate['Monthly'] = $Result->fields[0];
    
        return $ChannelRate;
    
}
    
    
    function GetChannelPricing($AreaID, $VOID)
    {
        
        
        
        $strSQL = "select TotalChannels,Setup,Monthly from ChannelAdmin 
    where OID=\"$VOID\"  and AreaID=\"$AreaID\" ";
        #echo "<br>$strSQL";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        if ($Result->EOF) {
            
            $strSQL = "select TotalChannels,Setup,Monthly from ChannelAdmin 
            where OID=\"$VOID\"  and AreaID=\"-1\" ";
            #echo "<br>$strSQL";
            $Result = $this->ADb->ExecuteQuery($strSQL);
            
            if ($Result->EOF) {
                return "-1";
            }
            
        }
        
        $TotalChannels = $Result->fields[0];
        $BuyingSetup   = $Result->fields[1];
        $BuyingMonthly = $Result->fields[2];
        $data   = array(
            'TotalChannels' => $TotalChannels,
            'BuyingSetup' => $BuyingSetup,
            'ChannelPrice' => $BuyingMonthly //BuyingMonthly
        );
        // $ChannelPrice =  $Result->fields[2];
        
        return $data;
        
    }
    
    function GetMyPurchasedChannel($ffDID)
    {
        
        $myADb  = new ADb();
        $pUID   = currentUser();
        $strSQL = "select qty from ChannelBuy where oid=\"$pUID\" and DIDNumber=\"$ffDID\"";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result->fields[0];
    }
    
    function RecordLoggedMaster($pAdmin, $pActivity, $pOID, $pAmount, $pDID, $pID, $pText)
    {
        
        $IP            = $_SERVER['REMOTE_ADDR'];
        $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $Location      = $_SERVER['SCRIPT_FILENAME'];
        $ServerName    = $_SERVER['SERVER_NAME'] . $Location;
        
        $strSQL = "insert into AdminMasterLog(UID,OID,Location,Amount,BrowserName,Activity,IP,TransID,DID,ThreadMsg)
        values(\"$pAdmin\", \"$pOID\", \"$ServerName\", \"$pAmount\", \"$httpUserAgent\", \"$pActivity\",\"$IP\",\"$pID\",\"$pDID\" ,\"$pText\")";
        #    print $strSQL;
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        
    }
    
    function RecordLoggedClient($pAdmin,$pActivity,$pOID,$pAmount,$pDID,$pID,$pText) {
        
        $IP = $_SERVER['REMOTE_ADDR'];
        $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $Location = $_SERVER['SCRIPT_FILENAME'];
        $ServerName = $_SERVER['SERVER_NAME'] . $Location  ;
        
            $strSQL = "insert into AdminMasterLog(UID,OID,Location,Amount,BrowserName,Activity,IP,TransID,DID,ThreadMsg,LogType)
            values(\"$pAdmin\", \"$pOID\", \"$ServerName\", \"$pAmount\", \"$httpUserAgent\", \"$pActivity\",\"$IP\",\"$pID\",\"$pDID\" ,\"$pText\",\"1\")";
            #   print $strSQL;
            $Result = $this->ADb->ExecuteQuery($strSQL);
        
        
    }

    // function RecordLoggedClient($pActivity, $pAmount, $pDID, $pID, $pText)
    // {
    //     $myADb=new ADb();
    //     $pAdmin        = $pOID = currentUser();
    //     $IP            = $_SERVER['REMOTE_ADDR'];
    //     $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
    //     $Location      = $_SERVER['SCRIPT_FILENAME'];
    //     $ServerName    = $_SERVER['SERVER_NAME'] . $Location;
        
    //     $strSQL = "insert into AdminMasterLog(UID,OID,Location,Amount,BrowserName,Activity,IP,TransID,DID,ThreadMsg,LogType)
    //     values(\"$pAdmin\", \"$pOID\", \"$ServerName\", \"$pAmount\", \"$httpUserAgent\", \"$pActivity\",\"$IP\",\"$pID\",\"$pDID\" ,\"$pText\",\"1\")";
    //     #    print $strSQL;
    //     $Result = $myADb->ExecuteQuery($strSQL);
        
        
        
    // }
    
    function GetFBCredentials()
    {
        
        #    $myADb = new ADb();
        
        if ($pSiteID == "VPL")
            $pSiteID = "virtualphoneline.com";
        
        $strSQL = " select FBID,FBSecret from sitesetting ";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $MyFBApplicationID     = $Result->fields[0];
        $MyFBApplicationSecret = $Result->fields[1];
        
        
        $FBArray = array();
        
        $FBArray[MyFBApplicationID]     = $MyFBApplicationID;
        $FBArray[MyFBApplicationSecret] = $MyFBApplicationSecret;
        
        
        return $FBArray;
        
    }
    
    function DetectLink($pMsg)
    {
        
        $pMsg = preg_replace('/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '<a href="\0">\4</a>', $pMsg);
        return $pMsg;
        
    }
    
    function GetOrderStatus($pCode)
    {
        
        $CStatusCode = $pCode;
        
        if ($CStatusCode == 0) {
            $CustomerStatus = "Active";
        } else if ($CStatusCode == 1) {
            $CustomerStatus = "<font color=red><b>Deleted</b></font>";
        } else if ($CStatusCode == 2) {
            $CustomerStatus = "<font color=red><b>Terminated</b></font>";
        } else if ($CStatusCode == 3) {
            $CustomerStatus = "<font color=red><b>Awaiting Paper Work</b></font>";
        } else if ($CStatusCode == 4) {
            $CustomerStatus = "<font color=red><b>Awaiting Payment</b></font>";
        } else if ($CStatusCode == 5) {
            $CustomerStatus = "<font color=red><b>Awaiting Shipment</b></font>";
        } else if ($CStatusCode == 6) {
            $CustomerStatus = "<font color=red><b>Suspended</b></font>";
        } else {
            $CustomerStatus = "<font color=red><b>Not Mentioned</b></font>";
        }
        
        return $CustomerStatus;
        
    }
    
    function GetAccountType($pType)
    {
        
        $CStatusCode = $pCode;
        
        if ($pType == 1)
            $Type = "Seller";
        
        if ($pType == 2)
            $Type = "Trader";
        
        if ($pType == 0)
            $Type = "Buyer";
        
        return $Type;
        
    }
    function WriteToFile($pText)
    {
        $fp = fopen('/var/www/vhosts/didx.net/httpdocs/tmpupload/LoggedData.txt', 'a+');
        
        $myDate = $this->currentDbDate();
        fwrite($fp, $myDate . "\n\n" . $pText . "\n\n");
        fclose($fp);
    }
    
    function RecordBuy($pDID, $pOID, $pPlace, $pOffer, $pAct, $pPerson, $pReason, $UnderTime)
    {
        $myADb=new ADb();
        
        $strSQL = " insert into BuyHistory(DIDNumber,OID,OfferedDate,Place,Date,Activity,Person,ReasonRemove,UnderTime)
                        values(\"$pDID\",\"$pOID\",\"$pOffer\",\"$pPlace\",sysdate(),\"$pAct\",\"$pPerson\",\"$pReason\",\"$UnderTime\" )";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
        
    }
    
    
    function GetSingleRowQueryResult($SQL)
    {
        
        include_once "config_setting.php";
        
        $root = $config['master_dbuser'];
        $pass = $config['master_dbpass'];
        $host = $config['db3_host'];
        $db   = $config['master_db'];
        
        $link = mysql_connect($host, $root, $pass) or die('can not connect');
        mysql_select_db($db, $link) or die('error in select db - news -');
        $sql_attr = mysql_query($SQL) or die(mysql_error());
        while ($row = mysql_fetch_row($sql_attr)) {
            $AdventureName = $row[0];
        }
        
        return $AdventureName;
        
    }
    
    function getUIDByEncUID($pEncUID)
    {
        $strSQL = "Select uid    from    user Where    ( Type = 'ADMI') and isactive=1 and MD5(concat(uid,pass))=\"$pEncUID\" ";
        $this->dPrint("\$strSQL: $strSQL");
        $Result = $this->ADb->query($strSQL);
        if ($Result === false) {
            return "";
        } else {
            $uid = $Result->fields[0];
            return $uid;
        }
    } #getUserInfoByEncUID
    
    function getUIDByEncUID1($pEncUID)
    {
        $this->dPrint("\$pEncUID: $pEncUID");
        $strSQL = "select    UID from    user";
        $this->dPrint("\$strSQL: $strSQL");
        $Result = $this->ADb->ExecuteQuery($strSQL);
        while (!$Result->EOF) {
            //            $this->dPrint ("In while loop: ".$i++);
            $UID = $Result->fields[0];
            if (crypt($UID, "V914") == $pEncUID) {
                $NormalUID = $UID;
                return $NormalUID;
            }
            $Result->MoveNext();
        }
        return $NormalUID;
    } #getUIDByEncUID
    
    
    function getKey($var)
    {
        return substr(md5(dechex($var) . time() . rand() . $var), 0, 10);
    }
    
    function getUserOtherInformation($OID)
    {
        
        $strSQL = " select * from 
                                    EmailPref 
                                    where
                                    UID=\"$OID\" ";
        #print "\$strSQL: $strSQL";                
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $Hash               = array();
        $Hash['MSN']        = $Result->fields[6];
        $Hash['Yahoo']      = $Result->fields[7];
        $Hash['ICQ']        = $Result->fields[8];
        $Hash['Skype']      = $Result->fields[9];
        $Hash['PayPal']     = $Result->fields[10];
        $Hash['News']       = $Result->fields[1];
        $Hash['Sold']       = $Result->fields[5];
        $Hash['Bought']     = $Result->fields[19];
        $Hash['Request']    = $Result->fields[2];
        $Hash['ServerType'] = $Result->fields[15];
        $Hash['ServerIP']   = $Result->fields[16];
        $Hash['TechName']   = $Result->fields[12];
        $Hash['TechEmail']  = $Result->fields[13];
        $Hash['TechPhone']  = $Result->fields[14];
        $Hash['NODIDS']     = $Result->fields[25];
        $Hash['H323']       = $Result->fields[26];
        $Hash['TTAutoPay']  = $Result->fields[27];
        $Hash['BackOrder']  = $Result->fields[35];
        
        return $Hash;
    }
    
    function getSusUnsusStatus($pOrderId)
    {
        
        $strSQL = "    select
                    reqid,
                    soid,
                    auid,
                    dat,
                    susstatus,
                    discription,
                    isdone,
                    isemailed,
                    emaildate,
                    counter,
                    counterover
                from
                    suspunsuspstatus
                where
                    soid=\"$pOrderId\"
                    order by reqid desc
                    limit 0,1    
                    ";
        
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        
        $Hash;
        
        $Hash[ReqId]       = $Result->fields[0];
        $Hash[SOID]        = $Result->fields[1];
        $Hash[AUID]        = $Result->fields[2];
        $Hash[DAT]         = $Result->fields[3];
        $Hash[SusStatus]   = $Result->fields[4];
        $Hash[Discription] = $Result->fields[5];
        
        $Hash[IsDone]      = $Result->fields[6];
        $Hash[IsEmailed]   = $Result->fields[7];
        $Hash[EmailDate]   = $Result->fields[8];
        $Hash[Counter]     = $Result->fields[9];
        $Hash[CounterOver] = $Result->fields[10];
        return $Hash;
        
    } #
    
    function currentDbDate()
    {
        
        $strSQL = "select now()";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        return $Result->fields[0];
        
    }
    
    function getStateNameByStateCode($pStateCode)
    {
        
        $State                        = array();
        $State[AL]                    = "Alabama AL";
        $State[AK]                    = "Alaska AK";
        $State[AZ]                    = "Arizona AZ";
        $State[AR]                    = "Arkansas AR";
        $State[CA]                    = "California CA";
        $State[CO]                    = "Colorado CO";
        $State[CT]                    = "Connecticut CT";
        $State[DC]                    = "Washington D.C. DC";
        $State[DE]                    = "Delaware DE";
        $State[FL]                    = "Florida FL";
        $State[GA]                    = "Georgia GA";
        $State[HI]                    = "Hawaii HI";
        $State[ID]                    = "Idaho ID";
        $State[IL]                    = "Illinois IL";
        $State[IN]                    = "Indiana IN";
        $State[IA]                    = "Iowa IA";
        $State[KS]                    = "Kansas KS";
        $State[KY]                    = "Kentucky KY";
        $State[LA]                    = "Louisiana LA";
        $State[ME]                    = "Maine ME";
        $State[MD]                    = "Maryland MD";
        $State[MA]                    = "Massachusetts MA";
        $State[MI]                    = "Michigan MI";
        $State[MN]                    = "Minnesota MN";
        $State[MS]                    = "Mississippi MS";
        $State[MO]                    = "Missouri MO";
        $State[MT]                    = "Montana MT";
        $State[NE]                    = "Nebraska NE";
        $State[NV]                    = "Nevada NV";
        $State[NH]                    = "New Hampshire NH";
        $State[NJ]                    = "New Jersey NJ";
        $State[NM]                    = "New Mexico NM";
        $State[NY]                    = "New York NY";
        $State[NC]                    = "North Carolina NC";
        $State[ND]                    = "North Dakota ND";
        $State[OH]                    = "Ohio OH";
        $State[OK]                    = "Oklahoma OK";
        $State['OR']                  = "Oregon OR";
        $State[PA]                    = "Pennsylvania PA";
        $State[PR]                    = "Puerto Rico PR";
        $State[RI]                    = "Rhode Island RI";
        $State[SC]                    = "South Carolina SC";
        $State[SD]                    = "South Dakota SD";
        $State[TN]                    = "Tennessee TN";
        $State[TX]                    = "Texas TX";
        $State[UT]                    = "Utah UT";
        $State[VT]                    = "Vermont VT";
        $State[VA]                    = "Virginia VA";
        $State[WA]                    = "Washington WA";
        $State[WV]                    = "West Virginia WV";
        $State[WI]                    = "Wisconsin WI";
        $State[WY]                    = "Wyoming WY";
        $State[AB]                    = "Alberta AB";
        $State[BC]                    = "British Columbia BC";
        $State[MB]                    = "Manitoba MB";
        $State[NB]                    = "New Brunswick NB";
        $State[NF]                    = "Newfoundland NF";
        $State[NT]                    = "Northwest Territories NT";
        $State[NS]                    = "Nova Scotia NS";
        $State[ON]                    = "Ontario ON";
        $State[PE]                    = "Prince Edward Island PE";
        $State[QC]                    = "Qu&eacute;bec QC";
        $State[SK]                    = "Saskatchewan SK";
        $State[YT]                    = "Yukon Territory YT";
        $State["Andhra Pradesh"]      = "Andhra Pradesh";
        $State[Assam]                 = "Assam";
        $State["Arunachal Pradesh"]   = "Arunachal Pradesh";
        $State["Andaman and Nicobar"] = "Andaman and Nicobar";
        $State[Bihar]                 = "Bihar";
        $State[Chandigarh]            = "Chandigarh";
        $State[Chattisgarh]           = "Chattisgarh";
        $State["Daman and Diu"]       = "Daman and Diu";
        $State[Delhi]                 = "Delhi";
        $State[Gujarat]               = "Gujarat";
        $State[Goa]                   = "Goa";
        $State[Haryana]               = "Haryana";
        $State["Himachal Pradesh"]    = "Himachal Pradesh";
        $State["Jammu and Kashmir"]   = "Jammu and Kashmir";
        $State[Jharkhand]             = "Jharkhand";
        $State[Karnataka]             = "Karnataka";
        $State[Kerala]                = "Kerala";
        $State[Lakhswadeep]           = "Lakhswadeep";
        $State["Madhya Pradesh"]      = "Madhya Pradesh";
        $State[Maharashtra]           = "Maharashtra";
        $State[Manipur]               = "Manipur";
        $State[Meghalaya]             = "Meghalaya";
        $State[Mizoram]               = "Mizoram";
        $State[Nagaland]              = "Nagaland";
        $State[Orissa]                = "Orissa";
        $State[Pondicherry]           = "Pondicherry";
        $State[Punjab]                = "Punjab";
        $State[Rajasthan]             = "Rajasthan";
        $State[Sikkim]                = "Sikkim";
        $State["Tamil Nadu"]          = "Tamil Nadu";
        $State["Uttar Pradesh"]       = "Uttar Pradesh";
        $State[Uttaranchal]           = "Uttaranchal";
        $State["West Benagal"]        = "West Benagal";
        $State[Sind]                  = "Sind";
        $State[Balochistan]           = "Balochistan";
        $State[Punjab]                = "Punjab";
        $State[NWFP]                  = "NWFP";
        
        return $State[$pStateCode];
    }
    
    # getCountryNameByCountryCode
    function getCountryNameByCountryCode($pCountryCode)
    {
        
        $Country       = array();
        $Country['AF'] = "Afghanistan";
        $Country['AL'] = "Albania";
        $Country['DZ'] = "Algeria";
        $Country['AS'] = "American Samoa";
        $Country['AD'] = "Andorra";
        $Country['AO'] = "Angola";
        $Country['AI'] = "Anguilla";
        $Country['AQ'] = "Antarctica";
        $Country['AG'] = "Antigua and Barbuda";
        $Country['AR'] = "Argentina";
        $Country['AM'] = "Armenia";
        $Country['AW'] = "Aruba";
        $Country['AU'] = "Australia";
        $Country['AT'] = "Austria";
        $Country['AZ'] = "Azerbaijan";
        $Country['BS'] = "Bahamas";
        $Country['BH'] = "Bahrain";
        $Country['BD'] = "Bangladesh";
        $Country['BB'] = "Barbados";
        $Country['BY'] = "Belarus";
        $Country['BE'] = "Belgium";
        $Country['BZ'] = "Belize";
        $Country['BJ'] = "Benin";
        $Country['BM'] = "Bermuda";
        $Country['BT'] = "Bhutan";
        $Country['BO'] = "Bolivia";
        $Country['BA'] = "Bosnia and Herzegovina";
        $Country['BW'] = "Botswana";
        $Country['BV'] = "Bouvet Island";
        $Country['BR'] = "Brazil";
        $Country['IO'] = "British Indian Ocean Territory";
        $Country['BN'] = "Brunei";
        $Country['BG'] = "Bulgaria";
        $Country['BF'] = "Burkina Faso";
        $Country['BI'] = "Burundi";
        $Country['KH'] = "Cambodia";
        $Country['CM'] = "Cameroon";
        $Country['CA'] = "Canada";
        $Country['CV'] = "Cape Verde";
        $Country['KY'] = "Cayman Islands";
        $Country['CF'] = "Central African Republic";
        $Country['TD'] = "Chad";
        $Country['CL'] = "Chile";
        $Country['CN'] = "China";
        $Country['CX'] = "Christmas Island";
        $Country['CC'] = "Cocos (Keeling) Islands";
        $Country['CO'] = "Colombia";
        $Country['KM'] = "Comoros";
        $Country['CG'] = "Congo";
        $Country['CK'] = "Cook Islands";
        $Country['CR'] = "Costa Rica";
        $Country['CI'] = "Cte d'Ivoire";
        $Country['HR'] = "Croatia (Hrvatska)";
        $Country['CU'] = "Cuba";
        $Country['CY'] = "Cyprus";
        $Country['CZ'] = "Czech Republic";
        $Country['CD'] = "Congo (DRC)";
        $Country['DK'] = "Denmark";
        $Country['DJ'] = "Djibouti";
        $Country['DM'] = "Dominica";
        $Country['DO'] = "Dominican Republic";
        $Country['TP'] = "East Timor";
        $Country['EC'] = "Ecuador";
        $Country['EG'] = "Egypt";
        $Country['SV'] = "El Salvador";
        $Country['GQ'] = "Equatorial Guinea";
        $Country['ER'] = "Eritrea";
        $Country['EE'] = "Estonia";
        $Country['ET'] = "Ethiopia";
        $Country['FK'] = "Falkland Islands (Islas Malvinas)";
        $Country['FO'] = "Faroe Islands";
        $Country['FJ'] = "Fiji Islands";
        $Country['FI'] = "Finland";
        $Country['FR'] = "France";
        $Country['GF'] = "French Guiana";
        $Country['PF'] = "French Polynesia";
        $Country['TF'] = "French Southern and Antarctic Lands";
        $Country['GA'] = "Gabon";
        $Country['GM'] = "Gambia";
        $Country['GE'] = "Georgia";
        $Country['DE'] = "Germany";
        $Country['GH'] = "Ghana";
        $Country['GI'] = "Gibraltar";
        $Country['GR'] = "Greece";
        $Country['GL'] = "Greenland";
        $Country['GD'] = "Grenada";
        $Country['GP'] = "Guadeloupe";
        $Country['GU'] = "Guam";
        $Country['GT'] = "Guatemala";
        $Country['GN'] = "Guinea";
        $Country['GW'] = "GuineaBissau";
        $Country['GY'] = "Guyana";
        $Country['HT'] = "Haiti";
        $Country['HM'] = "Heard Island and McDonald Islands";
        $Country['HN'] = "Honduras";
        $Country['HK'] = "Hong Kong SAR";
        $Country['HU'] = "Hungary";
        $Country['IS'] = "Iceland";
        $Country['IN'] = "India";
        $Country['ID'] = "Indonesia";
        $Country['IR'] = "Iran";
        $Country['IQ'] = "Iraq";
        $Country['IE'] = "Ireland";
        $Country['IL'] = "Israel";
        $Country['IT'] = "Italy";
        $Country['JM'] = "Jamaica";
        $Country['JP'] = "Japan";
        $Country['JO'] = "Jordan";
        $Country['KZ'] = "Kazakhstan";
        $Country['KE'] = "Kenya";
        $Country['KI'] = "Kiribati";
        $Country['KR'] = "Korea";
        $Country['KW'] = "Kuwait";
        $Country['KG'] = "Kyrgyzstan";
        $Country['LA'] = "Laos";
        $Country['LV'] = "Latvia";
        $Country['LB'] = "Lebanon";
        $Country['LS'] = "Lesotho";
        $Country['LR'] = "Liberia";
        $Country['LY'] = "Libya";
        $Country['LI'] = "Liechtenstein";
        $Country['LT'] = "Lithuania";
        $Country['LU'] = "Luxembourg";
        $Country['MO'] = "Macau SAR";
        $Country['MK'] = "Macedonia Former Yugoslav Republic of";
        $Country['MG'] = "Madagascar";
        $Country['MW'] = "Malawi";
        $Country['MY'] = "Malaysia";
        $Country['MV'] = "Maldives";
        $Country['ML'] = "Mali";
        $Country['MT'] = "Malta";
        $Country['MH'] = "Marshall Islands";
        $Country['MQ'] = "Martinique";
        $Country['MR'] = "Mauritania";
        $Country['MU'] = "Mauritius";
        $Country['YT'] = "Mayotte";
        $Country['MX'] = "Mexico";
        $Country['FM'] = "Micronesia";
        $Country['MD'] = "Moldova";
        $Country['MC'] = "Monaco";
        $Country['MN'] = "Mongolia";
        $Country['MS'] = "Montserrat";
        $Country['MA'] = "Morocco";
        $Country['MZ'] = "Mozambique";
        $Country['MM'] = "Myanmar";
        $Country['NA'] = "Namibia";
        $Country['NR'] = "Nauru";
        $Country['NP'] = "Nepal";
        $Country['NL'] = "Netherlands";
        $Country['AN'] = "Netherlands Antilles";
        $Country['NC'] = "New Caledonia";
        $Country['NZ'] = "New Zealand";
        $Country['NI'] = "Nicaragua";
        $Country['NE'] = "Niger";
        $Country['NG'] = "Nigeria";
        $Country['NU'] = "Niue";
        $Country['NF'] = "Norfolk Island";
        $Country['KP'] = "North Korea";
        $Country['MP'] = "Northern Mariana Islands";
        $Country['NO'] = "Norway";
        $Country['OM'] = "Oman";
        $Country['PK'] = "Pakistan";
        $Country['PW'] = "Palau";
        $Country['PL'] = "Palestine";
        $Country['PA'] = "Panama";
        $Country['PG'] = "Papua New Guinea";
        $Country['PY'] = "Paraguay";
        $Country['PE'] = "Peru";
        $Country['PH'] = "Philippines";
        $Country['PN'] = "Pitcairn Islands";
        $Country['PL'] = "Poland";
        $Country['PT'] = "Portugal";
        $Country['PR'] = "Puerto Rico";
        $Country['QA'] = "Qatar";
        $Country['RE'] = "Reunion";
        $Country['RO'] = "Romania";
        $Country['RU'] = "Russia";
        $Country['RW'] = "Rwanda";
        $Country['KN'] = "St. Kitts and Nevis";
        $Country['LC'] = "St. Lucia";
        $Country['VC'] = "St. Vincent and the Grenadines";
        $Country['WS'] = "Samoa";
        $Country['SM'] = "San Marino";
        $Country['ST'] = "Sao Tom and Prncipe";
        $Country['SA'] = "Saudi Arabia";
        $Country['SN'] = "Senegal";
        $Country['SC'] = "Seychelles";
        $Country['SL'] = "Sierra Leone";
        $Country['SG'] = "Singapore";
        $Country['SK'] = "Slovakia";
        $Country['SI'] = "Slovenia";
        $Country['SB'] = "Solomon Islands";
        $Country['SO'] = "Somalia";
        $Country['ZA'] = "South Africa";
        $Country['GS'] = "South Georgia and the South Sandwich Islands";
        $Country['ES'] = "Spain";
        $Country['LK'] = "Sri Lanka";
        $Country['SH'] = "St. Helena";
        $Country['PM'] = "St. Pierre and Miquelon";
        $Country['SD'] = "Sudan";
        $Country['SR'] = "Suriname";
        $Country['SJ'] = "Svalbard and Jan Mayen";
        $Country['SZ'] = "Swaziland";
        $Country['SE'] = "Sweden";
        $Country['CH'] = "Switzerland";
        $Country['SY'] = "Syria";
        $Country['TW'] = "Taiwan";
        $Country['TJ'] = "Tajikistan";
        $Country['TZ'] = "Tanzania";
        $Country['TH'] = "Thailand";
        $Country['TG'] = "Togo";
        $Country['TK'] = "Tokelau";
        $Country['TO'] = "Tonga";
        $Country['TT'] = "Trinidad and Tobago";
        $Country['TN'] = "Tunisia";
        $Country['TR'] = "Turkey";
        $Country['TM'] = "Turkmenistan";
        $Country['TC'] = "Turks and Caicos Islands";
        $Country['TV'] = "Tuvalu";
        $Country['UG'] = "Uganda";
        $Country['UA'] = "Ukraine";
        $Country['AE'] = "United Arab Emirates";
        $Country['UK'] = "United Kingdom";
        $Country['US'] = "United States";
        $Country['UM'] = "United States Minor Outlying Islands";
        $Country['UY'] = "Uruguay";
        $Country['UZ'] = "Uzbekistan";
        $Country['VU'] = "Vanuatu";
        $Country['VA'] = "Vatican City";
        $Country['VE'] = "Venezuela";
        $Country['VN'] = "Viet Nam";
        $Country['VG'] = "Virgin Islands (British)";
        $Country['VI'] = "Virgin Islands";
        $Country['WF'] = "Wallis and Futuna";
        $Country['YE'] = "Yemen";
        $Country['YU'] = "Yugoslavia";
        $Country['ZM'] = "Zambia";
        $Country['ZW'] = "Zimbabwe";
        $Country['USA'] = "USA";
        
        $strMyCountry = "";
        if ($Country{$pCountryCode} == "") {
            $strMyCountry = "N/A";
        } else {
            $strMyCountry = $Country[$pCountryCode];
        }
        return $strMyCountry;
    } #
    
    
    function getCountryCodeList($pInput)
    {

       $html = "
<option value=''> Select Country Code</option>
<option value='1'>1-USA/Canada</option>
<option value='7'>7-Kazakhstan</option>
<option value='7'>7-Russia</option>
<option value='20'>20-Egypt</option>
<option value='27'>27-South,Africa</option>
<option value='30'>30-Greece</option>
<option value='31'>31-Netherlands</option>
<option value='32'>32-Belgium</option>
<option value='33'>33-France</option>
<option value='34'>34-Spain</option>
<option value='36'>36-Hungary</option>
<option value='39'>39-Italy</option>
<option value='40'>40-Romania</option>
<option value='41'>41-Switzerland</option>
<option value='43'>43-Austria</option>
<option value='44'>44-United,Kingdom</option>
<option value='45'>45-Denmark</option>
<option value='46'>46-Sweden</option>
<option value='47'>47-Norway</option>
<option value='48'>48-Poland</option>
<option value='49'>49-Germany</option>
<option value='51'>51-Peru</option>
<option value='52'>52-Mexico</option>
<option value='53'>53-Cuba</option>
<option value='54'>54-Argentina</option>
<option value='55'>55-Brazil</option>
<option value='56'>56-Chile</option>
<option value='57'>57-Colombia</option>
<option value='58'>58-Venezuela</option>
<option value='60'>60-Malaysia</option>
<option value='61'>61-Australia</option>
<option value='62'>62-Indonesia</option>
<option value='63'>63-Philippines</option>
<option value='64'>64-New,Zealand</option>
<option value='65'>65-Singapore</option>
<option value='66'>66-Thailand</option>
<option value='81'>81-Japan</option>
<option value='82'>82-South,Korea</option>
<option value='84'>84-Vietnam</option>
<option value='86'>86-China</option>
<option value='90'>90-Turkey</option>
<option value='91'>91-India</option>
<option value='92'>92-Pakistan</option>
<option value='93'>93-Afghanistan</option>
<option value='94'>94-Sri,Lanka</option>
<option value='95'>95-Myanmar</option>
<option value='98'>98-Iran</option>
<option value='212'>212-Morocco</option>
<option value='213'>213-Algeria</option>
<option value='216'>216-Tunisia</option>
<option value='218'>218-Libya</option>
<option value='220'>220-Gambia</option>
<option value='221'>221-Senegal</option>
<option value='222'>222-Mauritania</option>
<option value='223'>223-Mali</option>
<option value='224'>224-Guinea</option>
<option value='225'>225-Cote,D'Ivoire</option>
<option value='226'>226-Burkina,Faso</option>
<option value='227'>227-Niger</option>
<option value='228'>228-Togo</option>
<option value='229'>229-Benin</option>
<option value='230'>230-Mauritius</option>
<option value='231'>231-Liberia</option>
<option value='232'>232-Sierra,Leone</option>
<option value='233'>233-Ghana</option>
<option value='234'>234-Nigeria</option>
<option value='235'>235-Chad</option>
<option value='236'>236-Central,African</option>
<option value='237'>237-Cameroon</option>
<option value='238'>238-Cape,Verde</option>
<option value='239'>239-Sao,Tome,E
<option value='240'>240-Equatorial,Guinea</option>
<option value='241'>241-Gabon</option>
<option value='242'>242-Congo</option>
<option value='244'>244-Angola</option>
<option value='245'>245-Guinea,Bissau</option>
<option value='248'>248-Seychelles</option>
<option value='249'>249-Sudan</option>
<option value='250'>250-Rwanda</option>
<option value='251'>251-Ethiopia</option>
<option value='252'>252-Somalia</option>
<option value='253'>253-Djibouti</option>
<option value='254'>254-Kenya</option>
<option value='255'>255-Tanzania</option>
<option value='256'>256-Uganda</option>
<option value='257'>257-Burundi</option>
<option value='258'>258-Mozambique</option>
<option value='260'>260-Zambia</option>
<option value='261'>261-Madagascar</option>
<option value='261'>262-Reunion Island</option>
<option value='263'>263-Zimbabwe</option>
<option value='264'>264-Namibia</option>
<option value='265'>265-Malawi</option>
<option value='266'>266-Lesotho</option>
<option value='267'>267-Botswana</option>
<option value='268'>268-Swaziland</option>
<option value='291'>291-Eritrea</option>
<option value='297'>297-Aruba</option>
<option value='298'>298-Faroe,Islands</option>
<option value='299'>299-Greenland</option>
<option value='350'>350-Gibraltar</option>
<option value='351'>351-Portugal</option>
<option value='352'>352-Luxembourg</option>
<option value='353'>353-Ireland</option>
<option value='354'>354-Iceland</option>
<option value='355'>355-Albania</option>
<option value='356'>356-Malta</option>
<option value='357'>357-Cyprus</option>
<option value='358'>358-Finland</option>
<option value='359'>359-Bulgaria</option>
<option value='370'>370-Lithuania</option>
<option value='371'>371-Latvia</option>
<option value='372'>372-Estonia</option>
<option value='373'>373-Moldova</option>
<option value='374'>374-Armenia</option>
<option value='375'>375-Belarus</option>
<option value='376'>376-Andorra</option>
<option value='378'>378-San,Marino</option>
<option value='380'>380-Ukraine</option>
<option value='381'>381-Yugoslavia</option>
<option value='385'>385-Croatia</option>
<option value='386'>386-Slovenia</option>
<option value='387'>387-Bosnia</option>
<option value='389'>389-Macedonia</option>
<option value='420'>420-Czech,Republic</option>
<option value='421'>421-Slovakia</option>
<option value='423'>423-Liechtenstein</option>
<option value='501'>501-Belize</option>
<option value='502'>502-Guatemala</option>
<option value='503'>503-El,Salvador</option>,
<option value='504'>504-Honduras</option>,,
<option value='505'>505-Nicaragua</option>,,
<option value='506'>506-Costa,Rica</option></option>
<option value='507'>507-Panama</option>,,
<option value='509'>509-Haiti</option>,,
<option value='590'>590-Guadeloupe</option>,,
<option value='591'>591-Bolivia</option>,,
<option value='592'>592-Guyana</option>,,
<option value='593'>593-Ecuador</option>,,
<option value='594'>594-French,Guyana</option>,
<option value='595'>595-Paraguay</option>,,
<option value='596'>596-Martinique</option>,,
<option value='597'>597-Surinam</option>,,
<option value='598'>598-Uruguay</option>,Principe</option>
<option value='599'>599-Netherlands,Antilles</option>,
<option value='662'>662-Mississippi</option>
<option value='673'>673-Brunei</option>
<option value='675'>675-Papua,New,Guinea
<option value='676'>676-Tonga</option>
<option value='677'>677-Solomon,Islands</option>
<option value='678'>678-Vanuatu</option>
<option value='679'>679-Fiji</option>
<option value='682'>682-Cook,Islands</option>
<option value='684'>684-American,Samoa</option>
<option value='685'>685-Western,Samoa</option>
<option value='687'>687-New,Caledonia</option>
<option value='850'>850-North,Korea</option>
<option value='852'>852-Hong,Kong</option>
<option value='853'>853-Macao</option>
<option value='855'>855-Cambodia</option>
<option value='856'>856-Laos</option>
<option value='880'>880-Bangladesh</option>
<option value='886'>886-Taiwan</option>
<option value='960'>960-Maldives</option>
<option value='961'>961-Lebanon</option>
<option value='962'>962-Jordan</option>
<option value='963'>963-Syria</option>
<option value='964'>964-Iraq</option>
<option value='965'>965-Kuwait</option>
<option value='966'>966-Saudi,Arabia</option>
<option value='967'>967-Yemen</option>
<option value='968'>968-Oman</option>
<option value='970'>970-Palestine</option>
<option value='971'>971-UAE</option>
<option value='972'>972-Israel</option>
<option value='973'>973-Bahrain</option>
<option value='974'>974-Qatar</option>
<option value='976'>976-Mongolia</option>
<option value='977'>977-Nepal</option>
<option value='992'>992-Tajikistan</option>
<option value='993'>993-Turkmenistan</option>
<option value='994'>994-Azerbaijan</option>
<option value='995'>995-Georgia</option>
<option value='996'>996-Kyrgyzstan</option>
<option value='998'>998-Uzbekistan</option>

";
        $html = str_replace("value='$pInput'", "value='$pInput' selected ", $html);
       
        return $html;
    }
    
    function getCCTypeList($pCCType)
    {
        
        $html = "<option value=''>Choose</option>";
        $html .= "<option value='VISA'>Visa</option>";
        $html .= "<option value='MAST'>Master</option>";
        $html .= "<option value='AMEX'>American Express</option>";
        $html .= "<option value='DISC'>Discovery</option>";
        $Pat  = "'$pCCType'>";
        $Rep  = "'$pCCType' selected>";
        $html = str_replace("'$Pat'", "'$Rep' selected ", $html);
        return $html;
    }
    
    function getDateList($pnYear, $pnMonth, $pnDay)
    {
        
        if (strlen($pnDay) == 1) {
            $pnDay = "0$pnDay";
        }
        
        
        $dayhtml = "<select name='Day' id='Day' class='TextBox'>";
        $dayhtml .= "<option value='01'>01</option>";
        $dayhtml .= "<option value='02'>02</option>";
        $dayhtml .= "<option value='03'>03</option>";
        $dayhtml .= "<option value='04'>04</option>";
        $dayhtml .= "<option value='05'>05</option>";
        $dayhtml .= "<option value='06'>06</option>";
        $dayhtml .= "<option value='07'>07</option>";
        $dayhtml .= "<option value='08'>08</option>";
        $dayhtml .= "<option value='09'>09</option>";
        $dayhtml .= "<option value='10'>10</option>";
        $dayhtml .= "<option value='11'>11</option>";
        $dayhtml .= "<option value='12'>12</option>";
        $dayhtml .= "<option value='13'>13</option>";
        $dayhtml .= "<option value='14'>14</option>";
        $dayhtml .= "<option value='15'>15</option>";
        $dayhtml .= "<option value='16'>16</option>";
        $dayhtml .= "<option value='17'>17</option>";
        $dayhtml .= "<option value='18'>18</option>";
        $dayhtml .= "<option value='19'>19</option>";
        $dayhtml .= "<option value='20'>20</option>";
        $dayhtml .= "<option value='21'>21</option>";
        $dayhtml .= "<option value='22'>22</option>";
        $dayhtml .= "<option value='23'>23</option>";
        $dayhtml .= "<option value='24'>24</option>";
        $dayhtml .= "<option value='25'>25</option>";
        $dayhtml .= "<option value='26'>26</option>";
        $dayhtml .= "<option value='27'>27</option>";
        $dayhtml .= "<option value='28'>28</option>";
        $dayhtml .= "<option value='29'>29</option>";
        $dayhtml .= "<option value='30'>30</option>";
        $dayhtml .= "<option value='31'>31</option>";
        $dayhtml .= "</select>";
        $dayhtml = str_replace("'$pnDay'", "'$pnDay' selected ", $dayhtml);
        
        $monthhtml = "<select name='Month' id='Month' class='TextBox'>";
        $monthhtml .= "<option value='01'>January-01</option>";
        $monthhtml .= "<option value='02'>February-02</option>";
        $monthhtml .= "<option value='03'>March-03</option>";
        $monthhtml .= "<option value='04'>April-04</option>";
        $monthhtml .= "<option value='05'>May-05</option>";
        $monthhtml .= "<option value='06'>June-06</option>";
        $monthhtml .= "<option value='07'>July-07</option>";
        $monthhtml .= "<option value='08'>August-08</option>";
        $monthhtml .= "<option value='09'>September-09</option>";
        $monthhtml .= "<option value='10'>October-10</option>";
        $monthhtml .= "<option value='11'>November-11</option>";
        $monthhtml .= "<option value='12'>December-12</option>";
        $monthhtml .= "</select>";
        $monthhtml = str_replace("'$pnMonth'", "'$pnMonth' selected ", $monthhtml);
        
        $yearhtml = "<select name='Year' id='select2' class='TextBox'>";
        for ($temp = 2000; $temp <= date("Y"); $temp++) {
            $yearhtml .= "<option value='$temp'>$temp</option>";
        }
        
        $yearhtml .= "</select>";
        $yearhtml = str_replace("'$pnYear'", "'$pnYear' selected ", $yearhtml);
        
        return "$dayhtml-$monthhtml-$yearhtml";
    }
    
    
    function getCCLastFirst4($pCCNumber)
    {
        
        $Hash = array();
        
        $Hash[First] = substr($pCCNumber, 0, 4);
        
        $Hash[Last] = substr($pCCNumber, strlen($pCCNumber) - 4, 4);
        
        return $Hash;
    }
    
    function getStateList($pStateCode)
    {
        
        $StateList;
        $StateList = "<option value='0'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Select A State</font></option>\n";
        $StateList .= "<option value='Non-US/Other'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Non-US/Other</font></option>\n";
        $StateList .= "<option value='AL'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Alabama AL</font></option>\n";
        $StateList .= "<option value='AK'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Alaska AK</font></option>\n";
        $StateList .= "<option value='AZ'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Arizona AZ</font></option>\n";
        $StateList .= "<option value='AR'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Arkansas AR</font></option>\n";
        $StateList .= "<option value='CA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>California CA</font></option>\n";
        $StateList .= "<option value='CO'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Colorado CO</font></option>\n";
        $StateList .= "<option value='CT'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Connecticut CT</font></option>\n";
        $StateList .= "<option value='DC'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Washington D.C. DC</font></option>\n";
        $StateList .= "<option value='DE'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Delaware DE</font></option>\n";
        $StateList .= "<option value='FL'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Florida FL</font></option>\n";
        $StateList .= "<option value='GA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Georgia GA</fon/option>\n";
        $StateList .= "<option value='IL'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Illinois IL</font></option>\n";
        $StateList .= "<option value='IN'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Indiana IN</font></option>\n";
        $StateList .= "<option value='IA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Iowa IA</font></option>\n";
        $StateList .= "<option value='KS'><font face='Verdanat></option>\n";
        $StateList .= "<option value='HI'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Hawaii HI</font></option>\n";
        $StateList .= "<option value='ID'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Idaho ID</font><, Arial, Helvetica, sans-serif' size='-2'>Kansas KS</font></option>\n";
        $StateList .= "<option value='KY'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Kentucky KY</font></option>\n";
        $StateList .= "<option value='LA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Louisiana LA</font></option>\n";
        $StateList .= "<option value='ME'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Maine ME</font></option>\n";
        $StateList .= "<option value='MD'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Maryland MD</font></option>\n";
        $StateList .= "<option value='MA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Massachusetts MA</font></option>\n";
        $StateList .= "<option value='MI'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Michigan MI</font></option>\n";
        $StateList .= "<option value='MN'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Minnesota MN</font></option>\n";
        $StateList .= "<option value='MS'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Mississippi MS</font></option>\n";
        $StateList .= "<option value='MO'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Missouri MO</font></option>\n";
        $StateList .= "<option value='MT'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Montana MT</font></option>\n";
        $StateList .= "<option value='NE'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Nebraska NE</font></option>\n";
        $StateList .= "<option value='NV'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Nevada NV</font></option>\n";
        $StateList .= "<option value='NH'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>New Hampshire NH</font></option>\n";
        $StateList .= "<option value='NJ'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>New Jersey NJ</font></option>\n";
        $StateList .= "<option value='NM'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>New Mexico NM</font></option>\n";
        $StateList .= "<option value='NY'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>New York NY</font></option>\n";
        $StateList .= "<option value='NC'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>North Carolina NC</font></option>\n";
        $StateList .= "<option value='ND'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>North Dakota ND</font></option>\n";
        $StateList .= "<option value='OH'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Ohio OH</font></option>\n";
        $StateList .= "<option value='OK'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Oklahoma OK</font></option>\n";
        $StateList .= "<option value='OR'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Oregon OR</font></option>\n";
        $StateList .= "<option value='PA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Pennsylvania PA</font></option>\n";
        $StateList .= "<option value='PR'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Puerto Rico PR</font></option>\n";
        $StateList .= "<option value='RI'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Rhode Island RI</font></option>\n";
        $StateList .= "<option value='SC'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>South Carolina SC</font></option>\n";
        $StateList .= "<option value='SD'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>South Dakota SD</font></option>\n";
        $StateList .= "<option value='TN'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Tennessee TN</font></option>\n";
        $StateList .= "<option value='TX'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Texas TX</font></option>\n";
        $StateList .= "<option value='UT'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Utah UT</font></option>\n";
        $StateList .= "<option value='VT'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Vermont VT</font></option>\n";
        $StateList .= "<option value='VA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Virginia VA</font></option>\n";
        $StateList .= "<option value='WA'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Washington WA</font></option>\n";
        $StateList .= "<option value='WV'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>West Virginia WV</font></option>\n";
        $StateList .= "<option value='WI'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Wisconsin WI</font></option>\n";
        $StateList .= "<option value='WY'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Wyoming WY</font></option>\n";
        $StateList .= "<option value='AB'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Alberta AB</font></option>\n";
        $StateList .= "<option value='BC'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>British Columbia BC</font></option>\n";
        $StateList .= "<option value='MB'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Manitoba MB</font></option>\n";
        $StateList .= "<option value='NB'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>New Brunswick NB</font></option>\n";
        $StateList .= "<option value='NF'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Newfoundland NF</font></option>\n";
        $StateList .= "\n<option value='NT'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Northwest Territories NT</font></option>";
        $StateList .= "\n<option value='NS'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Nova Scotia NS</font></option>";
        $StateList .= "\n<option value='ON'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Ontario ON</font></option>";
        $StateList .= "\n<option value='PE'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Prince Edward Island PE</font></option>";
        $StateList .= "\n<option value='QC'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Qu&eacute;bec QC</font></option>";
        $StateList .= "\n<option value='SK'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Saskatchewan SK</font></option>";
        $StateList .= "\n<option value='YT'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>Yukon Territory YT</font></option>\n";
        $StateList .= "\n<option value ='Andhra Pradesh'>Andhra Pradesh </option>";
        $StateList .= "\n<option value ='Assam'>Assam </option>";
        $StateList .= "\n<option value ='Arunachal Pradesh'>Arunachal Pradesh </option>";
        $StateList .= "\n<option value ='Andaman and Nicobar'>Andaman and Nicobar </option>";
        $StateList .= "\n<option value ='Bihar'>Bihar </option>";
        $StateList .= "\n<option value ='Chandigarh'>Chandigarh </option>";
        $StateList .= "\n<option value ='Chattisgarh'>Chattisgarh </option>";
        $StateList .= "\n<option value ='Daman and Diu'>Daman and Diu </option>";
        $StateList .= "\n<option value ='Delhi'>Delhi </option>";
        $StateList .= "\n<option value ='Gujarat'>Gujarat </option>";
        $StateList .= "\n<option value ='Goa'>Goa </option>";
        $StateList .= "\n<option value ='Haryana'>Haryana </option>";
        $StateList .= "\n<option value ='Himachal Pradesh'>Himachal Pradesh </option>";
        $StateList .= "\n<option value ='Jammu and Kashmir'>Jammu and Kashmir </option>";
        $StateList .= "\n<option value ='Jharkhand'>Jharkhand </option>";
        $StateList .= "\n<option value ='Karnataka'>Karnataka </option>";
        $StateList .= "\n<option value ='Kerala'>Kerala </option>";
        $StateList .= "\n<option value ='Lakhswadeep'>Lakhswadeep </option>";
        $StateList .= "\n<option value ='Madhya Pradesh'>Madhya Pradesh </option>";
        $StateList .= "\n<option value ='Maharashtra'>Maharashtra </option>";
        $StateList .= "\n<option value ='Manipur'>Manipur </option>";
        $StateList .= "\n<option value ='Meghalaya'>Meghalaya </option>";
        $StateList .= "\n<option value ='Mizoram'>Mizoram </option>";
        $StateList .= "\n<option value ='Nagaland'>Nagaland </option>";
        $StateList .= "\n<option value ='Orissa'>Orissa </option>";
        $StateList .= "\n<option value ='Pondicherry'>Pondicherry </option>";
        $StateList .= "\n<option value ='Punjab'>Punjab </option>";
        $StateList .= "\n<option value ='Rajasthan'>Rajasthan </option>";
        $StateList .= "\n<option value ='Sikkim'>Sikkim </option>";
        $StateList .= "\n<option value ='Tamil Nadu'>Tamil Nadu </option>";
        $StateList .= "\n<option value ='Uttar Pradesh'>Uttar Pradesh </option>";
        $StateList .= "\n<option value ='Uttaranchal'>Uttaranchal </option>";
        $StateList .= "\n<option value ='West Benagal'>West Benagal </option>";
        $StateList .= "\n<option value ='Sind'>Sind</option>";
        $StateList .= "\n<option value ='Balochistan'>Balochistan</option>";
        $StateList .= "\n<option value ='Punjab'>Punjab</option>";
        $StateList .= "\n<option value ='NWFP'>NWFP</option>";
        
        
        
        $Pat = "'$pStateCode'>";
        $Rep = "'$pStateCode' selected>";
        #$StateList    =~ s/$Pat/$Rep/;
        
        $StateList = str_replace($Pat, $Rep, $StateList);
        
        
        return $StateList;
    }
    
    function getDefaultRingto($pUID, $pDID)
    {
        
        
        $strSQL = " select DefaultRingTo,DefaultRingToType from EmailPref where uid=\"$pUID\"  ";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $Hash;
        
        $TempRingTo = $Result->fields[0];
        
        $TempRingTo = str_replace("DID", $pDID, $TempRingTo); #$TempRingTo =~ s/DID/$pDID/gs;
        
        
        
        $Hash['RingTo']     = $TempRingTo;
        $Hash['RingToType'] = $Result->fields[1];
        return $Hash;
    }
    

    function GetMonthList($pMonth) {
    
    
    $MonthHTML  = "
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
              
              
             ";
$MonthHTML  = str_replace("'$pMonth'","'$pMonth' selected ",$MonthHTML);

return $MonthHTML;
    
}
    
    function GetMonthNames($pMonthName)
    {
        if ($pMonthName == 01) {
            return "January";
        } elseif ($pMonthName == 02) {
            return 'Feburary';
        } elseif ($pMonthName == 03) {
            return 'March';
        } elseif ($pMonthName == 04) {
            return 'April';
        } elseif ($pMonthName == 05) {
            return 'May';
        }
        if ($pMonthName == 06) {
            return 'June';
        } elseif ($pMonthName == 07) {
            return 'July';
        }
        // elseif($pMonthName==08)
            
        // {
            
        //     return 'August';
            
        // }
            
        // elseif($pMonthName==09)
            
        // {
            
        //     return 'September';
            
        // }
            elseif ($pMonthName == 10) {
            return 'October';
        } elseif ($pMonthName == 11) {
            return 'November';
        } elseif ($pMonthName == 12) {
            return 'December';
        }
    }
    
    function getCCExpiryMonthList($pMonth)
    {
        // echo $pMonth;
        
        $html = "
    <option value='01'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>January-01</font></option>";
        $html .= "<option value='02'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>February-02</font></option>";
        $html .= "<option value='03'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>March-03</font></option>";
        $html .= "<option value='04'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>April-04</font></option>";
        $html .= "<option value='05'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>May-05</font></option>";
        $html .= "<option value='06'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>June-06</font></option>";
        $html .= "<option value='07'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>July-07</font></option>";
        $html .= "<option value='08'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>August-08</font></option>";
        $html .= "<option value='09'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>September-09</font></option>";
        $html .= "<option value='10'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>October-10</font></option>";
        $html .= "<option value='11'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>November-11</font></option>";
        $html .= "<option value='12'><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>December-12</font></option>";
        if ($pMonth != '') {
            //        $Pat        = "'$pMonth'<";
            //        $Rep        = "'$pMonth' selected>";
            //        $html    = str_replace($Pat,$Rep,$html);
            //    }
            $html = str_replace("'$pMonth'", "'$pMonth' selected ", $html);
        }
        
        return $html;
    }
    
    function getCCExpiryYearList($pYear)
    {
        //echo $pYear;
        $html   = '';
        $strSQL = "select year(curdate())";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        $StartYear = $Result->fields[0];
        if ($pYear < $StartYear) {
            $StartYear = $pYear;
        }
        
        
        for ($nIndex = $StartYear; $nIndex <= 2024; $nIndex++) {
            // echo $nIndex; 
            if ($pYear == $nIndex) {
                
                $selected = " selected ";
                //echo "hello".$pYear;
            } else {
                
                //echo "hi";
                $selected = "  ";
                
            }
            
            $html .= "<option value='$nIndex' $selected><font face='Verdana, Arial, Helvetica, sans-serif' size='-2'>$nIndex</font></option>";
            
        }
        return $html;
    }
    function getSimpleYearList($pYear)
    {
        $html = '';
        for ($nIndex = 2003; $nIndex <= date("Y"); $nIndex++) {
            if ($pYear == $nIndex) {
                $selected = " selected ";
            } else {
                $selected = "";
            }
            $html .= "<option value='$nIndex' $selected>$nIndex</option>";
        }
        
        return $html;
        
    }
    
    function giveAffliationPayment($pOID, $Charges, $TID)
    {
        $STRSQL    = "SELECT AffiliatedCode FROM orders WHERE OID='$pOID'";
        $RESULT    = $this->ADb->ExecuteQuery($STRSQL);
        //echo $STRSQL;
        $Affiliate = $RESULT->fields[0];
        if ($Affiliate == "") {
            return 0;
        }
        $strorderid    = "SELECT OID FROM orders WHERE AffliationCode='$Affiliate'";
        $Resultorderid = $this->ADb->ExecuteQuery($strorderid);
        if ($Resultorderid->fields[0] == "") {
            return 0;
        }
        $Amount = ($Charges * 2) / 100;
        $strSQL = "INSERT INTO `didx`.`ReqAffiliatePayment`
            (`OID`,
             `Amount`,
             `AffiliationID`,
             `TransactionID`)
VALUES ('" . $Resultorderid->fields[0] . "',
        '$Amount',
        '$pOID',
        '$TID')";
        $this->ADb->ExecuteQuery($strSQL);
        mail("hb@supertec.com", "Query", "$strSQL");
        
        
    }
    
    function getAffliationUID($pOID)
    {
        $STRSQL        = "SELECT AffiliatedCode FROM orders WHERE OID='$pOID'";
        $RESULT        = $this->ADb->ExecuteQuery($STRSQL);
        $Affiliate     = $RESULT->fields[0];
        $strorderid    = "SELECT OID FROM orders WHERE AffliationCode='$Affiliate'";
        $Resultorderid = $this->ADb->ExecuteQuery($strorderid);
        if ($Resultorderid->EOF) {
            return -1;
        } else {
            return $Resultorderid->fields[0];
        }
    }
    
    function UpdateSavedBalance($pOID)
    {
        $Balance   = $this->myTransaction->getCurrentBalanceInfobyUIDFinal($pOID);
        $strSQL    = "select count(DIDNumber) from DIDS where BOID=\"$pOID\" and status=2 ";
        $Result    = $this->ADb->ExecuteQuery($strSQL);
        $Purchased = $Result->fields[0];
        
        $strSQL = "update HomeDash set Balance=\"$Balance\",Purchased=\"$Purchased\" where OID=\"$pOID\"  ";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
    }
    
    function RemoveFromMinutesInfo($pDID)
    {
        $strSQL = "delete from MinutesInfo where DID=\"$pDID\" ";
        $Result = $this->ADb->ExecuteQuery($strSQL);
        return 1;
    }
    function check_input($value)
    {
        if ($value == ""){
            return $value;
        }
        // Stripslashes
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        // Quote if not a number
        if (!is_numeric($value)) {
            $value = "'" . mysql_real_escape_string($value) . "'";
        }
        return $value;
    }
    
    
    function GetAlternateRingTo($pDID, $pBOID, $pID)
    {
        
        global $myADb;
        
        $strSQL = "select CondType,RingTo,Flag from AlterRingTo where DID=\"$pDID\" and OID=\"$pBOID\"  ";
        $Result = $myADb->ExecuteQuery($strSQL);
        
        while (!$Result->EOF) {
            
            $Type   = $Result->fields[0];
            $RingTo = $Result->fields[1];
            $Flag   = $Result->fields[2];
            
            if ($Flag == 1) {
                $Flagval = "SIP";
            }
            if ($Flag == 2) {
                $Flagval = "IAX";
            }
            if ($Flag == 3) {
                $Flagval = "H323";
            }
            if ($Type == 0) {
                $WhenCall = "NOT ANSWERED";
            }
            
            if ($Type == 1) {
                $WhenCall = "BUSY";
            }
            
            if ($Type == 2) {
                $WhenCall = "CONGESTED";
            }
            
            if ($Type == 3) {
                $WhenCall = "CANCELLED";
            }
            if ($Type == 4) {
                $WhenCall = "CHANUNAVAIL";
            }
            
            // $html .= "Alternate RingTo on $WhenCall, $Flagval address <a href=\"EditSIPAlter.php?DealsID=$pID\">$RingTo</a><br>";
            // $Sno++;
            $Result->MoveNext();
        }
        
        
        #$CellAlter = "<tr><td bgcolor=#F0F0F0 height=\"25\" colspan=\"2\" valign=\"middle\">$html</td></tr>";
        if ($Sno <= 0)
            $html = "<a href=\"/EditSIPAlter?DealsID=$pID\">Settings</a><br>";
        
        return $html;
    }
    
    
    
    public static function getDefinedCountryList($Code = '')
    {
        
        
        $myADb = new ADb();
        $UID   = currentUser();
        $html="";
        $strSQL = "select DIDCountries.CountryCode,DIDCountries.Description,DIDCountries.ID 
               from DIDS,DIDCountries,DIDArea where DIDArea.ID=DIDS.AreaID and 
               DIDArea.CountryID = DIDCountries.ID and DIDS.CheckStatus=1  and DIDS.Status=0
               and DIDS.vendorrating>=1 and DIDS.vendorrating<=9
               group by DIDCountries.ID order by DIDCountries.CountryCode ";
        
        $Result = $myADb->ExecuteQuery($strSQL);
        $html   .= "<option value=\"\">-Select-</option>";
        while (!$Result->EOF) {
            $Countryname = $Result->fields[1];
            $CountryCode = $Result->fields[0] . " - $Countryname";
            $CountryID   = $Result->fields[2];
            // $res[$CountryCode] = $CountryCode;
            
            $selected = "";
            if ($Result->fields[0] == $Code)
                $selected = " selected ='selected'";
            else
                $selected = "";
            
            $html .= "<option value=" . $CountryID . "$selected>" . $CountryCode . "</option>\n";
            // $res[$CountryID] = $CountryCode;
            $Result->MoveNext();
        }
        
        
        return $html;
    }
    
    
    function getAreaCodeBackOrder($AreaID)
    {
        $myADb  = new ADb();
        $UID    = currentUser();
        $strSQL = "select DIDArea from  BackOrderAdmin where ID='$AreaID'";
        $Result = $myADb->ExecuteQuery($strSQL);
        $ffArea = $Result->fields[0];
        return $ffArea;
    }
    
    function getCountryCodeBackOrder($CountryID)
    {
        $myADb       = new ADb();
        $UID         = currentUser();
        $strSQL      = "select CountryCode from  BackOrderAdmin where ID='$CountryID'";
        $Result      = $myADb->ExecuteQuery($strSQL);
        $ffCountries = $Result->fields[0];
        return $ffCountries;
    }
    
    
    function getAreaListsBack($AreaID,$CountryID)
    {
        $myADb  = new ADb();
        
        //test
    $strSQL = "select CountryCode,CountryName from BackOrderAdmin where ID='$CountryID'";
#   echo $strSQL;
    $Result= $myADb->ExecuteQuery($strSQL);
    
    
    
    $pCountry = $Result->fields[0];
    $CountryName = $Result->fields[1];
    
    
    if($pCountry != '') {
        
        $WhereClause = " where CountryCode = '$pCountry' and CountryName='$CountryName' ";
        
    }else{
        
        $WhereClause = "  ";
    }

    $strSQL = " select DIDAREA,AreaName,ID from BackOrderAdmin USE INDEX (DIDArea) $WhereClause group by DIDArea order by DIDArea ";    

    $Result= $myADb->ExecuteQuery($strSQL);
    
    
        while(!$Result->EOF){
            
                $AreaCode = $Result->fields[0];
                $AreaName = $Result->fields[1];
                $BackOrderID = $Result->fields[2];
            
            $AreaDesc = $Result->fields[0]." - ". $AreaName;
            
            $Selected;


            if($BackOrderID == $AreaID) {

                    $Selected = " selected ";
                          
            }else{
                $Selected = " ";

            }
            
            
            $html .= "<option value=\"$BackOrderID\" $Selected >$AreaDesc</option>";

            $Result->MoveNext();

        }
         
        return $html;


        //test
       
    }

    function getAreaListsBackNew($CountryID,$AreaID)
    {
        $myADb  = new ADb();
        $html  = "";
        
        //test
    $strSQL = "select CountryCode,CountryName from BackOrderAdmin where ID='$CountryID'";
#   echo $strSQL;
    $Result= $myADb->ExecuteQuery($strSQL);
    
    
    
    $pCountry = $Result->fields[0];
    $CountryName = $Result->fields[1];
    
    $WhereClause = "";

    if($pCountry != '' && $CountryName != '') {
        
        $WhereClause = " where CountryCode = '$pCountry' and CountryName='$CountryName' ";

        $strSQL = " select DIDAREA,AreaName,ID from BackOrderAdmin USE INDEX (DIDArea) $WhereClause group by DIDArea order by DIDArea ";    

        $Result= $myADb->ExecuteQuery($strSQL);

        while(!$Result->EOF){
            
                $AreaCode = $Result->fields[0];
                $AreaName = $Result->fields[1];
                $BackOrderID = $Result->fields[2];
            
            $AreaDesc = $Result->fields[0]." - ". $AreaName;
            
            $Selected;


            if($BackOrderID == $AreaID) {

                    $Selected = " selected ";
                          
            }else{
                $Selected = " ";

            }
            
            
            $html .= "<option value=\"$BackOrderID\" $Selected >$AreaDesc</option>";

            $Result->MoveNext();

        }
        
    }else{
        
        // $WhereClause = "  ";
        $html = "";
    }
    

         
        return $html;


        //test
       
    }


   //test 
    public static function getDefinedCountryListBack($ID)
    {
        
        
        $myADb = new ADb();
        $UID   = currentUser();
        $html="";
        //
            $WhereClause;
    if($ID != '') {
        
        $WhereClause = "  ";
        
        
        
    }else{
        
        $WhereClause = "";
        
        
    }
    $strSQL = " select CountryCode,CountryName,ID from BackOrderAdmin $WhereClause group by countryname order by CountryCode";  

    
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $html .= "<option value=\"\">-Select-</option>";
    
        while(!$Result->EOF){
            
            $CountryCode = $Result->fields[0];
            $CountryName = $Result->fields[1];
        if($CountryName!='')
        {
            $BackOrderID = $Result->fields[2];
            $CountryArea = $Result->fields[0] ."-". $CountryName;
        
            $Selected;
            
            if($BackOrderID == $ID) {
                    $Selected = " selected ";       
            }else{
                $Selected = " ";
            }
            
            $html .= "<option value=\"$BackOrderID\" $Selected >$CountryArea</option>";
        }       
            $Result->MoveNext();
        }
        
        return $html;

        
    }
    
// Warisha
    function GetCountries($pc) {
    
        $myADb=new ADb();
        
        $strSQL = "select Description from DIDCountries order by Description ";
        $Result = $myADb->ExecuteQuery($strSQL);
        $html="";
        while(!$Result->EOF){
            
            $Country = $Result->fields[0];
            

            if(strtolower($pc)==strtolower($Country))       
                    $html .= "<OPTION value='$Country' selected >$Country</Option>";
            else
                    $html .= "<OPTION value='$Country' >$Country</Option>";
            
            $Result->MoveNext();
        }
        
        return $html;
    
   }



//Warisha

  //test  
    function CusDocs($ID)
    {
        $UID    = currentUser();
        $myADb  = new ADb();
        $strSQL = "select Doc,Type,CustomerName,Address,Contact,Email,DocNumber,TextDetail,
     LastIP,date_format(usagefrom,'%d-%b-%Y') as Date,date_format(usageto,'%d-%b-%Y') as UsagePeriod,lastip,
     currentringto,alsouses,OID,DID from CusDocs where KeyID=\"$ID\" ";

        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
    }

    function GetOfferedCountryArea($MyCountryID) {
    
    $myADb=new ADb();
    $UID=currentUser();
    
    $strSQL = "select AreaID,AreaCD,City,CountryN from DIDS where OID=\"$UID\" and MyCountryID=\"$MyCountryID\" group by AreaID order by AreaCD ";

    $Result = $myADb->ExecuteQuery($strSQL);
    
    $html="<strong>Select City/Area Code</strong><select name='AreaList'  id=\"AreaList\">";

    $Selected = " selected ";

    while(!$Result->EOF){

        $AreaID = $Result->fields[0];
        $AreaCode = $Result->fields[1];
        $AreaName = $Result->fields[2];
        $CountryName = $Result->fields[3];
        
        if($AreaCode=="-99"){
            $CountryName = "National";
            $Selected = " selected ";
                $html .= "<option value=\"$AreaID\" $Selected>$CountryName</option>";
                $Selected = "  ";
            }
        else
                $html .= "<option value=\"$AreaID\">$AreaCode - $AreaName</option>";

        $Result->MoveNext();
    }
    
    $html .= "<option value=\"-1\" $Selected>-- Select Area --</option></select>";
    return $html;
    
    
}

    function PaymentToVendor($UID, $ffAmount, $txndiscount, $TID, $UniqID, $PAYPALID, $URL)
    {
        $myADb=new ADb();
        $strSQL  = "insert into PaymentToVendor(OID,Amount,Date,Admin,Comments,TType,TransID,UniqueID,PayPalID,LocationURL)values
            (\"$UID\",\"$ffAmount\",now(),\"$UID\",\"".$txndiscount."\",\"PPPP\",\"$TID\",\"$UniqID\", \"$PAYPALID\", \"$URL\")";
        #    print $strSQL;
        $Result = $this->ADb->ExecuteQuery($strSQL);
        
        
    }

    function imageshow($ID){
        $myADb=new ADb();
         $strSQL="select Doc,type from CusDocs where KeyID=\"$ID\" ";
        #   echo $strSQL;
             $Result    = $myADb->ExecuteQuery($strSQL);
             
             if($Result->EOF){
                
                $strSQL="select Doc,type from CusDocsAdd where KeyID=\"$ID\" ";
        #   echo $strSQL;
             $Result    = $myADb->ExecuteQuery($strSQL);
             
            }
            return $Result;
    }

    function InsertReferFriend($UID,$email,$Code,$FreName){
        $myADb=new ADb();
        $strSQL="INSERT INTO ReferFriend (OID,Email,RefCode,Date,Status,EmailDate,EmailSent,CreditRecieved,FreName)
                             VALUES (\"$UID\",
                                              \"$email\",
                                              \"$Code\",
                                              sysdate(),
                                              \"0\",
                                              sysdate(),
                                              0,
                                              \"0\",
                                            \"$FreName\")";
            $Result    = $myADb->ExecuteQuery($strSQL);   
            return $Result;         
    }
    function GetFormatByFunction($FunctioName,$FormatId){
        $myADb=new ADb();
        $strSQL = "select * from email_settings where 
        functionused='$FunctioName'";
        $Result = $myADb->ExecuteQuery($strSQL);
        $Hash=array();
        $Hash['Title']    = $Result->fields[1];
        $Hash['description']    = $Result->fields[2];
        $Hash['from_feild']    = $Result->fields[3];
        $Hash['reply_to_feild']    = $Result->fields[4];
        $Hash['cc_feild']    = $Result->fields[5];
        $Hash['subject_feild']    = $Result->fields[6];
        $Hash['contents_feild']    = $Result->fields[7];
        return $Hash;
    }

    function ReferFriendEmail($friendEmail,$GetSubject,$FreName,$Title,$pEmail,$description,$from_feild,$contents_feild,$CName,$reply_to_feild,$from_feild,$cc_feild){
        $to = $friendEmail;
        $GetSubject =     str_replace('$CName',$CName,$GetSubject);
        $subject = $GetSubject;
        $FindWords = array('$Friend', '$CName','$pEmail');
        $ReplaceWords   = array($FreName,$CName,$pEmail);
        $contents_feild = str_replace($FindWords, $ReplaceWords, $contents_feild);

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

    function GetCreditLimitPercent($pLimit,$pUsed) {
    
        $Percent = ($pUsed/$pLimit) * 100;
        
        return sprintf("%2.2f",$Percent);
        
   }

   function MyChannel(){
    $myADb=new ADb();
    $UID=currentUser();
    $strSQL = "select * from ChannelBuy where OID=\"$UID\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
    return $Result;

   }

   function Getvalueforchannel($DIDNumber){
    $myADb=new ADb();
    $strSQL = "select DIDS.OID,DIDS.AreaID,DIDCountries.CountryCode,DIDCountries.Description,DIDArea.StateCode,
        DIDArea.Description as a from DIDS, DIDCountries,DIDArea where DIDS.AreaID=DIDArea.Id and 
        DIDCountries.Id=DIDArea.CountryID and DIDNumber = \"$DIDNumber\"  ";
    $Result = $myADb->ExecuteQuery($strSQL);
    return $Result;
   }



   ///TESTPURCHASED CGI
   function TestThisDID($DIDNumber,$pLocation,$pUser,$pEmail,$pCallerID) {

    // echo "$DIDNumber,  $pLocation, $pCallerID =>  $pUser";
    // exit;

    $myADb=new ADb();
    $pLocation;
    $pEmail;
    $pCallerID;
    // my($DIDNumber,$pLocation,$pUser,$pEmail,$pCallerID) = @_;
    
    $CallerID="";
    if($pCallerID == ''){
        $CallerID = "2125551234";
    }else{
        $CallerID = $pCallerID;
    }



    $mgrUSERNAME='tony';
    $mgrSECRET='mypass';
    #my $server_ip='66.98.180.77';
    #my $server_ip='67.15.10.28';
    #my $server_ip='67.15.232.122'; # New IP-PABX SERVER
    #my $server_ip='174.123.211.66';
    $server_ip='173.192.46.93';
        
    $strSQL = "Select DIDNumber,OID,BOID,Status from DIDS where DIDNumber=\"$DIDNumber\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
        
    $SellerOID = $Result->fields[1];
    $BuyerOID  = $Result->fields[2];
    $Status    = $Result->fields[3];
        
    #   print "\$strSQL: $strSQL";
        
    $strSQL = "update DIDS set UnderCheck=\"1\", CheckStatus=\"0\" where DIDNumber=\"$DIDNumber\" ";
    $Result = $myADb->ExecuteQuery($strSQL);
        
    if (strtoupper(ENVIRONMENT) == "LIVE" ){

         $socket = fsockopen($server_ip,"5036", $errno, $errstr, 10);
         if (!$socket) { 
            echo "$errstr ($errno)\n";
         } else {
            fputs($socket, "Action: Login\r\n"); 
            fputs($socket, "Username: $mgrUSERNAME\r\n"); 
            fputs($socket, "Secret: $mgrSECRET\r\n\r\n"); 
            fputs($socket, "Action: Originate\r\n");
            //echo "waiting...";
            stream_set_timeout($socket, 10);
            //echo "waiting end";

            fputs($socket, "Exten: \r\n");
            fputs($socket, "Context: default\r\n");
            fputs($socket, "Channel: IAX2/800005:d7n6unt567\@carrierx.org/$varNumber$DIDNumber\r\n"); 
            fputs($socket, "Priority: 1\r\n");
            fputs($socket, "Callerid: $CallerID\r\n\r\n");
            fputs($socket, "Action: Logoff\r\n\r\n");

            // fputs($socket, "Command: sip show peers\r\n\r\n"); 
            // fputs($socket, "Action: Logoff\r\n\r\n");
            //$tn->print("Action: Originate\nExten: \nContext: default\nChannel: IAX2/800005:d7n6unt567\@carrierx.org/$varNumber$DIDNumber\nPriority: 1\nCallerid: $CallerID \n\n");
            while (!feof($socket)) {
               // echo fgets($socket).'<br>'; 
            } 
            fclose($socket); 
        } 

        $TestIndex;
        $CheckStatus =0;
        for($TestIndex=1;$TestIndex<=6 && $CheckStatus==0;$TestIndex++){
                sleep(10);
                $strSQL = "select CheckStatus from DIDS where DIDNumber = \"$DIDNumber\" ";
                $Result = $myADb->ExecuteQuery($strSQL);
                $CheckStatus = $Result->fields[0];

                             
        }     #### End of TestIndex Loop
                                    
             // echo $CheckStatus;
             // exit;                       
                                    // OUTOFLOOP:
        if($CheckStatus==1){
            $this->RecordLoggedClient($UID,114,$UID,"","","","");     
            if($pEmail == '1' && $BuyerOID == ''){
                (file_get_contents($GLOBALS['website_url']."/SendTestSingleEmailToBuyer?OID=$BuyerOID&DID=$DIDNumber&STA=1"));
            }
            return 1;
        }
        else{

                $this->RecordLoggedClient($UID,114,$UID,"","","","");  
                if($pEmail == '1'){
                    (file_get_contents($GLOBALS['website_url']."/SendTestSingleEmailToVendor?OID=$SellerOID&DID=$DIDNumber&STA=0"));
                }
                if($pEmail == '1'  && $BuyerOID == ''){
                    (file_get_contents($GLOBALS['website_url']."/SendTestSingleEmailToBuyerOnFail?OID=$BuyerOID&DID=$DIDNumber&STA=0")
                        );
                }
                return 0;
        }    
    } else {
        
        // fail response
        return 0;
    }
    exit;
                                    
 
    }


   //TESTPURCHASEDCGI
}
?>