# Dice rolling plugin for [Phergie](http://github.com/phergie/phergie-irc-bot-react/)

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for returning randomly generated numbers in response to dice rolling requests.

[![Build Status](https://img.shields.io/travis/chrismou/phergie-irc-plugin-react-dice/master.svg?style=flat-square)](https://travis-ci.org/chrismou/phergie-irc-plugin-react-dice)
[![Test Coverage](https://codeclimate.com/github/chrismou/phergie-irc-plugin-react-dice/badges/coverage.svg)](https://codeclimate.com/github/chrismou/phergie-irc-plugin-react-dice/coverage)
[![Code Climate](https://codeclimate.com/github/chrismou/phergie-irc-plugin-react-dice/badges/gpa.svg)](https://codeclimate.com/github/chrismou/phergie-irc-plugin-react-dice)
[![Buy me a beer](https://img.shields.io/badge/donate-PayPal-019CDE.svg)](https://www.paypal.me/chrismou)

## About

This plugin returns the total of a user specified number of dice rolls.

By default, the plugin responds to "dice <number of dice> <number of sides on each die>" (number of sides is optional, defaults to 6); i.e. dice 5 returns the total of five 6-sided die rolls.

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```
composer require chrismou/phergie-irc-plugin-react-dice
```

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration
To activate the plugin using the default settings, add the following to your phergie config:

```php
new \Chrismou\Phergie\Plugin\Dice\Plugin
```

You can configure some of the settings as follows:

```php
new \Chrismou\Phergie\Plugin\Dice\Plugin(
    "defaultSides": 6,      // The number of sides on the dice if excluded from the command
    "maxRolls": 50,         // Maximum number of dice
    "maxSides": 1000,       // Maximum number of sides per dice
    "showSums": true        // Show the sums in the response
)
```

## Tests

To run the unit test suite:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit
```

## License

Released under the BSD License. See `LICENSE`.
