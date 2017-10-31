<?php
class Address
{
    // 	Copyright©2011 Huzoor Bux Panhwar. All rights reserved.
	var $fDebug = 1;
	function dPrint($str) 
    {
        if($this->fDebug) echo "$str<br>\n";
    }
    function Address($dbg=0)
	{
  //       include_once("Const.inc.php");
		// include_once($GLOBALS['INCLUDEPATH']."/ADb.inc.php");
		$this->Db = new ADb();
	}
    
    function GetAddressByOID($OID)
    {
        $strSQL	= "	select concat(street1,',',street2,',',city,',',state,',',zipcode,',',country) from address,customer where address.addressid=customer.addressid and customer.uid=\"$OID\"";
        $Result = $this->Db->ExecuteQuery($strSQL);	
		return $Result->fields[0];	
	}
    function addAddress($Hash)
    {
        $strSQL	= "insert into address
		            (addressid,	street1, street2, city,	state, zipcode,	country)
					values ('".$Hash["AddressID"]."', '".str_replace("'","\'",$Hash["Street1"])."',
					'".str_replace("'","\'",$Hash["Street2"])."', '".$Hash["City"]."', '".$Hash["State"]."',
					'".$Hash["ZipCode"]."', '".$Hash["Country"]."')";
		$rs = $this->Db->ExecuteQuery($strSQL);	
		return $rs;
	}
    function getAddressInfo($pAddressID)
    {
        $strSQL	= "	select
		            addressid,
					street1,
					street2,
					city,
					state,
					zipcode,
					country
					from
					address
					where
					addressid=\"$pAddressID\"";
        $Result	= $this->Db->ExecuteQuery($strSQL);
		
        $Hash=array();
		$Hash['AddressID']	= $Result->fields[0];
		$Hash['Street1']	= $Result->fields[1];
		$Hash['Street2']	= $Result->fields[2];
		$Hash['City']	= $Result->fields[3];
		$Hash['State']	= $Result->fields[4];
		$Hash['ZipCode']	= $Result->fields[5];
		$Hash['Country']	= $Result->fields[6];
		$arf = $Hash['AddressID'];
		#print "\$arf : $arf ";
		return $Hash;
	
	}
    function getAddressInfoByOID($pOID)
    {
        $strSQL	= "	select
		            address.addressid,
					street1,
					street2,
					city,
					state,
					zipcode,
					country
					from
					address,customer
					where
					address.addressid=customer.addressid and customer.uid=\"$pOID\" ";
        $Result	= $this->Db->ExecuteQuery($strSQL);
		$Hash=array();
		$Hash['AddressID']	= $Result->fields[0];
		$Hash['Street1']	= $Result->fields[1];
		$Hash['Street2']	= $Result->fields[2];
		$Hash['City']	    = $Result->fields[3];
		$Hash['State']	    = $Result->fields[4];
		$Hash['ZipCode']	= $Result->fields[5];
		$Hash['Country']	= $Result->fields[6];
		$arf                = $Hash['AddressID'];
		
        return $Hash;
    }
    function GetCustomerCountry($UID)
    {
        $strSQL	= "	select
		            address.country
					from
					address,customer
					where
					address.addressid=customer.addressid and customer.UID='$UID'";
        
        $Result	= $this->Db->ExecuteQuery($strSQL);
        return $Result->fields[0];
    }
    function editAddress($pHash)
    {
        $strSQL = "update address
                   set
                   street1    = \"$pHash[Street1]\",
                   street2    = \"$pHash[Street2]\",
                   city    = \"$pHash[City]\",
                   state    = \"$pHash[State]\",
                   zipcode    = \"$pHash[ZipCode]\",
                   country    = \"$pHash[Country]\"
                   where
                   addressid = \"$pHash[AddressID]\"";    
        //echo $strSQL;
        $rs = $this->Db->ExecuteQuery($strSQL);
        return $rs;
    }
    function getAddressString($OID)
    {
        $strSQL="select concat(street1,' ',street2,' ',zipcode,' ',city,' ',country) 
		        from address,customer where 
                customer.addressid=address.addressid 
                and customer.uid='$OID' ";
        
        $Result	= $this->Db->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}
    function getTelephoneNumber($OID)
    {
        $strSQL="select CTelHome from customer where uid='$OID' ";
		$Result	= $this->Db->ExecuteQuery($strSQL);
		return $Result->fields[0];
	}

	function GetCorrectAddress($CardAddID,$CusAddID){
            
            //global $myADb,$myAddress;
    
            $strSQL = "select AddressID from address where AddressID=\"$CardAddID\" ";
            
            $Result    = $this->Db->ExecuteQuery($strSQL);
            
            if($Result->EOF){
                
                        $strSQL = "select AddressID from address where AddressID=\"$CusAddID\" ";
                        
                        $Result    = $this->Db->ExecuteQuery($strSQL);
     
                        if($Result->EOF){
                                 $CCAddress    = $this->getAddressInfo($CusAddID);
                        }
    
         
            }else{
                
                $CCAddress    = $this->getAddressInfo($CardAddID);
                
            }


            return $CCAddress;
            
            
	}
}
?>
