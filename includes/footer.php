<?php
/**
 * Pied de page commun à toutes les pages.
 */

declare(strict_types=1);
?>
    </main>

    <footer class="pied">
        <div class="conteneur pied__haut">
            <a class="haut-de-page" href="#haut">Haut de page</a>
        </div>

        <div class="conteneur pied__grille">
            <div class="pied__bloc pied__bloc--presentation">
                <p class="pied__nom">Médiathèque IPNET</p>
                <p>
                    Le centre de ressources documentaires des étudiants et enseignants
                    d'IPNET Institute of Technology : prêt d'ouvrages, consultation sur place
                    et espaces de travail.
                </p>
            </div>

            <div class="pied__bloc">
                <h2 class="pied__titre">Adresse</h2>
                <address>
                    IPNET Institute of Technology<br>
                    Tour IPNET, Agbalépédo, Lossossimè<br>
                    05 BP 507, Lomé – Togo<br>
                    <a href="tel:+22899310714">+228 99 31 07 14</a><br>
                    <a href="mailto:ipnet@ipnetinstitute.com">ipnet@ipnetinstitute.com</a>
                </address>
            </div>

            <div class="pied__bloc">
                <h2 class="pied__titre">Horaires</h2>
                <ul class="pied__horaires">
                    <li><span>Lundi – vendredi</span><span>8 h – 19 h</span></li>
                    <li><span>Samedi</span><span>9 h – 13 h</span></li>
                    <li><span>Dimanche</span><span>Fermé</span></li>
                </ul>
            </div>

            <div class="pied__bloc">
                <h2 class="pied__titre">Accès rapide</h2>
                <ul class="pied__liens">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="catalogue.php">Catalogue complet</a></li>
                    <li><a href="catalogue.php?categorie=developpement-web">Développement web</a></li>
                    <li><a href="apropos.php">La médiathèque</a></li>
                </ul>
            </div>
        </div>

        <div class="pied__bas">
            <div class="conteneur pied__bas-contenu">
                <span>&copy; <?= echapper(date('Y')) ?> Médiathèque IPNET</span>
                <span>TP1 &middot; IT231 Développement Web I</span>
            </div>
        </div>
    </footer>
</body>
</html>
