<?php

namespace TwentyTwo\CodeAnalyser\Exception;

/**
 * ComposerJsonNotFound
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ComposerFileNotFound extends \Exception
{
    /**
     * ComposerJsonNotFound constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);
    }
}