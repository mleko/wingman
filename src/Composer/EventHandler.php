<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman\Composer;


use Composer\Script\Event;
use Mleko\Wingman\Wingman;

class EventHandler
{
    public static function format(Event $event)
    {
        $composerJsonPath = $event->getComposer()->getConfig()->getConfigSource()->getName();
        $wingman = new Wingman();
        $wingman->formatFile($composerJsonPath, new ComposerOutputAdapter($event->getIO()));
    }
}
