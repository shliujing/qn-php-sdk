<?php
/**
 * CURL类库
 * @author	Luckyboys
 */
class Helper_Curl
{
	/**
	 * User Agent
	 * @var	string
	 */
	private $_userAgent = '';
	
	/**
	 * 是否打开当返回空白时报错
	 * @var	boolean
	 */
	private $_isThrowError = true;
	
	/**
	 * HTTP头
	 * @var	array
	 */
	private $_headers = array();
	
	/**
	 * 连接
	 * @var	resource
	 */
	private $_connect = null;
	
	/**
	 * 最近的错误号
	 * @var	int
	 */
	private $_lastErrorCode = CURLE_OK;
	
	/**
	 * 最近的错误消息
	 * @var	string
	 */
	private $_lastErrorMessage = '';
	
	/**
	 * 结果
	 * @var	boolean
	 */
	private $_result = '';

	/**
	 * 最后访问地址信息
	 * @var	array
	 */
	private $_lastInfo = array();
	
	/**
	 * 最后访问的响应头信息
	 * @var	array
	 */
	private $_lastResponseHeader = array();
	
	/**
	 * 是否自动跳转
	 * @var	boolean
	 */
	private $_isAutoJump = true;

	/**
	 * 是否返回内容
	 * @var	boolean
	 */
	private $_isNoBody = false;
	
	/**
	 * HTTP版本
	 * @var	int
	 */
	private $_httpVersion = CURL_HTTP_VERSION_NONE;

	/**
	 * 代理主机地址
	 * host:port
	 * @var string
	 */
	private $_proxy = '';

	/**
	 * 个人证书路径
	 * @var string
	 */
	private $_personalCertFile = '';

	/**
	 * 个人证书密码
	 * @var string
	 */
	private $_personalCertPassword = '';

	/**
	 * 公钥证书路径
	 * @var string
	 */
	private $_caFile = '';
	
	/**
	 * 判断是否长度不一致
	 * @var	boolean
	 */
	private $_isValidateLength = true;

	/**
	 * 将文件下载到这里
	 *
	 * @var
	 */
	private $_downloadFile;

	/**
	 * 超时（单位：秒）
	 * @var	int
	 */
	private $_timeout = self::DEFAULT_TIMEOUT;

	/**
	 * Cookie文件内容
	 * @var	string
	 */
	private $_cookies = '';
	
	/**
	 * 最后访问结束时Cookie文件内容
	 * @var	string
	 */
	private $_lastCookies = '';

	/**
	 * 保持数组字段名（允许同名字段存在）
	 * @var bool
	 */
	private $_keepArrayParamName = false;

	/**
	 * 单例对象
	 * @var	\Helper_Curl
	 */
	private static $_singletonObject = null;
	
	/**
	 * 无效长度
	 * @var	int
	 */
	const ERROR_CODE_LENGTH_INVALID = 65535;
	
	/**
	 * 无效长度
	 * @var	string
	 */
	const ERROR_MESSAGE_LENGTH_INVALID = '返回数据跟长度不一致';

	/**
	 * 默认超时（单位：秒）
	 */
	const DEFAULT_TIMEOUT = 10;

	const PROXY_SOCKS5 = '180.76.110.115:1080';
	const PROXY_HTTP = '180.76.110.115:3128';

	/**
	 * @var int
	 */
	private $_proxyType = 0;

	/**
	 * 实例化
	 */
	public function __construct()
	{
		$this->simulateMobile();
	}
	
	/**
	 * 模拟手机端
	 * @return	Helper_Curl
	 */
	public function simulateMobile()
	{
		$this->_userAgent = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5';
		return $this;
	}

	/**
	 * 模拟PC端
	 * @return	Helper_Curl
	 */
	public function simulatePC()
	{
		$this->_userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36';
		return $this;
	}
	
	/**
	 * 设置HTTP头
	 * @param	array	$headers	HTTP头
	 * @return	Helper_Curl
	 */
	public function setHeaders( $headers )
	{
		$this->_headers = $headers;
		return $this;
	}
	
	/**
	 * 设置是否自动跳转
	 * @param	boolean	$isAutoJump	是否跳
	 * @return	Helper_Curl
	 */
	public function setIsAutoJump( $isAutoJump )
	{
		$this->_isAutoJump = $isAutoJump;
		return $this;
	}

