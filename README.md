# Médiathèque IPNET – Catalogue dynamique

TP1 – UE IT231 Développement Web I – Année académique 2026-2027

## Description

Ce projet est un mini-site en PHP qui présente le catalogue de livres de la
Médiathèque IPNET. Les données ne viennent pas d'une base de données : les
livres sont stockés dans un tableau associatif PHP (`data/livres.php`) et les
pages HTML sont générées dynamiquement à partir de ce tableau. Pour la
présentation, j'ai repris les couleurs du site officiel d'IPNET (bleu marine,
bleu IPNET et jaune doré).

Le site comporte quatre pages :

- **Accueil** (`index.php`) : message de bienvenue, recherche rapide, chiffres du fonds,
  rayons et cartes des parutions les plus récentes ;
- **Catalogue** (`catalogue.php`) : liste des livres dans un tableau HTML, avec recherche,
  filtre par catégorie, filtre de disponibilité et tri ;
- **Détail** (`detail.php?id=3`) : fiche complète d'un livre ;
- **À propos** (`apropos.php`) : présentation de la médiathèque et date du jour.

## Structure du projet

```
mediatheque-mp1/
├── index.php            Page d'accueil
├── catalogue.php        Catalogue : recherche, filtres et tri
├── detail.php           Fiche détaillée d'un livre
├── apropos.php          Présentation de la médiathèque
├── includes/
│   ├── header.php       En-tête commun (menu de navigation)
│   ├── footer.php       Pied de page commun
│   └── fonctions.php    Fonctions du site
├── data/
│   └── livres.php       Tableau des livres (16 ouvrages d'informatique)
├── css/
│   └── style.css        Feuille de style
├── favicon.svg          Icône du site (SVG)
├── JOURNAL_DE_BORD.md   Journal de bord
└── README.md
```

## Le fonds documentaire

Le catalogue contient 16 ouvrages d'informatique réels, choisis en lien avec les
filières de Licence d'IPNET (GL, WIM, DWM, IG, IA) et l'UE IT231 : titres, auteurs,
éditeurs et années correspondent aux éditions publiées (de préférence françaises).
Les prix en francs CFA et la disponibilité sont fictifs.

| Rayon | Ouvrages |
| --- | --- |
| Développement web | PHP 8 (O. Heurtel), Réalisez votre site web avec HTML5 et CSS3 (M. Nebra), Eloquent JavaScript (M. Haverbeke) |
| Programmation | Apprendre à programmer avec Python 3 (G. Swinnen), Algorithmique (Cormen et al.), Le langage C (Kernighan, Ritchie) |
| Bases de données | Programmer avec MySQL (C. Soutou), Merise et UML (J. Gabay) |
| Réseaux et sécurité | Réseaux (A. Tanenbaum, D. Wetherall), Sécurité informatique : Ethical Hacking (ACISSI) |
| Génie logiciel | Coder proprement (R. C. Martin), Design patterns (Gamma et al.), UML 2 par la pratique (P. Roques) |
| Intelligence artificielle | Intelligence artificielle : une approche moderne (S. Russell, P. Norvig), L'apprentissage profond (I. Goodfellow et al.), Machine Learning avec Scikit-Learn (A. Géron) |

Chaque livre possède les clés imposées (`id`, `titre`, `auteur`, `annee`, `categorie`,
`prix`, `disponible`) et deux clés complémentaires affichées sur la fiche : `editeur`
et `resume`.

## Contact de l'établissement

IPNET Institute of Technology – Tour IPNET, Agbalépédo, Lossossimè, 05 BP 507, Lomé – Togo.
Mobile : +228 99 31 07 14 / 99 31 07 21 / 99 31 07 20 – ipnet@ipnetinstitute.com
(source : site officiel www.ipnetinstitute.com). Les horaires et règles de prêt de la médiathèque
sont fictifs, dans le cadre du TP.

## Prérequis

