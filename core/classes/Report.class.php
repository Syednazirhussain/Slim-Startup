<?php
class Report 
{

  public static function DIDActualDays($ffOID)
	{
        $html="";
		$myADb=new ADb();
        $strSQL = "select DIDNumber,iPurchasedDate,OurMonthlyCharges,ID,BOID,substring(iPurchasedDate,9,2)
				   from DIDS where BOID='$ffOID' and status=2 order by iPurchasedDate desc Limit 0,20";

        $Result = $myADb->ExecuteQuery($strSQL);
        $bgcolor="grey"; 
        $index=1;
       

        while(!$Result->EOF){

			$DIDNumber = $Result->fields[0];

			$Date = $Result->fields[1];
			$TotalAmount = $Result->fields[2];
			$ID = $Result->fields[3];
			$BOID = $Result->fields[4];
			$Cycle = $Result->fields[5];
        $strSQL = "select type,Amount,description from transaction where DIDID='$ID' and IsDeleted=0 and OID='$BOID' and date>=substring('$Date',1,10) order by date desc Limit 0,20";

        $ResultR = $myADb->ExecuteQuery($strSQL);

        while(!$ResultR->EOF){

			$Type = str_replace("PVPL","",$ResultR->fields[0]);
			$Desc = $ResultR->fields[2];
			$Month = substr($Type,0,2); 
			$Year = "20".substr($Type,2,2);
			$ThisDate 		 = "$Year-$Month-$Cycle";
			$ThisStartDate = "$Year-$Month-01";
			$strSQL = "select date_format('$ThisDate','%b-%Y')";
            $ResultMonth = $myADb->ExecuteQuery($strSQL);
            $MonthDesc = $ResultMonth->fields[0];
            $LastDayOfThisMonth = Report::GetLastDayOfThisMonth($ThisDate);
			$TotalDaysThisMonth = Report::GetTotalDaysThisMonth($LastDayOfThisMonth,$ThisStartDate);
			$TotalBillingDaysThisMonth = Report::GetTotalBillingDaysThisMonth($LastDayOfThisMonth,$ThisDate);
		if($TotalBillingDaysThisMonth==0)
			$TotalBillingDaysThisMonth=1;
		    $NextMonthCycleDate = Report::GetNextMonthCycleDate($ThisDate);
			$NextMonthLastDay	= Report::GetNextMonthLastDay($NextMonthCycleDate);
			$NextMonthFirstDay	= Report::GetNextMonthFirstDay($NextMonthLastDay);
			$NextMonthTotalDays = Report::GetNextMonthTotalDays($NextMonthLastDay,$NextMonthFirstDay);
			$NextMonthBillingDays = Report::GetNextMonthBillingDays($NextMonthCycleDate,$NextMonthFirstDay);
			$PerDayCharge = $TotalAmount / 30;
			$ThisMonthTotalCharges = number_format($PerDayCharge * $TotalBillingDaysThisMonth,3);
            $NextMonthTotalCharges = number_format($PerDayCharge * $NextMonthBillingDays,3);
				$html .= "
						<tr class=\"$bgcolor\">
						<td><div align=center>$index</div></td>
						<td><div align=center><a href='CDIDInfo?did=$DIDNumber'>$DIDNumber</a></div></td>
						<td><div align=center>$Desc</div></td>
						<td><div align=center>$MonthDesc</div></td>										
						<td><div align=center>$TotalAmount</div></td>
						<td><div align=center>$ThisMonthTotalCharges ($TotalBillingDaysThisMonth Days)</div></td>
						<td><div align=center>$NextMonthTotalCharges ($NextMonthBillingDays Days)</div></td>
						</td>
						</tr>";
										
				$Sno1="&nbsp;";														
				$index++;			
				$ResultR->MoveNext();
			}

	    			$Result->MoveNext();
	    }
	   
	    return $html;
    }

    public static function GetTotalDaysThisMonth($LastDay,$ThisStartDate){
	
	    $myADb=new ADb();
		$strSQL = "select DATEDIFF('$LastDay','$ThisStartDate')";
		$ResultDate = $myADb->ExecuteQuery($strSQL);
		return $ResultDate->fields[0];
	
    }

    public static function GetLastDayOfThisMonth($ThisDate){
	
		$myADb = new ADb();
	    $strSQL = "select Last_Day('$ThisDate')";
		$ResultDate = $myADb->ExecuteQuery($strSQL);
		return $ResultDate->fields[0];
	
    }

    public static function GetTotalBillingDaysThisMonth($LastDay,$ThisDate) {
	
	    $myADb=new ADb();
	    $strSQL = "select DATEDIFF('$LastDay','$ThisDate')";
        $ResultDate = $myADb->ExecuteQuery($strSQL);	
        return $ResultDate->fields[0];
	
    }


    public static function GetNextMonthCycleDate($ThisDate){
	
	    $myADb=new ADb();
	    $strSQL = "select date_add('$ThisDate',interval 1 month) ";
		$ResultNxtDate = $myADb->ExecuteQuery($strSQL);
		return $ResultNxtDate->fields[0];
    }


    public static function GetNextMonthLastDay($NextMonthDate){
		
		$myADb=new ADb();
		$strSQL = "select Last_Day('$NextMonthDate')";
		$ResultNxtDate = $myADb->ExecuteQuery($strSQL);
		return $ResultNxtDate->fields[0];
		
	}


	public static function GetNextMonthFirstDay($NextMonthLastDay){
		
		$myADb=new ADb();
		$NextMonthFirstDay = substr($NextMonthLastDay,0,4)."-".substr($NextMonthLastDay,5,2)."-"."01";
		return $NextMonthFirstDay;
		
	}


	public static function  GetNextMonthTotalDays($NextMonthLastDay,$NextMonthFirstDay){
		
		$myADb=new ADb();
		$strSQL = "select DATEDIFF('$NextMonthLastDay','$NextMonthFirstDay')";
		$ResultDate = $myADb->ExecuteQuery($strSQL);
		
	}


	public static function GetNextMonthBillingDays($NextMonthDate,$NextMonthFirstDay){
		
		$myADb=new ADb();
		$strSQL = "select DATEDIFF('$NextMonthDate','$NextMonthFirstDay')";
		$ResultDate = $myADb->ExecuteQuery($strSQL);
		return  $ResultDate->fields[0];
	}



    function downloading(){

			$UID=currentUser();
			$myADb=new ADb();
			$strSQL = "select Status,FileName,Date from RequestBillCSV where OID='$UID' and Type='6'";
				#	echo $strSQL;
					$Result = $myADb->ExecuteQuery($strSQL);
						if(!$Result->EOF){
							$strSQL = "update RequestBillCSV set Status=0 where OID='$UID' and Type='6'";
						#	echo $strSQL;
							$Result = $myADb->ExecuteQuery($strSQL);
						}else{
							$strSQL = "insert into RequestBillCSV(OID,Type,Status)value('$UID','6','0')";
						#	echo $strSQL;
							$Result = $myADb->ExecuteQuery($strSQL);
						}
				return $Result;
						
	}



	function DownlaodMsg(){

			$UID=currentUser();
			$myADb=new ADb();
			$strSQL = "select Status,FileName,date_format(Date,'%d-%b-%Y') from RequestBillCSV where OID='$UID' and Type='6'";

		   $Result = $myADb->ExecuteQuery($strSQL);
		   return $Result;
	}


	function getFileNameFromRequestBillCSV($DID){

			$UID=currentUser();
			$myADb=new ADb();
			$strSQL = "select OID,Status,FileName from RequestBillCSV where OID=\"$UID\" and DID=\"$DID\" and Type=10";
		   $Result = $myADb->ExecuteQuery($strSQL);
		   return $Result;
	}

	function updateDateInRequestBillCSV($DID,$ffMonth,$ffYear){

			$UID=currentUser();
			$myADb=new ADb();
			$strSQL = "update RequestBillCSV set Status=0,Month=\"$ffMonth\",Year=\"$ffYear\",DID=\"$DID\"  where OID=\"$UID\"  and Type=10";
		   $Result = $myADb->ExecuteQuery($strSQL);
		   return $Result;
	}

	function insertDateInRequestBillCSV($DID,$ffMonth,$ffYear){
			$UID=currentUser();
			$myADb=new ADb();
			$strSQL = "insert into RequestBillCSV (OID,Date,Status,Month,Year,Type,DID)   values(\"$UID\",sysdate(),0,\"$ffMonth\",\"$ffYear\",\"10\",\"$DID\")";
			
		   $Result = $myADb->ExecuteQuery($strSQL);
		   return $Result;
	}

	function getCurrentDate(){
		$UID=currentUser();
		$myADb=new ADb();
		$strSQL = "select substring(curdate(),6,2),substring(curdate(),1,4)";
		$Result = $myADb->ExecuteQuery($strSQL);
		$data = array();
		$data['ThisMonth'] = $Result->fields[0];
    	$data['ThisYear'] = $Result->fields[1];
		return $data;
	}


