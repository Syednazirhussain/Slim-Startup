var http = createRequestObjectAjax();
var GlobalMyDIV;
var websiteUrl =  $( "#website_info" ).data( "website-url" );

function createRequestObjectAjax() 
{	
	var objAjax;
	var URL;	
	var browser = navigator.appName;	
	if(browser == "Microsoft Internet Explorer")
		{		
			objAjax = new ActiveXObject("Microsoft.XMLHTTP");
		}
	else
		{		
			objAjax = new XMLHttpRequest();	
		}	return objAjax;
}

function handleKeyPressSearch(e,form){
var key=e.keyCode || e.which;
	if (key==13){
	BuyDID_GetNumberListByNumber();
	return false;
	}

}
 	


function ShowPricing() {
	
	var PriceTag = document.getElementById("CHANNELPRICE");
	
	if(PriceTag.style.display == "none")
			PriceTag.style.display="inline";
	else
			PriceTag.style.display="none";
	
}

	function SelectAllCheckBox()
{
	myForm = document.getElementById("BuyDID_ListForm");
	
//	var myForm = elem.Form;
//	var myForm= formoffer;
	for(var i=0; i<myForm.elements.length; i++)
	{
		if(myForm.elements[i].type=='checkbox')
		{
			if(myForm.elements[i].checked==true) {
				myForm.elements[i].checked=false;
			} else {
						myForm.elements[i].checked=true;
					}
		}	
	}
}

	function BuyDID_AddDIDNumbersToCart()
{
	
	myForm = document.getElementById("BuyDID_ListForm");
	var Sno=0;
	for(var i=0; i<myForm.elements.length; i++)
	{
		
		
		
		if(myForm.elements[i].type=='checkbox')
		{
			if(myForm.elements[i].checked==true) {
				Sno=1;
			} 
		}	
	}
	
	if(Sno==0){
		
		alert("Please select a number");
		return false;
	}
	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	myForm.action= websiteUrl + "/BuyDID_AddToCart";
	//myForm.target="_blank";
	myForm.method="POST";
	myForm.submit();
	obj = document.getElementById("DIDLISTPANEL");
	if(obj.style.display=="block"){
			obj.style.display="none";	
			obj.innerHTML = "";			
		}
	

}
function BuyDID_AddDIDNumbersToCart_updateContents(){
	
	if(http.readyState == 4)
	{	
		obj = document.getElementById("DIDLISTPANEL");
		if(obj.style.display=="none"){
			obj.style.display="block";	
			obj.innerHTML = http.responseText;			
		}
		document.body.style.cursor='default';
	}
}
      
function DisplayHelpLine(Help){
	
	
	obj = document.getElementById("YELLOWTIPDIV");
	obj.innerHTML = Help;
}      
      
function LoadRecentDIDS()
{	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/RecentDIDSList?&Key='+(new Date().getTime());
	obj = document.getElementById("MAINAREADIV");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = LoadRecentDIDS_updateContents;	
	http.send(null);	
	return false;
}

function RefreshPage(){
	
	obj = document.getElementById("MAINAREADIV");
	if(obj.style.display=="none"){
		obj.style.display="block";
		obj.innerHTML="Form Submited";
	}
}

function BuyDID_BacktoCart(AreaID,NXX,Vendor,SortBy,SortDir,op)
{
    
    if(op=='1')
    {
         DisplayHelpLine("Available DID Numbers -  Buy DID on per minute basis");
    $("#MAINAREADIV").html("<img src=/images/searching.gif>");
        $.get("BuyDID_GetDIDSList",{AreaID:AreaID,NXX:NXX,Vendor:Vendor,sortby:SortBy,dir:SortDir},function(data)
    {
        $("#MAINAREADIV").html(data);
    });
    }
    else
    {
         DisplayHelpLine("Available DID Numbers - Buy DID with Channels on unlimited basis");
    $("#MAINAREADIV").html("<img src=/images/searching.gif>");
       $.get("BuyDID_GetChannelList",{AreaID:AreaID,NXX:NXX,Vendor:Vendor,sortby:SortBy,dir:SortDir},function(data)
    {
               $("#MAINAREADIV").html(data);

        
    }); 
    }
    
}

