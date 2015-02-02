<?php

namespace SwaggerUI\Silex\Provider;

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
        // Reference to $this, it's used for closure in anonymous function (PHP 5.3.x)
        $self = &$this;
        $app->get($app['swaggerui.path'], function(Request $request) use ($app) {
            return str_replace(
                array('{{swaggerui-root}}', '{{swagger-docs}}'),
                array($request->getBasePath() . $app['swaggerui.path'], $request->getBasePath() . $app['swaggerui.apiDocPath']),
                file_get_contents(__DIR__ . '/../../../../public/index.html')
            );
        });

        $app->get($app['swaggerui.path'] . '/{resource}', function($resource) use ($app) {
            $file = __DIR__ . '/../../../../public/' . $resource;
            if (is_file($file)) {
                return file_get_contents($file);
            }

            return '';
        });

        $app->get($app['swaggerui.path'] . '/lib/{resource}', function($resource) use ($app, $self) {
            return $self->getFile(
                __DIR__ . '/../../../../public/lib/' . $resource,
                'text/javascript'
            );
        });

        $app->get($app['swaggerui.path'] . '/css/{resource}', function($resource) use ($app, $self) {
            return $self->getFile(
                __DIR__ . '/../../../../public/css/' . $resource,
                'text/css'
            );
        });

        $app->get($app['swaggerui.path'] . '/images/{resource}', function($resource) use ($app, $self) {
            return $self->getFile(
                __DIR__ . '/../../../../public/images/' . $resource,
                'image/png'
            );
        });
    }

    /**
     * Registers the swagger UI service
     *
     * @param Application $app
     */
    public function register(Application $app) {}

    /**
     * Get a public file
     *
     * @param string Path to file
     * @param string Content-type
     * @return Response
     */
    public function getFile($path, $contentType) {
        if (is_file($path)) {
            $response = new Response(file_get_contents($path));
            $response->headers->set('Content-Type', $contentType);
            $response->setCharset('UTF-8');
            return $response;
        }

        return new Response('', 404);
    }
}
