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
        "require" => ["php", "hhvm", "ext.*", "lib.*"],
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

    /** @var Rules */
    private $rules = [];

    /**
     * Wingman constructor.
     * @param string[]|null $keys
     */
    public function __construct(?array $keys = null)
    {
        $this->rules = $this->normalizeConfig(
            null === $keys ? self::$order : $keys
        );
    }

    public function format($composerJson)
    {
        return $this->subFormat($composerJson, $this->rules);
    }

    /**
     * @param array $element
     * @param Rules $rules
     * @return array|object
     */
    private function subFormat($element, $rules)
    {
        $element = $this->sort($element, new Comparator($rules));
        return $this->formatChildren($element, $rules);
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
     * @param array $element
     * @param Rules $rules
     * @return array|object
     */
    private function formatChildren($element, $rules)
    {
        foreach ($element as $key => &$value) {
            if (!is_object($value)) {
                continue;
            }
            $index = $rules->findMatching($key);
            if (null === $index) {
                continue;
            }
            $value = $this->subFormat($value, $rules->getRule($index)->getChildRules());
        }
        return $element;
    }
}
