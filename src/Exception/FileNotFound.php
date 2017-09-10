<?php

namespace TwentyTwo\CodeAnalyser\Exception;

/**
 * FileNotFound
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class FileNotFound extends \Exception
{
    /**
     * FileNotFound constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);
    }
}