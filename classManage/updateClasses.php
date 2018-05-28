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
$count=$data->count;
$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");

$repeat = $pdo->query("select count(major) as total from classes where department='{$department}' and major='{$major}' and grade='{$grade}' and class_name='{$class_name}' and count='{$count}' ");
$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
//print_r($rowre[0]);
if($rowre[0]['total']>0){
	$obj= new stdClass();
	$obj->txt="更改班级信息失败";
	$obj->tip="班级:".$grade."级".$major.$class_name."班,已经存在";
	$obj->count=$rowre[0]['total'];
	returnStatus(100,"err",$obj);
}else{
	$sql = "update classes set department='{$department}',
	major='{$major}',grade='{$grade}',class_name='{$class_name}',count='{$count}' where id='{$id}' ";
	$result = $pdo->exec($sql);
	$obj= new stdClass();
	$obj->txt="更改班级信息成功";
	$obj->tip="已成功为你更改".$grade."级".$major.$class_name."班";
	$obj->count=$result;
	returnStatus(200,"success",$obj);
	
}


//
//print_r ($result);

}

?>