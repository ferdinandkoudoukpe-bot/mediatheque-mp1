<?php
/**
 * Page d'accueil de la Médiathèque IPNET.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/fonctions.php';
require_once __DIR__ . '/data/livres.php';

// Chiffres calculés à partir du tableau de livres.
$nombreTotal       = count($livres);
$nombreDisponibles = compterLivresDisponibles($livres);
$nombreEmpruntes   = $nombreTotal - $nombreDisponibles;
$categories        = compterParCategorie($livres);

// Sélection : les quatre parutions les plus récentes du fonds.
$selection = $livres;
usort($selection, fn (array $a, array $b): int => $b['annee'] <=> $a['annee']);
$selection = array_slice($selection, 0, 4);

$titrePage  = 'Accueil';
$descriptionPage = 'Médiathèque IPNET : consultez le catalogue des ouvrages d\'informatique (développement web, programmation, bases de données, réseaux, génie logiciel, IA) et leur disponibilité.';
$pageActive      = 'accueil';

require __DIR__ . '/includes/header.php';
?>

<section class="accueil-intro">
    <div class="conteneur accueil-intro__grille">
        <div class="accueil-intro__texte">
            <p class="surtitre">Bienvenue à la médiathèque</p>
            <h1 class="titre-principal">Les références de vos cours d'informatique, au cœur du campus.</h1>
            <p class="chapo">
                La Médiathèque IPNET réunit les ouvrages qui accompagnent les filières de
                Licence (GL, WIM, DWM, IG et IA) : développement web, programmation, bases de
                données, réseaux, génie logiciel et intelligence artificielle. Empruntez-les
                pour préparer vos TP, réviser vos UE et aller plus loin.
            </p>
        </div>

        <!-- Bloc de recherche : envoie la catégorie choisie au catalogue (GET) -->
        <form class="recherche" action="catalogue.php" method="get">
            <h2 class="recherche__titre">Rechercher dans le catalogue</h2>
            <p class="recherche__texte">
                <?= echapper(accorder($nombreTotal, 'ouvrage')) ?> au catalogue, dont
                <?= echapper(accorder($nombreDisponibles, 'disponible')) ?> à l'emprunt.
            </p>
            <label class="recherche__label" for="q-accueil">Titre ou auteur</label>
            <input class="champ__saisie recherche__saisie" type="search" id="q-accueil" name="q" maxlength="60"
                   placeholder="Par exemple : PHP, Python, Tanenbaum">
            <label class="recherche__label" for="categorie-accueil">Rayon</label>
            <div class="recherche__ligne">
                <select id="categorie-accueil" name="categorie">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $categorie => $nombre) : ?>
                        <option value="<?= echapper($categorie) ?>">
                            <?= echapper(libelleCategorie($categorie)) ?> (<?= echapper($nombre) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="bouton bouton--or" type="submit">Rechercher</button>
            </div>
            <a class="recherche__lien" href="catalogue.php">Consulter le catalogue complet</a>
        </form>
    </div>
</section>

<section class="bande-chiffres" aria-label="La médiathèque en chiffres">
    <div class="conteneur">
        <dl class="chiffres__liste">
            <div>
                <dt>Ouvrages au catalogue</dt>
                <dd><?= echapper($nombreTotal) ?></dd>
            </div>
            <div>
                <dt>Disponibles à l'emprunt</dt>
                <dd><?= echapper($nombreDisponibles) ?></dd>
            </div>
            <div>
                <dt>Actuellement empruntés</dt>
                <dd><?= echapper($nombreEmpruntes) ?></dd>
            </div>
            <div>
                <dt>Rayons thématiques</dt>
                <dd><?= echapper(count($categories)) ?></dd>
            </div>
        </dl>
    </div>
</section>

<section class="section">
    <div class="conteneur">
        <div class="section__entete">
            <h2 class="titre-section">Parcourir les rayons</h2>
            <a class="lien-fleche" href="catalogue.php">Tout le catalogue</a>
        </div>

        <ul class="rayons">
            <?php foreach ($categories as $categorie => $nombre) : ?>
                <li>
                    <a class="rayon rayon--<?= echapper($categorie) ?>"
                       href="catalogue.php?categorie=<?= echapper(urlencode($categorie)) ?>">
                        <span class="rayon__nom"><?= echapper(libelleCategorie($categorie)) ?></span>
                        <span class="rayon__nombre"><?= echapper(accorder($nombre, 'titre')) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="section section--teintee">
    <div class="conteneur">
        <div class="section__entete">
            <h2 class="titre-section">Parutions les plus récentes du fonds</h2>
            <a class="lien-fleche" href="catalogue.php">Voir tous les ouvrages</a>
        </div>

        <div class="grille-livres">
            <?php foreach ($selection as $livre) : ?>
                <?= afficherLivre($livre) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="conteneur services">
        <div class="service">
            <h3 class="service__titre">Emprunter</h3>
            <p>Sur présentation de la carte étudiante, jusqu'à trois ouvrages pour une durée de quinze jours, renouvelable une fois.</p>
        </div>
        <div class="service">
            <h3 class="service__titre">Travailler sur place</h3>
            <p>Une salle de lecture silencieuse et des tables de travail en groupe sont ouvertes à tous les étudiants inscrits.</p>
        </div>
        <div class="service">
            <h3 class="service__titre">Être conseillé</h3>
            <p>L'équipe de la médiathèque vous aide à trouver un ouvrage, préparer une bibliographie ou citer vos sources.</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
