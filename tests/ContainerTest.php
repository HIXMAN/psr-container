<?php

namespace Hixman\PsrContainer;

use stdClass;
use DateTime;
use Hixman\PsrContainer\Exceptions\NotFoundException;
use Hixman\PsrContainer\Interfaces\DelegableInterface;
use Hixman\PsrContainer\Interfaces\ContainerInterface;
use Hixman\PsrContainer\Exceptions\ContainerException;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testContainerIsCreated()
    {
        //
        //
        $container = new Container([]);
        //
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testContainerHasMethod()
    {
        //
        $itemsContainer = [
            DateTime::class => function () {
                return new DateTime();
            }
        ];
        $container = new Container($itemsContainer);
        //
        $itemDateTimeExists = $container->has(DateTime::class);
        $itemStdClassExists = $container->has(stdClass::class);
        //
        $this->assertTrue($itemDateTimeExists);
        $this->assertFalse($itemStdClassExists);
    }

    public function testContainerHasMethodWhenOnlyDelegatedContainer()
    {
        //
        $itemsContainer = [
            DateTime::class => function () {
                return new DateTime();
            }
        ];
        $itemsDelegatedContainer = [
            stdClass::class => function () {
                return new stdClass();
            }
        ];
        $container = new Container($itemsContainer);
        $delegatedContainer = new Container($itemsDelegatedContainer);
        $container->setDelegatedContainer($delegatedContainer);
        //
        $itemStdClassExists = $container->has(stdClass::class);
        $itemDateTimeExists = $container->has(DateTime::class);
        //
        $this->assertTrue($itemStdClassExists);
        $this->assertFalse($itemDateTimeExists);
    }

    public function testContainerHasMethodWhenNotOnlyDelegatedContainer()
    {
        //
        $itemsContainer = [
            DateTime::class => function () {
                return new DateTime();
            }
        ];
        $itemsDelegatedContainer = [
            stdClass::class => function () {
                return new stdClass();
            }
        ];
        $container = new Container($itemsContainer);
        $delegatedContainer = new Container($itemsDelegatedContainer);
        $container->setDelegatedContainer($delegatedContainer, DelegableInterface::NOT_ONLY_DELEGATED);
        //
        $dateTimeExists = $container->has(DateTime::class);
        $stdClassExists = $container->has(stdClass::class);
        //
        $this->assertTrue($stdClassExists);
        $this->assertTrue($dateTimeExists);
    }

    public function testContainerGetMethod()
    {
        //
        $items = [
            stdClass::class => function () {
                return new stdClass();
            }
        ];
        $expectedStdClass = new stdClass();
        $container = new Container($items);
        //
        $stdClass = $container->get(stdClass::class);
        //
        $this->assertEquals($expectedStdClass, $stdClass);
    }

    public function testContainerGetMethodWhenNotOnlyDelegatedContainer()
    {
        //
        $itemsContainer = [
            DateTime::class => function () {
                return new DateTime();
            }
        ];
        $itemsDelegatedContainer = [
            stdClass::class => function () {
                return new stdClass();
            }
        ];
        $expectedStdClass = new stdClass();
        $expectedDateTime = new DateTime();
        $container = new Container($itemsContainer);
        $delegatedContainer = new Container($itemsDelegatedContainer);
        $container->setDelegatedContainer($delegatedContainer, DelegableInterface::NOT_ONLY_DELEGATED);
        //
        $stdClass = $container->get(stdClass::class);
        $dateTime = $container->get(DateTime::class);
        //
        $this->assertEquals($expectedStdClass, $stdClass);
        $this->assertEquals($expectedDateTime, $dateTime);
    }

    public function testContainerGetMethodWhenOnlyDelegatedContainer()
    {
        //
        $itemsContainer = [
            DateTime::class => function () {
                return new DateTime();
            }
        ];
        $itemsDelegatedContainer = [
            stdClass::class => function () {
                return new stdClass();
            }
        ];
        $expectedItemStandart = new stdClass();
        $container = new Container($itemsContainer);
        $delegatedContainer = new Container($itemsDelegatedContainer);
        $container->setDelegatedContainer($delegatedContainer);
        //
        $itemStandart = $container->get(stdClass::class);
        //
        $this->assertEquals($expectedItemStandart, $itemStandart);
    }

    public function testContainerSetMethod()
    {
        //
        $container = new Container();
        $expectedStdClass = new stdClass();
        //
        $container->set(stdClass::class, function () {
            return new stdClass();
        });
        //
        $StdClass = $container->get(stdClass::class);
        $this->assertEquals($StdClass, $expectedStdClass);
    }

    public function testSetDelegateContainerMethod()
    {
        //
        $container = new Container();
        $delegatedContainer = new Container();
        //
        $container->setDelegatedContainer($delegatedContainer, DelegableInterface::NOT_ONLY_DELEGATED);
        //
        $this->assertAttributeInstanceOf(ContainerInterface::class, 'delegatedContainer', $container);
        $this->assertAttributeEquals(DelegableInterface::NOT_ONLY_DELEGATED, 'onlyDelegated', $container);
    }

    public function testContainerExceptionThrownWhenCreatingInstance()
    {
        //
        $items = [
            stdClass::class => function () {
                throw new \Exception('Exception thrown when creating instance');
            },
        ];
        $container = new Container($items);
        //
        $this->expectException(ContainerException::class);
        $container->get(stdClass::class);
        //
    }

    public function testNotFoundExceptionThrownNoIdFound()
    {
        //
        $container = new Container();
        //
        $this->expectException(NotFoundException::class);
        $container->get(stdClass::class);
        //
    }
}
