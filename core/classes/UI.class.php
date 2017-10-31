<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
//#include_once $INCLUDEPATH."/ADb.inc.php";
//include_once $INCLUDEPATH."/Rights.inc.php";
class UI
{
	// 	Copyright�2011 Huzoor Bux. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function aDPrint($arr) { if($this->fDebug){	var_dump($arr);	}}	
	
	function UI(){
		
        $this->Db = new ADb();
		#$this->Db = new ADb();

		$this->myAdminRights = new Rights();
		
	}
	
	
	function AdminUser()
	{
		$this->Db = new ADb();
		$this->myAdminRights = new Rights();
	}	

function getAccessForbiddenError(){
	
	echo"
<html>
<body>
<H1>Access Forbidden</H1>
</body>
</html>";
	

	
	exit;
}



function getSessionErrorMessage($pOID="",$pLink="") {
	
	$strSQL = "select * from LastLink where OID=\"$pOID\" ";
	#echo "\$strSQL: $strSQL";
	$Result = $this->Db->ExecuteQuery($strSQL);
	
		if($Result->EOF)
				$strSQL = "insert into LastLink(OID,Link) values(\"$pOID\",\"$pLink\") ";
		else
				$strSQL = "update LastLink set link = \"$pLink\" where OID=\"$pOID\" ";
	
#	echo "\$strSQL: $strSQL";			
	$Result = $this->Db->ExecuteQuery($strSQL);		
	
	
	
	#print "<center><br>Error: Session expired or Not Logged in. ";
	#print "<br>Redirecting to Login Page...";
	print "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"0\">
<tr>
	<td align=center><a href=\"http://sandbox.didx.net\" ><img border=0 src=\"http://sandbox.didx.net/images/sessionerror.gif\"></a></td>
  </tr>
</table>";
	echo "<meta http-equiv=refresh content=0;url=http://sandbox.didx.net/login/>";
	exit;
	
	
	
}#getSessionErrorMessage


#	getSessionHeader
#	IN:	Hash->CGI
#	OUT:	HeaderHTML




function getSessionHeader($pHash,$pTitle="") {
    
    
    $myAdminRights = new Rights();
    
    if($pHash['HEADTitle']=="")
                $pHash['HEADTitle']=$pTitle;
                
                 $strSQL = "select Counter from `AdminCounter` where CountID='AllUnResolvedComplains'";
     $Result = $this->Db->ExecuteQuery($strSQL);

     $Complains = $Result->fields[0];

    $html;
    $html    = "<html>\n";
    $html    .= "<head>\n";
    $html    .= "<title>" . $pHash['HEADTitle'] . "</title>\n";
    $html    .= "<meta http-equiv='Content-Type' content='text/html\; charset=iso-8859-1'>\n";
    
    $html    .= "<meta http-equiv= 'cache-control' content= 'max-age=0' />";
    $html    .= "<meta http-equiv= 'cache-control' content= 'no-cache' />";
    $html    .= "<meta http-equiv= 'expires' content= '0' />";
    $html    .= "<meta http-equiv= 'expires' content= 'Tue, 01 Jan 1980 1:00:00 GMT' />";
    $html    .= "<meta http-equiv= 'pragma' content= 'no-cache' />";

    $html    .= "<meta http-equiv='no-cahe' content='0'>\n";
    $html    .= "<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>\n";
    $html    .= "<script src='/admins/validate.js'></script>\n";
    $html    .= "<script src='/admins/AdminTickets.js'></script>\n";
    $html    .= "<link rel='stylesheet' href='/admins/images/style.css' type='text/css'>
    <link type='text/css' rel='stylesheet' href='/tmpl/css/style7.css' />
    <link type='text/css' rel='stylesheet' href='/css/tablestyledidx.css' />
    <link rel='stylesheet' rev='stylesheet' href='/admins/images/lofiscreen.css' media='screen'>
<script language='javascript' src='/admins/images/floatbar.js'></script>\n";
    $html    .= "   <script src=\"https://www.google.com/jsapi\"></script>
<script>
  
google.load(\"jquery\", \"1.3.2\");
</script>\n";
$html    .= "<script src='/admins/mypager.js'></script>\n";
    $html    .= "
    <script language=\"JavaScript\" type=\"text/JavaScript\"> 
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName==\"Netscape\")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
//-->
</script> 
<style> 
#didadmin{width:185px; float:none; position:relative;}
#didadmin ul{ padding-left:5px;}
#didadmin ul li{ background:url(\"/admins/images/arrow.gif\") no-repeat scroll 0 7px #FFFFFF; margin-bottom:4px; background-color:#92bed7; line-height:30px; list-style-type:none; width:156px; padding-left:25px;}
#didadmin ul li.abc{ background:url(\"/admins/images/arrow.gif\") no-repeat scroll 0 7px #FFFFFF; margin-bottom:4px; background-color:#92bed7; line-height:15px; list-style-type:none; width:183px; padding-left:25px;}
#didadmin ul li:hover{ background-color:#DAEAF7;}
#didadmin ul li a{ font-family:Verdana, Arial, Helvetica, sans-serif; color:#003366; text-decoration:none; font-size:10px; font-weight:bold; text-decoration:none;}
#didadmin ul li a:hover{ text-decoration:underline;}
</style> 
<script>

function ClearSearchBar(OP) {
    
    if(OP==1){
                SearchBoxValue = document.getElementById('HeaderSearchBox').value;
                
                if(SearchBoxValue=='DID Number'){
                    document.getElementById('HeaderSearchBox').value = '';
                }
    
    }
    
    if(OP==2){
                SearchBoxValue = document.getElementById('HeaderSearchBox').value;
                
                if(SearchBoxValue==''){
                    document.getElementById('HeaderSearchBox').value = 'DID Number';
                }
    
    }
    
}


</script>
";    
    $html    .= "</head>\n";
    $html    .= "<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' ONLOAD=\"getNewContent('".$pHash['ffOID']."',5,-1,-1);\">
    
<table width='100%' border='0' cellpadding='0' cellspacing='0'>
  <tr> 
    <td height='2' valign='top'> 
      <table width='100%' height='85' border='0' cellpadding='0' cellspacing='0'>
        <tr> 
          <td width='263' height='85' rowspan='2' background='/admins/images/DIDX.jpg'>&nbsp;</td>
          <td valign='middle'  style=\"background-image:url(/tmpl/images/blueebg.gif); background-repeat:repeat-x\"><table width='100%' height='78' border='0' cellpadding='0' cellspacing='0'>

            <tr>
              <td width='41%' valign='middle'>
                <table width=\"100%\" height=\"86\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                  <tr>
                    <td valign=\"top\"><div id=\"menubar_container\">
                      <div id=\"fb_menubar\" class=\"clearfix\">
                          <div id=\"fb_menubar_core\">
                            <div class=\"fb_menu\" id=\"fb_menu_home\">
                              <div class=\"fb_menu_title\"><a href=\"/admins/home\"><span class=\"menu_title\">Home</span></a></div>
                            </div>
                            <div class=\"fb_menu\" id=\"fb_menu_home\">
                            
                            </div>
                            <div class=\"fb_menu\" id=\"fb_menu_home\">
                              <div class=\"fb_menu_title\">
                              <a href=\"/admins/ComplainsReport.php\">
                              <span class=\"menu_title\">Complains ($Complains)</span></a></div>
                            </div>
                          </div>
                      </div></td>
                    <td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td valign=\"top\"> <form action='/admins/DIDInfo.php' method='get' name='form3' id='form3'>

                                <div align=\"left\">
                                 <input name=\"did\" type=\"text\" autocomplete=\"off\" style=\"border:thin; border-color:#333333; font-family:'lucida grande', tahoma, verdana, arial, sans-serif; font-size:11px; color:#777777\" value=\"DID Number\" id=\"HeaderSearchBox\" OnClick=\"ClearSearchBar('1');\" onBlur=\"ClearSearchBar('2');\">
                                 &nbsp; <input onClick='correctDIDNumber();' type='submit' value='Search' name='Submit22' style=\"background-color:#5c74a3; font-family:'lucida grande', tahoma, verdana, arial, sans-serif; font-size:11px; color:#FFFFFF; border-style:thin; border-color:#003366; border-width: 1px; font-weight: bold;\"/> <img src='http://sandbox.didx.net/admins/images/spacee.gif' border='0'>                                </div>
                          </form> </td>
                     
                     
                      <form name='SearchOrder' action='' method='post'>  <td valign=middle><div align='right'>
                        <input name=\"KeyVal2\" type=\"text\" style=\"border:thin; border-color:#333333; font-family:'lucida grande', tahoma, verdana, arial, sans-serif; font-size:11px; color:#777777\" value=\"Search Orders\" id=\"KeyVal\" onKeyPress=\"return handleKeyPress(event,this.form)\"   onBlur=\"if(this.value=='') this.value='Search Orders';\" onFocus=\"if(this.value=='Search Orders') this.value='';\"></div></td>
                        <td valign=\"top\">
                        <table><tr height=1><td></td></tr></table>  <div align=\"left\">
                          <a href=\"javascript:void(0);\" onclick=\"getNewContentSearch(-1,'','');\" ><img src='/inbox/go.gif' width='21' height='21' name='image' /></a>
                            
                            &nbsp;<a href='javascript:void(0);' OnClick=\"GotOPages('1');\"><img src=\"/admins/images/vieworder.gif\" alt=\"View\"></a>
                         &nbsp;<a href='javascript:void(0);' OnClick=\"GotOPages('2');\"><img src=\"/admins/images/editOrder.gif\" alt=\"Edit\"></a>
                        &nbsp;<a href='javascript:void(0);' OnClick=\"GotOPages('3');\"> <img src=\"/admins/images/ordercomplain.gif\" alt=\"Complain\"></a>
                        &nbsp;<a href='javascript:void(0);' OnClick=\"GotOPages('4');\"><img src=\"/admins/images/ClientLedger.gif\" alt=\"Client Ledger\"></a>
                           
                        </td></form>
                        <td valign=\"top\">                         </td>
                      </tr>
                    </table>
                    </td>
                  </tr>
                </table>
                </td>
            </tr>
          </table></td>
        </tr>
      
      </table>

    </td>
  </tr>
  <tr> 
    <td valign='top' height='2'> 
      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td background='/admins/images/navbasebg.jpg' height='29'><div align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'><b><font size='3' face='Arial, Helvetica, sans-serif'>Wholesale Phone Numbers Solution For Internet Telephone Service Providers.</font></b></font></div></td>
        </tr>
      </table>
      
    </td>

  </tr>
</table>

 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
  <tr> 
    <td valign='top' background='/admins/images/topnavbg.jpg' width='183'> 
      <table border='0'>
        <tr> 
          <td valign='top'> 
            <table border='0' cellpadding='1' cellspacing='1' align='center'>
              <tr>              
        
              </tr><div id='didadmin'> 
<ul>";
               

     #    <td bgcolor='#9999CC'><font color='#FFFFFF' face='Verdana, Arial, Helvetica, sans-serif' size='-1'>::Navigation</font></td>    
    #<TD><INPUT name=Submit onclick='return window.open(TaskManager.cgi);' style='BACKGROUND-COLOR: #8000ff; BORDER-BOTTOM: #ffffff 2px groove; BORDER-LEFT: #ffffff 2px groove; BORDER-RIGHT: #ffffff 2px groove; BORDER-TOP: #ffffff 2px groove; COLOR: #ffffff; FONT: 9px Verdana,Geneva,sans-serif' type=submit value='Task Manager >>'></TD>
   
   #below one is fine 
   #<td><input type=\"submit\" name=\"Submit\" value=\"Task Manager >>\" onClick=\"return window.open('TaskManager.cgi');\" style='font: 9px Verdana,Geneva,sans-serif; color: #ffffff; background-color: #8000FF; border-color: #FFCC00; border: 2px groove #FFFFFF;'></td>\n
    $Label;
    $Link;
    $ThisAdminRights    = $myAdminRights->getAdminRights($pHash['AUID']);
    
    $Link    = "Home.php";
    $Label    = "Home";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
              
    $strSQL = "select Counter from `AdminCounter` where CountID='AllBackOrders'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $BackOrder = $Result->fields[0];
    
    $Label    = "Pending Back Orders ($BackOrder)";
    $Link    = $GLOBALS['website_admin_url']."BackOrderClients.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
     $strSQL = "select Counter from `AdminCounter` where CountID='DocumentsApproval'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $DocApproval = $Result->fields[0];                 
    
    $Label    = "Document Approval ($DocApproval)";
    #$Link    = $GLOBALS['website_admin_url']."DocsUploadedByUse.php"; ///// redirecting to didx thats why poping up a no session error
    $Link    = "/admins/DocsUploadedByUse.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
    $strSQL = "select count(*) from creditcard_temp where Status=0";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $CC_Approval = $Result->fields[0];
    
    $Label    = "CreditCards Approval ($CC_Approval)";
    #$Link    = $GLOBALS['website_admin_url']."CC_Approved.php"; ///// redirecting to didx thats why poping up a no session error
    $Link    = "/admins/CC_Approved.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
    $Label    = "Document Cabinet";
    #$Link    = $GLOBALS['website_admin_url']."SearchDocuments.php";///// redirecting to didx thats why poping up a no session error
    $Link    = "/admins/SearchDocuments.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
     $strSQL = "select Counter from `AdminCounter` where CountID='VendorPaymentsRequest'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $VReq = $Result->fields[0];                 
     
      
    
    
    $Label    = "Vendor Payment <br>Requests($VReq)";
    #$Link    = $GLOBALS['website_admin_url']."PaymentReq.php";///// redirecting to didx thats why poping up a no session error
    $Link    = "/admins/PaymentReq.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
  
  
  $strSQL = "select Counter from `AdminCounter` where CountID='TotalSuspendedOrders'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $SusOrders = $Result->fields[0];                 
     
      
    
    
    $Label    = "Suspended Accounts ($SusOrders)";
    #$Link    = $GLOBALS['website_admin_url']."OrdersStatus.php?Status=6";///// redirecting to didx thats why poping up a no session error
   
    $Link    = "/admins/OrdersStatus.php?Status=6";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
     
     
     $strSQL = "select Counter from `AdminCounter` where CountID='PartnerPaymentsRequest'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $PReq = $Result->fields[0];                 
              
              
  $Label    = "Partner Sites <br>Payment Requests($PReq)";
    #$Link    = $GLOBALS['website_admin_url']."PaymentReqPartner.php";///// redirecting to didx thats why poping up a no session error
   
    $Link    = "/admins/PaymentReqPartner.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
     $strSQL = "select Counter from `AdminCounter` where CountID='LNP'";
     $Result = $this->Db->ExecuteQuery($strSQL);

     $LNP = $Result->fields[0];
    
    $Label    = "LNP Requests ($LNP)";
    $Link    = $GLOBALS['website_admin_url']."LNPRequestsReport.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
              



    
    $Label    = "Complains ($Complains)";
    $Link    = $GLOBALS['website_admin_url']."Home.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
              
  $strSQL = "select Counter from `AdminCounter` where CountID='VendorDocuments'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $VDocs = $Result->fields[0];                
       
  $Label    = "Police Inquiries ($VDocs)";
    $Link    = $GLOBALS['website_admin_url']."ViewAllDocs.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
              
              
 $strSQL = "select Counter from `AdminCounter` where CountID='RefundRequests'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $VDocs = $Result->fields[0];                
       
  $Label    = "Refund Requests ($VDocs)";
    $Link    = $GLOBALS['website_admin_url']."ClaimRefundClients.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
              
              

              
 $strSQL = "select Counter from `AdminCounter` where CountID='RefundClaimCount'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $Claims = $Result->fields[0];                
     
     if($Claims=="")
            $Claims=0;
     
       
  $Label    = "Refundable Claim ($Claims)";
    #$Link    = $GLOBALS['website_admin_url']."ClientRefundClaims.php";///// redirecting to didx thats why poping up a no session error
   
    $Link    = "/admins/ClientRefundClaims.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
              
              
              
                             
 $strSQL = "select Counter from `AdminCounter` where CountID='ZeroVendorRating'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $VRating = $Result->fields[0];
     // RecordCount();
       
  $Label    = "Zero Vendor Rating($VRating)";
    $Link    = $GLOBALS['website_admin_url']."VRatingZeroVendor.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";

     $strSQL = "select Counter from `AdminCounter` where CountID='ServiceNotices'";
                     
     $Result = $this->Db->ExecuteQuery($strSQL);
    
     $VService = $Result->fields[0];
       
  $Label    = "Service Notices ($VService)";
    $Link    = $GLOBALS['website_admin_url']."ServiceNoticesToClients.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";

    $Label    = "Client News Feeds";
    #$Link    = $GLOBALS['website_admin_url']."ClientNewsFeeds.php";///// redirecting to didx thats why poping up a no session error
   
    $Link    = "/admins/ClientNewsFeeds.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";

              
    # Add Admin
    if ((!$ThisAdminRights['AADM'])&&(!$ThisAdminRights['EADM'])){
    }
    else{
    $Label    = "Admin Manager";
    $Link    = "/admins/AddAdmin.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    }
    # LNP Requests
    if ((!$ThisAdminRights['AADM'])&&(!$ThisAdminRights['EADM'])){
    }
    else{
    
    }
        # LRequested DID
    if ((!$ThisAdminRights['AADM'])&&(!$ThisAdminRights['ADM'])){
    }
    else{
    $Label    = "Requested DID";
    $Link    = $GLOBALS['website_admin_url']."ViewRequestedDID.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    }
    
    $Label    = "Tools";
    $Link    = $GLOBALS['website_admin_url']."toolbox.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
    
#    if ($ThisAdminRights{MORD}){
#    # Order Manager
#    $Label    = "Order Manager";
#    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/OrderManager.cgi";
#    $html    .= "<tr onMouseover=\"this.bgColor='#DAEAF7'\" onMouseout=\"this.bgColor='#92BED7'\"><td height='30' valign='middle' align='left' NOWRAP><div align='left'><a href='$Link'><font face='Verdana, Arial, Helvetica, sans-serif' size='1'><img src=$GLOBALS['website_admin_url']images/arrow.gif width='20' height='10' border='0'><font color='#003333'><b><font color='#003366'>$Label</font></b></font></font></a></div></td></tr><tr> 
#                <td bgcolor='#FFFFFF' NOWRAP></td>
#              </tr>\n";
#    }
    # Add Order
    if ($ThisAdminRights['AORD']){
    $Label    = "Add Order";
    $Link    = $GLOBALS['website_admin_url']."AddOrder2.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    }
    
    # Super User
    if ($ThisAdminRights['SUPE']){
    $Label    = "Send Email";
    $Link    = $GLOBALS['website_admin_url']."SendEveryoneEmail.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    }

    # Delete Order
#    if (($ThisAdminRights{SUPE})||($ThisAdminRights{DORD})){
#    $Label    = "Change Status";
#    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/DeleteOrderForm.cgi";
#    $html    .= "<tr onMouseover=\"this.bgColor='#DAEAF7'\" onMouseout=\"this.bgColor='#92BED7'\"><td height='30' valign='middle' align='left' NOWRAP><div align='left'><a href='$Link'><font face='Verdana, Arial, Helvetica, sans-serif' size='1'><img src=$GLOBALS['website_admin_url']images/arrow.gif width='20' height='10' border='0'><font color='#003333'><b><font color='#003366'>$Label</font></b></font></font></a></div></td></tr><tr> 
#                <td bgcolor='#FFFFFF' NOWRAP></td>
#              </tr>\n";
#    }

    # Reports
    #if (($ThisAdminRights[SUPE])||($ThisAdminRights[DORD])){
    $Label    = "Report";
    $Link    = $GLOBALS['website_admin_url']."ReportsPage.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
#    $Label    = "Reports";    
#    $Link    = "#";
#    $html    .= "<tr><td class='TDBox' height='30' onMouseover=\"this.bgColor='black'\" onMouseout=\"this.bgColor='#FAFAD8'\" NOWRAP><div align='center'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'><a href='#' onMouseOver=\"MM_showHideLayers('LReports','','show')\">Reports <font color='#000099'>>>></font></a></font></div></td></tr>\n";
    #}
    
    
    
    
    #if (($ThisAdminRights{SUPE})||($ThisAdminRights{DORD})){
    #$Label    = "DID.SuperTec.Com";    
    #$Link    = "../did/ManageDID.cgi";
    #$html    .= "<tr><td class='TDBox' height='30' onMouseover=\"this.bgColor='black'\" onMouseout=\"this.bgColor='#FAFAD8'\" NOWRAP><div align='center'><a href='$Link' onMouseOver=\"MM_showHideLayers('LReports','','hide')\"><font face='Verdana, Arial, Helvetica, sans-serif' size='1'>$Label</font></a></div></td></tr>\n";
    #}
    
    

### comments by azfar 29-sep-2003
###    # Sales Report
###    if ($ThisAdminRights{RSRT}){
###    $Label    = "Sales Report";
###    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/SalesReport.cgi";
###    $html    .= "<tr><td class='TDBox' height='30' onMouseover=\"this.bgColor='black'\" onMouseout=\"this.bgColor='#FAFAD8'\" NOWRAP><div align='center'><a href='$Link'><font face='Verdana, Arial, Helvetica, sans-serif' size='1'>$Label</font></a></div></td></tr>\n";
###    }


    # Reseller Ledger
    #$strsql    = "select autype from adminuser where autype='RESE' and auid='$pHash{AUID}'";
    #@Result    = Db->ExecuteQuery($strsql);
    #if (($Result[1][0] ne '')||($ThisAdminRights{SUPE})){
    #$Label    = "Reseller Ledger";
    #$Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/ViewResellerAccount.cgi";
    #$html    .= "<tr><td class='TDBox' height='30' onMouseover=\"this.bgColor='black'\" onMouseout=\"this.bgColor='#FAFAD8'\" NOWRAP><div align='center'><a href='$Link' onMouseOver=\"MM_showHideLayers('LReports','','hide')\"><font face='Verdana, Arial, Helvetica, sans-serif' size='1'>$Label</font></a></div></td></tr>\n";
    #}


    # Accounts Section
    if ($ThisAdminRights['RACC']){
    $Label    = "Accounts";
    $Link    = $GLOBALS['website_admin_url']."AccountsHome.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    }

    # Periodic Monthly
#    if ($ThisAdminRights{RACC}){
#    $Label    = "Periodic";
#    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/PeriodicBillingManager.cgi";
#    $html    .= "<tr onMouseover=\"this.bgColor='#DAEAF7'\" onMouseout=\"this.bgColor='#92BED7'\"><td height='30' valign='middle' align='left' NOWRAP><div align='left'><a href='$Link'><font face='Verdana, Arial, Helvetica, sans-serif' size='1'><img src=$GLOBALS['website_admin_url']images/arrow.gif width='20' height='10' border='0'><font color='#003333'><b><font color='#003366'>$Label</font></b></font></font></a></div></td></tr><tr> 
#                <td bgcolor='#FFFFFF'></td>
#              </tr>\n";
#    }
#    
    # Statistics
    if ($ThisAdminRights['RACC']){
    $Label    = "Statistics";
    $Link    = $GLOBALS['website_admin_url']."iVPLreport.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    }


    #omitted on rehan's request
    ## VCare Home
    #if ($ThisAdminRights{RVCH}){
    #$Label    = "VCare Home";
    #$Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/VCareHome.cgi";
    #$html    .= "<tr><td class='TDBox' height='30' onMouseover=\"this.bgColor='black'\" onMouseout=\"this.bgColor='#FAFAD8'\" NOWRAP><div align='center'><a href='$Link' onMouseOver=\"MM_showHideLayers('LReports','','hide')\"><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'>$Label</font></a></div></td></tr>\n";
    #}
    
    # CRM Setteings
    $Label    = "CRM Settings";
    $Link    = "/admins/CRMSettings.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";


    # Change Password
    $Label    = "Change Password";
    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/ChangePassword.cgi";
    $html    .= "<li><a href='$Link'>$Label</a></li>";

    # Manage DIDs
    $Label    = "Manage DIDs";
    $Link    = $GLOBALS['website_admin_url']."ManageDID.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
    # Margaretha Manager
    $Label    = "Margrathea Manager";
    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/Magrathea.cgi";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
        # DIDww Manager
    $Label    = "DIDww Manager";
    $Link    = $GLOBALS['website_admin_url']."didww.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";

        # Margaretha Manager
    $Label    = "IP Kall Manager";
    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/IPKallManager.cgi";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
    $Label    = "Announcement";
    $Link    = $GLOBALS['website_admin_url']."AddAnnounce.php";
    $html    .= "<li><a href='$Link'>$Label</a></li>";
    
    # Manage Extensions
    #$Label    = "Manage Extensions";
    #$Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/ManageExt.cgi";
    #$html    .= "<tr><td class='TDBox' height='30' onMouseover=\"this.bgColor='black'\" onMouseout=\"this.bgColor='#FAFAD8'\"><div align='center'><a href='$Link' onMouseOver=\"MM_showHideLayers('LReports','','hide')\"><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'>$Label</font></a></div></td></tr>\n";

    /*Add new feature "API User Log" as instructed on 8-12-14*/
    $Label    = "API Users Log";
    $Link    = "#";
    $html    .= "<li><a href='$Link'>$Label</a></li>";

    # Signout
    $Label    = "Signout";
    $Link    = "http://sandbox.didx.net/cgi-bin/virtual/admins/SignoutAction.cgi";
    $html    .= "<li><a href='$Link'>$Label</a></li></tr></ul>";
    
    
    
    
    
    
    


    $html    .= "
            </table>
          </td>
        </tr>
        <tr> 

          <td><!--
            <table width='130' border='0'  class='NavBar'>
              <tr> 
                <td bgcolor='#9999CC'><font color='#FFFFFF' face='Verdana, Arial, Helvetica, sans-serif' size='-1'>::News</font></td>
              </tr>
              <tr> 
                <td height='30'><font face='Verdana, Arial, Helvetica, sans-serif' size='1'>New 
                  York's gateway added</font> 
                  <div align='center'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'></font></div>
                </td>
              </tr>
            </table>-->
          </td>
        </tr>
      </table> 
    </td>
    <td width='20'>&nbsp;</td>
    <td width='85%' valign='top'> ";
#print "\$html:$html";
    return $html;
} 
#	getSessionFooter
#	IN:	Hash->CompanyName
#	OUT:	FooterHTML
function getSessionFooter($pHash) {

	return "</td>
  </tr>
  <tr background=$GLOBALS['website_admin_url']images/basebg1.jpg height='24'> 
	<td width='143' background=$GLOBALS['website_admin_url']images/basebg1.jpg>&nbsp;</td>
	<td width='11' background=$GLOBALS['website_admin_url']images/basebg1.jpg height='24'>&nbsp;</td>
	<td width='602' background=$GLOBALS['website_admin_url']images/basebg1.jpg height='24'>&nbsp;</td>
  </tr>
  
</table>
</body>
</html>
	";
}

//function getOrderManagerPanelHTML($pZero){
//	$pRights	= $pZero;
//	if ((!$pRights['VORD'])&&(!$pRights['EORD'])&&(!$pRights['COOR'])&&(!$pRights['AGOR'])){
//		return "";
//	}
//	$html	= "<table width='482' border='1' height='120%' bordercolor='#DAEAF7' onMouseOver=\"MM_showHideLayers('LReports','','hide')\">";
//	$html	.= "  <tr bordercolor='#DAEAF7' bgcolor='#DAEAF7'>";
//	$html	.= "    <td colspan='2' valign='middle' height='20' bgcolor='DAEAF7'><strong><div align='center'><font face='Verdana, Arial, Helvetica, sans-serif' size='2'>Order";
//	$html	.= "          Manager</font></div></strong></td>";
//	$html	.= "  </tr>";
//	$html	.= "  <tr bordercolor='#DAEAF7'>";
//	$html	.= "    <td height='140%' colspan='2' bordercolor='DAEAF7' valign='top'>";
//	$html	.= "      <form !action=$GLOBALS['website_admin_url']ViewOrder.php method='post' name='OrderManager' id='OrderManager' ><br><br>";
//	$html	.= "        <input name='OID' type='text' id='OID' maxlength='6' class='TextBox'>
//	<script language='javascript'>
//	function disableSubmit(){
//		SearchOrder.Submit.disabled	= true;
//		SearchOrder.submit();
//	}
//	function submitComplain(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/AAddComplain.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitEdit(){
		//alert('Hello edit');
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/EditOrder.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitDelete(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/DeleteOrder.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitForm(tag){
//		FeatureManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/A\"+tag+\".cgi\";
//		FeatureManager.submit();
//		FeatureManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitView(){
//		OrderManager.action = \"http://didx.net/admins/ViewOrder.php\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitUpload(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/UploadDocuments.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitPassword(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/CreatePasswordAction.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitVCare(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/AViewVCare.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}	
//	function submitIntlBilling(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/IntlBillingHistory.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}	
//	function submitVCarePass(){
//		OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/ASendOldVPass.cgi\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function submitClientLedger(){
//		OrderManager.action = \"http://sandbox.didx.net/admins/ALedger2.php\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	function ViewOrderNewVersion(){
//		
//		OrderManager.action = \"http://didx.net/admins/ViewOrder.php\";
//		OrderManager.submit();
//		OrderManager.Submit.disabled	= true;
//		return false;
//	}
//	/*
//	#function submitCreateAccount(){
//		#OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/CreateAccount.cgi\";
//		#OrderManager.submit();
//		#OrderManager.Submit.disabled	= true;
//		#return false;
//	#}

//	#function submitProperAccount(){
//		#OrderManager.action = \"http://sandbox.didx.net/cgi-bin/virtual/admins/ProperAccount.cgi\";
//		#OrderManager.submit();
//		#OrderManager.Submit.disabled	= true;
//		#return false;
//	#}
//	
//	*/
//</script>
//";
//	$html	.= "        ";
//		if ($pRights['VORD']){
//		$html	.= "        <input type='button' name='Submit' value='View' id='View' onClick='return submitView();' class='Button'>\n";
//	}
//																														
//	if ($pRights['EORD']){
//        
//		$html	.= "        <input name='Submit' type='submit' id='Submit' value='Edit'
//         
//		onClick='return submitEdit();' class='Button'>\n";
//        
//	}
//	#]#
//#	if ($pRights[DORD]){
//	#	$html	.= "        <input name='Submit' type='submit' id='Submit' value='Delete' onClick='return submitDelete();' class='Button'>\n";
//		#}
//																																									if ($pRights[COOR]){
//		 $html	.= "        <input name='Submit' type='submit' id='Submit' value='Complain' onClick='return submitComplain();' class='Button'>\n";
//		 
//#	}
//#	if ($pRights[AGOR}){
//#		$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Upload' onClick='return submitUpload();' class='Button'>\n";
//#	}
//#	if($pRights[CPWD}){
//#		$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Create Password' onClick='return submitPassword();' class='Button'>\n";
//#	}
//	
//	#on rehan's req
//	#if($pRights[VCARE}){
//	#	$html	.= "        <input name='Submit' type='submit' id='Submit2' value='VCare' onClick='return submitVCare();' class='Button'>\n";
//	#}
//	#
//	#if($pRights[VCARE}){
//	#	$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Send Welcome Email' onClick='return submitVCarePass();' class='Button'>\n";
//	#}
//  #
//	#if($pRights[VCARE}){
//	#	$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Intl Billing History' onClick='return submitIntlBilling();' class='Button'>\n";
//	#}
//	
//#submitCreateAccount	
//	if($pRights['CVCA']){
//	#	$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Create Account' onClick='return submitCreateAccount();' class='Button'>\n";
//	}

//	if($pRights['LSUM']){
//		$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Client Ledger' onClick='return submitClientLedger();' class='Button'>\n";
//	}
//	
//	#$html	.= "        <input name='Submit' type='submit' id='Submit2' value='View Order New Ver.' onClick='return ViewOrderNewVersion();' class='Button'>\n";
//	
//	#Proper Account
//	#cgi-bin/admins/ProperAccount.cgi
//	if($pRights['LSUM']){
//	#	$html	.= "        <input name='Submit' type='submit' id='Submit2' value='Proper Account' onClick='return submitProperAccount();' class='Button'>\n";
//	}


//	$html	.= "  </form>";
//	$html	.= "  </td>";
//	$html	.= "  </tr>";
//	$html	.= "  </table>";
//	
//	return $html;
//}
//}

function getOrderManagerPanelHTML($pZero){
    $pRights    = $pZero;
    if ((!$pRights['VORD'])&&(!$pRights['EORD'])&&(!$pRights['COOR'])&&(!$pRights['AGOR'])){
        return "";
    }
    $html    = "<table width='482' border='1' height='120%' bordercolor='#DAEAF7' onMouseOver=\"MM_showHideLayers('LReports','','hide')\">";
    $html    .= "  <tr bordercolor='#DAEAF7' bgcolor='#DAEAF7'>";
    $html    .= "    <td colspan='2' valign='middle' height='20' bgcolor='DAEAF7'><strong><div align='center'><font face='Verdana, Arial, Helvetica, sans-serif' size='2'>Order";
    $html    .= "          Manager</font></div></strong></td>";
    $html    .= "  </tr>";
    $html    .= "  <tr bordercolor='#DAEAF7'>";
    $html    .= "    <td height='140%' colspan='2' bordercolor='DAEAF7' valign='top'>";
    $html    .= "      <form !action=$GLOBALS['website_admin_url']ViewOrder.php method='post' name='OrderManager' id='OrderManager' ><br><br>";
    $html    .= "        <input name='OID' type='text' autocomplete='off' id='OID' maxlength='6' class='TextBox'>
    
    <script language='javascript'>
    var OrderManager = document.getElementById('OrderManager');
    function disableSubmit(){
        SearchOrder.Submit.disabled    = true;
        SearchOrder.submit();
    }
    function submitComplain(){
        OrderManager.action = \"https://didx.net/cgi-bin/virtual/admins/AAddComplain.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitEdit(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/EditOrder.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitDelete(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/DeleteOrder.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitForm(tag){
        FeatureManager.action = \"http://didx.net/cgi-bin/virtual/admins/A\"+tag+\".cgi\";
        FeatureManager.submit();
        FeatureManager.Submit.disabled    = true;
        return false;
    }
    function submitView(){
        var OID = document.getElementById('OID').value; 
        // OrderManager.action = \"https://didx.net/admins/ViewOrder.php\";
        OrderManager.action = \"http://sandbox.didx.net/admins/ViewOrder.php?OID=\"+OID+\"\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitUpload(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/UploadDocuments.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitPassword(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/CreatePasswordAction.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitVCare(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/AViewVCare.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }    
    function submitIntlBilling(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/IntlBillingHistory.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }    
    function submitVCarePass(){
        OrderManager.action = \"http://didx.net/cgi-bin/virtual/admins/ASendOldVPass.cgi\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function submitClientLedger(){
      var OID = document.getElementById('OID').value;
        OrderManager.action = \"http://didx.net/admins/ALedger2.php?OID=\"+OID+\"\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }
    function ViewOrderNewVersion(){
        
        OrderManager.action = \"http://didx.net/admins/ViewOrder.php\";
        OrderManager.submit();
        OrderManager.Submit.disabled    = true;
        return false;
    }

</script>
";
    $html    .= "        ";
       
        if ($pRights['VORD']){
        $html    .= "        <input type='submit' name='Submit' value='View' id='Submit' onClick='return submitView();' class='Button'>\n";
        
    }
                                                                                                                            
    if ($pRights['EORD']){
        $html    .= "        <input name='Submit' type='submit' id='Submit' value='Edit' 
        onClick='return submitEdit();' class='Button'>\n";
    }

    if ($pRights[COOR]){
         $html    .= "        <input name='Submit' type='submit' id='Submit' value='Complain' onClick='return submitComplain();' class='Button'>\n";

    if($pRights['CVCA']){

    }

    if($pRights['LSUM']){
        $html    .= "        <input name='Submit' type='submit' id='Submit2' value='Client Ledger' onClick='return submitClientLedger();' class='Button'>\n";
    }
    

    if($pRights['LSUM']){

    }


    $html    .= "  </form>";
    $html    .= "    </td>";
    $html    .= "  </tr>";
    $html    .= "</table>";
    
    return $html;
}
}



function getSearchOrderHTML($pRights){
	
	if ((!$pRights['VORD'])&&(!$pRights['EORD'])&&(!$pRights['COOR'])&&(!$pRights['AGOR'])&&(!$pRights['DORD'])){
		//return "";
	}
	$html	= "<table width='500' border='1'  bordercolor='#DAEAF7'>";
	$html	.= "<tr bgcolor='DAEAF7'>";
	$html	.= "<td bgcolor='#DAEAF7' valign='middle' height='2'><div align='center'><strong><font  size='2' face='Verdana, Arial, Helvetica, sans-serif'>Search";
	$html	.= "Orders</font></strong></div></td>";
	$html	.= "</tr>";
	$html	.= "<tr>";
	$html	.= "<td  height='2' valign='top'>";
	#http://sandbox.didx.net/cgi-bin/virtual/admins/SearchOrderAction.cgi
	$html	.= "<form name='SearchOrder' action='' method='post'>";
	$html	.= "<input name='KeyVal' type='text' id='Key' class='TextBox' onkeypress=\"return handleKeyPress(event,this.form)\">";
	#$html	.= "<input type='checkbox' id='Key' name='AccountNo' value=1><font face=verdana size=1 color=#006699>Display Both Seller and Buyer OID</font>";
	#$html	.= "<input type='checkbox' id='Key' name='OnlyActive' value=1 checked><font face=verdana size=1 color=#006699>Only Active Orders</font>";
	#$html	.= "<input type='checkbox' id='Key' name='IncludeTerminated' value=1><font face=verdana size=1 color=#006699>Include Terminated/Deleted Orders</font>";
	$html	.= "<!--<input type='checkbox' id='Key' name='IncludeSold' value=0><font face=verdana size=1 color=#006699>Include Sold DID Numbers</font>-->";
	
	#$html	.= "<input type='checkbox' id='Key' name='IncludePaperWork' value=1><font face=verdana size=1 color=red>Include Awaiting Paper Works Orders</font><br>";
	$html	.= "<font face=verdana size=1 color=red></font>
			  <select name=\"SearcHCriteria\" class='TextBox' id=\"statusid\">
			  <option value=\"9\">All</option>
			<option value=\"0\">Active Orders</option>
			<option value=\"1\">Deleted</option>
			<option value=\"3\">Awaiting Interop</option>
			<option value=\"6\">Suspended</option>
			<option value=\"2\">Terminated</option>
			 </select>
		";
		
		#	<option value=\"ALL\">All</option>
#    			<option value=\"VPL\">VPL Number</option>
#    			<option value=\"MAC\">Mac</option>
#    			<option value=\"CUST\">Customer Name</option>
#    			<option value=\"TEL\">Telephone Number</option>
#    			<option value=\"COUN\">Country</option>
	
	$html	.= "<input name='Submit4' type='button' id='Submit10' value='Search' class='Button'  OnClick=\"getNewContentSearch(-1,'','');\"> ";
	$html	.= "</form>";
	$html	.= "</td>";
	$html	.= "</tr>";
	$html	.= "</table>";
	return $html;
}

function getFeatureManagerHTML($pRights){
		
	if ( (!$pRights['FCFW'])&&(!$pRights['FINT'])&&(!$pRights['FVML'])&&(!$pRights['FBDW'])&&(!$pRights['FCWT'])&&(!$pRights['FNAV'])&&(!$pRights['FACT'])){
		return "";
	}
	$html	= "<table width='89%' border='1' bordercolor='#DAEAF7' onMouseOver=\"MM_showHideLayers('LReports','','hide')\">";
	$html	.= "<script>\n
	function submitForm(tag){\n
		FeatureManager.action = \"/cgi-bin/virtual/admins/A\"+tag+\".cgi\";\n
		FeatureManager.submit();\n
		return false;\n
	}
</script>
\n
";	
	
	$html	.= "<tr bordercolor='#0033FF' bgcolor='#0033FF'>\n";
	$html	.= "<td height='14' colspan='2'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='#FFFFFF'><strong>Feature\n";
	$html	.= "Manager</strong></font></td>\n";
	$html	.= "</tr>\n";
	$html	.= "<tr bordercolor='#0033FF'>\n";
	$html	.= "<td height='5' colspan='2'>\n";
	$html	.= "<form action='/cgi-bin/virtual/admins/FeaturesManagerAction.cgi' method='post' name='FeatureManager' id='FeatureManager' >\n";
	$html	.= "<font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='#000000'>Order\n";
	$html	.= "ID</font>: &nbsp;&nbsp;\n";
	$html	.= "<input name='OID' type='text' id='OID2' maxlength='6' class='TextBox'>\n";
	$html	.= "<br>";
	if ($pRights['FCFW']){
		$html	.= "<input type='submit' name='Submit' value='Call Forwarding' id='Submit3' onClick=\"return submitForm('CallForwarding');\" class='Button'>\n";
	}
	if ($pRights['FINT']){
	$html	.= "<input name='Submit' type='submit' id='Submit4' value='International Call' onClick=\"return submitForm('InternationalCall');\" class='Button'>\n";
	}
	if ($pRights['FVML']){
	$html	.= "<input name='Submit' type='submit' id='Submit4' value='VoiceMail'  onClick=\"return submitForm('VoiceMail');\" class='Button'>\n";
	}
	if ($pRights['FBDW']){
	$html	.= "<input name='Submit' type='submit' id='Submit5' value='Bandwidth Saver'  onClick=\"return submitForm('BandwidthSaver');\"  class='Button'>\n";
	}
	if ($pRights['FCWT']){
	$html	.= "<input type='submit' name='Submit' value='Call Waiting' id='Submit4' onClick=\"return submitForm('CallWaiting');\"  class='Button'>\n";
	}
	if ($pRights['FNAV']){
	$html	.= "<input type='submit' name='Submit' value='Network Availability' onClick=\"return submitForm('NetworkAvailability');\" id='Submit6' class='Button'>\n";
	}
	if ($pRights['FACT']){
	$html	.= "<input type='submit' name='Submit' value='Access Controller' onClick=\"return submitForm('FeatureAccess');\" id='Submit4' class='Button'>\n";
	}
	if ($pRights['FACT']){
	$html	.= "<input type='submit' name='Submit' value='Charge International Call' onClick=\"return submitForm('InternationalBilling');\" id='Submit4' class='Button'>\n";
	}
	if ($pRights['FINT']){
	$html	.= "<input type='submit' name='Submit' value='International Call Log' onClick=\"return submitForm('IntCallLogs');\" id='Submit4' class='Button'>\n";
	}
	if ($pRights['FACT']){
	$html	.= "<input type='submit' name='Submit' value='Get Voice Mail' onClick=\"return submitForm('getVPLVmByOID');\" id='Submit4' class='Button'>\n";
	}
	$html	.= "</form>";
	$html	.= "</td>";
	$html	.= "</tr>";
	$html	.= "</table>";
	return $html;
}


function getOrderSusUnSusBtn($pRights,$pOID){
		
			
	if ((!$pRights['VORD'])&&(!$pRights['EORD'])&&(!$pRights['COOR'])&&(!$pRights['AGOR'])&&(!$pRights['DORD'])){
		return "";
	}
# By Altamash , Remodified by Arfeen 1/6/2006 

	$html = "<table width='730' height='10' border='0' cellspacing='0' bgcolor='#99CCFF'>";
	$html .= "<tr>";
	$html .= "    <td><form action='/cgi-bin/virtual/admins/SendVCareSuspEmail.cgi' method='post' enctype='multipart/form-data' name='EditOrder'  id='VCareSearch'>";
	$html .= "<input type='submit' name='btnVCareSearch' value='Suspend Email' class=Button>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "</form>";
	$html .= "</td>";
		$html .= "<td><form action='/cgi-bin/virtual/admins/SendVCareUnSuspEmail.cgi' method='post' enctype='multipart/form-data' name='EditOrder'  id='VCareSearch'>";
	$html .= "<input type='submit' name='btnVCareSearch' value='Un-Suspend Email' class=Button>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "</form>";
	$html .= "</td>";
	$html .= "</tr>";
	$html .= "</table>";



	return $html;
}#  End OF getOrderSusUnSusBtn





//function getOrderControlPanel($pRights,$pOID){
//		
//			
//	if ((!$pRights[VORD])&&(!$pRights[EORD])&&(!$pRights[COOR])&&(!$pRights[AGOR])&&(!$pRights[DORD])){
//		return "";
//	}
//	
//	$html = "<table width=\"740\" height=\"50\" border=\"0\" cellpadding=\"1\" cellspacing=\"5\" bordercolor=\"#FFFFFF\" bgcolor=\"#D5E1F0\">";
//#	$html .= "<tr valign='bottom' bgcolor='#FFFFCC'>";
//#	$html .= "<td colspan='9'><div align='center'><font face='Verdana, Arial, Helvetica, sans-serif'>Order Control Panel</font></div></td>";
//#	$html .= "</tr>";	
//		
//	$html .= "<tr bordercolor=\"#000000\" bgcolor=\"#FFFFFF\">";
//  $html .= "<td colspan=\"5\">";
//  $html .= "<div align=\"center\"><font size=\"-1\" face=\"Verdana\"><strong>Order Control ";
//  $html .= "Panel</strong></font></div></td>";
//  $html .= "</tr>";
//
//$html .= "<tr>";
//
//# Accounts Home
//
//		$html .= "<td><div align=\"center\"> ";
//
//if ($pRights[LSUM])
//	{
//		
//		$html .= "<form action='/admins/AccountsHome.php' method='post' enctype='multipart/form-data' name='EditOrder' target='_blank' id='EditOrder'>";
//		$html .= "<input type='submit' name='btnEdit' value='Accounts Home' class=Button>";
//		$html .= "<input type='hidden' name='OID' value='$pOID'>";
//		$html .= "</form>";
//			
//	}
//	$html .= "</div></td>";
//	
//	# Customer Ledger
//	
//	$html .= "<td><div align=\"center\"> ";
//	
//	if ($pRights[LSUM]){
//	$html .= "<form action='/cgi-bin/virtual/admins/ALedger2.cgi' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='hidden' name='Limit' value='20'>";
//	$html .= "<input type='submit' name='btnLedger2' value='Customer Ledger' class=Button>";
//	$html .= "</form>";
//	}
//
//	$html .= "</div></td>";
//	
//	# View Order
//	
//	$html .= "<td><div align=\"center\"> ";
//	if ($pRights[VORD]){
//	$html .= "<form action='/cgi-bin/virtual/admins/ViewOrder.cgi' method='post' enctype='multipart/form-data' name='ViewOrder' target='_blank' id='ViewOrder'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnView' value='View Order' class=Button>";
//	$html .= "</form>";
//	}
//	
//	$html .= "</div></td>";
//	
//	# Audit Monthly
//	
//	$html .= "<td><div align=\"center\"> ";
//	if ($pRights[LSUM]){
//	$html .= "<form action='/cgi-bin/virtual/admins/AuditInvoicesByOID.cgi' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnLedger2' value='Audit Monthly' class=Button>";
//	$html .= "</form>";
//	}
//	$html .= "</div></td>";
//	
//	# Edit Vendor Rating
//	
//	$html .= "<td><div align=\"center\"> ";
//	if ($pRights[LSUM]){
//	$html .= "<form action='/admins/VendorRatingEdit.php' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnVRating' value='Edit Vendor Rating' class=Button>";
//	$html .= "</form>";
//	}
//	$html .= "</div></td>";
//	
//	$html .= "</tr>";
//	
//	$html .= "<tr>";
//	
//	#--------
//	
//	$html .= "<td><div align=\"center\"> ";
//	if ($pRights[LSUM]){
//	$html .= "<form action='/cgi-bin/virtual/admins/EditCommision.cgi' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnVRating' value='Edit Commission' class=Button>";
//	$html .= "</form>";
//	}
//	
//	$html .= "</div></td>";
//	
//	# Password 
//	
//	$html .= "<td><div align=\"center\"> ";
//	if ($pRights[CPWD]){
//	$html .= "<form action='/cgi-bin/virtual/admins/CreatePasswordAction.cgi' method='post' enctype='multipart/form-data' name='CreatePassword' target='_blank' id='CreatePassword'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnCreatePass' value='Password' class=Button>";
//	$html .= "</form>";
//	}
//	$html .= "</div></td>";
//	
//	# Edit Order	
//	
//	$html .= "<td><div align=\"center\"> ";
//	
//	if ($pRights[EORD]){
//	$html .= "<form action='/cgi-bin/virtual/admins/EditOrder.cgi' method='post' enctype='multipart/form-data' name='EditOrder' target='_blank' id='EditOrder'>";
//	$html .= "<input type='submit' name='btnEdit' value='Edit Order' class=Button>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "</form>";
//	}
//	$html .= "</div></td>";
//	
//	# Complain
//	
//	$html .= "<td><div align=\"center\"> ";
//	
//	if ($pRights[COOR]){
//	$html .= "<form action='/cgi-bin/virtual/admins/AAddComplain.cgi' method='post' enctype='multipart/form-data' name='AddComplain' target='_blank' id='AddComplain'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnComplain' value='Complain' class=Button>";
//	$html .= "</form>";
//	}
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	$html .= "</tr>";
//	
//	$html .= "<tr>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	
//	if ($pRights[AGOR]){
//	$html .= "<form action='/cgi-bin/virtual/admins/UploadDocuments.cgi' method='post' enctype='multipart/form-data' name='Upload' target='_blank' id='Upload'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnUpload' value='Documents' class=Button>";
//	$html .= "</form>";
//	}
//	
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	if ($pRights[AGOR]){
//	$html .= "<form action='/cgi-bin/virtual/admins/ALedger2Bulk.cgi' method='post' enctype='multipart/form-data' name='Upload' target='_blank' id='Upload'>";
//	$html .= "<input type='hidden' name='OID' value='$pOID'>";
//	$html .= "<input type='submit' name='btnUpload' value='Bulk Ledger' class=Button>";
//	$html .= "</form>";
//	}
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	$html .= "</tr>";
//	
//	$html .= "<tr>";
//	
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	#---
//	$html .= "<td><div align=\"center\"> ";
//	$html .= "</div></td>";
//	
//	$html .= "</tr>";
//	
//	
//	
//	$html .= "</table>";
// 
//	return $html;
//}


function getOrderControlPanel($pRights,$pOID){


	if ((!$pRights['VORD'])&&(!$pRights['EORD'])&&(!$pRights['COOR'])&&(!$pRights['AGOR'])&&(!$pRights['DORD'])){
		return "";
	}
	
	$html = "<table width=\"740\" height=\"50\" border=\"0\" cellpadding=\"1\" cellspacing=\"5\" bordercolor=\"#FFFFFF\" bgcolor=\"#D5E1F0\">";
#	$html .= "<tr valign='bottom' bgcolor='#FFFFCC'>";
#	$html .= "<td colspan='9'><div align='center'><font face='Verdana, Arial, Helvetica, sans-serif'>Order Control Panel</font></div></td>";
#	$html .= "</tr>";	
		
	$html .= "<tr bordercolor=\"#000000\" bgcolor=\"#FFFFFF\">";
  $html .= "<td colspan=\"5\">";
  $html .= "<div align=\"center\"><font size=\"-1\" face=\"Verdana\"><strong>Order Control ";
  $html .= "Panel</strong></font></div></td>";
  $html .= "</tr>";

$html .= "<tr>";

# Accounts Home

		$html .= "<td><div align=\"center\"> ";

if ($pRights['LSUM'])
	{
		
		$html .= "<form action='/admins/AccountsHome.php' method='post' enctype='multipart/form-data' name='EditOrder' target='_blank' id='EditOrder'>";
		$html .= "<input type='submit' name='btnEdit' value='Accounts Home' class=Button>";
		$html .= "<input type='hidden' name='OID' value='$pOID'>";
		$html .= "</form>";
			
	}
	$html .= "</div></td>";
	
	# Customer Ledger
	
	$html .= "<td><div align=\"center\"> ";
	
	if ($pRights['LSUM']){
	$html .= "<form action='/cgi-bin/virtual/admins/ALedger2.cgi' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='hidden' name='Limit' value='20'>";
	$html .= "<input type='submit' name='btnLedger2' value='Customer Ledger' class=Button>";
	$html .= "</form>";
	}

	$html .= "</div></td>";
	
	# View Order
	
	$html .= "<td><div align=\"center\"> ";
	if ($pRights['VORD']){
	$html .= "<form action='/cgi-bin/virtual/admins/ViewOrder.cgi' method='post' enctype='multipart/form-data' name='ViewOrder' target='_blank' id='ViewOrder'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnView' value='View Order' class=Button>";
	$html .= "</form>";
	}
	
	$html .= "</div></td>";
	
	# Audit Monthly
	
	$html .= "<td><div align=\"center\"> ";
	if ($pRights['LSUM']){
	$html .= "<form action='/cgi-bin/virtual/admins/AuditInvoicesByOID.cgi' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnLedger2' value='Audit Monthly' class=Button>";
	$html .= "</form>";
	}
	$html .= "</div></td>";
	
	# Edit Vendor Rating
	
	$html .= "<td><div align=\"center\"> ";
	if ($pRights['LSUM']){
	$html .= "<form action='/admins/VendorRatingEdit.php' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnVRating' value='Edit Vendor Rating' class=Button>";
	$html .= "</form>";
	}
	$html .= "</div></td>";
	
	$html .= "</tr>";
	
	$html .= "<tr>";
	
	#--------
	
	$html .= "<td><div align=\"center\"> ";
	if ($pRights['LSUM']){
	$html .= "<form action='/cgi-bin/virtual/admins/EditCommision.cgi' method='post' enctype='multipart/form-data' name='AccountLedgerClient' target='_blank' id='AccountLedgerClient'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnVRating' value='Edit Commission' class=Button>";
	$html .= "</form>";
	}
	
	$html .= "</div></td>";
	
	# Password 
	
	$html .= "<td><div align=\"center\"> ";
	if ($pRights['CPWD']){
	$html .= "<form action='/cgi-bin/virtual/admins/CreatePasswordAction.cgi' method='post' enctype='multipart/form-data' name='CreatePassword' target='_blank' id='CreatePassword'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnCreatePass' value='Password' class=Button>";
	$html .= "</form>";
	}
	$html .= "</div></td>";
	
	# Edit Order	
	
	$html .= "<td><div align=\"center\"> ";
	
	if ($pRights['EORD']){
	$html .= "<form action='/cgi-bin/virtual/admins/EditOrder.cgi' method='post' enctype='multipart/form-data' name='EditOrder' target='_blank' id='EditOrder'>";
	$html .= "<input type='submit' name='btnEdit' value='Edit Order' class=Button>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "</form>";
	}
	$html .= "</div></td>";
	
	# Complain
	
	$html .= "<td><div align=\"center\"> ";
	
	if ($pRights['COOR']){
	$html .= "<form action='/cgi-bin/virtual/admins/AAddComplain.cgi' method='post' enctype='multipart/form-data' name='AddComplain' target='_blank' id='AddComplain'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnComplain' value='Complain' class=Button>";
	$html .= "</form>";
	}
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	$html .= "</tr>";
	
	$html .= "<tr>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	
	if ($pRights['AGOR']){
	$html .= "<form action='/cgi-bin/virtual/admins/UploadDocuments.cgi' method='post' enctype='multipart/form-data' name='Upload' target='_blank' id='Upload'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnUpload' value='Documents' class=Button>";
	$html .= "</form>";
	}
	
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	if ($pRights['AGOR']){
	$html .= "<form action='/cgi-bin/virtual/admins/ALedger2Bulk.cgi' method='post' enctype='multipart/form-data' name='Upload' target='_blank' id='Upload'>";
	$html .= "<input type='hidden' name='OID' value='$pOID'>";
	$html .= "<input type='submit' name='btnUpload' value='Bulk Ledger' class=Button>";
	$html .= "</form>";
	}
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	$html .= "</tr>";
	
	$html .= "<tr>";
	
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	#---
	$html .= "<td><div align=\"center\"> ";
	$html .= "</div></td>";
	
	$html .= "</tr>";
	
	
	
	$html .= "</table>";
 

	return $html;
}#getOrderControlPanel



}

?>
