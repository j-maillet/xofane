<?php

class UtilisateurModele
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Recherche un utilisateur par son adresse e-mail.
     */
    public function trouverParEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, email, pseudo, mdp, photo_url, admin
             FROM utilisateur
             WHERE email = :email
             LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        $utilisateur = $stmt->fetch();

        return $utilisateur !== false ? $utilisateur : null;
    }

    /**
     * Vérifie si un pseudo est déjà utilisé.
     */
    public function pseudoExiste(string $pseudo): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM utilisateur WHERE pseudo = :pseudo LIMIT 1'
        );
        $stmt->execute([':pseudo' => $pseudo]);

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Insère un nouvel utilisateur en base et retourne son identifiant.
     */
    public function creer(string $pseudo, string $email, string $mdpHash): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO utilisateur (pseudo, email, mdp)
             VALUES (:pseudo, :email, :mdp)'
        );
        $stmt->execute([
            ':pseudo' => $pseudo,
            ':email'  => $email,
            ':mdp'    => $mdpHash,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}