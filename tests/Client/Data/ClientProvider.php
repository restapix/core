<?php

namespace Resta\Core\Tests\Client\Data;

use Resta\Client\Client as RequestClient;

class ClientProvider extends RequestClient implements \ArrayAccess {

    /**
     * get request input all
     *
     * @return array
     */
    public function all()
    {
        return $this->inputs;
    }

    /**
     * @return array
     */
    public function generatorList()
    {
        return $this->generatorList;
    }

    /**
     * check if the exist the request input
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return (isset($this->inputs[$key])) ? true : false;
    }

    /**
     * get input for request
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function input($key, $default=null)
    {
        if(isset($this->inputs[$key])){
            return $this->inputs[$key];
        }

        return $default;
    }

    /**
     * remove input for request
     *
     * @param $key
     */
    public function remove($key)
    {
        if(isset($this->inputs[$key])){
            unset($this->inputs[$key]);
        }
    }

    /**
     * take real request for client
     * @return array
     */
    public function request()
    {
        return $this->getRequestData();
    }

    /**
     * set a new input value
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key,$value)
    {
        $this->inputs[$key] = $value;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        //
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetUnset($offset)
    {
        //
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset) {

        $request = $this->all();
        if(isset($request[$offset])){
            return $request[$offset];
        }
        return null;
    }

    /**
     * Dynamically access container services.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Dynamically set container services.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }

}