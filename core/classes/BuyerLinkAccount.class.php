<?php
class BuyerLinkAccount
{
 function VLinkAccount($UID)
    {
		$myADb=new ADb();
		$strSQL = "select * from VLinkAccount where Vendor=\"$UID\" ";

		$Result = $myADb->Executequery($strSQL);
		return $Result;
	}

 function DeleteVLink($ID,$ffOID)
 	{	
	 	$myADb=new ADb();
	 	$strSQL = "delete  from VLinkAccount where ID=\"$ID\" and Vendor=\"$ffOID\"  ";
		$Result = $myADb->Executequery($strSQL);
		return $Result;
    }

 function GetVLink($ffOID)
	{
    	$myADb=new ADb();
	 	
		$strSQL = "select * from VLinkAccount where Vendor=\"$ffOID\"  ";
		
		$Result = $myADb->Executequery($strSQL);
		return $Result;
    }

 function UpdateVLink($Auto,$AutoAmount,$Limit,$AlertAmount,$ffOID)
 	{
 	    $myADb=new ADb();
		$strSQL = "update VLinkAccount set  AmountLimit=\"$Limit\",AutoTransfer=\"$Auto\",AutoTransAmount=\"$AutoAmount\",Alert=\"$AlertAmount\" where Vendor=\"$ffOID\" ";
		$Result = $myADb->Executequery($strSQL);
		return $Result;
 	}

 function insertVLink($Auto,$AutoAmount,$Limit,$AlertAmount,$ffOID)
    {
	 	$myADb=new ADb();
	 	$strSQL = "insert into VLinkAccount(Vendor,LinkAccount,AmountLimit,AutoTransfer,AutoTransAmount,Alert) 
				values(\"$ffOID\", \"$BuyerOID\", \"$Limit\",\"$Auto\",\"$AutoAmount\" ,\"$AlertAmount\")";
	    $Result = $myADb->Executequery($strSQL);
	    return $Result;
    }

 function GetAllLinkedAccounts($ffOID)
   {
		$myADb=new ADb();
		$strSQL = "select LinkAccount,AmountLimit,ID,AutoTransAmount,AutoTransfer,Alert from VLinkAccount where Vendor=\"$ffOID\" ";
		$Result = $myADb->ExecuteQuery($strSQL);
		return $Result;
   } 

 function SwitchAccount($Sw,$Login) 
   {

   		$myADb=new ADb();
   		$UID=currentUser();
   		$strSQL = "select LinkAccount from VLinkAccount where MD5(CONCAT(id,LinkAccount))=\"$Sw\" AND  SUBSTRING(MD5(CONCAT(Linkaccount,id)),1,10)=\"$Login\" and Vendor=\"$UID\" ";

		$Result = $myADb->ExecuteQuery($strSQL);
		if($Result->EOF){

		return  "-1";

		}
	
		$SwitchAccount = $Result->fields[0];

		$strSQL = "select uid,pass from user where uid=\"$SwitchAccount\" ";

		$Result = $myADb->ExecuteQuery($strSQL);
		
		$UserID = $Result->fields[0];
		$Pass = $Result->fields[1];

		
		setcookie('vplc', '', time()-3600, '/', '.didx.net');

		setcookie('MyUID', '', time()-360000, '/', '.didx.net');
		
	#	echo "Please wait while we switch you to buyer's account";
		
		$EncUID = MD5($UserID.$Pass);

        // $RedirectTo=$response->withRedirect('/home');
        $RedirectTo="/home";

		setcookie('vplc', $EncUID, time()+3600, '/', '.didx.net');

		setcookie('MyUID', $UserID, time()+360000, '/', '.didx.net');
		
		print "<meta http-equiv=refresh content='0;url=$RedirectTo?aa=$EncUID'>";
		exit;

   }


}

?>