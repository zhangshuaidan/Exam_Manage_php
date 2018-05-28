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
	$roomstr=implode(",", $room);
	$invigilator=$obj->invigilator;
	$invigstr=implode(",",$invigilator);
	
	
//	print_r($invigilator);
//	exit();

	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
	
	$hasArrange=$pdo->query("select * from arrange where subject='{$subject}' ");
	$hasArrangerow = $hasArrange->fetchAll(PDO::FETCH_ASSOC);
	
	if(!empty($hasArrangerow)){
		  $majorcftobj= new stdClass();
	      $majorcftobj->txt="考试科目已安排";
	      $majorcftobj->tip=$subject."已进行考试安排,";
	 	  returnStatus(100,"err",$majorcftobj);
		die();
	}
	
	//查询 选修本课程的专业
	$result=$pdo->query("select distinct  major from elective where course='{$subject}' ");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	
	
//	print_r($row);
	//判断 涉及 的专业 是否 在安排表中冲突
	if(count($row)>0){
		
		$majorcft=array();
		foreach ($row as $key => $value) {  
     	foreach ($value as $key => $value) {  
		$repeat=$pdo->query("select * from arrange where major='{$value}' && date='{$date}' && time='{$time}' ");
		$rowrepeat = $repeat->fetchAll(PDO::FETCH_ASSOC);
//			print_r($rowrepeat);
		if(count($rowrepeat)>0){			 
			$majorcft=array_merge($majorcft,$rowrepeat);
		}
		
		}	
	}

	 if(count($majorcft)>0){
	 	$majorcfttip="以下班级在当前日期和考试时间已经存在考试安排，分别为";
	 	foreach ($majorcft as $key => $value){
	 		$majorcfttip.=$value['grade']."级".$value['major'].$value['class_name']."班"."考试科目:".$value['subject'].",";
	 }
	      $majorcftobj= new stdClass();
	      $majorcftobj->txt="考试科目安排冲突";
	      $majorcftobj->tip=$majorcfttip;
	      $majorcftobj->count=count($majorcft);
	 	  returnStatus(100,"err",$majorcftobj);
		return false;
	 }

	
	//判断教室数组是否安排冲突
	foreach ($room as $v){
 	$roomrepeat=$pdo->query("select * from arrange where room like '%{$v}%' && date='{$date}' && time='{$time}' ");
//	$roomrepeat=$pdo->query("select * from arrange where room='{$v}' && date='{$date}' && time='{$time}' ");
	$rowroomrepeat=$roomrepeat->fetchAll(PDO::FETCH_ASSOC);
	if(count($rowroomrepeat)>0){
		  $roomcftobj= new stdClass();
	      $roomcftobj->txt="教室安排冲突";
	      $roomcftobj->tip="在{$date}日时间为{$time},考场{$v}已存在考试安排,";
		  returnStatus(101,"err",$roomcftobj);
		return false;
	}
	}

	
	//判断监考人员是否冲突
	foreach($invigilator as $v){
		$ingtorrepeat=$pdo->query("select * from arrange where invigilator like '%{$v}%' && date='{$date}' && time='{$time}' ");
	$rowingtor=$ingtorrepeat->fetchAll(PDO::FETCH_ASSOC);
	if(count($rowingtor)>0){			 
//		returnStatus(103,"err","监考人员安排冲突");
		 $ingtorcftobj= new stdClass();
	      $ingtorcftobj->txt="监考人员安排冲突";
	      $ingtorcftobj->tip="在{$date}日时间为{$time},监考人员{$v}已存在监考安排,";
		  returnStatus(101,"err",$ingtorcftobj);
		return false;
	}
	}

	
	
	$arr_result=$pdo->query("select * from elective where course='{$subject}' ");
	$arr_row = $arr_result->fetchAll(PDO::FETCH_ASSOC);
	
//	print_r($arr_row);
	
//	考生总人数
	$totalcount=0;
	$diviman=array();
	foreach($arr_row as $v){
	$count_result=$pdo->query("select * from classes where major='{$v['major']}' and grade='{$v['grade']}' and class_name='{$v['class_name']}' ");
	$count_row = $count_result->fetchAll(PDO::FETCH_ASSOC);
	$diviman=array_merge($diviman,$count_row);
//	print_r($count_row);
	if(!empty($count_row)){
		$totalcount+=$count_row[0]['count'];
	}else{
		//未在班级管理中找到相关班级信息
		 $classobj= new stdClass();
	      $classobj->txt="未在班级管理中找到相关班级信息";
	      $classobj->tip="未找到的班级为:{$v['major']}{$v['grade']}级{$v['class_name']}班";
		  returnStatus(101,"err",$classobj);
		  die();
	}
	}
	
	
//	die();
	
//	print_r("考生总人数".$totalcount);
	

	//教室容纳人数 
//	print_r($totalcount);
	$classcount=0;
	$diviroom=array();

	foreach($room as $v){
	 	$vhold=$pdo->query("select * from room where room ='{$v}' ");
		$vholdrepeat=$vhold->fetchAll(PDO::FETCH_ASSOC);
		$diviroom=array_merge($diviroom,$vholdrepeat);
		if(!empty($vholdrepeat)){
			$classcount+=$vholdrepeat[0]['hold'];
		}
	}
	
//	print_r("教室容纳人数".$classcount);
	
	if($classcount<$totalcount){
		  $holdobj= new stdClass();
	      $holdobj->txt="所选考场位置不能满足考试需求";
	      $holdobj->tip="所选考场提供的位置为:{$classcount},考试需安排的人数为:{$totalcount},";
		  returnStatus(101,"err",$holdobj);
		  	die();
	}
	
	//自动分配 教室
//	$diviroom=$vholdrepeat;
//	$diviman=$count_row;
	
//	print_r($diviman);
//	print_r($diviroom);
	$diviintor=$invigilator;
	
	
//	print_r(array_slice($diviintor,0,2));
//	$diviintor=array_slice($diviintor,2);
	
//	print_r($diviintor);
//	print_r($diviintor);
//	foreach($diviroom as $v){
//		if($v['hold']>)
//	}

$k=0;
$allplace=array();
for($i=0;$i<count($diviman);$i++){
	
	if($diviman[$i]['count']<$diviroom[$k]['hold']){
//		$k++;
	$diviman[$i]['place']=$diviroom[$k]['room'];
	$diviroom[$k]['hold']=$diviroom[$k]['hold']-$diviman[$i]['count'];
//	print_r($diviman[$i]);

	}else{
		
		while($diviman[$i]['count']>$diviroom[$k]['hold']){
		if($k>=count($diviroom)-1){
//			print_r($k);
			break;
		}else{
			$k++;
		}
			
			
		if($diviman[$i]['count']<$diviroom[$k]['hold']){
				$diviman[$i]['place']=$diviroom[$k]['room'];
		$diviroom[$k]['hold']=$diviroom[$k]['hold']-$diviman[$i]['count'];
			break;
		}
	
		}
		


		
//		print_r($diviroom[$k]);

	
	}
//		print_r( array_key_exists( "place",$diviman[$i]));
		if(!array_key_exists( "place",$diviman[$i])){
			  $errobj= new stdClass();
	      $errobj->txt="所选考场不能满足考试需求";
	      $errobj->tip="所选考场数量过少,不能满足考试要求";
		  returnStatus(101,"err",$errobj);
		  	die();
		}
		array_push($allplace,$diviman[$i]['place']);
//print_r($diviman[$i]);
		
//	if($diviman[$i]['count']<$diviroom[$k]['hold']){
//		$diviman[$i]['place']=$diviroom[$k]['room'];
//		$diviroom[$k]['hold']=$diviroom[$k]['hold']-$diviman[$i]['count'];
////		print_r($diviroom[$k]);
//		if($diviroom[$k]['hold']<=0){
//			$k++;
//		}
//	
//	}else{
//		
//		$chazhi=$diviman[$i]['count']-$diviroom[$k]['hold'];
//		$diviman[$i]['place']=$diviroom[$k]['room'].",".$diviroom[$k+1]['room'];
//		$diviroom[$k+1]['hold']=$diviroom[$k+1]['hold']-$chazhi;
//		$k++;
//		
//	}
//
//	if($diviman[$i]['count']<30){
//			$diviman[$i]['intor']= array_slice($diviintor,0,2);
//		$diviintor=array_slice($diviintor,2);
//		
//	}else{
//		$peizhi=floor($diviman[$i]['count']/15);
//		$diviman[$i]['intor']=array_slice($diviintor,0,$peizhi);
//		$diviintor=array_slice($diviintor,$peizhi);
//	}
	
	
	
	
//	if(count($diviman[$i]['intor'])<2){
//		
//		 $holdobj= new stdClass();
//	      $holdobj->txt="监考老师人数过少";
//	      $holdobj->tip="所选监考老师人数过少不能满足考场监考任务";
//		  returnStatus(103,"err",$holdobj);
//		  die();
//	}
//	$diviman[$i]['intor']= implode(",",$diviman[$i]['intor']);
	
		
}
$allplace=array_unique($allplace);
	
if(count($diviintor)/2<count($allplace)){
		
		 $holdobj= new stdClass();
	      $holdobj->txt="监考老师人数过少";
	      $holdobj->tip="所选监考老师人数过少不能满足考场监考任务";
		  returnStatus(103,"err",$holdobj);
		  die();
}

$hasintor=array();
foreach(array_unique($allplace) as $v){
$hasintor[$v]= implode(",",array_slice($diviintor,0,2));
$diviintor=array_slice($diviintor,2);
}

for($i=0;$i<count($diviman);$i++){
	$diviman[$i]['intor']=$hasintor[$diviman[$i]['place']];
}
//foreach($diviman as $j){
////foreach($diviman as $v){
//	print_r($j['count']);
//	if($j['count']<$diviroom[$k]['hold']){
//		$j['place']=$diviroom[$k]['room'];
//	
//	}
////	if($v['count']<$diviroom[$j]['hold']){
////			$diviman['room']=$diviroom[$j]['hold'];
////	}
////}
//}
//

//
//print_r($diviman);
//print_r($diviroom);
//print_r($allplace);
//print_r($hasintor);	
//	
//	
//	
//	die();
	
	//无冲突  插入数据
		$tip="";
	foreach ($diviman as $key => $value) { 
			$result=$pdo->exec("insert into arrange (subject,date,time,major,grade,room,invigilator,class_name) 
				values ('{$subject}','{$date}','{$time}','{$value['major']}','{$value['grade']}','{$value['place']}','{$value['intor']}','{$value['class_name']}' )");
			$tip=$tip.$value['grade']."级".$value['major'].$value['class_name']."班".",";
    }
     $successobj= new stdClass();
     $successobj->str=$tip;
     $successobj->count=count($arr_row);
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