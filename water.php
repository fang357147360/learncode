<?php
/**
 * 水印
 */
/**
*功能：生成水印
*@param1 string dest目标图片
*@param2 string source原图图片
*@param3 string pos水印的位置
*@param4 string path保存水印图片的位置
*@param5 int op透明度
*@return string返回最后水印图的文件名
*/
function water($dest,$source,$path,$pos="rm",$op=50){
	//定义图片文件的类型
	$arr = array(
		'image/jpeg'=>'imagecreatefromjpeg',
		'image/jpg'=>'imagecreatefromjpeg',
		'image/pjpeg'=>'imagecreatefromjpeg',
		'image/png'=>'imagecreatefrompng',
		'image/gif'=>'imagecreatefromgif'
		);

	//获取用户图片的图片类型
	$destInfo = getimagesize($dest);
	$type = $destInfo['mime'];

	//根据图片的类型找到创建图片的函数
	if(array_key_exists($type,$arr)){
		$createFn = $arr[$type];
	}else{
		echo '文件类型不合法';
	}

	//调用函数创建画布
	$imgDest = $createFn($dest);

	/*第2步:将水印图读入一画布*/

	$sourceInfo = getimagesize($source);
	$type = $sourceInfo['mime'];
	if (array_key_exists($type,$arr)) {
		$createFn = $arr[$type];
	}else{
		echo '文件类型不合法';
	}
	$imgSource = $createFn($source);

	//根据$position确定水印的位置
	switch($pos){
		case 'lt':
			$posX = 0;
			$posY = 0;
			break;
		case 'rt':
			$posX = $destInfo[0] - $sourceInfo[0];
			$posY = 0;
			break;
		case 'cc':
			$posX = ($destInfo[0] - $sourceInfo[0])/2;
			$posY = ($destInfo[1] - $sourceInfo[1])/2;
			break;
		case 'rm':
			$posX = $destInfo[0] - $sourceInfo[0];
			$posY = $destInfo[1] - $sourceInfo[1];
			break;
		case 'lm':
			$posX = 0;
			$posY = $destInfo[1] - $sourceInfo[1];
			break;
	}

	/*第3步：合并*/
	imagecopymerge($imgDest,$imgSource,$posX,$posY,0,0,$sourceInfo[0],$sourceInfo[1],$op);
	//header('content-type:image/jpeg');
	$fileName = 'water_'.$dest;
	$fullName = $path.'/'.$fileName;
	$return = imagejpeg($imgDest,$fullName);
	if ($return) {
		return $fileName;
	}else{
		return false;
	}
}
$dest = "dest.jpg";
$source = "source.png";
$position = 'lt';	//lt左上角,cc同中间,rt右上角,rm是右下角,lm左角
$path = "./water";


water($dest,$source,$path);
