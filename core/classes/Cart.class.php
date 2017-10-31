<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
class Cart
{
   function GetVendorRatingByDID($pDIDNumber, $UID)
   {
      $myADb   = new ADb();
      $strSQL  = "select VendorRating from DIDS where DIDS.DIDNumber=\"$pDIDNumber\" ";
      $Result  = $myADb->ExecuteQuery($strSQL);
      $VRating = $Result->fields[0];
      $strSQL  = "select DIDRating from orders where OID=\"$UID\"  ";
      $Result  = $myADb->ExecuteQuery($strSQL);
      $Rating  = $Result->fields[0];
      if ($VRating < $Rating) {
         return -1;
      }
      return $Rating;
      
   }
   
   
   function AddToCartNow($pOID, $DID, $IsTrigger)
   {
      $myADb       = new ADb();
      $strSQL      = "select DIDNumber,CountryN as a,City,OurSetupCost,OurMonthlyCharges, 
        CountryCD,AreaCD,DIDS.OID from DIDS where DIDS.DIDNumber=\"$DID\" and status=0";
      $Result      = $myADb->ExecuteQuery($strSQL);
      $DIDNumber   = $Result->fields[0];
      $Country     = $Result->fields[1];
      $Area        = $Result->fields[2];
      $Setup       = $Result->fields[3];
      $Monthly     = $Result->fields[4];
      $CountryCode = $Result->fields[5];
      $AreaCode    = $Result->fields[6];
      $VOID        = $Result->fields[7];
      $KeyID       = substr(md5($pOID) . rand(), 0, 10);
      $strSQL      = "select DIDNumber from ShoppingCart where DIDNumber=\"$DIDNumber\" ";
      $ResultDID   = $myADb->ExecuteQuery($strSQL);
      if (!$ResultDID->EOF)
         return;
      $strSQL = "insert into ShoppingCart (IsTrigger,OID,VOID,DIDNumber,Date,Setup,Monthly, 
                   KeyID,AreaCode,CountryCode,Area,Country) values (\"$IsTrigger\",\"$pOID\",  
                   \"$VOID\",\"$DIDNumber\",now(),\"$Setup\",\"$Monthly\",\"$KeyID\",\"$AreaCode\" 
                   ,\"$CountryCode\",\"$Area\",\"$Country\") ";
      
      $Result = $myADb->ExecuteQuery($strSQL);
      $strSQL = "update DIDS set Status=1 where DIDNumber=\"$DIDNumber\" and Status=0 ";
      $Result = $myADb->ExecuteQuery($strSQL);
      
   }
   
   
   function AddToCartNowforMyCart($pOID, $DIDNum)
   {
      $myADb = new ADb();
      foreach ($DIDNum as $key => $value) {
         $SNo++;
         $strSQL      = "select DIDNumber,DIDCountries.description as    a, 
                DIDArea.description,OurSetupCost,OurMonthlyCharges,DIDCountries.CountryCode, 
                DIDArea.StateCode,DIDS.OID, DIDS.NumberHide from DIDS,DIDArea,DIDCountries,orders 
                where DIDS.AreaID=DIDArea.id and DIDArea.CountryID=DIDCountries.id  
                and    orders.OID=DIDS.OID and DIDS.DIDNumber='$value' and status=0 ";
         $Result      = $myADb->ExecuteQuery($strSQL);
         $DIDNumber   = $Result->fields[0];
         $Country     = $Result->fields[1];
         $Area        = $Result->fields[2];
         $Setup       = $Result->fields[3];
         $Monthly     = $Result->fields[4];
         $CountryCode = $Result->fields[5];
         $AreaCode    = $Result->fields[6];
         $VOID        = $Result->fields[7];
         $NumberHide  = $Result->fields[8];
         $KeyID       = $myGeneral->getKey($pOID);
         $strSQL      = "insert into ShoppingCart (OID,VOID,DIDNumber,Date,Setup,Monthly,KeyID, 
                   AreaCode,CountryCode,Area,Country,HideNumber) values (\"$pOID\", \"$VOID\", 
                   \"$DIDNumber\",now(),\"$Setup\",\"$Monthly\",\"$KeyID\",\"$AreaCode\", 
                   \"$CountryCode\",\"$Area\",\"$Country\",\"$NumberHide\") ";
         $Result      = $myADb->ExecuteQuery($strSQL);
         $strSQL      = "update DIDS set Status=1 where DIDNumber=\"$DIDNumber\" and Status=0 ";
         $Result      = $myADb->ExecuteQuery($strSQL);
      }
      $TotalNumbers = $nIndex;
   }
   
   
   function UpdateCart($ffID, $ffKID, $DID)
   {
      $myADb        = new ADb();
      $strSQL       = " delete from ShoppingCart where ID=\"$ffID\" and KeyID=\"$ffKID\"  ";
      $Result       = $myADb->ExecuteQuery($strSQL);
      $strSQL       = "update DIDS set Status=0,ResRelDat='',IsResRel=0,ResRelOID='' where  
                   Status=1 and DIDNumber=\"$DID\" ";
      $ResultUpdate = $myADb->ExecuteQuery($strSQL);
   }
   
   
   function getAllCartItemsByOID($UID, $myTransaction, $ButtonDisable, $ForceBuyer)
   {
      $myADb       = new ADb();
      $strSQL      = " select count(*) from ShoppingCart where OID=\"$UID\"";
      $ResultCount = $myADb->ExecuteQuery($strSQL);
      $strSQL      = "select ID,OID,VOID,DIDNumber,date_format(Date,'%d-%b-%Y') as Date, 
                   setup,Monthly,KeyID,AreaCode,CountryCode,Area,Country,HideNumber, 
                   IsTrigger from ShoppingCart where OID=\"$UID\" limit 0,50 ";
      
      $Result = $myADb->ExecuteQuery($strSQL);
      
      return $Result;
   }
   
