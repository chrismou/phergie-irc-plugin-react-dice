<?php
/**
 * Phergie plugin for provides randomly generated numbers in response to dice rolling requests (https://github.com/chrismou/phergie-irc-plugin-react-dice)
 *
 * @link https://github.com/chrismou/phergie-irc-plugin-react-dice for the canonical source repository
 * @copyright Copyright (c) 2014 Chris Chrisostomou (http://mou.me)
 * @license http://phergie.org/license New BSD License
 * @package Chrismou\Phergie\Plugin\Dice
 */

namespace Chrismou\Phergie\Tests\Plugin\Dice;

use Phake;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;
use Chrismou\Phergie\Plugin\Dice\Plugin;

/**
 * Tests for the Plugin class.
 *
 * @category Chrismou
 * @package Chrismou\Phergie\Plugin\Dice
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin;
        $this->assertInternalType('array', $plugin->getSubscribedEvents());
    }
}
