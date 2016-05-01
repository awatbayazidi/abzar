<?php namespace AwatBayazidi\Abzar\Provider;

use AwatBayazidi\Abzar\ServiceProvider;
use Illuminate\Support\Str;

abstract class CommandServiceProvider extends ServiceProvider
{
    protected $vendor   = 'AwatBayazidi';
    protected $package  = '';
    protected $commands = [];
    protected $defer = true;

    protected function getPrefix()
    {
        if (empty($this->package) ) {
            throw new \Exception('Enter name package');
        }
        $prefix =$this->package;
        if ( ! empty($this->vendor)) {
            $prefix = "{$this->vendor}\\{$prefix}";
        }
        return $prefix;
    }

    protected function getNamespace()
    {
        if (empty($this->package) ) {
            throw new \Exception('Enter name package');
        }
        $prefix =Str::ucfirst($this->package);
        if ( ! empty($this->vendor)) {
            $prefix = "{$this->vendor}\\{$prefix}";
        }
        return  "{$prefix}\\Commands\\";
    }

    public function register()
    {
        foreach ($this->commands as $command) {
            $this->commands($this->getNamespace().$command);
        }
    }

    public function provides()
    {
        $provides = [];
        foreach ($this->commands as $command) {
            $provides[] = $this->getNamespace().$command;
        }
        return $provides;
    }

}
