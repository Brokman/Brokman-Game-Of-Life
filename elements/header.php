<?php require_once dirname(__DIR__). DIRECTORY_SEPARATOR .'class'. DIRECTORY_SEPARATOR. 'function.php' ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?? 'Page'?></title>
        <link rel="icon" type="image/ico" href="/data/img/logo.ico">

        <!-- GOOGLEFONT -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="/elements/style.css">

    </head>
        
    <body>
        <div class="container">
            <div class=header-stick-area>
                <nav class="navbar">
                    <a href="/index.php" class="nav-link nav-bar-logo">
                        <img src="/data/img/logo.png" />
                    </a>
                    <ul class="nav-link-container">
                            <?= nav_menu('nav-link') ?>
                    </ul>
                </nav>
