<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
//	$data =json_decode($res->data);	
	$id=$res->id;
	$invigilator=$res->invigilator;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");

//  print_r ($result);
	$repeat = $pdo->query("select count(invigilator) as total from invigilator where invigilator='{$invigilator}' ");
	$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
	if($rowre[0]['total']>0){
	$obj= new stdClass();
	$obj->txt="更改监考人员信息失败";
	$obj->tip="监考人员:".$invigilator."已经存在";
	$obj->count=$rowre[0]['total'];
	returnStatus(100,"err",$obj);
	}else{
	$sql = "update invigilator set invigilator='{$invigilator}' where id='{$id}' ";
	$result = $pdo->exec($sql);
	$obj= new stdClass();
	$obj->txt="更改监考人员成功";
	$obj->tip="已成功为你更改监考人员:".$invigilator;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
	}
}
?>