<?php

function ForgetPass($key)
{

	$myADb=new ADb();

    if($key == ""){
        echo "<font color=red><b>ERROR:</b> Invalid userid or email.</font>";
        exit();
    }

 // $isValidEmail=isValidEmail($email);
 if(filter_var($key, FILTER_VALIDATE_EMAIL))                             
	{
    $strSQL = "SELECT user.uid,customer.cemail FROM user ,customer WHERE user.UID=customer.UID AND customer.CEmail=\"$key\"";
   // mysql_real_escape_string(htmlentities($key))  //@todo thsi function
    


	}
else
	{
    $strSQL = "SELECT user.uid,customer.cemail FROM user ,customer WHERE user.UID=customer.UID AND customer.uid=$key";
    // '".mysql_real_escape_string(htmlentities($key))."'";
    
	}

$Result = $myADb->ExecuteQuery($strSQL);

if(!$Result->EOF)
	{
        
    $OID = $Result->fields[0];

    echo "Check your email for the confirmation link.";
    // file_get_contents($GLOBALS['website_admin_url']."SendLostPasswordLink.php?OID=$OID"); 
    // file_get_contents("c\xampp\htdocs\didx_customer_web_portal\src\public"."\SendLostPasswordLink.php?OID=$OID"); 
    
    (file_get_contents($GLOBALS['website_url']."/SendLostPasswordLink?OID=$OID"));
    
	}
else
	{
        
    echo "<font color=red><b>ERROR:</b> Invalid userid or email.</font>";
    // exit;
	}

}


// function PassowrdLink()
//     {
//     $myEmail = new Email();
//     echo $myEmail->SendLostPasswordLink($_GET['OID']);  
//     }



function isValidEmail($email)
{
    $abc= preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);

}

?>