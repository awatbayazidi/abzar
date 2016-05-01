<?php namespace AwatBayazidi\Abzar\Provider;

use AwatBayazidi\Abzar\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\File;


abstract class BasteServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $vendor       = 'AwatBayazidi';
    protected $package      = '';
    protected $basePath     = '';
    protected $paths        = [];
    protected $multiConfigs = false;
    protected $providers    = [];
    protected $aliases      = [];
    protected $middleware      = [];
    /* ------------------------------------------------------------------------------------------------
     |  providers &  aliases
     | ------------------------------------------------------------------------------------------------
     */
    public function registerProviders()
    {
        $this->addProviders($this->providers);
    }

    public function registerAliases()
    {
        AliasLoader::getInstance($this->aliases)->register();
    }

    public function registerMiddleware()
    {
        $this->addMiddleware($this->middleware);
    }

    abstract public function getBasePath();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function register()
    {
        $this->registerProviders();
        $this->registerAliases();
        $this->registerMiddleware();
     //   $this->registerRoutes();
    }

    public function boot()
    {
        parent::boot();
        if (empty($this->package) ) {
            throw new \Exception('Enter name package');
        }
        $this->initPaths();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Package Functions
     | ------------------------------------------------------------------------------------------------
     */
    protected function init()
    {
        $this->registerConfig();
        $this->publishConfig();

        $this->registerViews();
        $this->publishViews();

        $this->registerTrans();
        $this->publishTrans();

        $this->publishAssets();

        $this->publishMigrations();
        $this->publishFactories();
        $this->publishSeeds();
    }
    protected function initViews()
    {
        $this->registerViews();
        $this->publishViews();
    }
    protected function initTrans()
    {
        $this->registerTrans();
        $this->publishTrans();
    }
    protected function initDb()
    {
        $this->publishMigrations();
        $this->publishFactories();
        $this->publishSeeds();
    }

    protected function initConfig()
    {
        $this->registerConfig();
        $this->publishConfig();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function initPaths()
    {
        $this->basePath = $this->getBasePath();
        $package = str_slug($this->package);
        $this->paths = [
            'migrations' => [
                'src'       => $this->getSourcePath('database/migrations'),
                'dest'      => database_path('migrations'),
            ],
            'factories' => [
                'src'       => $this->getSourcePath('database/factories'),
                'dest'      => database_path('factories'),
            ],
            'seeds'     => [
                'src'       => $this->getSourcePath('database/Seeds'),
                'dest'      => database_path('seeds'),
            ],
            'config'    => [
                'src'       => $this->getSourcePath('config'),
                'dest'      => config_path($package),
            ],
            'views'     => [
                'src'       => $this->getSourcePath('resources/views'),
                'dest'      => base_path('resources/views/vendor/'.$package),
            ],
            'trans'     => [
                'src'       => $this->getSourcePath('resources/lang'),
                'dest'      => base_path('resources/lang/vendor/'.$package),
            ],
            'assets'    => [
                'src'       => $this->getSourcePath('resources/assets'),
                'dest'      => public_path('vendor/'.$package),
            ],
            'assets_ex'    => [
                'src'       => $this->getSourcePath('resources/assets_ex'),
                'dest'      => public_path(),
            ],
            'routes' => [
                'src'       => $this->getSourcePath('src/Http/routes.php'),
                'dest'      => null,
            ],
        ];
        return $this;
    }

    private function getSourcePath($path)
    {
        return str_replace('/', DS, $this->basePath . DS . $path);
    }

    protected function getSrcPath($src = 'config'){
        return $this->paths[$src]['src'];
    }
    protected function getDestPath($src = 'config'){
        return $this->paths[$src]['dest'];
    }
 /* ------------------------------------------------------------------------------------------------
 |  config
 | ------------------------------------------------------------------------------------------------
 */

    protected function hasPackageConfig()
    {
        return $this->getConfigFile() !== false;
    }

    protected function getConfigKey()
    {
        return str_slug($this->package,'_');
    }

    protected function getConfigFolder()
    {
        return realpath($this->getBasePath() . DS .'config');
    }

    protected function getConfigFile()
    {
        return $this->getConfigFolder() . DS . $this->package . '.php';
    }

    protected function registerConfig($separator = '.')
    {
        if ($this->multiConfigs) {
            $this->registerMultipleConfigs($separator);
            return;
        }
        $this->mergeConfigFrom($this->getConfigFile(), $this->getConfigKey());
    }

    private function registerMultipleConfigs($separator = '.')
    {
        foreach (glob($this->getConfigFolder() . '/*.php') as $configPath) {
            $key = $this->getConfigKey() . $separator . basename($configPath, '.php');

            $this->mergeConfigFrom($configPath, $key);
        }
    }

    protected function publishConfig()
    {
        $this->publishes([
            $this->getConfigFile() => config_path("{$this->package}.php")
        ], 'config');
    }
    /* ------------------------------------------------------------------------------------------------
    |  assets
    | ------------------------------------------------------------------------------------------------
    */
    protected function publishAssets()
    {
        if (File::isDirectory($this->getSrcPath('assets'))) {
            $this->publishes([
                $this->getSrcPath('assets') => $this->getDestPath('assets')
            ], 'assets');
        }
        if (File::isDirectory($this->getSrcPath('assets_ex'))) {
            $this->publishes([
                $this->getSrcPath('assets_ex') => $this->getDestPath('assets_ex')
            ], 'assets_ex');
        }

    }

    /* ------------------------------------------------------------------------------------------------
    |  database
    | ------------------------------------------------------------------------------------------------
    */
    protected function publishMigrations()
    {
        if (File::isDirectory($this->getSrcPath('migrations'))) {
            $this->publishes([$this->getSrcPath('migrations') => $this->getDestPath('migrations')], 'migrations');
        }

    }
    protected function publishFactories()
    {
        if (File::isDirectory($this->getSrcPath('factories'))) {
            $this->publishes([$this->getSrcPath('factories') => $this->getDestPath('factories')], 'factories');
        }

    }
    protected function publishSeeds()
    {
        if (File::isDirectory($this->getSrcPath('seeds'))) {
            $this->publishes([$this->getSrcPath('seeds') => $this->getDestPath('seeds')], 'seeds');
        }

    }

    /* ------------------------------------------------------------------------------------------------
    |  trans
    | ------------------------------------------------------------------------------------------------
    */
    protected function registerTrans()
    {
        $this->loadTranslationsFrom($this->getSrcPath('trans'), $this->package);
    }
    protected function publishTrans()
    {
        if (File::isDirectory($this->getSrcPath('trans'))) {
            $this->publishes([$this->getSrcPath('trans') => $this->getDestPath('trans')], 'lang');
        }
    }
    /* ------------------------------------------------------------------------------------------------
    |  views
    | ------------------------------------------------------------------------------------------------
    */
    protected function registerViews()
    {
        $this->loadViewsFrom($this->getSrcPath('views'), $this->package);
    }

    protected function publishViews()
    {
        if (File::isDirectory($this->getSrcPath('views'))) {
            $this->publishes([
                $this->getSrcPath('views') => $this->getDestPath('views')
            ], 'views');
        }
    }
    /* ------------------------------------------------------------------------------------------------
     |  routes
     | ------------------------------------------------------------------------------------------------
     */
    protected function registerRoutes()
    {

        if(File::exists($this->getSrcPath('routes'))){
            include $this->getSrcPath('routes');
            $this->app->booted(function () {
                $this->app['events']->fire("{$this->package}::routes");
            });
        }
    }

    /**
     * Check if the environment is testing.
     *
     * @return bool
     */
    private function isTesting()
    {
        return $this->app->environment() == 'testing';
    }
}
