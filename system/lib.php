<?php
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
