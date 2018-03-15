<?php
include_once "../lib/fun.php";
$file = $_FILES['myfile'];
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
	$myfile= $_FILES['myfile']['tmp_name'];
	$myarr = importExecl($myfile);
	$newarr=array();
for ($i = 2; $i < sizeof($myarr)-2; $i++) {  
	array_push($newarr,$myarr[$i]["B"]);
}  
//print_r(array_unique($newarr));

$newarr=array_unique($newarr);

	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	
//	$result=$pdo->exec("truncate table course");
//	echo $result;
$c=0;
foreach($newarr as $v){
	
	$repeat = $pdo->query("select count(coursename) as total from course where coursename='{$v}' ");
	$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
	if($rowre[0]['total']<=0){
		$result=$pdo->exec("insert into course (coursename) values ('{$v}') ");
		$c++;
	}
}
   	$elecftobj= new stdClass();
	$elecftobj->txt="已成功为你导入".$c."个课程";
	$elecftobj->tip="提示";
	returnStatus(200,"success",$elecftobj);
}

?>