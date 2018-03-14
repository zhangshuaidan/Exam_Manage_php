<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}


//	$result=$pdo->query("select * , group_concat(major) from arrange group by subject,date,time ");
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->query("select *  from arrange");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	print_r(json_encode($row,JSON_UNESCAPED_UNICODE));
}
?>