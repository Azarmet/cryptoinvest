<?php
namespace App\Models;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Database
{
    // Instance unique de la classe
    private static $instance = null;
    // Objet PDO de connexion
    private $pdo;

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct()
    {
        // Charge les variables d'environnement depuis le fichier .env
        $dotenv = Dotenv::createImmutable(RACINE);  // RACINE définie dans index.php
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // En cas d'erreur, on arrête le script avec un message
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    // Méthode statique pour obtenir l'instance unique
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Retourne l'objet PDO de connexion
    public function getConnection()
    {
        return $this->pdo;
    }
}
?>