	/**
	 * 设置是否返回内容
	 * @param	boolean	$isNoBody	是否返回内容
	 * @return	Helper_Curl
	 */
	public function setIsNoBody( $isNoBody )
	{
		$this->_isNoBody = $isNoBody;
		return $this;
	}
	
	/**
	 * 设置User Agent
	 * @param	string	$userAgent	User Agent
	 * @return	Helper_Curl
	 */
	public function setUserAgent( $userAgent )
	{
		$this->_userAgent = $userAgent;
		return $this;
	}
	
	/**
	 * 设置是否抛出错误
	 * @param	boolean	$isThrowError	是否抛出异常
	 * @return	Helper_Curl
	 */
	public function setIsThrowError( $isThrowError )
	{
		$this->_isThrowError = $isThrowError;
		return $this;
	}
	
	/**
	 * 设置HTTP请求版本
	 * @param	int	$version	版本号（CURL_HTTP_VERSION_NONE、CURL_HTTP_VERSION_1_0、CURL_HTTP_VERSION_1_1）
	 * @return	Helper_Curl
	 */
	public function setHTTPVersion( $version )
	{
		$this->_httpVersion = $version;
		return $this;
	}

	/**
	 * 设置代理
	 * @param string $proxy
	 * @return	Helper_Curl
	 */
	public function setProxy( $proxy )
	{
		$this->_proxy = $proxy;
		return $this;
	}

	/**
	 * 设置代理类型
	 * https://tower.im/projects/e8196e369f364fbea411b8ed2cacd2b6/docs/37af5d98c8814832957e0139c8dc1055/
	 * @param	int	$proxyType //CURLPROXY_HTTP ,CURLPROXY_SOCKS5
	 * @return $this
	 */
	public function setProxyType( $proxyType )
	{
		$this->_proxyType = $proxyType;
		return $this;
	}

	/**
	 * 设置个人证书
	 * @param $certFile
	 * @param $certPassword
	 * @return Helper_Curl
	 */
	public function setCertFile( $certFile , $certPassword )
	{
		$this->_personalCertFile = $certFile;
		$this->_personalCertPassword = $certPassword;
		return $this;
	}

	/**
	 * 设置公钥证书
	 * @param $caFile
	 * @return Helper_Curl
	 */
	public function setCaFile( $caFile )
	{
		$this->_caFile = $caFile;
		return $this;
	}

	/**
	 * 设置是否判断返回值长度
	 * @param	boolean	$isValidateLength	是否判断返回值长度
	 * @return	Helper_Curl
	 */
	public function setIsValidateLength( $isValidateLength )
	{
		$this->_isValidateLength = $isValidateLength;
		return $this;
	}

	/**
	 * 设置超时时间
	 * @param	int	$timeoutSecond	超时（单位：秒）
	 * @return	Helper_Curl
	 */
	public function setTimeout( $timeoutSecond )
	{
		$this->_timeout = $timeoutSecond;
		return $this;
	}

	/**
	 * 设置Cookies数据
	 * @param	string	$cookies	Cookies数据【abc=def;hij=mnl】
	 * @return	Helper_Curl
	 */
	public function setCookie( $cookies )
	{
		$this->_cookies = $cookies;
		return $this;
	}

	/**
	 * @param bool $keepArrayParamName
	 * @return $this
	 */
	public function setKeepArrayParamName( $keepArrayParamName )
	{
		$this->_keepArrayParamName = $keepArrayParamName;

		return $this;
	}

	/**
	 * GET操作
	 * @param	string	$url	URL地址
	 * @param	int	$maxTryTimes	最大尝试次数
	 * @return	string
	 * @throws	Exception
	 */
	public function get( $url , $maxTryTimes = 3 )
	{
		do
		{
			$this->_init( $url );
			
			$this->_execute();
			
			$this->_checkError( $url );
			
			$this->_close();
			
			$this->_parseCookie();
		}
		while( $this->_lastErrorCode != CURLE_OK && --$maxTryTimes > 0 );

		$this->_clear();

		if( $this->_lastErrorCode != CURLE_OK && $this->_isThrowError )
		{
			throw new Exception( 7000 );
		}

		return $this->_result;
	}