	function getFileName(){
		$UID=currentUser();
		$myADb=new ADb();
		$strSQL = "select Status,FileName,date_format(Date,'%d-%b-%Y'),ID from RequestBillCSV where OID=\"$UID\"  and Type=10";
		$Result = $myADb->ExecuteQuery($strSQL);
		$Pre =" ";
	    $Count=0;
	    $bgcolor="";
	    while(!$Result->EOF)
	    {
	      $FileName = $Result->fields[1];
	      $Status = $Result->fields[0];
	      $Label = "Update CSV Download";
	      $Date = $Result->fields[2];
	      $ID = $Result->fields[3];
	      if($Status==0)
	        $Count++;
	      if($bgcolor=="")
	      {
	        $bgcolor="grey";
	      }else{
	        $bgcolor="";
	      }
	      if($FileName != '')
	      {
	        $Pre .= "<tr class=\"$bgcolor\"><td width=\"50%\">Download CSV Prepared on $Date</td>
	                             <td width=\"50%\"><a href=\"RequestCDRCSV_Download?Type=10&ID=$ID\">$FileName</a></td></tr>";
	      }
	      $Result->MoveNext();
	    }
		return $Pre;
	}

function getCount(){
		$UID=currentUser();
		$myADb=new ADb();
		$strSQL = "select Status,FileName,date_format(Date,'%d-%b-%Y'),ID from RequestBillCSV where OID=\"$UID\"  and Type=10";
		$Result = $myADb->ExecuteQuery($strSQL);
		$Count=0;
	    while(!$Result->EOF)
	    {
	      $FileName = $Result->fields[1];
	      $Status = $Result->fields[0];
	      $Label = "Update CSV Download";
	      $Date = $Result->fields[2];
	      $ID = $Result->fields[3];
	      if($Status==0)
	        $Count++;
	     
	      $Result->MoveNext();
	    }
		return $Count;
	}
	function GetMyDIDS() {
		$UID=currentUser();
		$myADb=new ADb();
		$html = '';
	    $strSQL = "select DIDNumber from DIDS where BOID=\"$UID\" and status=2 order by cast(DIDNUmber as unsigned)";
	    $Result = $myADb->ExecuteQuery($strSQL);
	    
	    while(!$Result->EOF){
	    
	      $DIDNumber  = $Result->fields[0];
	      $html .= "<option value= $DIDNumber >$DIDNumber </option>";
	      $Result->MoveNext();
	    }
	    $html .= "<option value= '-1' >ALL</option>";
	    return $html;
  }


  //Yearly Reports start
  function GetThisYear()
  {
  	$myADb=new ADb();
  	$strSQL  = "select year(curdate())";
    $ResultYear = $myADb->ExecuteQuery($strSQL);

    $ThisYear = $ResultYear->fields[0];
    return $ThisYear;
  }

  function GetSummaryYearly($pUID) {
	$myADb = new ADb();
	$ThisYear=$this->GetThisYear();
	
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	$bgcolor="grey";
	$html="<div class=\"onecolumn\">
            <div class=\"header\">

                </div>
                ";
		
	$MyTotal="";

			
			
			
			$RowHeader=1;
			$html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\"> 
			<h3><span><b>Payment Made To DIDx (USD)</b></span></h3>";
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									 $html .= "<tr class=\"grey\">";
									
									if($RowHeader==1){
										
												
										$html .= "<th >&nbsp;</td>";	
												$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
													#print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th >$MonthName</th>";
													$ResultM->MoveNext();
												}
												

												

												
												$html .= "<th ><strong>Year Total</strong></th>";

												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td ><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 
													 
													 $strSQL = "select sum(Amount) from transaction where 
													 date like '$Year-$Month%' and OID=\"$pUID\" and
													 (type =\"PURC\" or type =\"ADWU\" or type =\"ADCH\" or type =\"ADOP\" or type =\"ADWT\" or type =\"ADAH\" 
	or type =\"ADZR\" or type =\"ADBP\" or type =\"ADIN\" or type =\"ADDB\" or type =\"ADPP\" or type =\"ACPT\"  or type =\"ADMB\") and isdeleted=0 and iscredit=0
													 
													  ";
													 
														#print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 $Amount = $ResultA->fields[0];
														$MonthlyTotal = $MonthlyTotal + $Amount;
														 
													
													$YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
													
													if($Amount<=0)
													$html .= "<td>" . number_format($Amount,2) . "</td>";
													
													if($Amount>0)
													$html .= "<td>
                                                    <a href=\"PaymentMadeToDIDx?month=$Month&year=$Year\">" . number_format($Amount,2) . "</a></td>";
													
												}
												
												$html .= "<td>
                                                \$" . number_format($YearTotalAmount,2) . "</td>";
												
												$MyTotal = $MyTotal + $YearTotalAmount;
												
												$html .= "</tr>";
												
												
												
		}
		
		$html .= "<tr bgcolor=\"#F9F9E8\"><td><strong>Total</strong></td>
                  <td colspan=13 align=left>\$" . number_format($MyTotal,2) . "</td></tr>";
        $html.="</table>";
        $html.="</div>
            </div>";
		
							
	return $html;
}


function GetMinutesReportBuy($pUID)
{
    
    $myADb = new ADb();
	$ThisYear=$this->GetThisYear();
    

    
    $RowHeader=0;
    $MonthlyTotal=0;
    $YearTotalAmount=0;
    
    
    $bgcolor="grey";
	$html="<div class=\"onecolumn\">";
        
    $MyTotal="";

            
            
            
            $RowHeader=1;
            $html .= "
            <div class=\"header\">
            <div class=\"table table-responsive\"><table class=\"table table-striped\">  

    		
                    <h3><span><b>Minutes Report </b></span></h3>
                </div>";
                            for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
                                    
                                    $Year = $nIndex;
                                    $YearTotalAmount= 0;
                                    
                                    
                                    
                                    if($RowHeader==1){
                                        
                                        $html .= "<tr class=\"grey\">";
                                        $html .= "<th >&nbsp;</th>";       
                                                $MonthlyTotal = 0;
                                            for($aIndex=1;$aIndex<=12;$aIndex++){
                                                
                                                    if($aIndex>=1 && $aIndex<=9)
                                                            $UnitM = "0" . $aIndex;
                                                    else
                                                            $UnitM = $aIndex;
                                                            
                                                    $strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
                                                    #print $strSQL;
                                                  $ResultM = $myADb->ExecuteQuery($strSQL);
                                                 
                                                    $MonthName = $ResultM->fields[0];
                                                    $html .= "<th ><strong>$MonthName</strong></th>";
                                                    $ResultM->MoveNext();
                                                }
                                                
                                                $html .= "<th ><strong>Year Total</strong></th>";
                                                
                                                $html .= "</tr>";
                                                $RowHeader=2;
                                        }
                                            
                                            $html .= "<tr>";
                                            
                                                $html .= "<td ><strong>$Year</strong></td>";
                                                
                                                    for($aIndex=1;$aIndex<=12;$aIndex++){
                                                    $MonthlyTotal=0;
                                                    if($aIndex>=1 && $aIndex<=9)
                                                            $UnitM = "0" . $aIndex;
                                                    else
                                                            $UnitM = $aIndex;
                                                            
                                                        $Month = $UnitM;
                                                        
                                                    
                                                    if($Month>=1 && $Month<=9 && strlen($Month)==1){
                                                        $TmpMonth = "0" . $Month;
                                                    }else{
                                                        $TmpMonth = $Month;
                                                    }
                                                    
                                                     $CompleteDate = "$Year-$Month";
                                                    
                                                     
                                                     $InvoiceType = "PVPL" . $Month . substr($Year,2,2);
                                                     
                                                     
                                                     
                                                     $strSQL = "SELECT SUM(CEIL(Billseconds/60)) FROM  cdrs  WHERE OID=\"$pUID\" 
                                                     AND disposition=\"ANSWER\" AND YEAR(callstart)=$Year AND MONTH(callstart)=$Month";

                                                    #    print "<br>$strSQL";
                                                     $ResultA = $myADb->ExecuteQuery($strSQL);
                                                     
//                                                     $strSQL = "select sum(Amount) from transaction where 
//                                                     date like '$Year-$Month%' and Type=\"ACTV\" and OID=\"$pUID\" and isdeleted=0 and iscredit=1 ";                                                     
//                                                    #    print "<br>$strSQL";
//                                                     $ResultAC = $myADb->ExecuteQuery($strSQL);
                                                     
                                                     $AmountT = $ResultA->fields[0];

                                                    // $AmountAC = $ResultAC->fields[0];
                                                    // $AmountT = $Amount + $AmountAC;
                                                        $MonthlyTotal = $MonthlyTotal + $AmountT;
                                                         
                                                    
                                                    $YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
                                                    
                                                    
                                                    if($AmountT<=0)
                                                    $html .= "<td>0</td>";
                                                    
                                                    if($AmountT>0)
                                                    $html .= "<td>
                                                    <a href=\"DIDMinutesReport?month=$Month&year=$Year\">" . $AmountT . "</a></td>";
                                                    
                                                }
                                                
                                                $html .= "<td  align=left>
                                                ". $YearTotalAmount . "</td>";
                                                $MyTotal = $MyTotal + $YearTotalAmount;
                                                $html .= "</tr>";   }
        
        $html .= "<tr bgcolor=\"#F9F9E8\"><td><strong>Total</strong></td>
        <td colspan= 13 align=left><strong>" . $MyTotal . "</strong></td></tr>";
        $html .= "</table>";                    
        $html.="</div>
            ";
                            
    return $html;

}



