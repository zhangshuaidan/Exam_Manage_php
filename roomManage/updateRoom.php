<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
//	$data =json_decode($res->data);	
	$id=$res->id;
	$room=$res->room;
	$hold=$res->hold;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");

//  print_r ($result);
    
    $repeat = $pdo->query("select count(room) as total from room where room='{$room}' and hold='{$hold}' ");
	$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
	
	if($rowre[0]['total']>0){
	$obj= new stdClass();
	$obj->txt="更改教室信息失败";
	$obj->tip="教室:".$room."已经存在";
	$obj->count=$rowre[0]['total'];
	returnStatus(100,"err",$obj);
	}else{
	$sql = "update room set room='{$room}',hold='{$hold}'  where id='{$id}' ";
	$result = $pdo->exec($sql);
	$obj= new stdClass();
	$obj->txt="更改教室信息成功";
	$obj->tip="已成功为你更改教室:".$room;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
	}
}
?>