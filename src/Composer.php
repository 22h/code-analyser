<?php

namespace TwentyTwo\CodeAnalyser;

use TwentyTwo\CodeAnalyser\Exception\ComposerFileNotFoundException;

/**
 * Composer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Composer
{
    const COMPOSER_PATH = 'composer.json';
    const PSR_4 = 'psr-4';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    static protected $content;

    /**
     * Composer constructor.
     *
     * @param string|null $path
     */
    public function __construct(string $path = null)
    {
        if (is_null($path)) {
            $this->path = self::COMPOSER_PATH;
        }

        $this->loadComposerFile();
    }

    /**
     * loadComposerFile
     *
     * @throws ComposerFileNotFoundException
     * @return void
     */
    protected function loadComposerFile()
    {
        $composerContent = file_get_contents($this->path);
        if ($composerContent === false) {
            throw new ComposerFileNotFoundException('can not find composer.json');
        }

        self::$content = json_decode($composerContent, 1);
    }

    /**
     * getAutoloadPaths
     *
     * @param string $psr
     * @param bool   $dev
     *
     * @return array
     */
    public function getAutoloadPaths(string $psr = self::PSR_4, bool $dev = false): array
    {
        $autoload = ($dev) ? 'autoload-dev' : 'autoload';

        if (array_key_exists($autoload, self::$content)
            && array_key_exists($psr, self::$content[$autoload])
        ) {
            return self::$content[$autoload][$psr];
        }
        return [];
    }

    /**
     * findAutoloadMatch
     *
     * @param string $path
     *
     * @return array|null
     */
    public function findAutoloadMatch(string $path): ?array
    {
        $autoloadPaths = $this->getAutoloadPaths(self::PSR_4, false);
        foreach ($autoloadPaths as $namespace => $prefix) {
            if(strpos($path, $prefix) === 0) {
                return [
                    'namespace' => $namespace,
                    'prefix' => $prefix
                ];
            }
        }

        $autoloadPaths = $this->getAutoloadPaths(self::PSR_4, true);
        foreach ($autoloadPaths as $namespace => $prefix) {
            if(strpos($path, $prefix) === 0) {
                return [
                    'namespace' => $namespace,
                    'prefix' => $prefix
                ];
            }
        }

        return null;
    }

}