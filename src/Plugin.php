<?php
/**
 * Phergie plugin for returning randomly generated numbers in response to dice rolling requests (https://github.com/chrismou/phergie-irc-plugin-react-dice)
 *
 * @link https://github.com/chrismou/phergie-irc-plugin-react-dice for the canonical source repository
 * @copyright Copyright (c) 2014 Chris Chrisostomou (http://mou.me)
 * @license http://phergie.org/license New BSD License
 * @package Chrismou\Phergie\Plugin\Dice
 */

namespace Chrismou\Phergie\Plugin\Dice;

use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;

/**
 * Plugin class.
 *
 * @category Chrismou
 * @package Chrismou\Phergie\Plugin\Dice
 */
class Plugin extends AbstractPlugin
{
    /**
     * @var int
     */
    protected $defaultDieSides = 6;

    /**
     * Accepts plugin configuration
     */
    public function __construct()
    {
    }

    /**
     * IRC events to watch
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'command.dice' => 'handleCommand',
            'command.dice.help' => 'handleCommandHelp',
        );
    }

    /**
     * Handle the dice roll command
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleCommand(Event $event, Queue $queue)
    {
        if ($this->validateParams($event)) {
            $params = $event->getCustomParams();
            $results = array();
            $total = 0;
            $count = $params[0];
            $sides = (isset($params[1])) ? $params[1] : $this->defaultDieSides;

            for ($roll=1; $roll<=$count; $roll++) {
                $rollResult = $this->doRoll($sides);
                $results[] = $rollResult;
                $total += $rollResult;
            }
            $response = $this->generateResponse($event, $total, $results);
            $this->sendIrcResponseLine($event, $queue, $response);
        } else {
            $this->handleCommandHelp($event, $queue);
        }
    }

    /**
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param integer $total
     * @param array $results
     * @return string
     */
    public function generateResponse(Event $event, $total, array $results)
    {
        return sprintf(
            "%s: You rolled %d %s",
            $event->getNick(),
            $total,
            (count($results)>1) ? sprintf('(%s)', implode('+', $results)) : ''
        );
    }

    /**
     * Perform a single dice roll
     *
     * @param integer $sides
     * @return int
     */
    protected function doRoll($sides)
    {
        return mt_rand(1, (int)$sides);
    }

    /**
     * Check the supplied parameters are valid
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @return boolean
     */
    protected function validateParams(Event $event)
    {
        return (
            $this->genericParamValidation($event) &&
            $this->firstParamValidation($event) &&
            $this->secondParamValidation($event)
        );
    }

    /**
     * Verify the combination of parameters are valid
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @return boolean
     */
    private function genericParamValidation(Event $event)
    {
        $params = $event->getCustomParams();
        return (count($params)>=1 && count($params)<=2);
    }

    /**
     * Verify parameter 1 is valid
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @return boolean
     */
    private function firstParamValidation(Event $event)
    {
        $params = $event->getCustomParams();
        return (is_numeric($params[0]) && $params[0] > 0);
    }

    /**
     * Verify parameter 2 is valid
     *
     * @param Event $event
     * @return bool
     */
    private function secondParamValidation(Event $event)
    {
        $params = $event->getCustomParams();
        return (!isset($params[1]) || (is_numeric($params[1]) && $params[1]>=1));
    }

    /**
     * Handle the help command
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleCommandHelp(Event $event, Queue $queue)
    {
        $this->sendIrcResponse($event, $queue, $this->getHelpLines());
    }

    /**
     * Return an array of help command response lines
     *
     * @return array
     */
    public function getHelpLines()
    {
        return array(
            'Usage: dice [number of dice] [number of sides]',
            '[number of die] - how many dice to roll',
            '[number of sides] (optional) the number of sides on each die (defaults to 6)',
            'Returns randomly generated numbers in response to dice rolling requests'
        );
    }

    /**
     * Send an array of response lines back to IRC
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     * @param array $ircResponse
     */
    protected function sendIrcResponse(Event $event, Queue $queue, array $ircResponse)
    {
        foreach ($ircResponse as $ircResponseLine) {
            $this->sendIrcResponseLine($event, $queue, $ircResponseLine);
        }
    }

    /**
     * Send a single response line back to IRC
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     * @param string $ircResponseLine
     */
    protected function sendIrcResponseLine(Event $event, Queue $queue, $ircResponseLine)
    {
        $queue->ircPrivmsg($event->getSource(), $ircResponseLine);
    }
}
