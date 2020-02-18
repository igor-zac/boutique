<?php
include("functions.php");

session_start();

$articles_choisis_catalogue =[];

if(isset($_SESSION['panier'])){

    $articles_choisis = unserialize($_SESSION['panier']);

    foreach($articles_choisis as $article){
        $articles_choisis_catalogue[] = $article['nom'];
    }
}




?>

<!doctype html>

<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Titre de la page</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles.css">

    </head>
    <body>
        <?php include("entete.php"); ?>
<!-- Inclus le fichier functions.php et appelle ses fonctions pour générer les blocs article de la page -->
        <form action="panier.php" method="GET" class="block">
            <?php
            afficherCatalogue($articles_choisis_catalogue);

            ?>
            <div class="bouton">
                <button type="submit" name="add" class="btn btn-primary">Ajouter au panier</button>
            </div>
        </form>
    </body>
</html>