<?php

/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 16:08
 */
class Client
{
	/**
	 * 网关URL前缀
	 * @var string
	 */
	private $_gatewayBaseURL = '';
	
	/**
	 * 公钥
	 * @var string
	 */
	private $_publicKey;
	
	/**
	 * 密钥
	 * @var string
	 */
	private $_secretKey;
	
	const SIGNATURE_METHOD_HMAC_SHA1 = 'HMAC-SHA1';
	
	private function __construct( $publicKey , $secretKey )
	{
		$this->_publicKey = $publicKey;
		$this->_secretKey = $secretKey;
	}
	
	/**
	 * 发送请求
	 * @param Requester $requester
	 * @throws Exception
	 * @return array
	 */
	public function Request( Requester $requester ): array
	{
		if( !$requester->verify() )
		{
			return [ 'code' => 400 , 'error_message' => 'Verify Data Error.' ];
		}
		
		$path = $requester->getPath();
		$queryData = $requester->getQueryData();
		$data = $requester->getData();
		$method = $requester->getMethod();
		$code = $requester->getProductCode();
		
		$queryData['code'] = $code;
		$queryData['public_key'] = $this->_publicKey;
		$queryData['signature_method'] = self::SIGNATURE_METHOD_HMAC_SHA1;
		$queryData['signature_version'] = '1.0';
		$queryData['signature_nonce'] = mt_rand();
		$queryData['timestamp'] = $this->_getCurrentTime();
		
		$sign = $this->_signature( $method , $path , $queryData , $data );
		$queryData['signature'] = $sign;
		
		$requester = Helper_Curl::getInstance();
		$body = $requester
			->setIsThrowError( false )
			->setTimeout( 300 )
			->customer( $method , $url = $this->_buildURL( $path , $queryData ) , $data );
		
		printf( "URL: %s\n" , $url );
		
		if( $body === false || $body === '' || $body === null )
		{
			return [
				'code' => $requester->getLastErrorCode() ,
				'error_message' => $requester->getLastErrorMessage() ,
			];
		}
		
		printf( "body: %s\n" , $body );
		$result = json_decode( $body , true );
		if( $result !== null )
		{
			return $result;
		}
		
		return [
			'code' => 400 ,
			'error_message' => $body ,
		];
	}
	
	/**
	 * 设置网关URL前缀
	 * @param string $gatewayBaseURL 网关URL前缀
	 * @return Client
	 */
	public function setGatewayBaseURL( string $gatewayBaseURL ): Client
	{
		$this->_gatewayBaseURL = $gatewayBaseURL;
		return $this;
	}
	
	/**
	 * 获取实例
	 * @param string $publicKey 公钥
	 * @param string $secretKey 密钥
	 * @return Client
	 */
	public static function getInstance( string $publicKey , string $secretKey ): Client
	{
		return new self( $publicKey , $secretKey );
	}
	
	/**
	 * 获取当前时间的ISO8601格式
	 * @return string
	 * @throws Exception
	 */
	private function _getCurrentTime(): string
	{
		$time = new DateTime( null , new DateTimeZone( 'UTC' ) );
		return sprintf( '%sT%sZ' , $time->format( 'Y-m-d' ) , $time->format( 'H:i:s' ) );
	}
	
	private static function _canonicalizedString( array $data ): string
	{
		if( empty( $data ) )
		{
			return '';
		}
		ksort( $data , SORT_STRING );
		
		$stringBuilder = [];
		foreach( $data as $key => $value )
		{
			$stringBuilder[] = self::_percentEncode( $key ) . '=' . self::_percentEncode( $value );
		}
		$canonicalizedString = implode( '&' , $stringBuilder );
		
		return self::_percentEncode( $canonicalizedString );
	}
	
	private static function _percentEncode( string $str ): string
	{
		if( $str === null || $str === '' )
		{
			return '';
		}
		
		return strtr(
			urlencode( $str ) ,
			array(
				'+' => '%20' ,
				'*' => '%2A' ,
				'%7E' => '~' ,
			)
		);
	}
	
	/**
	 * 签名
	 * @param string $method HTTP方法
	 * @param string $path HTTP请求路径
	 * @param array $queryData HTTP查询参数
	 * @param string $data Body数据
	 * @return string
	 */
	private function _signature( string $method , string $path , array $queryData , string $data ): string
	{
		$stringToSign = $method . '&' .
			self::_percentEncode( $path ) . '&' .
			self::_canonicalizedString( $queryData ) . '&' .
			self::_percentEncode( $data );
		
		printf( "signature string: %s\n" , $stringToSign );
		printf( "secretKey: %s\n" , $this->_secretKey . '&' );
		return base64_encode( hash_hmac( 'sha1' , $stringToSign , $this->_secretKey . '&' , true ) );
	}
	
	/**
	 * 建立请求URL
	 * @param string $path
	 * @param array $queryData
	 * @return string
	 */
	private function _buildURL( $path , $queryData ): string
	{
		return sprintf( '%s%s?%s' , $this->_gatewayBaseURL , $path , http_build_query( $queryData ) );
	}
}