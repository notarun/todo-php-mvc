<?php

namespace Core;

use Exception;

class Container
{
    /**
     * Stores dependency class objects.
     *
     * @var array
     */
    public $containers = [];

    /**
     * Load dependencies from containers file.
     *
     * @param string $file
     * @return Container
     */
    public static function load(string $file): Container
    {
        $container = new static;
        require $file;
        return $container;
    }

    /**
     * Add new dependency inside container.
     *
     * @param string $dependency
     * @param $object
     * @return void
     */
    public function add(string $dependency, $object)
    {
        $this->containers[$dependency] = $object;
    }

    /**
     * Returns the requested dependency object.
     *
     * @param string $dependency
     */
    public function get(string $dependency)
    {
        if (!array_key_exists($dependency, $this->containers)) {
            throw new Exception("No dependency found with name the ${dependency}");
        }

        return $this->containers[$dependency];
    }
}
