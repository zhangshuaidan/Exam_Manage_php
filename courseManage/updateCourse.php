<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
//	$data =json_decode($res->data);	
	$id=$res->id;
	$coursename=$res->coursename;
	$coursecode=$res->coursecode;
	$teacher=$res->teacher;
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$repeat = $pdo->query("select count(coursename) as total from course where coursename='{$coursename}' ");
	$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
	if($rowre[0]['total']>0){
	$obj= new stdClass();
	$obj->txt="更改班级课程信息失败";
	$obj->tip="课程:".$coursename."已经存在";
	$obj->count=$rowre[0]['total'];
	returnStatus(100,"err",$obj);
	}else{
		
	$sql = "update course set coursename='{$coursename}',coursecode='{$coursecode}',teacher='{$teacher}'  where id='{$id}' ";
	$result = $pdo->exec($sql);
	$obj= new stdClass();
	$obj->txt="更改班级课程信息成功";
	$obj->tip="已成功为你更改课程:".$coursename;
	$obj->count=$result;
	returnStatus(200,"success",$obj);
	}
	


//  print_r ($result);
}

?>