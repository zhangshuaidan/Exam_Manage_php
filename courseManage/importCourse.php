<?php
include_once "../lib/fun.php";
$file = $_FILES['myfile'];
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
	$myfile= $_FILES['myfile']['tmp_name'];
//	importExecl($myfile);
	$myarr = importExecl($myfile);
//	print_r(json_encode($myarr));
//	print_r($myarr);
	$newarr=array();
for ($i = 2; $i < sizeof($myarr)-2; $i++) {  
//  echo $myarr[$i]["B"]. "<br>";  
	array_push($newarr,$myarr[$i]["B"]);
}  
//print_r($newarr);
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
//	$result=$pdo->exec("insert into course (coursename) values ('测试数据') ");
	
}

?>