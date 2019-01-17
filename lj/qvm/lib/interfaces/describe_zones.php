<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-17
 * Time: 14:31
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class DescribeZones implements Requester
{
	/**
	 * 区域ID
	 * @var string
	 */
	private $_regionId = '';
	
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
		return '/v1/zone';
	}
	
	public function getQueryData(): array
	{
		return [ 'region_id' => $this->_regionId ];
	}
	
	public function getProductCode(): string
	{
		return 'ecs';
	}
	
	public function verify(): bool
	{
		return !empty( $this->_regionId );
	}
	
	/**
	 * 设置区域ID
	 * @param string $regionId 区域ID
	 * @return DescribeZones
	 */
	public function setRegionId( $regionId ): DescribeZones
	{
		$this->_regionId = $regionId;
		return $this;
	}
	
	/**
	 * 获取实例
	 * @return DescribeZones
	 */
	public static function getInstance(): DescribeZones
	{
		return new self();
	}
}