   function ResultCheck($DIDNumber)
   {
      $myADb       = new ADb();
      $strSQLCheck = "select ID, DIDNumber, AreaID from DIDS where DIDNumber=\"$DIDNumber\" ";
      $ResultCheck = $myADb->ExecuteQuery($strSQLCheck);
      return $ResultCheck;
   }
   
   function getDocumentMsgByOIDDID($pVOID, $pPrefix)
   {
      
      $myADb = new ADb();
      $strSQL = " select docmsg,ID,Img from DocMsg where OID=\"$pVOID\" and Prefix=\"$pPrefix\"  ";
      #print "<br>$strSQL";
      $Result = $myADb->ExecuteQuery($strSQL);
      
      if ($Result->EOF) {
         $strSQL  = " select docmsg,ID,img from DocMsg where OID=\"$pVOID\" and Prefix=\"-1\"  ";
         #print "<br>$strSQL";
         $Result2 = $myADb->ExecuteQuery($strSQL);
         
         if (!$Result2->EOF) {
            
            $Hash;
            $Hash['MSG']     = $Result2->fields[0];
            $Hash['Enable']  = 1;
            $Hash['ID']      = $Result2->fields[1];
            $Hash['ImgType'] = $Result2->fields[2];
            return $Hash;
            
         } else {
            
            $Hash;
            $Hash['MSG']     = "";
            $Hash['Enable']  = 0;
            $Hash['ImgType'] = 1;
            return $Hash;
         }
      }
      
      $Hash;
      $Hash['MSG']     = $Result->fields[0];
      $Hash['ID']      = $Result->fields[1];
      $Hash['Enable']  = 1;
      $Hash['ImgType'] = $Result->fields[2];
      return $Hash;
   }
   
   
   function getIfNeedsDocs($AreaID, $VOID)
   {
      $NeedsDoc = $this->getDocumentMsgByOIDDID($VOID, $AreaID);
      return $NeedsDoc;
      
   }
   
   
   function CheckIfIgotApproval($pOID, $pDIDNumber)
   {
      $myADb  = new ADb();
      $strSQL = " select status from CusDocs where OID=\"$pOID\" and DID=\"$pDIDNumber\" ";
        
      $Result = $myADb->ExecuteQuery($strSQL);
      if (!$Result->EOF) {
         return $Result->fields[0];
      } else {
         return -1;
      }
   }
   
   function GetTotalNumbers()
   {
      $myADb  = new ADb();
      $UID    = currentUser();
      $strSQL = "select count(*) from ShoppingCart where OID=\"$UID\" ";
      $Result = $myADb->ExecuteQuery($strSQL);
      return $Result->fields[0];
      
   }

   

   function Check($DIDNumber){
      $myADb=new ADb();
      $strSQLCheck="select DIDNumber,OurSetupCost,OurMonthlyCharges,
        OurPerminutecharges,ID,AreaID,OID,SetupCost,OurMonthlyCharges from DIDS
        where DIDNumber=\"$DIDNumber\" and status=1";
      $ResultCheck = $myADb->ExecuteQuery($strSQLCheck);
      return $ResultCheck;
   }

   function IsAlreadyExits($pOID,$pType,$pDID) {
      
      $myADb = new ADb();  
         
      $strSQL  = "select * from transaction  where OID=\"$pOID\"
                         and Type = \"$pType\"  and description like \"%$pDID%\" and IsDeleted=0";
      
      $Result  = $myADb->ExecuteQuery($strSQL);
       
      if($Result->EOF)
            return 0;
      else
            return 1; 
    
     }


     function ResultCheck2($DIDNumber){
      $myADb = new ADb();  
       $strSQLCheck="select DIDNumber,OurSetupCost,OurMonthlyCharges,
                               OurPerminutecharges,ID,AreaID,OID,SetupCost,MonthlyCharges from DIDS
                                where DIDNumber=\"$DIDNumber\" ";
                                                                    
       $ResultCheck = $myADb->ExecuteQuery($strSQLCheck);
       return $ResultCheck;      
     }

     function updateDIDs($IsTrigger,$DefaultRingTo,$DefaultType,$CListBought,$BillingCycle1st,$DIDNumber){
      $myADb=new ADb();
      $UID=currentUser();
       $strSQL = "update DIDS  set OnTrigger=\"$IsTrigger\",Status = 2,BOID=\"$UID\",iPUrchasedDate=sysdate(), iURL=\"$DefaultRingTo\",iFlag=\"$DefaultType\" $CListBought $BillingCycle1st where DIDNUmber=\"$DIDNumber\" ";
       $Resultup = $myADb->ExecuteQuery($strSQL); 
       return $Resultup;
     }

     function GetSpan(){
      $myADb=new ADb();
       $strSQL = "select date_format(curdate(),'%d-%b-%Y'),date_format(date_sub(date_add(curdate(),interval 1 month),interval 1 day),'%d-%b-%Y'),substring(curdate(),3,2),substring(curdate(),6,2)";  
        $ResultSpan = $myADb->ExecuteQuery($strSQL);
        return $ResultSpan;   

     }

     function RemoveCart($DIDNumber){
      $myADb=new ADb();
      $strSQL = "delete from ShoppingCart where DIDNumber=\"$DIDNumber\" ";
      $ResultShop = $myADb->ExecuteQuery($strSQL); 
      return $ResultSpan; 
     }

  
   
   
}
?>