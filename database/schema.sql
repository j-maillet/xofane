-- Schéma XofaneMusic (MySQL / MariaDB, InnoDB pour les clés étrangères).
-- Import : phpMyAdmin > Importer. Recrée la base de zéro (perd les données locales).

DROP DATABASE IF EXISTS xofane;
CREATE DATABASE xofane CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE xofane;

-- ===================== SPRINT 1 : socle =====================

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(254) NOT NULL,
    pseudo VARCHAR(50) NOT NULL,
    mdp VARCHAR(255) NOT NULL,                 -- password_hash(), jamais le mot de passe en clair
    photo_url VARCHAR(255) NULL,
    admin TINYINT(1) NOT NULL DEFAULT 0,       -- 1 = peut gérer les comptes
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_utilisateur_email (email),
    UNIQUE KEY uq_utilisateur_pseudo (pseudo)
) ENGINE=InnoDB;

CREATE TABLE genre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE            -- page « Découvrir »
) ENGINE=InnoDB;

CREATE TABLE artiste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    biographie TEXT NULL,
    photo_url VARCHAR(255) NULL
    -- nombreJaimes n'est PAS stocké : on le calcule (COUNT sur aime_artiste), sinon il se désynchronise.
) ENGINE=InnoDB;

CREATE TABLE album (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    date_sortie DATE NULL,
    pochette_url VARCHAR(255) NULL,
    artiste_id INT NOT NULL,
    CONSTRAINT fk_album_artiste FOREIGN KEY (artiste_id) REFERENCES artiste(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seuls titre + artiste sont obligatoires : n'importe quelle source de musique les fournit.
CREATE TABLE musique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_id VARCHAR(100) NULL,               -- identifiant côté source (évite les doublons à l'import)
    titre VARCHAR(255) NOT NULL,
    duree INT UNSIGNED NULL,                   -- en secondes
    date_sortie DATE NULL,
    url_audio VARCHAR(500) NULL,               -- lien de lecture/téléchargement, selon la source
    pochette_url VARCHAR(500) NULL,
    format VARCHAR(20) NULL,                   -- « WAV / MP3 »
    genre_id INT NULL,
    artiste_id INT NOT NULL,
    album_id INT NULL,                         -- un single n'a pas d'album
    UNIQUE KEY uq_musique_source (source_id),
    KEY idx_musique_titre (titre),
    CONSTRAINT fk_musique_genre   FOREIGN KEY (genre_id)   REFERENCES genre(id)   ON DELETE SET NULL,
    CONSTRAINT fk_musique_artiste FOREIGN KEY (artiste_id) REFERENCES artiste(id) ON DELETE CASCADE,
    CONSTRAINT fk_musique_album   FOREIGN KEY (album_id)   REFERENCES album(id)   ON DELETE SET NULL
) ENGINE=InnoDB;

-- Colonne « Artistes invités » de la page Album : plusieurs à plusieurs -> table de liaison.
CREATE TABLE musique_invite (
    musique_id INT NOT NULL,
    artiste_id INT NOT NULL,
    PRIMARY KEY (musique_id, artiste_id),
    CONSTRAINT fk_invite_musique FOREIGN KEY (musique_id) REFERENCES musique(id) ON DELETE CASCADE,
    CONSTRAINT fk_invite_artiste FOREIGN KEY (artiste_id) REFERENCES artiste(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===================== SPRINT 2+ : espace utilisateur =====================

CREATE TABLE playlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT NULL,
    visibilite ENUM('privee', 'publique') NOT NULL DEFAULT 'privee',
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    utilisateur_id INT NOT NULL,
    CONSTRAINT fk_playlist_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE playlist_musique (
    playlist_id INT NOT NULL,
    musique_id INT NOT NULL,
    position INT NOT NULL,                     -- ordre des titres dans la playlist
    PRIMARY KEY (playlist_id, musique_id),
    CONSTRAINT fk_pm_playlist FOREIGN KEY (playlist_id) REFERENCES playlist(id) ON DELETE CASCADE,
    CONSTRAINT fk_pm_musique  FOREIGN KEY (musique_id)  REFERENCES musique(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- « Aimer » (coups de cœur) : une table de liaison par type d'objet.
CREATE TABLE aime_musique (
    utilisateur_id INT NOT NULL,
    musique_id INT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (utilisateur_id, musique_id),
    CONSTRAINT fk_am_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_am_musique     FOREIGN KEY (musique_id)     REFERENCES musique(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE aime_album (
    utilisateur_id INT NOT NULL,
    album_id INT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (utilisateur_id, album_id),
    CONSTRAINT fk_aa_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_aa_album       FOREIGN KEY (album_id)       REFERENCES album(id)       ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE aime_artiste (
    utilisateur_id INT NOT NULL,
    artiste_id INT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (utilisateur_id, artiste_id),
    CONSTRAINT fk_aar_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_aar_artiste     FOREIGN KEY (artiste_id)     REFERENCES artiste(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

-- « Suivre » un artiste (page Profil : Artistes suivis ; base des notifications).
CREATE TABLE suivre_artiste (
    utilisateur_id INT NOT NULL,
    artiste_id INT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (utilisateur_id, artiste_id),
    CONSTRAINT fk_sa_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_sa_artiste     FOREIGN KEY (artiste_id)     REFERENCES artiste(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

-- Historique d'écoute et téléchargements (page Profil). Une ligne par événement, pas de clé composée.
CREATE TABLE historique_ecoute (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    musique_id INT NOT NULL,
    date_ecoute DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_he_utilisateur_date (utilisateur_id, date_ecoute),
    CONSTRAINT fk_he_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_he_musique     FOREIGN KEY (musique_id)     REFERENCES musique(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE telechargement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    musique_id INT NOT NULL,
    date_telechargement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_dl_utilisateur_date (utilisateur_id, date_telechargement),
    CONSTRAINT fk_dl_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_dl_musique     FOREIGN KEY (musique_id)     REFERENCES musique(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===================== SPRINT 4+ (Could have) =====================

CREATE TABLE notification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    musique_id INT NOT NULL,                   -- la nouvelle sortie annoncée
    lue TINYINT(1) NOT NULL DEFAULT 0,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_notif_utilisateur (utilisateur_id, lue),
    CONSTRAINT fk_notif_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_notif_musique     FOREIGN KEY (musique_id)     REFERENCES musique(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

-- Pas de table pour FileAttente ni Lecteur (classes du diagramme) : l'état de lecture vit dans
-- le navigateur (JavaScript / session), un visiteur sans compte doit aussi pouvoir l'utiliser.