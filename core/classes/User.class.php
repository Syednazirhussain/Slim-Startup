<?php
	//include_once "Const.inc.php";
	//#include_once "$INCLUDEPATH/ADb.inc.php";					// For ADODB
	//include_once "/var/www/vhosts/didx.net/httpdocs/includes/ADb.inc.php";
	//#include_once $INCLUDEPATH."/ADbi.inc.php";				// For mysqli
	//#include_once $INCLUDEPATH."/Db.inc.php";				// General Db Functions
	
class User
{
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function User()
	{
		$this->ADb = new ADb();
	#	$this->ADbi = new ADbi();
		#$this->myDb = new Db();
	}	
	//	getUserInfo
//	function getUserInfo__2($pUID) {	
//		 $strSQL	= "	select
//						uid,
//						pass,
//						type,
//						isactive,
//						isremoved,
//						confcode,
//						datetime
//					from
//						user
//					where
//						uid='".$pUID."'";	
//		$this->dPrint ("\$strSQL: $strSQL");
//		$strSQL = "call GetUserInfo(\"$pUID\");";			// Now using a stored procedure
//		echo $strSQL;
//		$ResultSet=$this->ADbi->ExecuteQuery($strSQL);
//		print_r($ResultSet);
//		#$Result = $myDb->fetch_array($ResultSet);
//		$Hash = array()	;
//		while ($ResultRow = mysql_fetch_array($ResultSet)){
//		
//		
//			$Hash[UID]	= $ResultRow[0];
//			$Hash[Pass]	= $ResultRow[1];
//			$Hash[Type]	= $ResultRow[2];
//			$Hash[IsActive]	= $ResultRow[3];
//			$Hash[IsRemoved]	= $ResultRow[4];
//			$Hash[ConfCode]	= $ResultRow[5];
//			$Hash[DateTime]	= $ResultRow[6];
//			
//		}
//		print_r($Hash);
//		return $Hash;
//	}# getUserInfo
	
	function getUserInfo($pUID) {	
		 $strSQL	= "	select
						uid,
						pass,
						type,
						isactive,
						isremoved,
						confcode,
						datetime
					from
						user
					where
						uid='".$pUID."'";	
		$this->dPrint ("\$strSQL: $strSQL");
		$Result=$this->ADb->query($strSQL);
		$Hash = array()	;
		if(!$Result->EOF)
		{
			$Hash[UID]	= $Result->fields[0];
			$Hash[Pass]	= $Result->fields[1];
			$Hash[Type]	= $Result->fields[2];
			$Hash[IsActive]	= $Result->fields[3];
			$Hash[IsRemoved]	= $Result->fields[4];
			$Hash[ConfCode]	= $Result->fields[5];
			$Hash[DateTime]	= $Result->fields[6];
		}
		return $Hash;
	}# getUserInfo
	#	updateUserPassword
	function updateUserPassword($pUID, $pPass) {	
		 $strSQL	= "	update user
					set
						pass = \"$pPass\"
					where
						uid=\"$pUID\" ";
		
		$Result	= $this->ADb->query($strSQL);
		
		
	}# updateUserPassword
	#	addUser
	function addUser($Hash) {
		$dated = date('Y-m-d', time());
		$strSQL	= "	insert into
						user
						(
						uid,
						pass,
						type,
						isactive,
						isremoved,
						confcode,
						datetime
						)
					values (
						'".$Hash["UID"]."',
						'".$Hash["Pass"]."',
						'".$Hash["Type"]."',
						'".$Hash["IsActive"]."',
						'".$Hash["IsRemoved"]."',
						'".$Hash["ConfCode"]."', 
						'$dated'
						)						
						";
		$this->dPrint( "\$strSQL: $strSQL");	
		$Result	= $this->ADb->query($strSQL);
		//$this->dPrint( "\$Result[0]: $Result[0]");
	
		return $Result;
	
	}# addUser
	#	editUser
	function editUser($Hash)  {	
		 $strSQL	= "	update user
					set
						pass='".$Hash["Pass"]."',
						type='".$Hash["Type"]."',
						isactive='".$Hash["IsActive"]."',
						isremoved='".$Hash["IsRemoved"]."',
						confcode='".$Hash["ConfCode"]."'
					Where
						uid='".$Hash["UID"]."'";
		
		$this->dPrint( "\$strSQL: $strSQL");
	
		 $Result	= $this->ADb->query($strSQL);
		//$this->dPrint( "\$Result[0]: $Result[0]");
	
		return $Result;
	
	}# editUser
	function getUserType($pUID){
		$this->dPrint( "\$pUID: $pUID");		
		 $strSQL	= "	select
						type
					from
						user
					where
						uid	= '".$pUID."'";
		$this->dPrint( "\$strSQL: $strSQL");
		
		 $Result	= $this->ADb->query($strSQL);		
		$this->dPrint( "\$Result->fields[0]: $Result->fields[0]");
		
		return $Result->fields[0];
	}#getUserType
	//added on 25-11-05 Saleem Ahmed
	function getUIDbyEmailOrAccount($pEmail,$pAcNo)
	{		
		$strSQL	= "	select  * from siprelation where 	uid=\"$pEmail\" or 	sipid=\"$pAcNo\"";
		$this->dPrint( "\$strSQL: $strSQL");	
 	    $Result	= $this->ADb->query($strSQL);		
		if(!$Result->EOF)
		{
			$Hash = array();		
			$Hash[UID]	= $Result->fields[0];
			$Hash[SIPID]	= $Result->fields[1];
			$Hash[Cents]	= $Result->fields[2];		
		}
		return $Hash;
	}
	function getConfirmationCode($OID)
	{
		$strSQL = "select ConfCode from user where UID='$OID'";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result=$this->ADb->query($strSQL);
		if($Result === false)
			return "";
		$ConfCode = $Result->fields[0];
		return $ConfCode;
	}
	
