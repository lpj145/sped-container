<?php
namespace SpedTransform;

use SpedTransform\Macro\DateFormat;
use SpedTransform\Macro\Precision;
use SpedTransform\Macro\SanitizeString;

class SpedCollection
{
    use SanitizeString,
        Precision,
        DateFormat;

    protected $macros = [
        'onlyNumbers' => 'this',
        'toUpper' => 'this',
        'toLower' => 'this',
        'replaceSpecialsChars' => 'this',
        // Precision macros
        'precision10' => 'this',
        'precision2' => 'this',
        'precision4' => 'this',
        'normalizeValue' => 'this',
        // DateFormat
        'formatUTC' => 'this'
    ];

    /**
     * @var array
     */
    protected $items = [];

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function transform(callable $callback)
    {
        $this->items = $this->map($callback)->all();

        return $this;
    }

    /**
     * @param array $rules
     * @return SpedCollection
     */
    public function transformByRules(array $rules)
    {
        $items = [];
        array_map(function(&$ruleName, $ruleOptions) use (&$items){
            $value = $ruleOptions['default'] ?? null;
            $insertIn = $ruleOptions['insertIn'] ?? null;

            if (null === $value = $this->get($ruleName, $value)) {
                return;
            }

            if (isset($ruleOptions['macros'])) {
                $value = $this->executeMacros($ruleOptions['macros'], $value);
            }

            if (is_array($value)) {
                $ruleOptions['alias'] = key($value);
                $value = $value[$ruleOptions['alias']];
            }

            if (isset($ruleOptions['insertIn'])) {
                $items[$ruleOptions['insertIn']][$ruleOptions['alias'] ?? $ruleName] = $value;
                return;
            }

            $items[$ruleOptions['alias'] ?? $ruleName] = $value;
        }, array_keys($rules), $rules);

        return new static($items);
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * @param $key
     * @return $this
     */
    public function remove($key)
    {
        unset($this->items[$key]);
        return $this;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->items[$key]) &&
            null !== $this->items[$key];
    }

    public function insert($key, $data)
    {
        if ($this->has($key)) {
            $this->items[$key] += $data;
            return $this;
        }

        $this->items[$key] = $data;

        return $this;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->items[$key] : $default;
    }

    /**
     * @return object
     */
    public function toStd()
    {
        return (object)$this->items;
    }

    public function macro(string $name, $callable)
    {
        $this->macros[$name] = $callable;
    }

    /**
     * @param $callableTransform
     * @param null $data
     * @return mixed|null
     */
    private function executeMacros($callableTransform, $data = null)
    {
        if (is_string($callableTransform)) {
            return $this->callCallback($callableTransform, $data, $this);
        }

        if (is_array($callableTransform)) {
            foreach ($callableTransform as $callback) {
                $data = $this->callCallback($callback, $data, $this);
            }

            return $data;
        }
    }

    /**
     * @param $func
     * @param $data
     * @param null $object
     * @return mixed
     */
    private function callCallback($func, $data, $object = null)
    {
        if (false === isset($this->macros[$func])) {
            throw new \InvalidArgumentException('Macro: \''.$func.'\' cannot be found on scope');
        }

        $callable = $this->macros[$func];

        if (is_object($callable)) {
            $object = $callable;
        }

        if (is_callable($this->macros[$func])) {
            return $callable($data);
        }

        return call_user_func_array([$object, $func], [$data]);
    }
}
