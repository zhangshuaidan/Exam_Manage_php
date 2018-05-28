<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	$v =$res->value;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->query("select * from elective where course like '%{$v}%' ");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
//	print_r ($row);
	print_r(json_encode($row,JSON_UNESCAPED_UNICODE));
}
?>