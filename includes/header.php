<?php
/**
 * Fichier header.php
 */
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?> - Xofane</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/header.js" defer></script>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">
            <h1>Xofane</h1>
        </a>

        <nav class="header-center" aria-label="Navigation principale">
            <a href="/index.php" class="home-icon-btn" aria-label="Accueil" data-tooltip="Accueil">
                <?php afficher_icone('home', 'icon-home'); ?>
            </a>
            <form class="search-bar" action="/recherche.php" method="get" role="search">
                <button type="submit" class="search-submit" aria-label="Rechercher" data-tooltip="Rechercher">
                    <?php afficher_icone('search', 'icon-search'); ?>
                </button>
                <input type="search" name="q" placeholder="Artistes, titres, podcasts..." aria-label="Rechercher">
                <button type="button" class="filter-btn" aria-label="Parcourir" data-tooltip="Parcourir">
                    <?php afficher_icone('filter', 'icon-filter'); ?>
                </button>
            </form>
        </nav>

        <!-- Actions utilisateur -->
        <section class="header-right" aria-label="Compte utilisateur">
            <?php if (function_exists('estConnecte') && estConnecte()): ?>
                <!-- État CONNECTÉ -->
               <nav class="user-actions" aria-label="Actions du compte">
                    <a href="/notifications.php" class="notification-btn" aria-label="Notifications" data-tooltip="Notifications">
                        <?php afficher_icone('bell', 'icon-bell'); ?>
                    </a>
                    <a href="/profil.php" class="profile-avatar-link" aria-label="Profil" data-tooltip="Profil">
                        <img src="<?= htmlspecialchars($_SESSION['user']['avatar'] ?? '/assets/images/default-avatar.png') ?>"
                             alt="" class="avatar-img">
                    </a>
                </nav>
            <?php else: ?>
                <!-- État NON CONNECTÉ -->
                <nav class="auth-buttons" aria-label="Authentification">
                    <a href="/register.php" class="btn-signup">S'inscrire</a>
                    <a href="/login.php" class="btn-login">Se connecter</a>
                </nav>
            <?php endif; ?>
        </section>
    </header>
