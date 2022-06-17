<?php

namespace PhpMvc\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected static $instance;

    protected array $instances = [];
    protected array $bindings = [];


    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function bound($abstract)
    {

    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }

    public function get(string $id)
    {
        try {
            if (!$this->has($id)) {
                return false;
            } elseif (is_callable($this->bindings[$id])) {
                return $this->bindings[$id]($this);
            }



        } catch (\ReflectionException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }


    public function set($id, $concrete)
    {
        $this->bindings[$id] = $concrete;
    }


}