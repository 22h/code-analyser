<?php

namespace TwentyTwo\CodeAnalyser\Autoload;

use TwentyTwo\CodeAnalyser\Composer;
use TwentyTwo\CodeAnalyser\Exception\FileNotFound;

/**
 * Check
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CheckFile
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var array
     */
    protected $content;

    /**
     * Check constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        $this->loadFile();
    }

    /**
     * loadFile
     *
     * @throws FileNotFound
     * @return void
     */
    protected function loadFile()
    {
        $content = file($this->filePath, FILE_IGNORE_NEW_LINES);
        if ($content === false) {
            throw new FileNotFound('can not find '.$this->filePath);
        }
        $this->content = $content;
    }

    /**
     * analyseNamespace
     *
     * @return string
     */
    public function analyseNamespace():string
    {
        return $this->findFirstByPattern('@namespace (.*);@i');
    }

    /**
     * reconstructNamespace
     */
    public function reconstructNamespace()
    {
        $namespace = $this->analyseNamespace();

        echo 'FilePath: '.$this->filePath.PHP_EOL;
        echo 'Namespace 1: '.$namespace.PHP_EOL;

        $composer = new Composer();

        $autoload = $composer->findAutoloadMatch($this->filePath);

        $newNamespace = $this->filePath;
        $newNamespace = str_replace($autoload['prefix'], $autoload['namespace'], $newNamespace);
        $newNamespace = str_replace('/', '\\', $newNamespace);
        $newNamespace = str_replace('\\\\', '\\', $newNamespace);

        $newNamespace = substr($newNamespace, 0, strrpos($newNamespace, '\\'));

        echo 'Namespace 2: '.$newNamespace.PHP_EOL;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function findFirstByPattern(string $pattern): string
    {
        $hits = [];
        foreach ($this->content as $line) {
            if (preg_match($pattern, $line, $hits) === 1) {
                break;
            }
        }

        if(array_key_exists(1, $hits)) {
            return $hits[1];
        }
        return '';
    }

}