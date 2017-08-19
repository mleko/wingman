<?php


namespace Mleko\Wingman\Console;


class Application extends \Symfony\Component\Console\Application
{
    public function __construct()
    {
        parent::__construct("Wingman");

        $this->add(new FormatCommand());

        $this->setDefaultCommand("format");
    }


}
