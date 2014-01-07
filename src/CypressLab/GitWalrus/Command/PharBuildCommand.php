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
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    protected function configure()
    {
        $this
            ->setName('phar:build')
            ->addArgument('name', InputArgument::OPTIONAL, 'archive name', 'git-walrus')
            ->setDescription('Build the phar archive');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
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

        $phar->startBuffering();

        $output->writeln('<info>Adding vendor/src files...</info>');
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
                ->name('/index.php|\.js$|\.css$|\.html$/')
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
        $phar->setStub($this->getStub());
        $phar->stopBuffering();
        rename($buildDir.'/'.$pharName, $buildDir.'/'. $name);
    }

    private function addFinder(Finder $finder, \Phar $phar)
    {
        /** @var ProgressHelper $progress */
        $progress = $this->getHelperSet()->get('progress');
        $progress->setBarCharacter('<comment>=</comment>');
        $progress->setEmptyBarCharacter(' ');
        $progress->setBarWidth(50);
        //$progress->start($this->output, $finder->count());
        foreach ($finder as $file) {
            $this->addFile($phar, $file);
            //$progress->advance();
        }
        //$progress->finish();
    }

    private function getStub()
    {
        return <<<'EOF'
#!/usr/bin/env php
<?php

Phar::mapPhar("git-walrus.phar");

require "phar://git-walrus.phar/bin/git-walrus";

__HALT_COMPILER();
EOF;
    }

    private function addFile(\Phar $phar, \SplFileInfo $file, $strip = true)
    {
        $path = strtr(str_replace(dirname(dirname(dirname(dirname(__DIR__)))).DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');
        var_dump($path);

        $content = file_get_contents($file);
        if ($strip) {
            //$content = $this->stripWhitespace($content);
        } elseif ('LICENSE' === basename($file)) {
            $content = "\n".$content."\n";
        }

        $phar->addFile($file, $path);
    }
}
