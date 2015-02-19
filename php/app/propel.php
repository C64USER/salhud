<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'salhud' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host=' . getenv('DB_1_PORT_3306_TCP_ADDR') . ':'  . getenv('DB_1_PORT_3306_TCP_PORT') . ';dbname=salhud',
                    'user'       => 'root',
                    'password'   => 'root',
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'salhud',
            'connections' => ['salhud']
        ],
        'generator' => [
            'defaultConnection' => 'salhud',
            'connections' => ['salhud']
        ]
    ]
];