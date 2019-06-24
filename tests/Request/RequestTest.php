<?php

namespace Resta\Core\Tests\Request;

use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Request\Data\User\UserRequest;
use ReflectionException as ReflectionExceptionAlias;

class ConfigTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * test request 1
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest1()
    {
        $this->expectException(\UnexpectedValueException::class);
        new UserRequest(['a'=>'b']);
    }

    /**
     * test request 2
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest2()
    {
        $this->expectException(\InvalidArgumentException::class);
        new UserRequest(['username'=>'b']);
    }

    /**
     * test request 3
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest3()
    {
        $request = new UserRequest(['username'=>'aligurbuz']);
        $this->assertSame(['username'=>'aligurbuz'],$request->all());
    }

    /**
     * test request 4
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest4()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('username input value is not valid for min6Char request rule');
        new UserRequest(['username'=>'ali']);
    }

    /**
     * test request 5
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest5()
    {
        $request = new UserRequest(['username'=>'aligurbuz','password'=>'123456']);
        $inputs = $request->all();
        $username = (isset($inputs['username'])) ? true : false;
        $password = (isset($inputs['password'])) ? true : false;
        $this->assertSame(true,$username);
        $this->assertSame(true,$password);
        $this->assertSame(32,strlen($inputs['password']));
    }

    /**
     * test request 6
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest6()
    {
        $request = new UserRequest(['username'=>'aligurbuz','page'=>1]);
        $inputs = $request->all();
        $page = (isset($inputs['page'])) ? true : false;
        $this->assertSame(true,$page);
    }

    /**
     * test request 6
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest7()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('page input value is not valid as format (^\d+$)');
        new UserRequest(['email'=>'resta@gmail.com','page'=>'x']);
    }
}