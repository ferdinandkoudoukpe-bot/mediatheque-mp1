<?php
/**
 * Données du catalogue de la Médiathèque IPNET.
 *
 * Aucune base de données n'est utilisée dans ce TP : les livres sont
 * stockés dans un tableau associatif PHP. Chaque livre possède au minimum
 * les clés imposées : id, titre, auteur, annee, categorie, prix, disponible.
 * Les clés « editeur » et « resume » sont des compléments affichés sur la
 * page détail.
 *
 * Le fonds est constitué d'ouvrages réels d'informatique, en lien avec les
 * enseignements de Licence (GL, WIM, DWM, IG, IA). L'année est celle de
 * l'édition présente en rayon. Les prix sont exprimés en francs CFA.
 */

declare(strict_types=1);

$livres = [
    // --- Développement web ---------------------------------------------
    [
        'id'         => 1,
        'titre'      => 'PHP 8 : développez un site web dynamique et interactif',
        'auteur'     => 'Olivier Heurtel',
        'annee'      => 2023,
        'categorie'  => 'developpement-web',
        'prix'       => 35000,
        'disponible' => true,
        'editeur'    => 'Éditions ENI (2e édition)',
        'resume'     => 'Les bases du langage PHP 8 puis les besoins concrets d\'un site dynamique : '
                      . 'formulaires, accès aux bases de données, sessions, envoi de courriels. Les '
                      . 'nouveautés de la version 8 sont signalées et chaque chapitre propose des exercices.',
    ],
    [
        'id'         => 2,
        'titre'      => 'Réalisez votre site web avec HTML5 et CSS3',
        'auteur'     => 'Mathieu Nebra',
        'annee'      => 2023,
        'categorie'  => 'developpement-web',
        'prix'       => 18500,
        'disponible' => true,
        'editeur'    => 'Eyrolles, collection OpenClassrooms (3e édition)',
        'resume'     => 'Un guide pour débutants, sans prérequis : structure d\'une page en HTML5, mise '
                      . 'en forme et mise en page en CSS3, puis mise en ligne du site, avec des travaux '
                      . 'pratiques à chaque étape.',
    ],
    [
        'id'         => 3,
        'titre'      => 'Eloquent JavaScript',
        'auteur'     => 'Marijn Haverbeke',
        'annee'      => 2018,
        'categorie'  => 'developpement-web',
        'prix'       => 24000,
        'disponible' => false,
        'editeur'    => 'No Starch Press (3e édition, en anglais)',
        'resume'     => 'Une introduction moderne à la programmation par le JavaScript : valeurs, '
                      . 'fonctions, objets, programmation asynchrone, DOM et Node.js. Chaque chapitre '
                      . 'se termine par des exercices et des projets complets.',
    ],

    // --- Programmation ---------------------------------------------------
    [
        'id'         => 4,
        'titre'      => 'Apprendre à programmer avec Python 3',
        'auteur'     => 'Gérard Swinnen',
        'annee'      => 2012,
        'categorie'  => 'programmation',
        'prix'       => 21000,
        'disponible' => true,
        'editeur'    => 'Eyrolles (3e édition)',
        'resume'     => 'Un support de cours très utilisé pour apprendre les fondamentaux de la '
                      . 'programmation avec Python : structures de données, fonctions, programmation '
                      . 'orientée objet, interfaces graphiques, bases de données. Avec 60 pages '
                      . 'd\'exercices corrigés.',
    ],
    [
        'id'         => 5,
        'titre'      => 'Algorithmique',
        'auteur'     => 'T. H. Cormen, C. E. Leiserson, R. L. Rivest, C. Stein',
        'annee'      => 2010,
        'categorie'  => 'programmation',
        'prix'       => 46000,
        'disponible' => true,
        'editeur'    => 'Dunod (3e édition)',
        'resume'     => 'La traduction française du manuel de référence « Introduction to Algorithms » : '
                      . 'tris, structures de données, graphes, programmation dynamique, algorithmes '
                      . 'gloutons et complexité, avec de très nombreux exercices et problèmes.',
    ],
    [
        'id'         => 6,
        'titre'      => 'Le langage C : norme ANSI',
        'auteur'     => 'Brian W. Kernighan, Dennis M. Ritchie',
        'annee'      => 2004,
        'categorie'  => 'programmation',
        'prix'       => 24500,
        'disponible' => true,
        'editeur'    => 'Dunod (2e édition)',
        'resume'     => 'Le livre de référence écrit par les créateurs du langage C : types, '
                      . 'opérateurs, structures de contrôle, fonctions, pointeurs et tableaux, '
                      . 'structures et entrées-sorties, conformément à la norme ANSI.',
    ],

    // --- Bases de données -------------------------------------------------
    [
        'id'         => 7,
        'titre'      => 'Programmer avec MySQL',
        'auteur'     => 'Christian Soutou',
        'annee'      => 2021,
        'categorie'  => 'bases-de-donnees',
        'prix'       => 19500,
        'disponible' => true,
        'editeur'    => 'Eyrolles (6e édition)',
        'resume'     => 'La programmation SQL avec MySQL 8.0 : création et mise à jour des tables, '
                      . 'requêtes, transactions, procédures stockées et déclencheurs, accès depuis PHP '
                      . 'et Java, optimisation des requêtes. Avec 40 exercices corrigés.',
    ],
    [
        'id'         => 8,
        'titre'      => 'Merise et UML : pour la modélisation des systèmes d\'information',
        'auteur'     => 'Joseph Gabay',
        'annee'      => 2004,
        'categorie'  => 'bases-de-donnees',
        'prix'       => 22000,
        'disponible' => false,
        'editeur'    => 'Dunod (5e édition)',
        'resume'     => 'Un outil d\'apprentissage de la méthode Merise (MCD, MLD, modélisation des '
                      . 'traitements) et de la modélisation objet avec UML, illustré par des études de '
                      . 'cas, avec une comparaison des deux approches.',
    ],

    // --- Réseaux et sécurité ------------------------------------------------
    [
        'id'         => 9,
        'titre'      => 'Réseaux',
        'auteur'     => 'Andrew Tanenbaum, David Wetherall',
        'annee'      => 2011,
        'categorie'  => 'reseaux-securite',
        'prix'       => 45000,
        'disponible' => true,
        'editeur'    => 'Pearson (5e édition)',
        'resume'     => 'Le grand classique des réseaux informatiques, présenté couche par couche : '
                      . 'couche physique, liaison de données, réseau, transport et application, '
                      . 'avec un chapitre consacré à la sécurité des réseaux.',
    ],
    [
        'id'         => 10,
        'titre'      => 'Sécurité informatique : Ethical Hacking',
        'auteur'     => 'ACISSI',
        'annee'      => 2022,
        'categorie'  => 'reseaux-securite',
        'prix'       => 35500,
        'disponible' => true,
        'editeur'    => 'Éditions ENI (6e édition)',
        'resume'     => 'Apprendre l\'attaque pour mieux se défendre : un collectif d\'enseignants et '
                      . 'de professionnels présente les techniques d\'intrusion (réseaux, systèmes, '
                      . 'applications web) et les moyens de s\'en protéger.',
    ],

    // --- Génie logiciel ------------------------------------------------------
    [
        'id'         => 11,
        'titre'      => 'Coder proprement',
        'auteur'     => 'Robert C. Martin',
        'annee'      => 2009,
        'categorie'  => 'genie-logiciel',
        'prix'       => 23000,
        'disponible' => false,
        'editeur'    => 'Pearson',
        'resume'     => 'Traduction de « Clean Code » : nommer correctement, écrire des fonctions '
                      . 'courtes, commenter avec mesure, gérer les erreurs, tester et remanier le code '
                      . 'pour le rendre lisible et facile à maintenir.',
    ],
    [
        'id'         => 12,
        'titre'      => 'Design patterns',
        'auteur'     => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
        'annee'      => 1999,
        'categorie'  => 'genie-logiciel',
        'prix'       => 32500,
        'disponible' => true,
        'editeur'    => 'Vuibert',
        'resume'     => 'Le catalogue de modèles de conception réutilisables du « Gang of Four » : '
                      . '23 patrons orientés objet (Singleton, Fabrique, Observateur, Stratégie...) '
                      . 'classés en patrons de création, de structure et de comportement.',
    ],
    [
        'id'         => 13,
        'titre'      => 'UML 2 par la pratique',
        'auteur'     => 'Pascal Roques',
        'annee'      => 2009,
        'categorie'  => 'genie-logiciel',
        'prix'       => 20000,
        'disponible' => true,
        'editeur'    => 'Eyrolles (7e édition)',
        'resume'     => 'Études de cas et exercices corrigés pour maîtriser les principaux diagrammes '
                      . 'UML 2 : cas d\'utilisation, classes, séquence, états et activités, de '
                      . 'l\'analyse des besoins jusqu\'à la conception.',
    ],

    // --- Intelligence artificielle --------------------------------------------
    [
        'id'         => 14,
        'titre'      => 'Intelligence artificielle : une approche moderne',
        'auteur'     => 'Stuart Russell, Peter Norvig',
        'annee'      => 2021,
        'categorie'  => 'intelligence-artificielle',
        'prix'       => 52000,
        'disponible' => true,
        'editeur'    => 'Pearson (4e édition)',
        'resume'     => 'Le manuel de référence de l\'IA, construit autour de la notion d\'agent '
                      . 'intelligent : recherche, raisonnement, planification, apprentissage '
                      . 'automatique et profond, traitement du langage naturel, robotique et éthique.',
    ],
    [
        'id'         => 15,
        'titre'      => 'L\'apprentissage profond',
        'auteur'     => 'Ian Goodfellow, Yoshua Bengio, Aaron Courville',
        'annee'      => 2018,
        'categorie'  => 'intelligence-artificielle',
        'prix'       => 52500,
        'disponible' => true,
        'editeur'    => 'Massot éditions',
        'resume'     => 'Traduction de « Deep Learning » : bases mathématiques (algèbre linéaire, '
                      . 'probabilités, optimisation), réseaux de neurones profonds, réseaux convolutifs '
                      . 'et récurrents, puis thèmes de recherche actuels.',
    ],
    [
        'id'         => 16,
        'titre'      => 'Machine Learning avec Scikit-Learn',
        'auteur'     => 'Aurélien Géron',
        'annee'      => 2023,
        'categorie'  => 'intelligence-artificielle',
        'prix'       => 33000,
        'disponible' => true,
        'editeur'    => 'Dunod (3e édition)',
        'resume'     => 'Une mise en œuvre pratique de l\'apprentissage automatique en Python : '
                      . 'préparation des données, régression, classification, arbres de décision, '
                      . 'forêts aléatoires et réduction de dimension, à partir de cas concrets.',
    ],
];
