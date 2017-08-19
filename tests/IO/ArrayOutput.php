<?php


namespace Mleko\Wingman\IO;


class ArrayOutput implements Output
{

    public $messages = [];

    public function write($message)
    {
        $this->messages[] = $message;
    }
}
