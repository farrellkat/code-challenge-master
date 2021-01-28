<?php
//takes a float and returns it in USD. 3.5 => $3.50
function toDollar($amount)
{
    return '$' . number_format($amount, 2);
};