function BuyDID_UploadCompleted(Result)
{	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_Uploaded?result='+Result+'&Key='+(new Date().getTime());
	obj = document.getElementById("MAINAREADIV");
	if(obj.style.display=="none"){
		obj.style.display="block";
	}
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = LoadRecentDIDS_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_UploadCompleted_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("DIDLISTPANEL");
		
		if(obj.style.display=="none"){
			obj.style.display="block";
	
			obj.innerHTML = http.responseText;
			
		}
			document.body.style.cursor='default';
		
	}
}

function LoadRecentDIDS_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}

function BuyDID_GetNumberListByNumber()
{	
	DisplayHelpLine("Available DID");
	DID = document.getElementById("MyDIDNumber").value;
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	//alert(DID);
	//URL = websiteUrl + '/BuyDID_GetDIDSListByNumber?DIDNumber='+DID+ '&Key='+(new Date().getTime());

	if(DID==''){
		document.getElementById("demo").innerHTML='<span style="color:red;">Please Enter DID Number!</span>';
	}else{
	URL = websiteUrl + '/BuyDID_GetDIDSListByNumber?DIDNumber='+DID+ '&Key='+(new Date().getTime());
	 $('#demo').hide();		
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetNumberListByNumber_updateContents;	
	http.send(null);	
	return false;
}}

function BuyDID_GetNumberListByNumber_Premium()
{	
	DisplayHelpLine("Available DID");
	DID = document.getElementById("MyDIDNumber").value;
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	//alert(DID);
	URL = websiteUrl + '/BuyDID_GetChannelListByNumber?DIDNumber='+DID+ '&Key='+(new Date().getTime());
			
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetNumberListByNumber_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetNumberChannelListByNumber()
{	
	DisplayHelpLine("Available DID");
	DID = document.getElementById("MyDIDNumber").value;
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	//alert(DID);
	URL = websiteUrl + '/BuyDID_GetChannelListByNumber?DIDNumber='+DID+ '&Key='+(new Date().getTime());
			
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetNumberChannelListByNumber_updateContents;	
	http.send(null);	
	return false;
}
function BuyDID_GetNumberChannelListByNumber_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		if(GlobalMyDIV!="")
			obj = document.getElementById(GlobalMyDIV);
		else
			obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}
function BuyDID_GetNumberListByNumber_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		if(GlobalMyDIV!="")
			obj = document.getElementById(GlobalMyDIV);
		else
			obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}


function BuyDID_GetCountryList(Sort)
{	
	DisplayHelpLine("Available DID Coverage by Country");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetCountryList?sortby='+Sort+'&Key='+(new Date().getTime());
			
	obj = document.getElementById("LEFTNAVIGATION");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetCountryList_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetCountryListA(Page,TotalRec)
{	
	
		if(Page!=-1)	
				OldPage =  Page - 1;
		else{
				Page=0;
				OldPage=TotalRec;
				obj = document.getElementById("COUNTRYDIVMORE1");
				obj.style.display="none";		
				
			}
		
		if(Page==TotalRec){
			//	Page=0;
				
		obj = document.getElementById("COUNTRYDIVMORE1");
		obj.style.display="block";		
		
	}
	
		obj = document.getElementById("PANEL"+Page);
		obj.style.display="block";
		
		obj = document.getElementById("PANEL"+OldPage);
		obj.style.display="none";
	
//	URL = websiteUrl + '/BuyDID_GetCountryList?page='+Page+'&Key='+(new Date().getTime());
//			
//	obj = document.getElementById("LEFTNAVIGATION");
//	obj.innerHTML="<img src=/images/searching.gif>";
//	http.open('get',URL);
//	http.onreadystatechange = BuyDID_GetCountryList_updateContents;	
//	http.send(null);	
	return false;
}

function BuyDID_GetCountryList_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("LEFTNAVIGATION");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}

