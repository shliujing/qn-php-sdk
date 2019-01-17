<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 15:32
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class DescribeInstanceList implements Requester
{
	/**
	 * 页码
	 * @var int
	 */
	private $_page = 1;
	
	/**
	 * 分页大小
	 * @var int
	 */
	private $_pageSize = 20;
	
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
		return '/v1/instance';
	}
	
	public function getQueryData(): array
	{
		return [
			'page' => $this->_page ,
			'page_size' => $this->_pageSize ,
		];
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
	 * 设置页码
	 * @param int $page 页码
	 * @return DescribeInstanceList
	 */
	public function setPage( $page ): DescribeInstanceList
	{
		$this->_page = $page;
		return $this;
	}
	
	/**
	 * 设置分页大小
	 * @param int $pageSize 分页大小
	 * @return DescribeInstanceList
	 */
	public function setPageSize( $pageSize ): DescribeInstanceList
	{
		$this->_pageSize = $pageSize;
		return $this;
	}
	
	/**
	 * 获取实例
	 * @return DescribeInstanceList
	 */
	public static function getInstance(): DescribeInstanceList
	{
		return new self();
	}
}

