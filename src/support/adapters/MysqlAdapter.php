<?php

declare(strict_types=1);

namespace Payme\Support\Adapters;

use Payme\Support\Interfaces\DatabaseAdapterInterface;

/**
 * Adapter class to connect to a MySQL DB
 *
 * @package Payme\Support\Adapters
 */
class MysqlAdapter implements DatabaseAdapterInterface {
    /**
     * The connection to the DB
     *
     * @var PDO|null
     */
    private $conn;

    /**
     * The host where the DB is located
     *
     * @var string
     */
    private $host;

    /**
     * The Database name
     *
     * @var string
     */
    private $dbname;

    /**
     * The Database user
     *
     * @var string
     */
    private $user;

    /**
     * The Database password
     *
     * @var string
     */
    private $password;

    /**
     * Constructor
     *
     * Creates a new PostgresAdapter instance with the credentials stored in config file
     *
     * @param array $config The configuration data
     * @return void
     */
    public function __construct($config)
    {
        $this->host     = $config['host'];
        $this->dbname   = $config['database'];
        $this->user     = $config['username'];
        $this->password = $config['password'];
    }

    /**
     * Starts the connection to the database
     *
     * @return void
     */
    public function connect(): void
    {
        // TODO: connect to MySQL database
    }

    /**
     * Executes a prepared query (with params) that returns a single result.
     *
     * @param string $sql The SQL query with placeholders
     * @param array $params The params to bind to the query
     * @return mixed The result of the query
     */
    public function query(string $sql, array $params): mixed
    {
        // TODO: execute query on MySQL dataabase
    }

    /**
     * Closes the connection to the database
     *
     * @return void
     */
    public function close(): void
    {
        // TODO: close connection to MySQL database
    }
}
