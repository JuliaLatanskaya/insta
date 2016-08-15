<?php
spl_autoload_register(
    function($class)
    {
        static $classes = null;
        
        if ($classes === null) {
            $classes = array(
                'insta\\system\\baseexception' => '/BaseException.php',
                'insta\\system\\fileuploader' => '/FileUploader.php',
                'insta\\system\\frontcontroller' => '/FrontController.php',
                'insta\\system\\mongodb' => '/MongoDb.php',
                'insta\\system\\rendercontroller' => '/RenderController.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);

/**
 * Handles PHP errors and logs them into basic logs/
 * @param Katzgrau\KLogger\Logger $logger Logger instance. For more info visit @link https://github.com/katzgrau/KLogger
 */
function shutdown_handler($logger)
{
    $error = error_get_last();
    if (!empty($error)) {
        $logger->error($error['message'] . ' in ' . $error['file'] . ' line: ' . $error['line']); 
        if (in_array($error['type'], array(1, 64, 256))) {
            header("Location: /will-be-back-soon.html");
            exit;
        }
    }
}
