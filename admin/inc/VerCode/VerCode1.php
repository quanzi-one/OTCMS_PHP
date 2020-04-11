<?php 
error_reporting(E_ALL ^ E_NOTICE);

/*
* 这个类生成验证码图象,同时可以对用户输入的验证码进行验证
* @modify frozen
*/


class VerCode{
	/*
	*构造函数，初始化变量
	*/
	public function __construct(){
		$this->setCode();
		$this->setMolestation();
		$this->setImage();
		$this->setFont();
		$this->setBgColor();
	}


	/*
	* 获取产生有验证码VerCode
	*/
	public function getCode(){
		return $this->VerCode;
	}


	/*
	* 绘制图象
	* @param string 文件名，留空表示输出到浏览器
	* @return void
	*/
	public function paint($filename=''){
		/*创新图象*/
		$im = imagecreatetruecolor($this->image['width'],
									$this->image['height']
									);
		/*设置图象背景*/
		$bg_Color = imagecolorallocate($im,$this->bg_color['r'],
											$this->bg_color['g'],
											$this->bg_color['b']
										);
		imagefilledrectangle($im,0,0,$this->image['width'],$this->image['height'],$bg_Color);
		/*生成验证码相关信息*/
		$code = $this->generateCode();
		/*向图象中写入字符*/
		$num = count($code);
		$current_left = $this->font['left'];
		$current_top   = $this->font['top'];
		for($i=0;$i<$num;$i++){
			$font_color=imagecolorallocate($im,$code[$i]['color']['r'],$code[$i]['color']['g'],$code[$i]['color']['b']);
			@imagettftext($im,$this->font['size'],$code[$i]['angle'],$current_left,$current_top,$font_color,$this->font['file'],$code[$i]['char']);
			$current_left+= $this->font['size']+$this->font['space'];
			$the_code.=$code[$i]['char'];
		}
		/*保存VerCode*/
		session_start();
		$_SESSION["VerCode1"]=trim($the_code);
		$this->VerCode = $the_code;
		/*绘制图象干扰*/
		$this->paintMolestation($im);
		/*输出*/
		if(isset($filename) && $filename!=''){
			$this->image['func']($im,$filename,$this->image['type']);
		}else{
			header("Cache-Control: no-cache, must-revalidate");
			header("Content-type:",$this->image['mime']);
			$this->image['func']($im);
		}
		imagedestroy($im);
	}


	/*
	* 生成随机验证码
	* @return array    生成的验证码
	*/
	private function generateCode(){
		/* 创建允许的字符串*/
		$characters = explode(',', $this->code['characters']);
		$num = count($characters);
		for ($i=0; $i<$num; $i++){
			if (substr_count($characters[$i], '-') > 0){
				$character_range = explode('-', $characters[$i]);
				for ($j=ord($character_range[0]); $j<=ord($character_range[1]);$j++){
					$array_allow[] = chr($j);
				}
			}else{
				$array_allow[] = $array_allow[$i];
			}
		}
		$index = 0;
		while (list($key, $val) = $this->NewEach($array_allow)){
			$array_allow_tmp[$index] = $val;
			$index ++;
		}
		$array_allow = $array_allow_tmp;

		/**生成随机字符串*/
		mt_srand((double)microtime() * 1000000);
		$code = array();
		$index = 0;
		$i = 0;
		while ($i < $this->code['length']){
			$index = mt_rand(0, count($array_allow) - 1);
			$code[$i]['char'] = $array_allow[$index];
			if ($this->code['deflect']){
				$code[$i]['angle'] = mt_rand(-30, 30);
			}else{
				$code[$i]['angle'] = 0;
			}
			if ($this->code['multicolor']){
				$code[$i]['color']['r'] = mt_rand(0, 255);
				$code[$i]['color']['g'] = mt_rand(0, 255);
				$code[$i]['color']['b'] = mt_rand(0, 255);
			}else{
				$code[$i]['color']['r'] = $this->fg_color['r'];
				$code[$i]['color']['g'] = $this->fg_color['g'];
				$code[$i]['color']['b'] = $this->fg_color['b'];
			}
			$i++;
		}
		return $code;
	}


