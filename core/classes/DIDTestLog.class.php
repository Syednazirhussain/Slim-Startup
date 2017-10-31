<?php
class DIDTestLog
{
        //     Copyright©2005 Saleem Ahmed Kamboh. All rights reserved.
        var $fDebug = 0;
        function dPrint($str)
        {
                if ($this->fDebug)
                        echo "$str<br>\n";
        }
        
        public static function maxTime($pDID, $pDate)
        {
                $UID   = currentUser();
                $myADb = new ADb();
                
                $strSQL = "select max(id) from DIDTestLog where 
    did=\"$pDID\" and datestamp>=\"$pDate\" and status =1 and MyOID='$UID' group by DID";
                $Result = $myADb->ExecuteQuery($strSQL);
                return $Result;
        }
        
        public static function minTime($pDID, $pDate)
        {
                $UID    = currentUser();
                $myADb  = new ADb();
                $strSQL = "SELECT STATUS,datestamp FROM DIDTestLog WHERE 
        did=\"$pDID\" AND datestamp>=\"$pDate\" AND (SELECT MIN(id)) AND STATUS =0 GROUP BY DID";
                $Result = $myADb->ExecuteQuery($strSQL);
                return $Result;
        }
        
        public static function TotalHours($ID)
        {
                $UID    = currentUser();
                $myADb  = new ADb();
                $strSQL = "select status,datestamp from DIDTestLog where ID=\"$ID\"";
                $Result = $myADb->ExecuteQuery($strSQL);
                return $Result;
        }
        
        public static function getTotalHours($LastTest)
        {
                $myADb  = new ADb();
                $strSQL = "SELECT (TO_DAYS(NOW())-TO_DAYS(\"$LastTest\"))* 24";
                $Result = $myADb->ExecuteQuery($strSQL);
                return $Result;
        }
}
?>