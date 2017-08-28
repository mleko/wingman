<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */


namespace Mleko\Wingman;


use Mleko\Wingman\IO\Output;

class MistakeChecker
{
    private static $possibleMistakes = [
        "tags" => "keywords",
        "desc" => "description",
        "author" => "authors",
        "bugs" => "support",
        "dependencies" => "require",
        "devDependencies" => "require-dev",
        "optionalDependencies" => "suggests"
    ];

    private $rules = [];

    /**
     * MistakeChecker constructor.
     * @param array $rules
     */
    public function __construct(array $rules = null)
    {
        $this->rules = null === $rules ? static::$possibleMistakes : $rules;
    }

    public function checkForMistakes($composerJson, Output $output)
    {
        $mistakes = array_intersect_key($this->rules, (array)$composerJson);
        if (!$mistakes) {
            return;
        }
        $output->write("Potential mistakes have been found\n");
        foreach ($mistakes as $from => $to) {
            $output->write("$from => $to\n");
        }
    }
}
