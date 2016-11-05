<?php

namespace Hixman\PsrContainer\Interfaces;

/**
 * Interface DelegableInterface
 * @package Hixman\PsrContainer\Interfaces
 */
interface DelegableInterface
{

    /**
     *  Container only has to check delegated container.
     */
    const ONLY_DELEGATED = true;
    /**
     *  Container has to check delegated container and itself.
     */
    const NOT_ONLY_DELEGATED = false;

    /**
     * Sets delegated container.
     * @param ContainerInterface $container
     * @param bool $onlyDelegated
     * @return null
     */
    public function setDelegatedContainer(ContainerInterface $container, $onlyDelegated = true);
}