	/**
	 * POST操作
	 * @param    string $url URL地址
	 * @param    array|string $data 数据
	 * @param    int $maxTryTimes 最大尝试次数
	 * @return   string
	 * @throws   Exception
	 */
	public function post( $url , $data = array() , $maxTryTimes = 3 )
	{
		do
		{
			$this->_keepArrayParamName && $this->_buildBoundary( $data );

			$this->_init( $url );

			$this->_setPost();
			$this->_setData( $data );
			
			$this->_execute();
			
			$this->_checkError( $url );
			
			$this->_close();
			
			$this->_parseCookie();
		}
		while( $this->_lastErrorCode != CURLE_OK && --$maxTryTimes > 0 );
		
		$this->_clear();

		if( $this->_lastErrorCode != CURLE_OK && $this->_isThrowError )
		{
			throw new Exception( 7000 );
		}

		return $this->_result;
	}

	/**
	 * POST操作
	 * @param	string	$method	HTTP方法（GET，POST，PUT，DELETE）
	 * @param	string	$url	URL地址
	 * @param	array	$data	数据
	 * @param	int	$maxTryTimes	最大尝试次数
	 * @return	string
	 * @throws	Exception
	 */
	public function customer( $method , $url , $data = array() , $maxTryTimes = 3 )
	{
		do
		{
			$this->_init( $url );

			$this->_setMethod( $method );

			$this->_setData( $data );

			$this->_execute();

			$this->_checkError( $url );

			$this->_close();
			
			$this->_parseCookie();
		}
		while( $this->_lastErrorCode != CURLE_OK && --$maxTryTimes > 0 );
		
		$this->_clear();

		if( $this->_lastErrorCode != CURLE_OK && $this->_isThrowError )
		{
			throw new Exception( 7000 );
		}

		return $this->_result;
	}
	
	/**
	 * 获取最后访问信息
	 * @return	array
	 */
	public function getLastInfo()
	{
		return $this->_lastInfo;
	}
	
	/**
	 * 获取最后响应头信息
	 * @return	array
	 */
	public function getLastResponseHeaders()
	{
		return $this->_lastResponseHeader;
	}

	/**
	 * 获取最后的CURL错误码
	 * @return	int
	 */
	public function getLastErrorCode()
	{
		return $this->_lastErrorCode;
	}

	/**
	 * 获取最后的CURL错误信息
	 * @return	string
	 */
	public function getLastErrorMessage()
	{
		return $this->_lastErrorMessage;
	}

	/**
	 * 获取最后的Cookie内容
	 * @return	string
	 */
	public function getLastCookies()
	{
		return $this->_lastCookies;
	}

	/**
	 * 设置下载文件的位置
	 *
	 * @param $file
	 * @throws Exception
	 * @return Helper_Curl
	 */
	public function setDownloadFile( $file )
	{
		$this->_downloadFile = fopen($file, 'wb');

		if (!$this->_downloadFile)
		{
			throw new Exception("$file 文件打开失败");
		}

		return $this;
	}
	
	/**
	 * 过期函数，不推荐使用
	 * @param	string	$url
	 * @param	array	$get
	 * @param	array	$post
	 * @param	boolean	$isMobile
	 * @param	string	$customUserAgent
	 * @param	array	$httpHeaders
	 * @return	string
	 * @throws	Exception
	 */
	public static function curl( $url , $get = array() , $post = array() , $isMobile = true , $customUserAgent = '' , $httpHeaders = array() )
	{
		if( $get )
		{
			$tmp = array();
			foreach( $get as $key => $value )
			{
				$tmp[] = "{$key}={$value}";
			}
			$url .= '?' . implode( '&' , $tmp );
		}
		
		$client = self::getInstance()
		->setIsThrowError( true )
		->setHeaders( $httpHeaders );
		
		if( empty( $customUserAgent ) )
		{
			if( $isMobile )
			{
				$client->simulateMobile();
			}
			else
			{
				$client->simulatePC();
			}
		}
		else
		{
			$client->setUserAgent( $customUserAgent );
		}

		if( empty( $post ) )
		{
			return $client->get( $url );
		}
		return $client->post( $url , $post );
	}

	/**
	 * 获取单例
	 * @return	Helper_Curl
	 */
	public static function getInstance(): Helper_Curl
	{
		if( self::$_singletonObject === null )
		{
			self::$_singletonObject = new self();
		}
		return self::$_singletonObject;
	}

