<?php

namespace App\Contracts;

interface OccupationComparator
{
    function compare($scope, $list_1, $list_2);
}