# Dossier `config/`

Ce dossier contient la configuration de la connexion à la base de données MySQL.

## Fichiers

| Fichier | Versionné | Rôle |
|---|---|---|
| `config.example.php` | oui | Modèle de configuration, sans identifiants réels |
| `config.php` | **non** (dans le `.gitignore`) | Configuration réelle de chaque machine |

`config.php` n'est jamais envoyé sur le dépôt : chacun a le sien, adapté à son installation.

## Mise en place

1. Copier le modèle :
   ```
   cp config/config.example.php config/config.php
   ```
2. Ouvrir `config/config.php` et adapter les valeurs si besoin :

   | Clé | Valeur par défaut | Signification |
   |---|---|---|
   | `db_host` | `127.0.0.1` | Adresse du serveur MySQL |
   | `db_port` | `3306` | Port MySQL |
   | `db_name` | `xofane` | Nom de la base (créée par `sql/schema.sql`) |
   | `db_user` | `root` | Utilisateur MySQL |
   | `db_pass` | *(vide)* | Mot de passe MySQL |

   Avec WAMP, les valeurs par défaut fonctionnent en général sans rien changer.

## Utilisation dans le code

La connexion est ouverte par la classe `Database` (fichier `src/Database.php`), qui lit `config/config.php` :

```php
require_once __DIR__ . '/../src/database.php';

$pdo = Database::getConnection();
$stmt = $pdo->prepare('SELECT titre FROM musique WHERE id = ?');
$stmt->execute([$id]);
$musique = $stmt->fetch();
```

La connexion est créée une seule fois puis réutilisée. Utilisez toujours des requêtes préparées (`prepare` / `execute`), jamais de variables collées directement dans le SQL.

## En cas de problème

- **« Le site est momentanément indisponible »** : la connexion a échoué. Le détail de l'erreur est écrit dans le journal d'erreurs PHP (`error_log`), pas à l'écran.
- **`config.php` introuvable** : l'étape 1 n'a pas été faite.
- **Base inconnue** : importer `sql/schema.sql` dans phpMyAdmin pour créer la base `xofane`.
- **Accès refusé** : vérifier `db_user` et `db_pass` dans `config.php`.

## Règle

Ne jamais ajouter de vrais identifiants dans `config.example.php`, et ne jamais versionner `config.php`.
