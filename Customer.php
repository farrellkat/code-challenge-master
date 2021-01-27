<?php
require_once('./currencyConverter.php');

class Customer
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Rental[]
     */
    private $rentals;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->rentals = [];
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param Rental $rental
     */
    public function addRental(Rental $rental)
    {
        $this->rentals[] = $rental;
    }

    /**
     * @return array
     */
    public function totals()
    {
        $totalAmount = 0;
        $frequentRenterPoints = 0;
        $rentalAmounts = array();

        foreach ($this->rentals as $rental) {
            $thisAmount = 0;
            $rentalName = $rental->movie()->name();


            switch ($rental->movie()->priceCode()) {
                case Movie::REGULAR:
                    $thisAmount += 2;
                    if ($rental->daysRented() > 2) {
                        $thisAmount += ($rental->daysRented() - 2) * 1.5;
                    }
                    break;
                case Movie::NEW_RELEASE:
                    $thisAmount += $rental->daysRented() * 3;
                    break;
                case Movie::CHILDRENS:
                    $thisAmount += 1.5;
                    if ($rental->daysRented() > 3) {
                        $thisAmount += ($rental->daysRented() - 3) * 1.5;
                    }
                    break;
            }
            $totalAmount += $thisAmount;

            $frequentRenterPoints++;
            if ($rental->movie()->priceCode() === Movie::NEW_RELEASE && $rental->daysRented() > 1) {
                $frequentRenterPoints++;
            }

            $rentalAmounts[$rentalName] = $thisAmount;
        }
        return [
            'totalAmount' => $totalAmount,
            'frequentRenterPoints' => $frequentRenterPoints,
            'rentalAmounts' => $rentalAmounts
        ];
    }

    /**
     * @return string
     */
    public function statement()
    {
        $totals = $this->totals();
        $result = 'Rental Record for ' . $this->name() . PHP_EOL;
        foreach ($totals['rentalAmounts'] as $rental => $amount) {
            $result .= "\t" . str_pad($rental, 30, ' ', STR_PAD_RIGHT) . "\t" . $amount . PHP_EOL;
        }
        $result .= 'Amount owed is ' . $totals['totalAmount'] . PHP_EOL;
        $result .= 'You earned ' . $totals['frequentRenterPoints'] . ' frequent renter points' . PHP_EOL;

        return $result;
    }

    /**
     * @return string
     */
    public function htmlStatement()
    {
        $formattedRentals = null;
        $totals = $this->totals();
        foreach ($totals['rentalAmounts'] as $rental => $amount) {

            $formattedRentals .= "<li>{$rental} - " . toDollar($amount) . "</li>";
        }
        $result = "
            <h1>Rental Record for <em>$this->name</em></h1>
            <ul>$formattedRentals</ul>
            <p>Amount owed is <em>" . toDollar($totals['totalAmount']) . "</em></p>
            <p>You earned <em>{$totals['frequentRenterPoints']}</em> frequent renter points</p>";

        echo $result;
    }
}
