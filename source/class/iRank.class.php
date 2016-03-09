<?php
/**
 * 等级转换
 * 
 */
class iRank {
	// 获得一个星星需要的积分
	public $star1 = 10;
	// 获得一个月亮需要的积分
	public $star2 = 50; // 20+3*10=50//
	                  // 获得一个太阳需要的积分
	public $star3 = 230; // 50+3*50+3*10=230
	                   // 积分数
	public $score = null;
	// 处理结果
	public $res = null;
	/**
	 * 构架函数
	 */
	public function __construct() {
	
	}
	/**
	 * 获得等级数
	 */
	public function rankNum() {
		$rank = $this->res;
	
	}
	/**
	 * 积分向等级转换
	 * //调用该函数
	 */
	function rank($score) {
		if ($score != '')
			$this->score = $score;
			// 太阳
		$sun = $this->sun ( $this->score );
		$return ['star3'] = intval ( $sun ['star'] );
		// 月亮
		$moon = $this->moon ( $sun ['score'] );
		$return ['star2'] = intval ( $moon ['star'] );
		// 星星
		$star = $this->star ( $moon ['score'] );
		$return ['star1'] = intval ( $star ['star'] );
		$this->res = $return;
		
		return $return;
	}
	/**
	 * 验证等级3，返回太阳数
	 */
	public function sun($score) {
		$s3 = $this->star3;
		
		$mod1 = $score / $s3;
		if ($mod1 >= 1) {
			$sun = floor ( $mod1 ); // 太阳数目
			$score2 = $score - $sun * $s3; // 验证太阳后余下的积分数
		} else {
			$score2 = $score; // 返回原积分
		}
		$return ['star'] = $sun;
		$return ['score'] = $score2;
		return $return;
	}
	/**
	 * 验证月亮
	 */
	public function moon($score) {
		$s2 = $this->star2; // 月亮需要的积分
		$mod = $score / $s2;
		if ($mod >= 1) {
			$rank = floor ( $mod ); // 月亮数目
			if ($rank > 3) { // 进位，会出现超3个的情况
				$rank = 3;
			}
			$score2 = $score - $rank * $s2; // 验证月亮后余下的积分数
		} else {
			$score2 = $score; // 返回原积分
		}
		$return ['star'] = $rank;
		$return ['score'] = $score2;
		return $return;
	}
	/**
	 * 验证星星
	 */
	public function star($score) {
		$s = $this->star1; // 星星需要的积分
		$mod = $score / $s;
		if ($mod >= 1) {
			$rank = floor ( $mod ); // 星星数目
		}
		if ($rank > 3) { // 在3个星星向月亮进位时，时间变长，但不能出现4个星星的情况
			$rank = 3;
		}
		$return ['star'] = $rank;
		return $return;
	}

}

?>