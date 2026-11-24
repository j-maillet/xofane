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
    <title><?= htmlspecialchars($titre ?? 'Xofane') ?> - Xofane</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (!empty($extraCss)): ?>
        <?php foreach ($extraCss as $cssFile): ?>
            <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="/assets/js/header.js" defer></script>
</head>
<body>
    <header class="header">
        <a href="/index.php?page=accueil" class="logo">
            <h1>Xofane</h1>
        </a>

        <nav class="header-center" aria-label="Navigation principale">
            <a href="/index.php?page=accueil" class="home-icon-btn" aria-label="Accueil" data-tooltip="Accueil">
                <?php afficher_icone('home', 'icon-home'); ?>
            </a>
            <form class="search-bar" action="/index.php" method="get" role="search">
                <input type="hidden" name="page" value="recherche">
                <button type="submit" class="search-submit" aria-label="Rechercher" data-tooltip="Rechercher">
                    <?php afficher_icone('search', 'icon-search'); ?>
                </button>
                <input type="search" name="q" placeholder="Artistes, titres, podcasts..."
                       data-placeholder-desktop="Artistes, titres, podcasts..."
                       data-placeholder-mobile="Rechercher..." aria-label="Rechercher">
                <a href="/index.php?page=decouvrir" class="filter-btn" aria-label="Parcourir" data-tooltip="Parcourir">
                    <?php afficher_icone('filter', 'icon-filter'); ?>
                </a>
            </form>
        </nav>

        <!-- Actions utilisateur -->
        <section class="header-right" aria-label="Compte utilisateur">
            <?php if (function_exists('estConnecte') && estConnecte()): ?>
                <!-- État CONNECTÉ -->
                <nav class="user-actions" aria-label="Actions du compte">
                    <a href="/index.php?page=notifications" class="notification-btn" aria-label="Notifications" data-tooltip="Notifications">
                        <?php afficher_icone('bell', 'icon-bell'); ?>
                    </a>
                    <a href="/index.php?page=profil" class="user-profile-btn" aria-label="Profil" data-tooltip="Profil">
                        <?= htmlspecialchars((string) ($_SESSION['user']['nom'] ?? $_SESSION['user']['pseudo'] ?? 'Profil')) ?>
                    </a>
                </nav>
            <?php else: ?>
                <!-- État NON CONNECTÉ -->
                <nav class="auth-buttons" aria-label="Authentification">
                    <a href="/index.php?page=inscription" class="btn-signup">S'inscrire</a>
                    <a href="/index.php?page=connexion" class="btn-login">Se connecter</a>
                    <a href="/index.php?page=connexion" class="auth-icon-btn" aria-label="Se connecter" data-tooltip="Se connecter">
                        <?php afficher_icone('user', 'icon-user', 'svg'); ?>
                    </a>
                </nav>
            <?php endif; ?>
        </section>
    </header>