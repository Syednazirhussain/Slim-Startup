<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
class BackOrder
{
    
    function GetChannelPricing($AreaID, $VOID, $Qty, $ChannelAdminID, $BuyingSetup, $BuyingMonthly)
      {
        $myADb  = new ADb();
        $strSQL = "select TotalChannels,Setup,Monthly,ID from ChannelAdmin 
    where OID=\"$VOID\"  and AreaID=\"$AreaID\" ";
        
        $Result = $myADb->ExecuteQuery($strSQL);
        
        if ($Result->EOF) {
            
            $strSQL = "select TotalChannels,Setup,Monthly,ID from ChannelAdmin 
            where OID=\"$VOID\"  and AreaID=\"-1\" ";
            #echo $strSQL;
            $Result = $myADb->ExecuteQuery($strSQL);
            if ($Result->EOF) {
                return -1;
            }
        }
        
        $TotalChannels  = $Result->fields[0];
        $BuyingSetup    = $Result->fields[1];
        $BuyingMonthly  = $Result->fields[2];
        $ChannelAdminID = $Result->fields[3];
        
        $TotalMonthly = $BuyingMonthly * $Qty;
        $html         = "<tr> 
            <td><div align=\"right\">&nbsp;Monthly Channel Price </div></td>
            <td>&nbsp;</td>
            <td> &nbsp;\$$BuyingMonthly * $Qty Channels = \$$TotalMonthly</td>
           </tr>
           <tr>
           <td colspan=\"3\" ></td>
           </tr>
           <tr> 
           <td> 
           <div align=\"right\">&nbsp;Channel Setup Price</div></td>
           <td>&nbsp;</td>
           <td>&nbsp;\$$BuyingSetup</td>
           </tr>";
        
        
        return $html;
        
    }
    
    function getCountryAndAreaCode($pCode, $pArea)
    {
        
        $myADb  = new ADb();
        $strSQL = " select DIDCountries.Description,DIDArea.Description as a, DIDArea.ID 
                from DIDCountries,DIDArea where DIDCountries.CountryCode = \"$pCode\" 
                and DIDArea.StateCode=\"$pArea\" and DIDArea.CountryID=DIDCountries.ID";
        
        $Result              = $myADb->ExecuteQuery($strSQL);
        $Hash                = array();
        $strSQL              = " select RateCenter from BackOrderAdmin where DIDArea=\"$pArea\" and CountryCode=\"$pCode\" ";
        $ResultRate          = $myADb->ExecuteQuery($strSQL);
        $RateCenter          = $ResultRate->fields[0];
        $Hash['CountryCode'] = $Result->fields[0];
        $Hash['AreaCode']    = $Result->fields[1];
        $Hash['AreaID']      = $Result->fields[2];
        $Hash['RateCenter']  = $RateCenter;
        return $Hash;
        
    }
    
    function getDataforOrder($ffCountries, $ffArea, $WhereNXX, $WhereVendor)
    {
        $myADb  = new ADb();
        $strSQL = " select CountryCode,DIDArea,Quantity,Monthly,Setup,Days ,ID,
                   date_format(date_add(curdate(),INTERVAL Days day),'%d-%b-%Y') as Date
                 from BackOrderAdmin where CountryCode=\"$ffCountries\" and DIDArea=\"$ffArea\" 
                 $WhereNXX $WhereVendor  ";
        $Result              = $myADb->ExecuteQuery($strSQL);
        $Hash                = array();
        $Hash['CountryCode'] = $Result->fields[0];
        $Hash['DIDArea']     = $Result->fields[1];
        $Hash['Quantity']    = $Result->fields[2];
        $Hash['Monthly']     = $Result->fields[3];
        $Hash['Setup']       = $Result->fields[4];
        $Hash['Days']        = $Result->fields[5];
        $Hash['ID']          = $Result->fields[6];
        $Hash['Date']        = $Result->fields[7];
        return $Hash;
    }
    
    
    function getAdminBackOrderDetailByID($pID)
    {
        
        $myADb  = new Adb();
        $strSQL = " select * from BackOrderAdmin  where ID=\"$pID\" ";
        $Result = $myADb->ExecuteQuery($strSQL);
        $Vendor = $Result->fields[1];
        // $CountryCode = $Result->fields[2];
        // $DIDArea = $Result->fields[3];
        // $Prefix = $Result->fields[4];
        // $Monthly = $Result->fields[5];
        // $Setup = $Result->fields[6];
        // $NXX = $Result->fields[9];
        // $RateCenter = $Result->fields[10];
        
        $Hash                = array();
        $Hash['CountryCode'] = $Result->fields[2];
        $Hash['AreaCode']    = $Result->fields[3];
        $Hash['Prefix']      = $Result->fields[4];
        $Hash['Monthly']     = $Result->fields[5];
        $Hash['Setup']       = $Result->fields[6];
        $Hash['Vendor']      = $Vendor;
        $Hash['NXX']         = $Result->fields[9];
        $Hash['RateCenter']  = $Result->fields[10];
        #    print $Hash{Vendor};
        return $Hash;
        
    }
    
