<?php
$title = 'Homepage';
require_once './data/config.php';
require 'elements/header.php';
?>

<h1>Brokman's Game of Life</h1>

<!-- END OF STICKY AREA -->
</div>

<form action="/game.php" method="post" class="form-main">
    <h3>Select your grid options :</h3>
    <label for="title" class="form-label mt-0">
        <div class="label-title">Grid Title :</div>
        <div class="input-control">
            <input type="text" id="title" name="grid-title" placeholder="((optional))">     
        </div>   
    </label>
    <div class="form-num-group">
        <label for="col-num" class="form-label">
            <div class="label-title">Number of colones :</div>
            <div class="input-control">
                <input type="number" id="col-num" name="col-num" required>     
            </div>   
        </label>
        <label for="row-num" class="form-label">
            <div class="label-title">Number of rows :</div>
            <div class="input-control">
                <input type="number" id="row-num" name="row-num" required>   
            </div>
        </label>
    </div>
    <button type="submit" class="btn">Generate</button>
</form>
<div class="separating-sentence">
    <p>- or -</p>
</div>
<form action="/game.php" method="post" class="form-main">
    <h3>Select an existing grid :</h3>
    <div class="form-select-group">
        <select name="grid-file" id="grid-file" >
            <?php foreach(GRIDS as $k => $file): ?>
                <?= option($k, $file) ?>
            <?php endforeach ?>
        </select>
    </div>
    <button type="submit" class="btn">Load</button>
</form>



<? 
require 'elements/footer.php';
?>