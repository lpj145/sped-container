<?php
namespace SpedTransform\Support;

abstract class AbstractAttribute implements SpedAttribute
{
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
}