function BuyDID_GetChannelList(AreaID,NXX,Vendor,SortBy,SortDir,Page)
{	
	DisplayHelpLine("Available DID Numbers - Buy DID with Channels on unlimited basis");
	//URL = websiteUrl + '/BuyDID_GetChannelList?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetChannelList?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	//alert(URL);		
	
	if(Page!="")
	GlobalMyDIV = "MAINAREADIV";
	else
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetDIDSList_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetChannelList_Craig(AreaID,NXX,Vendor,SortBy,SortDir,Page)
{	
	DisplayHelpLine("Available DID Numbers - Buy DID with Channels on unlimited basis");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetChannelList_Craig?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	//alert(URL);		
	
	if(Page!="")
	GlobalMyDIV = "MAINAREADIV";
	else
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetDIDSList_updateContents;	
	http.send(null);	
	return false;
}
function BuyDID_GetDIDSListTrigger(AreaID,NXX,Vendor,SortBy,SortDir,Page)
{	
	DisplayHelpLine("Available DID Numbers -  Buy DID on per trigger basis");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetDIDSTriggersList?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	//alert(URL);		
	
	if(Page!="")
	GlobalMyDIV = "MAINAREADIV";
	else
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetDIDSListTrigger_updateContents;	
	http.send(null);	
	return false;
}
function BuyDID_GetDIDSListTrigger_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		if(GlobalMyDIV!="")
			obj = document.getElementById(GlobalMyDIV);
		else
			obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}
function BuyDID_GetDIDSList(AreaID,NXX,Vendor,SortBy,SortDir,Page)
{	
	DisplayHelpLine("Available DID Numbers -  Buy DID on per minute basis");
	//URL = websiteUrl + '/BuyDID_GetDIDSList?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetDIDSList?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	//alert(URL);		
	
	if(Page!="")
	GlobalMyDIV = "MAINAREADIV";
	else
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetDIDSList_updateContents;	
	http.send(null);	
	return false;
}
function BuyDID_GetDIDSList_Craig(AreaID,NXX,Vendor,SortBy,SortDir,Page)
{	
	DisplayHelpLine("Available DID Numbers -  Buy DID on per minute basis");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetDIDSList_Craig?AreaID='+AreaID+ '&NXX='+NXX+'&Vendor='+Vendor+'&sortby='+SortBy+'&dir='+SortDir+'&page='+Page+'&Key='+(new Date().getTime());
	//alert(URL);		
	
	if(Page!="")
	GlobalMyDIV = "MAINAREADIV";
	else
	GlobalMyDIV = "MAINAREADIV";
	obj = document.getElementById(GlobalMyDIV);
	
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetDIDSList_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetDIDSList_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		if(GlobalMyDIV!="")
			obj = document.getElementById(GlobalMyDIV);
		else
			obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}
function GetAreaList(CountryID)
{	
	DisplayHelpLine("Available DID Coverage by Area");
	if(CountryID=="-1"){
		var selObj = document.getElementById("Countries");
		CountryID = selObj.options[selObj.selectedIndex].value;
		if (CountryID =="-1"){
			DisplayHelpLine("Click the country name for its available areas");
			return false;
		}
		
	}
	
	if(CountryID=="")
			exit(0);
	
	
	//URL = websiteUrl + '/BuyDID_GetAreas?CountryID='+CountryID + '&Key='+(new Date().getTime());
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = '/BuyDID_GetAreas?countryId='+CountryID;
	//alert(URL);		
	obj = document.getElementById("MAINAREADIV");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = GetAreaList_updateContents;	
	http.send(null);	
	return false;
}

function GetAreaList_Craig(CountryID)
{	
	DisplayHelpLine("Available DID Coverage by Area");
	if(CountryID=="-1"){
		var selObj = document.getElementById("Countries");
		CountryID = selObj.options[selObj.selectedIndex].value;
		
	}
	
	if(CountryID=="")
			exit(0);
	
	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetAreas_Craig?CountryID='+CountryID + '&Key='+(new Date().getTime());
	//alert(URL);		
	obj = document.getElementById("MAINAREADIV");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = GetAreaList_updateContents;	
	http.send(null);	
	return false;
}

function GetAreaListPremium(CountryID)
{	
	DisplayHelpLine("Available DID Coverage by Area");
	if(CountryID=="-1"){
		var selObj = document.getElementById("Countries");
		CountryID = selObj.options[selObj.selectedIndex].value;
		
	}
	
	if(CountryID=="")
			exit(0);
	
	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetAreas_Premium?CountryID='+CountryID + '&Key='+(new Date().getTime());
	//alert(URL);		
	obj = document.getElementById("MAINAREADIV");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = GetAreaList_updateContents;	
	http.send(null);	
	return false;
}

function GetAreaList_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else{
			
			obj.innerHTML = http.responseText;
			
			document.body.style.cursor='default';
		}
		
	}
}

