<?php

namespace Hixman\PsrContainer\Interfaces;

use Closure;
use Hixman\PsrContainer\Exceptions\ContainerException;
use Hixman\PsrContainer\Exceptions\NotFoundException;

/**
 * Interface SetContainerInterface
 * @package Hixman\PsrContainer\Interfaces
 */
interface SettableInterface
{

    /**
     * Sets an entry on the container with a callable function that resturns
     * the object that is going to be set.
     *
     * @param string $id Identifier of the entry to set.
     * @param callable $object
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function set($id, Closure $object);
}
