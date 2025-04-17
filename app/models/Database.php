<?php namespace App\Models;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use App\Utils\Logger;


/**
 * Classe de gestion de la connexion à la base de données (singleton).
 */
class Database
{
    private static $instance = null;
    private $pdo;

     /**
     * Constructeur privé pour empêcher l'instanciation directe.
     * Charge les variables d'environnement et initialise la connexion PDO.
     */
    private function __construct()
    {
        // Chargement des variables d'environnement
        $dotenv = Dotenv::createImmutable(RACINE);
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
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            Logger::log('Erreur PDO : ' . $e->getMessage());
            die('Erreur de connexion à la base de données.'); // Message générique
        }
    }

    /**
     * Retourne l'instance unique de Database, la crée si nécessaire.
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Fournit l'objet PDO pour effectuer des requêtes SQL.
     *
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }
}

?>