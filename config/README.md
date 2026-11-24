# Dossier `config/`

Ce dossier contient la configuration et l'initialisation de la connexion à la base de données MySQL via PDO.

## Fichiers

| Fichier | Versionné | Rôle |
|---|---|---|
| `config.example.php` | oui | Modèle de configuration, sans identifiants réels |
| `config.php` | **non** (dans le `.gitignore`) | Configuration réelle propre à chaque machine |
| `database.php` | oui | Charge `config.php` et instancie la connexion `$pdo` |

`config.php` n'est jamais envoyé sur le dépôt : chacun a le sien, adapté à son installation (WAMP, MAMP, XAMPP).

## Mise en place

1. Copier le modèle :
   ```bash
   cp config/config.example.php config/config.php
   ```
2. Ouvrir `config/config.php` et adapter les valeurs si besoin :

   | Clé | Valeur par défaut | Signification |
   |---|---|---|
   | `db_host` | `127.0.0.1` | Adresse du serveur MySQL |
   | `db_name` | `xofane` | Nom de la base (créée depuis `database/`) |
   | `db_user` | `root` | Utilisateur MySQL |
   | `db_pass` | *(vide)* (WAMP) / `root` (MAMP) | Mot de passe MySQL |
   | `db_port` *(optionnel)* | `8889` (MAMP) / `3306` (WAMP) | Port MySQL si différent de celui par défaut |

## Utilisation dans le code

L'inclusion de `config/database.php` dans le routeur (`public/index.php`) met directement l'objet `$pdo` à disposition :

```php
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->prepare('SELECT titre FROM musique WHERE id = ?');
$stmt->execute([$id]);
$musique = $stmt->fetch();
```

Utilisez toujours des requêtes préparées (`prepare` / `execute`), jamais de variables concaténées directement dans le SQL.

## En cas de problème

- **`config.php` introuvable** : l'étape 1 n'a pas été faite.
- **Base inconnue (`Unknown database 'xofane'`)** : importer le script SQL du dossier `database/` dans phpMyAdmin.
- **Accès refusé (`Access denied`) ou connexion refusée** : vérifier `db_user`, `db_pass` et éventuellement `db_port` dans `config/config.php`.

## Règle

Ne jamais ajouter de vrais identifiants dans `config.example.php`, et ne jamais versionner `config.php`.