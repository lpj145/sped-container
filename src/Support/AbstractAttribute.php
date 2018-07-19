<?php
namespace SpedTransform\Support;

use SpedTransform\Support\IterableArray;
use SpedTransform\Support\SpedAttribute;

abstract class AbstractAttribute implements SpedAttribute
{
    use IterableArray;
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var bool
     */
    private $executed = false;

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
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->executed;
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
}
