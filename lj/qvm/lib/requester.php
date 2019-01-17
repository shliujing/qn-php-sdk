<?php

/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 15:58
 */
interface Requester
{
	public function getMethod(): string;
	
	public function getData(): string;
	
	public function getPath(): string;
	
	public function getQueryData(): array;
	
	public function getProductCode(): string;
	
	public function verify(): bool;
}