<?php

namespace TwentyTwo\CodeAnalyser\Autoload;

use TwentyTwo\CodeAnalyser\Composer;

/**
 * Check
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Check
{

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var Finder
     */
    protected $finder;

    public function checkAll()
    {
        $this->composer = new Composer();

        $this->finder = new Finder();

        $this->addAutoloadPaths();

        foreach ($this->finder->foundedFiles() as $file) {
            $checkFile = new CheckFile($file);
            echo PHP_EOL;
            $checkFile->reconstructNamespace();
        }

    }

    /**
     * addAutoloadPaths
     */
    protected function addAutoloadPaths() {
        $autoloadPaths = $this->composer->getAutoloadPaths(Composer::PSR_4, false);
        foreach ($autoloadPaths as $prefix) {
            $this->finder->addAutoloadPath($prefix);
        }

        $autoloadPaths = $this->composer->getAutoloadPaths(Composer::PSR_4, true);
        foreach ($autoloadPaths as $prefix) {
            $this->finder->addAutoloadPath($prefix);
        }
    }


}