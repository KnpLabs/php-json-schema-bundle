<?php

declare(strict_types=1);

namespace Knp\JsonSchemaBundle\DependencyInjection;

use Knp\JsonSchema\JsonSchemaInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class JsonSchemaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.xml');

        $container
            ->registerForAutoconfiguration(JsonSchemaInterface::class)
            ->addTag('knp.json_schema')
        ;
    }
}
