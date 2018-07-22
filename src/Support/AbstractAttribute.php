<?php
namespace SpedTransform\Support;

use SpedTransform\Macro\DateFormat;
use SpedTransform\Macro\Precision;
use SpedTransform\Macro\SanitizeString;
use SpedTransform\SpedData;
use SpedTransform\Support\IterableArray;
use SpedTransform\Support\SpedAttribute;

abstract class AbstractAttribute implements SpedAttribute
{
    use IterableArray,
        Precision,
        SanitizeString,
        DateFormat;
    /**
     * @var SpedData
     */
    private $data;
    /**
     * @var bool
     */
    private $executed = false;

    protected $aliases = [];

    public function __construct()
    {
        $this->data = new SpedData();
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

    /**
     * @param $indexName
     * @param array $options
     */
    protected function hydratValue($indexName, array $options = [])
    {
        if (false === isset($options['reference'])) {
            $options['reference'] = $this->data;
        }

        /** @var SpedData $data */
        $data = $options['reference'];
        $value = $data->get($indexName);

        if (false === $data instanceof SpedData) {
            throw new \InvalidArgumentException('Referente for hydrator \''.$indexName.'\' is not valid SpedData object');
        }

        if (isset($options['callbacks'])) {
            $value = $this->runValueCallback($options['callbacks'], $value);
        }

        if (isset($options['translate'])) {
            $data->remove($indexName);
            $indexName = $this->getAlias($indexName);
        }

        if (isset($options['namespace'])) {
            $data->insert($options['namespace'], [$indexName => $value]);
        }

        if (isset($options['exclude'])) {
            $data->remove($indexName);
        }
    }

    private function runValueCallback($callbacks, $data)
    {
        if (is_string($callbacks)) {
            return $this->callCallback($callbacks, $data, $this);
        }

        foreach ($callbacks as $callback) {
            $data = $this->callCallback($callback, $data, $this);
        }

        return $data;
    }

    private function callCallback($func, $data, $object = null)
    {
        if (null === $object) {
            return $func();
        }

        return call_user_func_array([$object, $func], [$data]);
    }

    public function toArray(): array
    {
        return $this->data->toArray();
    }

    public function toStd(): \stdClass
    {
        return $this->data->toStd();
    }

    public function setData($data)
    {
        $this->data->overwrite($data);
    }

    public function remove($key)
    {
        $this->data->remove($key);
    }
}
