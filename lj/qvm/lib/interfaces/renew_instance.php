<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-17
 * Time: 18:18
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class RenewInstance implements Requester
{
	/**
	 * 购买资源的时长单位（按月）
	 */
	public const PERIOD_UNIT_MONTH = 'Month';
	
	/**
	 * 购买资源的时长单位（按周）
	 */
	public const PERIOD_UNIT_WEEK = 'Week';
	
	/**
	 * 实例ID
	 * @var string
	 */
	private $_instanceId = '';
	
	/**
	 * 购买资源的时长
	 * @var int
	 */
	private $_period = 1;
	
	/**
	 * 购买资源的时长单位
	 * @var string
	 */
	private $_periodUnit = self::PERIOD_UNIT_MONTH;
	
	/**
	 * 保证请求幂等性
	 * @var string
	 */
	private $_clientToken = '';
	
	private function __construct()
	{
	}
	
	public function getMethod(): string
	{
		return 'POST';
	}
	
	public function getData(): string
	{
		$data = [];
		
		$data['instance_id'] = $this->_instanceId;
		$data['client_token'] = $this->_clientToken;
		
		if( $this->_period )
		{
			$data['period'] = $this->_period;
		}
		
		if( $this->_periodUnit )
		{
			$data['period_unit'] = $this->_periodUnit;
		}
		
		return json_encode( $data );
	}
	
	public function getPath(): string
	{
		return sprintf( '/v1/instance/%s/renew' , $this->_instanceId );
	}
	
	public function getQueryData(): array
	{
		return [];
	}
	
	public function getProductCode(): string
	{
		return 'ecs';
	}
	
	public function verify(): bool
	{
		return !( empty( $this->_instanceId )
			|| empty( $this->_period )
			|| empty( $this->_periodUnit )
		);
	}
	
	/**
	 * 设置实例ID
	 * @param string $instanceId 实例ID
	 * @return $this
	 */
	public function setInstanceId( $instanceId ): RenewInstance
	{
		$this->_instanceId = $instanceId;
		return $this;
	}
	
	/**
	 * 设置购买资源的时长
	 * @param int $period 购买资源的时长
	 * @return $this
	 */
	public function setPeriod( $period ): RenewInstance
	{
		$this->_period = $period;
		return $this;
	}
	
	/**
	 * 设置购买资源的时长单位
	 * @param string $periodUnit 购买资源的时长单位
	 * @return $this
	 */
	public function setPeriodUnit( $periodUnit ): RenewInstance
	{
		$this->_periodUnit = $periodUnit;
		return $this;
	}
	
	/**
	 * 设置保证请求幂等性
	 * @param string $clientToken 保证请求幂等性
	 * @return $this
	 */
	public function setClientToken( $clientToken ): RenewInstance
	{
		$this->_clientToken = $clientToken;
		return $this;
	}
	
	/**
	 * 获取实例
	 * @return RenewInstance
	 */
	public static function getInstance(): RenewInstance
	{
		return new self();
	}
}

