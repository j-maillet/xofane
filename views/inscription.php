<?php
$titre = 'Inscription';
$extraCss = ['auth.css'];
require __DIR__ . '/../includes/header.php';
?>

<main class="auth-page">
    <section class="auth-card" aria-labelledby="auth-title">
        <h2 id="auth-title" class="auth-title">Créer un compte Xofane</h2>
        <p class="auth-subtitle">Sauvegardez vos titres, créez vos playlists et suivez vos créateurs préférés.</p>
        
        <?php if (!empty($erreurs)): ?>
            <div class="auth-alert" role="alert">
                <ul>
                    <?php foreach ($erreurs as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="index.php?page=inscription" method="post" class="auth-form" novalidate>
            <div class="form-group">
                <label for="nom">Nom d'utilisateur ou pseudo</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    value="<?= htmlspecialchars($nom ?? '') ?>"
                    required
                    autocomplete="username"
                >
            </div>

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
                    placeholder="8 caractères minimum"
                    required
                    autocomplete="new-password"
                >
            </div>

            <div class="form-group">
                <label for="confirmation_mot_de_passe">Confirmer le mot de passe</label>
                <input
                    type="password"
                    id="confirmation_mot_de_passe"
                    name="confirmation_mot_de_passe"
                    placeholder="Retapez votre mot de passe"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn-auth-submit">S'inscrire</button>
        </form>

        <p class="auth-switch">
            Déjà un compte ?
            <a href="index.php?page=connexion">Se connecter</a>
        </p>
    </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>