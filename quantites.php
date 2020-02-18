<?php

function affichageQuantite(int $quantite, $errTable, string $nom_article)
{
    /*Fonction prenant en paramètre:
        - la quantité souhaitée pour l'article $quantite
        - les éventuelles erreurs liées aux quantités $errTable
        - le nom de l'article $nom_article
    La fonction affiche la quantité, l'erreur si il y en a, et le bouton de suppression permettant
    d'enlever l'article du panier
    Le bouton et le champ quantité ont en attributs le nom de l'article pour facilement effectuer
    la correspondance par la suite   */
    ?>

    <div class="quantities d-flex flex-column">
        <div class="d-flex flex-column">
                <div class="quantity d-flex flex-row">
                    <label>Quantité :
                        <input type="number" name="<?= $nom_article ?>" value="<?= $quantite ?>">

                    </label>


                </div>
                <p class="error"><?php echo htmlspecialchars($errTable[$nom_article]) ?></p>
        </div>
        <button type="submit" name="delete" value="<?= $nom_article ?>" class="btn btn-danger">Supprimer</button>
    </div>
    <?php
}
?>

