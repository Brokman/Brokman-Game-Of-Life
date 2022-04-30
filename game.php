<?php
$title = 'The Game';
require 'elements/header.php';
//function require via header.php
require 'class/Grid.php';
$nextGridFile = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'grid-active';
$gridTitleFile = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'grid-name';
$statFile = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'grid-stat';

//ARRIVER SUR PAGE :
//CAS 1 POST AVEC UN FICHIER CHARGER
if(isset($_POST['grid-file'])) {
    $gridFilePostElements = explode(',', $_POST['grid-file']);
    $gridObject = new Grid(10,10);
    $gridObject->loadGrid($gridFilePostElements[1]);
    $grid = $gridObject->mainGrid;
    //RECUPERER LE NOM DANS POST 
    // ndlr((on aurrait put récupéré par config avec valeur de file))
    $gridTitle = htmlentities($gridFilePostElements[0]);
    saveTitle($gridTitle);
    //CLEAR LES STATS
    $gridObject->clearStats($statFile);
    //PUIS INIT LES STATS
    $gridObject->calcStats($statFile);
}
//SINON CAS 2 : POST AVEC VALEURS DE COL/ROW
elseif(isset($_POST['col-num']) && isset($_POST['row-num'])){
    $postCol = (int)$_POST['col-num'];
    $postRow = (int)$_POST['row-num'];
    $gridObject = new Grid($postCol, $postRow);
    $gridObject->randomGrid();
    $grid = $gridObject->mainGrid;
    //SI IL Y A UN TITRE
    if(isset($_POST['grid-title']) && !empty($_POST['grid-title'])) {
        $gridTitle = htmlentities($_POST['grid-title']);
        saveTitle($gridTitle);
    }else {
        //CREER UN NOM AVEC LES STATS INITIALES
        $gridTitle = defaultNameMaker($gridObject->colNum, $gridObject->rowNum, $gridObject->livingCell);
        saveTitle($gridTitle); 
    }
    //CLEAR LES STATS
    $gridObject->clearStats($statFile);
    //PUIS INIT LES STATS
    $gridObject->calcStats($statFile);
//SINON CAS 3 : ON A CLICKER SUR NEXT
}elseif(isset($_GET['action'])){
    //((elseif EN PREVISION DE CREER D'AUTRES ACTIONS))
    if($_GET['action'] === 'next') {
        $gridObject = new Grid(10,10);
        $gridObject->loadGrid($nextGridFile);
        $grid = $gridObject->mainGrid;
        $gridTitle = loadTitle($gridTitleFile);
        //CALCUL STATS
        $gridObject->calcStats($statFile);
    }
//SINON DERNIER CAS : ON ACCEDER AU LIEN EN DIRECT :
//GENERER UNE GRIDE RANDOM 25/25
}else {
    $gridObject = new Grid(25,25);
    $gridObject->randomGrid();
    $grid = $gridObject->mainGrid;
    //CLEAR LES STATS
    $gridObject->clearStats($statFile);
    //PUIS INIT LES STATS
    $gridObject->calcStats($statFile);
    //CREER UN NOM AVEC LES STATS INITIALES
    $gridTitle = defaultNameMaker($gridObject->colNum, $gridObject->rowNum, $gridObject->livingCell);
    saveTitle($gridTitle); 
}

//LOGIQUE :
//CALCUL DE NEXT GRID
$gridOfSums = $gridObject->sumCalc();
$gridObject->basicRule($gridOfSums);
$gridObject->saveGrid($nextGridFile);
?>

<h1><?=$gridTitle ?? 'Grid of Game' ?></h1>
<!-- END OF STICKY AREA -->
</div>

<div class="game-page-container">
    <div class="grid-container">
        <div class="grid-table">
            <table
                <?php if($gridObject->rowNum === $gridObject->colNum): ?>
                    <?php $tableHeight = 80;
                    $tableWidth = 80;?>
                    <?= ' style="height:'.$tableHeight.'vmin; width:'.$tableWidth.'vmin;"'?>
                <?php elseif($gridObject->rowNum > $gridObject->colNum): ?>
                    <?php $tableHeight = 90;
                    $tableWidth = (($gridObject->colNum * 90) / $gridObject->rowNum);?>
                    <?= ' style="height:'.$tableHeight.'vmin; width:'.$tableWidth.'vmin;"'?>
                <?php elseif($gridObject->rowNum < $gridObject->colNum): ?>
                    <?php $tableWidth = 90;
                    $tableHeight = (($gridObject->rowNum * 90) / $gridObject->colNum);?>
                    <?= ' style="height:'.$tableHeight.'vmin; width:'.$tableWidth.'vmin;"'?>
                <?php endif ?>
            >
                <?php for ($i=0; $i < $gridObject->rowNum; $i++) : ?>
                    <tr style="border: 0;">
                    <?php for ($j=0; $j < $gridObject->colNum; $j++) : ?> 
                        <td style="border: 1px solid; <?php if($grid[$i][$j] === 1) {echo 'background-color:black;';}?>">   </td>
                    <?php endfor ?>
                    </tr>
                <?php endfor ?>
            </table>
        </div>
        <div class="grid-button">
            <a href="game.php?action=next" class="btn-next">
                <button type="submit">Next ></button>
            </a>
        </div>        
    </div>

    <div class="stat-container">
        <div class="stat-object">
            <div class="stat-title stat-first-title"><h2>Born Cells :</h2></div>
            <div class="stat-object-items">
                <div class="stat-item">Last Turn : <strong><?= $gridObject->lastTurnBorn ?? '0'?></strong></div>
                <div class="stat-item">Ever : <strong><?= $gridObject->everBornCell ?? '0'?></strong></div>
            </div>
        </div>
        <div class="stat-object">
            <div class="stat-title"><h2>Dead Cells :</h2></div>
            <div class="stat-object-items">
                <div class="stat-item">Last Turn : <strong><?= $gridObject->lastTurnDead ?? '0'?></strong></div>
                <div class="stat-item">Ever : <strong><?= $gridObject->everDeadCell ?? '0'?></strong></div>
            </div>
        </div>
        <div class="stat-object">
        <div class="stat-title"><h2>Living Cells :</h2></div>
            <div class="stat-object-items">
                <div class="stat-item">Currently : <strong><?= $gridObject->livingCell ?? '0'?></strong></div>
            </div>
        </div>
    </div>
</div>


<?php require 'elements/footer.php' ?>