<?php

/**
 *  @Author: Dennis L.
 *  @Test: 1
 *  @TimeLimit: 5 minutes
 *  @Testing: Reflection
 *  @Task: Make $mySecret public using Reflection.
 */

// Please write some code to output the secret. You cannot adjust the visibility of the variable.
final class ReflectionTest {
     private $mySecret = 'I have 99 problems. This isn\'t one of them.';
}

// Add your code here.
$property = (new ReflectionClass('ReflectionTest'))->getProperty('mySecret');
$property->setAccessible(true); // -- Make sure to allow access to the private property -- 

$secretValue = $property->getValue(new ReflectionTest());
echo $secretValue;