	/**
	* 获取图像类型
	* @param string   扩展名
	* @return [mixed] 错误时返回 false
	*/
	private function getImageType($extension){
		switch (strtolower($extension)){
			case 'png':
				$information['mime'] = image_type_to_mime_type(IMAGETYPE_PNG);
				$information['func'] = 'imagepng';
				break;
			case 'gif':
				$information['mime'] = image_type_to_mime_type(IMAGETYPE_GIF);
				$information['func'] = 'imagegif';
				break;
			case 'wbmp':
				$information['mime'] = image_type_to_mime_type(IMAGETYPE_WBMP);
				$information['func'] = 'imagewbmp';
				break;
			case 'jpg':
				$information['mime'] = image_type_to_mime_type(IMAGETYPE_JPEG);
				$information['func'] = 'imagejpeg';
				break;
			case 'jpeg':
				$information['mime'] = image_type_to_mime_type(IMAGETYPE_JPEG);
				$information['func'] = 'imagejpeg';
				break;
			case 'jpe':
				$information['mime'] = image_type_to_mime_type(IMAGETYPE_JPEG);
				$information['func'] = 'imagejpeg';
				break;
			default:
				$information = false;
		}
		return $information;
	}


	/**
	* 绘制图像干扰
	* @param resource 图像资源
	* @return void
	*/
	private function paintMolestation(&$im){
		// 总象素
		$num_of_pels = ceil($this->image['width']*$this->image['height']/5);
		switch ($this->molestation['density']){
			case 'fewness':
				$density = ceil($num_of_pels / 3);
				break;
			case 'muchness':
				$density = ceil($num_of_pels / 3 * 2);
				break;
			case 'normal':
				$density = ceil($num_of_pels / 2);
			default:
		}

		switch ($this->molestation['type']){
			case 'point':
				$this->paintPoints($im, $density);
				break;
			case 'line':
				$density = ceil($density / 30);
				$this->paintLines($im, $density);
				break;
			case 'both':
				$density = ceil($density / 2);
				$this->paintPoints($im, $density);
				$density = ceil($density / 30);
				$this->paintLines($im, $density);
				break;
			default:
				break;
		}
	}


	/**
	* 画点
	* @param resource 图像资源
	* @param int       图像资源
	* @return void
	*/
	private function paintPoints(&$im, $quantity){
		mt_srand((double)microtime()*1000000);

		for ($i=0; $i<$quantity; $i++){
			$randcolor = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			imagesetpixel($im, mt_rand(0, $this->image['width']), mt_rand(0, $this->image['height']), $randcolor);
		}
	}


	/**
	* 画线
	* @param resource 图像资源
	* @param int       图像资源
	* @return void
	*/
	private function paintLines(&$im, $quantity){
		mt_srand((double)microtime()*1000000);

		for ($i=0; $i<$quantity; $i++){
			$randcolor = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			imageline($im, mt_rand(0, $this->image['width']), 
							mt_rand(0, $this->image['height']), 
							mt_rand(0, $this->image['width']), 
							mt_rand(0, $this->image['height']), $randcolor);
		}
	}


	/**
	* 设置前景色
	* @param array    RGB 颜色
	* @return void
	*/
	private function setFgColor($color){
		if (is_array($color) && is_integer($color['r']) && 
			is_integer($color['g']) && is_integer($color['b']) && 
			($color['r'] >= 0 && $color['r'] <= 255) && 
			($color['g'] >= 0 && $color['g'] <= 255) && 
			($color['b'] >= 0 && $color['b'] <= 255))
		{
			$this->fg_color = $color;
		}else{
			$this->fg_color = array('r'=>0,'g'=>0,'b'=>0);
		}
	}


	/**
	* 设置验证码
	* @param array    字符信息
	* characters string   允许的字符
	* length int      验证码长度
	* deflect boolean 字符是否偏转
	* multicolor boolean 字符是否彩色
	* @return void
	*/
	public function setCode($code=''){
		if (is_array($code)){
			if (!isset($code['characters']) || !is_string($code['characters'])){
				$code['characters'] = '0-9';
			}
			if (!(is_integer($code['length']) || $code['length']<=0)){
				$code['length'] = 4;
			}
			if (!is_bool($code['deflect'])){
				$code['deflect'] = true;
			}
			if (!is_bool($code['multicolor'])){
				$code['multicolor'] = true;
			}
		}else{
			$code = array('characters'=>'0-9', 'length'=>4, 'deflect'=>true, 'multicolor'=>false);
		}
		$this->code = $code;
	}


