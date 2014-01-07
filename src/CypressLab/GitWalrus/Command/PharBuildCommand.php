<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */

namespace CypressLab\GitWalrus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PharBuildCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('phar:build')
            ->addArgument('name', InputArgument::OPTIONAL, 'archive name', 'git-walrus')
            ->setDescription('Build the phar archive');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $pharName = $name.'.phar';
        $baseDir = __DIR__.'/../../../..';
        $srcDir = $baseDir.'/src';
        $buildDir = $baseDir.'/build';
        $publicDir = $baseDir.'/public';
        $vendorDir = $baseDir.'/vendor';
        $appDir = $baseDir.'/app';
        $binDir = $baseDir.'/bin';

        if (file_exists($buildDir.'/'.$name)) {
            unlink($buildDir.'/'.$name);
        }

        $phar = new \Phar(
            $buildDir.'/'.$pharName,
            \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME,
            $pharName
        );

        $output->writeln('<info>Adding vendor files...</info>');
        $this->addFinder(
            Finder::create()
                ->ignoreVCS(true)
                ->files()
                ->name('*.php')
                ->exclude('Tests')
                ->exclude('phpunit')
                ->exclude('mockery')
                ->in([$vendorDir, $srcDir]),
            $phar,
            $output
        );

        $output->writeln('<info>Adding public files...</info>');
        $this->addFinder(
            Finder::create()
                ->ignoreVCS(true)
                ->files()
                ->name('/\.js$|\.css$/')
                ->in($publicDir),
            $phar,
            $output
        );

        $output->writeln('<info>Adding app files...</info>');
        $this->addFinder(
            Finder::create()
                ->ignoreVCS(true)
                ->files()
                ->in($appDir),
            $phar,
            $output
        );

        $phar->addFromString('bin/git-walrus', file_get_contents($binDir.'/git-walrus'));
        $phar->setStub($this->getStub($pharName));
        rename($buildDir.'/'.$pharName, $buildDir.'/'. $name);
    }

    private function addFinder(Finder $finder, \Phar $phar, OutputInterface $output)
    {
        $phar->startBuffering();
        /** @var ProgressHelper $progress */
        $progress = $this->getHelperSet()->get('progress');
        $progress->setBarCharacter('<comment>=</comment>');
        $progress->setEmptyBarCharacter(' ');
        $progress->setBarWidth(50);
        $progress->start($output, $finder->count());
        foreach ($finder as $file) {
            $this->addFile($phar, $file, $output);
            $progress->advance();
        }
        $phar->stopBuffering();
        $progress->finish();
    }

    private function getStub($name)
    {
        return sprintf(
            '#!/usr/bin/env php
            <?php

            Phar::mapPhar("%s");

            require "phar://%s/bin/git-walrus";

            __HALT_COMPILER();
            ',
            $name,
            $name
        );
    }

    private function addFile(\Phar $phar, \SplFileInfo $file, OutputInterface $output, $strip = true)
    {
        $path = strtr(str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');
        $path = preg_replace('/^git-walrus\//', '', $path);
        //$output->writeln($path);

        $content = file_get_contents($file);
        if ($strip) {
            //$content = $this->stripWhitespace($content);
        } elseif ('LICENSE' === basename($file)) {
            $content = "\n".$content."\n";
        }

        if ($path === 'src/Composer/Composer.php') {
            $content = str_replace('@package_version@', $this->version, $content);
            $content = str_replace('@release_date@', $this->versionDate, $content);
        }

        $phar->addFromString($path, $content);
    }
}
