<?php

function myStrlen($str)
{
    $i = 0;
    while (isset($str[$i])) {
        $i++;
    }
    return $i;
}

function myTrim($str)
{
    $strlen = myStrlen($str);
    $newStr = '';

    for ($i = 0; $i < $strlen; $i++) { 
        if ($str[$i] !== "\n") {
            $newStr .= $str[$i];
        }
    }
    return $newStr;
}

/**
 * Create another array and fill it with 1 and 0
 * 1 for cell and 0 for obstacle
 * @return array
 */
function cloneBoard($array, $rowLength)
{
    $newArr = array();

    foreach ($array as $row) {
        for ($i = 0; $i < $rowLength; $i++) {
            if ($row[$i] == "." ) {
                $row[$i] = '1';
            } else {
                $row[$i] = '0';
            }
        }
        $newArr[] = $row;
    }
    return $newArr;
}

function myMin($array)
{
    $min = $array[0];
    foreach ($array as $value) {
        if ($min > $value)
        {
            $min = $value;
        }
    }
    return $min;
}

/**
 * Checking adjacent cell
 * 
 * 0 = obstacle, 1 = cell
 * More information: https://www.youtube.com/watch?v=_Lf1looyJMU
 * 
 * @return int largest square currently possible in the present cell
 */
function check3($cell, $topLeft = 0, $top = 0, $left = 0)
{
    return $cell != 0 ? myMin([$topLeft, $top, $left]) + $cell : 0;
}

/**
 * Parse the array and apply this technique
 * https://www.youtube.com/watch?v=_Lf1looyJMU
 * @param array $array = $board2
 * @var array $bigSquarePos is returned for displaying the final array
 * @return array size and position of the largest square
 */
function maxSquare($array, $rowLength)
{
    $rowIndex = 0;
    $bigSquarePos = array();
    $x = null;
    $y = null;
    $maxNum = 0;

    foreach ($array as $row) {
        for ($i = 0; $i < $rowLength; $i++) {
            
            $topLeft = isset($array[$rowIndex - 1][$i - 1]) ? intval($array[$rowIndex - 1][$i - 1]) : 0;
            $top = isset($array[$rowIndex - 1][$i]) ? intval($array[$rowIndex - 1][$i]) : 0;
            $left = isset($array[$rowIndex][$i - 1]) ? intval($array[$rowIndex][$i - 1]) : 0;
            
            $array[$rowIndex][$i] = check3(intval($row[$i]), $topLeft, $top, $left);
            
            if ($maxNum < $array[$rowIndex][$i]) {
                $maxNum = $array[$rowIndex][$i];
                $x = $i;
                $y = $rowIndex;
            }
        }
        $rowIndex++; 
    }
    return ['size' => $maxNum, "x" => $x, "y" => $y];
}

function displayBigSquare($board, $data)
{
    $size = $data['size'];
    $x = $data['x'];
    $y = $data['y'];
    
    for ($i = 0; $i < $size; $i++) { 
        for ($j = 0; $j < $size; $j++) { 
            $board[$y - $i][$x - $j] = 'x'; 
        }
    }

    foreach ($board as $row) {
        echo "$row\n";
    }

}


/**
 * Main function
 * 
 * Display board with largest square
 */
function bsq($file)
{
    $numLines;
    $board = array();

    $handle = @fopen($file, 'r');
    if ($handle) {
        
        $numLines = intval(fgets($handle));
        
        while ($line = fgets($handle)) {
            $board[] = myTrim($line);
        }

        $rowLength = myStrlen($board[0]);
        // clone baord and replace characters with digits
        $board2 = cloneBoard($board, $rowLength);
        
        // Determine which is biggest array
        $data = maxSquare($board2, $rowLength);
        
        displayBigSquare($board, $data);
    }
    fclose($handle);
}

bsq($argv[1]);