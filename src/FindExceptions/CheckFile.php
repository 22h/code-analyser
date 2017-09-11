<?php

namespace TwentyTwo\CodeAnalyser\FindExceptions;

use TwentyTwo\CodeAnalyser\Exception\FileNotFoundException;

/**
 * CheckFile
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
     * @var string
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
     * @throws FileNotFoundException
     * @return void
     */
    protected function loadFile()
    {
        $content = file_get_contents($this->filePath);
        if ($content === false) {
            throw new FileNotFoundException('can not find '.$this->filePath);
        }
        $this->content = $content;
    }

    /**
     * findExceptions
     *
     * @return array
     */
    public function findExceptions(): array
    {
        $hits = [];
        preg_match_all('@throw[[:blank:]]*new[[:blank:]]*\\\\?(\w*Exception)(?=\(|\n\()@im', $this->content, $hits);
        return $hits;
    }

}