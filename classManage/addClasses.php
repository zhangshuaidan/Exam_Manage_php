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
	$count=$data->count;
//	print_r ($department);
//	print_r ($major);
//	print_r ($grade);
//	print_r ($class_name);
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");		
//	$result=$pdo->exec("insert into classes (department,major,grade,class_name) 
//	values ('{$department}','{$major}','{$grade}','{$class_name}')");
	

$result=$pdo->exec("insert into classes (department,major,grade,class_name,count) select '{$department}','{$major}','{$grade}','{$class_name}','{$count}'
from dual where not exists (select * from classes where department='{$department}' and major='{$major}' and grade='{$grade}' 
and class_name='{$class_name}') ");
if($result>0){
	$obj= new stdClass();
	$obj->txt="新增班级成功";
	$obj->tip="已成功新增".$grade."级".$major.$class_name."班";
	$obj->count=$result;
	returnStatus(200,"success",$obj);
}else{
//	echo "失败";
	$obj= new stdClass();
	$obj->txt="新增班级失败";
	$obj->tip="班级:".$grade."级".$major.$class_name."班,已经存在";
	$obj->count=$result;
	returnStatus(100,"err",$obj);
}
//print_r ($result);
//echo $result;
}
?>