function BuyDID_GetVendors(AreaID)
{	
	DisplayHelpLine("Available DID Coverage by Area and Vendors");
	//URL = websiteUrl + '/BuyDID_GetVendors?AreaID='+AreaID + '&Key='+(new Date().getTime());
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetVendors?AreaID='+AreaID + '&Key='+(new Date().getTime());
	obj = document.getElementById("MAINAREADIV");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = GetAreaList_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetVendors_Craig(AreaID)
{	
	DisplayHelpLine("Available DID Coverage by Area and Vendors");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_GetVendors_Craig?AreaID='+AreaID + '&Key='+(new Date().getTime());
	obj = document.getElementById("MAINAREADIV");
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = GetAreaList_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetDIDInfo_Close()
{	
	
			obj = document.getElementById("DIDLISTPANEL");
			obj.style.display="block";
			
			obj = document.getElementById("GENERALINFO");
			obj.style.display="none";
			obj.innerHTML="";
	
	
}

function BuyDID_GetDIDInfo(DID)
{	
	DisplayHelpLine("General DID Number Information");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/CIDInfoInclude?did='+DID+'&includeit=1&Key='+(new Date().getTime());
	
	obj = document.getElementById("DIDLISTPANEL");
	obj.style.display="none";
	obj = document.getElementById("GENERALINFO");
	obj = document.getElementById("MAINAREADIV");
	obj.style.display="block";
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetDIDInfo_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_GetDIDInfo_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			
			document.body.style.cursor='default';
		
	}
}

// function BuyDID_GetVendorInfo(OID)
// {	
// 	DisplayHelpLine("General Vendor Information");
// 	var websiteUrl =  $( "#website_info" ).data( "website-url" );
// 	URL = websiteUrl + '/VendorInfoInclude?VID='+OID+'&includeit=1&Key='+(new Date().getTime());
// 	obj = document.getElementById("DIDLISTPANEL");
// 	obj.style.display="none";
// 	// obj = document.getElementById("GENERALINFO");
// 	// obj.style.display="block";
// 	obj.innerHTML="<img src=/images/searching.gif>";
// 	http.open('get',URL);
// 	http.onreadystatechange = BuyDID_GetVendorInfo_updateContents;	
// 	http.send(null);	
// 	return false;
// }


// function BuyDID_GetVendorInfo_updateContents(){
	
// 	if(http.readyState == 4)
// 	{	
		
// 		//obj = document.getElementById("GENERALINFO");
// 		obj = document.getElementById("MAINAREADIV");
// 		if(obj==null)
// 			alert("obj is null");
// 		else
// 			obj.innerHTML = http.responseText;
			
// 			document.body.style.cursor='default';
		
// 	}
// }

function BuyDID_GetVendorInfo(OID,AreaID,NXX)
{    
    DisplayHelpLine("General Vendor Information");
    $("#MAINAREADIV").html("<img src=/images/searching.gif>");
    $.get("/VendorInfoInclude",{VID:OID,includeit:1,AID:AreaID,NXX:NXX},function(data)
    {
        $("#MAINAREADIV").html(data);
        
    });
}

function BuyDID_PurchaseDIDTrigger(DID)
{	
	DisplayHelpLine("Confirm DID Purchase");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_PurchaseTrigger?DID='+ DID + '&Key='+(new Date().getTime());
	//alert(URL );
	obj = document.getElementById("DIDLISTPANEL");
	obj.style.display="none";
	obj = document.getElementById("GENERALINFO");
	obj.style.display="block";
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_PurchaseDID_updateContents;	
	http.send(null);	
	return false;
}

// function BuyDID_PurchaseDID(DID)
// {	
// 	DisplayHelpLine("Confirm DID Purchase");
// 	//URL = websiteUrl + '/BuyDID_PurchaseInclude?DID='+ DID + '&Key='+(new Date().getTime());
// 	URL = websiteUrl + '/BuyDID_PurchaseInclude?DID='+ DID + '&Key='+(new Date().getTime());
// 	//alert(URL );
// 	obj = document.getElementById("DIDLISTPANEL");
// 	obj.style.display="none";
// 	obj = document.getElementById("GENERALINFO");
// 	obj.style.display="block";
// 	obj.innerHTML="<img src=/images/searching.gif>";
// 	http.open('get',URL);
// 	http.onreadystatechange = BuyDID_PurchaseDID_updateContents;	
// 	http.send(null);	
// 	return false;
// }

function BuyDID_PurchaseDID(DID)
{ 
  //alert(DID);                                           
    DisplayHelpLine("Confirm DID Purchase");
    var websiteUrl =  $( "#website_info" ).data( "website-url" );
    $("#MAINAREADIV").html("<img src=/images/searching.gif>");
    var BuyDID_PurchaseIncludeUrl = websiteUrl + "/BuyDID_PurchaseInclude";
    $.get(BuyDID_PurchaseIncludeUrl,{DID:DID},function(data){
    //$.post("BuyDID_PurchaseInclude",{DID:DID},function(data){
    	$("#MAINAREADIV").html(data);    
    });
}

function BuyDID_PurchaseDIDChannel(DID)
{	
	DisplayHelpLine("Confirm DID Purchase");
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_PurchaseChannel?DID='+ DID + '&Key='+(new Date().getTime());
	//alert(URL );
	obj = document.getElementById("DIDLISTPANEL");
	
	obj = document.getElementById("MAINAREADIV");
	obj.style.display="block";
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_PurchaseDID_updateContents;	
	http.send(null);	
	return false;
}


function BuyDID_PurchaseDID_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("MAINAREADIV");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			
			document.body.style.cursor='default';
		
	}
}

