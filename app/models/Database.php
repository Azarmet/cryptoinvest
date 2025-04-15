<?php namespace App\Models;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use App\Utils\Logger;
class Database
{
    private static $instance = null;
    private $pdo;

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

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}

?>