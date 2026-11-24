<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$page = $_GET['page'] ?? 'accueil';

switch ($page) {
    case 'accueil':
        require __DIR__ . '/../views/accueil.php';
        break;

    case 'connexion':
        require_once __DIR__ . '/../controllers/AuthentificationControleur.php';
        $auth = new AuthentificationControleur($pdo);
        $auth->connexion();
        break;

    case 'inscription':
        require_once __DIR__ . '/../controllers/AuthentificationControleur.php';
        $auth = new AuthentificationControleur($pdo);
        $auth->inscription();
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/../views/accueil.php';
        break;
}