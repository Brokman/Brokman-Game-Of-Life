<?php
class Grid {

    public $colNum;
    public $rowNum;

    public $mainGrid;
    public $nextGrid;

    public $livingCell = 0;
    public $lastTurnBorn = 0;
    public $everBornCell = 0;
    public $lastTurnDead = 0;
    public $everDeadCell = 0;
    private $lastTurnLiving = 0;

    //Crée une grid vide
    public function __construct(int $col, int $row) 
    {
        //traduire les int en array:
        $this->colNum = $col;
        $this->rowNum = $row;
        $arrCols = [];
        $arrRows = [];
        for ($i=0; $i < $col; $i++) { 
            $arrCols[] = 0;
        }
        for ($i=0; $i < $row; $i++) { 
            $arrRows[] = $arrCols;
        }
        $this->mainGrid = $arrRows;

    }

    //Créer une randomGrid
    public function randomGrid()
    {
        $grid = $this->mainGrid;

        foreach ($grid as $k=> $rows) {
            foreach ($rows as $l=> $cell) {
                $grid[$k][$l] = (int)floor(random_int(0,1));
                $this->livingCell += $grid[$k][$l];
            }
            unset($cell);
        }
        unset($rows);

        $this->mainGrid = $grid;
    }

    //Charger une grid
    public function loadGrid(string $file) 
    {
        if(file_exists($file)){
            $file = fopen(($file), 'r');
            while ($datalines = fgetcsv($file, 0, ',')) {
                foreach ($datalines as $value) {
                    $contentainer[]=(int)$value;
                }
                $gridFile[] = $contentainer;     
                $contentainer = null;
            }
            $this->rowNum = count($gridFile);
            $this->colNum = count($gridFile[0]);
            $this->mainGrid = $gridFile;
        }
        else {
            echo "$file" . "\n counldn't be found, random grid generated insted";
            $this->randomGrid();
        }
    }


    //Sauvegarder une grid
    public function saveGrid(string $file)
    {
        if(!file_exists($file)){
            file_put_contents($file, '');
        }else{
            unlink($file);
            file_put_contents($file, '');
        }
        $file = fopen($file, 'r+');
        for ($c=0; $c < count($this->nextGrid); $c++) { 
            fputcsv($file, $this->nextGrid[$c], ",");
        }
    }


    //Calcul de la sum 
    public function sumCalc()
    {
        //Les paramètres
        $grid = $this->mainGrid;
        $col = $this->colNum;
        $row = $this->rowNum;
        (array)$allNeighbours=[];
        (array)$lineNeighbours=[];
        (int)$sum = 0;
        for ($i=0; $i < $row; $i++) { 
            for ($j=0; $j < $col; $j++) { 
                //
                //LEFT TOP
                if($i-1 >=0 && $j-1 >=0){
                    $sum += ($grid[$i-1][$j-1]);
                }
                //LEFT
                if($i-1 >= 0){
                    $sum += ($grid[$i-1][$j]);
                }
                //LEFT BTM
                if($i-1 >=0 && $j+1 < $col){
                    $sum += ($grid[$i-1][$j+1]);
                }
                ///TOP
                if($j-1 >=0){
                    $sum += ($grid[$i][$j-1]);
                }
                ///BTM
                if($j+1 < $col) {
                    $sum += ($grid[$i][$j+1]);
                }
                //RIGHT TOP
                if($i+1 < $row && $j-1 >=0) {
                    $sum += ($grid[$i+1][$j-1]);
                }
                //RIGHT
                if($i+1 < $row){
                    $sum += ($grid[$i+1][$j]);
                }
                //RIGHT BTM
                if($i+1 < $row && $j+1 < $col){
                    $sum += ($grid[$i+1][$j+1]);
                }
                ////FIN TEST SUR UNE CASE
                $lineNeighbours[]=$sum;
                $sum = 0;
            }
            $allNeighbours[]=$lineNeighbours;
            $lineNeighbours=[];
        }
        return $allNeighbours;
    }


    //Règle à appliquer
    public function basicRule(array $sumGrid)
    {
        $grid = $this->mainGrid;
        $this->nextGrid = $grid;

        for ($i=0; $i < $this->rowNum; $i++) { 
            for ($j=0; $j < $this->colNum; $j++) { 
                if ($grid[$i][$j] === 0 && $sumGrid[$i][$j] === 3) {
                    $this->nextGrid[$i][$j] = 1;
                }
                elseif ($grid[$i][$j] === 1 && $sumGrid[$i][$j] < 2 || $grid[$i][$j] === 1 && $sumGrid[$i][$j] > 3)
                {
                    $this->nextGrid[$i][$j] = 0;
                }
            }
        }
    }



    //Calcul des cellules mortes ou en vie
    //1 - Function de récupération des données :
    private function getCellStats(string $statFile)
    {
        //On récupère l'état des cellules
        $grid = $this->mainGrid;
        $this->livingCell = 0;
        foreach ($grid as $k=> $rows) {
            foreach ($rows as $l=> $cell) {
                ////
                $this->livingCell += $grid[$k][$l];
            }
            unset($cell);
        }
        
        //On charge le fichier de l'état précédent
        if(!file_exists($statFile)){
            file_put_contents($statFile, '0,0,0');
        }
        //On recupère les anciennes stats :
        $stats = explode(',', file_get_contents($statFile));
        $this->everBornCell = intval($stats[0]);
        $this->everDeadCell = intval($stats[1]);
        $this->lastTurnLiving = intval($stats[2]);
    }

