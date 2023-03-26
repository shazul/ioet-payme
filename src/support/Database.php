<?php

declare(strict_types=1);

namespace Payme\Support;

use Payme\Support\Interfaces\DatabaseAdapterInterface;
use Payme\Support\Adapters\{MySQLAdapter, PostgresAdapter};

/**
 * Database driver manager
 *
 * This class provides methods to wrap and manage the databases implementations
 *
 * @package Payme\Support
 */
class Database {
    /**
     * The instance of the Database connection
     *
     * @var Database|null
     */
    private static $instance = null;

    /**
     * The adapter implementation previously mapped through the interface
     *
     * @var DatabaseAdapterInterface
     */
    private $db;

    /**
     * The configuration of the current driver in use
     *
     * @var array
     */
    private static $config;

    /**
     * Constructor
     *
     * Creates a new Database instance with the supplied adapter.
     * This method is private so it can't be instantiated outside the class.
     *
     * @param DatabaseAdapterInterface The driver to be used pointed through the interface
     * @return void
     */
    private function __construct(DatabaseAdapterInterface $db)
    {
        $this->db = $db;
        $this->db->connect();
    }

    /**
     * Returns the single instance of the class
     *
     * This static method returns a single instance of this class (Singleton).
     *
     * @return Database The instance of the Database to be used
     * @throws Exception if the driver doesn't exist in the config file
     */
    public static function getInstance(): Database
    {
        if (self::$instance == null) {
            self::getConfig();
            if (self::$config['selected'] == 'pg') {
                $db = new PostgresAdapter(self::$config['drivers']['pg']);
            } elseif (self::$config['selected'] == 'mysql') {
                $db = new MySQLAdapter(self::$config['drivers']['mysql']);
            } else {
                throw new \Exception('Invalid database driver specified in configuration.');
            }
            self::$instance = new Database($db);
        }

        return self::$instance;
    }

    /**
     * Stores the configuration
     *
     * This method gets the current driver configuration and stores it as a static property
     *
     * @return void
     */
    protected static function getConfig(): void
    {
        self::$config = require __DIR__.'/../config/db.php';
    }

    /**
     * Executes a query
     *
     * This method connects to the database, executes a query witm params and close the connection
     *
     * @return mixed The query result
     */
    public function query($sql, $params): mixed
    {
        $this->db->connect();
        $result = $this->db->query($sql, $params);
        $this->db->close();

        return $result;
    }
}
