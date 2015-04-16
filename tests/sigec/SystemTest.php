<?php

namespace Sigec;

use Core\core\System;

class SystemTest extends \PHPUnit_Framework_TestCase
{
    const SYSTEM = 'Core\core\System';

    /**
     * @test
     * @testdox Test if construct worked as expected
     */
    public function testInstance()
    {
        $system = new System('Controller');
        $this->assertInstanceOf(self::SYSTEM, $system, 'Could not instantiate System');
    }

    /**
     * @test
     * @testdox Test if controller will handler errors
     * @expectedException RuntimeException
     */
    public function testBadArguments()
    {
        $system = new System('');
        $this->assertInstanceOf(self::SYSTEM, $system, 'Constructor accepting empty string');
        
        $system = new System(null);
        $this->assertInstanceOf(self::SYSTEM, $system, 'Constructor accepting null');
    }

    /**
     * @test
     * @testdox Test if uri could be got
     */
    public function getUri()
    {
        $system = $this->verifyUri('/test/test');
        $this->assertEquals('test/test', $system->getUri());

        $system = $this->verifyUri('/test/test/');
        $this->assertEquals('test/test', $system->getUri());
        
        $system = $this->verifyUri('/test/test////');
        $this->assertEquals('test/test', $system->getUri());
    }

    /**
     * @test
     * @testdox Test if action and controller was setted correctely
     */
    public function controllerAndAction()
    {
        $system = $this->verifyUri('/product/sales/var/value');
        $this->assertEquals('product', $system->getController(), 'Controller could not be setted');
        $this->assertEquals('sales', $system->getAction(), 'Action could not be setted');
    }

    /**
     * @test
     * @testdox Test if get parameter was setted correctely
     */
    public function parameters()
    {
        $system = $this->verifyUri('/product/sales/var/value');
        $this->assertTrue(
            $this->isArraysEquals(array('var' => 'value'), $_GET),
            'Assert 1: Arrays is not equal'
        );
        
        $system = $this->verifyUri('/product/sales/var/value/var2/value2');
        $this->assertTrue(
            $this->isArraysEquals(array('var' => 'value', 'var2' => 'value2'), $_GET),
            'Assert 2: Arrays is not equal'
        );
    }

    private function isArraysEquals($ar1, $ar2)
    {
        sort($ar1);
        sort($ar2);

        return $ar1 == $ar2;
    }

    private function verifyUri($expected)
    {
        $_SERVER["PATH_INFO"] = $expected;
        return new System('Controller');
    }
}