function BuyDID_PurchaseDID_Close()
{	
			
			obj = document.getElementById("DIDLISTPANEL");
			obj.style.display="block";
			
			obj = document.getElementById("GENERALINFO");
			obj.style.display="none";
			obj.innerHTML="";
	
	
}


// function BuyDID_SubmitImage(DID,MSG)
// {	
// 	DisplayHelpLine("Upload Identification Documents");
// 	var chID;
// 	var chqty;
// 	var chGID , DID, MSG = "";
// 	alert("i am asif");
// 	if(document.getElementById("qty")!=null){
// 			chqty = document.getElementById("qty").value;
//             chID = document.getElementById("ChannelID").value;
//             chID = document.getElementById("ChannelID").value;
// 			chGID = document.getElementById("ChannelGroupID").value;
// 	}
	
// 	var IsTrigger="";
// 	if(document.getElementById("IsTrigger")!=null){
// 			IsTrigger = document.getElementById("IsTrigger").value;
			
// 	}
	
// 	var websiteUrl =  $( "#website_info" ).data( "website-url" );
// 	URL = websiteUrl + '/BuyDID_UploadImageDocs?IsTrigger='+IsTrigger+'&ChannelID='+chID+'&ChannelGroupID='+chGID+'&qty='+chqty+'&DID='+DID+'&MSG='+MSG+'&Key='+(new Date().getTime());
// 	obj = document.getElementById("DIDLISTPANEL");
// 	obj.style.display="none";
// 	obj = document.getElementById("GENERALINFO");
// 	obj.style.display="block";
// 	obj.innerHTML="<img src=/images/searching.gif>";
// 	http.open('get',URL);
// 	http.onreadystatechange = BuyDID_GetVendorInfo_updateContents;	
// 	http.send(null);	
// 	return false;
// }

function BuyDID_SubmitImage(DID,MSG)
{    
    DisplayHelpLine("Upload Identification Documents");
    var chID;
    var chqty;
    var websiteUrl =  $( "#website_info" ).data( "website-url" );

    chqty = $("#qty").val();
    chID = $("#ChannelID").val();
    chID = $("#ChannelID").val();
    chGID =$("#ChannelGroupID").val();

            
    var IsTrigger="";
    if(($("#IsTrigger").val())!=null){
            IsTrigger = $("#IsTrigger").val();
            
    }
            $("#MAINAREADIV").html("<img src=/images/searching.gif>");

    $.post(websiteUrl + "/BuyDID_UploadImageDocs",{IsTrigger:IsTrigger,ChannelID:chID,ChannelGroupID:chGID,qty:chqty,DID:DID,MSG:MSG},function(data)
    {
                        $("#MAINAREADIV").html(data);

    });
   
}

