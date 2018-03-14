<?php
	
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
$data =json_decode($res->data);


$id=$data->id;
$department=$data->department;
$major=$data->major;
$grade=$data->grade;
$class_name=$data->class_name;

//print_r ($id);
//print_r ($department);
//print_r ($major);
//print_r ($grade);
//print_r ($class_name);

$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
$sql = "update classes set department='{$department}',
	major='{$major}',grade='{$grade}',class_name='{$class_name}' where id='{$id}' ";
$result = $pdo->exec($sql);

print_r ($result);

}

?>