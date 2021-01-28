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

    //Broke all the logic out of statement() and htmlStatement() so as to keep the programming DRY
    //statementCalc() returns an array of key value pairs of the important data to be used in the templates

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
            $value = $priceCode->value();
            $price = $priceCode->price();

            // by creating it's own class of PriceCode, these settings can be set dynamically from the index.
            // multiple genres can be assined to a $value so that you don't have to add every single genre to the switch
            // Price is also derived from the PriceCode class so that rentals can have matching $values but different prices.

            switch ($value) {
                case 0:
                    $thisAmount += 2;
                    if ($rental->daysRented() > 2) {
                        $thisAmount += ($rental->daysRented() - 2) * $price;
                    }
                    break;
                case 1:
                    $thisAmount += $rental->daysRented() * $price;
                    break;
                case 2:
                    $thisAmount += 1.5;
                    if ($rental->daysRented() > 3) {
                        $thisAmount += ($rental->daysRented() - 3) * $price;
                    }
                    break;
            }
            $totalAmountOwed += $thisAmount;

            // Instead of the bonus being tied to a specific genre which has to be hardcoded,
            // I created a boolean called bonus flag in the PriceCode. If true it gets the bonus.
            // This allows for ease of use because you only have to change it in the index.
            // It also allows you to easily allow multiple PriceCodes to have bonuses

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

    //Replaced all variables with variables that come from statementCalc() 

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

    //Replaced all variables with variables that come from statementCalc()
    //The list of rentals get formatted and placed into their own variable to more easily be inserted into a <ul>
    //This also makes the html output easier to read.
    //Created and imported a function called toDollar() that formats the $amount correctly for a receipt.
    //Add all the extra php nonsense to make the html line up properly in the console. Definitely overkill.

    public function htmlStatement()
    {
        $formattedRentals = null;
        $statementCalc = $this->statementCalc();
        foreach ($statementCalc['rentalAmountsOwed'] as $rental => $amount) {

            $formattedRentals .= "\t" . "<li>{$rental} - " . toDollar($amount) . "\t" . "</li>" . PHP_EOL;
        }
        $result =
            "<h1>Rental Record for <em>$this->name</em></h1>" . PHP_EOL .
            "<ul>" . PHP_EOL . str_pad("$formattedRentals", 30, ' ', STR_PAD_RIGHT) . "</ul>" . PHP_EOL .
            "<p>Amount owed is <em>" . toDollar($statementCalc['totalAmountOwed']) . "</em></p>" . PHP_EOL .
            "<p>You earned <em>{$statementCalc['frequentRenterPoints']}</em> frequent renter points</p>";

        echo $result;
    }
}
