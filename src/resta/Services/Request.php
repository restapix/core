<?php

namespace Resta\Services;

use Resta\Contracts\HandleContracts;
use Resta\Utils;
use Store\Services\RequestClient;

class Request extends RequestClient implements HandleContracts {

    /**
     * @var array $origin
     */
    public $origin=[];

    /**
     * @var array $inputs
     */
    protected $inputs=[];

    /**
     * @var array $except
     */
    protected $except=[];

    /**
     * @var $capsule
     */
    protected $capsule;

    /**
     * RequestClient constructor.
     */
    public function __construct() {

       if(property_exists($this,'app') && $this->app){
           parent::handle();
       }
       else{
           $this->handle();
       }

    }

    /**
     * @return void
     */
    public function handle(){

        //get http method
        $method=appInstance()->httpMethod();

        //we record the values ​​
        //that coming with the post.
        $this->initClient($method);

        //we update the input values ​​after we receive and check the saved objects.
        foreach ($this->getClientObjects() as $key=>$value){

            if($method($key)!==null){

                $this->inputs[$key]=$value;

                if($value===null){
                    $this->{$key}=$method($key);
                    $this->inputs[$key]=$this->{$key};
                }


                //if there is method for key
                $requestMethod=$method.''.ucfirst($key);
                if(method_exists($this,$requestMethod)){
                    $this->inputs[$key]=$this->{$requestMethod}();
                }

                if(method_exists($this,$key)){
                    $this->inputs[$key]=$this->{$key}();
                }
            }
        }

        $this->checkHttpMethod($method);

        $this->capsule();

        $this->validation();

        $this->autoInjection();

        $this->fakerManager();

        $this->expectedInputs();


    }

    private function fakerManager(){

        if(method_exists($this,'faker')){

            $this->fakerMethod($this->faker());

        }
        else{

            if(property_exists($this,'faker') && is_array($this->faker) && count($this->faker)){

                $this->fakerMethod($this->faker);
            }

        }
    }

    /**
     * @param $faker
     */
    private function fakerMethod($faker){

        foreach ($faker as $fake){

            $fakerMethodName=$fake.'Faker';

            if(method_exists($this,$fakerMethodName)){

                if(!isset($this->inputs[$fake])){
                    $this->inputs[$fake]=$this->{$fakerMethodName}();
                }
            }

        }

    }

    /**
     * @return void|mixed
     */
    private function expectedInputs(){

        if(property_exists($this,'expected') && is_array($this->expected) && count($this->expected)){

           foreach ($this->expected as $expected){
               if(!isset($this->inputs[$expected])){
                   exception()->invalidArgument('You absolutely have to send the value '.$expected);
               }
           }
        }
    }

    /**
     * @param $method
     * @return void|mixed
     */
    private function checkHttpMethod($method){

        if(property_exists($this,'http') && is_array($this->http) && count($this->http)){
            if(!in_array($method,$this->http)){
                exception()->badMethodCall('Invalid http method process for '.class_basename($this).'. it is accepted http methods ['.implode(",",$this->http).'] ');
            }
        }
    }

    /**
     * @return array
     */
    private function getClientObjects(){
        return array_diff_key($this->getObjects(),['inputs'=>[]]);
    }

    /**
     * @return array
     */
    private function getObjects(){
        return get_object_vars($this);
    }

    /**
     * @method initClient
     * @param $method
     * @return void
     */
    private function initClient($method){
        foreach($method() as $key=>$value){
            $this->inputs[$key]=$value;
            $this->origin[$key]=$value;
        }
    }

    /**
     * @return array
     */
    protected function get(){
        return $this->inputs;
    }

    /**
     * @return void
     */
    protected function autoInjection(){

        $getObjects     = $this->getObjects();
        $autoInject     = $getObjects['autoInject'];

        if(count($autoInject)){
            foreach($autoInject as $key=>$method){
                $autoMethod='auto'.ucfirst($method);
                if(method_exists($this,$autoMethod)){
                    $this->inputs[$method]=$this->{$autoMethod}();
                }

            }
        }

    }

    /**
     * @param $except
     * @return $this
     */
    public function except($except){

        if(is_callable($except)){
            $call=call_user_func_array($except,[$this]);
            $except=$call;
        }

        $this->except=array_merge($this->except,$except);
        $this->inputs=array_diff_key($this->inputs,array_flip($this->except));
        return $this;
    }

    /**
     * @return void
     */
    private function capsule(){

        if(is_array($this->capsule) && count($this->capsule)){

            foreach ($this->inputs as $key=>$value){
                if(!in_array($key,$this->capsule)){
                    exception()->invalidArgument($key .' input  as value sent is not invalid ');
                }
            }

            if(Utils::isArrayEqual(array_keys($this->inputs),$this->capsule)===false){
                exception()->invalidArgument('the values accepted by the server are not the same with values you sent');
            }
        }
    }

    /**
     * @return void
     */
    private function validation(){

        if(method_exists($this,'rule')){
            $this->rule();
        }

        $validName=strtolower(str_replace('Request','',class_basename($this))).'Rule';

        if(method_exists($this,$validName)){
            $this->{$validName}();
        }
    }

}