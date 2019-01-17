<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 15:32
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class DescribeInstance implements Requester
{
	/**
	 * 实例ID
	 * @var string
	 */
	private $_instanceId = '';
	
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
		return '/v1/instance/' . $this->_instanceId;
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
		return !empty( $this->_instanceId );
	}
	
	/**
	 * 设置实例ID
	 * @param string $instanceId 实例ID
	 * @return DescribeInstance
	 */
	public function setInstanceId( $instanceId ): DescribeInstance
	{
		$this->_instanceId = $instanceId;
		return $this;
	}
	
	/**
	 * 获取实例
	 * @return DescribeInstance
	 */
	public static function getInstance(): DescribeInstance
	{
		return new self();
	}
}

