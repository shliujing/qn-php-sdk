<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-17
 * Time: 18:12
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class DescribeInstanceTypes implements Requester
{
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
		return '/v1/instance_type';
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
		return true;
	}
	
	/**
	 * 获取实例
	 * @return DescribeInstanceTypes
	 */
	public static function getInstance(): DescribeInstanceTypes
	{
		return new self();
	}
}