	function getPassCode($OID)
	{
		$strSQL = "select Pass from user where UID='$OID'";
		$this->dPrint ("\$strSQL: $strSQL");
		$Result=$this->ADb->query($strSQL);
		if($Result === false)
			return "";
		$Pass = $Result->fields[0];
		return $Pass;
	}

	function getInfoDuringSignUp($OID)
	{
		$strSQL = "select uid,pass,type,isactive,isremoved,confcode,datetime from user where uid='".$OID."'";
		$Result=$this->ADb->query($strSQL);
		 $User = array();        
        $User["uid"]    = $Result->fields[0];
        $User["pass"]    = $Result->fields[1];
        $User["type"]    = $Result->fields[2];
        $User["isactive"]    = $Result->fields[3];
        $User["isremoved"]    = $Result->fields[4];
        $User["confcode"]    = $Result->fields[5];
        $User["datetime"]    = $Result->fields[6];
		return $User;
	}

	function getSignUpInfo($lWh)
	{
		$strSQL = "select uid,pass,type,isactive,isremoved,confcode,datetime from user where confcode='".$lWh."'";
		$Result=$this->ADb->query($strSQL);
		if(!$Result->EOF){
		$Hash = array();        
        $Hash["uid"]		= $Result->fields[0];
        $Hash["pass"]		= $Result->fields[1];
        $Hash["type"]		= $Result->fields[2];
        $Hash["isactive"]	= $Result->fields[3];
        $Hash["isremoved"]  = $Result->fields[4];
        $Hash["confcode"]   = $Result->fields[5];
        $Hash["datetime"]   = $Result->fields[6];
		return $Hash;
	}
	else{
		return 0;
	}
	}

function addNewUser($uid)
	{
		$strSQL = "update user set isactive=1 where uid='".$uid."'";
		//echo $strSQL;
		$Result=$this->ADb->query($strSQL);
		return $Result;
	}			

/*	function getUIDByEncUID($pEncUID) 
	{
		$strSQL	= 	"Select		UID		from		User		Where			UType	= \"CUST\"		and		IsActive=1";
 	    $Result	= $this->ADb->query($strSQL);		
		if(!($Result===false))
		{
			while(!$Result->EOF)
			{		
				$myENCUID	= crypt($uid,"V914");
				if ($myENCUID == $pEncUID) 
				{
					return $uid;
				}
			}//while(list($uid) = $this->ADb->fetch_array($Result)) 		}
		}
		return "";
	}
	
	function getUserInfoByEncUID($pEncUID) 
	{
		$strSQL	= 	"Select		UID		from		User		Where			UType	= \"CUST\"		and
					IsActive=1";
 	    $Result	= $this->ADb->query($strSQL);		
		if($Result)
		{
			$UserInfo = array();
			while(list($uid) = $this->ADb->fetch_array($Result)) 
			{		
				$myENCUID	= crypt($uid,"Phone");
				if ($myENCUID == $pEncUID) 
				{
					$strSQL	= 	"Select	CustomerID,	UID,	AddressID,
								CFName,	CMName,	CLName,	CEmail
								from	Customer	Where CEmail= \"$uid\"";
			 	    $Result2 = $this->ADb->query($strSQL);		
					if ($Result2 && $row=$this->ADb->fetch_array($Result2)) 
					{
						$UserInfo[CustomerID]	= $Result2[1][0];
						$UserInfo[UID] 	= $Result2[1][1];
						$UserInfo[AddressID]	= $Result2[1][2];		
						$UserInfo[FName]	= $Result2[1][3];
						$UserInfo[MName]	= $Result2[1][4];
						$UserInfo[LName]	= $Result2[1][5];
						$UserInfo[Email]	= $Result2[1][6];
					}
		}



			}//while(list($uid) = $this->ADb->fetch_array($Result)) 
		}


	for (my $nIndex=0; $nIndex <= count($Result); $nIndex++) {
		
	dPrint "\$Result[1][0]: $Result[1][0]";
	return %UserInfo;
}#getUserInfoByEncUID
*/
	

	


public static function login($uid_or_email,$pwd) {

	if($uid_or_email==""){
		$statusCode = 1;
    $statusMsg  =  "Username can't be blank";

    $response = [
    'code'    => $statusCode,
    'message' => $statusMsg,
    'success' => false,
    'error'   => true
  	];

  	return json_encode($response);
   }

   if($pwd==""){
		$statusCode = 2;
    $statusMsg  =  "Password can't be blank";

    $response = [
    'code'    => $statusCode,
    'message' => $statusMsg,
    'success' => false,
    'error'   => true
  	];

  	return json_encode($response);
   }
    
    $strSQL = "select user.UID, user.Pass, user.Type,user.IsActive from customer,user where user.uid=customer.uid and user.type = 'CUST'
                   and (customer.cemail='".$uid_or_email."' or customer.uid='".$uid_or_email."') AND pass='".$pwd."'";
           $db =  new ADb();
   //$Result	= $this->ADb->query($strSQL);
           $Result	= $db->query($strSQL);

   if ($Result->recordCount() > 0){
		    $statusCode = NULL;
		    // when 
		    // if(!$User[IsActive])
		    if (!$Result->fields[3]){
		        // user is not active
		        $statusCode = 4;
		        $statusMsg  =  "Account Inactive <a href='".$GLOBALS['website_url']."/confirm'>Click Here</a> to activate.";

		        $response = [
		        'code'    => $statusCode,
		        'message' => $statusMsg,
		        'success' => false,
		        'error'   => true
		      	];

		      	return json_encode($response);
		    }

		    // deleted user functionality
		    $ffUID = $uid_or_email;
		    $myOrder   = new Orders();
		    $Hash = $myOrder->getOrdersInfoByUID($ffUID);
	        if($Hash['IsDeleted']==1)
	        {
			        // user is deleted
			        $statusCode = 5;
			        $statusMsg  =  "Account Deleted Please contact <a href='mailto:sales@didx.net' >sales@didx.net</a>";

			        $response = [
			        'code'    => $statusCode,
			        'message' => $statusMsg,
			        'success' => false,
			        'error'   => true
			      	];

			      	return json_encode($response);
	        }

	        // only buyer are allowed to login 
	        if($Hash['CusType']!=0)
	        {
			        // customer type must be buyer
			        $statusCode = 6;
			        $statusMsg  =  "User account must be buyer.";

			        $response = [
			        'code'    => $statusCode,
			        'message' => $statusMsg,
			        'success' => false,
			        'error'   => true
			      	];

			      	return json_encode($response);
	        }


		    $statusMsg = "Successfully login";
		    $response = [
		        'code'    => $statusCode,
		        'message' => $statusMsg,
		        'success' => true,
		        'error'   => false,
		        'location' => "home"
		      ];


		} else {
		    // user does not exists
		    $statusCode = 3;
		    $statusMsg  =  "Login ID / Password does not matched!";
		    $response = [
		        'code'    => $statusCode,
		        'message' => $statusMsg,
		        'success' => false,
		        'error'   => true
		      ];
		}

   return json_encode($response);
}



function getUIDByEncUID($pEncUID) 
	{
		$strSQL	= "Select uid	from	user Where	Type	= 'CUST' and isactive=1";
		$this->dPrint ("\$strSQL: $strSQL");
 	    $Result	= $this->ADb->query($strSQL);		
		if($Result===false){return "";}
		else
		{
			$UserInfo = array();
			while(!$Result->EOF) 
			{
				$uid = $Result->fields[0];		
				$myENCUID	= crypt($uid,"V914");
				$this->dPrint ("\$myENCUID: $myENCUID");
				$this->dPrint ("\$pEncUID: $pEncUID");				
				if ($myENCUID == $pEncUID) 
				{
					return $uid;
				}
				$Result->MoveNext();
			}//while(list($uid) = $this->Db->fetch_array($Result)) 
		}
		return "";
	}#getUserInfoByEncUID
	
