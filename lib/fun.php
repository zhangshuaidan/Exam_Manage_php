<?php
	
	/*
	 *   允许跨域
	 * 
	 * */
	
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');



class Response{
	function __construct($a,$b){
		$this->status=$a;
		$this->code=$b;
	}
}
function returnStatus($status, $msg = null, $data = null) {
		echo json_encode(array("status" => $status, "msg" => $msg, "data" => $data));
	}
	
	
/*
 * 数据库连接初始化函数
 */
function mysqlInit($dbms,$host,$dbname,$username,$password){
$dsn = "{$dbms}:host={$host};dbname={$dbname}";
$pdo = new PDO($dsn,$username,$password);
$pdo->query("set names utf8");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
return $pdo;
}



//跳转页面 
function msg($type,$msg=null,$url=null){
	$tomsg = "location:msg.php?type={$type}";
	$tomsg.= $msg?"&msg={$msg}":"";
	$tomsg.= $url?"&url={$url}":"";
	header($tomsg);
}

//判断登录
function checkLogin(){
	session_start();
	if(isset($_SESSION['user'])){
		return true;
	}else{
		return false;
	}
}


//获取上传图片并返回图片的路径
function imgUpload($file,$userId){

	//判断是否通过http 请求上传的文件
	if(!is_uploaded_file($file['tmp_name'])){
		msg(2,"请通过合法路径上传文件");
	}
	//判断上传文件的类型
	if(!in_array($file["type"], array("image/png","image/gif","image/jpeg"))){
		msg(2,"请上传png/gif/jpeg 格式的图片");
	}
	$filePath = "file/";
	$imgPath = "{$userId}/".date("Y/md/",time());
	$ext = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));//获取文件后缀名
	$img = uniqid().rand(1000, 9999); // 存储的图片名称
	//判断指定的路径是否存在，若不存在则创建文件夹
	if(!is_dir($filePath.$imgPath)){
		mkdir($filePath.$imgPath,0777,true);
	}
	$uploadPath=$filePath.$imgPath.$img.".".$ext;
	$imgUrl = "http://localhost/ArtGallery/".$uploadPath;
	//将文件从临时目录移动到指定目录，若成功提示操作成功并跳转到user.php 若失败提示操作失败
	if(move_uploaded_file($file["tmp_name"], $uploadPath)){
		return $imgUrl;
	}else{
		msg(2,"操作失败，请重试");
	}
}

//获取页面url 链接
function getUrl($page){
	$url=$_SERVER["REQUEST_SCHEME"]."://"; // http://
	$url.=$_SERVER["HTTP_HOST"]; //  http://localhost
	$url.=$_SERVER["SCRIPT_NAME"];// http://localhost/ArtGallery/user.php
	$queryString = $_SERVER["QUERY_STRING"]; //page=3&id=1&name=abc
	parse_str($queryString,$queryArr);
//	print_r($queryArr);
	if(isset($queryArr["page"])){
		unset($queryArr["page"]);
	}	
	$queryArr["page"]=$page;
	$queryString=http_build_query($queryArr);// 将数组转换为请求字符串 name=abc&& id=1&pafe=12
	
//	echo $queryString;
//	die;
	$url.="?".$queryString;
	return $url;
	
}

//创建页码函数,用来输出一个放有页码标签的字符串
function pages($totalPage,$page,$show = 5){
	$pageStr = "";
	if($totalPage>1){
		//设置页码起始点
		$from = max(1,$page - intval($show/2));
		//设置页码的结束点
		$to = $from + $show-1;
		if($to>$totalPage){
			$to = $totalPage;
			$from =max(1,$to - $show+1);
		}
		
		$pageStr.="<div class='page-nav'>";
		$pageStr.="<ul>";
		
		if($page >1){
			$pageStr.="<li><a href='".getUrl(1)."'>首页</a></li>";
			$pageStr.="<li><a href='".getUrl($page-1)."'>上一页</a></li>";
		}
		
		if($from >1){
			$pageStr.="<li>...</li>";
		}
		
		for($i=$from;$i<=$to;$i++){
			if($i==$page){
			$pageStr.="<li><span class='curr-page'>{$i}</span></li>";
			}else{
				$pageStr.="<li><a href='".getUrl($i)."'>{$i}</a></li>";
			}
		}
		
		if($to<$totalPage){
			$pageStr.="<li>...</li>";
		}
		
		if($page<$totalPage){
			$pageStr.="<li><a href='".getUrl($page+1)."'>下一页</a></li>";
			$pageStr.="<li><a href='".getUrl($totalPage)."'>尾页</a></li>";
		}
		$pageStr.="</ul>";
		$pageStr.="</div>";
	}
	return $pageStr;
}

//导入excel

function importExecl($file='', $sheet=0){  
    $file = iconv("utf-8", "gb2312", $file);   //转码  
    if(empty($file) OR !file_exists($file)) {  
        die('file not exists!');  
    }  
//  include('PHPExcel.php');  //引入PHP EXCEL类  
   include('Classes/PHPExcel.php');  
    $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象  
    if(!$objRead->canRead($file)){  
        $objRead = new PHPExcel_Reader_Excel5();  
        if(!$objRead->canRead($file)){  
            die('No Excel!');  
        }  
    }  
  
    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
  
    $obj = $objRead->load($file);  //建立excel对象  
    $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表  
    $columnH = $currSheet->getHighestColumn();   //取得最大的列号  
    $columnCnt = array_search($columnH, $cellName);  
    $rowCnt = $currSheet->getHighestRow();   //获取总行数  
  
    $data = array();  
    for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容  
        for($_column=0; $_column<=$columnCnt; $_column++){  
            $cellId = $cellName[$_column].$_row;  
            $cellValue = $currSheet->getCell($cellId)->getValue();  
         
             //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值  
            if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串  
                $cellValue = $cellValue->__toString();  
            }  
  
            $data[$_row][$cellName[$_column]] = $cellValue;  
        }  
    }  
  
    return $data;  
} 





?>