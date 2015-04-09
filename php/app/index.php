<?php
// setup the autoloading
require_once '/php/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'templates.path' => './templates'
));

$db = new PDO('mysql:host=db;dbname=salhud', 'root', 'root');
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$app->get('/', function () use ($app) {
    $app->render('index.html');
});

$app->get('/hi', function () {
    echo "hi";
});

$app->get('/map/:disease_id/:year', function ($disease_id, $year) use ($db, $app) {
    $memcache = new Memcache();
    $memcache->addServer("memcached", 11211);
    $data = $memcache->get('/map/'+$disease_id+'/' + $year);
    if ($data == null) {
        $statement = $db->prepare("SELECT t.name AS pueblo, t.mapping AS fips, r.name AS region, (SELECT COUNT(*) FROM disease_case c WHERE c.town_id = t.id AND c.year = $year AND c.disease_id = $disease_id) AS disease, (SELECT CASE WHEN COUNT(*)/p.population IS NULL THEN 0 ELSE COUNT(*)/p.population END FROM disease_case c, town t2, population p WHERE c.town_id = t.id AND c.year = $year AND c.town_id = t2.id AND p.town_id = t2.id) AS percent FROM town t, region r WHERE r.id = t.region_id;");
        $statement->execute();
        $data = json_encode($results = $statement->fetchAll(PDO::FETCH_ASSOC));    
        $memcache->set('/map/'+$disease_id+'/' + $year, $data, false, 86400) or die ("Failed to save data at the server");
    }
    
    echo $data;
});

$app->get('/diseases', function () use ($db, $app) {
    $statement = $db->prepare("SELECT * FROM disease;");
    $statement->execute();
    echo json_encode($results = $statement->fetchAll(PDO::FETCH_ASSOC));
});

$app->run();

 ?>