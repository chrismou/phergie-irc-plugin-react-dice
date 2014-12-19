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
     * @var \Chrismou\Phergie\Plugin\Dice\Plugin
     */
    protected $plugin;

    /**
     * @var \Phergie\Irc\Plugin\React\Command\CommandEvent
     */
    protected $event;

    /**
     * @var \Phergie\Irc\Bot\React\EventQueueInterface
     */
    protected $queue;

    protected function setUp()
    {
        $this->plugin = new Plugin();
        $this->event = $this->getMockEvent();
        $this->queue = $this->getMockQueue();
    }

    /**
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $this->assertInternalType('array', $this->plugin->getSubscribedEvents());
    }

    public function testHandleCommand()
    {
        Phake::when($this->event)->getCustomCommand()->thenReturn("dice");
        Phake::when($this->event)->getCustomParams()->thenReturn(array("5"));
        $this->plugin->handleCommand($this->event, $this->queue);

        foreach ((array)$helpLines as $responseLine) {
            Phake::verify($this->queue)->ircPrivmsg('#channel', $responseLine);
        }
    }

    /**
     * Returns a mock command event.
     *
     * @return \Phergie\Irc\Plugin\React\Command\CommandEvent
     */
    protected function getMockEvent()
    {
        return Phake::mock('\Phergie\Irc\Plugin\React\Command\CommandEvent');
    }

    /**
     * Returns a mock event queue.
     *
     * @return \Phergie\Irc\Bot\React\EventQueueInterface
     */
    protected function getMockQueue()
    {
        return Phake::mock('\Phergie\Irc\Bot\React\EventQueueInterface');
    }
}
