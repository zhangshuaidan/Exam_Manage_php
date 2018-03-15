<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	
	$obj=json_decode($res->obj);

	$course=$obj->course;
	$major=$obj->major;
	$grade=$obj->grade;
	
	
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
//	$result=$pdo->exec("insert into elective (course,major,grade) 
//	values ('{$course}','{$major}','{$grade}')");
	

	
$result=$pdo->exec("insert into elective (course,major,grade) select '{$course}','{$major}','{$grade}'
from dual where not exists (select * from elective where course='{$course}' and major='{$major}' and grade='{$grade}')");
//	print_r ($result);
if($result>0){
	$obj= new stdClass();
	$obj->txt="新增选修成功";
	$obj->tip="已成功新增选修记录:".$grade."级".$major."专业,选修课程:".$course;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
}else{
	$obj= new stdClass();
	$obj->txt="新增选修失败";
	$obj->tip="选修记录:".$grade."级".$major."专业,选修课程:".$course."已存在";
	$obj->count=$result;
	returnStatus(100,"err",$obj);
}

}
?>