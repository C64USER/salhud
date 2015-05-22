<?php
// setup the autoloading
require_once '/php/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'templates.path' => './templates'
));

$memcache = new Memcache();
$memcache->addServer("memcached", 11211);

$db = new PDO('mysql:host=db;dbname=salhud', 'root', 'root');
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$app->get('/', function () use ($app) {
    $app->render('index.html');
});

$app->get('/map/:disease_id/:year', function ($disease_id, $year) use ($db, $app, $memcache) {
    $data = $memcache->get('/map/'+$disease_id+'/' + $year);
    if ($data == null) {
        $statement = $db->prepare("SELECT t.name AS pueblo, t.mapping AS fips, r.name AS region, (SELECT COUNT(*) FROM disease_case c WHERE c.town_id = t.id AND c.year = $year AND c.disease_id = $disease_id) AS disease, (SELECT CASE WHEN COUNT(*)/p.population IS NULL THEN 0 ELSE COUNT(*)/p.population END FROM disease_case c, town t2, population p WHERE c.town_id = t.id AND c.year = $year AND c.town_id = t2.id AND p.town_id = t2.id) AS percent FROM town t, region r WHERE r.id = t.region_id;");
        $statement->execute();
        $data = json_encode($results = $statement->fetchAll(PDO::FETCH_ASSOC));    
        $memcache->set('/map/'+$disease_id+'/' + $year, $data, false, 86400) or die ("Failed to save data at the server");
    }
    
    echo $data;
});

$app->get('/curve/:disease_id', function ($disease_id) use ($db, $app, $memcache) {
    $data = $memcache->get('/curve/'+$disease_id);
    if ($data == null) {
        $statement = $db->prepare("SELECT SUM(`population`) as population, p.year FROM population p GROUP BY p.year ORDER BY p.year;");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement2 = $db->prepare("SELECT COUNT(*) as count, c.year FROM disease_case c GROUP BY c.year ORDER by c.year;");
        $statement2->execute();
        $results2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
        
        $idx = 0;
        $data = array();
        foreach($results2 as $row) {
            $tmp = array(
                "year" => $row['year'],
                "percent" => ($row['count'] / $results[$idx]['population'])
            );
            array_push($data, $tmp);
            $idx = $idx + 1;
        }
        $data = json_encode($data);
        $memcache->set('/curve/'+$disease_id, $data, false, 86400) or die ("Failed to save data at the server");
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