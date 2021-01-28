<?php

class Movie
{

    /**
     * @var string
     */
    private $name;

    // replaced the string priceCode with the class PriceCode
    /**
     * @var PriceCode
     */
    private $priceCode;

    /**
     * @param string $name
     * @param PriceCode $priceCode
     */
    public function __construct($name, PriceCode $priceCode)
    {
        $this->name = $name;
        $this->priceCode = $priceCode;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return PriceCode
     */
    public function priceCode()
    {
        return $this->priceCode;
    }
}
