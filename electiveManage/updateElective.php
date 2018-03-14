<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
//	print_r($res);
	$obj=$res->obj;
	
	$id=$obj->id;
	$course=$obj->course;
	$major=$obj->major;
	$grade=$obj->grade;
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$sql = "update elective set course='{$course}',
			major='{$major}',grade='{$grade}' where id='{$id}' ";
			$result = $pdo->exec($sql);
			print_r ($result);

					
	}
?>