<?php
/**
 * Page détail : fiche complète d'un livre.
 * Exemple d'URL : detail.php?id=3
 * Les paramètres de recherche du catalogue (q, categorie, tri, dispo) peuvent
 * être transmis : ils servent au lien « Retour aux résultats » et à la
 * navigation entre ouvrages précédent et suivant.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/fonctions.php';
require_once __DIR__ . '/data/livres.php';

$livre  = null;
$erreur = null;   // [titre, explication] si l'identifiant pose problème

// --- Validation du paramètre GET « id » ---------------------------------
if (!isset($_GET['id']) || !is_string($_GET['id']) || trim($_GET['id']) === '') {
    $erreur = [
        'Aucun ouvrage sélectionné',
        'L\'adresse de cette page doit préciser l\'identifiant d\'un ouvrage, par exemple detail.php?id=3.',
    ];
} else {
    // filter_var() n'accepte qu'un entier strictement positif (refuse « abc », « 2.5 », « -1 »...).
    $id = filter_var(trim($_GET['id']), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($id === false) {
        $erreur = [
            'Identifiant invalide',
            'L\'identifiant « ' . trim($_GET['id']) . ' » n\'est pas valide : il doit s\'agir d\'un nombre entier positif.',
        ];
    } else {
        $livre = trouverLivreParId($livres, $id);

        if ($livre === null) {
            $erreur = [
                'Ouvrage introuvable',
                'Aucun ouvrage ne porte l\'identifiant ' . $id . ' dans le catalogue de la médiathèque.',
            ];
        }
    }
}

if ($erreur !== null) {
    http_response_code(404);
}

$categories = compterParCategorie($livres);

// --- Contexte de recherche transmis par le catalogue ---------------------
$parametres = lireParametresCatalogue($_GET, $livres);
$parametres['categorieInvalide'] = null;   // une catégorie inconnue est simplement ignorée ici
$parametres['avertissements']    = [];
contexteCatalogue($parametres);

$voisins     = null;
$suggestions = [];

if ($livre !== null) {
    $resultats = appliquerParametres($livres, $parametres);

    // Si le livre ne fait pas partie des résultats, on revient au catalogue complet.
    if (voisinsLivre($resultats, $livre['id'])['position'] === 0) {
        $parametres = lireParametresCatalogue([], $livres);
        contexteCatalogue($parametres);
        $resultats = appliquerParametres($livres, $parametres);
    }

    $voisins = voisinsLivre($resultats, $livre['id']);

    // Autres ouvrages du même rayon, pour prolonger la lecture.
    foreach (filtrerParCategorie($livres, $livre['categorie']) as $autre) {
        if ($autre['id'] !== $livre['id']) {
            $suggestions[] = $autre;
        }
    }
    $suggestions = array_slice($suggestions, 0, 4);
}

$urlRetour     = urlCatalogue($parametres) . '#resultats';
$libelleRetour = filtresActifs($parametres) ? 'Retour aux résultats' : 'Retour au catalogue';

if ($livre !== null) {
    $titrePage       = $livre['titre'] . ', ' . $livre['auteur'];
    $descriptionPage = 'Fiche de « ' . $livre['titre'] . ' » de ' . $livre['auteur'] . ' (' . $livre['annee']
                     . ') : disponibilité, prix et référence à la Médiathèque IPNET.';
} else {
    $titrePage       = 'Ouvrage introuvable';
    $descriptionPage = 'Cet ouvrage est introuvable dans le catalogue de la Médiathèque IPNET.';
}
$pageActive = 'catalogue';

require __DIR__ . '/includes/header.php';
?>

<?php if ($erreur !== null) : ?>

    <section class="section">
        <div class="conteneur">
            <nav class="fil-ariane" aria-label="Fil d'Ariane">
                <ol>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="catalogue.php">Catalogue</a></li>
                    <li><span aria-current="page">Fiche ouvrage</span></li>
                </ol>
            </nav>

            <div class="message-erreur message-erreur--page">
                <p class="message-erreur__code"><?= echapper($erreur[0]) ?></p>
                <h1 class="message-erreur__titre">Cette fiche ne peut pas être affichée.</h1>
                <p><?= echapper($erreur[1]) ?></p>
                <?= afficherAidesErreur($categories) ?>
                <div class="actions actions--centre">
                    <a class="bouton bouton--plein" href="catalogue.php">Revenir au catalogue complet</a>
                    <a class="bouton bouton--contour" href="index.php">Page d'accueil</a>
                </div>
            </div>
        </div>
    </section>

<?php else : ?>

    <section class="section section--fiche">
        <div class="conteneur">
            <nav class="fil-ariane" aria-label="Fil d'Ariane">
                <ol>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="catalogue.php">Catalogue</a></li>
                    <li>
                        <a href="catalogue.php?categorie=<?= echapper(urlencode($livre['categorie'])) ?>">
                            <?= echapper(libelleCategorie($livre['categorie'])) ?>
                        </a>
                    </li>
                    <li><span aria-current="page"><?= echapper($livre['titre']) ?></span></li>
                </ol>
            </nav>

            <article class="fiche" aria-labelledby="titre-fiche">
                <div class="fiche__visuel">
                    <?= afficherCouverture($livre, 'grande') ?>
                </div>

                <div class="fiche__contenu">
                    <a class="lien-retour fiche__retour" href="<?= echapper($urlRetour) ?>"><?= echapper($libelleRetour) ?></a>

                    <p class="surtitre"><?= echapper(libelleCategorie($livre['categorie'])) ?></p>
                    <h1 class="fiche__titre" id="titre-fiche"><?= echapper($livre['titre']) ?></h1>
                    <p class="fiche__auteur">par <?= echapper($livre['auteur']) ?></p>

                    <?php if (isset($livre['resume'])) : ?>
                        <p class="fiche__resume"><?= echapper($livre['resume']) ?></p>
                    <?php endif; ?>

                    <dl class="fiche__donnees">
                        <div>
                            <dt>Titre</dt>
                            <dd><?= echapper($livre['titre']) ?></dd>
                        </div>
                        <div>
                            <dt>Auteur</dt>
                            <dd><?= echapper($livre['auteur']) ?></dd>
                        </div>
                        <?php if (isset($livre['editeur']) && is_string($livre['editeur'])) : ?>
                            <div>
                                <dt>Éditeur</dt>
                                <dd><?= echapper($livre['editeur']) ?></dd>
                            </div>
                        <?php endif; ?>
                        <div>
                            <dt>Année d'édition</dt>
                            <dd><?= echapper($livre['annee']) ?></dd>
                        </div>
                        <div>
                            <dt>Catégorie</dt>
                            <dd><?= echapper(libelleCategorie($livre['categorie'])) ?></dd>
                        </div>
                        <div>
                            <dt>Prix</dt>
                            <dd><?= echapper(formaterPrix($livre['prix'])) ?></dd>
                        </div>
                        <div>
                            <dt>Disponibilité</dt>
                            <dd><?= afficherDisponibilite($livre['disponible']) ?></dd>
                        </div>
                        <div>
                            <dt>Référence</dt>
                            <dd><?= echapper(referenceLivre($livre['id'])) ?></dd>
                        </div>
                    </dl>

                    <!-- Disponibilité expliquée : comment emprunter ou quand revenir -->
                    <?= afficherDisponibiliteDetaillee($livre) ?>
                </div>
            </article>

            <?= afficherNavigationVoisins($voisins) ?>
        </div>
    </section>

    <?php if ($suggestions !== []) : ?>
        <section class="section section--teintee" aria-labelledby="titre-suggestions">
            <div class="conteneur">
                <div class="section__entete">
                    <h2 class="titre-section" id="titre-suggestions">Dans le même rayon</h2>
                    <a class="lien-fleche" href="catalogue.php?categorie=<?= echapper(urlencode($livre['categorie'])) ?>">
                        Tout le rayon <?= echapper(libelleCategorie($livre['categorie'])) ?>
                    </a>
                </div>
                <div class="grille-livres">
                    <?php foreach ($suggestions as $suggestion) : ?>
                        <?= afficherLivre($suggestion) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
