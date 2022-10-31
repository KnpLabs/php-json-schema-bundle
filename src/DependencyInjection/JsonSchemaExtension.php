<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle\DependencyInjection;

use KnpLabs\JsonSchema\JsonSchemaInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class JsonSchemaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.php');

        $container
            ->registerForAutoconfiguration(JsonSchemaInterface::class)
            ->addTag('knp.json_schema')
        ;
    }

    public function getAlias(): string
    {
        return 'knp_json_schema';
    }
}