function GetSummaryInvoicesYearly($pUID) {
	
	$myADb = new ADb();
	$ThisYear=$this->GetThisYear();
	

	
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	
    $bgcolor="grey";
    
    $html="<div class=\"onecolumn\">
            
                
                ";
    $html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\">  
    		<div class=\"header\">
                    <span><h3><b>Invoices Made By DIDx (USD)</b> </h3></span>
            </div>";
	$MyTotal="";

			
			
			
			$RowHeader=1;
			
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									
									
									if($RowHeader==1){
										
												$html .="<tr class=\"$bgcolor\">";
                                                $html .="<th >&nbsp;</th> ";	
												$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
													#print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th><strong>$MonthName</strong></th>";
													$ResultM->MoveNext();
												}
												
												$html .= "<th><strong>Year Total</strong></th>";
												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td ><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 
													 $InvoiceType = "PVPL" . $Month . substr($Year,2,2);
													 
													 
													 
													 $strSQL = "select sum(Amount) from transaction where 
													 
													 date like '$Year-$Month%' and OID=\"$pUID\" and isdeleted=0 and iscredit=1
													 and
						(type !=\"PPCC\" and type !=\"PPWU\" and type !=\"PPBC\" and type !=\"PPOP\" and type !=\"PPWT\" and type !=\"PPAH\" 
	or type !=\"PPZR\" and type !=\"PPBP\" and type !=\"PPPP\"  ) 
													  ";													 
													#	print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 
//													 $strSQL = "select sum(Amount) from transaction where 
//													 date like '$Year-$Month%' and Type=\"ACTV\" and OID=\"$pUID\" and isdeleted=0 and iscredit=1 ";													 
//													#	print "<br>$strSQL";
//													 $ResultAC = $myADb->ExecuteQuery($strSQL);
													 
													 $AmountT = $ResultA->fields[0];
													// $AmountAC = $ResultAC->fields[0];
													// $AmountT = $Amount + $AmountAC;
														$MonthlyTotal = $MonthlyTotal + $AmountT;
														 
													
													$YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
													
													
													if($AmountT<=0)
													$html .= "<td>
                                                    " . number_format($AmountT,2) . "</td>";;
													
													if($AmountT>0)
													$html .= "<td>
                                                    <a href=\"InvoicesMadeByDIDx?month=$Month&year=$Year\">" . number_format($AmountT,2) . "</a></td>";
													
												}
												
												$html .= "<td align=left>
                                                <font face=verdana size=-2>\$" . number_format($YearTotalAmount,2) . "</td>";
                                                $MyTotal = $MyTotal + $YearTotalAmount;
                                                $html .= "</tr>";
												
												
												
												
												
		}
		
		$html .= "<tr bgcolor=\"#F9F9E8\"><td>Total</td>
        <td colspan= 13 align=left>\$" . number_format($MyTotal,2) . "</td></tr>";
        $html .= "</table>";
        $html .= "</div>
                    </div></div>
												";
							
	return $html;

}


function GetSummaryYearlyPurchasedDIDs($pUID) {
	
	$myADb = new ADb();
	$ThisYear=$this->GetThisYear();
	

	
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	
	
    $html="<div class=\"onecolumn\">
            
     
                ";	$MyTotal="";


			
			
			
			$RowHeader=1;
			$html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\">
			<div class=\"header\">
                    <h3><span><b>Purchased DIDs<b></span><h3>
                </div>";
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									
									
									if($RowHeader==1){
										
												$html .= "<tr class=\"grey\">";
										$html .= "<th >&nbsp;</th>";	
												#$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
												#	print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th width=\"40\" style=\"width:8%\">$MonthName</th>";
													$ResultM->MoveNext();
												}
												
												$html .= "<th >Year Total</th>";
												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													#$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 
													 
													 $strSQL = "select count(DIDNumber) from DIDS where 
													 iPurchaseddate like '$Year-$Month%' and Status=2 and BOID=\"$pUID\" 
													 
													  ";
													 
													#	print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 $Amount = $ResultA->fields[0];
														$MonthlyTotal = $MonthlyTotal + $Amount;
														 
													
													$YearTotalAmount = $YearTotalAmount + $Amount;
													
													if($Amount>0)
													$html .= "<td>
                                                    
                                                    <a href=\"PurchasedDIDsMonthly?month=$UnitM&year=$Year\">" . number_format($Amount) . "($MonthlyTotal)</a></td>";
													
													if($Amount<=0)
													$html .= "<td>" . number_format($Amount) . "($MonthlyTotal)</td>";
													
												}
												
												$html .= "<td align=left>
                                                " . number_format($YearTotalAmount) . "</td>";
												$MyTotal = $MyTotal + $YearTotalAmount;
											#	echo $html;
												$html .= "</tr>";
												
												
												
		}
		
			$html .= "<tr bgcolor=\"#F9F9E8\"><td><strong>Total DIDs Purchased</strong></td>
            <td colspan= 13 align=left>" . number_format($MyTotal) . "</td></tr>
            ";
            $html .="</table>";
            $html.="</div>
            </div></div>";
		
		
		
							
	return $html;

}

function GetSummaryDIDRefundYearly($pUID) {
	
	$myADb = new ADb();
	$ThisYear=$this->GetThisYear();
	

	// $AllYearTotal="";
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	
	
	$html="<div class=\"onecolumn\">";
		
	$MyTotal="";

			
			
			
			$RowHeader=1;
			$html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\">
			<div class=\"header\">
                    <span><h3><b>Refunds for Non Working DIDs (USD)</b></h3> </span>
                </div>";
			
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									
									
									if($RowHeader==1){
										
												$html .= "<tr class=\"grey\">";
                                        $html .= "<th width=\"43\" style=\"width:10px\">&nbsp;</th>";	
												$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
													#print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th width=\"40\" style=\"width:8%\"><strong>$MonthName</strong></th>";
													$ResultM->MoveNext();
												}
												
												$html .= "<th width=\"109\" style=\"width:45%\"><strong>Year Total</strong></th>";
												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 
													 $InvoiceType = "TRFD" . $Month . substr($Year,2,2);
													 
													 
													 
													 $strSQL = "select sum(Amount) from transaction where 
													 
													 date like '$Year-$Month%' and OID=\"$pUID\" and isdeleted=0 and iscredit=0
													 and
						(type like  '$InvoiceType%' ) 
													  ";													 
													#	print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 
//													 $strSQL = "select sum(Amount) from transaction where 
//													 date like '$Year-$Month%' and Type=\"ACTV\" and OID=\"$pUID\" and isdeleted=0 and iscredit=1 ";													 
//													#	print "<br>$strSQL";
//													 $ResultAC = $myADb->ExecuteQuery($strSQL);
													 
													 $AmountT = $ResultA->fields[0];
													// $AmountAC = $ResultAC->fields[0];
													// $AmountT = $Amount + $AmountAC;
														$MonthlyTotal = $MonthlyTotal + $AmountT;
														 
													
													$YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
													
													
													if($AmountT<=0)
													$html .= "<td>
                                                    " . number_format($AmountT,2) . "</td>";
													
													if($AmountT>0)
													$html .= "<td>
                                                    <a href=\"ClientDIDRefundReport?month=$Month&year=$Year\">" . number_format($AmountT,2) . "</a></td>";
													
												}
												
												$html .= "<td>\$" . number_format($YearTotalAmount,2) . "</td>";
												$MyTotal = $MyTotal + $YearTotalAmount;
												$html .= "</tr>";
												
												
												
												
												
		}
		
		$html .= "<tr bgcolor=\"#F9F9E8\"><td>Total</td>
        <td colspan= 13 align=right>\$" . number_format($MyTotal,2) . "</td></tr>";
        $html .= "</table>";
        $html .= "</div>
            </div>";
							
	return $html;

}

//PAYMENT MADE TO DIDX
function GetSummaryYearlyEarning($pUID) {
	
	$myADb = new ADb();
	$ThisYear=$this->GetThisYear();
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	
	
    $html="<div class=\"onecolumn\">
            <div class=\"header\">
                    <h2><span>Payments made by DIDX to you </span></h2>
                </div>
                ";		
			
			
			$RowHeader=1;
			$html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\">";
			
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									
									
									if($RowHeader==1){
										
										$html .= "<tr class=\"grey\">";
                                        $html .= "<th width=\"43\" style=\"width:10px\">&nbsp;</th>";    	
												$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
													#print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th width=\"40\" style=\"width:8%\"><strong>$MonthName</strong></th>";
													$ResultM->MoveNext();
												}
												
												$html .= "<th width=\"109\" style=\"width:45%\"><strong>Year Total</strong></th>";
												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td ><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 
													 
													 $strSQL = "select sum(Amount) from transaction where 
													 date like '$Year-$Month%' and OID=\"$pUID\" and
													 	(type =\"PPCC\" or type =\"PPWU\" or type =\"PPBC\" or type =\"PPOP\" or type =\"PPWT\" or type =\"PPAH\" 
	or type =\"PPZR\" or type =\"PPBP\" or type =\"PPPP\"   or type =\"PPAT\") and isdeleted=0 and iscredit=1
													 
													  ";
													 
														#print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 $Amount = $ResultA->fields[0];
														$MonthlyTotal = $MonthlyTotal + $Amount;
														 
													
													$YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
													
													if($Amount>0)
													$html .= "<td>
                                                    <a href=\"PaymentMadeByDIDx?month=$Month&year=$Year\">" . number_format($Amount,2) . "</a></td>";
													
													if($Amount<=0)
													$html .= "<td>
                                                    " . number_format($Amount,2) . "</td>";
													
												}
												
												$html .= "<td align=right>\$" . number_format($YearTotalAmount,2) . "</td>";
												$AllYearTotal = $AllYearTotal + $YearTotalAmount;
												$html .= "</tr>";
												
												
												
		}
		
		$html.="<tr bgcolor=\"#F9F9E8\"><td colspan=14><div align=right>\$" . number_format($AllYearTotal,2) . "</div></td></td></tr>";
        $html.="</table>";
        $html.="</div>
            </div>";
	return $html;

}



