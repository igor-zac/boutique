<?php

function checkActivePage(string $str){
    //Fonction responsable d'identifier la page actuelle pour la mettre en valeur dans la barre de navigation
    $path = explode('/',$_SERVER['PHP_SELF']);
    if(end($path) == $str) {
        echo 'active';
    }


}

function displayActivePage(){
    //Fonction affichant le nom de la page en haut de celle ci, en prenant le nom du fichier
    $path = explode('/',$_SERVER['PHP_SELF']);
    $file = explode(".",end($path));
    $page = $file[0];
    echo $page;

}
?>

<!-- Fichier se chargeant d'afficher le header et la navigation de la boutique, communs entre les différentes pages -->
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
