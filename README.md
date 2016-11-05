# psr-container

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Install

Via Composer

``` bash
$ composer require hixman/psr-container
```

## Usage

**Basic Usage**

``` php
$container =  new Container();
$container->set(UserRepository::class, function(){
    return new UserRepository();
});
$userRepositoryExits = $container->has(UserRepository::class;
$userRepository = $container->get(UserRepository::class);
```


**Delegated Repository**

Only delegated container lookup.

``` php
$container =  new Container();
$delegatedContainer = new Container();
$container->setDelegatedContainer($delegatedContainer);
$container->set(UserRepository::class, function(){
    return new UserRepository();
});
$userRepositoryExits = $container->has(UserRepository::class; // False
$userRepository = $container->get(UserRepository::class); // Throws NotFoundException
```

Delegated container and itself lookup

``` php
$container =  new Container();
$delegatedContainer = new Container();
$container->setDelegatedContainer($delegatedContainer, DelegableInterface::NOT_ONLY_DELEGATED);
$container->set(UserRepository::class, function(){
    return new UserRepository();
});
$userRepositoryExits = $container->has(UserRepository::class; // True
$userRepository = $container->get(UserRepository::class); // A new instance of UserRepository
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hixman88@gmail.com instead of using the issue tracker.

## Credits

- [Hixman][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/hixman/psr-container.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/hixman/psr-container/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/hixman/psr-container.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/hixman/psr-container
[link-travis]: https://travis-ci.org/HIXMAN/psr-container
[link-downloads]: https://packagist.org/packages/hixman/psr-container
[link-author]: https://github.com/Hixman
[link-contributors]: ../../contributors