	/**
	 * 初始化
	 * @param	string	$url	URL地址
	 */
	private function _init( $url )
	{
		$this->_lastErrorCode = CURLE_OK;
		$this->_lastErrorMessage = '';

		$this->_connect = curl_init();
		curl_setopt( $this->_connect , CURLOPT_URL , $url );
		curl_setopt( $this->_connect , CURLOPT_RETURNTRANSFER , true );
		curl_setopt( $this->_connect , CURLOPT_FOLLOWLOCATION , $this->_isAutoJump );
		curl_setopt( $this->_connect , CURLOPT_NOBODY , $this->_isNoBody );
		curl_setopt( $this->_connect , CURLOPT_ENCODING , 'gzip' );
		curl_setopt( $this->_connect , CURLOPT_CONNECTTIMEOUT , $this->_timeout );
		curl_setopt( $this->_connect , CURLOPT_TIMEOUT , $this->_timeout );
		curl_setopt( $this->_connect , CURLOPT_USERAGENT , $this->_userAgent );
		curl_setopt( $this->_connect , CURLOPT_HEADER , $this->_downloadFile ? false : true );
		curl_setopt( $this->_connect , CURLINFO_HEADER_OUT , $this->_downloadFile ? false : true );
		curl_setopt( $this->_connect , CURLOPT_HTTP_VERSION , $this->_httpVersion );
		curl_setopt( $this->_connect , CURLOPT_COOKIE , $this->_cookies );

		// 设置代理
		if( !empty( $this->_proxy ) )
		{
			curl_setopt( $this->_connect , CURLOPT_PROXY , $this->_proxy );
		}
		else
		{
			curl_setopt( $this->_connect , CURLOPT_PROXY , null );
		}

		if( !empty( $this->_proxy ) && $this->_proxyType >= 0 )
		{
			curl_setopt( $this->_connect, CURLOPT_PROXYTYPE, $this->_proxyType );
		}
		else
		{
			curl_setopt( $this->_connect, CURLOPT_PROXYTYPE, null );
		}

		if( is_array( $this->_headers ) && count( $this->_headers ) > 0 )
		{
			$headers = array();
			foreach( $this->_headers as $key => $value )
			{
				$headers[] = "{$key}: {$value}";
			}
			curl_setopt( $this->_connect , CURLOPT_HTTPHEADER , $headers );
		}

		// 设置个人证书信息
		if( !empty( $this->_personalCertFile ) )
		{
			curl_setopt( $this->_connect , CURLOPT_SSLCERT , $this->_personalCertFile );
			curl_setopt( $this->_connect , CURLOPT_SSLCERTPASSWD , $this->_personalCertPassword );
		}

		// 设置ssl的公钥证书检查
		if( !empty( $this->_caFile ) )
		{
			curl_setopt( $this->_connect , CURLOPT_SSL_VERIFYHOST , 2 );
			curl_setopt( $this->_connect , CURLOPT_SSL_VERIFYPEER , true );
			curl_setopt( $this->_connect , CURLOPT_CAINFO , $this->_caFile );
		}
		else
		{
			curl_setopt( $this->_connect , CURLOPT_SSL_VERIFYHOST , 0 );
			curl_setopt( $this->_connect , CURLOPT_SSL_VERIFYPEER , false );
		}

		// 将文件下载到这里
		if( $this->_downloadFile )
		{
			curl_setopt( $this->_connect , CURLOPT_FILE , $this->_downloadFile );
		}
	}
	
	/**
	 * 执行查询
	 */
	private function _execute()
	{
		$result = curl_exec( $this->_connect );

		$this->_lastInfo = curl_getinfo( $this->_connect );

		if( !$this->_downloadFile )
		{
			$this->_result = substr( $result , $this->_lastInfo['header_size'] );

			$header = substr( $result , 0 , $this->_lastInfo['header_size'] );

			$headers = explode( "\n" , $header );
			$this->_lastResponseHeader = array();
			foreach( $headers as $header )
			{
				if( strpos( $header , 'HTTP/' ) === 0 )
				{
					continue;
				}

				if( strlen( $header = trim( $header ) ) <= 0 )
				{
					continue;
				}

				list( $key , $value ) = explode( ':' , $header , 2 );
				if( !isset( $this->_lastResponseHeader[$key] ) )
				{
					$this->_lastResponseHeader[$key] = $value;
				}
				else
				{
					if( !is_array( $this->_lastResponseHeader[$key] ) )
					{
						$this->_lastResponseHeader[$key] = array(
							$this->_lastResponseHeader[$key]
						);
					}

					$this->_lastResponseHeader[$key][] = $value;
				}
			}
		}
	}
	
