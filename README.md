# Dice rolling plugin for [Phergie](http://github.com/phergie/phergie-irc-bot-react/)

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for returning randomly generated numbers in response to dice rolling requests.

[![Build Status](https://scrutinizer-ci.com/g/chrismou/phergie-irc-plugin-react-dice/badges/build.png?b=master)](https://scrutinizer-ci.com/g/chrismou/phergie-irc-plugin-react-dice/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/chrismou/phergie-irc-plugin-react-dice/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chrismou/phergie-irc-plugin-react-dice/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chrismou/phergie-irc-plugin-react-dice/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chrismou/phergie-irc-plugin-react-dice/?branch=master)

## About

This plugin returns the total of a user specified number of dice rolls.

By default, the plugin responds to "dice <number of dice> <number of sides on each die>" (number of sides is optional, defaults to 6); i.e. dice 5 returns the total of five 6-sided die rolls.

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```
composer require chrismou/phergie-irc-plugin-react-google
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
