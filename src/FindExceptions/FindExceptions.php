<?php

namespace TwentyTwo\CodeAnalyser\FindExceptions;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TwentyTwo\CodeAnalyser\Composer;
use TwentyTwo\CodeAnalyser\Finder;

/**
 * Autoload
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class FindExceptions extends Command
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('code-analyser:exceptions')
            ->setDescription('Find all exception calls in project')
            ->addOption(
                'directory',
                'd',
                InputArgument::OPTIONAL,
                'tests a folder recursive',
                null
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->composer = new Composer();
        $this->finder = new Finder();

        if (!is_null($input->getOption('directory'))) {
            $this->finder->addAutoloadPath($input->getOption('directory'));
        } else {
            $this->addAutoloadPaths();
        }

        $this->searchFiles();
        $this->checkFiles();
    }

    protected function addAutoloadPaths()
    {
        $this->io->section('Lookup autoload paths');

        $ioTable = [];

        $autoloadPaths = $this->composer->getAutoloadPaths(Composer::PSR_4, false);
        foreach ($autoloadPaths as $namespace => $prefix) {
            $this->finder->addAutoloadPath($prefix);

            $ioTable[] = ['prod', $namespace, $prefix];
        }

        $autoloadPaths = $this->composer->getAutoloadPaths(Composer::PSR_4, true);
        foreach ($autoloadPaths as $namespace => $prefix) {
            $this->finder->addAutoloadPath($prefix);
            $ioTable[] = ['dev', $namespace, $prefix];
        }

        $this->io->table(array('env', 'namespace', 'folder'), $ioTable);
    }

    protected function searchFiles()
    {
        $this->io->section('Search matching files');
        $this->io->text('Find '.$this->finder->countFiles().' matching files in directories');
    }

    protected function checkFiles()
    {
        $this->io->section('Search exceptions');

        $this->io->progressStart($this->finder->countFiles());

        $exceptions = [];
        $i = 0;
        foreach ($this->finder->foundedFiles() as $file) {
            $checkFile = new CheckFile($file);

            $foundExceptions = $checkFile->findExceptions();

            if (array_key_exists(1, $foundExceptions)) {
                foreach ($foundExceptions[1] as $exception) {
                    if (array_key_exists($exception, $exceptions)) {
                        $exceptions[$exception]['count']++;
                        $exceptions[$exception]['files'][] = (string)$file;
                    } else {
                        $exceptions[$exception] = [];
                        $exceptions[$exception]['count'] = 1;
                        $exceptions[$exception]['files'] = [];
                        $exceptions[$exception]['files'][] = (string)$file;
                    }
                    $i++;
                }
            }

            $this->io->progressAdvance();
        }
        $this->io->progressFinish();
        $this->io->section('List founded exceptions');

        uasort(
            $exceptions,
            function ($a, $b) {
                return ($a['count'] > $b['count']) ? -1 : 1;
            }
        );

        $ioTable = [];
        foreach ($exceptions as $exceptionName => $exception) {

            foreach ($exception['files'] as $file) {
                $ioTable[] = [
                    $exceptionName,
                    $file,
                ];
            }
        }
        $this->io->table(array('exception', 'files'), $ioTable);

        $this->io->section('List grouped exceptions');

        $ioTable = [];
        foreach ($exceptions as $exceptionName => $exception) {
            $ioTable[] = [
                $exceptionName,
                $exception['count'],
            ];
        }

        $this->io->table(array('exception', 'count'), $ioTable);

        $this->io->success('find '.$i.' exceptions');
    }

}