# Médiathèque IPNET – Catalogue dynamique

TP1 – UE IT231 Développement Web I – Année académique 2026-2027

## Description

> Variante graphique **B – Institutionnel aux couleurs d'IPNET** : en-tête et bandeaux bleu marine (#0d2137), boutons et liens bleu IPNET (#1e5fa0), accent jaune doré (#ffb81c), palette reprise du site officiel d'IPNET, bloc de recherche par rayon sur l'accueil. Le catalogue est présenté sous forme de tableau HTML (fonction complémentaire `afficherLigneLivre()`), la page d'accueil conserve les cartes générées par `afficherLivre()`.

Ce projet est un mini-site en PHP qui présente le catalogue de livres de la
Médiathèque IPNET. Les données ne viennent pas d'une base de données : les
livres sont stockés dans un tableau associatif PHP (`data/livres.php`) et les
pages HTML sont générées dynamiquement à partir de ce tableau.

Le site comporte quatre pages :

- **Accueil** (`index.php`) : message de bienvenue, accès au catalogue et chiffres du fonds ;
- **Catalogue** (`catalogue.php`) : liste des livres sous forme de cartes, avec filtre par catégorie ;
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
- Lien vers le catalogue.
- Nombre total de livres et nombre de livres disponibles, calculés en PHP
  à partir du tableau (`count()` et `compterLivresDisponibles()`).
- Accès direct aux rayons (catégories) et aperçu de quelques ouvrages.

### Catalogue
- Affichage de tous les livres sous forme de cartes : titre, auteur, année,
  catégorie, prix et disponibilité.
- Affichage du nombre de livres affichés.

### Filtrage par catégorie
- Filtre par liens (une pastille par catégorie) et par menu déroulant.
- Paramètre passé dans l'URL : `catalogue.php?categorie=developpement-web`.
- Vérification du paramètre : si la catégorie n'existe pas
  (ex. `catalogue.php?categorie=bd`), un message d'erreur est affiché.
- Lien « Revenir au catalogue complet » / « Tous les ouvrages ».

Catégories disponibles : `developpement-web`, `programmation`, `bases-de-donnees`,
`reseaux-securite`, `genie-logiciel`, `intelligence-artificielle`.

### Recherche, tri et filtres (ergonomie du catalogue)
- Champ de recherche par titre ou auteur (`catalogue.php?q=python`), insensible
  à la casse et aux accents, combinable avec le rayon.
- Tri par titre, auteur, année ou prix (`?tri=prix-asc`), contrôlé par une
  liste blanche : une valeur inconnue est ignorée avec un message.
- Case « Disponibles uniquement » (`?dispo=1`).
- Les paramètres se combinent : `catalogue.php?q=php&categorie=developpement-web&tri=annee-desc&dispo=1`.
- Résumé des filtres actifs sous forme de puces, chacune avec un lien pour la
  retirer, et lien « Tout réinitialiser ».
- Message « Aucun résultat » avec des pistes (autre orthographe, chercher dans
  tous les rayons, inclure les ouvrages empruntés) et un bouton de réinitialisation.
- Toutes les valeurs saisies sont validées (type, longueur limitée à 60 caractères)
  et échappées avec `htmlspecialchars()` avant affichage.

### Fiche ouvrage
- Fil d'Ariane, lien « Retour aux résultats » qui conserve la recherche en cours.
- Navigation vers l'ouvrage précédent et suivant dans la liste de résultats.
- Disponibilité expliquée : étapes pour emprunter, ou délai de retour et réservation.

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
- Le site fonctionne entièrement sans JavaScript.

### Page détail
- Fiche complète d'un livre via `detail.php?id=3`
  (les paramètres de recherche peuvent suivre : `detail.php?id=3&q=javascript`).
- Vérification de l'identifiant avec `filter_var()` (entier strictement positif).
- Message d'erreur propre si l'identifiant est absent (`detail.php`),
  invalide (`detail.php?id=abc`) ou inexistant (`detail.php?id=999`).
- Fil d'Ariane, lien de retour au catalogue et suggestions du même rayon.

### Page À propos
- Présentation statique de la médiathèque (mission, collections, conditions d'emprunt, horaires).
- Date du jour affichée en français par la fonction `dateDuJourEnFrancais()`.

### Fonctions (`includes/fonctions.php`)
- `afficherLivre(array $livre): string` – génère le HTML d'une carte livre ;
- `filtrerParCategorie(array $livres, string $categorie): array` – filtre les livres ;
- `trouverLivreParId(array $livres, int $id): ?array` – recherche un livre par son identifiant ;
- `compterLivresDisponibles(array $livres): int` – compte les livres disponibles ;
- fonctions complémentaires : `echapper()`, `libelleCategorie()`, `compterParCategorie()`,
  `categorieExiste()`, `formaterPrix()`, `afficherCouverture()`, `dateDuJourEnFrancais()`,
  `lireParametresCatalogue()`, `rechercherLivres()`, `trierLivres()`, `filtrerDisponibles()`,
  `afficherFiltresActifs()`, `afficherEtatVide()`, `voisinsLivre()`…

### Sécurité de base
- Toutes les données affichées passent par `htmlspecialchars()` (via la fonction `echapper()`).
- Les paramètres GET `categorie` et `id` sont contrôlés avant utilisation
  (type, valeur vide, valeur inconnue). Une page d'erreur renvoie le code HTTP 404.

### Présentation
- Mise en page responsive (ordinateur, tablette, mobile) sans JavaScript.
- Couvertures de livres générées en CSS, avec une couleur par catégorie
  (corps de titre réduit automatiquement pour les titres longs).

## Auteur

Projet réalisé dans le cadre du TP1 de l'UE IT231 – Développement Web I,
IPNET Institute of Technology.