	function getUIDByEncUIDMD1($pEncUID) 
	{
		$strSQL	= "CALL getUIDByEncUID(\"$pEncUID\") ";
		//echo $strSQL;
 	    $Result	= $this->ADb->query($strSQL);		
 	    print_r($Result);
		if($Result===false){return "";}
		else
		{
			$uid = $Result->fields[0];		
			return $uid;
		}
	}#getUserInfoByEncUID
	
		function getUIDByEncUIDMD2($pEncUID) 
	{
		$strSQL	= "Select uid	from	user Where	(Type	= 'CUST' or  Type = 'ADMI')  and MD5(concat(uid,pass))=\"$pEncUID\" ";
		#echo $strSQL;
 	    $Result	= $this->ADb->query($strSQL);		
		if($Result===false){return "";}
		else
		{
			$uid = $Result->fields[0];		
			return $uid;
		}
	}#getUserInfoByEncUID
	
			function getUIDByEncUIDMD($pEncUID) 
	{
		$strSQL	= "Select 
						uid,
						pass,
						type,
						isactive,
						isremoved,
						confcode,
						datetime	from	user Where	(Type	= 'CUST' or  Type = 'ADMI')  and MD5(concat(uid,pass))=\"$pEncUID\" ";
		#echo $strSQL;
 	    #$Result	= $this->ADb->query($strSQL);		
	$Result=$this->ADb->query($strSQL);
		$Hash = array()	;
		if(!$Result->EOF)
		{
			$Hash[UID]	= $Result->fields[0];
			$Hash[Pass]	= $Result->fields[1];
			$Hash[Type]	= $Result->fields[2];
			$Hash[IsActive]	= $Result->fields[3];
			$Hash[IsRemoved]	= $Result->fields[4];
			$Hash[ConfCode]	= $Result->fields[5];
			$Hash[DateTime]	= $Result->fields[6];
		}
		return $Hash;
	}#getUserInfoByEncUID


