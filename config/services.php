<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Knp\JsonSchema\Collection;
use Knp\JsonSchema\JsonSchemaInterface;
use Knp\JsonSchemaBundle\RequestHandler;
use Knp\JsonSchemaBundle\Validator\SwaggestValidator;

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
    $services->alias('Knp\JsonSchema\Validator', SwaggestValidator::class);

    $services->set(Collection::class)
        ->arg('$schemas', tagged_iterator('knp.json_schema'))
    ;

    $services->set(RequestHandler::class);
};