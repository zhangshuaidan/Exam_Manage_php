<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	$id =json_decode($res->id);
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result = $pdo->exec("delete from course where id ={$id} limit 1");
	print_r ($result);
}
?>