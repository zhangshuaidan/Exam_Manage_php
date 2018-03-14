<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}



if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->query("select * from classes");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	print_r(json_encode($row,JSON_UNESCAPED_UNICODE));
}
?>