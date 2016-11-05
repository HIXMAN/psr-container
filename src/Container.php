<?php

namespace Hixman\PsrContainer;

use Hixman\PsrContainer\Exceptions\ContainerException;
use Hixman\PsrContainer\Exceptions\NotFoundException;
use Hixman\PsrContainer\Interfaces\ContainerInterface;
use Hixman\PsrContainer\Interfaces\DelegableInterface;
use Hixman\PsrContainer\Interfaces\SettableInterface;

/**
 * Class Container
 * @package Hixman\PsrContainer
 */
class Container implements
    ContainerInterface,
    SettableInterface,
    DelegableInterface
{

    /**
     * @var array
     */
    protected $container = null;
    /**
     * @var ContainerInterface
     */
    protected $delegatedContainer = null;
    /**
     * @var bool
     */
    protected $onlyDelegated = null;

    /**
     * Container constructor.
     * @param array $container
     */
    public function __construct($container = [])
    {
        $this->container = $container;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        $instance = false;
        if ($this->delegatedContainer) {
            $instance = $this->getInstanceFromDelegatedContainer($id);
        }
        if (false === $instance) {
            if ($this->has($id)) {
                $instance = $this->getInstanceFromItself($id);
            }
        }
        if ($instance) {
            return $instance;
        }
        throw new NotFoundException("$id is not set in container");
    }

    /**
     * Sets an entry on the container with a callable function that resturns
     * the object that is going to be set.
     *
     * @param string $id Identifier of the entry to set.
     * @param callable $callable
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function set($id, callable $callable)
    {
        $this->container[$id] = $callable;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundException`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        $exists = false;
        if ($this->delegatedContainer) {
            $exists = $this->delegatedContainer->has($id);
        }
        if ($exists) {
            return true;
        }
        if ($this->onlyDelegated) {
            return false;
        }
        return array_key_exists($id, $this->container);
    }

    /**
     * Sets delegated container.
     * @param ContainerInterface $container
     * @param bool $onlyDelegated
     * @return null
     */
    public function setDelegatedContainer(ContainerInterface $container, $onlyDelegated = true)
    {
        $this->delegatedContainer = $container;
        $this->onlyDelegated = $onlyDelegated;
    }

    /**
     * Gets an instance form a id associated callable given
     * @param $id
     * @return mixed
     * @throws ContainerException
     */
    private function getInstanceFromItself($id)
    {
        try {
            $instance = $this->container[$id]();
        } catch (\Exception $exception) {
            throw new ContainerException($exception->getMessage());
        }
        return $instance;
    }

    /**
     * Gets an instance from a delegated container
     * @param $id
     * @return bool|mixed
     */
    private function getInstanceFromDelegatedContainer($id)
    {
        $instance = false;
        if ($this->delegatedContainer->has($id)) {
            $instance = $this->delegatedContainer->get($id);
        }
        return $instance;
    }
}
