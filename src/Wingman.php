<?php


namespace Mleko\Wingman;


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
        "require",
        "require-dev",
        "conflict",
        "replace",
        "provide",
        "suggest",
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

    private $keys = [];

    /**
     * Wingman constructor.
     * @param string[]|null $keys
     */
    public function __construct(?array $keys = null)
    {
        $this->keys = null === $keys ? self::$order : $keys;
    }

    public function format($composerJson)
    {
        return $this->sort($composerJson, new Comparator(self::$order));
    }

    private function sort($item, callable $comparator)
    {
        if (is_array($item)) {
            $copy = $item;
            uksort($copy, $comparator);
            return $copy;
        }
        if (is_object($item)) {
            $copy = (array)$item;
            uksort($copy, $comparator);
            return (object)$copy;
        }
        return $item;
    }
}
