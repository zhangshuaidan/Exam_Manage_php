<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
//	$data =json_decode($res->data);	
	$id=$res->id;
	$coursename=$res->coursename;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$sql = "update course set coursename='{$coursename}' where id='{$id}' ";
	$result = $pdo->exec($sql);
    print_r ($result);
}

?>