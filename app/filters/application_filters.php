<?php



function Check_Feild_NotEmpty($params){
	$errors = array();
	foreach ($params as $key => $value){
		if ($params[$key] == null && $params[$key] == "" && $value == null && $value == ""){
			$errors[$key] = $key." must be entered";
		}
	}
	if (empty($errors)){
		return array("status" => "ok");
	}else{
		return $errors;
	}

}

function Check_Valid_Email($email){
	$errors = array();
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	if (!preg_match_all($regex, $email)) {
		$errors['Email'] = $email." not valid email";
	}
	if (empty($errors)){
		return array("status" => "ok");
	}else{
		return $errors;
	}
}





function form_select_tag($name,$options,$selected = '',$params = '')
{
    $return = '<select name="'.$name.'" id="'.$name.'"';
    if(is_array($params))
    {
        foreach($params as $key=>$value)
        {
            $return.= ' '.$key.'="'.$value.'"';
        }
    }
    else
    {
        $return.= $params;
    }
    $return.= '>';
    foreach($options as $key=>$value)
    {
        $return.='<option value="'.$value.'"'.($selected != $value ? '' : ' selected="selected"').'>'.$key.'</option>';
    }
    return $return.'</select>';
}

function isset_and_not_empty($value='')
{
	return isset($value) && !empty($value) && $value != "%" ? true : false; 
}

function GetYearsList($from,$to){
	$res =  array();
	
	for($nIndex=$from;$nIndex<=$to;$nIndex++){
		$res[$nIndex] = $nIndex;
	}
	$res['All'] = "%";
	return $res;	
}

// this will return html will selected year
function GetYearList($pYear,$NowYear){

	$YearHTML = "";
		
	for($nIndex=2009;$nIndex<=$NowYear;$nIndex++){
		$YearHTML	.= "<option value='$nIndex'>$nIndex</option>";
	}
		
	$YearHTML	= str_replace("'$pYear'","'$pYear' selected ",$YearHTML);

	return $YearHTML;	
	
}

function GetMonthsList() {
	$res =  array();
	$res['January'] = "01";
	$res['February'] = "02";
	$res['March'] = "03";
	$res['April'] = "04";
	$res['May'] = "05";
	$res['June'] = "06";
	$res['July'] = "07";
	$res['August'] = "08";
	$res['September'] = "09";
	$res['October'] = "10";
	$res['November'] = "11";
	$res['December'] = "12";
	$res['All'] = "%";
	
	return $res;
	
}

function GetMonthList($pMonth) {
	
	
	$MonthHTML	= "
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
	$MonthHTML	= str_replace("'$pMonth'","'$pMonth' selected ",$MonthHTML);

	return $MonthHTML;
	
}


function GetDayList($pDay) {
	$DayHTML = "";
	
	for($nIndex=1;$nIndex<=31;$nIndex++){
		
		if($nIndex>=1 && $nIndex<=9)
				$DayInt = "0" . $nIndex;
		else
				$DayInt = $nIndex;
		
		$DayHTML	.= "<option value='$DayInt'>$DayInt</option>";
	}
	
	$DayHTML	= str_replace("'$pDay'","'$pDay' selected ",$DayHTML);
	
	return $DayHTML;
}

function GeneralMsg($Condition,$Error,$Successmsg,$General)
{
	$html='';
return ($Condition)? $html.=$Successmsg:'';

}