    function getIfAlreadyExist($pCode, $pArea, $pNXX, $pVendor)
    {
        $myADb = new ADb();
        $UID   = currentUser();
        if ($pCode == '1') {
            $strSQL = "select OID from BackOrder where Status=1 and CountryCode=\"$pCode\" and  AreaCode=\"$pArea\" and NXX=\"$pNXX\"  and Vendor=\"$pVendor\" and OID=\"$UID\"";
        } else {
            $strSQL = " select OID from BackOrder where Status=1 and CountryCode=\"$pCode\" and AreaCode=\"$pArea\" and Vendor=\"$pVendor\" and OID=\"$UID\"";
        }
        
        $Result = $myADb->ExecuteQuery($strSQL);
        
        if (!$Result->EOF) {
            return 1;
        } else {
            return 0;
        }
    }
    
    
    function GetChannelPricingAction($ChannelAdminID, $BuyingSetup, $BuyingMonthly)
    {
        $myADb = new ADb();
        
        $strSQL = "select TotalChannels,Setup,Monthly,ID from ChannelAdmin 
            where id='$ChannelAdminID' ";
        #echo $strSQL;
        $Result = $myADb->ExecuteQuery($strSQL);
        
        if ($Result->EOF) {
            
            $strSQL = "select TotalChannels,Setup,Monthly,ID from ChannelAdmin 
                            where id='$ChannelAdminID'  ";
            #echo $strSQL;
            $Result = $myADb->ExecuteQuery($strSQL);
            if ($Result->EOF) {
                return -1;
            }
        }
        
        $TotalChannels = $Result->fields[0];
        $BuyingSetup   = $Result->fields[1];
        $BuyingMonthly = $Result->fields[2];
        
    }


    function SaveOrders($pCountryCode,$pAreaCode,$pQty,$pVendor,$pPrice,$pNXX,$pID,$pTRID,$pGrandTotal,$pRC,$ChQty,$ChannelAdminID) {
    
    //global $myADb,$UID,$AltYesNo;
      $myADb =  new ADb();
      $UID   = currentUser();
    
    $strSQL = " insert into BackOrder (AltYesNo,OID,CountryCode,AreaCode,Date,Quantity,Choice,Vendor,NXX,BackAdminID,TRID,Amount,Chqty,ChannelAdminID) 
                            values (\"$AltYesNo\",\"$UID\",\"$pCountryCode\",\"$pAreaCode\",curdate(),\"$pQty\",\"$pPrice\",\"$pVendor\",\"$pNXX\",\"$pID\",\"$pTRID\",\"$pGrandTotal\",\"$ChQty\",\"$ChannelAdminID\"   )";

    #echo "<br> $strSQL";
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $strSQL = "select max(ID) from BackOrder";
    #echo "<br> $strSQL";
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $MaxID = $Result->fields[0];
    
    
    $strSQL ="UPDATE BackOrder SET OrderRef=CONCAT(\"DIDXBO\",'$MaxID','$pID',CEIL(RAND()*1000)) where ID='$MaxID'";
    #echo "<br> $strSQL";
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $strSQL = "select max(ID) from BackOrder where OID='$UID'";
    #echo "<br> $strSQL";
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $MaxID = $Result->fields[0];
    
    $strSQL = "select OrderRef from BackOrder where ID='$MaxID'";
    #echo "<br> $strSQL";
    $Result= $myADb->ExecuteQuery($strSQL);
    
    $BO = $Result->fields[0];
    // echo $GLOBALS['website_url']."/SendBackOrderEmailToVendor?OID=$pVendor&CCODE=$pCountryCode&ACODE=$pAreaCode&RC=$pRC&NXX=$pNXX&QTY=$pQty";
    // exit;
    
    
    (file_get_contents($GLOBALS['website_url']."/SendBackOrderEmailToVendor?OID=$pVendor&CCODE=$pCountryCode&ACODE=$pAreaCode&RC=$pRC&NXX=$pNXX&QTY=$pQty"));
    (file_get_contents($GLOBALS['website_url']."/SendBackOrderEmailToUser?OID=$UID&CCODE=$pCountryCode&ACODE=$pAreaCode&RC=$pRC&NXX=$pNXX&QTY=$pQty&BO=$BO"));
    #echo "$GLOBALS['website_admin_url']SendBackOrderEmailToUser.php?OID=$UID&CCODE=$pCountryCode&ACODE=$pAreaCode&RC=$pRC&NXX=$pNXX&QTY=$pQty&BO=$BO";
    
    
}

    
    
}
?>