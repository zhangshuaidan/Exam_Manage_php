<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	
	$invigilator=$res->invigilator;
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	
//	$result=$pdo->exec("insert into invigilator (invigilator) values ('{$invigilator}')");
//	print_r($result);
	$result=$pdo->exec("insert into invigilator (invigilator) select '{$invigilator}' from dual where not exists (select * from invigilator where invigilator='{$invigilator}') ");
	
	if($result>0){
	$obj= new stdClass();
	$obj->txt="新增监考人员成功";
	$obj->tip="已成功为你新增监考人员:".$invigilator;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
}else{
	$obj= new stdClass();
	$obj->txt="新增监考人员失败";
	$obj->tip="监考人员:".$invigilator."已经存在";
	$obj->count=$result;
	returnStatus(100,"err",$obj);
}

}
?>