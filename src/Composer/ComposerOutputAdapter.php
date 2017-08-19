<?php


namespace Mleko\Wingman\Composer;


use Composer\IO\IOInterface;
use Mleko\Wingman\IO\Output;

class ComposerOutputAdapter implements Output
{

    /** @var IOInterface */
    private $output;

    public function __construct(IOInterface $output)
    {
        $this->output = $output;
    }

    public function write($message)
    {
        $this->output->write($message);
    }
}
