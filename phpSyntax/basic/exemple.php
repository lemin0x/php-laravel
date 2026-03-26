<?php
function examplePhp() {
    $numbers = [1, 2, 3, 4, 5];

    foreach ($numbers as $number) {
        echo "Number: $number\n";
    }

    $sum = 0;
    foreach ($numbers as $number) {
        $sum += $number;
    }

    echo "Sum of numbers: $sum\n";

    $arr = ["John", "Paul", "George"];
    $newArr = array_map(function($name) {
        return strtoupper($name);
    }, $arr);

    print_r($newArr);

    $result = 0;
    for ($i = 0; $i < 10; $i++) {
        $result += $i;
    }

    echo "Result of for loop: $result\n";

    $result = 0;
    $i = 0;
    while ($i < 10) {
        $result += $i;
        $i++;
    }

    echo "Result of while loop: $result\n";
}
?>
