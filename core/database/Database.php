<?php

namespace App\core\database;

class Database
{
    public \PDO $pdo;
    public array $newMigrations = [];

    public function __construct(array $config)
    {
        try {
            $this->pdo = new \PDO($config['dsn'], $config['user'], $config['password']);

            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(var_dump($e->getMessage()));
        }
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();

        $appliedMigrations = $this->getAppliedMigrations();

        $files = $this->getMigrationFilesName();

        // Le migrations da applicare sono date dalla differenza tra quelle già applicate e i files presenti nella cartella migrations
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {

            require_once ROOT_PATH . "/migrations/$migration.php";

            $instance = new $migration();

            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");

            array_push($this->newMigrations, $migration);
        }

        if (!empty($this->newMigrations)) {
            $this->saveMigrations($this->newMigrations);
        } else {
            $this->log('Nothing to migrate.');
        }
    }

    /**
     * Crea una tabella migrations, in cui si tiene traccia delle migration effettuate
     * 
     * @return void 
     */
    public function createMigrationsTable(): void
    {
        $query = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
            ";

        $this->pdo->exec($query);
    }

    /**
     * Ritorna tutte le migrations applicate
     * 
     * @return array
     */
    public function getAppliedMigrations(): array
    {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Inserisco nella tabella migrations, il nome di ogni file di migration
     * 
     * @param array $migrations
     * @return void
     */
    public function saveMigrations(array $migrations): void
    {
        $values = array_map(function ($migration) {
            return '(\'' . $migration . '\'),';
        }, $migrations);

        $values = rtrim(implode($values), ',');

        $query = "INSERT INTO migrations (migration) VALUES $values";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    /**
     * Ritorna tutti i nomi dei file all'interno della cartella migrations
     * 
     * @return array 
     */
    protected function getMigrationFilesName(): array
    {
        $files = scandir(ROOT_PATH . '/migrations');

        // Filtro per prendere solo i nomi dei file
        $files = array_filter($files, function ($file) {
            if (strlen($file > 3)) {
                return $file;
            }
        });

        // Rimuovo l'estensione
        return array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files);
    }

    public function truncateDatabaseTables()
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");

        $stmt = $this->pdo->prepare("SHOW TABLES");
        $stmt->execute();

        $tables = $stmt->fetchAll();

        foreach ($tables as $table) {
            if (implode($table) === 'migrations') {
                continue;
            }
            $query = "TRUNCATE TABLE " . implode($table);
            $this->pdo->exec($query);
            $this->log("Truncated table " . implode($table));
        }

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    }

    public function dropDatabaseTables()
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");

        $stmt = $this->pdo->prepare("SHOW TABLES");
        $stmt->execute();

        $tables = $stmt->fetchAll();

        foreach ($tables as $table) {
            $query = "DROP TABLE " . implode($table);
            $this->pdo->exec($query);
            $this->log("Dropped table " . implode($table));
        }

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    }

    /**
     * @param string $message
     * @return void 
     */
    protected function log(string $message): void
    {
        echo '[' . date('d-m-Y H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
