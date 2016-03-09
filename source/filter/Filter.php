<?php 
/**
 * 净化过滤器
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class Filter{
	public static $_instance=null; //过滤器类实例
	/**
	 * 加载器
	*/
	public static function load(){
		if(self::$_instance==null){
			self::$_instance=new Filter();
		}
		return self::$_instance;
		return new Filter(); //TODO: Should Delete ! 只用于帮助ZendStudio提示
	}
	/**
	 * 正则表达式
	 * $subject = "abcdef";
	 * $pattern = '/^def/';
	 * 
	 * @param  $pattern 要搜索的模式，字符串类型。
	 * @param  $subject 输入字符串。
	 */
	public function f_regexp($str,$pattern){
		return preg_replace($pattern,"",$str);
	}
	/**
	 * 转换成整型
	 * 净化过滤器，返回合法的int型数据
	 */
	public function f_int($str){
		return (int)$str;
	}
	public function f_number($str){
		return preg_replace("/[^0-9]/","",$str);
	}
	public function f_float($str){
		return (float)$str;
	}
	/**
	 * 返回非空字符串
	 * 净化过滤器
	 */
	public function f_string($str){
		return preg_replace('/\s/',"",$str);
	}
	/**
	 *  净化过滤器
	 *  只返回字母（大写 小写）和数字和下划线
	 */
	public function f_abc123($str){
		return preg_replace("/[^a-zA-Z0-9_]/","",$str);
	}
	/**
	 * 过滤，除了中文
	 */
	public function f_zh($str){
		return preg_replace("/[^\x{4e00}-\x{9fa5}]/u","",$str);
	}
	/**
	 * 过滤，除了中文,字母，数字
	 * 
	 * @param  $str
	 * @param  $blank 是否包括空格
	 */
	public function f_zhabc123($str,$blank=false){
		if($blank){
			return preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9 ]/ui","",$str);
		}else{
			return preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9]/ui","",$str);
		}
	}
	/**
	 * 过滤掉空白
	 * \s：空白 ; \S：非空白字符
	 */
	public function f_nonBlank($str){
		return preg_replace('/\s/',"",$str);
	}
	/**
	 * strip_tags() 函数剥去 HTML、XML 以及 PHP 的标签。
	 * @param  $str
	 * @param  $allow 可选。规定允许的标签。这些标签不会被删除。
	 */
	public function f_strip_tags($str,$allow=null){
		if(!empty($allow)){
			return strip_tags($str,$allow);
		}else{
			return strip_tags($str);
		}
	}
	/**
	 * 返回安全文本,注意不转义，sql安全转义交给了model
	 * 1.strip_tags		 :剥去 HTML、XML 以及 PHP 的标签。
	 * 2.htmlspecialchars:一些预定义的字符转换为 HTML 实体  ; 单引号和双引号都转义了，go'o"gle=go&#039;o&quot;gle
	 * 3.addslashes      : 函数在指定的预定义字符前添加反斜杠。
	 * ----------------------------
	 * TODO:重要
	 * 1.自我构建sql的时候,如果不进行转义，则需要使用Model::filterString()，否则有sql注入。
	 * 2.linkup的某些方法，会有双重转义但还是安全的。
	 * 
	 * @param string $text		
	 * @param string $escape 是否转义
	 * @return string
	 */
	public function f_safeText($text,$escape=true){
		$text=$this->f_htmlspecialchars($this->f_strip_tags($text));
		if($escape){
			return $this->f_escape($text);
		}else{
			return $text;
		}
	}
	public function f_safeText_array($text){
		if(is_array($text)){
			return array_map(array($this,__FUNCTION__),$text); //__FUNCTION__==f_addslashes_array
		}
		return $this->f_safeText($text);
	}
	/**
	 * 返回安全HTML
	 * 1.htmlspecialchars:一些预定义的字符转换为 HTML 实体
	 * 2.addslashes      : 函数在指定的预定义字符前添加反斜杠。
	 * @param string $html
	 * @param string $escape
	 */
	public function f_safeHtml($html,$escape=true){
		$html=$this->f_htmlspecialchars($html);
		if($escape){
			return $this->f_escape($html);
		}else{
			return $html;
		}
	}
	/**
	 * 过滤文字，只剩下数字,字母,中文,或者空格
	 * f_safeWord()/f_safeChar()/f_safeString()
	 * 
	 * @param  $str
	 * @param  $blank 是否保留空格
	 */
	public function f_safeWord($str,$blank=true){
		if($blank){
			return preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9 ]/ui","",$str); //保留空格
		}else{
			return preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9]/ui","",$str);  //去除空格
		}
	}
	/**
	 * 转义函数
	 * @param $str
	 */
	public function f_escape($str){
		return $this->f_addslashes($str);
	}
	/**
	 * SQL组装字符串，安全过滤
	 * 1.特殊符号转义， ' " \ 
	 * 2.like通配符转义  _ %
	 * 3.避免双重转义
	 * 
	 * 注意：只有自行组建sql,未使用到model的自行转义才是使用该方法
	 * @param  $str
	 * @param  $like like '%_%', like '%_%%',是否是like查询
	 */
	public function f_safeSqlString($str,$like=false){
		$str=$this->f_addslashes($str);  //转义 ' " \ ;\' \" \\
		if($like){
// 			$str=str_replace('_','\_', $str); // 把 '_'转义，like：单个字符通配符
// 			$str=str_replace('%','\%', $str); // 把 '%'转义，like：多个字符通配符
			$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		}
		return $str;
	}
	//f_safeSql/f_safeSqlVar
	public function f_safeSql($str,$like=false){
		return $this->f_safeSqlString($str,$like);
	}
	/**
	 * 过滤like/和转义特殊符号
	 * @param  $str
	 * @param  $like
	 */
	public function f_safeSqlLike($str,$like=true){
		return $this->f_safeSqlString($str,$like);
	}
	/**
	 * 仅过滤like,不转义
	 * @param $str
	 */
	public function f_safeLikeSql($str){
		$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		return $str;
	}
	/**
	 * 把一些预定义的字符转换为 HTML 实体。
	 * -----------------------
	 * 预定义的字符是：
	 * & （和号） 成为 &amp;
	 * " （双引号） 成为 &quot;
	 * ' （单引号） 成为 &#039;
	 * < （小于） 成为 &lt;
	 * > （大于） 成为 &gt;
	 * ----------------------
	 * ENT_QUOTES - 编码双引号和单引号。
	 *
	 * @param  $string
	 * @param  $quotestyle
	 * @param  $character_set
	 */
	public function f_htmlspecialchars($string,$quotestyle=ENT_QUOTES,$character_set='UTF-8'){
		return htmlspecialchars($string,$quotestyle,$character_set);
	}
	/**
	 * 把字符转换为 HTML实体，比htmlspecialchars转换强度大。
	 * 
	 * @param string $string
	 * @param string $quotestyle
	 * @param string $character_set
	 * @return string
	 */
	public function f_htmlentities($string,$quotestyle=ENT_QUOTES,$character_set='UTF-8'){
		return htmlentities($string,$quotestyle,$character_set);
	}
	/**
	 * 删除由 addslashes()函数给[预定义]字符添加的反斜杠。
	 * @param  $str
	 */
	public function f_stripslashes($str){
		return stripslashes($str);
	}
	/**
	 * 删除由 addcslashes()函数给[指定Custom]字符添加的反斜杠。
	 * @param  $str
	 */
	public function f_stripcslashes($str){
		return stripcslashes($str);
	}
	/**
	 * 在[预定义]的字符前添加反斜杠。
	 * 这些预定义字符是：单引号 (')，双引号 (")，反斜杠 (\)，NULL
	 * --------------------
	 * $str=' \' " \ NULL ';  	//单引号实际没有转义，只是为了字符连接
	 * dump(" ' ".addslashes($str)." ' ");
	 * result： '  \' \" \\ NULL  '
	 * --------------------
	 * 默认情况下，PHP 指令 magic_quotes_gpc 为 on，
	 * 对所有的 GET、POST 和 COOKIE 数据自动运行 addslashes()。
	 * 不要对已经被 magic_quotes_gpc 转义过的字符串使用 addslashes()，因为这样会导致双层转义。【 \\' \\"】单引号又可以用了。
	 * 遇到这种情况时可以使用函数 get_magic_quotes_gpc() 进行检测。
	 *
	 * @param $str
	 * @return string
	 */
	public function f_addslashes($str){
		return addslashes($str);
	}
	/**
	 * 在[指定]的字符前添加反斜杠。
	 * -------------------
	 * 在对 0，r，n 和 t 应用 addcslashes() 时要小心。
	 * 在 PHP 中，\0，\r，\n 和 \t 是预定义的转义序列。
	 *
	 * @param  $str
	 * @param  $characters  "
	 */
	public function f_addcslashes($str,$charlist='\'"'){
		return addcslashes($str,$charlist);
	}
	/**
	 * magic_quotes_gpc 为 on，对所有的 GET、POST 和 COOKIE 数据自动运行 addslashes()。
	 * 不要对已经被 magic_quotes_gpc 转义过的字符串使用 addslashes()，因为这样会导致双层转义。
	 * 遇到这种情况时可以使用函数 get_magic_quotes_gpc() 进行检测。
	 * PHP5.4.0	始终返回 FALSE，因为这个魔术引号功能已经从 PHP 中移除了。
	 * 
	 * @param $str  用户提交的数据$_REQUEST
	 */
	public function f_magic_quotes_gpc($str){
		if(!get_magic_quotes_gpc()){
			return addslashes($str);
		}else{
			return $str;
		}
	}
}
 
