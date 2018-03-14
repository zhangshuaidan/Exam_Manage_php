<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}
if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$res =json_decode($GLOBALS['HTTP_RAW_POST_DATA']);

	$obj=json_decode($res->obj);
	
	$subject=$obj->subject;
	$date=$obj->date;
	$time=$obj->time;
	$room=$obj->room;
	$invigilator=$obj->invigilator;
	

	
	//查询 选修本课程的专业
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	$result=$pdo->query("select major from elective where course='{$subject}' ");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
//	print_r($row);
	
	
	//判断 涉及 的专业 是否 在安排表中冲突
	if(count($row)>0){
		
		$majorcft=array();
		foreach ($row as $key => $value) {  
     	foreach ($value as $key => $value) {  
		$repeat=$pdo->query("select * from arrange where major='{$value}' && date='{$date}' && time='{$time}' ");
		$rowrepeat = $repeat->fetchAll(PDO::FETCH_ASSOC);
		if(count($rowrepeat)>0){			 
			$majorcft=array_merge($majorcft,$rowrepeat);
		}
		
		}	
	}
	 if(count($majorcft)>0){
	 	$majorcfttip="以下班级在当前日期和考试时间已经存在考试安排，分别为";
	 	foreach ($majorcft as $key => $value){
	 		$majorcfttip.=$value['grade']."级".$value['major']."考试科目:".$value['subject'].",";
	 }
	      $majorcftobj= new stdClass();
	      $majorcftobj->txt="考试科目安排冲突";
	      $majorcftobj->tip=$majorcfttip;
	      $majorcftobj->count=count($majorcft);
	 	  returnStatus(100,"err",$majorcftobj);
		return false;
	 }

	
	//判断教室是否安排冲突
	$roomrepeat=$pdo->query("select * from arrange where room='{$room}' && date='{$date}' && time='{$time}' ");
	$rowroomrepeat=$roomrepeat->fetchAll(PDO::FETCH_ASSOC);
	if(count($rowroomrepeat)>0){
		  $roomcftobj= new stdClass();
	      $roomcftobj->txt="教室安排冲突";
	      $roomcftobj->tip="在{$date}日时间为{$time},考场{$room}已存在考试安排,";
		  returnStatus(101,"err",$roomcftobj);
		return false;
	}
	
	//判断监考人员是否冲突
	$ingtorrepeat=$pdo->query("select * from arrange where invigilator='{$invigilator}' && date='{$date}' && time='{$time}' ");
	$rowingtor=$ingtorrepeat->fetchAll(PDO::FETCH_ASSOC);
	if(count($rowingtor)>0){			 
//		returnStatus(103,"err","监考人员安排冲突");
		 $ingtorcftobj= new stdClass();
	      $ingtorcftobj->txt="监考人员安排冲突";
	      $ingtorcftobj->tip="在{$date}日时间为{$time},监考人员{$invigilator}已存在监考安排,";
		  returnStatus(101,"err",$ingtorcftobj);
		return false;
	}
	
	
	
	
//	print_r ("没有冲突");
	$arr_result=$pdo->query("select * from elective where course='{$subject}' ");
	$arr_row = $arr_result->fetchAll(PDO::FETCH_ASSOC);
		$tip="";
		foreach ($arr_row as $key => $value) { 
			
			$result=$pdo->exec("insert into arrange (subject,date,time,major,grade,room,invigilator) 
				values ('{$subject}','{$date}','{$time}','{$value['major']}','{$value['grade']}','{$room}','{$invigilator}')");
			$tip=$tip.$value['grade']."级".$value['major'].",";
     }
     
     $successobj= new stdClass();
     $successobj->str=$tip;
     $successobj->count=count($arr_row);
     
//  	print_r($tip);
	returnStatus(200,"success",$successobj);
	}else{
		  $elecftobj= new stdClass();
	      $elecftobj->txt="未找到科目为{$subject}选修记录";
	      $elecftobj->tip="请在选修管理中添加科目为{$subject}的选修记录，";
		  returnStatus(101,"err",$elecftobj);
//		echo "没有找到选修本课程的班级";
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