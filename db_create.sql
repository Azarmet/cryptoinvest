-- -----------------------------------------------------
-- 1. Création de la base de données
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS crypto_invest
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE crypto_invest;

-- -----------------------------------------------------
-- 2. Table Utilisateur
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email         VARCHAR(100) NOT NULL UNIQUE,
    pseudo        VARCHAR(50)  NOT NULL UNIQUE,
    mot_de_passe  VARCHAR(255) NOT NULL,
    role          VARCHAR(20)  NOT NULL DEFAULT 'utilisateur',
    bio           TEXT NULL,
    image_profil  VARCHAR(255) NULL
    -- Exemples de rôles : 'utilisateur', 'administrateur'
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- 3. Table CryptoTrans (cryptos disponibles aux transactions)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cryptotrans (
    id_crypto_trans INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code           VARCHAR(20) NOT NULL UNIQUE
    -- Ex. : 'BTCUSDT', 'ETHUSDT'...
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 4. Table CryptoMarket (données de marché, pour la page "Market")
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cryptomarket (
    id_crypto_market INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code             VARCHAR(20)  NOT NULL,  -- Ex. : 'BTCUSDT', 'ETHUSDT'
    prix_actuel      DECIMAL(15,6) NOT NULL, -- Ajustez la précision selon vos besoins
    variation_24h    DECIMAL(7,4)  NOT NULL, -- Variation en pourcentage (+X.XXXX / -X.XXXX)
    date_maj         DATETIME      NOT NULL,
    CONSTRAINT uk_code_market UNIQUE (code)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 5. Table Portefeuille
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS portefeuille (
    id_portefeuille INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    capital_initial DECIMAL(15,2) NOT NULL DEFAULT 10000.00,
    -- Ex. : 10 000 USDT de départ
    id_utilisateur  INT UNSIGNED NOT NULL,
    CONSTRAINT fk_portefeuille_utilisateur
      FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
      ON DELETE CASCADE
      ON UPDATE CASCADE
    -- Relation 1-1 avec l’utilisateur (mais techniquement 1-N autorisé).
    -- Pour forcer le 1-1 strictement, vous pouvez ajouter une contrainte UNIQUE sur id_utilisateur.
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 6. Table HistoriqueBTC (historique de prix du BTC)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS historiquebtc (
    date    DATETIME     NOT NULL,
    prix    DECIMAL(15,6) NOT NULL,
    PRIMARY KEY (date)
    -- Stocke l'évolution du prix BTC dans le temps.
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 7. Table Article (contenu éditorial, tutoriels, etc.)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS article (
    id_article       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre            VARCHAR(200) NOT NULL,
    contenu          TEXT         NOT NULL,
    date_publication DATETIME     NOT NULL,
    id_auteur        INT UNSIGNED NOT NULL,
    categorie        VARCHAR(100) NULL,
    CONSTRAINT fk_article_auteur
      FOREIGN KEY (id_auteur) REFERENCES utilisateur (id_utilisateur)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 8. Table FAQ
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS faq (
    id_faq   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    reponse  TEXT         NOT NULL
    -- Possibilité d'ajouter catégorie, ordre d'affichage, etc.
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 9. Table Classement
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS classement (
    id_classement       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    valeur_portefeuille DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    pnl_24h             DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    pnl_1semaine        DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    pnl_1mois           DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    nb_transactions     INT UNSIGNED  NOT NULL DEFAULT 0,
    date_calcul         DATETIME      NOT NULL,
    id_utilisateur      INT UNSIGNED  NOT NULL,
    CONSTRAINT fk_classement_utilisateur
      FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
      ON DELETE CASCADE
      ON UPDATE CASCADE
    -- Selon vos besoins, vous pouvez rendre cette relation 1-1 ou 1-N
    -- si vous stockez un historique du classement.
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 10. Table Transaction (historique de transactions sur BTC ou autres cryptos)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS transaction (
    id_transaction  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    statut          ENUM('open','close') NOT NULL DEFAULT 'open',
    date_ouverture  DATETIME NOT NULL,
    prix_ouverture  DECIMAL(15,6) NOT NULL,
    quantite        DECIMAL(15,6) NOT NULL,
    sens            ENUM('long','short') NOT NULL,
    date_cloture    DATETIME NULL,
    prix_cloture    DECIMAL(15,6) NULL,
    pnl             DECIMAL(15,6) NULL,
    id_portefeuille INT UNSIGNED NOT NULL,
    id_crypto_trans INT UNSIGNED NOT NULL,
    CONSTRAINT fk_transaction_portefeuille
      FOREIGN KEY (id_portefeuille) REFERENCES portefeuille (id_portefeuille)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    CONSTRAINT fk_transaction_cryptotrans
      FOREIGN KEY (id_crypto_trans) REFERENCES cryptotrans (id_crypto_trans)
      ON DELETE RESTRICT
      ON UPDATE CASCADE
);

-- -----------------------------------------------------
-- 11. Table Watchlist (relation N-N entre Utilisateur et CryptoMarket)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS watchlist (
    id_utilisateur    INT UNSIGNED      NOT NULL,
    id_crypto_market  INT UNSIGNED      NOT NULL,
    PRIMARY KEY (id_utilisateur, id_crypto_market),
    CONSTRAINT fk_watchlist_utilisateur
      FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    CONSTRAINT fk_watchlist_cryptomarket
      FOREIGN KEY (id_crypto_market) REFERENCES cryptomarket (id_crypto_market)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Fin du script.
-- Vous pouvez à présent insérer des données de test si nécessaire.