	function randomString($length, $type = '') {
  // Select which type of characters you want in your random string
  switch($type) {
    case 'num':
      // Use only numbers
      $salt = '1234567890';
      break;
    case 'lower':
      // Use only lowercase letters
      $salt = 'abcdefghijklmnopqrstuvwxyz';
      break;
    case 'upper':
      // Use only uppercase letters
      $salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      break;
    default:
      // Use uppercase, lowercase, numbers, and symbols
      $salt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      break;
  }
  $rand = '';
  $i = 0;
  while ($i < $length) { // Loop until you have met the length
    $num = rand() % strlen($salt);
    $tmp = substr($salt, $num, 1);
    $rand = $rand . $tmp;
    $i++;
  }
  return $rand; // Return the random string 
         // return $this->view->render($response, 'AdBanner', $PH);
    }


   function GetAffCode(){
   	$UID=currentUser();
   	$myADb=new ADb();
   	$strSQL="Select AffliationCode from orders where UID='$UID'";
	$Result=$myADb->ExecuteQuery($strSQL);
	$AffliationCode=$Result->fields[0];
	return $AffliationCode;

   }

   function updateAffCode($AffCode){
   	$UID=currentUser();
   	$myADb=new ADb();
   	$str="Update orders SET AffliationCode='$AffCode' WHERE UID='$UID'"; 
   //echo $str;
    $Result=$myADb->ExecuteQuery($str);
    
   }

   public function getKey($var) {
		return substr(md5($var.time()), 0, 10);
	}

   public function StoreFileToDB($Comments,$file1) {
   	$myADb=new ADb();
   	$myComplain=new Complain();
   	$MyUser=new User();
   	$UID=currentUser();
	//global $myADb,$UID,$Comments,$file1, $fileglobal,$myComplain;
	
		
	$ODID = $MyUser->getKey($UID);
	// insert file 
	  $strSQL = "insert into orderdocs(ODID,OrderID,AUID,Type,Path,Comments,Date,FileName)
	values(\"$ODID\",  \"$UID\", \"$UID\", \"MISC\", \"\", \"$Comments\",now(),\"$file1\" )";
	//echo $strSQL;
	$Result = $myADb->ExecuteQuery($strSQL);	
	
	
	$NewComplainID = $myComplain->getNewComplainID($UID);

	$Complain['ComplainID'] = $NewComplainID;
	$Complain['OID'] = $UID;
	$Complain['AUID'] = $UID;
	$Complain['Assign'] = "Tier1";
	$Complain['Type'] = "COMP";
	$Complain['Complain'] = "Customer has uploaded contract documents. Details: $Comments";
	$Complain['Notify'] = "1";
	$NewComplainID = $myComplain->addComplain($Complain);
	
}

public function GetUploadDocuments(){
	$myADb=NEW ADb();
	$UID=currentUser();
	$strSQL="SELECT Comments,Date,FileName FROM orderdocs WHERE OrderID=$UID";
	$Result = $myADb->ExecuteQuery($strSQL);
	//$Result['Type'];
	return $Result;
}

	

}
?>
