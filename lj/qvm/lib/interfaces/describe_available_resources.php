<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 15:32
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class DescribeAvailableResources implements Requester
{
	/**
	 * 区域ID
	 * @var string
	 */
	private $_regionId = '';
	
	/**
	 * 可用区ID
	 * @var string
	 */
	private $_zoneId = '';
	
	/**
	 * 资源的计费方式
	 * @var string
	 */
	private $_instanceChargeType = self::INSTANCE_CHARGE_TYPE_POSTPAID;
	
	/**
	 * 实例规格
	 * @var string
	 */
	private $_instanceType = '';
	
	/**
	 * 计费方式（预付费，包年包月）
	 */
	public const INSTANCE_CHARGE_TYPE_PREPAID = 'PrePaid';
	
	/**
	 * 计费方式（后付费，按时按量）
	 */
	public const INSTANCE_CHARGE_TYPE_POSTPAID = 'PostPaid';
	
	public function getMethod(): string
	{
		return 'GET';
	}
	
	public function getData(): string
	{
		return '';
	}
	
	public function getPath(): string
	{
		return '/v1/available_resource';
	}
	
	public function getQueryData(): array
	{
		$queryData = [
			'region_id' => $this->_regionId ,
			'instance_charge_type' => $this->_instanceChargeType ,
		];
		
		if( $this->_instanceType )
		{
			$queryData['instance_type'] = $this->_instanceType;
		}
		
		if( $this->_zoneId )
		{
			$queryData['zone_id'] = $this->_zoneId;
		}
		
		return $queryData;
	}
	
	public function getProductCode(): string
	{
		return 'ecs';
	}
	
	public function verify(): bool
	{
		return true;
	}
	
	/**
	 * 设置区域ID
	 * @param string $regionId 区域ID
	 * @return DescribeAvailableResources
	 */
	public function setRegionId( $regionId ): DescribeAvailableResources
	{
		$this->_regionId = $regionId;
		return $this;
	}
	
	/**
	 * 设置可用区ID
	 * @param string $zoneId 可用区ID
	 * @return DescribeAvailableResources
	 */
	public function setZoneId( $zoneId ): DescribeAvailableResources
	{
		$this->_zoneId = $zoneId;
		return $this;
	}
	
	/**
	 * 设置资源的计费方式
	 * @param string $instanceChargeType 资源的计费方式
	 * @return DescribeAvailableResources
	 */
	public function setInstanceChargeType( $instanceChargeType ): DescribeAvailableResources
	{
		$this->_instanceChargeType = $instanceChargeType;
		return $this;
	}
	
	/**
	 * 设置实例规格
	 * @param string $instanceType 实例规格
	 * @return DescribeAvailableResources
	 */
	public function setInstanceType( $instanceType ): DescribeAvailableResources
	{
		$this->_instanceType = $instanceType;
		return $this;
	}
	
	/**
	 * 获取实例
	 * @return DescribeAvailableResources
	 */
	public static function getInstance(): DescribeAvailableResources
	{
		return new self();
	}
}

