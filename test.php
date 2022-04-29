<?php
require 'Grid.php';

$testGrid = new Grid(10, 10);
$testGrid->randomGrid();
echo "----// BASIC GRID // ----\n";
var_dump($testGrid);
// echo "----// GRID OF THE SUMS // ---\n";
// $sum=$testGrid->sumCalc();
// var_dump($sum);
// echo "----- //  NEXT GRID   // -----\n";
// $testGrid->basicRule($sum);
// var_dump($testGrid->nextGrid);

$file = __DIR__. DIRECTORY_SEPARATOR . 'grid';
$testGrid->loadGrid($file);

echo "---------//  LOADED GRID //------\n";
var_dump($testGrid->mainGrid);

