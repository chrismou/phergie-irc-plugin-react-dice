<?php
/**
 * Phergie plugin for returning randomly generated numbers in response to dice rolling requests
 *
 * @link https://github.com/chrismou/phergie-irc-plugin-react-dice for the canonical source repository
 * @copyright Copyright (c) 2015 Chris Chrisostomou (https://mou.me)
 * @license http://phergie.org/license New BSD License
 * @package Chrismou\Phergie\Plugin\Dice
 */

namespace Chrismou\Phergie\Tests\Plugin\Dice;

use Phake;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;
use Chrismou\Phergie\Plugin\Dice\Plugin;

/**
 * Tests for the Plugin class
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
        $this->plugin = $this->getPlugin();
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

    /**
     * Test the main command handler
     */
    public function testHandleCommand()
    {
        Phake::when($this->event)->getSource()->thenReturn('#channel');
        Phake::when($this->event)->getCommand()->thenReturn('PRIVMSG');
        Phake::when($this->event)->getCustomCommand()->thenReturn("dice");
        Phake::when($this->event)->getCustomParams()->thenReturn(array("5"));

        // Manually seed mt_rand
        mt_srand(0);
        $this->plugin->handleCommand($this->event, $this->queue);

        $response = $this->plugin->generateResponse($this->event, 20, array(5, 1, 4, 6, 4));
        Phake::verify($this->queue)->ircPrivmsg('#channel', $response);
    }

    /**
     * Test the param validation with default and custom config settings
     */
    public function testValidation()
    {
        $defaultPlugin = $this->getPlugin();
        $configPlugin = $this->getPlugin(array(
            "maxSides" => 3,
            "maxRolls" => 3,
            "defaultSides" => 2,
            "showSums" => false,
        ));

        /* generic validation tests */
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array(1, 0));
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array(51, 10));
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array(6, 'a'));
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array('a', 6));
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array());

        /* default plugin settings */
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array(6, 6, 6));
        $this->canHandleCommandFailedValidation($defaultPlugin, $this->getMockQueue(), array(10, 1001));

        /* custom plugin settings */
        $this->canHandleCommandFailedValidation($configPlugin, $this->getMockQueue(), array(3, 5));
        $this->canHandleCommandFailedValidation($configPlugin, $this->getMockQueue(), array(5, 3));
    }

    /**
     * Test a plugin object with the specified parameters
     *
     * @param Plugin $plugin
     * @param Queue $queue
     * @param array $params
     */
    public function canHandleCommandFailedValidation(Plugin $plugin, Queue $queue, array $params)
    {
        Phake::when($this->event)->getSource()->thenReturn('#channel');
        Phake::when($this->event)->getCommand()->thenReturn('PRIVMSG');
        Phake::when($this->event)->getCustomParams()->thenReturn($params);

        $plugin->handleCommand($this->event, $queue);

        $helpLines = $plugin->getHelpLines();
        $this->assertInternalType('array', $helpLines);

        foreach ((array)$helpLines as $responseLine) {
            Phake::verify($queue)->ircPrivmsg('#channel', $responseLine);
        }
    }

    /**
     * Tests handleCommandHelp() is doing what it's supposed to
     */
    public function testHandleCommandHelp()
    {
        Phake::when($this->event)->getSource()->thenReturn('#channel');
        Phake::when($this->event)->getCommand()->thenReturn('PRIVMSG');

        $this->plugin->handleCommandHelp($this->event, $this->queue);

        $helpLines = $this->plugin->getHelpLines();
        $this->assertInternalType('array', $helpLines);

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

    /**
     * Return a plugin object
     *
     * @param array $config
     * @return Plugin
     */
    protected function getPlugin(array $config = array())
    {
        return new Plugin($config);
    }
}
