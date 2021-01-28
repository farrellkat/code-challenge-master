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
     * @var int
     */
    private $value;

    /**
     * @param string $Name
     * @param int $Value
     */
    public function __construct($name, $price, $value)
    {
        $this->name = $name;
        $this->price = $price;
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
     * @return int
     */
    public function value()
    {
        return $this->value;
    }
}