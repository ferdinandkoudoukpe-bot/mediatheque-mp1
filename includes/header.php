<?php
/**
 * En-tête commun à toutes les pages.
 *
 * Variables attendues (définies dans la page avant l'include) :
 *  - $titrePage  : titre affiché dans l'onglet du navigateur ;
 *  - $pageActive : identifiant de la page courante, pour surligner le menu.
 */

declare(strict_types=1);

$titrePage  = $titrePage ?? 'Médiathèque IPNET';
$pageActive = $pageActive ?? '';
$descriptionPage = $descriptionPage
    ?? 'Catalogue en ligne de la Médiathèque IPNET : ouvrages de développement web, programmation, bases de données, réseaux, génie logiciel et intelligence artificielle.';

// Entrées du menu principal : identifiant => [lien, libellé].
$menu = [
    'accueil'   => ['index.php', 'Accueil'],
    'catalogue' => ['catalogue.php', 'Catalogue'],
    'apropos'   => ['apropos.php', 'À propos'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= echapper($descriptionPage) ?>">
    <title><?= echapper($titrePage) ?> | Médiathèque IPNET</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="haut">
    <a class="lien-evitement" href="#contenu">Aller au contenu</a>

    <div class="bandeau-infos">
        <div class="conteneur bandeau-infos__contenu">
            <span>Lun. – ven. 8 h – 19 h &middot; Sam. 9 h – 13 h</span>
            <span class="bandeau-infos__etat <?= estOuverte() ? 'est-ouvert' : 'est-ferme' ?>">
                <?= estOuverte() ? 'Ouvert actuellement' : 'Fermé actuellement' ?>
            </span>
        </div>
    </div>

    <header class="entete">
        <div class="conteneur entete__contenu">
            <a class="logo" href="index.php">
                <span class="logo__monogramme" aria-hidden="true">MI</span>
                <span class="logo__texte">
                    <span class="logo__nom">Médiathèque IPNET</span>
                    <span class="logo__baseline">Bibliothèque universitaire &middot; Lomé</span>
                </span>
            </a>

            <!-- Menu mobile sans JavaScript : la case à cocher ouvre et ferme la navigation -->
            <input class="menu-bascule visuellement-cache" type="checkbox" id="menu-bascule"
                   aria-label="Afficher ou masquer le menu" aria-controls="menu-principal">
            <label class="menu-bouton" for="menu-bascule" aria-hidden="true">
                <span class="menu-bouton__barres"></span>
                <span class="menu-bouton__texte">Menu</span>
            </label>

            <nav class="navigation" id="menu-principal" aria-label="Navigation principale">
                <ul>
                    <?php foreach ($menu as $cle => [$lien, $libelle]) : ?>
                        <li>
                            <a href="<?= echapper($lien) ?>"
                               class="<?= $cle === $pageActive ? 'est-actif' : '' ?>"
                               <?= $cle === $pageActive ? 'aria-current="page"' : '' ?>>
                                <?= echapper($libelle) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main id="contenu" class="principal">
