# CryptoInvestMVC

## 📖 Présentation

**CryptoInvestMVC** est une application web de simulation de trading de cryptomonnaies en mode démo, conçue selon une architecture MVC en PHP. Elle permet à un utilisateur de :

- S’inscrire, se connecter et gérer son profil (pseudo, bio, réseaux sociaux, photo).
- Visualiser et trader avec un portefeuille virtuel financé à 10 000 USDT de départ.
- Ouvrir et clôturer des positions (long/short) sur des cryptos réelles (via l’API Binance).
- Suivre l’historique des transactions et les positions ouvertes en temps réel.
- Consulter un leaderboard des meilleurs portefeuilles et le profil détaillé de chaque utilisateur.
- Lire des articles pédagogiques (section **Learn**) avec recherche et pagination AJAX.
- Consulter une FAQ interactive.
- Gérer le back‑office (CRUD FAQ, Articles, Market, Utilisateurs).

---

## 🛠️ Tech Stack

- **Backend** : PHP 7+, PDO (MySQL)
- **Frontend** : HTML5, CSS3, JavaScript (vanilla + Chart.js + TradingView)
- **Architecture** : MVC  
- **API externes** : Binance (données marché), Alternative.me (Fear & Greed Index)
- **Design** : Responsive, mobile-first

---

## 📂 Arborescence principale

```
CryptoInvestMVC/
├── app/
│   ├── Controllers/         # Logique métier, routes publiques & back-office
│   ├── Models/              # Accès base de données (User, Portefeuille, Article…)
│   └── views/
│       ├── backoffice/      # Vues admin (tables, formulaires…)
│       └── templates/       # Vues publiques (header, footer, sections…)
├── public/
│   ├── css/                 # Feuilles de style
│   ├── js/                  # Scripts JavaScript
│   ├── image/               # Images statiques
│   └── uploads/             # Uploads dynamiques (profils, articles)
├── index.php                # Front controller
├── routeur.php              # Dispatch des routes
├── database/
│   └── schema.sql           # Script de création des tables
└── README.md                # Documentation
```

---

## ⚙️ Installation

1. **Cloner le dépôt**  
   ```bash
   git clone https://github.com/monorg/CryptoInvestMVC.git
   cd CryptoInvestMVC
   ```

2. **Configurer la base de données**  
   - Créer une base MySQL (ex. `cryptoinvest`).  
   - Importer le schéma :
     ```bash
     mysql -u root -p cryptoinvest < db_create.sql
     ```
   - Utiliser Composer et créer les constantes de connexion dans le .env :
     ```
     DB_HOST=localhost
     DB_NAME=crypto_invest
     DB_USER=root
     DB_PASSWORD=
     ```

3. **Définir les constantes globales** 
   - Vérifier `RACINE` (chemin absolu) si nécessaire.

4. **Droits d’écriture**  
   ```bash
   chmod -R 775 public/uploads
   ```

5. **Lancer le serveur local**  
   ```bash
   php -S localhost:8000
   ```
   Accéder à `http://localhost:8000/index.php?page=home`.

---

## 🚀 Fonctionnalités

### Authentification & Profil
- Inscription sécurisée (hash bcrypt).
- Connexion / déconnexion / gestion de session.
- Édition du profil (pseudo, bio, image, Instagram, X, Telegram).
- Suppression de compte avec confirmation.

### Portefeuille & Trading
- Portefeuille virtuel initialisé à 10 000 USDT.
- Graphique de solde historique (Chart.js) : jour, semaine, mois, année.
- Statistiques de performance (ROI, PnL total, nombre de trades, répartition long/short…).
- Ouvrir/clôturer positions long/short (API Binance).
- Rafraîchissement live des positions et du solde disponible via AJAX.

### Section Learn (Articles)
- CRUD articles en back‑office.
- Affichage paginé, filtrage par catégorie et recherche en AJAX.
- Page de détail d’article.

### FAQ
- CRUD FAQ en back‑office.
- Page publique interactive avec accordéon et recherche dynamique.

### Market
- Récupération des cryptos depuis Binance (top10, DeFi, Web3…).
- Affichage du Top 10 et filtrage par catégorie.
- Watchlist utilisateur (ajout/suppression).
- Widget TradingView mis à jour au clic.

### Leaderboard
- Top 3 sur la home.
- Vue complète avec PnL 24h & 7 jours, tri et recherche client.

### Back‑Office (Admin)
- Gestion FAQ, Articles, Market, Transactions, Utilisateurs.
- Pagination, confirmations, messages de succès/erreur.

---

## 🧩 Personnalisation & Extensibilité

- **Ajouter de nouvelles catégories d’articles** : modifier `$categories` dans les formulaires.
- **Nouveaux KPI** : étendre `Portefeuille` et adapter JS/AJAX.
- **Autre source de données marché** : remplacer `updateFromBinance()`.

---

## 🤝 Contribuer

1. Fork ce dépôt.  
2. Crée une branche feature :  
   ```bash
   git checkout -b feature/ma-fonctionnalite
   ```
3. Commit tes changements :  
   ```bash
   git commit -m "Ajout de ma fonctionnalité"
   ```
4. Push & ouvre une Pull Request.

---

## 📄 Licence

Ce projet est sous licence **MIT**. Voir `LICENSE` pour plus de détails.

---

## 📞 Contact

Pour toute question ou suggestion, ouvre une **issue** ou contacte :  
**contact@cryptoinvest.com**

Bonne exploration et bon trading virtuel ! 🚀  
```