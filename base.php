<?php
//Definition d'une fonction pour créer un tableau vide
//On prend une array de colonne et dans chaque cellule on place une array de ligne
function gridBuild(array $cols, array $rows){
    for ($i=0; $i < count($cols) ; $i++) { 
        $cols[$i] = $rows;
    }
    return $cols;
}

//On définie 2 variable pour le nombre de lignes et colonnes souhaitées
$colNum = 25;
$rowNum = 25;

//On détermine une fonction où l'on va pouvoir générer notre tableau à partir du nombre de ligne et colonnes
function setup(int $col, int $row){
    //On défnit les array qui vont traduire nos int en array
    $colarrs = [];
    $rowarrs = [];
    //On a notre array de colonnes vides
    for ($i=0; $i < $col; $i++) { 
        $colarrs[] = 0;
    }
    //Puis notre array de lignes vides
    for ($i=0; $i < $row; $i++) { 
        $rowarrs[] = 0;
    }
    //Maintenant on compose notre tableau vide avec notre fonction défini en amont
    $grid = gridBuild($colarrs, $rowarrs);
    for ($i=0; $i < count($colarrs); $i++) { 
        for ($j=0; $j < count($rowarrs); $j++) { 
            //On va aléatoirement définir les cellules du tableau comme vide ou non (0 ou 1)
            $grid[$i][$j] = floor(random_int(0,1));
        }
    }
    return $grid;
}

//Génération de la grid aléatoir :
$grid = setup($colNum, $rowNum);

$saveGrid=[];
//Si une grid est save, on la charge, sinon on utilise la grid aléatoir :
if(file_exists(__DIR__. '/'. 'grid')){
    $file = fopen((__DIR__. '/'. 'grid'), 'r');
    while ($datalines = fgetcsv($file, 0, ',')) {
        foreach ($datalines as $value) {
            $testGrid[]=(int)$value;
        }
        $gridFile[] = $testGrid;     
        $testGrid = null;
    }
    $saveGrid = $gridFile;
} else {
    $saveGrid = $grid;
}

//On affect les valeurs de la Old sur la Next
$nextGrid = $saveGrid;

for ($i=0; $i < $colNum; $i++) { 
    for ($j=0; $j < $rowNum; $j++) { 
        //Init sum
        $sum=0;
        //
        //LEFT TOP
        if($i-1 >=0 && $j-1 >=0){
            $sum += ($saveGrid[$i-1][$j-1]);
        }
        //LEFT
        if($i-1 >= 0){
            $sum += ($saveGrid[$i-1][$j]);
        }
        //LEFT BTM
        if($i-1 >=0 && $j+1 < $rowNum){
            $sum += ($saveGrid[$i-1][$j+1]);
        }
        ///TOP
        if($j-1 >=0){
            $sum += ($saveGrid[$i][$j-1]);
        }
        ///BTM
        if($j+1 < $rowNum) {
            $sum += ($saveGrid[$i][$j+1]);
        }
        //RIGHT TOP
        if($i+1 < $colNum && $j-1 >=0) {
            $sum += ($saveGrid[$i+1][$j-1]);
        }
        //RIGHT
        if($i+1 < $colNum){
            $sum += ($saveGrid[$i+1][$j]);
        }
        //RIGHT BTM
        if($i+1 < $colNum && $j+1 < $rowNum){
            $sum += ($saveGrid[$i+1][$j+1]);
        }
        //
        //
        if ($saveGrid[$i][$j] === 0 && $sum === 3) 
        {
            $nextGrid[$i][$j] = 1;
        } elseif ($saveGrid[$i][$j] === 1 && $sum < 2 || $saveGrid[$i][$j] === 1 && $sum > 3)
        {
            $nextGrid[$i][$j] = 0;
        }
    }
}

if(!file_exists(__DIR__ . '/' . 'grid')){
    file_put_contents(__DIR__ . '/' . 'grid', '');
}
$file = fopen(__DIR__ . '/' . 'grid', 'r+');
for ($c=0; $c < count($nextGrid); $c++) { 
    fputcsv($file, $nextGrid[$c], ",");
}