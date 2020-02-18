<?php

include("quantites.php");



/*=====================================================================================================
fonction prenant en paramètre le nom de l'article, le nom du fichier jpg et l'entier correspondant au prix
la fonction se charge d'afficher les articles entrés au même format que les fonctions précédentes
====================================================================================================*/

function afficherCatalogue($array){

    $bdd=dbConnect();
    $sql = 'SELECT nom, nomImage, poids, prix FROM produit WHERE dispo=1';
    foreach($bdd->query($sql) as $row) {
        afficheArticles($row, null, true, null, $array);
    }
}

function afficherPanier($bdd, $articles, $errors){
    foreach ($articles as $article){
        $req = $bdd->prepare('SELECT nom, nomImage, poids, prix FROM produit WHERE nom = :article');
        $req->bindParam(':article', $article['nom']);
        $req->execute();

        $row = $req->fetch();
        afficheArticles($row, $errors, false, $article['quantite'], null);
    }
}


function afficheArticles($row, $errTable,  bool $check, $quantite, $articles_check){


            $nom_fichier = $row['nomImage'];
            $nom_article = $row['nom'];
            $prix = $row['prix']/100;
            $poids = $row['poids']/1000;
            ?>

            <div class="article">
                <div>
                    <img class="image_article" src="img/<?= $nom_fichier ?>" alt="L'image de mon article">
                </div>

                <div class="infosArticles">
                    <p class="nomArticle"><?= $nom_article ?></p>
                    <p class="poidsArticle">Poids : <?= $poids ?> kg</p>
                </div>

                <div class="prix">
                    <p><?= $prix ?> €</p>
                </div>

                <?php
                if ($check) { ?>
                    <div class="check">
                        <input type="checkbox" name="articles[]" value="<?= $nom_article ?>"
                            <?php
                            if (in_array($nom_article, $articles_check)) {
                                echo 'checked';
                            }
                            ?> >

                    </div>
                    <?php
                } else {
                    $quant = $quantite;
                    affichageQuantite($quant, $errTable, $nom_article);

                }
                ?>

            </div>

            <?php


}

function coutTotal($bdd, $articles){
    $totalPanier=0;
    $totalPoids=0;

    foreach($articles as $article){
        $req = $bdd->prepare('SELECT prix, poids FROM produit WHERE nom=:article');
        $req->execute(array(
           'article' => $article['nom']
        ));
        $data=$req->fetch();
        $totalPanier+=$data['prix']*$article['quantite'];
        $totalPoids+=$data['poids']*$article['quantite'];
    }


    if($totalPoids<=500){
        $frais=500;
    } else if ($totalPoids<=2000){
        $frais=$totalPanier/10;
    } else {
        $frais=0;
    }

    $total=[
        'panier' => $totalPanier/100,
        'frais' => $frais/100
    ];

    return ($total);
}



function dbConnect()
{
    try
    {
        $bdd = new PDO('mysql:host=localhost;port=3308;dbname=mydb;charset=utf8', 'admin', 'MotDePasse');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    return $bdd;
}
?>

