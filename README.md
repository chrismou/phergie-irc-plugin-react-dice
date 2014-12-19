# chrismou/phergie-irc-plugin-react-dice

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for provides randomly generated numbers in response to dice rolling requests.

[![Build Status](https://secure.travis-ci.org/chrismou/phergie-irc-plugin-react-dice.png?branch=master)](http://travis-ci.org/chrismou/phergie-irc-plugin-react-dice)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "chrismou/phergie-irc-plugin-react-dice": "dev-master"
    }
}
```

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration

```php
new \Chrismou\Phergie\Plugin\Dice\Plugin
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
