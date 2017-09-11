<?php

namespace TwentyTwo\CodeAnalyser\Autoload;

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
class Autoload extends Command
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
            ->setName('code-analyser:namespaces')
            ->setDescription('Check all namespaces in project')
            ->setHelp('Check all namespaces in folders who are defined in composer.json autoload')
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
        $this->io->section('Search incorrect namespaces');

        $this->io->progressStart($this->finder->countFiles());

        $incorrectNamespaces = [];

        foreach ($this->finder->foundedFiles() as $file) {
            $checkFile = new CheckFile($file);

            $reconstructNamespace = $checkFile->reconstructNamespace();
            if ($reconstructNamespace['current_namespace'] !== $reconstructNamespace['new_namespace']) {
                $incorrectNamespaces[] = $reconstructNamespace;
            }
            $this->io->progressAdvance();
        }
        $this->io->progressFinish();
        $this->io->section('List incorrect namespaces');

        foreach ($incorrectNamespaces as $incorrectNamespace) {
            $this->io->table(
                [],
                [
                    ['File', $incorrectNamespace['file_path']],
                    ['Current Namespace', $incorrectNamespace['current_namespace']],
                    ['New Namespace', $incorrectNamespace['new_namespace']],
                ]
            );
        }
    }
}