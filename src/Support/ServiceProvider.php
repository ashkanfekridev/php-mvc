<?php


namespace PhpMvc\Support;


use PhpMvc\Foundation\Application;

abstract class ServiceProvider
{
    /**
     * The application instance.
     * @var Application
     */
    protected $app;

    /**
     * Create a new service provider instance.
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {}

    /**
     * Register the service provider.
     *
     * @return void
     */
    abstract public function register();

}