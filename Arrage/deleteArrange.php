<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);

	$subject=$res->subject;
	$date=$res->date;
	$time=$res->time;
	
//	print_r ($subject);
//	print_r ($date);
//	print_r ($time);
	
//	$id =json_decode($res->id);

	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result = $pdo->exec("delete from arrange where subject='{$subject}' && date='{$date}' && time='{$time}' ");
	print_r ($result);
}
?>