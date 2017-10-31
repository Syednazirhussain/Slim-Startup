<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
class AdminUser
{
	// 	Copyright©2005 Saleem Ahmed Kamboh. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function aDPrint($arr) { if($this->fDebug){	var_dump($arr);	}}	
	function AdminUser()
	{
		$this->Db = new ADb();
	}	
	#	getAdminUserByUID
	#	IN:	AUID
	#	OUT:	AdminUser table hash
	function getAdminUserByUID($pUID)	 {
		$this->dPrint ("\$pUID: $pUID");	
		$strSQL	= "	select	*	from	adminuser	where	uid	= '".$pUID."'";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->Db->ExecuteQuery ($strSQL);
		$AdminUser = array();
		if(!($Result===false))
		{
			$AdminUser["AUID"]	= $Result->fields[0];
			$AdminUser["UID"]	= $Result->fields[1];
			$AdminUser["AddressID"]	= $Result->fields[2];
			$AdminUser["FName"]	= $Result->fields[3];
			$AdminUser["LName"]	= $Result->fields[4];
			$AdminUser["Email"]	= $Result->fields[5];
			$AdminUser["Type"]	= $Result->fields[6];
			$AdminUser["Desig"]	= $Result->fields[7];
			$AdminUser["Tel1"]	= $Result->fields[8];
			$AdminUser["Tel2"]	= $Result->fields[9];
			$AdminUser["Salary"]	= $Result->fields[10];
			$AdminUser["DoJ"]	= $Result->fields[11];
			$AdminUser["ADate"]	= $Result->fields[12];
			$AdminUser["Tier1"]	= $Result->fields[13];
			$AdminUser["Tier2"]	= $Result->fields[14];
			$AdminUser["Tier3"]	= $Result->fields[15];
		}
		return $AdminUser;
	
	} # getAdminUserByUID
	
