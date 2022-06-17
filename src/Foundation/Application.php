<?php

namespace PhpMvc\Foundation;

use PhpMvc\Container\Container;
use PhpMvc\Support\ServiceProvider;

class Application extends Container
{

    /**
     * The PhpMvc framework version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The base path for the Laravel installation.
     *
     * @var string
     */
    protected string $basePath;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected bool $booted = false;

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected array $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected array $loadedProviders = [];

    /**
     * Create a new pure application instance.
     *
     * @param string|null $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        echo "ffff";
        if ($basePath) $this->setBasePath($basePath);
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        return $this;
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @param string $path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->basePath . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }


    /**
     * Register a service provider with the application.
     *
     * @param ServiceProvider $provider
     * @param array $options
     * @param bool $force
     * @return bool|ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        ;
        if (($registered = $this->getProvider($provider))) {
            return $registered;
        }

        if (method_exists($provider, 'register')) {
            (new $provider($this))->register();
        }

        $this->markAsRegistered($provider);

        if ($this->booted) {
            $this->bootProvider($provider);
        }
        return $provider;
    }

    /**
     * @param $provider
     * @return bool
     */
    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        foreach ($this->serviceProviders as $serviceProvider)
            return $serviceProvider instanceof $name;
    }

    /**
     * Mark the given provider as registered.
     *
     * @param ServiceProvider $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[is_string($provider) ? $provider : get_class($provider)] = true;
    }

    /**
     * Boot the given service provider.
     *
     * @param ServiceProvider $provider
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return (new $provider($this))->boot();
        }
    }

    public function getServiceProvider()
    {
        return $this->serviceProviders;
    }
}