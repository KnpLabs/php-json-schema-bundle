<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle;

use KnpLabs\JsonSchemaBundle\DependencyInjection\JsonSchemaExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class JsonSchemaBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JsonSchemaExtension();
    }
}
