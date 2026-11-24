<?php
$titre = 'Connexion';
$extraCss = ['auth.css'];
require __DIR__ . '/../includes/header.php';
?>

<main class="auth-page">
    <section class="auth-card" aria-labelledby="auth-title">
        <h2 id="auth-title" class="auth-title">Se connecter à 
            <span class="auth-logo">Xofane</span>
        </h2>
        <p class="auth-subtitle">Accédez à votre espace créateur et à votre bibliothèque musicale.</p>
        
        <?php if (!empty($erreur)): ?>
            <div class="auth-alert" role="alert">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=connexion" method="post" class="auth-form" novalidate>
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($email ?? '') ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input
                    type="password"
                    id="mot_de_passe"
                    name="mot_de_passe"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn-auth-submit">Se connecter</button>
        </form>

        <p class="auth-switch">
            Pas encore de compte ?
            <a href="index.php?page=inscription">Créer un compte gratuit</a>
        </p>
    </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>