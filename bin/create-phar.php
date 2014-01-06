<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$srcRoot = __DIR__.'/../src';
$buildDir = __DIR__.'/../build';
$webDir = __DIR__.'/../public';
$appDir = __DIR__.'/../app';

if (file_exists($buildDir.'/git-walrus.phar')) {
    unlink($buildDir.'/git-walrus.phar');
}

function addFile(Phar $phar, SplFileInfo $file, $strip = true)
{
    $path = strtr(str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');
    $path = preg_replace('/^git-walrus\//', '', $path);
    var_dump($path);

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

function getStub()
{
    return <<<'EOF'
#!/usr/bin/env php
<?php

Phar::mapPhar('git-walrus.phar');

require 'phar://git-walrus.phar/bin/git-walrus';

__HALT_COMPILER();
EOF;
}

$phar = new Phar(
    $buildDir.'/git-walrus.phar',
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
    "git-walrus.phar"
);
//$phar->startBuffering();

$finder = Finder::create()
    ->ignoreVCS(true)
    ->files()
    ->name('phar.php')
    ->in(__DIR__.'/../public');
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder = Finder::create()
    ->ignoreVCS(true)
    ->files()
    ->name('*.php')
    ->exclude('Tests')
    ->exclude('phpunit')
    ->exclude('mockery')
    ->in([__DIR__.'/../vendor', __DIR__.'/../src']);
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder = Finder::create()
    ->ignoreVCS(true)
    ->files()
    ->in(__DIR__.'/../public');
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder = Finder::create()
    ->ignoreVCS(true)
    ->files()
    ->in(__DIR__.'/../app');
foreach ($finder as $file) {
    addFile($phar, $file);
}

$phar->addFromString('bin/git-walrus', file_get_contents(__DIR__.'/git-walrus'));

$phar->setStub(getStub());
//$phar->stopBuffering();
