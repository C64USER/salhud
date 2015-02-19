<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'salhud' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host=localhost;dbname=salhud',
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