function getSearchCountriesList($SortBy=1)
{
    global $htmlSelect;
	global $html;
	//global $myADb;
	$myADb = new ADb(); 
	
    if($SortBy==1)	
        $OrderBy=" DIDCountries.CountryCode";
	else
	    $OrderBy=" DIDCountries.Description";
    
    $html="";
    //		$strSQL="select  DIDCountries.id,DIDCountries.countrycode,DIDCountries.description,DIDS.AreaID from DIDCountries,DIDS,DIDArea 
    //								where DIDS.AreaID=DIDArea.id and
    //								DIDArea.CountryID=DIDCountries.id and
    //								Status = 0 and  DIDS.VendorRating>0 and DIDS.VendorRating<10 and 
    //								
    //								CheckStatus = 1 group by DIDCountries.ID 
    //								order by $OrderBy ";
    //							#	echo $strSQL;
    
    $strSQL = "select * from CountriesAvail order by cast(countrycode as unsigned)";					
	$Result = $myADb->ExecuteQuery($strSQL);
	
    $Sno=0;
	$MySno=0;
	$OldSno = 0;
	$html ="<DIV ID='PANEL$Sno' style='width:190px;float:left; clear:both;margin-left:10px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;vertical-align:middle;margin:4px;display: block;'>";
	$htmlSelect .= "<option value=-1 selected>-- Select ---</option>\n";
    while(!$Result->EOF)
    {
        $Sno++;
		$CountryID = $Result->fields[0];
		$CountryCode = $Result->fields[1];
		$CountryName = $Result->fields[2];
		$AreaID = $Result->fields[3];
		
        if($Sno>12)
        {
            $MySno++;
//				if($MySno==1)
//					$HideLayer = "display: block;";
//			else
            $HideLayer = "display: none;";
            
            $html .=  " <DIV align=right ID='COUNTRYDIVMORE$CountryID' style='width:190px;float:left; clear:both;margin-left:10px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;vertical-align:middle;margin:4px;display: block;'>
                        <a Title='More' href=\"javascript:void(0);\" OnClick=\"BuyDID_GetCountryListA('$MySno','__TOTALREC__','');\" ><img src='http://didx.net/tmpl/images/arrowdown1.png' border='0' alt='down'></a></DIV></DIV><DIV ID='PANEL$MySno' style='$HideLayer width:190px;float:left; clear:both;margin-left:10px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;vertical-align:middle;margin:4px;'>";
            $Sno=1;
         }
         $html .= "<DIV ID='COUNTRYDIV$CountryID' style='width:190px;float:left; clear:both;margin-left:10px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;vertical-align:middle;margin:4px;display: block;' OnMouseOver=\"document.getElementById('COUNTRYDIV$CountryID').style.backgroundColor='#D9E5E7'; \" OnMouseOut=\"document.getElementById('COUNTRYDIV$CountryID').style.backgroundColor=''; \" >
									<a href=\"javascript:void(0);\" OnClick=\"GetAreaList('$CountryID');\">$CountryCode - $CountryName</a></DIV>";
						#$html .= "<DIV ID=SEP$CountryID style='float: left;clear: both;border-bottom:solid 1px;border-color:#f0f0f0;width:100%;height:1px;'>&nbsp;</DIV>";			
						
						$html .= "<DIV ID='bg'>&nbsp;</DIV>";
				#$Sno=0;												

			
			
			#$html .= "<div style=\"margin: 0px 3px 2px 3px; height: 1px; overflow: hidden; background-color: #f0f0f0\"></div>";
			
			#$html .= "<DIV ID='AREANAMEDIV$CountryID' style='float:left; clear:both;margin-left:12px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;line-height:1'></DIV>";
            
			$htmlSelect .= "<option value=$CountryID>$CountryCode - $CountryName</option>\n";
			
			$Result->MoveNext();
			
			
		}
		
		$html .= "</DIV>";
		
		$html .=  "<DIV align=right ID='COUNTRYDIVMORE1' style='width:190px;float:left; clear:both;margin-left:10px;font-family:Verdana, Arial, 
		Helvetica, sans-serif;font-size:12px;vertical-align:middle;margin:4px;display: none;'>
									<a  Title='Back' href=\"javascript:void(0);\" OnClick=\"BuyDID_GetCountryListA('-1','__TOTALREC__');\" ><img src='http://didx.net/tmpl/images/arrowup1.png' border='0' alt='up'></a></DIV>";
		
//		$html .= "<DIV align=right ID='COUNTRYDIV$CountryID' style='width:190px;float:left; clear:both;margin-left:10px;font-family:Verdana, Arial, 
//		Helvetica, sans-serif;font-size:12px;vertical-align:middle;margin:4px;display: block;'>
//									<a href=\"javascript:void(0);\" OnClick=\"BuyDID_GetCountryListA('13');\" class=PageLink2>More Countries >></a></DIV></DIV>";
						#$html .= "<DIV ID=SEP$CountryID style='float: left;clear: both;border-bottom:solid 1px;border-color:#f0f0f0;width:100%;height:1px;'>&nbsp;</DIV>";			
						
						$html .= "<DIV ID='bg'>&nbsp;</DIV>";
						
						$html = str_replace("__TOTALREC__",$MySno,$html);
	 	
	
	
	
	
	
}


