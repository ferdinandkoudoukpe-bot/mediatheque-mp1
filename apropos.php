<?php
/**
 * Page « À propos » : présentation statique de la médiathèque.
 * La date du jour est générée par la fonction dateDuJourEnFrancais().
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/fonctions.php';

$titrePage  = 'À propos';
$descriptionPage = 'Présentation de la Médiathèque IPNET : mission, collections d\'informatique, conditions d\'emprunt, horaires et accès.';
$pageActive      = 'apropos';

require __DIR__ . '/includes/header.php';
?>

<section class="entete-page">
    <div class="conteneur">
        <nav class="fil-ariane" aria-label="Fil d'Ariane">
            <ol>
                <li><a href="index.php">Accueil</a></li>
                <li aria-current="page">À propos</li>
            </ol>
        </nav>
        <h1 class="titre-page">La médiathèque</h1>
        <p class="chapo">
            Un lieu de ressources, de travail et de découverte ouvert à toute la communauté d'IPNET Institute of Technology.
        </p>
        <p class="date-du-jour">
            Nous sommes le <time datetime="<?= echapper(date('Y-m-d')) ?>"><?= echapper(dateDuJourEnFrancais()) ?></time>.
        </p>
    </div>
</section>

<section class="section">
    <div class="conteneur apropos">
        <div class="apropos__texte texte-courant">
            <h2>Notre mission</h2>
            <p>
                La Médiathèque IPNET accompagne les étudiants tout au long de leur parcours.
                Elle met à leur disposition un fonds d'ouvrages d'informatique choisi en lien
                avec les enseignements des filières de Licence : Génie logiciel, WIM,
                Développement web et mobile, Informatique de gestion et Intelligence
                artificielle.
            </p>

            <h2>Les collections</h2>
            <p>
                Le fonds est organisé en six rayons thématiques : développement web,
                programmation, bases de données, réseaux et sécurité, génie logiciel et
                intelligence artificielle. On y trouve les manuels de référence cités en cours,
                de préférence dans leur édition française, du PHP de l'UE IT231 jusqu'à
                l'apprentissage automatique.
            </p>
            <p>
                Chaque ouvrage dispose d'une fiche en ligne indiquant son auteur, son année de
                publication, sa catégorie, son prix de référence et sa disponibilité.
            </p>

            <h2>Conditions d'emprunt</h2>
            <ul>
                <li>Inscription gratuite pour les étudiants et le personnel de l'établissement, sur présentation de la carte étudiante ou professionnelle.</li>
                <li>Trois ouvrages au maximum par lecteur, pour une durée de quinze jours.</li>
                <li>Un renouvellement possible, sauf si l'ouvrage est réservé par un autre lecteur.</li>
                <li>Les ouvrages perdus ou abîmés sont remplacés ou remboursés au prix de référence.</li>
            </ul>
        </div>

        <aside class="apropos__encadre">
            <div class="encadre">
                <h2 class="encadre__titre">Horaires d'ouverture</h2>
                <table class="horaires">
                    <tbody>
                        <tr><th scope="row">Lundi</th><td>8 h – 19 h</td></tr>
                        <tr><th scope="row">Mardi</th><td>8 h – 19 h</td></tr>
                        <tr><th scope="row">Mercredi</th><td>8 h – 19 h</td></tr>
                        <tr><th scope="row">Jeudi</th><td>8 h – 19 h</td></tr>
                        <tr><th scope="row">Vendredi</th><td>8 h – 19 h</td></tr>
                        <tr><th scope="row">Samedi</th><td>9 h – 13 h</td></tr>
                        <tr><th scope="row">Dimanche</th><td>Fermé</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="encadre">
                <h2 class="encadre__titre">Nous trouver</h2>
                <address>
                    IPNET Institute of Technology<br>
                    Tour IPNET, Agbalépédo, Lossossimè<br>
                    05 BP 507, Lomé – Togo<br>
                    <a href="tel:+22899310714">+228 99 31 07 14</a><br>
                    <a href="mailto:ipnet@ipnetinstitute.com">ipnet@ipnetinstitute.com</a>
                </address>
                <p>Renseignements et inscriptions à la banque d'accueil, aux heures d'ouverture.</p>
            </div>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
