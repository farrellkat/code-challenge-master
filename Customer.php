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
     * @return string
     */
    public function statement()
    {
        $totalAmount = 0;
        $frequentRenterPoints = 0;
        $rentalAmounts = array();

        $result = 'Rental Record for ' . $this->name() . PHP_EOL;

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
            $result .= "\t" . str_pad($rentalName, 30, ' ', STR_PAD_RIGHT) . "\t" . $thisAmount . PHP_EOL;
        }
        $result .= 'Amount owed is ' . $totalAmount . PHP_EOL;
        $result .= 'You earned ' . $frequentRenterPoints . ' frequent renter points' . PHP_EOL;

        $this->htmlStatement($rentalAmounts, $totalAmount, $frequentRenterPoints);

        return $result;
    }

    /**
     * @return string
     */
    public function htmlStatement($rentalAmounts, $totalAmount, $frequentRenterPoints)
    {
        $formattedRentals = null;

        foreach ($rentalAmounts as $rental => $amount) {

            $formattedRentals .= "<li>{$rental} - " . toDollar($amount) . "</li>";
        }
        $result = "
            <h1>Rental Record for <em>$this->name</em></h1>
            <ul>$formattedRentals</ul>
            <p>Amount owed is <em>" . toDollar($totalAmount) . "</em></p>
            <p>You earned <em>$frequentRenterPoints</em> frequent renter points</p>";

        echo $result;
    }
}
