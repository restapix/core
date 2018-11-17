<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Middleware\ExcludeMiddleware;

class Middleware extends ApplicationProvider
{
    /**
     * @return void
     */
    public function setMiddleware()
    {
        //We are logging the kernel for the middleware class and the exclude class.
        $this->singleton()->middlewareClass     = $this->makeBind(app()->namespace()->serviceMiddleware());
        $this->singleton()->excludeClass        = $this->makeBind(ExcludeMiddleware::class);
    }

    /**
     * @param $middleValue
     */
    public function pointer($middleValue)
    {
        if(isset(resta()->pointer['middlewareList'])){

            $middlewareList = resta()->pointer['middlewareList'];

            if(is_array($middlewareList)){
                $middlewareList = array_merge($middlewareList,[$middleValue]);
                $this->register('pointer','middlewareList',$middlewareList);
            }
        }
        else{
            $this->register('pointer','middlewareList',[$middleValue]);
        }
    }

}