	/**
	 * 检查错误
	 * @param	string	$url	请求的URL地址
	 */
	private function _checkError( $url )
	{
		$this->_lastErrorCode = curl_errno( $this->_connect );
		$this->_lastErrorMessage = curl_error( $this->_connect );
		
		$length = curl_getinfo( $this->_connect , CURLINFO_CONTENT_LENGTH_DOWNLOAD );
		
		if( !$this->_downloadFile && $this->_isValidateLength && $length > 0 && $length > strlen( $this->_result ) )
		{
			$this->_lastErrorCode = self::ERROR_CODE_LENGTH_INVALID;
			$this->_lastErrorMessage = self::ERROR_MESSAGE_LENGTH_INVALID;
			$this->_result = '';
		}
	}
	
	/**
	 * 关闭连接
	 */
	private function _close()
	{
		if( is_resource( $this->_connect ) )
		{
			curl_close( $this->_connect );
		}

		if( is_resource( $this->_downloadFile ) )
		{
			fclose( $this->_downloadFile );
		}
	}

	/**
	 * 设置提交的数据
	 * @param    array $data 数据
	 */
	private function _setData( $data )
	{
		$this->_setCURLData( $this->_compatibilityUploadFile( $data ) );
	}
	
	/**
	 * 兼容多级传图
	 * @param	array	$data	数据
	 * @return	array
	 */
	private function _compatibilityUploadFile( $data )
	{
		$returnData = $data;
		if( is_array( $data ) )
		{
			$returnData = array();
			foreach( $data as $key => $value )
			{
				if( is_array( $value ) )
				{
					foreach( $this->_flatData( $value , $key ) as $item )
					{
						$returnData[$item['key']] = $item['value'];
					}
				}
				else
				{
					$returnData[$key] = $this->_tryToConvertToFileObject( $value );;
				}
			}
		}
		return $returnData;
	}
	
	/**
	 * 扁平化数据
	 * @param	array	$data	数据
	 * @param	string	$keys	键的名称
	 * @return	array
	 */
	private function _flatData( $data , $keys = '' )
	{
		$returnData = array();
		foreach( $data as $key => $value )
		{
			$partKey = sprintf( '%s[%s]' , $keys , urlencode( $key ) );
			if( is_array( $value ) )
			{
				foreach( $this->_flatData( $value , $partKey ) as $flatKey => $flatValue )
				{
					$returnData[$flatKey] = $flatValue;
				}
			}
			else
			{
				$returnData[] = array(
					'key' => $partKey ,
					'value' => $this->_tryToConvertToFileObject( $value ) ,
				);
			}
		}
		
		return $returnData;
	}
	
	/**
	 * 设置提交给CURL的数据
	 * @param	array	$data	数据
	 */
	private function _setCURLData( $data )
	{
		curl_setopt( $this->_connect , CURLOPT_POSTFIELDS , $data );
	}

	/**
	 * 清理现场
	 */
	private function _clear()
	{
		$this->_headers = array();

		$this->_isNoBody = false;

		$this->_timeout = self::DEFAULT_TIMEOUT;
		$this->_downloadFile = null;
		
		$this->_cookies = '';

		$this->_keepArrayParamName = false;
	}

	/**
	 * 设置操作方法为POST
	 */
	private function _setPost()
	{
		curl_setopt( $this->_connect , CURLOPT_POST , 1 );
	}

	/**
	 * 设置HTTP方法
	 * @param	string	$method	HTTP方法
	 */
	private function _setMethod( $method )
	{
		curl_setopt( $this->_connect , CURLOPT_CUSTOMREQUEST , $method );
	}

	/**
	 * 如果是文件,就转换为文件对象
	 * @param	mixed	$value	数据值
	 * @return	CURLFile|mixed
	 */
	private function _tryToConvertToFileObject( $value )
	{
		if( strpos( $value , '@/' ) === 0 )
		{
			if( version_compare( '5.5.0' , PHP_VERSION ) <= 0 )
			{
				// Get the file name
				$filename = ltrim( $value , '@' );
				// Convert the value to the new class
				$value = new CURLFile( $filename );
				return $value;
			}
			return $value;
		}
		return $value;
	}
	
