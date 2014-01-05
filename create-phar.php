<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$srcRoot = __DIR__.'/src';
$buildDir = __DIR__.'/build';
$webDir = __DIR__.'/public';
$appDir = __DIR__.'/app';

if (file_exists($buildDir.'/git-walrus.phar')) {
    unlink($buildDir.'/git-walrus.phar');
}

$phar = new Phar(
    $buildDir.'/git-walrus.phar',
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
    "git-walrus.phar"
);
$phar->startBuffering();

$phar->addFromString('index.php', file_get_contents($appDir.'/phar/index.php'));
$phar->addFromString('app.php', file_get_contents($appDir.'/app.php'));
$phar->setStub($phar->createDefaultStub('index.php'));

function addFile(Phar $phar, SplFileInfo $file)
{
    $path = strtr(str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');
    $content = file_get_contents($file);
    $phar->addFromString($path, $content);
}

$finder = new Finder();
$finder->files()
    ->ignoreVCS(true)
    ->name('*.twig')
    ->in($appDir.'/views')
;
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder = new Finder();
$finder->files()
    ->ignoreVCS(true)
    ->name('*.yml')
    ->in($appDir.'/serializer')
;
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder->files()
    ->ignoreVCS(true)
    ->name('*.php')
    ->exclude('Tests')
    ->exclude('phpunit')
    ->in(__DIR__.'/vendor')
;
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder->files()
    ->ignoreVCS(true)
    ->name('*.php')
    ->in($srcRoot)
;
foreach ($finder as $file) {
    addFile($phar, $file);
}

$finder->files()
    ->ignoreVCS(true)
    ->name('index.php')
    ->name('git-walrus.js')
    ->name('screen.css')
    ->name('ie.css')
    ->name('print.css')
    ->in($webDir)
;
foreach ($finder as $file) {
    addFile($phar, $file);
}

$phar->stopBuffering();
