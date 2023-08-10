<?php

/**
* @Author: Dennis L.
* @Test: 2
* @TimeLimit: 10 minutes
* @Testing: Closures
*/

var_dump(changeDateFormat(array("2010/03/30","15/12/2016","11-15-2012","20130720")));

/**
* When this method runs, it should return valid dates in the following format: DD/MM/YYYY.
*/
function changeDateFormat(array $dates): array
{
    $listOfDates = [];
    $closure = [];
    
    // Add code here
    $closure = function ($date) use (&$listOfDates)
    {
        $timestamp = strtotime($date);
        $listOfDates[] = ($timestamp !== false) ? date('d/m/Y', $timestamp) : 'Invalid date!';
    };

    // Don't edit anything else!
    array_map($closure, $dates);
    return $listOfDates;
}