<?php
include_once "../lib/fun.php";
$file = $_FILES['myfile'];
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
	$myfile= $_FILES['myfile']['tmp_name'];
	$myarr = importExecl($myfile);
	
	
	for ($i = 2; $i < sizeof($myarr); $i++) {

		$grade="";
		$class="";
		if(strpos($myarr[$i]["B"],"14")!==false){
			$myarr[$i]["B"]="2014";
		}else if(strpos($myarr[$i]["B"],"15")!==false){
				$myarr[$i]["B"]="2015";
		}else if(strpos($myarr[$i]["B"],"16")!==false){
			$myarr[$i]["B"]="2016";
		}
		
		if(strpos($myarr[$i]["D"],"1")!==false){
			$myarr[$i]["D"]="1";
		}else if(strpos($myarr[$i]["D"],"2")!==false){
				$myarr[$i]["D"]="2";
		}else if(strpos($myarr[$i]["D"],"3")!==false){
			$myarr[$i]["D"]="3";
		}
		
		
//			print_r($myarr[$i]);
//		for($j=0;$j<sizeof($myarr[$i]);$j++){
//			
//		}

	}
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$c=0;
	for ($i = 2; $i < sizeof($myarr); $i++) {
//		print_r($myarr[$i]);
	$repeat = $pdo->query("select count(major) as total from classes where major='{$myarr[$i]['C']}' and grade= '{$myarr[$i]['B']}' and class_name='{$myarr[$i]['D']}'  ");
	$rowre = $repeat->fetchALL(PDO::FETCH_ASSOC);
	if($rowre[0]['total']<=0){
		$result=$pdo->exec("insert into classes (department,major,grade,class_name,count) values
		 ('{$myarr[$i]['A']}','{$myarr[$i]['C']}','{$myarr[$i]['B']}','{$myarr[$i]['D']}','{$myarr[$i]['E']}') ");
		$c++;
	}
		
	}
	
	 $elecftobj= new stdClass();
	$elecftobj->txt="已成功为你导入".$c."条班级记录";
	$elecftobj->tip="提示";
	returnStatus(100,"success",$elecftobj);
//	print_r($c);
	
}
?>