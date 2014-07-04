# Silex SwaggerUI service provider
A silex service provider that integrates the Swagger UI documentation browser into silex. Does not depend on anything besides Silex.

## Installation
Add `"kbrabrand/silex-swagger-ui": "dev-master"` to you composer.json file and run `composer install` inside the root of your project.

In addition to this you will need to register the service in your Silex app like this;

```php
// Set up swagger ui service for viewing the swagger docs
$app->register(new SwaggerUI\Silex\Provider\SwaggerUIServiceProvider(), array(
    'swaggerui.path'       => '/v1/swagger',
    'swaggerui.apiDocPath' => '/v1/docs'
));
```

This will result in the SwaggerUI interface being available at */v1/swagger* under you Silex application root. The `swaggerui.apiDovPath` config option specifies the URL/path to the swagger doc files.

## Known issues
The Swagger UI is pretty slow right now due to the fact that static resources are served _through_ a Silex controller and no cache is in place (yet). I'll try to fix this soon.

## License
Copyright (c) 2014, Kristoffer Brabrand kristoffer@brabrand.no

Licensed under the MIT License
