<?php

namespace Payme\Support\Interfaces;

/**
 * An interface for classes that serve as adapters to a Database.
 *
 * This interface defines methods that should be implemented by objects
 * that want to connect to a DB engine.
 *
 * @interface DatabaseAdapterInterface
 */
interface DatabaseAdapterInterface {
    /**
     * Starts the connection to the database
     *
     * @method void connect()
     * @return void
     */
    public function connect(): void;

    /**
     * Executes a PDO query with parameters
     *
     * @method mixed query(string $sql, array $params)
     * @param string $sql The SQL query with placeholders
     * @param array $params The params to bind to the query
     * @return mixed The result of the query
     */
    public function query(string $sql, array $params): mixed;

    /**
     * Closes the connection to the database
     *
     * @method void close()
     * @return void
     */
    public function close(): void;
}
