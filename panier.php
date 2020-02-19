
<?php
include("functions.php");

session_start();

$errorTable = []; //Tableau contenant les erreurs relatives a la saisie des quantités sur les articles


$bdd = dbConnect();



if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['empty_cart'])){
    // Si le champ empty_cart de $_GET est initialisé, on rend le tableau d'articles choisis vide
        $articles_choisis=[];

    } elseif (isset($_GET['add'])){
    // Si le champ add est initialisé, alors on arrive depuis la page catalogue.php
        $articles_choisis=[];

        if(isset($_GET['articles'])) {
            /* Chaque article coché a son nom ajouté au tableau $_GET['articles']
            On initialise articles choisis avec pour chaque article son nom et une quantité à 1 */
            foreach ($_GET['articles'] as $article){
                $articles_choisis[$article] = ['nom' => $article, 'quantite' => 1];
            }
        }

    } elseif (isset($_SESSION['panier'])){
        // Si add ou empty_cart ne sont pas initialisés, on vérifie si le panier est sauvegardé dans la session
        $articles_choisis = unserialize($_SESSION['panier']);
        //Si il est stocké, on le déserialize dans $articles_choisis

        if(isset($_GET['delete'])){ // Si un bouton supprimé a été cliqué, on enleve l'article correspondant des articles choisis
            unset($articles_choisis[$_GET['delete']]);
        }

        foreach ($articles_choisis as $key => $article){
            /* Pour chacun des articles restants dans le panier, on vérifie la cohérence des quantités choisies
            En cas d'informations incorrects, on stock la cause de l'erreur dans le tableau $errorTable, la clé du tableau
            étant le nom de l'article concerné par l'erreur */
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
    /* Pour tous les article n'ayant pas d'erreur suite aux vérifs précédentes, on initialise le champ erreur
    correspondant a ''
    */
    if(!isset($errorTable[$article['nom']])){
        $errorTable[$article['nom']] = '';
    }
}


}


//Après toutes les vérifs, on sérialize le panier et on le stock dans $_SESSION['panier']
$panier = serialize($articles_choisis);
$_SESSION['panier'] = $panier;

$total = coutTotal($bdd, $articles_choisis); // Calcul du montant des articles et des frais de port

$totalPanier = $total['panier'];
$fraisDePort = $total['frais'];

//On stock le total du panier dans $_SESSION pour pouvoir le récupérer lors de la validation de la commande
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
            //Si le panier n'est pas vide, on affiche les différents prix, le bouton recalculer et le bouton continuer

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
            // Si le panier est vide, on affiche simplement le message ce-dessous
            ?>
            <p>Le panier est vide </p>
            <?php
        }
        ?>


        </form>
    </body>
</html>