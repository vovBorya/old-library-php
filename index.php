<?php

require_once("./vendor/autoload.php");

use App\Controllers\Controller;
use App\DB\DBConnector;
use App\Gateways\ClientGateway;
use App\Gateways\FineGateway;
use App\Gateways\GenreGateway;

$serverName = "localhost";
$username = "root";
$password = "63od74en";
$dbname = "old_library";

$db = new DBConnector($serverName, $username, $password, $dbname);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /person
// everything else results in a 404 Not Found
if ($uri[1] !== 'api') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the resource id is, of course, optional and must be a number:
$resourceId = null;
if (isset($uri[3])) {
    $resourceId = (int) $uri[3];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new Controller($requestMethod, $resourceId);

switch ($uri[2]) {
    case "clients": {
        $controller->setGateway(new ClientGateway($db->getConnection()));
        break;
    }
    case "genres": {
        $controller->setGateway(new GenreGateway($db->getConnection()));
        break;
    }
    case "fines": {
        $controller->setGateway(new FineGateway($db->getConnection()));
        break;
    }
}

$controller->processRequest();
