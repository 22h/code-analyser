<?php

namespace TwentyTwo\CodeAnalyserA\Tests\Test;

use TwentyTwo\CodeAnalyser\Exception\ComposerFileNotFoundException;

/**
 * Wrang
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Wrang
{

    public function testException()
    {
        throw new \Exception();
    }

    public function anotherTest()
    {
        throw new ComposerFileNotFoundException();
    }

}