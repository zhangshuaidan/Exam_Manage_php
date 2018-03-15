<?php
	include_once "../lib/fun.php";
	include_once "export.php";
//
   $xmldata = file_get_contents("php://input"); 
//// echo $xmldata;
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->query("select * from arrange");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	
//	print_r(json_encode($row,JSON_UNESCAPED_UNICODE));
	$newarr=array();
	foreach($row as $v){
		unset($v['id']);
		array_push($newarr,$v);
	}
//	print_r($newarr);
		
   exportExcel(array('科目',"日期","时间","专业","年级","监考教室","监考人员"),$newarr, '档案', './', true);  
 

?>