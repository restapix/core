<?php

namespace Resta\Container;

class ContainerInstanceResolver
{
    /**
     * @var $instances
     */
    protected $instances;

    /**
     * ContainerInstanceResolver constructor.
     * @param $instances
     */
    public function __construct($instances)
    {
        //get container instances
        $this->instances = $instances;
    }

    /**
     * reflection resolve for instance
     *
     * @return mixed
     */
    public function reflection()
    {
        return app()->resolve($this->instances[__FUNCTION__]);
    }

    /**
     * container resolve for instance
     *
     * @return array
     */
    public function container($name=null)
    {
        //check container value for kernel
        if(isset($this->instances['container'])){

            if($name===null){
                return (array)$this->instances['container'];
            }

            if(isset($this->container()[$name])){
                return $this->container()[$name];
            }

        }
        return [];
    }

    /**
     * @param $name
     * @param $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        //we call container instance as data
        return $this->instances[$name] ?? $this->container($name);
    }
}