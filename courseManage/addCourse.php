<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	$course=$res->coursename;
	$coursecode=$res->coursecode;
	$teacher=$res->teacher;
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
//	$result=$pdo->exec("insert into course (coursename) values ('{$course}')");
	$result=$pdo->exec("insert into course (coursecode,coursename,teacher) select 
	 '{$coursecode}','{$course}','{$teacher}' from dual where not exists (select * from course where  coursename='{$course}'  ) ");

if($result>0){
	$obj= new stdClass();
	$obj->txt="新增课程成功";
	$obj->tip="已成功为你添加课程:".$course;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
}else{
	$obj= new stdClass();
	$obj->txt="新增课程失败";
	$obj->tip="课程:".$course."已经存在";
	$obj->count=$result;
	returnStatus(100,"err",$obj);
}

//	$data =json_decode($res->data);
//	$department=$data->department;
//	$major=$data->major;
//	$grade=$data->grade;
//	$class_name=$data->class_name;
	
//	print_r ($department);
//	print_r ($major);
//	print_r ($grade);
//	print_r ($class_name);
	
//	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
//	$result=$pdo->exec("insert into classes (department,major,grade,class_name) 
//	values ('{$department}','{$major}','{$grade}','{$class_name}')");
	
	
}
?>