- PHP 8.0 ou supérieur (le code utilise `declare(strict_types=1)`, les types
  union et le type de retour `?array`) ;
- un navigateur web récent ;
- aucune base de données, aucun framework, aucune bibliothèque externe.

## Installation et lancement

### Option 1 : avec XAMPP (Windows)

1. Installer XAMPP depuis le site officiel d'Apache Friends.
2. Copier le dossier `mediatheque-mp1` dans `C:\xampp\htdocs\`.
3. Ouvrir le panneau de contrôle XAMPP et démarrer **Apache**.
4. Ouvrir le navigateur à l'adresse : <http://localhost/mediatheque-mp1/>

### Option 2 : avec le serveur intégré de PHP

1. Vérifier que PHP est installé :
   ```bash
   php -v
   ```
2. Se placer dans le dossier du projet puis lancer le serveur :
   ```bash
   cd mediatheque-mp1
   php -S localhost:8000
   ```
3. Ouvrir le navigateur à l'adresse : <http://localhost:8000>

## Fonctionnalités réalisées

### Page d'accueil
- Message de bienvenue et présentation de la médiathèque.
- Formulaire de recherche (titre ou auteur + rayon) qui envoie vers le catalogue,
  et lien vers le catalogue complet.
- Nombre total de livres, de livres disponibles et empruntés, et nombre de rayons,
  calculés en PHP à partir du tableau (`count()` et `compterLivresDisponibles()`).
- Accès direct aux six rayons, avec le nombre de titres de chacun.
- Les quatre parutions les plus récentes, affichées en cartes avec `afficherLivre()`.

### Catalogue
- Affichage des livres dans un tableau HTML (une ligne par livre, générée par
  `afficherLigneLivre()`) : référence, titre, auteur, catégorie, année, prix,
  disponibilité et lien vers la fiche.
- Affichage du nombre de livres affichés sur le nombre total du catalogue.

### Filtrage par catégorie
- Filtre par liens (une pastille par catégorie avec son nombre de livres) et par menu déroulant.
- Paramètre passé dans l'URL : `catalogue.php?categorie=developpement-web`.
- Vérification du paramètre : si la catégorie n'existe pas
  (ex. `catalogue.php?categorie=bd`), un message d'erreur est affiché avec le code HTTP 404.
- Pastille « Tous les rayons » et lien « Revenir au catalogue complet ».

Catégories disponibles : `developpement-web`, `programmation`, `bases-de-donnees`,
`reseaux-securite`, `genie-logiciel`, `intelligence-artificielle`.

### Recherche, tri et filtres (ergonomie du catalogue)
- Champ de recherche par titre ou auteur (`catalogue.php?q=python`), insensible
  à la casse et aux accents, combinable avec le rayon.
- Tri par titre, auteur, année (croissante ou décroissante) ou prix (croissant ou
  décroissant), par exemple `?tri=prix-asc`. Les valeurs sont contrôlées par une
  liste blanche : une valeur inconnue est ignorée avec un message.
- Case « Disponibles uniquement » (`?dispo=1`).
- Les paramètres se combinent : `catalogue.php?q=php&categorie=developpement-web&tri=annee-desc&dispo=1`.
- Résumé des filtres actifs sous forme de puces, chacune avec un lien pour la
  retirer, et lien « Tout réinitialiser ».
- Message « Aucun résultat » avec des pistes (autre orthographe, chercher dans
  tous les rayons, inclure les ouvrages empruntés) et un bouton de réinitialisation.
- Toutes les valeurs saisies sont validées (type, longueur limitée à 60 caractères)
  et échappées avec `htmlspecialchars()` avant affichage.

### Accessibilité et confort
- Lien d'évitement « Aller au contenu », repères sémantiques (`header`, `nav`,
  `main`, `footer`), `aria-current` sur le menu et le fil d'Ariane.
- Focus clavier toujours visible (`:focus-visible`), contrastes conformes WCAG AA.
- Menu mobile sans JavaScript (case à cocher + CSS), en-tête collant,
  zones tactiles d'au moins 44 px.
- Animations discrètes, désactivées si le système demande de réduire les mouvements
  (`prefers-reduced-motion`).
- Pages d'erreur avec actions proposées (recherche rapide, rayons, retour au catalogue).
- Titre d'onglet et méta-description propres à chaque page, favicon SVG,
  lien « Haut de page ».
- Horaires dans le bandeau du haut, avec l'indication « Ouvert actuellement » ou
  « Fermé actuellement » calculée en PHP (`estOuverte()`).
- Le site fonctionne entièrement sans JavaScript.

### Page détail
- Fiche complète d'un livre via `detail.php?id=3` : titre, auteur, éditeur, année
  d'édition, catégorie, prix, disponibilité, référence et résumé.
- Les paramètres de recherche peuvent suivre (`detail.php?id=3&q=javascript`) :
  le lien « Retour aux résultats » ramène alors à la même recherche.
- Navigation vers l'ouvrage précédent et suivant dans la liste de résultats.
- Disponibilité expliquée : étapes pour emprunter, ou délai de retour et réservation.
- Vérification de l'identifiant avec `filter_var()` (entier strictement positif).
- Message d'erreur propre si l'identifiant est absent (`detail.php`),
  invalide (`detail.php?id=abc`) ou inexistant (`detail.php?id=999`), avec le code HTTP 404.
- Fil d'Ariane et suggestions d'autres ouvrages du même rayon.

### Page À propos
- Présentation statique de la médiathèque (mission, collections, conditions d'emprunt, horaires).
- Date du jour affichée en français par la fonction `dateDuJourEnFrancais()`.

### Fonctions (`includes/fonctions.php`)
- `afficherLivre(array $livre): string` – génère le HTML d'une carte livre
  (accueil et suggestions de la page détail) ;
- `filtrerParCategorie(array $livres, string $categorie): array` – filtre les livres ;
- `trouverLivreParId(array $livres, int $id): ?array` – recherche un livre par son identifiant ;
- `compterLivresDisponibles(array $livres): int` – compte les livres disponibles ;
- fonctions complémentaires, entre autres : `afficherLigneLivre()` (ligne du tableau
  du catalogue), `echapper()`, `libelleCategorie()`, `compterParCategorie()`,
  `categorieExiste()`, `formaterPrix()`, `afficherCouverture()`, `dateDuJourEnFrancais()`,
  `estOuverte()`, `lireParametresCatalogue()`, `rechercherLivres()`, `trierLivres()`,
  `filtrerDisponibles()`, `afficherFiltresActifs()`, `afficherEtatVide()`, `voisinsLivre()`.

### Sécurité de base
- Toutes les données affichées passent par `htmlspecialchars()` (via la fonction `echapper()`).
- Tous les paramètres GET (`categorie`, `id`, `q`, `tri`, `dispo`) sont contrôlés avant
  utilisation (type, valeur vide, valeur inconnue, longueur). Une catégorie ou un livre
  introuvable affiche une page d'erreur avec le code HTTP 404.

### Présentation
- Mise en page responsive (ordinateur, tablette, mobile) sans JavaScript.
- Couvertures de livres générées en CSS, avec une couleur par catégorie
  (corps de titre réduit automatiquement pour les titres longs).

## Dépôt Git

Le code source est disponible sur GitHub :
<https://github.com/ferdinandkoudoukpe-bot/mediatheque-mp1>

## Auteur

- **Étudiant :** KOUDOUKPE Kokou Frédéric
- **Filière :** Licence DWM (Développement Web et Mobile)
- **Établissement :** IPNET Institute of Technology, Lomé
- **UE :** IT231 – Développement Web I (TP1)
- **Chargé de cours :** M. EDOU Dodji
- **Année académique :** 2026-2027
