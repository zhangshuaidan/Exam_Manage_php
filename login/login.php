<?php
include_once "../lib/fun.php";
if(($_SERVER['REQUEST_METHOD']) =='OPTIONS'){
	return false;
}

if(!empty(json_decode($GLOBALS['HTTP_RAW_POST_DATA']))){
		$data = $GLOBALS['HTTP_RAW_POST_DATA'];
		$arr = json_decode($data); 
		$username=$arr->uname;
		$password =$arr->pwd;
		$pdo=mysqlInit("mysql", "localhost", "myexam", "root", "");
		
		$result=$pdo->query("select * from user where uname='{$username}'");
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
		if(count($row)>0){
		if(md5($password)===$row[0]['pwd']){
			
			$res=new Response("success",100);
			echo json_encode($res);
			
			
		}else{
			
			
			$res=new Response("err",201);
			echo json_encode($res);
			
			
		}
		}else{
			
			
			$res=new Response("err",202);
			echo json_encode($res);
			
			
		}
		

}

?>