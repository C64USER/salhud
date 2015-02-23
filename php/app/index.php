<?php
// setup the autoloading
require '/php/vendor/autoload.php';

// setup Propel
require_once 'generated-conf/config.php';

$app = new \Slim\Slim();
$app->get('/', function () {
    $region = new Region();
    $region->setName('San Juan');
    $region->setPopulation(123);
    $region->save();
    $regions = RegionQuery::create()->find();
    // $regions contains a collection of Region objects
    // one object for every row of the author table
    foreach($regions as $r) {
      echo $r->getName();
      echo $r->getPopulation();
    }
});
$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});
$app->get('/books/:id', function ($id) {
    echo $id;
});
$app->run();

 ?>