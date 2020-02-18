<?php


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
        <form action="recapCommande.php" method="POST" class="form-client my-5 py-5">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" placeholder="Ex : Bourgeois">
                </div>
                <div class="form-group col-md-6">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" placeholder="Ex : Alexis">
                </div>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" placeholder="1234 Main St">
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" id="ville">
                </div>
                <div class="form-group col-md-4">
                    <label for="cp">Code Postal</label>
                    <input type="text" class="form-control" id="cp">
                </div>
            </div>
            <div class="form-row justify-content-between my-3">
                <a href="panier.php" class="btn btn-secondary">Précédent</a>
                <button type="submit" class="btn btn-primary">Valider la commande</button>
            </div>

        </form>
    </body>
</html>
