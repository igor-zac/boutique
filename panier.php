
<?php
include("functions.php");

session_start();

$errorTable = [];


$bdd = dbConnect();



if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['empty_cart'])){

        $articles_choisis=[];

    } elseif (isset($_GET['add'])){

        $articles_choisis=[];

        if(isset($_GET['articles'])) {
            foreach ($_GET['articles'] as $article){
                $articles_choisis[$article] = ['nom' => $article, 'quantite' => 1];
            }
        }

    } elseif (isset($_SESSION['panier'])){
        $articles_choisis = unserialize($_SESSION['panier']);

        if(isset($_GET['delete'])){
            unset($articles_choisis[$_GET['delete']]);
        }

        foreach ($articles_choisis as $key => $article){
            if(isset($_GET[$key])){
                $getkey = $_GET[$key];
                if ($getkey > 0){
                    if ($getkey < 1){
                        $articles_choisis[$key]['quantite'] = 1;
                    } else {
                        $articles_choisis[$key]['quantite'] = $getkey;
                    }
                    $errorTable[$article['nom']]='';
                } else {
                    $errorTable[$article['nom']] = "Veuillez entrer un nombre supérieur à 0";
                }
            } else {
                $errorTable[$article['nom']] = '';
            }
        }
    }

foreach ($articles_choisis as $article){
    if(!isset($errorTable[$article['nom']])){
        $errorTable[$article['nom']] = '';
    }
}


}



$panier = serialize($articles_choisis);
$_SESSION['panier'] = $panier;

$total = coutTotal($bdd, $articles_choisis);

$totalPanier = $total['panier'];
$fraisDePort = $total['frais'];

$_SESSION['total']=$totalPanier*100;





?>

<!doctype html>

<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Mon panier</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <?php include("entete.php"); ?>
        <!-- Inclus le fichier functions.php et appelle ses fonctions pour générer les blocs article de la page -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" class="block">

        <?php
        if(!empty($articles_choisis)) {

            afficherPanier($bdd, $articles_choisis, $errorTable);
            ?>
            <p class="total"><strong>Prix :</strong> <?= number_format($totalPanier, 2) ?> €</p>
            <p class="total"><strong>Frais de port :</strong> <?= number_format($fraisDePort, 2) ?> €</p>
            <p class="total"><strong>Total commande :</strong> <?= number_format($totalPanier + $fraisDePort, 2) ?> €</p>

            <div class="bouton">
                <button type="submit" name="recalc" class="btn btn-secondary">Recalculer</button>
            </div>

            <div class="bouton">
                <a class="btn btn-primary" href="infosClient.php">Continuer</a>
            </div>
            <?php

        } else {
            ?>
            <p>Le panier est vide </p>
            <?php
        }
        ?>


        </form>
    </body>
</html>