<?php
session_start();

$page = $_GET['page'] ?? 'accueil';

switch ($page) {
    case 'accueil':
        require __DIR__ . '/../views/accueil.php';
        break;

    case 'connexion':
        require __DIR__ . '/../views/connexion.php';
        break;

    case 'inscription':
        require __DIR__ . '/../views/inscription.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/../views/accueil.php';
        break;
}