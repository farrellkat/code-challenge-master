<?php

require_once('Movie.php');
require_once('Rental.php');
require_once('Customer.php');
require_once('PriceCode.php');

$childrens = new PriceCode('CHILDRENS', 1.5, false, 2);
$regular = new PriceCode('REGULAR', 1.5, false, 0);
$newRelease = new PriceCode('NEW_RELEASE', 3, true, 1);

$rental1 = new Rental(
    new Movie(
        'Back to the Future',
        $childrens
    ), 4
);

$rental2 = new Rental(
    new Movie(
        'Office Space',
        $regular
    ), 3
);

$rental3 = new Rental(
    new Movie(
        'The Big Lebowski',
        $newRelease
    ), 5
);

$customer = new Customer('Joe Schmoe');

$customer->addRental($rental1);
$customer->addRental($rental2);
$customer->addRental($rental3);

echo $customer->statement();
echo $customer->htmlStatement();
