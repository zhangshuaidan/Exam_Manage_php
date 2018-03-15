<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	$obj=$res->obj;
	
	$id=$obj->id;
	$course=$obj->course;
	$major=$obj->major;
	$grade=$obj->grade;
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");

	$repeat = $pdo->query("select count(course) as total from elective where course='{$course}' and major='{$major}' and grade='{$grade}' ");
	$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
	
	if($rowre[0]['total']>0){
	$obj= new stdClass();
	$obj->txt="更改选修失败";
	$obj->tip="选修记录:".$grade."级".$major."专业,选修课程:".$course."已存在";
	$obj->count=$rowre[0]['total'];
	returnStatus(100,"err",$obj);
}else{
	$sql = "update elective set course='{$course}',
			major='{$major}',grade='{$grade}' where id='{$id}' ";
	$result = $pdo->exec($sql);
	
	$obj= new stdClass();
	$obj->txt="更改选修成功";
	$obj->tip="已成功更改选修记录:".$grade."级".$major."专业,选修课程:".$course;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
	
}					
	}
?>