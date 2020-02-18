<?php
include('functions.php');
session_start();

$bdd = dbConnect();

$panier = unserialize($_SESSION['panier']);



$req = $bdd->prepare('INSERT INTO client(nom, prenom, adresse, cp, ville) 
                                VALUES(:nom, :prenom, :adresse, :cp, :ville)');
$req->execute($_POST);

$id = $bdd->lastInsertId();
$commande = substr(sha1(serialize($id).date("YmdHis").$_SESSION['panier']), 0, 10);
$total=$_SESSION['total'];

$req = $bdd->prepare('INSERT INTO commande(commande, date, idClient, montant)
                                VALUES(:commande, CURRENT_DATE(), :idClient, :montant)');
$req->execute(array(
    'commande' => $commande,
    'idClient' => $id,
    'montant' => $total
));


$id = $bdd->lastInsertId();
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
    <!-- Inclus le fichier functions.php et appelle ses fonctions pour générer les blocs article de la page -->
    <p class="titreRecap mt-5"><u>Votre commande a bien été passée!</u></p>

</body>
</html>
