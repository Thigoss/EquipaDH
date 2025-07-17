<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        # Conexão com o banco de dados principal da aplicação
        'pgsql' => [
            'driver' => 'pgsql',
            'port' => env('DB_PORT', '5432'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
            'options'   => array(
                PDO::ATTR_PERSISTENT => true,
            ),
            'pooling' => [
                'enabled' => env('DB_POOL_ENABLED', true),
                'max_connections' => env('DB_POOL_MAX_CONNECTIONS', 50),
                'timeout' => env('DB_POOL_TIMEOUT', 30)
            ]
        ],

        # Conexão com a base de dados de auditoria
        'auditoria' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_AUDIT'),
            'port' => env('DB_PORT_AUDIT'),
            'database' => env('DB_DATABASE_AUDIT'),
            'username' => env('DB_USERNAME_AUDIT'),
            'password' => env('DB_PASSWORD_AUDIT'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => env('DB_SCHEMA_AUDIT'),
            'sslmode' => 'prefer',
            'options'   => array(
                PDO::ATTR_PERSISTENT => true,
            ),
            'pooling' => [
                'enabled' => env('DB_POOL_ENABLED', true),
                'max_connections' => env('DB_POOL_MAX_CONECTION', 300),
                'timeout' => env('DB_POOL_TIMEOUT', 30),
            ],
        ],

        # Conexão com o schema public da base de dados legada do SIPIA CT, para fins de execução da migração de dados
        'sipiact_legado_public' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_LEGADO', '127.0.0.1'),
            'port' => env('DB_PORT_LEGADO', '5432'),
            'database' => env('DB_DATABASE_LEGADO', 'forge'),
            'username' => env('DB_USERNAME_LEGADO', 'forge'),
            'password' => env('DB_PASSWORD_LEGADO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => env('DB_SCHEMA_PUBLIC_LEGADO', ''),
            'sslmode' => 'prefer',
        ],
        
        # Conexão com o schema corporativo da base de dados legada do SIPIA CT, para fins de execução da migração de dados
        'sipiact_legado_corporativo' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_LEGADO', '127.0.0.1'),
            'port' => env('DB_PORT_LEGADO', '5432'),
            'database' => env('DB_DATABASE_LEGADO', 'forge'),
            'username' => env('DB_USERNAME_LEGADO', 'forge'),
            'password' => env('DB_PASSWORD_LEGADO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => env('DB_SCHEMA_CORPORATIVO_LEGADO', ''),
            'sslmode' => 'prefer',
        ],

        # Conexão com o schema sipiact da base de dados legada do SIPIA CT, para fins de execução da migração de dados
        'sipiact_legado_sipiact' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_LEGADO', '127.0.0.1'),
            'port' => env('DB_PORT_LEGADO', '5432'),
            'database' => env('DB_DATABASE_LEGADO', 'forge'),
            'username' => env('DB_USERNAME_LEGADO', 'forge'),
            'password' => env('DB_PASSWORD_LEGADO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => env('DB_SCHEMA_SIPIACT_LEGADO', ''),
            'sslmode' => 'prefer',
        ],       

        # Conexão com o schema seguranca da base de dados legada do SIPIA CT, para fins de execução da migração de dados
        'sipiact_legado_seguranca' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_LEGADO', '127.0.0.1'),
            'port' => env('DB_PORT_LEGADO', '5432'),
            'database' => env('DB_DATABASE_LEGADO', 'forge'),
            'username' => env('DB_USERNAME_LEGADO', 'forge'),
            'password' => env('DB_PASSWORD_LEGADO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => env('DB_SCHEMA_SEGURANCA_LEGADO', ''),
            'sslmode' => 'prefer',
        ],

        # Conexão com o autenticador para migração de dados no momento da sincronização de usuários e de perfis
        'autenticador' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_AUTENTICADOR', '127.0.0.1'),
            'port' => env('DB_PORT_AUTENTICADOR', '5432'),
            'database' => env('DB_DATABASE_AUTENTICADOR', 'forge'),
            'username' => env('DB_USERNAME_AUTENTICADOR', 'forge'),
            'password' => env('DB_PASSWORD_AUTENTICADOR', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql2' => [
            'driver' => 'pgsql',
            'port' => env('DB_PORT', '5432'),
            'read' => [
                'host' => array_map('trim', explode(',', env('DB_READ_HOSTS', '127.0.0.1')))
            ],
            'write' => [
                'host' => array_map('trim', explode(',', env('DB_WRITE_HOSTS', '127.0.0.1')))
            ],
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'history',
            'sslmode' => 'prefer',
            'pooling' => [
                    'enabled' => env('DB_POOL_ENABLED', true),
                    'max_connections' => env('DB_POOL_MAX_CONECTION', 50),
                    'timeout' => env('DB_POOL_TIMEOUT', 30),
                ],
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
