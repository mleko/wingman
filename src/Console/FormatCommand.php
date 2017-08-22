<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman\Console;


use Mleko\Wingman\Wingman;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FormatCommand extends Command
{
    protected function configure()
    {
        parent::configure();
        $this->setName("format")
            ->setDescription("Format composer.json file")
            ->addArgument("path", InputArgument::OPTIONAL, "Path to composer.json file", "./composer.json")
            ->addOption("register", null, InputOption::VALUE_NONE, "Register wingman as post-update script");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument("path");
        $wingman = new Wingman();
        $wingman->formatFile($path, new ConsoleOutputAdapter($output), $input->getOption("register"));
    }

}
