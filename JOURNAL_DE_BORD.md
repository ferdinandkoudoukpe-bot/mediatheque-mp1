# Journal de bord – TP1 IT231 « Médiathèque IPNET »

## Déroulement

J'ai commencé par créer l'arborescence imposée par le sujet, puis le fichier
`data/livres.php` avec seize livres d'informatique réels, choisis en lien
avec nos cours (développement web, programmation, bases de données, réseaux,
génie logiciel, intelligence artificielle). J'ai vérifié les titres, les auteurs
et les années d'édition. J'ai ensuite écrit les fonctions de
`includes/fonctions.php` avant les pages, ce qui m'a permis de garder des pages
courtes : elles préparent les données en haut du fichier et s'occupent seulement
de l'affichage en bas. Le CSS est venu en dernier.

## Difficultés rencontrées et solutions

**Les accents dans les URL.** Au départ, je voulais utiliser « Bases de données »
et « Développement web » comme valeurs dans l'URL, mais avec les majuscules, les
accents et les espaces ce n'était pas pratique. J'ai séparé l'identifiant (`bases-de-donnees`)
du libellé affiché, grâce à un tableau dans la fonction `listeCategories()`.

**La validation de l'identifiant.** Avec un simple `(int) $_GET['id']`,
`detail.php?id=abc` donnait 0 et `id=3abc` donnait 3, ce qui n'est pas correct.
J'ai remplacé cela par `filter_var()` avec `FILTER_VALIDATE_INT` et une valeur
minimale de 1. Je distingue maintenant trois cas : identifiant absent, invalide
ou inexistant, chacun avec son propre message.

**Le paramètre envoyé sous forme de tableau.** En testant
`catalogue.php?categorie[]=programmation`, j'ai obtenu une erreur, car `trim()`
attend une chaîne. J'ai ajouté une vérification avec `is_string()` avant de
traiter le paramètre.

**`strict_types` et les types.** Après avoir ajouté `declare(strict_types=1)`,
une erreur est apparue parce que je passais un entier à une fonction qui
attendait une chaîne. J'ai typé la fonction `echapper()` pour qu'elle accepte
`string|int|float`.

**La date en français.** `date()` renvoie les jours et les mois en anglais, et
l'extension `intl` n'est pas toujours activée dans XAMPP. J'ai donc écrit
`dateDuJourEnFrancais()` avec deux tableaux de traduction, et j'ai fixé le
fuseau horaire sur `Africa/Lome`.

**Le code HTTP des pages d'erreur.** Je voulais renvoyer un code 404, mais
`http_response_code()` doit être appelé avant tout affichage. J'ai donc placé
toute la validation avant l'inclusion de `header.php`.

**L'affichage sur mobile.** Le menu et les cartes débordaient sur un petit
écran. J'ai utilisé CSS Grid avec des `@media` pour passer de quatre colonnes à
une seule, sans avoir besoin de JavaScript.

## Ce que j'ai appris

- Organiser un site en plusieurs fichiers avec `require` et partager l'en-tête
  et le pied de page entre toutes les pages.
- Ne jamais faire confiance à une donnée venant de l'URL : vérifier son type,
  sa forme et son existence avant de l'utiliser.
- Échapper systématiquement l'affichage avec `htmlspecialchars()` ; j'ai vérifié
  qu'une URL contenant une balise `<script>` s'affiche bien comme du texte.
- Typer les paramètres et les retours des fonctions (`?array`, `int`, `string`)
  pour repérer les erreurs plus tôt.
- Tester chaque cas limite dans le navigateur (`id=999`, `id=abc`, catégorie
  inconnue, paramètre vide) avant de considérer une page comme terminée.
