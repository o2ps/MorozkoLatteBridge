# Oops/MorozkoLatteBridge

:warning: **THIS PACKAGE IS NO LONGER MAINTAINED.** You can use the AdvancedCache module from [contributte/console-extra](https://github.com/contributte/console-extra) instead.

[![Build Status](https://img.shields.io/travis/o2ps/MorozkoLatteBridge.svg)](https://travis-ci.org/o2ps/MorozkoLatteBridge)
[![Downloads this Month](https://img.shields.io/packagist/dm/oops/morozko-latte-bridge.svg)](https://packagist.org/packages/oops/morozko-latte-bridge)
[![Latest stable](https://img.shields.io/packagist/v/oops/morozko-latte-bridge.svg)](https://packagist.org/packages/oops/morozko-latte-bridge)

This package provides a [Latte](https://latte.nette.org) templates cache warmer for [Morozko](https://github.com/o2ps/Morozko).


## Installation and requirements

```bash
$ composer require oops/morozko-latte-bridge
```

Oops/MorozkoLatteBridge requires PHP >= 7.1.

This bridge requires that Morozko is set up correctly; please refer to its README for instructions.


## Usage

Register the extension in your config file, along with Morozko and a Symfony/Console integration:

```yaml
extensions:
    morozko: Oops\Morozko\DI\MorozkoExtension
    morozko.latte: Oops\MorozkoLatteBridge\DI\MorozkoLatteBridgeExtension
    console: Kdyby\Console\DI\ConsoleExtension

morozko.latte:
    directory: %appDir% # <- this is the default
```

When you run the `oops:morozko:warmup` command, Latte cache warmer will search for any `*.latte` files within the configured directory, and pre-compile them.
