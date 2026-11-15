<?php
/**
 * Fonctions utilitaires de la Médiathèque IPNET.
 *
 * Ce fichier regroupe toutes les fonctions utilisées par les pages du site :
 * échappement des données, filtrage, recherche d'un livre, affichage, dates.
 */

declare(strict_types=1);

// Fuseau horaire de Lomé, pour que la date du jour soit correcte.
date_default_timezone_set('Africa/Lome');

/* -------------------------------------------------------------------------
 * Sécurité
 * ---------------------------------------------------------------------- */

/**
 * Échappe une valeur avant de l'afficher dans le HTML.
 * Toutes les données affichées passent par cette fonction, qui utilise
 * htmlspecialchars() pour neutraliser les caractères spéciaux (<, >, &, ", ').
 */
function echapper(string|int|float $valeur): string
{
    return htmlspecialchars((string) $valeur, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* -------------------------------------------------------------------------
 * Catégories
 * ---------------------------------------------------------------------- */

/**
 * Liste des catégories connues : identifiant utilisé dans l'URL => libellé affiché.
 */
function listeCategories(): array
{
    return [
        'developpement-web'         => 'Développement web',
        'programmation'             => 'Programmation',
        'bases-de-donnees'          => 'Bases de données',
        'reseaux-securite'          => 'Réseaux et sécurité',
        'genie-logiciel'            => 'Génie logiciel',
        'intelligence-artificielle' => 'Intelligence artificielle',
    ];
}

/**
 * Renvoie le libellé lisible d'une catégorie (ex. « bases-de-donnees » => « Bases de données »).
 */
function libelleCategorie(string $categorie): string
{
    $categories = listeCategories();

    return $categories[$categorie] ?? ucfirst($categorie);
}

/**
 * Renvoie les catégories réellement présentes dans le tableau de livres,
 * sous la forme identifiant => nombre de livres.
 */
function compterParCategorie(array $livres): array
{
    $compteur = [];

    // On part de la liste officielle pour garder un ordre stable.
    foreach (array_keys(listeCategories()) as $categorie) {
        $compteur[$categorie] = 0;
    }

    foreach ($livres as $livre) {
        $categorie = $livre['categorie'];
        $compteur[$categorie] = ($compteur[$categorie] ?? 0) + 1;
    }

    // On retire les catégories qui ne contiennent aucun livre.
    return array_filter($compteur, fn (int $nombre): bool => $nombre > 0);
}

/**
 * Vérifie qu'une catégorie demandée dans l'URL existe bien dans le catalogue.
 */
function categorieExiste(array $livres, string $categorie): bool
{
    return array_key_exists($categorie, compterParCategorie($livres));
}

/* -------------------------------------------------------------------------
 * Fonctions imposées par le sujet
 * ---------------------------------------------------------------------- */

/**
 * Génère le code HTML d'une carte présentant un livre.
 * Chaque valeur issue du tableau est échappée avec htmlspecialchars().
 */
function afficherLivre(array $livre): string
{
    $id          = (int) $livre['id'];
    $titre       = echapper($livre['titre']);
    $auteur      = echapper($livre['auteur']);
    $annee       = echapper($livre['annee']);
    $categorie   = echapper(libelleCategorie($livre['categorie']));
    $prix        = echapper(formaterPrix($livre['prix']));
    $lienDetail  = echapper(urlDetail($id));
    $couverture  = afficherCouverture($livre);
    $disponibilite = afficherDisponibilite((bool) $livre['disponible']);

    return <<<HTML
        <article class="carte-livre">
            <a class="carte-livre__couverture" href="{$lienDetail}" tabindex="-1" aria-hidden="true">
                {$couverture}
            </a>
            <div class="carte-livre__corps">
                <p class="carte-livre__categorie">{$categorie}</p>
                <h3 class="carte-livre__titre"><a href="{$lienDetail}">{$titre}</a></h3>
                <p class="carte-livre__auteur">{$auteur}</p>
                <dl class="carte-livre__infos">
                    <div><dt>Année</dt><dd>{$annee}</dd></div>
                    <div><dt>Prix</dt><dd>{$prix}</dd></div>
                </dl>
                <div class="carte-livre__pied">
                    {$disponibilite}
                    <a class="lien-fleche" href="{$lienDetail}">Voir la fiche</a>
                </div>
            </div>
        </article>
        HTML;
}

/**
 * Génère une ligne de tableau (<tr>) pour la vue « liste » du catalogue.
 * Les valeurs sont échappées avec htmlspecialchars() via echapper().
 */
function afficherLigneLivre(array $livre): string
{
    $id            = (int) $livre['id'];
    $lienDetail    = echapper(urlDetail($id));
    $reference     = echapper(referenceLivre($id));
    $titre         = echapper($livre['titre']);
    $auteur        = echapper($livre['auteur']);
    $annee         = echapper($livre['annee']);
    $classeCat     = echapper($livre['categorie']);
    $categorie     = echapper(libelleCategorie($livre['categorie']));
    $prix          = echapper(formaterPrix($livre['prix']));
    $disponibilite = afficherDisponibilite((bool) $livre['disponible']);

    return <<<HTML
        <tr>
            <td class="tableau-livres__ref">{$reference}</td>
            <td>
                <div class="tableau-livres__ouvrage">
                    <span class="dos dos--{$classeCat}" aria-hidden="true"></span>
                    <span>
                        <a class="tableau-livres__titre" href="{$lienDetail}">{$titre}</a>
                        <span class="tableau-livres__auteur">{$auteur}</span>
                    </span>
                </div>
            </td>
            <td>{$categorie}</td>
            <td class="nombre">{$annee}</td>
            <td class="nombre">{$prix}</td>
            <td>{$disponibilite}</td>
            <td class="tableau-livres__action"><a class="lien-fleche" href="{$lienDetail}">Fiche</a></td>
        </tr>
        HTML;
}

/**
 * Renvoie uniquement les livres appartenant à la catégorie donnée.
 */
function filtrerParCategorie(array $livres, string $categorie): array
{
    $resultat = [];

    foreach ($livres as $livre) {
        if ($livre['categorie'] === $categorie) {
            $resultat[] = $livre;
        }
    }

    return $resultat;
}

/**
 * Recherche un livre à partir de son identifiant.
 * Renvoie le livre trouvé, ou null s'il n'existe pas.
 */
function trouverLivreParId(array $livres, int $id): ?array
{
    foreach ($livres as $livre) {
        if ($livre['id'] === $id) {
            return $livre;
        }
    }

    return null;
}

/**
 * Compte le nombre de livres actuellement disponibles à l'emprunt.
 */
function compterLivresDisponibles(array $livres): int
{
    $total = 0;

    foreach ($livres as $livre) {
        if ($livre['disponible'] === true) {
            $total++;
        }
    }

    return $total;
}

/* -------------------------------------------------------------------------
 * Aides à l'affichage
 * ---------------------------------------------------------------------- */

/**
 * Formate un prix en francs CFA : 9500 => « 9 500 FCFA ».
 */
function formaterPrix(int|float $prix): string
{
    return number_format((float) $prix, 0, ',', ' ') . ' FCFA';
}

/**
 * Accorde un mot selon le nombre : accorder(1, 'ouvrage') => « 1 ouvrage ».
 */
function accorder(int $nombre, string $singulier, ?string $pluriel = null): string
{
    $mot = $nombre > 1 ? ($pluriel ?? $singulier . 's') : $singulier;

    return $nombre . ' ' . $mot;
}

/**
 * Génère le badge texte de disponibilité (Disponible / Emprunté).
 */
function afficherDisponibilite(bool $disponible): string
{
    if ($disponible) {
        return '<span class="statut statut--disponible">Disponible</span>';
    }

    return '<span class="statut statut--emprunte">Emprunté</span>';
}

/**
 * Génère une couverture typographique en CSS (pas d'image) :
 * la couleur dépend de la catégorie du livre.
 */
function afficherCouverture(array $livre, string $taille = 'normale'): string
{
    $classe    = 'couverture couverture--' . echapper($livre['categorie']) . ' couverture--' . echapper($taille);

    // Titre long : la couverture utilise un corps de texte plus petit.
    if (mb_strlen($livre['titre']) > 30) {
        $classe .= ' couverture--titre-long';
    }
    $titre     = echapper($livre['titre']);
    $auteur    = echapper($livre['auteur']);
    $categorie = echapper(libelleCategorie($livre['categorie']));

    return <<<HTML
        <div class="{$classe}">
            <span class="couverture__collection">{$categorie}</span>
            <span class="couverture__titre">{$titre}</span>
            <span class="couverture__filet"></span>
            <span class="couverture__auteur">{$auteur}</span>
            <span class="couverture__marque">Médiathèque IPNET</span>
        </div>
        HTML;
}

/**
 * Formate la référence interne d'un livre : 3 => « IPN-0003 ».
 */
function referenceLivre(int $id): string
{
    return sprintf('IPN-%04d', $id);
}

/* -------------------------------------------------------------------------
 * Dates
 * ---------------------------------------------------------------------- */

/**
 * Renvoie la date du jour en français, par exemple « jeudi 12 novembre 2026 ».
 * On utilise des tableaux de traduction pour ne pas dépendre de l'extension intl.
 */
function dateDuJourEnFrancais(): string
{
    $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $mois  = [
        1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre',
    ];

    $jourSemaine = $jours[(int) date('w')];
    $jourMois    = (int) date('j');
    $nomMois     = $mois[(int) date('n')];
    $annee       = date('Y');

    // « 1er » pour le premier jour du mois, comme en français courant.
    $jourAffiche = $jourMois === 1 ? '1er' : (string) $jourMois;

    return $jourSemaine . ' ' . $jourAffiche . ' ' . $nomMois . ' ' . $annee;
}

/**
 * Indique si la médiathèque est ouverte en ce moment, d'après les horaires.
 */
function estOuverte(): bool
{
    $jour  = (int) date('N');   // 1 = lundi ... 7 = dimanche
    $heure = (int) date('G') + (int) date('i') / 60;

    if ($jour <= 5) {
        return $heure >= 8 && $heure < 19;
    }

    if ($jour === 6) {
        return $heure >= 9 && $heure < 13;
    }

    return false;
}

/* -------------------------------------------------------------------------
 * Recherche, tri et filtres du catalogue (paramètres GET q, tri, dispo)
 * ---------------------------------------------------------------------- */

// Longueur maximale acceptée pour le texte recherché.
const LONGUEUR_MAX_RECHERCHE = 60;

/**
 * Liste blanche des tris autorisés : valeur dans l'URL => libellé affiché.
 */
function optionsTri(): array
{
    return [
        'defaut'     => 'Par défaut',
        'titre'      => 'Titre (A à Z)',
        'auteur'     => 'Auteur (A à Z)',
        'annee-desc' => 'Année : plus récents',
        'annee-asc'  => 'Année : plus anciens',
        'prix-asc'   => 'Prix croissant',
        'prix-desc'  => 'Prix décroissant',
    ];
}

/**
 * Met un texte en minuscules et retire les accents, pour comparer
 * « etranger » et « Étranger » sans tenir compte de la casse ni des accents.
 */
function normaliserTexte(string $texte): string
{
    $texte = mb_strtolower($texte, 'UTF-8');

    return strtr($texte, [
        'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ç' => 'c', 'é' => 'e', 'è' => 'e',
        'ê' => 'e', 'ë' => 'e', 'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o',
        'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ÿ' => 'y', 'œ' => 'oe', 'æ' => 'ae',
        '’' => "'",
    ]);
}

/**
 * Renvoie les livres dont le titre ou l'auteur contient tous les mots recherchés.
 */
function rechercherLivres(array $livres, string $recherche): array
{
    $mots = array_filter(explode(' ', normaliserTexte($recherche)), fn (string $mot): bool => $mot !== '');

    if ($mots === []) {
        return $livres;
    }

    $resultat = [];

    foreach ($livres as $livre) {
        $texte = normaliserTexte($livre['titre'] . ' ' . $livre['auteur']);
        $trouve = true;

        foreach ($mots as $mot) {
            if (!str_contains($texte, $mot)) {
                $trouve = false;
                break;
            }
        }

        if ($trouve) {
            $resultat[] = $livre;
        }
    }

    return $resultat;
}

/**
 * Ne conserve que les livres disponibles à l'emprunt.
 */
function filtrerDisponibles(array $livres): array
{
    return array_values(array_filter($livres, fn (array $livre): bool => $livre['disponible'] === true));
}

/**
 * Trie les livres selon une option de la liste blanche optionsTri().
 */
function trierLivres(array $livres, string $tri): array
{
    usort($livres, function (array $a, array $b) use ($tri): int {
        return match ($tri) {
            'titre'      => strcmp(normaliserTexte($a['titre']), normaliserTexte($b['titre'])),
            'auteur'     => strcmp(normaliserTexte($a['auteur']), normaliserTexte($b['auteur'])),
            'annee-desc' => $b['annee'] <=> $a['annee'],
            'annee-asc'  => $a['annee'] <=> $b['annee'],
            'prix-asc'   => $a['prix'] <=> $b['prix'],
            'prix-desc'  => $b['prix'] <=> $a['prix'],
            default      => $a['id'] <=> $b['id'],
        };
    });

    return $livres;
}

/**
 * Lit et valide tous les paramètres GET du catalogue.
 * Les valeurs non conformes sont écartées et un avertissement est préparé.
 */
function lireParametresCatalogue(array $get, array $livres): array
{
    $parametres = [
        'categorie'         => '',
        'q'                 => '',
        'tri'               => 'defaut',
        'dispo'             => false,
        'categorieInvalide' => null,
        'avertissements'    => [],
    ];

    // Catégorie : doit exister dans le catalogue.
    if (isset($get['categorie'])) {
        if (!is_string($get['categorie'])) {
            $parametres['categorieInvalide'] = '';
        } else {
            $categorie = trim($get['categorie']);

            if ($categorie !== '' && categorieExiste($livres, $categorie)) {
                $parametres['categorie'] = $categorie;
            } elseif ($categorie !== '') {
                $parametres['categorieInvalide'] = $categorie;
            }
        }
    }

    // Recherche : texte libre, nettoyé et limité en longueur.
    if (isset($get['q'])) {
        if (!is_string($get['q'])) {
            $parametres['avertissements'][] = 'La recherche transmise n\'est pas valide : elle a été ignorée.';
        } else {
            $recherche = preg_replace('/[\p{C}]+/u', '', $get['q']) ?? '';
            $recherche = trim(preg_replace('/\s+/u', ' ', $recherche) ?? '');

            if (mb_strlen($recherche, 'UTF-8') > LONGUEUR_MAX_RECHERCHE) {
                $recherche = mb_substr($recherche, 0, LONGUEUR_MAX_RECHERCHE, 'UTF-8');
                $parametres['avertissements'][] = 'La recherche a été limitée à ' . LONGUEUR_MAX_RECHERCHE . ' caractères.';
            }

            $parametres['q'] = $recherche;
        }
    }

    // Tri : uniquement les valeurs de la liste blanche.
    if (isset($get['tri']) && $get['tri'] !== '') {
        if (is_string($get['tri']) && array_key_exists($get['tri'], optionsTri())) {
            $parametres['tri'] = $get['tri'];
        } else {
            $parametres['avertissements'][] = 'Le tri demandé n\'est pas reconnu : l\'ordre du catalogue a été appliqué.';
        }
    }

    // Disponibles uniquement : case cochée (valeur « 1 »).
    $parametres['dispo'] = isset($get['dispo']) && $get['dispo'] === '1';

    return $parametres;
}

/**
 * Applique catégorie, recherche, disponibilité puis tri au tableau de livres.
 */
function appliquerParametres(array $livres, array $parametres): array
{
    if ($parametres['categorie'] !== '') {
        $livres = filtrerParCategorie($livres, $parametres['categorie']);
    }

    if ($parametres['q'] !== '') {
        $livres = rechercherLivres($livres, $parametres['q']);
    }

    if ($parametres['dispo']) {
        $livres = filtrerDisponibles($livres);
    }

    return trierLivres($livres, $parametres['tri']);
}

/**
 * Indique si au moins un filtre (ou un tri) est actif.
 */
function filtresActifs(array $parametres): bool
{
    return requeteParametres($parametres) !== '';
}

/**
 * Construit la partie « requête » d'une URL à partir des paramètres actifs.
 * Seules les valeurs différentes des valeurs par défaut sont conservées.
 */
function requeteParametres(array $parametres): string
{
    $requete = [];

    if (($parametres['categorie'] ?? '') !== '') {
        $requete['categorie'] = $parametres['categorie'];
    }
    if (($parametres['q'] ?? '') !== '') {
        $requete['q'] = $parametres['q'];
    }
    if (($parametres['tri'] ?? 'defaut') !== 'defaut') {
        $requete['tri'] = $parametres['tri'];
    }
    if (($parametres['dispo'] ?? false) === true) {
        $requete['dispo'] = '1';
    }

    return http_build_query($requete, '', '&', PHP_QUERY_RFC3986);
}

/**
 * URL du catalogue avec les paramètres actuels, éventuellement modifiés.
 * Attention : l'URL renvoyée doit être échappée avant affichage.
 */
function urlCatalogue(array $parametres, array $modifications = []): string
{
    $requete = requeteParametres(array_merge($parametres, $modifications));

    return 'catalogue.php' . ($requete !== '' ? '?' . $requete : '');
}

/**
 * Mémorise les paramètres de recherche en cours, pour que les liens vers
 * les fiches les conservent (lien « Retour aux résultats »).
 */
function contexteCatalogue(?array $parametres = null): array
{
    static $contexte = [];

    if ($parametres !== null) {
        $contexte = $parametres;
    }

    return $contexte;
}

/**
 * URL de la fiche d'un livre, avec le contexte de recherche en cours.
 */
function urlDetail(int $id): string
{
    $requete = requeteParametres(contexteCatalogue());

    return 'detail.php?id=' . $id . ($requete !== '' ? '&' . $requete : '');
}

/**
 * Génère le formulaire complet de recherche du catalogue (méthode GET).
 */
function afficherFormulaireCatalogue(array $parametres, array $categories): string
{
    $q = echapper($parametres['q']);
    $max = LONGUEUR_MAX_RECHERCHE;

    $optionsCategories = '<option value="">Tous les rayons</option>';
    foreach ($categories as $categorie => $nombre) {
        $selection = $categorie === $parametres['categorie'] ? ' selected' : '';
        $optionsCategories .= '<option value="' . echapper($categorie) . '"' . $selection . '>'
            . echapper(libelleCategorie($categorie)) . ' (' . echapper($nombre) . ')</option>';
    }

    $optionsTri = '';
    foreach (optionsTri() as $valeur => $libelle) {
        $selection = $valeur === $parametres['tri'] ? ' selected' : '';
        $optionsTri .= '<option value="' . echapper($valeur) . '"' . $selection . '>' . echapper($libelle) . '</option>';
    }

    $coche = $parametres['dispo'] ? ' checked' : '';

    return <<<HTML
        <form class="panneau-recherche" action="catalogue.php" method="get" role="search" aria-label="Rechercher dans le catalogue">
            <div class="champ champ--recherche">
                <label class="champ__label" for="q">Titre ou auteur</label>
                <input class="champ__saisie" type="search" id="q" name="q" value="{$q}" maxlength="{$max}"
                       placeholder="Par exemple : PHP, Python, Tanenbaum" autocomplete="off">
            </div>
            <div class="champ">
                <label class="champ__label" for="categorie">Rayon</label>
                <select class="champ__saisie" id="categorie" name="categorie">{$optionsCategories}</select>
            </div>
            <div class="champ">
                <label class="champ__label" for="tri">Trier par</label>
                <select class="champ__saisie" id="tri" name="tri">{$optionsTri}</select>
            </div>
            <div class="case">
                <input type="checkbox" id="dispo" name="dispo" value="1"{$coche}>
                <label for="dispo">Disponibles uniquement</label>
            </div>
            <div class="panneau-recherche__actions">
                <button class="bouton bouton--plein" type="submit">Rechercher</button>
            </div>
        </form>
        HTML;
}

/**
 * Liens rapides par rayon : chaque lien conserve la recherche, le tri et la disponibilité.
 */
function afficherLiensRayons(array $parametres, array $categories, int $total, string $classe = 'filtres__liens'): string
{
    $html = '<ul class="' . echapper($classe) . '" aria-label="Filtrer par rayon">';

    $tous = $parametres['categorie'] === '' && $parametres['categorieInvalide'] === null;
    $html .= '<li><a class="pastille' . ($tous ? ' est-actif' : '') . '"'
        . ($tous ? ' aria-current="true"' : '')
        . ' href="' . echapper(urlCatalogue($parametres, ['categorie' => ''])) . '">'
        . '<span>Tous les rayons</span> <span class="pastille__nombre">' . echapper($total) . '</span></a></li>';

    foreach ($categories as $categorie => $nombre) {
        $actif = $categorie === $parametres['categorie'];
        $html .= '<li><a class="pastille' . ($actif ? ' est-actif' : '') . '"'
            . ($actif ? ' aria-current="true"' : '')
            . ' href="' . echapper(urlCatalogue($parametres, ['categorie' => $categorie])) . '">'
            . '<span>' . echapper(libelleCategorie($categorie)) . '</span> '
            . '<span class="pastille__nombre">' . echapper($nombre) . '</span></a></li>';
    }

    return $html . '</ul>';
}

/**
 * Résumé des filtres actifs sous forme de puces, chacune avec un lien pour la retirer.
 */
function afficherFiltresActifs(array $parametres): string
{
    $puces = [];

    if ($parametres['q'] !== '') {
        $puces[] = ['Recherche : « ' . $parametres['q'] . ' »', ['q' => '']];
    }
    if ($parametres['categorie'] !== '') {
        $puces[] = ['Rayon : ' . libelleCategorie($parametres['categorie']), ['categorie' => '']];
    }
    if ($parametres['dispo']) {
        $puces[] = ['Disponibles uniquement', ['dispo' => false]];
    }
    if ($parametres['tri'] !== 'defaut') {
        $puces[] = ['Tri : ' . optionsTri()[$parametres['tri']], ['tri' => 'defaut']];
    }

    if ($puces === []) {
        return '';
    }

    $html = '<div class="filtres-actifs" role="group" aria-label="Filtres actifs">'
        . '<span class="filtres-actifs__titre">Filtres actifs</span><ul class="filtres-actifs__liste">';

    foreach ($puces as [$libelle, $modification]) {
        $html .= '<li><a class="puce" href="' . echapper(urlCatalogue($parametres, $modification)) . '#resultats"'
            . ' aria-label="Retirer le filtre ' . echapper($libelle) . '">'
            . '<span>' . echapper($libelle) . '</span><span class="puce__croix" aria-hidden="true">&times;</span></a></li>';
    }

    return $html . '</ul><a class="lien-reinitialiser" href="catalogue.php">Tout réinitialiser</a></div>';
}

/**
 * Message affiché lorsqu'aucun livre ne correspond aux critères, avec des pistes.
 */
function afficherEtatVide(array $parametres, array $categories): string
{
    $pistes = [];

    if ($parametres['q'] !== '') {
        $pistes[] = 'Vérifiez l\'orthographe, ou essayez un seul mot du titre ou le nom de l\'auteur.';
    }
    if ($parametres['categorie'] !== '' && $parametres['q'] !== '') {
        $pistes[] = '<a href="' . echapper(urlCatalogue($parametres, ['categorie' => ''])) . '#resultats">'
            . 'Chercher « ' . echapper($parametres['q']) . ' » dans tous les rayons</a>';
    }
    if ($parametres['dispo']) {
        $pistes[] = '<a href="' . echapper(urlCatalogue($parametres, ['dispo' => false])) . '#resultats">'
            . 'Inclure les ouvrages actuellement empruntés</a>';
    }

    $rayons = '';
    foreach (array_keys($categories) as $categorie) {
        $rayons .= '<li><a href="catalogue.php?categorie=' . echapper(urlencode($categorie)) . '">'
            . echapper(libelleCategorie($categorie)) . '</a></li>';
    }

    $listePistes = '';
    foreach ($pistes as $piste) {
        $listePistes .= '<li>' . $piste . '</li>';
    }

    return <<<HTML
        <div class="etat-vide">
            <p class="etat-vide__code">Aucun résultat</p>
            <h2 class="etat-vide__titre">Aucun ouvrage ne correspond à votre recherche.</h2>
            <ul class="etat-vide__pistes">{$listePistes}</ul>
            <p class="etat-vide__rayons-titre">Parcourir un rayon :</p>
            <ul class="etat-vide__rayons">{$rayons}</ul>
            <a class="bouton bouton--plein" href="catalogue.php">Réinitialiser la recherche</a>
        </div>
        HTML;
}

/**
 * Aides proposées sur les pages d'erreur : recherche rapide et liens vers les rayons.
 */
function afficherAidesErreur(array $categories): string
{
    $rayons = '';
    foreach (array_keys($categories) as $categorie) {
        $rayons .= '<li><a href="catalogue.php?categorie=' . echapper(urlencode($categorie)) . '">'
            . echapper(libelleCategorie($categorie)) . '</a></li>';
    }

    $max = LONGUEUR_MAX_RECHERCHE;

    return <<<HTML
        <div class="aide-erreur">
            <form class="recherche-rapide" action="catalogue.php" method="get" role="search" aria-label="Recherche rapide">
                <label class="champ__label" for="q-aide">Rechercher un titre ou un auteur</label>
                <div class="recherche-rapide__ligne">
                    <input class="champ__saisie" type="search" id="q-aide" name="q" maxlength="{$max}" placeholder="Par exemple : PHP, Python">
                    <button class="bouton bouton--contour" type="submit">Rechercher</button>
                </div>
            </form>
            <p class="aide-erreur__titre">Ou parcourir un rayon :</p>
            <ul class="aide-erreur__rayons">{$rayons}</ul>
        </div>
        HTML;
}

/**
 * Trouve le livre précédent et le livre suivant dans une liste de résultats.
 */
function voisinsLivre(array $liste, int $id): array
{
    $liste = array_values($liste);
    $voisins = ['precedent' => null, 'suivant' => null, 'position' => 0, 'total' => count($liste)];

    foreach ($liste as $index => $livre) {
        if ($livre['id'] === $id) {
            $voisins['position']  = $index + 1;
            $voisins['precedent'] = $liste[$index - 1] ?? null;
            $voisins['suivant']   = $liste[$index + 1] ?? null;
            break;
        }
    }

    return $voisins;
}

/**
 * Navigation « ouvrage précédent / suivant » sur la page détail.
 */
function afficherNavigationVoisins(array $voisins): string
{
    $html = '<nav class="voisins" aria-label="Ouvrages précédent et suivant">';

    if ($voisins['precedent'] !== null) {
        $html .= '<a class="voisins__lien voisins__lien--precedent" rel="prev" href="'
            . echapper(urlDetail($voisins['precedent']['id'])) . '">'
            . '<span class="voisins__sens">Ouvrage précédent</span>'
            . '<span class="voisins__titre">' . echapper($voisins['precedent']['titre']) . '</span></a>';
    } else {
        $html .= '<span class="voisins__lien voisins__lien--vide"></span>';
    }

    $html .= '<p class="voisins__position">Ouvrage ' . echapper($voisins['position'])
        . ' sur ' . echapper($voisins['total']) . '</p>';

    if ($voisins['suivant'] !== null) {
        $html .= '<a class="voisins__lien voisins__lien--suivant" rel="next" href="'
            . echapper(urlDetail($voisins['suivant']['id'])) . '">'
            . '<span class="voisins__sens">Ouvrage suivant</span>'
            . '<span class="voisins__titre">' . echapper($voisins['suivant']['titre']) . '</span></a>';
    } else {
        $html .= '<span class="voisins__lien voisins__lien--vide"></span>';
    }

    return $html . '</nav>';
}

/**
 * Encadré détaillé sur la disponibilité : comment emprunter, ou quand revenir.
 */
function afficherDisponibiliteDetaillee(array $livre): string
{
    if ($livre['disponible']) {
        return <<<HTML
            <div class="disponibilite disponibilite--oui">
                <p class="disponibilite__titre">En rayon, disponible à l'emprunt</p>
                <ol class="disponibilite__etapes">
                    <li>Notez la référence de l'ouvrage indiquée ci-dessus.</li>
                    <li>Présentez-vous à la banque d'accueil avec votre carte étudiante.</li>
                    <li>Le prêt est de quinze jours, renouvelable une fois.</li>
                </ol>
            </div>
            HTML;
    }

    return <<<HTML
        <div class="disponibilite disponibilite--non">
            <p class="disponibilite__titre">Actuellement emprunté</p>
            <p>
                Cet ouvrage est en prêt. Il revient en rayon au plus tard quinze jours après
                son emprunt, ou trente jours en cas de renouvellement. Vous pouvez le réserver
                à la banque d'accueil : vous serez prévenu dès son retour.
            </p>
        </div>
        HTML;
}
