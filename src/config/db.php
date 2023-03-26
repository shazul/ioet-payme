<?php

/*
 * The drivers configuration for available databases.
 */

return [
    'selected'  => 'pg',
    'drivers'   => [
        'pg'    => [
            'driver'    => 'postgres',
            'host'      => 'db',
            'port'      => '5432',
            'database'  => 'payroll',
            'username'  => 'ioet_test',
            'password'  => 'uG7rI4hS4iR1cY1f'
        ],
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'db',
            'port'      => '3306',
            'database'  => 'db',
            'username'  => 'user',
            'password'  => 'pw'
        ]
    ]
];