function BuyDID_SubmitText(DID,MSG)
{	
	DisplayHelpLine("Upload Identification Documents");
	var chID;
	var chqty;
	if(document.getElementById("qty")!=null){
			chqty = document.getElementById("qty").value;
			chID = document.getElementById("ChannelID").value;
	}
	
	var IsTrigger="";
	if(document.getElementById("IsTrigger")!=null){
			IsTrigger = document.getElementById("IsTrigger").value;
			
	}
	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	URL = websiteUrl + '/BuyDID_UploadTextDocs?IsTrigger='+IsTrigger+'&ChannelID='+chID+'&qty='+chqty+'&DID='+DID+'&MSG='+MSG+'&Key='+(new Date().getTime());
	obj = document.getElementById("DIDLISTPANEL");
	obj.style.display="none";
	obj = document.getElementById("GENERALINFO");
	obj.style.display="block";
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_GetVendorInfo_updateContents;	
	http.send(null);	
	return false;
}

function BuyDID_SubmitImage_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("GENERALINFO");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			
			document.body.style.cursor='default';
		
	}
}



function BuyDID_UploadImageDocs(DID,MSG)
{	
	DisplayHelpLine("Upload Identification Documents");
	
	if(document.getElementById("cusname").value==""){
			alert("Please enter customer name of this DID.");
			return false;
		}
		
	if(document.getElementById("address").value==""){
			alert("Please enter customer's address.");
			return false;
		}
		
	if(document.getElementById("contact").value==""){
			alert("Please enter customer's contact no. .");
			return false;
		}
		
	if(document.getElementById("email").value==""){
			alert("Please enter customer's email address.");
			return false;
		}
		
	if(document.getElementById("userfile5").value==""){
			alert("Please enter document file.");
			return false;
		}


	$("#UploadImageForm").attr("action", "/BuyDID_UploadImageDocsAction");
    $("#UploadImageForm").submit();				
	
}


function BuyDID_UploadTextDocs(DID,MSG)
{	
	DisplayHelpLine("Upload Identification Documents");
	
	if(document.getElementById("cusname").value==""){
			alert("Please enter customer name of this DID.");
			return false;
		}
		
	if(document.getElementById("address").value==""){
			alert("Please enter customer's address.");
			return false;
		}
		
	if(document.getElementById("contact").value==""){
			alert("Please enter customer's contact no. .");
			return false;
		}
		
	if(document.getElementById("email").value==""){
			alert("Please enter customer's email address.");
			return false;
		}
		
		if(document.getElementById("desc").value==""){
			alert("Please enter complete details.");
			return false;
		}
	
}

function BuyDID_UploadImageDocs_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("GENERALINFO");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			
			document.body.style.cursor='default';
		
	}
}


// function BuyDID_PurchaseDIDNow(MyElement)
// {	
	
// 	obj = document.getElementById("DIDLISTPANEL");
// 	obj.style.display="none";
// 	obj = document.getElementById("GENERALINFO");
// 	obj.style.display="block";
	
// 	var selObj = document.getElementById("RINGTOTYPE");
// 	RingToType = selObj.options[selObj.selectedIndex].value;
	
// 	var RingTo = document.getElementById("RingTo").value;
	
// 	var chID="";
// 	var chqty="";
	
// 	if(document.getElementById("qty")!=null){
// 			chqty = document.getElementById("qty").value;
// 			chID = document.getElementById("ChannelID").value;
//             chGID = document.getElementById("ChannelGroupID").value;
// 	}
// 	var IsTrigger="";
// 	if(document.getElementById("IsTrigger")!=null){
// 			IsTrigger = document.getElementById("IsTrigger").value;
			
// 	}
	
// 	URL = websiteUrl + '/BuyDID_BuyDIDFinal?IsTrigger='+IsTrigger+'&ChannelID='+chID+'&ChannelGroupID='+chGID+'&qty='+chqty+'&RINGTOTYPE='+RingToType+'&RingTo='+RingTo+'&DID='+MyElement+'&Key='+(new Date().getTime());
// //	alert(URL );
// 	obj = document.getElementById("GENERALINFO");
	
// 	obj.innerHTML="<img src=/images/searching.gif>";
	
// 	http.open('get',URL);
	
// 	http.onreadystatechange = BuyDID_PurchaseDIDNow_updateContents;	
	
// 	http.send(null);	
// 	return false;
// }

