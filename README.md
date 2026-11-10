# XofaneMusic

Application web d'écoute de musique libre de droit, avec comptes utilisateurs, playlists et favoris.
Projet de la SAÉ S3-S4 du BUT Informatique, IUT Lyon 1.

## Équipe

Julien MAILLET, Milo ROUSSET, Gwendal TORRES-MARTINEZ, Mattis WENDELS.

## Technologies

HTML, CSS, JavaScript, PHP (PDO) et MySQL / MariaDB.

## Fonctionnalités prévues

- **Prioritaires (Must have)** : recherche de musiques, d'artistes, d'albums et de playlists publiques ;
  lecture (lecture/pause, suivant/précédent, volume) ; inscription, connexion et réinitialisation du mot de passe ;
  téléchargement d'un morceau.
- **Importantes (Should have)** : playlists, titres aimés, bibliothèque personnelle, historique d'écoute,
  file d'attente, modes de lecture (aléatoire, boucle), gestion du profil.
- **Si le temps le permet (Could have)** : suivi d'artistes et notifications de nouvelles sorties,
  playlists publiques ou privées, prédiction du morceau suivant.

Le détail (priorisation MoSCoW, diagrammes, maquettes, planning) est dans le dossier de conception.

## Installation

Prérequis : WAMP (Apache, PHP, MySQL) et Git.

1. Cloner le dépôt :
   ```
   git clone https://forge.univ-lyon1.fr/p2506442/sae-s3-s4.git
   ```
2. Créer la base : dans phpMyAdmin, onglet **Importer**, choisir `sql/schema.sql`.
   Attention : ce script supprime et recrée la base `xofane` à chaque import.
3. Configurer la connexion : copier `config/config.example.php` en `config/config.php`
   (détails dans [`config/README.md`](config/README.md)).
4. Faire pointer le VirtualHost WAMP sur le dossier `public/`.

## Structure du projet

```
config/   configuration de la connexion à la base (config.php non versionné)
public/   racine web : seul dossier accessible depuis le navigateur
sql/      schéma de la base de données
src/      code PHP (connexion à la base, etc.)
```

## Base de données

Le schéma est dans `sql/schema.sql`. Il est découpé par sprint :

- **Sprint 1 (socle)** : `utilisateur`, `genre`, `artiste`, `album`, `musique`, `musique_invite`.
- **Sprints suivants** : playlists, favoris, suivis, historique d'écoute, téléchargements, notifications.

Les tables du catalogue ne rendent obligatoires que le titre et l'artiste ; le reste est facultatif
tant que la source des musiques n'est pas définitivement choisie.

## Organisation de l'équipe

- `sql/schema.sql` est la référence de la base : quand on modifie une table, on met le fichier à jour
  et on prévient les autres, qui réimportent le schéma.
- Faire un `git pull` avant de commencer à travailler et avant chaque `git push`.
- Un commit = une modification cohérente, avec un message qui dit ce qui a été fait.
- Ne jamais versionner `config/config.php`.

## Avancement

En développement : Sprint 1, mise en place du socle technique.