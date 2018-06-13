<?php
 
use PHPUnit\Framework\TestCase;

class MyTest extends TestCase
{
	
	public function testeSoma()
	{
		return $this->assertEquals(1+1, 2);
	}
	public function division()
	{
		return $this->assertEquals(10/1, 10);
	}
}