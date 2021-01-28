<?php

require_once('Movie.php');
require_once('Rental.php');
require_once('Customer.php');
require_once('PriceCode.php');

// Break out PriceCode into it's own class with 4 properties: ('name','price','bonus','value')
// 'Name' is the code name for the genre/label
// 'Bonus' Assigns the frequent renter points bonus. Makes it easy to change frequent renter points bonuses without going into the code
// 'value' Allows you to more easily assign rental pricing rules aka <($rental->daysRented() - 2) * $price> to new genres
// for example $sciFi = new PriceCode('SCIFI', 1.5, false, 0) would have the same rental rules as 'regular'.

$childrens = new PriceCode('CHILDRENS', 1.5, false, 2);
$regular = new PriceCode('REGULAR', 1.5, false, 0);
$newRelease = new PriceCode('NEW_RELEASE', 3, true, 1);

$rental1 = new Rental(
    new Movie(
        'Back to the Future',
        $childrens
    ),
    4
);

$rental2 = new Rental(
    new Movie(
        'Office Space',
        $regular
    ),
    3
);

$rental3 = new Rental(
    new Movie(
        'The Big Lebowski',
        $newRelease
    ),
    5
);

$customer = new Customer('Joe Schmoe');

$customer->addRental($rental1);
$customer->addRental($rental2);
$customer->addRental($rental3);

echo "===========STATEMENT=============" . PHP_EOL . "\n";
echo $customer->statement() . PHP_EOL;
echo "=========HTML STATEMENT==========" . PHP_EOL . "\n";
echo $customer->htmlStatement();
