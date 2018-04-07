<?php
include_once "../lib/fun.php";
$file = $_FILES['myfile'];
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
	$myfile= $_FILES['myfile']['tmp_name'];
	$myarr = importExecl($myfile);
		$newarr=array();
	for ($i = 2; $i < sizeof($myarr)-2; $i++) {  
		$a=array();
		array_push($a,$myarr[$i]["B"]);
		array_push($a,$myarr[$i]["E"]);
		array_push($newarr,$a);
}  


	for ($i = 0; $i < sizeof($newarr); $i++) {  
      $c=explode(",",$newarr[$i][1]);

		    for($j=0;$j<sizeof($c);$j++){
		    	$class="";
		    	$grade="";
		    	if(strpos($c[$j],"数媒")!==false){
		    		$class="数字媒体";
		    	}else if(strpos($c[$j],"网络")!==false){
		    		$class="网络工程";
		    	}else if(strpos($c[$j],"计科")!==false){
		    		$class="计算机科学与技术";
		    	}else  if(strpos($c[$j],"物联网")!==false){
		    		$class ="物联网工程";
		    	}else {
		    		$class ="";
		    	}
		    	
		    	
		    	if(strpos($c[$j],"15")!==false){
		    			$grade="2015";
		    	}else if(strpos($c[$j],"16")!==false){
		    		$grade="2016";
		    	}else if(strpos($c[$j],"17")!==false){
		    		$grade="2017";
		    	}else  if(strpos($c[$j],"18")!==false){
		    		$grade="2018";
		    	}else {
		    		$grade="";
		    	}
		    			    	
		    	
		    	$newc =array("class"=>$class,"grade"=>$grade);
		    	$c[$j]=$newc;
		    	
		    }
		       $newarr[$i][1]=$c;
}  

	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
$totalcount=0;
for($k=0;$k<sizeof($newarr);$k++){
	$mycourse=$newarr[$k][0];
	$myclass=$newarr[$k][1][0]["class"];
	$mygrade=$newarr[$k][1][0]['grade'];
	if($myclass){
$result=$pdo->exec("insert into elective (course,major,grade) select '{$mycourse}','{$myclass}','{$mygrade}'
from dual where not exists (select * from elective where course='{$mycourse}' and major='{$myclass}' and grade='{$mygrade}')");
//	print_r($result);
	if($result>0){
		$totalcount++;
	}
//	echo "\n";
	
	}
}	
  	$elecftobj= new stdClass();
	$elecftobj->txt="已成功为你导入".$totalcount."条选修记录";
	$elecftobj->tip="提示";
	returnStatus(100,"success",$elecftobj);
//echo ("共导入".$totalcount);
	

}

?>