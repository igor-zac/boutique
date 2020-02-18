<?php

include("quantites.php"); //Fichier contenant la fonction affichageQuantite()





function afficherCatalogue($array){
    /* Fonction responsable d'afficher tous les articles disponibles
    elle prend en paramètre un tableau représentant les articles actuellement dans le panier, afin de
    cocher les checkbox correspondantes */

    $bdd=dbConnect();
    $sql = 'SELECT nom, nomImage, poids, prix FROM produit WHERE dispo=1'; //Requete récupérant tous les articles dispo
    foreach($bdd->query($sql) as $row) {
        /*On fait appel a la fonction afficheArticles() pour afficher un bloc article
        La fonction prend en paramètre une une entrée parmi celles récupérées dans la requete ci-dessus et
        le tableau des articles actuellement dans le panier
        le 3eme parametre est mis a true car on veut générer des checkbox
        Les paramètres 2 et 4 sont mis a null, car nous n'avons pas d'erreur ou de quantités dans le catalogue */
        afficheArticles($row, null, true, null, $array);
    }
}



function afficherPanier($bdd, $articles, $errors){
    /* Fonction s'occupant d'afficher les articles dans le panier
    Prend en paramètre:
        - la connexion à la base de données $bdd,
        - le tableau des articles choisis $articles
        - le tableau des erreurs liées aux quantités saisies incorrectes $errors
    */

    foreach ($articles as $article){
        //Pour chaque article de $articles, on récupère le nom, l'image, le poids et le prix depuis la bdd
        $req = $bdd->prepare('SELECT nom, nomImage, poids, prix FROM produit WHERE nom = :article');
        $req->bindParam(':article', $article['nom']);
        $req->execute();

        $row = $req->fetch();

        /*On passe ensuite les infos récupérées a la fonction afficheArticles() pour afficher les articles choisis
        On passe en paramètres:
            - le tableau informations relatives a l'article a afficher stockées dans $row
            - le tableau des erreurs relatives aux quantités choisies $errors
            - false en 3eme paramètre car on ne veut pas générer de checkbox
            - la quantité de l'article en cours de traitement $article['quantite"]
            - null en dernier paramètre car il n'y a aucune checkbox a cocher
        */
        afficheArticles($row, $errors, false, $article['quantite'], null);
    }
}


function afficheArticles($row, $errTable,  bool $check, $quantite, $articles_check){
            /*Fonction responsable d'afficher un 'bloc article'
            - $row est le tableau contenant les informations relatives à l'article
            - $errTable est le tableau contenant les erreurs liées à la saisie des quantités dans le panier
            - $check est le booléen associé a la présence ou non de checkboxs dans le bloc article
            - $quantite est l'entier représentant la quantité souhaitée pour l'article
            - $articles_check est le tableau des articles actuellement dans le panier, utilisé pour cocher les checkboxs correspondantes
            */

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
                /*Si $check est mis à true, on génère une checkbox dans le bloc article
                Sinon, on est dans le panier, on affiche la quantité, le bouton de suppression de l'article et les
                éventuelles erreurs */
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
                    //Fonction responsable d'afficher les quantités, le bouton de suppression de l'article ainsi que les
                    //erreurs liées aux quantités
                    affichageQuantite($quantite, $errTable, $nom_article);

                }
                ?>

            </div>

            <?php


}

function coutTotal($bdd, $articles){
    /* Fonction responsable de calculer le montant de la somme des articles ainsi que les frais de ports
    Elle prend en paramètre la connexion à la bdd $bdd, et la liste des articles choisis $articles */
    $totalPanier=0;
    $totalPoids=0;

    foreach($articles as $article){
        // Pour chaque article, on récupère le poids et le prix, puis on met a jour le montant et le poids du panier
        $req = $bdd->prepare('SELECT prix, poids FROM produit WHERE nom=:article');
        $req->execute(array(
           'article' => $article['nom']
        ));
        $data=$req->fetch();
        $totalPanier+=$data['prix']*$article['quantite'];
        $totalPoids+=$data['poids']*$article['quantite'];
    }

    //Calcul des frais de ports en fonction du poids total
    if($totalPoids<=500){
        $frais=500;
    } else if ($totalPoids<=2000){
        $frais=$totalPanier/10;
    } else {
        $frais=0;
    }

    //On stock le prix du panier et les frais de port dans $total
    //Les valeurs sont en centimes, on les fait passer en euro en divisant par 100
    $total=[
        'panier' => $totalPanier/100,
        'frais' => $frais/100
    ];

    return ($total);
}



function dbConnect()
{
    //Fonction retournant l'objet représentant la connexion a la base de données
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

