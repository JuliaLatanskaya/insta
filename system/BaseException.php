<?php
namespace Insta\system;

/**
 * Wrapper for basic \Exception
 */
class BaseException extends \Exception
{
    function __construct($message = '', $code = 0)
    {
        parent::__construct($message, 0);
    }
}
