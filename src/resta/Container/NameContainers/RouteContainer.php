<?php

namespace Resta\Container\NameContainers;

use Resta\Support\Str;
use Resta\Utils;
use Resta\GlobalLoaders\Container;

class RouteContainer
{
    /**
     * @var array $parameters
     */
    protected $parameters=array();

    /**
     * @param $parameters
     */
    private function checkRouteObligation($parameters)
    {
        //check $parameters value for obligation
        foreach ($parameters as $parameterKey=>$parameter){

            // we use a question mark as a constant value which is not a necessity.
            // according to the presence of this sign,
            // we take this mark as a parameter to save the value as a real value.
            $this->parameters[]=Str::replaceArray('?',[''],$parameter);

            // we take all the route values
            // ​​into the allRoutes variable.
            $allRoutes=route();

            // the values ​​that do not end with the question mark
            // as mandatory values ​​will be required as parameters.
            if(!Str::endsWith($parameter,'?')){

                // if the requirement is not in the true route value,
                // the exception will be thrown with the missing parameter message.
                if(!isset($allRoutes[$parameterKey])){
                    exception()->invalidArgument('Route parameter is missing [ as '.$parameterKey.' key ]');
                }
            }
        }
    }

    /**
     * @param $parameters
     * @param $param
     * @return mixed
     */
    public function resolveContainer($parameters,$param)
    {

        // we apply this method to obtain reliable
        // route data by checking the route requirement.
        $this->checkRouteObligation($parameters);

        //get service configuration params for route
        $serviceConf = Utils::getServiceConf();

        // we get the container global object with
        // the help of global loaders and register the route container.
        $containerGlobalLoaders=app()->makeBind(Container::class);
        $containerGlobalLoaders->routeContainer($this->parameters);

        //route helper method
        $param['route']=route();

        // the user will determine
        // if the route parameters are in
        // accordance with the regular expression rule as a pattern.
        $this->routePatternProcess($serviceConf,$param['route']);

        // when the route nameContainer is defined,
        // these keys must be absolute string data.
        $this->shouldBeStringRouteValues();

        //return $param
        return $param;
    }

    /**
     * @param $serviceConf
     * @param $params
     */
    private function routePatternProcess($serviceConf,$params)
    {
        if(isset($serviceConf['routeParameters'])){

            $routeParameters = $serviceConf['routeParameters'];

            $pattern = [];
            if(isset($routeParameters[strtolower(httpMethod)][methodName]['pattern'])){
                $pattern = $routeParameters[strtolower(httpMethod)][methodName]['pattern'];
            }

            foreach ($params as $key=>$param){

                if(isset($pattern[$key])){

                    if(!preg_match('@'.$pattern[$key].'$@is',$param)){
                        exception()
                            ->invalidArgument('route id value is not valid for configuration as pattern');
                    }
                }
            }
        }
    }


    /**
     * @return mixed|void
     */
    private function shouldBeStringRouteValues()
    {
        // we pass all the key values ​​of
        // the route data through the string control.
        foreach (route() as $routeKey=>$routeValue){

            if(in_array($routeKey,$this->parameters) && preg_match('@[^a-z||\?]@',$routeKey)){
                exception()->invalidArgument('Route key should be only string value');
            }
        }
    }

}