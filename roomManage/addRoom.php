<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	
	$room=$res->room;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->exec("insert into room (room) values ('{$room}')");
	print_r($result);
}
?>