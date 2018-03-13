<?php

namespace Resta;

use Resta\Contracts\ApplicationContracts;
use Resta\StaticPathModel;

class ApplicationProvider {

    /**
     * @var $app \Resta\Contracts\ApplicationContracts
     */
    public $app;

    /**
     * @var $url
     */
    public $url;

    /**
     * constructor.
     * @param $app \Resta\Contracts\ApplicationContracts
     */
    public function __construct(ApplicationContracts $app)
    {
        //application object
        $this->app=$app;

        //url object assign
        $this->url();

    }

    /**
     * Application Kernel.
     * @return mixed
     */
    public function app()
    {
        //symfony request
        return $this->app->kernel();
    }

    /**
     * SymfonyRequest constructor.
     * @return \Symfony\Component\HttpFoundation\Request|\Store\Services\RequestService
     */
    public function request()
    {
        //symfony request
        return $this->app()->request;
    }

    /**
     * @param $param null
     * @return mixed
     */
    public function get($param=null)
    {
        //symfony request query object
        $get=$this->app()->get;

        return ($param===null) ? $get : (isset($get[$param]) ? $get[$param] : null);
    }

    /**
     * @param $param null
     * @return mixed
     */
    public function post($param=null)
    {
        //symfony request query object
        $post=$this->app()->post;

        return ($param===null) ? $post : (isset($post[$param]) ? $post[$param] : null);
    }

    /**
     * @method url
     * @return mixed
     */
    public function url(){

        if(isset($this->app()->url)){

            //we assign the url object to the global kernel url object
            //so that it can be read anywhere in our route file.
            $this->url=$this->app->kernel()->url;
        }
    }

    /**
     * @method $class
     * @param $class
     * @return mixed
     */
    public function makeBind($class){

        return Utils::makeBind($class,$this->providerBinding());
    }

    /**
     * @method providerBinding
     * @return mixed
     */
    public function providerBinding(){

        return $this->app->applicationProviderBinding($this->app);
    }

    /**
     * @method getStatus
     * @return mixed
     */
    public function getStatus(){

        return $this->app()->responseStatus;
    }

    /**
     * @method getSuccess
     * @return mixed
     */
    public function getSuccess(){

        return $this->app()->responseSuccess;
    }

    /**
     * @method httpMethod
     * @return mixed
     */
    public function httpMethod(){

        return $this->app()->httpMethod;
    }

    /**
     * @method routeParameters
     * @return mixed
     */
    public function routeParameters(){

        return $this->app()->routeParameters;
    }

    /**
     * @method singleton
     * @return mixed
     */
    public function singleton(){

        return $this->app->singleton();
    }

    /**
     * @method container
     * @return mixed
     */
    public function container(){

        return (object)$this->app()->serviceContainer;
    }

    /**
     * @param $config null
     * @method config
     * @return mixed
     */
    public function config($config=null){

        return $this->singleton()->appClass->configLoaders($config);

    }
}