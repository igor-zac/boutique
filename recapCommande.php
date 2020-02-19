<?php
include('functions.php');
session_start();

$bdd = dbConnect();

$panier = unserialize($_SESSION['panier']);


//On ajoute les informations récupérées par POST depuis infosClient.php dans la table client de la bdd
$req = $bdd->prepare('INSERT INTO client(nom, prenom, adresse, cp, ville) 
                                VALUES(:nom, :prenom, :adresse, :cp, :ville)');
$req->execute($_POST);
//============================================================================================================

//On récupère l'id du client précédemment ajouté
$id = $bdd->lastInsertId();
//On génère un numéro de commande de 10 caractères unique en faisant un SHA-1 sur l'id client, le datetime et le contenu du panier
$commande = substr(sha1(serialize($id).date("YmdHis").$_SESSION['panier']), 0, 10);
//On récupère le total de la commande depuis la variable $_SESSION
$total=$_SESSION['total'];

//On ajoute la commande créée avec les infos précédentes dans la table commande
$req = $bdd->prepare('INSERT INTO commande(commande, date, idClient, montant)
                                VALUES(:commande, CURRENT_DATE(), :idClient, :montant)');
$req->execute(array(
    'commande' => $commande,
    'idClient' => $id,
    'montant' => $total
));
//==================================================================================================================


//On récupère l'id de la commande insérée juste avant
$id = $bdd->lastInsertId();
//Pour chaque article de la commande, on ajoute l'idProduit, l'idCommande et la quantité article dans la table cmdproduit
foreach ($panier as $article){
    $data=$bdd->prepare('SELECT id FROM produit WHERE nom=:nom');
    $data->execute(array('nom' => $article['nom']));
    $idProduit=$data->fetch();
    $quantite=intval($article['quantite']);



    $req = $bdd->prepare('INSERT INTO cmdproduit(idProduit, idCommande, quantite)
                         VALUES(:idProduit, :idCommande, :quantite)');
    $req->execute(array('idProduit' => $idProduit['id'],
                        'idCommande' => $id,
                        'quantite' => $quantite
    ));
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

        <p class="titreRecap mt-5"><u>Votre commande a bien été passée!</u></p>

        <a href="catalogue.php?empty_choice=" class="btn btn-primary">Revenir au catalogue</a>
    </body>
</html>
