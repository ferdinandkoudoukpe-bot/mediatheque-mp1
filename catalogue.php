<?php
/**
 * Page catalogue : liste des livres avec recherche, filtres et tri.
 * Exemples d'URL :
 *   catalogue.php?categorie=developpement-web
 *   catalogue.php?q=python&tri=annee-desc&dispo=1
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/fonctions.php';
require_once __DIR__ . '/data/livres.php';

$categories = compterParCategorie($livres);

// --- Lecture et validation des paramètres GET (categorie, q, tri, dispo) ---
$parametres = lireParametresCatalogue($_GET, $livres);
contexteCatalogue($parametres);

if ($parametres['categorieInvalide'] !== null) {
    // Catégorie inconnue : code 404 et message d'erreur, aucun livre affiché.
    http_response_code(404);
    $livresAffiches = [];
} else {
    $livresAffiches = appliquerParametres($livres, $parametres);
}

$nombreAffiches   = count($livresAffiches);
$categorieChoisie = $parametres['categorie'];

// Titre de l'onglet selon le contexte.
if ($parametres['categorieInvalide'] !== null) {
    $titrePage = 'Catégorie introuvable';
} elseif ($parametres['q'] !== '') {
    $titrePage = 'Recherche « ' . $parametres['q'] . ' »';
} elseif ($categorieChoisie !== '') {
    $titrePage = 'Catalogue : ' . libelleCategorie($categorieChoisie);
} else {
    $titrePage = 'Catalogue';
}

$descriptionPage = 'Catalogue de la Médiathèque IPNET : recherchez un livre par titre ou auteur, filtrez par rayon et par disponibilité.';
$pageActive      = 'catalogue';

require __DIR__ . '/includes/header.php';
?>

<section class="entete-page">
    <div class="conteneur">
        <nav class="fil-ariane" aria-label="Fil d'Ariane">
            <ol>
                <li><a href="index.php">Accueil</a></li>
                <?php if ($categorieChoisie !== '') : ?>
                    <li><a href="catalogue.php">Catalogue</a></li>
                    <li><span aria-current="page"><?= echapper(libelleCategorie($categorieChoisie)) ?></span></li>
                <?php else : ?>
                    <li><span aria-current="page">Catalogue</span></li>
                <?php endif; ?>
            </ol>
        </nav>

        <h1 class="titre-page">
            <?= $categorieChoisie !== '' ? echapper(libelleCategorie($categorieChoisie)) : 'Le catalogue' ?>
        </h1>
        <p class="chapo">
            Recherchez un ouvrage par titre ou par auteur, puis affinez par rayon, disponibilité ou ordre d'affichage.
        </p>

        <div class="filtres">
            <?= afficherFormulaireCatalogue($parametres, $categories) ?>
            <?= afficherLiensRayons($parametres, $categories, count($livres)) ?>
        </div>
    </div>
</section>

<section class="section section--compacte section--catalogue">
    <div class="conteneur">
        <?php if ($parametres['categorieInvalide'] !== null) : ?>
            <!-- Message affiché lorsque la catégorie demandée n'existe pas -->
            <div class="message-erreur" id="resultats">
                <p class="message-erreur__code">Catégorie introuvable</p>
                <h2 class="message-erreur__titre">Ce rayon n'existe pas dans notre catalogue.</h2>
                <p>
                    <?php if ($parametres['categorieInvalide'] !== '') : ?>
                        La catégorie « <strong><?= echapper($parametres['categorieInvalide']) ?></strong> » ne correspond à aucun rayon de la médiathèque.
                    <?php else : ?>
                        Le paramètre de catégorie transmis n'est pas valide.
                    <?php endif; ?>
                </p>
                <?= afficherAidesErreur($categories) ?>
                <div class="actions actions--centre">
                    <a class="bouton bouton--plein" href="catalogue.php">Revenir au catalogue complet</a>
                </div>
            </div>
        <?php else : ?>
            <div class="barre-resultats" id="resultats">
                <p class="resultats__nombre" role="status">
                    <strong><?= echapper(accorder($nombreAffiches, 'ouvrage affiché', 'ouvrages affichés')) ?></strong>
                    sur <?= echapper(count($livres)) ?> au catalogue
                </p>
                <?php if (filtresActifs($parametres)) : ?>
                    <a class="lien-retour" href="catalogue.php">Afficher tout le catalogue</a>
                <?php endif; ?>
            </div>

            <?php foreach ($parametres['avertissements'] as $avertissement) : ?>
                <p class="avertissement" role="note"><?= echapper($avertissement) ?></p>
            <?php endforeach; ?>

            <?= afficherFiltresActifs($parametres) ?>

            <?php if ($nombreAffiches === 0) : ?>
                <?= afficherEtatVide($parametres, $categories) ?>
            <?php else : ?>
                <!-- Affichage du catalogue sous forme de tableau HTML -->
                <div class="tableau-conteneur">
                    <table class="tableau-livres">
                        <caption class="visuellement-cache">Ouvrages correspondant à la recherche</caption>
                        <thead>
                            <tr>
                                <th scope="col">Réf.</th>
                                <th scope="col">Titre et auteur</th>
                                <th scope="col">Catégorie</th>
                                <th scope="col" class="nombre">Année</th>
                                <th scope="col" class="nombre">Prix</th>
                                <th scope="col">Disponibilité</th>
                                <th scope="col"><span class="visuellement-cache">Fiche</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($livresAffiches as $livre) : ?>
                                <?= afficherLigneLivre($livre) ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