    //2 - Function pour clear les données :
    public function clearStats(string $statFile)
    {
        if(file_exists($statFile)){
            unlink($statFile);
        }
    }

    // PRINCIPAL //
    //3 - Calculs :
    public function calcStats(string $statFile)
    {
        $this->getCellStats($statFile);
        //Les anciennes données de everBorn, everDead et living sont chargées
        //Et les current living sont à jour

        //Init une array pour simplifier l'écriture des calculs
        $statArrs = [
            $this->livingCell,
            $this->lastTurnLiving
        ];

        $this->lastTurnBorn = $statArrs[0] - $statArrs[1];
        if($this->lastTurnBorn < 0){
            $this->lastTurnBorn = 0;
        }

        $this->lastTurnDead = $statArrs[0] - $statArrs[1];
        if($this->lastTurnDead < 0) {
            $this->lastTurnDead = abs($this->lastTurnDead);
        } else {
            $this->lastTurnDead = 0;
        }

        $this->everBornCell += $this->lastTurnBorn;
        $this->everDeadCell += $this->lastTurnDead;

        $this->saveCellStats($statFile);
    }

    //4- Save le current living cell
    private function saveCellStats(string $statFile)
    {
        $this->clearStats($statFile);
        file_put_contents($statFile, "$this->everBornCell,$this->everDeadCell,$this->livingCell");
    }
}



//créer un tableau vide
// function gridBuild(array $cols, array $rows){
//     for ($i=0; $i < count($cols) ; $i++) { 
//         $cols[$i] = $rows;
//     }
//     return $cols;
// }

//variable pour le nombre de lignes et colonnes
// $colNum = 25;
// $rowNum = 25;

//générer notre tableau à partir du nombre de ligne et colonnes
// function setup(int $col, int $row){
    // //On défnit les array qui vont traduire nos int en array
    // $colarrs = [];
    // $rowarrs = [];
    // //On a notre array de colonnes vides
    // for ($i=0; $i < $col; $i++) { 
    //     $colarrs[] = 0;
    // }
    // //Puis notre array de lignes vides
    // for ($i=0; $i < $row; $i++) { 
    //     $rowarrs[] = 0;
    // }
    //Maintenant on compose notre tableau vide avec notre fonction défini en amont
    // $grid = gridBuild($colarrs, $rowarrs);
    // for ($i=0; $i < count($colarrs); $i++) { 
    //     for ($j=0; $j < count($rowarrs); $j++) { 
    //         //On va aléatoirement définir les cellules du tableau comme vide ou non (0 ou 1)
    //         $grid[$i][$j] = floor(random_int(0,1));
    //     }
    // }
    // return $grid;
// }

// $saveGrid=[];
//Si une grid est save, on la charge, sinon on utilise la grid aléatoir :
// if(file_exists(__DIR__. '/'. 'grid')){
//     $file = fopen((__DIR__. '/'. 'grid'), 'r');
//     while ($datalines = fgetcsv($file, 0, ',')) {
//         foreach ($datalines as $value) {
//             $testGrid[]=(int)$value;
//         }
//         $gridFile[] = $testGrid;     
//         $testGrid = null;
//     }
//     $saveGrid = $gridFile;
// } else {
//     $saveGrid = $grid;
//     // Génération de la grid aléatoir :
//     // $grid = setup($colNum, $rowNum);
// }

//On affect les valeurs de la Old sur la Next
// $nextGrid = $saveGrid;








// for ($i=0; $i < $colNum; $i++) { 
//     for ($j=0; $j < $rowNum; $j++) { 
//         //Init sum
//         $sum=0;
//         //
//         //LEFT TOP
//         if($i-1 >=0 && $j-1 >=0){
//             $sum += ($saveGrid[$i-1][$j-1]);
//         }
//         //LEFT
//         if($i-1 >= 0){
//             $sum += ($saveGrid[$i-1][$j]);
//         }
//         //LEFT BTM
//         if($i-1 >=0 && $j+1 < $rowNum){
//             $sum += ($saveGrid[$i-1][$j+1]);
//         }
//         ///TOP
//         if($j-1 >=0){
//             $sum += ($saveGrid[$i][$j-1]);
//         }
//         ///BTM
//         if($j+1 < $rowNum) {
//             $sum += ($saveGrid[$i][$j+1]);
//         }
//         //RIGHT TOP
//         if($i+1 < $colNum && $j-1 >=0) {
//             $sum += ($saveGrid[$i+1][$j-1]);
//         }
//         //RIGHT
//         if($i+1 < $colNum){
//             $sum += ($saveGrid[$i+1][$j]);
//         }
//         //RIGHT BTM
//         if($i+1 < $colNum && $j+1 < $rowNum){
//             $sum += ($saveGrid[$i+1][$j+1]);
//         }
//         //
//         //
//         if ($saveGrid[$i][$j] === 0 && $sum === 3) 
//         {
//             $nextGrid[$i][$j] = 1;
//         } elseif ($saveGrid[$i][$j] === 1 && $sum < 2 || $saveGrid[$i][$j] === 1 && $sum > 3)
//         {
//             $nextGrid[$i][$j] = 0;
//         }
//     }
// }

// if(!file_exists(__DIR__ . '/' . 'grid')){
//     file_put_contents(__DIR__ . '/' . 'grid', '');
// }
// $file = fopen(__DIR__ . '/' . 'grid', 'r+');
// for ($c=0; $c < count($nextGrid); $c++) { 
//     fputcsv($file, $nextGrid[$c], ",");
// }