function BuyDID_PurchaseDIDNow(MyElement)
{    
            //alert("hello");
        //return false;
    var RingToType = $("#RINGTOTYPE").val();
    
    var RingTo =$("#RingTo").val();
    var quantity=$("#qty").val();
    if(quantity!=null || quantity!="" ){
            var chqty = $("#qty").val();
            var chID = $("#ChannelID").val();
            var chGID = $("#ChannelGroupID").val();
    }
    var IsTrigger="";
    var trigger=$("#IsTrigger").val();
    if(trigger!=null)
    {
            IsTrigger = $("#IsTrigger").val();
            
    }
        $("#MAINAREADIV").html("<img src=/images/searching.gif>");
        var websiteUrl =  $( "#website_info" ).data( "website-url" );
        var BuyDID_BuyDIDFinalUrl = websiteUrl + "/BuyDID_BuyDIDFinal";

    //$.post("BuyDID_BuyDIDFinal",{IsTrigger:IsTrigger,ChannelID:chID,ChannelGroupID:chGID,qty:chqty,RINGTOTYPE:RingToType,RingTo:RingTo,DID:MyElement},function(data)
    $.post(BuyDID_BuyDIDFinalUrl,{IsTrigger:IsTrigger,ChannelID:chID,ChannelGroupID:chGID,qty:chqty,RINGTOTYPE:RingToType,RingTo:RingTo,DID:MyElement},function(data)
    {
        $("#MAINAREADIV").html(data);
    });
//    alert(URL );
}

function BuyDID_PurchaseDIDNow_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		obj = document.getElementById("GENERALINFO");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			
			document.body.style.cursor='default';
		
	}
}


function GetDefaultRingTo(RingTo){
	
	//alert("arfeen");
	
		var selObj = document.getElementById("RINGTOTYPE");
	//alert("arfeen");
		if(selObj.options[selObj.selectedIndex].value=="11")
					document.getElementById("RingTo").value=RingTo;
		else if(selObj.options[selObj.selectedIndex].value=="10")
					document.getElementById("RingTo").value="radio@us1.didx.net";
		else	
					document.getElementById("RingTo").value="";
			
	
	
}


function BuyDID_BackOrder(CountryCode,AreaCode,Page)
{	
	var websiteUrl =  $( "#website_info" ).data( "website-url" );
	var URLs = websiteUrl + '/BuyDID_GetBackOrders?AreaCode='+AreaCode+'&CountryCode='+CountryCode+'&page='+Page+'&Key='+(new Date().getTime());
	//alert(URL);		
	
	
	obj = document.getElementById("BACKORDER");
	
	obj.innerHTML="<img src=/images/searching.gif>";
	http.open('get',URL);
	http.onreadystatechange = BuyDID_BackOrder_updateContents;	
	http.send(URLs);	
	return false;
}

function BuyDID_BackOrder_updateContents(){
	
	if(http.readyState == 4)
	{	
		
		
			obj = document.getElementById("BACKORDER");
		if(obj==null)
			alert("obj is null");
		else
			obj.innerHTML = http.responseText;
			document.body.style.cursor='default';
		
	}
}

function URLEncode(plaintext)
{
	// The Javascript escape and unescape functions do not correspond
	// with what browsers actually do...
	var SAFECHARS = "0123456789" +					// Numeric
					"ABCDEFGHIJKLMNOPQRSTUVWXYZ" +	// Alphabetic
					"abcdefghijklmnopqrstuvwxyz" +
					"-_.!~*'()";					// RFC2396 Mark characters
	var HEX = "0123456789ABCDEF";

	
	var encoded = "";
	for (var i = 0; i < plaintext.length; i++ ) {
		var ch = plaintext.charAt(i);
	    if (ch == " ") {
		    encoded += "+";				// x-www-urlencoded, rather than %20
		} else if (SAFECHARS.indexOf(ch) != -1) {
		    encoded += ch;
		} else {
		    var charCode = ch.charCodeAt(0);
			if (charCode > 255) {
			    alert( "Unicode Character '" 
                        + ch 
                        + "' cannot be encoded using standard URL encoding.\n" +
				          "(URL encoding only supports 8-bit characters.)\n" +
						  "A space (+) will be substituted." );
				encoded += "+";
			} else {
				encoded += "%";
				encoded += HEX.charAt((charCode >> 4) & 0xF);
				encoded += HEX.charAt(charCode & 0xF);
			}
		}
	} // for

	
	return encoded;
}
