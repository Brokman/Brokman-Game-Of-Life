<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require 'base.php' ?>
</head>
<body>
    <!-- <table>
        <?php 
            for ($i=0; $i < $colNum; $i++) { 
                echo '<tr>';
                for ($j=0; $j < $rowNum; $j++) { 
                    echo '<td>' . $grid[$i][$j] . '</td>';
                }
            echo '</tr>';
            } ?>
    </table> -->

    <hr>

    <table style="border: 1px solid; width:800px; height:800px; empty-cells: show; table-layout:fixed;">
        <?php for ($i=0; $i < $colNum; $i++) : ?>
            <tr style="border: 0;">
            <?php for ($j=0; $j < $rowNum; $j++) : ?> 
                <td style="border: 0.1px solid; <?php if($nextGrid[$i][$j] === 1) {echo 'background-color:black;';}?>">   </td>
            <?php endfor ?>
            </tr>
        <?php endfor ?>
    </table>
            
    <form action="" method="post">
        <button type="submit">Next</button>
    </form>


    <hr>
</body>
</html>