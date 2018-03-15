<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	
	$room=$res->room;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
//	$result=$pdo->exec("insert into room (room) values ('{$room}')");
//	print_r($result);

	$result=$pdo->exec("insert into room (room) select '{$room}' 
	from dual where not exists (select * from room where room='{$room}') ");
	
if($result>0){
	$obj= new stdClass();
	$obj->txt="新增教室成功";
	$obj->tip="已成功为你新增教室:".$room;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
}else{
	$obj= new stdClass();
	$obj->txt="新增教室失败";
	$obj->tip="教室:".$room."已经存在";
	$obj->count=$result;
	returnStatus(100,"err",$obj);
}
}
?>