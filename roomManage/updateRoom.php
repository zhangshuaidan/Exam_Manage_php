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
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$sql = "update room set room='{$room}' where id='{$id}' ";
	$result = $pdo->exec($sql);
    print_r ($result);
}
?>