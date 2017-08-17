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

    /** @var Rule[] */
    private $config = [];

    /**
     * Wingman constructor.
     * @param string[]|null $keys
     */
    public function __construct(?array $keys = null)
    {
        $this->config = $this->normalizeConfig(
            null === $keys ? self::$order : $keys
        );
    }

    public function format($composerJson)
    {
        return $this->subFormat($composerJson, $this->config);
    }

    /**
     * @param array $element
     * @param Rule[] $config
     * @return array|object
     */
    private function subFormat($element, $config)
    {
        $element = $this->sort($element, new Comparator($config));
        foreach ($element as $key => &$value) {
            foreach ($config as $rule) {
                if ($rule->isMatch($key)) {
                    if (is_object($value) && $rule->isSortChildren()) {
                        $value = $this->subFormat($value, $rule->getChildRules());
                    }
                    break;
                }
            }
        }
        return $element;
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
     * @return Rule[]
     */
    private function normalizeConfig(array $config): array
    {
        $rules = [];
        foreach ($config as $key => $value) {
            if (is_numeric($key)) {
                $rules[] = new Rule($value, false, []);
            } else {
                $rules[] = new Rule($key, true, true === $value ? [] : $this->normalizeConfig((array)$value));
            }
        }
        return $rules;
    }
}
