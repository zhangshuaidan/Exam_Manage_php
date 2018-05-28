<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
	$data = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	$uname =$data->values->username;
	$oldpwd=$data->values->oldpwd;
	$pwd=md5($data->values->password);
	
	$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");	
	$result=$pdo->query("select * from user where uname='{$uname}'");
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	if(count($row)>0){
	if(md5($oldpwd)===$row[0]['pwd']){
	
	$sql="update user set pwd='{$pwd}'where uname='{$uname}' ";
	$updateresult = $pdo->exec($sql);
//		if($updateresult>0){
		$obj= new stdClass();
		$obj->txt="成功";
		$obj->tip="修改密码成功";
	returnStatus(100,"success",$obj);
//		}

	
		}else{
			
			
//			$res=new Response("err",201);
//			echo json_encode($res);
			
				$obj= new stdClass();
	$obj->txt="失败";
	$obj->tip="修改密码失败，该用户密码输入错误";
//	$obj->count=$result;
	returnStatus(201,"err",$obj);
			
		}
		}else{
			
			
//			$res=new Response("err",202);
//			echo json_encode($res);
	$obj= new stdClass();
	$obj->txt="失败";
	$obj->tip="修改密码失败，未找到该用户";
//	$obj->count=$result;
	returnStatus(202,"err",$obj);
			
		}
	
}
?>