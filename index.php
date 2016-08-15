<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

use Insta\system\BaseException;
use Insta\system\FrontController;
use Insta\system\RenderController;

$config = require_once('config/main.php');
require_once('autoload.php');

$logger = new Katzgrau\KLogger\Logger($config['basePath'].'/logs');

register_shutdown_function("shutdown_handler", $logger);

try {
    $frontController = new FrontController(
        $config,
        $logger,
        isset($_GET['route']) ? $_GET['route'] : null,
        isset($_GET['action']) ? $_GET['action'] : null
    );
} catch (BaseException $e) {
    $logger->error($e->getMessage());
    header("Location: /will-be-back-soon.html");
}
