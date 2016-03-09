<?php
/**
 * 文件上传
 * @author Administrator
 *
 */
class iUploadFile { 
    // 上传文件的最大值/留空不做要求
    public $maxSize ='';
    // 允许上传的文件后缀
    //  留空不作后缀检查
    public $allowExts = array();
    // 允许上传的文件类型
    // 留空不做检查
    public $allowTypes = array();
    // 上传文件保存路径
    public $savePath = '';
    //保存目录规则
    public $savePathRule = '';
    //文件名保存规则
    public $saveRule = '';
    // 存在同名是否覆盖
    public $uploadReplace = false;
    // 错误信息
    private $error = '';
    // 上传成功的文件信息
    private $FileInfo ;

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct() {
    	
    }
    /**
     +----------------------------------------------------------
     * 上传所有文件
     * $file的所有参数
     * $_FILES
     *    ["name"] => string(5) "1.jpg"
          ["type"] => string(10) "image/jpeg"
         -["tmp_name"] => string(29) "D:\_Inp\wamp5\tmp\php3728.tmp"
         -["error"] => int(0)
          ["size"] => int(45834)
     * unset:tmp_name,error
     * +
     * key    --上传的表单域name
     * extension--后缀
     * savepath--保存路径，要把上传的文件移到的地方
     * savename--保存名称
     +----------------------------------------------------------
     *2012年8月28日 17:11:46
     *只能上传一个文件表单，且要传入name
     *<input type='file' name='pic'>
     *$name='pic'
     */
    public function upload($name) {
    	if($name==''){
    		$this->error  =  '需要传入要上传file表单的name';
    		return false;
    	}
    	//保存路径检测
    	$savePath = $this->savePath.'/'.$this->savePathRule.'/'; // data/uploads 201211
    	// 检查上传目录
    	if(!is_dir($savePath)) {
    		// 检查目录是否编码后的
    		if(is_dir(base64_decode($savePath))) {
    			$savePath	=	base64_decode($savePath);
    		}else{
    			// 尝试创建目录
    			if(!mkdir($savePath)){
    				$this->error  =  '上传目录'.$savePath.'不存在';
    				return false;
    			}
    		}
    	}else {
    		if(!is_writeable($savePath)) {
    			$this->error  =  '上传目录'.$savePath.'不可写';
    			return false;
    		}
    	}
    	
    	$fileInfo = array();
    	$isUpload   = false;    	 
    	$file=$_FILES[$name];//$_FILES['pic']
    	//过滤无效的上传
    	if(!empty($file['name'])) {
    		//登记上传文件的扩展信息
    		$file['key']        =$name;
    		$file['extension']  = $this->getExt($file['name']);
    		$file['savepath']   = $savePath;
    		$file['savename']   = $this->getSaveName($file);
    		//保存上传文件
    		if(!$this->save($file)) return false;
    		
    		//上传成功后保存文件信息，供其他地方调用
    		unset($file['tmp_name'],$file['error']);
    		$file['savepath']=$this->savePathRule;//只保存相对于上传文件夹下的路径
    		$fileInfo= $file;
    		$isUpload= true;
    	}
    
    	if($isUpload) {
    		$this->FileInfo = $fileInfo;
    		return true;
    	}else {
    		$this->error  =  '没有选择上传文件';
    		return false;
    	}
    }
    /**
     * 上传一个文件
     */
    private function save($file) {
    	//进行上传前的检测
    	if(!$this->_check($file)) return false;
        $filename = $file['savepath'].$file['savename'];
        // 不覆盖同名文件
        if(!$this->uploadReplace && is_file($filename)) {           
            $this->error='文件已经存在！'.$filename;
            return false;
        }
        // 如果是图像文件 检测文件格式
        if( in_array(strtolower($file['extension']),array('gif','jpg','jpeg','bmp','png','swf')) && false === getimagesize($file['tmp_name'])) {
            $this->error = '非法图像文件';
            return false;
        }
        //move_uploaded_file,把上传文件移动到新位置
        if(!move_uploaded_file($file['tmp_name'], $this->autoCharset($filename,'utf-8','gbk'))) {
            $this->error = '文件上传保存错误！';
            return false;
        }
//         //保存后进行缩放处理  
//         $thumbname=$file['savepath'].'thumb_'.$file['savename'];
//         Image::thumb($filename,$thumbname,'',100,100,true);
        
        return true;
    }
    /**
     * 文件上传前进行检测，可选检测项
 ["name"]=>
  string(26) "7a936ef0gw1do5zo79omuj.jpg"
  ["type"]=>
  string(10) "image/jpeg"
  ["tmp_name"]=>
  string(29) "D:\_Inp\wamp5\tmp\php8937.tmp"
  ["error"]=>
  int(0)
  ["size"]=>
  int(35939)
  ["key"]=>
  string(3) "pic"
  ["extension"]=>
  string(3) "jpg"
  ["savepath"]=>
  string(27) "Public/uploads/201208_test/"
  ["savename"]=>
  string(14) "1346144702.jpg"
     */
    private function _check($file){   
    	$ext=$file['extension'];
    	$size=$file['size'];
    	$tmp_name=$file['tmp_name'];
    	$type=$file['type'];
    	//允许的后缀
    	if( !(!empty($this->allowExts) && in_array(strtolower($ext),$this->allowExts,true) ) ){
    		$this->error='后缀不允许';
    		return false;
    	}
    	//大小是否合法
    	if( ($size > $this->maxSize) && ($this->maxSize>'') ){
    		$this->error='文件大小大于最大允许上传'.($this->maxSize/1024/1024).'M';
    		return false;
    	}
    	//检查文件是否非法提交
    	if(!is_uploaded_file($tmp_name)){
    		$this->error='非法提交';
    		return false;
    	}
    	//检测文件类型
    	if(!empty($this->allowTypes) && in_array(strtolower($type),$this->allowTypes) ){
    		$this->error='文件类型不允许';
    		return false;
    	}
	    return true; 	
    }
    // 自动转换字符集 支持数组转换
    private function autoCharset($fContents, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
            //如果编码相同或者非字符串标量则不转换
            return $fContents;
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    }

    /**
     +----------------------------------------------------------
     * 取得上传文件的后缀
     * pathinfo()
     *   ["dirname"] => string(17) "D:\_Inp\wamp5\tmp"
     *   ["basename"] => string(11) "php3728.tmp"
     *   ["extension"] => string(3) "tmp"
     *   ["filename"] => string(7) "php3728"
     +----------------------------------------------------------
     */
    private function getExt($filename) {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }
    /**
     +----------------------------------------------------------
     * 根据上传文件命名规则取得保存文件名
     +----------------------------------------------------------
     */
    private function getSaveName($filename) {
    	$rule = $this->saveRule;
    	if(empty($rule)) {//没有定义命名规则，则保持文件名不变
    		$saveName = $filename['name'];
    	}else {
    		if(function_exists($rule)) {
    			//使用函数生成一个唯一文件标识号
    			$saveName = $rule().".".$filename['extension'];
    		}else {
    			//使用给定的文件名作为标识号
    			$saveName = $rule.".".$filename['extension'];
    		}
    	}
    	return $saveName;
    }

    /**
      * 取得上传文件的信息
     */
    public function getFileInfo() {
        return $this->FileInfo;
    }

    /**
      * 取得错误信息
     */
    public function getErrorMsg() {
        return $this->error;
    }

}