<?php namespace AwatBayazidi\Abzar;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as Provider;

abstract class ServiceProvider extends Provider
{

    private   $aliasLoader;


    public function __construct(Application $app)
    {
        Provider::__construct($app);
        $this->aliasLoader = AliasLoader::getInstance();
    }



    public function addProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }


    public function addMiddleware(array $middleware)
    {
        foreach ($middleware as $key => $value) {
            $this->app['router']->middleware($key,$value);
        }
    }


    public function boot()
    {
     //
    }


    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->app->bind($abstract, $concrete, $shared);
    }



    protected function singleton($abstract, $concrete = null)
    {
        $this->app->singleton($abstract, $concrete);
    }



    protected function addFacades(array $aliases)
    {
        foreach ($aliases as $alias => $facade) {
            $this->addFacade($alias, $facade);
        }
        return $this;
    }



    protected function addFacade($alias, $facade)
    {
        $this->aliasLoader->alias($alias, $facade);
    }

}
