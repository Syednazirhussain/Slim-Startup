<?php
class CallLogs
{


	function CallLogs()
    {
        $this->Db = new ADb();
    }

    public static function ListWhere($conditions_array,$startLimit = null, $sortByColumn = null){
    	
    	$myADb = new ADb();
    	$start = 0;
    	$TableCDRS = "";
    	$TodayDate = Current_Date();

	   	$orderClause = "";
		$whereClause = "";

		$ffYear = substr($TodayDate,0,4);
		$ffMonth = substr($TodayDate,5,2);

		if(isset($startLimit)){
			$start = $startLimit;
		}

		if (isset($sortByColumn)) {
			$orderClause = " Order by $sortByColumn";
		} else {
			$orderClause = " Order by id";
		}

		if (array_key_exists('UID', $conditions_array)) {
			$UID = $conditions_array['UID'];
			if($UID != ''){
				if(is_numeric($UID)) {
					$whereClause .= "  OID = \"$UID\" ";
				}
			}
		}

        if (array_key_exists('DIDNumber', $conditions_array)) {
			$ffPDids = $conditions_array['DIDNumber'];
			if($ffPDids != ''){
				if($ffPDids =='%') {
					$whereClause .= "and callednum  like '$ffPDids'";
				} else {
					$whereClause .= " and callednum= '$ffPDids'";
				} 
			}
		}

		if (array_key_exists('month', $conditions_array)) {
			$ffMonth = $conditions_array['month'];

		}

		if (array_key_exists('year', $conditions_array)) {
			$ffYear = $conditions_array['year'];
		}

		$OldDate = "$ffYear-$ffMonth-01 00:00:00";
	    $IfOldDate = GetIfOld($OldDate);
		$IfOldDate2014 = GetIfOld2($OldDate);

		if($IfOldDate2014==0)
		{
	        $TableCDRS = " cdrs ";
	    }
		elseif($IfOldDate==1)
        {
            $TableCDRS = " cdrs2013 ";
        }
        elseif($IfOldDate2014==1)
        {
            $TableCDRS = " cdrs2014 ";
        } 

     
        $whereClause .= " and callstart like \"$ffYear-$ffMonth%\"";

		if (array_key_exists('CallStartDate', $conditions_array)) {
			$callstartDate = $conditions_array['CallStartDate'];
			if($callstartDate != ''){
				//$whereClause .= " and callstart like \"$ffYear-$ffMonth%\""; format
				$whereClause .= " and callstart like '$callstartDate%' ";
			}
		}


		$strSQL	= "select ringto,callerid,callednum,trunk,disposition,billseconds,billcost,callstart,OID,id,calleridname,
							uniqueid,fromip,
							TotalMinutes,TalkTimeWas,TalkTimeCut,TalkTImeRemain,ExpiryDate,MinutesTotalUsed,
							date_format(callstart,'%d-%m-%Y %h:%i') AS CallDate,RecordKey,IsPayFone, SystemBox, resellerrate
							 from $TableCDRS where $whereClause $orderClause limit $start, 100";
		

		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;

    }

}


?>