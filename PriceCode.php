<?php

class PriceCode
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var float
     */
    private $price;
    /**
     * @var bool
     */
    private $bonus;
    /**
     * @var int
     */
    private $value;

    /**
     * @param string $Name
     * @param float $price
     * @param bool $bonus
     * @param int $Value
     */
    public function __construct($name, $price, $bonus, $value)
    {
        $this->name = $name;
        $this->price = $price;
        $this->bonus = $bonus;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
    /**
     * @return float
     */
    public function price()
    {
        return $this->price;
    }
    /**
     * @return bool
     */
    public function bonus()
    {
        return $this->bonus;
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->value;
    }
}
