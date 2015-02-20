<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('salhud', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
  'dsn' => 'mysql:host=' . getenv('DB_1_PORT_3306_TCP_ADDR') . ':'  . getenv('DB_1_PORT_3306_TCP_PORT') . ';dbname=salhud',
  'user' => 'root',
  'password' => 'root',
  'attributes' =>
  array (
    'ATTR_EMULATE_PREPARES' => false,
  ),
));
$manager->setName('salhud');
$serviceContainer->setConnectionManager('salhud', $manager);
$serviceContainer->setDefaultDatasource('salhud');