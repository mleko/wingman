<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman\IO;


class ArrayOutput implements Output
{

    public $messages = [];

    public function write($message)
    {
        $this->messages[] = $message;
    }
}
