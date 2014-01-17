<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */


namespace CypressLab\GitWalrus\Controller;


use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\CssRewriteFilter;
use CypressLab\GitWalrus\Application;
use Symfony\Component\HttpFoundation\Response;

class Assets
{
    public function css()
    {
        $r = new Response();
        $r->headers->set('content-type', 'text/css');
        $baseFolder = __DIR__.'/../../../../public';

        $css = new AssetCollection([
            new FileAsset($baseFolder.'/bower/bootstrap/dist/css/bootstrap.min.css'),
            new FileAsset($baseFolder.'/css/github.min.css'),
            new FileAsset($baseFolder.'/compass/stylesheets/screen.css'),
            new FileAsset($baseFolder.'/compass/stylesheets/print.css')
        ]);
        $r->setContent($css->dump());

        return $r;
    }

    public function js()
    {
        $r = new Response();
        $r->headers->set('content-type', 'text/javascript');
        $baseFolder = __DIR__.'/../../../../public';

        $js = new AssetCollection([
            new FileAsset($baseFolder.'/bower/jquery/jquery.min.js'),
            new FileAsset($baseFolder.'/bower/underscore/underscore-min.js'),
            new FileAsset($baseFolder.'/bower/bootstrap/js/affix.js'),
            new FileAsset($baseFolder.'/bower/greensock/src/minified/TweenMax.min.js'),
            new FileAsset($baseFolder.'/bower/greensock/src/minified/plugins/CSSPlugin.min.js'),
            new FileAsset($baseFolder.'/bower/greensock/src/minified/utils/Draggable.min.js'),
            new FileAsset($baseFolder.'/bower/spin.js/spin.js'),
            new FileAsset($baseFolder.'/bower/spin.js/jquery.spin.js'),
            new FileAsset($baseFolder.'/bower/angular/angular.min.js'),
            new FileAsset($baseFolder.'/bower/angular-resource/angular-resource.min.js'),
            new FileAsset($baseFolder.'/bower/angular-route/angular-route.min.js'),
            new FileAsset($baseFolder.'/bower/angular-animate/angular-animate.min.js'),
            new FileAsset($baseFolder.'/bower/angular-underscore/angular-underscore.js'),
            new FileAsset($baseFolder.'/bower/angular-highlightjs/angular-highlightjs.min.js'),
            new FileAsset($baseFolder.'/libs/highlight.pack.js'),
            new FileAsset($baseFolder.'/js/git-walrus.js'),
        ]);
        $r->setContent($js->dump());

        return $r;
    }

    public function partial($name)
    {
        $baseFolder = __DIR__.'/../../../../public/partials/';
        return new Response(file_get_contents($baseFolder.$name));
    }
}