	function  getCustomerNameByUID($pUID) {
	

	$strSQL	= "	select
					concat(cfname,' ',
					cmname,' ',
					clname)
				from
					customer
				where
					uid=\"$pUID\"";
				#echo "<br>$strSQL";	
		$Result	= $this->Db->ExecuteQuery($strSQL);
	#	print_r($Result);
		return $Result->fields[0];					
	
}

function  getAdminNameByUIDAUID($pUID) {
	

	$strSQL	= "	select
					concat(AUFName,' ',
					AULName)
				from
					adminuser
				where
					(uid=\"$pUID\" or AUID=\"$pUID\") ";
				#echo "<br>$strSQL";	
		$Result	= $this->Db->ExecuteQuery($strSQL);
	#	print_r($Result);
		return $Result->fields[0];					
	
}

function  getAdminUIDByAUID($pUID) {
	

	$strSQL	= "	select
					uid
				from
					adminuser
				where
					AUID=\"$pUID\" ";
				#echo "<br>$strSQL";	
		$Result	= $this->Db->ExecuteQuery($strSQL);
	#	print_r($Result);
		return $Result->fields[0];					
	
}
		
		
	/*
			addResellerBillingInfo
			addAdminUser
			getAdminUserInfoByUID
			getAdminNameByUID
			getAdminNameByAUID
			addAdminRights
			getAllAdminUserInfo
			getAdminUserByUID
			getAdminUser
			editAdminUser
			getEachAndEveryAdmin
	
	function getKey {
		return substr(MD5->hexhash(time(). {$_} . rand(). $$. @_),0,10);
	}*/
	//	addAdminUser
	function addAdminUser($Hash) {
		$strSQL	= "	insert into
						adminuser
						(
						auid,
						uid,
						addressid,
						aufname,
						aulname,
						auemail,
						autype,
						autel1,
						acdate
						)
					values (
						'".$Hash['AUID']."',
						'".$Hash['UID']."',
						'".$Hash['AddressID']."',
						'".$Hash['FName']."',
						'".$Hash['LName']."',
						'".$Hash['Email']."',
						'".$Hash['Type']."',
						'".$Hash['Tel1']."',
						'".$Hash['DateTime']."'
						)				
						";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->Db->ExecuteQuery($strSQL);
		$this->dPrint ("\$Result: $Result");
		return $Result;
	
	}# addAdminUser
	



function getAdminNameByAUID($pUID){
	
	$strSQL	= "	select concat(AUFName,\" \",AULName) from adminuser where AUID=\"$pUID\" ";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	
	return $Result->fields[0];
	
}

function getAdminNameByUID($pUID){
	
	$strSQL	= "	select concat(AUFName,\" \",AULName) from adminuser where UID=\"$pUID\" ";
	$Result	= $this->Db->ExecuteQuery($strSQL);
	if($Result->EOF)
    {
        return -1;
    }
	return $Result->fields[0];
	
}
	
function getAdminUser($pAUID) {
	
	
	$strSQL	= "	select * from 
				adminuser a,resellerbillinginfo r 
				where a.auid=r.auid 
			and 	a.auid	= \"$pAUID\"";
	
	
	$Result	= $this->Db->ExecuteQuery($strSQL);


	$AdminUser;
	
	#if ($#Result==0)
	if ($Result->EOF)	
	{
					 $strSQL	= "	select * from 
				adminuser 
				where auid	= \"$pAUID\"";
	
			$Result	= $this->Db->ExecuteQuery($strSQL);

			
		$AdminUser["AUID"]	= $Result->fields[0];
		$AdminUser["UID"]	= $Result->fields[1];
		$AdminUser["AddressID"]	= $Result->fields[2];
		$AdminUser["FName"]	= $Result->fields[3];
		$AdminUser["LName"]	= $Result->fields[4];
		$AdminUser["Email"]	= $Result->fields[5];
		$AdminUser["Type"]	= $Result->fields[6];
		$AdminUser["Desig"]	= $Result->fields[7];
		$AdminUser["Tel1"]	= $Result->fields[8];
		$AdminUser["Tel2"]	= $Result->fields[9];
		$AdminUser["Salary"]	= $Result->fields[10];
		$AdminUser["DoJ"]	= $Result->fields[11];
		$AdminUser["ADate"]	= $Result->fields[12];
		

		$AdminUser["Tier1"]			= $Result->fields[13];
		$AdminUser["Tier2"]			= $Result->fields[14];
		$AdminUser["Tier3"]			= $Result->fields[15];	
		return $AdminUser;
	}
	
	if (!$Result->EOF) {
		$AdminUser["AUID"]	= $Result->fields[0];
		$AdminUser["UID"]	= $Result->fields[1];
		$AdminUser["AddressID"]	= $Result->fields[2];
		$AdminUser["FName"]	= $Result->fields[3];
		$AdminUser["LName"]	= $Result->fields[4];
		$AdminUser["Email"]	= $Result->fields[5];
		$AdminUser["Type"]	= $Result->fields[6];
		$AdminUser["Desig"]	= $Result->fields[7];
		$AdminUser["Tel1"]	= $Result->fields[8];
		$AdminUser["Tel2"]	= $Result->fields[9];
		$AdminUser["Salary"]	= $Result->fields[10];
		$AdminUser["DoJ"]	= $Result->fields[11];
		$AdminUser["ADate"]	= $Result->fields[12];
		          
		$AdminUser["UnLimitedPlanRate"]		= $Result->fields[17];
		$AdminUser["HwCost"]			= $Result->fields[19];
		$AdminUser["str500MinPlan"]		= $Result->fields[18];
		$AdminUser["SetupFee"]			= $Result->fields[20];
		$AdminUser["Tier1"]			= $Result->fields[13];
		$AdminUser["Tier2"]			= $Result->fields[14];
		$AdminUser["Tier3"]			= $Result->fields[15];
		
	}


	
	return $AdminUser;

} # getAdminUser

	
	function getAdminUserInfoByUID($pUID) {
		$strSQL	= "	select
						auid,
						uid,
						addressid,
						aufname,
						aulname,
						auemail,
						autype,
						autel1,
						acdate
					from
						adminuser
					where
						uid='".$pUID."'";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result	= $this->Db->ExecuteQuery($strSQL);
		$Hash=array();		
		$Hash['AUID']=$Result->fields[0];
		$Hash['UID']=$Result->fields[1];
		$Hash['AddressID']=$Result->fields[2];
		$Hash['FName']=$Result->fields[3];
		$Hash['LName']=$Result->fields[4];
		$Hash['Email']=$Result->fields[5];
		$Hash['Type']=$Result->fields[6];
		$Hash['Tel1']=$Result->fields[7];
		$Hash['DateTime']=$Result->fields[8];
		$this->aDPrint($Hash);
		return $Hash;
	}# getAdminUserInfoByUID
	
		function getAllAdminUserInfo($pUID=""){
		
		$strSQL = "select AUID,concat(AUFName,' ',AULName) from adminuser order by uid ";
		$Result	= $this->Db->ExecuteQuery ($strSQL);
		
		$html = "";
		
		while(!$Result->EOF){
			
			$AUID 		 = $Result->fields[0];
			$AdminName = $Result->fields[1];
			if($pUID == $AUID)
            {
			    $html .= "<option value=\"$AUID\" selected>$AdminName</option>";
			}
            else
            {
                $html .= "<option value=\"$AUID\">$AdminName</option>";
            }
			
			$Result->MoveNext();
		}
		
		return $html;
	}
	
	
	
}//class User
?>