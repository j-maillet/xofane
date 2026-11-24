<?php
/**
 * Fonctions utilitaires partagées par toutes les pages.
 */

/**
 * Affiche une icône SVG directement dans le HTML (« inline »).
 *
 * Inline plutôt que <img> : le SVG fait alors partie de la page, donc le CSS peut le
 * colorer (color, :hover...) grâce à currentColor, ce qu'un <img> ne permet pas.
 *
 * @param string $nom    nom du fichier sans extension (ex. 'bell' -> public/assets/icons/bell.svg)
 * @param string $classe classes CSS à ajouter sur la balise <svg>
 */
function afficher_icone(string $nom, string $classe = ''): void
{
    static $cache = [];

    // On n'accepte que des noms simples : empêche d'aller lire un fichier ailleurs sur le serveur.
    if (!preg_match('/^[a-z0-9-]+$/', $nom)) {
        return;
    }

    if (!isset($cache[$nom])) {
        $chemin = __DIR__ . '/../public/assets/icons/' . $nom . '.svg';
        $cache[$nom] = is_file($chemin) ? file_get_contents($chemin) : '';
    }

    $attributs = 'class="icon ' . htmlspecialchars($classe, ENT_QUOTES) . '" aria-hidden="true" focusable="false"';
    echo preg_replace('/<svg\b/', '<svg ' . $attributs, $cache[$nom], 1);
}
