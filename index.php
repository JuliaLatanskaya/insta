<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use Insta\libs\BaseException;
use Insta\system\FrontController;
use Insta\system\RenderController;

$config = require_once('config/main.php');
require_once('vendor/autoload.php');
require_once('libs/BaseException.php');
require_once('libs/MongoDb.php');
require_once('system/FrontController.php');
require_once('system/RenderController.php');

require_once('models/Post.php');

try {
    $frontController = new FrontController(
        $config,
        isset($_GET['route']) ? $_GET['route'] : null,
        isset($_GET['action']) ? $_GET['action'] : null
    );
} catch (BaseException $e) {
    print_r($e->getMessage());
}
