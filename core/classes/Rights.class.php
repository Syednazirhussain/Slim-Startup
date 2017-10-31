<?php
//include_once $INCLUDEPATH."/ADb.inc.php";
class Rights
{
	// 	Copyright©2008 Muhammad Arfeen. All rights reserved.
	var $fDebug = 0;
	function dPrint($str) { if($this->fDebug) echo "$str<br>\n";}
	function aDPrint($arr) { if($this->fDebug){	var_dump($arr);	}}	
	function Rights()
	{
		$this->Db = new ADb();
	}	
	
	
	function getAllRights($pEncUID) {
	
	$strSQL	= 	"select
					*
				from
					rights";
	
	
	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	return $Result;
}#getAllRights


function getAdminRights($pAUID) {

	
	
	$strSQL	= "	select 
					r.rtype,
					ar.arvalue
				from 
					adminrights ar,
					rights r
				where 
					ar.auid	= '$pAUID'
				And
					r.rid = ar.righttype
					";
	
	$Result	= $this->Db->ExecuteQuery($strSQL) ;
	
	$AdminRights = array();
	while(!$Result->EOF) {
		$AdminRights[$Result->fields[0]]= $Result->fields[1];
		$Result->MoveNext();
	}
	return $AdminRights;
} # End getAdminRights

function getAdminRightsAll($pAUID) {

	$strSQL	= "	select 
					arid,
					auid,
					righttype,
					arvalue
				from 
					adminrights
				where 
					auid	= '$pAUID'
					";
	#dPrint "\$strSQL: $strSQL";
	$Result	= $this->Db->ExecuteQuery($strSQL) ;
	
	$AdminRights;
	while(!$Result->EOF) {
		$AdminRights{$Result->fields[2]}= $Result->fields[3];
		$Result->MoveNext();
	}
	return $AdminRights;
} # End getAdminRightsAll

#	getAdminRightsTable
#	IN:	AUID, Mode (Edit, View, Add)
#	OUT:	Tabluated HTML

//function getAdminRightsTable($pZero,$pOne,$pTwo) {
//	
//	$Mode=$pOne;
//	$AdminRights	= getAdminRightsAll($pZero);
//	$AdminType	= $pTwo;
//	# RIGHTS
//	$Rights	= getAllRights();
//	$rightshtml;
//	$fSwitch=1;
//	
//	foreach ($nIndex=1; $nIndex<=$#Rights; $nIndex+=4){
//		#$IsRestricketd;
//		#if ($AdminType eq 'RESE'){
//		#	# nIndex
//		#	$RT	= $Rights[$nIndex][3];
//		#	print "BEFORE CHECK \$RT: $RT<br>";
//	  	#	if ( ($RT eq 'DADM')||($RT eq 'DORD')||($RT eq 'SUPE')||($RT eq 'CLGN')||($RT eq 'VNEW') ){
//	  	#		print "UNDER CHECK \$RT: $RT<br>";
//	  	#		next;
//	  	#	}
//		#	
//		#	# nIndex+1
//		#	$RT	= $Rights[$nIndex+1][3];
//		#	print "BEFORE CHECK \$RT: $RT<br>";
//	  	#	if ( ($RT eq 'DADM')||($RT eq 'DORD')||($RT eq 'SUPE')||($RT eq 'CLGN')||($RT eq 'VNEW') ){
//	  	#		print "UNDER CHECK \$RT: $RT<br>";
//	  	#		next;
//	  	#	}
//                #
//		#	# nIndex+2
//		#	$RT	= $Rights[$nIndex+2][3];
//		#	print "BEFORE CHECK \$RT: $RT<br>";
//	  	#	if ( ($RT eq 'DADM')||($RT eq 'DORD')||($RT eq 'SUPE')||($RT eq 'CLGN')||($RT eq 'VNEW') ){
//	  	#		print "UNDER CHECK \$RT: $RT<br>";
//	  	#		next;
//	  	#	}
//                #
//                #
//		#	# nIndex+3
//		#	$RT	= $Rights[$nIndex+3][3];
//		#	print "BEFORE CHECK \$RT: $RT<br>";
//	  	#	if ( ($RT eq 'DADM')||($RT eq 'DORD')||($RT eq 'SUPE')||($RT eq 'CLGN')||($RT eq 'VNEW') ){
//	  	#		print "UNDER CHECK \$RT: $RT<br>";
//	  	#		next;
//	  	#	}
//                #
//	  	#}
//		if ($fSwitch){
//			$rightshtml .= "<tr bgcolor=#FAFEE9>";
//			$fSwitch=0;
//		}
//		else {
//			$rightshtml .= "<tr>";
//			$fSwitch=1;
//		}
//		for ($nIndex2=$nIndex; $nIndex2<$nIndex+4; $nIndex2++)
//		{
//		    	# If Admin Already Exist (Edit/View)
//		    	if ($Mode ne 'ADD')
//		    	{
//				#$RT	= $Rights[$nIndex2][3];
//				#print "BEFORE CHECK \$RT: $RT<br>";
//		  		#if ( ($RT eq 'DADM')||($RT eq 'DORD')||($RT eq 'SUPE')||($RT eq 'CLGN')||($RT eq 'VNEW') ){
//		  		#	print "UNDER CHECK \$RT: $RT<br>";
//		  		#	next;
//		  		#}
//			    	if ($Rights[$nIndex2][1] ne ""){
//				    	$rightshtml .= "<td align=right><font face=verdana size=-1>$Rights[$nIndex2][1]</td>
//				    	<td><font face=verdana size=-1><center>";
//				    	#print "\$AdminRights{$Rights[$nIndex2][0]}: $AdminRights{$Rights[$nIndex2][0]}<br>";
//				    	if ($AdminRights{$Rights[$nIndex2][0]}){
//				    		if ($Mode eq 'EDIT'){
//				    			$rightshtml .= "<input type='checkbox' name='AdminRights' value='$Rights[$nIndex2][0]' checked>";
//				    		}
//				    		else {
//				    				$rightshtml .= "<font face=verdana size=-1>YES</font>";
//				    		}
//				    	}
//				    	else {
//				    		if ($Mode eq 'EDIT'){
//				    			$rightshtml .= "<input type='checkbox' name='AdminRights' value='$Rights[$nIndex2][0]'>";
//				    		}
//				    		else {
//				    				$rightshtml .= "<font face=verdana size=-1>NO</font>";
//				    		}
//				    	}
//					$rightshtml .= "</center></td>";
//				}
//			    	# Have no rights
//			    	else {
//			    	
//			    		$rightshtml .= "<td colspan=2>&nbsp;</td>";
//				}
//			}
//			# ADD
//		    	else {
//			    	if ($Rights[$nIndex2][1] ne ""){
//				    	$rightshtml .= "<td align=right><font face=verdana size=-1>$Rights[$nIndex2][1]</td>
//				    	<td><font face=verdana size=-1><center>
//				    	<input type='checkbox' name='AdminRights' value='$Rights[$nIndex2][0]'>";
//				}
//				else {
//			    		$rightshtml .= "<td colspan=2>&nbsp;</td>";
//			    	}
//		    	}
//		    	
//		    	$rightshtml .= "<td bgcolor=white>&nbsp;</td>";
//	    	}# for
//	  	$rightshtml .= "</tr>";
//  
//	}# for
//	dPrint "\$rightshtml: $rightshtml";
//	return $rightshtml;
//}


function addRights($pHash){

	$strSQL	= "	insert into rights
					(rid,
					rtitle,
					rdesc,
					rtype)
					values (
					'$pHash{RID}',
					'$pHash{Title}',
					'$pHash{Desc}',
					'$pHash{Type}')";
	#dPrint "\$strSQL: $strSQL";
	$Result	= $this->Db->ExecuteQuery($strSQL) ;
	
	return $Result->fields[0];
}

function isRightExist($pRID, $pRType) {

	


	$strSQL	= 	"select
					*
				from
					rights
				where
					rid='$pRID'
				or
					rtype='$RType'";
	

	
	$Result	= $this->Db->ExecuteQuery($strSQL);
	if ($Result->EOF){
		return 0;
	}
	else {
		return 1;
	}
}#isRightExist


#	editAdminRights
#	IN:	AUID, Array of right
#	OUT:	Boolean
//function editAdminRights {
//	(	$pAUID, @AURight)	= @_;
//	
//	$fResult	= 1;
//	
//	# Delet All rights
//	$strSQL	= "	delete from adminrights 
//				where 
//					auid	= \"$pAUID\"";
//	
//	dPrint "\$strSQL: $strSQL";
//	dPrint "\$#AURight: $#AURight";
//	@Delete	= $this->Db->ExecuteQuery($strSQL);
//	dPrint 	"\$Delete[0]: $Delete[0]";
//	if (!$Delete[0]) {
//		$fResult	= 0;
//	}
//	
//	$IsAdded	= addAdminRights($pAUID, @AURight);
//	$fResult	= $IsAdded;
//	return $fResult;
//}#editAdminRights
//
//
//#	addAdminRights
//#	IN:	AUID, Array of right
//#	OUT:	Boolean
//function addAdminRights {
//	(	$pAUID, @AURight)	= @_;
//	
//	$fInsertResult	= 1;
//	
//	
//	for ($nIndex=0; $nIndex<=$#AURight; $nIndex++) {
//	
//		$ARID	= substr(MD5->hexhash(time(). {$nIndex.$pAUID} . rand(). $$. @_),0,10);
//		$strSQL	= "	insert into 
//						adminrights 
//					values (
//						\"$ARID\",
//						\"$pAUID\",
//						\"$AURight[$nIndex]\",
//						1
//						)";
//		
//		$Result	= $this->Db->ExecuteQuery($strSQL);
//		dPrint "\$Result[0]: $Result[0]";
//		$fInsertResult	= $Result[0];
//	}
//	return $fInsertResult;
//}#addAdminRights
//	
	
}
?>