//PAYMENR MADE TO DIDX
//
function GetMinutesReport($pUID) {
    
    $myADb = new ADb();
    $ThisYear=$this->GetThisYear();
    $RowHeader=0;
    $MonthlyTotal=0;
    $YearTotalAmount=0;
    
    
    $html="<div class=\"onecolumn\">
            <div class=\"header\">
                   <h2><span>Minutes Report</span></h2>
                </div>
                <br class=\"clear\"/>
                ";
        
    $MyTotal="";

            
            
            
            $RowHeader=1;
            $html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\">";
            
                            for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
                                    
                                    $Year = $nIndex;
                                    $YearTotalAmount= 0;
                                    
                                    
                                    
                                    if($RowHeader==1){
                                        
                                        $html .= "<tr class=\"grey\">";
                                        $html .= "<th width=\"43\" style=\"width:10px\">&nbsp;</th>";       
                                                $MonthlyTotal = 0;
                                            for($aIndex=1;$aIndex<=12;$aIndex++){
                                                
                                                    if($aIndex>=1 && $aIndex<=9)
                                                            $UnitM = "0" . $aIndex;
                                                    else
                                                            $UnitM = $aIndex;
                                                            
                                                    $strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
                                                    #print $strSQL;
                                                  $ResultM = $myADb->ExecuteQuery($strSQL);
                                                 
                                                    $MonthName = $ResultM->fields[0];
                                                    $html .= "<th width=\"40\" style=\"width:8%\"><strong>$MonthName</strong></th>";
                                                    $ResultM->MoveNext();
                                                }
                                                
                                                $html .= "<th width=\"109\" style=\"width:45%\"><strong>Year Total</strong></th>";
                                                
                                                $html .= "</tr>";
                                                $RowHeader=2;
                                        }
                                            
                                            $html .= "<tr>";
                                            
                                                $html .= "<td><strong>$Year</strong></td>";
                                                
                                                    for($aIndex=1;$aIndex<=12;$aIndex++){
                                                    $MonthlyTotal=0;
                                                    if($aIndex>=1 && $aIndex<=9)
                                                            $UnitM = "0" . $aIndex;
                                                    else
                                                            $UnitM = $aIndex;
                                                            
                                                        $Month = $UnitM;
                                                        
                                                    
                                                    if($Month>=1 && $Month<=9 && strlen($Month)==1){
                                                        $TmpMonth = "0" . $Month;
                                                    }else{
                                                        $TmpMonth = $Month;
                                                    }
                                                    
                                                     $CompleteDate = "$Year-$Month";
                                                    
                                                     
                                                     $InvoiceType = "PVPL" . $Month . substr($Year,2,2);
                                                     
                                                     
                                                     
                                                     $strSQL = "SELECT SUM(CEIL(Billseconds/60)) FROM  cdrs  WHERE Vendor=\"$pUID\" 
                                                     AND disposition=\"ANSWER\" AND YEAR(callstart)=$Year AND MONTH(callstart)=$Month";
                                                    #    print "<br>$strSQL";
                                                     $ResultA = $myADb->ExecuteQuery($strSQL);
                                                     
//                                                     $strSQL = "select sum(Amount) from transaction where 
//                                                     date like '$Year-$Month%' and Type=\"ACTV\" and OID=\"$pUID\" and isdeleted=0 and iscredit=1 ";                                                     
//                                                    #    print "<br>$strSQL";
//                                                     $ResultAC = $myADb->ExecuteQuery($strSQL);
                                                     
                                                     $AmountT = $ResultA->fields[0];
                                                    // $AmountAC = $ResultAC->fields[0];
                                                    // $AmountT = $Amount + $AmountAC;
                                                        $MonthlyTotal = $MonthlyTotal + $AmountT;
                                                         
                                                    
                                                    $YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
                                                    
                                                    
                                                    if($AmountT<=0)
                                                    $html .= "<td>0</td>";
                                                    
                                                    if($AmountT>0)
                                                    $html .= "<td>
                                                    <a href=\"DIDMinutesReport?month=$Month&year=$Year\">" . $AmountT . "</a></td>";
                                                    
                                                }
                                                
                                                $html .= "<td  align=right>
                                                ". $YearTotalAmount . "</td>";
                                                $MyTotal = $MyTotal + $YearTotalAmount;
                                                $html .= "</tr>";
                                                
                                                
                                                
                                                
                                                
        }
        
        $html .= "<tr bgcolor=\"#F9F9E8\"><td><strong>Total</strong></td>
        <td colspan= 13 align=right><strong>" . $MyTotal . "</strong></td></tr>";
        $html .= "</table>";                    
        $html.="</div>
            </div>";
                            
    return $html;

}


//


function GetSummaryDIDPaymentsToVendor($pUID) {
	
	 $myADb = new ADb();
	$ThisYear=$this->GetThisYear();
	
	

	
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	$AllYearTotal=0;
	
	
	$html="<div class=\"onecolumn\">
            <div class=\"header\">
                    
                </div>
                <br class=\"clear\"/>
                
                	<span><h2>Payment invoices  made by DIDX for your sales</h2></span>
                ";
		


			
			
			
			$RowHeader=1;
			$html .= "<div class=\"table table-responsive\"><table class=\"table table-striped\">";
			
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									
									
									if($RowHeader==1){
										
												$html .= "<tr class=\"grey\">";
                                                $html .= "<th>&nbsp;</th>";    
												$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
													#print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th ><strong>$MonthName</strong></th>";
													$ResultM->MoveNext();
												}
												
												$html .= "<th ><strong>Year Total</strong></th>";
												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 $TypeDate = "PAY" . $Month . substr($Year,2,2);
													 
													 $strSQL = "select sum(Amount) from transaction where 
													 (type = '$TypeDate' or type='ACTP') and date like '$Year-$Month%' and OID=\"$pUID\" and
													 	isdeleted=0 and iscredit=0
													 
													  ";
													 
														#print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 $Amount = $ResultA->fields[0];
														$MonthlyTotal = $MonthlyTotal + $Amount;
														 
													
													$YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
													
													if($Amount<=0)
													$html .= "<td>" . number_format($Amount,2) . "</td>";
													
													if($Amount>0)
													$html .= "<td>
                                                    <a href=\"PaymentInvocesSales?month=$Month&year=$Year\">
                                                    " . number_format($Amount,2) . "</a></td>";
													
												}
												
							$html .= "<td><div align=left>\$" . number_format($YearTotalAmount,2) . "</div></td>";
												$AllYearTotal = $AllYearTotal + $YearTotalAmount;
												$html .= "</tr>";
												
												
												
		}
		
		$html.="<tr bgcolor=\"#F9F9E8\"><td colspan=14><div align=left><b>Total:</b>&nbsp;&nbsp;\$" . number_format($AllYearTotal,2) . "</div></td></td></tr>";
        $html.="</table>";
        $html.="</div>
            </div></div>";
		
							
	return $html;

}



