<?php

/**
* @Author: Dennis L.
* @Test: 3
* @TimeLimit: 15 minutes
* @Testing: Recursion
*/

function numberOfItems(array $arr, string $needle): int
{
    // Write some code to tell me how many of my selected fruit is in these lovely nested arrays.
    $countItem = 0;

    foreach ($arr as $item)
    {
        if (is_array($item))
            $countItem += numberOfItems($item, $needle);
        else if ($item === $needle)
            $countItem++;
    }

    return $countItem;
}

$arr = ['apple', ['banana', 'strawberry', 'apple', ['banana', 'strawberry', 'apple']]];
echo numberOfItems($arr, 'apple') . PHP_EOL;
