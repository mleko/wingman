<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman\Console;


use Mleko\Wingman\IO\Output;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleOutputAdapter implements Output
{
    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function write($message)
    {
        $this->output->write($message);
    }
}
