<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 15:32
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class DescribeRegions implements Requester
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
		return '/v1/region';
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
	 * @return DescribeRegions
	 */
	public static function getInstance(): DescribeRegions
	{
		return new self();
	}
}

