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
    public function statementCalc()
    {
        $totalAmountOwed = 0;
        $frequentRenterPoints = 0;
        $rentalAmountsOwed = array();

        foreach ($this->rentals as $rental) {
            $thisAmount = 0;
            $movieName = $rental->movie()->name();
            $priceCode = $rental->movie()->priceCode();
            $bonus = $priceCode->bonus();
            $priceCodeName = $priceCode->name();
            $price = $priceCode->price();


            switch ($priceCodeName) {
                case 'REGULAR':
                    $thisAmount += 2;
                    if ($rental->daysRented() > 2) {
                        $thisAmount += ($rental->daysRented() - 2) * $price;
                    }
                    break;
                case 'NEW_RELEASE':
                    $thisAmount += $rental->daysRented() * $price;
                    break;
                case 'CHILDRENS':
                    $thisAmount += 1.5;
                    if ($rental->daysRented() > 3) {
                        $thisAmount += ($rental->daysRented() - 3) * $price;
                    }
                    break;
            }
            $totalAmountOwed += $thisAmount;

            $frequentRenterPoints++;
            if ($bonus === true && $rental->daysRented() > 1) {
                $frequentRenterPoints++;
            }

            $rentalAmountsOwed[$movieName] = $thisAmount;
        }
        return [
            'totalAmountOwed' => $totalAmountOwed,
            'frequentRenterPoints' => $frequentRenterPoints,
            'rentalAmountsOwed' => $rentalAmountsOwed
        ];
    }

    /**
     * @return string
     */
    public function statement()
    {
        $statementCalc = $this->statementCalc();
        $result = 'Rental Record for ' . $this->name() . PHP_EOL;
        foreach ($statementCalc['rentalAmountsOwed'] as $rental => $amount) {
            $result .= "\t" . str_pad($rental, 30, ' ', STR_PAD_RIGHT) . "\t" . $amount . PHP_EOL;
        }
        $result .= 'Amount owed is ' . $statementCalc['totalAmountOwed'] . PHP_EOL;
        $result .= 'You earned ' . $statementCalc['frequentRenterPoints'] . ' frequent renter points' . PHP_EOL;

        return $result;
    }

    /**
     * @return string
     */
    public function htmlStatement()
    {
        $formattedRentals = null;
        $statementCalc = $this->statementCalc();
        foreach ($statementCalc['rentalAmountsOwed'] as $rental => $amount) {

            $formattedRentals .= "<li>{$rental} - " . toDollar($amount) . "</li>";
        }
        $result = "
            <h1>Rental Record for <em>$this->name</em></h1>
            <ul>$formattedRentals</ul>
            <p>Amount owed is <em>" . toDollar($statementCalc['totalAmountOwed']) . "</em></p>
            <p>You earned <em>{$statementCalc['frequentRenterPoints']}</em> frequent renter points</p>";

        echo $result;
    }
}
