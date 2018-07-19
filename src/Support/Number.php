<?php
namespace SpedTransform\Support;

class Number extends \Cake\I18n\Number
{
    /**
     * @var float
     */
    private $currency;

    public function __construct($number = 0)
    {
        if (is_string($number)) {
            $number = parent::parseFloat($number, ['locale' => 'pt_BR']);
        }

        $this->currency = $number;
    }

    /**
     * @param $number
     * @return static
     */
    public static function fromFloat($number)
    {
        return new static((float)$number);
    }

    /**
     * @param Number $numbers
     * @return static
     */
    public function multiply(Number $numbers)
    {
        return new static($this->getCurrency() * $numbers->getCurrency());
    }

    /**
     * @param Number $numbers
     * @return static
     */
    public function divide(Number $numbers)
    {
        return new static($this->getCurrency() / $numbers->getCurrency());
    }

    /**
     * @param Number $numbers
     * @return static
     */
    public function add(Number $numbers)
    {
        return new static($this->getCurrency() + $numbers->getCurrency());
    }

    /**
     * @param Number $numbers
     * @return static
     */
    public function subtract(Number $numbers)
    {
        return new static($this->getCurrency() - $numbers->getCurrency());
    }

    /**
     * @param int $base
     * @return string
     */
    public function base(int $base = 10)
    {
        return number_format($this->getCurrency(), $base, '.', '');
    }

    /**
     * @return float|int
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