	/**
	 * 识别Cookie内容
	 */
	private function _parseCookie()
	{
		$cookies = array();
		foreach( explode( ';' , $this->_cookies ) as $cookie )
		{
			if( strpos( $cookie , '=' ) === false )
			{
				continue;
			}
			list( $key , $value ) = explode( '=' , $cookie , 2 );
			if( !empty( $value ) )
			{
				$cookies[trim( $key )] = trim( $value );
			}
		}
		
		$this->_lastCookies = $this->_cookies;
		if( empty( $this->_lastResponseHeader['Set-Cookie'] ) )
		{
			return;
		}
		
		if( !is_array( $this->_lastResponseHeader['Set-Cookie'] ) )
		{
			$this->_lastResponseHeader['Set-Cookie'] = array(
				$this->_lastResponseHeader['Set-Cookie']
			);
		}
		
		foreach( $this->_lastResponseHeader['Set-Cookie'] as $cookie )
		{
			$isFirstItem = true;
			foreach( explode( ';' , $cookie ) as $item )
			{
				if( !strpos( $item , '=' ) !== false )
				{
					continue;
				}
				
				list( $key , $value ) = explode( '=' , $item , 2 );
				$key = trim( $key );
				$value = trim( $value );
				
				if( $isFirstItem )
				{
					$cookies[$key] = $value;
					$isFirstItem = false;
					continue;
				}
				
				$expireTime = strtotime( '+1 year' );
				switch( strtolower( $key ) )
				{
					case 'path':
					case 'domain':
					default:
						
						break;
						
					case 'expires':
						
						$expireTime = strtotime( $value );
						break;
						
				}
				
				if( $expireTime < time() )
				{
					if( isset( $cookies[$key] ) )
					{
						unset( $cookies[$key] );
					}
					continue;
				}
			}
		}
		
		$this->_lastCookies = '';
		$stringCookie = array();
		
		foreach( $cookies as $key => $value )
		{
			$stringCookie[] = sprintf( "%s=%s" , $key , $value );
		}
		$this->_lastCookies = implode( ';' , $stringCookie );
	}

	/**
	 * @param $data
	 */
	private function _buildBoundary( &$data )
	{
		$algos = hash_algos();
		$hashAlgo = null;
		foreach ( array('sha1', 'md5') as $preferred ) {
			if ( in_array($preferred, $algos) ) {
				$hashAlgo = $preferred;
				break;
			}
		}
		if ( $hashAlgo === null ) { list($hashAlgo) = $algos; }
		$boundary =
			'----------------------------' .
			substr(hash($hashAlgo, 'cURL-php-multiple-value-same-key-support' . microtime()), 0, 12);

		$body = array();
		$crlf = "\r\n";
		$fields = array();
		foreach ( $data as $key => $value ) {
			if ( is_array($value) ) {
				foreach ( $value as $v ) {
					$fields[] = array($key, $v);
				}
			} else {
				$fields[] = array($key, $value);
			}
		}
		foreach( $fields as list( $key , $value ) )
		{
			if( strpos( $value , '@' ) === 0 )
			{
				preg_match( '/^@(.*?)$/' , $value , $matches );
				list( , $filename ) = $matches;
				$body[] = '--' . $boundary;
				$body[] = 'Content-Disposition: form-data; name="' . $key . '"; filename="' . basename($filename) . '"';
				$body[] = 'Content-Type: application/octet-stream';
				$body[] = '';
				$body[] = file_get_contents($filename);
			}
			else
			{
				$body[] = '--' . $boundary;
				$body[] = 'Content-Disposition: form-data; name="' . $key . '"';
				$body[] = '';
				$body[] = $value;
			}
		}
		$body[] = '--' . $boundary . '--';
		$body[] = '';
		$contentType = 'multipart/form-data; boundary=' . $boundary;
		$content = implode($crlf, $body);
		$contentLength = strlen($content);

		$this->_headers['Content-Length'] = $contentLength;
		$this->_headers['Expect'] = '100-continue';
		$this->_headers['Content-Type'] = $contentType;

		$data = $content;
	}
}