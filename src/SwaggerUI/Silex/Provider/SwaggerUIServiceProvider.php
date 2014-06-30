<?php

namespace SwaggerUI\Silex\Provider;

use Twig_Autoloader;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * The SwaggerUIServiceProvider adds views for swagger UI to a silex app, making
 * it possible to view swagger docs.
 */
class SwaggerUIServiceProvider implements ServiceProviderInterface
{
    /**
     * Add routes to the swagger UI documentation browser
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        if ($app["logger"]) {
            $logger = Logger::getInstance();
            $originalLog = $logger->log;
            $logger->log = function ($entry, $type) use ($app, $originalLog) {
                $app["logger"]->notice($entry);
                $originalLog($entry, $type);
            };
        }

        $twig = $app['twig'];

        if (!$twig) {
            $twig = $this->getTwig($app);
        }

        $app->get($app["swaggerui.path"], function() {
            $twig->loadTemplate('index.html')->render(array('the' => 'variables', 'go' => 'here'));
        });
    }

    /**
     * Get twig instance
     */
    protected function getTwig(Application $app) {
        Twig_Autoloader::register();

        $loader = new Twig_Loader_Filesystem('../../../../views');
        $twig = new Twig_Environment($loader, array(
            'cache' => '../../../../cache',
        ));

        $twig = new Twig_Environment($loader);

        return $twig;
    }
}