function GetSummaryCreditIssueYearly($pUID) {
	
	$myADb = new ADb();
	$ThisYear=$this->GetThisYear();;
	

	
	$RowHeader=0;
	$MonthlyTotal=0;
	$YearTotalAmount=0;
	
	
	$html="    <div class=\"onecolumn\">
            
                 ";
		
	$MyTotal="";

			
			
			
			$RowHeader=1;
			$html .= "<table class=\"table table-striped\">
			<div class=\"header\">
                    <span><h3><b>Credit Issued(USD) </b></h3></span>
                </div>";
			
							for($nIndex=2005;$nIndex<=$ThisYear;$nIndex++){
									
									$Year = $nIndex;
									$YearTotalAmount= 0;
									
									
									
									if($RowHeader==1){
										
										$html .= "<tr class=\"grey\">";
                                        $html .= "<th width=\"43\" style=\"width:10px\">&nbsp;</th>";    	
												$MonthlyTotal = 0;
											for($aIndex=1;$aIndex<=12;$aIndex++){
												
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
													$strSQL = "select date_format(concat('2001-',\"$UnitM\",'-01'),'%b')";
													#print $strSQL;
												  $ResultM = $myADb->ExecuteQuery($strSQL);
												 
													$MonthName = $ResultM->fields[0];
													$html .= "<th width=\"40\" style=\"width:8%\"><strong>$MonthName</strong></th>";
													$ResultM->MoveNext();
												}
												
												$html .= "<th width=\"109\" style=\"width:45%\"><strong>Year Total</strong></th>";
												
												$html .= "</tr>";
												$RowHeader=2;
										}
											
											$html .= "<tr>";
											
												$html .= "<td><strong>$Year</strong></td>";
												
													for($aIndex=1;$aIndex<=12;$aIndex++){
													$MonthlyTotal=0;
													if($aIndex>=1 && $aIndex<=9)
															$UnitM = "0" . $aIndex;
													else
															$UnitM = $aIndex;
															
														$Month = $UnitM;
														
													
													if($Month>=1 && $Month<=9 && strlen($Month)==1){
														$TmpMonth = "0" . $Month;
													}else{
														$TmpMonth = $Month;
													}
													
													 $CompleteDate = "$Year-$Month";
													
													 
													 $InvoiceType = "PVPL" . $Month . substr($Year,2,2);
													 
													 
													 
													 $strSQL = "select sum(Amount) from transaction where 
													 
													 date like '$Year-$Month%' and OID=\"$pUID\" and isdeleted=0 and iscredit=0
													 and
						(type =\"DISC\" or type =\"ADIS\" or type =\"MFVW\" ) 
													  ";													 
													#	print "<br>$strSQL";
													 $ResultA = $myADb->ExecuteQuery($strSQL);
													 
//													 $strSQL = "select sum(Amount) from transaction where 
//													 date like '$Year-$Month%' and Type=\"ACTV\" and OID=\"$pUID\" and isdeleted=0 and iscredit=1 ";													 
//													#	print "<br>$strSQL";
//													 $ResultAC = $myADb->ExecuteQuery($strSQL);
													 
													 $AmountT = $ResultA->fields[0];
													// $AmountAC = $ResultAC->fields[0];
													// $AmountT = $Amount + $AmountAC;
														$MonthlyTotal = $MonthlyTotal + $AmountT;
														 
													
													$YearTotalAmount = $YearTotalAmount + $MonthlyTotal;
													
													
													if($AmountT<=0)
													$html .= "<td>
                                                    " . number_format($AmountT,2) . "</td>";
													
													if($AmountT>0)
													$html .= "<td>
                                                    <a href=\"ClientCreditIssuedReport?month=$Month&year=$Year\">" . number_format($AmountT,2) . "</a></td>";
													
												}
												
												$html .= "<td>\$" . number_format($YearTotalAmount,2) . "</td>";
												$MyTotal = $MyTotal + $YearTotalAmount;
												$html .= "</tr>";
												
												
												
												
												
		}
		
		$html .= "<tr bgcolor=\"#F9F9E8\"><td><strong>Total</strong></td>
        <td colspan= 13 align=right>
        \$" . number_format($MyTotal,2) . "</td></tr>
                                                ";
        $html .="</table>";    
        $html .=" </div>
            </div>";            
							
	return $html;

}



  //Yearly Report end   


//for seller reports
	function PayPhone()
	{
		$myADb=new ADb();
		$UID=currentUser();
		$strSQL = "select PayPhoneLogs.ID,PayPhoneLogs.CallerID,DID,Company
		,CallerID2,ChannelID,SipCallID,ChannelID1,CMD,ChannelID2,
		PayPhoneLogs.Date,Date2,Date3,Duration,Duration2,
		Disposition,Day,Month,Year,IsBilled,DIDS.BOID from 
		PayPhoneLogs,DIDS where DIDS.DIDNumber = concat(\"1\",
		PayPhoneLogs.DID) and BOID=\"$UID\" and Status=2 and
		(PayPhoneLogs.callerid like '27%' or PayPhoneLogs.callerid 
		like '70%') and length(PayPhoneLogs.callerid)=12 order by Date 
		desc limit 0,50";
		
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
	}

	function getCountry($ffOID)
	{
		$myADb=new ADb();
		$strSQL ="select CountryCode,DIDCountries.Description,count(DIDNumber) from DIDS,DIDCountries,DIDArea 
					where DIDS.AreaID=DIDArea.Id and DIDCountries.ID=DIDArea.CountryID and DIDS.OID=\"$ffOID\" 
					group by DIDCountries.CountryCode";

		$Result = $myADb->ExecuteQuery($strSQL);
		while(!$Result->EOF){
		$CountryCode =$Result->fields[0];
		$CountryName = $Result->fields[1];

		$htmlList .= "<option value=\"$CountryCode\">$CountryCode - $CountryName</option>";
		
		$Result->MoveNext();
	}
		return $htmlList;		

	}


	function getData($pCountryCode,$ffOID)
	{
		$myADb=new ADb();
		if ($pCountryCode==1)
		{
			$strSQL ="select CountryCD,CountryN,AreaCD,City,count(DIDNumber) as TotalDID,DIDS.AreaID,RCenter,NXX
					from DIDS where CountryCD=\"$pCountryCode\" and DIDS.OID=\"$ffOID\" group by 
					DIDS.RCenter order by CountryCD,AreaCD,RCenter";	
		}else{
				  $strSQL ="select CountryCD,CountryN,AreaCD,City,count(DIDNumber) as TotalDID,DIDS.AreaID,RCenter,NXX from DIDS where CountryCD=\"$pCountryCode\" and DIDS.OID=\"$ffOID\"group by DIDS.AreaID order by CountryCD,AreaCD";
		}
		


    $Result = $myADb->ExecuteQuery($strSQL);
    if($Result->fields[6] == "" and $Result->fields[7] == "" and $pCountryCode == '1')
			{
			    $strSQL ="select CountryCD,CountryN,AreaCD,City,count(DIDNumber) as TotalDID,DIDS.AreaID,RCenter,NXX from DIDS where  CountryCD=\"$pCountryCode\" and DIDS.OID=\"$ffOID\" group by DIDS.AreaID order by CountryCD,AreaCD,RCenter";
			 
			}
		
	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;

	}

	function GetPaymentLogsForSMSTalkTime()	{

		$myADb=new ADb();
		$UID=currentUser();
    
	$strSQL = " select Payment,date_format(Date,\"%d-%b-%Y\")as Date,PaymentTill,User,TotalSMS,id from SMSPayment where oid=\"$UID\" ";

	$Result = $myADb->ExecuteQuery($strSQL);
	return $Result;
    }


    function GetRequestedHistory()
        {
		    $myADb=new ADb();
			$UID=currentUser();
			$strSQL = " select date_format(concat(Year,'-',Month,'-01'),'%b-%Y') as Date1,amount,SMS,date_format(date,'%d-%b-%Y') as Date2,Status,ID from ReqSMSPayment where oid=\"$UID\" order by date asc";

		    $Result = $myADb->ExecuteQuery($strSQL);
		    return $Result;

	    }

	  function Getsumpayment()
	  { 
	  	    $myADb=new ADb();
			$UID=currentUser();
			$strSQL = "select SUM(Payment) AS Amount from SMSPayment where oid=\"$UID\"";
			$Result = $myADb->ExecuteQuery($strSQL);
			$Result = $Result->fields[0];
		    return $Result; 
	  }

	function GetYears($pYear)
		{
		    $myADb= new ADb();
			$html="";
			$strSQL = "select year(curdate())";
			$Result = $myADb->ExecuteQuery($strSQL);
			$CurYear = $Result->fields[0];
			for($nIndex=2005;$nIndex<=$CurYear;$nIndex++)
		    {
		        $html .= "<option value='$nIndex'>$nIndex</option>";
		    }
		    if($pYear != '')
		    {
		        $Pat        = "'$pYear'>";
		        $Rep        = "'$pYear' selected>";
		        $html = str_replace($Pat,$Rep,$html);
		    }
		    else
		    {
		        $Pat        = "'$CurYear'>";
		        $Rep        = "'$CurYear' selected>";
		        $html = str_replace($Pat,$Rep,$html);
		    }
		    return $html;
			
		}


	function GetAllMonths($pMonth) 
		{
			$html	= "<option value='01'>January-01</option>";
			$html	.= "<option value='02'>February-02</option>";
			$html	.= "<option value='03'>March-03</option>";
			$html	.= "<option value='04'>April-04</option>";
			$html	.= "<option value='05'>May-05</option>";
			$html	.= "<option value='06'>June-06</option>";
			$html	.= "<option value='07'>July-07</option>";
			$html	.= "<option value='08'>August-08</option>";
			$html	.= "<option value='09'>September-09</option>";
			$html	.= "<option value='10'>October-10</option>";
			$html	.= "<option value='11'>November-11</option>";
			$html	.= "<option value='12'>December-12</option>";
			if ($pMonth != '') {

				$Pat		= "'$pMonth'>";
				$Rep		= "'$pMonth' selected>";
			//	$html	=~ s/$Pat/$Rep/;
			$html = str_replace($Pat,$Rep,$html);
			}

			return $html;
		}

	function GetCurDate($Month,$Year){
	
	        $myADb= new ADb();
		    $strSQL = "select substring(curdate(),1,4),substring(curdate(),6,2)";
		    $Result = $myADb->ExecuteQuery($strSQL);
		    $ffMonth = $Result->fields[1];
		    $ffYear  = $Result->fields[0];
			 if($ffMonth==$Month && $Year==$ffYear){
				
				return -1;
				
			}
	}

	function GetStatus1($Month,$Year){
		$myADb= new ADb();
		$UID=currentUser();
			$strSQL = "select Amount,SMS,date_format('$Year-$Month-01','%b-%Y') as Date,ID from ReqSMSPayment where OID='$UID' and Month=\"$Month\"  and Year=\"$Year\" and status=1";
	    $Result = $myADb->ExecuteQuery($strSQL);
	    return $Result;


    }

    function GetTrans($TTPV)
    {
    	$myADb= new ADb();
		$UID=currentUser();
    	$strSQL = "select * from transaction where OID='$UID' and Type=\"$TTPV\" and isdeleted=0 ";
    	$Result = $myADb->ExecuteQuery($strSQL);
	    return $Result;

    }

    function  GetStatus0($Month,$Year){

    		$myADb= new ADb();
		    $UID=currentUser();
			$strSQL = "select Amount,SMS,date_format('$Year-$Month-01','%b-%Y') as Date,ID from ReqSMSPayment where
			 OID='$UID' and Month=\"$Month\"  and Year=\"$Year\" and status=0";

			$Result = $myADb->ExecuteQuery($strSQL);
			return $Result;
    }

    function GetDataStatus2($Month,$Year){
    	$myADb= new ADb();
		$UID=currentUser();
    	$strSQL = "select Amount,SMS,date_format('$Year-$Month-01','%b-%Y') as Date,ID from ReqSMSPayment where
             OID='$UID' and Month=\"$Month\"  and Year=\"$Year\" and status=2";

            $Result = $myADb->ExecuteQuery($strSQL);
            return $Result;
    }

    function TotalSoldbyCCode($ffOID,$AreaID,$Rcenter)
    {
    	$myADb= new ADb();
    	$strSQL ="select Count(DIDNumber) from DIDS where AreaID = \"$AreaID\" and RCenter=\"$Rcenter\"  and Status=2 and OID=\"$ffOID\"";

            $ResultSold = $myADb->ExecuteQuery($strSQL);
            return $TotalSold = $ResultSold->fields[0];
    }
    function TotalSolds($ffOID,$AreaID)
    {
    	$myADb= new ADb();
    	$strSQL ="select Count(DIDNumber) from DIDS where AreaID = \"$AreaID\" and Status=2 and OID=\"$ffOID\"";
					#print "\$strSQL : $strSQL ";
			$ResultSold = $myADb->ExecuteQuery($strSQL);
			return $TotalSold = $ResultSold->fields[0];
    }

    function TotalAvailbyCCode($ffOID,$AreaID,$Rcenter)
    {
    	$myADb= new ADb();
    	$strSQL ="select Count(DIDNumber) from DIDS where AreaID = \"$AreaID\" and RCenter=\"$Rcenter\" and Status=0 and OID=\"$ffOID\"";

            $ResultAvail = $myADb->ExecuteQuery($strSQL);
            return $TotalAvail = $ResultAvail->fields[0];
    }

    function TotalAvails($ffOID,$AreaID)
    {
    	$myADb= new ADb();
    	$strSQL ="select Count(DIDNumber) from DIDS where AreaID = \"$AreaID\" and Status=0 and OID=\"$ffOID\"";
            $ResultAvail = $myADb->ExecuteQuery($strSQL);
            return $TotalAvail = $ResultAvail->fields[0];
    }

    function InsertSMSPayment($Month,$Year){
    	$myADb= new ADb();
		$UID=currentUser();
	    $strSQL = "insert into ReqSMSPayment(OID,Month,Year)values(\"$UID\", \"$Month\", \"$Year\")";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;        
    }

    function GetdataforWithdraw($ID)
    {	
    	$myADb= new ADb();
		$UID=currentUser();
    	$strSQL = "select Status,Amount,SMS,date_format(concat(Year,'-',Month,'-01'),'%b-%Y') as Date,month,year,concat(Year,'-',Month,'-01') as Date1 from ReqSMSPayment where ID=\"$ID\" ";
        $Result = $myADb->ExecuteQuery($strSQL);
        return $Result;
    }

    function SaveLogForThisPayment($pPayment,$pTill,$pPaymentFrom,$pTotalSMS,$ID) {
	
		$myADb= new ADb();
		$UID=currentUser();
		
		$strSQL = " select * from SMSPayment where oid=\"$UID\" ";
	    $Result = $myADb->ExecuteQuery($strSQL);
	    $strSQL = " Update ReqSMSPayment set Status=2 where id=\"$ID\" ";
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$strSQL = " insert into SMSPayment(OID,Payment,Date,PaymentTill,User,PaymentFrom,TillMonth,TotalSMS)
								values(\"$UID\",\"$pPayment\",curdate(),\"$pTill\",\"$UID\",\"$pPaymentFrom\",\"$pProperTill\",\"$pTotalSMS\") ";
		#print "\$strSQL: $strSQL";
		$Result = $myADb->ExecuteQuery($strSQL);
	                                       
   }  

   function GetYearss($pYear) {
    $html = '';
    for($nIndex=2004;$nIndex<=$pYear;$nIndex++) {
      if($pYear == $nIndex) {
        $selected = " selected ";
      }else{
        $selected = "  ";
      }
    $html .= "<option value=$nIndex $selected>$nIndex</option>";
    }
    $html .= "<option value='%' >ALL</option>";
    return $html;
  }

  function GetCurrentDateTemp(){
	
	$myADb=new ADb();
	
	$strSQL = "select curdate() ";
	$Result = $myADb->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
	
	}

	//PURCHASED DID MONTHLY

	function GetEarningReport($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		$html="";
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$strSQL = "select count(*) from DIDS where status=2 and
							BOID=\"$pUID\" and ipurchaseddate like '$pYear-$pMonth%' ";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0]);
		
		
		$strSQL = "select DIDNumber,date_format(iPurchasedDate,'%d-%b-%Y'),
						DIDCountries.Description as a,DIDArea.Description,OurSetupCost,OurMonthlyCharges from DIDS,
						DIDCountries,DIDArea where status=2 and DIDCountries.id=DIDArea.countryid and DIDArea.id=DIDS.areaid
						and BOID=\"$pUID\" and ipurchaseddate like '$pYear-$pMonth%' order by iPurchasedDate desc limit 0,100
							";
	#	echo $strSQL;
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$bgcolor = "grey";
		
		while(!$Result->EOF){
			
			$Sno++;
			
			$DID = $Result->fields[0];
			$Date = $Result->fields[1];
			$Country = $Result->fields[2];
			$Area = $Result->fields[3];
			$Setup = $Result->fields[4];
			$Monthly = $Result->fields[5];
			
			
			$ThisPageTotal = $ThisPageTotal + $Sno;
			
			
			if($bgcolor=="grey")
					$bgcolor="";		
			else
					$bgcolor="grey";		
					
			$html .= " <tr class=$bgcolor>
	                <td >$Sno</td> 
	                <td><DIV >$DID</DIV></td>
	                <td ><DIV >$Date</DIV></td>
	                <td ><DIV >$Country</DIV></td>
	                <td ><DIV >$Area</DIV></td>
	                <td ><DIV >$Setup</DIV></td>
	                <td ><DIV >$Monthly</DIV></td>
	                </tr>";
				
			
			
			
			$Result->MoveNext();
			
		}
		
		
		return $html;
		
	}

	function GetGrandTotalPur($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		$html="";
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$strSQL = "select count(*) from DIDS where status=2 and
							BOID=\"$pUID\" and ipurchaseddate like '$pYear-$pMonth%' ";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0]);
		return $GrandTotalAmount;
	}

	function ThisPagePur($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		$html="";
		$Sno="0";
		$GrandTotalAmount="0";
		$ThisPageTotal="0";
		
		$strSQL = "select count(*) from DIDS where status=2 and
							BOID=\"$pUID\" and ipurchaseddate like '$pYear-$pMonth%' ";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0]);
		
		
		$strSQL = "select DIDNumber,date_format(iPurchasedDate,'%d-%b-%Y'),
						DIDCountries.Description as a,DIDArea.Description,OurSetupCost,OurMonthlyCharges from DIDS,
						DIDCountries,DIDArea where status=2 and DIDCountries.id=DIDArea.countryid and DIDArea.id=DIDS.areaid
						and BOID=\"$pUID\" and ipurchaseddate like '$pYear-$pMonth%' order by iPurchasedDate desc limit 0,100
							";
		// echo $strSQL;

		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$bgcolor = "grey";
		
		while(!$Result->EOF){
			
			$Sno++;

			$DID = $Result->fields[0];
			$Date = $Result->fields[1];
			$Country = $Result->fields[2];
			$Area = $Result->fields[3];
			$Setup = $Result->fields[4];
			$Monthly = $Result->fields[5];
			
			
			$ThisPageTotal = $ThisPageTotal + $Sno;
		
			$Result->MoveNext();
			
		}
		
		
		return $ThisPageTotal;
		
	}

	function GetFormattedDate($pDate,$p){
	
	        $myADb=new ADb();
	
			if($p==1)
			$strSQL = "select date_format(\"$pDate\",'%b-%Y') as Dateformat ";
			
			
			if($p==0)
			$strSQL = "select date_format(\"$pDate\",'%Y') as Dateformat ";

			$Result = $myADb->ExecuteQuery($strSQL);

			return $Result->fields[0];
	
	
    }

	//PURCHASED DID MONTHLY
	
	//REFUND REPORT

    function GetCreditReport($pUID,$pMonth,$pYear){
	
	        $myADb = new ADb();
	
			global $Sno;
			global $GrandTotalAmount;
			global $ThisPageTotal;
			
			$MyType = "TRFD" . $pMonth .substr($pYear,2,2) ;
			
			$strSQL = "select sum(amount) from transaction where 
								OID=\"$pUID\" and isdeleted=0 and iscredit=0 and
								(type like  '$MyType%' ) 
								and date like '$pYear-$pMonth%'
								";
			#	echo $strSQL;
			$Result = $myADb->ExecuteQuery($strSQL);
			
			$GrandTotalAmount = number_format($Result->fields[0],2);
			
			
			$strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
								OID=\"$pUID\" and isdeleted=0  and iscredit=0 and
									(type like  '$MyType%' ) 
								and date like '$pYear-$pMonth%'
								order by Date desc limit 0,100
								";
			#echo $strSQL;
			
			$Result = $myADb->ExecuteQuery($strSQL);
			
			$bgcolor = "#FFFFFF";
			
			while(!$Result->EOF){
				
				$Sno++;
				
				$Desc = $Result->fields[0];
				$Date = $Result->fields[1];
				$Amount = $Result->fields[2];
				$TypeList = $Result->fields[3];
				$TrID = $Result->fields[4];
				
				$ThisPageTotal = $ThisPageTotal + $Amount;
				
				
						$TyMode="Refund";
				
						
				#$TyMode = "Monthly DID Payment";

				// if($bgcolor=="grey")
				// 		$bgcolor="";		
				// else
				// 		$bgcolor="grey";		
						
				$html .= " <tr>
		                <td  width=\"275\" align=\"center\">$Sno</td>
		                <td width=\"275\" align=\"center\">$TrID</td>
		                <td width=\"275\" align=\"center\">$Date</td>
		                <td width=\"275\" align=\"center\">$TyMode</td>
		                <td width=\"275\" align=\"center\">$Desc</td>
		                <td width=\"275\" align=\"center\"><font size=-2>".number_format($Amount,2)."</td>
		                </tr>";
				
				$Result->MoveNext();
		    }
			return $html;
	
    }


    function GetGrandRefund($pUID,$pMonth,$pYear){
	
	        $myADb = new ADb();
	
			global $Sno;
			global $GrandTotalAmount;
			global $ThisPageTotal;
			
			$MyType = "TRFD" . $pMonth .substr($pYear,2,2) ;
			
			$strSQL = "select sum(amount) from transaction where 
								OID=\"$pUID\" and isdeleted=0 and iscredit=0 and
								(type like  '$MyType%' ) 
								and date like '$pYear-$pMonth%'
								";
			#	echo $strSQL;
			$Result = $myADb->ExecuteQuery($strSQL);
			
			$GrandTotalAmount = number_format($Result->fields[0],2);
			
			return $GrandTotalAmount;
			
	

    }

    function GetPagerefund($pUID,$pMonth,$pYear){
	
	        $myADb = new ADb();
	
			$Sno="0";
		
			$ThisPageTotal="0";
			
			
			
			$strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
								OID=\"$pUID\" and isdeleted=0  and iscredit=0 and
									(type like  '$MyType%' ) 
								and date like '$pYear-$pMonth%'
								order by Date desc limit 0,100
								";
			#echo $strSQL;
			
			$Result = $myADb->ExecuteQuery($strSQL);
			
			$bgcolor = "#FFFFFF";
			
			while(!$Result->EOF){
				
				$Sno++;
				
				$Desc = $Result->fields[0];
				$Date = $Result->fields[1];
				$Amount = $Result->fields[2];
				$TypeList = $Result->fields[3];
				$TrID = $Result->fields[4];
				
				$ThisPageTotal = $ThisPageTotal + $Amount;
				
			
				
				$Result->MoveNext();
		    }
			return $ThisPageTotal;
	
    }
	//REFUND REPORT

	//PAYMENT MADE TO DIDX
    function GetEarningReportPayment($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		
	    $html="";
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
	
	    $strSQL = "select sum(amount) from transaction where 
						OID=\"$pUID\" and isdeleted=0 and date like '$pYear-$pMonth%' and
													(type =\"PURC\" or type =\"ADWU\" or type =\"ADCH\" or type =\"ADOP\" or type =\"ADWT\" or type =\"ADAH\" 
	or type =\"ADZR\" or type =\"ADBP\" or type =\"ADIN\" or type =\"ADDB\"  or type =\"ADPP\" or type =\"ACPT\" or type =\"ADMB\")   and iscredit=0 ";
	
	#echo $strSQL ;
	
	   $Result = $myADb->ExecuteQuery($strSQL);
	
	   $GrandTotalAmount = number_format($Result->fields[0],2);
	
	
	    $strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
						OID=\"$pUID\" and isdeleted=0 and date like '$pYear-$pMonth%' and
													 (type =\"PURC\" or type =\"ADWU\" or type =\"ADCH\" or type =\"ADOP\" or type =\"ADWT\" or type =\"ADAH\" 
	or type =\"ADZR\" or type =\"ADBP\" or type =\"ADIN\" or type =\"ADDB\"  or type =\"ADPP\" or type =\"ACPT\" or type =\"ADMB\")  and iscredit=0 order by Date desc limit 0,100
						";
	#echo $strSQL;
	
	   $Result = $myADb->ExecuteQuery($strSQL);
	
	   $bgcolor = "grey";
	
	while(!$Result->EOF){
		
		$Sno++;
		
		$Desc = $Result->fields[0];
		$Date = $Result->fields[1];
		$Amount = $Result->fields[2];
		$TypeList = $Result->fields[3];
		$TrID = $Result->fields[4];
		
		$ThisPageTotal = $ThisPageTotal + $Amount;
		
		    if($TypeList=="ADPP"){
				$TyMode = "PayPal";
			}
			if($TypeList=="ADCH"){
				$TyMode = "Cheque";
			}
			if($TypeList=="ADWU"){
				$TyMode = "Western Union";
			}
			if($TypeList=="ADAH"){
				$TyMode = "ACH";
			}
			if($TypeList=="ADWT"){
				$TyMode = "Wire";
			}
			if($TypeList=="ADBP"){
				$TyMode = "BillPayment";
			}
			if($TypeList=="ADMB"){
				$TyMode = "MoneyBookers";
			}
			if($TypeList=="ADOP"){
				$TyMode = "Online Payment";
			}
			if($TypeList=="ADZR"){
				$TyMode = "Zollar";
			}

			if($TypeList=="PURC"){
				$TyMode = "Credit Card"	;
			}

			if($TypeList=="ADIN"){
				$TyMode = "Internal Sale"	;
			}

			if($TypeList=="ADDB"){
				$TyMode = "Direct Bank Deposit"	;
			}

			
				
		    $html .= " <tr>
                <td><div align=\"center\">$Sno</div></td>
                <td ><div align=\"center\">$TrID</div></td>
                <td><div align=\"center\"><font size=-2>$Date</div></td>
                <td><div align=\"center\">$TyMode</div></td>
                <td>$Desc</td>
                <td align=right><div align=\"center\">".number_format($Amount,2)."</div></td>
                </tr>";
			
		    $Result->MoveNext();
		}
	
	   return $html;
	}

	function GetThisPageTotal($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		
	    $html="";
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
	
	    $strSQL = "select sum(amount) from transaction where 
						OID=\"$pUID\" and isdeleted=0 and date like '$pYear-$pMonth%' and
													(type =\"PURC\" or type =\"ADWU\" or type =\"ADCH\" or type =\"ADOP\" or type =\"ADWT\" or type =\"ADAH\" 
	or type =\"ADZR\" or type =\"ADBP\" or type =\"ADIN\" or type =\"ADDB\"  or type =\"ADPP\" or type =\"ACPT\" or type =\"ADMB\")   and iscredit=0 ";
	
	#echo $strSQL ;
	
	   $Result = $myADb->ExecuteQuery($strSQL);
	
	   $GrandTotalAmount = number_format($Result->fields[0],2);
	
	
	    $strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
						OID=\"$pUID\" and isdeleted=0 and date like '$pYear-$pMonth%' and
													 (type =\"PURC\" or type =\"ADWU\" or type =\"ADCH\" or type =\"ADOP\" or type =\"ADWT\" or type =\"ADAH\" 
	or type =\"ADZR\" or type =\"ADBP\" or type =\"ADIN\" or type =\"ADDB\"  or type =\"ADPP\" or type =\"ACPT\" or type =\"ADMB\")  and iscredit=0 order by Date desc limit 0,100
						";
	#echo $strSQL;
	
	   $Result = $myADb->ExecuteQuery($strSQL);
	
	   $bgcolor = "grey";
	
	while(!$Result->EOF){
		
		$Sno++;
		
		$Desc = $Result->fields[0];
		$Date = $Result->fields[1];
		$Amount = $Result->fields[2];
		$TypeList = $Result->fields[3];
		$TrID = $Result->fields[4];
		
		$ThisPageTotal = $ThisPageTotal + $Amount;
		
		   			
		    $Result->MoveNext();
		}
	
	   return $ThisPageTotal;
	}

	function GetGrandTotalAmount($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		
	    $html="";
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
	
	    $strSQL = "select sum(amount) from transaction where 
						OID=\"$pUID\" and isdeleted=0 and date like '$pYear-$pMonth%' and
													(type =\"PURC\" or type =\"ADWU\" or type =\"ADCH\" or type =\"ADOP\" or type =\"ADWT\" or type =\"ADAH\" 
	or type =\"ADZR\" or type =\"ADBP\" or type =\"ADIN\" or type =\"ADDB\"  or type =\"ADPP\" or type =\"ACPT\" or type =\"ADMB\")   and iscredit=0 ";
	
	#echo $strSQL ;
	
	   $Result = $myADb->ExecuteQuery($strSQL);
	
	   $GrandTotalAmount = number_format($Result->fields[0],2);
	   return $GrandTotalAmount;
	}
	//PAYMENT MADE TO DIDX

//INVOICE
	function GetEarningReportInvoice($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		$html="";
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$MyType = "PVPL" . $pMonth .substr($pYear,2,2) ;
		
		$strSQL = "select sum(amount) from transaction where 
							OID=\"$pUID\" and isdeleted=0 and iscredit=1 and
							(type !=\"PPCC\" and type !=\"PPWU\" and type !=\"PPBC\" and type !=\"PPOP\" and type !=\"PPWT\" and type !=\"PPAH\" 
		or type !=\"PPZR\" and type !=\"PPBP\" and type !=\"PPPP\"  ) 
							and date like '$pYear-$pMonth%'
							";
	#	echo $strSQL;
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0],2);
		
		
		$strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
							OID=\"$pUID\" and isdeleted=0  and iscredit=1 and
							(type !=\"PPCC\" and type !=\"PPWU\" and type !=\"PPBC\" and type !=\"PPOP\" and type !=\"PPWT\" and type !=\"PPAH\" 
		or type !=\"PPZR\" and type !=\"PPBP\" and type !=\"PPPP\"  ) 
							and date like '$pYear-$pMonth%'
							order by Date desc limit 0,100
							";
	#echo $strSQL;
	
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$bgcolor = "grey";
		
		while(!$Result->EOF){
		
			$Sno++;
			
			$Desc = $Result->fields[0];
			$Date = $Result->fields[1];
			$Amount = $Result->fields[2];
			$TypeList = $Result->fields[3];
			$TrID = $Result->fields[4];
			
			$ThisPageTotal = $ThisPageTotal + $Amount;

			
			if($TypeList=="ACTV")	
					$TyMode="Activation Cost";
			else if(substr($TypeList,0,4)=="PVPL")
					$TyMode="Monthly DID Cost";
			else
					$TyMode="Others";
					
			#$TyMode = "Monthly DID Payment";

					
					
			$html .= " <tr>
	                <td align=\"center\">$Sno</td>
	                <td align=\"center\">$TrID</td>
	                <td align=\"center\">$Date</td>
	                <td align=\"center\">$TyMode</td>
	                <td align=\"center\">$Desc</td>
	                <td align=\"center\">".number_format($Amount,2)."</td>
	                </tr>";
				
			
			
			
			$Result->MoveNext();
			
		}
	 return $html;
	}


	function GetGrandTotalInvoice($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$MyType = "PVPL" . $pMonth .substr($pYear,2,2) ;
		
		$strSQL = "select sum(amount) from transaction where 
							OID=\"$pUID\" and isdeleted=0 and iscredit=1 and
							(type !=\"PPCC\" and type !=\"PPWU\" and type !=\"PPBC\" and type !=\"PPOP\" and type !=\"PPWT\" and type !=\"PPAH\" 
		or type !=\"PPZR\" and type !=\"PPBP\" and type !=\"PPPP\"  ) 
							and date like '$pYear-$pMonth%'
							";

		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0],2);
		
		
		return $GrandTotalAmount;
	}

	function GetTpageinvoice($pUID,$pMonth,$pYear){
		$myADb = new ADb();
		
		global $Sno;
		// global $GrandTotalAmount;
		$ThisPageTotal="0";
		
		$MyType = "PVPL" . $pMonth .substr($pYear,2,2) ;
		
		$strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
							OID=\"$pUID\" and isdeleted=0  and iscredit=1 and
							(type !=\"PPCC\" and type !=\"PPWU\" and type !=\"PPBC\" and type !=\"PPOP\" and type !=\"PPWT\" and type !=\"PPAH\" 
		or type !=\"PPZR\" and type !=\"PPBP\" and type !=\"PPPP\"  ) 
							and date like '$pYear-$pMonth%'
							order by Date desc limit 0,100
							";

	
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$bgcolor = "grey";
		
		while(!$Result->EOF){
		
			$Sno++;
			
			$Desc = $Result->fields[0];
			$Date = $Result->fields[1];
			$Amount = $Result->fields[2];
			$TypeList = $Result->fields[3];
			$TrID = $Result->fields[4];
			
			$ThisPageTotal = $ThisPageTotal + $Amount;
				
			$Result->MoveNext();
			
		}

	 return $ThisPageTotal;
	}
//INVOICE

//Seller Links in yearly report
    function GetEarningReportInvSeller($pUID,$pMonth,$pYear){
	
		$myADb = new ADb();
		
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$MyType = "PAY" . $pMonth .substr($pYear,2,2) ;
		
		$strSQL = "select sum(amount) from transaction where 
							OID=\"$pUID\" and isdeleted=0 and (Type = \"$MyType\" or TYpe='ACTP') 
							and date like '$pYear-$pMonth%'
							and iscredit=0 ";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0],2);
		
		
		$strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
							OID=\"$pUID\" and isdeleted=0 and (Type = \"$MyType\" or TYpe='ACTP')
							and date like '$pYear-$pMonth%'
							 and iscredit=0 order by Date desc limit 0,100
							";
#	echo $strSQL;
	
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$bgcolor = "grey";
		
		while(!$Result->EOF){
			
			$Sno++;
			
			$Desc = $Result->fields[0];
			$Date = $Result->fields[1];
			$Amount = $Result->fields[2];
			$TypeList = $Result->fields[3];
			$TrID = $Result->fields[4];
			
			$ThisPageTotal = $ThisPageTotal + $Amount;
			
			if($TypeList=="ACTP")	
					$TyMode="Activation Payment";
			else if(substr($TypeList,0,3)=="PAY")
					$TyMode="Monthly DID Payment";

	        if($bgcolor=="grey")
	        {
	            $bgcolor="";
	        }
	        else
	        {
	            $bgcolor="grey";
	        }
			$html .= " <tr class=\"$bgcolor\">
	                <td >$Sno</td>
	                <td align=right>$TrID</td>
	                <td align=center>$Date</td>
	                <td align=center>$TyMode</td>
	                <td align=right>$Desc</td>
	                <td align=right>".number_format($Amount,2)."</td>
	                </tr>";
				
			$Result->MoveNext();
			
	    }
	
	    return $html;
	
    }

   function GetTotalInvSeller($pUID,$pMonth,$pYear){
	   	$myADb = new ADb();
		
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$MyType = "PAY" . $pMonth .substr($pYear,2,2) ;
		
		$strSQL = "select sum(amount) from transaction where 
							OID=\"$pUID\" and isdeleted=0 and (Type = \"$MyType\" or TYpe='ACTP') 
							and date like '$pYear-$pMonth%'
							and iscredit=0 ";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0],2);

		return $GrandTotalAmount;
		
	
	}

	function GetPgTotalInv($pUID,$pMonth,$pYear){
		$myADb = new ADb();
	
		global $Sno;
		global $GrandTotalAmount;
		global $ThisPageTotal;
		
		$MyType = "PAY" . $pMonth .substr($pYear,2,2) ;
		
		$strSQL = "select sum(amount) from transaction where 
							OID=\"$pUID\" and isdeleted=0 and (Type = \"$MyType\" or TYpe='ACTP') 
							and date like '$pYear-$pMonth%'
							and iscredit=0 ";
		
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$GrandTotalAmount = number_format($Result->fields[0],2);
	
	
	    $strSQL = "select description,date_format(date,'%d-%b-%Y'),amount,Type,transactionid from transaction where 
						OID=\"$pUID\" and isdeleted=0 and (Type = \"$MyType\" or TYpe='ACTP')
						and date like '$pYear-$pMonth%'
						 and iscredit=0 order by Date desc limit 0,100
						";
#	echo $strSQL;
	
		$Result = $myADb->ExecuteQuery($strSQL);
		
		$bgcolor = "grey";
		
		while(!$Result){
			
			$Sno++;
			
			$Desc = $Result->fields[0];
			$Date = $Result->fields[1];
			$Amount = $Result->fields[2];
			$TypeList = $Result->fields[3];
			$TrID = $Result->fields[4];
			
			$ThisPageTotal = $ThisPageTotal + $Amount;
			
			$Result->MoveNext();

		}
		return $ThisPageTotal;
    }

  //Seller Links in yearly report
}


?>