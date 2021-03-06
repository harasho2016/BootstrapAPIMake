<?php
	/* Query Method
	** 	$methodName 	=$_GET['qm_methodName'] // Method Name(SELECT, DELETE, UPDATE, INSERT)
	** 	$numCountBool 	= $_GET['qm_numCountBool'] // Method Name(SELECT Method only)
	** 	$orderBy 		= $_GET['qm_orderBy'] // Response Order(SELECT Nethod only)
	** 	$orderByTarget 	= $_GET['qm_orderByTarget'] // Response Order Target(SELECT Method and (orderBy DESC or orderBy ASC) only)
	** 	$limit 			= $_GET['qm_limit'] // Response Limit(Select Method Only)
	============================================ */
	// Evaluate Query Method (INSERT, UPDATE, DELETE)
	$numCountBool 	= false;
	$limit 			= '';
	if(isset($orderBy)) call404();
	if(isset($orderByTarget)) call404();

	// Make "SET" Selecter
	$updSet = '';
	if(count($setTargetKey) == 1){
		$updSet = " $setTargetKey[0] = '".$_GET[$setTargetValue[0]]."' ";
	}else if(count($setTargetKey) >= 2){
		$updSet = " $setTargetKey[0] = '".$_GET[$setTargetValue[0]]."' ";
		for($i=1; $i<count($setTargetKey); $i++){
			$updSet = $updSet." ,$setTargetKey[$i] = '".$_GET[$setTargetValue[$i]]."' ";
		}
	}

	// Make "Where" Selecter
	$updWhere = '';
	if(count($colTarget) == 1){
		$updWhere = " WHERE $colTarget[0] = '".$_GET[$colTarget[0]]."' ";
	}else if(count($colTarget) >= 2){
		$updWhere = " WHERE $colTarget[0] = '".$_GET[$colTarget[0]]."' ";
		for($i=1; $i<count($colTarget); $i++){
			$updWhere = $updWhere." AND $colTarget[$i] = '".$_GET[$colTarget[$i]]."' ";
		}
	}

	// Evaluate Query Method and Make "SQL" and Execute
	$i = 0;
	if($updWhere != '' && $updSet != ''){
		try{
			$targetSQL = "UPDATE $tbName SET $updSet $updWhere";
		    $upd = $dbc->prepare($targetSQL);
			$upd -> execute();
			$data["status"] = 'SUCCESS';
			$data["count"] = $upd->rowCount();

		}catch (PDOException $e){
			$data["status"] 	= 'ERROR';
			$data["count"] 		= 0;
			$data["message"] 	= $e;
		}
	}else{
		$data["status"] = 'WRAN';
		$data["count"] = 0;
		$data["message"] = 'Please specify your "SET" and "where" selecter.';
	}


	// outputJson
	apiResultShowJson($data);
