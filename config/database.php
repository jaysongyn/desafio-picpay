<?php


return [
    'fetch' => \PDO::FETCH_CLASS,
    'default' => env('DB_CONNECTION'),

    'connections' => [
        'mongodb' => [
            'driver' => 'mongodb',
            'dsn' => 'mongodb://' . env('DB_URL_MONGODB', 'localhost') . ':27017',
            'database' => 'picpay-backend'
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_URL_MYSQL', 'localhost'),
            'port'      => 3306,
            'database'  => 'users',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
        ],
    ],

    'migrations' => 'migrations',
];
