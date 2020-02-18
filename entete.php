<?php

function checkActivePage(string $str){

    $path = explode('/',$_SERVER['PHP_SELF']);
    if(end($path) == $str) {
        echo 'active';
    }


}

function displayActivePage(){
    $path = explode('/',$_SERVER['PHP_SELF']);
    $file = explode(".",end($path));
    $page = $file[0];
    echo $page;

}
?>

<header>
    <h1>Nom de la boutique</h1>
</header>
<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-right justify-content-end">
    <ul class="navbar-nav">
        <li class="nav-item
        <?php checkActivePage('catalogue.php'); ?> ">
            <a class="nav-link" href="catalogue.php">Catalogue</a>
        </li>
        <li class="nav-item
        <?php checkActivePage('panier.php'); ?> ">
            <a class="nav-link" href="panier.php">Mon panier</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="panier.php?empty_cart=">Vider le panier</a>
        </li>
    </ul>
</nav>

<p class="text-capitalize display-4 nom_page"><?php displayActivePage(); ?></p>
