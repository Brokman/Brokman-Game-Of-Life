<?php

function nav_item (string $link, string $name, string $linkClass): string
{
    $class = 'nav-item';
    if ($_SERVER['SCRIPT_NAME'] === $link) {
        $class .= ' active';
    }
    return '<li class="' . $class . '">
                <a class="' . $linkClass. '" href="' . $link . '">' .$name . '</a>
                </li>
                ';
}

function nav_menu (string $linkClass = ''): string 
{
    return
        nav_item('/index.php', 'Home', $linkClass) .
        nav_item('/game.php', 'Game', $linkClass) .
        nav_item('/about.php', 'About', $linkClass);
}

function option (string $index, string $value): string
{
    return <<<HTML
    <option value="$index,$value"> $index </option>

HTML;
}

function saveTitle (string $title): void
{
    $titleFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'grid-name';
    if(!file_exists($titleFile)){
        file_put_contents($titleFile, $title);
    }else{
        unlink($titleFile);
        file_put_contents($titleFile, $title);
    }
}

function loadTitle(string $titleFile) : string 
{
    if(file_exists($titleFile)){
        return file_get_contents($titleFile);
    }
    else {
        echo "$titleFile" . PHP_EOL . "counldn't be found";
        return NULL;
    }
}

function defaultNameMaker(int $col, int $row, int $liv) : string
{
    return $liv . " cells in " . $col . " by " . $row . " grid";
}