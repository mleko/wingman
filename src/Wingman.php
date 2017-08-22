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

class Wingman
{
    private static $order = [
        "name",
        "description",
        "version",
        "type",
        "keywords",
        "homepage",
        "time",
        "license",
        "authors",
        "support",
        "require" => ["php", "hhvm", "ext.*", "lib.*"],
        "require-dev" => ["php", "hhvm", "ext.*", "lib.*"],
        "conflict" => true,
        "replace" => true,
        "provide" => true,
        "suggest" => true,
        "autoload",
        "autoload-dev",
        "include-path",
        "target-dir",
        "minimum-stability",
        "prefer-stable",
        "repositories",
        "config",
        "scripts",
        "extra",
        "bin",
        "archive",
        "non-feature-branches"
    ];

    /** @var Rules */
    private $rules = [];

    /**
     * Wingman constructor.
     * @param string[]|null $keys
     */
    public function __construct(array $keys = null)
    {
        $this->rules = $this->normalizeConfig(
            null === $keys ? self::$order : $keys
        );
    }

    public function format($composerJson)
    {
        return Formatter::format($composerJson, $this->rules);
    }

    public function formatFile($path, Output $output)
    {
        return $this->processFile($path, $output);
    }

    public function registerInFile($path, Output $output)
    {
        return $this->processFile($path, $output, true);
    }

    public function register($composerJson)
    {
        if (!isset($composerJson->scripts)) {
            $composerJson->scripts = new \stdClass();
        }
        if (!isset($composerJson->scripts->{"post-update-cmd"})) {
            $composerJson->scripts->{"post-update-cmd"} = [];
        }
        $puc = &$composerJson->scripts->{"post-update-cmd"};
        if (is_array($puc)) {
            if (!in_array("Mleko\\Wingman\\Composer\\EventHandler::format", $puc)) {
                $puc[] = "Mleko\\Wingman\\Composer\\EventHandler::format";
            }
        } elseif ($puc !== "Mleko\\Wingman\\Composer\\EventHandler::format") {
            $puc = [$puc, "Mleko\\Wingman\\Composer\\EventHandler::format"];
        }
        return $composerJson;
    }

    private function processFile($path, Output $output, $register = false)
    {
        $composerJson = $this->readFile($path, $output);
        if (null === $composerJson) {
            return false;
        }
        if ($register) {
            $output->write(sprintf("Register wingman in file: %s\n", $path));
            $composerJson = $this->register($composerJson);
            $output->write("Wingman registered\n");
        }
        $output->write(sprintf("Formatting file: %s\n", $path));
        $composerJson = $this->format($composerJson);
        return $this->writeFile($path, $composerJson);
    }

    /**
     * @param array $config
     * @return Rules
     */
    private function normalizeConfig(array $config): Rules
    {
        $rules = [];
        foreach ($config as $key => $value) {
            if (is_numeric($key)) {
                $rules[] = new Rule($value, false, null);
            } else {
                $rules[] = new Rule($key, true, true === $value ? null : $this->normalizeConfig((array)$value));
            }
        }
        return new Rules($rules);
    }

    /**
     * @param $path
     * @param Output $output
     * @return string|null
     */
    private function readFile($path, Output $output)
    {
        if (!is_readable($path) || !is_writable($path)) {
            $output->write("composer.json is not readable/writable\n");
            return null;
        }
        return json_decode(file_get_contents($path));
    }

    /**
     * @param $path
     * @param $composerJson
     * @return bool
     */
    private function writeFile($path, $composerJson): bool
    {
        return false !== file_put_contents($path, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
    }
}