//         
function GetChannelPricing($AreaID,$VOID) {
    $myADb = new ADb();
    //global $myADb, $ChannelTableName;
    $myOrder = new Orders();
    $UID = currentUser();
    $Orders = $myOrder->getOrdersInfoByUID($UID);
	$ForceBuyer = $Orders[ForceBuyer]; 
    $IsPremium = $Orders['Premium'];
	$ChannelTableName = "ChannelAdmin";

	if($IsPremium==1){
		$ChannelTableName="ChannelAdmin_Premium";
		
	}
    // global  $ChannelTableName;
    
    $strSQL = "select TotalChannels,Setup,Monthly from $ChannelTableName 
    where OID=\"$VOID\"  and AreaID=\"$AreaID\" ";
    #echo "<br>$strSQL";
    $Result = $myADb->ExecuteQuery($strSQL);
    
    if($Result->EOF){
        
            $strSQL = "select TotalChannels,Setup,Monthly from $ChannelTableName 
            where OID=\"$VOID\"  and AreaID=\"-1\" ";
        #echo "<br>$strSQL";
            $Result = $myADb->ExecuteQuery($strSQL);
            
                if($Result->EOF){
                    return "-1";
                }
        
    }
    
    $ChannelPrice =  $Result->fields[2];
    
    return $ChannelPrice;
    
}


function url_for($stringURL = ""){
	if ($stringURL != ""){
		$stringURL = ltrim($stringURL, '/');
		return $GLOBALS['website_url']."/".$stringURL;
	}
	return $GLOBALS['website_url'];
}

	function GetDateSpans() {
	
		$myADb = new ADb();
			
			$strSQL = "select to_days(last_day(curdate())) - to_days(curdate()) as a, 
		date_format(curdate(),'%d-%b-%Y') as aa,date_format(last_day(curdate()),'%d-%b-%Y') as b,
		date_format(date_add(last_day(curdate()),interval 1 day),'%d-%b-%Y') as c,
		date_format(date_add(date_add(last_day(curdate()),interval 1 day),interval 1 month),'%d-%b-%Y') as d,
		substring(date_add(last_day(curdate()),interval 1 day),6,2),substring(date_add(last_day(curdate()),interval 1 day),3,2),
		substring(last_day(curdate()),9,2),
		date_add(last_day(curdate()),interval 1 day) as cc,
		substring(curdate(),6,2),substring(curdate(),3,2),
		substring(date_add(curdate(),interval 1 MONTH),6,2) as j,substring(date_add(curdate(),interval 1 month),3,2) as jj
		";
			
			$Result = $myADb->ExecuteQuery($strSQL);
			
			 $TotalDays = $Result->fields[0];
			 $Todays = $Result->fields[1];
			 $LastDay = $Result->fields[2];
			 $StartDay = $Result->fields[3];
			 $NextMonth = $Result->fields[4];
			
			 $Type1 = $Result->fields[5];
			 $Type2 = $Result->fields[6];
			
			 $MaxDays = $Result->fields[7];
			 $OStartDay = $Result->fields[8];
			
			 $ThisType1 = $Result->fields[9];
			 $ThisType2 = $Result->fields[10];
			
			 $NextType1 = $Result->fields[9];
			 $NextType2 = $Result->fields[10];
			
			 $Type = "$Type1$Type2";
			
			 $ThisType = "$ThisType1$ThisType2";
			 $NextType = "$NextType1$NextType2";
			
			 $Span1 = "Channels Bill From $Todays To $LastDay";
			 $Span2 = "Channels Bill From $StartDay To $NextMonth";
			
			 $Hash = array();
			
			$Hash[TotalDays] = $TotalDays;
			$Hash[Todays] =$Todays;
			$Hash[Span1] = $Span1;
			$Hash[Span2] = $Span2;
			$Hash[Type] = $Type;
			$Hash[OStartDay] = $OStartDay;
			
			$Hash[MaxDayNo] = $MaxDays;
			
			$Hash[ThisType] = $ThisType;
			$Hash[NextType] = $NextType;
			
			
			return $Hash;
			
			
		}

?>