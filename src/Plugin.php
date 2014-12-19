<?php
/**
 * Phergie plugin for provides randomly generated numbers in response to dice rolling requests (https://github.com/chrismou/phergie-irc-plugin-react-dice)
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
     * Accepts plugin configuration.
     *
     * @param array $config
     */
    public function __construct(array $config = array())
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

            $response = sprintf("%s: You rolled a %d %s",
                $event->getNick(),
                $total,
                (count($results)>1) ? sprintf('(%s)', implode('+', $results)) : ''
            );

            $this->sendIrcResponseLine($event, $queue, $response);

        } else {
            $this->handleCommandHelp($event, $queue);
        }
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
     * @return bool
     */
    protected function validateParams(Event $event) {
        $params = $event->getCustomParams();
        return (
            // At least 1 parameter
            count($params)>=1 &&
            // No more than 2 parameters
            count($params)<=2 &&
            // Parameter 1 must be an integer
            is_numeric($params[0]) &&
            // Parameter 1 must be 1 or over
            $params[0] > 0 &&
            // Parameter 2 should either not exist, or be an integer over 1
            (!isset($params[1]) || (isset($params[1]) && is_numeric($params[1]) && $params[1]>=1))
        ) ? true : false;
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
