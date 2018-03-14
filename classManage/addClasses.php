<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);

	$data =json_decode($res->data);
	$department=$data->department;
	$major=$data->major;
	$grade=$data->grade;
	$class_name=$data->class_name;
	
//	print_r ($department);
//	print_r ($major);
//	print_r ($grade);
//	print_r ($class_name);
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->exec("insert into classes (department,major,grade,class_name) 
	values ('{$department}','{$major}','{$grade}','{$class_name}')");
	
	print_r ($result);
}
?>