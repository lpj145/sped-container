<?php
namespace SpedTransform\Support;

use SpedTransform\SpedCollection;

abstract class AbstractAttribute implements SpedAttribute
{
    /**
     * @var SpedCollection
     */
    protected $spedCollection;
    /**
     * @var bool
     */
    private $executed = false;

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->executed;
    }

    /**
     * @return $this
     */
    protected function setExecuted()
    {
        $this->executed = true;
        return $this;
    }

    protected function setCollection(SpedCollection $collection)
    {
        $this->spedCollection = $collection;
    }

    public function toStd($key = null): \stdClass
    {
        if (null !== $key) {
            return (object)$this->spedCollection->get($key);
        }

        return (object)$this->spedCollection->all();
    }

    public function toArray($key = null): array
    {
        if (null !== $key) {
            return $this->spedCollection->get($key);
        }

        return $this->spedCollection->all();
    }


}
