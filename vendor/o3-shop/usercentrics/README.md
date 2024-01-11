# O3-Shop Cookie Management powered by usercentrics

[![Packagist](https://img.shields.io/packagist/v/o3-shop/usercentrics.svg)](https://packagist.org/packages/o3-shop/usercentrics)

This module provides the [Usercentrics](https://usercentrics.com/de/preise/?partnerid=o3partnerid#business-paket) functionality for the [O3-Shop](https://www.o3-shop.com/) allowing you to use their Consent Management Platform.

## Usage

This assumes you have O3-Shop (at least the `v1.0.0` compilation) up and running.

### Install

The Usercentrics module is already included in the O3-Shop `v1.0.0` compilation.

Module can be installed manually, by using composer:
```bash
$ composer require o3-shop/usercentrics
$ vendor/bin/oe-console oe:module:install source/modules/oxps/usercentrics
```

After requiring the module, you need to activate it, either via O3-Shop admin or CLI.

Navigate to shop folder and execute the following: 
```bash
$ vendor/bin/oe-console oe:module:activate oxps_usercentrics
```

### How to use

Activate the module and enter your usercentrics ID in the module settings.

User documentation: [DE](https://docs.o3-shop.com/modules/usercentrics/de/latest/)

## Branch Compatibility

* master branch for master shop compilation branches
* b-6.5.x branch for b-6.5.x shop compilation branches
* b-6.3.x branch for b-6.3.x and b-6.4.x shop compilation branches
* b-6.2.x branch for b-6.2.x shop compilation branches

## Developer installation

```bash
$ git clone https://github.com/o3-shop/usercentrics.git source/modules/oxps/usercentrics
$ composer config repositories.o3-shop/usercentrics path ./source/modules/oxps/usercentrics
$ composer require o3-shop/usercentrics:*

$ vendor/bin/oe-console oe:module:install source/modules/oxps/usercentrics
```

## Testing

Modify the `test_config.yml` configuration:

```
    ...
    partial_module_paths: oxps/usercentrics
    ...
    activate_all_modules: true
    run_tests_for_shop: false
    run_tests_for_modules: true
    ...
```

Then tests can be run like this:

```bash
$ ./vendor/bin/runtests
$ SELENIUM_SERVER_IP=selenium BROWSER_NAME=chrome ./vendor/bin/runtests-codeception
```

## Contributing

You like to contribute? 🙌 AWESOME 🙌\
Go and check the [contribution guidelines](CONTRIBUTING.md)

## Issues

To report issues with the module, please use the [O3-Shop bugtracking system](https://bugs.o3-shop.com/) - module Usercentrics project.

## License

GPLv3, see [LICENSE file](LICENSE).
