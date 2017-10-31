<?php
// include_once("Const.inc.php");
// include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
class RingToHistory
{
    
    function getRingToHistory($pDID)
    {
        
        $myADb  = new ADb();
        $UID    = currentUser();
        $strSQL = "select id,did,oid,oldringto,newringto,date_format(date,'%d-%b-%Y %h:%i:%s') as Todays,user,code,type,cond,locationringto from RingtoHistory where DID=$pDID and OID=$UID order by date desc limit 0,10";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
    }
    
    function getIfDIDorID($pID)
    {
        
        $myADb  = new ADb();
        $UID    = currentUser();
        $strSQL = " select DIDS.Id from DIDS where DIDNumber=$pID ";
        $Result = $myADb->ExecuteQuery($strSQL);
        $Deal   = $Result->fields[0];
        return $Deal;
        
    }
    
    
    function getcurrent($ffDealID)
    {
        
        $myADb  = new ADb();
        $UID    = currentUser();
        $strSQL = "select iFlag, iURL, ID, DIDNumber,
(select ServerIP from EmailPref    where UID = $UID) as ServerIP,
    (select DefaultRingTo from EmailPref where uid = $UID) as DefaultRingTo
     from  DIDS where id = $ffDealID ";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
    }
    
    function getdidnumber($DealsID)
    {
        $myADb    = new ADb();
        $UID      = currentUser();
        $strSQL   = "select DIDnumber from DIDS where DIDS.id=\"$DealsID\" and status=2 and BOID=\"$UID\"  ";

        $ResultID = $myADb->ExecuteQuery($strSQL);
        
        if($ResultID->EOF){
            print "<meta http-equiv=refresh content='0;url=/home'>";
        exit;
       }
       
        $ResultID = $ResultID->fields[0];
        return $ResultID;
    }
    
    
    function AddRingtoHistory($pDID, $NewRingTo, $pFlag, $pDealsID)
    {
        
        $myGeneral  = new General();
        $myADb      = new ADb();
        $UID        = currentUser();
        $strSQL     = "select DIDS.DIDNumber,DIDS.iURL,DIDS.BOID from DIDS where DIDS.id=\"$pDealsID\" ";
        $Result     = $myADb->ExecuteQuery($strSQL);
        $CureRingTo = $Result->fields[1];
        $DID        = $Result->fields[0];
        $OID        = $Result->fields[2];
        $strSQL     = "insert into RingtoHistory (DID,OID,OldRingTo,NewRingTo,Date,User,Type,LocationRingTo) 
                                values(\"$DID\",\"$UID\",\"$CureRingTo\",\"$NewRingTo\",sysdate(),\"$UID\",\"$pFlag\",\"WEB-CLIENT\")";
        $Result     = $myADb->ExecuteQuery($strSQL);
        $Changed    = " $CureRingTo to $NewRingTo";
        $myGeneral->RecordLoggedClient($UID, 111, $UID, "", $DID, "", $Changed);
        return;
        
    }
    
    
    function getAlterRingToByDID($pDID, $pOID, $ffDealsID)
    {
        $myADb  = new ADb();
        $strSQL = "select ID,OID,DID,AlterRingTo.Condition,condtype,ringto,flag,status,date_format(date,'%d-%b-%Y') as date from AlterRingTo where (DID=\"$pDID\" or DID=\"-11\") and OID=\"$pOID\" order by date desc limit 0,10";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
        
    }
    
    function UpdateEditRingTo($NewRingToURL, $Flag, $DealsID, $Desc)
    {
        
        $myADb  = new ADb();
        $strSQL = "update  DIDS set iURL=\"$NewRingToURL\", iFlag=$Flag where id ='$DealsID'";
        $Result = $myADb->ExecuteQuery($strSQL);
        $data   = array(
             'Desc' => $Desc,
            'NewRingToURL' => $NewRingToURL
        );
        return $data;
    }
    
    
    function UpdateAlterRingTo($pDID, $RingTo, $pFlag, $pCond, $ffDealsIDNew)
    {
        
        
        $myADb = new ADb();
        $UID   = currentuser();
        
        if ($ffDealsIDNew) {
            $pDID = "-11";
        }
        
        
        $strSQL = "select OID,DID from AlterRingTo where OID=\"$UID\"  and DID=\"$pDID\"  ";

        $Result = $myADb->ExecuteQuery($strSQL);
        
        if ($Result->EOF) 
        {
            $strSQL = "insert into AlterRingTo(OID,DID,CondType,RingTo,Flag)values(\"$UID\",\"$pDID\",\"$pCond\",\"$RingTo\",\"$pFlag\") ";
            $Result = $myADb->ExecuteQuery($strSQL);
        }
        
        $strSQL = "update AlterRingTo set CondType=\"$pCond\",RingTo=\"$RingTo\",Flag=\"$pFlag\" where
                                        DID=\"$pDID\" and OID=\"$UID\"  ";
        
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
        
        
        
    }

    function RemoveAlterRingTo($DID)
    {
        $myADb = new ADb();
        $UID   = currentuser();
        $strSQL=" delete from AlterRingTo where DID=\"$DID\" and OID=\"$UID\"  ";
        $ResultID = $myADb->ExecuteQuery($strSQL);
        return $ResultID;
    }

    function Changedefault()
    {
        $myADb = new ADb();
        $UID   = currentuser();
        $strSQL = "select DefaultRingTo as Current,DefaultRingToType as Type,                    
                     Servertype, ServerIP                              
                     from EmailPref where uid=\"$UID\" ";           

        $Result = $myADb->ExecuteQuery($strSQL);

        return $Result;
    }

    function UpdateDefaultRingTo($ffRingToType, $ffRingTo)
    {
        $myADb = new ADb();
        $UID   = currentuser();
        $strSQL= "update EmailPref set
                    DefaultRingtoType    =    \"$ffRingToType\",
                    DefaultRingTo        =    \"$ffRingTo\"
                    where UID=\"$UID\" ";
        
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;

    }

    function AddRingtoHistoryforMassUP($NewRingTo,$pFlag) {
    
    $myADb=new ADb();
    $myGeneral=new General();
    $UID=currentUser();
    $CureRingTo="";
    
    
    $strSQL="insert into RingtoHistory (DID,OID,OldRingTo,NewRingTo,Date,User,Type,LocationRingTo) 
                                values(\"ALL\",\"$UID\",\"$CureRingTo\",\"$NewRingTo\",sysdate(),\"$UID\",\"$pFlag\",\"WEB-CLIENT\")";  
    
    $Result = $myADb->ExecuteQuery($strSQL);
    
    $Changed = " $CureRingTo to $NewRingTo";
    
    
    $myGeneral->RecordLoggedClient($UID,111,$UID,"",$DID,"",$Changed);
    return;
}  
    


   
}
?>