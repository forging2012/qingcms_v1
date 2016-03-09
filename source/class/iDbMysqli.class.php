<?php
class iDbMysqli {
	// 数据库连接参数配置
	protected $config = '';
	// 是否已经连接数据库
	protected $connected = false;
	// 影响行数
	public $numRows = 0;
	// 影响字段
	public $fieldCount = 0;
	// 数据库版本
	public $Version; // 50045
	                 // 数据库对象
	protected $mysqli = null;
	// 表前缀 iDbMysqli替换的表前缀
	private $iprefix = '_PRE_';
	
	/**
	 * 数据库初始化 架构函数
	 */
	public function __construct($config = '') {
		$this->setConfig ( $config );
		$this->connect ();
	}
	/**
	 * 数据库连接参数配置
	 */
	private function setConfig($config) {
		if (! empty ( $config )) {
			$this->config = $config;
			return;
		}
		$config ['hostname'] = C ( 'DB_HOST' ); // 服务器地址
		$config ['dbname'] = C ( 'DB_NAME' ); // 数据库名
		$config ['username'] = C ( 'DB_USER' ); // 用户名
		$config ['password'] = C ( 'DB_PWD' ); // 密码
		$config ['prefix'] = C ( 'DB_PREFIX' ); // 数据库表前缀
		$config ['charset'] = C ( 'DB_CHARSET' );
		$this->config = $config;
	}
	
	/**
	 * 创建数据库连接
	 */
	private function connect() {
		if ($this->mysqli)
			return;
		$config = $this->config;
		$mysqli = new mysqli ( $config ['hostname'], $config ['username'], $config ['password'], $config ['dbname'] );
		if (mysqli_connect_errno ())
			throw_exception ( mysqli_connect_error () );
			// 数据库版本
		$this->Version = $mysqli->server_version;
		// 设置数据库编码
		$mysqli->set_charset ( $config ['charset'] );
		$this->mysqli = $mysqli;
	}
	/**
	 * 执行查询，返回数据
	 * $sql:Select 'table' where id=1 order by id desc,up
	 */
	public function query($sql) {
		$sql = $this->parseSql ( $sql );
		$mysqli = $this->mysqli;
		$result = $mysqli->query ( $sql );
		
		if ($result == false)
			return false;
		
		$this->numRows = $result->num_rows; // 影响的行数
		$this->fieldCount = $result->field_count; // 影响的字段
		$res = array ();
		// 如果有数据则返回
		if ($this->numRows > 0) {
			for($i = 0; $i < $this->numRows; $i ++) {
				$res [$i] = $result->fetch_assoc ();
			}
		}
		return $res;
	}
	/**
	 * 插入数据 ，返回插入id
	 * $sql: Insert into _PRE_table ('','') values('','')
	 */
	public function insert($sql) {
		$sql = $this->parseSql ( $sql );
		$mysqli = $this->mysqli;
		$result = $mysqli->query ( $sql ); // 影响行数
		if ($result == false)
			return false;
		return $mysqli->insert_id;
	}
	/**
	 * 更新数据，返回影响行数
	 * $slq:Update _PRE_table set name='' where id=1
	 */
	public function update($sql) {
		$sql = $this->parseSql ( $sql );
		$mysqli = $this->mysqli;
		$result = $mysqli->query ( $sql ); // 影响行数
		if ($result == false && is_bool ( $result ))
			return false;
		if ($result == 0 && is_int ( $result ) || $result===0)
			return '影响条数为0，内容没有改变';
		return $result;
	}
	/**
	 * 解析sql语句，替换前缀
	 */
	public function parseSql($sql) {
		// _PRE_ $this->config['prefix']
		return str_replace ( $this->iprefix, $this->config ['prefix'], $sql );
	}
	/**
	 * 关闭数据库
	 */
	public function close() {
		if ($this->mysqli) {
			$this->mysqli->close ();
		}
		$this->mysqli = null;
	}
	/**
	 * 析构方法
	 */
	public function __destruct() {
		// 关闭连接
		$this->close ();
	}

}

?>