	/**
	* 设置背景色
	* @param array    RGB 颜色
	* @return void
	*/
	public function setBgColor($color=''){
		if (is_array($color) && is_integer($color['r']) && 
			is_integer($color['g']) && is_integer($color['b']) && 
			($color['r'] >= 0 && $color['r'] <= 255) && 
			($color['g'] >= 0 && $color['g'] <= 255) && 
			($color['b'] >= 0 && $color['b'] <= 255))
		{
			$this->bg_color = $color;
		}else{
			$this->bg_color = array('r'=>255,'g'=>255,'b'=>255);
		}

		// 设置默认的前景色, 与背景色相反
		$fg_color = array(
			'r'=>255-$this->bg_color['r'], 
			'g'=>255-$this->bg_color['g'], 
			'b'=>255-$this->bg_color['b']
		);
		$this->setFgColor($fg_color);
	}


	/**
	* 设置干扰信息
	* @param array    干扰信息
	* type string   干扰类型 (选项: false, 'point', 'line')
	* density string   干扰密度 (选项: 'normal', 'muchness', 'fewness')
	* @return void
	*/
	public function setMolestation($molestation=''){
		if (is_array($molestation)){
			if (!isset($molestation['type']) || 
				($molestation['type']!='point' && 
				$molestation['type']!='line' && 
				$molestation['type']!='both'))
			{
				$molestation['type'] = 'point';
			}
			if (!is_string($molestation['density'])){
				$molestation['density'] = 'normal';
			}
			$this->molestation = $molestation;
		}else{
			$this->molestation = array(
				'type' => 'point',
				'density' => 'normal'
			);
		}
	}


	/**
	* 设置字体信息
	* @param array	字体信息
	* space int		字符间隔 (px)
	* size int		字体大小 (px)
	* left int		第一个字符距离图像最左边的象素 (px)
	* top int		字符距离图像最上边的象素 (px)
	* file string	字体文件的路径
	* @return void
	*/
	public function setFont($font=''){
		if (is_array($font)){
			if (!is_integer($font['space']) || $font['space']<0){
				$font['space'] = 3;
			}
			if (!is_integer($font['size']) || $font['size']<0){
				$font['size'] = 13;
			}
			if (!is_integer($font['left']) || $font['left']<0 || 
				$font['left']>$this->image['width']) 
			{
				$font['left'] = 5;
			}
			if (!is_integer($font['top']) || $font['top']<0 || 
				$font['top']>$this->image['height']) 
			{
				$font['top'] = $this->image['height'] - 5;
			}
			if (!file_exists($font['file'])){
				$font['file'] = dirname(__FILE__).'/VerCode.ttf';
			}
			$this->font = $font;
		}else{
			$this->font = array('space'=>5, 'size'=>13, 'left'=>5, 'top'=>15, 'file'=>dirname(__FILE__).'/VerCode.ttf');
		}
	}


	/**
	* 设置图像信息
	* @param array	图像信息
	* type string	图像类型 (选项: 'png', 'gif', 'wbmp', 'jpg')
	* width int		图像宽 (px)
	* height int	图像高 (px)
	* @return void
	*/
	public function setImage($image=''){
		if (is_array($image)){
			if (!is_integer($image['width']) || $image['width'] <= 0){
				$image['width'] = 70;
			}
			if (!is_integer($image['height']) || $image['height'] <= 0){
				$image['height'] = 20;
			}
			$this->image = $image;
			$information = $this->getImageType($image['type']);
			if (is_array($information)){
				$this->image['mime'] = $information['mime'];
				$this->image['func'] = $information['func'];
			}else{
				$this->image['type'] = 'png';
				$information = $this->getImageType('png');
				$this->image['mime'] = $information['mime'];
				$this->image['func'] = $information['func'];
			}
		}else{
			$information = $this->getImageType('png');
			$this->image = array(
				'type'=>'png', 
				'mime'=>$information['mime'], 
				'func'=>$information['func'], 
				'width'=>70, 
				'height'=>20);
		}
	}
	
	
	public function NewEach(&$array){
		$res = array();
		$key = key($array);
		if($key !== null){
			next($array); 
			$res[1] = $res['value'] = $array[$key];
			$res[0] = $res['key'] = $key;
		}else{
			$res = false;
		}
		return $res;
	}

}

$auth_code = new VerCode();
$auth_code->setImage(array('width'=>73,'height'=>23));
// 输出到浏览器
//echo "<img src='". $auth_code->paint()."'/>";
$auth_code->paint();
?>