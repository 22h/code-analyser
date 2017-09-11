<?php

namespace TwentyTwo\CodeAnalyser;

/**
 * Finder
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Finder
{

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * Finder constructor.
     */
    public function __construct()
    {
        $this->finder = new \Symfony\Component\Finder\Finder();
        $this->finder->name('*.php');
    }

    /**
     * addAutoloadPath
     *
     * @param string $path
     */
    public function addAutoloadPath(string $path)
    {
        $this->finder->in($path);
    }

    /**
     * countFiles
     *
     * @return int
     */
    public function countFiles(): int
    {
        return $this->finder->count();
    }

    /**
     * foundedFiles
     *
     * @return \IteratorAggregate
     */
    public function foundedFiles(): \IteratorAggregate
    {
        return $this->finder;
    }

}