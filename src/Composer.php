<?php


namespace Mleko\Wingman;


use Composer\Script\Event;

class Composer
{
    public static function format(Event $event)
    {
        $composerJsonPath = $event->getComposer()->getConfig()->getConfigSource()->getName();
        if (!is_readable($composerJsonPath) || !is_writable($composerJsonPath)) {
            $event->getIO()->writeError("composer.json is not readable/writable");
            exit(1);
        }
        $event->getIO()->write(sprintf("Formatting file: %s", $composerJsonPath));
        $composerJson = json_decode(file_get_contents($composerJsonPath));
        $fifer = new Wingman();
        $composerJson = $fifer->format($composerJson);
        file_put_contents($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
    }
}
