<?php
namespace SpedTransform\Support;

use SpedTransform\Macro\DateFormat;
use SpedTransform\Macro\Precision;
use SpedTransform\Macro\SanitizeString;
use SpedTransform\Support\IterableArray;
use SpedTransform\Support\SpedAttribute;

abstract class AbstractAttribute implements SpedAttribute
{
    use IterableArray,
        Precision,
        SanitizeString,
        DateFormat;
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var bool
     */
    private $executed = false;

    protected $aliases = [];

    /**
     * @return \stdClass
     */
    public function toStd(): \stdClass
    {
        return (object)$this->data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->data) ?
            $this->data[$key] :
            $default;
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->executed;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function translateIndex(array $data)
    {
        $indexName = key($data);
        $alias = $this->getAlias($indexName);

        if (null !== $alias) {
            return [$alias => $data[$indexName]];
        }

        return $data;
    }

    /**
     * @param array $data
     */
    protected function setData(array $data)
    {
        $this->data = $data;
    }

    protected function setExecuted()
    {
        $this->executed = true;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function getAlias($key)
    {
        return array_key_exists($key, $this->aliases) ? $this->aliases[$key] : null;
    }
}
