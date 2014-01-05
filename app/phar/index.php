<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */

require_once "phar://git-walrus.phar/libraries/git-walrus/vendor/autoload.php";

define('DEBUG', false);
define('SERIALIZER_METADATA_DIR', 'phar://git-walrus.phar/libraries/git-walrus/app/serializer');
define('TWIG_VIEWS_DIR', 'phar://git-walrus.phar/libraries/git-walrus/app/views');
/*$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}*/

$app = require_once "phar://git-walrus.phar/app.php";

$app->run();

exec('php -S localhost:8000 -t phar://git-walrus.phar/public phar://git-walrus.phar/public/index.php');