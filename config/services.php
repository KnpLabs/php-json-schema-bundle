<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KnpLabs\JsonSchema\Collection;
use KnpLabs\JsonSchema\JsonSchemaInterface;
use KnpLabs\JsonSchemaBundle\RequestHandler;
use KnpLabs\JsonSchemaBundle\Validator\SwaggestValidator;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
    ;

    $services->instanceof(JsonSchemaInterface::class)
        ->tag('knp.json_schema')
    ;

    $services->set(SwaggestValidator::class);
    $services->alias('KnpLabs\JsonSchema\Validator', SwaggestValidator::class);

    $services->set(Collection::class)
        ->arg('$schemas', tagged_iterator('knp.json_schema'))
    ;

    $services->set(RequestHandler::class);
};