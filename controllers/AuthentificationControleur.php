<?php

require_once __DIR__ . '/../models/UtilisateurModele.php';

class AuthentificationControleur
{
    private UtilisateurModele $modele;

    public function __construct(PDO $pdo)
    {
        $this->modele = new UtilisateurModele($pdo);
    }

    /**
     * Affiche et traite le formulaire d'inscription.
     */
    public function inscription(): void
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?page=accueil');
            exit;
        }

        $erreurs = [];
        $pseudo = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = trim((string) ($_POST['pseudo'] ?? $_POST['nom'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');
            $confirmation = (string) ($_POST['confirmation_mot_de_passe'] ?? '');

            if ($pseudo === '' || mb_strlen($pseudo) < 2) {
                $erreurs[] = 'Le pseudo doit contenir au moins 2 caractères.';
            } elseif (mb_strlen($pseudo) > 50) {
                $erreurs[] = 'Le pseudo ne doit pas dépasser 50 caractères.';
            }

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = 'Veuillez saisir une adresse e-mail valide.';
            }

            if (mb_strlen($motDePasse) < 8) {
                $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            } elseif ($motDePasse !== $confirmation) {
                $erreurs[] = 'Les deux mots de passe ne correspondent pas.';
            }

            if (empty($erreurs)) {
                if ($this->modele->trouverParEmail($email) !== null) {
                    $erreurs[] = 'Cette adresse e-mail est déjà associée à un compte.';
                }
                if ($this->modele->pseudoExiste($pseudo)) {
                    $erreurs[] = 'Ce pseudo est déjà pris.';
                }
            }

            if (empty($erreurs)) {
                $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
                $nouvelId = $this->modele->creer($pseudo, $email, $hash);

                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id'    => $nouvelId,
                    'nom'   => $pseudo,
                    'email' => $email,
                    'admin' => 0,
                ];

                header('Location: /index.php?page=accueil');
                exit;
            }
        }

        require __DIR__ . '/../views/inscription.php';
    }

    /**
     * Affiche et traite le formulaire de connexion (avec blocage temporaire après 5 échecs).
     */
    public function connexion(): void
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?page=accueil');
            exit;
        }

        $_SESSION['tentatives_connexion'] ??= 0;
        $_SESSION['blocage_jusqua'] ??= 0;

        $erreur = null;
        $email = '';
        $maintenant = time();

        if ($_SESSION['blocage_jusqua'] > $maintenant) {
            $secondesRestantes = $_SESSION['blocage_jusqua'] - $maintenant;
            $erreur = "Trop de tentatives. Réessayez dans {$secondesRestantes} seconde(s).";
            require __DIR__ . '/../views/connexion.php';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');

            if ($email === '' || $motDePasse === '') {
                $erreur = 'Veuillez remplir tous les champs.';
            } else {
                $utilisateur = $this->modele->trouverParEmail($email);

                if ($utilisateur !== null && password_verify($motDePasse, $utilisateur['mdp'])) {
                    $_SESSION['tentatives_connexion'] = 0;
                    $_SESSION['blocage_jusqua'] = 0;

                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id'    => (int) $utilisateur['id'],
                        'nom'   => $utilisateur['pseudo'],
                        'email' => $utilisateur['email'],
                        'admin' => (int) $utilisateur['admin'],
                    ];

                    header('Location: /index.php?page=accueil');
                    exit;
                }

                $_SESSION['tentatives_connexion']++;

                if ($_SESSION['tentatives_connexion'] >= 5) {
                    $_SESSION['blocage_jusqua'] = $maintenant + 60;
                    $_SESSION['tentatives_connexion'] = 0;
                    $erreur = 'Trop de tentatives échouées. Compte bloqué pendant 60 secondes.';
                } else {
                    $restants = 5 - $_SESSION['tentatives_connexion'];
                    $erreur = "Identifiants incorrects ({$restants} tentative(s) restante(s)).";
                }
            }
        }

        require __DIR__ . '/../views/connexion.php';
    }
}